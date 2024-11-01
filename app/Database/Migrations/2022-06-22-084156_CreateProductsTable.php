<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
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
            'product_type' => [
                'type'       => 'enum',
                'constraint' => ['loans', 'savings'],
                'default' => null,
            ],
            'min_account_balance' => [
                'type'       => 'float',
                'default' => null,
            ],
            'max_account_balance' => [
                'type'       => 'float',
                'default' => null,
            ],
            'min_per_entry' => [
                'type'       => 'float',
                'default' => null,
            ],
            'max_per_entry' => [
                'type'       => 'float',
                'default' => null,
            ],
            'interest_rate' => [
                'type' => 'DOUBLE',
                'constraint' => [10,2],
                'default' => null,
            ],
            'interest_period' => [
                'type' => 'ENUM',
                'constraint' => ['day', 'week', 'month', 'year'],
                'default' => null,
            ],
            'interest_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Flat','Reducing'],
                'default' => null,
            ],
            'product_period' => [
                'type' => 'int',
                'constraint' => 10,
                'default' => null,
            ],
            'product_duration' => [
                'type' => 'ENUM',
                'constraint' => ['day(s)', 'week(s)', 'month(s)', 'year(s)'],
                'default' => null,
            ],
            'product_frequency' => [
                'type'       => 'enum',
                'constraint' => ['Weekly', 'Bi-Weekly', 'Monthly', 'Bi-Monthly', 'Quarterly', 'Termly', 'Bi-Annual', 'Annually'],
                'default' => null,
            ],
            'application_min_savings_balance_type' => [
                'type'       => 'enum',
                'constraint' => ['amount', 'rate', 'multiplier'],
                'default' => null,
            ],
            'application_min_savings_balance' => [
                'type'       => 'float',
                'default' => null,
            ],
            'application_max_savings_balance_type' => [
                'type'       => 'enum',
                'constraint' => ['amount', 'rate', 'multiplier'],
                'default' => null,
            ],
            'application_max_savings_balance' => [
                'type'       => 'float',
                'default' => null,
            ],
            'disbursement_min_savings_balance_type' => [
                'type'       => 'enum',
                'constraint' => ['amount', 'rate', 'multiplier'],
                'default' => null,
            ],
            'disbursement_min_savings_balance' => [
                'type'       => 'float',
                'default' => null,
            ],
            'disbursement_max_savings_balance_type' => [
                'type'       => 'enum',
                'constraint' => ['amount', 'rate', 'multiplier'],
                'default' => null,
            ],
            'disbursement_max_savings_balance' => [
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
        $this->forge->createTable('products', true);
    }

    public function down()
    {
        $this->forge->dropTable('products', true);
    }
}
