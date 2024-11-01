<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CashFlowTypes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'varchar',
                'constraint' => 30,
            ],
            'status' => [
                'type' => 'enum',
                'constraint' => ['Active', 'Inactive'],
                'default' => 'Active'
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('cash_flow_types', true);
    }

    public function down()
    {
        $this->forge->dropTable('cash_flow_types', true);
    }
}
