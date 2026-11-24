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
    // CLIENTE: recibe las cantidades de cada combo y los guarda en sesión
    public function seleccionar()
    {
        $cantidadesPost = $this->request->getPost('cantidades');

        if (empty($cantidadesPost) || ! is_array($cantidadesPost)) {
            return redirect()->to(base_url('combos'))
                ->with('errores', ['Seleccioná al menos un combo.']);
        }

        // Filtramos solo los combos con cantidad mayor a 0
        $seleccionados = [];
        foreach ($cantidadesPost as $id => $cant) {
            $id   = (int) $id;
            $cant = (int) $cant;
            if ($id > 0 && $cant > 0) {
                $seleccionados[$id] = $cant;
            }
        }

        if (empty($seleccionados)) {
            return redirect()->to(base_url('combos'))
                ->with('errores', ['Seleccioná al menos un combo.']);
        }

        $combos = $this->comboModel->whereIn('id_combo', array_keys($seleccionados))->findAll();

        if (empty($combos)) {
            return redirect()->to(base_url('combos'))
                ->with('errores', ['Los combos seleccionados no existen.']);
        }

        // Calculamos el total multiplicando precio * cantidad en el servidor
        $total = 0;
        $totalUnidades = 0;
        foreach ($combos as $combo) {
            $id   = (int) $combo['id_combo'];
            $cant = $seleccionados[$id] ?? 0;
            $total += (float) $combo['precio'] * $cant;
            $totalUnidades += $cant;
        }

        session()->set([
            'combos_seleccionados' => $seleccionados, // Ej: [1 => 2, 3 => 1]
            'combos_total'         => $total,
        ]);

        return redirect()->to(base_url('pago'))
            ->with('mensaje', 'Combos seleccionados: ' . $totalUnidades);
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
        // CLIENTE: pasa a pago sin seleccionar combos
        public function omitir()
        {
            session()->remove(['combos_seleccionados', 'combos_total']);
            return redirect()->to(base_url('pago'));
        }
    
}
