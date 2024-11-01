<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChargesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'charge_method' => [
                'type' => 'ENUM("Amount", "Percent")',
                'null' => true,
            ],
            'charge' => [
                'type' => 'double',
                'null' => true,
            ],
            'charge_mode' => [
                'type' => 'ENUM("Auto", "Manual")',
                'null' => true,
            ],
            'frequency' => [
                'type' => 'ENUM("One-Time", "Daily", "Weekly", "Monthly", "Annually")',
                'null' => true,
            ],
            'effective_date' => [
                'type' => 'date',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM("Active", "Inactive")',
                'default' => 'Inactive',
            ],
            'particular_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('particular_id', 'particulars', 'id');
        $this->forge->addForeignKey('product_id', 'loanproducts', 'id');
        $this->forge->createTable('charges');
    }

    public function down()
    {
        $this->forge->dropTable('charges');
    }
}
