<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateButaca extends Migration
{
    public function up()
    {
        $this->forge->addField([
        'id_butaca' => ['type'=> 'INT','unsigned'=> true,'auto_increment' => true,],
        'posicionX' => ['type'=> 'VARCHAR','constraint' => '2',],
        'posicionY' => ['type'=> 'INT',],
            'disponibilidad' => ['type'=> 'BOOLEAN',],
            'id_compra' => ['type'=> 'INT','unsigned'=> true, ],
            'id_sala' => ['type'=> 'INT','unsigned'   => true,],
            'created_at' => ['type' => 'DATETIME','null' => true,],
            'updated_at' => ['type' => 'DATETIME','null' => true,],
        ]);

        $this->forge->addKey('id_butaca', true);
        $this->forge->addForeignKey('id_compra', 'compras', 'id_compra', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_sala', 'salas', 'id_sala', 'CASCADE', 'CASCADE');
        $this->forge->createTable('butacas');
    }
    public function down()
    {
        $this->forge->dropTable('butacas');
    }
}
