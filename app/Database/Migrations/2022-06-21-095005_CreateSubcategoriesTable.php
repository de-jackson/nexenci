<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubcategoriesTable extends Migration
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
            'subcategory_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'subcategory_slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                // 'unique' => true,
            ],
            'subcategory_type' => [
                'type' => 'ENUM',
                'constraint' => ['System', 'Custom'],
                'default'    => 'System',
            ],
            'subcategory_status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Inactive'],
                'default'    => 'Active',
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id');
        $this->forge->createTable('subcategories', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('subcategories', true);
    }
}
