<?php

namespace App\Controllers;

use App\Models\ComboModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Files\UploadedFile;

class ComboController extends BaseController
{
    use ResponseTrait;

    /** Carpeta (dentro de /public) donde se guardan las imágenes de los combos. */
    private const CARPETA_IMAGENES = 'uploads/combos';

    protected $comboModel;

    public function __construct()
    {
        $this->comboModel = new ComboModel();
    }

    public function index()
    {
        // Obtenemos todos los combos de la base de datos
        $data['combos'] = $this->comboModel->findAll();
        return view('combo/combo_view', $data);
    }

    // ================= VISTAS WEB =================

    // ADMIN: muestra el formulario para agregar un combo
    public function nuevo()
    {
        return view('combo/form');
    }

    // ADMIN: recibe el formulario y guarda el combo (con imagen opcional)
    public function guardar()
    {
        $data      = $this->request->getPost(['comida', 'bebida', 'precio']);
        $rutaNueva = null;

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
                return redirect()->back()->withInput()
                    ->with('errores', array_values($this->validator->getErrors()));
            }

            $rutaNueva      = $this->moverImagen($archivo);
            $data['imagen'] = $rutaNueva;
        }

        if (! $this->comboModel->insert($data)) {
            $this->borrarImagen($rutaNueva);

            return redirect()->back()->withInput()
                ->with('errores', $this->comboModel->errors());
        }

        return redirect()->to(base_url('combos'))
            ->with('mensaje', 'Combo agregado correctamente.');
    }

    /** Guarda la imagen con un nombre aleatorio y devuelve su ruta relativa a /public. */
    private function moverImagen(UploadedFile $archivo): string
    {
        $nombre = $archivo->getRandomName();
        $archivo->move(FCPATH . self::CARPETA_IMAGENES, $nombre);

        return self::CARPETA_IMAGENES . '/' . $nombre;
    }

    /** Borra una imagen subida. Nunca toca archivos fuera de uploads/combos. */
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

    // CLIENTE: recibe las cantidades de cada combo y los guarda en sesión
    public function seleccionar()
    {
        $cantidadesPost = $this->request->getPost('cantidades');

        if (empty($cantidadesPost) || ! is_array($cantidadesPost)) {
            return redirect()->to(base_url('combos'))
                ->with('errores', ['Seleccioná al menos un combo.']);
        }

        // Filtramos solo los combos con cantidad mayor a 0
        $seleccionados = [];
        foreach ($cantidadesPost as $id => $cant) {
            $id   = (int) $id;
            $cant = (int) $cant;
            if ($id > 0 && $cant > 0) {
                $seleccionados[$id] = $cant;
            }
        }

        if (empty($seleccionados)) {
            return redirect()->to(base_url('combos'))
                ->with('errores', ['Seleccioná al menos un combo.']);
        }

        $combos = $this->comboModel->whereIn('id_combo', array_keys($seleccionados))->findAll();

        if (empty($combos)) {
            return redirect()->to(base_url('combos'))
                ->with('errores', ['Los combos seleccionados no existen.']);
        }

        // Calculamos el total multiplicando precio * cantidad en el servidor
        $total = 0;
        $totalUnidades = 0;
        foreach ($combos as $combo) {
            $id   = (int) $combo['id_combo'];
            $cant = $seleccionados[$id] ?? 0;
            $total += (float) $combo['precio'] * $cant;
            $totalUnidades += $cant;
        }

        session()->set([
            'combos_seleccionados' => $seleccionados, // Ej: [1 => 2, 3 => 1]
            'combos_total'         => $total,
        ]);

        return redirect()->to(base_url('pago'))
            ->with('mensaje', 'Combos seleccionados: ' . $totalUnidades);
    }

    
    // ================= API (JSON) =================

    public function show($id = null)
    {
        $combo = $this->comboModel->find($id);

        if (!$combo) {
            return $this->failNotFound('El combo solicitado no existe.');
        }

        return $this->respond($combo, 200);
    }

    //REGISTRAR UN NUEVO COMBO
    public function create()
    {
        $data = [
            'comida' => $this->request->getVar('comida'),
            'bebida' => $this->request->getVar('bebida'),
            'precio' => $this->request->getVar('precio'),
        ];

        if (!$this->comboModel->insert($data)) {
            return $this->respond(['errors' => $this->comboModel->errors()], 400);
        }

        return $this->respond([
            'status'   => 201,
            'message'  => 'Combo registrado con éxito.',
            'id_combo' => $this->comboModel->getInsertID()
        ], 201);
    }

    //ACTUALIZAR LOS DATOS DE UN COMBO EXISTENTE
    public function update($id = null)
    {
        if (!$this->comboModel->find($id)) {
            return $this->failNotFound('No se puede actualizar. El combo no existe.');
        }

        $data = [
            'comida' => $this->request->getVar('comida'),
            'bebida' => $this->request->getVar('bebida'),
            'precio' => $this->request->getVar('precio'),
        ];

        // Limpiamos los campos nulos para que conserve los datos anteriores si no se envían
        $data = array_filter($data, function($value) {
            return $value !== null;
        });

        if (!$this->comboModel->update($id, $data)) {
            return $this->respond(['errors' => $this->comboModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Combo modificado correctamente.'
        ], 200);
    }

    //ELIMINAR UN COMBO
    public function delete($id = null)
    {
        $combo = $this->comboModel->find($id);
        if (!$combo) {
            return $this->failNotFound('El combo no existe.');
        }

        $this->comboModel->delete($id);
        $this->borrarImagen($combo['imagen'] ?? null);

        return $this->respond([
            'status'  => 200,
            'message' => 'Combo eliminado correctamente.'
        ], 200);
    }
        // CLIENTE: pasa a pago sin seleccionar combos
        public function omitir()
        {
            session()->remove(['combos_seleccionados', 'combos_total']);
            return redirect()->to(base_url('pago'));
        }
    
}
