<?php

namespace App\Controllers;

use App\Models\CompraButacaModel;
use CodeIgniter\API\ResponseTrait;

class CompraButacaController extends BaseController
{
    use ResponseTrait;

    protected $compraButacaModel;

    public function __construct()
    {
        $this->compraButacaModel = new CompraButacaModel();
    }
    public function index()
    {
        $asignaciones = $this->compraButacaModel->findAll();
        return $this->respond($asignaciones, 200);
    }
    public function show($id = null)
    {
        $asignacion = $this->compraButacaModel->find($id);

        if (!$asignacion) {
            return $this->failNotFound('La asignación de butaca solicitada no existe.');
        }

        return $this->respond($asignacion, 200);
    }

    // 3. ASOCIAR UNA BUTACA A UNA COMPRA
    public function create()
    {
        // Capturamos las llaves foráneas requeridas
        $data = [
            'id_compra' => $this->request->getVar('id_compra'),
            'id_butaca' => $this->request->getVar('id_butaca'),
        ];

        // Validación básica manual ya que el modelo actual no incluye reglas estrictas
        if (empty($data['id_compra']) || empty($data['id_butaca'])) {
            return $this->respond(['errors' => ['fields' => 'Los campos id_compra e id_butaca son obligatorios.']], 400);
        }

        // Realizamos la inserción (no generará fechas automáticamente por tu configuración del modelo)
        if (!$this->compraButacaModel->insert($data)) {
            return $this->respond(['errors' => $this->compraButacaModel->errors()], 400);
        }

        return $this->respond([
            'status'           => 201,
            'message'          => 'Butaca vinculada a la compra exitosamente.',
            'id_Compra_Butaca' => $this->compraButacaModel->getInsertID()
        ], 201);
    }

    // ELIMINAR O LIBERAR UNA BUTACA DE UNA COMPRA
    public function delete($id = null)
    {
        if (!$this->compraButacaModel->find($id)) {
            return $this->failNotFound('La asignación de butaca no existe.');
        }

        $this->compraButacaModel->delete($id);

        return $this->respond([
            'status'  => 200,
            'message' => 'Vínculo de butaca eliminado correctamente.'
        ], 200);
    }
}
