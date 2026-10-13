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
        'monto'       => 'required|decimal',
        'moneda'      => 'required|decimal',
        'metodo_pago' => 'required|max_length',
        'estado'      => 'required|max_length',
    ];

    protected $validationMessages = [
        'monto'       => ['decimal' => 'El monto debe ser un valor decimal válido.'],
        'moneda'      => ['decimal' => 'La moneda debe ser un valor decimal válido.'],
        'metodo_pago' => ['max_length' => 'El método de pago no puede superar los 20 caracteres.'],
        'estado'      => ['max_length' => 'El estado no puede superar los 20 caracteres.'],
    ];
}
