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
        $id_sala = $this->request->getGet('id_sala') ?? 1;
        $butacas = $this->butacaModel->where('id_sala', $id_sala)->findAll();

        return $this->respond($butacas, 200);
    }

    public function mostrarPantalla()
    {
        $id_sala = $this->request->getGet('id_sala') ?? 1;
        $rol = strtoupper((string) session()->get('rol'));
        $puedeEditar = in_array($rol, ['EMPLEADO', 'ADMINISTRADOR']);

        return view('butacas/index', [
            'id_sala'     => $id_sala,
            'puedeEditar' => $puedeEditar
        ]); 
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
            'posicionX'      => $this->request->getVar('posicionX'),
            'posicionY'      => $this->request->getVar('posicionY'),
            'disponibilidad' => $this->request->getVar('disponibilidad'),
            'id_entrada'     => $this->request->getVar('id_entrada'),
            'id_sala'        => $this->request->getVar('id_sala'),
        ];

        if (!$this->butacaModel->insert($data)) {
            return $this->respond(['errors' => $this->butacaModel->errors()], 400);
        }

        return $this->respond([
            'status'    => 201,
            'message'   => 'Butaca registrada con éxito en el sistema.',
            'id_butaca' => $this->butacaModel->getInsertID()
        ], 201);
    }

    public function update($id = null)
    {
        if (!$this->butacaModel->find($id)) {
            return $this->failNotFound('No se puede actualizar. La butaca no existe.');
        }

        $data = array_filter([
            'posicionX'      => $this->request->getVar('posicionX'),
            'posicionY'      => $this->request->getVar('posicionY'),
            'disponibilidad' => $this->request->getVar('disponibilidad'),
            'id_entrada'     => $this->request->getVar('id_entrada'),
            'id_sala'        => $this->request->getVar('id_sala'),
        ], fn($value) => $value !== null);

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

    public function Combo()
    {
        return view('butacas/Combo'); 
    }

    // --- GUARDAR MAPA EDITADO POR EMPLEADO O ADMINISTRADOR ---
    public function guardarMapa($id_sala)
    {
        $rol = strtoupper((string) session()->get('rol')); 
        if (!in_array($rol, ['EMPLEADO', 'ADMINISTRADOR'])) {
            return $this->failForbidden('No tenés permisos para modificar la sala.');
        }

        $json = $this->request->getJSON(true);
        $nuevasButacas = $json['butacas'] ?? [];

        if (empty($nuevasButacas)) {
            return $this->fail('La sala debe tener al menos una butaca activa.', 400);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Borramos la configuración anterior de esa sala y guardamos la nueva
        $this->butacaModel->where('id_sala', $id_sala)->delete();

        $loteInsertar = [];
        foreach ($nuevasButacas as $b) {
            $loteInsertar[] = [
                'posicionX'      => $b['posicionX'],
                'posicionY'      => $b['posicionY'],
                'disponibilidad' => $b['disponibilidad'] ?? 1,
                'id_sala'        => $id_sala,
                'id_entrada'     => null
            ];
        }

        $this->butacaModel->insertBatch($loteInsertar);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->failServerError('Error al guardar el diseño de la sala.');
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Diseño de la sala actualizado correctamente.'
        ], 200);
    }
}
