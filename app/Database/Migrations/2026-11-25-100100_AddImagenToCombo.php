<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImagenToCombo extends Migration
{
    public function up()
    {
        $this->forge->addColumn('combo', [
            // Ruta relativa a /public, p. ej. "uploads/combos/abc123.jpg"
            'imagen' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'precio'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('combo', 'imagen');
    }
}
