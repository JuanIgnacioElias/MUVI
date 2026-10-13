<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCombo extends Migration
{
    public function up()
    {
        $this->forge->addField(['id_combo' => ['type'=> 'INT','unsigned'=> true,'auto_increment' => true,],
            'comida' => ['type'=> 'VARCHAR','constraint' => '20',],
            'bebida' => ['type'=> 'VARCHAR','constraint' => '20',],
            'created_at' => ['type' => 'DATETIME','null' => true,],
            'updated_at' => ['type' => 'DATETIME','null' => true,],
        ]);
 $this->forge->addKey('id_combo', true);
$this->forge->createTable('combo');
    }
    public function down()
    {
        $this->forge->dropTable('combo');
    }
}
