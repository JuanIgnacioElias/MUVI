<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCompraButaca extends Migration
{
    public function up(): void
    {
        $this->forge->addField(['id_Compra_Butaca' => ['type'=> 'INT','constraint'=> 11,'unsigned'=> false, 'auto_increment' => true,
    ],
    'id_compra' => ['type'=> 'INT', 'constraint'     => 11,
    ],
    'id_butaca' => [ 'type'=> 'INT','constraint'     => 11,
    ],
        ]);

        // Definir Llave Primaria
        $this->forge->addKey('id_Compra_Butaca', true);

        // Definir Llave Foránea hacia compra_entrada
        $this->forge->addForeignKey('id_compra', 'compra_entrada', 'id_compra', 'CASCADE', 'CASCADE');

        // Crear la tabla
        $this->forge->createTable('compra_butaca');
    }

    public function down(): void
    {
        $this->forge->dropTable('compra_butaca');
    }
}
