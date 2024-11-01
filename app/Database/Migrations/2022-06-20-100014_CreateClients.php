<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClients extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'staff_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'account_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'account_type' => [
                'type' => 'ENUM',
                'constraint' => ['Client', 'Stakeholder', 'Non Stakeholder', 'Exited'],
                'default' => 'Client',
            ],
            'savings_product' => [
                'type' => 'text',
                'default' => null
            ],
            'account_balance' => [
                'type' => 'double',
                'constraint' => [10, 2]
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                // 'unique' => true
            ],
            'mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'alternate_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'gender' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'dob' => [
                'type' => 'date',
                'null' => true,
            ],
            'religion' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'nationality' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'marital_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'occupation' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'job_location' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'residence' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'id_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'id_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'id_expiry_date' => [
                'type' => 'date',
                'null' => true,
            ],
            'next_of_kin_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'next_of_kin_relationship' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'next_of_kin_contact' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'next_of_kin_alternate_contact' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nok_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nok_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'id_photo_front' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'id_photo_back' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'token_expire_date' => [
                'type' => 'datetime',
                'null' => true,
            ],
            '2fa' => [
                'type' => 'ENUM',
                'constraint' => ['True', 'False'],
                'default' => 'False',
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'token_expire_date' => [
                'type' => 'datetime',
                'null' => true,
            ],
            '2fa' => [
                'type' => 'ENUM',
                'constraint' => ['True', 'False'],
                'default' => 'False',
            ],
            'signature' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'account' => [
                'type'       => 'enum',
                'constraint' => ['Pending', 'Approved', 'Declined'],
                'default' => 'Approved',
                'null' => true,
            ],
            'account' => [
                'type'       => 'enum',
                'constraint' => ['Pending', 'Approved', 'Declined'],
                'default' => 'Approved',
            ],
            'access_status' => [
                'type'       => 'enum',
                'constraint' => ['Active', 'Inactive'],
                'constraint' => ['Active', 'Inactive'],
                'default' => 'Active',
            ],
            'reg_date' => [
                'type'       => 'date',
                'null' => true,
            ],
            'reg_date' => [
                'type'       => 'date',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('branch_id', 'branches', 'id');
        $this->forge->addForeignKey('staff_id', 'staffs', 'id');
        $this->forge->createTable('clients', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('clients', true);
    }
}
