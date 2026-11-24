<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategorias extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_categoria' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre'       => ['type' => 'VARCHAR', 'constraint' => 40],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_categoria', true);
        $this->forge->addUniqueKey('nombre');
        $this->forge->createTable('categorias');

        // Categorías iniciales. El administrador puede crear más desde el
        // formulario de películas.
        $ahora = date('Y-m-d H:i:s');
        $this->db->table('categorias')->insertBatch(
            array_map(
                static fn (string $nombre) => [
                    'nombre'     => $nombre,
                    'created_at' => $ahora,
                    'updated_at' => $ahora,
                ],
                ['Acción', 'Aventura', 'Animación', 'Comedia', 'Drama', 'Terror', 'Ciencia ficción']
            )
        );
    }

    public function down()
    {
        $this->forge->dropTable('categorias');
    }
}
