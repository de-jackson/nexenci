<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserLogsTable extends Migration
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
            'loginfo' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'login_at datetime default current_timestamp',
            'logout_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'duration' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default' => null,
            ],
            'browser' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default' => null,
            ],
            'browser_version' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default' => null,
            ],
            'operating_system' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default' => null,
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default' => null,
            ],
            'latitude' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default' => null,
            ],
            'longitude' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default' => null,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['online', 'offline'],
                'default' => 'online',
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'referrer_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => NULL,
            ],
            'account' => [
                'type' => 'ENUM',
                'constraint' => ['Administrator', 'Employee', 'Client'],
                'default' => NULL,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->addForeignKey('client_id', 'clients', 'id');
        $this->forge->createTable('userlogs');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('userlogs', true);
    }
}
