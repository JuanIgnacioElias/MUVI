<?php

namespace App\Controllers;

use App\Models\FuncionModel;

class FuncionController extends BaseController
{
    protected $FuncionModel;

    public function __construct()
    {
        $this->FuncionModel = new FuncionModel();
    }

    // LISTAR TODAS LAS FUNCIONES (Muestra la tabla principal)
    public function index()
    {
        $data['funciones'] = $this->FuncionModel->findAll();
        
        // 🟢 DEFINIMOS EL ROL EN MAYÚSCULAS:
        // Podés cambiarlo a 'EMPLEADO' o 'CLIENTE' para probar cómo reacciona tu vista.
        $data['rol_usuario'] = 'ADMINISTRADOR'; 
        
        // Carga la vista en app/Views/funciones/index.php
        return view('funciones/index', $data); 
    }

    // MOSTRAR EL FORMULARIO DE CREACIÓN
    public function create()
    {
        // Carga la vista con el formulario en app/Views/funciones/create.php
        return view('funciones/create');
    }

    // ALMACENAR LA NUEVA FUNCIÓN (Procesa el formulario de alta)
    public function store()
    {
        $data = [
            'fecha'        => $this->request->getPost('fecha'),
            'hora_ingreso' => $this->request->getPost('hora_ingreso'),
            'hora_salida'  => $this->request->getPost('hora_salida'),
            'id_sala'      => $this->request->getPost('id_sala'),
            'id_pelicula'  => $this->request->getPost('id_pelicula'),
        ];

        // Validamos formatos de hora adicionales para proteger el tipo de dato TIME
        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $data['hora_ingreso'] ?? '') || 
            !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $data['hora_salida'] ?? '')) {
            
            return redirect()->back()
                             ->withInput()
                             ->with('errors', ['horas' => 'Los formatos de hora de ingreso o salida no son válidos (debe ser HH:MM).']);
        }

        // El modelo ejecuta la inserción y procesa sus reglas de validación interna
        if (!$this->FuncionModel->insert($data)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->FuncionModel->errors());
        }

        return redirect()->to('/funciones')
                         ->with('success', 'Función programada con éxito.');
    }

    // MOSTRAR EL FORMULARIO DE EDICIÓN
    public function edit($id = null)
    {
        $data['funcion'] = $this->FuncionModel->find($id);

        if (!$data['funcion']) {
            return redirect()->to('/funciones')
                             ->with('error', 'La función solicitada no existe.');
        }

        // Carga la vista con el formulario de edición en app/Views/funciones/edit.php
        return view('funciones/edit', $data);
    }

    // ACTUALIZAR UNA FUNCIÓN EXISTENTE (Procesa el formulario de edición)
    public function update($id = null)
    {
        if (!$this->FuncionModel->find($id)) {
            return redirect()->to('/funciones')
                             ->with('error', 'No se puede actualizar. La función no existe.');
        }

        $data = [
            'fecha'        => $this->request->getPost('fecha'),
            'hora_ingreso' => $this->request->getPost('hora_ingreso'),
            'hora_salida'  => $this->request->getPost('hora_salida'),
            'id_sala'      => $this->request->getPost('id_sala'),
            'id_pelicula'  => $this->request->getPost('id_pelicula'),
        ];

        // Filtramos para actualizar únicamente los campos que no vengan vacíos
        $data = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });

        if (!$this->FuncionModel->update($id, $data)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->FuncionModel->errors());
        }

        return redirect()->to('/funciones')
                         ->with('success', 'Función actualizada correctamente.');
    }

    public function delete($id = null)
    {
        if (!$this->FuncionModel->find($id)) {
            return redirect()->to('/funciones')
                             ->with('error', 'La función no existe.');
        }

        $this->FuncionModel->delete($id);

        return redirect()->to('/funciones')
                         ->with('success', 'Función eliminada correctamente.');
    }
}
