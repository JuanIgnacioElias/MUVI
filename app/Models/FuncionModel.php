<?php

namespace App\Models;

use CodeIgniter\Model;

class FuncionModel extends Model
{
    protected $table            = 'funciones';
    protected $primaryKey       = 'id_funcion';
    protected $returnType       = 'array';
    protected $allowedFields    = ['fecha', 'hora_ingreso', 'hora_salida', 'id_sala', 'id_pelicula'];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'fecha'        => 'required|valid_date',
        'hora_ingreso' => 'required',
        'hora_salida'  => 'required',
        'id_sala'      => 'required|is_natural_no_zero',
        'id_pelicula'  => 'required|is_natural_no_zero',
    ];
    
    protected $validationMessages = [
        'id_sala'     => ['is_natural_no_zero' => 'Debe seleccionar una sala válida.'],
        'id_pelicula' => ['is_natural_no_zero' => 'Debe seleccionar una película válida.'],
    ];
}
