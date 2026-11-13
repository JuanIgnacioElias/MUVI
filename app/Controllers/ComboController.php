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
        return view('combo/combo_view', $data);
    }

    // ================= VISTAS WEB =================

    // ADMIN: muestra el formulario para agregar un combo
    public function nuevo()
    {
        return view('combo/form');
    }

    // ADMIN: recibe el formulario y guarda el combo
    public function guardar()
    {
        $data = $this->request->getPost(['comida', 'bebida', 'precio']);

        if (! $this->comboModel->insert($data)) {
            return redirect()->back()->withInput()
                ->with('errores', $this->comboModel->errors());
        }

        return redirect()->to(base_url('combos'))
            ->with('mensaje', 'Combo agregado correctamente.');
    }

    // CLIENTE: recibe los combos marcados (checkbox) y los guarda en sesión
    public function seleccionar()
    {
        $ids = $this->request->getPost('combos');

        if (empty($ids) || ! is_array($ids)) {
            return redirect()->to(base_url('combos'))
                ->with('errores', ['Seleccioná al menos un combo.']);
        }

        $ids    = array_values(array_unique(array_map('intval', $ids)));
        $combos = $this->comboModel->whereIn('id_combo', $ids)->findAll();

        if (empty($combos)) {
            return redirect()->to(base_url('combos'))
                ->with('errores', ['Los combos seleccionados no existen.']);
        }

        // El total se calcula en el servidor, no se confía en el del navegador
        $total = array_sum(array_column($combos, 'precio'));

        session()->set([
            'combos_seleccionados' => array_column($combos, 'id_combo'),
            'combos_total'         => $total,
        ]);

        return redirect()->to(base_url('pago'))
            ->with('mensaje', 'Combos seleccionados: ' . count($combos));
    }

    // ================= API (JSON) =================

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
        $data = [
            'comida' => $this->request->getVar('comida'),
            'bebida' => $this->request->getVar('bebida'),
            'precio' => $this->request->getVar('precio'),
        ];

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
        if (!$this->comboModel->find($id)) {
            return $this->failNotFound('No se puede actualizar. El combo no existe.');
        }

        $data = [
            'comida' => $this->request->getVar('comida'),
            'bebida' => $this->request->getVar('bebida'),
            'precio' => $this->request->getVar('precio'),
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
