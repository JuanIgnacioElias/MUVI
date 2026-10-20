<?php

namespace App\Models;

use CodeIgniter\Model;

class ComboCompraModel extends Model
{
    protected $table = 'ComboCompra'; 
    protected $primaryKey       = 'id_compra'; 
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_compra', 'id_combo', 'estado_entrega'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; 

    protected $validationRules = [
        'id_compra'      => 'required|integer',
        'id_combo'       => 'required|integer',
        'estado_entrega' => 'required|max_length[20]',
    ];
    protected $validationMessages = [
        'id_compra' => [
            'required' => 'El ID de la compra es obligatorio.',
            'integer'  => 'El ID de la compra debe ser un número entero.'
        ],
        'id_combo' => [
            'required' => 'El ID del combo es obligatorio.',
            'integer'  => 'El ID del combo debe ser un número entero.'
        ],
        'estado_entrega' => [
            'required'   => 'El estado de entrega es obligatorio.',
            'max_length' => 'El estado de entrega no puede superar los 20 caracteres.'
        ],
    ];
}
