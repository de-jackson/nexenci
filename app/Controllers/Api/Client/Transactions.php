<?php

namespace App\Controllers\Client;

use App\Controllers\Client\MainController;

use \Hermawan\DataTables\DataTable;

class Transactions extends MainController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Transactions';
        $this->title = 'Transactions';

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
        if (($this->userPermissions == 'all') || (in_array('create' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions) || in_array('view' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions) || in_array('update' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions) || in_array('delete' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {

            switch (strtolower($type)) {

                case 'deposit':
                    $view = 'client/transactions/account';
                    break;

                case 'withdraw':
                    $view = 'client/transactions/account';
                    break;

                case 'repayments':
                    $view = 'client/transactions/account';
                    break;

                default:
                    $view = 'layout/404';
                    break;
            }

            return view($view, [
                'title' => $this->title,
                'menu' => $this->menu,
                'type' => $type,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),

            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('client/dashboard'));
        }
    }

    protected function particularDataRow($id)
    {
        $row = $this->particular
            ->select('particulars.*, categories.id as c_id, categories.category_name,categories.category_slug, categories.part, subcategories.id as s_id, subcategories.subcategory_name, subcategories.subcategory_status, subcategories.subcategory_slug, subcategories.created_at as screated, subcategories.updated_at as supdated, statements.name as statement, account_types.id as type_id ,account_types.name as account_type, cash_flow_types.id as cash_flow_id, cash_flow_types.name as cash_flow_type')
            ->join('categories', 'categories.id = particulars.category_id')
            ->join('subcategories', 'subcategories.id = particulars.subcategory_id')
            ->join('statements', 'statements.id = categories.statement_id')
            ->join('account_types', 'account_types.id = particulars.account_typeId')
            ->join('cash_flow_types', 'cash_flow_types.id = particulars.cash_flow_typeId')
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
                                'messages' => 'An Error occured while creating Transaction, Try again!',
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

    public function create()
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
            ->join('staffs', 'staffs.id = entries.staff_id')
            ->join('particulars', 'particulars.id = entries.particular_id')
            ->join('account_types', 'account_types.id = entries.account_typeId')
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
                return '<div class="btn-group dropend my-1">
                <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu">
                        <a href="javascript:void(0)" onclick="view_transaction(' . "'" . $entry->id . "'" . ')" title="View Transaction" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Transaction</a>
                        <div class="dropdown-divider"></div>
                        <a href="/admin/transactions/receipt/' . $entry->id . '/' . $entry->code . '"  title="Transaction Receipt" class="dropdown-item"><i class="fas fa-receipt text-info"></i> View Receipt</a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="delete_particularPayment(' . "'" . $entry->id . "'" . ',' . "'" . $entry->particular_name . "'" . ')" title="Delete Transaction" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Transaction</a>
                    </ul>
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
        $phone = trim(preg_replace('/^0/', '+256', $this->request->getVar('contact')));

        if (trim($this->request->getVar('particular_id')) == '') {
            $data['inputerror'][] = 'particular_id';
            $data['error_string'][] = 'Account module is required';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('entry_typeId') == '') {
            $data['inputerror'][] = 'entry_typeId';
            $data['error_string'][] = 'Select Type is required';
            $data['status'] = FALSE;
        }

        if (!empty($this->request->getVar('contact'))) {
            # check whether the first phone number is with +256
            if (substr($this->request->getVar('contact'), 0, 4) == '+256') {
                if (
                    strlen($this->request->getVar('contact')) > 13 ||
                    strlen($this->request->getVar('contact')) < 13
                ) {
                    $data['inputerror'][] = 'contact';
                    $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                    $data['status'] = FALSE;
                }
            }
            # check whether the first phone number is with 0
            else if (substr($this->request->getVar('contact'), 0, 1) == '0') {
                if (
                    strlen($this->request->getVar('contact')) > 10 ||
                    strlen($this->request->getVar('contact')) < 10
                ) {
                    $data['inputerror'][] = 'contact';
                    $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                    $data['status'] = FALSE;
                }
            } else if (substr($this->request->getVar('contact'), 0, 1) == '+') {
                if (
                    strlen($this->request->getVar('contact')) > 13 ||
                    strlen($this->request->getVar('contact')) < 13
                ) {
                    $data['inputerror'][] = 'contact';
                    $data['error_string'][] = 'Should have 13 digits with Country Code';
                    $data['status'] = FALSE;
                }
            } else {
                $data['inputerror'][] = 'contact';
                $data['error_string'][] = 'Valid Phone Number is required';
                $data['status'] = FALSE;
            }
            # check whether the phone number is valid
            if ($this->settings->validatePhoneNumber($this->request->getVar('contact')) == FALSE) {
                $data['inputerror'][] = 'contact';
                $data['error_string'][] = 'Valid Phone Number is required';
                $data['status'] = FALSE;
            }
        }

        if ($this->request->getVar('amount') == '') {
            $data['inputerror'][] = 'amount';
            $data['error_string'][] = 'Amount is required';
            $data['status'] = FALSE;
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

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
