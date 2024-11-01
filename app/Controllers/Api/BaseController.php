<?php

namespace App\Controllers\Api;

use Ramsey\Uuid\Uuid;

use \App\Models\FileModel;
use \App\Models\MenuModel;
use \App\Models\UserModel;
use App\Libraries\Api\IoTecPaySDK;
# Africstalking API Gateway
use CodeIgniter\I18n\Time;
use \App\Models\StaffModel;
use CodeIgniter\Files\File;
use \App\Models\BranchModel;
use \App\Models\ClientModel;
use \App\Models\ReportModel;
use \App\Models\EntriesModel;
use \App\Models\SettingModel;
use \App\Models\UserLogModel;
use \App\Models\PositionModel;
use App\Models\APIRequestModel;
use \App\Models\CategoriesModel;
use \App\Models\CurrenciesModel;
use \App\Models\DepartmentModel;
use \App\Models\EntryTypesModel;
use \App\Models\ParticularModel;
use \App\Models\StatementsModel;
use \App\Models\SubCategoryModel;
use \App\Models\AccountTypesModel;
use \App\Models\DisbursementModel;
use \App\Models\LoanProductsModel;
use \App\Models\UserActivityModel;
use CodeIgniter\API\ResponseTrait;
use \App\Models\CashFlowTypesModel;
use \App\Models\LoanApplicationModel;
use AfricasTalking\SDK\AfricasTalking;
use \App\Models\ApplicationRemarksModel;
use function PHPUnit\Framework\fileExists;
use CodeIgniter\RESTful\ResourceController;

class BaseController extends ResourceController
{
    use ResponseTrait;
    protected $accountType;
    protected $applicationRemark;
    protected $branch;
    protected $cashFlow;
    protected $category;
    protected $client;
    protected $currency;
    protected $department;
    protected $disbursement;
    protected $entry;
    protected $entryType;
    protected $file;
    protected $loanApplication;
    protected $loanProduct;
    protected $menuModel;
    protected $menuItem;
    protected $particular;
    protected $position;
    protected $report;
    protected $settings;
    protected $staff;
    protected $statement;
    protected $subcategory;
    protected $userActivity;
    protected $userLog;
    protected $user;
    //  global variable declaration
    protected $session;
    protected $dompdf;
    protected $menu;
    private $settingId = 1;
    protected $settingsRow = [];
    protected $subTitle1;
    protected $subTitle2;
    protected $title;
    protected $userRow;
    protected $userPermissions = [];
    # Africstalking API gateway
    # $username = "sandbox";
    # $apiKey = getenv("API_KEY");
    protected $AT, $username, $apiKey, $smsAPI;

    # IoTecPay API Gateway
    protected $clientId, $clientSecret, $walletId;
    protected $ioTecPaySDK;

    /**
     * constructor
     */
    public function __construct()
    {
        # IoTec Pay API Gateway
        $this->clientId = env("IOTECPAY_CLIENT_ID", "YOUR_IOTECPAY_CLIENT_ID");
        $this->clientSecret = env("IOTECPAY_CLIENT_SECRET", "YOUR_IOTECPAY_CLIENT_SECRET");
        $this->walletId = env("IOTECPAY_WALLET_ID", "YOUR_IOTECPAY_WALLET_ID");
        # create the instance of IoTecPaySDK
        $this->ioTecPaySDK = new IoTecPaySDK($this->clientId, $this->clientSecret, $this->walletId);
        # Africastalking API Gateway
        # use 'sandbox' for development in the test environment
        $this->username = env("AT_USERNAME", "YOUR_USERNAME");
        # use your sandbox app API key for development in the test environment
        $this->apiKey   = env("AT_API_KEY", "YOUR_API_KEY");
        # create the instance of AfricasTalking
        $this->AT       = new AfricasTalking($this->username, $this->apiKey);
        # SMS one of the services
        $this->smsAPI   = $this->AT->sms();
        # load models
        $this->accountType = new AccountTypesModel();
        $this->applicationRemark = new ApplicationRemarksModel();
        $this->branch = new BranchModel();
        $this->cashFlow = new CashFlowTypesModel();
        $this->category = new CategoriesModel();
        $this->client = new ClientModel();
        $this->currency = new CurrenciesModel();
        $this->department = new DepartmentModel();
        $this->disbursement = new DisbursementModel();
        $this->entry = new EntriesModel();
        $this->entryType = new EntryTypesModel();
        $this->file = new FileModel();
        $this->loanApplication = new LoanApplicationModel();
        $this->loanProduct = new LoanProductsModel();
        $this->menuModel = new MenuModel();
        $this->particular = new ParticularModel();
        $this->position = new PositionModel();
        $this->report = new ReportModel();
        $this->settings = new SettingModel();
        $this->staff = new StaffModel();
        $this->statement = new StatementsModel();
        $this->subcategory = new SubCategoryModel();
        $this->userActivity = new UserActivityModel();
        $this->userLog = new UserLogModel();
        $this->user = new UserModel();
        # load session
        $this->session = session();
        # load settings
        $this->settingsRow = $this->settings->select('settings.*, currencies.currency,  currencies.symbol')->join('currencies', 'currencies.id = settings.currency_id')->find($this->settingId);
    }

