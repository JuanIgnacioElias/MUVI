<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * - La categoría de la película pasa a ser un simple texto (columna "categoria"),
 *   igual que la clasificación. Se elimina la tabla "categorias".
 * - Se agrega "estado" (ESTRENO / PROXIMAMENTE) para separar el catálogo.
 */
class PeliculasCategoriaTextoYEstado extends Migration
{
    public function up()
    {
        $this->forge->addColumn('peliculas', [
            'categoria' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true, 'after' => 'clasificacion'],
            // Las películas que ya estaban cargadas quedan "en estreno".
            'estado'    => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false, 'default' => 'ESTRENO', 'after' => 'categoria'],
        ]);

        // Conservamos las categorías que ya tenían asignadas, pasadas a texto.
        if ($this->db->tableExists('categorias') && $this->db->fieldExists('id_categoria', 'peliculas')) {
            $this->db->query(
                'UPDATE `peliculas` p
                 JOIN `categorias` c ON c.`id_categoria` = p.`id_categoria`
                 SET p.`categoria` = c.`nombre`'
            );
            $this->db->query('ALTER TABLE `peliculas` DROP FOREIGN KEY `fk_peliculas_categoria`');
            $this->forge->dropColumn('peliculas', 'id_categoria');
        }

        $this->forge->dropTable('categorias', true);
    }

    public function down()
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

        $this->db->query(
            'INSERT INTO `categorias` (`nombre`, `created_at`, `updated_at`)
             SELECT DISTINCT `categoria`, NOW(), NOW() FROM `peliculas`
             WHERE `categoria` IS NOT NULL AND `categoria` <> \'\''
        );

        $this->forge->addColumn('peliculas', [
            'id_categoria' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'imagen'],
        ]);
        $this->db->query(
            'UPDATE `peliculas` p
             JOIN `categorias` c ON c.`nombre` = p.`categoria`
             SET p.`id_categoria` = c.`id_categoria`'
        );
        $this->db->query(
            'ALTER TABLE `peliculas`
             ADD CONSTRAINT `fk_peliculas_categoria`
             FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`)
             ON DELETE SET NULL ON UPDATE CASCADE'
        );

        $this->forge->dropColumn('peliculas', ['categoria', 'estado']);
    }
}
