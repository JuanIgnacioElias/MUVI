<?php

namespace App\Models;

use CodeIgniter\Model;

class ButacaModel extends Model
{
    protected $table            = 'butacas';
    protected $primaryKey       = 'id_butaca';
    protected $returnType       = 'array';
    // Si usás eliminación suave (soft delete), ponelo en true (tu migración no lo tiene)
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'posicionX', 
        'posicionY', 
        'disponibilidad', 
        'id_entrada', 
        'id_sala'
    ];

    // Fechas automáticas (CodeIgniter manejará created_at y updated_at por vos)
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
}
