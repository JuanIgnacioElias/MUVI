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
        $id_sala = $this->request->getGet('id_sala') ?? session()->get('id_sala') ?? 1;
        
        // Leemos 'role' igual que en tu RoleFilter.php
        $rol = strtoupper((string) session()->get('role'));
        $puedeEditar = in_array($rol, ['EMPLEADO', 'ADMINISTRADOR'], true);

        return view('butacas/index', [
            'id_sala'     => $id_sala,
            'puedeEditar' => $puedeEditar
        ]); 
    }

    // CLIENTE: recibe las butacas elegidas, calcula precio (4D vs Normal) y va a Combos
    public function seleccionar()
    {
        $asientosJson = $this->request->getPost('asientos');
        $asientos = json_decode((string) $asientosJson, true);

        if (empty($asientos) || !is_array($asientos)) {
            return redirect()->back()->with('error', 'Seleccioná al menos una butaca.');
        }

        $id_sala = session()->get('id_sala') ?? 1;

        $db = db_connect();
        $sala = $db->table('salas')->where('id_sala', $id_sala)->get()->getRowArray();

        // Detectamos si la sala es 4D (por tipo_sala == 4 o si el nombre dice "4D")
        $es4D = false;
        if ($sala) {
            $tipo = (float) ($sala['tipo_sala'] ?? 0);
            $nombreSala = strtoupper((string) ($sala['nombre'] ?? ''));
            $es4D = ($tipo >= 4.0 || str_contains($nombreSala, '4D'));
        }

        // PRECIOS FIJOS (Ajustalos a los valores de tu cine)
        $precioUnitario = $es4D ? 8000 : 5000;
        $cantidad       = count($asientos);
        $totalEntradas  = $cantidad * $precioUnitario;

        // Guardamos todo en la sesión para el resumen de Pago
        session()->set([
            'butacas_seleccionadas' => array_column($asientos, 'id'), // Ej: ['A-1', 'A-2']
            'entradas_cantidad'     => $cantidad,
            'entrada_precio_unit'   => $precioUnitario,
            'entradas_es_4d'        => $es4D,
            'entradas_total'        => $totalEntradas,
        ]);

        return redirect()->to(base_url('combos'));
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
        $rol = strtoupper((string) session()->get('role')); 
        if (!in_array($rol, ['EMPLEADO', 'ADMINISTRADOR'], true)) {
            return $this->failForbidden('Sin permisos. Rol detectado: ' . ($rol ?: 'vacío'));
        }

        $json = $this->request->getJSON(true);
        $nuevasButacas = $json['butacas'] ?? [];

        if (empty($nuevasButacas)) {
            return $this->fail('La sala debe tener al menos una butaca activa.', 400);
        }

        $db = \Config\Database::connect();

        try {
            // 1. Verificamos si existe la sala en la tabla 'salas'
            if ($db->tableExists('salas')) {
                $existeSala = $db->table('salas')->where('id_sala', $id_sala)->countAllResults();
                if ($existeSala === 0) {
                    $db->table('salas')->insert([
                        'id_sala'   => $id_sala,
                        'nombre'    => 'Sala ' . $id_sala,
                        'capacidad' => count($nuevasButacas)
                    ]);
                }
            }

            // 2. Borramos las butacas anteriores de esa sala
            $this->butacaModel->where('id_sala', $id_sala)->delete();

            // 3. Preparamos e insertamos las nuevas (sin id_entrada)
            $loteInsertar = [];
            foreach ($nuevasButacas as $b) {
                $loteInsertar[] = [
                    'posicionX'      => $b['posicionX'],
                    'posicionY'      => $b['posicionY'],
                    'disponibilidad' => $b['disponibilidad'] ?? 1,
                    'id_sala'        => $id_sala
                ];
            }

            if (!$this->butacaModel->insertBatch($loteInsertar)) {
                return $this->failServerError('Error Modelo: ' . json_encode($this->butacaModel->errors()));
            }

        } catch (\Throwable $e) {
            return $this->failServerError('MySQL dice: ' . $e->getMessage());
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Diseño de la sala actualizado correctamente.'
        ], 200);
    }
}
