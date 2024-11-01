<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use \App\Models\LoanProductsModel;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $model = new LoanProductsModel();

        $data = [
            [
                'product_name' => 'Karibu Loan',
                'interest_rate' => 8,
                'interest_type' => 'Flat',
                'repayment_period' => 12,
                'repayment_freq' => 'Monthly',
                'product_desc' => 'Karibu Loan',
                'min_principal' => 500000,
                'max_principal' => 5000000,
            ],
            [
                'product_name' => 'Tambula Loan',
                'interest_rate' => 5,
                'interest_type' => 'Flat',
                'repayment_period' => 12,
                'repayment_freq' => 'Monthly',
                'product_desc' => 'Tambula Loan',
                'min_principal' => 5000000,
                'max_principal' => 10000000,
            ],
            [
                'product_name' => 'Wayagale Loan',
                'interest_rate' => 3,
                'interest_type' => 'Flat',
                'repayment_period' => 12,
                'repayment_freq' => 'Monthly',
                'product_desc' => 'Wayagale Loan',
                'min_principal' => 10000000,
                'max_principal' => 50000000,
            ],
        ];
        $model->insertBatch($data);
    }
}
