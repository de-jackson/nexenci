<?php

namespace App\Controllers\Client;

use App\Controllers\Client\MainController;

use \Hermawan\DataTables\DataTable;
use CodeIgniter\I18n\Time;

class Transactions extends MainController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Transactions';

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
    public function module($type)
    {
        switch (strtolower($type)) {
            case 'deposits':
                $this->menu = ucfirst('savings');
                $part = 'credit';
                $view = 'client/transactions/savings';
                break;
            case 'withdraws':
                $this->menu = ucfirst('savings');
                $part = 'debit';
                $view = 'client/transactions/savings';
                break;
            case 'repayments':
                $this->menu = ucfirst('loans');
                $part = 'credit';
                $view = 'client/transactions/financial/repayments';
                break;
            case 'applicationcharges':
                $this->menu = ucfirst('loans');
                $part = 'credit';
                $view = 'client/transactions/financial/applications';
                break;
            default:
                session()->setFlashdata('failed', ucwords($type) . ' Page requested can not be found!');
                return redirect()->to(base_url('client/dashboard'));
                break;
        }
        // $view = 'client/transactions/account';
        $this->title = ucfirst($type);
        $this->menuItem = [
            'menu' => $this->menu,
            'title' => $this->title,
        ];

        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            return view($view, [
                'title' => $this->title,
                'menu' => $this->menu,
                'type' => $type,
                'part' => $part,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                // 'applicationsCounter' => $this->getTableCounts('applications'),
                // 'disbursementsCounter' => $this->getTableCounts('disbursements'),

            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('client/dashboard'));
        }
    }

    public function client_transactions()
    {
        $where = ['entries.client_id' => $this->userRow['id'], 'entries.deleted_at' => Null];

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

    public function transactions_list($module, $part = null)
    {
        // all transactions list
        switch (strtolower($module)) {
            case "savings": // savings transactions list
                $this->menu = 'savings';
                if (isset($part) && strtolower($part) == 'credit') {
                    $this->title = ucwords('Deposits');
                    // Credit condition: status = 'credit' and account_typeId = 12
                    $where = [
                        'entries.client_id' => $this->userRow['id'],
                        'entries.status' => 'credit',
                        'entries.account_typeId' => 12,
                        'entries.deleted_at' => null
                    ];
                } elseif (isset($part) && strtolower($part) == 'debit') {
                    $this->title = ucwords('Withdraws');
                    // Debit condition: 
                    // status = 'credit' and account_typeId = 20 
                    // OR status = 'debit' and account_typeId = 12
                    $client_id = $this->userRow['id'];
                    $where = "(entries.client_id = $client_id) AND ((entries.status = 'debit' AND entries.account_typeId = 12) OR (entries.status = 'credit' AND entries.account_typeId = 20))";
                } else {
                    // Default condition: only consider non-deleted entries
                    $where = ['entries.client_id' => $this->userRow['id'], 'entries.deleted_at' => null];
                }
                # 12: Savings
                # 20: Revenue from Deposit & Withdraw
                # 24: Membership
                $account_typeId = ["12", "20"];
                $transactions = $this->entry
                    ->select('particulars.particular_name, payments.particular_name as payment_method, account_types.code, entrytypes.type, clients.name,, clients.title, clients.account_no, clients.photo, clients.reg_date, staffs.staff_name, entries.id, entries.date, entries.amount, entries.ref_id, entries.account_typeId, entries.status, entries.entry_menu, entries.created_at')
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
                    ->where(['entries.client_id' => $this->userRow['id'], 'entries.account_typeId' => 3, 'entries.status' => 'credit', 'entries.deleted_at' => null])->groupBy('entries.ref_id')->orderBy('entries.date', 'desc');
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
                    ->where(['entries.client_id' => $this->userRow['id'], 'entries.account_typeId' => 24, 'entries.deleted_at' => null])->orderBy('entries.date', 'desc');
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
                    ->where(['entries.client_id' => $this->userRow['id'], 'entries.account_typeId' => 18, 'entries.deleted_at' => null])->orderBy('entries.date', 'desc');
                break;
            case "shares": // shares transactions list
                $this->menu = 'loans';
                if (isset($part) && strtolower($part) == 'credit') {
                    $this->title = ucwords('purchases');
                } elseif (isset($part) && strtolower($part) == 'debit') {
                    $this->title = ucwords('withdrawals');
                }
                $where = isset($part) ? ['entries.client_id' => $this->userRow['id'], 'entries.account_typeId' => 8, 'entries.status' => strtolower($part), 'entries.deleted_at' => null] : ['entries.client_id' => $this->userRow['id'], 'entries.account_typeId' => 8, 'entries.deleted_at' => null];
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
                    ->where(['entries.client_id' => $this->userRow['id'], 'entries.deleted_at' => null])->orderBy('entries.date', 'desc');
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
                if ($entry->account_typeId == 8) {
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

    protected function particularDataRow($id)
    {
        $row = $this->particular
            ->select('particulars.*, categories.id as c_id, categories.category_name,categories.category_slug, categories.part, subcategories.id as s_id, subcategories.subcategory_name, subcategories.subcategory_status, subcategories.subcategory_slug, subcategories.created_at as screated, subcategories.updated_at as supdated, statements.name as statement, account_types.id as type_id ,account_types.name as account_type, cash_flow_types.id as cash_flow_id, cash_flow_types.name as cash_flow_type')
            ->join('categories', 'categories.id = particulars.category_id', 'left')
            ->join('subcategories', 'subcategories.id = particulars.subcategory_id', 'left')
            ->join('statements', 'statements.id = categories.statement_id', 'left')
            ->join('account_types', 'account_types.id = particulars.account_typeId', 'left')
            ->join('cash_flow_types', 'cash_flow_types.id = particulars.cash_flow_typeId', 'left')
            ->find($id);
        return $row;
    }

    protected function computeAccountLedgerBalance($particular_id, $amount, $status, $entryId = null)
    {
        $accountingBalance = $balance = $debitTotal = $creditTotal = 0;
        // get all particular entries
        if (!$entryId) { // for new transaction entry
            $allTransactions = $this->entry->where(['particular_id' => $particular_id])->findAll();
        } else { // for updated transaction entry
            $transactionRow = $this->entry->find($entryId);
            $allTransactions = $this->entry->where(['particular_id' => $particular_id, 'created_at <=' => $transactionRow['created_at']])->findAll();
        }
        // sum all entries where status is debit|| credit
        if (count($allTransactions) > 0) {
            foreach ($allTransactions as $entry) {
                if (strtolower($entry['status']) == 'debit') {
                    $debitTotal += $entry['amount'];
                } else {
                    $creditTotal += $entry['amount'];
                }
            }
            // get the balance of all entries
            $balance = ($debitTotal - $creditTotal);
        }
        $particularInfo = $this->particularDataRow($particular_id);
        // add amount to balance
        if (strtolower($particularInfo['part']) == 'debit') {
            if (strtolower($status) == 'debit') {
                $accountingBalance = ($balance + $amount);
            } else {
                $accountingBalance = ($balance - $amount);
            }
        }
        if (strtolower($particularInfo['part']) == 'credit') {
            $balance = abs($balance); // remove negative for credited particulars
            if (strtolower($status) == 'debit') {
                $accountingBalance = ($balance - $amount);
            } else {
                $accountingBalance = ($balance + $amount);
            }
        }
        return $accountingBalance;
    }

    public function clientSavingsAccount($data)
    {
        $client_id = $data['client_id'];
        $clientInfo = $this->client->find($client_id);
        $particular_id = trim($data['particular_id']);
        $payment_id = trim($data['payment_id']);
        $entry_typeId = trim($data['entry_typeId']);

        $account_typeId = 12;
        $amount = str_replace(',', '', trim($data['amount']));
        $xentry_menu = trim($data['entry_menu']);
        $ref_id = $data['ref_id'];

        // get transaction type data
        $transaction_typeRow = $this->entryType->find($entry_typeId);
        if ($transaction_typeRow) {
            // entry_menu of transaction based on entry type
            $entry_menu = $transaction_typeRow['entry_menu'];
            // status of transaction based on entry type
            $status = $transaction_typeRow['part'];
            // validate if client still exists
            if ($clientInfo) {
                // calculate selected particular balance as of this transaction
                $accountingBalance = (float)$this->computeAccountLedgerBalance($particular_id, $amount, $status);
                // get particular data
                $particularRow = $this->particularDataRow($particular_id);
                // get payment method data
                $paymentRow = $this->particularDataRow($payment_id);
                // check existence of accounting particulars
                if ($particularRow && $paymentRow) {
                    // validate if entry\transaction type still exists
                    if ($transaction_typeRow) {
                        /** 
                         * update client balance and perform double entry
                         * since account type is for Liability category,
                         * credit particular & debit payment method if its gaining,
                         * debit particular & credit payment method if its loosing,
                         */
                        if (strtolower($status) == 'credit') {
                            $balance = (float)($clientInfo['account_balance'] + $amount);
                            $clientBalance = ['account_balance' => $balance];
                            // credit client savings particular[liability]
                            $savingParticularBal = ['credit' => $particularRow['credit'] + $amount];
                            // debit selected payment method[assets]
                            $paymentParticularBal = ['debit' => $paymentRow['debit'] + $amount];
                        }
                        if (strtolower($status) == 'debit') {
                            $balance = (float)($clientInfo['account_balance'] - $amount);
                            $clientBalance = ['account_balance' => $balance];
                            // debit client savings particular[liability]
                            $savingParticularBal = ['debit' => $particularRow['debit'] + $amount];
                            // credit selected payment method[assets]
                            $paymentParticularBal = ['credit' => $paymentRow['credit'] + $amount];
                        }

                        // transaction\entry data
                        $transactionData = [
                            'date' => ($data['date']) ? $data['date'] : date('Y-m-d'),
                            'payment_id' => $payment_id,
                            'particular_id' => $particular_id,
                            'branch_id' => $this->userRow['branch_id'],
                            'staff_id' => $this->userRow['staff_id'],
                            'client_id' => $client_id,
                            'entry_menu' => $entry_menu,
                            'entry_typeId' => $entry_typeId,
                            'account_typeId' => $account_typeId,
                            'ref_id' => $ref_id,
                            'amount' => $amount,
                            'status' => $status,
                            'balance' => $accountingBalance,
                            'contact' => trim($data['contact']),
                            'entry_details' => trim($data['entry_details']),
                            # 'remarks' => trim($this->request->getVar('remarks')),
                        ];
                        // save transaction
                        $saveTransaction = $this->entry->insert($transactionData);
                        if ($saveTransaction) {
                            // update client account balance
                            $updateClientAccount = $this->client->update($client_id, $clientBalance);
                            if ($updateClientAccount) {
                                // update accounting particulars balances
                                $particular_idBal = $this->particular->update($particular_id, $savingParticularBal);
                                $payment_idBal = $this->particular->update($payment_id, $paymentParticularBal);
                                if ($particular_idBal && $payment_idBal) {
                                    // add transaction into the activity log
                                    $activityData = [
                                        'client_id' => $this->userRow['id'],
                                        'action' => 'create',
                                        'description' => ucfirst('New ' . $entry_menu . ' ' . $transaction_typeRow['type'] .  $this->title . ' for ' . $particularRow['particular_name'] . ' Ref ID ' . $ref_id),
                                        'module' => $this->menu,
                                        'referrer_id' => $ref_id,
                                    ];
                                    $activity = $this->insertActivityLog($activityData);
                                    if ($activity) {
                                        // send transaction notification email to client
                                        if ($clientInfo['email'] != '') {
                                            $clientInfo['branch_name'] = $this->userRow['branch_name'];
                                            $clientInfo['amount'] = $amount;
                                            $clientInfo['balance'] = $balance;
                                            $clientInfo['ref_id'] = $ref_id;
                                            $clientInfo['date'] = date('d-m-Y H:i:s');
                                            $clientInfo['entry_details'] = trim($this->request->getVar('entry_details'));
                                            $clientInfo['account_typeID'] = $account_typeId;
                                            $clientInfo['type'] = $transaction_typeRow['type'];
                                            $clientInfo['particular_name'] = $particularRow['particular_name'];
                                            $clientInfo['payment_mode'] = $paymentRow['particular_name'];
                                            // email message
                                            $message = $clientInfo;
                                            $checkInternet = $this->settings->checkNetworkConnection();
                                            if ($checkInternet) {
                                                $subject = 'New ' . $particularRow['particular_name'] . ' Transaction';
                                                $message = $message;
                                                $token = 'transaction';
                                                $this->settings->sendMail($message, $subject, $token);
                                                $response = [
                                                    'status' => 200,
                                                    'error' => null,
                                                    'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. Email Sent'
                                                ];
                                                return $this->respond($response);
                                                exit;
                                            } else {
                                                $response = [
                                                    'status' => 200,
                                                    'error' => null,
                                                    'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. No Internet'
                                                ];
                                                return $this->respond($response);
                                                exit;
                                            }
                                        } else {
                                            $response = [
                                                'status' => 200,
                                                'error' => null,
                                                'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully.',
                                            ];
                                            return $this->respond($response);
                                            exit;
                                        }
                                    } else {
                                        $response = [
                                            'status'   => 200,
                                            'error'    => null,
                                            'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. loggingFailed'
                                        ];
                                        return $this->respond($response);
                                        exit;
                                    }
                                } else {
                                    $response = [
                                        'status'   => 500,
                                        'error'    => 'Accounting Error',
                                        'messages' => 'Implementing Double Entry failed, Client Balance updated!',
                                    ];
                                    return $this->respond($response);
                                    exit;
                                }
                            } else {
                                $response = [
                                    'status' => 500,
                                    'error' => 'Balance Update Failed',
                                    'messages' => 'Updating Client account balance Failed, Transaction recorded successfully!',
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
                            'messages' => 'Transaction type data could not be found!',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status'   => 404,
                        'error'    => 'Not Found',
                        'messages' => 'Particular or Payment method data could not be found!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'Client Data could not be found!',
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
    }

    public function payment()
    {
        $tx_ref = $this->request->getVar('tx_ref');
        $status = $this->request->getVar('status');
        $transaction_id = $this->request->getVar('transaction_id');

        $client_id = trim($this->request->getVar('clientid'));
        $particular_id = trim($this->request->getVar('particularid'));
        $payment_id = trim($this->request->getVar('paymentid'));
        $entry_typeId = trim($this->request->getVar('entrytypeId'));
        $account_typeId = 12;
        $amount = str_replace(',', '', trim($this->request->getVar('amount')));
        $entry_menu = trim($this->request->getVar('entrymenu'));
        $date = trim($this->request->getVar('entrydate'));
        $contact = trim($this->request->getVar('contact'));
        $entry_details = trim($this->request->getVar('entrydetails'));

        # check whether the transaction status exist
        if (isset($status)) {
            # check the payment status
            if ($status === "successful") {
                # update subscription
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
                    'ref_id' => $tx_ref,
                    'amount' => $amount,
                    'status' => $status,
                    'transaction_id' => $transaction_id,
                    'contact' => trim($contact),
                    'entry_details' => trim($entry_details),
                    # 'remarks' => trim($this->request->getVar('remarks')),
                ];

                # Perform account update
                $this->clientSavingsAccount($transactionData);
                session()->setFlashdata('success', '
                <i class="fa fa-check"></i> Success. Your payment was Successful');
                # Redirect the user to the transaction page
                return redirect()->to(base_url('client/transactions/type/' . strtolower($entry_menu)));
            } else {
                # redirect the user to subscription
                session()->setFlashdata('error', '
                <i class="fa fa-exclamation-triangle"></i> Cancelled. You have cancelled your payment!');
                # Redirect the user to the transaction page
                return redirect()->to(base_url('client/transactions/type/' . strtolower($entry_menu)));
            }
        } else {
            # redirect the user to subscription
            session()->setFlashdata('error', '<i class="fa fa-exclamation-triangle"></i> External Error. You have cancelled your payment!');
            return redirect()->to(base_url('client/transactions/type/deposit'));
        }
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
        }

        // Execute the query
        $data = $query->find($id);

        return $this->respond($data);
    }

    public function createOld()
    {
        $this->validateTransaction("transactions");

        $client_id = trim($this->request->getVar('client_id'));
        $particular_id = trim($this->request->getVar('particular_id'));
        $payment_id = trim($this->request->getVar('payment_id'));
        $entry_typeId = trim($this->request->getVar('entry_typeId'));
        $account_typeId = trim($this->request->getVar('account_typeId'));
        $amount = str_replace(',', '', trim($this->request->getVar('amount')));
        $entry_menu = trim($this->request->getVar('entry_menu'));
        $date = trim($this->request->getVar('date'));
        $contact = trim($this->request->getVar('contact_full'));
        $entry_details = trim($this->request->getVar('entry_details'));
        $remarks = trim($this->request->getVar('remarks'));
        $ref_id = $this->settings->generateReference();
        $token = $this->settings->generateToken();

        $redirect_url = base_url('client/transactions/payment?entrydetails=' . $entry_details . '&account=' . $token . '&entrymenu=' . $entry_menu . '&entrydate=' . $date . '&clientid=' . $client_id . '&particularid=' . $particular_id . '&paymentid=' . $payment_id . '&entrytypeId=' . $entry_typeId . '&amount=' . $amount . '&contact=' . $contact);

        $response = [
            'status' => 200,
            'error' => null,
            'client' => $this->userRow,
            'account' => [
                'tx_ref' => $ref_id,
                'date' => $date,
                'amount' => $amount,
                'currency' => 'UGX',
                'redirect_url' => $redirect_url,
            ],
            'entry' => [
                'particular_id' => $particular_id,
                'payment_id' => $payment_id,
                'entry_typeId' => $entry_typeId,
                'account_typeId' => $account_typeId,
                'entry_menu' => $entry_menu,
                'client_id' => $client_id
            ]
        ];

        # Enable this for Flutter online payment
        return $this->respond($response);
        # Disable this to enable flutter way
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
            'ref_id' => $ref_id,
            'amount' => $amount,
            'status' => 'successful',
            // 'transaction_id' => $transaction_id,
            'contact' => trim($contact),
            'entry_details' => trim($entry_details),
            'remarks' => trim($this->request->getVar('remarks')),
        ];

        # Perform account update
        $this->clientSavingsAccount($transactionData);

        return $this->respond($response);
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

        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'create')) {
            // $this->_validateTransactions('add');
            $this->validateTransaction("transactions");

            $account_typeId = trim($this->request->getVar('account_typeId'));
            // $amount = str_replace(',', '', trim($this->request->getVar('amount')));
            $amount = $this->removeCommasFromAmount($this->request->getVar('amount'));
            $entry_menu = trim($this->request->getVar('entry_menu'));
            $refId = $this->settings->generateReference();
            /**
             * NOTE: The following are the guide lines for inserting entries to the db & Double Entry
             * 1. particular_id is the ID of the primary particular of the entry/transaction
             * 2. payment_id is the ID of the secondary particular of the entry/transaction
             * 3. status refers to the status of the transaction to the primary particular of the entry/transaction
             * 4. balance refers to the balance of the primary particular as of this new entry
             */

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

    // functionality for creating shares transactions
    private function add_sharesTransaction($refId, $account_typeInfo, $sharesAmount)
    {
        $account_typeId = $account_typeInfo['id'];
        $client_id = $this->userRow['id'];
        $clientInfo = $this->userRow;
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
        // get payment method data
        $paymentRow = $this->particularDataRow($payment_id);
        // check existence of payment particular
        if (!$paymentRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Payment data could not be found!',
            ];
            return $this->respond($response);
            exit;
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
            'ref_id' => $refId,
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
                        $applicationInfo['branch_name'] = $this->userRow['branch_name'];
                        $transactionInfo['amount'] = $amount;
                        $transactionInfo['charge'] = $chargeAmt;
                        // $applicationInfo['totalCollected'] = $totalAmountPaid;
                        $transactionInfo['ref_id'] = $refId;
                        $transactionInfo['date'] = date('d-m-Y H:i:s');
                        $transactionInfo['entry_details'] = trim($this->request->getVar('entry_details'));
                        $transactionInfo['account_typeID'] = $account_typeId;
                        $transactionInfo['type'] = $transaction_typeRow['type'];
                        $transactionInfo['particular_name'] = $particularRow['particular_name'];
                        $transactionInfo['payment_mode'] = $paymentRow['particular_name'];
                        $message = $transactionInfo;
                        # check the email existence and email notify is enabled
                        if (!empty($clientInfo['email']) && $this->settingsRow['email']) {
                            $subject = 'New ' . $particularRow['particular_name'] . ' Transaction';
                            $message = $message;
                            $token = 'transaction';
                            $this->settings->sendMail($message, $subject, $token);
                            $txt .= 'Email Sent';
                        }
                        # check the mobile existence and sms notify is enabled
                        if (!empty($clientInfo['mobile']) && $this->settingsRow['sms']) {
                            # send sms
                            $sms = $this->sendSMS([
                                'mobile' => trim($clientInfo['mobile']),
                                'text' => 'A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . number_format($amount) . ' via ' . strtolower($particularRow['particular_name']) . ' has been received by ' . strtolower($this->settingsRow['business_name']) . ' on ' . $date . ' . ID: ' . $refId . ' ~ ' . strtoupper($this->settingsRow["system_abbr"])
                            ]);
                            $txt .= ' SMS Sent';
                        }
                    }
                    $response = [
                        'status' => 200,
                        'error' => null,
                        'messages' => ucwords($this->menu) . ' ' . $this->title . ' transaction created successfully.' . $txt,
                    ];
                    return $this->respond($response);
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => ucwords($this->menu) . ' ' . $this->title . ' transaction created successfully. loggingFailed'
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

            $savingsProducts = (($clientInfo['savingsProducts']) ? $clientInfo['savingsProducts'] : null);
            if ($savingsProducts) {
                foreach ($savingsProducts as $key => &$product) {
                    if ($product_id == $product['product_id']) {
                        // validate if amount to be saved corresponds with product settings
                        $productData = [
                            'id' => $product_id,
                            'product_balance' => $product['product_balance'],
                            'status' => $status,
                            'amount' => $amountToBeSaved,
                            'amount' => $amountToBeSaved,
                        ];

                        // Compute & Update product_balance
                        $product['product_balance'] = $this->entry->sum_client_amountPaid($client_id, $particular_id, $status, 'savings-product', $product['product_id']);
                        break;
                    }
                }
            }
        }

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
            'ref_id' => $ref_id,
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
                    'messages' => ucwords($this->menu) . ' ' . $this->title . ' successful. Pending Membership Charges are greated than transaction amount!'
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
                'ref_id' => $ref_id,
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
        // $newAccountBalance = (strtolower($status) == 'credit') ? (float)($clientInfo['account_balance'] + $amountToBeSaved) : (float)($clientInfo['account_balance'] - $amountToBeSaved);
        if ($savingsProducts) {
            foreach ($savingsProducts as $key => &$product) {
                if ($product_id == $product['product_id']) {
                    // Compute & Update product_balance
                    $product['product_balance'] = $this->entry->sum_client_amountPaid($client_id, $particular_id, $status, 'savings-product', $product['product_id']);
                    break;
                }
            }
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
            $clientInfo['branch_name'] = $this->userRow['branch_name'];
            $clientInfo['amount'] = $amount;
            $clientInfo['balance'] = $accountBalance['accountBalance'];
            $clientInfo['ref_id'] = $ref_id;
            $clientInfo['date'] = date('d-m-Y H:i:s');
            $clientInfo['entry_details'] = trim($this->request->getVar('entry_details'));
            $clientInfo['account_typeID'] = $account_typeId;
            $clientInfo['type'] = $transaction_typeRow['type'];
            $clientInfo['particular_name'] = $particularRow['particular_name'];
            $clientInfo['payment_mode'] = $paymentRow['particular_name'];
            $message = $clientInfo;
            # Check the client email existence
            if (!empty($clientInfo['email']) && $this->settingsRow['email']) {
                $subject = 'New ' . $particularRow['particular_name'] . ' Transaction';
                $message = $message;
                $token = 'transaction';
                # Send transaction notification email to client
                $this->settings->sendMail($message, $subject, $token);
            }

            # Check the client mobile existence
            if (!empty($clientInfo['mobile']) && $this->settingsRow['sms']) {
                $lastName = $this->splitName($clientInfo['name']);
                # check whether transaction type is for withdraw
                if (strtolower($type) == 'withdraw') {
                    $text = 'Dear ' . $lastName . ', A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . number_format($amount) . ' via ' . $paymentRow['particular_name'] . ' has been withdrawn from your savings account' . ' on ' . $date . '. ID: ' . $ref_id . ' ~ ' . strtoupper($this->settingsRow["system_abbr"]);
                } else {
                    # set sms text message fo deposit
                    $text1 = 'A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . $amount . ' via ' . $paymentRow['particular_name'] . ' has been received by ' . strtolower($this->settingsRow['business_name']) . ' on ' . $date . '. ID: ' . $ref_id . ' New balance: ' . $accountBalance['accountBalance'] . ' ~ ' . strtoupper($this->settingsRow["system_abbr"]);
                    $text = 'Dear ' . $lastName . ', Your savings of ' . $this->settingsRow["currency"] . ' ' . number_format($amount) . ' has been received on ' . $date . '. ID: ' . $ref_id . '. Thank you for saving with ' . strtoupper($this->settingsRow["system_abbr"]);
                }


                # send sms
                $sms = $this->sendSMS([
                    'mobile' => trim($clientInfo['mobile']),
                    'text' => $text
                ]);
            }

            $response = [
                'status' => 200,
                'error' => null,
                'messages' => ucwords($this->menu) . ' ' . $this->title . ' transaction created successfully.'
            ];
            return $this->respond($response);
            exit;
        } else {
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => ucwords($this->menu) . ' ' . $this->title . ' transaction created successfully.',
            ];
            return $this->respond($response);
        }
    }

    private function add_disbursementTransaction($ref_id, $account_typeInfo, $amount)
    {
        $principalInstallment = $interestInstallment = 0;
        $interestCollected = $principalCollected = $totalCollected = 0;
        $account_typeId = $account_typeInfo['id'];
        $client_id = trim($this->request->getVar('client_id'));
        $loanParticular_id = trim($this->request->getVar('particular_id'));
        $payment_id = trim($this->request->getVar('payment_id'));
        $entry_typeId = trim($this->request->getVar('entry_typeId'));

        // get loan particular data
        $loanParticularRow = $this->particularDataRow($loanParticular_id);

        $interest_account_typeId = 19; // revenue from loans id

        $interestAccountType = $this->accountType->where(['status' => 'Active'])
            ->find($interest_account_typeId);

        if (!$interestAccountType) {
            # code...
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Account Type: Revenue from Loan Repayments is not found!',
            ];
            return $this->respond($response);
            exit;
        }

        $interestParticular = $this->particular->where([
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
        // get transaction type data
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
        $disbursement_id = trim($this->request->getVar('disbursement_id'));
        $disbursementInfo = $this->loanDisbursementRow($disbursement_id);
        $actualInstallment = $disbursementInfo['actual_installment'];
        if (!$disbursementInfo) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Disbursement could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        $product_id = $disbursementInfo['product_id'];

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
        // get interest particular data
        $interestParticularRow = $this->particularDataRow($interest_particularId);
        // get payment method data
        $paymentRow = $this->particularDataRow($payment_id);
        // check existence of accounting particulars
        if ($loanParticularRow && $paymentRow) {
            // validate if entry\transaction type still exists
            if ($transaction_typeRow) {
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
                    # code...
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
                    $loanParticularBal = ['credit' => ((float)$loanParticularRow['credit'] + $principalRepayment)];
                    // credit\add Interest on Loans particular[revenue]
                    $interestParticularBal = ['credit' => ((float)$interestParticularRow['credit'] + $interestRepayment)];
                    // debit selected payment method[assets]
                    $paymentParticularBal = ['debit' => ((float)$paymentRow['debit'] + $amount)];
                }
                if (strtolower($status) == 'debit') {
                    // debit/add Gross Loans - Principal particular[assets]
                    $loanParticularBal = ['debit' => ((float)$loanParticularRow['debit'] + $amount)];
                    // debit\reduce Interest on Loans particular[revenue]
                    $interestParticularBal = ['debit' => ((float)$interestParticularRow['debit'] + $amount)];
                    // credit selected payment method[assets]
                    $paymentParticularBal = ['credit' => ((float)$paymentRow['credit'] + $amount)];
                }

                if (!empty($this->request->getVar('date'))) {
                    $date = trim($this->request->getVar('date'));
                } else {
                    $date = date('Y-m-d');
                }
                // transaction\entry data
                $transactionData = [
                    # Principal installment transaction data
                    [
                        'date' => $date,
                        'payment_id' => $payment_id,
                        'particular_id' => $loanParticular_id,
                        'branch_id' => $this->userRow['branch_id'],
                        'staff_id' => $this->userRow['staff_id'],
                        'client_id' => $client_id,
                        'product_id' => ($product_id) ? $product_id : null,
                        'disbursement_id' => $disbursement_id,
                        'entry_menu' => $entry_menu,
                        'entry_typeId' => $entry_typeId,
                        'account_typeId' => $account_typeId,
                        'ref_id' => $ref_id,
                        'amount' => $principalRepayment,
                        'status' => $status,
                        'balance' => $principalAccountingBalance,
                        'contact' => trim($this->request->getVar('contact_full')),
                        'entry_details' => trim($this->request->getVar('entry_details')),
                        'remarks' => trim($this->request->getVar('remarks')),
                    ],
                    # Interest installment transaction data
                    [
                        'date' => $date,
                        'payment_id' => $payment_id,
                        'particular_id' => $interest_particularId,
                        'branch_id' => $this->userRow['branch_id'],
                        'staff_id' => $this->userRow['staff_id'],
                        'client_id' => $client_id,
                        'product_id' => ($product_id) ? $product_id : null,
                        'disbursement_id' => $disbursement_id,
                        'entry_menu' => $entry_menu,
                        'entry_typeId' => $entry_typeId,
                        'account_typeId' => $account_typeId,
                        'ref_id' => $ref_id,
                        'amount' => $interestRepayment,
                        'status' => $status,
                        'balance' => $interestAccountingBalance,
                        'contact' => trim($this->request->getVar('contact_full')),
                        'entry_details' => trim($this->request->getVar('entry_details')),
                        'remarks' => trim($this->request->getVar('remarks')),
                    ],
                ];
                # Save Loan Repayment Transactions
                $saveTransaction = $this->entry->insertBatch($transactionData);
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
                        if ($balanceOnRepaymentAmt > 0) {
                            $this->push_excessAmount_to_client_savings(['client_id' => $client_id, 'amount' => $balanceOnRepaymentAmt, 'payment_id' => $payment_id, 'from' => $account_typeInfo['name']]);
                        }
                        # Update the accounting particulars balances
                        $loanParticular_idBal = $this->particular->update($loanParticular_id, $loanParticularBal);
                        $interestParticular_idBal = $this->particular->update($interest_particularId, $interestParticularBal);
                        $payment_idBal = $this->particular->update($payment_id, $paymentParticularBal);
                        if ($loanParticular_idBal && $interestParticular_idBal  && $payment_idBal) {
                            # Save the above transactions into the activity log
                            $activityData = [
                                'user_id' => $this->userRow['id'],
                                'action' => 'create',
                                'description' => ucfirst('New ' . $entry_menu . ' ' . $transaction_typeRow['type'] .  $this->title . ' for ' . $loanParticularRow['particular_name'] . ' Ref ID ' . $ref_id),
                                'module' => $this->menu,
                                'referrer_id' => $ref_id,
                            ];
                            $activity = $this->insertActivityLog($activityData);
                            if ($activity) {
                                $txt = '';
                                # Check the internet connection
                                $checkInternet = $this->settings->checkNetworkConnection();
                                if ($checkInternet) {
                                    $disbursementInfo['branch_name'] = $this->userRow['branch_name'];
                                    $disbursementInfo['amount'] = $amount;
                                    $disbursementInfo['interestCollected'] = $interestCollected;
                                    $disbursementInfo['principalCollected'] = $principalCollected;
                                    $disbursementInfo['totalCollected'] = $totalCollected;
                                    $disbursementInfo['ref_id'] = $ref_id;
                                    $disbursementInfo['date'] = date('d-m-Y H:i:s');
                                    $disbursementInfo['entry_details'] = trim($this->request->getVar('entry_details'));
                                    $disbursementInfo['account_typeID'] = $account_typeId;
                                    $disbursementInfo['type'] = $transaction_typeRow['type'];
                                    $disbursementInfo['particular_name'] = $loanParticularRow['particular_name'];
                                    $disbursementInfo['payment_mode'] = $paymentRow['particular_name'];
                                    $disbursementInfo['repayment_period'] = $period;
                                    # the principal balance
                                    $disbursementInfo['principalBalance'] = $principalBalance;
                                    # get the interest Balance
                                    $disbursementInfo['interestBalance'] = $interestBalance;
                                    # get the total loan outstanding balance
                                    $disbursementInfo['totalLoanBalance'] = $totalLoanBalance;

                                    # email message
                                    $message = $disbursementInfo;
                                    # check the email existence and email notify is enabled
                                    if (!empty($disbursementInfo['email']) && $this->settingsRow['email']) {
                                        $subject = 'New ' . $loanParticularRow['particular_name'] . ' Transaction';
                                        $message = $message;
                                        $token = 'transaction';
                                        $this->settings->sendMail($message, $subject, $token);
                                        $txt .= 'Email Sent';
                                    }
                                    # check the mobile existence and sms notify is enabled
                                    if (!empty($clientInfo['mobile']) && $this->settingsRow['sms']) {
                                        # send sms
                                        $sms = $this->sendSMS([
                                            'mobile' => trim($clientInfo['mobile']),
                                            'text' => 'A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . number_format($amount) . ' via ' . strtolower($paymentRow['particular_name']) . ' has been received by ' . strtolower($this->settingsRow['business_name']) . ' on ' . $date . ' . ID: ' . $ref_id . ' ~ ' . strtoupper($this->settingsRow["system_abbr"])
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
                                    'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. loggingFailed'
                                ];
                                return $this->respond($response);
                                exit;
                            }
                        } else {
                            $response = [
                                'status'   => 500,
                                'error'    => 'Accounting Error',
                                'messages' => 'Implementing Double Entry failed, Client Loan Balance updated!',
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
            } else {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'Transaction type data could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Particular or Payment method data could not be found!',
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
        $client_id = trim($this->request->getVar('client_id'));
        $clientInfo = $this->client->find($client_id);
        $particular_id = trim($this->request->getVar('particular_id'));
        $payment_id = trim($this->request->getVar('payment_id'));
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
        // get particular data
        $particularRow = $this->particularDataRow($particular_id);
        // get payment method data
        $paymentRow = $this->particularDataRow($payment_id);
        // calculate entries total amount per entry status & final balance
        $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $particularRow['part']]);
        // check existence of accounting particulars
        if ($particularRow && $paymentRow) {
            // calculate the charge
            if (strtolower($particularRow['charge_method']) == 'amount') {
                $chargeAmt = ($particularRow['charge']);
            } else {
                $chargeAmt = ($particularRow['charge'] / 100) * $applicationInfo['principal'];
            }
            // get all payments made for this application
            // $applicationPayments = $this->entry->where(
            //     ['particular_id' => $particular_id, 'application_id' => $application_id]
            // )->findAll();
            // if (count($applicationPayments) > 0) { // if any payment is made
            //     $entryTotal = 0;
            //     foreach ($applicationPayments as $payment) {
            //         $entryTotal += $payment['amount'];
            //     }
            //     $totalCollected =  ($entryTotal + $amount);
            // } else {
            //     $totalCollected = $amount;
            // }
            // validate if entry\transaction type still exists
            if ($transaction_typeRow) {

                # Check if The Client Has Fully Paid or is Paying Excess Amounts
                $totalAmountPaid = $this->entry->sum_client_amountPaid($client_id, $particular_id, $status, 'application', $application_id);
                // echo json_encode($totalAmountPaid); exit;
                /** Check for Excess Payments
                 * calculate total paid and excess amount
                 * if any excess payment, push it to client savings
                 */
                if ($totalAmountPaid == 0) {
                    if ($applicationAmt > $chargeAmt) {
                        $excessAmt = ($applicationAmt - $chargeAmt);
                        $amount = $chargeAmt;
                    } else {
                        $excessAmt = 0;
                        $amount = $applicationAmt;
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
                        if ($applicationAmt > $particularBalance) {
                            $excessAmt = ($applicationAmt - $particularBalance);
                            $amount = $particularBalance;
                        }
                        if ($applicationAmt <= $particularBalance) {
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
                    'product_id' => ($product_id) ? $product_id : null,
                    'application_id' => $application_id,
                    'entry_menu' => $entry_menu,
                    'entry_typeId' => $entry_typeId,
                    'account_typeId' => $account_typeId,
                    'ref_id' => $ref_id,
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
                                $applicationInfo['branch_name'] = $this->userRow['branch_name'];
                                $applicationInfo['amount'] = $amount;
                                $applicationInfo['charge'] = $chargeAmt;
                                $applicationInfo['totalCollected'] = $totalAmountPaid;
                                $applicationInfo['ref_id'] = $ref_id;
                                $applicationInfo['date'] = date('d-m-Y H:i:s');
                                $applicationInfo['entry_details'] = trim($this->request->getVar('entry_details'));
                                $applicationInfo['account_typeID'] = $account_typeId;
                                $applicationInfo['type'] = $transaction_typeRow['type'];
                                $applicationInfo['particular_name'] = $particularRow['particular_name'];
                                $applicationInfo['payment_mode'] = $paymentRow['particular_name'];
                                $message = $applicationInfo;
                                # check the email existence and email notify is enabled
                                if (!empty($clientInfo['email']) && $this->settingsRow['email']) {
                                    $subject = 'New ' . $particularRow['particular_name'] . ' Transaction';
                                    $message = $message;
                                    $token = 'transaction';
                                    $this->settings->sendMail($message, $subject, $token);
                                    $txt .= 'Email Sent';
                                }
                                # check the mobile existence and sms notify is enabled
                                if (!empty($clientInfo['mobile']) && $this->settingsRow['sms']) {
                                    # send sms
                                    $sms = $this->sendSMS([
                                        'mobile' => trim($clientInfo['mobile']),
                                        'text' => 'A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . number_format($amount) . ' via ' . strtolower($particularRow['particular_name']) . ' has been received by ' . strtolower($this->settingsRow['business_name']) . ' on ' . $date . ' . ID: ' . $ref_id . ' ~ ' . strtoupper($this->settingsRow["system_abbr"])
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
                        } else {
                            $response = [
                                'status'   => 200,
                                'error'    => null,
                                'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. loggingFailed'
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
            } else {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'Transaction type data could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Particular or Payment method data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    // function to handle membership transactions
    private function add_membershipTransaction($ref_id, $account_typeInfo, $membershipAmt)
    {
        $account_typeId = $account_typeInfo['id'];
        $client_id = trim($this->request->getVar('client_id'));
        $totalAmount = trim($this->request->getVar('particular_charge'));
        # Check the client existence
        $clientInfo = $this->checkArrayExistance('client', [
            'id' => $client_id
        ]);
        $particular_id = trim($this->request->getVar('particular_id'));
        $payment_id = trim($this->request->getVar('payment_id'));
        # Check the particular existence
        $particularRow = $this->checkArrayExistance('particular', [
            'id' => $particular_id,
        ]);
        # Check the particular existence that will be used as for payment
        $paymentRow = $this->checkArrayExistance('particular', [
            'id' => $payment_id,
        ]);

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
            if ($membershipAmt > $chargeAmt) {
                $excessAmt = ($membershipAmt - $chargeAmt);
                $amount = $chargeAmt;
            } else {
                $excessAmt = 0;
                $amount = $membershipAmt;
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

        # echo json_encode($accountingBalance); exit;
        # Compute the account balance
        # Particular: Membership (Revenue Category)
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
            'ref_id' => $ref_id,
            'amount' => $amount,
            'status' => $status,
            'balance' => $accountingBalance,
            'contact' => trim($this->request->getVar('contact_full')),
            'entry_details' => trim($this->request->getVar('entry_details')),
            'remarks' => trim($this->request->getVar('remarks')),
        ];
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
            $clientInfo['branch_name'] = $this->userRow['branch_name'];
            $clientInfo['amount'] = $amount;
            $clientInfo['charge'] = $particularRow['charge'];
            $clientInfo['ref_id'] = $ref_id;
            $clientInfo['date'] = date('d-m-Y H:i:s');
            $clientInfo['entry_details'] = trim($this->request->getVar('entry_details'));
            $clientInfo['account_typeID'] = $account_typeId;
            $clientInfo['type'] = $transaction_typeRow['type'];
            $clientInfo['particular_name'] = $particularRow['particular_name'];
            $clientInfo['payment_mode'] = $paymentRow['particular_name'];
            $message = $clientInfo;
            # Check the client email existence and email notify is enabled
            if (!empty($clientInfo['email']) && $this->settingsRow['email']) {
                $subject = 'New ' . $particularRow['particular_name'] . ' Transaction';
                $message = $message;
                $token = 'transaction';
                $this->settings->sendMail($message, $subject, $token);
                $txt .= 'Email Sent';
            }

            # check the mobile existence and sms notify is enabled
            if (!empty($clientInfo['mobile']) && $this->settingsRow['sms']) {
                # send sms
                $sms = $this->sendSMS([
                    'mobile' => trim($clientInfo['mobile']),
                    'text' => 'A ' . strtolower($transaction_typeRow['type']) . ' of ' . $this->settingsRow["currency"] . ' ' . number_format($amount) . ' being made for ' . strtolower($particularRow['particular_name']) . ' has been received by ' . strtolower($this->settingsRow['business_name']) . ' on ' . $date . ' . ID: ' . $ref_id . ' ~ ' . strtoupper($this->settingsRow["system_abbr"])
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
    }

    public function createFlutterWave()
    {
        $this->validateTransaction("transactions");

        $client_id = trim($this->request->getVar('client_id'));
        $particular_id = trim($this->request->getVar('particular_id'));
        $payment_id = trim($this->request->getVar('payment_id'));
        $entry_typeId = trim($this->request->getVar('entry_typeId'));
        $account_typeId = trim($this->request->getVar('account_typeId'));
        $amount = str_replace(',', '', trim($this->request->getVar('amount')));
        $entry_menu = trim($this->request->getVar('entry_menu'));
        $date = trim($this->request->getVar('date'));
        $contact = trim($this->request->getVar('contact'));
        $entry_details = trim($this->request->getVar('entry_details'));
        $remarks = trim($this->request->getVar('remarks'));
        $ref_id = $this->settings->generateReference();
        $token = $this->settings->generateToken();

        $redirect_url = base_url('client/transactions/payment?entrydetails=' . $entry_details . '&account=' . $token . '&entrymenu=' . $entry_menu . '&entrydate=' . $date . '&clientid=' . $client_id . '&particularid=' . $particular_id . '&paymentid=' . $payment_id . '&entrytypeId=' . $entry_typeId . '&amount=' . $amount . '&contact=' . $contact);

        $response = [
            'status' => 200,
            'error' => null,
            'client' => $this->userRow,
            'account' => [
                'tx_ref' => $ref_id,
                'date' => $date,
                'amount' => $amount,
                'currency' => 'UGX',
                'redirect_url' => $redirect_url,
            ],
            'entry' => [
                'particular_id' => $particular_id,
                'payment_id' => $payment_id,
                'entry_typeId' => $entry_typeId,
                'account_typeId' => $account_typeId,
                'entry_menu' => $entry_menu,
                'client_id' => $client_id
            ]
        ];

        return $this->respond($response);
    }

    public function getEntriesByApplicationId($application_id)
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
                return '<div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-info tp-btn" data-bs-toggle="dropdown">
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

    private function validateTransaction($menu)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        $client_id = trim($this->request->getVar('client_id'));
        $account_typeId = trim($this->request->getVar('account_typeId'));
        $entry_typeId = trim($this->request->getVar('entry_typeId'));
        if (!empty($entry_typeId)) {
            $transaction_typeRow = $this->entryType->find($entry_typeId);
        }
        $application_id = $this->request->getVar('application_id');
        $disbursement_id = trim($this->request->getVar('disbursement_id'));
        $amount = $this->removeCommasFromAmount($this->request->getVar('amount'));
        # trimmed the white space between between country code and phone number
        $phone = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('contact_country_code'),
            'phone' => $this->request->getVar('contact')
        ]);
        $particular_id = trim($this->request->getVar('particular_id')); # check the particular existence
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
        $payment_id = trim($this->request->getVar('payment_id'));

        if (trim($this->request->getVar('particular_id')) == '') {
            $data['inputerror'][] = 'particular_id';
            $data['error_string'][] = 'Account module is required';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('entry_typeId') == '') {
            $data['inputerror'][] = 'entry_typeId';
            $data['error_string'][] = 'Transaction Type is required';
            $data['status'] = FALSE;
        }

        if (!empty($this->request->getVar('contact'))) {
            # validate the phone number
            $this->validPhoneNumber([
                'phone' => $phone,
                'input' => 'contact',
            ]);
        }

        if ($this->request->getVar('amount') == '') {
            $data['inputerror'][] = 'amount';
            $data['error_string'][] = 'Amount is required';
            $data['status'] = FALSE;
        }

        if (!empty($this->request->getVar('amount'))) {
            # accept only digits
            if (!preg_match("/^[0-9.' ]*$/", $amount)) {
                $data['inputerror'][] = 'amount';
                $data['error_string'][] = 'Only digit is required!';
                $data['status'] = FALSE;
            }
        }

        if ($this->request->getVar('payment_id') == '') {
            $data['inputerror'][] = 'payment_id';
            $data['error_string'][] = 'Payment method is required';
            $data['status'] = FALSE;
        }

        if (empty($this->request->getVar('date'))) {
            $data['inputerror'][] = 'date';
            $data['error_string'][] = 'Transaction date is required';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('entry_details') == '') {
            $data['inputerror'][] = 'entry_details';
            $data['error_string'][] = 'Description is required';
            $data['status'] = FALSE;
        }

        $clientInfo = $this->clientDataRow($client_id);
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
                        $savingsProducts = (($clientInfo['savingsProducts']) ? $clientInfo['savingsProducts'] : null);
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
                    $data['error_string'][] = 'Minimum amount per transaction is ' . number_format($min_per_entry) . '!';
                    $data['status'] = FALSE;
                }
                # amount isn'max_per_entry above max per transaction
                if ($min_per_entry && ($amount > $max_per_entry)) {
                    $data['inputerror'][] = 'amount';
                    $data['error_string'][] = 'Maximum amount per transaction is ' . number_format($max_per_entry) . '!';
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
                            $data['error_string'][] = $transaction_typeRow['type'] . ' amount with '.number_format($charge).' charges exceeds account balance of ' . number_format($operationalBalance) . '!';
                            $data['status'] = FALSE;
                        }
                        # ensure client balance doesn't go below min set on withdraw
                        if ((strtolower($transaction_typeRow['part']) == 'debit') && $min_account_balance && (($operationalBalance - $charge['totalAmount']) < $min_account_balance)) {
                            $recommendDr = ($operationalBalance - ($charge['chargeAmount'] + $min_account_balance));

                            $data['inputerror'][] = 'amount';
                            $data['error_string'][] = 'Account Balance can not go below min balance of ' . number_format($min_account_balance) . '! Recommended ' . $transaction_typeRow['type'] . ' is ' . number_format($recommendDr) . '!';
                            $data['status'] = FALSE;
                        }
                        # ensure client balance doesn't go above max set on withdraw
                        if ((strtolower($transaction_typeRow['part']) == 'credit') && $max_account_balance && (($operationalBalance + $amount) > $max_account_balance)) {
                            $recommendCr = ($max_account_balance -  $operationalBalance);

                            $data['inputerror'][] = 'amount';
                            $data['error_string'][] = 'Account Balance can not go above max balance of ' . number_format($max_account_balance) . '! Recommended ' . $transaction_typeRow['type'] . ' is ' . number_format($recommendCr) . '!';
                            $data['status'] = FALSE;
                        }
                    } else {
                        # code...
                        if ((strtolower($transaction_typeRow['part']) == 'debit') && ($amount > $operationalBalance)) {
                            $data['inputerror'][] = 'amount';
                            $data['error_string'][] = $transaction_typeRow['type'] . ' amount exceeds account balance of ' . number_format($operationalBalance) . '!';
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
                    $totalAmountPaid = $this->entry->sum_client_amountPaid($this->request->getVar('client_id'), $particular_id, $transaction_typeRow['part']);
                    if (strtolower($particularInfo['charge_frequency']) == 'one-time') {
                        if ($totalAmountPaid == $charge) {
                            $data['inputerror'][] = 'amount';
                            $data['error_string'][] = $particularInfo['particular_name'] . ' of ' . $charge . ' paid in full!';
                            $data['status'] = FALSE;
                        }
                    } else {
                        // check if client has made any entries for the selected particular
                        $clientParticularEntries = $this->entry->where(['particular_id' => $particular_id, 'client_id' => $this->request->getVar('client_id')])->findAll();
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

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
