<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use phpDocumentor\Reflection\Types\Null_;

class CreateBranches extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'branch_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'branch_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'branch_mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'alternate_mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'branch_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'branch_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'branch_status' => [
                'type'       => 'enum',
                'constraint' => ['Active','Inactive'],
                'default' => 'Active',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('branches', true);
    }

    public function down()
    {
        $this->forge->dropTable('branches', true);
    }
}
