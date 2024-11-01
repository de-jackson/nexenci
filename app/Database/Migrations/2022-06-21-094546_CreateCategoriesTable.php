<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriesTable extends Migration
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
            'category_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique' => true,
            ],
            'category_slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                // 'unique' => true,
            ],
            'part' => [
                'type' => 'ENUM',
                'constraint' => ['debit', 'credit'],
                'null' => true,
            ],
            'statement_id' => [
                'type' => 'int',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'category_type' => [
                'type' => 'ENUM',
                'constraint' => ['System', 'Custom'],
                'default'    => 'System',
            ],
            'bring_forward' => [
                'type' => 'ENUM',
                'constraint' => ['Yes', 'No'],
                'default'    => 'No',
            ],
            'category_status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Inactive'],
                'default'    => 'Active',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('statement_id','statements', 'id');
        $this->forge->createTable('categories', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('categories', true);
    }
}
