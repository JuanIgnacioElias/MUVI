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

    /**
     * Salas y películas para los <select> del formulario.
     *
     * Se usa el query builder porque Models/Salas.php y Models/Peliculas.php
     * declaran las clases SalaModel y PeliculaModel, y CodeIgniter no las
     * puede autocargar con un nombre de archivo distinto al de la clase.
     */
    private function opcionesFormulario(): array
    {
        $db = db_connect();

        return [
            'salas'     => $db->table('salas')
                               ->select('id_sala, nombre')
                               ->orderBy('nombre')
                               ->get()->getResultArray(),
            'peliculas' => $db->table('peliculas')
                               ->select('id_pelicula, titulo')
                               ->orderBy('titulo')
                               ->get()->getResultArray(),
        ];
    }

    // LISTAR TODAS LAS FUNCIONES (Muestra la tabla principal)
    public function index()
    {
        $data['funciones'] = $this->FuncionModel->findAll();

        // El rol sale de la sesión (lo guarda Auth::processLogin en la clave 'role').
        $data['rol_usuario'] = strtoupper((string) session()->get('role'));

        return view('funciones/index', $data);
    }

    // MOSTRAR EL FORMULARIO DE CREACIÓN
    public function create()
    {
        return view('funciones/form', $this->opcionesFormulario() + ['funcion' => null]);
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

        return redirect()->to('/funcion')
                         ->with('success', 'Función programada con éxito.');
    }

    // MOSTRAR EL FORMULARIO DE EDICIÓN
    public function edit($id = null)
    {
        $funcion = $this->FuncionModel->find($id);

        if (!$funcion) {
            return redirect()->to('/funcion')
                             ->with('error', 'La función solicitada no existe.');
        }

        return view('funciones/form', $this->opcionesFormulario() + ['funcion' => $funcion]);
    }

    // ACTUALIZAR UNA FUNCIÓN EXISTENTE (Procesa el formulario de edición)
    public function update($id = null)
    {
        if (!$this->FuncionModel->find($id)) {
            return redirect()->to('/funcion')
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
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });

        if (!$this->FuncionModel->update($id, $data)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->FuncionModel->errors());
        }

        return redirect()->to('/funcion')
                         ->with('success', 'Función actualizada correctamente.');
    }

    public function delete($id = null)
    {
        if (!$this->FuncionModel->find($id)) {
            return redirect()->to('/funcion')
                             ->with('error', 'La función no existe.');
        }

        $this->FuncionModel->delete($id);

        return redirect()->to('/funcion')
                         ->with('success', 'Función eliminada correctamente.');
    }

    // CLIENTE: elige una función y pasa a la selección de butacas
    public function seleccionar($id = null)
    {
        $funcion = $this->FuncionModel->find($id);

        if (!$funcion) {
            return redirect()->to('/funcion')
                             ->with('error', 'La función solicitada no existe.');
        }
// Guardamos la función y su sala en sesión
session()->set([
    'id_funcion' => (int) $funcion['id_funcion'],
    'id_sala'    => (int) $funcion['id_sala'],
]);

return redirect()->to('/butacas?id_sala=' . $funcion['id_sala']);
       
    }
}
