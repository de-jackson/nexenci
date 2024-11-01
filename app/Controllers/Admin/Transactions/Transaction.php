<?php

namespace App\Controllers\Admin\Transactions;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;
use CodeIgniter\I18n\Time;

class Transaction extends MasterController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Transactions';
        $this->title = 'Transactions';
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            return view('admin/transactions/index', [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    /** redirect to transactions type view */
    public function view_transations($transaction = 'financing')
    {
        switch (strtolower($transaction)) {
            case 'expenses':
                $this->menu = $this->menu;
                $url = 'admin/transactions/expense/index';
                break;
            case 'transfer':
                $this->menu = $this->menu;
                $url = 'admin/transactions/transfer/index';
                break;
            case 'investment':
                $this->menu = $this->menu;
                $url = 'admin/transactions/investments/index';
                break;
            case 'deposits':
                $this->menu = ucfirst('savings');
                $part = 'credit';
                $url = 'admin/savings/transactions';
                break;
            case 'withdraws':
                $this->menu = ucfirst('savings');
                $part = 'debit';
                $url = 'admin/savings/transactions';
                break;
            case 'transfered':
                $this->menu = ucfirst('savings');
                $part = 'transfer';
                $url = 'admin/savings/transactions';
                break;
            case 'membership':
                $this->menu = ucfirst('members');
                $part = 'credit';
                $url = 'admin/transactions/financial/membership';
                break;
            case 'repayments':
                $this->menu = ucfirst('loans');
                $part = 'credit';
                $url = 'admin/transactions/financial/repayments';
                break;
            case 'applicationcharges':
                $this->menu = ucfirst('loans');
                $part = 'credit';
                $url = 'admin/transactions/financial/applications';
                break;
            default:
                session()->setFlashdata('failed', ucwords($transaction) . ' Page requested can not be found!');
                return redirect()->to(base_url('admin/dashboard'));
                break;
        }
        $this->title = ucfirst($transaction);
        $this->menuItem = [
            'menu' => $this->menu,
            'title' => $this->title,
        ];
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $data = [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'part' => (isset($part) ? $part : null),
            ];

            return view($url, $data);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    // redirect to transaction by reference id
    public function refID_transaction($ref_id)
    {
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $transaction = $this->entry->where(['ref_id' => $ref_id])->findAll();
            if ($transaction) {
                return view('admin/transactions/ref_transaction', [
                    'title' => ucfirst($ref_id),
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                    'ref_id' => $ref_id,
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaction could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    function transaction_forms($id, $menu)
    {
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            if ($id == 0) {
                $title = "Add " . ucwords($menu) . " Transaction Form";
                $data = '';
            } else {
                $title = "Transaction " . ucwords($menu) . " View Form";
                $entry = $this->entry->find($id);
                if (($entry['client_id'] != null) && (($entry['application_id'] == null) && ($entry['disbursement_id'] == null))) {
                    // join to clients table if client_id is not empty
                    $data = $this->entry
                        ->select('entries.*, debitParticular.particular_name as payment_id, creditParticular.particular_name as particular_id, staffs.staff_name, clients.name')
                        ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                        ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                        ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                        ->join('clients', 'clients.id = entries.client_id', 'left')
                        ->find($id);
                } else if (($entry['client_id'] != null) && ($entry['application_id'] != null)) {
                    // join to clients n loanapplications tables if client_id && application_id are not empty
                    $data = $this->entry
                        ->select('entries.*, debitParticular.particular_name as payment_id, creditParticular.particular_name as particular_id, staffs.staff_name, clients.name, loanapplications.application_code, entries.entry_menu, loanproducts.product_name')
                        ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                        ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                        ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                        ->join('clients', 'clients.id = entries.client_id', 'left')
                        ->join('loanapplications', 'loanapplications.id = entries.application_id', 'left')
                        ->join('loanproducts', 'loanproducts.id = loanapplications.product_id', 'left')
                        ->find($id);
                } else if (($entry['client_id'] != null) && ($entry['disbursement_id'] != null)) {
                    // join to clients n disbursements tables if client_id && disbursement_id are not empty
                    $data = $this->entry
                        ->select('entries.*, debitParticular.particular_name as payment_id, creditParticular.particular_name as particular_id, staffs.staff_name, clients.name, disbursements.disbursement_code, disbursements.class, disbursements.cycle, loanproducts.product_name')
                        ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                        ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                        ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                        ->join('clients', 'clients.id = entries.client_id', 'left')
                        ->join('disbursements', 'disbursements.id = entries.disbursement_id', 'left')
                        ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')
                        ->find($id);
                } else {
                    // join to staffs table
                    $data = $this->entry
                        ->select('entries.*, debitParticular.particular_name as payment_id, creditParticular.particular_name as particular_id, staffs.staff_name')
                        ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                        ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                        ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                        ->find($id);
                }
            }
            return view('admin/transactions/transaction_formPDF', [
                'title' => $title,
                'menu' => $menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'id' => $id,
                'transaction' => $data,
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    /** return transactions as rows */
    public function transactions_list($entry_menu, $part = null)
    {
        $this->menuItem['title'] = ucfirst($entry_menu);
        // all transactions list
        switch (strtolower($entry_menu)) {
            case "savings": // savings transactions list
                $this->menu = 'savings';
                if (isset($part) && strtolower($part) == 'credit') {
                    $this->title = ucwords('Deposits');
                    // Credit condition: status = 'credit' and account_typeId = 12
                    $where = [
                        'entries.status' => 'credit',
                        'entries.account_typeId' => 12,
                        'entries.entry_menu' => 'financing',
                        'entries.deleted_at' => null
                    ];
                } elseif (isset($part) && strtolower($part) == 'debit') {
                    $this->title = ucwords('Withdraws');
                    // Debit condition: 
                    // status = 'credit' and account_typeId = 20 
                    // OR status = 'debit' and account_typeId = 12
                    $where = "(entries.status = 'debit' AND entries.account_typeId = 12 AND  entries.entry_menu = 'financing') OR (entries.status = 'credit' AND entries.account_typeId = 20 AND entries.entry_menu = 'financing')";
                } else {
                    // Default condition: only consider non-deleted entries
                    $where = ['entries.deleted_at' => null];
                }
                # 12: Savings
                # 20: Revenue from Deposit & Withdraw
                # 24: Membership
                $account_typeId = ["12", "20"];
                $transactions = $this->entry
                    ->select('particulars.particular_name, payments.particular_name as payment_method, account_types.code, entrytypes.type, clients.name, clients.title, clients.account_no, clients.photo, clients.reg_date, staffs.staff_name, entries.id, entries.date, entries.amount, entries.ref_id, entries.account_typeId, entries.status, entries.entry_menu, entries.created_at')
                    ->join('clients', 'clients.id = entries.client_id', 'left')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                    ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                    ->join('particulars', 'particulars.id = entries.particular_id', 'left')
                    ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
                    ->where($where)->orderBy('entries.date', 'desc');
                break;
            case "repayments": // repayments transactions list
                $this->menu = 'loans';
                $this->title = ucwords('repayments');
                $transactions = $this->entry
                    ->select('particulars.particular_name, payments.particular_name as payment_method, disbursements.disbursement_code, loanproducts.product_name, account_types.code, entrytypes.type, clients.name, clients.title, clients.account_no, clients.photo, clients.reg_date, staffs.staff_name, entries.id, entries.date, SUM(entries.amount) as amount,entries.ref_id, entries.account_typeId, entries.status, entries.entry_menu, entries.created_at')
                    ->join('clients', 'clients.id = entries.client_id', 'left')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                    ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                    ->join('particulars', 'particulars.id = entries.particular_id', 'left')
                    ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
                    ->join('disbursements', 'disbursements.id = entries.disbursement_id', 'left')
                    ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')
                    ->where(['entries.account_typeId' => 3, 'entries.status' => 'credit', 'entries.entry_menu' => 'financing', 'entries.deleted_at' => null])->groupBy('entries.ref_id')->orderBy('entries.date', 'desc');
                break;
            case "membership": // membership transactions list
                $this->menu = 'clients';
                $this->title = ucwords('membership');
                $transactions = $this->entry
                    ->select('particulars.particular_name, payments.particular_name as payment_method, account_types.code, entrytypes.type, clients.name, clients.title, clients.account_no, clients.photo, clients.reg_date, staffs.staff_name, entries.id, entries.date, entries.amount, entries.ref_id, entries.account_typeId, entries.status, entries.entry_menu, entries.created_at')
                    ->join('clients', 'clients.id = entries.client_id', 'left')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                    ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                    ->join('particulars', 'particulars.id = entries.particular_id', 'left')
                    ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
                    ->where(['entries.account_typeId' => 24, 'entries.entry_menu' => 'financing', 'entries.deleted_at' => null])->orderBy('entries.date', 'desc');
                break;
            case "applicationcharges": // applications transactions list
                $this->menu = 'loans';
                $this->title = ucwords('application charges');
                $transactions = $this->entry
                    ->select('particulars.particular_name, payments.particular_name as payment_method, loanapplications.application_code, loanproducts.product_name, account_types.code, entrytypes.type, clients.name, clients.title, clients.account_no, clients.photo, clients.reg_date, staffs.staff_name, entries.id, entries.date, entries.amount, entries.ref_id, entries.account_typeId, entries.status, entries.entry_menu, entries.created_at')
                    ->join('clients', 'clients.id = entries.client_id', 'left')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                    ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                    ->join('particulars', 'particulars.id = entries.particular_id', 'left')
                    ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
                    ->join('loanapplications', 'loanapplications.id = entries.application_id', 'left')
                    ->join('loanproducts', 'loanproducts.id = loanapplications.product_id', 'left')
                    ->where(['entries.account_typeId' => 18, 'entries.entry_menu' => 'financing', 'entries.deleted_at' => null])->orderBy('entries.date', 'desc');
                break;
            case "expense":  // expense  transactions list
                $this->title = ucwords('expenses');
                $transactions = $this->entry
                    ->select('particulars.particular_name, payments.particular_name as payment_method, account_types.code,  account_types.name as module,account_types.code,  account_types.name as module, staffs.staff_name, entrytypes.type, entries.id, entries.date, entries.amount, entries.ref_id, entries.account_typeId, entries.status, entries.entry_menu, entries.entry_menu, entries.created_at')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                    ->join('particulars', 'particulars.id = entries.particular_id', 'left')
                    ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
                    ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                    ->where(['entries.entry_menu' => 'expense', 'entries.deleted_at' => null])->orderBy('entries.date', 'desc');
                break;
            case "transfer": // transfer  transactions list
                $this->title = ucwords('transfer');
                $transactions = $this->entry
                    ->select('crParticular.particular_name as crParticular_name, account_types.code, account_types.name as module, staffs.staff_name, drParticular.particular_name as drParticular_name, entrytypes.type, entries.id, entries.date, entries.amount, entries.ref_id, entries.account_typeId, entries.status, entries.entry_menu, entries.created_at')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                    ->join('particulars as drParticular', 'drParticular.id = entries.particular_id', 'left')
                    ->join('particulars as crParticular', 'crParticular.id = entries.payment_id', 'left')
                    ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                    ->where(['entries.entry_menu' => 'transfer', 'entries.deleted_at' => null])->orderBy('entries.date', 'desc');
                break;
            case "investment": // investment  transactions list
                $this->title = ucwords('investment');
                $transactions = $this->entry
                    ->select('crParticular.particular_name as crParticular_name, crParticular.account_typeId, crAccount_types.name as cr_module, crAccount_types.code, drParticular.particular_name as drParticular_name, drParticular.account_typeId, drAccount_types.name as dr_module, staffs.staff_name, entrytypes.type, entries.id, entries.date, entries.amount, entries.ref_id, entries.account_typeId, entries.status, entries.entry_menu, entries.created_at')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('particulars as crParticular', 'crParticular.id = entries.particular_id', 'left')
                    ->join('account_types as crAccount_types', 'crAccount_types.id = crParticular.account_typeId', 'left')
                    ->join('particulars as drParticular', 'drParticular.id = entries.payment_id', 'left')
                    ->join('account_types as drAccount_types', 'drAccount_types.id = drParticular.account_typeId', 'left')
                    ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                    ->where(['entries.entry_menu' => 'investment', 'entries.deleted_at' => null])->orderBy('entries.date', 'desc');
                break;
            case "shares": // shares transactions list
                $this->menu = 'shares';
                if (isset($part) && strtolower($part) == 'credit') {
                    $this->title = ucwords('purchases');
                } elseif (isset($part) && strtolower($part) == 'debit') {
                    $this->title = ucwords('withdrawals');
                }
                $where = isset($part) ? ['entries.account_typeId' => 8, 'entries.status' => strtolower($part), 'entries.entry_menu' => 'financing', 'entries.deleted_at' => null] : ['entries.account_typeId' => 8, 'entries.entry_menu' => 'financing', 'entries.deleted_at' => null];
                $transactions = $this->entry
                    ->select('particulars.particular_name, payments.particular_name as payment_method, account_types.code, entrytypes.type, clients.name, clients.title, clients.account_no, clients.photo, clients.reg_date, staffs.staff_name, entries.id, entries.client_id, entries.particular_id, entries.date, entries.amount, entries.ref_id, entries.account_typeId, entries.status, entries.entry_menu, entries.created_at')
                    ->join('clients', 'clients.id = entries.client_id', 'left')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                    ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                    ->join('particulars', 'particulars.id = entries.particular_id', 'left')
                    ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
                    ->where($where)->orderBy('entries.date', 'desc');
                break;
            default: // all transactions list
                $transactions = $this->entry
                    ->select('particulars.particular_name, payments.particular_name as payment_method, account_types.code, account_types.name as module, entrytypes.type, entries.date, entries.amount, account_types.code,  account_types.name as module, staffs.staff_name, entries.ref_id, entries.status,  entries.created_at, entries.id')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                    ->join('particulars', 'particulars.id = entries.particular_id', 'left')
                    ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
                    ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                    ->where(['entries.deleted_at' => null])->orderBy('entries.date', 'desc');
                break;
        }

        $this->menuItem['menu'] = $this->menu;
        $this->menuItem['title'] = $this->title;

        return DataTable::of($transactions)
            ->add('checkbox', function ($transaction) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $transaction->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('name', function ($transaction) {
                if (strtolower($transaction->entry_menu) == 'financing') {
                    # check whether the photo exist
                    if (file_exists("uploads/clients/passports/" . $transaction->photo) && $transaction->photo) {
                        # display the current photo
                        $photo = '
                        <a href="javascript:void(0)" title="' . strtoupper($transaction->name) . '"><img src="' . base_url('uploads/clients/passports/' . $transaction->photo) . '" style="width:40px;height:40px;" class="avatar avatar-md" /></a>
                        ';
                    } else {
                        # display the default photo
                        $photo =  '
                        <a href="javascript:void(0)" title="No photo"><img src="' . base_url('assets/dist/img/nophoto.jpg') . '" style="width:40px;height:40px;" class="avatar avatar-md" /></a>
                        ';
                    }

                    return '<div class="products">
                    ' . $photo . '
                        <div>
                            <h6>' . ucwords($transaction->title) . ' ' . trim(strtoupper($transaction->name)) . '</h6>
                            <span>' . trim($transaction->account_no) . '</span>	
                        </div>	
                    </div>';
                } else {
                    return null;
                }
            })
            ->add('type', function ($entry) {
                switch (strtolower($entry->entry_menu)) {
                    case 'investment':
                        return '<b>' . ucwords($entry->type) . '</b>:</b><br>' . ucwords($entry->cr_module);
                        break;
                    case 'transfer':
                        return '<b>' . ucwords($entry->type) . '</b>:</b><br>' . ucwords($entry->module);
                        break;
                    case 'expense':
                        return '<b>' . ucwords($entry->type) . '</b>:</b><br>' . ucwords($entry->module);
                        break;
                    default:
                        return '<b>' . ucwords($entry->type) . ':</b><br>' . ucwords($entry->particular_name);
                        break;
                }
            })
            ->add('shares', function ($entry) {
                if (($entry->account_typeId == 8) && (strtolower($entry->entry_menu) == 'financing')) {
                    $client_id = $entry->client_id;
                    $reg_date = $entry->reg_date;
                    $particular_id = $entry->particular_id;
                    $status = $entry->status;
                    # get all shares transactions
                    $sharesTransactions = $this->entry->select('SUM(amount) as amount')->where(['entries.account_typeId' => 8, 'entries.particular_id' => $particular_id])->get()->getRow();
                    // return $sharesTransactions;
                    if (!$sharesTransactions) {
                        return 'No Data';
                    }
                    # calculate total shares bought by all clients
                    $totalSharesBought = $sharesTransactions->amount;

                    /*
                    # get client shares transactions
                    $clientTotalSharesPurchase = $this->entry->select('SUM(amount) as amount')->where(['client_id' => $client_id, 'entries.account_typeId' => 8, 'entries.particular_id' => $particular_id])->get()->getRow();
                    if ($clientTotalSharesPurchase) {
                        $clientSharesPurchase = $clientTotalSharesPurchase->amount;
                        # lookup charge for share particular
                        $chargeRow = $this->vlookupSharesCharge($particular_id, $reg_date);
                        if ($chargeRow) {
                            return '<b>'.round(($clientSharesPurchase / $chargeRow['charge']), 2) . ' Units ~ ' . round((($clientSharesPurchase / $totalSharesBought) * 100), 2) . '%</b>:<br>'.ucwords($status);
                        } else {
                            return '<b>'. round(($clientSharesPurchase / $totalSharesBought), 2) . ' Units:</b><br>'. ucwords($status);
                        }
                    } else {
                        return 'N/A';
                    }
                    */
                    # calculate client shares units for entry
                    $clientSharesPurchase = $entry->amount;
                    # lookup charge for share particular
                    $chargeRow = $this->vlookupSharesCharge($particular_id, $reg_date);
                    if ($chargeRow) {
                        return '<b>' . round(($clientSharesPurchase / $chargeRow['charge']), 2) . ' Units:</b><br>' . ucwords($status);
                    } else {
                        return '<b>' . round(($clientSharesPurchase / $totalSharesBought), 2) . ' Units:</b><br>' . ucwords($status);
                    }
                } else {
                    return null;
                }
            })
            ->add('action', function ($transaction) {
                $viewBtn = $updateBtn = $deleteBtn = '';
                // show view button
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                    $viewBtn = '<a href="javascript:void(0)" onclick="view_transaction(' . "'" . $transaction->id . "'" . ',' . "'" . $transaction->amount . "'" . ')" title="view ' . $transaction->ref_id . ' transaction" class="dropdown-item"><i class="fas fa-eye text-success"></i> View ' . $transaction->ref_id . ' Transaction</a>
                    <div class="dropdown-divider"></div>
                    <a href="/admin/transactions/receipt/' . $transaction->id . '/' . $transaction->code . '"  title="View ' . $transaction->ref_id . ' receipt" class="dropdown-item"><i class="fas fa-receipt text-info"></i> View ' . $transaction->ref_id . ' Receipt</a>';
                }
                // show update button
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    if (($transaction->account_typeId != 3) && ($transaction->status != 'debit')) {
                        $updateBtn = '<div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="edit_transaction(' . "'" . $transaction->id . "'" . ',' . "'" . $transaction->amount . "'" . ')" title="edit ' . $transaction->ref_id . ' transaction" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit ' . $transaction->ref_id . ' Transaction</a>';
                    } else {
                        $updateBtn = '';
                    }
                }
                // show delete button
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $deleteBtn = '<div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" onclick="delete_transaction(' . "'" . $transaction->id . "'" . ',' . "'" . $transaction->ref_id . "'" . ')" title="delete ' . $transaction->ref_id . ' transaction" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete ' . $transaction->ref_id . ' Transaction</a>';
                }
                $text = "info";
                // show buttons based on user permissions
                $actions = '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">' .
                    $viewBtn . $updateBtn . $deleteBtn
                    . '</div>
                </div>';
                return $actions;
            })
            ->toJson(true);
    }

    public function transactions_report($filter, $val = null, $parm = null, $from = null, $to = null)
    {
        $this->menuItem['title'] = $this->title;
        if (strtolower($filter) == 'all') {
            if ($from != 0 && $to == 0) {
                if ($val != 0 && $parm == 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'entries.amount >' => $val, 'entries.deleted_at' => Null];
                } elseif ($val == 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'entries.amount >' => $parm, 'entries.deleted_at' => Null];
                } elseif ($val != 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'entries.amount >' => $val, 'entries.amount <' => $parm, 'entries.deleted_at' => Null];
                } else {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'entries.deleted_at' => Null];
                }
            } elseif ($from == 0 && $to != 0) {
                if ($val != 0 && $parm == 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $to, 'entries.amount >' => $val, 'entries.deleted_at' => Null];
                } elseif ($val == 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $to, 'entries.amount >' => $parm, 'entries.deleted_at' => Null];
                } elseif ($val != 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $to, 'entries.amount >' => $val, 'entries.amount <' => $parm, 'entries.deleted_at' => Null];
                } else {
                    $where = ['DATE_FORMAT(entries.created_at, "%Y-%m-%d") >=' => $to, 'entries.deleted_at' => Null];
                }
            } elseif ($from != 0 && $to != 0) {
                if ($val != 0 && $parm == 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(entries.date,, "%Y-%m-%d") <=' => $to, 'entries.amount >' => $val, 'entries.deleted_at' => Null];
                } elseif ($val == 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(entries.date,, "%Y-%m-%d") <=' => $to, 'entries.amount >' => $parm, 'entries.deleted_at' => Null];
                } elseif ($val != 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(entries.date,, "%Y-%m-%d") <=' => $to, 'entries.amount >' => $val, 'entries.amount <' => $parm, 'entries.deleted_at' => Null];
                } else {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(entries.date,, "%Y-%m-%d") <=' => $to, 'entries.deleted_at' => Null];
                }
            } else {
                if ($val != 0 && $parm == 0) {
                    $where = ['entries.amount >' => $val, 'entries.deleted_at' => Null];
                } elseif ($val == 0 && $parm != 0) {
                    $where = ['entries.amount >' => $parm, 'entries.deleted_at' => Null];
                } elseif ($val != 0 && $parm != 0) {
                    $where = ['entries.amount >' => $val, 'entries.amount <' => $parm, 'entries.deleted_at' => Null];
                } else {
                    $where = ['entries.deleted_at' => Null];
                }
            }
        } else {
            if ($from != 0 && $to == 0) {
                if ($val != 0 && $parm == 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'entries.amount >' => $val, 'entries.entry_menu' => $filter, 'entries.deleted_at' => Null];
                } elseif ($val == 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'entries.amount >' => $parm, 'entries.entry_menu' => $filter, 'entries.deleted_at' => Null];
                } elseif ($val != 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'entries.amount >' => $val, 'entries.amount <' => $parm, 'entries.entry_menu' => $filter, 'entries.deleted_at' => Null];
                } else {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'entries.entry_menu' => $filter, 'entries.deleted_at' => Null];
                }
            } elseif ($from == 0 && $to != 0) {
                if ($val != 0 && $parm == 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $to, 'entries.amount >' => $val, 'entries.entry_menu' => $filter, 'entries.deleted_at' => Null];
                } elseif ($val == 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $to, 'entries.amount >' => $parm, 'entries.entry_menu' => $filter, 'entries.deleted_at' => Null];
                } elseif ($val != 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $to, 'entries.amount >' => $val, 'entries.amount <' => $parm, 'entries.entry_menu' => $filter, 'entries.deleted_at' => Null];
                } else {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $to, 'entries.entry_menu' => $filter, 'entries.deleted_at' => Null];
                }
            } elseif ($from != 0 && $to != 0) {
                if ($val != 0 && $parm == 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(entries.date,, "%Y-%m-%d") <=' => $to, 'entries.entry_menu' => $filter, 'entries.amount >' => $val, 'entries.deleted_at' => Null];
                } elseif ($val == 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(entries.date,, "%Y-%m-%d") <=' => $to, 'entries.entry_menu' => $filter, 'entries.amount >' => $parm, 'entries.deleted_at' => Null];
                } elseif ($val != 0 && $parm != 0) {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(entries.date,, "%Y-%m-%d") <=' => $to, 'entries.entry_menu' => $filter, 'entries.amount >' => $val, 'entries.amount <' => $parm, 'entries.deleted_at' => Null];
                } else {
                    $where = ['DATE_FORMAT(entries.date,, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(entries.date,, "%Y-%m-%d") <=' => $to, 'entries.entry_menu' => $filter, 'entries.deleted_at' => Null];
                }
            } else {
                if ($val != 0 && $parm == 0) {
                    $where = ['entries.entry_menu' => $filter, 'entries.amount >' => $val, 'entries.deleted_at' => Null];
                } elseif ($val == 0 && $parm != 0) {
                    $where = ['entries.entry_menu' => $filter, 'entries.amount >' => $parm, 'entries.deleted_at' => Null];
                } elseif ($val != 0 && $parm != 0) {
                    $where = ['entries.entry_menu' => $filter, 'entries.amount >' => $val, 'entries.amount <' => $parm, 'entries.deleted_at' => Null];
                } else {
                    $where = ['entries.entry_menu' => $filter, 'entries.deleted_at' => Null];
                }
            }
        }
        $transactions = $this->entry
            ->select('particulars.particular_name, payments.particular_name as payment_method, entries.date, entries.amount,account_types.code,  account_types.name as module, staffs.staff_name, entries.ref_id, entries.created_at, entries.id')
            ->join('staffs', 'staffs.id = entries.staff_id', 'left')
            ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
            ->where($where)->groupBy('entries.ref_id')->orderBy('entries.id', 'desc');

        return DataTable::of($transactions)
            ->add('checkbox', function ($transaction) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $transaction->id . '">
                    </div>
                ';
            })
            ->addNumbering("no")
            ->add('action', function ($transaction) {
                return '
                    <div class="text-center">
                        <a href="javascript:void(0)" onclick="view_transaction(' . "'" . $transaction->id . "'" . ')" title="view transaction" class="dropdown-item"><i class="fas fa-eye text-success"></i></a>
                    </div>
                ';
            })
            ->toJson(true);
    }

    public function ref_transactionList($ref_id)
    {
        $this->menuItem['title'] = $this->title;
        $transactions = $this->entry
            ->select('staffs.staff_name, particulars.particular_name, payments.particular_name as payment_method, account_types.code, account_types.name as module, entrytypes.type, entries.date, entries.amount, entries.ref_id, entries.entry_menu, entries.created_at, entries.id')
            ->join('staffs', 'staffs.id = entries.staff_id', 'left')
            ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->where(['entries.deleted_at' => null, 'entries.ref_id' => $ref_id])->groupBy('entries.ref_id')->orderBy('entries.id', 'desc');

        return DataTable::of($transactions)
            ->add('checkbox', function ($transaction) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $transaction->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('type', function ($entry) {
                switch (strtolower($entry->entry_menu)) {
                    case 'investment':
                        return '<b>' . ucwords($entry->type) . '</b>:</b><br>' . ucwords($entry->cr_module);
                        break;
                    case 'transfer':
                        return '<b>' . ucwords($entry->type) . '</b>:</b><br>' . ucwords($entry->module);
                        break;
                    case 'expense':
                        return '<b>' . ucwords($entry->type) . '</b>:</b><br>' . ucwords($entry->module);
                        break;
                    default:
                        return '<b>' . ucwords($entry->type) . ':</b><br>' . ucwords($entry->particular_name);
                        break;
                }
            })
            ->add('action', function ($transaction) {
                return '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-info tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0)" onclick="view_transaction(' . "'" . $transaction->id . "'" . ')" title="view transaction" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Transaction</a>
                        <div class="dropdown-divider"></div>
                        <a href="/admin/transactions/receipt/' . $transaction->id . '/' . $transaction->code . '"  title="Generate receipt" class="dropdown-item"><i class="fas fa-receipt text-info"></i> View Receipt</a>
                        <!-- <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="edit_transaction(' . "'" . $transaction->id . "'" . ')" title="edit transaction" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit Transaction</a> -->
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="delete_transaction(' . "'" . $transaction->id . "'" . ',' . "'" . $transaction->ref_id . "'" . ')" title="delete transaction" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Transaction</a>
                    </div>
                </div>';
            })
            ->toJson(true);
    }

    public function client_transactions($client_id = null, $type_id = null)
    {
        $where = ['entries.client_id' => $client_id, 'entries.deleted_at' => Null];

        $entries = $this->entry
            ->select('entries.id, entries.client_id, entries.account_typeId, entries.date, entries.created_at, entries.amount, entries.balance, entries.ref_id, entries.entry_details, entries.status,entries.parent_id, particulars.particular_name, payments.particular_name as payment_method, entrytypes.type,account_types.code,  account_types.name as module, staffs.staff_name')
            ->join('staffs', 'staffs.id = entries.staff_id', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
            ->where($where)->orderBy('entries.date', 'desc')->orderBy('entries.id', 'desc');

        return DataTable::of($entries)
            ->add('checkbox', function ($entry) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $entry->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('type', function ($entry) {
                return '<b>' . ucwords($entry->type) . ':</b><br>' . ucwords($entry->particular_name);
            })
            ->add('account_bal', function ($entry) {
                $debitBal = $creditBal = $acc_balance = 0;

                // Fetch transactions for the client up until the current entry's created_at
                $transactions = $this->entry->select('entries.*, 
                    parentEntries.id as p_id, 
                    parentEntries.account_typeId as p_account_typeId, 
                    parentEntries.amount as p_amount, 
                    parentEntries.status as p_status')
                    ->where([
                        'entries.client_id' => $entry->client_id,
                        'entries.date <=' => $entry->date,
                        'entries.id <=' => $entry->id
                    ])
                    ->join('entries as parentEntries', 'entries.parent_id = parentEntries.id', 'left')
                    ->orderBy('entries.date', 'asc')
                    ->orderBy('entries.id', 'asc')
                    ->findAll();

                if (count($transactions) > 0) {
                    foreach ($transactions as $row) {
                        // Check if this is a savings transaction or if it's derived from a savings transaction
                        $isSavingsTransaction = $row['account_typeId'] == 12;
                        $isDerivedFromSavings = !empty($row['parent_id']) && $row['p_account_typeId'] == 12;

                        // calculate the balance If it's a savings transaction
                        if ($isSavingsTransaction) {
                            if (strtolower($row['status']) == 'debit') {
                                $debitBal += $row['amount'];
                            }
                            if (strtolower($row['status']) == 'credit') {
                                $creditBal += $row['amount'];
                            }
                        }
                        if ($isDerivedFromSavings) {
                            // calculate the balance If it's derived a savings one
                            if (strtolower($row['status']) == 'debit') {
                                $creditBal += $row['amount'];
                            }
                            if (strtolower($row['status']) == 'credit') {
                                $debitBal += $row['amount'];
                            }
                        }
                    }
                }

                // Calculate account balance by subtracting debits from credits
                $acc_balance = abs($creditBal - $debitBal);
                return $acc_balance;
            })
            /*->add('account_bal', function ($entry) {
                # Calculate debits and credits at the DB level
                $balances = $this->entry->select('
                        SUM(CASE 
                            WHEN entries.account_typeId = 12 AND LOWER(entries.status) = "debit" THEN entries.amount
                            WHEN parentEntries.account_typeId = 12 AND LOWER(parentEntries.status) = "credit" THEN parentEntries.amount
                            ELSE 0 
                        END) as debitBal,
                        SUM(CASE 
                            WHEN entries.account_typeId = 12 AND LOWER(entries.status) = "credit" THEN entries.amount
                            WHEN parentEntries.account_typeId = 12 AND LOWER(parentEntries.status) = "debit" THEN parentEntries.amount
                            ELSE 0 
                        END) as creditBal
                    ')
                    ->where([
                        'entries.client_id' => $entry->client_id,
                        'entries.date <=' => $entry->date,
                        'entries.id <=' => $entry->id
                    ])
                    ->join('entries as parentEntries', 'entries.parent_id = parentEntries.id', 'left')
                    ->get()->getRow();
            
                // Calculate account balance by subtracting debits from credits
                if ($balances) {
                    $acc_balance = ((float)$balances->creditBal - (float)$balances->debitBal);
                    // $acc_balance = ($accBalance < 0) ? -($accBalance) : $accBalance;
                } else {
                    $acc_balance = '0.00';
                }
            
                return $acc_balance;
            })*/


            ->add('action', function ($entry) {
                $text = "info";
                return '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="/admin/transaction/info/' . $entry->ref_id . '"  title="view transaction" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Transaction</a>
                        <div class="dropdown-divider"></div>
                        <a href="/admin/transactions/receipt/' . $entry->id . '/' . $entry->code . '"  title="Generate receipt" class="dropdown-item"><i class="fas fa-receipt text-info"></i> View Receipt</a>
                    </div>
                </div> ';
            })
            ->toJson(true);
    }

    public function applicant_transactions($application_id)
    {
        $entries = $this->entry
            ->select('particulars.particular_name, account_types.code,  account_types.name as module, entries.date, entries.amount, entries.ref_id, entries.contact, entries.staff_id, staffs.staff_name, entries.created_at, entries.id')
            ->join('staffs', 'staffs.id = entries.staff_id', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
            ->where(['application_id' => $application_id, 'entries.account_typeId' => 18,  'entries.deleted_at' => Null])->groupBy('entries.ref_id')->orderBy('entries.date', 'desc');

        return DataTable::of($entries)
            ->add('checkbox', function ($entry) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $entry->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('action', function ($entry) {
                $text = "info";
                return '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0)" onclick="view_transaction(' . "'" . $entry->id . "'" . ')" title="View Transaction" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Transaction</a>
                        <div class="dropdown-divider"></div>
                        <a href="/admin/transactions/receipt/' . $entry->id . '/' . $entry->code . '"  title="Transaction Receipt" class="dropdown-item"><i class="fas fa-receipt text-info"></i> View Receipt</a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="delete_particularPayment(' . "'" . $entry->id . "'" . ',' . "'" . $entry->particular_name . "'" . ')" title="Delete Transaction" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Transaction</a>
                    </div>
                </div>';
            })
            ->toJson(true);
    }

    public function disbursement_transactions($disbursement_id)
    {
        $entries = $this->entry
            ->select('entries.id,entries.disbursement_id, entries.account_typeId, entries.date, entries.created_at, SUM(entries.amount) as amount, entries.ref_id, entries.entry_details, entries.status, particulars.particular_name, payments.particular_name as payment, account_types.code,  account_types.name as module, entrytypes.type, entrytypes.part, staffs.staff_name')
            ->join('staffs', 'staffs.id = entries.staff_id', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
            ->where(['entries.disbursement_id' => $disbursement_id, 'entries.status' => 'credit', 'entries.deleted_at' => Null])->groupBy('entries.ref_id')->orderBy('entries.date', 'desc');

        return DataTable::of($entries)
            ->add('checkbox', function ($entry) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $entry->id . '">
                    </div>
                ';
            })
            ->addNumbering("no")
            ->add('balance', function ($entry) {
                $debitBal = $creditBal = 0;
                $disbursement = $this->loanDisbursementRow($entry->disbursement_id);
                $repayments = $this->entry->where(['entries.disbursement_id' => $disbursement['id'], 'entries.status' => 'credit', 'entries.created_at <=' => $entry->created_at])->findAll();
                if (count($repayments) > 0) {
                    foreach ($repayments as $row) {
                        if (strtolower($row['status']) == 'debit') {
                            $debitBal += $row['amount'];
                        } else {
                            $creditBal += $row['amount'];
                        }
                    }
                }
                return ($disbursement['actual_repayment'] - abs($debitBal - $creditBal));
            })
            ->add('action', function ($entry) {
                $text = "info";
                return '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0)" onclick="view_transaction(' . "'" . $entry->id . "'" . ')" title="View Transaction" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Transaction</a>
                        <div class="dropdown-divider"></div>
                        <a href="/admin/transactions/receipt/' . $entry->id . '/' . $entry->code . '"  title="Generate receipt" class="dropdown-item"><i class="fas fa-receipt text-info"></i> View Receipt</a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="delete_particularTransaction(' . "'" . $entry->id . "'" . ',' . "'" . $entry->particular_name . "'" . ')" title="Delete Transaction" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Transaction</a>
                    </div>
                </div>';
            })
            ->toJson(true);
    }

    public function generate_receipt($id = null, $receipt = null)
    {
        $entry = $this->entry->find($id);
        if ($entry['client_id'] != null && ($entry['application_id'] == null) && ($entry['disbursement_id'] == null)) {
            // join to clients table if client_id is not empty
            $entryRow = $this->entry
                ->select('entries.*, debitParticular.particular_name as payment, creditParticular.particular_name, entrytypes.type,account_types.code,  account_types.name as module, staffs.staff_name, staffs.account_type, staffs.signature, branches.branch_name, clients.name, clients.account_no, clients.signature as sign')
                ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                ->join('branches', 'branches.id = entries.branch_id', 'left')
                ->join('clients', 'clients.id = entries.client_id', 'left')
                ->find($id);
        } else if (($entry['client_id'] != null) && ($entry['application_id'] != null)) {
            // join to clients n loanapplications tables if client_id && application_id are not empty
            $entryRow = $this->entry
                ->select('entries.*, debitParticular.particular_name as payment, creditParticular.particular_name, entrytypes.type,account_types.code,  account_types.name as module, staffs.staff_name, staffs.account_type, staffs.signature, branches.branch_name, clients.name, clients.account_no, clients.signature as sign, loanapplications.application_code, entries.entry_menu, loanproducts.product_name')
                ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                ->join('branches', 'branches.id = entries.branch_id', 'left')
                ->join('clients', 'clients.id = entries.client_id', 'left')
                ->join('loanapplications', 'loanapplications.id = entries.application_id', 'left')
                ->join('loanproducts', 'loanproducts.id = loanapplications.product_id', 'left')
                ->find($id);
        } else if ($entry['client_id'] != null && $entry['disbursement_id'] != null) {
            // join to clients n disbursements tables if client_id && disbursement_id are not empty
            $entryRow = $this->entry
                ->select('entries.*, debitParticular.particular_name as payment, creditParticular.particular_name,entrytypes.type,account_types.code,  account_types.name as module, staffs.staff_name, staffs.account_type, staffs.signature, branches.branch_name, clients.name, clients.account_no, clients.signature as sign, disbursements.disbursement_code, disbursements.cycle, disbursements.cycle, disbursements.class, loanproducts.product_name')
                ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                ->join('branches', 'branches.id = entries.branch_id', 'left')
                ->join('clients', 'clients.id = entries.client_id', 'left')
                ->join('disbursements', 'disbursements.id = entries.disbursement_id', 'left')
                ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')
                ->find($id);
        } else {
            // join to staffs table
            $entryRow = $this->entry
                ->select('entries.*, debitParticular.particular_name as payment, creditParticular.particular_name, entrytypes.type,account_types.code,  account_types.name as module, staffs.staff_name, staffs.account_type, staffs.signature, branches.branch_name')
                ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                ->join('branches', 'branches.id = entries.branch_id', 'left')
                ->find($id);
            # set client signature to null
            $entryRow['sign'] = '';
        }
        switch (strtolower($receipt)) {
            case '1003': // loans
                $condition = ['entries.disbursement_id' => $entryRow['disbursement_id'], 'entries.status' => 'credit', 'entries.created_at <=' => $entryRow['created_at']];
                break;
            case '3003': // saving
                $condition = ['entries.client_id' => $entryRow['client_id'], 'entries.account_typeId' => $entryRow['account_typeId'], 'entries.created_at <=' => $entryRow['created_at']];
                break;
            case '4001': // applications
                $condition = ['entries.application_id' => $entryRow['application_id'], 'entries.account_typeId' => $entryRow['account_typeId'], 'entries.created_at <=' => $entryRow['created_at']];
                break;
            default:
                $condition = ['entries.created_at <=' => $entryRow['created_at']];
                break;
        }
        $debitBal = $creditBal = 0;
        $transactions = $this->entry->where($condition)->findAll();
        if (count($transactions) > 0) {
            // $acc_balance;
            foreach ($transactions as $row) {
                if (strtolower($row['status']) == 'debit') {
                    $debitBal += $row['amount'];
                } else {
                    $creditBal += $row['amount'];
                }
            }
        }
        switch (strtolower($receipt)) {
            case '1003':
                $disbursement =  $this->loanDisbursementRow($entry['disbursement_id']);
                $balance = ($disbursement['actual_repayment'] - abs($debitBal - $creditBal));
                break;
            case '3003':
                $balance = abs($debitBal - $creditBal);
                break;
            case '4001':
                $applicationInfo = $this->loanApplicationRow($entryRow['application_id']);
                $particularInfo = $this->particularDataRow($entryRow['particular_id']);
                if (strtolower($particularInfo['charge_method']) == 'amount') {
                    $charge = ($particularInfo['charge']);
                } else {
                    $charge = ($particularInfo['charge'] / 100) * $applicationInfo['principal'];
                }
                $balance = ($charge - abs($debitBal - $creditBal));
                break;
            default:
                $balance = abs($debitBal - $creditBal);
                break;
        }
        return view('admin/transactions/receipt', [
            'title'         => $entryRow['module'] . ' Transaction Receipt',
            'receipt'      => $receipt,
            'settings'      => $this->settingsRow,
            'entry'      => $entryRow,
            'balance'      => $balance,
        ]);
        // $html = view('admin/transactions/receipt', $data);
        // $this->dompdf->loadHtml($html);
        // $this->dompdf->render();
        // $this->dompdf->stream('receipt.pdf', [ 'Attachment' => false ]);
    }
    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $entry = $this->entry->find($id);
        if (!$entry) {
            return $this->respond([
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requested ' . $this->title . ' resource could not be found!'
            ]);
        }

        // Start the base query and always include shared joins and selects
        $query = $this->entry
            ->select('entries.*, debitParticular.particular_name as payment, creditParticular.particular_name, entrytypes.type, account_types.code, account_types.name as module, staffs.staff_name, staffs.account_type, staffs.signature, branches.branch_name')
            ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
            ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
            ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->join('staffs', 'staffs.id = entries.staff_id', 'left')
            ->join('branches', 'branches.id = entries.branch_id', 'left');

        // Condition-based joins and additional selects
        if ($entry['client_id'] && !$entry['application_id'] && !$entry['disbursement_id']) {
            $query->select('clients.name, clients.account_no')
                ->join('clients', 'clients.id = entries.client_id', 'left');
        } elseif ($entry['client_id'] && $entry['application_id']) {
            $query->select('clients.name, clients.account_no, loanapplications.application_code, entries.entry_menu, loanproducts.product_name, loanproducts.product_code')
                ->join('clients', 'clients.id = entries.client_id', 'left')
                ->join('loanapplications', 'loanapplications.id = entries.application_id', 'left')
                ->join('loanproducts', 'loanproducts.id = loanapplications.product_id', 'left');
        } elseif ($entry['client_id'] && $entry['disbursement_id']) {
            $query->select('clients.name, clients.account_no, disbursements.disbursement_code, disbursements.cycle, disbursements.class, loanproducts.product_name, loanproducts.product_code')
                ->join('clients', 'clients.id = entries.client_id', 'left')
                ->join('disbursements', 'disbursements.id = entries.disbursement_id', 'left')
                ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left');
        } elseif ($entry['product_id']) {
            $query->select('products.product_name as savings_product')
                ->join('products', 'products.id = entries.product_id', 'left');
        }

        // Execute the query
        $data = $query->find($id);

        return $this->respond($data);
    }

    public function showOld($id = null)
    {
        $entry = $this->entry->find($id);
        if ($entry['client_id'] != null && ($entry['application_id'] == null) && ($entry['disbursement_id'] == null)) {
            // join to clients table if client_id is not empty
            $data = $this->entry
                ->select('entries.*, debitParticular.particular_name as payment, creditParticular.particular_name, entrytypes.type,account_types.code,  account_types.name as module, staffs.staff_name, staffs.account_type, staffs.signature, branches.branch_name, clients.name, clients.account_no')
                ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                ->join('branches', 'branches.id = entries.branch_id', 'left')
                ->join('clients', 'clients.id = entries.client_id', 'left')
                ->find($id);
        } else if (($entry['client_id'] != null) && ($entry['application_id'] != null)) {
            // join to clients n loanapplications tables if client_id && application_id are not empty
            $data = $this->entry
                ->select('entries.*, debitParticular.particular_name as payment, creditParticular.particular_name, entrytypes.type,account_types.code,  account_types.name as module, staffs.staff_name, staffs.account_type, staffs.signature, branches.branch_name, clients.name, clients.account_no, loanapplications.application_code, entries.entry_menu, loanproducts.product_name')
                ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                ->join('branches', 'branches.id = entries.branch_id', 'left')
                ->join('clients', 'clients.id = entries.client_id', 'left')
                ->join('loanapplications', 'loanapplications.id = entries.application_id', 'left')
                ->join('loanproducts', 'loanproducts.id = loanapplications.product_id', 'left')
                ->find($id);
        } else if ($entry['client_id'] != null && $entry['disbursement_id'] != null) {
            // join to clients n disbursements tables if client_id && disbursement_id are not empty
            $data = $this->entry
                ->select('entries.*, debitParticular.particular_name as payment, creditParticular.particular_name,entrytypes.type,account_types.code,  account_types.name as module, staffs.staff_name, staffs.account_type, staffs.signature, branches.branch_name, clients.name, clients.account_no, disbursements.disbursement_code, disbursements.cycle, disbursements.cycle, disbursements.class, loanproducts.product_name')
                ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                ->join('branches', 'branches.id = entries.branch_id', 'left')
                ->join('clients', 'clients.id = entries.client_id', 'left')
                ->join('disbursements', 'disbursements.id = entries.disbursement_id', 'left')
                ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')
                ->find($id);
        } else {
            // join to staffs table
            $data = $this->entry
                ->select('entries.*, debitParticular.particular_name as payment, creditParticular.particular_name, entrytypes.type,account_types.code,  account_types.name as module, staffs.staff_name, staffs.account_type, staffs.signature, branches.branch_name')
                ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
                ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
                ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                ->join('branches', 'branches.id = entries.branch_id', 'left')
                ->find($id);
        }
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requested ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
    }
    // get transaction types by accountType or part or both
    public function account_entry_types($account_typeId)
    {
        $menu = $this->request->getVar('transaction_menu');
        /**
         * check the entry_menu is provided from the view, 
         * load types with the same entry_menu
         * if entry_menu is notset, get all type with the same account_type
         */

        if ($menu == "repayments") {
            $where = ['entrytypes.account_typeId' => $account_typeId, 'entrytypes.part' => "credit", 'entrytypes.status' => 'Active'];
        } else {
            $where = ['entrytypes.account_typeId' => $account_typeId, 'entrytypes.status' => 'Active'];
        }

        $data = $this->entryType->select('entrytypes.*,')
            ->join('account_types', 'account_types.id = entrytypes.account_typeId', 'left')
            ->where($where)->orderBy('entrytypes.part', 'desc')->findAll();
        return $this->respond($data);
    }
    // get transaction type data 
    public function entry_type_info($entry_typeId = null)
    {
        $data = $this->entryType->find($entry_typeId);
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'No data found!',
            ];
            return $this->respond($response);
            exit;
        }
    }
    // get transactions by entry menu
    public function entryMenu_transactions($entry_menu)
    {
        $data = $this->entry->where(['entry_menu' => $entry_menu])->findAll();
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requsted ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    // find application particular transactions
    public function applicant_particularPayments($applicant_id, $particular_id)
    {
        $data = $this->entry->select('date, payment_id, particular_id, client_id, application_id, entry_menu, ref_id, entry_typeId, account_typeId, amount, status')->where(['application_id' => $applicant_id, 'particular_id' => $particular_id])->findAll();
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requsted ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
    }
    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $this->menu = trim($this->request->getVar('menu'));
        $this->title = trim(ucwords($this->request->getVar('title')));
        $this->menuItem['menu'] = $this->menu;
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $mode = trim($this->request->getVar('mode'));
            if ($mode == 'import') {
                return $this->import_transactions();
                exit;
            }

            $this->_validateTransactions('add');
            $account_typeId = trim($this->request->getVar('account_typeId'));
            // $amount = str_replace(',', '', trim($this->request->getVar('amount')));
            $amount = $this->removeCommasFromAmount($this->request->getVar('amount'));
            $entry_menu = trim($this->request->getVar('entry_menu'));
            $refId = $this->settings->generateReference(5);
            /**
             * NOTE: The following are the guide lines for inserting entries to the db & Double Entry
             * 1. particular_id is the ID of the primary particular of the entry/transaction
             * 2. payment_id is the ID of the secondary particular of the entry/transaction
             * 3. status refers to the status of the transaction to the primary particular of the entry/transaction
             * 4. balance refers to the balance of the primary particular as of this new entry
             */


            switch (strtolower($entry_menu)) {
                case 'transfer':
                    return $this->add_transferTransaction($refId, $amount, $entry_menu);
                    break;
                case 'expense':
                    return $this->add_expenseTransaction($refId, $amount, $entry_menu);
                    break;
                case 'investment':
                    return $this->add_investmentTransaction($refId, $amount, $entry_menu);
                    break;
                default:
                    // get account type information
                    $account_typeInfo = $this->accountType->find($account_typeId);
                    if ($account_typeInfo) {
                        // switch functionality based on account_type
                        switch ($account_typeId) {
                            case 3: # Loan Portfolio
                                return $this->add_disbursementTransaction($refId, $account_typeInfo, $amount);
                                break;
                            case 8: # Shares
                                return $this->add_sharesTransaction($refId, $account_typeInfo, $amount);
                                break;
                            case 12: // switch to savings transactions logic
                                return $this->add_savingsTransaction($refId, $account_typeInfo, $amount);
                                exit;
                                break;
                            case 18: // switch to application payment transactions logic
                                return $this->add_applicationTransaction($refId, $account_typeInfo, $amount);
                                exit;
                                break;
                            case  24: // switch to membership payment transaction logic
                                return $this->add_membershipTransaction($refId, $account_typeInfo, $amount);
                                exit;
                                break;
                            default:
                                return $this->respond([
                                    'status'   => 404,
                                    'error'    => 'Invalid Account Type',
                                    'messages' => 'Account Type provided is invalid, try again with a valid Account Type!',
                                ]);
                                break;
                        }
                    } else {
                        $response = [
                            'status'   => 404,
                            'error'    => 'Invalid Account Type',
                            'messages' => 'Account Type provided is invalid, try again with a valid Account Type!',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                    break;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to create ' . $this->title . ' ' . $this->menu . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }
    // functionality for transfer transactions i.e from one particular to another
    private function add_transferTransaction($refId, $amount, $entry_menu)
    {
        // particular loosing
        $crParticular_id = $this->request->getVar('crParticular_id');
        $crParticularRow = $this->particularDataRow($crParticular_id);
        // particular gaining
        $drParticular_id = $this->request->getVar('drParticular_id');
        $drParticularRow = $this->particularDataRow($drParticular_id);
        if (!$crParticularRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Transfer From Particular couldn\'t be found!',
            ];
            return $this->respond($response);
            exit;
        }

        if (!$drParticularRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Transfer To Particular couldn\'t be found!',
            ];
            return $this->respond($response);
            exit;
        }
        // transaction type details
        if (!empty($this->request->getVar('entry_typeId'))) {
            $entry_typeRow = $this->entryType->find($this->request->getVar('entry_typeId'));
        } else {
            $entry_typeRow = $this->entryType->where(['entry_menu' => 'transfer'])->first();
        }
        if (!$entry_typeRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Selected entry type couldn\'t be found!',
            ];
            return $this->respond($response);
            exit;
        }
        $entry_typeId = $entry_typeRow['id'];
        // set transaction status to the opposite of the loosing/credited particular
        if (strtolower($drParticularRow['part']) == 'debit') {
            $status = 'debit';
        } else {
            $status = 'credit';
        }
        // $status = $crParticularRow['part'];
        // $status = $entry_typeRow['part'];

        // calculate entries total amount per entry status & final balance
        $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $crParticular_id, 'status' => $crParticularRow['part']]);
        $drAccounting_balance = (float)($entriesStatusTotals['totalBalance'] + $amount); // calculate balance for primary paticular as of this entry

        /** 
         * perform double entry
         * since status represents the loosing side of the particular
         * increase the gaining particular's credit if it's part is credit else increase its debit ,
         * increase the loosing particular' credit if it's part is debit else increase its debit,
         */
        // credit loosing particular & debit gaining particular
        if (strtolower($status) == 'credit') {
            $drParticularBal = ['credit' => ((float)$crParticularRow['credit'] + $amount)];
            $crParticularBal = ['debit' => ((float)$drParticularRow['debit'] + $amount)];
        }
        // debit loosing particular  & credit gaining particular
        if (strtolower($status) == 'debit') {
            $drParticularBal = ['debit' => ((float)$crParticularRow['debit'] + $amount)];
            $crParticularBal = ['credit' => ((float)$drParticularRow['credit'] + $amount)];
        }

        if (!empty($this->request->getVar('date'))) {
            $date = trim($this->request->getVar('date'));
        } else {
            $date = date('Y-m-d');
        }
        // transaction\entry data
        $transactionData = [
            'date' => $date,
            'particular_id' => $drParticular_id,
            'payment_id' => $crParticular_id,
            'branch_id' => $this->userRow['branch_id'],
            'staff_id' => $this->userRow['staff_id'],
            'entry_menu' => $entry_menu,
            'account_typeId' => $drParticularRow['account_typeId'],
            'entry_typeId' => $entry_typeId,
            'ref_id' => strtolower(substr($entry_typeRow['type'], 0, 3)) . '-' . date('ym') . '-' . $refId,
            'amount' => $amount,
            'status' => $status,
            'balance' => $drAccounting_balance,
            'entry_details' => trim($this->request->getVar('entry_details')),
            'remarks' => trim($this->request->getVar('remarks')),
        ];
        // save transaction
        $saveTransaction = $this->entry->insert($transactionData);
        if ($saveTransaction) {
            // update accounting particulars balances
            $crParticular_idBal = $this->particular->update($crParticular_id, $crParticularBal);
            $drParticular_idBal = $this->particular->update($drParticular_id, $drParticularBal);
            if ($crParticular_idBal && $drParticular_idBal) {
                // add transaction into the activity log
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'create',
                    'description' => ucfirst('New ' . $entry_menu . ' Transaction, Ref ID ' . $refId),
                    'module' => $this->menu,
                    'referrer_id' => $refId,
                ];
                $activity = $this->insertActivityLog($activityData);
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => ucfirst($this->title) . ' Transaction created successfully.'
                    ];
                    return $this->respond($response);
                    exit;
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => ucfirst($this->title) . ' Transaction created successfully. loggingFailed'
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status'   => 500,
                    'error'    => 'Accounting Error',
                    'messages' => 'Implementing Double Entry failed!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 500,
                'error'    => 'Transaction Failed',
                'messages' => 'An Error occurred while creating Transaction, Try again!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    // functionality for transfer transactions i.e from one particular to another
    private function add_investmentTransaction($refId, $amount, $entry_menu)
    {
        // investment particular details
        $crParticular_id = $this->request->getVar('crParticular_id');
        $crParticularRow = $this->particularDataRow($crParticular_id);
        // investment mode details
        $drParticular_id = $this->request->getVar('drParticular_id');
        $drParticularRow = $this->particularDataRow($drParticular_id);
        if ($crParticularRow && $drParticularRow) {
            // transaction type details
            if (!empty($this->request->getVar('entry_typeId'))) {
                $entry_typeRow = $this->entryType->find($this->request->getVar('entry_typeId'));
            } else {
                $entry_typeRow = $this->entryType->where(['entry_menu' => 'transfer'])->first();
            }
            if ($entry_typeRow) {
                $entry_typeId = $entry_typeRow['id'];
                // set transaction status as the part for the investment particular
                $status = $crParticularRow['part'];

                // calculate entries total amount per entry status & final balance
                $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $crParticular_id, 'status' => $crParticularRow['part']]);
                $crAccounting_balance = (float)($entriesStatusTotals['totalBalance'] + $amount); // calculate balance for primary paticular as of this entry

                /** 
                 * perform double entry
                 * since investments are in form of eqity\capital and part is credit,
                 * increase the investment particular's credit if it's part is credit else increase its debit ,
                 * increase the investment mode particular' credit if it's part is debit else increase its debit,
                 */
                // credit investment particular & debit investment mode particular
                if (strtolower($status) == 'credit') {
                    //  increase the gaining particular's credit
                    $crParticularBal = ['credit' => ((float)$crParticularRow['credit'] + $amount)];
                    $drParticularBal = ['debit' => ((float)$drParticularRow['debit'] + $amount)];
                }
                // debit investment particular & credit investment mode particular
                if (strtolower($status) == 'debit') {
                    $crParticularBal = ['debit' => ((float)$crParticularRow['debit'] + $amount)];
                    $drParticularBal = ['credit' => ((float)$drParticularRow['credit'] + $amount)];
                }

                if (!empty($this->request->getVar('date'))) {
                    $date = trim($this->request->getVar('date'));
                } else {
                    $date = date('Y-m-d');
                }
                // transaction\entry data
                $transactionData = [
                    'date' => $date,
                    'particular_id' => $crParticular_id,
                    'payment_id' => $drParticular_id,
                    'branch_id' => $this->userRow['branch_id'],
                    'staff_id' => $this->userRow['staff_id'],
                    'entry_menu' => $entry_menu,
                    'account_typeId' => $crParticularRow['account_typeId'],
                    'entry_typeId' => $entry_typeId,
                    'ref_id' => strtolower(substr($entry_typeRow['type'], 0, 3)) . '-' . date('ym') . '-' . $refId,
                    'amount' => $amount,
                    'status' => $status,
                    'balance' => $crAccounting_balance,
                    'entry_details' => trim($this->request->getVar('entry_details')),
                    'remarks' => trim($this->request->getVar('remarks')),
                ];
                // save transaction
                $saveTransaction = $this->entry->insert($transactionData);
                if ($saveTransaction) {
                    // update accounting particulars balances
                    $crParticular_idBal = $this->particular->update($crParticular_id, $crParticularBal);
                    $drParticular_idBal = $this->particular->update($drParticular_id, $drParticularBal);
                    if ($crParticular_idBal && $drParticular_idBal) {
                        // add transaction into the activity log
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'create',
                            'description' => ucfirst('New ' . $entry_menu . ' Transaction, Ref ID ' . $refId),
                            'module' => $this->menu,
                            'referrer_id' => $refId,
                        ];
                        $activity = $this->insertActivityLog($activityData);
                        $activity = $this->insertActivityLog($activityData);
                        if ($activity) {
                            $response = [
                                'status'   => 200,
                                'error'    => null,
                                'messages' => ucfirst($this->title) . ' Transaction created successfully.'
                            ];
                            return $this->respond($response);
                            exit;
                        } else {
                            $response = [
                                'status'   => 200,
                                'error'    => null,
                                'messages' => ucfirst($this->title) . ' Transaction created successfully. loggingFailed'
                            ];
                            return $this->respond($response);
                            exit;
                        }
                    } else {
                        $response = [
                            'status'   => 500,
                            'error'    => 'Accounting Error',
                            'messages' => 'Implementing Double Entry failed!',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status'   => 500,
                        'error'    => 'Transaction Failed',
                        'messages' => 'An Error occurred while creating Transaction, Try again!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'Selected entry type couldn\'t be found!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Selected transer particular couldn\'t be found!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    // functionality for expense transactions
    private function add_expenseTransaction($refId, $amount, $entry_menu)
    {
        $account_typeId = trim($this->request->getVar('account_typeId'));
        $particular_id = trim($this->request->getVar('particular_id'));
        $particularRow = $this->particularDataRow($particular_id);
        $payment_id = trim($this->request->getVar('payment_id'));
        $paymentRow = $this->particularDataRow($particular_id);
        if (!$particularRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Particular method data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        if (!$paymentRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Payment method data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        if (!empty($this->request->getVar('entry_typeId'))) {
            $entry_typeId = trim($this->request->getVar('entry_typeId'));
            $transaction_typeRow = $this->entryType->find($entry_typeId);
        } else {
            $transaction_typeRow = $this->entryType->where(['entry_menu' => 'expense'])->first();
        }
        if (!$transaction_typeRow) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Transaction Type could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        // set transaction status as the part for the particular expense is made for
        $status = $particularRow['part'];

        // calculate entries total amount per entry status & final balance
        $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $particularRow['part']]);
        $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $amount); // calculate balance for primary paticular as of this entry
        /** 
         * perform double entry
         * since transaction type is debit,
         * increase the gaining particular's debit if it's part is debit else increase its credit ,
         * increase the payment particular's credit since its of asset category,
         */
        if (strtolower($status) == 'debit') {
            // debit  particular
            $particularBal = ['debit' => ((float)$particularRow['debit'] + $amount)];
            // credit payment method[assets]
            $paymentBal = ['credit' => ((float)$paymentRow['credit'] + $amount)];
        }
        if (strtolower($status) == 'credit') {
            // credit  particular
            $particularBal = ['credit' => ((float)$particularRow['credit'] + $amount)];
            // credit payment method[assets]
            $paymentBal = ['credit' => ((float)$paymentRow['credit'] + $amount)];
        }

        if (!empty($this->request->getVar('date'))) {
            $date = trim($this->request->getVar('date'));
        } else {
            $date = date('Y-m-d');
        }
        // transaction\entry data
        $transactionData = [
            'date' => $date,
            'payment_id' => $payment_id,
            'particular_id' => $particular_id,
            'branch_id' => $this->userRow['branch_id'],
            'staff_id' => $this->userRow['staff_id'],
            'entry_menu' => $entry_menu,
            'account_typeId' => $account_typeId,
            'entry_typeId' => trim($this->request->getVar('entry_typeId')),
            'ref_id' => strtolower(substr($transaction_typeRow['type'], 0, 3)) . '-' . date('ym') . '-' . $refId,
            'amount' => $amount,
            'status' => $status,
            'balance' => $accountingBalance,
            'entry_details' => trim($this->request->getVar('entry_details')),
            'remarks' => trim($this->request->getVar('remarks')),
        ];
        // save transaction
        $saveTransaction = $this->entry->insert($transactionData);
        if ($saveTransaction) {
            // update accounting particulars balances
            $particular_idBal = $this->particular->update($particular_id, $particularBal);
            $payment_idBal = $this->particular->update($payment_id, $paymentBal);
            if ($particular_idBal && $payment_idBal) {
                // add transaction into the activity log
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'create',
                    'description' => ucfirst('New ' . $this->title . ' Transaction, Ref ID ' . $refId),
                    'module' => $this->menu,
                    'referrer_id' => $refId,
                ];
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => ucfirst($this->title) . ' Transaction created successfully.'
                    ];
                    return $this->respond($response);
                    exit;
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => ucfirst($this->title) . ' Transaction created successfully. loggingFailed'
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status'   => 500,
                    'error'    => 'Accounting Error',
                    'messages' => 'Implementing Double Entry failed!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 500,
                'error'    => 'Transaction Failed',
                'messages' => 'An Error occurred while creating Transaction, Try again!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    // functionality for creating shares transactions
    private function add_sharesTransaction($refId, $account_typeInfo, $sharesAmount)
    {
        $account_typeId = $account_typeInfo['id'];
        $client_id = trim($this->request->getVar('client_id'));
        $clientInfo = $this->client->find($client_id);
        // check is client still exists
        if (!$clientInfo) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Client Data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        $reg_date = $clientInfo['reg_date'];

        $particular_id = trim($this->request->getVar('particular_id'));

        // get particular data
        $particularRow = $this->particularDataRow($particular_id);
        // check existence of accounting particular
        if (!$particularRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Particular data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }

        $payment_id = trim($this->request->getVar('payment_id'));
        # check payment is though client savings
        $productRow = $this->product->where(['product_code' => $payment_id])->first();
        if ($productRow) {
            $paymentBySavings = true;
        } else {
            $paymentBySavings = false;
        }
        # Check the particular existence that will be used as for payment
        # if payment is thru savings then assign product savings particular id
        if ($paymentBySavings) {
            $paymentRow = $this->checkArrayExistance('particular', [
                'id' => $productRow['savings_particular_id'],
            ]);
            $payment_id = $productRow['savings_particular_id'];
        } else {
            $paymentRow = $this->checkArrayExistance('particular', [
                'id' => $payment_id,
            ]);
        }

        $entry_typeId = trim($this->request->getVar('entry_typeId'));
        // get transaction type data
        $transaction_typeRow = $this->entryType->find($entry_typeId);

        if (!$transaction_typeRow) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Transaction Type could not be found for ' . $account_typeInfo['name'] . '!',
            ];
            return $this->respond($response);
            exit;
        }
        // entry_menu of transaction based on entry type
        $entry_menu = $transaction_typeRow['entry_menu'];
        // status of transaction based on entry type
        $status = $transaction_typeRow['part'];

        // calculate entries total amount per entry status & final balance
        $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $particularRow['part']]);

        /*
        // for shares purchase, push access amount being paid to savings
        if (strtolower($status) == 'credit') {
            $chargeRow = $this->vlookupSharesCharge($particular_id, $reg_date);
            if (!$chargeRow) {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => $this->title . ' charge data could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
            $chargeAmt = $chargeRow['charge'];

            # Check for Excess Payments
            # calculate if amount being used to buy share has a reminder if devided by the unit charge of a share
            # if there is a reminder, push it to client savings as excess payment

            if (($sharesAmount % $chargeAmt) > 0) {
                # calculate perfect shares that can be bought without reminders
                $sharesToBeBought = ((int)($sharesAmount / $chargeAmt) * $chargeAmt);
                $excessAmt = ($sharesAmount - $sharesToBeBought);
                $amount = $sharesToBeBought;
            } else {
                $excessAmt = 0;
                $amount = $sharesAmount;
            }
        } else {
            $amount = $sharesAmount;
        }
        */
        $amount = $sharesAmount;
        $chargeAmt = trim($this->request->getVar('particular_charge'));

        // calculate balance for primary particular as of this entry
        $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $amount);
        /** 
         * since account type is for Equity category,
         * credit particular & debit payment method if its gaining,
         * debit particular & credit payment method if its loosing,
         */
        if (strtolower($status) == 'credit') {
            // credit selected particular
            $sharesParticularBal = ['credit' => ((float)$particularRow['credit'] + $amount)];
            // debit payment method
            $paymentParticularBal = ['debit' => ((float)$paymentRow['debit'] + $amount)];
        }
        if (strtolower($status) == 'debit') {
            // debit selected  particular
            $sharesParticularBal = ['debit' => ((float)$particularRow['debit'] + $amount)];
            // credit selected payment method
            $paymentParticularBal = ['credit' => ((float)$paymentRow['credit'] + $amount)];
        }

        if (!empty($this->request->getVar('date'))) {
            $date = trim($this->request->getVar('date'));
        } else {
            $date = date('Y-m-d');
        }
        // transaction\entry data
        $transactionData = [
            'date' => $date,
            'payment_id' => $payment_id,
            'particular_id' => $particular_id,
            'branch_id' => $this->userRow['branch_id'],
            'staff_id' => $this->userRow['staff_id'],
            'client_id' => $client_id,
            'entry_menu' => $entry_menu,
            'entry_typeId' => $entry_typeId,
            'account_typeId' => $account_typeId,
            'ref_id' => strtolower(substr($transaction_typeRow['type'], 0, 3)) . '-' . date('ym') . '-' . $refId,
            'amount' => $amount,
            'status' => $status,
            'balance' => $accountingBalance,
            'contact' => trim($this->request->getVar('contact_full')),
            'entry_details' => trim($this->request->getVar('entry_details')),
            'remarks' => trim($this->request->getVar('remarks')),
        ];
        # add savings product id to transaction data
        $transactionData['product_id'] = ($paymentBySavings) ? $productRow['id'] : null;

        // save transaction
        $saveTransaction = $this->entry->insert($transactionData);
        if ($saveTransaction) {
            /*# push over payment to client  savings
                if ((strtolower($status) == 'credit') && ($excessAmt > 0)) {
                    $this->push_excessAmount_to_client_savings(['client_id' => $client_id, 'amount' => $excessAmt, 'payment_id' => $payment_id, 'from' => $particularRow['particular_name']]);
                }*/

            // update accounting particulars balances
            $particular_idBal = $this->particular->update($particular_id, $sharesParticularBal);
            $payment_idBal = $this->particular->update($payment_id, $paymentParticularBal);
            if ($particular_idBal && $payment_idBal) {
                // add transaction into the activity log
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'create',
                    'description' => ucfirst('New ' . $transaction_typeRow['type'] . ' Transaction for ' . $particularRow['particular_name'] . ' Ref ID ' . $refId),
                    'module' => $this->menu,
                    'referrer_id' => $refId,
                ];
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $txt = '' .
                        # check whether the internet connection exist
                        $checkInternet = $this->settings->checkNetworkConnection();
                    if ($checkInternet) {
                        $mailInfo = $clientInfo;
                        $mailInfo['branch_name'] = $this->userRow['branch_name'];
                        $mailInfo['amount'] = $amount;
                        $mailInfo['charge'] = $chargeAmt;
                        $mailInfo['ref_id'] = $refId;
                        $mailInfo['date'] = date('d-m-Y H:i:s');
                        $mailInfo['entry_details'] = trim($this->request->getVar('entry_details'));
                        $mailInfo['account_typeID'] = $account_typeId;
                        $mailInfo['type'] = $transaction_typeRow['type'];
                        $mailInfo['particular_name'] = $particularRow['particular_name'];
                        $mailInfo['payment_mode'] = $paymentRow['particular_name'];
                        $message = $mailInfo;
                        # check the email existence and email notify is enabled
                        if (!empty($mailInfo['email']) && $this->settingsRow['email']) {
                            $subject = 'New ' . $particularRow['particular_name'] . ' Transaction';
                            $message = $message;
                            $token = 'transaction';
                            $this->settings->sendMail($message, $subject, $token);
                            $txt .= 'Email Sent';
                        }
                        # check the mobile existence and sms notify is enabled
                        if (!empty($mailInfo['mobile']) && $this->settingsRow['sms']) {
                            # send sms
                            $sms = $this->sendSMS([
                                'mobile' => trim($mailInfo['mobile']),
                                'text' => 'A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . number_format($amount, 2) . ' for ' . strtolower($particularRow['particular_name']) . ' has been processed by ' . strtolower($this->settingsRow['business_name']) . ' on ' . $date . ' . ID: ' . $refId . ' ~ ' . strtoupper($this->settingsRow["system_abbr"])
                            ]);
                            $txt .= ' SMS Sent';
                        }
                    }
                    $response = [
                        'status' => 200,
                        'error' => null,
                        'messages' => $this->title . ' transaction created successfully.' . $txt,
                    ];
                    return $this->respond($response);
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => $this->title . ' transaction created successfully. loggingFailed'
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status'   => 500,
                    'error'    => 'Accounting Error',
                    'messages' => 'Implementing Double Entry failed, Transaction saved!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 500,
                'error'    => 'Transaction Failed',
                'messages' => 'An Error occurred while creating Transaction, Try again!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    private function add_savingsTransaction($ref_id, $account_typeInfo, $amount)
    {
        $account_typeId = $account_typeInfo['id'];
        $client_id = trim($this->request->getVar('client_id'));
        # Check the client existence
        $clientInfo = $this->checkArrayExistance('client', [
            'id' => $client_id
        ]);

        $particular_id = trim($this->request->getVar('particular_id'));
        # Check the particular existence
        $particularRow = $this->checkArrayExistance('particular', [
            'id' => $particular_id,
        ]);

        $payment_id = trim($this->request->getVar('payment_id'));
        # Check the particular existence that will be used as for payment
        $paymentRow = $this->checkArrayExistance('particular', [
            'id' => $payment_id,
        ]);

        $entry_typeId = trim($this->request->getVar('entry_typeId'));
        # Check the transaction existence
        $transaction_typeRow = $this->checkArrayExistance('entryType', [
            'id' => $entry_typeId
        ]);
        # Get the entry menu
        $entry_menu = $transaction_typeRow['entry_menu'];
        $type = $transaction_typeRow['type'];
        $status = $transaction_typeRow['part'];
        $amountToBeSaved =  $amount;

        # Calculate entries total amount per entry status & final balance
        $entriesStatusTotals = $this->entry->calculateTotalBalance([
            'module' => 'particular',
            'module_id' => $particular_id,
            'status' => $particularRow['part']
        ]);

        $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $amountToBeSaved);

        $product_id = (!empty($this->request->getVar('product_id'))) ? trim($this->request->getVar('product_id')) : null;
        # Validate selected client savings product balance
        if ($product_id) {
            $productRow = $this->product->find($product_id);
            if (!$productRow) {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'Product Data could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
        }
        // client's savings products
        $savingsProducts = (($clientInfo['savingsProducts']) ? $clientInfo['savingsProducts'] : null);

        # Compute the account balance
        $accountBalance = $this->checkArrayExistance('accountBalance', [
            'part' => strtolower($status),
            'amount' => $amountToBeSaved,
            'accountBalance' => $clientInfo['account_balance'],
            'debitParticularBalance' => $particularRow['debit'],
            'creditParticularBalance' => $particularRow['credit'],
            'debitPaymentBalance' => $paymentRow['debit'],
            'creditPaymentBalance' => $paymentRow['credit']
        ]);

        # Check the transaction date existence
        if (!empty($this->request->getVar('date'))) {
            $date = trim($this->request->getVar('date'));
        } else {
            $date = date('Y-m-d');
        }
        # Set the transaction information to save
        $transactionData = [
            'date' => $date,
            'payment_id' => $payment_id,
            'particular_id' => $particular_id,
            'branch_id' => $this->userRow['branch_id'],
            'staff_id' => $this->userRow['staff_id'],
            'client_id' => $client_id,
            'product_id' => ($product_id) ? $product_id : null,
            'entry_menu' => $entry_menu,
            'entry_typeId' => $entry_typeId,
            'account_typeId' => $account_typeId,
            'ref_id' => strtolower(substr($transaction_typeRow['type'], 0, 3)) . '-' . date('ym') . '-' . $ref_id,
            'amount' => $amountToBeSaved,
            'status' => $status,
            'balance' => $accountingBalance,
            'contact' => trim($this->request->getVar('contact_full')),
            'entry_details' => trim($this->request->getVar('entry_details')),
            'remarks' => trim($this->request->getVar('remarks')),
        ];

        # check whether the user is allowed to add withdraw
        /*
            if (strtolower($type) == 'withdraw' && $this->userRow['position_id'] == 8) {
                $response = [
                    'status'   => 403,
                    'error'    => 'Access Denied',
                    'messages' => 'You are not authorized to create withdraw savings transactions',
                ];
                return $this->respond($response);
                exit;
            }
            */
        /*
            $mobile = $this->phoneNumberWithCountryCode($this->request->getVar('contact_full'));
            
            # Remove HTML tags and send the sanitized content
            $sanitizedContent = strip_tags(trim($this->request->getVar('entry_details')));
            $limitedContent = $this->egoAPI->limitContent($sanitizedContent);
            
            # $this->entry->insert($transactionData);
            # check the entry menu
            switch (strtolower($type)) {
                case 'deposit':
                    # Yo PAYMENT
                    $apiResponse = $this->yopayment("deposit", [
                        'phone' => $mobile,
                        'amount' => $amount,
                        'reason' => $limitedContent,
                        'transactionReference' => $ref_id,
                    ]);
                    # Update the transaction reference
                    # $transactionData['transaction_reference'] = $apiResponse['TransactionReference'];
                    # IO TECH
                    # $moduleRequest = 'payment';
                    # $statusRequest = 'status';
                    # $reciever = "payer";
                    break;

                case 'withdraw':
                    $moduleRequest = 'disburse';
                    $statusRequest = 'disburseStatus';
                    $reciever = "payee";
                    break;

                default:
                    echo json_encode([
                        'status' => 404,
                        'error' => 'Transaction Failed',
                        'messages' => "Transaction Type is found at the moment"
                    ]);
                    exit;
                    break;
            }
            */
        # Remove HTML tags and send the sanitized content
        /*
            $sanitizedContent = strip_tags(trim($this->request->getVar('entry_details')));
            $limitedContent = $this->ioTecPaySDK->limitContent($sanitizedContent);
            # SDK
            $collection = $this->ioTecPaySDK->iotech($moduleRequest, '', [
                "externalId" => $ref_id,
                $reciever => trim($this->request->getVar('contact_full')),
                "payerNote" => $limitedContent,
                # "payerNote" => "Savings transactions",
                "amount" => $amount,
                "payeeNote" => "Savings transactions",
            ]);
            # Check the transaction status
            $transactionResponse = $this->ioTecPaySDK->iotech($statusRequest, $collection['id']);
            */
        /*
            $startTime = time();
            $maxExecutionTime = 180; // 3 minutes in seconds
    
            while (time() - $startTime < $maxExecutionTime) {
                # Check the transaction status
                $response = $this->ioTecPaySDK->iotech($statusRequest, $collection['id']);
                # Check the response status
                if ($response['status'] == "Success") {
                    break; # Exit the loop if successful
                }
                sleep(10); # Sleep for 10 seconds before calling the function again
            }
            */

        # Save transaction
        $saveTransaction = $this->entry->insert($transactionData);

        # Update accounting particulars balances
        $updateParticularAccount = $this->particular->update($particular_id, [
            'credit' => $accountBalance['creditBalance'],
            'debit' => $accountBalance['debitBalance']
        ]);
        $updatePaymentAccount = $this->particular->update($payment_id, [
            'credit' => $accountBalance['creditBalance'],
            'debit' => $accountBalance['debitBalance']
        ]);

        # check for the membership payment status
        if (strtolower($status) == 'credit') {
            # get the membership transaction status

            $membershipTransaction = $this->membershipTransaction([
                'membershipAmt' => $amount,
                'payment_id' => $particular_id,
                'account_typeId' => 24,
                'client_id' => $client_id,
                'from' => $type,
                'product_id' => ($product_id) ? $product_id : null,
                'parent_id' => $saveTransaction
            ]);
            # get the savings amount to be paid in case the membership deductions are done
            $amountToBePaid = $membershipTransaction['savingsToBePaid'];
            # Check whether the membership deductions are all set
            if ($amountToBePaid <= 0) {
                $response = [
                    'status' => 200,
                    'error' => null,
                    'messages' => $this->title . ' successful. Pending Membership Charges are greated than transaction amount!'
                ];
                return $this->respond($response);
                exit;
            } else {
                $amountToBeSaved = $amountToBePaid;
            }
        }

        # check whether the savings transaction is withdraw and the deduct charges
        if (strtolower($status) == 'debit') {
            # get withdraw charges particular
            $withdrawChargeParticular = ($productRow) ? $this->particular->find($productRow['withdrawCharges_particular_id']) : $this->particular->where(['account_typeId' => 20])->first();

            # deduct the savings withdrawal charges
            $this->savingsWithdrawalChargesTransaction([
                'ref_id' => strtolower(substr($transaction_typeRow['type'], 0, 3)) . '-' . date('ym') . '-' . $ref_id,
                'amount' => $amount,
                'payment_id' => $particular_id,
                'product_id' => $product_id,
                'account_typeId' => 20,
                'client_id' => $client_id,
                'from' => $type,
                'parent_id' => $saveTransaction,
                'withdrawChargeParticular' => $withdrawChargeParticular,
            ]);
        }

        # Compute & Update client account balance and balance for selected savings product
        $newAccountBalance = $this->entry->sum_client_amountPaid(
            $client_id,
            $particular_id,
            $status,
            'savings'
        );
        if ($savingsProducts) {
            // Re-index savingsProducts by product_id without looping
            $productsById = array_column($savingsProducts, null, 'product_id');
            // Retrieve the entire product array for the selected product code
            $selectedProduct = &$productsById[$product_id] ?? null;
            // Update the product balance (or other properties as needed)
            $selectedProduct['product_balance'] = $this->entry->sum_client_amountPaid($client_id, $particular_id, $status, 'savings-product', $selectedProduct['product_id']);
            // Convert back to an indexed array Reflecting the update
            $savingsProducts = array_values($productsById);

            $updateClientAccount = $this->client->update($client_id, [
                'account_balance' => $newAccountBalance,
                'savings_products' => json_encode($savingsProducts)
            ]);
        } else {
            $updateClientAccount = $this->client->update($client_id, [
                'account_balance' => $newAccountBalance
            ]);
        }

        # Add transaction information into the activity log
        $activity = $this->insertActivityLog([
            'user_id' => $this->userRow['id'],
            'action' => 'create',
            'description' => ucfirst('New ' . $entry_menu . ' ' . $transaction_typeRow['type'] . ' ' .  $this->title . ' for ' . $particularRow['particular_name'] . ' Ref ID ' . $ref_id),
            'module' => $this->menu,
            'referrer_id' => $ref_id,
        ]);
        # Check the internet connection
        $checkInternet = $this->settings->checkNetworkConnection();
        if ($checkInternet) {
            $mailInfo = $clientInfo;
            $mailInfo['branch_name'] = $this->userRow['branch_name'];
            $mailInfo['amount'] = $amount;
            $mailInfo['balance'] = $accountBalance['accountBalance'];
            $mailInfo['ref_id'] = $ref_id;
            $mailInfo['date'] = date('d-m-Y H:i:s');
            $mailInfo['entry_details'] = trim($this->request->getVar('entry_details'));
            $mailInfo['account_typeID'] = $account_typeId;
            $mailInfo['type'] = $transaction_typeRow['type'];
            $mailInfo['particular_name'] = $particularRow['particular_name'];
            $mailInfo['payment_mode'] = $paymentRow['particular_name'];
            $message = $mailInfo;
            # Check the client email existence
            if (!empty($mailInfo['email']) && $this->settingsRow['email']) {
                $subject = 'New ' . $particularRow['particular_name'] . ' Transaction';
                $message = $message;
                $token = 'transaction';
                # Send transaction notification email to client
                $this->settings->sendMail($message, $subject, $token);
            }

            # Check the client mobile existence
            if (!empty($mailInfo['mobile']) && $this->settingsRow['sms']) {
                $lastName = $this->splitName($mailInfo['name']);
                # check whether transaction type is for withdraw
                if (strtolower($type) == 'withdraw') {
                    $text = 'Dear ' . $lastName . ', A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . number_format($amount, 2) . ' via ' . $paymentRow['particular_name'] . ' has been withdrawn from your savings account' . ' on ' . $date . '. ID: ' . $ref_id . ' ~ ' . strtoupper($this->settingsRow["system_abbr"]);
                } else {
                    # set sms text message fo deposit
                    $text1 = 'A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . $amount . ' via ' . $paymentRow['particular_name'] . ' has been received by ' . strtolower($this->settingsRow['business_name']) . ' on ' . $date . '. ID: ' . $ref_id . ' New balance: ' . $accountBalance['accountBalance'] . ' ~ ' . strtoupper($this->settingsRow["system_abbr"]);
                    $text = 'Dear ' . $lastName . ', Your savings of ' . $this->settingsRow["currency"] . ' ' . number_format($amount, 2) . ' has been received on ' . $date . '. ID: ' . $ref_id . '. Thank you for saving with ' . strtoupper($this->settingsRow["system_abbr"]);
                }


                # send sms
                $sms = $this->sendSMS([
                    'mobile' => trim($mailInfo['mobile']),
                    'text' => $text
                ]);
            }

            $response = [
                'status' => 200,
                'error' => null,
                'messages' => $this->title . ' transaction created successfully.'
            ];
            return $this->respond($response);
            exit;
        } else {
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => $this->title . ' transaction created successfully.',
            ];
            return $this->respond($response);
        }
    }

    // functionarity for creating disbursements transactions
    private function add_disbursementTransaction($ref_id, $account_typeInfo, $amount)
    {
        $principalInstallment = $interestInstallment = 0;
        $interestCollected = $principalCollected = $totalCollected = 0;
        $account_typeId = $account_typeInfo['id'];
        $client_id = trim($this->request->getVar('client_id'));
        $clientInfo = $this->client->find($client_id);
        if (!$clientInfo) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Client could not be found!',
            ];
            return $this->respond($response);
            exit;
        }

        $disbursement_id = trim($this->request->getVar('disbursement_id'));
        $disbursementInfo = $this->loanDisbursementRow($disbursement_id);
        if (!$disbursementInfo) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Disbursement could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        $actualInstallment = $disbursementInfo['actual_installment'];
        $product_id = $disbursementInfo['product_id'];

        $payment_id = trim($this->request->getVar('payment_id'));
        # check payment is though client savings
        $productRow = $this->product->where(['product_code' => $payment_id])->first();
        if ($productRow) {
            $paymentBySavings = true;
        } else {
            $paymentBySavings = false;
        }
        # Check the particular existence that will be used as for payment
        # if payment is thru savings then assign product savings particular id
        if ($paymentBySavings) {
            $paymentRow = $this->checkArrayExistance('particular', [
                'id' => $productRow['savings_particular_id'],
            ]);
            $payment_id = $productRow['savings_particular_id'];
        } else {
            $paymentRow = $this->checkArrayExistance('particular', [
                'id' => $payment_id,
            ]);
        }

        $loanParticular_id = trim($this->request->getVar('particular_id'));
        // get loan particular data & check for its existance
        $loanParticularRow = $this->checkArrayExistance('particular', [
            'id' => $loanParticular_id
        ]);

        $interest_account_typeId = 19; // revenue from loans id
        $interestAccountType = $this->accountType->where(['status' => 'Active'])
            ->find($interest_account_typeId);
        if (!$interestAccountType) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Account Type: Revenue from Loan Repayments is not found!',
            ];
            return $this->respond($response);
            exit;
        }

        $interestParticular = (isset($disbursementInfo['interest_particular_id'])) ? $this->particular->find($disbursementInfo['interest_particular_id']) : $this->particular->where([
            'account_typeId' => $interest_account_typeId
        ])->first();

        if (!$interestParticular) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'No ' . $interestAccountType['name'] . ' Particular found!',
            ];
            return $this->respond($response);
            exit;
        }
        $interest_particularId = $interestParticular['id']; // interest on loans id
        // get interest particular data
        $interestParticularRow = $this->particularDataRow($interest_particularId);

        // get transaction type data
        $entry_typeId = trim($this->request->getVar('entry_typeId'));
        $transaction_typeRow = $this->entryType->find($entry_typeId);
        if (!$transaction_typeRow) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Transaction Type could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        // entry_menu of transaction based on entry type
        $entry_menu = $transaction_typeRow['entry_menu'];
        // status of transaction based on entry type
        $status = $transaction_typeRow['part'];

        /** 
         * update loan balance and perform double entry
         * since account type is for assets category[Gross Loans - Principal],
         * debit particular & credit payment method & interest on loan[revenue] if its gaining,
         * credit particular & debit payment method & interest on loan[revenue] if its loosing,
         */
        // compute payment towards principal and interest respectively
        $interestInstallment = $interval = $payouts = 0;

        $frequency = $disbursementInfo['repayment_frequency'];
        $rate = $disbursementInfo['interest_rate'];
        $interestPeriod = $disbursementInfo['interest_period'];
        $rateType = $disbursementInfo['interest_type'];
        $period = $disbursementInfo['repayment_period'];

        # convert the loan internal per year
        $loanInterval = $this->settings->generateIntervals($frequency);
        # check the loan interest period
        if (strtolower($interestPeriod) == "year") {
            $interval = $loanInterval['interval'];
            $payouts = (12 / $interval);
        } else {
            $interval = $payouts = 1;
        }

        // calculate interest installment
        if (strtolower($rateType) == 'reducing') {
            $interestInstallment = round(($disbursementInfo['principal_balance']) * ($rate / 100)) / ($payouts);
        }
        if (strtolower($rateType) == 'flat') {
            $interestInstallment = round(($disbursementInfo['principal']) * ($rate / 100)) / ($payouts);
        }
        # set parameters to calculate the loan repayment amount
        $loan = [
            'repayment' => $amount,
            'monthlyInstallment' => $actualInstallment,
            'monthlyInterest' => $interestInstallment,
            'totalInterestCollected' => $disbursementInfo['interest_collected'],
            'totalInterest' => $disbursementInfo['actual_interest'],
            'principal' => $disbursementInfo['principal_balance'],
            'interestBalance' => $disbursementInfo['interest_balance'],
            'originalPrincipal' => $disbursementInfo['principal'],
            'totalPrincipalPaid' => $disbursementInfo['principal_collected']
        ];
        # get the loan repayment calculations
        $data = $this->disbursement->calculateLoanRepayment($loan);
        # get the principal repayment
        $principalRepayment = $data['principalRepayment'];
        # get the interest repayment
        $interestRepayment = $data['interestRepayment'];
        # get the overpayment balance
        $balanceOnRepaymentAmt = $data['overpaymentBalance'];
        # get the principal Balance
        $principalBalance = $data['principalBalance'];
        # get the interest Balance
        $interestBalance = $data['interestPayableBalance'];
        # get the total loan outstanding balance
        $totalLoanBalance = $data['totalLoanBalance'];
        # get the total interest collected
        $interestCollected = $data['interestCollected'];
        # get the total principal collected
        $principalCollected = $data['principalCollected'];
        # get the total principal and interest collected
        $totalCollected = $data['totalLoanCollection'];
        # $totalCollected = ($disbursementInfo['total_collected'] + $amount);

        # return $this->respond($data);
        // calculate entries total amount per entry status & final balance
        $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $loanParticular_id, 'status' => $loanParticularRow['part']]);
        $principalAccountingBalance = (float)($entriesStatusTotals['totalBalance'] + $amount); // calculate balance for primary particular as of this entry
        $entriesStatusTotals_interest = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $interest_particularId, 'status' => $interestParticularRow['part']]);
        $interestAccountingBalance = (float)($entriesStatusTotals_interest['totalBalance'] + $amount); # Implement double entry
        if (strtolower($status) == 'credit') {
            // credit\reduce Gross Loans - Principal particular[assets]
            $loanParticularBal = ($data['principalRepayment'] > 0) ? ['credit' => ((float)$loanParticularRow['credit'] + $principalRepayment)] : null;
            // credit\add Interest on Loans particular[revenue]
            $interestParticularBal = ($data['interestRepayment'] > 0) ? ['credit' => ((float)$interestParticularRow['credit'] + $interestRepayment)] : null;
            // debit selected payment method[assets]
            $paymentParticularBal = ['debit' => ((float)$paymentRow['debit'] + $amount)];
        }
        if (strtolower($status) == 'debit') {
            // debit/add Gross Loans - Principal particular[assets]
            $loanParticularBal = ($data['principalRepayment'] > 0) ? ['debit' => ((float)$loanParticularRow['debit'] + $amount)] : null;
            // debit\reduce Interest on Loans particular[revenue]
            $interestParticularBal = ($data['interestRepayment'] > 0) ? ['debit' => ((float)$interestParticularRow['debit'] + $amount)] : null;
            // credit selected payment method[assets]
            $paymentParticularBal = ['credit' => ((float)$paymentRow['credit'] + $amount)];
        }

        if (!empty($this->request->getVar('date'))) {
            $date = trim($this->request->getVar('date'));
        } else {
            $date = date('Y-m-d');
        }
        // transaction\entry data
        $transactionData = [];
        # Principal installment transaction data if amount covers Principal repayment 
        if ($data['principalRepayment'] > 0) {
            $transactionData[] = [
                'date' => $date,
                'payment_id' => $payment_id,
                'particular_id' => $loanParticular_id,
                'branch_id' => $this->userRow['branch_id'],
                'staff_id' => $this->userRow['staff_id'],
                'client_id' => $client_id,
                # add savings product id to transaction data
                'product_id' => ($paymentBySavings) ? $productRow['id'] : null,
                'application_id' => isset($disbursementInfo['application_id']) ? $disbursementInfo['application_id'] : null,
                'disbursement_id' => $disbursement_id,
                'entry_menu' => $entry_menu,
                'entry_typeId' => $entry_typeId,
                'account_typeId' => $account_typeId,
                'ref_id' => strtolower(substr($transaction_typeRow['type'], 0, 3)) . '-' . date('ym') . '-' . $ref_id,
                'amount' => $principalRepayment,
                'status' => $status,
                'balance' => $principalAccountingBalance,
                'contact' => trim($this->request->getVar('contact_full')),
                'entry_details' => trim($this->request->getVar('entry_details')),
                'remarks' => trim($this->request->getVar('remarks')),
            ];
        }

        # Interest installment transaction data if amount covers Interest repayment
        if ($data['interestRepayment'] > 0) {
            $transactionData[] = [
                'date' => $date,
                'payment_id' => $payment_id,
                'particular_id' => $interest_particularId,
                'branch_id' => $this->userRow['branch_id'],
                'staff_id' => $this->userRow['staff_id'],
                'client_id' => $client_id,
                # add savings product id to transaction data
                'product_id' => ($paymentBySavings) ? $productRow['id'] : null,
                'application_id' => isset($disbursementInfo['application_id']) ? $disbursementInfo['application_id'] : null,
                'disbursement_id' => $disbursement_id,
                'entry_menu' => $entry_menu,
                'entry_typeId' => $entry_typeId,
                'account_typeId' => $account_typeId,
                'ref_id' => strtolower(substr($transaction_typeRow['type'], 0, 3)) . '-' . date('ym') . '-' . $ref_id,
                'amount' => $interestRepayment,
                'status' => $status,
                'balance' => $interestAccountingBalance,
                'contact' => trim($this->request->getVar('contact_full')),
                'entry_details' => trim($this->request->getVar('entry_details')),
                'remarks' => trim($this->request->getVar('remarks')),
            ];
        }
        // echo json_encode(count($transactionData)); exit;
        # Save Loan Repayment Transaction(s)
        if (count($transactionData) == 1) {
            $saveTransaction = $this->entry->insert($transactionData[0]);
        } else {
            $saveTransaction = $this->entry->insertBatch($transactionData);
        }

        if ($saveTransaction) {
            # Update the loan interest and principal collected
            $disbursementData = [
                'total_balance' => $totalLoanBalance,
                'interest_collected' => round($interestCollected),
                'principal_collected' => round($principalCollected),
                'total_collected' => round($totalCollected),
            ];
            $updateDisbursementBal = $this->disbursement->update($disbursement_id, $disbursementData);
            if ($updateDisbursementBal) {
                # Push overpayment to the client savings account
                if (!$paymentBySavings && ($balanceOnRepaymentAmt > 0)) {
                    $this->push_excessAmount_to_client_savings(['client_id' => $client_id, 'amount' => $balanceOnRepaymentAmt, 'payment_id' => $payment_id, 'from' => $account_typeInfo['name']]);
                }
                if ($paymentBySavings && (count($transactionData) > ! 0)) {
                    $response = [
                        'status'   => 500,
                        'error'    => null,
                        'messages' => $this->title . ' transaction could not processed'
                    ];
                    return $this->respond($response);
                    exit;
                }

                # Update the accounting particulars balances
                $loanParticular_idBal = ($data['principalRepayment'] > 0) ?? $this->particular->update($loanParticular_id, $loanParticularBal);
                $interestParticular_idBal = ($data['interestRepayment'] > 0) ?? $this->particular->update($interest_particularId, $interestParticularBal);

                $payment_idBal = $this->particular->update($payment_id, $paymentParticularBal);

                # Save the above transactions into the activity log
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'create',
                    'description' => ucfirst('New ' . $entry_menu . ' ' . $transaction_typeRow['type'] .  $this->title . ' for ' . $loanParticularRow['particular_name'] . ' Ref ID ' . $ref_id),
                    'module' => $this->menu,
                    'referrer_id' => $ref_id,
                ];
                $activity = $this->insertActivityLog($activityData);
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $txt = '';
                    # Check the internet connection
                    $checkInternet = $this->settings->checkNetworkConnection();
                    if ($checkInternet) {
                        $mailInfo = $disbursementInfo;
                        $mailInfo['amount'] = $amount;
                        $mailInfo['interestCollected'] = $interestCollected;
                        $mailInfo['branch_name'] = $this->userRow['branch_name'];
                        $mailInfo['principalCollected'] = $principalCollected;
                        $mailInfo['totalCollected'] = $totalCollected;
                        $mailInfo['ref_id'] = $ref_id;
                        $mailInfo['date'] = date('d-m-Y H:i:s');
                        $mailInfo['entry_details'] = trim($this->request->getVar('entry_details'));
                        $mailInfo['account_typeID'] = $account_typeId;
                        $mailInfo['type'] = $transaction_typeRow['type'];
                        $mailInfo['particular_name'] = $loanParticularRow['particular_name'];
                        $mailInfo['payment_mode'] = $paymentRow['particular_name'];
                        $mailInfo['repayment_period'] = $period;
                        # the principal balance
                        $mailInfo['principalBalance'] = $principalBalance;
                        # get the interest Balance
                        $mailInfo['interestBalance'] = $interestBalance;
                        # get the total loan outstanding balance
                        $mailInfo['totalLoanBalance'] = $totalLoanBalance;

                        # email message
                        $message = $mailInfo;
                        # check the email existence and email notify is enabled
                        if (!empty($mailInfo['email']) && $this->settingsRow['email']) {
                            $subject = 'New ' . $loanParticularRow['particular_name'] . ' Transaction';
                            $message = $message;
                            $token = 'transaction';
                            $this->settings->sendMail($message, $subject, $token);
                            $txt .= 'Email Sent';
                        }
                        # check the mobile existence and sms notify is enabled
                        if (!empty($mailInfo['mobile']) && $this->settingsRow['sms']) {
                            # send sms
                            $sms = $this->sendSMS([
                                'mobile' => trim($clientInfo['mobile']),
                                'text' => 'A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . number_format($amount, 2) . ' via ' . strtolower($paymentRow['particular_name']) . ' has been received by ' . strtolower($this->settingsRow['business_name']) . ' on ' . $date . ' . ID: ' . $ref_id . ' ~ ' . strtoupper($this->settingsRow["system_abbr"])
                            ]);
                            $txt .= ' SMS Sent';
                        }
                    }

                    $response = [
                        'status' => 200,
                        'error' => null,
                        'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully.' . $txt,
                    ];
                    return $this->respond($response);
                    exit;
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => $this->title . ' transaction created successfully. loggingFailed'
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Balance Update Failed',
                    'messages' => 'Updating Client loan balance Failed, Transaction recorded successfully!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 500,
                'error'    => 'Transaction Failed',
                'messages' => 'An Error occurred while creating Transaction, Try again!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    // function to handle application payments transactions
    private function add_applicationTransaction($ref_id, $account_typeInfo, $applicationAmt)
    {
        $account_typeId = $account_typeInfo['id'];
        $application_id = trim($this->request->getVar('application_id'));
        $applicationInfo = $this->loanApplicationRow($application_id);
        // check is application still exists
        if (!$applicationInfo) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Application Data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        $product_id = $applicationInfo['product_id'];
        $applicantCharges = unserialize($applicationInfo['overall_charges']);

        $client_id = trim($this->request->getVar('client_id'));
        $clientInfo = $this->client->find($client_id);
        // check is client still exists
        if (!$clientInfo) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Client Data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }

        $particular_id = trim($this->request->getVar('particular_id'));
        // get particular data
        $particularRow = $this->particularDataRow($particular_id);
        // check existence of accounting particulars
        if (!$particularRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Particular data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }

        $payment_id = trim($this->request->getVar('payment_id'));
        # check payment is though client savings
        $productRow = $this->product->where(['product_code' => $payment_id])->first();
        if ($productRow) {
            $paymentBySavings = true;
        } else {
            $paymentBySavings = false;
        }
        # Check the particular existence that will be used as for payment
        # if payment is thru savings then assign product savings particular id
        if ($paymentBySavings) {
            $paymentRow = $this->checkArrayExistance('particular', [
                'id' => $productRow['savings_particular_id'],
            ]);
            $payment_id = $productRow['savings_particular_id'];
        } else {
            $paymentRow = $this->checkArrayExistance('particular', [
                'id' => $payment_id,
            ]);
        }

        // calculate the charge
        $particularCharge = $this->report->loanProductCharge($applicantCharges, $applicationInfo['principal'], $particular_id);
        $chargeAmt = $particularCharge['totalCharge'];

        $entry_typeId = trim($this->request->getVar('entry_typeId'));
        // get transaction type data
        $transaction_typeRow = $this->entryType->find($entry_typeId);
        if (!$transaction_typeRow) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Transaction Type could not be found for ' . $account_typeInfo['name'] . '!',
            ];
            return $this->respond($response);
            exit;
        }
        // entry_menu of transaction based on entry type
        $entry_menu = $transaction_typeRow['entry_menu'];
        // status of transaction based on entry type
        $status = $transaction_typeRow['part'];

        // calculate entries total amount per entry status & final balance
        $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $particularRow['part']]);


        # Check if The Client Has Fully Paid or is Paying Excess Amounts
        $totalAmountPaid = $this->entry->sum_client_amountPaid($client_id, $particular_id, $status, 'application', $application_id);
        /** Check for Excess Payments
         * calculate total paid and excess amount
         * if any excess payment, push it to client savings
         */
        if ($totalAmountPaid == 0) {
            if ((int)$applicationAmt > (int)$chargeAmt) {
                $excessAmt = ((int)$applicationAmt - (int)$chargeAmt);
                $amount = (int)$chargeAmt;
            } else {
                $excessAmt = 0;
                $amount = (int)$applicationAmt;
            }
        } else {
            $particularBalance = ((int)$chargeAmt - (int)$totalAmountPaid);
            if ($particularBalance < 0) {
                $response = [
                    'status' => 500,
                    'error' => 'Entry Error!',
                    'messages' => 'An error was detected in posting of ' . $particularRow['particular_name'] . ' entries.',
                ];
                return $this->respond($response);
                exit;
            } else {
                if ((int)$applicationAmt > (int)$particularBalance) {
                    $excessAmt = ((int)$applicationAmt - (int)$particularBalance);
                    $amount = $particularBalance;
                }
                if ((int)$applicationAmt <= (int)$particularBalance) {
                    $excessAmt = 0;
                    $amount = $applicationAmt;
                }
            }

            # if $particularBalance = 0 (particular is fully paid) && $excessAmt > 0, push to savings then exit
            if (($particularBalance == 0) && ($excessAmt > 0)) {
                return $this->push_excessAmount_to_client_savings(['client_id' => $client_id, 'amount' => $excessAmt, 'payment_id' => $payment_id, 'from' => $particularRow['particular_name']]);
                exit;
            }
        }

        $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $applicationAmt); // calculate balance for primary particular as of this entry
        /** 
         * since account type is for Revenue category,
         * credit particular & debit payment method if its gaining,
         * debit particular & credit payment method if its loosing,
         */
        if (strtolower($status) == 'credit') {
            // credit selected particular
            $applParticularBal = ['credit' => ((float)$particularRow['credit'] + $amount)];
            // debit payment method
            $paymentParticularBal = ['debit' => ((float)$paymentRow['debit'] + $amount)];
        }
        if (strtolower($status) == 'debit') {
            // debit selected  particular
            $applParticularBal = ['debit' => ((float)$particularRow['debit'] + $amount)];
            // credit selected payment method
            $paymentParticularBal = ['credit' => ((float)$paymentRow['credit'] + $amount)];
        }

        if (!empty($this->request->getVar('date'))) {
            $date = trim($this->request->getVar('date'));
        } else {
            $date = date('Y-m-d');
        }
        // transaction\entry data
        $transactionData = [
            'date' => $date,
            'payment_id' => $payment_id,
            'particular_id' => $particular_id,
            'branch_id' => $this->userRow['branch_id'],
            'staff_id' => $this->userRow['staff_id'],
            'client_id' => $client_id,
            # add savings product id to transaction data
            'product_id' => ($paymentBySavings) ? $productRow['id'] : null,
            'application_id' => $application_id,
            'entry_menu' => $entry_menu,
            'entry_typeId' => $entry_typeId,
            'account_typeId' => $account_typeId,
            'ref_id' => strtolower(substr($transaction_typeRow['type'], 0, 3)) . '-' . date('ym') . '-' . $ref_id,
            'amount' => $amount,
            'status' => $status,
            'balance' => $accountingBalance,
            'contact' => trim($this->request->getVar('contact_full')),
            'entry_details' => trim($this->request->getVar('entry_details')),
            'remarks' => trim($this->request->getVar('remarks')),
        ];
        // save transaction
        $saveTransaction = $this->entry->insert($transactionData);
        if ($saveTransaction) {
            // update application status if application fees have been paid
            if (strtolower($applicationInfo['status']) == 'pending') {
                $this->loanApplication->update($applicationInfo['id'], ['status' => 'Processing']);
            }

            # push over payment to client  savings
            if ($excessAmt > 0) {
                $this->push_excessAmount_to_client_savings(['client_id' => $client_id, 'amount' => $excessAmt, 'payment_id' => $payment_id, 'from' => $particularRow['particular_name']]);
            }

            // update accounting particulars balances
            $particular_idBal = $this->particular->update($particular_id, $applParticularBal);
            $payment_idBal = $this->particular->update($payment_id, $paymentParticularBal);
            if ($particular_idBal && $payment_idBal) {
                // add transaction into the activity log
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'create',
                    'description' => ucfirst('New ' . $entry_menu . ' ' . $transaction_typeRow['type'] .  $this->title . ' for ' . $particularRow['particular_name'] . ' Ref ID ' . $ref_id),
                    'module' => $this->menu,
                    'referrer_id' => $ref_id,
                ];
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $txt = '' .
                        # check whether the internet connection exist
                        $checkInternet = $this->settings->checkNetworkConnection();
                    if ($checkInternet) {
                        $mailInfo = $applicationInfo;
                        $mailInfo['branch_name'] = $this->userRow['branch_name'];
                        $mailInfo['amount'] = $amount;
                        $mailInfo['charge'] = $chargeAmt;
                        $mailInfo['totalCollected'] = $totalAmountPaid;
                        $mailInfo['ref_id'] = $ref_id;
                        $mailInfo['date'] = date('d-m-Y H:i:s');
                        $mailInfo['entry_details'] = trim($this->request->getVar('entry_details'));
                        $mailInfo['account_typeID'] = $account_typeId;
                        $mailInfo['type'] = $transaction_typeRow['type'];
                        $mailInfo['particular_name'] = $particularRow['particular_name'];
                        $mailInfo['payment_mode'] = $paymentRow['particular_name'];
                        $message = $mailInfo;
                        # check the email existence and email notify is enabled
                        if (!empty($mailInfo['email']) && $this->settingsRow['email']) {
                            $subject = 'New ' . $particularRow['particular_name'] . ' Transaction';
                            $message = $message;
                            $token = 'transaction';
                            $this->settings->sendMail($message, $subject, $token);
                            $txt .= 'Email Sent';
                        }
                        # check the mobile existence and sms notify is enabled
                        if (!empty($mailInfo['mobile']) && $this->settingsRow['sms']) {
                            # send sms
                            $sms = $this->sendSMS([
                                'mobile' => trim($mailInfo['mobile']),
                                'text' => 'A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . number_format($amount, 2) . ' via ' . strtolower($particularRow['particular_name']) . ' has been received by ' . strtolower($this->settingsRow['business_name']) . ' on ' . $date . ' . ID: ' . $ref_id . ' ~ ' . strtoupper($this->settingsRow["system_abbr"])
                            ]);
                            $txt .= ' SMS Sent';
                        }
                    }
                    $response = [
                        'status' => 200,
                        'error' => null,
                        'messages' => $this->title . ' transaction created successfully.' . $txt,
                    ];
                    return $this->respond($response);
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => $this->title . ' transaction created successfully. loggingFailed'
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status'   => 500,
                    'error'    => 'Accounting Error',
                    'messages' => 'Implementing Double Entry failed, Transaction saved!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 500,
                'error'    => 'Transaction Failed',
                'messages' => 'An Error occurred while creating Transaction, Try again!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    // function to handle membership transactions
    private function add_membershipTransaction($ref_id, $account_typeInfo, $membershipAmt)
    {
        $account_typeId = $account_typeInfo['id'];
        $totalAmount = trim($this->request->getVar('particular_charge'));
        $client_id = trim($this->request->getVar('client_id'));
        # Check the client existence
        $clientInfo = $this->checkArrayExistance('client', [
            'id' => $client_id
        ]);

        $particular_id = trim($this->request->getVar('particular_id'));
        # Check the particular existence
        $particularRow = $this->checkArrayExistance('particular', [
            'id' => $particular_id,
        ]);

        # check payment is though client savings
        $payment_id = trim($this->request->getVar('payment_id'));
        $productRow = $this->product->where(['product_code' => $payment_id])->first();
        if ($productRow) {
            $paymentBySavings = true;
        } else {
            $paymentBySavings = false;
        }
        # Check the particular existence that will be used as for payment
        # if payment is thru savings then assign product savings particular id
        if ($paymentBySavings) {
            $paymentRow = $this->checkArrayExistance('particular', [
                'id' => $productRow['savings_particular_id'],
            ]);
            $payment_id = $productRow['savings_particular_id'];
        } else {
            $paymentRow = $this->checkArrayExistance('particular', [
                'id' => $payment_id,
            ]);
        }

        # Check the transaction existence
        $entry_typeId = trim($this->request->getVar('entry_typeId'));
        $transaction_typeRow = $this->checkArrayExistance('entryType', [
            'id' => $entry_typeId
        ]);
        # Get the entry menu
        $entry_menu = $transaction_typeRow['entry_menu'];
        $type = $transaction_typeRow['type'];
        # Get the transaction 
        $status = $transaction_typeRow['part'];

        # Check if The Client Has Fully Paid or is Paying Excess Amounts
        $totalAmountPaid = $this->entry->sum_client_amountPaid($client_id, $particular_id, $status);
        /** Check for Excess Payments
         * calculate total paid and excess amount
         * if any excess payment, push it to client savings
         */

        $chargeAmt = $totalAmount;
        if ($totalAmountPaid == 0) {
            if ((int)$membershipAmt > (int)$chargeAmt) {
                $excessAmt = ((int)$membershipAmt - (int)$chargeAmt);
                $amount = (int)$chargeAmt;
            } else {
                $excessAmt = 0;
                $amount = (int)$membershipAmt;
            }
        } else {
            $particularBalance = ($chargeAmt - $totalAmountPaid);
            if ($particularBalance < 0) {
                $response = [
                    'status' => 500,
                    'error' => 'Entry Error!',
                    'messages' => 'An error was detected in posting of ' . $particularRow['particular_name'] . ' entries.',
                ];
                return $this->respond($response);
                exit;
            } else {
                if ($membershipAmt > $particularBalance) {
                    $excessAmt = ($membershipAmt - $particularBalance);
                    $amount = $particularBalance;
                }
                if ($membershipAmt <= $particularBalance) {
                    $excessAmt = 0;
                    $amount = $membershipAmt;
                }
            }

            # if $particularBalance = 0 (particular is fully paid) && $excessAmt > 0, push to savings then exit
            if (($particularBalance == 0) && ($excessAmt > 0)) {
                return $this->push_excessAmount_to_client_savings(['client_id' => $client_id, 'amount' => $excessAmt, 'payment_id' => $payment_id, 'from' => $particularRow['particular_name']]);
                exit;
            }
        }

        # calculate entries total amount per entry status & final balance
        $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $particularRow['part']]);
        $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $amount);

        # Compute the account balance
        # Particular: Membership, Category: Revenue
        /** 
         * since account type is for Revenue category,
         * credit particular & debit payment method if its gaining,
         * debit particular & credit payment method if its loosing,
         */
        $accountBalance = $this->checkArrayExistance('accountBalance', [
            'part' => strtolower($status),
            'amount' => $amount,
            'accountBalance' => $clientInfo['account_balance'],
            'debitParticularBalance' => $particularRow['debit'],
            'creditParticularBalance' => $particularRow['credit'],
            'debitPaymentBalance' => $paymentRow['debit'],
            'creditPaymentBalance' => $paymentRow['credit']
        ]);
        # Check the membership transaction data existence
        if (!empty($this->request->getVar('date'))) {
            $date = trim($this->request->getVar('date'));
        } else {
            $date = date('Y-m-d');
        }
        # Membership transaction payload
        $transactionData = [
            'date' => $date,
            'payment_id' => $payment_id,
            'particular_id' => $particular_id,
            'branch_id' => $this->userRow['branch_id'],
            'staff_id' => $this->userRow['staff_id'],
            'client_id' => $client_id,
            'entry_menu' => $entry_menu,
            'entry_typeId' => $entry_typeId,
            'account_typeId' => $account_typeId,
            'ref_id' => strtolower(substr($transaction_typeRow['type'], 0, 3)) . '-' . date('ym') . '-' . $ref_id,
            'amount' => $amount,
            'status' => $status,
            'balance' => $accountingBalance,
            'contact' => trim($this->request->getVar('contact_full')),
            'entry_details' => trim($this->request->getVar('entry_details')),
            'remarks' => trim($this->request->getVar('remarks')),
        ];
        # add savings product id to transaction data
        $transactionData['product_id'] = ($paymentBySavings) ? $productRow['id'] : null;

        # Save membership transactions
        $saveTransaction = $this->entry->insert($transactionData);

        # push over payment to client savings
        if ($excessAmt > 0) {
            $this->push_excessAmount_to_client_savings(['client_id' => $client_id, 'amount' => $excessAmt, 'payment_id' => $payment_id, 'from' => $particularRow['particular_name']]);
        }

        # Update accounting particulars balances
        $updateParticularAccount = $this->particular->update($particular_id, [
            'credit' => $accountBalance['creditBalance'],
            'debit' => $accountBalance['debitBalance']
        ]);
        $updatePaymentAccount = $this->particular->update($payment_id, [
            'credit' => $accountBalance['creditBalance'],
            'debit' => $accountBalance['debitBalance']
        ]);
        # Add transaction information into the activity log
        $activity = $this->insertActivityLog([
            'user_id' => $this->userRow['id'],
            'action' => 'create',
            'description' => ucfirst('New ' . $entry_menu . ' ' . $transaction_typeRow['type'] .  $this->title . ' for ' . $particularRow['particular_name'] . ' Ref ID ' . $ref_id),
            'module' => $this->menu,
            'referrer_id' => $ref_id,
        ]);
        # text to add the email status and sms
        $txt = '';
        $checkInternet = $this->settings->checkNetworkConnection();
        # Check the internet connection
        if ($checkInternet) {
            $mailInfo = $clientInfo;
            $mailInfo['branch_name'] = $this->userRow['branch_name'];
            $mailInfo['amount'] = $amount;
            $mailInfo['charge'] = $particularRow['charge'];
            $mailInfo['ref_id'] = $ref_id;
            $mailInfo['date'] = date('d-m-Y H:i:s');
            $mailInfo['entry_details'] = trim($this->request->getVar('entry_details'));
            $mailInfo['account_typeID'] = $account_typeId;
            $mailInfo['type'] = $transaction_typeRow['type'];
            $mailInfo['particular_name'] = $particularRow['particular_name'];
            $mailInfo['payment_mode'] = $paymentRow['particular_name'];
            $message = $mailInfo;
            # Check the client email existence and email notify is enabled
            if (!empty($mailInfo['email']) && $this->settingsRow['email']) {
                $subject = 'New ' . $particularRow['particular_name'] . ' Transaction';
                $message = $message;
                $token = 'transaction';
                $this->settings->sendMail($message, $subject, $token);
                $txt .= 'Email Sent';
            }

            # check the mobile existence and sms notify is enabled
            if (!empty($mailInfo['mobile']) && $this->settingsRow['sms']) {
                # send sms
                $sms = $this->sendSMS([
                    'mobile' => trim($mailInfo['mobile']),
                    'text' => 'A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . number_format($amount, 2) . ' being made for ' . strtolower($particularRow['particular_name']) . ' has been received by ' . strtolower($this->settingsRow['business_name']) . ' on ' . $date . ' . ID: ' . $ref_id . ' ~ ' . strtoupper($this->settingsRow["system_abbr"])
                ]);
                $txt .= ' SMS Sent';
            }
        }
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => $this->title . ' transaction created successfully.' . $txt,
        ];
        return $this->respond($response);
        exit;
    }

    public function getMembershipCharge(array $data)
    {
        $reg_date = $data['reg_date'];
        $particular_id = $data['particular_id'];

        $chargeRow = $this->vlookupCharge($particular_id, $reg_date);
        $data = [];
        if ($chargeRow) {
            $data[] = $chargeRow;
        }

        return $data;
    }

    // bulky import transaction entries
    private function import_transactions()
    {
        $this->menu = trim($this->request->getVar('menu'));
        $this->title = trim(ucwords($this->request->getVar('title')));
        $this->menuItem['menu'] = $this->menu;
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'import')) {
            if (!empty($_FILES['file']['name']) && !empty($this->request->getVar('branchID'))) {
                # get uploaded file extension
                $path_parts = pathinfo($_FILES["file"]["name"]);
                $ext = $path_parts['extension'];
                # check whether the uploaded file extension matches with csv
                if ($ext == 'csv') {
                    $file = $this->request->getFile("file");
                    $file_name = $file->getTempName();
                    $file_data = array_map('str_getcsv', file($file_name));

                    if (count($file_data) > 0) {
                        $index = 0;
                        $data = [];
                        // $insert = false;
                        foreach ($file_data as $column) {
                            $index++;

                            # ignore the column headers
                            if ($index == 1) {
                                continue;
                            }
                            # ignore empty row in excel sheets
                            if ((string) $column[0] != '0' && empty($column[0])) {
                                continue;
                            }
                            # nullable columns
                            $client_id = $product_id = $application_id = $disbursement_id = null;

                            # transaction particular data
                            $particularCode = $column[1];
                            $particularRow = $this->particular->where(['particular_code' => $particularCode])->first();
                            if (!$particularRow) {
                                continue;
                            }
                            $particular_id = $particularRow['id'];
                            # transaction payment data
                            $paymentCode = $column[2];
                            $paymentRow = $this->particular->where(['particular_code' => $paymentCode])->first();
                            if (!$paymentRow) {
                                continue;
                            }
                            $payment_id = $paymentRow['id'];
                            # transaction client data
                            if (!empty($column[3])) {
                                $account_no = $column[3];
                                $clientRow = $this->client->where(['account_no' => $account_no])->first();
                                if (!$clientRow) {
                                    continue;
                                }
                                $client_id = $clientRow['id'];
                                $contact = $clientRow['mobile'];
                            }
                            # transaction product data
                            if (!empty($column[4])) {
                                $product_code = $column[4];
                                $productRow = $this->loanProduct->where(['product_code' => $product_code])->first();
                                if (!$productRow) {
                                    continue;
                                }
                                $product_id = $productRow['id'];
                            }
                            # transaction application data
                            if (!empty($column[5])) {
                                $application_code = $column[5];
                                $applicationRow = $this->loanApplication->where(['application_code' => $application_code])->first();
                                if (!$applicationRow) {
                                    continue;
                                }
                                $application_id = $applicationRow['id'];
                            }
                            # transaction disbursement data
                            if (!empty($column[6])) {
                                $disbursement_code = $column[6];
                                $disbursementRow = $this->disbursement->where(['disbursement_code' => $disbursement_code])->first();
                                if (!$disbursementRow) {
                                    continue;
                                }
                                $disbursement_id = $disbursementRow['id'];
                            }
                            # transaction account type data
                            $accTypeCode = $column[7];
                            $accTypeRow = $this->accountType->where(['code' => $accTypeCode])->first();
                            if (!$accTypeRow) {
                                continue;
                            }
                            $account_typeId = $accTypeRow['id'];
                            # transaction entry type data
                            $entryTypeCode = $column[8];
                            $entryTypeRow = $this->entryType->where(['type_code' => $entryTypeCode])->first();
                            if (!$entryTypeRow) {
                                continue;
                            }
                            $entry_typeId = $entryTypeRow['id'];
                            $entry_menu = $entryTypeRow['entry_menu'];
                            $status = $entryTypeRow['part'];

                            $amount = $column[9];
                            $ref_id = (!empty($column[10]) ? $column[10] : $this->settings->generateReference());

                            # Calculate entries total amount per entry status & final balance
                            $entriesStatusTotals = $this->entry->calculateTotalBalance([
                                'module' => 'particular',
                                'module_id' => $particular_id,
                                'status' => $status
                            ]);
                            $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $amount);

                            # entry data
                            $data[] = [
                                'date' => date('Y-m-d', strtotime($column[0])),
                                'particular_id' => $particular_id,
                                'payment_id' => $payment_id,
                                'branch_id' => trim($this->request->getVar('branchID')),
                                'staff_id' => $this->userRow['staff_id'],
                                'client_id' => $client_id,
                                'product_id' => $product_id,
                                'application_id' => $application_id,
                                'disbursement_id' => $disbursement_id,
                                'account_typeId' => $account_typeId,
                                'entry_typeId' => $entry_typeId,
                                'amount' => $amount,
                                'ref_id' => $ref_id,
                                'entry_menu' => $entry_menu,
                                'entry_details' => $column[11],
                                'contact' => (!empty($column[12]) ? $column[12] : $contact),
                                'status' => $status,
                                'balance' => $accountingBalance,
                                'remarks' => $column[13],
                            ];
                            // echo json_encode($data); exit;

                            # save the entry information
                            // $insert = $this->entry->insert($data);

                            # check whether the internet connection exist
                            // $checkInternet = $this->settings->checkNetworkConnection();
                            // if ($checkInternet) {
                            //     # check the email existance
                            //     if (!empty($email)) {
                            //         $subject = $this->title . " Registration";
                            //         $message = $client;
                            //         $token = 'registration';
                            //         $this->settings->sendMail($message, $subject, $token, $password);
                            //     }

                            //     # check the phone number existance
                            //     if (!empty($client['mobile'])) {
                            //         # send sms
                            //         /*
                            //         $sms = $this->sendSMS([
                            //             'module' => 'account',
                            //             'name' => $client['name'],
                            //             'mobile' => $client['mobile'],
                            //             'password' => $password
                            //         ]);
                            //         */
                            //     }
                            // }
                        }
                        # insert imported data
                        // echo json_encode($data); exit;
                        $insert = $this->entry->insertBatch($data);

                        if ($insert) {
                            // insert into activity logs
                            $activityData = [
                                'user_id' => $this->userRow['id'],
                                'action' => 'create',
                                'description' => ucfirst('Imported ' . $index . ' ' . $this->title),
                                'module' => $this->menu,
                                'referrer_id' => $insert,
                            ];
                            $activity = $this->insertActivityLog($activityData);
                            if ($activity) {
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => $index . ' ' . $this->title . " imported successfully."
                                ];
                                return $this->respond($response);
                                exit;
                            } else {
                                $response = [
                                    'status'   => 200,
                                    'error'    => null,
                                    'messages' => 'Imported ' . $index . ' ' . $this->title . ' successfully. loggingFailed'
                                ];
                                return $this->respond($response);
                                exit;
                            }
                        }
                    }
                } else {
                    # mismatch of the uploaded file type
                    $data['inputerror'][] = 'file';
                    $data['error_string'][] = 'Upload Error: The filetype you are attempting to upload is not allowed.';
                    $data['status'] = FALSE;
                    echo json_encode($data);
                    exit();
                }
            } else {
                # validation
                $data = array();
                $data['error_string'] = array();
                $data['inputerror'] = array();
                $data['status'] = TRUE;

                if ($this->request->getVar('branchID') == '') {
                    $data['inputerror'][] = 'branchID';
                    $data['error_string'][] = 'Branch is required!';
                    $data['status'] = FALSE;
                }
                if (empty($_FILES['file']['name'])) {
                    # Please browse for the file to be uploaded
                    $data['inputerror'][] = 'file';
                    $data['error_string'][] = 'Upload Error: CSV File is required!';
                    $data['status'] = FALSE;
                }

                if ($data['status'] === FALSE) {
                    echo json_encode($data);
                    exit;
                }
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to create ' . $this->title . ' ' . $this->menu . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update_transaction($id = null)
    {
        $this->menu = trim($this->request->getVar('menu'));
        $this->title = trim(ucwords($this->request->getVar('title')));
        $this->menuItem['menu'] = $this->menu;
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $this->_validateTransactions("update");
            $transactionRow = $this->entry->find($id);
            if ($transactionRow) {
                $entry_menu = trim($this->request->getVar('entry_menu'));
                // set particula and payment ids
                if (strtolower($entry_menu) == "transfer" || strtolower($entry_menu) == "investment") {
                    $particular_id = trim($this->request->getVar('crParticular_id'));
                    $payment_id = trim($this->request->getVar('drParticular_id'));
                } else {
                    $particular_id = trim($this->request->getVar('particular_id'));
                    $payment_id = trim($this->request->getVar('payment_id'));
                }
                $particularRow = $this->particularDataRow($particular_id);
                $particularRow = $this->particularDataRow($payment_id);
                if ($particularRow && $particularRow) {
                    $amount = trim($this->request->getVar('amount'));
                    $account_typeId = trim($this->request->getVar('entry_typeId'));
                    $entry_typeId = trim($this->request->getVar('entry_typeId'));
                    // get transaction type data
                    $transaction_typeRow = $this->entryType->find($entry_typeId);
                    if ($transaction_typeRow) {
                        // calculate entries total amount per entry status & final balance
                        $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $particularRow['part'], 'entryId' => $id]);
                        $particularBalance = (float)($entriesStatusTotals['totalBalance'] + $amount); // calculate balance for primary paticular as of this entry
                        $status = $transaction_typeRow['part'];

                        if (!empty($this->request->getVar('date'))) {
                            $date = trim($this->request->getVar('date'));
                        } else {
                            $date = date('Y-m-d');
                        }
                        /**
                         * if edit entry is disbursement repayment & was loan repayment 
                         * slit the transaction into 2. i.e interest & principal entries
                         */
                        if (($account_typeId == 3) && ($transactionRow['account_typeId'] == 3)) {
                            return $this->update_disbursementTransaction($id, $transactionRow, $transaction_typeRow);
                            exit;
                        }
                        // edit any other non loan repayment 
                        if ($account_typeId != 3) {
                            // entry data 
                            $data = [
                                'date' => $date,
                                'payment_id' => $payment_id,
                                'particular_id' => $particular_id,
                                'branch_id' => $this->userRow['branch_id'],
                                'staff_id' => $this->userRow['staff_id'],
                                'entry_menu' => $entry_menu,
                                'entry_details' => trim($this->request->getVar('entry_details')),
                                'account_typeId' => $account_typeId,
                                'entry_typeId' => $entry_typeId,
                                'amount' => $amount,
                                'status' => $status,
                                'contact' => trim($this->request->getVar('contact_full')),
                                'balance' => $particularBalance,
                                'remarks' => trim($this->request->getVar('remarks')),
                            ];

                            /**
                             * if edit entry was disbursement repayment but changed
                             * edit principal entry to new details & delete the interest entry
                             */
                            $update = $this->entry->update($id, $data);
                            // delete corresponding interest entry row
                            if ($transactionRow['account_typeId'] == 3) {
                                $delete = $this->entry->where(['ref_id' => $transactionRow['ref_id'], 'id !=' => $id])->delete();
                            }
                        }
                        if ($update) {
                            // insert into activity logs
                            $activityData = [
                                'user_id' => $this->userRow['id'],
                                'action' => 'update',
                                'description' => ucfirst('updated ' . $this->title . ', ' . $transactionRow['ref_id']),
                                'module' => $this->menu,
                                'referrer_id' => $transactionRow['ref_id'],
                            ];
                            $activity = $this->insertActivityLog($activityData);
                            if ($activity) {
                                $response = [
                                    'status'   => 200,
                                    'error'    => null,
                                    'messages' => 'Transaction updated successfully'
                                ];
                                return $this->respond($response);
                                exit;
                            } else {
                                $response = [
                                    'status'   => 201,
                                    'error'    => null,
                                    'messages' => 'Transaction updated successfully. loggingFailed'
                                ];
                                return $this->respond($response);
                                exit;
                            }
                        } else {
                            $response = [
                                'status' => 500,
                                'error' => 'Update Failed',
                                'messages' => 'Updating ' . $this->title . ' record failed, try again later!',
                            ];
                            return $this->respond($response);
                            exit;
                        }
                    } else {
                        $response = [
                            'status' => 404,
                            'error' => 'Not Found',
                            'messages' => 'Transaction Type could not be found!',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status'   => 404,
                        'error'    => 'Not Found',
                        'messages' => 'Selected transer particular couldn\'t be found!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'Transaction could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to update ' . $this->title . ' ' . $this->menu . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }
    // update disbursement repayment transaction
    private function update_disbursementTransaction($id, $transactionRow, $transaction_typeRow)
    {
        $this->menu = trim($this->request->getVar('menu'));
        $this->title = trim(ucwords($this->request->getVar('title')));
        $this->menuItem['menu'] = $this->menu;
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            $payment_id = trim($this->request->getVar('payment_id'));
            $particular_id = trim($this->request->getVar('particular_id'));
            $amount = $this->removeCommasFromAmount($this->request->getVar('amount'));
            $account_typeId = trim($this->request->getVar('entry_typeId'));
            $entry_typeId = trim($this->request->getVar('entry_typeId'));
            $disbursementRow = $this->loanDisbursementRow($transactionRow['disbursement_id']);
            $interest_account_typeId = 19; // revenue from loans id
            $loanParticular = $this->particularDataRow($disbursementRow['particular_id']);
            $interestParticular = $this->particular->where(['account_typeId' => $interest_account_typeId])->first();
            $interest_particularId = $interestParticular['id'];
            $principalEntryId = $id; // id for principal repayment entry/transaction
            // get interest entry row corresponding to principal entry row
            $interestEntryRow = $this->entry->where(['ref_id' => $transactionRow['ref_id'], 'particular_id' => $interest_particularId])->find();
            $interestEntryId = $interestEntryRow['id']; // id for interest repayment entry/transaction
            // compute payment towards principal and interest respectively
            $interestInstallment = $principalInstallment = $interval = $payouts = 0;
            $interval = $this->settings->generateIntervals($disbursementRow['repayment_freq']);
            $payouts = (12 / $interval['interval']);
            // calculate interest installment
            if (strtolower($disbursementRow['interest_type']) == 'reducing') {
                $interestInstallment = round(($disbursementRow['principal_balance']) * ($disbursementRow['interest_rate'] / 100)) / ($payouts);
            }
            if (strtolower($disbursementRow['interest_type']) == 'flat') {
                $interestInstallment = round(($disbursementRow['principal']) * ($disbursementRow['interest_rate'] / 100)) / ($payouts);
            }
            $principalInstallment = ($amount - $interestInstallment);
            // calculate entries total amount per entry status & final balance
            $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $loanParticular['part'], 'entryId' => $id]);
            $principalAccountingBalance = (float)($entriesStatusTotals['totalBalance'] + $amount); // calculate balance for primary paticular as of this entry
            // interest
            $entriesStatusTotals_interest = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $interest_particularId, 'status' => $interestParticular['part'], 'entryId' => $id]);
            $interestAccountingBalance = (float)($entriesStatusTotals_interest['totalBalance'] + $amount); // calculate balance for primary paticular as of this entry

            // transaction\entry data
            if (!empty($this->request->getVar('date'))) {
                $date = trim($this->request->getVar('date'));
            } else {
                $date = date('Y-m-d');
            }
            // principal installment transaction data
            $updateData = [
                // principal installment transaction data
                [
                    'id' => $principalEntryId,
                    'date' => $date,
                    'payment_id' => $payment_id,
                    'particular_id' => $particular_id,
                    'branch_id' => $this->userRow['branch_id'],
                    'staff_id' => $this->userRow['staff_id'],
                    'client_id' => $this->request->getVar('client_id'),
                    'disbursement_id' => $transactionRow['disbursement_id'],
                    'entry_menu' => $transaction_typeRow['entry_menu'],
                    'entry_typeId' => $entry_typeId,
                    'account_typeId' => $account_typeId,
                    'ref_id' => $transactionRow['ref_id'],
                    'amount' => $principalInstallment,
                    'status' => $transaction_typeRow['status'],
                    'balance' => $principalAccountingBalance,
                    'contact' => trim($this->request->getVar('contact_full')),
                    'entry_details' => trim($this->request->getVar('entry_details')),
                    'remarks' => trim($this->request->getVar('remarks')),
                ],
                // interest installment transaction data
                [
                    'id' => $interestEntryId,
                    'date' => $date,
                    'payment_id' => $payment_id,
                    'particular_id' => $interest_particularId,
                    'branch_id' => $this->userRow['branch_id'],
                    'staff_id' => $this->userRow['staff_id'],
                    'client_id' => $this->request->getVar('client_id'),
                    'disbursement_id' => $transactionRow['disbursement_id'],
                    'entry_menu' => $transaction_typeRow['entry_menu'],
                    'entry_typeId' => $entry_typeId,
                    'account_typeId' => $account_typeId,
                    'ref_id' => $transactionRow['ref_id'],
                    'amount' => $interestInstallment,
                    'status' => $transaction_typeRow['status'],
                    'balance' => $interestAccountingBalance,
                    'contact' => trim($this->request->getVar('contact_full')),
                    'entry_details' => trim($this->request->getVar('entry_details')),
                    'remarks' => trim($this->request->getVar('remarks')),
                ]
            ];
            // Update batch  row
            $update = $this->entry->updateBatch($updateData, 'id');
            if ($update) {
                // insert into activity logs
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'update',
                    'description' => ucfirst('updated ' . $this->title . ', ' . $transactionRow['ref_id']),
                    'module' => $this->menu,
                    'referrer_id' => $transactionRow['ref_id'],
                ];
                $activity = $this->insertActivityLog($activityData);
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => 'Transaction updated successfully'
                    ];
                    return $this->respond($response);
                    exit;
                } else {
                    $response = [
                        'status'   => 201,
                        'error'    => null,
                        'messages' => 'Transaction updated successfully. loggingFailed'
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Update Failed',
                    'messages' => 'Updating ' . $this->title . ' record failed, try again later!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to update ' . $this->title . ' ' . $this->menu . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $this->menu = trim($this->request->getVar('menu'));
        $this->title = trim(ucwords($this->request->getVar('title')));
        $this->menuItem['menu'] = $this->menu;
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
            $data = $this->entry->find($id);
            if ($data) {
                $ref_id = $data['ref_id'];
                $parent_id = $data['parent_id'];
                $childRow = $this->entry->where(['parent_id' => $parent_id])->find();
                $delete = $this->entry->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', ' . $data['ref_id']),
                        'module' => $this->menu,
                        'referrer_id' => $data['ref_id'],
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    // also delete referenced entry records if any
                    $refRow = $this->entry->where(['ref_id' => $ref_id])->find();
                    if ($refRow) {
                        $deleteRef = $this->entry->delete($refRow['id']);
                        $activityDataRef = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'delete',
                            'description' => ucfirst('deleted ' . $this->title . ', ' . $refRow['ref_id']),
                            'module' => $this->menu,
                            'referrer_id' => $refRow['ref_id'],
                        ];
                        $this->insertActivityLog($activityDataRef);
                    }
                    // also delete child entry records if any
                    if ($childRow) {
                        $deleteChild = $this->entry->delete($childRow['id']);
                        $activityDataChild = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'delete',
                            'description' => ucfirst('deleted ' . $this->title . ', ' . $childRow['ref_id']),
                            'module' => $this->menu,
                            'referrer_id' => $childRow['ref_id'],
                        ];
                        $this->insertActivityLog($activityDataChild);
                    }
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' deleted successfully',
                        ];
                        return $this->respond($response);
                        exit;
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' deleted successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Delete Failed',
                        'messages' => 'Deleting ' . $this->title . ' failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'The requsted ' . $this->title . ' resource could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' record!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     */
    public function ajax_bulky_delete()
    {
        $this->menu = trim($this->request->getVar('menu'));
        $this->title = trim(ucwords($this->request->getVar('title')));
        $this->menuItem['menu'] = $this->menu;
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->entry->find($id);
                if ($data) {
                    $delete = $this->entry->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['ref_id']),
                            'module' => $this->menu,
                            'referrer_id' => $data['ref_id'],
                        ];
                        $activity = $this->insertActivityLog($activityData);
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            }
            if ($activity) {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . '(s) deleted successfully',
                ];
                return $this->respond($response);
                exit;
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . '(s) deleted successfully. loggingFailed'
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' ' . $this->menu . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * validate form inputs
     */
    private function _validateTransactions($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        $application_id = $this->request->getVar('application_id');
        $entry_menu = trim($this->request->getVar('entry_menu'));
        $amount = $this->removeCommasFromAmount($this->request->getVar('amount'));
        $account_typeId = trim($this->request->getVar('account_typeId'));
        $entry_typeId = trim($this->request->getVar('entry_typeId'));
        $disbursement_id = trim($this->request->getVar('disbursement_id'));
        $client_id = trim($this->request->getVar('client_id'));

        $particularInfo = $particularInfo = $disbursementInfo = $transaction_typeRow = [];
        if (!empty($entry_typeId)) {
            $transaction_typeRow = $this->entryType->find($entry_typeId);
        }

        # trimmed the white space between between country code and phone number
        $contact = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('contact_country_code'),
            'phone' => $this->request->getVar('contact')
        ]);

        # check the entry menu first
        if (strtolower($entry_menu) == "transfer" || strtolower($entry_menu) == "investment") {
            $particular_id = trim($this->request->getVar('crParticular_id'));
            $payment_id = trim($this->request->getVar('drParticular_id'));
        } else {
            $particular_id = trim($this->request->getVar('particular_id'));
            $payment_id = trim($this->request->getVar('payment_id'));
        }

        # check the particular existence
        if (!empty($particular_id)) {
            # get the particular information
            $particularInfo = $this->particularDataRow($particular_id);
            if (strtolower($particularInfo['part']) == 'debit') {
                $particularBalance = (float)(($particularInfo['opening_balance'] + $particularInfo['debit']) - $particularInfo['credit']);
            }
            if (strtolower($particularInfo['part']) == 'credit') {
                $particularBalance = (float)($particularInfo['debit'] - ($particularInfo['opening_balance'] + $particularInfo['credit']));
            }
        }

        # check the payment method existence
        if (!empty($payment_id)) {
            # get the particular information that is being used as payment method
            $paymentInfo = $this->particularDataRow($payment_id);
        }

        if ($this->request->getVar('title') == '') {
            $data['inputerror'][] = 'title';
            $data['error_string'][] = 'Title is required';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('entry_menu') == '') {
            $data['inputerror'][] = 'entry_menu';
            $data['error_string'][] = 'Entry Menu is required';
            $data['status'] = FALSE;
        }

        if (strtolower($entry_menu) == "transfer" || strtolower($entry_menu) == "investment") {
            // validate transaction particular account types
            if ($this->request->getVar('credit_accountType') == '') {
                $data['inputerror'][] = 'credit_accountType';
                $data['error_string'][] = 'Credited Account Type is required';
                $data['status'] = FALSE;
            }
            if ($this->request->getVar('debit_accountType') == '') {
                $data['inputerror'][] = 'debit_accountType';
                $data['error_string'][] = 'Debited Account Type is required';
                $data['status'] = FALSE;
            }
            // validate particular to be credited
            if ($this->request->getVar('crParticular_id') == '') {
                $data['inputerror'][] = 'crParticular_id';
                $data['error_string'][] = 'Credited Particular is required';
                $data['status'] = FALSE;
            }
            // validate particular to be debited
            if ($this->request->getVar('drParticular_id') == '') {
                $data['inputerror'][] = 'drParticular_id';
                $data['error_string'][] = 'Debited Particular is required';
                $data['status'] = FALSE;
            }
            //  validate if debited and credited particular isn't the same
            if ($this->request->getVar('crParticular_id') == $this->request->getVar('drParticular_id')) {
                $data['inputerror'][] = 'drParticular_id';
                $data['error_string'][] = 'Credit and Debited Particular can\'t be the same';
                $data['status'] = FALSE;
            }
        } else {
            $particular_id = $this->request->getVar('particular_id');
            $payment_id = $this->request->getVar('payment_id');
            // validate transacting particular
            if ($this->request->getVar('particular_id') == '') {
                $data['inputerror'][] = 'particular_id';
                $data['error_string'][] = 'Particular is required';
                $data['status'] = FALSE;
            }
            // validate particular to be used for payment
            if ($this->request->getVar('payment_id') == '') {
                $data['inputerror'][] = 'payment_id';
                $data['error_string'][] = 'Payment Method is required';
                $data['status'] = FALSE;
            }

            if ($this->request->getVar('entry_typeId') == '') {
                $data['inputerror'][] = 'entry_typeId';
                $data['error_string'][] = 'Transaction Type is required';
                $data['status'] = FALSE;
            }

            // validate transaction particular account type
            if ($this->request->getVar('account_typeId') == '') {
                $data['inputerror'][] = 'account_typeId';
                $data['error_string'][] = 'Account Type is required';
                $data['status'] = FALSE;
            }
            # check the transaction entry menu
            if (strtolower($entry_menu) != "expense") {
                # check if the payment method is savings
                $productRow = $this->product->where(['product_code' => $payment_id])->first();
                if ($productRow) {
                    $paymentBySavings = true;
                } else {
                    $paymentBySavings = false;
                }
                if (!empty($this->request->getVar('account_typeId'))) {
                    if ($this->request->getVar('client_id') == '') {
                        $data['inputerror'][] = 'client_id';
                        $data['error_string'][] = 'Client is required';
                        $data['status'] = FALSE;
                    }

                    $clientInfo = $this->clientDataRow($client_id);
                    # selected client savings product balance
                    $savingsProducts = (($clientInfo['savingsProducts']) ? $clientInfo['savingsProducts'] : null);
                    if ($paymentBySavings && $savingsProducts) {
                        $productbalance = 0;
                        // Re-index savingsProducts by product_id without looping
                        $productsByCode = array_column($savingsProducts, null, 'product_code');
                        // Retrieve the entire product array for the selected product code
                        $selectedProduct = $productsByCode[$payment_id] ?? null;
                        $productbalance = $selectedProduct['product_balance'];

                        if ((strtolower($this->request->getVar('title')) != 'withdrawals') && ($productbalance < $amount)) {
                            $data['inputerror'][] = 'amount';
                            $data['error_string'][] = 'Insufficient ' . $productRow['product_name'] . ' balance. Available balance: ' . number_format($productbalance, 2) . '!';
                            $data['status'] = FALSE;
                        }
                    }
                    # validate transaction based on account type
                    switch (strtolower($account_typeId)) {
                        case 3: # Loan Portfolio
                            if ($this->request->getVar('disbursement_id') == '') {
                                $data['inputerror'][] = 'disbursement_id';
                                $data['error_string'][] = 'Disbursement ID is required';
                                $data['status'] = FALSE;
                            }

                            if (!empty($disbursement_id)) {
                                $disbursementInfo = $this->disbursement->find($disbursement_id);

                                if ($disbursementInfo) {
                                    # check whether the loan total amount is fully paid
                                    if ($disbursementInfo['total_balance'] <= 0) {
                                        $data['inputerror'][] = 'amount';
                                        $data['error_string'][] = 'Total loan amount of ' . $disbursementInfo['actual_repayment'] . ' paid in full!';
                                        $data['status'] = FALSE;
                                    }
                                    # check whether there is overpayment
                                    if ($amount > $disbursementInfo['total_balance']) {
                                        $data['inputerror'][] = 'amount';
                                        $data['error_string'][] = 'Amount exceeds the total loan balance of ' . $disbursementInfo['total_balance'] . '!';
                                        $data['status'] = FALSE;
                                    }
                                }
                            }
                            break;

                        case 8: # Shares
                            if ((strtolower($this->request->getVar('title')) == 'withdrawals') && ($clientInfo['sharesBalance'] < $amount)) {

                                $data['inputerror'][] = 'shares_units';
                                $data['error_string'][] = 'Units to be Withdrawn can not be more than Units Purchased!';
                                $data['status'] = FALSE;
                            }
                            if (($particularInfo['charged'] == "Yes") && !empty($this->request->getVar('client_id'))) {
                                $clientInfo = $this->client->find($client_id);
                                $chargeRow = $this->vlookupSharesCharge($particular_id, $clientInfo['reg_date']);

                                // get particular charge
                                if ($chargeRow) {
                                    $charge = (float)$chargeRow['charge'];
                                } else {
                                    $charge = null;
                                }
                                // calculate the charge
                                if ($charge && ($amount < $charge)) {
                                    $data['inputerror'][] = 'amount';
                                    $data['error_string'][] = 'Amount is below ' . $particularInfo['particular_name'] . ' charge of ' . $charge . '!';
                                    $data['status'] = FALSE;
                                }
                            }
                            break;
                        case 12: # Savings
                            $acc_balance = $clientInfo['account_balance'];
                            $productbalance = 0;
                            $min_account_balance = $max_account_balance = $min_per_entry = $max_per_entry = null;
                            $product_id = !empty($this->request->getVar('product_id')) ? trim($this->request->getVar('product_id')) : null;
                            # get savings product row
                            if ($product_id) {
                                $selectedProductRow = $this->product->find($product_id);
                                if ($selectedProductRow) {
                                    # Validate selected client savings product balance
                                    if ($savingsProducts) {
                                        // Re-index savingsProducts by product_id without looping
                                        $productsById = array_column($savingsProducts, null, 'product_id');
                                        // Retrieve the entire product array for the selected product id
                                        $selectedProduct = $productsById[$product_id] ?? null;
                                        $productbalance = $selectedProduct['product_balance'];
                                    }
                                    $min_account_balance = ($selectedProductRow['min_account_balance'] > 0) ? $selectedProductRow['min_account_balance'] : null;
                                    $max_account_balance = ($selectedProductRow['max_account_balance'] > 0) ? $selectedProductRow['max_account_balance'] : null;
                                    $min_per_entry = ($selectedProductRow['min_per_entry'] > 0) ? $selectedProductRow['min_per_entry'] : null;
                                    $max_per_entry = ($selectedProductRow['max_per_entry'] > 0) ? $selectedProductRow['max_per_entry'] : null;
                                }
                            }
                            // balance to use for validating withdraw
                            $operationalBalance = ($product_id) ? $productbalance : $acc_balance;

                            # amount isn't below min per transaction
                            if ($min_per_entry && ($amount < $min_per_entry)) {
                                $data['inputerror'][] = 'amount';
                                $data['error_string'][] = 'Minimum amount per transaction is ' . number_format($min_per_entry, 2) . '!';
                                $data['status'] = FALSE;
                            }
                            # amount isn'max_per_entry above max per transaction
                            if ($min_per_entry && ($amount > $max_per_entry)) {
                                $data['inputerror'][] = 'amount';
                                $data['error_string'][] = 'Maximum amount per transaction is ' . number_format($max_per_entry, 2) . '!';
                                $data['status'] = FALSE;
                            }

                            if ($clientInfo && $transaction_typeRow) {
                                # get withdraw charges particular
                                $withdrawChargeParticular = $this->particular->where(['account_typeId' => 20])->first();
                                if ($withdrawChargeParticular) {
                                    # check the withdrawal charges based on the amount
                                    $charge = $this->vLookUpWithdrawalsCharge($withdrawChargeParticular['id'], $amount);
                                } else {
                                    $charge = null;
                                }
                                if ($charge) {
                                    # withdraw beyond account balance
                                    if ((strtolower($transaction_typeRow['part']) == 'debit') && ($charge['totalAmount'] > $operationalBalance)) {
                                        $data['inputerror'][] = 'amount';
                                        $data['error_string'][] = $transaction_typeRow['type'] . ' amount with ' . number_format($charge, 2) . ' charges exceeds account balance of ' . number_format($operationalBalance, 2) . '!';
                                        $data['status'] = FALSE;
                                    }
                                    # ensure client balance doesn't go below min set on withdraw
                                    if ((strtolower($transaction_typeRow['part']) == 'debit') && $min_account_balance && (($operationalBalance - $charge['totalAmount']) < $min_account_balance)) {
                                        $recommendDr = ($operationalBalance - ($charge['chargeAmount'] + $min_account_balance));

                                        $data['inputerror'][] = 'amount';
                                        $data['error_string'][] = 'Account Balance can not go below min balance of ' . number_format($min_account_balance, 2) . '! Recommended ' . $transaction_typeRow['type'] . ' is ' . number_format($recommendDr, 2) . '!';
                                        $data['status'] = FALSE;
                                    }
                                    # ensure client balance doesn't go above max set on withdraw
                                    if ((strtolower($transaction_typeRow['part']) == 'credit') && $max_account_balance && (($operationalBalance + $amount) > $max_account_balance)) {
                                        $recommendCr = ($max_account_balance -  $operationalBalance);

                                        $data['inputerror'][] = 'amount';
                                        $data['error_string'][] = 'Account Balance can not go above max balance of ' . number_format($max_account_balance, 2) . '! Recommended ' . $transaction_typeRow['type'] . ' is ' . number_format($recommendCr, 2) . '!';
                                        $data['status'] = FALSE;
                                    }
                                } else {
                                    # code...
                                    if ((strtolower($transaction_typeRow['part']) == 'debit') && ($amount > $operationalBalance)) {
                                        $data['inputerror'][] = 'amount';
                                        $data['error_string'][] = $transaction_typeRow['type'] . ' amount exceeds account balance of ' . number_format($operationalBalance, 2) . '!';
                                        $data['status'] = FALSE;
                                    }
                                }
                            }
                            break;
                        case 18: # Revenue from Applications
                            if ($this->request->getVar('application_id') == '') {
                                $data['inputerror'][] = 'application_id';
                                $data['error_string'][] = 'Application ID is required';
                                $data['status'] = FALSE;
                            }
                            if ($this->request->getVar('client_id') == '') {
                                $data['inputerror'][] = 'client_id';
                                $data['error_string'][] = 'Client is required';
                                $data['status'] = FALSE;
                            }
                            if (!empty($application_id)) {
                                # loan application information
                                $applicationInfo = $this->loanApplication->find($application_id);
                                if ($applicationInfo) {
                                    $principal = $applicationInfo['principal'];
                                    $applicantCharges = unserialize($applicationInfo['overall_charges']);
                                    # Get Application charges
                                    $particularCharge = $this->report->loanProductCharge($applicantCharges, $principal, $particular_id);
                                    $charge = $particularCharge['totalCharge'];
                                    /*
                                    if (strtolower($particularInfo['charge_method']) == 'amount') {
                                        $charge = ($particularInfo['charge']);
                                    } else {
                                        $charge = ($particularInfo['charge'] / 100) * $applicationInfo['principal'];
                                    }
                                    */
                                    // get all payments made for this application
                                    $applicationPayments = $this->entry->where(['particular_id' => $particular_id, 'application_id' => $application_id])->findAll();
                                    if (count($applicationPayments) > 0) { // if any payment is made
                                        $entryTotal = 0;
                                        foreach ($applicationPayments as $payment) {
                                            $entryTotal += $payment['amount'];
                                        }
                                        # check whether the particular charge is fully paid
                                        if ($entryTotal == $charge) {
                                            $data['inputerror'][] = 'amount';
                                            $data['error_string'][] = $particularInfo['particular_name'] . ' of ' . $particularInfo['charge'] . ' is fully paid!';
                                            $data['status'] = FALSE;
                                        } else {
                                            # calculation of the previous balance
                                            $previous_balance = $charge - $entryTotal;
                                            # calculation of the new balance
                                            $new_amount = $entryTotal + $this->removeCommasFromAmount($this->request->getVar('amount'));
                                            # check whether there was overpayment
                                            if (($previous_balance > 0) && ($new_amount > $charge)) {
                                                $data['inputerror'][] = 'amount';
                                                $data['error_string'][] = 'Overpayment, Charge balance is ' . $previous_balance . '!';
                                                $data['status'] = FALSE;
                                            }
                                        }
                                    } else {
                                        # check whether there was overpayment
                                        if ($amount > $charge) {
                                            $data['inputerror'][] = 'amount';
                                            $data['error_string'][] = 'Amount exceeds the total charge of ' . $charge . '!';
                                            $data['status'] = FALSE;
                                        }
                                    }
                                } else {
                                    $data['inputerror'][] = 'application_id';
                                    $data['error_string'][] = 'Application not found';
                                    $data['status'] = FALSE;
                                }
                            }
                            break;
                        case 24: // validate membership payment transaction
                            if (isset($particularInfo['charged']) && ($particularInfo['charged'] == "Yes") && !empty($this->request->getVar('client_id'))) {
                                // get particular charge
                                if (strtolower($particularInfo['charge_method']) == 'amount') {
                                    $charge = ($particularInfo['charge']);
                                }
                                $chargeRow = $this->vlookupCharge($particular_id, $clientInfo['reg_date']);
                                $charge = $chargeRow['charge'];
                                // calculate the charge
                                if ($transaction_typeRow) {
                                    $totalAmountPaid = $this->entry->sum_client_amountPaid($this->request->getVar('client_id'), $particular_id, $transaction_typeRow['part']);
                                } else {
                                    $totalAmountPaid = null;
                                }

                                if (strtolower($particularInfo['charge_frequency']) == 'one-time') {
                                    if ($totalAmountPaid && ($totalAmountPaid == $charge)) {
                                        $data['inputerror'][] = 'amount';
                                        $data['error_string'][] = $particularInfo['particular_name'] . ' of ' . $charge . ' paid in full!';
                                        $data['status'] = FALSE;
                                    }
                                }
                                /*
                                else {
                                    // check if client has made any entries for the selected particular
                                    $clientParticularEntries = $this->entry->where(['particular_id' => $particular_id, 'client_id' => $client_id])->findAll();
                                    if ($clientParticularEntries) {
                                        // get the last entry the client made
                                        $lastEntry = end($clientParticularEntries);
                                        // check how many days since the payment was done
                                        $lastPaymentDate = new Time($lastEntry['date']);
                                        $daysPast = (int)(Time::today()->diff($lastPaymentDate)->format('%a'));

                                        // if fully paid for a period within the grace period
                                        if (($totalAmountPaid == $charge) && ($daysPast < $particularInfo['grace_period'])) {
                                            $data['inputerror'][] = 'amount';
                                            $data['error_string'][] = $particularInfo['particular_name'] . ' already paid in full!';
                                            $data['status'] = FALSE;
                                        }
                                    }
                                }
                                */
                            }
                            break;

                        default: // validate other client particular payments
                            if (isset($particularInfo['charged']) && ($particularInfo['charged'] == "Yes") && !empty($this->request->getVar('client_id'))) {
                                $client_id = $this->request->getVar('client_id');
                                $totalAmount = $this->request->getVar('particular_charge');
                                // if ((float)$amount < (float)$totalAmount) {
                                //     $data['inputerror'][] = 'amount';
                                //     $data['error_string'][] = $particularInfo['particular_name'] . ' should be paid in full!';
                                //     $data['status'] = FALSE;
                                // }
                                if ((float)$amount > (float)$totalAmount) {
                                    $data['inputerror'][] = 'amount';
                                    $data['error_string'][] = 'Amount exceeds the accepted charge of ' . $particularInfo['charge'];
                                    $data['status'] = FALSE;
                                }
                                /**
                                 *  get all payments made for this particular
                                 * order payment in descending order of date
                                 * limit only one[latest]
                                 * check if particular has been paid for
                                 */
                                $paymentsMade = $this->entry->where(['particular_id' => $particular_id, 'client_id' => $client_id])->orderBy('date', 'DESC')->findAll(1);
                                if ($paymentsMade) {
                                    if (strtolower($particularInfo['charge_frequency']) == 'one-time') {
                                        $data['inputerror'][] = 'particular_id';
                                        $data['error_string'][] = $particularInfo['particular_name'] . ' is already paid for';
                                        $data['status'] = FALSE;
                                    }
                                }
                            }
                            break;
                    }
                }
            }
        }

        // validate transaction amount
        if ($this->removeCommasFromAmount($this->request->getVar('amount')) == '') {
            $data['inputerror'][] = 'amount';
            $data['error_string'][] = 'Amount is required';
            $data['status'] = FALSE;
        }
        // validate transaction amount
        if (!empty($this->removeCommasFromAmount($this->request->getVar('amount')))) {
            if ((float)$amount < 100) {
                $data['inputerror'][] = 'amount';
                $data['error_string'][] = 'Amount is below minimum of 100!';
                $data['status'] = FALSE;
            }
            if (!preg_match("/^[0-9. ']*$/", $amount)) {
                $data['inputerror'][] = 'amount';
                $data['error_string'][] = 'Illegal character. Only numbers allowed!';
                $data['status'] = FALSE;
            }
            // validate that transfer amount should not exceed particular balance
            if ((strtolower($entry_menu) == "transfer") && ($amount > $particularBalance)) {
                $data['inputerror'][] = 'amount';
                $data['error_string'][] = 'Can not transfer more than Particular balance of ' . $particularBalance . '!';
                $data['status'] = FALSE;
            }
        }
        // validate transacting staff
        if ($this->request->getVar('staff_id') == '') {
            $data['inputerror'][] = 'staff_id';
            $data['error_string'][] = 'Staff is required';
            $data['status'] = FALSE;
        }
        // validate transaction details
        if ($this->request->getVar('entry_details') == '') {
            $data['inputerror'][] = 'entry_details';
            $data['error_string'][] = 'Transaction Details are required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('entry_details'))) {
            if (strlen($this->request->getVar('entry_details')) < 4) {
                $data['inputerror'][] = 'entry_details';
                $data['error_string'][] = 'Minimum length should be 4[' . strlen($this->request->getVar('entry_details')) . ']';
                $data['status'] = FALSE;
            }
        }
        // validate transaction remarks
        if (!empty($this->request->getVar('remarks'))) {
            if (strlen($this->request->getVar('remarks')) < 4) {
                $data['inputerror'][] = 'remarks';
                $data['error_string'][] = 'Minimum length should be 4[' . strlen($this->request->getVar('remarks')) . ']';
                $data['status'] = FALSE;
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
