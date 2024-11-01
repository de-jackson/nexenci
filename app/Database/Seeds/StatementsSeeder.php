<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\StatementsModel;

class StatementsSeeder extends Seeder
{
    public function run()
    {
        $statementModel = new StatementsModel();
        $statementModel->insertBatch([
            [
                'name' => 'Balance Sheet',
                'code' => 'BS100',
            ],
            [
                'name' => 'Income Statement',
                'code' => 'PL200',
            ],
            [
                'name' => 'Trial Balance',
                'code' => 'TB300',
            ],
            [
                'name' => 'Cash Flow Statement',
                'code' => 'CF400',
            ],
        ]);
        
    }
}
