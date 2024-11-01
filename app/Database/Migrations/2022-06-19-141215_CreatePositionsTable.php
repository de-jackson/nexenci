<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePositionsTable extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => "INT",
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'department_id' => [
                'type' => "INT",
                'constraint' => 11,
                'unsigned' => true,
            ],
            'position' =>[
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'position_slug' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'position_status' => [
                'type' => 'enum',
                'constraint' => ['Active', 'Inactive'],
                'default' => 'Active',
            ],
            'permissions' => [
                'type' => 'text',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null'
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('department_id', 'departments', 'id');
        $this->forge->createTable('positions', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('positions', true);
    }
}
