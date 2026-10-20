<?php

namespace App\Models;

use CodeIgniter\Model;

class PagoModel extends Model
{
    protected $table            = 'pagos';
    protected $primaryKey       = 'id_pago';
    protected $returnType       = 'array';
    protected $allowedFields    = ['monto', 'moneda', 'metodo_pago', 'estado'];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'monto'       => 'required|numeric', // Cambiado a numeric porque en la migración es INT
        'moneda'      => 'required|alpha_space|max_length[20]', // Cambiado a texto con longitud máxima
        'metodo_pago' => 'required|max_length[20]', // Se añadió [20]
        'estado'      => 'required|max_length[20]', // Se añadió [20]
    ];

    protected $validationMessages = [
        'monto' => [
            'required' => 'El monto es obligatorio.',
            'numeric'  => 'El monto debe ser un número válido.'
        ],
        'moneda' => [
            'required'   => 'La moneda es obligatoria.',
            'max_length' => 'La moneda no puede superar los 20 caracteres.'
        ],
        'metodo_pago' => [
            'required'   => 'El método de pago es obligatorio.',
            'max_length' => 'El método de pago no puede superar los 20 caracteres.'
        ],
        'estado' => [
            'required'   => 'El estado es obligatorio.',
            'max_length' => 'El estado no puede superar los 20 caracteres.'
        ],
    ];
}
