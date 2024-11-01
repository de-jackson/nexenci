<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StatementsTable extends Migration
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
                'constraint' => 25,
            ],
            'code' => [
                'type' => 'varchar',
                'constraint' => 10,
                'unique' => true,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('statements', true);
    }

    public function down()
    {
        $this->forge->dropTable('statements', true);
    }
}
