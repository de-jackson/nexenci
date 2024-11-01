<?php

namespace App\Controllers\Microfinance;

// Load CodeIgniter resources
use CodeIgniter\I18n\Time;
use CodeIgniter\Files\File;
use function PHPUnit\Framework\fileExists;
use \Hermawan\DataTables\DataTable;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
// Load plugins
use YoAPI;
use Dompdf\Dompdf;
# Africstalking API Gateway
use AfricasTalking\SDK\AfricasTalking;
use App\Libraries\Api\EgoSMSAPI;
use App\Libraries\Api\IoTecPaySDK;

// load models
use \App\Models\AccountModel;
use \App\Models\AccountTypesModel;
use App\Models\ApplicantProductsModel;
use \App\Models\ApplicationRemarksModel;
use App\Models\BlogModel;
use \App\Models\BranchModel;
use \App\Models\CategoriesModel;
use \App\Models\CashFlowTypesModel;
use App\Models\ChargeModel;
use \App\Models\ClientModel;
use \App\Models\CurrenciesModel;
use \App\Models\DepartmentModel;
use \App\Models\DisbursementModel;
use \App\Models\EmailsModel;
use \App\Models\EmailAttachmentsModel;
use \App\Models\EmailTagsModel;
use \App\Models\EntriesModel;
use \App\Models\EntryTypesModel;
use \App\Models\FileModel;
use \App\Models\LoanApplicationModel;
use \App\Models\LoanProductsModel;
use \App\Models\MenuModel;
use \App\Models\NationalitieslistModel;
use \App\Models\ParticularModel;
use \App\Models\PositionModel;
use \App\Models\PostModel;
use \App\Models\ProductsModel;
use \App\Models\ReportModel;
use \App\Models\RelationshipModel;
use \App\Models\SettingModel;
use \App\Models\StaffModel;
use \App\Models\StatementsModel;
use \App\Models\SubCategoryModel;
use \App\Models\UserModel;
use \App\Models\UserActivityModel;
use \App\Models\UserLogModel;

class BaseController extends ResourceController
{
    protected $account;
    protected $accountType;
    protected $applicantProduct;
    protected $applicationRemark;
    protected $attachment;
    protected $blog;
    protected $branch;
    protected $cashFlow;
    protected $category;
    protected $chargeModel;
    protected $client;
    protected $currency;
    protected $department;
    protected $disbursement;
    protected $email;
    protected $emailTags;
    protected $entry;
    protected $entryType;
    protected $file;
    protected $loanApplication;
    protected $loanProduct;
    protected $menuItem;
    protected $menuModel;
    protected $nationality;
    protected $particular;
    protected $position;
    protected $post;
    protected $product;
    protected $report;
    protected $relationships;
    protected $settings;
    protected $staff;
    protected $statement;
    protected $subcategory;
    protected $userActivity;
    protected $userLog;
    protected $user;
    protected $session;
    protected $dompdf;
    protected $menu;
    protected $settingId = 1;
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
    protected $yoAPI;
    protected $egoAPI;

    protected $encrypter;
    /**
     * constructor
     */
    public function __construct()
    {
        # YO PAYMENTS API Gateway
        $username = env("YO_PAYMENT_USERNAME", "YO_PAYMENT_USERNAME");
        $password = env("YO_PAYMENT_PASSWORD", "YO_PAYMENT_PASSWORD");
        $mode = env("YO_PAYMENT_MODE", "sandbox");
        $this->yoAPI = new YoAPI($username, $password, $mode);
        # IoTec Pay API Gateway
        $this->clientId = env("IOTECPAY_CLIENT_ID", "YOUR_IOTECPAY_CLIENT_ID");
        $this->clientSecret = env("IOTECPAY_CLIENT_SECRET", "YOUR_IOTECPAY_CLIENT_SECRET");
        $this->walletId = env("IOTECPAY_WALLET_ID", "YOUR_IOTECPAY_WALLET_ID");
        # create the instance of IoTecPaySDK
        $this->ioTecPaySDK = new IoTecPaySDK($this->clientId, $this->clientSecret, $this->walletId);
        $this->egoAPI = new EgoSMSAPI();
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
        $this->post = new PostModel();
        $this->blog = new BlogModel();
        $this->account = new AccountModel();
        $this->accountType = new AccountTypesModel();
        $this->applicantProduct = new ApplicantProductsModel();
        $this->chargeModel = new ChargeModel();
        $this->applicationRemark = new ApplicationRemarksModel();
        $this->attachment = new EmailAttachmentsModel();
        $this->branch = new BranchModel();
        $this->cashFlow = new CashFlowTypesModel();
        $this->category = new CategoriesModel();
        $this->client = new ClientModel();
        $this->currency = new CurrenciesModel();
        $this->department = new DepartmentModel();
        $this->disbursement = new DisbursementModel();
        $this->email = new EmailsModel();
        $this->emailTags = new EmailTagsModel();
        $this->entry = new EntriesModel();
        $this->entryType = new EntryTypesModel();
        $this->file = new FileModel();
        $this->loanApplication = new LoanApplicationModel();
        $this->loanProduct = new LoanProductsModel();
        $this->menuModel = new MenuModel();
        $this->nationality = new NationalitieslistModel();
        $this->particular = new ParticularModel();
        $this->position = new PositionModel();
        $this->product = new ProductsModel();
        $this->report = new ReportModel();
        $this->relationships = new RelationshipModel();
        $this->settings = new SettingModel();
        $this->staff = new StaffModel();
        $this->statement = new StatementsModel();
        $this->subcategory = new SubCategoryModel();
        $this->userActivity = new UserActivityModel();
        $this->userLog = new UserLogModel();
        $this->user = new UserModel();

        $this->encrypter = Services::encrypter();
        $this->dompdf = new Dompdf();
        # load session
        $this->session = session();
        # load settings
        $this->settingsRow = $this->settings
            ->select('settings.*, currencies.currency,  currencies.symbol')
            ->join('currencies', 'currencies.id = settings.currency_id', 'left')
            ->find($this->settingId);
    }

