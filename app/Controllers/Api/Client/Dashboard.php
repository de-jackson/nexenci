<?php

namespace App\Controllers\Api\Client;

use App\Controllers\Api\Client\MainController;

class Dashboard extends MainController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Dashboard';
        $this->title = 'Dashboard';
    }

    public function index()
    {
        $userActivity = $this->userActivity->where(['client_id' => $this->userRow['id']])->orderBy('id', 'desc')->paginate();
        $logs = $this->userLog->where(['client_id' => $this->userRow['id']])
            ->orderBy('id', 'desc')->paginate();
        # get the loan products
        $products = $this->loanProduct->where([
            'deleted_at' => NULL, 'status' => 'Active'
        ])->orderBy('id', 'desc')->paginate();

        # loan repayment information
        $repayments = $this->entry
            ->select('particulars.particular_name, payments.particular_name as payment_method, disbursements.disbursement_code, loanproducts.product_name, account_types.code, disbursements.actual_repayment,
            account_types.name as account_type_name, 
            entrytypes.type, entries.date, SUM(entries.amount) as total, entries.ref_id, entries.status, clients.name, clients.account_no, staffs.staff_name, entries.created_at,entries.account_typeId, entries.id, entries.amount')
            ->join('clients', 'clients.id = entries.client_id')
            ->join('staffs', 'staffs.id = entries.staff_id')
            ->join('account_types', 'account_types.id = entries.account_typeId')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId')
            ->join('particulars', 'particulars.id = entries.particular_id')
            ->join('particulars as payments', 'payments.id = entries.payment_id')
            ->join('disbursements', 'disbursements.id = entries.disbursement_id')
            ->join('loanproducts', 'loanproducts.id = disbursements.product_id')
            ->where([
                'entries.account_typeId' => 3, 'entries.deleted_at' => null,
                'entries.client_id' => $this->userRow['id']
            ])->groupBy('entries.ref_id')->orderBy('entries.id', 'asc')->paginate();

        return $this->sendResponse([
            'title' => $this->title,
            'menu' => $this->menu,
            'settings' => $this->settingsRow,
            'user' => $this->userRow,
            'logs' => $logs,
            # get the total loan
            'loan' => [
                'total' => $this->settings->sum_column('disbursements', 'actual_repayment', [
                    'deleted_at' => null, 'client_id' => $this->userRow['id'], 'class !=' => 'Cleared'
                ], 'disbursements'),
                'paid' => $this->settings->sum_column('disbursements', 'total_collected', [
                    'deleted_at' => null, 'client_id' => $this->userRow['id'], 'class !=' => 'Cleared'
                ], 'disbursements'),
                'balance' => $this->settings->sum_column('disbursements', 'total_balance', [
                    'deleted_at' => null, 'client_id' => $this->userRow['id'], 'class !=' => 'Cleared'
                ], 'disbursements'),
            ],
            # loan products
            'products' => $products,
            # disbursement
            'disbursements' => $this->getLoanDisbursements([
                'disbursements.client_id' => $this->userRow['id'],
                'disbursements.class !=' => 'Cleared'
            ]),
            # loan repayment
            'repayments' => $repayments,

        ], 'Dashboard');
    }

    public function profile()
    {
        return $this->sendResponse([
            'settings' => $this->settingsRow,
            'user' => $this->userRow,
        ], 'Client User Profile');
    }

    public function logout()
    {
        $userlog_id = session()->get('userlog_id');
        # check whether the session exit
        if (isset($userlog_id)) {
            # get user log information
            $userlog = $this->userLog->find($userlog_id);
            $login_at = date_create($userlog['login_at']);
            $logout_at = date_create(date('Y-m-d H:i:s'));
            $duration = $login_at->diff($logout_at)->format('%r%H:%I:%S');
            # update user login information
            $update = $this->userLog->update($userlog_id, [
                'status' => 'offline',
                'logout_at' => date('Y-m-d H:i:s'),
                'duration' => $duration,
            ]);
            if ($update) {
                # destroy user session
                unset(
                    $_SESSION['client'],
                    $_SESSION['name'],
                    $_SESSION['client_id']
                );
                # $session = session();
                # $session->destroy();
                return $this->sendResponse([
                    'url' => '/api/client/auth'
                ], 'You Logged Out Successfully');
            }
        } else {
            # redirect the user to login screen again
            return redirect()->to('/api/client/auth');
        }
    }
}
