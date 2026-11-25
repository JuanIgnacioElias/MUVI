<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdUsuarioToPagos extends Migration
{
    public function up()
    {
        // Nulo para los pagos viejos (se guardaron sin usuario) y si el usuario se elimina.
        $this->forge->addColumn('pagos', [
            'id_usuario' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'estado'],
        ]);

        $this->db->query(
            'ALTER TABLE `pagos`
             ADD CONSTRAINT `fk_pagos_usuario`
             FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
             ON DELETE SET NULL ON UPDATE CASCADE'
        );
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `pagos` DROP FOREIGN KEY `fk_pagos_usuario`');
        $this->forge->dropColumn('pagos', 'id_usuario');
    }
}
