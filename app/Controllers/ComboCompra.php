<?php

namespace App\Controllers;

use App\Models\ComboCompraModel;
use CodeIgniter\API\ResponseTrait;

class ComboCompraController extends BaseController
{
    use ResponseTrait;

    protected $comboCompraModel;

    public function __construct()
    {
        $this->comboCompraModel = new ComboCompraModel();
    }
    public function index()
    {
        $asignaciones = $this->comboCompraModel->findAll();
        return $this->respond($asignaciones, 200);
    }
    public function show($id_compra = null, $id_combo = null)
    {
        if ($id_compra === null || $id_combo === null) {
            return $this->failValidationErrors('Se requieren tanto el id_compra como el id_combo.');
        }
        // Buscamos utilizando ambos identificadores debido a la clave compuesta
        $asignacion = $this->comboCompraModel
                           ->where('id_compra', $id_compra)
                           ->where('id_combo', $id_combo)
                           ->first();

        if (!$asignacion) {
            return $this->failNotFound('El combo asignado a la compra no existe.');
        }

        return $this->respond($asignacion, 200);
    }
    public function create()
    {
        $data = [
            'id_compra'      => $this->request->getVar('id_compra'),
            'id_combo'       => $this->request->getVar('id_combo'),
            'estado_entrega' => $this->request->getVar('estado_entrega'),
        ];

        // Se ejecuta la inserción y el modelo valida automáticamente todas tus reglas (max_length[20], integer, etc.)
        if (!$this->comboCompraModel->insert($data)) {
            return $this->respond(['errors' => $this->comboCompraModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 201,
            'message' => 'Combo asociado a la compra con éxito y registrado en sistema.'
        ], 201);
    }

    //ACTUALIZAR ESTADO DE ENTREGA (Ej: De "pendiente" a "entregado")
    public function updateStatus($id_compra = null, $id_combo = null)
    {
        if ($id_compra === null || $id_combo === null) {
            return $this->failValidationErrors('Se requieren tanto el id_compra como el id_combo para actualizar.');
        }

        // Comprobamos la existencia del registro compuesto
        $existe = $this->comboCompraModel
                       ->where('id_compra', $id_compra)
                       ->where('id_combo', $id_combo)
                       ->first();

        if (!$existe) {
            return $this->failNotFound('No se encontró el registro para actualizar.');
        }

        $data = [
            'estado_entrega' => $this->request->getVar('estado_entrega')
        ];

        // Validamos la regla max_length de manera manual sobre el campo modificado
        if (!$this->validateData($data, ['estado_entrega' => 'required|max_length[20]'])) {
            return $this->respond(['errors' => $this->validator->getErrors()], 400);
        }

        // Ejecutamos la actualización directa filtrando por la fila compuesta
        $this->comboCompraModel
             ->where('id_compra', $id_compra)
             ->where('id_combo', $id_combo)
             ->set($data)
             ->update();

        return $this->respond([
            'status'  => 200,
            'message' => 'El estado de entrega del combo fue actualizado.'
        ], 200);
    }

    // Remover un combo de la compra
    public function delete($id_compra = null, $id_combo = null)
    {
        if ($id_compra === null || $id_combo === null) {
            return $this->failValidationErrors('Se requieren ambos identificadores para la eliminación.');
        }

        $existe = $this->comboCompraModel
                       ->where('id_compra', $id_compra)
                       ->where('id_combo', $id_combo)
                       ->first();

        if (!$existe) {
            return $this->failNotFound('La asignación de combo solicitada no existe.');
        }

        // Borramos usando la coincidencia de ambos criterios
        $this->comboCompraModel
             ->where('id_compra', $id_compra)
             ->where('id_combo', $id_combo)
             ->delete();

        return $this->respond([
            'status'  => 200,
            'message' => 'El combo fue removido de la compra correctamente.'
        ], 200);
    }
}
