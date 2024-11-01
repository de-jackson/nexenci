<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCurrenciesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
                'unsigned' => true,
            ],
            'currency' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'symbol' => [
                'type' => 'VARCHAR',
                'constraint' => 40,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Inactive'],
                'default' => 'Active',
            ]
            ,'created_at datetime default current_timestamp'
            ,'updated_at datetime default current_timestamp on update current_timestamp'
            ,'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('currencies', true);
    }

    public function down()
    {
        $this->forge->dropTable('currencies', true);

    }
}
