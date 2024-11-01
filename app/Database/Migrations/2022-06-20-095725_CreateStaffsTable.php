<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffsTable extends Migration
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
            'staffID' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'staff_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'alternate_mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'gender' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'marital_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'religion' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nationality' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'date_of_birth' => [
                'type'       => 'date',
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'account_type' => [
                'type' => 'ENUM',
                'constraint' => ['Employee', 'Administrator', 'Super'],
                'default' => 'Employee',
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'position_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'officer_staff_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'id_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'id_number' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
            ],
            'id_expiry_date' => [
                'type' => 'date',
                'null' => true,
            ],
            'qualifications' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'salary_scale' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'bank_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'bank_branch' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'bank_account' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'appointment_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'id_photo_front' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'id_photo_back' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'signature' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'access_status' => [
                'type'       => 'enum',
                'constraint' => ['Active','Inactive'],
                'default' => 'Active',
            ],
            'reg_date' => [
                'type'       => 'date',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('branch_id', 'branches', 'id');
        $this->forge->addForeignKey('position_id', 'positions', 'id');
        $this->forge->addForeignKey('officer_staff_id', 'staffs', 'id');
        $this->forge->createTable('staffs');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('staffs');
    }
}
