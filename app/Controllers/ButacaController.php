<?php

namespace App\Controllers;

use App\Models\ButacaModel;
use CodeIgniter\API\ResponseTrait;

class ButacaController extends BaseController
{
    use ResponseTrait;

    protected $butacaModel;

    public function __construct()
    {
        $this->butacaModel = new ButacaModel();
    }
    public function index()
    {
        $butacas = $this->butacaModel->findAll();
        return $this->respond($butacas, 200);
    }
    public function show($id = null)
    {
        $butaca = $this->butacaModel->find($id);

        if (!$butaca) {
            return $this->failNotFound('La butaca solicitada no existe.');
        }

        return $this->respond($butaca, 200);
    }
    public function create()
    {
        $data = [
            'posicionX'      => $this->request->getVar('posicionX'), // Ej: 'A'
            'posicionY'      => $this->request->getVar('posicionY'), // Ej: 5
            'disponibilidad' => $this->request->getVar('disponibilidad'), // Ej: 1 o true
            'id_entrada'     => $this->request->getVar('id_entrada'),
            'id_sala'        => $this->request->getVar('id_sala'),
        ];

        // El modelo ejecuta la inserción y valida la longitud y tipos de datos
        if (!$this->butacaModel->insert($data)) {
            return $this->respond(['errors' => $this->butacaModel->errors()], 400);
        }

        return $this->respond([
            'status'    => 201,
            'message'   => 'Butaca registrada con éxito en el sistema.',
            'id_butaca' => $this->butacaModel->getInsertID()
        ], 201);
    }

    //MODIFICAR EL ESTADO O UBICACIÓN DE UNA BUTACA
    public function update($id = null)
    {
        if (!$this->butacaModel->find($id)) {
            return $this->failNotFound('No se puede actualizar. La butaca no existe.');
        }

        $data = [
            'posicionX'      => $this->request->getVar('posicionX'),
            'posicionY'      => $this->request->getVar('posicionY'),
            'disponibilidad' => $this->request->getVar('disponibilidad'),
            'id_entrada'     => $this->request->getVar('id_entrada'),
            'id_sala'        => $this->request->getVar('id_sala'),
        ];

        // Limpiamos los nulos para actualizar solo lo que se envía en la petición
        $data = array_filter($data, function($value) {
            return $value !== null;
        });

        if (!$this->butacaModel->update($id, $data)) {
            return $this->respond(['errors' => $this->butacaModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Información de la butaca modificada correctamente.'
        ], 200);
    }
    public function delete($id = null)
    {
        if (!$this->butacaModel->find($id)) {
            return $this->failNotFound('La butaca no existe.');
        }

        $this->butacaModel->delete($id);

        return $this->respond([
            'status'  => 200,
            'message' => 'Butaca removida del mapa de la sala correctamente.'
        ], 200);
    }
}
