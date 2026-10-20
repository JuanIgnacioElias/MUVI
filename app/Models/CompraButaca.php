<?php

namespace App\Models;

use CodeIgniter\Model;

class CompraButacaModel extends Model
{
    protected $table            = 'compra_butaca';
    protected $primaryKey       = 'id_Compra_Butaca';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id_compra', 'id_butaca'];
    protected $useTimestamps    = false; 
}
