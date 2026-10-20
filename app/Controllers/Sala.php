<?php

namespace App\Controllers;

use App\Models\SalaModel;
use CodeIgniter\API\ResponseTrait;

class SalaController extends BaseController
{
    use ResponseTrait;

    protected $salaModel;

    public function __construct()
    {
        $this->salaModel = new SalaModel();
    }
    public function index()
    {
        $salas = $this->salaModel->findAll();
        return $this->respond($salas, 200);
    }
    public function show($id = null)
    {
        $sala = $this->salaModel->find($id);

        if (!$sala) {
            return $this->failNotFound('La sala solicitada no existe.');
        }

        return $this->respond($sala, 200);
    }

    //REGISTRAR UNA NUEVA SALA
    public function create()
    {
        // Mapeamos los campos del modelo
        $data = [
            'nombre'    => $this->request->getVar('nombre'),
            'capacidad' => $this->request->getVar('capacidad'),
            'tipo_sala' => $this->request->getVar('tipo_sala'), // Recibe el formato decimal (ej: 2.00 o 3.00)
        ];

        // El modelo valida de forma automática (max_length[20], is_natural_no_zero, decimal)
        if (!$this->salaModel->insert($data)) {
            return $this->respond(['errors' => $this->salaModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 201,
            'message' => 'Sala registrada con éxito.',
            'id_sala' => $this->salaModel->getInsertID()
        ], 201);
    }

    //ACTUALIZAR LOS DATOS DE UNA SALA
    public function update($id = null)
    {
        // Verificamos si la sala existe
        if (!$this->salaModel->find($id)) {
            return $this->failNotFound('No se puede actualizar. La sala no existe.');
        }

        $data = [
            'nombre'    => $this->request->getVar('nombre'),
            'capacidad' => $this->request->getVar('capacidad'),
            'tipo_sala' => $this->request->getVar('tipo_sala'),
        ];

        // Filtramos para ignorar parámetros nulos y actualizar sólo lo enviado
        $data = array_filter($data, function($value) {
            return $value !== null;
        });

        if (!$this->salaModel->update($id, $data)) {
            return $this->respond(['errors' => $this->salaModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Sala modificada correctamente.'
        ], 200);
    }

    //ELIMINAR UNA SALA
    public function delete($id = null)
    {
        if (!$this->salaModel->find($id)) {
            return $this->failNotFound('La sala no existe.');
        }

        $this->salaModel->delete($id);

        return $this->respond([
            'status'  => 200,
            'message' => 'Sala eliminada correctamente.'
        ], 200);
    }
}
