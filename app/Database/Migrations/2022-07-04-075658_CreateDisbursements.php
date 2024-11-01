<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDisbursements extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
                'unsigned' => true,
            ],
            'disbursement_code' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
            ],
            'cycle' => [
                'type' => 'INT',
                'constraint' => 6 ,
            ],
            'application_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'staff_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'particular_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'payment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'disbursed_by' => [
                'type' => 'enum',
                'constraint' => ['Deposited into Client Account', 'Paid in Cash'],
                'default' => 'Deposited into Client Account',
            ],
            'principal' => [
                'type' => 'double',
            ],
            'computed_interest' => [
                'type' => 'double',
            ],
            'actual_interest' => [
                'type' => 'double',
            ],
            'computed_installment' => [
                'type' => 'double',
            ],
            'actual_installment' => [
                'type' => 'double',
            ],
            'principal_installment' => [
                'type' => 'double',
            ],
            'interest_installment' => [
                'type' => 'double',
            ],
            'computed_repayment' => [
                'type' => 'double',
            ],
            'actual_repayment' => [
                'type' => 'double',
            ],
            'loan_period_days' => [
                'type' => 'INT',
                'constraint' => 6,
            ],
            'days_covered' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'days_remaining' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
            ],
            'grace_period' =>[
                'type' => 'INT',
                'constraint' => 6,
            ],
            'installments_num' => [
                'type' => 'INT',
                'constraint' => 6,
            ],
            'installments_covered' => [
                'type' => 'INT',
                'constraint' => 6,
            ],
            'first_recovery' => [
                'type' => 'date',
            ],
            'loan_expiry_date' => [
                'type' => 'date',
            ],
            'expiry_day' => [
                'type' => 'varchar',
                'constraint' => 10,
            ],
            'expected_interest_recovered' => [
                'type' => 'double',
            ],
            'expected_principal_recovered' => [
                'type' => 'double',
            ],
            'expected_amount_recovered' => [
                'type' => 'double',
            ],
            'expected_loan_balance' => [
                'type' => 'double',
            ],
            'interest_collected' => [
                'type' => 'double',
            ],
            'principal_collected' => [
                'type' => 'double',
            ],
            'total_collected' => [
                'type' => 'double',
            ],
            'interest_balance' => [
                'type' => 'double',
            ],
            'principal_balance' => [
                'type' => 'double',
            ],
            'total_balance' => [
                'type' => 'double',
            ],
            'arrears' => [
                'type' => 'double',
            ],
            'arrears_info' => [
                'type' => 'text',
                'default' => null,
            ],
            'principal_due' => [
                'type' => 'double',
            ],
            'interest_due' => [
                'type' => 'double',
            ],
            'installments_due' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
                "default" => " - ",
            ],
            'days_due' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                "default" => " - ",
            ],
            'status' => [
                'type' => 'enum',
                'constraint' => ['Open', 'Fully Paid', 'Defaulted'],
                'default' => 'Open',
            ],
            'class' => [
                'type' => 'enum',
                'constraint' => ['Running', 'Arrears', 'Cleared', 'Expired', 'Defaulted'],
                'default' => 'Running',
            ],
            'comments' => [
                'type' => 'varchar',
                'constraint' => 100,
            ],
            'date_disbursed' => [
                'type' => 'date',
                'default' => null,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('application_id', 'loanapplications', 'id');
        $this->forge->addForeignKey('product_id', 'loanproducts', 'id');
        $this->forge->addForeignKey('client_id', 'clients', 'id');
        $this->forge->addForeignKey('staff_id', 'staffs', 'id');
        $this->forge->addForeignKey('branch_id', 'branches', 'id');
        $this->forge->addForeignKey('particular_id', 'particulars', 'id');
        $this->forge->addForeignKey('payment_id', 'particulars', 'id');
        $this->forge->createTable('disbursements', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('disbursements', true);
    }
}
