<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApplicationRemarks extends Migration
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
            'application_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'staff_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'status' => [
                'type' => 'enum',
                'constraint' => ['Pending', 'Processing', 'Declined', 'Approved', 'Disbursed','Cancelled'],
            ],
            'level' => [
                'type' => 'enum',
                'constraint' => ['Loan Officer', 'Loan Committee', 'Credit Officer'],
                'default' => 'Loan Officer',
            ],
            'action' => [
                'type' => 'enum',
                'constraint' => ['Processing', 'Review', 'Approved', 'Disbursed', 'Declined'],
                'default'    => null,
            ],
            'remarks' => [
                'type' => 'text',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('application_id', 'loanapplications', 'id');
        $this->forge->addForeignKey('staff_id', 'staffs', 'id');
        $this->forge->createTable('applicationremarks', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('applicationremarks', true);
    }
}
