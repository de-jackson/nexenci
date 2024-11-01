<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ApplicantProductsTable extends Migration
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
            'interest_type' => [
                'type' => 'ENUM("Flat", "Reducing")',
                'null' => true,
            ],
            'interest_rate' => [
                'type' => 'double',
                'constraint' => [10,2],
                'null' => true,
            ],
            'interest_period' => [
                'type' => 'ENUM("day", "week", "month", "year")',
                'null' => true,
            ],
            'repayment_period' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'repayment_frequency' => [
                'type'       => 'enum',
                'constraint' => ['Daily', 'Weekly', 'Bi-Weekly', 'Monthly', 'Bi-Monthly', 'Quarterly', 'Termly', 'Bi-Annual', 'Annually'],
                'null' => true,
            ],            
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'application_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'loanproducts', 'id');
        $this->forge->addForeignKey('application_id', 'loanapplications', 'id');
        $this->forge->createTable('applicant_products');
    }

    public function down()
    {
        $this->forge->dropTable('applicant_products');
    }
}
