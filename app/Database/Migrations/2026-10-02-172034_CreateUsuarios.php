<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
        'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
        'name' => ['type' => 'VARCHAR', 'constraint' => 100],
        'last_name' => ['type' => 'VARCHAR', 'constraint' => 100],
        'email' => ['type' => 'VARCHAR', 'constraint' => 150],
        'password' => ['type' => 'VARCHAR', 'constraint' => 255],
        'cuil' => ['type' => 'VARCHAR', 'constraint' => 11],
        'role' => ['type'=> 'ENUM', 'constraint' => ['CLIENTE', 'ADMINISTRADOR', 'EMPLEADO'], 'default' => 'CLIENTE'],
        'created_at' => ['type' => 'DATETIME', 'null' => true],
        'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('cuil');
        $this->forge->createTable('users');
        }
        public function down()
        {
        $this->forge->dropTable('users');
        }
       }       