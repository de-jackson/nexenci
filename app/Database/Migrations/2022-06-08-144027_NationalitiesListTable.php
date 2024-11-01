<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NationalitiesListTable extends Migration
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
            'country_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'nationality' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'alpha_2_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'alpha_3_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'num_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ]
            ,'created_at datetime default current_timestamp'
            ,'updated_at datetime default current_timestamp on update current_timestamp'
            ,'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('nationalitieslist');
    }

    public function down()
    {
        $this->forge->dropTable('nationalitieslist');
    }
}
