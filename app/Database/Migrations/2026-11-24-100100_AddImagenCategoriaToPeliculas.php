<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImagenCategoriaToPeliculas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('peliculas', [
            // Ruta relativa a /public, p. ej. "uploads/peliculas/abc123.jpg"
            'imagen'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'trailer_url'],
            'id_categoria' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'imagen'],
        ]);

        // Si se borra una categoría, las películas quedan "sin categoría".
        $this->db->query(
            'ALTER TABLE `peliculas`
             ADD CONSTRAINT `fk_peliculas_categoria`
             FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`)
             ON DELETE SET NULL ON UPDATE CASCADE'
        );
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `peliculas` DROP FOREIGN KEY `fk_peliculas_categoria`');
        $this->forge->dropColumn('peliculas', ['imagen', 'id_categoria']);
    }
}
