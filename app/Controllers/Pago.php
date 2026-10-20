<?php

namespace App\Controllers;

use App\Models\PagoModel;
use CodeIgniter\API\ResponseTrait;

class PagoController extends BaseController
{
    use ResponseTrait;

    protected $pagoModel;

    public function __construct()
    {
        $this->pagoModel = new PagoModel();
    }

    //  OBTENER TODOS LOS PAGOS
    public function index()
    {
        $pagos = $this->pagoModel->findAll();
        return $this->respond($pagos, 200);
    }

    //  OBTENER UN PAGO POR SU ID
    public function show($id = null)
    {
        $pago = $this->pagoModel->find($id);

        if (!$pago) {
            return $this->failNotFound('El pago solicitado no existe.');
        }

        return $this->respond($pago, 200);
    }

    //REGISTRAR UN NUEVO PAGO
    public function create()
    {
        // Recolectamos los datos exactos que permite tu modelo
        $data = [
            'monto'       => $this->request->getVar('monto'),
            'moneda'      => $this->request->getVar('moneda'),
            'metodo_pago' => $this->request->getVar('metodo_pago'),
            'estado'      => $this->request->getVar('estado'),
        ];

        // Se ejecuta la inserción y CodeIgniter valida automáticamente las reglas
        if (!$this->pagoModel->insert($data)) {
            // Si falla, devuelve error
            return $this->respond(['errors' => $this->pagoModel->errors()], 400);
        }

        // Si todo sale bien, CodeIgniter ya habrá guardado el "created_at" solo
        return $this->respond([
            'status'  => 201,
            'message' => 'Pago registrado con éxito.',
            'id_pago' => $this->pagoModel->getInsertID() // Retorna el ID generado
        ], 201);
    }

    //  ACTUALIZA EL ESTADO DE UN PAGO (Ej: De 'pendiente' a 'aprobado')
    public function update($id = null)
    {
        // Verificamos si el pago existe
        if (!$this->pagoModel->find($id)) {
            return $this->failNotFound('No se puede actualizar. El pago no existe.');
        }

        // Capturamos solo el campo estado 
        $data = [
            'estado' => $this->request->getVar('estado')
        ];

        // Validamos y actualizamos. Esto actualizará automáticamente el "updated_at"
        if (!$this->pagoModel->update($id, $data)) {
            return $this->respond(['errors' => $this->pagoModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Estado del pago actualizado correctamente.'
        ], 200);
    }
}