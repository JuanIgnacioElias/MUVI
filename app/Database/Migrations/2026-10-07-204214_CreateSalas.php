<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_sala'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre'     => ['type' => 'VARCHAR', 'constraint' => 20],
            'capacidad'  => ['type' => 'SMALLINT', 'unsigned' => true],
            'tipo_sala'  => ['type' => 'DECIMAL', 'constraint' => '10,2'], 
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_sala', true);
        $this->forge->createTable('salas');
    }

    public function down()
    {
        $this->forge->dropTable('salas');
    }
}