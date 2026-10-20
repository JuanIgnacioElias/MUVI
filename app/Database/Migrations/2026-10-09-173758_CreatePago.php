<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePago extends Migration
{
    public function up()
    {
        $this->forge->addField([
    'id_pago' => [ 'type'=> 'INT','unsigned'=> true,'auto_increment' => true,],
            'monto' => ['type'=> 'INT','unsigned'=> true, ],
            'moneda' => ['type'=> 'VARCHAR','constraint' => '20',],
            'metodo_pago' => [ 'type'=> 'VARCHAR','constraint' => '20',],
            'estado' => ['type'=> 'VARCHAR','constraint' => '20',],
            'created_at' => ['type' => 'DATETIME','null' => true,],
            'updated_at' => ['type' => 'DATETIME','null' => true,],
        ]);
        $this->forge->addKey('id_pago', true); 
        $this->forge->createTable('pagos');   
    }
    public function down()
    {
        $this->forge->dropTable('pagos'); 
    }
}