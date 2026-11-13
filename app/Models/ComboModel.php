<?php

namespace App\Models;

use CodeIgniter\Model;

class ComboModel extends Model
{
    protected $table            = 'combo';
    protected $primaryKey       = 'id_combo';
    protected $returnType       = 'array';
    protected $allowedFields    = ['comida', 'bebida', 'precio'];
    protected $useTimestamps    = true;
    protected $validationRules = [
        'comida' => 'required|max_length[20]',
        'bebida' => 'required|max_length[20]',
        'precio' => 'required|numeric|greater_than_equal_to[0]',
    ];
    protected $validationMessages = [
        'comida' => [
            'required'   => 'El campo comida es obligatorio.',
            'max_length' => 'La descripción de la comida no puede superar los 20 caracteres.'
        ],
        'bebida' => [
            'required'   => 'El campo bebida es obligatorio.',
            'max_length' => 'La descripción de la bebida no puede superar los 20 caracteres.'
        ],
        'precio' => [
            'required'              => 'El precio es obligatorio.',
            'numeric'               => 'El precio debe ser un número.',
            'greater_than_equal_to' => 'El precio no puede ser negativo.'
        ],
    ];
}
