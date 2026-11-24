<?php

namespace App\Controllers;

use App\Models\PeliculaModel;

class Home extends BaseController
{
    public function index(): string
    {
        // Últimas películas cargadas por el administrador.
        $peliculas = (new PeliculaModel())
            ->orderBy('created_at', 'DESC')
            ->findAll(10);

        return view('home', ['peliculas' => $peliculas]);
    }
}
