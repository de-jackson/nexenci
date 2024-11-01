<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoanApplications extends Migration
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
            'application_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'application_date' => [
                'type' => 'date',
                'default' => null,
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
            'principal' => [
                'type'       => 'float',
            ],
            'purpose' => [
                'type'       => 'text',
                'default'    => null,
            ],
            // application status
            'status' => [
                'type' => 'enum',
                'constraint' => ['Pending', 'Processing', 'Declined', 'Approved', 'Disbursed','Cancelled'],
                'default'    => 'Pending',
            ],
            'level' => [
                'type' => 'enum',
                'constraint' => ['Loan Officer', 'Loan Committee', 'Credit Officer'],
                'default'    => null,
            ],
            'action' => [
                'type' => 'enum',
                'constraint' => ['Processing', 'Review', 'Approved', 'Disbursed', 'Declined'],
                'default'    => null,
            ],
            // charges
            'overall_charges' => [
                'type' => 'text',
                'default'    => null,
            ],
            'total_charges' => [
                'type' => 'varchar',
                'constraint' => 12,
            ],
            'applicant_products' => [
                'type' => 'text',
                'default'    => null,
            ],
            'reduct_charges' => [
                'type' => 'enum',
                'constraint' => ['Principal', 'Savings'],
                'default'    => 'Principal',
            ],
            // collateral
            'security_item' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'security_info' => [
                'type'       => 'text',
            ],
            'est_value' => [
                'type'       => 'float',
            ],
            // client references(guarantors)
            'ref_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_job' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_contact' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_alt_contact' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_relation' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_name2' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_address2' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_job2' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_contact2' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_alt_contact2' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_email2' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ref_relation2' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            // client incomes
            'net_salary' => [
                'type' => 'double',
            ],
            'farming' => [
                'type' => 'double',
            ],
            'business' => [
                'type' => 'double',
            ],
            'others' => [
                'type' => 'double',
            ],
            // client expenses
            'rent' => [
                'type' => 'double',
            ],
            'education' => [
                'type' => 'double',
            ],
            'medical' => [
                'type' => 'double',
            ],
            'transport' => [
                'type' => 'double',
            ],
            'exp_others' => [
                'type' => 'double',
            ],
            'difference' => [
                'type' => 'double',
            ],
            'dif_status' => [
                'type' => 'ENUM',
                'constraint' => ['Surplus','Balanced','Deficit'],
            ],
            // client other bankers
            'institute_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'institute_branch' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'account_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'institute_name2' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'institute_branch2' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'account_type2' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            // client other loans
            'amt_advance' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'date_advance' => [
                'type' => 'date',
                'null' => true
            ],
            'loan_duration' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'amt_outstanding' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'amt_advance2' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'date_advance2' => [
                'type' => 'date',
                'null' => true
            ],
            'loan_duration2' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'amt_outstanding2' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            // 'pass_photo' => [
            //     'type'       => 'VARCHAR',
            //     'constraint' => 100,
            // ],
            // 'ID_photo' => [
            //     'type'       => 'VARCHAR',
            //     'constraint' => 100,
            // ],
            // 'signature' => [
            //     'type'       => 'VARCHAR',
            //     'constraint' => 50,
            // ],
            'loan_agreement' => [
                'type' => 'varchar',
                'constraint' => 50,
            ]
            ,'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('branch_id', 'branches', 'id');
        $this->forge->addForeignKey('client_id', 'clients', 'id');
        $this->forge->addForeignKey('staff_id', 'staffs', 'id');
        $this->forge->addForeignKey('product_id', 'loanproducts', 'id');
        $this->forge->createTable('loanapplications', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('loanapplications', true);
    }
}
