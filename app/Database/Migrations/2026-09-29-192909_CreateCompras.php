<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCompras extends Migration
{
    public function up()
    {
        $this->forge->addField([
        'id_compra' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
        'total' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        'estado' => ['type' => 'VARCHAR', 'constraint' => 20],
        'fecha_compra' => ['type' => 'DATETIME', 'null' => true],
        'codigo_qr' => ['type' => 'VARCHAR', 'constraint' => 100], 
        'created_at' => ['type' => 'DATETIME', 'null' => true],
        'updated_at' => ['type' => 'DATETIME', 'null' => true],

        ]);
        $this->forge->addKey('id_compra', true);
        $this->forge->createTable('compras');
        }

    public function down()
    {
        $this->forge->dropTable('compras');
    }       
}
