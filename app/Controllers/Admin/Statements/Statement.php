<?php

namespace App\Controllers\Admin\Statements;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Statement extends MasterController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Statements';
        $this->title = 'Statements';
        $this->menuItem = [
            'menu' => $this->menu,
        ];
    }

    public function index()
    {
        $this->title = "Balance-Sheet";
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            // get financial years array
            $financial_years = $this->financial_yearsArray();
            // Extract the first element using array_slice to get current financial year
            $firstElement = array_slice($financial_years, 0, 1, true);
            if (!empty($firstElement)) {
                // loop through the array to get the start & end of the financial year
                foreach ($firstElement as $key => $value) {
                    $f_year = $key;
                    $fYear_start = $value['start'];
                    $fYear_end = $value['end'];
                    break; // Exit loop after accessing first element
                }
            } else {
                $f_year = date('Y');
                $fYear_start = date('Y-m-d', strtotime($this->settingsRow['financial_year_start'] . '-' . date('Y')));
                $fYear_end = date('Y-m-d', strtotime($fYear_start . '-' . (date('Y') + 1)) - 1);
            }

            // set default financial year
            if (!empty($this->request->getVar('f_year'))) {
                $selected_fYear = $this->request->getVar('f_year');
            } else {
                $selected_fYear = $f_year;
            }
            // set default financial year
            if (!empty($this->request->getVar('f_quarter'))) {
                $selected_fQuarter = $this->request->getVar('f_quarter');
            } else {
                $selected_fQuarter = '';
            }
            // set default start date
            if (!empty($this->request->getVar('start_date'))) {
                $start_date = date('Y-m-d', strtotime($this->request->getVar('start_date')));
            } else {
                $start_date = $fYear_start;
            }
            // set default end date
            if (!empty($this->request->getVar('end_date'))) {
                $end_date = date('Y-m-d', strtotime($this->request->getVar('end_date')));
            } else {
                $end_date = $fYear_end;
            }

            // get financial quarters array
            $financial_quarters = $this->generateFinancialYearQuarters($start_date, $end_date);
            // echo ($start_date . "<br>" . $end_date . "<br>");
            // print_r($financial_quarters);
            // exit;

            return view('admin/statements/balancesheet/index', [
                'menu' => $this->menu,
                'title' => $this->title,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'selected_fYear' => $selected_fYear,
                'selected_fQuarter' => $selected_fQuarter,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'statement' => 'Balance Sheet',
                'categories' => $this->get_categories(1),
                'subcategories' => $this->get_subcategories(),
                'particulars' => $this->get_particulars(),
                'entries' => $this->get_entries($start_date, $end_date),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    // other statements views
    public function statement_view($statement)
    {
        $this->title = ucwords($statement);
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            switch (strtolower($statement)) {
                case 'balancesheet':
                    $view = 'admin/statements/balancesheet/index';
                    $title = 'Balance Sheet';
                    $statement_id = 1;
                    break;
                case 'cashflow':
                    $view = 'admin/statements/cashflow/index';
                    $title = 'Cash Flow';
                    $statement_id = 'all';
                    break;
                case 'incomestatement':
                    $view = 'admin/statements/income/index';
                    $title = 'Income Statement';
                    $statement_id = 2;
                    break;
                case 'trialbalance':
                    $view = 'admin/statements/trialbalance/index';
                    $title = 'Trial Balance';
                    $statement_id = 'all';
                    break;
                case 'particular':
                    $view = 'admin/statements/particular_statement';
                    $title = $this->title;
                    $statement_id = 'all';
                    break;
                default:
                    break;
            }
            // get financial years array
            $financial_years = $this->financial_yearsArray();
            // Extract the first element using array_slice to get current financial year
            $firstElement = array_slice($financial_years, 0, 1, true);
            if (!empty($firstElement)) {
                // loop through the array to get the start & end of the financial year
                foreach ($firstElement as $key => $value) {
                    $f_year = $key;
                    $fYear_start = $value['start'];
                    $fYear_end = $value['end'];
                    break; // Exit loop after accessing first element
                }
            } else {
                $f_year = date('Y');
                $fYear_start = date('Y-m-d', strtotime($this->settingsRow['financial_year_start'] . '-' . date('Y')));
                $fYear_end = date('Y-m-d', strtotime($fYear_start . '-' . (date('Y') + 1)) - 1);
            }

            // set default financial year
            if (!empty($this->request->getVar('f_year'))) {
                $selected_fYear = $this->request->getVar('f_year');
            } else {
                $selected_fYear = $f_year;
            }
            // set default financial year
            if (!empty($this->request->getVar('f_quarter'))) {
                $selected_fQuarter = $this->request->getVar('f_quarter');
            } else {
                $selected_fQuarter = '';
            }
            // set default start date
            if (!empty($this->request->getVar('start_date'))) {
                $start_date = date('Y-m-d', strtotime($this->request->getVar('start_date')));
            } else {
                $start_date = $fYear_start;
            }
            // set default end date
            if (!empty($this->request->getVar('end_date'))) {
                $end_date = date('Y-m-d', strtotime($this->request->getVar('end_date')));
            } else {
                $end_date = $fYear_end;
            }

            $data = [
                'title' => ucwords($title),
                'menu' => $this->menu,
                'statement' => $statement,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'selected_fYear' => $selected_fYear,
                'selected_fQuarter' => $selected_fQuarter,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'cashflows' => $this->get_cashflowTypes(),
                'categories' => $this->get_categories($statement_id),
                'subcategories' => $this->get_subcategories(),
                'particulars' => $this->get_particulars(),
                'entries' => $this->get_entries($start_date, $end_date),
            ];
            return view($view, $data);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    // particular statement
    public function particular_statement($particular_id, $startDate = null, $endDate = null)
    {
        $title = $this->title;
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            // get financial years array
            $financial_years = $this->financial_yearsArray();
            // Extract the first element using array_slice to get current financial year
            $firstElement = array_slice($financial_years, 0, 1, true);
            if (!empty($firstElement)) {
                // loop through the array to get the start & end of the financial year
                foreach ($firstElement as $key => $value) {
                    $f_year = $key;
                    $fYear_start = $value['start'];
                    $fYear_end = $value['end'];
                    break; // Exit loop after accessing first element
                }
            } else {
                $f_year = date('Y');
                $fYear_start = date('Y-m-d', strtotime($this->settingsRow['financial_year_start'] . '-' . date('Y')));
                $fYear_end = date('Y-m-d', strtotime($fYear_start . '-' . (date('Y') + 1)) - 1);
            }

            // set default financial year
            if (!empty($this->request->getVar('f_year'))) {
                $selected_fYear = $this->request->getVar('f_year');
            } else {
                $selected_fYear = $f_year;
            }
            // set default start date
            $start_date = (!$startDate) ? $fYear_start : $startDate;
            // set default end date
            $end_date = (!$endDate) ? $fYear_end : $endDate;
            
            // get financial quarters array
            $financial_quarters = $this->generateFinancialYearQuarters($start_date, $end_date);
            return view('admin/statements/particular_statement', [
                'title' => "Particular Statement",
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'selected_fYear' => $selected_fYear,
                'selected_fQuarter' => $financial_quarters,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'particular' => $this->particularDataRow($particular_id),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    // export statement to pdf
    function export_statement($statement, $start_date, $end_date, $particular_id = null)
    {

        $this->menuItem['title'] = ucwords($statement);
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $data = [
                'title' => ucwords($statement) . ' Statement',
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'subcategories' => $this->get_subcategories(),
                'particulars' => $this->get_particulars(),
                'entries' => $this->get_entries($start_date, $end_date),
                'statements' => $this->generate_statementArray($start_date, $end_date),
            ];
            switch (strtolower($statement)) {
                case 'balancesheet';
                    $data['categories'] = $this->get_categories('1');
                    return view('admin/statements/balancesheet/balance_sheetPDF', $data);
                    break;
                case 'cashflow';
                    $data['cashflows'] = $this->get_cashflowTypes();
                    return view('admin/statements/cashflow/cash_flowPDF', $data);
                    break;
                case 'incomestatement':
                    $data['categories'] = $this->get_categories('2');
                    return view('admin/statements/income/incomestatementPDF', $data);
                    break;
                case 'trialbalance':
                    $data['categories'] = $this->get_categories('all');
                    return view('admin/statements/trialbalance/trial_balancePDF', $data);
                    break;
                case 'particularstatement';
                    return view('admin/statements/particular_statementPDF', [
                        'title' => "Particular Statement",
                        'menu' => $this->menu,
                        'settings' => $this->settingsRow,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'particular' => $this->particular->select('particulars.id, particulars.particular_name, particulars.opening_balance, particulars.account_typeId, particulars.created_at, categories.category_name, categories.part')->join('categories', 'categories.id = particulars.category_id', 'left')->find($particular_id),
                        'entries' => $this->get_entries($start_date, $end_date),
                    ]);
                    break;
                default:
                    session()->setFlashdata('failed', 'Page requested can not be found');
                    return redirect()->to(base_url('admin/dashboard'));
                    break;
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    // generate statement and return it as a json response
    public function generate_statement()
    {
        // get financial years array
        $financial_years = $this->financial_yearsArray();
        // Extract the first element using array_slice to get current financial year
        $firstElement = array_slice($financial_years, 0, 1, true);
        if (!empty($firstElement)) {
            // loop through the array to get the start & end of the financial year
            foreach ($firstElement as $key => $value) {
                $f_year = $key;
                $fYear_start = $value['start'];
                $fYear_end = $value['end'];
                break; // Exit loop after accessing first element
            }
        } else {
            $fYear_start = date('Y-m-d', strtotime($this->settingsRow['financial_year_start'] . '-' . date('Y')));
            $fYear_end = date('Y-m-d', strtotime($fYear_start . '-' . (date('Y') + 1)) - 1);
        }
        // pick start and end date from the form or set default from the settings
        if (!empty($this->request->getVar('start_date'))) {
            $startDate = date('Y-m-d', strtotime($this->request->getVar('start_date')));
        } else {
            $startDate = $fYear_start;
        }
        if (!empty($this->request->getVar('end_date'))) {
            $endDate = date('Y-m-d', strtotime($this->request->getVar('end_date')));
        } else {
            $endDate = $fYear_end;
        }

        // Return the response as JSON
        return $this->respond(($this->generate_statementArray($startDate, $endDate)));
    }

    private function generate_statementArray($start_date, $end_date)
    {
        // Prepare the response data
        $response = array(
            'cashFlowData' => [],
            'categoryData' => [],
            'subcategoryData' => [],
            'particularData' => [],
            'getTotals' => [],
            'start_date' => $start_date,
            'end_date' => $end_date
        );

        // Get data needed to generate the statement
        $cashflow_types = $this->get_cashflowTypes();
        $categories = $this->get_categories();
        $subcategories = $this->get_subcategories();
        $particulars = $this->get_particulars();

        // Memoize particular balances to avoid recalculating them multiple times
        $particularBalances = [];

        if (count($cashflow_types) > 0) {
            $assetsTotal = $equityTotal = $liabilityTotal = $revenueTotal = $expensesTotal = 0;
            $grossIncome = $tax_payable = $netIncome = $debitTotal = $creditTotal = 0;

            foreach ($cashflow_types as $cashFlowID => $cashflowType) {
                $cashFlowBalance = 0;

                foreach ($categories as $categoryID => $category) {
                    $cashFlowBalance = $categoryBalance = 0;

                    foreach ($subcategories as $subcategoryID => $subcategory) {
                        $cashFlowBalance = $categoryBalance = $subcategoryBalance = 0;
                        $debitTotal = $creditTotal = 0;
                        foreach ($particulars as $particularID => $particular) {
                            // Skip recalculating balances if already computed for the particular
                            if (!isset($particularBalances[$particular['id']])) {
                                $particularBalances[$particular['id']] = $this->entry->calculateTotalBalance([
                                    'module' => 'particular',
                                    'module_id' => $particular['id'],
                                    'status' => $particular['part'],
                                    'entryId' => 'statements',
                                    'start_date' => $start_date,
                                    'end_date' => $end_date,
                                ]);
                            }

                            $particularTotalBalances = $particularBalances[$particular['id']];
                            $particularDebitTotal = $particularTotalBalances['totalDebit'];
                            $particularCreditTotal = $particularTotalBalances['totalCredit'];
                            $particularBalance = $particularTotalBalances['totalBalance'];

                            // Calculate balance updates at different levels
                            if ($particular['subcategory_id'] == $subcategory['id']) {
                                $subcategoryBalance += $particularBalance;
                            }
                            if ($particular['category_id'] == $category['id']) {
                                $categoryBalance += $particularBalance;
                            }
                            if ($particular['cash_flow_typeId'] == $cashflowType['id']) {
                                $cashFlowBalance += $particularBalance;
                            }

                            # Sanitize balance then Add particular data to response
                            if (strtolower($particular['part']) == 'debit') {
                                $debitTotal += $particularBalance;
                            }
                            if (strtolower($particular['part']) == 'credit') {
                                $creditTotal += $particularBalance;
                            }
                            
                            $particular['balance'] = ($particularBalance >= 0) ? number_format($particularBalance, 2) : '(' . number_format(abs($particularBalance), 2) . ')';
                            $response['particularData'][$particularID] = $particular;
                        }

                        # Sanitize balance then Add subcategory data to response
                        $subcategory['balance'] = ($subcategoryBalance < 0) ? '(' . number_format(abs($subcategoryBalance), 2) . ')' : number_format($subcategoryBalance, 2);
                        $response['subcategoryData'][$subcategoryID] = $subcategory;
                    }

                    # Update category totals
                    switch ($category['id']) {
                        case 1:
                            $assetsTotal = $categoryBalance;
                            break;
                        case 2:
                            $equityTotal = $categoryBalance;
                            break;
                        case 3:
                            $liabilityTotal = $categoryBalance;
                            break;
                        case 4:
                            $revenueTotal = $categoryBalance;
                            break;
                        case 5:
                            $expensesTotal = $categoryBalance;
                            break;
                    }

                    # Sanitize balance then Add category data to response
                    $category['balance'] = ($categoryBalance < 0) ? '(' . number_format(abs($categoryBalance), 2) . ')' : number_format($categoryBalance, 2);
                    $response['categoryData'][$categoryID] = $category;
                }

                // Add cashflow balance to the response
                $cashflowType['balance'] = ($cashFlowBalance > 0) ? number_format(abs($cashFlowBalance), 2) : '(' . number_format ($cashFlowBalance, 2) . ')';
                $response['cashFlowData'][$cashFlowID] = $cashflowType;
            }

            // Calculate gross income, tax payable, and net income
            $grossIncome = ($revenueTotal - $expensesTotal);
            $tax_payable = ($grossIncome > 0) ? $grossIncome * ($this->settingsRow['tax_rate'] / 100) : 0;
            $netIncome = ($grossIncome - $tax_payable);
            $equityLiabilityTotal = ($equityTotal + $liabilityTotal);
            $equityRetainedTotal = ($equityTotal + $netIncome);
            $liabilityTaxTotal = ($liabilityTotal + $tax_payable);
            $equityLiabilityTotal_iS = ($equityRetainedTotal + $liabilityTaxTotal);

            // Add totals to the response array
            $response['getTotals'] = [
                'totalDebits' => number_format(floatval($debitTotal), 2),
                'totalCredits' => number_format(floatval($creditTotal), 2),
                'assetsTotal' => number_format(floatval($assetsTotal), 2),
                'equityTotal' => number_format(floatval($equityTotal), 2),
                'liabilityTotal' => number_format(floatval($liabilityTotal), 2),
                'revenueTotal' => number_format(floatval($revenueTotal), 2),
                'expensesTotal' => number_format(floatval($expensesTotal), 2),
                'grossIncome' => number_format($grossIncome, 2),
                'taxPayableTotal' => number_format($tax_payable, 2),
                'netIncome' => number_format($netIncome, 2),
                'equityRetainedTotal' => number_format($equityRetainedTotal, 2),
                'liabilityTaxTotal' => number_format($liabilityTaxTotal, 2),
                'equityLiabilityTotal' => number_format($equityLiabilityTotal, 2),
                'equityLiabilityTotal_iS' => number_format($equityLiabilityTotal_iS, 2),
            ];
        }

        // Return the response as JSON
        return $response;
    }

    private function generate_statementArrayO($start_date, $end_date)
    {
        // Prepare the response data
        $response = array(
            'cashFlowData' => [],
            'categoryData' => [],
            'subcategoryData' => [],
            'particularData' => [],
            'getTotals' => [],
            'start_date' => $start_date,
            'end_date' => $end_date
        );
        // get data needed to generate the statement
        $cashflow_types = $this->get_cashflowTypes();
        $categories = $this->get_categories();
        $subcategories = $this->get_subcategories();
        $particulars = $this->get_particulars();

        //  check if there is any cash flow types
        if (count($cashflow_types) > 0) {
            // loop through cash flow types
            foreach ($cashflow_types as $cashFlowID => $cashflowType) {
                // declare variables for totals
                $cashFlowBalance = 0;
                //  check if there is any categories
                if (count($categories) > 0) {
                    $assetsTotal = $equityTotal = $liabilityTotal = $revenueTotal = $expensesTotal = $tax_payable = $grossIncome = $netIncome = 0;
                    $equityLiabilityTotal = $equityRetainedTotal = $liabilityTaxTotal = 0;
                    // loop through categories
                    foreach ($categories as $categoryID => $category) {
                        // declare variables for totals
                        $categoryBalance = 0;
                        //  check if there is any subcategories
                        if (count($subcategories) > 0) {
                            // loop through subcategories
                            foreach ($subcategories as $subcategoryID => $subcategory) {
                                // declare variables for totals
                                $subcategoryBalance = 0;
                                //  check if there is any particulars
                                if (count($particulars) > 0) {
                                    // declare variables for totals
                                    $debitTotal = $creditTotal = 0;
                                    $particularBalance = $particularDebitTotal = $particularCreditTotal = 0;
                                    $cashFlowBalance = $categoryBalance = $subcategoryBalance = 0;
                                    // loop through particulars
                                    foreach ($particulars as $particularID => $particular) {
                                        # get particular total debit, credit and balance
                                        $particularTotalBalances = $this->entry->calculateTotalBalance([
                                            'module' => 'particular',
                                            'module_id' => $particular['id'],
                                            'status' => $particular['part'],
                                            'entryId' => 'statements',
                                            'start_date' => $start_date,
                                            'end_date' => $end_date,
                                        ]);

                                        // debit total
                                        $particularDebitTotal = $particularTotalBalances['totalDebit'];
                                        // credit total
                                        $particularCreditTotal = $particularTotalBalances['totalCredit'];
                                        // overall total
                                        $particularBalance = $particularTotalBalances['totalBalance'];

                                        // accumulate total debit & credit
                                        // $debitTotal += $particularDebitTotal;
                                        // $creditTotal += $particularCreditTotal;
                                        //calculate actual particular balance;
                                        if ($particular['part'] == 'debit') {
                                            // $particularBalance = (float)(($particular['opening_balance'] + $particularDebitTotal) - $particularCreditTotal);
                                            // $particularBalance = (float)($particularDebitTotal - $particularCreditTotal);
                                            // accumulate total debit
                                            $debitTotal += $particularBalance;
                                        }
                                        if ($particular['part'] == 'credit') {
                                            // $particularBalance = (flototalCreditat)($particularDebitTotal - ($particular['opening_balance'] + $particularCreditTotal));
                                            // $particularBalance = (float)($particularDebitTotal - $particularCreditTotal);
                                            // accumulate total credit
                                            $creditTotal += $particularBalance;
                                        }
                                        # totals && balance @ subcategory level
                                        if ($particular['subcategory_id'] == $subcategory['id']) {
                                            $subcategoryBalance += $particularBalance;
                                        }
                                        # totals && balance @ category level
                                        if ($particular['category_id'] == $category['id']) {
                                            $categoryBalance += $particularBalance;
                                        }
                                        # totals && balance @ cashflow-type level
                                        if ($particular['cash_flow_typeId'] == $cashflowType['id']) {
                                            $cashFlowBalance += $particularBalance;
                                        }
                                        // total for each category
                                        switch ($category['id']) {
                                            case 1:
                                                $assetsTotal = $categoryBalance;
                                                break;
                                            case 2:
                                                # remove/add -ve since its credit
                                                $equityTotal = ($categoryBalance < 0) ? abs($categoryBalance) : -$categoryBalance;
                                                break;
                                            case 3:
                                                # remove/add -ve since its credit
                                                $liabilityTotal = ($categoryBalance < 0) ? abs($categoryBalance) : -$categoryBalance;
                                                break;
                                            case 4:
                                                # remove/add -ve since its credit
                                                $revenueTotal = ($categoryBalance < 0) ? abs($categoryBalance) : -$categoryBalance;
                                                break;
                                            case 5:
                                                $expensesTotal = $categoryBalance;
                                                break;
                                            default:
                                                break;
                                        };
                                        // calculate profit\loss
                                        $grossIncome = ($revenueTotal - $expensesTotal);
                                        if ($grossIncome > 0) {
                                            $tax_payable = $grossIncome * (($this->settingsRow['tax_rate']) / 100);
                                            $netIncome = $grossIncome - $tax_payable;
                                        } else {
                                            $tax_payable =  0;
                                            $netIncome = $grossIncome;
                                        }
                                        // Add  balance as a particular field
                                        // remove -ve, put -ve balance in ()
                                        if ($particular['part'] == 'credit') {
                                            if ($particularBalance <= 0) {
                                                $particularBalance =  number_format(abs($particularBalance), 2);
                                            } else {
                                                $particularBalance = '(' . number_format($particularBalance, 2) . ')';
                                            }
                                        }
                                        if ($particular['part'] == 'debit') {
                                            if ($particularBalance < 0) {
                                                $particularBalance = '(' . number_format(abs($particularBalance), 2) . ')';
                                            } else {
                                                $particularBalance = number_format($particularBalance, 2);
                                            }
                                        }
                                        $particular["balance"] = $particularBalance;
                                        // add particular data to the response array
                                        $response['particularData'][$particularID] = $particular;
                                    }
                                }
                                // remove -ve, put -ve balance in () 
                                if ($subcategory['part'] == 'credit') {
                                    if ($subcategoryBalance <= 0) {
                                        $subcategoryBalance =  number_format(abs($subcategoryBalance), 2);
                                    } else {
                                        $subcategoryBalance = '(' . number_format($subcategoryBalance, 2) . ')';
                                    }
                                } else {
                                    if ($subcategoryBalance < 0) {
                                        $subcategoryBalance = '(' . number_format(abs($subcategoryBalance), 2) . ')';
                                    } else {
                                        $subcategoryBalance = number_format($subcategoryBalance, 2);
                                    }
                                }
                                // add balance as subcategory attribute
                                $subcategory["balance"] = $subcategoryBalance;

                                // add subcategory data to the response array
                                $response['subcategoryData'][$subcategoryID] = $subcategory;
                            }
                        }
                        // remove -ve, put -ve balance in ()
                        if ($category['part'] == 'credit') {
                            if ($categoryBalance <= 0) {
                                $categoryBalance = // declare variables for totals
                                    $particularBalance = $particularDebitTotal = $particularCreditTotal = 0;
                                number_format(abs($categoryBalance), 2);
                            } else {
                                $categoryBalance = '(' . number_format($categoryBalance, 2) . ')';
                            }
                        } else {
                            if ($categoryBalance < 0) {
                                $categoryBalance = '(' . number_format(abs($categoryBalance), 2) . ')';
                            } else {
                                $categoryBalance = number_format($categoryBalance, 2);
                            }
                        }
                        // add balance as category attribute
                        $category["balance"] = $categoryBalance;

                        // add category data to the response array
                        $response['categoryData'][$categoryID] = $category;
                        //add totals to the response array
                        $response['getTotals']['totalDebits'] = (($debitTotal >= 0) ? number_format(floatval($debitTotal), 2) : "(" . number_format(floatval(abs($debitTotal)), 2) . ")"); // total debits
                        $response['getTotals']['totalCredits'] = (($creditTotal < 0) ? number_format(abs(floatval($creditTotal)), 2) : "(" . number_format(floatval(abs($creditTotal)), 2) . ")"); // total credits
                        $response['getTotals']['assetsTotal'] = number_format(floatval($assetsTotal), 2); // assets total
                        $response['getTotals']['equityTotal'] = number_format(floatval($equityTotal), 2); // equity total
                        $response['getTotals']['liabilityTotal'] = number_format(floatval($liabilityTotal), 2); // liabilities total
                        $response['getTotals']['revenueTotal'] = number_format(floatval($revenueTotal), 2); // revenue total
                        $response['getTotals']['expensesTotal'] = number_format(floatval($expensesTotal), 2); // expense total
                        $response['getTotals']['grossIncome'] = (($grossIncome >= 0) ? number_format($grossIncome, 2) : "(" . number_format(abs($grossIncome), 2) . ")"); // grossIncome total
                        $response['getTotals']['taxPayableTotal'] = number_format($tax_payable, 2); // total tax
                        $response['getTotals']['netIncome'] = number_format($netIncome, 2); // netIncome total
                        $equityRetainedTotal = $equityTotal + $netIncome;
                        $liabilityTaxTotal = $liabilityTotal + $tax_payable;
                        $response['getTotals']['equityRetainedTotal'] = (($equityRetainedTotal >= 0) ? number_format($equityRetainedTotal, 2) : "(" . number_format(abs($equityRetainedTotal), 2) . ")"); //  equity + net income total
                        $response['getTotals']['liabilityTaxTotal'] = (($liabilityTaxTotal >= 0) ? number_format($liabilityTaxTotal, 2) : "(" . number_format(abs($liabilityTaxTotal), 2) . ")"); //  liability + tax payable total
                        $equityLiabilityTotal = ($equityTotal + $liabilityTotal);
                        $response['getTotals']['equityLiabilityTotal'] = (($equityLiabilityTotal >= 0) ? number_format($equityLiabilityTotal, 2) : "(" . number_format(abs($equityLiabilityTotal), 2) . ")"); //  equity + liability total
                        $equityLiabilityTotal_iS = $equityRetainedTotal + $liabilityTaxTotal;
                        $response['getTotals']['equityLiabilityTotal_iS'] = (($equityLiabilityTotal_iS >= 0) ? number_format($equityLiabilityTotal_iS, 2) : "(" . number_format(abs($equityLiabilityTotal_iS), 2) . ")"); //  equity + liability + surplus total
                    }
                }
                // Add cashflow balances to the response for each cashflow type
                // remove -tive from total
                if ($cashFlowBalance < 0) {
                    $cashFlowBalance = '(' . number_format(abs($cashFlowBalance), 2) . ')';
                } else {
                    $cashFlowBalance = number_format($cashFlowBalance, 2);
                }
                // add balance as cashflow attribute
                $cashflowType['balance'] = $cashFlowBalance;
                // add Cashflows to the response array
                $response['cashFlowData'][$cashFlowID] = $cashflowType;
            }
        }
        // Return the response as JSON
        return $response;
    }

    /** entry year */
    public function entry_years()
    {
        $entrys = $this->entry->orderBy('created_at', 'desc')->findAll();
        $years_data = [];
        foreach ($entrys as $key => $value) {
            $years = date('Y', strtotime($value['created_at']));
            $years_data[] = $years;
        }
        return $this->respond(array_unique($years_data));
    }
    public function financial_years()
    {
        return $this->respond(($this->financial_yearsArray()));
    }
    private function financial_yearsArray()
    {
        $entries = $this->entry->orderBy('created_at', 'desc')->findAll();
        $fYears = [];
        // Get the day and month for the financial year start
        $fy_start_day_month = (isset($this->settingsRow['financial_year_start']) ? date('d-M', strtotime($this->settingsRow['financial_year_start'])) : date('d-M', '01-Jan'));

        foreach ($entries as $entry) {
            $entryDate = strtotime($entry['date']);

            // Calculate financial year start and end dates dynamically based on entry date
            $fyStartDate = date('Y-m-d', strtotime($fy_start_day_month . '-' . date('Y', $entryDate)));
            $fyEndDate = date('Y-m-d', strtotime($fy_start_day_month . '-' . (date('Y', $entryDate) + 1)) - 1);

            if ($entryDate >= strtotime($fyStartDate) && $entryDate <= strtotime($fyEndDate)) {
                $financial_year = date('M-y', strtotime($fyStartDate)) . ' TO ' . date('M-y', strtotime($fyEndDate));
                $years_data = $financial_year;
                // add only the unique financial years
                if (!isset($fYears[$years_data])) {
                    $fYears[$years_data] = [
                        'start' => $fyStartDate,
                        'end' => $fyEndDate,
                    ];
                }
            }
        }

        return $fYears;
    }
    /** entry months */
    public function entry_months($year = null)
    {
        if (!isset($year)) {
            $year = date('Y');
        }
        $entries = $this->entry->where(['YEAR(date)' => $year])->orderBy('date', 'asc')->findAll();
        $months = [];
        foreach ($entries as $key => $value) {
            $month = date('M', strtotime($value['date']));
            $months[] = $month;
        }
        return $this->respond(array_unique($months));
    }
    public function generate_quarters()
    {
        // get financial years array
        $financial_years = $this->financial_yearsArray();
        // Extract the first element using array_slice to get current financial year
        $firstElement = array_slice($financial_years, 0, 1, true);
        if (!empty($firstElement)) {
            // loop through the array to get the start & end of the financial year
            foreach ($firstElement as $key => $value) {
                $f_year = $key;
                $fYear_start = $value['start'];
                $fYear_end = $value['end'];
                break; // Exit loop after accessing first element
            }
        } else {
            $fYear_start = date('Y-m-d', strtotime($this->settingsRow['financial_year_start'] . '-' . date('Y')));
            $fYear_end = date('Y-m-d', strtotime($fYear_start . '-' . (date('Y') + 1)) - 1);
        }
        // pick start and end date from the form or set default from the settings
        if (!empty($this->request->getVar('start_date'))) {
            $startDate = date('Y-m-d', strtotime($this->request->getVar('start_date')));
        } else {
            $startDate = $fYear_start;
        }
        if (!empty($this->request->getVar('end_date'))) {
            $endDate = date('Y-m-d', strtotime($this->request->getVar('end_date')));
        } else {
            $endDate = $fYear_end;
        }
        // Validate date format
        if (!$startDate || !$endDate) {
            return $this->respond([
                'status' => 500,
                'error' => 'Invalid Date',
                'message' => "The date format for '$startDate' or '$endDate' is invalid",
            ]);
        } else {
            return $this->respond(($this->generateFinancialYearQuarters($startDate, $endDate)));
        }
    }

    public function generateFinancialYearQuarters($startDate, $endDate)
    {
        // Convert dates to timestamps
        $startDateTimestamp = date('Y-m-d', strtotime($startDate));
        $endDateTimestamp = date('Y-m-d', strtotime($endDate));

        // Initialize quarters array
        $quarters = [
            "First Quarter" => ["start" => null, "end" => null],
            "Second Quarter" => ["start" => null, "end" => null],
            "Third Quarter" => ["start" => null, "end" => null],
            "Last Quarter" => ["start" => null, "end" => null],
        ];

        // Calculate and populate quarter dates
        $currentQuarter = 0;
        $currentDate = ($startDateTimestamp);
        while ($currentDate <= $endDateTimestamp) {
            // stop the loop if the current quarter is greater than 3
            if ($currentQuarter > 3) {
                break;
            }
            $quarterStart = date("Y-m-d", strtotime($currentDate));
            $quarterEnd = date("Y-m-d", strtotime("+3 months", strtotime($currentDate)) - 1);

            $quarters["First Quarter"]["start"] = ($currentQuarter === 0) ? $quarterStart : $quarters["First Quarter"]["start"];
            $quarters["Second Quarter"]["start"] = ($currentQuarter === 1) ? $quarterStart : $quarters["Second Quarter"]["start"];
            $quarters["Third Quarter"]["start"] = ($currentQuarter === 2) ? $quarterStart : $quarters["Third Quarter"]["start"];
            $quarters["Last Quarter"]["start"] = ($currentQuarter === 3) ? $quarterStart : $quarters["Last Quarter"]["start"];

            $quarters[array_keys($quarters)[$currentQuarter]]["end"] = $quarterEnd;

            $currentDate = date("Y-m-d", strtotime("+3 months", strtotime($currentDate)));
            $currentQuarter++;
        }

        return $quarters;
    }

    // get cash flow types with entries as table rows
    private function get_cashflowTypes()
    {
        // get all particulars first whose debit or credit isn't zero
        $particulars = $this->get_particulars();

        $typeIDs = [];
        if (count($particulars) > 0) {
            // loop through the particulars to get their corresponding cash flow types
            foreach ($particulars as $particular) {
                $typeId = $particular['cash_flow_typeId'];

                // Check if the key already exists in $cashflowTypes array
                if (!isset($typeIDs[$typeId])) {
                    $typeIDs[$typeId] = $typeId;
                }
            }

            $cashflowTypes = $this->cashFlow->find($typeIDs);
        } else {
            $cashflowTypes = $this->cashFlow->where(['status' => 'Active'])->find();
        }
        return $cashflowTypes;
    }

    // get categories
    private function get_categories($statement = null)
    {
        switch (strtolower($statement)) {
            case 'balancesheet':
                $statement_id = 1;
                $where = ['statement_id' => $statement_id, 'category_status' => "Active"];
                break;
            case 1:
                $statement_id = 1;
                $where = ['statement_id' => $statement_id, 'category_status' => "Active"];
                break;
            case 'incomestatement':
                $statement_id = 2;
                $where = ['statement_id' => $statement_id, 'category_status' => "Active"];
                break;
            case 2:
                $statement_id = 2;
                $where = ['statement_id' => $statement_id, 'category_status' => "Active"];
                break;
            default:
                $where = ['category_status' => "Active"];
                break;
        }
        $categories = $this->category->where($where)->findAll();
        return $categories;
    }
    // get subcategories
    private function get_subcategories()
    {
        // get all particulars first whose debit or credit isn't zero
        $particulars = $this->get_particulars();

        $subcategoryIDs = [];
        if (count($particulars) > 0) {
            // loop through the particulars to get their corresponding cash flow types
            foreach ($particulars as $particular) {
                $subcategoryId = $particular['subcategory_id'];

                // Check if the key already exists in $subcategoryIDs array
                if (!isset($subcategoryIDs[$subcategoryId])) {
                    $subcategoryIDs[$subcategoryId] = $subcategoryId;
                }
            }
            $subcategories = $this->subcategory
                ->select('subcategories.id, subcategories.subcategory_code, subcategories.subcategory_name, subcategories.category_id, categories.category_name, categories.part, categories.statement_id')
                ->join('categories', 'categories.id = subcategories.category_id', 'left')
                ->find($subcategoryIDs);
        } else {
            $subcategories = $this->subcategory
                ->select('subcategories.id, subcategories.subcategory_name, subcategories.category_id, categories.category_name, categories.part, categories.statement_id')
                ->join('categories', 'categories.id = subcategories.category_id', 'left')
                ->where(['subcategory_status' => 'Active'])->find();
        }
        return $subcategories;
    }
    // get all particulars
    public function get_particulars()
    {
        $particulars = $this->particular
            ->select('particulars.id, particulars.particular_code, particulars.particular_name, particulars.subcategory_id, particulars.category_id, particulars.account_typeId, particulars.cash_flow_typeId, particulars.opening_balance, cash_flow_types.name, subcategories.subcategory_name, categories.category_name, categories.part, categories.statement_id')
            ->join('cash_flow_types', 'cash_flow_types.id = particulars.cash_flow_typeId', 'left')
            ->join('subcategories', 'subcategories.id = particulars.subcategory_id', 'left')
            ->join('categories', 'categories.id = particulars.category_id', 'left')
            ->where(['debit >' => 0])->orwhere(['credit >' => 0])->findAll();
        return $particulars;
    }
    //  get all entries
    public function get_entries($start_date, $end_date)
    {
        $data = $this->entry
            ->select('entries.payment_id, entries.particular_id, entries.amount, entries.ref_id, entries.account_typeId, entries.entry_typeId, entries.entry_menu, entries.status, entries.entry_details, entries.date, account_types.name, entrytypes.type')
            ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->where(['DATE_FORMAT(entries.date, "%Y-%m-%d") >=' => $start_date, 'DATE_FORMAT(entries.date, "%Y-%m-%d") <=' => $end_date])
            ->findAll();
        // echo json_encode($data);
        return $data;
    }

    public function particular_entries()
    {
        $start_date = $this->request->getVar("start_date");
        $end_date = $this->request->getVar("end_date");
        $response = [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
        $data = $this->entry
            ->select('entries.payment_id, entries.particular_id, entries.amount, entries.ref_id, entries.account_typeId, entries.entry_typeId, entries.entry_menu, entries.status, entries.date, entries.entry_details, account_types.name, entrytypes.type')
            ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->where(['DATE_FORMAT(entries.date, "%Y-%m-%d") >=' => $start_date, 'DATE_FORMAT(entries.date, "%Y-%m-%d") <=' => $end_date])
            ->findAll();
        $response['data'] = $data;
        return $this->respond($response);
    }
}
