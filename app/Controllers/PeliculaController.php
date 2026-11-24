<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\PeliculaModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;

class PeliculaController extends BaseController
{
    use ResponseTrait;

    /**
     * Las funciones se guardan con fecha/hora "de pared" del cine, no en UTC
     * (App::$appTimezone es UTC). Se usa esta zona para saber qué es "hoy".
     * Cambiala si el cine está en otro huso horario.
     */
    private const ZONA_CINE = 'America/Argentina/Buenos_Aires';

    /** Carpeta (dentro de /public) donde se guardan los pósters subidos. */
    private const CARPETA_IMAGENES = 'uploads/peliculas';

    protected $peliculaModel;
    protected $categoriaModel;

    public function __construct()
    {
        $this->peliculaModel  = new PeliculaModel();
        $this->categoriaModel = new CategoriaModel();
    }

    // ================= VISTAS WEB: CLIENTES =================

    // Catálogo de películas (público). Permite filtrar por categoría: ?categoria=ID
    public function catalogo()
    {
        $idCategoria = (int) $this->request->getGet('categoria');

        $consulta = $this->peliculaModel->conCategoria();
        if ($idCategoria > 0) {
            $consulta->where('peliculas.id_categoria', $idCategoria);
        }

        return view('peliculas/catalogo', [
            'peliculas'       => $consulta->orderBy('peliculas.titulo')->findAll(),
            'categorias'      => $this->categoriaModel->orderBy('nombre')->findAll(),
            'categoriaActiva' => $idCategoria,
        ]);
    }

    // Detalle de una película + las funciones que tiene programadas (público).
    public function detalle($id = null)
    {
        $pelicula = $this->peliculaModel
            ->conCategoria()
            ->where('peliculas.id_pelicula', (int) $id)
            ->first();

        if (! $pelicula) {
            throw PageNotFoundException::forPageNotFound('La película solicitada no existe.');
        }

        $hoy = (new \DateTime('now', new \DateTimeZone(self::ZONA_CINE)))->format('Y-m-d');

        $funciones = db_connect()->table('funciones')
            ->select('funciones.id_funcion, funciones.fecha, funciones.hora_ingreso, funciones.hora_salida, salas.nombre AS sala')
            ->join('salas', 'salas.id_sala = funciones.id_sala', 'left')
            ->where('funciones.id_pelicula', (int) $pelicula['id_pelicula'])
            ->where('funciones.fecha >=', $hoy)
            ->orderBy('funciones.fecha', 'ASC')
            ->orderBy('funciones.hora_ingreso', 'ASC')
            ->get()->getResultArray();

        // Agrupadas por día para mostrarlas ordenadas en la vista.
        $funcionesPorFecha = [];
        foreach ($funciones as $funcion) {
            $funcionesPorFecha[$funcion['fecha']][] = $funcion;
        }

        return view('peliculas/detalle', [
            'pelicula'          => $pelicula,
            'funcionesPorFecha' => $funcionesPorFecha,
        ]);
    }

    // ================= VISTAS WEB: ADMINISTRADOR =================

    // Listado para administrar (editar / eliminar).
    public function administrar()
    {
        return view('peliculas/administrar', [
            'peliculas' => $this->peliculaModel->conCategoria()->orderBy('peliculas.titulo')->findAll(),
        ]);
    }

    public function nueva()
    {
        return view('peliculas/form', [
            'pelicula'   => null,
            'categorias' => $this->categoriaModel->orderBy('nombre')->findAll(),
        ]);
    }

    public function guardar()
    {
        return $this->persistir(null);
    }

    public function editar($id = null)
    {
        $pelicula = $this->peliculaModel->find((int) $id);

        if (! $pelicula) {
            return redirect()->to(site_url('admin/peliculas'))
                             ->with('error', 'La película solicitada no existe.');
        }

        return view('peliculas/form', [
            'pelicula'   => $pelicula,
            'categorias' => $this->categoriaModel->orderBy('nombre')->findAll(),
        ]);
    }

    public function actualizar($id = null)
    {
        return $this->persistir((int) $id);
    }

