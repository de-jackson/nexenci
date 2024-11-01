<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFilesTable extends Migration
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
            'application_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['collateral','income','expense'],
                'defualt' => 'collateral'
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('application_id', 'loanapplications', 'id');
        $this->forge->createTable('files', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        # $this->forge->dropForeignKey('files','application_id', true);
        $this->forge->dropTable('files', true);
    }
}
