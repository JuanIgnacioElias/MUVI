<?php

namespace App\Controllers;

use App\Models\PeliculaModel;
use CodeIgniter\API\ResponseTrait;

class PeliculaController extends BaseController
{
    use ResponseTrait;

    protected $peliculaModel;

    public function __construct()
    {
        $this->peliculaModel = new PeliculaModel();
    }
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
