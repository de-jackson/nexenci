<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoanProducts extends Migration
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
            'product_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'product_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'interest_rate' => [
                'type' => 'DOUBLE',
                'constraint' => [10,2],
            ],
            'interest_period' => [
                'type' => 'ENUM',
                'constraint' => ['day', 'week', 'month', 'year'],
                'default' => 'year',
            ],
            'interest_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Flat','Reducing'],
                'default' => 'Reducing',
            ],
            'repayment_period' => [
                'type' => 'int',
                'constraint' => 10,
            ],
            'repayment_duration' => [
                'type' => 'ENUM',
                'constraint' => ['day(s)', 'week(s)', 'month(s)', 'year(s)'],
                'default' => 'month(s)',
            ],
            'repayment_freq' => [
                'type'       => 'enum',
                'constraint' => ['Weekly', 'Bi-Weekly', 'Monthly', 'Bi-Monthly', 'Quarterly', 'Termly', 'Bi-Annual', 'Annually'],
                'default' => 'Monthly',
            ],
            'min_principal' => [
                'type'       => 'float',
                'default' => null,
            ],
            'max_principal' => [
                'type'       => 'float',
                'default' => null,
            ],
            'min_savings_balance_type_application' => [
                'type'       => 'enum',
                'constraint' => ['amount', 'rate', 'multiplier'],
                'default' => null,
            ],
            'min_savings_balance_application' => [
                'type'       => 'float',
                'default' => null,
            ],
            'max_savings_balance_type_application' => [
                'type'       => 'enum',
                'constraint' => ['amount', 'rate', 'multiplier'],
                'default' => null,
            ],
            'max_savings_balance_application' => [
                'type'       => 'float',
                'default' => null,
            ],
            'min_savings_balance_type_disbursement' => [
                'type'       => 'enum',
                'constraint' => ['amount', 'rate', 'multiplier'],
                'default' => null,
            ],
            'min_savings_balance_disbursement' => [
                'type'       => 'float',
                'default' => null,
            ],
            'max_savings_balance_type_disbursement' => [
                'type'       => 'enum',
                'constraint' => ['amount', 'rate', 'multiplier'],
                'default' => null,
            ],
            'max_savings_balance_disbursement' => [
                'type'       => 'float',
                'default' => null,
            ],
            'product_desc' => [
                'type'       => 'text',
            ],
            'product_features' => [
                'type'       => 'text',
            ],
            'product_charges' => [
                'type'       => 'text',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Active','Inactive'],
                'default' => 'Active',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('loanproducts', true);
    }

    public function down()
    {
        $this->forge->dropTable('loanproducts', true);
    }
}
