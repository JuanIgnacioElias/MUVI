<?php

namespace App\Controllers;

class EmpleadoController extends BaseController
{
    public function index()
    {
        return view('empleado/panel');
    }
}