<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AccountTypesModel;

class AccountTypes extends Seeder
{
    public function run()
    {
        $typesModel = new AccountTypesModel;

        $typesModel->insertBatch([
            // account types belonging to assets
            [
                'name' => 'Cash and Bank',
                'code' => '1001',
                'status' => 'Active',
                'category_id' => '1',
                'description' => 'Represents physical cash and funds held in bank accounts.',
            ],
            [
                'name' => 'Cash Investment',
                'code' => '1002',
                'status' => 'Active',
                'category_id' => '1',
                'description' => 'Investments made with surplus cash, usually short-term.',
            ],
            [
                'name' => 'Loan Portfolio',
                'code' => '1003',
                'status' => 'Active',
                'category_id' => '1',
                'description' => 'A collection of loans granted by the business.',
            ],
            [
                'name' => 'Receivable',
                'code' => '1004',
                'status' => 'Active',
                'category_id' => '1',
                'description' => 'Amounts owed to the business by customers.',
            ],
            [
                'name' => 'Tax Paid on Purchase',
                'code' => '1005',
                'status' => 'Active',
                'category_id' => '1',
                'description' => 'Taxes incurred while purchasing items.',
            ],
            [
                'name' => 'Current Asset',
                'code' => '1006',
                'status' => 'Active',
                'category_id' => '1',
                'description' => 'Assets expected to be converted to cash within a year.',
            ],
            [
                'name' => 'Non-current Asset',
                'code' => '1007',
                'status' => 'Active',
                'category_id' => '1',
                'description' => 'Assets with a lifespan exceeding one year.',
            ],
            // account types belonging to equity
            [
            'name' => 'Equity',
                'code' => '2001',
                'status' => 'Active',
                'category_id' => '2',
                'description' => 'Represents ownership interest in the business.',
            ],
            [
                'name' => 'Retained Earnings',
                'code' => '2002',
                'status' => 'Active',
                'category_id' => '2',
                'description' => 'Accumulated profits not distributed as dividends.',
            ],
            // account types belonging to liabilities
            [
                'name' => 'Accumulated Depreciation',
                'code' => '3001',
                'status' => 'Active',
                'category_id' => '3',
                'description' => 'The total depreciation charged to date on fixed assets.',
            ],
            [
                'name' => 'Loan Impairment',
                'code' => '3002',
                'status' => 'Active',
                'category_id' => '3',
                'description' => 'Reduction in the value of a loan due to the likelihood of non-payment.',
            ],
            [
                'name' => 'Savings',
                'code' => '3003',
                'status' => 'Active',
                'category_id' => '3',
                'description' => 'Accumulated funds from Members/Clients/Customers savings',
            ],
            [
                'name' => 'Investor Deposit',
                'code' => '3004',
                'status' => 'Active',
                'category_id' => '3',
                'description' => 'Funds deposited by investors.',
            ],
            [
                'name' => 'Liability',
                'code' => '3005',
                'status' => 'Active',
                'category_id' => '3',
                'description' => 'Obligations owed by the business.',
            ],
            [
                'name' => 'Merchant Borrowing',
                'code' => '3007',
                'status' => 'Active',
                'category_id' => '3',
                'description' => 'Loans obtained from merchants or suppliers.',
            ],
            [
                'name' => 'Payable',
                'code' => '3008',
                'status' => 'Active',
                'category_id' => '3',
                'description' => 'Amounts owed to suppliers or creditors.',
            ],
            [
                'name' => 'Taxes',
                'code' => '3009',
                'status' => 'Active',
                'category_id' => '3',
                'description' => 'Taxes due to government authorities.',
            ],
            // account types belonging on revenue
            [
                'name' => 'Revenue from Applications',
                'code' => '4001',
                'status' => 'Active',
                'category_id' => '4',
                'description' => 'Income generated from loan application charges.',
            ],
            [
                'name' => 'Revenue from Loan Repayments',
                'code' => '4002',
                'status' => 'Active',
                'category_id' => '4',
                'description' => 'Income/interest from loan repayments.',
            ],
            [
                'name' => 'Revenue from Savings',
                'code' => '4003',
                'status' => 'Active',
                'category_id' => '4',
                'description' => 'Income earned on deposits & withdrwas.',
            ],
            [
                'name' => 'Revenue from Lender Investment',
                'code' => '4004',
                'status' => 'Active',
                'category_id' => '4',
                'description' => 'Income from investments made by lenders.',
            ],
            [
                'name' => 'Non-operating Revenue',
                'code' => '4005',
                'status' => 'Active',
                'category_id' => '4',
                'description' => 'Income from sources unrelated to core operations.',
            ],
            [
                'name' => 'Subsidy',
                'code' => '4006',
                'status' => 'Active',
                'category_id' => '4',
                'description' => 'Financial assistance received from government or other entities.',
            ],
            [
                'name' => 'Membership',
                'code' => '4007',
                'status' => 'Active',
                'category_id' => '4',
                'description' => 'Funds contributed by members/customers/clients.',
            ],
            // account types belonging to expenses
            [
                'name' => 'Asset Disposal',
                'code' => '5001',
                'status' => 'Active',
                'category_id' => '5',
                'description' => 'Proceeds from the sale of assets.',
            ],
            [
                'name' => 'Default Loan',
                'code' => '5002',
                'status' => 'Active',
                'category_id' => '5',
                'description' => 'Loss due to a loan not being repaid.',
            ],
            [
                'name' => 'Depreciation',
                'code' => '5003',
                'status' => 'Active',
                'category_id' => '5',
                'description' => 'Allocation of the cost of fixed assets over their useful life.',
            ],
            [
                'name' => 'Exchange Rate Loss',
                'code' => '5004',
                'status' => 'Active',
                'category_id' => '5',
                'description' => 'Loss incurred due to currency fluctuations.',
            ],
            [
                'name' => 'Expenses on Deposit',
                'code' => '5005',
                'status' => 'Active',
                'category_id' => '5',
                'description' => 'Costs related to managing deposits.',
            ],
            [
                'name' => 'Expenses on Borrowing',
                'code' => '5006',
                'status' => 'Active',
                'category_id' => '5',
                'description' => 'Costs associated with borrowing funds.',
            ],
            [
                'name' => 'Miscellaneous Expense',
                'code' => '5007',
                'status' => 'Active',
                'category_id' => '5',
                'description' => 'General operating expenses not classified elsewhere.',
            ],
            [
                'name' => 'Non-operating Expense',
                'code' => '5008',
                'status' => 'Active',
                'category_id' => '5',
                'description' => 'Expenses unrelated to core operations.',
            ],
            [
                'name' => 'Provision for Loan Impairment',
                'code' => '5009',
                'status' => 'Active',
                'category_id' => '5',
                'description' => 'Amount set aside to cover potential loan losses.',
            ],
            [
                'name' => 'Restructured Loan',
                'code' => '5010',
                'status' => 'Active',
                'category_id' => '5',
                'description' => 'Modified loan terms, often impacting equity.',
            ],
            [
                'name' => 'Tax',
                'code' => '5011',
                'status' => 'Active',
                'category_id' => '5',
                'description' => '',
            ],
        ]);
    }
}
