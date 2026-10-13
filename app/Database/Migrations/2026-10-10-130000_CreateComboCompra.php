<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateComboCompra extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_compra' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_combo' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'estado_entrega' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey(['id_compra', 'id_combo'], true);
        $this->forge->addForeignKey(
            'id_compra',       // Campo local
            'compras',         // Tabla destino
            'id',                  // Campo destino
            'CASCADE',             // Al actualizar (onUpdate)
            'CASCADE'              // Al eliminar (onDelete)
        );
        
        $this->forge->addForeignKey(
            'id_combo',            // Campo local
            'combo',              // Tabla destino
            'id',                  // Campo destino
            'CASCADE',             // Al actualizar
            'CASCADE'              // Al eliminar
        );

        $this->forge->createTable('ComboCompra');
    }

    public function down()
    {

        $this->forge->dropTable('ComboCompra');
    }
}
