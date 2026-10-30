<?php

namespace App\Controllers;

use App\Models\ComboModel;
use CodeIgniter\API\ResponseTrait;

class ComboController extends BaseController
{
    use ResponseTrait;

    protected $comboModel;

    public function __construct()
    {
        $this->comboModel = new ComboModel();
    }

    public function index()
    {
        // Obtenemos todos los combos de la base de datos
        $data['combos'] = $this->comboModel->findAll();
        // Si estás consumiendo esto desde una app web tradicional de CI4:
        return view('combo_view', $data);
    }
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
        // Mapeamos los datos a los campos 
        $data = [
            'comida' => $this->request->getVar('comida'),
            'bebida' => $this->request->getVar('bebida'),
        ];

        // Se ejecuta la inserción y el modelo valida automáticamente tus reglas (max_length[20])
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
        // Verificamos si el combo existe previamente en la base de datos
        if (!$this->comboModel->find($id)) {
            return $this->failNotFound('No se puede actualizar. El combo no existe.');
        }

        $data = [
            'comida' => $this->request->getVar('comida'),
            'bebida' => $this->request->getVar('bebida'),
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
        if (!$this->comboModel->find($id)) {
            return $this->failNotFound('El combo no existe.');
        }

        $this->comboModel->delete($id);

        return $this->respond([
            'status'  => 200,
            'message' => 'Combo eliminado correctamente.'
        ], 200);
    }
}
