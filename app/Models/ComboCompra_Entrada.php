<?php

namespace App\Models;

use CodeIgniter\Model;

class ComboCompraEntradaModel extends Model
{
    protected $table            = 'combo_compra_entrada';
    protected $primaryKey       = 'id_Combo_Compra_entra';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['estado_entrega', 'id_combo', 'id_compra'];
    protected $useTimestamps    = false;
}
