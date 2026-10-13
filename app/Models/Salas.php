<?php

namespace App\Models;

use CodeIgniter\Model;

class SalaModel extends Model
{
    protected $table            = 'salas';
    protected $primaryKey       = 'id_sala';
    protected $returnType       = 'array';
    protected $allowedFields    = ['nombre', 'capacidad', 'tipo_sala'];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'nombre'    => 'required|max_length',
        'capacidad' => 'required|is_natural_no_zero',
        'tipo_sala' => 'required|decimal',
    ];

    protected $validationMessages = [
        'nombre'    => ['max_length' => 'El nombre de la sala no puede superar los 20 caracteres.'],
        'capacidad' => ['is_natural_no_zero' => 'La capacidad debe ser un número entero mayor a cero.'],
        'tipo_sala' => ['decimal' => 'El tipo de sala debe ser un valor decimal válido.'],
    ];
}
