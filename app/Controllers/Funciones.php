<?php

namespace App\Controllers;

use App\Models\FuncionModel;
use CodeIgniter\API\ResponseTrait;

class FuncionController extends BaseController
{
    use ResponseTrait;

    protected $funcionModel;

    public function __construct()
    {
        $this->funcionModel = new FuncionModel();
    }

    // LISTAR TODAS LAS FUNCIONES
    public function index()
    {
        $funciones = $this->funcionModel->findAll();
        return $this->respond($funciones, 200);
    }

    // MOSTRAR UNA FUNCIÓN POR SU ID
    public function show($id = null)
    {
        $funcion = $this->funcionModel->find($id);

        if (!$funcion) {
            return $this->failNotFound('La función solicitada no existe.');
        }

        return $this->respond($funcion, 200);
    }

    //CREAR UNA NUEVA FUNCIÓN
    public function create()
    {
        // Recolectamos los datos requeridos por la migración y el modelo
        $data = [
            'fecha'        => $this->request->getVar('fecha'),
            'hora_ingreso' => $this->request->getVar('hora_ingreso'),
            'hora_salida'  => $this->request->getVar('hora_salida'),
            'id_sala'      => $this->request->getVar('id_sala'),
            'id_pelicula'  => $this->request->getVar('id_pelicula'),
        ];

        // Validamos formatos de hora adicionales para proteger el tipo de dato TIME
        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $data['hora_ingreso'] ?? '') || 
            !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $data['hora_salida'] ?? '')) {
            return $this->respond(['errors' => ['horas' => 'Los formatos de hora de ingreso o salida no son válidos (debe ser HH:MM).']], 400);
        }

        // El modelo ejecuta la inserción y procesa sus reglas (valid_date, is_natural_no_zero)
        if (!$this->funcionModel->insert($data)) {
            return $this->respond(['errors' => $this->funcionModel->errors()], 400);
        }
        return $this->respond([
            'status'     => 201,
            'message'    => 'Función programada con éxito.',
            'id_funcion' => $this->funcionModel->getInsertID()
        ], 201);
    }

    // ACTUALIZAR UNA FUNCIÓN EXISTENTE
    public function update($id = null)
    {
        // Verificamos si el registro existe en la base de datos
        if (!$this->funcionModel->find($id)) {
            return $this->failNotFound('No se puede actualizar. La función no existe.');
        }

        // Tomamos los datos del cuerpo de la petición
        $data = [
            'fecha'        => $this->request->getVar('fecha'),
            'hora_ingreso' => $this->request->getVar('hora_ingreso'),
            'hora_salida'  => $this->request->getVar('hora_salida'),
            'id_sala'      => $this->request->getVar('id_sala'),
            'id_pelicula'  => $this->request->getVar('id_pelicula'),
        ];

        // Filtramos para actualizar únicamente los campos que el usuario envió
        $data = array_filter($data, function($value) {
            return $value !== null;
        });

        // Intentamos realizar la actualización empleando las reglas del modelo
        if (!$this->funcionModel->update($id, $data)) {
            return $this->respond(['errors' => $this->funcionModel->errors()], 400);
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Función actualizada correctamente.'
        ], 200);
    }

    //ELIMINAR UNA FUNCIÓN
    public function delete($id = null)
    {
        if (!$this->funcionModel->find($id)) {
            return $this->failNotFound('La función no existe.');
        }

        $this->funcionModel->delete($id);

        return $this->respond([
            'status'  => 200,
            'message' => 'Función eliminada correctamente.'
        ], 200);
    }
}