<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDepartmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => "INT",
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'department_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'department_slug' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'department_status' => [
                'type'       => 'enum',
                'constraint' => ['Active','Inactive'],
                'default' => 'Active',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('departments', true);
    }

    public function down()
    {
        $this->forge->dropTable('departments', true);
    }
}
