<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use \App\Models\CategoriesModel;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $categoriesModel = new CategoriesModel;
        $categories = [
            [
                'category_name' => 'Assets',
                'category_slug' => 'assets',
                'part' => 'debit',
                'statement_id' => 1,
                'bring_forward' => 'Yes',
            ],
            [
                'category_name' => 'Equity',
                'category_slug' => 'equity',
                'part' => 'credit',
                'statement_id' => 1,
                'bring_forward' => 'Yes',
            ],
            [
                'category_name' => 'Liabilities',
                'category_slug' => 'liabilities',
                'part' => 'credit',
                'statement_id' => 1,
                'bring_forward' => 'Yes',
            ],
            [
                'category_name' => 'Revenue',
                'category_slug' => 'revenue',
                'part' => 'credit',
                'statement_id' => 2,
                'bring_forward' => 'No',
            ],
            [
                'category_name' => 'Expenses',
                'category_slug' => 'expenses',
                'part' => 'debit',
                'statement_id' => 2,
                'bring_forward' => 'No',
            ]
        ];

        $categoriesModel->insertBatch($categories);
    }
}
