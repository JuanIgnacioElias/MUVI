<?php
namespace App\Models;
use CodeIgniter\Model;
class CompraModel extends Model
{
 protected $table = 'compras';
 protected $primaryKey = 'id_compra';
 protected $returnType = 'array';
 protected $allowedFields = ['total', 'estado', 'fecha_compra', 'codigo_qr'];
 protected $useTimestamps = true;
 protected $validationRules = [
 'total' => 'required|decimal|greater_than_equal_to[0]',
 ];
 protected $validationMessages = [
 'total' => ['greater_than_equal_to' => 'El precio no puede ser negativo.'],
 ];
}