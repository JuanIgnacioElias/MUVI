<?php

namespace App\Controllers;

use App\Models\PagoModel;
use CodeIgniter\API\ResponseTrait;

class Pago extends BaseController
{
    use ResponseTrait;

    protected $pagoModel;

    public function __construct()
    {
        $this->pagoModel = new PagoModel();
    }

    // =========================================================================
    // VISTAS WEB (Control de flujo e Interfaz del Navegador)
    // =========================================================================

    public function index()
    {
        $session = session();

        // Verificamos si es personal del cine (por rol en sesión o por ?admin=true en la URL)
        $rolUsuario     = $session->get('rol') ?? 'Cliente';
        $esAdminUrl     = $this->request->getGet('admin') === 'true';
        $esPersonalCine = $esAdminUrl || in_array(strtolower($rolUsuario), ['admin', 'administrador', 'empleado', 'boleteria']);

        $data = [
            'esPersonalCine' => $esPersonalCine,
            'rolUsuario'     => $rolUsuario,
            'pagos'          => $esPersonalCine ? $this->pagoModel->findAll() : [],
            'resumen'        => $this->pagoModel->obtenerResumenDesdeSesion($session),
        ];

        return view('Pago/index', $data); 
    }

    public function nuevo()
    {
        $data = [
            'resumen' => $this->pagoModel->obtenerResumenDesdeSesion(session()),
        ];

        return view('Pago/form', $data);
    }

    public function guardarWeb()
    {
        $metodoPago = $this->request->getPost('metodo_pago');
        $estado     = ($metodoPago === 'efectivo') ? 'Pendiente' : 'Pagado';

        // Obtenemos el total real desde el modelo para evitar manipulación desde el navegador
        $resumen = $this->pagoModel->obtenerResumenDesdeSesion(session());

        $data = [
            'monto'       => $resumen['total_redondeado'],
            'moneda'      => $this->request->getPost('moneda'),
            'metodo_pago' => $metodoPago,
            'estado'      => $estado,
            'id_usuario'  => (int) session()->get('id') ?: null,
        ];

        if ($this->pagoModel->insert($data)) {
            // Limpiamos el carrito de la sesión tras confirmar el pago
            session()->remove([
                'id_funcion', 'id_sala', 'butacas_seleccionadas',
                'entradas_cantidad', 'entrada_precio_unit', 'entradas_es_4d',
                'entradas_total', 'combos_seleccionados', 'combos_total'
            ]);

            session()->setFlashdata('clear_cart', true);

            return redirect()->to(base_url('funcion'))->with('success', '¡Su pago ha sido registrado con éxito! Disfrute la función.');
        }

        return redirect()->back()->withInput()->with('errors', $this->pagoModel->errors());
    }

    // =========================================================================
    // ENDPOINTS API REST
    // =========================================================================

    public function apiIndex()
    {
        $pagos = $this->pagoModel->findAll();
        return $this->respond($pagos, 200);
    }

    public function show($id = null)
    {
        $pago = $this->pagoModel->find($id);

        if (!$pago) {
            return $this->failNotFound('El pago solicitado no existe.');
        }

        return $this->respond($pago, 200);
    }

    public function create()
    {
        $data = [
            'monto'       => $this->request->getVar('monto'),
            'moneda'      => $this->request->getVar('moneda'),
            'metodo_pago' => $this->request->getVar('metodo_pago'),
            'estado'      => $this->request->getVar('estado'),
        ];

        if (!$this->pagoModel->insert($data)) {
            return $this->respond(['errors' => $this->pagoModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 201,
            'message' => 'Pago registrado con éxito.',
            'id_pago' => $this->pagoModel->getInsertID()
        ], 201);
    }

    public function update($id = null)
    {
        if (!$this->pagoModel->find($id)) {
            return $this->failNotFound('No se puede actualizar. El pago no existe.');
        }

        $data = [
            'estado' => $this->request->getVar('estado')
        ];

        if (!$this->pagoModel->update($id, $data)) {
            return $this->respond(['errors' => $this->pagoModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Estado del pago actualizado correctamente.'
        ], 200);
    }
}
