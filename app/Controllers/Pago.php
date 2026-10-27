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
    // VISTAS WEB (Para ver e interactuar en el navegador)
    //Muestra el historial visual 
    public function index()
    {
        $data['pagos'] = $this->pagoModel->findAll();
        return view('Pago/index', $data); 
    }
    // Muestra el formulario para crear un pago
    public function nuevo()
    {
        return view('Pago/form');
    }

    // Procesa el formulario web y redirige
    public function guardarWeb()
    {
        $data = [
            'monto'       => $this->request->getPost('monto'),
            'moneda'      => $this->request->getPost('moneda'),
            'metodo_pago' => $this->request->getPost('metodo_pago'),
            'estado'      => $this->request->getPost('estado'),
        ];

        if ($this->pagoModel->insert($data)) {
            return redirect()->to(base_url('pago'))->with('success', '¡Pago registrado con éxito!');
        }

        return redirect()->back()->withInput()->with('errors', $this->pagoModel->errors());
    }

    // OBTENER TODOS LOS PAGOS
    public function apiIndex()
    {
        $pagos = $this->pagoModel->findAll();
        return $this->respond($pagos, 200);
    }

    // OBTENER UN PAGO POR SU ID
    public function show($id = null)
    {
        $pago = $this->pagoModel->find($id);

        if (!$pago) {
            return $this->failNotFound('El pago solicitado no existe.');
        }

        return $this->respond($pago, 200);
    }

    // REGISTRAR UN NUEVO PAGO
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

    // ACTUALIZA EL ESTADO DE UN PAGO
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