    public function eliminar($id = null)
    {
        $pelicula = $this->peliculaModel->find((int) $id);

        if (! $pelicula) {
            return redirect()->to(site_url('admin/peliculas'))
                             ->with('error', 'La película no existe.');
        }

        // Las funciones de esta película se borran en cascada (clave foránea).
        $this->peliculaModel->delete((int) $id);
        $this->borrarImagen($pelicula['imagen'] ?? null);

        return redirect()->to(site_url('admin/peliculas'))
                         ->with('success', 'Película eliminada correctamente.');
    }

    /**
     * Alta ($id = null) o edición de una película, con su imagen y categoría.
     *
     * Todo ocurre en una transacción: si algo falla, no queda ni la categoría
     * nueva ni la película a medias, y se borra el archivo recién subido.
     */
    private function persistir(?int $id)
    {
        $existente = null;
        if ($id !== null) {
            $existente = $this->peliculaModel->find($id);
            if (! $existente) {
                return redirect()->to(site_url('admin/peliculas'))
                                 ->with('error', 'La película no existe.');
            }
        }

        $texto = static fn (?string $v): ?string => ($v = trim((string) $v)) === '' ? null : $v;

        $datos = [
            'titulo'        => trim((string) $this->request->getPost('titulo')),
            'sinopsis'      => $texto($this->request->getPost('sinopsis')),
            'duracion'      => trim((string) $this->request->getPost('duracion')),
            'clasificacion' => trim((string) $this->request->getPost('clasificacion')),
            'trailer_url'   => $texto($this->request->getPost('trailer_url')),
        ];

        $errores    = [];
        $rutaNueva  = null;
        $idCategoria = (int) $this->request->getPost('id_categoria') ?: null;
        $nuevaCategoria = trim((string) $this->request->getPost('nueva_categoria'));

        $db = db_connect();
        $db->transBegin();

        try {
            // 1) Categoría: una existente o una nueva escrita por el administrador.
            if ($nuevaCategoria !== '') {
                $categoria = $this->categoriaModel->where('nombre', $nuevaCategoria)->first();

                if ($categoria) {
                    $idCategoria = (int) $categoria['id_categoria'];
                } elseif ($this->categoriaModel->insert(['nombre' => $nuevaCategoria])) {
                    $idCategoria = (int) $this->categoriaModel->getInsertID();
                } else {
                    $errores = array_merge($errores, array_values($this->categoriaModel->errors()));
                }
            }
            $datos['id_categoria'] = $idCategoria;

            // 2) Imagen (opcional): se valida y se guarda en /public/uploads/peliculas.
            $archivo = $this->request->getFile('imagen');
            if ($archivo !== null && $archivo->getError() !== UPLOAD_ERR_NO_FILE) {
                $valida = $this->validate([
                    'imagen' => 'uploaded[imagen]|is_image[imagen]|mime_in[imagen,image/jpeg,image/png,image/webp]|max_size[imagen,2048]',
                ], [
                    'imagen' => [
                        'uploaded' => 'No se pudo subir la imagen. Probá con otro archivo.',
                        'is_image' => 'El archivo elegido no es una imagen.',
                        'mime_in'  => 'La imagen debe ser JPG, PNG o WEBP.',
                        'max_size' => 'La imagen no puede pesar más de 2 MB.',
                    ],
                ]);

                if (! $valida) {
                    $errores = array_merge($errores, array_values($this->validator->getErrors()));
                } else {
                    $rutaNueva = $this->moverImagen($archivo);
                    $datos['imagen'] = $rutaNueva;
                }
            }

            // 3) Película
            if (empty($errores)) {
                $ok = $id === null
                    ? $this->peliculaModel->insert($datos)
                    : $this->peliculaModel->update($id, $datos);

                if (! $ok) {
                    $errores = array_merge($errores, array_values($this->peliculaModel->errors()));
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'No se pudo guardar la película: ' . $e->getMessage());
            $errores[] = 'Ocurrió un error al guardar la película. Intentá de nuevo.';
        }

        if (! empty($errores) || ! $db->transStatus()) {
            $db->transRollback();
            $this->borrarImagen($rutaNueva);

            return redirect()->back()->withInput()
                             ->with('errors', $errores ?: ['No se pudo guardar la película.']);
        }

        $db->transCommit();

        // Si se reemplazó el póster, el anterior ya no se usa.
        if ($existente && $rutaNueva !== null) {
            $this->borrarImagen($existente['imagen'] ?? null);
        }

        return redirect()->to(site_url('admin/peliculas'))
                         ->with('success', $id === null
                             ? 'Película agregada correctamente.'
                             : 'Película actualizada correctamente.');
    }

    /** Guarda la imagen con un nombre aleatorio y devuelve su ruta relativa a /public. */
    private function moverImagen(UploadedFile $archivo): string
    {
        $nombre = $archivo->getRandomName();
        $archivo->move(FCPATH . self::CARPETA_IMAGENES, $nombre);

        return self::CARPETA_IMAGENES . '/' . $nombre;
    }

    /** Borra un póster subido. Nunca toca archivos fuera de uploads/peliculas. */
    private function borrarImagen(?string $ruta): void
    {
        if ($ruta === null || strpos($ruta, self::CARPETA_IMAGENES . '/') !== 0 || strpos($ruta, '..') !== false) {
            return;
        }

        $archivo = FCPATH . $ruta;
        if (is_file($archivo)) {
            @unlink($archivo);
        }
    }

    // ================= API (JSON) =================

    public function index()
    {
        $peliculas = $this->peliculaModel->findAll();
        return $this->respond($peliculas, 200);
    }
    public function show($id = null)
    {
        $pelicula = $this->peliculaModel->find($id);

        if (!$pelicula) {
            return $this->failNotFound('La película solicitada no existe.');
        }

        return $this->respond($pelicula, 200);
    }

    //AGREGAR UNA NUEVA PELÍCULA
    public function create()
    {
        // Mapeamos los datos correspondientes a los campos permitidos
        $data = [
            'titulo'        => $this->request->getVar('titulo'),
            'sinopsis'      => $this->request->getVar('sinopsis'),
            'duracion'      => $this->request->getVar('duracion'),
            'clasificacion' => $this->request->getVar('clasificacion'),
            'trailer_url'   => $this->request->getVar('trailer_url'),
        ];

        // El modelo ejecuta la inserción y valida la longitud y formatos (como valid_url)
        if (!$this->peliculaModel->insert($data)) {
            return $this->respond(['errors' => $this->peliculaModel->errors()], 400);
        }

        return $this->respond([
            'status'      => 201,
            'message'     => 'Película agregada con éxito.',
            'id_pelicula' => $this->peliculaModel->getInsertID()
        ], 201);
    }

    //MODIFICAR LOS DATOS DE UNA PELÍCULA
    public function update($id = null)
    {
        // Validamos la existencia previa del registro
        if (!$this->peliculaModel->find($id)) {
            return $this->failNotFound('No se puede actualizar. La película no existe.');
        }

        $data = [
            'titulo'        => $this->request->getVar('titulo'),
            'sinopsis'      => $this->request->getVar('sinopsis'),
            'duracion'      => $this->request->getVar('duracion'),
            'clasificacion' => $this->request->getVar('clasificacion'),
            'trailer_url'   => $this->request->getVar('trailer_url'),
        ];

        // Limpiamos los valores nulos para no pisar datos existentes si no se envían en la petición
        $data = array_filter($data, function($value) {
            return $value !== null;
        });

        if (!$this->peliculaModel->update($id, $data)) {
            return $this->respond(['errors' => $this->peliculaModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Película modificada correctamente.'
        ], 200);
    }

    // REMOVER UNA PELÍCULA
    public function delete($id = null)
    {
        if (!$this->peliculaModel->find($id)) {
            return $this->failNotFound('La película no existe.');
        }

        $this->peliculaModel->delete($id);

        return $this->respond([
            'status'  => 200,
            'message' => 'Película eliminada correctamente.'
        ], 200);
    }
}
