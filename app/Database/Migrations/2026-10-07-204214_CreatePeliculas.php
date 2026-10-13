<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeliculas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pelicula'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'titulo'        => ['type' => 'VARCHAR', 'constraint' => 40],
            'sinopsis'      => ['type' => 'TEXT', 'null' => true],
            'duracion'      => ['type' => 'INT', 'unsigned' => true],
            'clasificacion' => ['type' => 'VARCHAR', 'constraint' => 40],
            'trailer_url'   => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_pelicula', true);
        $this->forge->createTable('peliculas');
    }

    public function down()
    {
        $this->forge->dropTable('peliculas');
    }
}