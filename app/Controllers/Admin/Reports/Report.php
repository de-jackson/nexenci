<?php

namespace App\Controllers\Admin\Reports;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Report extends MasterController
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
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            return view('admin/reports/reports/xindex', [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),

                # count the number of savings collection entries
                'savings' => $this->settings->countResults('entries', ['deleted_at' => null, 'account_typeId' => 12]),

                # count the number of loan repayment
                'repayments' => $this->settings->countResults('entries', ['deleted_at' => null, 'account_typeId' => 3, 'status' => 'credit']),

                # count the number of loan applications
                'applications' => $this->settings->countResults('loanapplications', ['deleted_at' => null]),

                # count the number of branches
                'branches' => $this->settings->countResults('branches', ['deleted_at' => null]),

                # count the number of clients
                'clients' => $this->settings->countResults('clients', ['deleted_at' => null]),

                # count the number of loans disbursed
                'disbursements' => $this->settings->countResults('disbursements', ['deleted_at' => null]),

                # count the number of loans arrears
                'arrears' => $this->settings->countResults('disbursements', ['deleted_at' => null, 'class' => 'Arrears']),

                # count the number of loan products
                'products' => $this->settings->countResults('loanproducts', ['deleted_at' => null]),

                # count the number of staff
                'staff' => $this->settings->countResults('staffs', ['deleted_at' => null, 'account_type !=' => "Super Admin"]),

                'transactions' => $this->settings->countResults('entries', ['deleted_at' => null]),
                'logs' => $this->settings->countResults('userlogs', ['deleted_at' => null]),
                'activities' => $this->settings->countResults('useractivities', ['deleted_at' => null]),

            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    public function report_view($report)
    {
        switch (strtolower($report)) {
            case 'branches':
                if (!empty($this->request->getVar('from'))) {
                    $from = date('Y-m-d', strtotime($this->request->getVar('from')));
                } else {
                    $from = null;
                }
                if (!empty($this->request->getVar('to'))) {
                    $to = date('Y-m-d', strtotime($this->request->getVar('to')));
                } else {
                    $to = null;
                }
                $data = [
                    'from' => $from,
                    'to' => $to,
                ];
                $view = 'admin/reports/reports/branches';
                break;
            case 'staffs':
                $data = [
                    'filters' => $this->report_filters($report),
                    'filter' => $this->request->getVar('filter'),
                    'opted' => $this->request->getVar('opted'),
                    'selected' => $this->request->getVar('opted'),
                    'from' => $this->request->getVar('from'),
                    'to' => $this->request->getVar('to'),
                ];
                $view = 'admin/reports/reports/staffs';
                break;
            case 'clients':
                $data = [
                    'filters' => $this->report_filters($report),
                    'bal' => $this->request->getVar('bal'),
                    'btn' => $this->request->getVar('btn'),
                    'from' => $this->request->getVar('from'),
                    'to' => $this->request->getVar('to'),
                    'filter' => $this->request->getVar('filter'),
                ];
                $view = 'admin/reports/reports/clients';
                break;
            case 'products':
                if (!empty($this->request->getVar('from'))) {
                    $from = date('Y-m-d', strtotime($this->request->getVar('from')));
                } else {
                    $from = null;
                }
                if (!empty($this->request->getVar('to'))) {
                    $to = date('Y-m-d', strtotime($this->request->getVar('to')));
                } else {
                    $to = null;
                }
                $data = [
                    'from' => $from,
                    'to' => $to,
                ];
                $view = 'admin/reports/reports/products';
                break;
            case 'applications':
                $data = [
                    'filters' => $this->report_filters($report),
                    'filter' => $this->request->getVar('filter'),
                    'opted' => $this->request->getVar('opted'),
                    'selected' => $this->request->getVar('opted'),
                    'bal' => $this->request->getVar('bal'),
                    'btn' => $this->request->getVar('btn'),
                    'from' => $this->request->getVar('from'),
                    'to' => $this->request->getVar('to'),
                ];
                $view = 'admin/reports/reports/applications';
                break;
            case 'disbursements':
                $data = [
                    'filters' => $this->report_filters($report),
                    'filter' => $this->request->getVar('filter'),
                    'opted' => $this->request->getVar('opted'),
                    'selected' => $this->request->getVar('opted'),
                    'bal' => $this->request->getVar('bal'),
                    'btn' => $this->request->getVar('btn'),
                    'from' => $this->request->getVar('from'),
                    'to' => $this->request->getVar('to'),
                ];
                $view = 'admin/reports/reports/disbursements';
                break;
            case 'transactions':
                $data = [
                    'filters' => $this->report_filters($report),
                    'filter' => $this->request->getVar('filter'),
                    'selected' => $this->request->getVar('opted'),
                    'bal' => $this->request->getVar('bal'),
                    'btn' => $this->request->getVar('btn'),
                    'from' => $this->request->getVar('from'),
                    'to' => $this->request->getVar('to'),
                    // total financing transactions\entries
                    'financing' => $this->settings->countResults('entries', ['entry_menu' => 'financing', 'deleted_at' => null]),
                    'financingAmt' => $this->entry_total('financing'),
                    // total expense transactions\entries
                    'expenses' => $this->settings->countResults('entries', ['entry_menu' => 'expense', 'deleted_at' => null]),
                    'expenseAmt' => $this->entry_total('expense'),
                    // total transfer transactions\entries
                    'transfers' => $this->settings->countResults('entries', ['entry_menu' => 'transfer', 'deleted_at' => null]),
                    'transferAmt' => $this->entry_total('transfer'),
                    // total transactions\entries
                    'totalEntries' => $this->settings->countResults('entries', ['deleted_at' => null]),
                ];
                $view = 'admin/reports/reports/transactions';
                break;
            case 'logs':
                if (!empty($this->request->getVar('from'))) {
                    $from = date('Y-m-d', strtotime($this->request->getVar('from')));
                } else {
                    $from = null;
                }
                if (!empty($this->request->getVar('to'))) {
                    $to = date('Y-m-d', strtotime($this->request->getVar('to')));
                } else {
                    $to = null;
                }
                $data = [
                    'from' => $from,
                    'to' => $to,
                ];
                $view = 'admin/reports/reports/logs';
                break;
            case 'activities':
                if (!empty($this->request->getVar('from'))) {
                    $from = date('Y-m-d', strtotime($this->request->getVar('from')));
                } else {
                    $from = null;
                }
                if (!empty($this->request->getVar('to'))) {
                    $to = date('Y-m-d', strtotime($this->request->getVar('to')));
                } else {
                    $to = null;
                }
                $data = [
                    'from' => $from,
                    'to' => $to,
                ];
                $view = 'admin/reports/reports/activities';
                break;
            default:
                return redirect()->to(base_url('admin/reports/reports/report'));
                break;
        }

        $data['title'] = $this->title;
        $data['menu'] = $this->menu;
        $data['settings'] = $this->settingsRow;
        $data['user'] = $this->userRow;
        $data['userMenu'] = $this->load_menu();
        $data['report'] = $report;
        return view($view, $data);
    }

    // return report filter options as an array
    public function report_filters($report)
    {
        switch (strtolower($report)) {
            case "staffs":
                $filters = [
                    'null' => '-- select --',
                    'Role' => 'Role',
                    'Department' => 'Department',
                    'Position' => 'Position',
                ];
                break;
            case "clients":
                $filters = [
                    'null' => '-- select --',
                    'Equal' => 'Equal',
                    'Above' => 'Above',
                    'Below' => 'Below',
                    'Between' => 'Between',
                ];
                break;
            case "applications":
                $filters = [
                    'null' => '-- select --',
                    'Product' => 'Product',
                    'Status' => 'Status',
                    // 'Amount' => 'Amount',
                ];
                break;
            case "disbursements":
                $filters = [
                    'null' => '-- select --',
                    'Product' => 'Product',
                    'Class' => 'Class',
                    // 'Amount' => 'Amount',
                ];
                break;
            case "transactions":
                $filters = [
                    // 'null' => '-- select --',
                    'All' => 'All',
                    'Financing' => 'Financing',
                    'Expense' => 'Expense',
                    'Transfer' => 'Transfer',
                ];
                break;
            default:
                $filters = [
                    'null' => 'No Filter',
                ];
                break;
        }
        return $filters;
    }

    // return report filter options as json
    public function filter_options($report, $filter = null)
    {
        switch (strtolower($report)) {
            case "staffs":
                return $this->respond([
                    'Administrator', 'Employee',
                ]);
                break;
            case "applications":
                if (strtolower($filter) == 'amount') {
                    return $this->respond([
                        'Below', 'Equal', 'Above', 'Between',
                    ]);
                    exit;
                } else {
                    return $this->respond([
                        'Pending', 'Processing', 'Approved', 'Issued', 'Review', 'Declined',
                    ]);
                    exit;
                }
                break;
            case "disbursements":
                if (strtolower($filter) == 'amount') {
                    return $this->respond([
                        'Below', 'Equal', 'Above', 'Between',
                    ]);
                    exit;
                } else {
                    return $this->respond([
                        'Running', 'Pending', 'Cleared', 'Expired',
                    ]);
                    exit;
                }
                break;
            default:
                return $this->respond([
                    'No Option',
                ]);
                break;
        }
    }

    /** entry months */
    public function monthlyTransactionsTotal($entry_menu = null)
    {
        $year = date('Y');
        if (isset($entry_menu)) {
            $where = ['entry_menu' => strtolower($entry_menu), 'deleted_at' => null];
        } else {
            $where = ['deleted_at' => null];
        }
        # get the transactions from the entries
        $entries = $this->entry->where(
            ['YEAR(date)' => $year]
        )->where($where)->orderBy('date', 'asc')->findAll();

        # set an array to hold the monthly total amount for entries transaction
        $entry_monthly_total = [];
        $months = $this->entryMonths();
        # set the entries total to zero to always start the chart from zero in x - axis
        $entry_monthly_total[] = 0;
        # loop through the entry months
        foreach ($months as $month) {
            # set the month year 
            $month_year = $month . '-' . $year;
            # check the entries existance
            if (count($entries) > 0) {
                # set the total amount to zero to sum all the entries amount
                $total_amount = 0;
                foreach ($entries as $entry) {
                    # filter only month and year from the entry transaction date
                    $entry_year_month = date('M-Y', strtotime($entry['date']));
                    # check whether the entries transaction month year matches the entries month
                    if ($entry_year_month == $month_year) {
                        # perform sum of all the entries amount
                        $total_amount += $entry['amount'];
                    }
                }
                # store the entries total amount to the entry monthly total array
                $entry_monthly_total[] = $total_amount;
            } else {
                # set the entries total to zero incase the condition does not match
                $entry_monthly_total[] = 0;
            }
        }
        # return the jsco encoded entries transaction monthly total amount
        return $this->respond(($entry_monthly_total));
    }

    private function months()
    {
        return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    }
    private function entryMonths($year = null)
    {
        if (!isset($year)) {
            $year = date('Y');
        }
        $entrys = $this->entry->where(
            ['YEAR(date)' => $year]
        )->orderBy('date', 'asc')->findAll();
        $months = [];
        foreach ($entrys as $key => $value) {
            $month = date('M', strtotime($value['date']));
            $months[] = $month;
        }
        return array_unique($months);
    }

    // entry type total amount
    private function entry_total($entry_menu)
    {
        $entries = $this->entry->where(['entry_menu' => $entry_menu, 'deleted_at' => null])->findAll();
        $total = 0;
        foreach ($entries as $entry) {
            $total += $entry['amount'];
        }
        return $total;
    }
}