    private function logApiRequest($result, $responseStatusCode, $errorMessage = NULL)
    {
        $request = service('request');

        $uuid = Uuid::uuid4()->toString(); // Generate a version 4 (random) UUID

        $status = ($responseStatusCode >= 200 && $responseStatusCode < 400) ? 'SUCCESS' : 'FAILED';

        $data = [
            'url' => $request->uri->getPath(),
            'method' => $request->getMethod(),
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent(),
            'status' => $status,
            'output' => json_encode($result),
            'error_message' => $errorMessage,
            'uuid' => $uuid
        ];

        if (!empty($request->getJSON())) {
            # code...
            $data['input'] = json_encode($request->getJSON()); // Assuming the input is JSON
        }

        $model = new APIRequestModel();

        $save = $model->insert($data);
    }

    public function sendResponse($result = [], $message, $code = 200)
    {
        try {

            $response = [
                'status' => true,
                'message' => $message,
            ];

            if (!empty($result)) {
                $response['data'] = $result;
            }

            # Add the API Version
            $response['version'] = '1.0.0';

            # Log the request
            $this->logApiRequest($response, $code);

            return $this->respond($response, $code);
        } catch (\Exception $e) {
            # Log the request
            # $this->updateRequestStatus('FAILED', $e->getMessage());
            $this->logApiRequest([], 500, $e->getMessage());

            # Return an error response
            return $this->failServerError('External Error Occurred' . $e->getMessage());
        }
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        try {

            $response = [
                'status' => false,
                'message' => $error,
            ];


            if (!empty($errorMessages)) {
                $response['data'] = $errorMessages;
            }

            # Add the API Version
            $response['version'] = '1.0.0';

            # Log the request
            $this->logApiRequest($response, $code, $error);

            return $this->respond($response, $code);
        } catch (\Exception $e) {
            # Log the request
            # $this->updateRequestStatus('FAILED', $e->getMessage());
            $this->logApiRequest([], 500, $e->getMessage());

            # Return an error response
            return $this->failServerError('External Error Occurred.' . $e->getMessage());
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
            ->join('categories', 'categories.id = particulars.category_id')
            ->join('subcategories', 'subcategories.id = particulars.subcategory_id')
            ->join('statements', 'statements.id = categories.statement_id')
            ->join('account_types', 'account_types.id = particulars.account_typeId')
            ->join('cash_flow_types', 'cash_flow_types.id = particulars.cash_flow_typeId')
            ->find($id);
        return $row;
    }

    protected function getClientByID($id)
    {
        $client = $this->client
            ->select('clients.id, clients.name, clients.branch_id, clients.staff_id, clients.account_no, clients.account_type, clients.account_balance, clients.email, clients.mobile, clients.alternate_no, clients.gender, clients.dob, clients.marital_status, clients.religion, clients.nationality, clients.occupation, clients.job_location, clients.residence, clients.id_type, clients.id_number, clients.id_expiry_date, clients.next_of_kin_name, clients.next_of_kin_relationship, clients.next_of_kin_contact, clients.next_of_kin_alternate_contact, clients.nok_email, clients.nok_address, clients.photo, clients.id_photo_front, clients.id_photo_back, clients.signature, clients.access_status, clients.reg_date,clients.created_at, clients.updated_at, branches.branch_name, staffs.staff_name, staffs.staffID')
            ->join('branches', 'branches.id = clients.branch_id')
            ->join('staffs', 'staffs.id = clients.staff_id')
            ->find($id);
        if ($client) {
            # Remove some client fields
            # unset($client['password']);
            # unset($client['token']);
            # unset($client['token_expire_date']);
            unset($client['deleted_at']);
        }
        return $client;
    }

    protected function getLoanApplicationById($id)
    {
        $row = $this->loanApplication
            ->select('loanapplications.*, clients.id as c_id, clients.name, clients.account_no, clients.email, clients.mobile, clients.residence, clients.photo, clients.id_number, clients.next_of_kin_name, clients.next_of_kin_contact, clients.gender, clients.religion, clients.marital_status, clients.nationality, clients.alternate_no, clients.dob, clients.occupation, clients.job_location, clients.id_type, clients.id_expiry_date, clients.next_of_kin_relationship, clients.next_of_kin_alternate_contact, clients.nok_email, clients.nok_address, clients.account_type as acc_type,, clients.signature as sign, clients.id_photo_front, clients.id_photo_back, loanproducts.product_name, loanproducts.interest_rate, loanproducts.interest_type, loanproducts.repayment_period, loanproducts.repayment_duration, loanproducts.repayment_freq, branches.branch_name, staffs.staff_name, staffs.staffID')
            ->join('clients', 'clients.id = loanapplications.client_id')
            ->join('staffs', 'staffs.id = loanapplications.staff_id')
            ->join('loanproducts', 'loanproducts.id = loanapplications.product_id')
            ->join('branches', 'branches.id = clients.branch_id')
            ->find($id);
        return $row;
    }

    protected function getLoanDisbursementById($id)
    {
        $row = $this->disbursement
            ->select('disbursements.*, branches.branch_name,  staffs.staff_name, staffs.signature, clients.id as client_id, clients.name, clients.account_no, clients.account_balance, clients.email, clients.mobile, clients.residence, clients.photo, clients.id_number, clients.next_of_kin_name, clients.next_of_kin_contact,clients.nok_email, clients.nok_address, clients.gender, clients.religion, clients.nationality, clients.marital_status, clients.alternate_no, clients.dob, clients.occupation, clients.job_location, clients.id_type, clients.id_expiry_date, clients.next_of_kin_relationship, clients.next_of_kin_alternate_contact, clients.account_type, clients.signature as sign, clients.id_photo_front, clients.id_photo_back, loanproducts.product_name, interest_rate, loanproducts.interest_type, loanproducts.repayment_period, loanproducts.repayment_duration, loanproducts.repayment_freq, loanapplications.application_code, loanapplications.purpose,loanapplications.overall_charges, loanapplications.total_charges, loanapplications.reduct_charges, loanapplications.security_item, loanapplications.security_info, loanapplications.est_value, loanapplications.ref_name, loanapplications.ref_address, loanapplications.ref_job, loanapplications.ref_contact, loanapplications.ref_alt_contact, loanapplications.ref_email, loanapplications.ref_relation, loanapplications.ref_name2, loanapplications.ref_address2, loanapplications.ref_job2, loanapplications.ref_contact2, loanapplications.ref_alt_contact2, loanapplications.ref_email2, loanapplications.ref_relation2')
            ->join('clients', 'clients.id = disbursements.client_id')
            ->join('staffs', 'staffs.id = disbursements.staff_id')
            ->join('branches', 'branches.id = clients.branch_id')
            ->join('loanproducts', 'loanproducts.id = disbursements.product_id')
            ->join('loanapplications', 'loanapplications.id = disbursements.application_id')
            ->find($id);
        return $row;
    }

    protected function getLoanDisbursements($condition)
    {
        $disbursements = $this->disbursement
            ->select('disbursements.*, branches.branch_name,  staffs.staff_name, staffs.signature, clients.id as client_id, clients.name, clients.account_no, clients.account_balance, clients.email, clients.mobile, clients.residence, clients.photo, clients.id_number, clients.next_of_kin_name, clients.next_of_kin_contact,clients.nok_email, clients.nok_address, clients.gender, clients.religion, clients.nationality, clients.marital_status, clients.alternate_no, clients.dob, clients.occupation, clients.job_location, clients.id_type, clients.id_expiry_date, clients.next_of_kin_relationship, clients.next_of_kin_alternate_contact, clients.account_type, clients.signature as sign, clients.id_photo_front, clients.id_photo_back, loanproducts.product_name, interest_rate, loanproducts.interest_type, loanproducts.repayment_period, loanproducts.repayment_duration, loanproducts.repayment_freq, loanapplications.application_code, loanapplications.purpose,loanapplications.overall_charges, loanapplications.total_charges, loanapplications.reduct_charges, loanapplications.security_item, loanapplications.security_info, loanapplications.est_value, loanapplications.ref_name, loanapplications.ref_address, loanapplications.ref_job, loanapplications.ref_contact, loanapplications.ref_alt_contact, loanapplications.ref_email, loanapplications.ref_relation, loanapplications.ref_name2, loanapplications.ref_address2, loanapplications.ref_job2, loanapplications.ref_contact2, loanapplications.ref_alt_contact2, loanapplications.ref_email2, loanapplications.ref_relation2, loanapplications.applicant_products')
            ->join('clients', 'clients.id = disbursements.client_id')
            ->join('staffs', 'staffs.id = disbursements.staff_id')
            ->join('branches', 'branches.id = clients.branch_id')
            ->join('loanproducts', 'loanproducts.id = disbursements.product_id')
            ->join('loanapplications', 'loanapplications.id = disbursements.application_id')
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
        $allDisbursements = $this->disbursement->where(['class !=' => 'Cleared'])->findAll();
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
                } else { // make days coverd expired if
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
                // calculate amount expected to be recoved
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
                # update the dusbursement information 
                $updateDays = $this->disbursement->update($row['id'], $autoData);
            }

            if ($updateDays) {
                $response = [
                    'status' => 200,
                    'error' => null,
                    'message' => 'Auto Disbursment Fields Update Success',
                ];
                return $this->respond($response);
                exit;
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Update Failed',
                    'message' => 'Auto Disbursment Fields Update Failed!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'message' => 'No Disbursments Found!',
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
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
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
            ->join('categories', 'categories.id = account_types.category_id')
            ->where($where)->findAll();
        return $this->respond($data);
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

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        return $this->sendResponse([
            'settings' => $this->settingsRow,
        ], 'Business Information');
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        return $this->sendError('You\'re not Authorized to perform this.', [], 401);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        return $this->sendError('You\'re not Authorized to perform this.', [], 401);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        return $this->sendError('You\'re not Authorized to perform this.', [], 401);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        return $this->sendError('You\'re not Authorized to perform this with id ' . $id, [], 401);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        return $this->sendError('You\'re not Authorized to perform this with id ' . $id, [], 401);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        return $this->sendError('You\'re not Authorized to perform this with id ' . $id, [], 401);
    }

    public function checkPermissions($permissions, $menuItem, $permission = null)
    {
        // create an array of permitted actions
        $actions = ['create', 'view', 'update', 'delete', 'bulkDelete'];
        $allowed = false;

        $slug = ucwords(str_replace(' ', '', $this->title));
        if($permission){
            // generate permission string by menu's concatenating action, menu & slug
            $permissionString = $permission . '_' . strtolower($menuItem['menu']) . $slug;
            // check if permission string exists in user permissions
            if ($permissions == 'all' || in_array($permissionString, $permissions)) {
                $allowed = true;
            }
        } else{
            foreach ($actions as $action) {
                // generate permission string by menu's concatenating action, menu & slug
                $permissionString = $action . '_' . strtolower($menuItem['menu']) . $slug;
                // check if permission string exists in user permissions
                if ($permissions == 'all' || in_array($permissionString, $permissions)) {
                    $allowed = true;
                    break;
                }
            }
        }

        return $allowed;
    }

    // insert into activity logs
    protected function insertActivityLog($data)
    {
        # get user information
        $agent = $this->request->getUserAgent();
        $ip_address = $this->request->getIPAddress();
        # $ip_address = "41.75.191.108";
        # check internet connection
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
        # append geo data to the activity data
        $data['ip_address'] = $ip_address;
        $data['browser'] = $agent->getBrowser();
        $data['browser_version'] = $agent->getVersion();
        $data['operating_system'] = $agent->getPlatform();
        $data['location'] = $location;
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $insert = $this->userActivity->insert($data);
        if ($insert) {
            return true;
        } else {
            return false;
        }
    }
}
