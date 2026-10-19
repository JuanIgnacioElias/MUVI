<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComboCompraEntrada extends Migration
{
    public function up(): void
    {
    $this->forge->addField(['id_Combo_Compra_entra' => ['type'=> 'INT','constraint'=> 11,'auto_increment' => true,
            ],
    'estado_entrega' => ['type'=> 'VARCHAR','constraint' => 20,
            ],
    'id_combo' => ['type'=> 'INT','constraint'=> 11,
            ],
            'id_compra' => ['type'=> 'INT','constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id_Combo_Compra_entra', true);
        $this->forge->addForeignKey('id_compra', 'compra_entrada', 'id_compra', 'CASCADE', 'CASCADE');

        $this->forge->createTable('combo_compra_entrada');
    }

    public function down(): void
    {
        $this->forge->dropTable('combo_compra_entrada');
    }
}
