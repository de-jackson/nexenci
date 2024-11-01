<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRelationshipsTable extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'contact' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'alternate_contact' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'relationship' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'occupation' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'type' => [
                'type' => 'enum',
                'constraint' => ['next_of_kin', 'guarantor'],
                'default'    => null,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'application_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => null,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('client_id', 'clients', 'id');
        $this->forge->addForeignKey('application_id', 'loanapplications', 'id');
        $this->forge->createTable('relationships', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('relationships', true);

    }
}
