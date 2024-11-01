<?php

namespace App\Controllers\Client;

use \Hermawan\DataTables\DataTable;
use CodeIgniter\I18n\Time;
use App\Controllers\Microfinance\BaseController as MicrofinanceBaseController;


class MainController extends MicrofinanceBaseController
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        # Check the Settings Information
        if ($this->settingsRow) {
            # load user data if session is set
            $client_id = session()->get('client_id');
            $clientLoggedIn = session()->get('clientLoggedIn');
            if (isset($client_id) && isset($clientLoggedIn)) {
                $this->userRow = $this->clientDataRow($client_id);
                # Manual Assign Permissions
                $permissions = serialize([
                    "view_dashboardDashboard",
                    # Company module
                    "view_companyCompany",
                    "view_companyBranches",
                    "export_companyBranches",
                    # clients module permissions
                    "create_clientsClients",
                    "view_clientsClients",
                    "import_clientsClients",
                    "update_clientsClients",
                    "export_clientsClients",
                    # Shares Module
                    "view_sharesShares",
                    # shares Purchases module permissions
                    "create_sharesPurchases",
                    "view_sharesPurchases",
                    "import_sharesPurchases",
                    "update_sharesPurchases",
                    "export_sharesPurchases",
                    # shares Withdrawals module permissions
                    "create_sharesWithdrawals",
                    "view_savharesWithdrawals",
                    "import_sharesWithdrawals",
                    "update_sharesWithdrawals",
                    "export_sharesWithdrawals",
                    "view_savingsSavings",
                    # savings Deposits module permissions
                    "create_savingsDeposits",
                    "view_savingsDeposits",
                    "import_savingsDeposits",
                    "update_savingsDeposits",
                    "export_savingsDeposits",
                    # savings Withdraws module permissions
                    "create_savingsWithdraws",
                    "view_savingsDeWithdraws",
                    "import_savingsWithdraws",
                    "update_savingsWithdraws",
                    "export_savingsWithdraws",
                    # Loans Module
                    "view_loansLoans",
                    # Loan Product Module
                    "view_loansProducts",
                    # Loan Application Module
                    "create_loansApplications",
                    "view_loansApplications",
                    "import_loansApplications",
                    "update_loansApplications",
                    "export_loansApplications",
                    # Disbursement Module
                    "view_loansDisbursements",
                    "export_loansDisbursements",
                    # Loan Repayment Module
                    "view_loansRepayments",
                    "create_loansRepayments",
                    "export_loansRepayments",
                    # Loan Application Charges Module
                    "view_loansApplicationCharges",
                    "create_loansApplicationCharges",
                    "export_loansApplicationCharges",
                    # Reports module
                    "view_reportsReports",
                ]);
                # Add custom permissions to the client user information
                $this->userRow["permissions"] = $permissions;
                $this->userRow["position"] = 'Client';

                $this->userRow['applicationsCounter'] = $this->getTableCounts('applications');
                $this->userRow['disbursementsCounter'] = $this->getTableCounts('disbursements');

                $this->userPermissions = unserialize($permissions);

                # end session for the first user
                $this->checkClientMultipleLogin();

                # Check 
                /*
                if (!isset($userPermissions)) {
                    # redirect the user to login screen again
                    session()->setFlashdata('failed', "Client Session Has Expired. Kindly Login Again!");
                    return redirect()->to('/client/login');
                }
                */
            } else {
                # redirect the user to login screen again
                return redirect()->to('/client/login');
            }
        } else {
            # No System Settings Is Found
            return view('layout/404');
        }
    }

    public function load_menu()
    {
        $menus = $this->menuModel->where(['status' => 'Active'])->orderBy('order')->findAll();
        if ($menus) {
            $data = [];
            foreach ($menus as $menu) {
                if (($this->userPermissions == 'all') || (in_array('create' . $this->title, $this->userPermissions) || in_array('view' . $this->title, $this->userPermissions) || in_array('update' . $this->title, $this->userPermissions) || in_array('delete' . $this->title, $this->userPermissions))) {
                    if ($menu['parent_id'] == 0) {
                        $data['parents'][] = $menu;
                    } else {
                        $data['children'][] = $menu;
                    }
                }
            }
            return $data;
        }
    }

    public function checkClientMultipleLogin()
    {
        # Check the client session information
        if (session()->get('clientLoggedIn') && session()->get('client_id')) {
            $client_id = session()->get('client_id');
            $user = $this->client->find($client_id);
            if ($user) {
                # check user login access
                if (strtolower($user["access_status"]) == "active") {
                    $token = $user['token'];
                    $authorized = password_verify(session()->get('token'), $token);
                    if (!$authorized) {
                        # logout user automatically from the first device 
                        # when multiple login is deducted 
                        $this->logout();
                    }
                } else {
                    # user is not authorized to access this
                    session()->setFlashdata('failed', 'Failed! You are Not Authorized to Access this!');
                    return redirect()->to(base_url('client/login'));
                }
            } else {
                session()->setFlashdata('failed', 'Failed! We could not find your account!');
                return redirect()->to(base_url('client/login'));
            }
        }
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
                    $_SESSION['clientLoggedIn'],
                    $_SESSION['name'],
                    $_SESSION['client_id'],
                    $_SESSION['userlog_id'],
                );
                // $session = session();
                // $session->destroy();
                return redirect()->to('/client/login');
            }
        } else {
            # redirect the user to login screen again
            return redirect()->to('/client/login');
        }
    }

    public function getTableCounts($table)
    {
        switch (strtolower($table)) {
            case 'emails':
                $counter = [
                    'emails' => $this->settings->countResults('emails', ['deleted_at' => null]),
                    'inbox' => $this->settings->countResults('emails', ['recipient_id' => $this->userRow['id'], "status" => 'unread', 'deleted_at' => null]),
                    'sent' => $this->settings->countResults('emails', ['sender_id' => $this->userRow['id'], "status" => 'unread', 'deleted_at' => null]),
                    'draft' => $this->settings->countResults('emails', ['label' => "draft", 'deleted_at' => null]),
                    'spam' => $this->settings->countResults('emails', ['label' => "spam", 'deleted_at' => null]),
                    'important' => $this->settings->countResults('emails', ['label' => "important", 'deleted_at' => null]),
                    'trash' => $this->settings->countResults('emails', ['label' => "trash", 'deleted_at' => null]),
                    'archive' => $this->settings->countResults('emails', ['label' => "archive", 'deleted_at' => null]),
                    'starred' => $this->settings->countResults('emails', ['label' => "starred", 'deleted_at' => null]),
                    // 'unread' => $this->settings->countResults('emails', ['recipient_id' => $this->userRow['id'], "status" => 'unread', 'deleted_at' => null]),
                    // 'read' => $this->settings->countResults('emails', ['recipient_id' => $this->userRow['id'], "status" => 'read', 'deleted_at' => null]),
                ];
                break;
            case 'disbursements':
                $counter = [
                    'disbursements' => $this->settings->countResults('disbursements', ['deleted_at' => null]),
                    'running' => $this->settings->countResults('disbursements', ['class' => "Running", 'deleted_at' => null]),
                    'arrears' => $this->settings->countResults('disbursements', ['class' => "Arrears", 'deleted_at' => null]),
                    'cleared' => $this->settings->countResults('disbursements', ['class' => "Cleared", 'deleted_at' => null]),
                    'expired' => $this->settings->countResults('disbursements', ['class' => "Expired", 'deleted_at' => null]),
                    'defaulted' => $this->settings->countResults('disbursements', ['class' => "Defaulted", 'deleted_at' => null]),
                ];
                break;
            case 'applications':
                $counter = [
                    'applications' => $this->settings->countResults('loanapplications', ['deleted_at' => null, 'client_id' => $this->userRow['id']]),
                    'pending' => $this->settings->countResults('loanapplications', ['status ' => 'Pending', 'deleted_at' => null, 'client_id' => $this->userRow['id']]),
                    'processing' => $this->settings->countResults('loanapplications', ['status ' => 'Processing', 'deleted_at' => null, 'client_id' => $this->userRow['id']]),
                    'declined' => $this->settings->countResults('loanapplications', ['status ' => 'Declined', 'deleted_at' => null, 'client_id' => $this->userRow['id']]),
                    'review' => $this->settings->countResults('loanapplications', ['status ' => 'Review', 'deleted_at' => null, 'client_id' => $this->userRow['id']]),
                    'approved' => $this->settings->countResults('loanapplications', ['status ' => 'Approved', 'deleted_at' => null, 'client_id' => $this->userRow['id']]),
                    'cancelled' => $this->settings->countResults('loanapplications', ['status ' => 'Cancelled', 'deleted_at' => null, 'client_id' => $this->userRow['id']])
                ];
                break;
            case 'subcategories':
                $counter = [
                    'assets' => $this->settings->countResults('subcategories', ['category_id ' => '1', 'deleted_at' => null]),
                    'equity' => $this->settings->countResults('subcategories', ['category_id ' => '2', 'deleted_at' => null]),
                    'liabilities' => $this->settings->countResults('subcategories', ['category_id ' => '3', 'deleted_at' => null]),
                    'revenue' => $this->settings->countResults('subcategories', ['category_id ' => '4', 'deleted_at' => null]),
                    'expenses' => $this->settings->countResults('subcategories', ['category_id ' => '5', 'deleted_at' => null]),
                ];
                break;
            case 'particulars':
                $counter = [
                    'assets' => $this->settings->countResults('particulars', ['category_id ' => '1', 'deleted_at' => null]),
                    'equity' => $this->settings->countResults('particulars', ['category_id ' => '2', 'deleted_at' => null]),
                    'liabilities' => $this->settings->countResults('particulars', ['category_id ' => '3', 'deleted_at' => null]),
                    'revenue' => $this->settings->countResults('particulars', ['category_id ' => '4', 'deleted_at' => null]),
                    'expenses' => $this->settings->countResults('particulars', ['category_id ' => '5', 'deleted_at' => null]),
                ];
                break;

            default:
                $counter = [];
                break;
        }

        return $counter;
    }

    protected function getParticularById($id)
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

    protected function getClientByID($id)
    {
        $row = $this->client
            ->select('clients.id, clients.name, clients.branch_id, clients.staff_id, clients.account_no, clients.account_type, clients.account_balance, clients.email, clients.mobile, clients.alternate_no, clients.gender, clients.dob, clients.marital_status, clients.religion, clients.nationality, clients.occupation, clients.job_location, clients.residence, clients.id_type, clients.id_number, clients.id_expiry_date, clients.next_of_kin_name, clients.next_of_kin_relationship, clients.next_of_kin_contact, clients.next_of_kin_alternate_contact, clients.nok_email, clients.nok_address, clients.photo, clients.id_photo_front, clients.id_photo_back, clients.signature, clients.access_status, clients.reg_date,clients.created_at, clients.updated_at, branches.branch_name, staffs.staff_name, staffs.staffID')
            ->join('branches', 'branches.id = clients.branch_id', 'left')
            ->join('staffs', 'staffs.id = clients.staff_id', 'left')
            ->find($id);
        return $row;
    }

    protected function getLoanApplicationById($id)
    {
        $row = $this->loanApplication
            ->select('loanapplications.*, clients.id as c_id, clients.name, clients.account_no, clients.email, clients.mobile, clients.residence, clients.photo, clients.id_number, clients.next_of_kin_name, clients.next_of_kin_contact, clients.gender, clients.religion, clients.marital_status, clients.nationality, clients.alternate_no, clients.dob, clients.occupation, clients.job_location, clients.id_type, clients.id_expiry_date, clients.next_of_kin_relationship, clients.next_of_kin_alternate_contact, clients.nok_email, clients.nok_address, clients.account_type as acc_type,, clients.signature as sign, clients.id_photo_front, clients.id_photo_back, loanproducts.product_name, loanproducts.interest_rate, loanproducts.interest_type, loanproducts.repayment_period, loanproducts.repayment_duration, loanproducts.repayment_freq, branches.branch_name, staffs.staff_name, staffs.staffID')
            ->join('clients', 'clients.id = loanapplications.client_id', 'left')
            ->join('staffs', 'staffs.id = loanapplications.staff_id', 'left')
            ->join('loanproducts', 'loanproducts.id = loanapplications.product_id', 'left')
            ->join('branches', 'branches.id = clients.branch_id', 'left')
            ->find($id);
        return $row;
    }

    protected function getLoanDisbursementById($id)
    {
        $row = $this->disbursement
            ->select('disbursements.*, branches.branch_name,  staffs.staff_name, staffs.signature, clients.id as client_id, clients.name, clients.account_no, clients.account_balance, clients.email, clients.mobile, clients.residence, clients.photo, clients.id_number, clients.next_of_kin_name, clients.next_of_kin_contact,clients.nok_email, clients.nok_address, clients.gender, clients.religion, clients.nationality, clients.marital_status, clients.alternate_no, clients.dob, clients.occupation, clients.job_location, clients.id_type, clients.id_expiry_date, clients.next_of_kin_relationship, clients.next_of_kin_alternate_contact, clients.account_type, clients.signature as sign, clients.id_photo_front, clients.id_photo_back, loanproducts.product_name, interest_rate, loanproducts.interest_type, loanproducts.repayment_period, loanproducts.repayment_duration, loanproducts.repayment_freq, loanapplications.application_code, loanapplications.purpose,loanapplications.overall_charges, loanapplications.total_charges, loanapplications.reduct_charges, loanapplications.security_item, loanapplications.security_info, loanapplications.est_value, loanapplications.ref_name, loanapplications.ref_address, loanapplications.ref_job, loanapplications.ref_contact, loanapplications.ref_alt_contact, loanapplications.ref_email, loanapplications.ref_relation, loanapplications.ref_name2, loanapplications.ref_address2, loanapplications.ref_job2, loanapplications.ref_contact2, loanapplications.ref_alt_contact2, loanapplications.ref_email2, loanapplications.ref_relation2')
            ->join('clients', 'clients.id = disbursements.client_id', 'left')
            ->join('staffs', 'staffs.id = disbursements.staff_id', 'left')
            ->join('branches', 'branches.id = clients.branch_id', 'left')
            ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')
            ->join('loanapplications', 'loanapplications.id = disbursements.application_id', 'left')
            ->find($id);
        return $row;
    }

    protected function getLoanDisbursements($condition)
    {
        $disbursements = $this->disbursement
            ->select('disbursements.*, branches.branch_name,  staffs.staff_name, staffs.signature, clients.id as client_id, clients.name, clients.account_no, clients.account_balance, clients.email, clients.mobile, clients.residence, clients.photo, clients.id_number, clients.next_of_kin_name, clients.next_of_kin_contact,clients.nok_email, clients.nok_address, clients.gender, clients.religion, clients.nationality, clients.marital_status, clients.alternate_no, clients.dob, clients.occupation, clients.job_location, clients.id_type, clients.id_expiry_date, clients.next_of_kin_relationship, clients.next_of_kin_alternate_contact, clients.account_type, clients.signature as sign, clients.id_photo_front, clients.id_photo_back, loanproducts.product_name, loanapplications.application_code, loanapplications.purpose,loanapplications.overall_charges, loanapplications.total_charges, loanapplications.reduct_charges, loanapplications.security_item, loanapplications.security_info, loanapplications.est_value, loanapplications.ref_name, loanapplications.ref_address, loanapplications.ref_job, loanapplications.ref_contact, loanapplications.ref_alt_contact, loanapplications.ref_email, loanapplications.ref_relation, loanapplications.ref_name2, loanapplications.ref_address2, loanapplications.ref_job2, loanapplications.ref_contact2, loanapplications.ref_alt_contact2, loanapplications.ref_email2, loanapplications.ref_relation2,
            applicant_products.interest_rate, applicant_products.repayment_frequency, applicant_products.repayment_period, applicant_products.interest_period, applicant_products.interest_type, applicant_products.id as applicant_product_id')
            ->join('clients', 'clients.id = disbursements.client_id', 'left')
            ->join('staffs', 'staffs.id = disbursements.staff_id', 'left')
            ->join('branches', 'branches.id = clients.branch_id', 'left')
            ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')
            ->join('loanapplications', 'loanapplications.id = disbursements.application_id', 'left')
            ->join('applicant_products', 'applicant_products.application_id = disbursements.application_id', 'left')
            ->where($condition)
            ->orderBy('disbursements.id', 'desc')->paginate(10);

        return $disbursements;
    }

    protected function getApplicationChargeParticulars($particular_ids = null, $amount = null)
    {
        if ($particular_ids) {
            $particulars = $this->particular->select('id, particular_name, charged, charge, charge_method, charge_mode, grace_period')->find($particular_ids);
        } else {
            $particulars = $this->particular->select('id, particular_name, charged, charge, charge_method, charge_mode, grace_period')->where(['account_typeId' => 18, 'charge_mode' => 'Auto', 'particular_status' => 'Active'])->find();
        }

        # gets particular id
        $particularIDs = [];
        $totalCharges = 0;
        // loop through the particulars to get their corresponding cash flow types
        foreach ($particulars as $particular) {
            $id = $particular['id'];
            if ($amount) {
                $charge = 0;
                if (strtolower($particular['charged']) == 'yes') {
                    if (strtolower($particular['charge_method']) == 'amount') {
                        $charge = $particular['charge'];
                    }
                    if (strtolower($particular['charge_method']) == 'percent') {
                        $charge = (($particular['charge'] / 100) * $amount);
                    }
                }
                $totalCharges += $charge;
            }
            // Check if the key already exists in $cashflowTypes array
            $particularIDs[] = $id;
        }
        $data['particulars'] = $particulars;
        $data['particularIDs'] = array_unique($particularIDs);
        $data['totalCharges'] = $totalCharges;

        return $data;
    }

    protected function calculateAccountingBalance($particular_id, $amount, $status, $entryId = null)
    {
        $accountingBalance = $balance = $debitTotal = $creditTotal = 0;
        # get all particular entries
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
        $particularInfo = $this->getParticularById($particular_id);
        // add amount to balance
        if (strtolower($particularInfo['part']) == 'debit') {
            if (strtolower($status) == 'debit') {
                $accountingBalance = ($balance + $amount);
            } else {
                $accountingBalance = ($balance - $amount);
            }
        }
        if (strtolower($particularInfo['part']) == 'credit') {
            # remove negative for credited particulars
            $balance = abs($balance);
            if (strtolower($status) == 'debit') {
                $accountingBalance = ($balance - $amount);
            } else {
                $accountingBalance = ($balance + $amount);
            }
        }
        return $accountingBalance;
    }

    protected function calculateLoanEMI($method, $principal, $rate, $period, $interval)
    {
        # EMI (Equated Monthly Installment)
        # Calculate the total number of payments in a year.
        $payouts = (12 / $interval);
        # Convert period to years
        $loanTerm = ($period / $payouts);
        # Calculate the total number of monthly payments
        $numberOfPayments = ($period / $interval);
        $loanInstallment = 0;

        if (strtolower($method) == "reducing") {
            /**
             * M = ([P * r * (1 + r)^n] / [((1 + r)^n - 1)])
             * Where:
             * M is the monthly installment
             * P is the loan amount (principal)
             * r is the monthly interest rate (calculated by dividing the annual interest rate by 12)
             * n is the total number of monthly payments (calculated by multiplying the loan term by 12)
             */

            # Convert annual interest rate to monthly interest rate
            $monthlyInterestRate = (float)($rate / 100 / 12);
            # Calculate the monthly installment using the formula
            $numerator = ($principal *
                $monthlyInterestRate *
                pow(1 + $monthlyInterestRate, $numberOfPayments));

            $denominator = (pow(1 + $monthlyInterestRate, $numberOfPayments) - 1);

            $loanInstallment = ($numerator / $denominator);
        }

        if (strtolower($method) == "flat") {
            /**
             * M = (P + (P * r * n)) / (n * 12)
             * Where:
             * M is the monthly installment
             * P is the loan amount (principal)
             * r is the flat interest rate
             * n is the loan term in years
             */

            // Calculate the interest amount
            $interestAmount = $principal * ($rate / 100) * $loanTerm;
            // Calculate the total amount repayable
            $totalAmountRepayable = $principal + $interestAmount;
            // Calculate the monthly installment using the formula
            $loanInstallment = ($totalAmountRepayable / $numberOfPayments);
        }

        # Return the monthly installment
        return $loanInstallment;
    }

    function calculateLoanInterest($interest_type, $principal, $installment, $rate, $frequency, $no_of_installments)
    {
        $total_principal = $total_interest = $interest_installment = $principal_installment = 0;
        # interval between installment
        $interval = $this->settings->generateIntervals($frequency);
        # number of payments in a year
        $payouts = (12 / $interval['interval']);
        # default balances
        $originalPrincipal = $principal;
        $principal_balance = $principal;

        $index = 1;
        while ($index <= $no_of_installments) {
            if (strtolower($interest_type) == "reducing") {
                $interest_installment = ($principal_balance * ($rate / 100)) / $payouts;
                $principal_installment = $installment - $interest_installment;
            }

            if (strtolower($interest_type) == "flat") {
                $principal_installment = $originalPrincipal / $no_of_installments;
                $interest_installment = $installment - $principal_installment;
            }

            $principal_balance -= $principal_installment;

            # calculate loan total amounts
            $total_principal += $principal_installment;
            $total_interest += $interest_installment;

            $index++;
        }

        return $total_interest;
    }

    public function autoUpdateDisbursementBalance()
    {
        $principal_particularId = 4; // Gross loan principal particular id
        $interest_particularId = 29; // Interest on loans particular id
        $account_typeId = 3; # Loan Portfolio

        $entryDisbursementIds = $this->entry
            ->distinct()
            ->select('disbursement_id')
            ->where(['account_typeId' => $account_typeId])
            ->findColumn('disbursement_id');

        if (empty($entryDisbursementIds)) {
            return $this->respond(([
                'status' => 404,
                'error' => 'Not Found',
                'message' => 'No Disbursement Ids Found',
            ]));
        }

        foreach ($entryDisbursementIds as $disbursementId) {
            $disbursementRow = $this->disbursement->find($disbursementId);

            if (!$disbursementRow) {
                continue; // Skip invalid disbursements
            }

            # Update only when disbursement is not cleared
            if (strtolower($disbursementRow['class']) !== 'cleared') {
                $principalCollected = $this->calculateTotalCollected($principal_particularId, $disbursementId, $account_typeId);
                $interestCollected = $this->calculateTotalCollected($interest_particularId, $disbursementId, $account_typeId);
                $totalAmountCollected = round($principalCollected + $interestCollected);

                $disbursementData = [
                    'interest_collected' => round($interestCollected),
                    'principal_collected' => round($principalCollected),
                    'total_collected' => $totalAmountCollected,
                ];

                $this->disbursement->update($disbursementId, $disbursementData);
            }
        }

        return $this->respond(([
            'status' => 200,
            'error' => null,
            'message' => 'Auto Disbursement Balance Success',
        ]));
    }

    private function calculateTotalCollected($particularId, $disbursementId, $account_typeId)
    {
        $columnSelect = 'date, payment_id, particular_id, account_typeId, client_id, disbursement_id, entry_typeId, amount';

        $entries = $this->entry
            ->select($columnSelect)
            ->where([
                'particular_id' => $particularId,
                'disbursement_id' => $disbursementId,
                'account_typeId' => $account_typeId,
                'status' => 'credit',
            ])
            ->findAll();

        $totalCollected = 0;

        foreach ($entries as $entry) {
            $totalCollected += $entry['amount'];
        }

        return $totalCollected;
    }

    public function autoUpdateParticularBalance()
    {
        // Declare variables to store particular IDs
        $entryParticularIds = [];
        // Get the unique particular IDs
        $uniqueIds = [];

        // Get distinct payment_id values
        $paymentIds = $this->entry->distinct()->select('payment_id')->findColumn('payment_id');
        if ($paymentIds) {
            $uniqueIds = array_merge($uniqueIds, $paymentIds);

            // Get distinct particular_id values
            $particularIds = $this->entry->distinct()->select('particular_id')->findColumn('particular_id');
            $uniqueIds = array_merge($uniqueIds, $particularIds);

            // Remove duplicate IDs and return the array
            $entryParticularIds = array_unique($uniqueIds);
            // Select columns to select from entries table
            $columnSelect = 'date, payment_id, particular_id, account_typeId, entry_typeId, amount';
            // Loop through particular IDs
            foreach ($entryParticularIds as $particularId) {
                // Get particular data for each iteration
                $particularRow = $this->particular->find($particularId);
                if (!$particularRow) {
                    continue; // Skip invalid particulars
                }

                $totalDebitAmount = $this->calculateTotalAmount($particularId, 'debit');
                $totalCreditAmount = $this->calculateTotalAmount($particularId, 'credit');

                // Update particular total debit and credit
                $updateTotal = $this->particular->update($particularId, [
                    'debit' => $totalDebitAmount,
                    'credit' => $totalCreditAmount,
                ]);
            }
            if ($updateTotal) {
                return $this->respond(([
                    'status' => 200,
                    'error' => null,
                    'message' => "Auto Particular Total Debit & Credit Success",
                ]));
            } else {
                return $this->respond(([
                    'status' => 500,
                    'error' => 'Action Failed',
                    'message' => "Auto Particular Total Debit & Credit Failed",
                ]));
            }
        } else {
            return $this->respond(([
                'status' => 404,
                'error' => 'Not Found',
                'message' => "No Particular Ids Found",
            ]));
        }
    }

    private function calculateTotalAmount($particularId, $status)
    {
        $columnSelect = 'date, payment_id, particular_id, account_typeId, entry_typeId, amount';

        $transactions = $this->entry
            ->select($columnSelect)
            ->where([
                'particular_id' => $particularId,
                'status' => $status,
            ])
            ->findAll();

        $totalAmount = 0;

        foreach ($transactions as $transaction) {
            $totalAmount += $transaction['amount'];
        }

        return $totalAmount;
    }

    public function autoUpdateClientAccountBalance()
    {
        $account_typeId = 12; # Savings
        $columnSelect = 'client_id, SUM(CASE WHEN status = "debit" THEN amount ELSE 0 END) AS debit_total, SUM(CASE WHEN status = "credit" THEN amount ELSE 0 END) AS credit_total';

        $results = $this->entry
            ->select($columnSelect)
            ->where(['account_typeId' => $account_typeId])
            ->groupBy('client_id')
            ->findAll();

        $updateSuccess = true; // Initialize a flag to track if all updates are successful.

        foreach ($results as $result) {
            $client_id = $result['client_id'];
            $debitTotal = $result['debit_total'];
            $creditTotal = $result['credit_total'];

            // Calculate client total balance
            $totalBalance = ((($debitTotal - $creditTotal) > 0) ? - ($debitTotal - $creditTotal) : abs($debitTotal - $creditTotal));

            // Attempt to update client account balance
            $updateResult = $this->client->update($client_id, ['account_balance' => $totalBalance]);

            // Check if the update was successful
            if (!$updateResult) {
                $updateSuccess = false;
                // Log or report the error, and continue processing other clients or exit the loop as needed.
                // You may also want to break the loop or implement a rollback mechanism.
                // Example: log_error("Failed to update client balance for client ID: $client_id");
            }
        }

        if ($updateSuccess) {
            return $this->respond(([
                'status' => 200,
                'error' => null,
                'message' => "Auto Client Savings Balance Update Success",
            ]));
        } else {
            return $this->respond(([
                'status' => 500,
                'error' => 'Action Failed',
                'message' => "Auto Client Savings Balance Update Failed for some clients.",
            ]));
        }
    }

    public function updateDisbursementAutoFields()
    {
        $allDisbursements = $this->disbursement->where([
            'client_id' => $this->userRow['id'],
            'class !=' => 'Cleared'
        ])->findAll();
        if (count($allDisbursements) > 0) {
            $comments = '';
            $updateDays = false;
            foreach ($allDisbursements as $row) {
                $interest_installment = $principal_installment = $days_covered = $days_remaing = $installments_covered = $arrears = $installment_due = $principal_due = $interest_due = 0;
                $installments_num = $row['installments_num'];
                $productRow = $this->loanProduct->find($row['product_id']);
                $frequency = $productRow['repayment_freq'];
                $rate = $productRow['interest_rate'];
                $rateType = $productRow['interest_type'];
                $period = $productRow['repayment_period'];
                $interval = $this->settings->generateIntervals($frequency);
                $payouts = (12 / $interval['interval']);
                // calculate principal and interest value per installment
                /** 
                 * if(strtolower($rateType) == "reducing"){
                 *    $interest_installment = round(($row['principal_balance'] * ($rate/100) / $payouts), 1);
                 * } else{
                 *     $interest_installment = round(($row['principal'] * (($rate / 100) / $payouts)), 1);
                 * }
                 * $principal_installment = $row['computed_installment'] - $interest_installment;
                 */
                if (strtolower($rateType) == "reducing") {
                    $interest_installment = round(($row['principal_balance'] * ($rate / 100) / $payouts));
                    $principal_installment = ($row['computed_installment'] - $interest_installment);
                } else {
                    $principal_installment = round(($row['principal'] / $installments_num));
                    $interest_installment = round(($row['computed_installment'] - $principal_installment));
                }
                // calculate days remaining to loan maturity\expiry
                $days_remaing = (Time::today()->diff(new Time($row['loan_expiry_date']))->format('%a'));
                if ($days_remaing > 0) {
                    $days_remaing = $days_remaing;
                } else {
                    $days_remaing = " - ";
                }
                // calculate days covered
                $days_covered = ((int)($row['loan_period_days']) - (int)$days_remaing);
                if ($days_covered <= $row['loan_period_days']) {
                    $days_covered;
                } else { // make days covered expired if
                    $days_covered = "Expired";
                }
                // calculate installments covered based on the days covered
                if ($days_covered == "Expired") { // all installments have been covered
                    $installments_covered = $installments_num;
                } elseif ($days_covered >=  $row['grace_period']) {
                    // $installments_covered = $this->roundToFactor('round', ($days_covered / $row['grace_period']));
                    $installments_covered = (int)($days_covered / $row['grace_period']);
                } else { // no installment covered
                    $installments_covered = 0;
                }
                $days_due = $row['days_due'];
                $class = $row["class"];
                // calculate amount expected to be recovered
                $expected_amount_recovered = $this->roundToFactor('round', ((float)$installments_covered * (float)$row['actual_installment']),);
                $expected_interest_recovered = $this->roundToFactor('round', ((float)$installments_covered * (float)$row['interest_installment']));
                $expected_principal_recovered = $this->roundToFactor('round', ((float)$installments_covered * (float)$row['principal_installment']));
                $expected_loan_balance = $this->roundToFactor('round', ((float)$row['actual_repayment'] - $expected_amount_recovered));
                // calculate balances
                $interest_balance = ((float)$row['actual_interest'] - (float)$row['interest_collected']);
                $principal_balance = ((float)$row['principal'] - (float)$row['principal_collected']);
                $total_balance = ($interest_balance + $principal_balance);
                // calculate disbursement arrears
                if ($total_balance > $expected_loan_balance) {
                    $arrears = ($total_balance - $expected_loan_balance);
                }
                // calculate number of missed installments
                $installment_due = round($arrears / $row['actual_installment']);

                // calculate number of days missed [days due]
                switch (strtolower($frequency)) {
                    case 'weekly':
                        $days_due = $installment_due * 7;
                        break;
                    case 'bi-weekly':
                        $days_due = $installment_due * 14;
                        break;
                    case 'monthly':
                        $days_due = $installment_due * 30;
                        break;
                    case 'bi-monthly':
                        $days_due = $installment_due * 60;
                        break;
                    case 'quarterly':
                        $days_due = $installment_due * 90;
                        break;
                    case 'termly':
                        $days_due = $installment_due * 120;
                        break;
                    case 'bi-annual':
                        $days_due = $installment_due * 180;
                        break;
                    case 'annually':
                        $days_due = $installment_due * 365;
                        break;
                    default:
                        $days_due = "error";
                        break;
                }
                // auto  update comments
                switch ($days_due) {
                    case ($days_due > 0 && $days_due <= 30):
                        $comments = "Missed (1 - 30) Days";
                        break;
                    case ($days_due > 30 && $days_due <= 60):
                        $comments = "Missed (31 - 60) Days";
                        break;
                    case ($days_due > 60 && $days_due <= 90):
                        $comments = "Missed (61 - 90) Days";
                        break;
                    case ($days_due > 90 && $days_due <= 120):
                        $comments = "Missed (91 - 120) Days";
                        break;
                    case ($days_due > 120 && $days_due <= 180):
                        $comments = "Missed (120 - 180) Days";
                        break;
                    case ($days_due > 180 && $days_due <= 365):
                        $comments = "Missed (120 -  365) Days";
                        break;
                    case ($days_due > 365):
                        $comments = "Missed Over 365 Days";
                        break;
                    default:
                        if (($row['interest_balance'] || $row['principal_balance'] || $row['total_balance']) < 0) {
                            $comments = "Data Entry Error";
                        } else {
                            $comments = "Okay";
                        }
                        break;
                }

                // auto update disbursement class
                if ($total_balance <= 1) {
                    $status = 'Fully Paid';
                    $class = 'Cleared';
                    $comments = "Disbursement Cleared";
                } elseif ($arrears > 0) {
                    // calculate principle and interest due
                    if (strtolower($rateType) == "reducing") {
                        $interest_due = round($arrears * ($rate / 100) * ($days_due / 365));
                    } else {
                        $interest_due = round($arrears * ($rate / 100));
                    }
                    $principal_due = ($arrears - $interest_due);

                    // update disbursement status
                    if ($comments == "Missed Over 365 Days") {
                        $status = 'Defaulted';
                    } else {
                        $status = $row['status'];
                    }
                    $class = 'Arrears';
                } elseif ($days_covered == "Expired") {
                    $status = $row['status'];
                    $class = 'Expired';
                } else {
                    $status = $row['status'];
                    $class = 'Running';
                }
                // auto update data
                $autoData = [
                    'principal_installment' => $principal_installment,
                    'interest_installment' => $interest_installment,
                    'days_remaining' => $days_remaing,
                    'days_covered' => $days_covered,
                    'installments_covered' => $installments_covered,
                    'expected_amount_recovered' => $expected_amount_recovered,
                    'expected_interest_recovered' => $expected_interest_recovered,
                    'expected_principal_recovered' => $expected_principal_recovered,
                    'expected_loan_balance' => $expected_loan_balance,
                    'interest_balance' => $interest_balance,
                    'principal_balance' => $principal_balance,
                    'total_collected' => ($row['interest_collected'] + $row['principal_collected']),
                    'total_balance' => $total_balance,
                    'arrears' => $arrears,
                    'principal_due' => $principal_due,
                    'interest_due' => $interest_due,
                    'installments_due' => $installment_due,
                    'days_due' => $days_due,
                    'status' => $status,
                    'class' => $class,
                    'comments' => $comments,
                ];
                # update the disbursement information 
                $updateDays = $this->disbursement->update($row['id'], $autoData);
            }

            if ($updateDays) {
                $response = [
                    'status' => 200,
                    'error' => null,
                    'message' => 'Auto Disbursement Fields Update Success',
                ];
                return $this->respond($response);
                exit;
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Update Failed',
                    'message' => 'Auto Disbursement Fields Update Failed!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'message' => 'No Disbursements Found!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    public function roundToFactor($action, $num, $factor = 1)
    {
        $quotient = $num / $factor;
        if ($action == 'round') {
            $res = round($quotient) * $factor;
        } else {
            $res = ceil($quotient) * $factor;
        }
        return $res;
    }

    public function cancelLoanApplication($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $applicationRow = $this->getLoanApplicationById($id);
            if ($applicationRow) {
                $clientRow  = $this->client->find($applicationRow['client_id']);
                if ($clientRow) {
                    $productRow = $this->loanProduct->find($applicationRow['product_id']);
                    if ($productRow) {
                        if (strtolower($applicationRow['status']) == 'cancelled') {
                            $response = [
                                'status' => 500,
                                'error' => 'Application Cancelled!',
                                'messages' => 'Application is already Cancelled!',
                            ];
                            return $this->respond($response);
                            exit;
                        } else {
                            $applicationStatus = [
                                'status' => 'Cancelled',
                            ];
                            $update = $this->loanApplication->update($id, $applicationStatus);
                            if ($update) {
                                // insert into activity logs
                                $activityData = [
                                    'user_id' => $this->userRow['id'],
                                    'action' => 'update',
                                    'description' => ucfirst('updated ' . $this->title . ', status: Cancelled, application: ' . $applicationRow['application_code']),
                                    'module' => strtolower('applications'),
                                    'referrer_id' => $id,
                                ];
                                $activity = $this->insertActivityLog($activityData);
                                if ($activity) {
                                    # send mail notification
                                    if ($clientRow['email'] != '') {
                                        $applicationRow['branch_name'] = $this->userRow['branch_name'];
                                        $applicationRow['module'] = 'processing';
                                        $applicationRow['date'] = date('d-m-Y H:i:s');
                                        $checkInternet = $this->settings->checkNetworkConnection();
                                        if ($checkInternet) {
                                            $subject = "Loan Application";
                                            $message = $applicationRow;
                                            $token = 'application';
                                            $this->settings->sendMail($message, $subject, $token);
                                            $response = [
                                                'status' => 200,
                                                'error' => null,
                                                'messages' => "Application Cancelled successfully. Email Sent"
                                            ];
                                            return $this->respond($response);
                                            exit;
                                        } else {
                                            $response = [
                                                'status' => 200,
                                                'error' => null,
                                                'messages' => "Application Cancelled successfully. No Internet"
                                            ];
                                            return $this->respond($response);
                                            exit;
                                        }
                                    } else {
                                        $response = [
                                            'status'   => 200,
                                            'error'    => null,
                                            'messages' => "Application Cancelled successfully"
                                        ];
                                        return $this->respond($response);
                                        exit;
                                    }
                                } else {
                                    $response = [
                                        'status'   => 200,
                                        'error'    => null,
                                        'messages' => 'Application Cancelled successfully. loggingFailed'
                                    ];
                                    return $this->respond($response);
                                    exit;
                                }
                            } else {
                                $response = [
                                    'status' => 500,
                                    'error' => 'Update Failed',
                                    'messages' => 'Cancelling application failed, try again later!',
                                ];
                                return $this->respond($response);
                                exit;
                            }
                        }
                    } else {
                        $response = [
                            'status'   => 404,
                            'error'    => 'Not Found',
                            'messages' => 'Product record not found',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status'   => 404,
                        'error'    => 'Not Found',
                        'messages' => 'Client record not found',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'Application record not found',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to update ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    public function getAccountTypesByPart($part)
    {
        if (strtolower($part) == 'debit') {
            $where = ['categories.part' => $part, 'account_types.id !=' => 1, 'account_types.status' => 'Active'];
        } else {
            $where = ['categories.part' => $part, 'account_types.status' => 'Active'];
        }

        $data = $this->accountType->select('account_types.*, categories.part, categories.statement_id')
            ->join('categories', 'categories.id = account_types.category_id', 'left')
            ->where($where)->findAll();
        return $this->respond($data);
    }

    protected function clientValidation($menu, $action = null)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        # trimmed the white space between between country code and phone number
        $phone = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('phone_country_code'),
            'phone' => $this->request->getVar('phone')
        ]);

        # trimmed the white space between between country code and phone number
        $mobile = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('mobile_country_code'),
            'phone' => $this->request->getVar('mobile')
        ]);

        # trimmed the white space between between country code and phone number
        $alternate_mobile = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('alternate_mobile_country_code'),
            'phone' => $this->request->getVar('alternate_mobile')
        ]);
        # trimmed the white space between between country code and phone number
        $next_of_kin_contact = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('nok_phone_country_code'),
            'phone' => $this->request->getVar('nok_phone')
        ]);

        # trimmed the white space between between country code and phone number
        $next_of_kin_alternate_contact = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('nok_alt_phone_country_code'),
            'phone' => $this->request->getVar('nok_alt_phone')
        ]);

        # get the client information by id
        $clientInfo = $this->client->find($this->request->getVar('id'));

        if ($menu == "registration") {
            # validation client registration
            if (trim($this->request->getVar('name')) == '') {
                $data['inputerror'][] = 'name';
                $data['error_string'][] = 'Full Name is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('name'))) {
                if ($this->settings->validateName($this->request->getVar('name')) == TRUE) {
                    if (strlen(trim($this->request->getVar('name'))) < 6) {
                        $data['inputerror'][] = 'name';
                        $data['error_string'][] = 'Full Name is too short';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->settings->validateName($this->request->getVar('name')) == FALSE) {
                    $data['inputerror'][] = 'name';
                    $data['error_string'][] = 'Valid Full Name is required';
                    $data['status'] = FALSE;
                }
            }
            if (empty($this->request->getVar('phone'))) {
                $data['inputerror'][] = 'phone';
                $data['error_string'][] = 'Phone Number is required';
                $data['status'] = FALSE;
            }
            if (!empty($phone)) {
                # check the phone number
                $this->validPhoneNumber([
                    'phone' => $phone,
                    'input' => 'phone',
                ]);
                # check the phone number existence only when the phone number is valid
                if ($this->settings->validatePhoneNumber($phone) == TRUE) {
                    # check the phone number existence
                    $client = $this->client->where(['mobile' => $phone])->countAllResults();
                    if ($client) {
                        $data['inputerror'][] = 'phone';
                        $data['error_string'][] = 'You already have an account, Login to access your account!';
                        $data['status'] = FALSE;
                    }
                }
            }
            if ($this->request->getVar('password') == '') {
                $data['inputerror'][] = 'password';
                $data['error_string'][] = 'Password is required';
                $data['status'] = FALSE;
            }
            if ($this->request->getVar('confirm_password') == '') {
                $data['inputerror'][] = 'confirm_password';
                $data['error_string'][] = 'Confirm Password is required';
                $data['status'] = FALSE;
            }
        }

        if (strtolower($menu) == "login") {
            # Validate Login Credentials
            if (empty($this->request->getVar('password'))) {
                $data['inputerror'][] = 'password';
                $data['error_string'][] = 'Password is required';
                $data['status'] = FALSE;
            }

            if (empty($phone)) {
                $data['inputerror'][] = 'phone';
                $data['error_string'][] = 'Phone Number is required';
                $data['status'] = FALSE;
            }

            if (!empty($phone)) {
                # check the phone number
                $this->validPhoneNumber([
                    'phone' => $phone,
                    'input' => 'phone',
                ]);
                # check the phone number existence only when the phone number is valid
                if ($this->settings->validatePhoneNumber($phone) == TRUE) {
                    # count the client information by the phone number
                    $client = $this->client
                        ->where(['mobile' => $phone])
                        ->first();
                    # check the client account existence
                    if ($client) {
                        # check client login access
                        if (strtolower($client["access_status"]) == "inactive") {
                            echo json_encode([
                                'status' => 500,
                                'error' => "notAuthorized",
                                'messages' => "You are not Authorized to Access"
                            ]);
                            exit();
                        }
                    } else {
                        echo json_encode([
                            'status' => 500,
                            'error' => "wrongPhone",
                            'messages' => "We could not find your account"
                        ]);
                        exit();
                    }
                }
            }
        }

        if (strtolower($menu) == "recovery") {
            # Valid Forgot Password
            if ($this->request->getVar('phone') == '') {
                $data['inputerror'][] = 'phone';
                $data['error_string'][] = 'Phone number is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('phone'))) {
                # check the phone number
                $this->validPhoneNumber([
                    'phone' => $phone,
                    'input' => 'phone',
                ]);
                # check the phone number existence only when the phone number is valid
                if ($this->settings->validatePhoneNumber($phone) == TRUE) {
                    # count the client information by the phone number
                    $client = $this->client
                        ->where(['mobile' => $phone])
                        ->first();
                    # check the client account existence
                    if (!$client) {
                        $data['inputerror'][] = 'phone';
                        $data['error_string'][] = 'We could not find your account';
                        $data['status'] = FALSE;
                    }
                }
            }
        }

        if (strtolower($menu) == "password") {
            # Valid Change Password
            if ($this->request->getVar('password') == '') {
                $data['inputerror'][] = 'password';
                $data['error_string'][] = 'Password is required!';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('password'))) {
                $pwd = $this->request->getVar('password');
                if (strlen($pwd) < 8) {
                    $data['inputerror'][] = 'password';
                    $data['error_string'][] = 'Password should be at least 8 characters long![' . strlen($pwd) . ']';
                    $data['status'] = FALSE;
                }
                if (!preg_match("/^[a-zA-Z0-9 ']*$/", $pwd)) {
                    $data['inputerror'][] = 'password';
                    $data['error_string'][] = 'Illegal character. Letters & numbers allowed!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->request->getVar('password_confirm') == '') {
                $data['inputerror'][] = 'password_confirm';
                $data['error_string'][] = 'Confirm Password is required!';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('password_confirm'))) {
                $pwd = $this->request->getVar('password_confirm');
                if (strlen($pwd) < 8) {
                    $data['inputerror'][] = 'password_confirm';
                    $data['error_string'][] = 'Confirm Password should be at least 8 characters long![' . strlen($pwd) . ']';
                    $data['status'] = FALSE;
                }
                if (!preg_match("/^[a-zA-Z0-9 ']*$/", $pwd)) {
                    $data['inputerror'][] = 'password_confirm';
                    $data['error_string'][] = 'Illegal character. Letters & numbers allowed!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->request->getVar('password') != $this->request->getVar('password_confirm')) {
                $data['inputerror'][] = 'password_confirm';
                $data['error_string'][] = 'Confirm Password didn\'t match!';
                $data['status'] = FALSE;
            }
        }

        if ($menu == "reset") {

            if ($this->request->getVar('email') == '') {
                $data['inputerror'][] = 'email';
                $data['error_string'][] = 'Email Address is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('email'))) {
                # check whether the email is valid
                if ($this->settings->validateEmail($this->request->getVar('email')) == FALSE) {
                    $data['inputerror'][] = 'email';
                    $data['error_string'][] = 'Valid Email Address is required';
                    $data['status'] = FALSE;
                }
                # validate email
                /*
                if ($this->settings->validateEmail($this->request->getVar('email')) == TRUE) {
                    # validate email
                    $userRow = $this->user->where(['email' => $this->request->getVar('email')])->first();
                    if (!$userRow) {
                        $data['inputerror'][] = 'email';
                        $data['error_string'][] = $this->request->getVar('email').' could not be found!';
                        $data['status'] = FALSE;
                    }
                }
                */
            }
        }

        if ($menu == "otp") {
            if ($this->request->getVar('code') == '') {
                $data['inputerror'][] = 'code';
                $data['error_string'][] = 'Your Verification OTP Code is required';
                $data['status'] = FALSE;
            }
        }

        # client registration form
        if ($menu == "client") {
            # code...
            $clientInfo = $this->client->find($this->request->getVar('id'));

            if ($this->request->getVar('staff_id') == '') {
                $data['inputerror'][] = 'staff_id';
                $data['error_string'][] = 'Responsible Officer is required!';
                $data['status'] = FALSE;
            }

            if ($this->request->getVar('c_name') == '') {
                $data['inputerror'][] = 'c_name';
                $data['error_string'][] = 'Full Name is required!';
                $data['status'] = FALSE;
            }

            if (!empty($this->request->getVar('c_name'))) {
                $name = trim($this->request->getVar('c_name'));
                if ($this->settings->validateName($name) == TRUE) {
                    if (strlen(trim($name)) < 5) {
                        $data['inputerror'][] = 'c_name';
                        $data['error_string'][] = 'Minimum 5 letters required [' . strlen(trim($name)) . ']!';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->settings->validateName($name) == FALSE) {
                    $data['inputerror'][] = 'name';
                    $data['error_string'][] = 'Valid Full Name is required!';
                    $data['status'] = FALSE;
                }
            }

            if (!empty($this->request->getVar('c_email'))) {
                $email = trim($this->request->getVar('c_email'));
                # get the number of client by the email address
                $client = $this->client->where(['email' => $email])->countAllResults();
                # check whether the email is valid
                if ($this->settings->validateEmail($email) == FALSE) {
                    $data['inputerror'][] = 'c_email';
                    $data['error_string'][] = 'Valid Email is required!';
                    $data['status'] = FALSE;
                }

                if ($action == 'add') {
                    # check the client email existence
                    if ($client) {
                        $data['inputerror'][] = 'c_email';
                        $data['error_string'][] = $this->request->getVar('c_email') . ' already added!';
                        $data['status'] = FALSE;
                    }
                }

                if ($action == 'update' && $clientInfo['email'] != $email) {
                    # check the client email existence
                    if ($client) {
                        $data['inputerror'][] = 'c_email';
                        $data['error_string'][] = $this->request->getVar('c_email') . ' already added!';
                        $data['status'] = FALSE;
                    }
                }
            }

            if ($this->request->getVar('mobile') == '') {
                $data['inputerror'][] = 'mobile';
                $data['error_string'][] = 'Phone Number is required!';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('mobile'))) {
                # validate the phone number
                $this->validPhoneNumber([
                    'phone' => $mobile,
                    'input' => 'mobile',
                ]);
                # validate below when the phone number is valid
                if ($this->settings->validatePhoneNumber($mobile) == TRUE) {
                    # count the number of clients by the phone number
                    $client = $this->client->where(['mobile' => $mobile])->countAllResults();

                    if ($action == 'add') {
                        if ($client) {
                            $data['inputerror'][] = 'mobile';
                            $data['error_string'][] = $this->request->getVar('mobile') . ' already added!';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($action == 'update' && $clientInfo['mobile'] != $mobile) {
                        if ($client) {
                            $data['inputerror'][] = 'mobile';
                            $data['error_string'][] = $mobile . ' already added!';
                            $data['status'] = FALSE;
                        }
                    }
                }
            }
            if (!empty($this->request->getVar('alternate_mobile'))) {
                # validate the phone number
                $this->validPhoneNumber([
                    'phone' => $alternate_mobile,
                    'input' => 'alternate_no',
                ]);
                # validate below when the phone number is valid
                if ($this->settings->validatePhoneNumber($alternate_mobile) == TRUE) {
                    # count clients by the alternate phone number
                    $client = $this->client->where([
                        'alternate_no' => $alternate_mobile
                    ])->countAllResults();
                    # check the action to be taken on the client registration form
                    if ($action == 'add') {
                        # check the client existence by the alternate phone number
                        if ($client) {
                            $data['inputerror'][] = 'alternate_no';
                            $data['error_string'][] = $alternate_mobile . ' already added!';
                            $data['status'] = FALSE;
                        }
                    }
                    # check the action to be taken on the client registration form
                    if ($action == 'update' && $clientInfo['alternate_no'] != $alternate_mobile) {
                        # check the client existence by the alternate phone number
                        if ($client) {
                            $data['inputerror'][] = 'alternate_no';
                            $data['error_string'][] = $alternate_mobile . ' already added!';
                            $data['status'] = FALSE;
                        }
                    }
                }
            }

            if (empty($this->request->getVar('residence'))) {
                $data['inputerror'][] = 'residence';
                $data['error_string'][] = 'Address is required!';
                $data['status'] = FALSE;
            }

            if (!empty($this->request->getVar('residence'))) {
                $address = $this->request->getVar('residence');
                if (strlen(trim($address)) < 4) {
                    $data['inputerror'][] = 'residence';
                    $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($address)) . ']!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->request->getVar('gender') == '') {
                $data['inputerror'][] = 'gender';
                $data['error_string'][] = 'Gender is required!';
                $data['status'] = FALSE;
            }

            if ($this->request->getVar('marital_status') == '') {
                $data['inputerror'][] = 'marital_status';
                $data['error_string'][] = 'Marital status is required!';
                $data['status'] = FALSE;
            }
            /*
            if ($this->request->getVar('religion') == '') {
                $data['inputerror'][] = 'religion';
                $data['error_string'][] = 'Religion is required!';
                $data['status'] = FALSE;
            }
            */

            if ($this->request->getVar('nationality') == '') {
                $data['inputerror'][] = 'nationality';
                $data['error_string'][] = 'Nationality is required!';
                $data['status'] = FALSE;
            }

            if ($this->request->getVar('reg_date') == '') {
                $data['inputerror'][] = 'reg_date';
                $data['error_string'][] = 'Registration Date is required!';
                $data['status'] = FALSE;
            }

            if ($this->request->getVar('dob') == '') {
                $data['inputerror'][] = 'dob';
                $data['error_string'][] = 'Date of birth is required!';
                $data['status'] = FALSE;
            }

            if ($this->request->getVar('occupation') == '') {
                $data['inputerror'][] = 'occupation';
                $data['error_string'][] = 'Occupation is required!';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('occupation'))) {
                $occupation = $this->request->getVar('occupation');
                # Validate the client occupation
                if ($this->settings->validateName($occupation) == TRUE) {
                    if (strlen(trim($occupation)) < 4) {
                        $data['inputerror'][] = 'occupation';
                        $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($occupation)) . ']!';
                        $data['status'] = FALSE;
                    }
                }
            }
            # Occupation address validation
            if ($this->request->getVar('job_location') == '') {
                $data['inputerror'][] = 'job_location';
                $data['error_string'][] = 'Job location is required!';
                $data['status'] = FALSE;
            }

            if (!empty($this->request->getVar('job_location'))) {
                $location = $this->request->getVar('job_location');
                if (strlen(trim($location)) < 4) {
                    $data['inputerror'][] = 'job_location';
                    $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($location)) . ']!';
                    $data['status'] = FALSE;
                }
            }

            if ($this->request->getVar('id_type') == '') {
                $data['inputerror'][] = 'id_type';
                $data['error_string'][] = 'ID Type is required!';
                $data['status'] = FALSE;
            }

            if ($this->request->getVar('id_number') == '') {
                $data['inputerror'][] = 'id_number';
                $data['error_string'][] = 'ID Number is required!';
                $data['status'] = FALSE;
            }
            # check whether the ID type and number is not null
            if (
                !empty($this->request->getVar('id_type')) && !empty($this->request->getVar('id_number'))
            ) {
                $idType = $this->request->getVar('id_type');
                $nin = $this->request->getVar('id_number');
                $client = $this->client->where([
                    'id_number' => $this->request->getVar('id_number')
                ])->first();
                # validate the id number to take only digits and alphabets
                if (!preg_match("/^[0-9a-zA-Z']*$/", $nin)) {
                    $data['inputerror'][] = 'id_number';
                    $data['error_string'][] = 'Only letters and numbers allowed!';
                    $data['status'] = FALSE;
                }
                # check the id type matches the National ID and validate the id number length
                if (strcmp($idType, 'National ID') == 0 && strlen(trim($nin)) != 14) {
                    $data['inputerror'][] = 'id_number';
                    $data['error_string'][] = '14 characters accepted [' . strlen(trim($nin)) . ']!';
                    $data['status'] = FALSE;
                }
                # check the id type matches the Passport and validate the id number length
                if (strcmp($idType, 'Passport') == 0 && strlen(trim($nin)) != 8) {
                    $data['inputerror'][] = 'id_number';
                    $data['error_string'][] = '8 characters accepted [' . strlen(trim($nin)) . ']!';
                    $data['status'] = FALSE;
                }
                # check the id type matches the Driver License and validate the id number length
                if (strcmp($idType, 'Driver License') == 0 && strlen(trim($nin)) != 13) {
                    $data['inputerror'][] = 'id_number';
                    $data['error_string'][] = '13 characters accepted [' . strlen(trim($nin)) . ']!';
                    $data['status'] = FALSE;
                }
                # check the method and validate the client id number exists
                if ($action == 'add' && $client) {
                    $data['inputerror'][] = 'id_number';
                    $data['error_string'][] = $this->request->getVar('id_number') . ' already added!';
                    $data['status'] = FALSE;
                }
                # validate the id number to check the id number existence when updating client info
                if ($action == 'update' && $clientInfo['id_number'] != $this->request->getVar('id_number')) {
                    if ($client) {
                        $data['inputerror'][] = 'id_number';
                        $data['error_string'][] = $this->request->getVar('id_number') . ' already added!';
                        $data['status'] = FALSE;
                    }
                }
            }

            if ($this->request->getVar('next_of_kin') == '') {
                $data['inputerror'][] = 'next_of_kin';
                $data['error_string'][] = 'Next of Kin is required!';
                $data['status'] = FALSE;
            }

            if (!empty($this->request->getVar('next_of_kin'))) {
                $nok = trim($this->request->getVar('next_of_kin'));
                if ($this->settings->validateName($nok) == TRUE) {
                    if (strlen(trim($nok)) < 5) {
                        $data['inputerror'][] = 'next_of_kin';
                        $data['error_string'][] = 'Minimum 5 letters required [' . strlen($nok) . ']!';
                        $data['status'] = FALSE;
                    }
                }
            }

            if ($this->request->getVar('nok_relationship') == '') {
                $data['inputerror'][] = 'nok_relationship';
                $data['error_string'][] = 'Relationship is required!';
                $data['status'] = FALSE;
            }

            if ($this->request->getVar('nok_address') == '') {
                $data['inputerror'][] = 'nok_address';
                $data['error_string'][] = 'Next of Kin Address is required!';
                $data['status'] = FALSE;
            }

            if (!empty($this->request->getVar('nok_address'))) {
                $nok_address = trim($this->request->getVar('nok_address'));
                if (strlen(trim($nok_address)) < 4) {
                    $data['inputerror'][] = 'nok_address';
                    $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($nok_address)) . ']!';
                    $data['status'] = FALSE;
                }
            }

            if (empty($this->request->getVar('nok_phone'))) {
                $data['inputerror'][] = 'nok_phone';
                $data['error_string'][] = 'Next of Kin mobile is required!';
                $data['status'] = FALSE;
            }

            if (!empty($this->request->getVar('nok_phone'))) {
                # validate the next of kin phone number
                $this->validPhoneNumber([
                    'phone' => $next_of_kin_contact,
                    'input' => 'nok_phone',
                ]);
                # validate below when the next of kin phone number is valid
                if ($this->settings->validatePhoneNumber($next_of_kin_contact) == TRUE) {
                    # check the client information by next of kin phone number
                    $client = $this->client->where([
                        'next_of_kin_contact' => $next_of_kin_contact
                    ])->first();
                    # check the method and validate the client next of kin phone number exists
                    if ($action == 'add' && $client) {
                        $data['inputerror'][] = 'nok_phone';
                        $data['error_string'][] = $next_of_kin_contact . ' already added!';
                        $data['status'] = FALSE;
                    }
                    if (
                        $action == 'update' && $clientInfo['next_of_kin_contact'] != $next_of_kin_contact
                    ) {
                        if ($client) {
                            $data['inputerror'][] = 'nok_phone';
                            $data['error_string'][] = $next_of_kin_contact . ' already added!';
                            $data['status'] = FALSE;
                        }
                    }
                }
            }
            if (!empty($this->request->getVar('nok_alt_phone'))) {
                # validate the next of kin phone number
                $this->validPhoneNumber([
                    'phone' => $next_of_kin_alternate_contact,
                    'input' => 'nok_alt_phone',
                ]);
                # validate below when the next of kin phone number is valid
                if ($this->settings->validatePhoneNumber($next_of_kin_alternate_contact) == TRUE) {
                    # check the client information by next of kin phone number
                    $client = $this->client->where([
                        'next_of_kin_alternate_contact' => $next_of_kin_alternate_contact
                    ])->first();
                    # check the method and validate the client next of kin phone number exists
                    if ($action == 'add' && $client) {
                        $data['inputerror'][] = 'nok_alt_phone';
                        $data['error_string'][] = $next_of_kin_alternate_contact . ' already added!';
                        $data['status'] = FALSE;
                    }
                    if (
                        $action == 'update' && $clientInfo['next_of_kin_alternate_contact'] != $next_of_kin_alternate_contact
                    ) {
                        if ($client) {
                            $data['inputerror'][] = 'nok_alt_phone';
                            $data['error_string'][] = $next_of_kin_alternate_contact . ' already added!';
                            $data['status'] = FALSE;
                        }
                    }
                }
            }

            if (!empty($this->request->getVar('nok_email'))) {
                # check whether the email is valid
                if ($this->settings->validateEmail($this->request->getVar('nok_email')) == FALSE) {
                    $data['inputerror'][] = 'nok_email';
                    $data['error_string'][] = 'Valid Email is required!';
                    $data['status'] = FALSE;
                }
            }

            if ($this->request->getVar('branch_id') == '') {
                $data['inputerror'][] = 'branch_id';
                $data['error_string'][] = 'Branch is required!';
                $data['status'] = FALSE;
            }
            /*
            # Ask the client to upload the passport photo
            if (empty($_FILES['photo']['name'])) {
                $data['inputerror'][] = 'photo';
                $data['error_string'][] = 'Passport Photo is required!';
                $data['status'] = FALSE;
            }
            # Ask the client to upload the front ID photo
            if (empty($_FILES['id_photo_front']['name'])) {
                $data['inputerror'][] = 'id_photo_front';
                $data['error_string'][] = 'Front ID Photo is required!';
                $data['status'] = FALSE;
            }
            # Ask the client to upload the back ID photo
            if (empty($_FILES['id_photo_back']['name'])) {
                $data['inputerror'][] = 'id_photo_back';
                $data['error_string'][] = 'Back ID Photo is required!';
                $data['status'] = FALSE;
            }
            # Ask the client to upload the signature
            if (empty($_FILES['signature']['name'])) {
                $data['inputerror'][] = 'signature';
                $data['error_string'][] = 'Your Signature Photo is required!';
                $data['status'] = FALSE;
            }
            */
        }

        if ($menu == "application") {
            # Loan Application
            if ($this->request->getVar('client_id') == '') {
                $data['inputerror'][] = 'client_id';
                $data['error_string'][] = 'Your Full Name is required!';
                $data['status'] = FALSE;
            }

            if ($this->request->getVar('principal') == '') {
                $data['inputerror'][] = 'principal';
                $data['error_string'][] = 'Principal is required!';
                $data['status'] = FALSE;
            }

            if (!empty($this->request->getVar('principal'))) {
                $principal = $this->request->getVar('principal');
                if (!preg_match("/^[0-9.' ]*$/", $principal)) {
                    $data['inputerror'][] = 'principal';
                    $data['error_string'][] = 'Only digit is required!';
                    $data['status'] = FALSE;
                }
            }

            if ($this->request->getVar('product_id') == '') {
                $data['inputerror'][] = 'product_id';
                $data['error_string'][] = 'Loan Product is required!';
                $data['status'] = FALSE;
            }

            if ($action == "add" && !empty($this->request->getVar('client_id'))) {
                $application = $this->loanApplication
                    ->where([
                        'client_id' => trim($this->request->getVar('client_id')),
                        'status' => 'Processing'
                    ])->orWhere([
                        'client_id' => trim($this->request->getVar('client_id')),
                        'status' => 'Pending'
                    ])
                    ->findAll();
                if (count($application) > 0) {
                    $data['inputerror'][] = 'client_id';
                    $data['error_string'][] = 'You already have ' . count($application) - 1 . ' running application(s)!';
                    $data['status'] = FALSE;;
                }

                /*
                $loan = $this->disbursement
                    ->where([
                        'client_id' => trim($this->request->getVar('client_id')),
                        'class !=' => 'Cleared'
                    ])
                    ->findAll();
                if (count($loan) > 0) {
                    $data['inputerror'][] = 'client_id';
                    $data['error_string'][] = 'You already have a running loan!';
                    $data['status'] = FALSE;;
                }
                */
            }

            if ($this->request->getVar('purpose') == '') {
                $data['inputerror'][] = 'purpose';
                $data['error_string'][] = 'Purpose is required!';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('purpose'))) {
                if (strlen($this->request->getVar('purpose')) < 7) {
                    $data['inputerror'][] = 'purpose';
                    $data['error_string'][] = 'Minimum length should is 7 characters';
                    $data['status'] = FALSE;
                }
            }
            # Loan
            if ($this->request->getVar('security_item') == '') {
                $data['inputerror'][] = 'security_item';
                $data['error_string'][] = 'Security Item is required!';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('security_item'))) {
                if (strlen($this->request->getVar('security_item')) < 3) {
                    $data['inputerror'][] = 'security_item';
                    $data['error_string'][] = 'Minimum length should is 3!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->request->getVar('est_value') == '') {
                $data['inputerror'][] = 'est_value';
                $data['error_string'][] = 'Item Estimated Value is required!';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('est_value'))) {
                if (!preg_match("/^[0-9' ]*$/", $this->request->getVar('est_value'))) {
                    $data['inputerror'][] = 'est_value';
                    $data['error_string'][] = 'Invalid format for est_value!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->request->getVar('security_info') == '') {
                $data['inputerror'][] = 'security_info';
                $data['error_string'][] = 'Item Details is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('security_info'))) {
                if ($this->settings->validateAddress($this->request->getVar('security_info')) == TRUE) {
                    if (strlen(trim($this->request->getVar('security_info'))) < 4) {
                        $data['inputerror'][] = 'security_info';
                        $data['error_string'][] = 'Security Details is too short';
                        $data['status'] = FALSE;
                    }
                }
            }
            if ($this->request->getVar('ref_name') == '') {
                $data['inputerror'][] = 'ref_name';
                $data['error_string'][] = 'Guarantor Name is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('ref_name'))) {
                if ($this->settings->validateName($this->request->getVar('ref_name')) == TRUE) {
                    if (strlen(trim($this->request->getVar('ref_name'))) < 6) {
                        $data['inputerror'][] = 'ref_name';
                        $data['error_string'][] = 'Guarantor Full Name is too short';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->settings->validateName($this->request->getVar('ref_name')) == FALSE) {
                    $data['inputerror'][] = 'ref_name';
                    $data['error_string'][] = 'Valid Guarantor Full Name is required';
                    $data['status'] = FALSE;
                }
            }
            if ($this->request->getVar('ref_relation') == '') {
                $data['inputerror'][] = 'ref_relation';
                $data['error_string'][] = 'Guarantor Relationship is required';
                $data['status'] = FALSE;
            }
            if ($this->request->getVar('ref_job') == '') {
                $data['inputerror'][] = 'ref_job';
                $data['error_string'][] = 'Guarantor Job Address is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('ref_job'))) {
                if ($this->settings->validateName($this->request->getVar('ref_job')) == TRUE) {
                    if (strlen(trim($this->request->getVar('ref_job'))) < 4) {
                        $data['inputerror'][] = 'ref_job';
                        $data['error_string'][] = 'Guarantor Job Address is too short';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->settings->validateName($this->request->getVar('ref_job')) == FALSE) {
                    $data['inputerror'][] = 'ref_job';
                    $data['error_string'][] = 'Valid Guarantor Job Address is required';
                    $data['status'] = FALSE;
                }
            }
            if ($this->request->getVar('ref_contact') == '') {
                $data['inputerror'][] = 'ref_contact';
                $data['error_string'][] = 'Phone Number is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('ref_contact'))) {
                # check whether the first phone number is with +256
                if (substr($this->request->getVar('ref_contact'), 0, 4) == '+256') {
                    if (
                        strlen($this->request->getVar('ref_contact')) > 13 ||
                        strlen($this->request->getVar('ref_contact')) < 13
                    ) {
                        $data['inputerror'][] = 'ref_contact';
                        $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with 0
                else if (substr($this->request->getVar('ref_contact'), 0, 1) == '0') {
                    if (
                        strlen($this->request->getVar('ref_contact')) > 10 ||
                        strlen($this->request->getVar('ref_contact')) < 10
                    ) {
                        $data['inputerror'][] = 'ref_contact';
                        $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                        $data['status'] = FALSE;
                    }
                } else if (substr($this->request->getVar('ref_contact'), 0, 1) == '+') {
                    if (
                        strlen($this->request->getVar('ref_contact')) > 13 ||
                        strlen($this->request->getVar('ref_contact')) < 13
                    ) {
                        $data['inputerror'][] = 'ref_contact';
                        $data['error_string'][] = 'Should have 13 digits with Country Code';
                        $data['status'] = FALSE;
                    }
                } else {
                    $data['inputerror'][] = 'ref_contact';
                    $data['error_string'][] = 'Valid Phone Number is required';
                    $data['status'] = FALSE;
                }
                # check whether the phone number is valid
                if ($this->settings->validatePhoneNumber($this->request->getVar('ref_contact')) == FALSE) {
                    $data['inputerror'][] = 'ref_contact';
                    $data['error_string'][] = 'Valid Phone Number is required';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('ref_alt_contact'))) {
                # check whether the first phone number is with +256
                if (substr($this->request->getVar('ref_alt_contact'), 0, 4) == '+256') {
                    if (
                        strlen($this->request->getVar('ref_alt_contact')) > 13 ||
                        strlen($this->request->getVar('ref_alt_contact')) < 13
                    ) {
                        $data['inputerror'][] = 'ref_alt_contact';
                        $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with 0
                else if (substr($this->request->getVar('ref_alt_contact'), 0, 1) == '0') {
                    if (
                        strlen($this->request->getVar('ref_alt_contact')) > 10 ||
                        strlen($this->request->getVar('ref_alt_contact')) < 10
                    ) {
                        $data['inputerror'][] = 'ref_alt_contact';
                        $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                        $data['status'] = FALSE;
                    }
                } else if (substr($this->request->getVar('ref_alt_contact'), 0, 1) == '+') {
                    if (
                        strlen($this->request->getVar('ref_alt_contact')) > 13 ||
                        strlen($this->request->getVar('ref_alt_contact')) < 13
                    ) {
                        $data['inputerror'][] = 'ref_alt_contact';
                        $data['error_string'][] = 'Should have 13 digits with Country Code';
                        $data['status'] = FALSE;
                    }
                } else {
                    $data['inputerror'][] = 'ref_alt_contact';
                    $data['error_string'][] = 'Valid Phone Number is required';
                    $data['status'] = FALSE;
                }
                # check whether the phone number is valid
                if ($this->settings->validatePhoneNumber($this->request->getVar('ref_alt_contact')) == FALSE) {
                    $data['inputerror'][] = 'ref_alt_contact';
                    $data['error_string'][] = 'Valid Phone Number is required';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('ref_email'))) {
                # check whether the email is valid
                if ($this->settings->validateEmail($this->request->getVar('ref_email')) == FALSE) {
                    $data['inputerror'][] = 'ref_email';
                    $data['error_string'][] = 'Valid Email is required';
                    $data['status'] = FALSE;
                }
            }
            if ($this->request->getVar('ref_address') == '') {
                $data['inputerror'][] = 'ref_address';
                $data['error_string'][] = 'Guarantor Address is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('ref_address'))) {
                if ($this->settings->validateAddress($this->request->getVar('ref_address')) == TRUE) {
                    if (strlen(trim($this->request->getVar('ref_address'))) < 4) {
                        $data['inputerror'][] = 'ref_address';
                        $data['error_string'][] = 'Guarantor Address is too short';
                        $data['status'] = FALSE;
                    }
                }
                /*
                if ($this->settings->validateAddress($this->request->getVar('ref_address')) == FALSE) {
                    $data['inputerror'][] = 'ref_address';
                    $data['error_string'][] = 'Valid Guarantor Address is required';
                    $data['status'] = FALSE;
                }
                */
            }
            if ($this->request->getVar('ref_name2') == '') {
                $data['inputerror'][] = 'ref_name2';
                $data['error_string'][] = 'Guarantor Name is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('ref_name2'))) {
                if ($this->settings->validateName($this->request->getVar('ref_name2')) == TRUE) {
                    if (strlen(trim($this->request->getVar('ref_name2'))) < 6) {
                        $data['inputerror'][] = 'ref_name2';
                        $data['error_string'][] = 'Guarantor Full Name is too short';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->settings->validateName($this->request->getVar('ref_name2')) == FALSE) {
                    $data['inputerror'][] = 'ref_name2';
                    $data['error_string'][] = 'Valid Guarantor Full Name is required';
                    $data['status'] = FALSE;
                }
            }
            if ($this->request->getVar('ref_relation2') == '') {
                $data['inputerror'][] = 'ref_relation2';
                $data['error_string'][] = 'Guarantor Relationship is required';
                $data['status'] = FALSE;
            }
            if ($this->request->getVar('ref_job2') == '') {
                $data['inputerror'][] = 'ref_job2';
                $data['error_string'][] = 'Guarantor Job Address is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('ref_job2'))) {
                if ($this->settings->validateName($this->request->getVar('ref_job2')) == TRUE) {
                    if (strlen(trim($this->request->getVar('ref_job2'))) < 4) {
                        $data['inputerror'][] = 'ref_job2';
                        $data['error_string'][] = 'Guarantor Job Address is too short';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->settings->validateName($this->request->getVar('ref_job2')) == FALSE) {
                    $data['inputerror'][] = 'ref_job2';
                    $data['error_string'][] = 'Valid Guarantor Job Address is required';
                    $data['status'] = FALSE;
                }
            }
            if ($this->request->getVar('ref_contact2') == '') {
                $data['inputerror'][] = 'ref_contact2';
                $data['error_string'][] = 'Phone Number is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('ref_contact2'))) {
                # check whether the first phone number is with +256
                if (substr($this->request->getVar('ref_contact2'), 0, 4) == '+256') {
                    if (
                        strlen($this->request->getVar('ref_contact2')) > 13 ||
                        strlen($this->request->getVar('ref_contact2')) < 13
                    ) {
                        $data['inputerror'][] = 'ref_contact';
                        $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with 0
                else if (substr($this->request->getVar('ref_contact2'), 0, 1) == '0') {
                    if (
                        strlen($this->request->getVar('ref_contact2')) > 10 ||
                        strlen($this->request->getVar('ref_contact2')) < 10
                    ) {
                        $data['inputerror'][] = 'ref_contact2';
                        $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                        $data['status'] = FALSE;
                    }
                } else if (substr($this->request->getVar('ref_contact2'), 0, 1) == '+') {
                    if (
                        strlen($this->request->getVar('ref_contact2')) > 13 ||
                        strlen($this->request->getVar('ref_contact2')) < 13
                    ) {
                        $data['inputerror'][] = 'ref_contact2';
                        $data['error_string'][] = 'Should have 13 digits with Country Code';
                        $data['status'] = FALSE;
                    }
                } else {
                    $data['inputerror'][] = 'ref_contact2';
                    $data['error_string'][] = 'Valid Phone Number is required';
                    $data['status'] = FALSE;
                }
                # check whether the phone number is valid
                if ($this->settings->validatePhoneNumber($this->request->getVar('ref_contact2')) == FALSE) {
                    $data['inputerror'][] = 'ref_contact2';
                    $data['error_string'][] = 'Valid Phone Number is required';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('ref_alt_contact2'))) {
                # check whether the first phone number is with +256
                if (substr($this->request->getVar('ref_alt_contact2'), 0, 4) == '+256') {
                    if (
                        strlen($this->request->getVar('ref_alt_contact2')) > 13 ||
                        strlen($this->request->getVar('ref_alt_contact2')) < 13
                    ) {
                        $data['inputerror'][] = 'ref_alt_contact2';
                        $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with 0
                else if (substr($this->request->getVar('ref_alt_contact2'), 0, 1) == '0') {
                    if (
                        strlen($this->request->getVar('ref_alt_contact2')) > 10 ||
                        strlen($this->request->getVar('ref_alt_contact2')) < 10
                    ) {
                        $data['inputerror'][] = 'ref_alt_contact2';
                        $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                        $data['status'] = FALSE;
                    }
                } else if (substr($this->request->getVar('ref_alt_contact2'), 0, 1) == '+') {
                    if (
                        strlen($this->request->getVar('ref_alt_contact2')) > 13 ||
                        strlen($this->request->getVar('ref_alt_contact2')) < 13
                    ) {
                        $data['inputerror'][] = 'ref_alt_contact2';
                        $data['error_string'][] = 'Should have 13 digits with Country Code';
                        $data['status'] = FALSE;
                    }
                } else {
                    $data['inputerror'][] = 'ref_alt_contact2';
                    $data['error_string'][] = 'Valid Phone Number is required';
                    $data['status'] = FALSE;
                }
                # check whether the phone number is valid
                if ($this->settings->validatePhoneNumber($this->request->getVar('ref_alt_contact2')) == FALSE) {
                    $data['inputerror'][] = 'ref_alt_contact2';
                    $data['error_string'][] = 'Valid Phone Number is required';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('ref_email2'))) {
                # check whether the email is valid
                if ($this->settings->validateEmail($this->request->getVar('ref_email2')) == FALSE) {
                    $data['inputerror'][] = 'ref_email2';
                    $data['error_string'][] = 'Valid Email is required';
                    $data['status'] = FALSE;
                }
            }
            if ($this->request->getVar('ref_address2') == '') {
                $data['inputerror'][] = 'ref_address2';
                $data['error_string'][] = 'Guarantor Address is required';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('ref_address2'))) {
                if ($this->settings->validateAddress($this->request->getVar('ref_address2')) == TRUE) {
                    if (strlen(trim($this->request->getVar('ref_address2'))) < 4) {
                        $data['inputerror'][] = 'ref_address2';
                        $data['error_string'][] = 'Guarantor Address is too short';
                        $data['status'] = FALSE;
                    }
                }
                /*
                if ($this->settings->validateAddress($this->request->getVar('ref_address2')) == FALSE) {
                    $data['inputerror'][] = 'ref_address2';
                    $data['error_string'][] = 'Valid Guarantor Address is required';
                    $data['status'] = FALSE;
                }
                */
            }

            /*
            if ($this->request->getFileMultiple('collateral')) {
                # set validation rule
                $validationRule = [
                    'collateral[]' => [
                        "rules" => "uploaded[collateral]|max_size[collateral,5120]",
                        "label" => "Collateral Files",
                        "errors" => [
                            'max_size' => 'The size of this image(s) is too large. The image must have less than 5MB size',
                        ]
                    ],
                ];
                if (!$this->validate($validationRule) && strtolower($action) == 'add') {
                    $data['inputerror'][] = 'collateral[]';
                    $data['error_string'][] = $this->validator->getError("collateral[]") . '!';
                    $data['status'] = FALSE;
                    echo json_encode($data);
                    exit;
                }
                if (count($this->request->getFileMultiple('collateral')) > 5) {
                    $data['inputerror'][] = 'collateral[]';
                    $data['error_string'][] = "Maximum 5 Collateral Files allowed!";
                    $data['status'] = FALSE;
                    echo json_encode($data);
                    exit;
                }
            }
            */

            if (!empty($this->request->getVar('net_salary'))) {
                $sal = $this->request->getVar('net_salary');
                if (!preg_match("/^[0-9']*$/", $sal)) {
                    $data['inputerror'][] = 'net_salary';
                    $data['error_string'][] = 'Only digits allowed!';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('farming'))) {
                $farm = $this->request->getVar('farming');
                if (!preg_match("/^[0-9']*$/", $farm)) {
                    $data['inputerror'][] = 'farming';
                    $data['error_string'][] = 'Only digits allowed!';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('business'))) {
                $buz = $this->request->getVar('business');
                if (!preg_match("/^[0-9']*$/", $buz)) {
                    $data['inputerror'][] = 'business';
                    $data['error_string'][] = 'Only digits allowed!';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('others'))) {
                $others = $this->request->getVar('others');
                if (!preg_match("/^[0-9']*$/", $others)) {
                    $data['inputerror'][] = 'others';
                    $data['error_string'][] = 'Only digits allowed!';
                    $data['status'] = FALSE;
                }
            }
            if (empty($this->request->getVar('net_salary')) && empty($this->request->getVar('farming')) && empty($this->request->getVar('business')) && empty($this->request->getVar('others'))) {
                $data['inputerror'][] = 'others';
                $data['error_string'][] = 'At Least one income is required';
            }
            if (!empty($this->request->getVar('rent'))) {
                $rent = $this->request->getVar('rent');
                if (!preg_match("/^[0-9']*$/", $rent)) {
                    $data['inputerror'][] = 'rent';
                    $data['error_string'][] = 'Only digits allowed!';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('education'))) {
                $educ = $this->request->getVar('education');
                if (!preg_match("/^[0-9']*$/", $educ)) {
                    $data['inputerror'][] = 'education';
                    $data['error_string'][] = 'Only digits allowed!';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('medical'))) {
                $med = $this->request->getVar('medical');
                if (!preg_match("/^[0-9']*$/", $med)) {
                    $data['inputerror'][] = 'medical';
                    $data['error_string'][] = 'Only digits allowed!';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('transport'))) {
                $tp = $this->request->getVar('transport');
                if (!preg_match("/^[0-9']*$/", $tp)) {
                    $data['inputerror'][] = 'transport';
                    $data['error_string'][] = 'Only digits allowed!';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('exp_others'))) {
                $exp_o = $this->request->getVar('exp_others');
                if (!preg_match("/^[0-9']*$/", $exp_o)) {
                    $data['inputerror'][] = 'exp_others';
                    $data['error_string'][] = 'Only digits allowed!';
                    $data['status'] = FALSE;
                }
            }
            if (empty($this->request->getVar('rent')) && empty($this->request->getVar('education')) && empty($this->request->getVar('medical')) && empty($this->request->getVar('transport')) && empty($this->request->getVar('exp_others'))) {
                $data['inputerror'][] = 'exp_others';
                $data['error_string'][] = 'At Least one expense is required';
            }

            /*
            if ($this->request->getFileMultiple('income')) {
                $validationRule = [
                    'income[]' => [
                        "rules" => "uploaded[income]|max_size[income,5120]",
                        "label" => "Income Files",
                        "errors" => [
                            'max_size' => 'The size of this image is too large. The image must have less than 5MB size',
                        ]
                    ],
                ];
                if (!$this->validate($validationRule) && strtolower($action) == 'add') {
                    $data['inputerror'][] = 'income[]';
                    $data['error_string'][] = $this->validator->getError("income[]") . '!';
                    $data['status'] = FALSE;
                    // echo json_encode($data);
                    // exit;
                }
                if (count($this->request->getFileMultiple('income')) > 5) {
                    $data['inputerror'][] = 'income[]';
                    $data['error_string'][] = "Maximum 5 income Files allowed!";
                    $data['status'] = FALSE;
                    // echo json_encode($data);
                    // exit;
                }
            }
            if ($this->request->getFileMultiple('expense')) {
                $validationRule = [
                    'expense[]' => [
                        "rules" => "uploaded[expense]|max_size[expense,5120]",
                        "label" => "Expense Files",
                        "errors" => [
                            'max_size' => 'The size of this image is too large. The image must have less than 5MB size',
                        ]
                    ],
                ];
                if (!$this->validate($validationRule) && strtolower($action) == 'add') {
                    $data['inputerror'][] = 'expense[]';
                    $data['error_string'][] = $this->validator->getError("expense[]") . '!';
                    $data['status'] = FALSE;
                    echo json_encode($data);
                    exit;
                }
                if (count($this->request->getFileMultiple('expense')) > 5) {
                    $data['inputerror'][] = 'expense[]';
                    $data['error_string'][] = "Maximum 5 expense Files allowed!";
                    $data['status'] = FALSE;
                    echo json_encode($data);
                    exit;
                }
            }
            */

            if (!empty($this->request->getVar('institute_name'))) {
                if ($this->settings->validateName($this->request->getVar('institute_name')) == TRUE) {
                    if (strlen(trim($this->request->getVar('institute_name'))) < 5) {
                        $data['inputerror'][] = 'institute_name';
                        $data['error_string'][] = 'Institute Name is too short';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->settings->validateName($this->request->getVar('institute_name')) == FALSE) {
                    $data['inputerror'][] = 'institute_name';
                    $data['error_string'][] = 'Valid Institute Name is required';
                    $data['status'] = FALSE;
                }
                if ($this->request->getVar('institute_branch') == '') {
                    $data['inputerror'][] = 'institute_branch';
                    $data['error_string'][] = 'Institute Branch is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('institute_branch'))) {
                    if ($this->settings->validateName($this->request->getVar('institute_branch')) == TRUE) {
                        if (strlen(trim($this->request->getVar('institute_branch'))) < 5) {
                            $data['inputerror'][] = 'institute_branch';
                            $data['error_string'][] = 'Branch Name is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('institute_branch')) == FALSE) {
                        $data['inputerror'][] = 'institute_branch';
                        $data['error_string'][] = 'Valid Branch Name is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('account_type') == '') {
                    $data['inputerror'][] = 'account_type';
                    $data['error_string'][] = 'Account Type is required';
                    $data['status'] = FALSE;
                }
            }

            if (!empty($this->request->getVar('institute_name2'))) {
                if ($this->settings->validateName($this->request->getVar('institute_name2')) == TRUE) {
                    if (strlen(trim($this->request->getVar('institute_name2'))) < 5) {
                        $data['inputerror'][] = 'institute_name2';
                        $data['error_string'][] = 'Institute Name is too short';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->settings->validateName($this->request->getVar('institute_name2')) == FALSE) {
                    $data['inputerror'][] = 'institute_name2';
                    $data['error_string'][] = 'Valid Institute Name is required';
                    $data['status'] = FALSE;
                }
                if ($this->request->getVar('institute_branch2') == '') {
                    $data['inputerror'][] = 'institute_branch2';
                    $data['error_string'][] = 'Branch name is required!';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('institute_branch2'))) {
                    if ($this->settings->validateName($this->request->getVar('institute_branch2')) == TRUE) {
                        if (strlen(trim($this->request->getVar('institute_branch2'))) < 5) {
                            $data['inputerror'][] = 'institute_branch2';
                            $data['error_string'][] = 'Branch Name is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('institute_branch2')) == FALSE) {
                        $data['inputerror'][] = 'institute_branch2';
                        $data['error_string'][] = 'Valid Branch Name is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('account_type2') == '') {
                    $data['inputerror'][] = 'account_type2';
                    $data['error_string'][] = 'Account Type is required!';
                    $data['status'] = FALSE;
                }
            }

            if (!empty($this->request->getVar('amt_advance'))) {
                if (!preg_match("/^[0-9 ]+$/", $this->request->getVar('amt_advance'))) {
                    $data['inputerror'][] = 'amt_advance';
                    $data['error_string'][] = 'Valid Amount is required';
                    $data['status'] = FALSE;
                }
                if ($this->request->getVar('date_advance') == '') {
                    $data['inputerror'][] = 'date_advance';
                    $data['error_string'][] = 'Date Advance is required';
                    $data['status'] = FALSE;
                }
                if ($this->request->getVar('loan_duration') == '') {
                    $data['inputerror'][] = 'loan_duration';
                    $data['error_string'][] = 'Loan Duration is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('loan_duration'))) {
                    if (!preg_match("/^[0-9.]+$/", $this->request->getVar('loan_duration'))) {
                        $data['inputerror'][] = 'loan_duration';
                        $data['error_string'][] = 'Valid duration is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('amt_outstanding') == '') {
                    $data['inputerror'][] = 'amt_outstanding';
                    $data['error_string'][] = 'Total Amount is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('amt_outstanding'))) {
                    if (!preg_match("/^[0-9 ]+$/", $this->request->getVar('amt_outstanding'))) {
                        $data['inputerror'][] = 'amt_outstanding';
                        $data['error_string'][] = 'Valid Amount is required';
                        $data['status'] = FALSE;
                    }
                }
            }

            if (!empty($this->request->getVar('amt_advance2'))) {
                if (!preg_match("/^[0-9 ]+$/", $this->request->getVar('amt_advance2'))) {
                    $data['inputerror'][] = 'amt_advance2';
                    $data['error_string'][] = 'Valid Amount is required';
                    $data['status'] = FALSE;
                }
                if ($this->request->getVar('date_advance2') == '') {
                    $data['inputerror'][] = 'date_advance2';
                    $data['error_string'][] = 'Date Advance is required';
                    $data['status'] = FALSE;
                }
                if ($this->request->getVar('loan_duration2') == '') {
                    $data['inputerror'][] = 'loan_duration2';
                    $data['error_string'][] = 'Loan Duration is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('loan_duration2'))) {
                    if (!preg_match("/^[0-9.]+$/", $this->request->getVar('loan_duration2'))) {
                        $data['inputerror'][] = 'loan_duration2';
                        $data['error_string'][] = 'Valid duration is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('amt_outstanding2') == '') {
                    $data['inputerror'][] = 'amt_outstanding2';
                    $data['error_string'][] = 'Total Amount is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('amt_outstanding2'))) {
                    if (!preg_match("/^[0-9 ]+$/", $this->request->getVar('amt_outstanding2'))) {
                        $data['inputerror'][] = 'amt_outstanding2';
                        $data['error_string'][] = 'Valid Amount is required';
                        $data['status'] = FALSE;
                    }
                }
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function loginClientNow($client)
    {
        # generate unique random token
        $token = $this->settings->generateRandomNumbers(32);
        # get client information
        $agent = $this->request->getUserAgent();
        # update client token information
        $save = $this->client->update($client["id"], [
            'token' => password_hash($token, PASSWORD_DEFAULT),
            'token_expire_date' => date("Y-m-d H:i:s")
        ]);

        $ip_address = $this->request->getIPAddress();
        # $ip_address = "41.75.191.108";
        # check internet connect
        $checkInternet = $this->settings->checkNetworkConnection();
        if ($checkInternet) {
            # member computer has internet connection
            $geoData = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip_address));

            # check the geo data existance
            if ($geoData) {
                # check whether the geo data status
                if ($geoData['geoplugin_status'] === 200) {
                    # fetch the geo information
                    $city = $geoData['geoplugin_city'];
                    $region = $geoData['geoplugin_region'];
                    $country = $geoData['geoplugin_countryName'];
                    $latitude = $geoData['geoplugin_latitude'];
                    $longitude = $geoData['geoplugin_longitude'];
                    $continent = $geoData['geoplugin_continentName'];
                    $location = $city . ', ' . $region . ', ' . $country . ' ' . $continent;
                } else {
                    # set the geo data to null incase the status is 404
                    $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = '';
                }
            } else {
                # set the geo data to null incase the status is 404
                $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = '';
            }
        } else {
            # set the geo data to null incase the system is not connected
            # to the internet
            $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = '';
        }

        if ($save) {
            # save user logs information
            $userlog_id = $this->userLog->insert([
                'client_id' => $client['id'],
                'token' => password_hash($token, PASSWORD_DEFAULT),
                'ip_address' => $ip_address,
                'browser' => $agent->getBrowser(),
                'browser_version' => $agent->getVersion(),
                'operating_system' => $agent->getPlatform(),
                'referrer_link' => $agent->getReferrer(),
                'loginfo' => $agent->getAgentString(),
                'location' => $location,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);
            // echo json_encode($userlog_id); exit();
            // $sessionData = $this->setUserSession($user , $userlog_id,  $token);
            # set user information on the session
            $clientSessionData = [
                'client_id' => $client['id'],
                'userlog_id' => $userlog_id,
                'name' => $client['name'],
                'email' => $client['email'],
                'branch_id' => $client['branch_id'],
                'photo' => $client['photo'],
                'token' => $token,
                'clientLoggedIn' => true,
            ];
            $this->session->set($clientSessionData);
            # set user information on the session
            # $this->setUserSession($user);
            $response = [
                'status' => true,
                'error' => null,
                'url' => base_url('client/dashboard'),
                'messages' => "Success. Redirecting to dashboard"
            ];

            # prevent admin mulitple login
            # $prevent = $this->preventAdminMultipleLogin($user['id']);
            # check whether the admin already logged in
            /*
            if ($prevent == 1) {
                # user already logged in some where
                $response = [
                    'status' => 500,
                    'error' => "alreadylogin",
                    'messages' => "Error: Multiple Login is Not Allowed!"
                ];
                return $this->respond($response);
            }else{
                $response = [
                    'status' => true,
                    'error' => null,
                    'messages' => "loginSuccess"
                ];
                return $this->respond($response);
            }
            */
        } else {
            $response = [
                'status' => 500,
                'error' => 'tokenNotSaved',
                'messages' => "Token caused External Error. Try again"
            ];
        }
    }

    public function maskPhoneNumber($phoneNumber)
    {
        # Determine the number of digits to mask (8 or 11)
        if (strlen($phoneNumber) == 10) {
            # code...
            $numDigitsToMask = min(strlen($phoneNumber), 8);
        } else {
            $numDigitsToMask = min(strlen($phoneNumber), 11);
        }

        # Create a string of asterisks with the same length as the digits to mask
        $maskedPart = str_repeat('*', $numDigitsToMask);

        # Replace the first 8 or 11 digits with asterisks
        $maskedPhoneNumber = substr_replace($phoneNumber, $maskedPart, 0, $numDigitsToMask);

        return $maskedPhoneNumber;
    }

    public function maskEmail($email)
    {
        return preg_replace("/(?!^).(?=[^@]+@)/", "*", $email);
    }

    # return client total loan, active applications & disbursements
    public function clientCounter($count)
    {
        switch ($count) {
            case 'totalLoan':
                $value = $this->settings->sum_column('disbursements', 'actual_repayment', [
                    'deleted_at' => null,
                    'client_id' => $this->userRow['id'],
                    'class !=' => 'Cleared'
                ], 'disbursements');
                break;

            case 'totalLoanPaid':
                $value = $this->settings->sum_column('disbursements', 'total_collected', [
                    'deleted_at' => null,
                    'client_id' => $this->userRow['id'],
                    'class !=' => 'Cleared'
                ], 'disbursements');
                break;

            case 'totalLoanBalance':
                $value = $this->settings->sum_column('disbursements', 'total_balance', [
                    'deleted_at' => null,
                    'client_id' => $this->userRow['id'],
                    'class !=' => 'Cleared'
                ], 'disbursements');
                break;

            case 'applications':
                $value = $this->settings->countResults('loanapplications', [
                    'client_id' => $this->userRow['id'],
                    'status' => 'Pending',
                    'status' => 'Processing',
                    'status' => 'Approved',
                ]);
                break;

            default:
                $value =  null;
                break;
        }

        return $value;
    }
}
