<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CashFlowTypesModel;
class CashFlowTypesSeeder extends Seeder
{
    public function run()
    {
        $model = new CashFlowTypesModel();
        $model->insertBatch([
            [
                'name' => 'Operating Activities',
            ],
            [
                'name' => 'Investing Activities',
            ],
            [
                'name' => 'Financing Activities',
            ],
            [
                'name' => 'Non-operating Activities',
            ],
            [
                'name' => 'Cash Flow From Taxes',
            ],
            [
                'name' => 'Non Applicables',
            ],
        ]);
    }
}
