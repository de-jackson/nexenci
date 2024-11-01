<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMenuTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
                'unsigned' => true
            ],
            'order' => [
                'type' => 'INT',
                'constraint' => 6
            ],
            'parent_id' => [
                'type' => 'INT',
                'constraint' => 6,
                'default' => 0,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 150
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 25
            ],
            'menu' => [
                'type' => 'VARCHAR',
                'constraint' => 20
            ],
            'url' => [
                'type' => 'text',
            ],
            'accounts' => [
                'type' => 'text',
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => 150
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Active','Inactive'],
                'default' => 'Active'
            ],
            'create' => [
                'type' => 'enum',
                'constraint' => ['on', 'off'],
                'default' => null
            ],
            'import' => [
                'type' => 'enum',
                'constraint' => ['on', 'off'],
                'default' => null
            ],
            'view' => [
                'type' => 'enum',
                'constraint' => ['on', 'off'],
                'default' => null
            ],
            'update' => [
                'type' => 'enum',
                'constraint' => ['on', 'off'],
                'default' => null
            ],
            'delete' => [
                'type' => 'enum',
                'constraint' => ['on', 'off'],
                'default' => null
            ],
            'bulkDelete' => [
                'type' => 'enum',
                'constraint' => ['on', 'off'],
                'default' => null
            ],
            'export' => [
                'type' => 'enum',
                'constraint' => ['on', 'off'],
                'default' => null
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('menus', true);
    }

    public function down()
    {
        $this->forge->dropTable('menus', true);
    }
}
