<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateParticularsTable extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'particular_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'particular_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'opening_balance' => [
                'type'       => 'float',
            ],
            'debit' => [
                'type'       => 'float',
            ],
            'credit' => [
                'type'       => 'float',
            ],
            'particular_slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                // 'unique' => true,
            ],
            'particular_type' => [
                'type' => 'ENUM',
                'constraint' => ['System', 'Custom'],
                'default'    => 'System',
            ],
            'particular_status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Inactive'],
                'default'    => 'Active',
            ],
            'charged' => [
                'type' => 'enum',
                'constraint' => ['Yes', 'No'],
                'default' => 'No',
            ],
            'charge' => [
                'type' => 'double',
                'default' => null
            ],
            'charge_method' => [
                'type' => 'ENUM',
                'constraint' => ['Amount','Percent'],
                'default' => null
            ],
            'charge_mode' => [
                'type' => 'ENUM',
                'constraint' => ['Auto', 'Manual'],
                'default' => null
            ],
            'charge_frequency' => [
                'type' => 'ENUM',
                'constraint' => ['One-Time', 'Weekly', 'Bi-Weekly', 'Monthly', 'Bi-Monthly', 'Quarterly', 'Termly', 'Bi-Annual', 'Annually'],
                'default' => null
            ],
            'grace_period' => [
                'type' => 'int',
                'constraint' => 6,
                'null' => null
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'subcategory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'account_typeId' => [
                'type' => 'int',
                'constraint' => 11,
                'unsigned' => true
            ],
            'cash_flow_typeId' => [
                'type' => 'int',
                'constraint' => 11,
                'unsigned' => true
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id');
        $this->forge->addForeignKey('subcategory_id', 'subcategories', 'id');
        $this->forge->addForeignKey('account_typeId', 'account_types', 'id');
        $this->forge->addForeignKey('cash_flow_typeId', 'cash_flow_types', 'id');
        $this->forge->createTable('particulars', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('particulars', true);
    }
}
