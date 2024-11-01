<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\EntryTypesModel;

class EntryTypes extends Seeder
{
    public function run()
    {
        $model = new EntryTypesModel();
        $data = [
            // transfer entry type. Universal everywhere[should be id 1]
            [
                'type' => 'Transfer',
                'part' => 'credit',
                'entry_menu' => 'transfer',
                'account_typeId' => null,
                'type_code' => '01001',
                'status' => 'active',
            ],
            // General investment/capital entry type [Equity]
            [
                'type' => 'Investment',
                'part' => 'credit',
                'entry_menu' => 'investment',
                'account_typeId' => null,
                'type_code' => '01002',
                'status' => 'active',
            ],
            // General expenses entry type.
            [
                'type' => 'Expense',
                'part' => 'debit',
                'entry_menu' => 'expense',
                'account_typeId' => null,
                'type_code' => '01003',
                'status' => 'active',
            ],
            // disbursement\loan entry types[assets: id 1]
            [
                'type' => 'Repayment',
                'part' => 'credit',
                'entry_menu' => 'financing',
                'account_typeId' => 3,
                'type_code' => '10030',
                'status' => 'active',
            ],
            [
                'type' => 'Disbursement',
                'part' => 'debit',
                'entry_menu' => 'financing',
                'account_typeId' => 3,
                'type_code' => '10131',
                'status' => 'active',
            ],
            // Shares entry types[equity: id 2]
            [
                'type' => 'Purchase',
                'part' => 'credit',
                'entry_menu' => 'financing',
                'account_typeId' => 8,
                'type_code' => '20080',
                'status' => 'active',
            ],
            [
                'type' => 'Dividend',
                'part' => 'debit',
                'entry_menu' => 'financing',
                'account_typeId' => 8,
                'type_code' => '20181',
                'status' => 'inactive',
            ],
            // client savings entry types[liability: id 3]
            [
                'type' => 'Deposit',
                'part' => 'credit',
                'entry_menu' => 'financing',
                'account_typeId' => 12,
                'type_code' => '30012',
                'status' => 'active',
            ],
            [
                'type' => 'Withdraw',
                'part' => 'debit',
                'entry_menu' => 'financing',
                'account_typeId' => 12,
                'type_code' => '30112',
                'status' => 'active',
            ],
            // Revenue from Applications entry types[revenue: id 4]
            [
                'type' => 'Payment',
                'part' => 'credit',
                'entry_menu' => 'financing',
                'account_typeId' => 18,
                'type_code' => '40018',
                'status' => 'active',
            ],
            // Withdraw Charges entry types[revenue: id 4]
            [
                'type' => 'Payment',
                'part' => 'credit',
                'entry_menu' => 'financing',
                'account_typeId' => 20,
                'type_code' => '40020',
                'status' => 'active',
            ],
            // Membership entry types[revenue: id 4]
            [
                'type' => 'Payment',
                'part' => 'credit',
                'entry_menu' => 'financing',
                'account_typeId' => 24,
                'type_code' => '40024',
                'status' => 'active',
            ],
        ];
        $model->insertBatch($data);
    }
}