    public function load_menu()
    {
        $menus = $this->menuModel->where(['status' => 'Active'])->orderBy('order')->findAll();
        if ($menus) {
            $data = [];
            # define the system operations
            $actions = ['create', 'view', 'update', 'import', 'export', 'delete', 'bulkDelete'];
            foreach ($menus as $menu) {
                # get the menu title and capitalize the each word
                $slug = ucwords(str_replace(' ', '', $menu['title']));
                # define the system permissions
                $permissions = '_' . strtolower($menu['menu']) . $slug;
                # check whether the user permissions matches the system permissions
                if (($this->userPermissions == 'all') || (in_array('create' . $permissions, $this->userPermissions) || in_array('view' . $permissions, $this->userPermissions) || in_array('update' . $permissions, $this->userPermissions) || in_array('delete' . $permissions, $this->userPermissions))) {
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

    public function counter($table)
    {
        switch (strtolower($table)) {
            case 'emails':
                $counter = [
                    'emails' => $this->settings->countResults('emails', [
                        // 'account_id' => $this->userRow['account_id'], 
                        'deleted_at' => null
                    ]),
                    'inbox' => $this->settings->countResults(
                        'emails',
                        [
                            // 'account_id' => $this->userRow['account_id'], 
                            'recipient_id' => $this->userRow['id'],
                            "status" => 'unread',
                            'deleted_at' => null
                        ]
                    ),
                    'sent' => $this->settings->countResults('emails', [
                        // 'account_id' => $this->userRow['account_id'], 
                        'sender_id' => $this->userRow['id'],
                        "status" => 'unread',
                        'deleted_at' => null
                    ]),
                    'draft' => $this->settings->countResults('emails', [
                        // 'account_id' => $this->userRow['account_id'], 
                        'label' => "draft",
                        'deleted_at' => null
                    ]),
                    'spam' => $this->settings->countResults('emails', [
                        // 'account_id' => $this->userRow['account_id'], 
                        'label' => "spam",
                        'deleted_at' => null
                    ]),
                    'important' => $this->settings->countResults('emails', [
                        // 'account_id' => $this->userRow['account_id'], 
                        'label' => "important",
                        'deleted_at' => null
                    ]),
                    'trash' => $this->settings->countResults('emails', [
                        // 'account_id' => $this->userRow['account_id'], 
                        'label' => "trash",
                        'deleted_at' => null
                    ]),
                    'archive' => $this->settings->countResults('emails', [
                        // 'account_id' => $this->userRow['account_id'], 
                        'label' => "archive",
                        'deleted_at' => null
                    ]),
                    'favorite' => $this->settings->countResults('emails', [
                        // 'account_id' => $this->userRow['account_id'], 
                        'label' => "favorite",
                        'deleted_at' => null
                    ]),
                ];
                break;
            case 'disbursements':
                $counter = [
                    'disbursements' => $this->settings->countResults('disbursements', [
                        'account_id' => $this->userRow['account_id'],
                        'deleted_at' => null
                    ]),
                    'running' => $this->settings->countResults('disbursements', [
                        'account_id' => $this->userRow['account_id'],
                        'class' => "Running",
                        'deleted_at' => null
                    ]),
                    'arrears' => $this->settings->countResults('disbursements', [
                        'account_id' => $this->userRow['account_id'],
                        'class' => "Arrears",
                        'deleted_at' => null
                    ]),
                    'cleared' => $this->settings->countResults('disbursements', [
                        'account_id' => $this->userRow['account_id'],
                        'class' => "Cleared",
                        'deleted_at' => null
                    ]),
                    'expired' => $this->settings->countResults('disbursements', [
                        'account_id' => $this->userRow['account_id'],
                        'class' => "Expired",
                        'deleted_at' => null
                    ]),
                    'defaulted' => $this->settings->countResults('disbursements', [
                        'account_id' => $this->userRow['account_id'],
                        'class' => "Defaulted",
                        'deleted_at' => null
                    ]),
                ];
                break;
            case 'applications':
                $counter = [
                    'applications' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'deleted_at' => null
                    ]),
                    'pending' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status ' => 'Pending',
                        'deleted_at' => null
                    ]),
                    'processing' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status ' => 'Processing',
                        'deleted_at' => null
                    ]),
                    'declined' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status ' => 'Declined',
                        'deleted_at' => null
                    ]),
                    'review' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status ' => 'Review',
                        'deleted_at' => null
                    ]),
                    'approved' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status ' => 'Approved',
                        'deleted_at' => null
                    ]),
                    'approved' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status ' => 'Approved',
                        'deleted_at' => null
                    ]),
                    'cancelled' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status ' => 'Cancelled',
                        'deleted_at' => null
                    ])
                ];
                break;
            case 'subcategories':
                $counter = [
                    'assets' => $this->settings->countResults('subcategories', [
                        'account_id' => $this->userRow['account_id'],
                        'account_id' => null,
                        'category_id ' => '1',
                        'deleted_at' => null
                    ]),
                    'equity' => $this->settings->countResults('subcategories', [
                        'account_id' => $this->userRow['account_id'],
                        'account_id' => null,
                        'category_id ' => '2',
                        'deleted_at' => null
                    ]),
                    'liabilities' => $this->settings->countResults('subcategories', [
                        'account_id' => $this->userRow['account_id'],
                        'account_id' => null,
                        'category_id ' => '3',
                        'deleted_at' => null
                    ]),
                    'revenue' => $this->settings->countResults('subcategories', [
                        'account_id' => $this->userRow['account_id'],
                        'account_id' => null,
                        'category_id ' => '4',
                        'deleted_at' => null
                    ]),
                    'expenses' => $this->settings->countResults('subcategories', [
                        'account_id' => $this->userRow['account_id'],
                        'account_id' => null,
                        'category_id ' => '5',
                        'deleted_at' => null
                    ]),
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
    // return counter as json array
    public function json_counter($table)
    {
        return $this->respond(($this->counter($table)));
    }

    protected function product($id)
    {
        $data = $this->loanProduct->find($id);
        if ($data) {
            $data['charges'] = $this->getCharges([
                'charges.product_id' => $id,
                'charges.status' => 'Active',
            ]);
            // $data['productCharges'] = unserialize($data['product_charges']);
            // $data['charges'] = $this->application_chargesParticulars();
        }

        return $data;
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
        if ($row) {
            $charges = $this->getCharges([
                'charges.status' => 'Active',
                'charges.particular_id' => $row['id'],
                'charges.product_id' => null
            ]);
            $row['particular_charges'] = $charges;
            # Check the charges column 
            if (strtolower($row['charged']) == "yes" && !empty($row['particular_charges'])) {
                # code...
                $row['particular_active_charges'] = $this->particularCharges($charges);
            } else {
                $row['particular_active_charges'] = NULL;
            }
        }
        return $row;
    }

    public function getCharges(array $condition)
    {
        $charges = $this->chargeModel->select('charges.*, p.particular_name, p.charged')
            ->join('particulars as p', 'charges.particular_id = p.id', 'left')
            ->join('account_types as a', 'p.account_typeId = a.id', 'left')
            ->orderBy('charges.effective_date', 'desc')
            ->where($condition)->findAll();
        return $charges;
    }

    public function particularCharges(array $charges)
    {
        $activeCharges = [];
        $currentDate = date('Y-m-d');
        foreach ($charges as $key => $row) {
            $effectiveDate = date('Y-m-d', strtotime($row['effective_date']));
            # Skip the particular charges that are inactive
            if (strtolower($row['status']) == 'inactive') {
                # code...
                continue;
            }
            # Skip the particular charges whose effective data is a head
            if ($effectiveDate > $currentDate) {
                # code...
                continue;
            }
            $activeCharges[] = $row;
        }

        return $activeCharges;
    }

    protected function clientDataRow($id)
    {
        $data = $this->client
            ->select('clients.id, clients.name, clients.title, clients.branch_id, clients.staff_id, clients.account_no, clients.account_type, clients.savings_products, clients.account_balance, clients.email, clients.mobile, clients.alternate_no, clients.gender, clients.dob, clients.marital_status, clients.religion, clients.nationality, clients.occupation, clients.job_location, clients.residence, clients.closest_landmark, clients.id_type, clients.id_number, clients.id_expiry_date, clients.next_of_kin_name, clients.next_of_kin_relationship, clients.next_of_kin_contact, clients.next_of_kin_alternate_contact, clients.nok_email, clients.nok_address, clients.photo, clients.id_photo_front, clients.id_photo_back, clients.signature, clients.access_status, clients.2fa, clients.account, clients.reg_date, clients.created_at, clients.updated_at, branches.branch_name, staffs.staff_name, staffs.staffID')
            ->join('branches', 'branches.id = clients.branch_id', 'left')
            ->join('staffs', 'staffs.id = clients.staff_id', 'left')
            ->find($id);
        $data['age'] = (isset($data['dob'])) ? (date('Y', strtotime('now')) - date('Y', strtotime($data['dob']))) : '';
        $data['ecryptedId'] = bin2hex($this->encrypter->encrypt($data['id']));

        if (isset($data['savings_products']) && $data['savings_products']) {
            $savingsProducts = json_decode($data['savings_products']);
            // fetch products information
            $productData = $this->get_productsDetails($savingsProducts, 'savings');

            // Assign $productData to $data['savingsProducts']
            $data['savingsProducts'] = $productData;
        } else {
            $data['savingsProducts'] = null;
        }
        // fetch client relations
        $data['nok'] = $this->getRelationship('nok', $id);
        // fetch client shares
        $data['sharesBalance'] = $this->client_sharesBalance($id);
        // fetch client loans
        $data['disbursements'] = $this->client_loans($id);
        
        return $data;
    }

    protected function loanApplicationRow($id)
    {
        $data = $this->loanApplication
            ->select('loanapplications.*, clients.id as c_id, clients.name, clients.title, clients.account_no, clients.account_balance, clients.savings_products, clients.email, clients.mobile, clients.residence, clients.closest_landmark, clients.photo, clients.id_number, clients.next_of_kin_name, clients.next_of_kin_contact, clients.gender, clients.religion, clients.marital_status, clients.nationality, clients.alternate_no, clients.dob, clients.occupation, clients.job_location, clients.id_type, clients.id_expiry_date, clients.next_of_kin_relationship, clients.next_of_kin_alternate_contact, clients.nok_email, clients.nok_address, clients.account_type as acc_type, clients.signature as sign, clients.id_photo_front, clients.id_photo_back, loanproducts.product_name, loanproducts.principal_particular_id, loanproducts.interest_particular_id, branches.branch_name, staffs.staff_name, staffs.staffID, staffs.signature, applicant_products.interest_rate, applicant_products.repayment_frequency, applicant_products.repayment_period, applicant_products.interest_period, applicant_products.interest_type, applicant_products.id as applicant_product_id, applicant_products.loan_period, applicant_products.loan_frequency')
            ->join('clients', 'clients.id = loanapplications.client_id', 'left')
            ->join('staffs', 'staffs.id = loanapplications.staff_id', 'left')
            ->join('loanproducts', 'loanproducts.id = loanapplications.product_id', 'left')
            ->join('applicant_products', 'applicant_products.application_id = loanapplications.id', 'left')
            ->join('branches', 'branches.id = clients.branch_id', 'left')
            ->find($id);
        if ($data) {
            $data['ecryptedId'] = bin2hex($this->encrypter->encrypt($data['id']));
            $data['charges'] = unserialize($data['overall_charges']);
            $applicantProduct = $this->applicantProduct->where(['application_id' => $id])->first();
            if ($applicantProduct) {
                $data['applicantProduct'] = $applicantProduct;
                $data['applicant_products'] = $applicantProduct;
                $other = $this->loanProduct->getOtherLoanProduct($applicantProduct['repayment_frequency']);
                $data['repayment_duration'] = $other['duration'];
            } else {
                $data['repayment_duration'] = NULL;
            }

            if (isset($data['savings_products']) && $data['savings_products']) {
                $savingsProducts = json_decode($data['savings_products']);
                // fetch products information
                $productData = $this->get_productsDetails($savingsProducts, 'savings');
    
                // Assign $productData to $data['savingsProducts']
                $data['savingsProducts'] = $productData;
            } else {
                $data['savingsProducts'] = null;
            }
        }
        return $data;
    }

    protected function loanDisbursementRow($id)
    {
        $data = $this->disbursement
            ->select('disbursements.*, branches.branch_name,  staffs.staff_name, staffs.signature, clients.id as client_id, clients.name, clients.title, clients.account_no, clients.account_balance, clients.savings_products, clients.email, clients.mobile, clients.residence, clients.closest_landmark, clients.photo, clients.id_number, clients.next_of_kin_name, clients.next_of_kin_contact,clients.nok_email, clients.nok_address, clients.gender, clients.religion, clients.nationality, clients.marital_status, clients.alternate_no, clients.dob, clients.occupation, clients.job_location, clients.id_type, clients.id_expiry_date, clients.next_of_kin_relationship, clients.next_of_kin_alternate_contact, clients.account_type, clients.signature as sign, clients.id_photo_front, clients.id_photo_back, principalParticular.particular_name as principal_particular, interestParticular.particular_name as interest_particular, disbursementParticular.particular_name as payment_method, loanproducts.product_name, loanproducts.principal_particular_id, loanproducts.interest_particular_id, loanapplications.application_code, loanapplications.application_date, loanapplications.purpose, loanapplications.overall_charges, loanapplications.total_charges, loanapplications.reduct_charges, loanapplications.security_item, loanapplications.security_info, loanapplications.est_value, loanapplications.ref_name, loanapplications.ref_address, loanapplications.ref_job, loanapplications.ref_contact, loanapplications.ref_alt_contact, loanapplications.ref_email, loanapplications.ref_relation, loanapplications.ref_name2, loanapplications.ref_address2, loanapplications.ref_job2, loanapplications.ref_contact2, loanapplications.ref_alt_contact2, loanapplications.ref_email2, loanapplications.ref_relation2, loanapplications.applicant_products,loanapplications.status as app_status, appl_officer.staff_name as application_officer,  loanapplications.updated_at as app_created_at, loanapplications.updated_at as app_updated_at, applicant_products.interest_rate, applicant_products.repayment_frequency, applicant_products.repayment_period, applicant_products.interest_period, applicant_products.interest_type, applicant_products.id as applicant_product_id')
            ->join('clients', 'clients.id = disbursements.client_id', 'left')
            ->join('staffs', 'staffs.id = disbursements.staff_id', 'left')
            ->join('branches', 'branches.id = clients.branch_id', 'left')
            ->join('particulars as principalParticular', 'principalParticular.id = disbursements.particular_id', 'left')
            ->join('particulars as interestParticular', 'interestParticular.id = disbursements.interest_particular_id', 'left')
            ->join('particulars as disbursementParticular', 'disbursementParticular.id = disbursements.payment_id', 'left')
            ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')
            ->join('loanapplications', 'loanapplications.id = disbursements.application_id', 'left')
            ->join('staffs as appl_officer', 'appl_officer.id = loanapplications.staff_id', 'left')
            ->join('applicant_products', 'applicant_products.application_id = disbursements.application_id', 'left')
            ->find($id);
        if ($data) {
            $data['ecryptedId'] = bin2hex($this->encrypter->encrypt($data['id']));
            # unserialize applicant loan product information
            $data['charges'] = json_decode($data['overall_charges']);
            $data['arrearsInfo'] = json_decode($data['arrears_info']);
            $applicantProduct = $this->applicantProduct->where([
                'application_id' => $data['application_id']
            ])->first();
            if ($applicantProduct) {
                $data['applicantProduct'] = $applicantProduct;
                $other = $this->loanProduct->getOtherLoanProduct($applicantProduct['repayment_frequency']);
                $data['repayment_duration'] = $other['duration'];
            } else {
                $data['repayment_duration'] = NULL;
            }

            if (isset($data['savings_products']) && $data['savings_products']) {
                $savingsProducts = json_decode($data['savings_products']);
                // fetch products information
                $productData = $this->get_productsDetails($savingsProducts, 'savings');
    
                // Assign $productData to $data['savingsProducts']
                $data['savingsProducts'] = $productData;
            } else {
                $data['savingsProducts'] = null;
            }
        }

        return $data;
    }

    protected function getRelationship($type, $id){
        if ($type == 'nok') {
            $where = ['type' => 'next_of_kin', 'client_id' => $id];
        } else {
            $where = ['type' => 'guarantor', 'application_id' => $id];
        }
        $data = $this->relationships->select('id as relation_id, full_name as relation_name, contact as relation_contact, alternate_contact as relation_alternate_contact, email as relation_email, address as relation_address, relationship as relation_relationship')->where($where)->findAll();
        
        return $data;
    }

    // get products details
    protected function get_productsDetails(array $products, $type = 'loans')
    {
        $productData = []; // Initialize an empty array to store product data

        foreach ($products as $item) {
            if ($type == 'loans') {
                $productRow = $this->loanProduct->find($item->product_id);
            } else {
                $productRow = $this->product->select('products.*, savingsParticular.particular_name')->join('particulars as savingsParticular', 'savingsParticular.id = products.savings_particular_id', 'left')->find($item->product_id);
            }

            // Construct a new product object with required fields
            $productObject = [
                'product_id' => $item->product_id,
                'product_code' => $productRow['product_code'],
                'savings_particular_id' => $productRow['savings_particular_id'],
                'savings_particular' => $productRow['particular_name'],
                'product_name' => $productRow['product_name'],
                'product_balance' => $item->product_balance,
            ];

            // Push the product object into the $productData array
            $productData[] = $productObject;
        }

        return $productData;
    }

    // get application payable
    protected function application_chargesParticulars($particular_ids = null, $amount = null)
    {
        if ($particular_ids) {
            $particulars = $this->particular->select('id, particular_name, charged, charge, charge_method, charge_mode, grace_period')->find($particular_ids);
        } else {
            $particulars = $this->particular->select('id, particular_name, charged, charge, charge_method, charge_mode, grace_period')->where(['account_typeId' => 18, 'charge_mode' => 'Auto', 'particular_status' => 'Active'])->find();
        }
        // gets particular id
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

    // calculate monthly installment amount
    protected function calculateEMIOld($method, $principal, $rate, $period, $interval, $interestPeriod)
    {
        # check the loan interest period
        if (strtolower($interestPeriod) === "year") {
            # Calculate the total number of payments in a year.
            $payouts = (12 / $interval);
        } else {
            $payouts = 1;
        }


        # convert period to years
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

            // Convert annual interest rate to monthly interest rate
            $monthlyInterestRate = (float)($rate / 100 / $payouts);
            // Calculate the monthly installment using the formula
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

        // Return the monthly installment
        return $loanInstallment;
    }
    /**
     * Calculates the monthly installment for an equated monthly installment (EMI) loan.
     *
     * @param string $method The method of interest calculation (reducing or flat).
     * @param float $principal The principal amount of the loan.
     * @param float $rate The annual interest rate.
     * @param int $period The total number of payments.
     * @param int $interval The interval at which interest is compounded (monthly, weekly, etc.).
     * @param string $interest_period The period for which the interest is calculated (yearly or monthly).
     * @param int $loan_period The loan term in the interest period.
     *
     * @return float The monthly installment for the loan.
     */
    protected function calculateEMI($method, $principal, $rate, $period, $interval, $interest_period, $loan_period)
    {
        if ($interest_period === 'year') {
            // Calculate the total number of payments in a year
            $payouts = 12 / $interval;
            // Convert period to years
            $loanTerm = $period / $payouts;
            // Calculate the total number of payments
            $numberOfPayments = $period;
            // Number of months in a year
            $reducingLoanTerm = 12;
        } else {
            // Calculate the total number of payments based per loan interest period
            $payouts = $interval;
            // Convert period based on the loan interest period
            $loanTerm = $loan_period / $payouts;
            // Number based on loan interest period
            $reducingLoanTerm = 1;
            // Calculate the total number of payments
            $numberOfPayments = $period / $interval;
        }

        $loanInstallment = 0;

        if (strtolower($method) == 'reducing') {
            // Convert annual interest rate to monthly interest rate
            $monthlyInterestRate = $rate / 100 / $reducingLoanTerm;
            // Calculate the numerator
            $numerator = $principal * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $numberOfPayments);
            // Calculate the denominator
            $denominator = pow(1 + $monthlyInterestRate, $numberOfPayments) - 1;
            // Calculate the monthly installment
            $loanInstallment = $numerator / $denominator;
        }

        if (strtolower($method) == 'flat') {
            // Calculate the interest amount
            $interestAmount = $principal * ($rate / 100) * $loanTerm;
            // Calculate the total amount repayable
            $totalAmountRepayable = $principal + $interestAmount;
            // Calculate the monthly installment
            $loanInstallment = $totalAmountRepayable / $numberOfPayments;
        }

        // Return the loan installment
        return $loanInstallment;
    }


    /**
     * Calculates the total interest amount for a loan.
     *
     * @param string $interest_type The type of interest (reducing or flat).
     * @param float $principal The principal amount of the loan.
     * @param float $installment The monthly installment amount.
     * @param float $rate The annual interest rate.
     * @param int $interval The interval at which interest is compounded (monthly, weekly, etc.).
     * @param int $no_of_installments The total number of installments.
     * @param string $interestPeriod The period for which the interest is calculated (yearly or monthly).
     *
     * @return float The total interest amount.
     */
    function calculateInterestOld($interest_type, $principal, $installment, $rate, $interval, $no_of_installments, $interestPeriod)
    {
        $total_principal = $total_interest = $interest_installment = $principal_installment = 0;

        # check the loan interest period
        if (strtolower($interestPeriod) === "year") {
            # Calculate the total number of payments in a year.
            $payouts = (12 / $interval);
        } else {
            $payouts = 1;
        }
        // default balances
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

            // calculate loan total amounts
            $total_principal += $principal_installment;
            $total_interest += $interest_installment;

            $index++;
        }

        return $total_interest;
    }
    function calculateInterest(
        $interest_type,
        $principal,
        $installment,
        $rate,
        $frequency,
        $no_of_installments,
        $period,
        $interestPeriod,
        $disbursement_date = null,
        $installsCovered = null,
        $installsMissed = null
    ) {
        $total_principal = $total_interest = $principal_installment = 0;
        $interest_installment = 0;
        $principal_balance = $principal;
        $originalPrincipal = $principal;
        if ($interestPeriod == 'year') {
            $interval = $this->settings->generateIntervals($frequency);
            $payouts = 12 / $interval['interval'];
        } else {
            $interval = 1;
            $payouts = $interval;
        }

        $index = 1;
        while ($index <= $no_of_installments) {
            if (strtolower($interest_type) == 'reducing') {
                $interest_installment = ($principal_balance * ($rate / 100)) / $payouts;
                $principal_installment = $originalPrincipal / $no_of_installments;
            } elseif (strtolower($interest_type) == 'flat') {
                $principal_installment = $originalPrincipal / $no_of_installments;
                $interest_installment = $installment - $principal_installment;
            }

            $principal_balance -= $principal_installment;

            $total_principal += $principal_installment;
            $total_interest += $interest_installment;

            $index++;
        }

        $totalLoan = $total_principal + $total_interest;

        return $total_interest;
    }

    public function loginUserNow($user)
    {
        # generate unique random token
        $token = $this->settings->generateRandomNumbers(32);
        # get user information
        $agent = $this->request->getUserAgent();
        # update user token information
        $save = $this->user->update($user["id"], [
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

            # check the geo data existence
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
                'user_id' => $user['id'],
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
            $sessionData = [
                'id' => $user['id'],
                'userlog_id' => $userlog_id,
                'name' => $user['name'],
                'email' => $user['email'],
                'branch_id' => $user['branch_id'],
                'photo' => $user['photo'],
                'token' => $token,
                'loggedIn' => true,
            ];
            $this->session->set($sessionData);
            # set user information on the session
            # $this->setUserSession($user);
            $response = [
                'status' => true,
                'error' => null,
                'url' => "/admin/dashboard",
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

        return $response;
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

    public function sendSMS($params): array
    {
        # Set the numbers you want to send to in international format
        // $recipient = trim(preg_replace('/^0/', '+256', $client['mobile']));
        $recipient = $this->phoneNumberWithCountryCode($params['mobile']);
        /*
        $apiResponse   = $this->smsAPI->send([
            'to'      => $recipient,
            'message' => $text
        ]);
        */
        $apiResponse = $this->egoAPI->initiate('sms', [
            "number" => $recipient,
            "message" => $params['text']
        ]);
        return [
            'apiResponse' => $apiResponse,
        ];
    }

    public function splitName($name)
    {
        $names = explode(' ', $name);
        $lastname = $names[count($names) - 1];
        unset($names[count($names) - 1]);
        $firstname = join(' ', $names);
        return $lastname;
        return $firstname . ' = ' . $lastname;
    }

    public function sendSMS1($data): array
    {
        $module = $data['module'];
        $phone = $data['mobile'];
        $password = $data['password'];
        $names = preg_split("/ /", $data['name']);
        $firstName = $names[0];
        if (count($names) == 3) {
            $lastName = $names[count($names) - 2] . " " . $names[count($names) - 1];
        } else {
            $lastName = $names[count($names) - 1];
        }
        # Set the numbers you want to send to in international format
        $recipient = trim(preg_replace('/^0/', '+256', $data['mobile']));
        # check the modules
        switch (strtolower($module)) {
            case 'account':
                # Set the text message
                $text = 'Your Login for ' . strtoupper($this->settingsRow["system_abbr"]) . ' is ' . $password;
                break;

            default:
                # code...
                break;
        }

        # send the sms via phone numbers
        $apiResponse   = $this->smsAPI->send([
            'to'      => $recipient,
            'message' => $text
        ]);

        # decode the JSON response
        # $decodedResponse = json_decode($apiResponse);
        # $statusCode = $apiResponse->SMSMessageData->Recipients[0]->statusCode;

        /*
        "sms":{"apiResponse":{"status":"success","data":{"SMSMessageData":{"Message":"Sent to 1\/1 Total Cost: UGX 25.0000","Recipients":[{"cost":"UGX 25.0000","messageId":"ATXid_e7aa53e4addacd473637170295356600","messageParts":1,"number":"+256706551841","status":"Success","statusCode":101}]}}}}
        if ($statusCode == 101) {
            # code...
        } else {

        }

        */
        # print_r($apiResponse);
        return [
            'apiResponse' => $apiResponse,
        ];
    }

    public function checkArrayExistance(String $module, array $data)
    {
        # check the module
        switch ($module) {
            case 'accountType':
                $account_typeId = $data['id'];
                $account_typeInfo = $this->accountType->find($account_typeId);
                # check the account type existence
                if ($account_typeInfo) {
                    $response = $account_typeInfo;
                } else {
                    echo json_encode([
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Transaction Type could not be found for ' . $account_typeInfo['name'] . '!',
                    ]);
                    exit;
                }
                break;

            case 'entryType':
                $entry_typeId = $data['id'];
                $transaction_typeRow = $this->entryType->find($entry_typeId);
                if ($transaction_typeRow) {
                    $response = $transaction_typeRow;
                } else {
                    echo json_encode([
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Transaction Type could not be found!',
                    ]);
                    exit;
                }
                break;

            case 'client':
                $client_id = $data['id'];
                $client = $this->clientDataRow($client_id);
                # Check the client existence
                if ($client) {
                    $response = $client;
                } else {
                    echo json_encode([
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Client information not be found!',
                    ]);
                    exit;
                }
                break;

            case 'particular':
                $particular_id = $data['id'];
                $particularRow = $this->particularDataRow($particular_id);
                # Check the particular existence
                if ($particularRow) {
                    $response = $particularRow;
                } else {
                    echo json_encode([
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Particular information not be found!',
                    ]);
                    exit;
                }
                break;

            case 'accountBalance':
                $part = $data['part'];
                $amount = $data['amount'];
                $accountBalance = $data['accountBalance'];
                $debitParticularBalance = $data['debitParticularBalance'];
                $creditParticularBalance = $data['creditParticularBalance'];
                $debitPaymentBalance = $data['debitPaymentBalance'];
                $creditPaymentBalance = $data['creditPaymentBalance'];
                # Check the account particular part
                switch ($part) {
                    case 'credit':
                        # Update account balance
                        $response = [
                            'accountBalance' => (float)($accountBalance + $amount),
                            'creditBalance' => ((float)$creditParticularBalance + $amount),
                            'debitBalance' => ((float)$debitPaymentBalance + $amount)
                        ];
                        break;

                    case 'debit':
                        # Update account balance
                        $response = [
                            'accountBalance' => (float)($accountBalance - $amount),
                            'debitBalance' => ((float)$debitParticularBalance + $amount),
                            'creditBalance' => ((float)$creditPaymentBalance + $amount)
                        ];
                        break;

                    default:
                        # Update account balance
                        $response = [
                            'accountBalance' => 0,
                            'debitBalance' => 0,
                            'creditBalance' => 0
                        ];
                        break;
                }
                break;

            default:
                # code...
                echo json_encode([
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'External Error Occurred at the moment!',
                ]);
                exit;
                break;
        }

        return ($response);
    }

    protected function createOrUpdateEntityLog($data)
    {
        if ($data) {
            return true; // Log creation/update successful
        } else {
            echo json_encode([
                'status' => 404,
                'error' => 'Entity Log',
                'messages' => 'Internal Error Occurred at the moment while processing the entity!',
            ]);
            exit;
            return false; // Log creation/update failed
        }
    }

    /**
     * Fetches the client's loan history.
     *
     * @return mixed
     *  Returns an array of client's loan applications and disbursements if found.
     *  Returns an error message if no history is found.
     */
    public function fetchHistory()
    {
        // Fetch the history type from the request
        $history = $this->request->getVar('history');
        // Fetch the client's ID from the request
        $client_id = $this->request->getVar('client_id');

        // Fetch the history based on the history menu selected
        switch (strtolower($history)) {
            case 'loans':
                // Get client application history excluding the current application
                $applications = $this->loanApplication->where(['client_id' => $client_id])->findAll();
                // Get client disbursement history excluding the current disbursement
                $disbursements = $this->disbursement->where(['client_id' => $client_id])->findAll();

                // Verify that either client has got any applications or disbursements history
                if (!$applications && !$disbursements) {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'No Client ' . ucfirst($history) . ' History Found!',
                    ];
                }

                $historyArray = [];
                // $historyArray['applications'][] = null;
                // $historyArray['disbursements'][] = null;

                if ($applications && !$disbursements) {
                    if ($applications && (count($applications) > 0)) {
                        foreach ($applications as $application) {
                            $historyArray['applications'][] = $this->loanApplicationRow($application['id']);
                        }
                    }
                }

                if ($disbursements && !$applications) {
                    if ($disbursements && (count($disbursements) > 0)) {
                        foreach ($disbursements as $disbursement) {
                            $historyArray['disbursements'][] = $this->loanDisbursementRow($disbursement['id']);
                        }
                    }
                }

                if ($applications && $disbursements) {
                    if ($applications && (count($applications) > 0)) {
                        foreach ($applications as $application) {
                            $historyArray['applications'][] = $this->loanApplicationRow($application['id']);
                        }
                    }

                    if ($disbursements && (count($disbursements) > 0)) {
                        foreach ($disbursements as $disbursement) {
                            $historyArray['disbursements'][] = $this->loanDisbursementRow($disbursement['id']);
                        }
                    }
                }

                $response = $historyArray;

                break;
            default:
                $response = [
                    'status' => 404,
                    'error' => 'Invalid Option',
                    'messages' => ucfirst($history) . ' history couldn\'t be retrieved!',
                ];
                break;
        }

        return $this->respond($response);
    }

    public function countClientApplications($client_id)
    {
        $applicationsCount = $this->loanApplication
            ->groupStart()
            ->where('client_id', trim($client_id))
            ->groupStart()
            ->where('status', 'Pending')
            ->orWhere('status', 'Processing')
            ->groupEnd()
            ->groupEnd()
            ->countAllResults();

        return $applicationsCount;
    }

    public function fetchUtilities($utility = null)
    {
        $data = [];
        switch (strtolower($utility)) {
            case 'accounts':
                $data['accounts'] = $this->getAccounts();
                break;
            case 'currency':
                $data['currency'] = $this->getCurrencies();
            default:
                $data['accounts'] = $this->getAccounts();
                $data['currency'] = $this->getCurrencies();
                break;
        }
        return $this->respond($data);
    }

    private function getAccounts()
    {
        $data = $this->account->where(['account_id' => null, 'status' => '1'])->findAll();
        return $data;
    }

    private function getCurrencies()
    {
        $data = $this->currency->where(['status' => 'Active'])->findAll();
        return $data;
    }

    public function accounts($condition)
    {
        $data = $this->account->select('accounts.*, COUNT(a.account_id) as nexen_clients_counter,
            , COUNT(c.account_id) as account_clients_counter')
            ->join('accounts a', 'a.account_id = accounts.id', 'left')
            ->join('clients c', 'c.account_id = accounts.id', 'left')
            ->where($condition)
            ->groupBy('accounts.id')
            ->findAll();

        return $data;
    }

    public function getLastName($name)
    {
        $names = preg_split("/ /", trim($name));

        switch (count($names)) {
            case 1:
                $lastName = $names[0];
                break;
            case 2:
                $lastName = $names[count($names) - 1];
                break;
            case 3:
                $lastName = $names[count($names) - 2] . " " . $names[count($names) - 1];
                break;
            default:
                $lastName = $names[count($names) - 1];
                break;
        }

        return $lastName;
    }

    public function phoneNumberWithCountryCode($phoneNumber)
    {
        $phone = trim($phoneNumber);
        if (substr($phone, 0, 4) == '+256') {
            $recipient = intval($phone, 10);
            $digit = 12;
        } else if (substr($phone, 0, 3) == '256') {
            $recipient = intval($phone, 10);
            $digit = 12;
        } elseif (substr($phone, 0, 1) == '+') {
            $recipient = intval($phone, 10);
            $digit = 10;
        } else {
            $recipient = preg_replace('/^0+/', '256', $phone);
            $digit = 10;
        }

        # Check the Phone Number 
        if (strlen($recipient) < 12 || strlen($recipient) > 12) {
            echo json_encode([
                'error' => true,
                'message' => 'Your phone number is Invalid. Only ' . $digit . ' digits is allowed.'
            ]);
            exit;
        }

        return (int)$recipient;
    }

    public function trimmedWhiteSpaceFromPhoneNumber($params)
    {
        # check whether the country code is not empty otherwise set the default to 256
        $countryCode = ($params['country_code']) ? $params['country_code']  : '256';
        $phoneInput = $params['phone'];
        # add the country code to the phone number input
        $phone = $countryCode . $phoneInput;
        # add the plus (+) to the trimmed phone number with the country code
        $phoneNumber = trim('+' . $phone);
        # remove white spaces between country code and phone number: method 1
        $trimmedPhoneNumber = str_replace(' ', '', $phoneNumber);
        # remove white spaces between country code and phone number: method 2
        # $trimmedPhoneNumber = preg_replace('/\s+/', '', $phoneNumber);
        # return clean phone number along with the country code
        return ($trimmedPhoneNumber);
    }

    public function validPhoneNumber($params)
    {
        $phone = $params['phone'];
        $input = $params['input'];

        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        # check whether the first phone number is with +256
        if (substr($phone, 0, 4) == '+256') {
            if (
                strlen($phone) > 13 ||
                strlen($phone) < 13
            ) {
                $data['inputerror'][] = $input;
                $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                $data['status'] = FALSE;
            }
        }
        # check whether the first phone number is with 0
        else if (substr($phone, 0, 1) == '0') {
            if (
                strlen($phone) > 10 ||
                strlen($phone) < 10
            ) {
                $data['inputerror'][] = $input;
                $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                $data['status'] = FALSE;
            }
        } else if (substr($phone, 0, 1) == '+') {
            if (
                strlen($phone) > 13 ||
                strlen($phone) < 13
            ) {
                $data['inputerror'][] = $input;
                $data['error_string'][] = 'Should have 13 digits with Country Code';
                $data['status'] = FALSE;
            }
        } else {
            $data['inputerror'][] = $input;
            $data['error_string'][] = 'Valid Phone Number is required';
            $data['status'] = FALSE;
        }
        # check whether the phone number is valid
        if ($this->settings->validatePhoneNumber($phone) == FALSE) {
            $data['inputerror'][] = $input;
            $data['error_string'][] = 'Valid Phone Number is required';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function checkPermissionsOLD($permissionString, $menuItem, $permission = null)
    {
        # define the system operations
        $actions = ['create', 'view', 'update', 'import', 'export', 'delete', 'bulkDelete'];
        $allowed = false;
        $slug = ucwords(str_replace(' ', '', $this->title));
        $menu = strtolower($this->menu);
        # define the system permissions string by menu's concatenating action, menu & slug
        $permissions = '_' . $menu . $slug;
        # check whether the system permissions string matches the user permissions
        if (($this->userPermissions == 'all') || (in_array('create' . $permissions, $this->userPermissions) || in_array('view' . $permissions, $this->userPermissions) || in_array('update' . $permissions, $this->userPermissions) || in_array('delete' . $permissions, $this->userPermissions))) {
            $allowed = true;
        }

        return $allowed;
    }

    public function checkPermissions($userPermissions, $menuItem, $permission = null)
    {
        # define the system operations
        $actions = ['create', 'view', 'update', 'import', 'export', 'delete', 'bulkDelete'];
        $allowed = false;
        $slug = str_replace(' ', '', ucwords($this->menuItem['title']));

        if ($this->userPermissions == 'all') {
            $allowed = true;
        } else {
            if ($permission) {
                // generate permission string by menu's concatenating action, menu & slug
                $permissionString = $permission . '_' . strtolower($menuItem['menu']) . $slug;
                // check if permission string exists in user permissions
                if (in_array($permissionString, $userPermissions)) {
                    $allowed = true;
                }
            } else {
                foreach ($actions as $action) {
                    // generate permission string by menu's concatenating action, menu & slug
                    $permissionString = $action . '_' . strtolower($menuItem['menu']) . $slug;
                    // check if permission string exists in user permissions
                    if (in_array($permissionString, $userPermissions)) {
                        $allowed = true;
                        break;
                    }
                }
            }
        }

        return $allowed;
    }

    public function doUploadAttachment($inputFileName, $fileSourceFolder)
    {
        $validationRule = [
            $inputFileName => [
                "rules" => "uploaded[" . $inputFileName . "]|max_size[" . $inputFileName . ",1024]|is_image[" . $inputFileName . "]|mime_in[" . $inputFileName . ",image/jpg,image/jpeg,image/png]",
                "label" => "Profile Image",
                "errors" => [
                    'max_size' => 'The size of this image is too large. The image must have less than 1MB size',
                    'mime_in' => 'Your upload does not have a valid image format',
                    'is_image' => 'Your file is not allowed! Please use an image!'
                ]
            ],
        ];
        if (!$this->validate($validationRule)) {
            $data['inputerror'][] = $inputFileName;
            $data['error_string'][] = $this->validator->getError($inputFileName);
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
        $file = $this->request->getFile($inputFileName);
        $profile_image = $file->getName();
        # Renaming file before upload
        $temp = explode(".", $profile_image);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        if ($file->move("uploads/" . $fileSourceFolder, $newfilename)) {
            return $newfilename;
        } else {
            $data['inputerror'][] = $inputFileName;
            $data['error_string'][] = "Failed to upload Image";
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
    }

    public function yopayment($module, $params)
    {
        switch (strtolower($module)) {
            case 'deposit':
                // Create a unique transaction reference that you will reference this payment with
                # $transaction_reference1 = date("YmdHis") . rand(1, 100);
                $transaction_reference = $params['transactionReference'];
                $this->yoAPI->set_external_reference($transaction_reference);
                $response = $this->yoAPI->ac_deposit_funds($params['phone'], $params['amount'], $params['reason']);

                if ($response['Status'] == 'OK') {
                    /*
                    echo json_encode("Payment made! Funds have been deposited onto your account. Transaction Reference = " . $response['TransactionReference'] . ". Thank you for using Yo! Payments");
                    */
                    # Check the transaction status
                    $transaction = $this->checkYoPaymentTransaction($params['transactionReference']);
                    $response = $transaction;

                    /*

                    $startTime = time();
                    $maxExecutionTime = 120; // 2 minutes in seconds
                    # Set the recursive transaction status condition
                    while (time() - $startTime < $maxExecutionTime) {
                        # Check the transaction status
                        $transaction = $this->checkYoPaymentTransaction($params['transactionReference']);
                        # Check the response status
                        if (strcmp($transaction['TransactionStatus'], 'SUCCEEDED') == 0) {
                            break; # Exit the loop if successful
                        }
                        sleep(10); # Sleep for 10 seconds before calling the function again
                    }
                    // Save this transaction for future reference
                    return $response;
                    $response = $transaction;
                    */
                } else {
                    echo json_encode([
                        'status' => 404,
                        'error' => 'External Error',
                        'messages' => "Yo Payments Error: " . $response['StatusMessage'],
                    ]);
                    exit;
                }
                break;

            case 'ndeposit':
                // Create a unique transaction reference that you will reference this payment with
                $transaction_reference = date("YmdHis") . rand(1, 100);
                $this->yoAPI->set_external_reference($transaction_reference);

                // Set non blocking to TRUE so that you get an instant response
                $this->yoAPI->set_nonblocking("TRUE");

                // Set an instant notification url where a successful payment notification POST will be sent
                // See documentation on how to handle IPN
                $this->yoAPI->set_instant_notification_url('example.com/ipn.php');

                // Set a failure notification url where a failed payment notification POST will be sent
                // See documentation on how to handle IPNs
                $this->yoAPI->set_failure_notification_url('example.com/fpn.php');

                $response = $this->yoAPI->ac_deposit_funds('256770000000', 1000, 'Reason for transfer of funds');

                //Wait a little and check for the status of the transaction.
                sleep(25);
                $this->checkYoPaymentTransaction($transaction_reference);

                if ($response['Status'] == 'OK') {
                    echo "Waiting for user to confirm mobile money transfer. You can check using this Transaction Reference = " . $response['TransactionReference'] . ". Thank you for using Yo! Payments\n";
                    // Save this transaction for future reference
                    $response = $response;
                } else {
                    echo json_encode("Yo Payments Error: " . $response['StatusMessage'] . "\n");
                    exit;
                }
                break;

            default:
                # code...
                $response = [
                    'status' => "FAILED",
                    'error' => 'Not Found',
                    'messages' => 'Invalid parameter passed at the moment.'
                ];
                break;
        }

        return $response;
    }

    public function checkYoPaymentTransaction($transaction_reference)
    {
        $transaction = $this->yoAPI->ac_transaction_check_status(NULL, $transaction_reference);
        if (strcmp($transaction['TransactionStatus'], 'SUCCEEDED') == 0) {
            // Transaction was completed and funds were deposited onto the account
            // Save data into the database
            # echo "Transaction was successful ";
            return $transaction;
        } else {
            return [
                'status' => 404,
                'error' => "{$transaction['TransactionStatus']} state",
                'messages' => "Transaction is still in {$transaction['TransactionStatus']} state.\n",
            ];
            echo json_encode([
                'status' => 404,
                'error' => "{$transaction['TransactionStatus']} state",
                'messages' => "Transaction is still in {$transaction['TransactionStatus']} state.\n",
            ]);
            exit;
            echo "Transaction is still in {$transaction['TransactionStatus']} state.\n";
        }
    }

    public function _doUploadDigitalSignature($module, $signature_image)
    {
        # Split the string to get the actual base64 encoded image
        $image_parts = explode(";base64,", $signature_image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        # Generate a unique name for the image file
        $file_name = uniqid() . '.' . $image_type;

        # Set the path to the upload folder

        switch (strtolower($module)) {
            case "client":
                $upload_folder = "uploads/clients/signatures/";
                break;
            case "employee":
                $upload_folder = "uploads/staffs/employees/signatures/";
                break;
            case "admin":
                $upload_folder = "uploads/staffs/admins/signatures/";
                break;
            case "application":
                $upload_folder = "uploads/applications/signatures/";
                break;
            default:
                $upload_folder = 'upload/signatures/';
                break;
        }

        # Ensure the upload folder exists
        if (!file_exists($upload_folder)) {
            mkdir($upload_folder, 0777, true);
        }

        # Full path to the saved file
        $file_path = $upload_folder . $file_name;

        # Save the file
        if (file_put_contents($file_path, $image_base64)) {
            # echo "File uploaded successfully: " . $file_path;
            return $file_name;
        } else {
            echo json_encode([
                'status' => 404,
                'error' => "Failed",
                'messages' => "Failed to upload the digital signature",
            ]);
            exit;
        }
    }

    public function computeTotalAmount($charge, $amount)
    {
        # initialize charge amount to 0
        $chargeAmount = 0;
        $totalAmount = 0;
        # check the charge existence
        if ($charge) {
            # check charge method matches amount
            if (strtolower($charge['charge_method']) == 'amount') {
                $chargeAmount = $charge['charge'];
            }
            # check the charge method matches percent
            if (strtolower($charge['charge_method']) == 'percent') {
                $chargeAmount = (($charge['charge'] / 100) * $amount);
            }
            # compute total amount which include charge amount plus amount
            $totalAmount += $amount + $chargeAmount;
        }

        return [
            'amount' => $amount,
            'totalAmount' => $totalAmount,
            'chargeAmount' => $chargeAmount
        ];
    }

    // Remove commas from the amount
    public function removeCommasFromAmount($amount)
    {
        $cleanAmount = str_replace(',', '', trim($amount));
        return $cleanAmount;
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

    protected function push_excessAmount_to_client_savings(array $data)
    {
        $client_id = $data['client_id'];
        $amount = $data['amount'];
        $payment_id = $data['payment_id'];
        $from = $data['from'];

        $clientInfo = $this->clientDataRow($client_id);
        $ref_id = $this->settings->generateReference();
        $account_typeId = 12;
        $savingsParticular = $this->particular->where(['account_typeId' => $account_typeId])->first();
        if ($savingsParticular) {
            $particular_id = $savingsParticular['id'];
            // get transaction type data
            $transaction_typeRow = $this->entryType->where(['account_typeId' => $account_typeId, 'part' => 'credit'])->first();
            if ($transaction_typeRow) {
                $entry_typeId = $transaction_typeRow['id'];
                // entry_menu of transaction based on entry type
                $entry_menu = $transaction_typeRow['entry_menu'];
                // status of transaction based on entry type
                $status = $transaction_typeRow['part'];
                // get particular data
                $particularRow = $this->particularDataRow($particular_id);
                // get payment method data
                // calculate entries total amount per entry status & final balance
                $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $particularRow['part']]);
                $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $amount); // calculate balance for primary paticular as of this entry

                $paymentRow = $this->particularDataRow($payment_id);
                // check existence of accounting particulars
                if ($particularRow && $paymentRow) {
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
                        $savingParticularBal = ['credit' => ((float)$particularRow['credit'] + $amount)];
                        // debit selected payment method[assets]
                        $paymentParticularBal = ['debit' => ((float)$paymentRow['debit'] + $amount)];
                    }
                    if (strtolower($status) == 'debit') {
                        $balance = (float)($clientInfo['account_balance'] - $amount);
                        $clientBalance = ['account_balance' => $balance];
                        // debit client savings particular[liability]
                        $savingParticularBal = ['debit' => ((float)$particularRow['debit'] + $amount)];
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
                        'contact' => trim($this->request->getVar('contact')),
                        'entry_details' => "Over Payment of " . $this->settingsRow['currency'] . ' ' . $amount . " from " . $from . " has been pushed to " . $clientInfo['name'] . "'s savings",
                        'remarks' => "Over Payment",
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
                                    'user_id' => $this->userRow['id'],
                                    'action' => 'create',
                                    'description' => ucfirst('New ' . $transaction_typeRow['type'] . ' ' .  $this->title . ' for ' . $particularRow['particular_name'] . ' Ref ID ' . $ref_id),
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
                                        $clientInfo['entry_details'] = $transactionData['entry_details'];
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
                                            # $this->settings->sendMail($message, $subject, $token);
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
                    'messages' => 'Transaction Type could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'No Savings Particular found!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    // validate saving product balance
    protected function validateSavingsProductBalances($data)
    {
        // get product row
        $productRow = $this->product->find($data['id']);
        // get/set product min & max balance settings
        if ($productRow) {
            $minSavingsEntry = (($productRow['min_per_entry']) ? (float)$productRow['min_per_entry'] : 0);
            $minSavingsBal = (($productRow['min_account_balance']) ? (float)$productRow['min_account_balance'] : 0);
            $maxSavingsEntry = (($productRow['max_per_entry']) ? (float)$productRow['max_per_entry'] : 0);
            $maxSavingsBal = (($productRow['max_account_balance']) ? (float)$productRow['max_account_balance'] : 0);
        } else {
            return [
                'status' => 404,
                'error' => 'Invalid Product!',
                'messages' => 'Selected Product could not be found!',
            ];
            exit;
        }
        if ($data['amount'] < $minSavingsEntry) {
            return [
                'status' => 500,
                'error' => 'Min Transaction Limit!',
                'messages' => ucwords($productRow['product_name']) . ' Minimum Transaction limit of ' . $this->settingsRow['currency'] . ' ' . $minSavingsEntry . ' not reached!',
            ];
            exit;
        }
        if ($data['amount'] > $maxSavingsEntry) {
            return [
                'status' => 500,
                'error' => 'Max Transaction Limit!',
                'messages' => ucwords($productRow['product_name']) . ' Maximum Transaction limit of ' . $this->settingsRow['currency'] . ' ' . $maxSavingsEntry . ' exceeded!',
            ];
            exit;
        }
        # validate if amount to be saved corresponds with product settings
        if (strtolower($data['status']) == 'debit') { // withdrawing savings
            if (($minSavingsBal > 0) && ($minSavingsBal >= $data['product_balance'])) {
                return [
                    'status' => 500,
                    'error' => 'Min Savings Reached!',
                    'messages' => ucwords($productRow['product_name']) . ' Minimum Balance of ' . $this->settingsRow['currency'] . ' ' . $minSavingsBal . ' reached!',
                ];
                exit;
            }
        } elseif (strtolower($data['status']) == 'credit') { // collecting savings
            if (($maxSavingsBal > 0) && ($maxSavingsBal <= $data['product_balance'])) {
                return [
                    'status' => 500,
                    'error' => 'Max Savings Reached!',
                    'messages' => ucwords($productRow['product_name']) . ' Maximum Balance of ' . $this->settingsRow['currency'] . ' ' . $maxSavingsBal . ' reached!',
                ];
                exit;
            }
        } else {
            return [
                'status' => 200,
                'error' => false,
                'messages' => '',
            ];
            // return [
            //     'status' => 500,
            //     'error' => 'Invalid Status',
            //     'messages' => "Transaction status '". strtoupper($data['status']) ."' is invalid",
            // ];
            exit;
        }
    }

    protected function membershipTransaction(array $data)
    {
        // $ref_id = $this->settings->generateReference();
        $membershipOutsandingBalance = $data['membershipAmt'];
        $payment_id = $data['payment_id'];
        $client_id = $data['client_id'];
        $account_typeId = $data['account_typeId'];

        # Check the particular existance that will be used as for payment
        $paymentRow = $this->checkArrayExistance('particular', [
            'id' => $payment_id,
        ]);
        # Check the account type existance
        $account_typeInfo = $this->checkArrayExistance('accountType', [
            'id' => $account_typeId,
        ]);
        # Check the client existance
        $clientInfo = $this->checkArrayExistance('client', [
            'id' => $client_id
        ]);

        $reg_date = $clientInfo['reg_date'];

        $transaction_typeRow = $this->entryType->where([
            'account_typeId' => $account_typeId,
            'part' => 'credit'
        ])->first();

        if (!$transaction_typeRow) {
            echo json_encode([
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Transaction Type could not be found!',
            ]);
            exit;
        }

        $entry_typeId = $transaction_typeRow['id'];
        $entry_menu = $transaction_typeRow['entry_menu'];
        $status = $transaction_typeRow['part'];
        $transactionType = $transaction_typeRow['type'];

        $particulars = $this->particular->where([
            'account_typeId' => $account_typeId,
        ])->findAll();

        $totalOverPayment = $particularBalance = $excessAmt = 0;
        $amountToBePaid = $membershipOutsandingBalance;

        foreach ($particulars as $particular) {
            if ($amountToBePaid <= 0) {
                break; // Break loop if charge to be paid is negative
            }

            $particular_id = $particular['id'];
            # Check the particular existance
            $particularRow = $this->checkArrayExistance('particular', [
                'id' => $particular_id,
            ]);

            if (!$particularRow) {
                continue; // Skip if particular is not found
            }


            $chargeRow = $this->vlookupCharge($particular['id'], $reg_date);
            if (!$chargeRow) {
                continue; // Skip if no valid charge is found
            }

            $chargeAmt = $chargeRow['charge'];
            $chargeFrequency = strtolower($particularRow['charge_frequency']);
            $chargeMode = strtolower($particularRow['charge_mode']);

            # Check if The Client Has Fully Paid or is Paying Excess Amounts
            $totalAmountPaid = $this->entry->sum_client_amountPaid($client_id, $particular_id, $status);

            # Check the charge balance
            $particularBalance = ($chargeAmt - $totalAmountPaid);

            /** Check for Excess Payments
             * calculate total paid and excess amount
             * if any excess payment, push it to client savings
             */
            if ($particularBalance < 0) {
                $response = [
                    'status' => 500,
                    'error' => 'Entry Error!',
                    'messages' => 'An error was detected in posting of ' . $particular['particular_name'] . ' entries.',
                ];

                echo json_encode($response);
                exit;
            }

            if ($particularBalance == 0) {
                continue;
            }

            $membershipCharge = min($amountToBePaid, $particularBalance);
            $excessAmt = max(0, ($amountToBePaid - $particularBalance));

            if ($membershipCharge == 0) {
                continue;
            }

            $totalOverPayment += $excessAmt;

            # calculate entries total amount per entry status & final balance
            $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $particularRow['part']]);
            $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $membershipCharge);

            /** 
             * since account type is for Revenue category,
             * credit particular & debit payment method if its gaining,
             * debit particular & credit payment method if its loosing,
             */
            $accountBalance = $this->checkArrayExistance('accountBalance', [
                'part' => strtolower($status),
                'amount' => $membershipCharge,
                'accountBalance' => $clientInfo['account_balance'],
                'debitParticularBalance' => $particular['debit'],
                'creditParticularBalance' => $particular['credit'],
                'debitPaymentBalance' => $paymentRow['debit'],
                'creditPaymentBalance' => $paymentRow['credit']
            ]);
            # Check the membership transaction data existance
            if (!empty($this->request->getVar('date'))) {
                $date = trim($this->request->getVar('date'));
            } else {
                $date = date('Y-m-d');
            }
            # Membership transaction payload
            $ref_id = $this->settings->generateReference();
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
                'amount' => $membershipCharge,
                'status' => $status,
                'balance' => $accountingBalance,
                'contact' => trim($this->request->getVar('contact')),
                'entry_details' => 'Direct Reduction of ' . $this->settingsRow['currency'] . ' ' . $membershipCharge . ' from ' . $clientInfo['name'] . '\'s ' . $data['from'] . '. Reason: ' . $particularRow['particular_name'],
                'remarks' => $clientInfo['name'] . ' ' . $particularRow['particular_name'] . ' Reduction',
            ];
            # Save membership transactions
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
            # Add transaction information into the activity log
            $activity = $this->insertActivityLog([
                'user_id' => $this->userRow['id'],
                'action' => 'create',
                'description' => ucfirst('New ' . $entry_menu . ' ' . $transaction_typeRow['type'] .  $this->title . ' for ' . $particular['particular_name'] . ' Ref ID ' . $ref_id),
                'module' => $this->menu,
                'referrer_id' => $ref_id,
            ]);
            # Check the client email existance
            if (!empty($clientInfo['email'])) {
                $clientInfo['branch_name'] = $this->userRow['branch_name'];
                $clientInfo['amount'] = $membershipCharge;
                $clientInfo['charge'] = $particular['charge'];
                $clientInfo['ref_id'] = $ref_id;
                $clientInfo['date'] = date('d-m-Y H:i:s');
                $clientInfo['entry_details'] = trim($this->request->getVar('entry_details'));
                $clientInfo['account_typeID'] = $account_typeId;
                $clientInfo['type'] = $transaction_typeRow['type'];
                $clientInfo['particular_name'] = $particular['particular_name'];
                $clientInfo['payment_mode'] = $paymentRow['particular_name'];
                $message = $clientInfo;
                $checkInternet = $this->settings->checkNetworkConnection();
                # Check the internet coneection
                if ($checkInternet) {
                    $subject = 'New ' . $particular['particular_name'] . ' Transaction';
                    $message = $message;
                    $token = 'transaction';
                    # $this->settings->sendMail($message, $subject, $token);
                    $response = [
                        'status' => 200,
                        'error' => null,
                        'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. Email Sent'
                    ];
                    // return $this->respond($response);
                    // echo (json_encode($response));
                    // exit;
                } else {
                    $response = [
                        'status' => 200,
                        'error' => null,
                        'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. No Internet'
                    ];
                    // return $this->respond($response);
                    // echo (json_encode($response));
                    // exit;
                }
            } else {
                $response = [
                    'status' => 200,
                    'error' => null,
                    'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully.',
                ];
                // return $this->respond($response);
                // echo (json_encode($response));
                // exit;
            }

            $amountToBePaid -= $membershipCharge;
        }

        return [
            'overpayment' => $excessAmt,
            'membershipBalance' => $particularBalance,
            'totalOverPayment' => $totalOverPayment,
            'savingsToBePaid' => $amountToBePaid
        ];
        exit;
    }
    // lookup particular charge
    protected function vlookupCharge($particular_id, $reg_date)
    {
        // Get all charges for the particular
        $charges = $this->getCharges([
            'charges.particular_id' => $particular_id,
            'charges.status' => 'Active'
        ]);

        // Initialize variables to hold the closest charge
        $closestCharge = null;
        $closestDate = null;

        foreach ($charges as $charge) {
            $effectiveDate = strtotime($charge['effective_date']);
            $registrationDate = strtotime($reg_date);

            // Check if the effective date is less than or equal to the registration date
            if ($effectiveDate <= $registrationDate) {
                // If this is the first charge or a more recent charge, update the closest charge
                if ($closestDate === null || $effectiveDate > $closestDate) {
                    $closestCharge = $charge;
                    $closestDate = $effectiveDate;
                }
            }
        }

        // Return the closest charge amount or null if no valid charge is found
        return $closestCharge ? $closestCharge : null;
    }

    /**
     * Calculates the total balance of shares for a specific client.
     *
     * @param int|null $client_id The ID of the client whose shares balance is to be calculated.
     * @return float|string The total balance of shares for the client, or 'N/A' if the client ID is not provided or the client has no shares.
     */
    protected function client_sharesBalance($client_id = null)
    {
        $clientShares = $this->entry->select('
            SUM(CASE WHEN LOWER(entries.status) = "debit" THEN entries.amount ELSE 0 END) as sharesDebit,
            SUM(CASE WHEN LOWER(entries.status) = "credit" THEN entries.amount ELSE 0 END) as sharesCredit')
            ->where(['entries.account_typeId' => 8, 'entries.client_id' => $client_id])
            ->get()->getRow();
        if ($clientShares) {
            $clientSharesPurchases = (float)$clientShares->sharesCredit;
            $clientSharesWithdrawals = (float)$clientShares->sharesDebit;
            $clientSharesBalance = ($clientSharesPurchases - $clientSharesWithdrawals);

            return $clientSharesBalance;
        } else {
            return 'N/A';
        }
    }

     /**
     * Looks up the shares particular charge based on the registration date.
     *
     * @param int $particular_id The ID of the particular.
     * @param string $reg_date The registration date.
     *
     * @return array|null The charge data or null if no charge is found.
     */
    protected function vlookupSharesCharge($particular_id, $reg_date)
    {
        // Get all charges for the particular
        $charges = $this->getCharges([
            'charges.particular_id' => $particular_id,
            'charges.status' => 'Active'
        ]);

        // If no charges are found, return null
        if (empty($charges)) {
            return null;
        }

        // Initialize variables to hold the earliest & closest available charge
        $earliestCharge = null;
        $earliestDate = null;
        $closestCharge = null;
        $closestDate = null;

        foreach ($charges as $charge) {
            $effectiveDate = strtotime($charge['effective_date']);
            $registrationDate = strtotime($reg_date);

            // Track the earliest charge regardless of registration date
            if ($earliestDate === null || $effectiveDate < $earliestDate) {
                $earliestCharge = $charge;
                $earliestDate = $effectiveDate;
            }

            // Check if the effective date is less than or equal to the registration date
            if ($effectiveDate <= $registrationDate) {
                // Update the closest charge if this charge is more recent
                if ($closestDate === null || $effectiveDate > $closestDate) {
                    $closestCharge = $charge;
                    $closestDate = $effectiveDate;
                }
            }
        }

        // Return the closest charge, or the earliest charge if none is found before the reg_date
        return $closestCharge ? $closestCharge : $earliestCharge;
    }

    // calculate total non cleared loans & balances for client
    public function client_loans($clientId = null)
    {
        $allDisbursements = $this->disbursement->select('COUNT(id) as totalDisbursements, SUM(total_balance) totalLoanBalance')->where(['class !=' => 'Cleared', 'client_id' => $clientId])->get()->getRow();
        if ($allDisbursements->totalDisbursements > 0) {
            $disbursements = $allDisbursements->totalDisbursements;
            $totalBalance = $allDisbursements->totalLoanBalance;

            $response = [
                'disbursements' => $disbursements,
                'totalBalance' => $totalBalance,
                'message' => 'Total Disbursment Records!',
            ];
            return $response;
            exit;
        } else {
            $response = [
                'disbursements' => 00,
                'totalBalance' => 0.00,
                'message' => 'No Disbursments Found!',
            ];
            return $response;
            exit;
        }
    }


    protected function savingsWithdrawalChargesTransaction(array $data)
    {
        // $ref_id = $this->settings->generateReference();
        $amount = $data['amount'];
        $payment_id = $data['payment_id'];
        $client_id = $data['client_id'];
        $account_typeId = $data['account_typeId'];
        $parent_id = $data['parent_id'];
        $product_id = isset($data['product_id']) ? $data['product_id'] : null;
        $withdrawChargeParticular = $data['withdrawChargeParticular'];
        $withdraw_particularId = $withdrawChargeParticular['id'];

        # Check the particular existance that will be used as for payment
        $paymentRow = $this->checkArrayExistance('particular', [
            'id' => $payment_id,
        ]);
        # Check the account type existance
        $account_typeInfo = $this->checkArrayExistance('accountType', [
            'id' => $account_typeId,
        ]);
        # Check the client existance
        $clientInfo = $this->checkArrayExistance('client', [
            'id' => $client_id
        ]);

        $transaction_typeRow = $this->entryType->where([
            'account_typeId' => $account_typeId,
            'part' => 'credit'
        ])->first();

        if (!$transaction_typeRow) {
            echo json_encode([
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Transaction Type could not be found!',
            ]);
            exit;
        }

        $entry_typeId = $transaction_typeRow['id'];
        $entry_menu = $transaction_typeRow['entry_menu'];
        $status = $transaction_typeRow['part'];
        $transactionType = $transaction_typeRow['type'];

        $particulars = $this->particular->where([
            'account_typeId' => $account_typeId,
            'particular_status' => 'Active',
        ])->findAll();

        $totalOverPayment = $particularBalance = $excessAmt = 0;
        $amountToBePaid = $amount;
        # get withdraw charges particular
        /*$withdrawChargeParticular = $this->particular->where(['account_typeId' => 20])->first();
        if ($withdrawChargeParticular) {
            $withdraw_particularId = $withdrawChargeParticular['id'];
            # check the withdrawal charges based on the amount
            $chargeRow = $this->vLookUpWithdrawalsCharge($withdrawChargeParticular['id'], $amount);
        } else {
            $chargeRow = null;
        }*/
        $chargeRow = $this->vLookUpWithdrawalsCharge($withdrawChargeParticular['id'], $amount);

        if (!$chargeRow || strtolower($chargeRow['charge_mode']) == 'manual') {
            return [
                'overpayment' => $excessAmt,
                'membershipBalance' => $particularBalance,
                'totalOverPayment' => $totalOverPayment,
                'savingsToBePaid' => $amountToBePaid
            ];
            exit;
        }

        $chargeAmt = $chargeRow['chargeAmount'];

        # Check if The Client Has Fully Paid or is Paying Excess Amounts
        $totalAmountPaid = $this->entry->sum_client_amountPaid($client_id, $withdraw_particularId, $status);

        # Check the charge balance
        $particularBalance = ($chargeAmt - $totalAmountPaid);

        # calculate entries total amount per entry status & final balance
        $entriesStatusTotals = $this->entry->calculateTotalBalance([
            'module' => 'particular',
            'module_id' => $withdraw_particularId,
            'status' => $status
        ]);

        $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $chargeAmt);

        /** 
         * since account type is for Revenue category,
         * credit particular & debit payment method if its gaining,
         * debit particular & credit payment method if its loosing,
         */
        $accountBalance = $this->checkArrayExistance('accountBalance', [
            'part' => strtolower($status),
            'amount' => $chargeAmt,
            'accountBalance' => $clientInfo['account_balance'],
            'debitParticularBalance' => $withdrawChargeParticular['debit'],
            'creditParticularBalance' => $withdrawChargeParticular['credit'],
            'debitPaymentBalance' => $paymentRow['debit'],
            'creditPaymentBalance' => $paymentRow['credit']
        ]);

        # Check the withdrawal charges transaction date existance
        if (!empty($this->request->getVar('date'))) {
            $date = trim($this->request->getVar('date'));
        } else {
            $date = date('Y-m-d');
        }
        # Savings withdraw charges transaction payload
        $ref_id = $this->settings->generateReference();
        $transactionData = [
            'date' => $date,
            'payment_id' => $payment_id,
            'particular_id' => $withdraw_particularId,
            'product_id' => $product_id,
            'branch_id' => $this->userRow['branch_id'],
            'staff_id' => $this->userRow['staff_id'],
            'client_id' => $client_id,
            'entry_menu' => $entry_menu,
            'entry_typeId' => $entry_typeId,
            'account_typeId' => $account_typeId,
            'ref_id' => $ref_id,
            'amount' => $chargeAmt,
            'status' => $status,
            'balance' => $accountingBalance,
            'contact' => trim($this->request->getVar('contact')),
            'entry_details' => 'Direct Deduction of ' . $this->settingsRow['currency'] . ' ' . $chargeAmt . ' from ' . $clientInfo['name'] . '\'s ' . $data['from'] . '. Reason: ' . $withdrawChargeParticular['particular_name'],
            'remarks' => $clientInfo['name'] . ' ' . $withdrawChargeParticular['particular_name'] . ' Deduction',
            'parent_id' => $parent_id,
        ];
        # Save membership transactions
        $saveTransaction = $this->entry->insert($transactionData);

        # Update accounting particulars balances
        $updateParticularAccount = $this->particular->update($withdraw_particularId, [
            'credit' => $accountBalance['creditBalance'],
            'debit' => $accountBalance['debitBalance']
        ]);
        $updatePaymentAccount = $this->particular->update($payment_id, [
            'credit' => $accountBalance['creditBalance'],
            'debit' => $accountBalance['debitBalance']
        ]);
        # Add transaction information into the activity log
        $activity = $this->userActivity->insert([
            'user_id' => $this->userRow['id'],
            'action' => 'create',
            'description' => ucfirst('New ' . $entry_menu . ' ' . $transaction_typeRow['type'] .  $this->title . ' for ' . $withdrawChargeParticular['particular_name'] . ' Ref ID ' . $ref_id),
            'module' => $this->menu,
            'referrer_id' => $ref_id,
        ]);

        return [
            'overpayment' => $excessAmt,
            'membershipBalance' => $particularBalance,
            'totalOverPayment' => $totalOverPayment,
            'savingsToBePaid' => $amountToBePaid
        ];
        exit;
    }

    protected function vLookUpWithdrawalsCharge($particular_id, $amount)
    {
        # Get all particular charges
        $charges = $this->getCharges([
            'charges.particular_id' => $particular_id,
            'charges.status' => 'Active'
        ]);
        # Initialize the closest charge and closestChargeLimit to null
        $closestCharge = $closestChargeLimit = null;

        foreach ($charges as $charge) {
            $chargeLimits = ($charge['charge_limits']);

            # skip the charges that will be handled manually
            if (strtolower($charge['charge_mode']) == 'manual') {
                # continue;
            }

            # check whether the charge limits is less or equal to the amount
            if ($chargeLimits <= $amount) {
                # check whether the closes charge limit is in range between the amount
                if ($closestChargeLimit === null || $chargeLimits > $closestChargeLimit) {
                    $closestCharge = $charge;
                    $closestChargeLimit = $chargeLimits;
                }
            }
        }

        # check whether the charge has been found and return the entire charge row
        if ($closestCharge) {
            # compute the total amount and total charge amount based on the amount
            $data = $this->computeTotalAmount($closestCharge, $amount);
            # update the closest charge array to include the totalAmount and chargeAmount
            $closestCharge = array_merge($closestCharge, $data);
        }
        return $closestCharge ? $closestCharge : null;
    }
}
