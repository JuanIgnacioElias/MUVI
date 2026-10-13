<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateCompraButaca extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_compra' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_butaca' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey(['id_compra', 'id_butaca'], true);
        $this->forge->addForeignKey(
            'id_compra',       // Campo local
            'compras',         // Tabla destino
            'id',                  // Campo destino
            'CASCADE',             // Al actualizar (onUpdate)
            'CASCADE'              // Al eliminar (onDelete)
        );
        $this->forge->addForeignKey(
            'id_butaca',            // Campo local
            'butaca',              // Tabla destino
            'id',                  // Campo destino
            'CASCADE',             // Al actualizar
            'CASCADE'              // Al eliminar
        );
        $this->forge->createTable('CompraButaca');
    }
    public function down()
    {
        $this->forge->dropTable('CompraButaca');
    }
}
