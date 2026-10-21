<?php

namespace App\Controllers;

class ClienteController extends BaseController
{
    public function index()
    {
        // 1. Cargamos la sesión para poder usar los datos del usuario logueado
        $session = session();

        // 2. Preparamos los datos orientados a la vista
        $data = [
            'nombre'    => $session->get('name'),
            'apellido'  => $session->get('last_name'),
            'email'     => $session->get('email'),
            'rol'       => $session->get('role')
        ];

        // 3. Retornamos la vista enviándole el arreglo de datos
        return view('cliente/mi_cuenta_view', $data);
    }
}
