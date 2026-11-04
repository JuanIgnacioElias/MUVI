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

    /**
     * Muestra la vista principal. 
     * Con la inversión de flujos, esta ruta carga el Formulario de Pago para Clientes.
     * Si se pasa el parámetro ?admin=true en la URL, renderiza la tabla de auditoría.
     */
    public function index()
    {
        $data['pagos'] = $this->pagoModel->findAll();
        return view('Pago/index', $data); 
    }

    /**
     * Muestra el formulario alternativo para crear un pago.
     * Nota: Mantenido por compatibilidad estructural del sistema.
     */
    public function nuevo()
    {
        return view('Pago/form');
    }

    /**
     * Procesa el formulario enviado por el cliente, inserta la orden en la BD
     * y retorna de forma segura limpiando la sesión local del carrito.
     */
    public function guardarWeb()
    {
        $data = [
            'monto'       => $this->request->getPost('monto'),
            'moneda'      => $this->request->getPost('moneda'),
            'metodo_pago' => $this->request->getPost('metodo_pago'),
            'estado'      => $this->request->getPost('estado'),
        ];

        if ($this->pagoModel->insert($data)) {
            // Guardamos una señal flash temporal que el JavaScript de la vista leerá para vaciar el localStorage
            session()->setFlashdata('clear_cart', true);
            
            // Redirección fluida y segura a la cartelera pública del cine con mensaje de éxito
            return redirect()->to(base_url('cartelera'))->with('success', '¡Su pago ha sido registrado con éxito! Disfrute la función.');
        }

        return redirect()->back()->withInput()->with('errors', $this->pagoModel->errors());
    }

    /**============
     * OBTENER TODOS LOS PAGOS VIA API
     */
    public function apiIndex()
    {
        $pagos = $this->pagoModel->findAll();
        return $this->respond($pagos, 200);
    }

    /**
     * OBTENER UN PAGO POR SU ID
     */
    public function show($id = null)
    {
        $pago = $this->pagoModel->find($id);

        if (!$pago) {
            return $this->failNotFound('El pago solicitado no existe.');
        }

        return $this->respond($pago, 200);
    }

    /**
     * REGISTRAR UN NUEVO PAGO VIA API
     */
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

    /**
     * ACTUALIZA EL ESTADO DE UN PAGO VIA API
     */
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
