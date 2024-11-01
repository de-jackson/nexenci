<?php

namespace App\Controllers\Client;

use App\Controllers\Client\MainController;

use \Hermawan\DataTables\DataTable;

class Reports extends MainController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Reports';
        $this->title = 'Reports';
        $this->menuItem = [
            'title' => $this->title,
            'menu' => $this->menu,
        ];

        if (strtolower($this->userRow["account_type"]) == 'client') {
            $this->report->setUserAccountType([
                # Index: 0 account type i.e Administrator, Employee, Client
                strtolower($this->userRow["account_type"]),
                # Index: 1
                $this->userRow["id"]
            ]);
        }
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function module($menu)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {

            switch (strtolower($menu)) {

                case 'savings':
                    $view = 'client/reports/savings/savings';
                    break;

                case 'products':
                    $view = 'client/reports/loan/products';
                    break;

                case 'applications':
                    $view = 'client/reports/loan/applications';
                    break;

                case 'disbursements':
                    $view = 'client/reports/loan/disbursements';
                    break;

                case 'loans':
                    $view = 'admin/reports/modules/loan/clients';
                    break;

                case 'repayments':
                    $view = 'client/reports/loan/repayments';
                    break;

                case 'arrears':
                    $view = 'client/reports/loan/arrears';
                    break;

                case 'branches':
                    $view = 'admin/reports/modules/company/branches';
                    break;

                case 'clients':
                    $view = 'admin/reports/modules/clients/clients';
                    break;

                case 'staff':
                    $view = 'admin/reports/modules/staff/staff';
                    break;

                case 'collections':
                    $view = 'admin/reports/modules/loan/collections';
                    break;

                case 'collectors':
                    $view = 'admin/reports/modules/loan/collectors';
                    break;

                default:
                    $view = 'layout/404';
                    break;
            }

            return view($view, [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                // 'applicationsCounter' => $this->getTableCounts('applications'),
                // 'disbursementsCounter' => $this->getTableCounts('disbursements'),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('/client/dashboard'));
        }
    }

    public function getEntryYears($account_typeId)
    {
        $years = $this->report->getUniqueEntryYears($account_typeId);
        return $this->respond(($years));
    }

    public function types($menu, $id = null, $submenu = null)
    {
        switch (strtolower($menu)) {
            case 'gender':
                $results = $this->settings->generateGender();
                break;

            case 'accountstatus':
                $results = $this->settings->generateAccountStatus();
                break;

            case 'clientyears':
                # getUniqueTableYears() is having an arguement as table name
                $results = $this->report->getUniqueTableYears("clients");
                break;

            case 'staffyears':
                # getUniqueTableYears() is having an arguement as table name
                $results = $this->report->getUniqueTableYears("staffs");
                break;

            case 'loanproductyears':
                # getUniqueTableYears() is having an arguement as table name
                $results = $this->report->getUniqueTableYears("loanproducts");
                break;

            case 'disbursementyears':
                # getUniqueTableYears() is having an arguement as table name
                $results = $this->report->getUniqueTableYears("disbursements");
                break;

            case 'disbursementstatus':
                $results = $this->settings->generateDisbursementStatus();
                break;

            case 'loanapplicationyears':
                # getUniqueTableYears() is having an arguement as table name
                $results = $this->report->getUniqueTableYears("loanapplications");
                break;

            case 'loanapplicationlevels':
                $results = $this->settings->generateLoanApplicationLevels();
                break;

            case 'loanapplicationactions':
                $results = $this->settings->generateLoanApplicationActions();
                break;

            case 'loanapplicationstatus':
                $results = $this->settings->generateLoanApplicationStatus();
                break;

            case 'maritalstatus':
                $results = $this->settings->generateMaritalStatus();
                break;

            case 'religion':
                $results = $this->settings->generateReligion();
                break;

            case 'occupation':
                $results = $this->settings->generateOccupations();
                break;

            case 'nationality':
                $results = $this->settings->generateNationality();
                break;

            case 'relationships':
                $results = $this->settings->generateRelationships();
                break;

            case 'idtypes':
                $results = $this->settings->generateIDTypes();
                break;

            case 'accounttypes':
                $results = $this->settings->generateAccountTypes();
                break;

            case 'staffaccounttypes':
                $results = $this->settings->generateStaffAccountTypes();
                break;

            case 'interesttypes':
                $results = $this->settings->generateInterestTypes();
                break;

            case 'loanrepaymentdurations':
                # get loan repayment durations
                $results = $this->settings->generateLoanRepaymentDurations();
                break;

            case 'appointmenttypes':
                $results = $this->settings->generateAppointments();
                break;

            case 'repayments':
                $results = $this->settings->generateRepayments();
                break;

            case 'chargemodes':
                $results = $this->settings->generateChargeModes();
                break;

            case 'client':
                $results = $this->getClientByID($id);

            case 'staff':
                $results = $this->staff->where([
                    'deleted_at' => NULL
                ])->orderBy('id', 'desc')->findAll();
                break;

            case 'branches':
                # Check the account type
                if (strtolower($this->userRow["account_type"]) == 'client') {
                    $condition = [
                        'branch_status' => 'Active',
                        'id' => $this->userRow["branch_id"]
                    ];
                } else {
                    $condition = ['branch_status' => 'Active'];
                }
                $results = $this->branch->where($condition)->orderBy('id', 'desc')->findAll();
                break;

            case 'departments':
                $results = $this->department->where([
                    'department_status' => 'Active'
                ])->findAll();
                break;

            case 'clients':
                # Check the account type
                if (strtolower($this->userRow["account_type"]) == 'client') {
                    $condition = ['access_status' => 'Active', 'id' => $this->userRow["id"]];
                } else {
                    $condition = ['access_status' => 'Active'];
                }
                $results = $this->client->where($condition)->findAll();
                break;

            case 'currencies':
                $results = $this->currency->where(['status' => 'Active'])->findAll();
                break;

            case 'products':
                $results = $this->loanProduct->where(['status' => 'Active'])->findAll();
                break;

            case 'payments':
                $results = $this->particular->where(
                    [
                        'account_typeId' => 1,
                        'particular_status' => 'Active'
                    ]
                )->findAll();
                break;

            case 'particulars':
                $results = $this->particular
                    ->select('particulars.*, subcategories.id as s_id, subcategories.subcategory_name, subcategories.subcategory_status, subcategories.subcategory_slug, subcategories.created_at as screated, subcategories.updated_at as supdated, categories.id as c_id, categories.category_name,categories.category_slug, categories.part, statements.name as statement, account_types.name as account_type')
                    ->join('subcategories', 'subcategories.id = particulars.subcategory_id', 'left')
                    ->join('categories', 'categories.id = particulars.category_id', 'left')
                    ->join('statements', 'statements.id = categories.statement_id', 'left')
                    ->join('account_types', 'account_types.id = particulars.account_typeId', 'left')
                    ->join('cash_flow_types', 'cash_flow_types.id = particulars.cash_flow_typeId', 'left')
                    ->where(['particulars.account_typeId' => $id, 'particulars.particular_status' => 'Active'])
                    ->findAll();
                break;

            case 'ledger':
                $results = $this->getParticularById($id);
                break;

            case 'productsinfo':
                $results = $this->loanProduct->find($id);
                break;

            case 'application':
                $results = $this->getLoanApplicationById($id);
                break;

            case 'cancelapplication':
                $results = $this->cancelLoanApplication($id);
                break;

            case 'account_entry_types':
                if ($submenu == "repayments") {
                    $where = ['entrytypes.account_typeId' => $id, 'entrytypes.part' => "credit", 'entrytypes.status' => 'Active'];
                } else {
                    $where = ['entrytypes.account_typeId' => $id, 'entrytypes.status' => 'Active'];
                }

                $results = $this->entryType->select('entrytypes.*,')
                    ->join('account_types', 'account_types.id = entrytypes.account_typeId', 'left')
                    ->where($where)->orderBy('entrytypes.part', 'desc')->findAll();
                break;

            case 'transactions':
                if ($submenu == "repayments") {
                    $where = ['entrytypes.account_typeId' => $id, 'entrytypes.part' => "credit", 'entrytypes.status' => 'Active'];
                } else {
                    $where = ['entrytypes.account_typeId' => $id, 'entrytypes.status' => 'Active'];
                }

                $results = $this->entryType->select('entrytypes.*,')
                    ->join('account_types', 'account_types.id = entrytypes.account_typeId', 'left')
                    ->where($where)->orderBy('entrytypes.part', 'desc')->findAll();
                break;

            case 'entrytype':
                $results = $this->entryType->find($id);
                break;

            case 12:
                $results = $this->particular
                    ->select('particulars.*, subcategories.id as s_id, subcategories.subcategory_name, subcategories.subcategory_status, subcategories.subcategory_slug, subcategories.created_at as screated, subcategories.updated_at as supdated, categories.id as c_id, categories.category_name,categories.category_slug, categories.part, statements.name as statement, account_types.name as account_type')
                    ->join('subcategories', 'subcategories.id = particulars.subcategory_id', 'left')
                    ->join('categories', 'categories.id = particulars.category_id', 'left')
                    ->join('statements', 'statements.id = categories.statement_id', 'left')
                    ->join('account_types', 'account_types.id = particulars.account_typeId', 'left')
                    ->join('cash_flow_types', 'cash_flow_types.id = particulars.cash_flow_typeId', 'left')
                    ->where(['particulars.account_typeId' => 12, 'particulars.particular_status' => 'Active'])
                    ->findAll();
                break;

            default:
                $results = [];
                break;
        }

        return $this->respond(($results));
    }

    public function getEntryTransactions($account_typeId)
    {

        $results = $this->entryType->select('entrytypes.*,')
            ->join('account_types', 'account_types.id = entrytypes.account_typeId', 'left')
            ->where([
                'entrytypes.account_typeId' => $account_typeId,
                'entrytypes.status' => 'Active'
            ])->orderBy('entrytypes.part', 'desc')->findAll();

        return $this->respond(($results));
    }

    public function getReportType($menu)
    {
        switch (strtolower($menu)) {
            case 'branches':
                # generate branches report
                $reportResults = $this->branchesReport();
                # $reportResults = $this->report->getBranchesReport();
                break;

            case 'clients':
                # generate clients report
                $reportResults = $this->clientsReport();
                break;

            case 'staff':
                # generate staff report
                $reportResults = $this->staffAccountsReport();
                break;

            case 'products':
                # generate loan products report
                $reportResults = $this->loanProductsReport();
                break;

            case 'applications':
                # generate loan applications report
                $reportResults = $this->loanApplicationsReport();
                break;

            case 'disbursements':
                # generate loan disbursement report
                $reportResults = $this->loanDisbursementReport();
                break;

            case 'repayments':
                # generate loan repayments report
                $reportResults = $this->loanRepaymentsReport();
                break;

            case 'arrears':
                # generate loan arrears report
                $reportResults = $this->loanArrearsReport();
                break;


            case 'savings':
                # generate savings report
                $reportResults = $this->savingsReport();
                break;

            default:
                # no report found thus, set empty array
                $reportResults = [];
                break;
        }

        return $reportResults;
    }

    public function branchesReport()
    {
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        if (!empty($start_date) && !empty($end_date)) {
            $this->report->setStartDate($this->request->getVar('start_date'));
            $this->report->setEndDate($this->request->getVar('end_date'));
        }

        if (!empty($this->request->getVar('year'))) {
            $this->report->setYear($this->request->getVar('year'));
        }

        if (!empty($this->request->getVar('entry_status'))) {
            $this->report->setEntryStatus($this->request->getVar('entry_status'));
        }

        if (!empty($this->request->getVar('gender'))) {
            $this->report->setGender($this->request->getVar('gender'));
        }

        if (!empty($this->request->getVar('account_no'))) {
            $this->report->setAccountNo($this->request->getVar('account_no'));
        }

        if (!empty($this->request->getVar('others'))) {
            $this->report->setSearch($this->request->getVar('others'));
        }

        if (!empty($this->request->getVar('payment_id'))) {
            $this->report->setPaymentMethod($this->request->getVar('payment_id'));
        }

        if (!empty($this->request->getVar('entry_typeId'))) {
            $this->report->setTransactionEntryType($this->request->getVar('entry_typeId'));
        }

        if (!empty($this->request->getVar('reference_id'))) {
            $this->report->setReference($this->request->getVar('reference_id'));
        }

        if (!empty($this->request->getVar('branch_id'))) {
            # selected multiple branches as an array ["1", "2"]
            $this->report->setBranch($this->request->getVar('branch_id'));
        }

        if (!empty($this->request->getVar('staff_id'))) {
            $this->report->setStaff($this->request->getVar('staff_id'));
        }

        # get the savings collection report
        $savingsResults = $this->report->getSavingsAccountReport();
        # results array to hold the monthly savings collection report
        $results = [];
        # from the savingsResults, pick only the monthlyEntries
        foreach ($savingsResults["monthlyEntries"] as $key => $savingResults) {
            # check the savings collection report existance
            if (count($savingResults) > 1) {
                $debitAmount = $creditAmount = $amount = [];
                # set the number of savings entries
                $clientsTotal = $debitClientsTotal = $creditClientsTotal = [];
                foreach ($savingResults as $k2 => $v2) {
                    if ($v2) {
                        # check whether the entry status is debit
                        if (strtolower($v2['status']) == "debit") {
                            # debit total client amount
                            $debitAmount[] = $v2['amount'];
                            # count the total number clients with debit
                            $debitClientsTotal[] = $v2['client_id'];
                        }
                        # check whether the entry status is credit
                        if (strtolower($v2['status']) == "credit") {
                            # crdeit total client amount
                            $creditAmount[] = $v2['amount'];
                            # count the total number clients with credit
                            $creditClientsTotal[] = $v2['client_id'];
                        }
                        $amount[] = $v2['amount'];
                        # count the total number of clients
                        $clientsTotal[] = $v2['client_id'];
                    }
                }
                # calculate the total amount of credit, debit
                $results[$key][] = array_sum($creditAmount);
                $results[$key][] = array_sum($debitAmount);
                $results[$key][] = array_sum($amount);
                # count unique number of clients
                $results[$key][] = count(array_unique(($creditClientsTotal)));
                $results[$key][] = count(array_unique(($debitClientsTotal)));
                # add total of credit and debit
                $clients = array_merge(array_unique(($creditClientsTotal)), array_unique(($debitClientsTotal)));
                # $results[$key][] = count($clients);
                $results[$key][] = count(array_unique(($clientsTotal)));
            } else {
                # set the total amount of credit, debit to zero
                $results[$key][] = 0;
                $results[$key][] = 0;
                $results[$key][] = 0;
                # count unique number of clients
                $results[$key][] = 0;
                $results[$key][] = 0;
                $results[$key][] = 0;
            }
        }

        # set the array variables to hold monthly total amount
        $branchesSavings = [];
        $monthlyBranchesTotalAmount = [];
        $monthlyBranchesTotalCredit = $monthlyBranchesTotalDebit = [];
        $no = $counter = 0;
        # get branches savings collection report
        $branchesResults = $this->report->getBranchesReport();
        # from the branchesResults, pick only the monthlyBranches
        foreach ($branchesResults["monthlyBranches"] as $key => $branches) {
            # check the savings collection report existance
            if (count($branches) > 1) {
                foreach ($branches as $k2 => $branch) {
                    if ($branch) {
                        # get the year month
                        $year_month = $key;
                        # get the branch id
                        $branch_id = $branch['id'];
                        # get total for the savings collection only
                        $account_typeId = 12;
                        # get total amount, total credit, total debit
                        $summation = $this->report->getEntriesSummationReport(
                            $year_month,
                            $branch_id,
                            $account_typeId
                        );

                        # get total credit amount
                        $monthlyBranchesTotalCredit[] = $summation["creditTotalAmount"];
                        # get total debit amount
                        $monthlyBranchesTotalDebit[] = $summation["debitTotalAmount"];
                        # get total amount
                        $monthlyBranchesTotalAmount[] = $summation["totalAmount"];

                        # skip the monthly branches with zero as total amount
                        if ($summation["totalAmount"] == 0) {
                            # uncomment this to skip the branches with zero
                            // continue;
                        }

                        $no++;
                        $row = [];

                        $row[] = $key;
                        $row[] = $branch['branch_name'];
                        $row[] = number_format($summation["creditTotalAmount"], 2);
                        $row[] = number_format($summation["debitTotalAmount"], 2);
                        $row[] = number_format($summation["totalAmount"], 2);

                        $branchesSavings[] = $row;
                    }
                }
            } else {
                # set the monthly total to zeror
                $monthlyBranchesTotalCredit[] = 0;
                $monthlyBranchesTotalDebit[] = 0;
                $monthlyBranchesTotalAmount[] = 0;

                $no++;
                $row = [];
                $row[] = $key;
                $row[] = '';
                $row[] = number_format(0, 2);
                $row[] = number_format(0, 2);
                $row[] = number_format(0, 2);

                $branchesSavings[] = $row;
            }
        }

        # set the array variables to hold monthly total amount
        $dataArray = $clientsCounter = [];
        $monthlyTotalAmount = $monthlyTotalCredit = $monthlyTotalDebit = [];
        # clients counter
        $monthlyClientsNumber = $monthlyCreditClientsNo = [];
        $monthlyDebitClientsNo = [];
        $no = $counter = 0;
        # client amount
        foreach ($results as $year_month => $amount) {
            $monthlyTotalCredit[] = $amount[0];
            $monthlyTotalDebit[] = $amount[1];
            $monthlyTotalAmount[] = $amount[2];

            $no++;
            $row = [];
            $row[] = $year_month;
            $row[] = number_format($amount[0], 2);
            $row[] = number_format($amount[1], 2);
            $row[] = number_format($amount[2], 2);
            $dataArray[] = $row;
        }

        # count the number of clients
        foreach ($results as $year_month_client => $client) {
            # count the total monthly number of clients with credit
            $monthlyCreditClientsNo[] = $client[3];
            # count the total monthly number of clients with debit
            $monthlyDebitClientsNo[] = $client[4];
            # count the total monthly number of clients
            $monthlyClientsNumber[] = $client[5];

            $counter++;
            $row = [];
            $row[] = $year_month_client;
            $row[] = $client[3];
            $row[] = $client[4];
            $row[] = $client[5];

            $clientsCounter[] = $row;
        }

        # client savings entries
        $entriesResults = [];
        $sno = $total = 0;

        # from the savingsResults, pick only the entries results
        foreach ($savingsResults["entries"] as $key => $entry) {
            $total += $entry['amount'];
            $sno++;
            $row = [];
            $row[] = $sno;
            $row[] = $entry['date'];
            $row[] = $entry['name'];
            $row[] = $entry['account_no'];
            $row[] = number_format($entry['amount'], 2);
            $row[] = ucwords($entry['type']);
            $row[] = ucwords($entry['status']);
            $row[] = number_format($total, 2);

            $entriesResults[] = $row;
        }

        return $this->respond(([
            'branches' => [
                # branches savings transactions
                "branchesSavings" => $branchesSavings,
                'entry' => [
                    # yearly branches total transactions
                    'entry' => number_format(array_sum($monthlyBranchesTotalAmount), 2),
                    # yearlt branches total debit trnsactions
                    'debit' => number_format(array_sum($monthlyBranchesTotalDebit), 2),
                    # yearly branches total credit transactions
                    'credit' => number_format(array_sum($monthlyBranchesTotalCredit), 2),
                    # monthly branches total transactions
                    'monthlyBranchesTotal' => $monthlyBranchesTotalAmount,
                    # monthly 
                    'monthly' => $dataArray,
                ],
                # savings collections
                'collections' => [
                    # compute yearly total savings collection
                    'total' => number_format(array_sum($monthlyTotalAmount), 2),
                    # compute monthly total savings collection
                    'monthly' => $monthlyTotalAmount,
                    # compute yearly total debit transactions
                    'debit' => number_format(array_sum($monthlyTotalDebit), 2),
                    # compute yearly total credit transactions
                    'credit' => number_format(array_sum($monthlyTotalCredit), 2),
                ],
                # clients counter
                'clients' => [
                    # monthly
                    'monthlyTotalClientsNumber' => array_unique($monthlyClientsNumber),
                    # clients counter
                    "clientsCounter" => $clientsCounter,
                    # compute yearly total number of clients
                    'total' => array_sum(array_unique($monthlyClientsNumber)),
                    # compute yearly total number of clients with debits
                    'debits' => array_sum(array_unique($monthlyDebitClientsNo)),
                    # compute yearly total number of clients with credits
                    'credits' => array_sum(array_unique($monthlyCreditClientsNo)),
                    # entries transactions
                    'entries' => $entriesResults,
                ],
            ],
        ]));
    }

    public function savingsReport()
    {
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        if (!empty($start_date) && !empty($end_date)) {
            $this->report->setStartDate($this->request->getVar('start_date'));
            $this->report->setEndDate($this->request->getVar('end_date'));
        }

        if (!empty($this->request->getVar('year'))) {
            $this->report->setYear($this->request->getVar('year'));
        }

        if (!empty($this->request->getVar('entry_status'))) {
            $this->report->setEntryStatus($this->request->getVar('entry_status'));
        }

        if (!empty($this->request->getVar('gender'))) {
            $this->report->setGender($this->request->getVar('gender'));
        }

        if (!empty($this->request->getVar('account_no'))) {
            $this->report->setAccountNo($this->request->getVar('account_no'));
        }

        if (!empty($this->request->getVar('others'))) {
            $this->report->setSearch($this->request->getVar('others'));
        }

        if (!empty($this->request->getVar('payment_id'))) {
            $this->report->setPaymentMethod($this->request->getVar('payment_id'));
        }

        if (!empty($this->request->getVar('entry_typeId'))) {
            $this->report->setTransactionEntryType($this->request->getVar('entry_typeId'));
        }

        if (!empty($this->request->getVar('reference_id'))) {
            $this->report->setReference($this->request->getVar('reference_id'));
        }

        if (!empty($this->request->getVar('branch_id'))) {
            # selected multiple branches as an array ["1", "2"]
            $this->report->setBranch($this->request->getVar('branch_id'));
        }

        if (!empty($this->request->getVar('staff_id'))) {
            $this->report->setStaff($this->request->getVar('staff_id'));
        }
        # get the savings collection report
        $savingsResults = $this->report->getSavingsAccountReport();
        # results array to hold the monthly savings collection report
        $results = [];
        # from the savingsResults, pick only the monthlyEntries
        foreach ($savingsResults["monthlyEntries"] as $key => $savingResults) {
            # check the savings collection report existance
            if (count($savingResults) > 1) {
                $debitAmount = $creditAmount = $amount = [];
                # set the number of savings entries
                $clientsTotal = $debitClientsTotal = $creditClientsTotal = [];
                foreach ($savingResults as $k2 => $v2) {
                    if ($v2) {
                        # check whether the entry status is debit
                        if (strtolower($v2['status']) == "debit") {
                            # debit total client amount
                            $debitAmount[] = $v2['amount'];
                            # count the total number clients with debit
                            $debitClientsTotal[] = $v2['client_id'];
                        }
                        # check whether the entry status is credit
                        if (strtolower($v2['status']) == "credit") {
                            # crdeit total client amount
                            $creditAmount[] = $v2['amount'];
                            # count the total number clients with credit
                            $creditClientsTotal[] = $v2['client_id'];
                        }
                        $amount[] = $v2['amount'];
                        # count the total number of clients
                        $clientsTotal[] = $v2['client_id'];
                    }
                }
                # calculate the total amount of credit, debit
                $results[$key][] = array_sum($creditAmount);
                $results[$key][] = array_sum($debitAmount);
                $results[$key][] = array_sum($amount);
                # count unique number of clients
                $results[$key][] = count(array_unique(($creditClientsTotal)));
                $results[$key][] = count(array_unique(($debitClientsTotal)));
                # add total of credit and debit
                $clients = array_merge(array_unique(($creditClientsTotal)), array_unique(($debitClientsTotal)));
                $results[$key][] = count($clients);
                # $results[$key][] = count(array_unique(($clientsTotal)));

            } else {
                # set the total amount of credit, debit to zero
                $results[$key][] = 0;
                $results[$key][] = 0;
                $results[$key][] = 0;
                # count unique number of clients
                $results[$key][] = 0;
                $results[$key][] = 0;
                $results[$key][] = 0;
            }
        }

        # set the array variables to hold monthly total amount
        $dataArray = $clientsCounter = [];
        $monthlyTotalAmount = $monthlyTotalCredit = $monthlyTotalDebit = [];
        # clients counter
        $monthlyClientsNumber = $monthlyCreditClientsNo = [];
        $monthlyDebitClientsNo = [];
        $no = $counter = 0;
        # client amount
        foreach ($results as $year_month => $amount) {
            $monthlyTotalCredit[] = $amount[0];
            $monthlyTotalDebit[] = $amount[1];
            $monthlyTotalAmount[] = $amount[2];

            $no++;
            $row = [];
            $row[] = $year_month;
            $row[] = number_format($amount[0], 2);
            $row[] = number_format($amount[1], 2);
            $row[] = number_format($amount[2], 2);
            $dataArray[] = $row;
        }

        # count the number of clients
        foreach ($results as $year_month_client => $client) {
            # count the total monthly number of clients with credit
            $monthlyCreditClientsNo[] = $client[3];
            # count the total monthly number of clients with debit
            $monthlyDebitClientsNo[] = $client[4];
            # count the total monthly number of clients
            $monthlyClientsNumber[] = $client[5];

            $counter++;
            $row = [];
            $row[] = $year_month_client;
            $row[] = $client[3];
            $row[] = $client[4];
            $row[] = $client[5];

            $clientsCounter[] = $row;
        }

        # client savings entries
        $entriesResults = [];
        $sno = $total = 0;

        # from the savingsResults, pick only the entries results
        foreach ($savingsResults["entries"] as $key => $entry) {
            $total += $entry['amount'];
            $sno++;
            $row = [];
            $row[] = $sno;
            $row[] = $entry['date'];
            $row[] = $entry['name'];
            $row[] = $entry['account_no'];
            $row[] = number_format($entry['amount'], 2);
            $row[] = ucwords($entry['type']);
            $row[] = ucwords($entry['status']);
            $row[] = number_format($total, 2);

            $entriesResults[] = $row;
        }

        return $this->respond(([
            "data" => $dataArray,
            'entries' => $entriesResults,
            'yearlyTotal' => number_format(array_sum($monthlyTotalAmount), 2),
            'monthlyTotal' => $monthlyTotalAmount,
            'yearlyTotalDebit' => number_format(array_sum($monthlyTotalDebit), 2),
            'yearlyTotalCredit' => number_format(array_sum($monthlyTotalCredit), 2),
            'monthlyTotalClientsNumber' => $monthlyClientsNumber,
            "clientsCounter" => $clientsCounter,
            'yearlyClientsNumber' => array_sum($monthlyClientsNumber),
            'yearlyDebitClientsNumber' => array_sum($monthlyDebitClientsNo),
            'yearlyCreditClientsNumber' => array_sum($monthlyCreditClientsNo),
        ]));
    }

    public function clientsReport()
    {
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        if (!empty($start_date) && !empty($end_date)) {
            $this->report->setStartDate($this->request->getVar('start_date'));
            $this->report->setEndDate($this->request->getVar('end_date'));
        }

        if (!empty($this->request->getVar('year'))) {
            $this->report->setYear($this->request->getVar('year'));
        }

        if (!empty($this->request->getVar('entry_status'))) {
            $this->report->setEntryStatus($this->request->getVar('entry_status'));
        }

        if (!empty($this->request->getVar('gender'))) {
            $this->report->setGender($this->request->getVar('gender'));
        }

        if (!empty($this->request->getVar('account_no'))) {
            $this->report->setAccountNo($this->request->getVar('account_no'));
        }

        if (!empty($this->request->getVar('others'))) {
            $this->report->setSearch($this->request->getVar('others'));
        }

        if (!empty($this->request->getVar('status'))) {
            $this->report->setPaymentMethod($this->request->getVar('status'));
        }

        if (!empty($this->request->getVar('location'))) {
            $this->report->setTransactionEntryType($this->request->getVar('location'));
        }

        if (!empty($this->request->getVar('reference_id'))) {
            $this->report->setReference($this->request->getVar('reference_id'));
        }

        if (!empty($this->request->getVar('branch_id'))) {
            # selected multiple branches as an array ["1", "2"]
            $this->report->setBranch($this->request->getVar('branch_id'));
        }

        if (!empty($this->request->getVar('staff_id'))) {
            $this->report->setStaff($this->request->getVar('staff_id'));
        }

        # get the clients report
        $clientsResults = $this->report->getClientsReport();
        # set array variable to hold the monthly total clients numbers
        $results = [];
        # from the clientsResults, pick only the monthlyClients results
        foreach ($clientsResults["monthlyClients"] as $key => $clientsInfo) {
            # check the clients existance
            if (count($clientsInfo) > 1) {
                # store the number of clients who are active and inactive
                $activeClients = $inactiveClients = $clientsTotal = [];
                foreach ($clientsInfo as $k2 => $client) {
                    if ($client) {
                        # check whether the access status is active
                        if (strtolower($client['access_status']) == "active") {
                            # count the number of active clients
                            $activeClients[] = $client['id'];
                        }
                        # check whether the access status is inactive
                        if (strtolower($client['access_status']) == "inactive") {
                            # count the number of inactive clients
                            $inactiveClients[] = $client['id'];
                        }
                        # count the total number of clients
                        $clientsTotal[] = $client['id'];
                    }
                }
                # count unique number of clients
                $results[$key][] = count(array_unique(($activeClients)));
                $results[$key][] = count(array_unique(($inactiveClients)));
                # count the total of active clients and inactive
                $clientsNumber = array_merge(array_unique(($activeClients)), array_unique(($inactiveClients)));
                $results[$key][] = count($clientsNumber);
                # $results[$key][] = count(array_unique(($clientsTotal)));

            } else {
                # count unique number of clients
                $results[$key][] = 0;
                $results[$key][] = 0;
                $results[$key][] = 0;
            }
        }

        # clients counter
        $monthlyClientsTotal = $monthlyActiveClients = [];
        $clientsCounter = $monthlyInactiveClients = [];
        $counter = 0;
        # count the number of clients
        foreach ($results as $year_month_client => $record) {
            # count the total monthly number of qctive clients
            $monthlyActiveClients[] = $record[0];
            # count the total monthly number of clients with debit
            $monthlyInactiveClients[] = $record[1];
            # count the total monthly number of clients
            $monthlyClientsTotal[] = $record[2];

            $counter++;
            $row = [];
            $row[] = $year_month_client;
            $row[] = $record[0];
            $row[] = $record[1];
            $row[] = $record[2];

            $clientsCounter[] = $row;
        }

        # client table information
        $clientsArray = [];
        $sno = 0;
        # from the clientsResults, pick only the clients results
        foreach ($clientsResults["clients"] as $key => $client) {
            $sno++;
            $row = [];
            $row[] = $sno;
            $row[] = $client['created_at'] ? date("d-m-Y H:i:s", strtotime($client['created_at'])) : $client['created_at'];
            $row[] = $client['name'];
            $row[] = $client['mobile'];
            $row[] = $client['account_no'];
            $row[] = number_format($client['account_balance'], 2);
            $row[] = ucwords($client['access_status']);
            # check the client access status
            if (strtolower($client['access_status']) == 'active') {
                $text = "text-info";
            } else {
                $text = "text-danger";
            }
            # display the view client button
            $row[] = '
                <div class="text-center">
                    <a href="javascript:void(0)" onclick="viewClient(' . "'" . $client['id'] . "'" . ')" title="view client" class="' . $text . '"><i class="fas fa-eye"></i></a>
                </div>
            ';

            $clientsArray[] = $row;
        }

        return $this->respond(([
            'clients' => $clientsArray,
            'monthlyClientsTotal' => $monthlyClientsTotal,
            "clientsCounter" => $clientsCounter,
            'yearlyClientsTotal' => array_sum($monthlyClientsTotal),
            'yearlyActiveClientsTotal' => array_sum($monthlyActiveClients),
            'yearlyInactiveClientsTotal' => array_sum($monthlyInactiveClients),
        ]));
    }

    public function staffAccountsReport()
    {
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        if (!empty($start_date) && !empty($end_date)) {
            $this->report->setStartDate($this->request->getVar('start_date'));
            $this->report->setEndDate($this->request->getVar('end_date'));
        }

        if (!empty($this->request->getVar('year'))) {
            $this->report->setYear($this->request->getVar('year'));
        }

        if (!empty($this->request->getVar('entry_status'))) {
            $this->report->setEntryStatus($this->request->getVar('entry_status'));
        }

        if (!empty($this->request->getVar('gender'))) {
            $this->report->setGender($this->request->getVar('gender'));
        }

        if (!empty($this->request->getVar('account_no'))) {
            $this->report->setAccountNo($this->request->getVar('account_no'));
        }

        if (!empty($this->request->getVar('others'))) {
            $this->report->setSearch($this->request->getVar('others'));
        }

        if (!empty($this->request->getVar('status'))) {
            $this->report->setPaymentMethod($this->request->getVar('status'));
        }

        if (!empty($this->request->getVar('qualification'))) {
            $this->report->setTransactionEntryType($this->request->getVar('qualification'));
        }

        if (!empty($this->request->getVar('reference_id'))) {
            $this->report->setReference($this->request->getVar('reference_id'));
        }

        if (!empty($this->request->getVar('branch_id'))) {
            # selected multiple branches as an array ["1", "2"]
            $this->report->setBranch($this->request->getVar('branch_id'));
        }

        if (!empty($this->request->getVar('staff_id'))) {
            $this->report->setStaff($this->request->getVar('staff_id'));
        }

        if (!empty($this->request->getVar('department_id'))) {
            $this->report->setDepartment($this->request->getVar('department_id'));
        }

        if (!empty($this->request->getVar('position_id'))) {
            $this->report->setPosition($this->request->getVar('position_id'));
        }

        if (!empty($this->request->getVar('appointment_type'))) {
            $this->report->setAppointmentType($this->request->getVar('appointment_type'));
        }

        if (!empty($this->request->getVar('staff_account_type'))) {
            $this->report->setAccountType($this->request->getVar('staff_account_type'));
        }

        # get staff accounts information
        $staffResults = $this->report->getStaffsReport();
        # set results to hold the monthly total number of staff
        $results = [];
        # from the staffResults, pick only the monthlyStaffResults
        foreach ($staffResults["monthlyStaffResults"] as $key => $staffInfo) {
            # check the staff accounts existancr
            if (count($staffInfo) > 1) {
                # store the number of staff who are active and inactive
                $activeStaff = $inactiveStaff = $staffTotal = [];
                foreach ($staffInfo as $k2 => $staff) {
                    if ($staff) {
                        # check whether the access status is active
                        if (strtolower($staff['access_status']) == "active") {
                            # count the number of active staff
                            $activeStaff[] = $staff['id'];
                        }
                        # check whether the access status is inactive
                        if (strtolower($staff['access_status']) == "inactive") {
                            # count the number of inactive staff
                            $inactiveStaff[] = $staff['id'];
                        }
                        # count the total number of staff
                        $staffTotal[] = $staff['id'];
                    }
                }
                # count unique number of staff
                $results[$key][] = count(array_unique(($activeStaff)));
                $results[$key][] = count(array_unique(($inactiveStaff)));
                # count the total of active staff and inactive
                $staffTotalNumber = array_merge(array_unique(($activeStaff)), array_unique(($inactiveStaff)));
                $results[$key][] = count($staffTotalNumber);
                # $results[$key][] = count(array_unique(($staffTotal)));

            } else {
                # count unique number of staff
                $results[$key][] = 0;
                $results[$key][] = 0;
                $results[$key][] = 0;
            }
        }

        # staff counter
        $monthlyStaffTotal = $monthlyActiveStaff = [];
        $clientsCounter = $monthlyInactiveStaff = [];
        $counter = 0;
        # count the number of staff
        foreach ($results as $year_month_client => $record) {
            # count the total monthly number of active staff
            $monthlyActiveStaff[] = $record[0];
            # count the total monthly number of inactive staff
            $monthlyInactiveStaff[] = $record[1];
            # count the total monthly number of staff
            $monthlyStaffTotal[] = $record[2];

            $counter++;
            $row = [];
            $row[] = $year_month_client;
            $row[] = $record[0];
            $row[] = $record[1];
            $row[] = $record[2];

            $clientsCounter[] = $row;
        }

        # staff table information
        $staffArray = [];
        $sno = 0;
        # from the staffResults, pick only the staffAccounts
        foreach ($staffResults["staffAccounts"] as $key => $staff) {
            $sno++;
            $row = [];
            $row[] = $sno;
            $row[] = $staff['created_at'] ? date("d-m-Y H:i:s", strtotime($staff['created_at'])) : $staff['created_at'];
            $row[] = $staff['staff_name'];
            $row[] = $staff['mobile'];
            $row[] = $staff['staffID'];
            $row[] = $staff['position'];
            $row[] = ucwords($staff['access_status']);
            # check the staff access status
            if (strtolower($staff['access_status']) == 'active') {
                $text = "text-info";
            } else {
                $text = "text-danger";
            }
            # display the view staff button
            $row[] = '
                <div class="text-center">
                    <a href="javascript:void(0)" onclick="viewStaffAccount(' . "'" . $staff['id'] . "'" . ')" title="View ' . ucwords(strtolower($staff['staff_name'])) . '" class="' . $text . '"><i class="fas fa-eye"></i></a>
                </div>
            ';

            $staffArray[] = $row;
        }

        return $this->respond(([
            'staffAccounts' => $staffArray,
            'monthlyStaffTotal' => $monthlyStaffTotal,
            "staffAccountsCounter" => $clientsCounter,
            'yearlyStaffTotal' => array_sum($monthlyStaffTotal),
            'yearlyActiveStaffTotal' => array_sum($monthlyActiveStaff),
            'yearlyInactiveStaffTotal' => array_sum($monthlyInactiveStaff),
        ]));
    }


    public function loanProductsReport()
    {
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        if (!empty($start_date) && !empty($end_date)) {
            $this->report->setStartDate($this->request->getVar('start_date'));
            $this->report->setEndDate($this->request->getVar('end_date'));
        }

        if (!empty($this->request->getVar('year'))) {
            $this->report->setYear($this->request->getVar('year'));
        }

        if (!empty($this->request->getVar('entry_status'))) {
            $this->report->setEntryStatus($this->request->getVar('entry_status'));
        }

        if (!empty($this->request->getVar('loanrepaymentdurations'))) {
            # selected multiple branches as an array ["1", "2"]
            $this->report->setBranch($this->request->getVar('loanrepaymentdurations'));
        }

        if (!empty($this->request->getVar('account_no'))) {
            $this->report->setAccountNo($this->request->getVar('account_no'));
        }

        if (!empty($this->request->getVar('others'))) {
            $this->report->setSearch($this->request->getVar('others'));
        }

        if (!empty($this->request->getVar('status'))) {
            $this->report->setEntryStatus($this->request->getVar('status'));
        }

        if (!empty($this->request->getVar('interesttypes'))) {
            $this->report->setReference($this->request->getVar('interesttypes'));
        }


        # get loan products information
        $productsResults = $this->report->getLoanProductsReport();
        # set results to hold the monthly total number of loan products
        $results = [];
        # from the productsResults, pick only the monthlyProductResults
        foreach ($productsResults["monthlyProductResults"] as $key => $loanProducts) {
            # check the loan product existance
            if (count($loanProducts) > 1) {
                # store the number of the loan products who are active and inactive
                $activeProducts = $inactiveProducts = $productsTotal = [];
                foreach ($loanProducts as $k2 => $product) {
                    if ($product) {
                        # check whether the product status is active
                        if (strtolower($product['status']) == "active") {
                            # count the number of active products
                            $activeProducts[] = $product['id'];
                        }
                        # check whether the access status is inactive
                        if (strtolower($product['status']) == "inactive") {
                            # count the number of inactive products
                            $inactiveStaff[] = $product['id'];
                        }
                        # count the total number of products
                        $productsTotal[] = $product['id'];
                    }
                }
                # count unique number of product
                $results[$key][] = count(array_unique(($activeProducts)));
                $results[$key][] = count(array_unique(($inactiveProducts)));
                # count the total of active and inactive loan products
                $productsNumber = array_merge(array_unique(($activeProducts)), array_unique(($inactiveProducts)));
                # $results[$key][] = count($productsNumber);
                $results[$key][] = count(array_unique(($productsTotal)));
            } else {
                # count unique number of loan products
                $results[$key][] = 0;
                $results[$key][] = 0;
                $results[$key][] = 0;
            }
        }

        # loan products counter
        $monthlyProductsTotal = $monthlyActiveProducts = [];
        $loanProductsCounter = $monthlyInactiveProducts = [];
        $counter = 0;
        foreach ($results as $year_month_products => $record) {
            # count the total monthly number of active loan products
            $monthlyActiveProducts[] = $record[0];
            # count the total monthly number of inactive loan products
            $monthlyInactiveProducts[] = $record[1];
            # count the total monthly number of loan products
            $monthlyProductsTotal[] = $record[2];

            $counter++;
            $row = [];
            $row[] = $year_month_products;
            $row[] = $record[0];
            $row[] = $record[1];
            $row[] = $record[2];

            $loanProductsCounter[] = $row;
        }

        # product table information
        $loanProducts = [];
        $sno = 0;
        # from the staffResults, pick only the loanProductsResults
        foreach ($productsResults["loanProductsResults"] as $key => $product) {
            $sno++;
            $row = [];
            $row[] = $sno;
            $row[] = $product['created_at'] ? date("d-m-Y H:i:s", strtotime($product['created_at'])) : $product['created_at'];
            $row[] = $product['product_name'];
            $row[] = $product['repayment_freq'];
            $row[] = $product['repayment_period'];
            $row[] = $product['interest_rate'];
            $row[] = ucwords($product['interest_type']);
            # check the product status existance
            if (strtolower($product['status']) == 'active') {
                $text = "text-info";
            } else {
                $text = "text-danger";
            }
            # display the view product button
            $row[] = '
                <div class="text-center">
                    <a href="javascript:void(0)" onclick="viewLoadProduct(' . "'" . $product['id'] . "'" . ')" title="View ' . ucwords(strtolower($product['product_name'])) . '" class="' . $text . '"><i class="fas fa-eye"></i></a>
                </div>
            ';

            $loanProducts[] = $row;
        }

        return $this->respond(([
            'loanProducts' => $loanProducts,
            'monthlyProductsTotal' => $monthlyProductsTotal,
            "loanProductsCounter" => $loanProductsCounter,
            'yearlyProductsTotal' => array_sum($monthlyProductsTotal),
            'yearlyActiveProductsTotal' => array_sum($monthlyActiveProducts),
            'yearlyInactiveProductsTotal' => array_sum($monthlyInactiveProducts),
        ]));
    }


    public function loanApplicationsReport()
    {
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        if (!empty($start_date) && !empty($end_date)) {
            $this->report->setStartDate($this->request->getVar('start_date'));
            $this->report->setEndDate($this->request->getVar('end_date'));
        }

        $start_amount_applied = $this->request->getVar('start_amount_applied');
        $end_amount_applied = $this->request->getVar('end_amount_applied');

        if (!empty($start_amount_applied) && !empty($end_amount_applied)) {
            $this->report->setFieldName01($this->request->getVar('start_amount_applied'));
            $this->report->setFieldName02($this->request->getVar('end_amount_applied'));
        }

        if (!empty($this->request->getVar('client_name'))) {
            $this->report->setFieldName03($this->request->getVar('client_name'));
        }

        if (!empty($this->request->getVar('application_code'))) {
            $this->report->setFieldName04($this->request->getVar('application_code'));
        }

        if (!empty($this->request->getVar('application_level'))) {
            $this->report->setFieldName05($this->request->getVar('application_level'));
        }

        if (!empty($this->request->getVar('application_action'))) {
            $this->report->setFieldName06($this->request->getVar('application_action'));
        }

        if (!empty($this->request->getVar('application_status'))) {
            $this->report->setFieldName07($this->request->getVar('application_status'));
        }

        if (!empty($this->request->getVar('client_account_status'))) {
            $this->report->setFieldName08($this->request->getVar('client_account_status'));
        }

        if (!empty($this->request->getVar('year'))) {
            $this->report->setYear($this->request->getVar('year'));
        }

        if (!empty($this->request->getVar('entry_status'))) {
            $this->report->setEntryStatus($this->request->getVar('entry_status'));
        }

        if (!empty($this->request->getVar('gender'))) {
            $this->report->setGender($this->request->getVar('gender'));
        }

        if (!empty($this->request->getVar('account_no'))) {
            $this->report->setAccountNo($this->request->getVar('account_no'));
        }

        if (!empty($this->request->getVar('branch_id'))) {
            # selected multiple branches as an array ["1", "2"]
            $this->report->setBranch($this->request->getVar('branch_id'));
        }

        if (!empty($this->request->getVar('staff_id'))) {
            $this->report->setStaff($this->request->getVar('staff_id'));
        }

        # get the loan applications information
        $applicationResults = $this->report->getLoanApplicationsReport();
        # set results to hold the monthly total number of loan applications
        $results = [];
        # from the applicationResults, pick only the monthlyApplicationResults
        foreach ($applicationResults["monthlyApplicationResults"] as $key => $loanApplications) {
            # check the loan application existance
            if (count($loanApplications) > 1) {
                # store the number of loan applications status
                $pending = $processing = $declined = $approved = $disbursed = [];
                $statusTotal = $cancelled = [];
                foreach ($loanApplications as $k2 => $application) {
                    if ($application) {
                        # check whether the loan application status is pending
                        if (strtolower($application['status']) == "pending") {
                            # count the number of pending loan applications
                            $pending[] = $application['client_id'];
                        }
                        # check whether the loan application status is processing
                        if (strtolower($application['status']) == "processing") {
                            # count the number of processing loan applications
                            $processing[] = $application['client_id'];
                        }
                        # check whether the loan application status is declined
                        if (strtolower($application['status']) == "declined") {
                            # count the number of declined loan applications
                            $declined[] = $application['client_id'];
                        }
                        # check whether the loan application status is approved
                        if (strtolower($application['status']) == "approved") {
                            # count the number of approved loan applications
                            $approved[] = $application['client_id'];
                        }
                        # check whether the loan application status is disbursed
                        if (strtolower($application['status']) == "disbursed") {
                            # count the number of disbursed loan applications
                            $disbursed[] = $application['client_id'];
                        }
                        # check whether the loan application status is cancelled
                        if (strtolower($application['status']) == "cancelled") {
                            # count the number of cancelled loan applications
                            $cancelled[] = $application['client_id'];
                        }
                        # count the total number of loan applications
                        $statusTotal[] = $application['client_id'];
                    }
                }
                # count unique number of client loan applications
                # count(array_unique(($pending)))
                # count the total number of pending loan applications
                $results[$key][] = count($pending);
                # count the total number of processing loan applications
                $results[$key][] = count($processing);
                # count the total number of declined loan applications
                $results[$key][] = count($declined);
                # count the total number of approved loan applications
                $results[$key][] = count($approved);
                # count the total number of disbursed loan applications
                $results[$key][] = count($disbursed);
                # count the total number of cancelled loan applications
                $results[$key][] = count($cancelled);
                # count the total number of all loan applications status
                $results[$key][] = count($statusTotal);
            } else {
                # set the count of loan application status to zero
                # pending loan applications number
                $results[$key][] = 0;
                # processing loan applications number
                $results[$key][] = 0;
                # declined loan applications number
                $results[$key][] = 0;
                # approved loan applications number
                $results[$key][] = 0;
                # disbursed loan applications number
                $results[$key][] = 0;
                # cancelled loan applications number
                $results[$key][] = 0;
                # total loan applications number
                $results[$key][] = 0;
            }
        }

        # monthly counter of loan applications status
        $pendingTotal = $processingTotal = $declinedTotal = $approvedTotal = $disbursedTotal = [];
        $statusOverallTotal = $cancelledTotal = [];
        $counter = 0;
        foreach ($results as $year_month => $record) {
            # count the total number of pending loan applications
            $pendingTotal[] = $record[0];
            # count the total number of processing loan applications
            $processingTotal[] = $record[1];
            # count the total number of declined loan applications
            $declinedTotal[] = $record[2];
            # count the total number of approved loan applications
            $approvedTotal[] = $record[3];
            # count the total number of disbursed loan applications
            $disbursedTotal[] = $record[4];
            # count the total number of cancelled loan applications
            $cancelledTotal[] = $record[5];
            # count the total number of all loan applications status
            $statusOverallTotal[] = $record[6];

            $counter++;
            $row = [];
            $row[] = $year_month;
            $row[] = $record[0];
            $row[] = $record[1];
            $row[] = $record[2];
            $row[] = $record[3];
            $row[] = $record[4];
            $row[] = $record[5];
            $row[] = $record[6];

            $clientsCounter[] = $row;
        }

        # loan applications report information
        $clientLoanApplications = [];
        $sno = 0;
        # from the applicationResults, pick only the loanApplicationsResults
        foreach ($applicationResults["loanApplicationsResults"] as $key => $loanapplication) {
            $sno++;
            $row = [];
            $row[] = $sno;
            $row[] = $loanapplication['created_at'] ? date("d-m-Y H:i:s", strtotime($loanapplication['created_at'])) : $loanapplication['created_at'];
            $row[] = $loanapplication['application_code'];
            $row[] = $loanapplication['name'];
            $row[] = $loanapplication['account_no'];
            $row[] = $loanapplication['product_name'];
            $row[] = number_format($loanapplication['principal'], 2);
            $row[] = number_format($loanapplication['interest_rate'], 2) . "%";
            // $row[] = ucwords($loanapplication['status']);

            # check the loan applications
            switch (strtolower($loanapplication['status'])) {
                case 'pending':
                    if (strtolower($loanapplication['level']) == null) {
                        $text = "text-secondary";
                    } else {
                        $text = "text-warning";
                    }
                    break;
                case 'approved':
                    $text = "text-info";
                    break;
                case 'disbursed':
                    $text = "text-success";
                    break;
                case 'declined':
                    $text = "text-danger";
                    break;
                default:
                    $text = "text-primary";
                    break;
            }

            # Application Status
            $row[] = '
                <div class="text-center ' . $text . '">
                    ' . ucwords($loanapplication['status']) . '
                </div>
            ';

            # display the view loan application
            /*
            $row[] = '
                <div class="text-center">
                    <a href="/client/application/info/' . $loanapplication['id'] . '" title="View ' . ucwords(strtolower($loanapplication['name'])) . '" class="' . $text . '"><i class="fas fa-eye"></i></a>
                </div>
            ';
            */

            $clientLoanApplications[] = $row;
        }

        return $this->respond(([
            'loanapplications' => $clientLoanApplications,
            'monthlytotalloanapplications' => $statusOverallTotal,
            "loanapplicationscounter" => $clientsCounter,
            'yearlytotalapplications' => array_sum($statusOverallTotal),
            'yearlytotalpending' => array_sum($pendingTotal),
            'yearlytotalprocessing' => array_sum($processingTotal),
            'yearlytotaldeclined' => array_sum($declinedTotal),
            'yearlytotalapproved' => array_sum($approvedTotal),
            'yearlytotaldisbursed' => array_sum($disbursedTotal),
            'yearlytotalcancelled' => array_sum($cancelledTotal),
            'yearlytotalapplications' => array_sum($statusOverallTotal)
        ]));
    }

    public function loanDisbursementReport()
    {
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        if (!empty($start_date) && !empty($end_date)) {
            $this->report->setStartDate($this->request->getVar('start_date'));
            $this->report->setEndDate($this->request->getVar('end_date'));
        }

        $start_amount_applied = $this->request->getVar('start_amount_applied');
        $end_amount_applied = $this->request->getVar('end_amount_applied');

        if (!empty($start_amount_applied) && !empty($end_amount_applied)) {
            $this->report->setFieldName01($this->request->getVar('start_amount_applied'));
            $this->report->setFieldName02($this->request->getVar('end_amount_applied'));
        }

        if (!empty($this->request->getVar('client_name'))) {
            $this->report->setFieldName03($this->request->getVar('client_name'));
        }

        if (!empty($this->request->getVar('disbursement_code'))) {
            $this->report->setFieldName04($this->request->getVar('disbursement_code'));
        }

        if (!empty($this->request->getVar('start_expiry_date'))) {
            $this->report->setFieldName05($this->request->getVar('start_expiry_date'));
        }

        if (!empty($this->request->getVar('end_expiry_date'))) {
            $this->report->setFieldName06($this->request->getVar('end_expiry_date'));
        }

        if (!empty($this->request->getVar('disbursement_status'))) {
            $this->report->setFieldName07($this->request->getVar('disbursement_status'));
        }

        if (!empty($this->request->getVar('client_account_status'))) {
            $this->report->setFieldName08($this->request->getVar('client_account_status'));
        }

        if (!empty($this->request->getVar('year'))) {
            $this->report->setYear($this->request->getVar('year'));
        }

        if (!empty($this->request->getVar('entry_status'))) {
            $this->report->setEntryStatus($this->request->getVar('entry_status'));
        }

        if (!empty($this->request->getVar('gender'))) {
            $this->report->setGender($this->request->getVar('gender'));
        }

        if (!empty($this->request->getVar('account_no'))) {
            $this->report->setAccountNo($this->request->getVar('account_no'));
        }

        if (!empty($this->request->getVar('branch_id'))) {
            # selected multiple branches as an array ["1", "2"]
            $this->report->setBranch($this->request->getVar('branch_id'));
        }

        if (!empty($this->request->getVar('staff_id'))) {
            $this->report->setStaff($this->request->getVar('staff_id'));
        }

        # get the loan disbursement information
        $disbursementResults = $this->report->getDisbursementLoansReport();
        # set results to hold the monthly total number of loan disbursement
        $results = [];
        # from the disbursementResults, pick only the monthlyDisbursementResults
        foreach ($disbursementResults["monthlyDisbursementResults"] as $key => $loanDisbursements) {
            # check the loan disbursement existance
            if (count($loanDisbursements) > 1) {
                # store the number of loan disbursement status
                $running = $arrears = $cleared = $expired = [];
                $statusTotal = [];
                # loan principal
                $principalAmount = $principalCollected = $principalBalance = [];
                # loan interest 
                $interestAmount = $interestCollected = $interestBalance = [];
                foreach ($loanDisbursements as $k2 => $disbursement) {
                    if ($disbursement) {
                        # check whether the loan disbursement class status is running
                        if (strtolower($disbursement['class']) == "running") {
                            # count the number of pending loan disbursement
                            $running[] = $disbursement['client_id'];
                        }
                        # check whether the loan disbursement status is arrears
                        if (strtolower($disbursement['class']) == "arrears") {
                            # count the number of arrears loan disbursement
                            $arrears[] = $disbursement['client_id'];
                        }
                        # check whether the loan disbursement status is cleared
                        if (strtolower($disbursement['class']) == "cleared") {
                            # count the number of cleared loan disbursement
                            $cleared[] = $disbursement['client_id'];
                        }
                        # check whether the loan disbursement status is expired
                        if (strtolower($disbursement['class']) == "expired") {
                            # count the number of expired loan disbursement
                            $expired[] = $disbursement['client_id'];
                        }
                        # count the total number of loan disbursement
                        $statusTotal[] = $disbursement['client_id'];
                        # principal disbursed report
                        # principal amount disbursed
                        $principalAmount[] = $disbursement['principal'];
                        # principal amount collected
                        $principalCollected[] = $disbursement['principal_collected'];
                        # principal amount outstanding or balance
                        $principalBalance[] = $disbursement['principal_balance'];
                        # interest report
                        # interest amount to be paid
                        $interestAmount[] = $disbursement['actual_interest'];
                        # interest amount collected
                        $interestCollected[] = $disbursement['interest_collected'];
                        # interest amount outstanding or balance
                        $interestBalance[] = $disbursement['interest_balance'];
                    }
                }
                # count unique number of client loan disbursement
                # count(array_unique(($pending)))
                # count the total number of running loan disbursement
                $results[$key][] = count($running);
                # count the total number of arrears loan disbursement
                $results[$key][] = count($arrears);
                # count the total number of cleared loan disbursement
                $results[$key][] = count($cleared);
                # count the total number of expired loan disbursement
                $results[$key][] = count($expired);
                # count the total number of all loan disbursement status
                $results[$key][] = count($statusTotal);
                # principal disbursed report
                $results[$key][] = array_sum($principalAmount);
                # principal amount collected
                $results[$key][] = array_sum($principalCollected);
                # principal amount outstanding or balance
                $results[$key][] = array_sum($principalBalance);
                # interest report
                # interest amount to be paid
                $results[$key][] = array_sum($interestAmount);
                # interest amount collected
                $results[$key][] = array_sum($interestCollected);
                # interest amount outstanding or balance
                $results[$key][] = array_sum($interestBalance);
            } else {
                # set the count of loan disbursement status to zero
                # running loan disbursement number
                $results[$key][] = 0;
                # cleared loan disbursement number
                $results[$key][] = 0;
                # cleared loan disbursement number
                $results[$key][] = 0;
                # expired loan disbursement number
                $results[$key][] = 0;
                # total loan disbursement number
                $results[$key][] = 0;
                # principal disbursed report
                $results[$key][] = 0;
                # principal amount collected
                $results[$key][] = 0;
                # principal amount outstanding or balance
                $results[$key][] = 0;
                # interest report
                # interest amount to be paid
                $results[$key][] = 0;
                # interest amount collected
                $results[$key][] = 0;
                # interest amount outstanding or balance
                $results[$key][] = 0;
            }
        }

        # monthly counter of loan disbursement status
        $runningTotal = $arrearsTotal = $clearedTotal = $expiredTotal = [];
        $totalDisbursement = $loansConter = [];
        $counter = 0;
        foreach ($results as $year_month => $record) {
            # count the total number of running loan disbursement
            $runningTotal[] = $record[0];
            # count the total number of arrears loan disbursement
            $arrearsTotal[] = $record[1];
            # count the total number of cleared loan disbursement
            $clearedTotal[] = $record[2];
            # count the total number of expired loan disbursement
            $expiredTotal[] = $record[3];
            # count the total number of all loan disbursement status
            $totalDisbursement[] = $record[4];

            $counter++;
            $row = [];
            $row[] = $year_month;
            $row[] = $record[0];
            $row[] = $record[1];
            $row[] = $record[2];
            $row[] = $record[3];
            $row[] = $record[4];

            $loansConter[] = $row;
        }

        # loan disbursement report information
        $loanResults = [];
        $sno = 0;
        # from the disbursementResults, pick only the loanDisbursementResults
        foreach ($disbursementResults["loanDisbursementResults"] as $key => $disbursement) {
            $sno++;
            $row = [];
            $row[] = $sno;
            $row[] = $disbursement['created_at'] ? date("d-m-Y H:i:s", strtotime($disbursement['created_at'])) : $disbursement['created_at'];
            // $row[] = $disbursement['disbursement_code'];
            $row[] = strtoupper($disbursement['name']) . ' (' . $disbursement['account_no'] . ')';
            // $row[] = $disbursement['account_no'];
            $row[] = $disbursement['product_name'];
            $row[] = $disbursement['interest_rate'] . "%";
            $row[] = $disbursement['repayment_period'] . " " . $disbursement['repayment_duration'];
            $row[] = ($disbursement['principal']) ? number_format($disbursement['principal'], 0) : number_format($disbursement['principal'], 2);
            $row[] = ($disbursement['total_balance']) ? number_format($disbursement['total_balance'], 0) : number_format($disbursement['total_balance'], 2);
            // $row[] = ucwords($disbursement['class']);
            // $row[] = ucwords($disbursement['particular_name']);

            # check the loan disbursement class status
            switch (strtolower($disbursement['class'])) {
                case 'running':
                    $text = "text-info";
                    break;
                case 'cleared':
                    $text = "text-success";
                    break;
                case 'expired':
                    $text = "text-danger";
                    break;
                default:
                    $text = "text-danger";
                    break;
            }

            $row[] = '
                <div class="text-center ' . $text . '">
                    ' . ucwords($disbursement['class']) . '
                </div>
            ';

            $row[] = ucwords($disbursement['particular_name']);

            $expiry = (date('Y-m-d', strtotime($disbursement['loan_expiry_date'])) == date('Y-m-d')) ? "text-success" : "text-danger";

            $row[] = '
                <div class="text-center ' . $expiry . '">
                    ' . date('d-m-Y', strtotime($disbursement['loan_expiry_date'])) . '
                </div>
            ';

            # display the view loan disbursement
            /*
            $row[] = '
                <div class="text-center">
                    <a href="/client/disbursement/info/' . $disbursement['id'] . '" title="View ' . ucwords(strtolower($disbursement['name'])) . '" class="' . $text . '"><i class="fas fa-eye"></i></a>
                </div>
            ';
            */

            $loanResults[] = $row;
        }

        # monthly principal amount disbursed
        $principalAmountDisbursed = $principalAmountCollected = $principalOutstanding = [];
        $loansArray = [];
        $i = 0;
        foreach ($results as $loan_year_month => $record) {
            # perform calculation of the total principal amount disbursed
            $principalAmountDisbursed[] = $record[5];
            # principal amount collected
            $principalAmountCollected[] = $record[6];
            # principal amount balance
            $principalOutstanding[] = $record[7];

            $i++;
            $row = [];
            $row[] = $loan_year_month;
            $row[] = number_format($record[5], 2);
            $row[] = number_format($record[6], 2);
            $row[] = number_format($record[7], 2);

            $loansArray[] = $row;
        }

        # monthly interest amount disbursed
        $totalInterestAmount = $interestAmountCollected = $interestOutstanding = [];
        $loansInterestsArray = [];
        $j = 0;
        foreach ($results as $loan_year_month => $record) {
            # perform calculation of the total interest amount to be paid
            $totalInterestAmount[] = $record[8];
            # interest amount collected
            $interestAmountCollected[] = $record[9];
            # interest amount balance
            $interestOutstanding[] = $record[10];

            $j++;
            $row = [];
            $row[] = $loan_year_month;
            $row[] = number_format($record[8], 2);
            $row[] = number_format($record[9], 2);
            $row[] = number_format($record[10], 2);

            $loansInterestsArray[] = $row;
        }

        return $this->respond(([
            'loans' => $loanResults,
            'monthlytotalloans' => $totalDisbursement,
            "loanscounter" => $loansConter,
            'loanSummary' => [
                # yearly total number of loans released
                'loans' => array_sum($totalDisbursement),
                # yearly total number of running loans released
                'runningLoans' => array_sum($runningTotal),
                # yearly total number of arrears loans released
                'loanArrears' => array_sum($arrearsTotal),
                # yearly total number of cleared loans released
                'clearedLoans' => array_sum($clearedTotal),
                # yearly total number of expired loans released
                'expiredLoans' => array_sum($expiredTotal),
            ],
            'disbursement' => [
                # monthly total principal amount released
                'principalReleased' => $loansArray,
                # yearly total principal amount released
                'principalDisbursed' => number_format(array_sum($principalAmountDisbursed), 2),
                # yearly total principal amount collected
                'principalCollected' => number_format(array_sum($principalAmountCollected), 2),
                # yearly total principal outstanding
                'principalOutstanding' => number_format(array_sum($principalOutstanding), 2),
            ],
            'revenue' => [
                # monthly total interest amount to be paid
                'interests' => $loansInterestsArray,
                # yearly total interest amount released
                'interestTotal' => number_format(array_sum($totalInterestAmount), 2),
                # yearly total interest amount collected
                'interestCollected' => number_format(array_sum($interestAmountCollected), 2),
                # yearly total interest outstanding
                'interestOutstanding' => number_format(array_sum($interestOutstanding), 2),
            ]
        ]));
    }

    public function loanRepaymentsReport()
    {
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        if (!empty($start_date) && !empty($end_date)) {
            $this->report->setStartDate($this->request->getVar('start_date'));
            $this->report->setEndDate($this->request->getVar('end_date'));
        }

        $start_amount_applied = $this->request->getVar('start_amount_applied');
        $end_amount_applied = $this->request->getVar('end_amount_applied');

        if (!empty($start_amount_applied) && !empty($end_amount_applied)) {
            $this->report->setFieldName01($this->request->getVar('start_amount_applied'));
            $this->report->setFieldName02($this->request->getVar('end_amount_applied'));
        }

        if (!empty($this->request->getVar('client_name'))) {
            $this->report->setFieldName03($this->request->getVar('client_name'));
        }

        if (!empty($this->request->getVar('disbursement_code'))) {
            $this->report->setFieldName04($this->request->getVar('disbursement_code'));
        }

        if (!empty($this->request->getVar('start_expiry_date'))) {
            $this->report->setFieldName05($this->request->getVar('start_expiry_date'));
        }

        if (!empty($this->request->getVar('end_expiry_date'))) {
            $this->report->setFieldName06($this->request->getVar('end_expiry_date'));
        }

        if (!empty($this->request->getVar('disbursement_status'))) {
            $this->report->setFieldName07($this->request->getVar('disbursement_status'));
        }

        if (!empty($this->request->getVar('client_account_status'))) {
            $this->report->setFieldName08($this->request->getVar('client_account_status'));
        }

        if (!empty($this->request->getVar('year'))) {
            $this->report->setYear($this->request->getVar('year'));
        }

        if (!empty($this->request->getVar('entry_status'))) {
            $this->report->setEntryStatus($this->request->getVar('entry_status'));
        }

        if (!empty($this->request->getVar('gender'))) {
            $this->report->setGender($this->request->getVar('gender'));
        }

        if (!empty($this->request->getVar('account_no'))) {
            $this->report->setAccountNo($this->request->getVar('account_no'));
        }

        if (!empty($this->request->getVar('branch_id'))) {
            # selected multiple branches as an array ["1", "2"]
            $this->report->setBranch($this->request->getVar('branch_id'));
        }

        if (!empty($this->request->getVar('staff_id'))) {
            $this->report->setStaff($this->request->getVar('staff_id'));
        }

        # get the loan disbursement information
        $disbursementResults = $this->report->getDisbursementLoansReport();
        # set results to hold the monthly total number of loan disbursement
        $results = [];
        # from the disbursementResults, pick only the monthlyDisbursementResults
        foreach ($disbursementResults["monthlyDisbursementResults"] as $key => $loanDisbursements) {
            # check the loan disbursement existance
            if (count($loanDisbursements) > 1) {
                # store the number of loan disbursement status
                $running = $arrears = $cleared = $expired = [];
                $statusTotal = [];
                # loan principal
                $principalAmount = $principalCollected = $principalBalance = [];
                # loan interest 
                $interestAmount = $interestCollected = $interestBalance = [];
                foreach ($loanDisbursements as $k2 => $disbursement) {
                    if ($disbursement) {
                        # check whether the loan disbursement class status is running
                        if (strtolower($disbursement['class']) == "running") {
                            # count the number of pending loan disbursement
                            $running[] = $disbursement['client_id'];
                        }
                        # check whether the loan disbursement status is arrears
                        if (strtolower($disbursement['class']) == "arrears") {
                            # count the number of arrears loan disbursement
                            $arrears[] = $disbursement['client_id'];
                        }
                        # check whether the loan disbursement status is cleared
                        if (strtolower($disbursement['class']) == "cleared") {
                            # count the number of cleared loan disbursement
                            $cleared[] = $disbursement['client_id'];
                        }
                        # check whether the loan disbursement status is expired
                        if (strtolower($disbursement['class']) == "expired") {
                            # count the number of expired loan disbursement
                            $expired[] = $disbursement['client_id'];
                        }
                        # count the total number of loan disbursement
                        $statusTotal[] = $disbursement['client_id'];
                        # principal disbursed report
                        # principal amount disbursed
                        $principalAmount[] = $disbursement['principal'];
                        # principal amount collected
                        $principalCollected[] = $disbursement['principal_collected'];
                        # principal amount outstanding or balance
                        $principalBalance[] = $disbursement['principal_balance'];
                        # interest report
                        # interest amount to be paid
                        $interestAmount[] = $disbursement['actual_interest'];
                        # interest amount collected
                        $interestCollected[] = $disbursement['interest_collected'];
                        # interest amount outstanding or balance
                        $interestBalance[] = $disbursement['interest_balance'];
                    }
                }
                # count unique number of client loan disbursement
                # count(array_unique(($pending)))
                # count the total number of running loan disbursement
                $results[$key][] = count($running);
                # count the total number of arrears loan disbursement
                $results[$key][] = count($arrears);
                # count the total number of cleared loan disbursement
                $results[$key][] = count($cleared);
                # count the total number of expired loan disbursement
                $results[$key][] = count($expired);
                # count the total number of all loan disbursement status
                $results[$key][] = count($statusTotal);
                # principal disbursed report
                $results[$key][] = array_sum($principalAmount);
                # principal amount collected
                $results[$key][] = array_sum($principalCollected);
                # principal amount outstanding or balance
                $results[$key][] = array_sum($principalBalance);
                # interest report
                # interest amount to be paid
                $results[$key][] = array_sum($interestAmount);
                # interest amount collected
                $results[$key][] = array_sum($interestCollected);
                # interest amount outstanding or balance
                $results[$key][] = array_sum($interestBalance);
            } else {
                # set the count of loan disbursement status to zero
                # running loan disbursement number
                $results[$key][] = 0;
                # cleared loan disbursement number
                $results[$key][] = 0;
                # cleared loan disbursement number
                $results[$key][] = 0;
                # expired loan disbursement number
                $results[$key][] = 0;
                # total loan disbursement number
                $results[$key][] = 0;
                # principal disbursed report
                $results[$key][] = 0;
                # principal amount collected
                $results[$key][] = 0;
                # principal amount outstanding or balance
                $results[$key][] = 0;
                # interest report
                # interest amount to be paid
                $results[$key][] = 0;
                # interest amount collected
                $results[$key][] = 0;
                # interest amount outstanding or balance
                $results[$key][] = 0;
            }
        }

        # monthly counter of loan disbursement status
        $runningTotal = $arrearsTotal = $clearedTotal = $expiredTotal = [];
        $totalDisbursement = $loansConter = [];
        $counter = 0;
        foreach ($results as $year_month => $record) {
            # count the total number of running loan disbursement
            $runningTotal[] = $record[0];
            # count the total number of arrears loan disbursement
            $arrearsTotal[] = $record[1];
            # count the total number of cleared loan disbursement
            $clearedTotal[] = $record[2];
            # count the total number of expired loan disbursement
            $expiredTotal[] = $record[3];
            # count the total number of all loan disbursement status
            $totalDisbursement[] = $record[4];

            $counter++;
            $row = [];
            $row[] = $year_month;
            $row[] = $record[0];
            $row[] = $record[1];
            $row[] = $record[2];
            $row[] = $record[3];
            $row[] = $record[4];

            $loansConter[] = $row;
        }

        # loan disbursement report information
        $loanResults = [];
        $sno = 0;
        # from the disbursementResults, pick only the loanDisbursementResults
        foreach ($disbursementResults["loanDisbursementResults"] as $key => $disbursement) {
            $sno++;
            $row = [];
            $row[] = $sno;
            $row[] = $disbursement['created_at'] ? date("d-m-Y H:i:s", strtotime($disbursement['created_at'])) : $disbursement['created_at'];
            // $row[] = $disbursement['disbursement_code'];
            $row[] = strtoupper($disbursement['name']) . ' (' . $disbursement['account_no'] . ')';
            // $row[] = $disbursement['account_no'];
            $row[] = $disbursement['product_name'];
            $row[] = $disbursement['interest_rate'] . "%";
            $row[] = $disbursement['repayment_period'] . " " . $disbursement['repayment_duration'];
            $row[] = ($disbursement['principal']) ? number_format($disbursement['principal'], 0) : number_format($disbursement['principal'], 2);
            $row[] = ($disbursement['total_balance']) ? number_format($disbursement['total_balance'], 0) : number_format($disbursement['total_balance'], 2);
            $row[] = ucwords($disbursement['class']);
            $row[] = ucwords($disbursement['particular_name']);

            # check the loan disbursement class status
            switch (strtolower($disbursement['class'])) {
                case 'running':
                    $text = "text-info";
                    break;
                case 'cleared':
                    $text = "text-success";
                    break;
                case 'expired':
                    $text = "text-danger";
                    break;
                default:
                    $text = "text-primary";
                    break;
            }

            # display the view loan disbursement
            $row[] = '
                <div class="text-center">
                    <a href="/client/disbursement/info/' . $disbursement['id'] . '" title="View ' . ucwords(strtolower($disbursement['name'])) . '" class="' . $text . '"><i class="fas fa-eye"></i></a>
                </div>
            ';

            $loanResults[] = $row;
        }

        # monthly principal amount disbursed
        $principalAmountDisbursed = $principalAmountCollected = $principalOutstanding = [];
        $loansArray = [];
        $i = 0;
        foreach ($results as $loan_year_month => $record) {
            # perform calculation of the total principal amount disbursed
            $principalAmountDisbursed[] = $record[5];
            # principal amount collected
            $principalAmountCollected[] = $record[6];
            # principal amount balance
            $principalOutstanding[] = $record[7];

            $i++;
            $row = [];
            $row[] = $loan_year_month;
            $row[] = number_format($record[5], 2);
            $row[] = number_format($record[6], 2);
            $row[] = number_format($record[7], 2);

            $loansArray[] = $row;
        }

        # monthly interest amount disbursed
        $totalInterestAmount = $interestAmountCollected = $interestOutstanding = [];
        $loansInterestsArray = [];
        $j = 0;
        foreach ($results as $loan_year_month => $record) {
            # perform calculation of the total interest amount to be paid
            $totalInterestAmount[] = $record[8];
            # interest amount collected
            $interestAmountCollected[] = $record[9];
            # interest amount balance
            $interestOutstanding[] = $record[10];

            $j++;
            $row = [];
            $row[] = $loan_year_month;
            $row[] = number_format($record[8], 2);
            $row[] = number_format($record[9], 2);
            $row[] = number_format($record[10], 2);

            $loansInterestsArray[] = $row;
        }

        # monthly loan repayment
        $principalCollected = $interestCollected = $totalAmountCollected = [];
        $repayments = [];
        $repaymentCounter = $totalAmount = 0;
        foreach ($results as $loan_year_month => $record) {
            # principal amount collected
            $principalCollected[] = $record[6];
            # interest amount collected
            $interestCollected[] = $record[9];
            # total amount collected 
            $totalAmount = $record[6] + $record[9];
            $totalAmountCollected[] = $totalAmount;

            $repaymentCounter++;
            $row = [];
            $row[] = $loan_year_month;
            $row[] = number_format($record[6], 2);
            $row[] = number_format($record[9], 2);
            $row[] = number_format($totalAmount, 2);

            $repayments[] = $row;
        }

        return $this->respond(([
            'loans' => $loanResults,
            'monthlytotalloans' => $totalDisbursement,
            "loanscounter" => $loansConter,
            'loanSummary' => [
                # yearly total number of loans released
                'loans' => array_sum($totalDisbursement),
                # yearly total number of running loans released
                'runningLoans' => array_sum($runningTotal),
                # yearly total number of arrears loans released
                'loanArrears' => array_sum($arrearsTotal),
                # yearly total number of cleared loans released
                'clearedLoans' => array_sum($clearedTotal),
                # yearly total number of expired loans released
                'expiredLoans' => array_sum($expiredTotal),
            ],
            'disbursement' => [
                # monthly total principal amount released
                'principalReleased' => $loansArray,
                # yearly total principal amount released
                'principalDisbursed' => number_format(array_sum($principalAmountDisbursed), 2),
                # yearly total principal amount collected
                'principalCollected' => number_format(array_sum($principalAmountCollected), 2),
                # yearly total principal outstanding
                'principalOutstanding' => number_format(array_sum($principalOutstanding), 2),
            ],
            'revenue' => [
                # monthly total interest amount to be paid
                'interests' => $loansInterestsArray,
                # yearly total interest amount released
                'interestTotal' => number_format(array_sum($totalInterestAmount), 2),
                # yearly total interest amount collected
                'interestCollected' => number_format(array_sum($interestAmountCollected), 2),
                # yearly total interest outstanding
                'interestOutstanding' => number_format(array_sum($interestOutstanding), 2),
            ],
            'repayments' => [
                # monthly loan repayments
                "loans" => $repayments,
                # yearly total principal amount collected
                'principal' => number_format(array_sum($principalCollected), 2),
                # yearly total interest amount collected
                'interest' => number_format(array_sum($interestCollected), 2),
                # yearly total amount collected
                'total' => number_format(array_sum($totalAmountCollected), 2),
                # monthly total amount collected
                'monthly' => $totalDisbursement,
            ],
            'collections' => [
                # monthly loan principal repayments
                "principal" => $principalCollected,
                # monthly loan interest repayments
                "interest" => $interestCollected,
                # monthly loan total repayments
                "total" => $totalAmountCollected,
            ]
        ]));
    }

    public function loanArrearsReport()
    {
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        if (!empty($start_date) && !empty($end_date)) {
            $this->report->setStartDate($this->request->getVar('start_date'));
            $this->report->setEndDate($this->request->getVar('end_date'));
        }

        $start_amount_applied = $this->request->getVar('start_amount_applied');
        $end_amount_applied = $this->request->getVar('end_amount_applied');

        if (!empty($start_amount_applied) && !empty($end_amount_applied)) {
            $this->report->setFieldName01($this->request->getVar('start_amount_applied'));
            $this->report->setFieldName02($this->request->getVar('end_amount_applied'));
        }

        if (!empty($this->request->getVar('client_name'))) {
            $this->report->setFieldName03($this->request->getVar('client_name'));
        }

        if (!empty($this->request->getVar('disbursement_code'))) {
            $this->report->setFieldName04($this->request->getVar('disbursement_code'));
        }

        if (!empty($this->request->getVar('start_expiry_date'))) {
            $this->report->setFieldName05($this->request->getVar('start_expiry_date'));
        }

        if (!empty($this->request->getVar('end_expiry_date'))) {
            $this->report->setFieldName06($this->request->getVar('end_expiry_date'));
        }

        if (!empty($this->request->getVar('disbursement_status'))) {
            $this->report->setFieldName07($this->request->getVar('disbursement_status'));
        }

        if (!empty($this->request->getVar('client_account_status'))) {
            $this->report->setFieldName08($this->request->getVar('client_account_status'));
        }

        if (!empty($this->request->getVar('year'))) {
            $this->report->setYear($this->request->getVar('year'));
        }

        if (!empty($this->request->getVar('entry_status'))) {
            $this->report->setEntryStatus($this->request->getVar('entry_status'));
        }

        if (!empty($this->request->getVar('gender'))) {
            $this->report->setGender($this->request->getVar('gender'));
        }

        if (!empty($this->request->getVar('account_no'))) {
            $this->report->setAccountNo($this->request->getVar('account_no'));
        }

        if (!empty($this->request->getVar('branch_id'))) {
            # selected multiple branches as an array ["1", "2"]
            $this->report->setBranch($this->request->getVar('branch_id'));
        }

        if (!empty($this->request->getVar('staff_id'))) {
            $this->report->setStaff($this->request->getVar('staff_id'));
        }

        # get the loan disbursement information
        $disbursementResults = $this->report->getDisbursementLoansReport();
        # set results to hold the monthly total number of loan disbursement
        $results = [];
        # from the disbursementResults, pick only the monthlyDisbursementResults
        foreach ($disbursementResults["monthlyDisbursementResults"] as $key => $loanDisbursements) {
            # check the loan disbursement existance
            if (count($loanDisbursements) > 1) {
                # store the number of loan disbursement status
                $running = $arrears = $cleared = $expired = [];
                $statusTotal = [];
                # loan principal
                $principalAmount = $principalCollected = $principalBalance = [];
                # loan interest 
                $interestAmount = $interestCollected = $interestBalance = [];
                foreach ($loanDisbursements as $k2 => $disbursement) {
                    if ($disbursement) {
                        # check whether the loan disbursement class status is running
                        if (strtolower($disbursement['class']) == "running") {
                            # count the number of pending loan disbursement
                            $running[] = $disbursement['client_id'];
                        }
                        # check whether the loan disbursement status is arrears
                        if (strtolower($disbursement['class']) == "arrears") {
                            # count the number of arrears loan disbursement
                            $arrears[] = $disbursement['client_id'];
                        }
                        # check whether the loan disbursement status is cleared
                        if (strtolower($disbursement['class']) == "cleared") {
                            # count the number of cleared loan disbursement
                            $cleared[] = $disbursement['client_id'];
                        }
                        # check whether the loan disbursement status is expired
                        if (strtolower($disbursement['class']) == "expired") {
                            # count the number of expired loan disbursement
                            $expired[] = $disbursement['client_id'];
                        }
                        # count the total number of loan disbursement
                        $statusTotal[] = $disbursement['client_id'];
                        # principal disbursed report
                        # principal amount disbursed
                        $principalAmount[] = $disbursement['principal'];
                        # principal amount collected
                        $principalCollected[] = $disbursement['principal_collected'];
                        # principal amount outstanding or balance
                        $principalBalance[] = $disbursement['principal_balance'];
                        # interest report
                        # interest amount to be paid
                        $interestAmount[] = $disbursement['actual_interest'];
                        # interest amount collected
                        $interestCollected[] = $disbursement['interest_collected'];
                        # interest amount outstanding or balance
                        $interestBalance[] = $disbursement['interest_balance'];
                    }
                }
                # count unique number of client loan disbursement
                # count(array_unique(($pending)))
                # count the total number of running loan disbursement
                $results[$key][] = count($running);
                # count the total number of arrears loan disbursement
                $results[$key][] = count($arrears);
                # count the total number of cleared loan disbursement
                $results[$key][] = count($cleared);
                # count the total number of expired loan disbursement
                $results[$key][] = count($expired);
                # count the total number of all loan disbursement status
                $results[$key][] = count($statusTotal);
                # principal disbursed report
                $results[$key][] = array_sum($principalAmount);
                # principal amount collected
                $results[$key][] = array_sum($principalCollected);
                # principal amount outstanding or balance
                $results[$key][] = array_sum($principalBalance);
                # interest report
                # interest amount to be paid
                $results[$key][] = array_sum($interestAmount);
                # interest amount collected
                $results[$key][] = array_sum($interestCollected);
                # interest amount outstanding or balance
                $results[$key][] = array_sum($interestBalance);
            } else {
                # set the count of loan disbursement status to zero
                # running loan disbursement number
                $results[$key][] = 0;
                # cleared loan disbursement number
                $results[$key][] = 0;
                # cleared loan disbursement number
                $results[$key][] = 0;
                # expired loan disbursement number
                $results[$key][] = 0;
                # total loan disbursement number
                $results[$key][] = 0;
                # principal disbursed report
                $results[$key][] = 0;
                # principal amount collected
                $results[$key][] = 0;
                # principal amount outstanding or balance
                $results[$key][] = 0;
                # interest report
                # interest amount to be paid
                $results[$key][] = 0;
                # interest amount collected
                $results[$key][] = 0;
                # interest amount outstanding or balance
                $results[$key][] = 0;
            }
        }

        # monthly counter of loan disbursement status
        $runningTotal = $arrearsTotal = $clearedTotal = $expiredTotal = [];
        $totalDisbursement = $loansConter = [];
        $counter = 0;
        foreach ($results as $year_month => $record) {
            # count the total number of running loan disbursement
            $runningTotal[] = $record[0];
            # count the total number of arrears loan disbursement
            $arrearsTotal[] = $record[1];
            # count the total number of cleared loan disbursement
            $clearedTotal[] = $record[2];
            # count the total number of expired loan disbursement
            $expiredTotal[] = $record[3];
            # count the total number of all loan disbursement status
            $totalDisbursement[] = $record[4];

            $counter++;
            $row = [];
            $row[] = $year_month;
            $row[] = $record[0];
            $row[] = $record[1];
            $row[] = $record[2];
            $row[] = $record[3];
            $row[] = $record[4];

            $loansConter[] = $row;
        }

        # loan disbursement report information
        $loanResults = [];
        $sno = 0;
        # from the disbursementResults, pick only the loanDisbursementResults
        foreach ($disbursementResults["loanDisbursementResults"] as $key => $disbursement) {
            $sno++;
            $row = [];
            $row[] = $sno;
            $row[] = $disbursement['created_at'] ? date("d-m-Y H:i:s", strtotime($disbursement['created_at'])) : $disbursement['created_at'];
            // $row[] = $disbursement['disbursement_code'];
            $row[] = strtoupper($disbursement['name']) . ' (' . $disbursement['account_no'] . ')';
            // $row[] = $disbursement['account_no'];
            $row[] = $disbursement['product_name'];
            $row[] = $disbursement['interest_rate'] . "%";
            $row[] = $disbursement['repayment_period'] . " " . $disbursement['repayment_duration'];
            $row[] = ($disbursement['principal']) ? number_format($disbursement['principal'], 0) : number_format($disbursement['principal'], 2);
            $row[] = ($disbursement['total_balance']) ? number_format($disbursement['total_balance'], 0) : number_format($disbursement['total_balance'], 2);
            $row[] = ucwords($disbursement['class']);
            $row[] = ucwords($disbursement['particular_name']);

            # check the loan disbursement class status
            switch (strtolower($disbursement['class'])) {
                case 'running':
                    $text = "text-info";
                    break;
                case 'cleared':
                    $text = "text-success";
                    break;
                case 'expired':
                    $text = "text-danger";
                    break;
                default:
                    $text = "text-primary";
                    break;
            }

            # display the view loan disbursement
            $row[] = '
                <div class="text-center">
                    <a href="/client/disbursement/info/' . $disbursement['id'] . '" title="View ' . ucwords(strtolower($disbursement['name'])) . '" class="' . $text . '"><i class="fas fa-eye"></i></a>
                </div>
            ';

            $loanResults[] = $row;
        }

        # monthly principal amount disbursed
        $principalAmountDisbursed = $principalAmountCollected = $principalOutstanding = [];
        $loansArray = [];
        $i = 0;
        foreach ($results as $loan_year_month => $record) {
            # perform calculation of the total principal amount disbursed
            $principalAmountDisbursed[] = $record[5];
            # principal amount collected
            $principalAmountCollected[] = $record[6];
            # principal amount balance
            $principalOutstanding[] = $record[7];

            $i++;
            $row = [];
            $row[] = $loan_year_month;
            $row[] = number_format($record[5], 2);
            $row[] = number_format($record[6], 2);
            $row[] = number_format($record[7], 2);

            $loansArray[] = $row;
        }

        # monthly interest amount disbursed
        $totalInterestAmount = $interestAmountCollected = $interestOutstanding = [];
        $loansInterestsArray = [];
        $j = 0;
        foreach ($results as $loan_year_month => $record) {
            # perform calculation of the total interest amount to be paid
            $totalInterestAmount[] = $record[8];
            # interest amount collected
            $interestAmountCollected[] = $record[9];
            # interest amount balance
            $interestOutstanding[] = $record[10];

            $j++;
            $row = [];
            $row[] = $loan_year_month;
            $row[] = number_format($record[8], 2);
            $row[] = number_format($record[9], 2);
            $row[] = number_format($record[10], 2);

            $loansInterestsArray[] = $row;
        }

        # monthly loan arraers
        $principalOverdue = $interestOverdue = $totalAmountOverdue = [];
        $arrears = [];
        $arrearsCounter = $totalOverDueAmount = 0;
        foreach ($results as $loan_year_month => $record) {
            # principal amount overdue
            $principalOverdue[] = $record[7];
            # interest amount overdue
            $interestOverdue[] = $record[10];
            # total amount overdue 
            $totalOverDueAmount = $record[7] + $record[10];
            $totalAmountOverdue[] = $totalOverDueAmount;

            $arrearsCounter++;
            $row = [];
            $row[] = $loan_year_month;
            $row[] = number_format($record[7], 2);
            $row[] = number_format($record[10], 2);
            $row[] = number_format($totalOverDueAmount, 2);

            $arrears[] = $row;
        }

        return $this->respond(([
            'loans' => $loanResults,
            'monthlytotalloans' => $totalDisbursement,
            "loanscounter" => $loansConter,
            'loanSummary' => [
                # yearly total number of loans released
                'loans' => array_sum($totalDisbursement),
                # yearly total number of running loans released
                'runningLoans' => array_sum($runningTotal),
                # yearly total number of arrears loans released
                'loanArrears' => array_sum($arrearsTotal),
                # yearly total number of cleared loans released
                'clearedLoans' => array_sum($clearedTotal),
                # yearly total number of expired loans released
                'expiredLoans' => array_sum($expiredTotal),
            ],
            'disbursement' => [
                # monthly total principal amount released
                'principalReleased' => $loansArray,
                # yearly total principal amount released
                'principalDisbursed' => number_format(array_sum($principalAmountDisbursed), 2),
                # yearly total principal amount collected
                'principalCollected' => number_format(array_sum($principalAmountCollected), 2),
                # yearly total principal outstanding
                'principalOutstanding' => number_format(array_sum($principalOutstanding), 2),
            ],
            'revenue' => [
                # monthly total interest amount to be paid
                'interests' => $loansInterestsArray,
                # yearly total interest amount released
                'interestTotal' => number_format(array_sum($totalInterestAmount), 2),
                # yearly total interest amount collected
                'interestCollected' => number_format(array_sum($interestAmountCollected), 2),
                # yearly total interest outstanding
                'interestOutstanding' => number_format(array_sum($interestOutstanding), 2),
            ],
            'outstandings' => [
                # monthly loan repayments
                "loans" => $arrears,
                # yearly total principal amount overdue
                'principal' => number_format(array_sum($principalOverdue), 2),
                # yearly total interest amount overdue
                'interest' => number_format(array_sum($interestOverdue), 2),
                # yearly total amount overdue
                'total' => number_format(array_sum($totalAmountOverdue), 2),
                # monthly total amount overdue
                'monthly' => $totalDisbursement,
            ],
            'arrears' => [
                # monthly loan principal overdue
                "principal" => $principalOverdue,
                # monthly loan interest overdue
                "interest" => $interestOverdue,
                # monthly loan total overdue
                "total" => $totalAmountOverdue,
            ]
        ]));
    }
}
