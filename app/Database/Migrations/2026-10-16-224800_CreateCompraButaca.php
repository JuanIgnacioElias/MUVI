<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateCompraButaca extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_Compra_Butaca' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_compra' => [
                'type'           => 'INT',
                'unsigned'       => true, // Corregido: Agregado unsigned
            ],
            'id_butaca' => [
                'type'           => 'INT',
                'unsigned'       => true, // Corregido: Agregado unsigned
            ],
        ]);

        $this->forge->addKey('id_Compra_Butaca', true);
        $this->forge->addForeignKey(
            'id_compra', 
            'compras', 
            'id_compra', 
            'CASCADE', 
            'CASCADE'
        );
        $this->forge->addForeignKey(
            'id_butaca', 
            'butacas', 
            'id_butaca', 
            'CASCADE', 
            'CASCADE'
        );
        $this->forge->createTable('compra_butaca');
    }
    public function down()
    {
        $this->forge->dropTable('compra_butaca');
    }
}