<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table         = 'categorias';
    protected $primaryKey    = 'id_categoria';
    protected $returnType    = 'array';
    protected $allowedFields = ['nombre'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'nombre' => 'required|max_length[40]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre de la categoría es obligatorio.',
            'max_length' => 'El nombre de la categoría no puede superar los 40 caracteres.',
        ],
    ];
}
