<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use \App\Models\SubCategoryModel;
class SubCategoriesSeeder extends Seeder
{
    public function run()
    {
        $SubcategoryModel = new SubCategoryModel;
        $data = [
            [
                'subcategory_name' => 'Current Assets',
                'subcategory_slug' => 'current-assets',
                'category_id' => 1,
            ],
            [
                'subcategory_name' => 'Fixed Assets',
                'subcategory_slug' => 'fixed-assets',
                'category_id' => 1,
            ],
            [
                'subcategory_name' => 'Capital',
                'subcategory_slug' => 'capital',
                'category_id' => 2,
            ],
            [
                'subcategory_name' => 'Reserves',
                'subcategory_slug' => 'reserves',
                'category_id' => 2,
            ],
            [
                'subcategory_name' => 'Long-Term Liabilities',
                'subcategory_slug' => 'long-term-liabilities',
                'category_id' => 3,
            ],
            [
                'subcategory_name' => 'Current Liabilities',
                'subcategory_slug' => 'current-liabilities',
                'category_id' => 3,
            ],
            [
                'subcategory_name' => 'Primary Income',
                'subcategory_slug' => 'primary-income',
                'category_id' => 4,
            ],
            [
                'subcategory_name' => 'Other Income',
                'subcategory_slug' => 'other-income',
                'category_id' => 4,
            ],
            [
                'subcategory_name' => 'Direct Expenses',
                'subcategory_slug' => 'direct-expenses',
                'category_id' => 5,
            ],
            [
                'subcategory_name' => 'Other Expenses',
                'subcategory_slug' => 'other-expenses',
                'category_id' => 5,
            ],
        ];
        $SubcategoryModel->insertBatch($data);
    }
}
