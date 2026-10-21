<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFunciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_funcion'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'fecha'        => ['type' => 'DATE'],
            'hora_ingreso' => ['type' => 'TIME'],
            'hora_salida'  => ['type' => 'TIME'],
            'id_sala'      => ['type' => 'INT', 'unsigned' => true],
            'id_pelicula'  => ['type' => 'INT', 'unsigned' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_funcion', true);
        $this->forge->addForeignKey('id_sala', 'salas', 'id_sala', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_pelicula', 'peliculas', 'id_pelicula', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('funciones');
    }

    public function down()
    {
        $this->forge->dropTable('funciones');
    }
}