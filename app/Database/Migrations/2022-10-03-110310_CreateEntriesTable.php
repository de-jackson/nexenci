<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEntriesTable extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
                'auto_increment'=>true,
            ],
            'date' => [
                'type' => 'date',
                'default' => null,
            ],
            'particular_id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
            ],
            'payment_id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
            ],
            'branch_id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
            ],
            'staff_id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
            ],
            'client_id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
                'null'=> true,
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                // 'unsigned' => true,
                'null' => true,
            ],
            'application_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'disbursement_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'account_typeId' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'entry_typeId' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'amount' =>[
                'type' => 'double',
            ],
            'ref_id' => [
                'type'=>'varchar',
                'constraint'=>20,
            ],
            'entry_menu' => [
                'type'=>'enum',
                'constraint'=>['financing', 'expense', 'transfer', 'investment'],
                'default' => 'financing'
            ],
            'entry_details' => [
                'type'=>'text',
            ],
            'contact' =>[
                'type' => 'varchar',
                'constraint'=>20,
            ],
            'status' =>[
                'type' => 'enum',
                'constraint'=>['debit', 'credit'],
                'default' => null,
            ],
            'balance' =>[
                'type' => 'double',
            ],
            'remarks' =>[
                'type' => 'text',
                'default' => 'none'
            ],
            'transaction_reference' => [
                'type'=>'varchar',
                'constraint'=>100,
            ],
            'parent_id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
                'default' => null,
            ],
            'account_id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
                'default' => null,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null'
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('payment_id','particulars','id');
        $this->forge->addForeignKey('particular_id','particulars','id');
        $this->forge->addForeignKey('entry_typeId','entrytypes','id');
        $this->forge->addForeignKey('account_typeId','account_types','id');
        $this->forge->addForeignKey('branch_id','branches','id');
        $this->forge->addForeignKey('staff_id','staffs','id');
        $this->forge->addForeignKey('client_id','clients','id');
        $this->forge->addForeignKey('application_id','loanapplications','id');
        $this->forge->addForeignKey('disbursement_id','disbursements','id');
        $this->forge->addForeignKey('parent_id','entries','id');
        $this->forge->createTable('entries');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('entries');
    }
}
