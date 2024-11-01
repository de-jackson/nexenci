<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApiRequestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'url' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'method' => [
                'type' => 'ENUM("GET", "POST", "PUT", "PATCH", "DELETE")'
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM("PENDING","SUCCESS", "FAILED")'
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'input' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'output' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'uuid' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME'
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('api_requests');
    }

    public function down()
    {
        $this->forge->dropTable('api_requests');
    }
}
