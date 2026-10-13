<?php

namespace App\Models;

use CodeIgniter\Model;

class PeliculaModel extends Model
{
    protected $table            = 'peliculas';
    protected $primaryKey       = 'id_pelicula';
    protected $returnType       = 'array';
    protected $allowedFields    = ['titulo', 'sinopsis', 'duracion', 'clasificacion', 'trailer_url'];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'titulo'        => 'required|max_length[40]',
        'sinopsis'      => 'permit_empty',
        'duracion'      => 'required|is_natural_no_zero',
        'clasificacion' => 'required|max_length[40]',
        'trailer_url'   => 'permit_empty|valid_url',
    ];

    protected $validationMessages = [
        'titulo'        => ['max_length' => 'El título no puede superar los 40 caracteres.'],
        'duracion'      => ['is_natural_no_zero' => 'La duración debe ser un número de minutos válido.'],
        'clasificacion' => ['max_length' => 'La clasificación no puede superar los 40 caracteres.'],
        'trailer_url'   => ['valid_url' => 'Debe ingresar una URL válida para el tráiler.'],
    ];
}
