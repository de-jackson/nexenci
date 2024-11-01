<?php

namespace App\Controllers\Admin\Clients;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;
use Config\Services;


\Config\Services::email();

class Client extends MasterController
{
    // savings account type id
    protected $account_typeId = 12;

    public function __construct()
    {
        parent::__construct();
        $this->encrypter = Services::encrypter();
        $this->menu = 'Clients';
        $this->title = 'Clients';
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
            return view('admin/clients/index', [
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

    public function client_view($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $client = $this->clientDataRow($id);
            if ($client) {
                return view('admin/clients/index', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'client' => $client,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Client could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    function client_forms($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            return view('admin/clients/client_formPDF', [
                'title' => "Client Registration Form",
                'id' => $id,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'client' => $this->clientDataRow($id),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    /**
     * return all clients as rows
     */
    public function clients_list($id = null)
    {
        if ($id == 0) {
            $where = ['clients.deleted_at' => null];
        } else {
            $where = ['clients.deleted_at' => null, 'clients.id' => $id];
        }
        # $clients = $this->client->select('id ,name, title,account_no, account_balance, email, mobile, residence, access_status, photo, savings_products, gender')->where($where)->orderBy('name', 'asc');
        $clients = $this->client->select('clients.id ,clients.name, clients.title, clients.account_no, clients.account_balance, clients.email, clients.mobile, clients.residence, clients.access_status, clients.photo, clients.savings_products, clients.gender, clients.reg_date, branches.branch_name')->join('branches', 'clients.branch_id = branches.id', 'left')->where($where)->orderBy('clients.id', 'desc');

        return DataTable::of($clients)
            ->add('checkbox', function ($client) {
                return '<div class=""><input type="checkbox" class="data-check" value="' . $client->id . '"></div>';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('name', function ($client) {
                # check whether the photo exist
                if (file_exists("uploads/clients/passports/" . $client->photo) && $client->photo) {
                    # display the current photo
                    $photo = '
                    <a href="javascript:void(0)" onclick="view_client_photo(' . "'" . $client->id . "'" . ')" title="' . strtoupper($client->name) . '"><img src="' . base_url('uploads/clients/passports/' . $client->photo) . '" style="width:40px;height:40px;" class="avatar avatar-md" /></a>
                    ';
                } else {
                    # display the default photo
                    $photo =  '
                    <a href="javascript:void(0)" onclick="view_client_photo(' . "'" . $client->id . "'" . ')" title="No photo"><img src="' . base_url('assets/dist/img/nophoto.jpg') . '" style="width:40px;height:40px;" class="avatar avatar-md" /></a>
                    ';
                }

                return '<div class="products">
                ' . $photo . '
                    <div>
                        <h6>' . ucwords($client->title) . ' ' . trim(strtoupper($client->name)) . '</h6>
                        <span>' . trim($client->account_no) . '</span>	
                    </div>	
                </div>';
            })
            ->add('savings', function ($client) {
                $clientRow = $this->client->find($client->id);
                if (($clientRow['savings_products'])) { // Clients with savings products
                    $savingsProducts = json_decode($clientRow['savings_products']);
                    $productLinks = '';

                    if ($savingsProducts) {
                        $products = $this->get_productsDetails($savingsProducts, 'savings');

                        if ($products) { // Ensure $products is not empty
                            foreach ($products as $product) {
                                $productLinks .= '<a href="javascript:void(0)" class="text-info py-1" onclick="client_savings(' . "'" . $client->id . "', '" . $product['product_id'] . "'" . ')" title="Client Savings">' .
                                    $product['product_name'] . ' ~ ' . $product['product_balance'] .
                                    '</a><br>';
                            }
                        }
                    }
                    return $productLinks;
                } else { // Clients without savings products
                    return '
                        <a href="javascript:void(0)" class="text-success" onclick="client_savings(' . "'" . $client->id . "'" . ')" title="Client Savings">
                            <i class="fa fa-money-bill-1-wave"></i>
                        </a>
                    ';
                }
            })
            ->add('membership', function ($client) {
                $membershiparticulars = $this->particular->where(['account_typeId' => 24, 'particular_status' => 'Active'])->findAll();
                if (!$membershiparticulars) {
                    return 'No Memberships Particulars Available!';
                }
                $membershipLinks = '';
                foreach ($membershiparticulars as $particular) {
                    # get particular charge
                    $chargeRow = $this->vlookupCharge($particular['id'], $client->reg_date);
                    if (!$chargeRow) {
                        continue;
                        $membershipLinks .= 'No Charge for ' . $particular['particular_name'] . '!<br>';
                    } else {
                        # get client charges paid
                        $totalAmountPaid = $this->entry->sum_client_amountPaid($client->id, $particular['id'], 'credit');

                        $membershipLinks .=  ucwords($particular['particular_name']) . ' <b>@' . $this->settingsRow['currency'] . ' ' . number_format($chargeRow['charge']) . ':</b><br><b>Paid: </b>' . number_format($totalAmountPaid) . '.<br>';
                    }
                }
                return $membershipLinks;
            })
            ->add('shares', function ($client) {
                $shareParticulars = $this->particular->where(['account_typeId' => 8, 'particular_status' => 'Active'])->findAll();
                if (!$shareParticulars) {
                    return 'No Shares Particulars Available!';
                }
                $sharesLinks = '';
                foreach ($shareParticulars as $shareParticular) {
                    # get all shares transactions
                    $sharesTransactions = $this->entry->select('SUM(amount) as amount')->where(['entries.account_typeId' => 8, 'entries.particular_id' => $shareParticular['id']])->get()->getRow();
                    // return $sharesTransactions;
                    if (!$sharesTransactions) {
                        continue;
                        // return 'No Data';
                    }
                    # calculate total shares bought by all clients
                    $totalSharesBought = $sharesTransactions->amount;
                    
                    if(!$totalSharesBought) {
                        continue;
                        // return '';
                    }

                    # get client shares particular transactions totals
                    $clientShares = $this->entry->select('
                        SUM(CASE WHEN LOWER(entries.status) = "debit" THEN entries.amount ELSE 0 END) as sharesDebit,
                        SUM(CASE WHEN LOWER(entries.status) = "credit" THEN entries.amount ELSE 0 END) as sharesCredit')
                        ->where(['entries.account_typeId' => 8, 'entries.client_id' => $client->id, 'particular_id' => $shareParticular['id']])
                        ->get()->getRow();
                    if ($clientShares) {
                        $clientSharesPurchases = (float)$clientShares->sharesCredit;
                        $clientSharesWithdrawals = (float)$clientShares->sharesDebit;
                        $clientSharesBalance = ($clientSharesPurchases - $clientSharesWithdrawals);

                        if($clientSharesBalance <= 0) return 'N/A';

                        # lookup charge for share particular
                        $chargeRow = $this->vlookupSharesCharge($shareParticular['id'], $client->reg_date);
                        if ($chargeRow) {
                            $sharesLinks .=  ucwords($shareParticular['particular_name']) . ':<br><b>' . round(($clientSharesBalance / $chargeRow['charge']), 2) . '</b>  Units ~ ' . round((($clientSharesBalance / $totalSharesBought) * 100), 2) . '%';
                        } else {
                            $sharesLinks .= ucwords($shareParticular['particular_name']) . ':<br><b>' . $this->settingsRow['currency'] . '. ' . number_format($clientSharesBalance) . '</b> Units ~ ' . round((($clientSharesBalance / $totalSharesBought) * 100), 2) . '%';
                        }

                    } else {
                        $sharesLinks .= 'N/A '.$shareParticular['particular_name'];
                    }
                }
                return $sharesLinks;
            })
            ->add('action', function ($client) {
                $password =  $this->settings->generateRandomNumbers(8);
                if (strtolower($client->access_status) == 'active') {
                    $text = "info";
                    $statusBtn = '<div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="edit_clientStatus(' . "'" . $client->id . "'" . ',' . "'" . $client->name . "'" . ')" title="de-activate ' . ucwords($client->name) . '" class="dropdown-item">
                        <i class="fas fa-user-slash text-secondary"></i> De-activate ' . ucwords($client->name) . '
                        </a>';
                    $passwordBtn = '<div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="create_password(' . "'" . $client->id . "'" . ', ' . "'" . $password . "'" . ')" title="Generate Password"><i class="fas fa-lock text-warning"></i> New Password</a><div class="dropdown-divider"></div>';
                } else {
                    $text = "danger";
                    $statusBtn = '<div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" onclick="edit_clientStatus(' . "'" . $client->id . "'" . ',' . "'" . $client->name . "'" . ')" title="activate ' . ucwords($client->name) . '" class="dropdown-item">
                      <i class="fas fa-user-check text-success"></i> Activate ' . ucwords($client->name) . '
                    </a>';
                    $passwordBtn = '<div class="dropdown-divider"></div>';
                }
                // show buttons based on user permissions
                $actions = '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">';
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                    $encryptedId = bin2hex($this->encrypter->encrypt($client->id));
                    $actions .= '<a href="/admin/clients/client/' . $encryptedId . '/edit" title="View ' . ucwords($client->name) . '" class="dropdown-item"><i class="fa fa-eye text-success"></i> View ' . $client->name . '</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    $actions .= $statusBtn . '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="edit_client(' . "'" . $client->id . "'" . ')" title="edit client" class="dropdown-item">
                                        <i class="fa fa-edit text-info"></i> Edit ' . ucwords($client->name) . '
                                    </a>' . $passwordBtn;
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '
                                    <a href="javascript:void(0)" onclick="delete_client(' . "'" . $client->id . "'" . ',' . "'" . $client->name . "'" . ')" title="delete ' . ucwords($client->name) . '" class="dropdown-item">
                                        <i class="fa fa-trash text-danger"></i> Delete ' . ucwords($client->name) . '
                                    </a>';
                }
                $actions .= ' 
                        </div>
                </div>';
                return $actions;
            })
            ->toJson(true);
    }
    public function clients_report($filter, $bal = null, $btn, $from = null, $to = null)
    {
        switch (strtolower($filter)) {
            case "equal":
                if ($from != 0 && $to == 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'account_balance' => $bal, 'deleted_at' => Null];
                } elseif ($from == 0 && $to != 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $to, 'account_balance' => $bal, 'deleted_at' => Null];
                } elseif ($from != 0 && $to != 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(created_at, "%Y-%m-%d") <=' => $to, 'account_balance' => $bal, 'deleted_at' => Null];
                } else {
                    $where = ['account_balance' => $bal, 'deleted_at' => Null];
                }
                break;
            case "above":
                if ($from != 0 && $to == 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'account_balance >' => $bal, 'deleted_at' => Null];
                } elseif ($from == 0 && $to != 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $to, 'account_balance >' => $bal, 'deleted_at' => Null];
                } elseif ($from != 0 && $to != 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(created_at, "%Y-%m-%d") <=' => $to, 'account_balance >' => $bal, 'deleted_at' => Null];
                } else {
                    $where = ['account_balance >' => $bal, 'deleted_at' => Null];
                }
                break;
            case "below":
                if ($from != 0 && $to == 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'account_balance <' => $bal, 'deleted_at' => Null];
                } elseif ($from == 0 && $to != 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $to, 'account_balance <' => $bal, 'deleted_at' => Null];
                } elseif ($from != 0 && $to != 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(created_at, "%Y-%m-%d") <=' => $to, 'account_balance <' => $bal, 'deleted_at' => Null];
                } else {
                    $where = ['account_balance <' => $bal, 'deleted_at' => Null];
                }
                break;
            case "between":
                if ($from != 0 && $to == 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'account_balance >' => $bal, 'account_balance <' => $btn, 'deleted_at' => Null];
                } elseif ($from == 0 && $to != 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $to, 'account_balance >' => $bal, 'account_balance <' => $btn, 'deleted_at' => Null];
                } elseif ($from != 0 && $to != 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(created_at, "%Y-%m-%d") <=' => $to, 'account_balance >' => $bal, 'account_balance <' => $btn, 'deleted_at' => Null];
                } else {
                    $where = ['account_balance >' => $bal, 'account_balance <' => $btn, 'deleted_at' => Null];
                }
                break;
            default:
                if ($from != 0 && $to == 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'deleted_at' => Null];
                } elseif ($from == 0 && $to != 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $to, 'deleted_at' => Null];
                } elseif ($from != 0 && $to != 0) {
                    $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(created_at, "%Y-%m-%d") <=' => $to, 'deleted_at' => Null];
                } else {
                    $where = ['deleted_at' => Null];
                }
                break;
        }
        $clients = $this->client->select('id ,name, account_no, account_balance, email, mobile, residence, access_status, photo')->where($where);
        return DataTable::of($clients)
            ->add('checkbox', function ($client) {
                return '<div class=""><input type="checkbox" class="data-check" value="' . $client->id . '"></div>';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('photo', function ($client) {
                # check whether the photo exist
                if (file_exists("uploads/clients/passports/" . $client->photo) && $client->photo) {
                    # display the current photo
                    return '
                    <a href="javascript:void(0)" onclick="view_client_photo(' . "'" . $client->id . "'" . ')" title="' . ucwords($client->name) . '"><img src="' . base_url('uploads/clients/passports/' . $client->photo) . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                } else {
                    # display the default photo
                    return '
                    <a href="javascript:void(0)" onclick="view_client_photo(' . "'" . $client->id . "'" . ')" title="no photo"><img src="' . base_url('assets/dist/img/nophoto.jpg') . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                }
            })
            ->add('action', function ($client) {
                if (strtolower($client->access_status) == 'active') {
                    $text = "text-info";
                } else {
                    $text = "text-danger";
                }
                return '
                    <div class="text-center">
                        <a href="javascript:void(0)" onclick="view_client(' . "'" . $client->id . "'" . ')" title="view client" class="' . $text . '"><i class="fas fa-eye"></i></a>
                    </div>
                ';
            })
            ->toJson(true);
    }
    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->client->find($id);
        if ($data) {
            return $this->respond(($this->clientDataRow($id)));
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

    /**
     * get all clients
     */
    public function getClients()
    {
        $data = $this->client->where(['access_status' => 'Active'])->findAll();
        return $this->respond($data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function createNew()
    {
        $mode = $this->request->getVar('mode');
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, strtolower($mode))) {
            $reg_date = trim($this->request->getVar('reg_date'));
            $acc_number = $this->settings->generateUniqueNo('client', $reg_date);

            if (strtolower($mode) == 'create') {
                $this->_validateClient('add');
                $mobile = trim($this->request->getVar('mobile_full'));
                $email = trim($this->request->getVar('c_email'));
                $password = $this->settings->generateRandomNumbers(8);
                $alternate_no = trim($this->request->getVar('alternate_mobile_full'));
                $nok_phone = trim($this->request->getVar('nok_phone_full'));
                $nok_phone_alt = trim($this->request->getVar('nok_alt_phone_full'));

                $savingsProducts = $this->request->getVar('savings_products[]');
                for ($i = 0; $i < count($savingsProducts); $i++) {
                    $savings_products[] = [
                        "product_id" => (isset($savingsProducts[$i]) ? $savingsProducts[$i] : null),
                        "product_balance" => (isset($savingsProducts[$i]) ? '0.00' : null),
                    ];
                }

                $data = [
                    'title' => trim($this->request->getVar('title')),
                    'name' => trim($this->request->getVar('c_name')),
                    'account_type' => "Client",
                    'account_no' => $acc_number,
                    'mobile' => preg_replace('/^0/', '+256', $mobile),
                    'email' => $email,
                    'alternate_no' => preg_replace('/^0/', '+256', $alternate_no),
                    'gender' => trim($this->request->getVar('gender')),
                    'dob' => trim($this->request->getVar('dob')),
                    'reg_date' => trim($this->request->getVar('reg_date')),
                    'marital_status' => trim($this->request->getVar('marital_status')),
                    'religion' => trim($this->request->getVar('religion')),
                    'nationality' => trim($this->request->getVar('nationality')),
                    'staff_id' => $this->userRow['staff_id'],
                    'occupation' => trim($this->request->getVar('occupation')),
                    'job_location' => trim($this->request->getVar('job_location')),
                    'residence' => trim($this->request->getVar('residence')),
                    'id_type' => trim($this->request->getVar('id_type')),
                    'id_number' => trim(strtoupper($this->request->getVar('id_number'))),
                    'id_expiry_date' => trim($this->request->getVar('id_expiry')),
                    'next_of_kin_name' => trim($this->request->getVar('next_of_kin')),
                    'next_of_kin_relationship' => trim($this->request->getVar('nok_relationship')),
                    'next_of_kin_contact' => preg_replace('/^0/', '+256', $nok_phone),
                    'next_of_kin_alternate_contact' => preg_replace('/^0/', '+256', $nok_phone_alt),
                    'nok_email' => trim($this->request->getVar('nok_email')),
                    'nok_address' => trim($this->request->getVar('nok_address')),
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'branch_id' => trim($this->request->getVar('branch_id')),
                    'savings_products' => json_encode($savings_products)
                ];
                // upload client photo
                if (!empty($_FILES['photo']['name'])) {
                    $upload = $this->settings->_doUploadPhoto("client",  $this->request->getFile('photo'));
                    $data['photo'] = $upload;
                }
                if (!empty($_FILES['id_photo_front']['name'])) {
                    $idFront = $this->settings->_doUploadIdPhoto("client", 'front',  $this->request->getFile('id_photo_front'));
                    $data['id_photo_front'] = $idFront;
                }
                if (!empty($_FILES['id_photo_back']['name'])) {
                    $idBack = $this->settings->_doUploadIdPhoto("client", 'back',  $this->request->getFile('id_photo_back'));
                    $data['id_photo_back'] = $idBack;
                }
                if (!empty($_FILES['signature']['name'])) {
                    $sign = $this->settings->_doUploadSignature("client",  $this->request->getFile('signature'));
                    $data['signature'] = $sign;
                }
                # save the client information
                $insert = $this->client->insert($data);
                # check whether the internet connection exist
                $checkInternet = $this->settings->checkNetworkConnection();
                if ($checkInternet) {
                    # check the email existence and email notify is enabled
                    if (!empty($email) && $this->settingsRow['email']) {
                        $subject = $this->title . " Registration";
                        $message = $data;
                        $token = 'registration';
                        $this->settings->sendMail($message, $subject, $token, $password);
                    }

                    # check the phone number existence and sms notify is enabled
                    if (!empty($data['mobile']) && $this->settingsRow['sms']) {
                        # send sms
                        $sms = $this->sendSMS([
                            'mobile' => trim($data['mobile']),
                            'text' => 'Your Login for ' . strtoupper($this->settingsRow["system_abbr"]) . ' is ' . $password
                        ]);
                    }
                }

                $index = 1;
            } else {
                if (!empty($_FILES['file']['name']) && !empty($this->request->getVar('branchID'))) {
                    # get uploaded file extension
                    $path_parts = pathinfo($_FILES["file"]["name"]);
                    $ext = $path_parts['extension'];
                    # check whether the uploaded file extension matches with csv
                    if ($ext == 'csv') {
                        $file = $this->request->getFile("file");
                        $file_name = $file->getTempName();
                        $file_data = array_map('str_getcsv', file($file_name));
                        // print_r($file_data);
                        // echo "<br><br>";
                        // var_dump($file_data);
                        // exit;
                        if (count($file_data) > 0) {
                            $index = 0;
                            $data = [];
                            foreach ($file_data as $column) {
                                $index++;
                                # generate the password
                                $password = $this->settings->generateRandomNumbers(8);
                                # ignore the column headers
                                if ($index == 1) {
                                    continue;
                                }
                                # ignore empty row in excel sheets
                                if ((string) $column[0] != '0' && empty($column[0])) {
                                    continue;
                                }
                                # generate account number for each client
                                $client_reg_date = date('Y-m-d', strtotime($column[22]));
                                $client_account_no = $this->settings->generateUniqueNo('client', $client_reg_date);
                                $email = trim($column[4]);

                                # check the client account_no existence
                                $account = $this->client->where([
                                    'account_no' => trim($column[23])
                                    # 'account_no' => trim($client_account_no)
                                ])->countAllResults();

                                # ignore the client with the same account number
                                if ($account) {
                                    continue;
                                }

                                # set phone number and the alternate phone number to null
                                $mobile = $alternate_no = $nok_contact = $nok_alt_contact = '';
                                # check the phone number
                                if (!empty(trim($column[2]))) {
                                    # check whether the phone number starts with 0 or + characters
                                    if ((substr($column[2], 0, 1) == '0') ||
                                        (substr($column[2], 0, 1) == '+')
                                    ) {
                                        # replace +256 with phone number that starts with 0
                                        $mobile = preg_replace('/^0/', '+256', trim($column[2]));
                                    } else {
                                        # add +256 to the phone number since it has no leading 0
                                        $mobile = '+256' . $column[2];
                                    }
                                }

                                # check the alternate phone number
                                if (!empty(trim($column[3]))) {
                                    # check whether the phone number starts with 0 or + characters
                                    if ((substr($column[3], 0, 1) == '0') ||
                                        (substr($column[3], 0, 1) == '+')
                                    ) {
                                        # replace +256 with phone number that starts with 0
                                        $alternate_no = preg_replace('/^0/', '+256', trim($column[3]));
                                    } else {
                                        # add +256 to the phone number since it has no leading 0
                                        $alternate_no = '+256' . $column[3];
                                    }
                                }

                                # check the phone number
                                if (!empty(trim($column[18]))) {
                                    # check whether the phone number starts with 0 or + characters
                                    if ((substr($column[18], 0, 1) == '0') ||
                                        (substr($column[18], 0, 1) == '+')
                                    ) {
                                        # replace +256 with phone number that starts with 0
                                        $nok_contact = preg_replace('/^0/', '+256', trim($column[18]));
                                    } else {
                                        # add +256 to the phone number since it has no leading 0
                                        $nok_contact = '+256' . $column[18];
                                    }
                                }

                                # check the alternate phone number
                                if (!empty(trim($column[19]))) {
                                    # check whether the phone number starts with 0 or + characters
                                    if ((substr($column[19], 0, 1) == '0') ||
                                        (substr($column[19], 0, 1) == '+')
                                    ) {
                                        # replace +256 with phone number that starts with 0
                                        $nok_alt_contact = preg_replace('/^0/', '+256', trim($column[19]));
                                    } else {
                                        # add +256 to the phone number since it has no leading 0
                                        $nok_alt_contact = '+256' . $column[19];
                                    }
                                }

                                # $client[] = [

                                $client = [
                                    'branch_id' => trim($this->request->getVar('branchID')),
                                    'staff_id' => $this->userRow['staff_id'],
                                    # 'account_no' => $client_account_no,
                                    'password' => password_hash($password, PASSWORD_DEFAULT),
                                    # 'account_type' => 'Client',
                                    # csv column header information
                                    'title' => trim($column[0]),
                                    'name' => trim($column[1]),
                                    'mobile' => $mobile,
                                    'alternate_no' => $alternate_no,
                                    'email' => trim($column[4]),
                                    'gender' => trim($column[5]),
                                    'dob' => date('Y-m-d', strtotime($column[6])),
                                    'marital_status' => trim($column[7]),
                                    'religion' => trim($column[8]),
                                    'nationality' => trim($column[9]),
                                    'occupation' => trim($column[10]),
                                    'job_location' => trim($column[11]),
                                    'residence' => trim($column[12]),
                                    'id_type' => trim($column[13]),
                                    'id_number' => trim(strtoupper($column[14])),
                                    'id_expiry_date' => date('Y-m-d', strtotime($column[15])),
                                    'next_of_kin_name' => trim($column[16]),
                                    'next_of_kin_relationship' => trim($column[17]),
                                    'next_of_kin_contact' => trim($nok_contact),
                                    'next_of_kin_alternate_contact' => trim($nok_alt_contact),
                                    'nok_email' => trim($column[20]),
                                    'nok_address' => trim($column[21]),
                                    'reg_date' => date('Y-m-d', strtotime($column[22])),
                                    'account_no' => trim($column[23]),
                                    'account_type' => trim($column[24]),
                                ];

                                # save the client information
                                $insert = $this->client->insert($client);

                                # check whether the internet connection exist
                                $checkInternet = $this->settings->checkNetworkConnection();
                                if ($checkInternet) {
                                    # check the email existence and email notify is enabled
                                    if (!empty($email) && $this->settingsRow['email']) {
                                        $subject = $this->title . " Registration";
                                        $message = $client;
                                        $token = 'registration';
                                        $this->settings->sendMail($message, $subject, $token, $password);
                                    }

                                    # check the phone number existence and sms notify is enabled
                                    if (!empty($mobile) && $this->settingsRow['sms']) {
                                        # send sms
                                        $sms = $this->sendSMS([
                                            'mobile' => trim($mobile),
                                            'text' => 'Your Login for ' . strtoupper($this->settingsRow["system_abbr"]) . ': ' . $password
                                        ]);
                                    }
                                }
                            }
                            # insert imported data
                            # $insert = $this->client->insertBatch($client);
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
            }
            # Activity
            $record = ($index == 1) ? " record" : " records";
            $this->saveUserActivity([
                'user_id' => $this->userRow['id'],
                'action' => $mode,
                'description' => ($index . ' client ' . $record),
                'module' => strtolower($this->menu),
                'referrer_id' => $insert,
                'title' => $this->title,
            ]);
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to ' . $mode . ' ' . $this->title . ' record(s)!',
            ];
            return $this->respond($response);
            exit;
        }
    }
    public function create()
    {
        $mode = $this->request->getVar('mode');
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, strtolower($mode))) {
            $reg_date = trim($this->request->getVar('reg_date'));
            $acc_number = $this->settings->generateUniqueNo('client', $reg_date);

            if (strtolower($mode) == 'create') {
                $this->_validateClient('add');
                $mobile = trim($this->request->getVar('mobile_full'));
                $email = trim($this->request->getVar('c_email'));
                $password = $this->settings->generateRandomNumbers(8);
                $alternate_no = trim($this->request->getVar('alternate_mobile_full'));
                $nok_phone = trim($this->request->getVar('nok_phone_full'));
                $nok_phone_alt = trim($this->request->getVar('nok_alt_phone_full'));
                $savings_products = null;
                $savingsProducts = $this->request->getVar('savings_products[]');
                # check whether the client has the savings product
                if ($savingsProducts) {
                    for ($i = 0; $i < count($savingsProducts); $i++) {
                        $savings_products[] = [
                            "product_id" => (isset($savingsProducts[$i]) ? $savingsProducts[$i] : null),
                            "product_balance" => (isset($savingsProducts[$i]) ? '0.00' : null),
                        ];
                    }
                }

                # client data
                $data = [
                    'name' => strtoupper(trim($this->request->getVar('c_name'))),
                    'title' => strtoupper(trim($this->request->getVar('title'))),
                    'account_type' => "Client",
                    'account_no' => $acc_number,
                    'mobile' => preg_replace('/^0/', '+256', $mobile),
                    'email' => $email,
                    'alternate_no' => preg_replace('/^0/', '+256', $alternate_no),
                    'gender' => trim($this->request->getVar('gender')),
                    'dob' => trim($this->request->getVar('dob')),
                    'reg_date' => trim($this->request->getVar('reg_date')),
                    'marital_status' => trim($this->request->getVar('marital_status')),
                    'religion' => trim($this->request->getVar('religion')),
                    'nationality' => trim($this->request->getVar('nationality')),
                    'staff_id' => $this->userRow['staff_id'],
                    'occupation' => trim($this->request->getVar('occupation')),
                    'job_location' => trim($this->request->getVar('job_location')),
                    'residence' => trim($this->request->getVar('residence')),
                    'closest_landmark' => trim($this->request->getVar('closest_landmark')),
                    'id_type' => trim($this->request->getVar('id_type')),
                    'id_number' => trim(strtoupper($this->request->getVar('id_number'))),
                    'id_expiry_date' => trim($this->request->getVar('id_expiry')),
                    'next_of_kin_name' => strtoupper((trim($this->request->getVar('next_of_kin')))),
                    'next_of_kin_relationship' => trim($this->request->getVar('nok_relationship')),
                    'next_of_kin_contact' => preg_replace('/^0/', '+256', $nok_phone),
                    'next_of_kin_alternate_contact' => preg_replace('/^0/', '+256', $nok_phone_alt),
                    'nok_email' => trim($this->request->getVar('nok_email')),
                    'nok_address' => trim($this->request->getVar('nok_address')),
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'branch_id' => trim($this->request->getVar('branch_id')),
                    'savings_products' => ($savings_products) ? json_encode($savings_products) : null,
                    'account_id' => $this->userRow['account_id'],
                ];
                // upload client photo
                if (!empty($_FILES['photo']['name'])) {
                    $upload = $this->settings->_doUploadPhoto("client",  $this->request->getFile('photo'));
                    $data['photo'] = $upload;
                }
                if (!empty($_FILES['id_photo_front']['name'])) {
                    $idFront = $this->settings->_doUploadIdPhoto("client", 'front',  $this->request->getFile('id_photo_front'));
                    $data['id_photo_front'] = $idFront;
                }
                if (!empty($_FILES['id_photo_back']['name'])) {
                    $idBack = $this->settings->_doUploadIdPhoto("client", 'back',  $this->request->getFile('id_photo_back'));
                    $data['id_photo_back'] = $idBack;
                }
                if (!empty($_FILES['signature']['name'])) {
                    $sign = $this->settings->_doUploadSignature("client",  $this->request->getFile('signature'));
                    $data['signature'] = $sign;
                }
                # check whether the digital signature was signature
                if (!empty($this->request->getVar('signature_image'))) {
                    # upload the signature and return the file name
                    $sign = $this->_doUploadDigitalSignature("client",  $this->request->getVar('signature_image'));
                    # save the new digital signature
                    $data['signature'] = $sign;
                }
                # save the client information
                $insert = $this->client->insert($data);

                # check if the client has been registered successfully
                if ($insert) {
                    # client relationship data
                    $relationshipData = [
                        'full_name' => strtoupper((trim($this->request->getVar('next_of_kin')))),
                        'contact' => preg_replace('/^0/', '+256', $nok_phone),
                        'alternate_contact' => preg_replace('/^0/', '+256', $nok_phone_alt),
                        'email' => trim($this->request->getVar('nok_email')),
                        'address' => trim($this->request->getVar('nok_address')),
                        'relationship' => trim($this->request->getVar('nok_relationship')),
                        'type' => 'next_of_kin',
                        'client_id' => 'insert',
                    ];
                    # save the relationship
                    $this->relationships->insert($relationshipData);
                    
                    $response = [
                       'status' => true,
                       'message' => 'Client registered successfully',
                        'data' => $data,
                    ];
                } else {
                    $response = [
                       'status' => false,
                       'message' => 'Failed to register client',
                    ];
                    return $this->respond([
                        'status'   => 301,
                        'error'    => 'Access Denied',
                        'messages' => 'Failed ' . $mode . ' ' . $this->title . ' record(s), try again later!',
                    ]);
                    exit;
                }
                # check whether the internet connection exist
                $checkInternet = $this->settings->checkNetworkConnection();
                if ($checkInternet) {
                    # check the email existence and email notify is enabled
                    if (!empty($email) && $this->settingsRow['email']) {
                        $subject = $this->title . " Registration";
                        $message = $data;
                        $token = 'registration';
                        $this->settings->sendMail($message, $subject, $token, $password);
                    }

                    # check the phone number existence and sms notify is enabled
                    if (!empty($data['mobile']) && $this->settingsRow['sms']) {
                        # send sms
                        $sms = $this->sendSMS([
                            'mobile' => trim($data['mobile']),
                            'text' => 'Your Login for ' . strtoupper($this->settingsRow["system_abbr"]) . ' is ' . $password
                        ]);
                    }
                }

                $index = 1;
            } else {
                if (!empty($_FILES['file']['name']) && !empty($this->request->getVar('branchID'))) {
                    # get uploaded file extension
                    $path_parts = pathinfo($_FILES["file"]["name"]);
                    $ext = $path_parts['extension'];
                    # check whether the uploaded file extension matches with csv
                    if ($ext == 'csv') {
                        $file = $this->request->getFile("file");
                        $file_name = $file->getTempName();
                        $file_data = array_map('str_getcsv', file($file_name));
                        // print_r($file_data);
                        // echo "<br><br>";
                        // var_dump($file_data);
                        // exit;
                        if (count($file_data) > 0) {
                            $index = 0;
                            $data = [];
                            foreach ($file_data as $column) {
                                $index++;
                                # generate the password
                                $password = $this->settings->generateRandomNumbers(8);
                                # ignore the column headers
                                if ($index == 1) {
                                    continue;
                                }
                                # ignore empty row in excel sheets
                                if ((string) $column[0] != '0' && empty($column[0])) {
                                    continue;
                                }
                                # generate account number for each client
                                $client_reg_date = (!empty(trim($column[23])) ? date('Y-m-d', strtotime($column[23])) : date('Y-m-d', strtotime('now')));
                                # $client_reg_date = date('Y-m-d', strtotime($column[23]));
                                $client_account_no = $this->settings->generateUniqueNo('client', $client_reg_date);
                                $email = trim($column[4]);

                                # check the client account_no existence
                                $account = $this->client->where([
                                    'account_no' => trim($column[23])
                                    # 'account_no' => trim($client_account_no)
                                ])->countAllResults();

                                # ignore the client with the same account number
                                if ($account) {
                                    continue;
                                }

                                # set phone number and the alternate phone number to null
                                $mobile = $alternate_no = $nok_contact = $nok_alt_contact = '';
                                # check the phone number
                                if (!empty(trim($column[2]))) {
                                    # check whether the phone number starts with 0 or + characters
                                    if ((substr($column[3], 0, 1) == '0') ||
                                        (substr($column[3], 0, 1) == '+')
                                    ) {
                                        # replace +256 with phone number that starts with 0
                                        $mobile = preg_replace('/^0/', '+256', trim($column[2]));
                                    } else {
                                        # add +256 to the phone number since it has no leading 0
                                        $alternate_no = '+256' . $column[2];
                                    }
                                }

                                # check the phone number
                                if (!empty(trim($column[17]))) {
                                    # check whether the phone number starts with 0 or + characters
                                    if ((substr($column[19], 0, 1) == '0') ||
                                        (substr($column[19], 0, 1) == '+')
                                    ) {
                                        # replace +256 with phone number that starts with 0
                                        $nok_contact = preg_replace('/^0/', '+256', trim($column[1]));
                                    } else {
                                        # add +256 to the phone number since it has no leading 0
                                        $nok_contact = '+256' . $column[1];
                                    }
                                }

                                # check the alternate phone number
                                if (!empty(trim($column[18]))) {
                                    # check whether the phone number starts with 0 or + characters
                                    if ((substr($column[2], 0, 1) == '0') ||
                                        (substr($column[2], 0, 1) == '+')
                                    ) {
                                        # replace +256 with phone number that starts with 0
                                        $nok_alt_contact = preg_replace('/^0/', '+256', trim($column[20]));
                                    } else {
                                        # add +256 to the phone number since it has no leading 0
                                        $nok_alt_contact = '+256' . $column[1];
                                    }
                                }

                                $client = [
                                    'branch_id' => trim($this->request->getVar('branchID')),
                                    'staff_id' => $this->userRow['staff_id'],
                                    # 'account_no' => $client_account_no,
                                    'password' => password_hash($password, PASSWORD_DEFAULT),
                                    # 'account_type' => 'Client',
                                    # csv column header information
                                    'title' => trim($column[0]),
                                    'name' => trim($column[1]),
                                    'mobile' => $mobile,
                                    'alternate_no' => $alternate_no,
                                    'email' => trim($column[3]),
                                    'gender' => trim($column[4]),
                                    'dob' => date('Y-m-d', strtotime($column[5])),
                                    'marital_status' => trim($column[6]),
                                    'religion' => trim($column[7]),
                                    'nationality' => trim($column[8]),
                                    'occupation' => trim($column[9]),
                                    'job_location' => trim($column[10]),
                                    'residence' => trim($column[11]),
                                    'id_type' => trim($column[12]),
                                    'id_number' => trim(strtoupper($column[13])),
                                    'id_expiry_date' => date('Y-m-d', strtotime($column[14])),
                                    'next_of_kin_name' => trim($column[15]),
                                    'next_of_kin_relationship' => trim($column[16]),
                                    'next_of_kin_contact' => trim($nok_contact),
                                    'next_of_kin_alternate_contact' => trim($nok_alt_contact),
                                    'nok_email' => trim($column[21]),
                                    'nok_address' => trim($column[22]),
                                    'reg_date' => $client_reg_date,
                                    'account_no' => (!empty(trim($column[24])) ? trim($column[24]) : $client_account_no),
                                    'account_type' => trim($column[25]),
                                    'account_id' => $this->userRow['account_id'],
                                ];

                                # save the client information
                                $insert = $this->client->insert($client);

                                # check whether the internet connection exist
                                $checkInternet = $this->settings->checkNetworkConnection();
                                if ($checkInternet) {
                                    # check the email existence and email notify is enabled
                                    if (!empty($email) && $this->settingsRow['email']) {
                                        $subject = $this->title . " Registration";
                                        $message = $client;
                                        $token = 'registration';
                                        $this->settings->sendMail($message, $subject, $token, $password);
                                    }

                                    # check the phone number existence and sms notify is enabled
                                    if (!empty($mobile) && $this->settingsRow['sms']) {
                                        # send sms
                                        $sms = $this->sendSMS([
                                            'mobile' => trim($mobile),
                                            'text' => 'Your Login for ' . strtoupper($this->settingsRow["system_abbr"]) . ': ' . $password
                                        ]);
                                    }
                                }
                            }
                            # insert imported data
                            # $insert = $this->client->insertBatch($client);
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
            }
            # Activity
            $record = ($index == 1) ? " record" : " records";
            $this->saveUserActivity([
                'user_id' => $this->userRow['id'],
                'action' => $mode,
                'description' => ($index . ' client ' . $record),
                'module' => strtolower($this->menu),
                'referrer_id' => $insert,
                'title' => $this->title,
            ]);
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to ' . $mode . ' ' . $this->title . ' record(s)!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
            $decryptedId = $this->encrypter->decrypt(hex2bin($id));
            $client = $this->clientDataRow($decryptedId);

            if ($client) {
                return view('admin/clients/view', [
                    'title' => $client['name'],
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'client_id' => $id,
                    'newPassword' => $this->settings->generateRandomNumbers(8),
                    'client' => $client,
                    'module' => $this->title,
                    'userMenu' => $this->load_menu(),
                    'disbursements' => $this->client_loans($decryptedId),
                    'relations' => $this->settings->generateRelationships(),
                    'applicationsCounter' => $this->counter('applications'),
                    'charges' => $this->getCharges([
                        'charges.status' => 'Active',
                        'p.account_typeId' => 18,
                        'p.particular_status' => 'Active',
                        'charges.product_id' => null
                    ]),
                    'decryptedId' => $decryptedId,
                ]);
            } else {
                return view('layout/404', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ]);
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to view " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update_client($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateClient('update');
                $clientRow = $clientRow = $this->checkArrayExistance('client', [
                    'id' => $id,
                ]);
                $mobile = $this->request->getVar('mobile_full');
                $email = $this->request->getVar('c_email');
                $alternate_no = $this->request->getVar('alternate_mobile_full');
                $nok_phone = $this->request->getVar('nok_phone_full');
                $nok_phone_alt = $this->request->getVar('nok_alt_phone_full');
                // set up savings products
                $savingsProducts = $this->request->getVar('savings_products[]');
                $clientSavingsProducts = $clientRow['savingsProducts'];
                $savings_products = null;
                # check the savings product existence
                if ($savingsProducts) {
                    for ($i = 0; $i < count($savingsProducts); $i++) {
                        $savings_products[] = [
                            "product_id" => (isset($savingsProducts[$i]) ? $savingsProducts[$i] : null),
                            "product_balance" => (isset($savingsProducts[$i]) ? '0.00' : null),
                        ];
                    }
                }

                $data = [
                    'name' => strtoupper(trim($this->request->getVar('c_name'))),
                    'mobile' => trim(preg_replace('/^0/', '+256', $mobile)),
                    'email' => trim($email),
                    'alternate_no' => trim(preg_replace('/^0/', '+256', $alternate_no)),
                    'gender' => trim($this->request->getVar('gender')),
                    'dob' => trim($this->request->getVar('dob')),
                    'reg_date' => trim($this->request->getVar('reg_date')),
                    'marital_status' => trim($this->request->getVar('marital_status')),
                    'religion' => trim($this->request->getVar('religion')),
                    'nationality' => trim($this->request->getVar('nationality')),
                    // 'staff_id' => $this->userRow['staff_id'],
                    'occupation' => trim($this->request->getVar('occupation')),
                    'job_location' => trim($this->request->getVar('job_location')),
                    'residence' => trim($this->request->getVar('residence')),
                    'closest_landmark' => trim($this->request->getVar('closest_landmark')),
                    'id_type' => trim($this->request->getVar('id_type')),
                    'id_number' => trim(strtoupper($this->request->getVar('id_number'))),
                    'id_expiry_date' => trim($this->request->getVar('id_expiry')),
                    'next_of_kin_name' => strtoupper(trim($this->request->getVar('next_of_kin'))),
                    'next_of_kin_relationship' => trim($this->request->getVar('nok_relationship')),
                    'next_of_kin_contact' => trim(preg_replace('/^0/', '+256', $nok_phone)),
                    'next_of_kin_alternate_contact' => trim(preg_replace('/^0/', '+256', $nok_phone_alt)),
                    'nok_email' => trim($this->request->getVar('nok_email')),
                    'nok_address' => trim($this->request->getVar('nok_address')),
                    'branch_id' => trim($this->request->getVar('branch_id')),
                    'savings_products' => ($savings_products) ? json_encode($savings_products) : null
                ];
                # check whether the photo has been uploaded
                if (!empty($_FILES['photo']['name'])) {
                    $upload_photo = $this->settings->_doUploadPhoto("client", $this->request->getFile('photo'));
                    if (file_exists("uploads/clients/passports/" . $clientRow['photo']) && $clientRow['photo']) {
                        # delete or remove the previous photo
                        unlink("uploads/clients/passports/" . $clientRow['photo']);
                    }
                    $data['photo'] = $upload_photo;
                }
                if (!empty($_FILES['id_photo_front']['name'])) {
                    $upload_idFront = $this->settings->_doUploadIdPhoto("client", 'front', $this->request->getFile('id_photo_front'));
                    if (file_exists("uploads/clients/ids/front/" . $clientRow['id_photo_front']) && $clientRow['id_photo_front']) {
                        # delete or remove the previous photo
                        unlink("uploads/clients/ids/front/" . $clientRow['id_photo_front']);
                    }
                    $data['id_photo_front'] = $upload_idFront;
                }
                if (!empty($_FILES['id_photo_back']['name'])) {
                    $upload_idBack = $this->settings->_doUploadIdPhoto("client", 'back', $this->request->getFile('id_photo_back'));
                    if (file_exists("uploads/clients/ids/back/" . $clientRow['id_photo_back']) && $clientRow['id_photo_back']) {
                        # delete or remove the previous photo
                        unlink("uploads/clients/ids/back/" . $clientRow['id_photo_back']);
                    }
                    $data['id_photo_back'] = $upload_idBack;
                }
                if (!empty($_FILES['signature6']['name'])) {
                    $upload_sign = $this->settings->_doUploadSignature("client", $this->request->getFile('signature'));
                    if (file_exists("uploads/clients/signatures/" . $clientRow['signature']) && $clientRow['signature']) {
                        unlink("uploads/clients/signatures/" . $clientRow['signature']);
                    }
                    $data['signature'] = $upload_sign;
                }
                # check whether the digital signature was signature
                if (!empty($this->request->getVar('signature_image'))) {
                    # upload the signature and return the file name
                    $sign = $this->_doUploadDigitalSignature("client",  $this->request->getVar('signature_image'));
                    # check the signature existence and then delete it if it exists
                    if (file_exists("uploads/clients/signatures/" . $clientRow['signature']) && $clientRow['signature']) {
                        # delete the previous [old] signature
                        unlink("uploads/clients/signatures/" . $clientRow['signature']);
                    }
                    # save the new digital signature
                    $data['signature'] = $sign;
                }

                // echo json_encode($data['signature']); exit
                $update = $this->client->update($id, $data);
                # Activity
                $this->saveUserActivity([
                    'user_id' => $this->userRow['id'],
                    'action' => 'update',
                    'description' => ('client: ' . $data['name']),
                    'module' => strtolower($this->menu),
                    'referrer_id' => $id,
                    'title' => $this->title,
                ]);
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'Update Failed. Invalid ID provided, try again!',
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
    public function update_clientStatus($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            $data = $this->client->find($id);
            $mode = $this->request->getVar('mode');
            if ($data) {
                if (isset($mode) && $mode == 'regenerate') {
                    $token = "passwords";
                    $password = ($this->request->getVar('c_password')) ? $this->request->getVar('c_password') : $this->settings->generateRandomNumbers(8);
                    $edit = $this->client->update($id, [
                        'password' => password_hash($password, PASSWORD_DEFAULT)
                    ]);
                    $data['menu'] = $mode;
                    $data['password'] = $password;
                } else {
                    $token = 'access_status';
                    if (strtolower($data['access_status']) == 'active') {
                        $status = 'Inactive';
                        $edit = $this->client->update($id, ['access_status' => $status]);
                    } else {
                        $status = 'Active';
                        $edit = $this->client->update($id, ['access_status' => $status]);
                    }
                    $data['status'] = $status;
                }
                if ($edit) {
                    if (isset($mode) && $mode == 'regenerate') {
                        $description = "Client updated successfully";
                    } else {
                        $description = ucfirst('updated ' . $this->title . ' access status, ' . $data['name']);
                    }
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'update',
                        'description' => $description,
                        'module' => $this->menu,
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $txt = '';
                        $checkInternet = $this->settings->checkNetworkConnection();
                        if ($checkInternet && $mode == 'regenerate') {
                            # check the email existence and email notify is enabled
                            if (!empty($data['email']) && $this->settingsRow['email']) {
                                $subject = $this->title . " " . ucwords($mode);
                                $message = $data;
                                $token = $token;
                                $this->settings->sendMail($message, $subject, $token, $password);
                                $txt .= 'Email Sent';
                            }

                            # check the phone number existence and sms notify is enabled
                            if (!empty($data['mobile']) && $this->settingsRow['sms']) {
                                # send sms
                                $sms = $this->sendSMS([
                                    'mobile' => trim($data['mobile']),
                                    'text' => 'Your Login for ' . strtoupper($this->settingsRow["system_abbr"]) . ' is ' . $password
                                ]);
                            }
                        }
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => 'Client record updated successfully',
                        ];
                        return $this->respond($response);
                        exit;
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => 'Client updated successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }

                    # Activity
                    $this->saveUserActivity([
                        'user_id' => $this->userRow['id'],
                        'action' => 'update',
                        'description' => ('client: ' . $data['name'] . ' access status to ' . $data['access_status']),
                        'module' => strtolower($this->menu),
                        'referrer_id' => $id,
                        'title' => $this->title,
                    ]);
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
                    'messages' => 'The requested ' . $this->title . ' resource could no be found!'
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
    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
            $data = $this->client->find($id);
            if ($data) {
                # delete or remove photo
                if (file_exists("uploads/clients/passports/" . $data['photo']) && $data['photo']) {
                    unlink("uploads/clients/passports/" . $data['photo']);
                }
                if (file_exists("uploads/clients/ids/front/" . $data['id_photo_front']) && $data['id_photo_front']) {
                    unlink("uploads/clients/ids/front/" . $data['id_photo_front']);
                }
                if (file_exists("uploads/clients/ids/back/" . $data['id_photo_back']) && $data['id_photo_back']) {
                    unlink("uploads/clients/ids/back/" . $data['id_photo_back']);
                }
                if (file_exists("uploads/clients/signatures/" . $data['signature']) && $data['signature']) {
                    unlink("uploads/clients/signatures/" . $data['signature']);
                }
                $delete = $this->client->delete($id);
                if ($delete) {
                    # Activity
                    $this->saveUserActivity([
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ('client: ' . $data['name']),
                        'module' => strtolower($this->menu),
                        'referrer_id' => $id,
                        'title' => $this->title,
                    ]);
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Delete Failed',
                        'messages' => 'Deleting ' . $this->title . ' record failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'The requested ' . $this->title . ' resource could not be found!',
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
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkdelete')) {
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->client->find($id);
                if ($data) {
                    # delete or remove photo
                    if (file_exists("uploads/clients/passports/" . $data['photo']) && $data['photo']) {
                        unlink("uploads/clients/passports/" . $data['photo']);
                    }
                    if (file_exists("uploads/clients/ids/front/" . $data['id_photo_front']) && $data['id_photo_front']) {
                        unlink("uploads/clients/ids/front/" . $data['id_photo_front']);
                    }
                    if (file_exists("uploads/clients/ids/back/" . $data['id_photo_back']) && $data['id_photo_back']) {
                        unlink("uploads/clients/ids/back/" . $data['id_photo_back']);
                    }
                    if (file_exists("uploads/clients/signatures/" . $data['signature']) && $data['signature']) {
                        unlink("uploads/clients/signatures/" . $data['signature']);
                    }
                    $delete = $this->client->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted client: ' . $data['name']),
                            'module' => $this->menu,
                            'referrer_id' => $id,
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
                    'messages' => $this->title . ' Deleted Successfully',
                ];
                return $this->respond($response);
                exit;
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' rDeleted Successfully. loggingFailed'
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * validate form inputs
     */
    private function _validateClient($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        # get the client information by id
        $clientInfo = $this->client->find($this->request->getVar('id'));
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

        # validate the staff registration form

        if ($this->request->getVar('c_name') == '') {
            $data['inputerror'][] = 'c_name';
            $data['error_string'][] = 'Client Name is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('c_name'))) {
            $name = $this->request->getVar('c_name');
            if ($this->settings->validateName($name) == TRUE) {
                if (strlen(trim($name)) < 5) {
                    $data['inputerror'][] = 'c_name';
                    $data['error_string'][] = 'Minimum 5 letters required [' . strlen(trim($name)) . ']!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($name) == FALSE) {
                $data['inputerror'][] = 'name';
                $data['error_string'][] = 'Valid Client Name is required!';
                $data['status'] = FALSE;
            }
        }
        if (!empty($this->request->getVar('c_email'))) {
            # check whether the email is valid
            if ($this->settings->validateEmail($this->request->getVar('c_email')) == FALSE) {
                $data['inputerror'][] = 'c_email';
                $data['error_string'][] = 'Valid Email is required!';
                $data['status'] = FALSE;
            }
            # get client information by email address
            $client = $this->client->where(['email' => $this->request->getVar('c_email')])->first();
            # check the method and the validate the client existence
            if ($method == 'add' && $client) {
                $data['inputerror'][] = 'c_email';
                $data['error_string'][] = $this->request->getVar('c_email') . ' already added!';
                $data['status'] = FALSE;
            }
            if ($method == 'update' && $clientInfo['email'] != $this->request->getVar('c_email')) {
                # check the client existence to validate the email exists
                if ($client) {
                    $data['inputerror'][] = 'c_email';
                    $data['error_string'][] = $this->request->getVar('c_email') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('mobile') == '') {
            $data['inputerror'][] = 'mobile';
            $data['error_string'][] = 'Phone number is required!';
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
                # check the client information by phone number
                $client = $this->client->where(['mobile' => $mobile])->first();
                # check the method and validate the client phone number exists
                if ($method == 'add' && $client) {
                    $data['inputerror'][] = 'mobile';
                    $data['error_string'][] = $mobile . ' already added!';
                    $data['status'] = FALSE;
                }
                if ($method == 'update' && $clientInfo['mobile'] != $mobile) {
                    if ($client) {
                        $data['inputerror'][] = 'mobile';
                        $data['error_string'][] = $mobile . ' already added!';
                        $data['status'] = FALSE;
                    }
                }
            }
        }
        if (!empty($this->request->getVar('alternate_no'))) {
            # validate the phone number
            $this->validPhoneNumber([
                'phone' => $alternate_mobile,
                'input' => 'alternate_no',
            ]);
            # validate below when the phone number is valid
            if ($this->settings->validatePhoneNumber($alternate_mobile) == TRUE) {
                # check the client information by alternate phone number
                $client = $this->client->where(['alternate_no' => $alternate_mobile])->first();
                # check the method and validate the client phone number exists
                if ($method == 'add' && $client) {
                    $data['inputerror'][] = 'alternate_no';
                    $data['error_string'][] = $alternate_mobile . ' already added!';
                    $data['status'] = FALSE;
                }
                if ($method == 'update' && $clientInfo['alternate_no'] != $alternate_mobile) {
                    if ($client) {
                        $data['inputerror'][] = 'alternate_no';
                        $data['error_string'][] = $alternate_mobile . ' already added!';
                        $data['status'] = FALSE;
                    }
                }
            }
        }
        if ($this->request->getVar('residence') == '') {
            $data['inputerror'][] = 'residence';
            $data['error_string'][] = 'Client address is required!';
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
        if ($this->request->getVar('closest_landmark') == '') {
            $data['inputerror'][] = 'closest_landmark';
            $data['error_string'][] = 'Closest Landmark is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('closest_landmark'))) {
            $address = $this->request->getVar('closest_landmark');
            if (strlen(trim($address)) < 4) {
                $data['inputerror'][] = 'closest_landmark';
                $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($address)) . ']!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('gender') == '') {
            $data['inputerror'][] = 'gender';
            $data['error_string'][] = 'Client gender is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('marital_status') == '') {
            $data['inputerror'][] = 'marital_status';
            $data['error_string'][] = 'Marital status is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('religion') == '') {
            $data['inputerror'][] = 'religion';
            $data['error_string'][] = 'Religion is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('nationality') == '') {
            $data['inputerror'][] = 'nationality';
            $data['error_string'][] = 'Nationality is required!';
            $data['status'] = FALSE;
        }
        # validate the registration date
        if ($this->request->getVar('reg_date') == '') {
            $data['inputerror'][] = 'reg_date';
            $data['error_string'][] = 'Registration Date is required!';
            $data['status'] = FALSE;
        }
        /* if ($this->request->getVar('dob') == '') {
            $data['inputerror'][] = 'dob';
            $data['error_string'][] = 'Date of birth is required!';
            $data['status'] = FALSE;
        } */
        if ($this->request->getVar('occupation') == '') {
            $data['inputerror'][] = 'occupation';
            $data['error_string'][] = 'Client Occupation is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('occupation'))) {
            $occupation = $this->request->getVar('occupation');
            if ($this->settings->validateName($occupation) == TRUE) {
                if (strlen(trim($occupation)) < 4) {
                    $data['inputerror'][] = 'occupation';
                    $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($occupation)) . ']!';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('job_location') == '') {
            $data['inputerror'][] = 'job_location';
            $data['error_string'][] = 'Business location is required!';
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
        /* if ($this->request->getVar('id_type') == '') {
            $data['inputerror'][] = 'id_type';
            $data['error_string'][] = 'ID type is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('id_number') == '') {
            $data['inputerror'][] = 'id_number';
            $data['error_string'][] = 'ID number is required!';
            $data['status'] = FALSE;
        } */

        # check whether the ID type and number is not null
        if (!empty($this->request->getVar('id_type')) && !empty($this->request->getVar('id_number'))) {
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
            if ($method == 'add' && $client) {
                $data['inputerror'][] = 'id_number';
                $data['error_string'][] = $this->request->getVar('id_number') . ' already added!';
                $data['status'] = FALSE;
            }
            # validate the id number to check the id number existence when updating client info
            if ($method == 'update' && $clientInfo['id_number'] != $this->request->getVar('id_number')) {
                if ($client) {
                    $data['inputerror'][] = 'id_number';
                    $data['error_string'][] = $this->request->getVar('id_number') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        /* if ($this->request->getVar('id_expiry') == '') {
            $data['inputerror'][] = 'id_expiry';
            $data['error_string'][] = 'ID expiry date is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('next_of_kin') == '') {
            $data['inputerror'][] = 'next_of_kin';
            $data['error_string'][] = 'Next of Kin is required!';
            $data['status'] = FALSE;
        } */
        if (!empty($this->request->getVar('next_of_kin'))) {
            $nok = $this->request->getVar('next_of_kin');
            if ($this->settings->validateName($nok) == TRUE) {
                if (strlen(trim($nok)) < 5) {
                    $data['inputerror'][] = 'next_of_kin';
                    $data['error_string'][] = 'Minimum 5 letters required [' . strlen(trim($nok)) . ']!';
                    $data['status'] = FALSE;
                }
            }
            # get client information
            $client = $this->client->where([
                'next_of_kin_name' => $this->request->getVar('next_of_kin')
            ])->first();
            if ($method == 'add' && $client) {
                $data['inputerror'][] = 'next_of_kin';
                $data['error_string'][] = $this->request->getVar('next_of_kin') . ' already added!';
                $data['status'] = FALSE;
            }
            if (
                $method == 'update' &&
                $clientInfo['next_of_kin_name'] != $this->request->getVar('next_of_kin')
            ) {

                if ($client) {
                    $data['inputerror'][] = 'next_of_kin';
                    $data['error_string'][] = $this->request->getVar('next_of_kin') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        /* if ($this->request->getVar('nok_relationship') == '') {
            $data['inputerror'][] = 'nok_relationship';
            $data['error_string'][] = 'Relationship is required!';
            $data['status'] = FALSE;
        } */
        /* if ($this->request->getVar('nok_address') == '') {
            $data['inputerror'][] = 'nok_address';
            $data['error_string'][] = 'Client address is required!';
            $data['status'] = FALSE;
        } */
        if (!empty($this->request->getVar('nok_address'))) {
            $add = $this->request->getVar('nok_address');
            if (strlen(trim($add)) < 4) {
                $data['inputerror'][] = 'nok_address';
                $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($add)) . ']!';
                $data['status'] = FALSE;
            }
        }
        /* if ($this->request->getVar('nok_phone') == '') {
            $data['inputerror'][] = 'nok_phone';
            $data['error_string'][] = 'Next of Kin mobile is required!';
            $data['status'] = FALSE;
        } */
        if (!empty($this->request->getVar('nok_phone'))) {
            # validate the next of kin phone number
            $this->validPhoneNumber([
                'phone' => $next_of_kin_contact,
                'input' => 'nok_phone',
            ]);
            # validate below when the next of kin phone number is valid
            if ($this->settings->validatePhoneNumber($next_of_kin_contact) == TRUE) {
                # check the client information by next of kin phone number
                $client = $this->client->where(['next_of_kin_contact' => $next_of_kin_contact])->first();
                # check the method and validate the client next of kin phone number exists
                if ($method == 'add' && $client) {
                    $data['inputerror'][] = 'nok_phone';
                    $data['error_string'][] = $next_of_kin_contact . ' already added!';
                    $data['status'] = FALSE;
                }
                if ($method == 'update' && $clientInfo['next_of_kin_contact'] != $next_of_kin_contact) {
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
                if ($method == 'add' && $client) {
                    $data['inputerror'][] = 'nok_alt_phone';
                    $data['error_string'][] = $next_of_kin_alternate_contact . ' already added!';
                    $data['status'] = FALSE;
                }
                if (
                    $method == 'update' && $clientInfo['next_of_kin_alternate_contact'] != $next_of_kin_alternate_contact
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
            # get the client information by the next of kin email address
            $client = $this->client->where([
                'nok_email' => $this->request->getVar('nok_email')
            ])->first();
            if ($method == 'add' && $client) {
                $data['inputerror'][] = 'nok_email';
                $data['error_string'][] = $this->request->getVar('nok_email') . ' already added!';
                $data['status'] = FALSE;
            }
            if ($method == 'update' && $clientInfo['nok_email'] != $this->request->getVar('nok_email')) {
                if ($client) {
                    $data['inputerror'][] = 'nok_email';
                    $data['error_string'][] = $this->request->getVar('nok_email') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('branch_id') == '') {
            $data['inputerror'][] = 'branch_id';
            $data['error_string'][] = 'Branch is required!';
            $data['status'] = FALSE;
        }
        /*if ($this->request->getVar('savings_products') == '') {
            $data['inputerror'][] = 'savings_products';
            $data['error_string'][] = 'Savings Product is required!';
            $data['status'] = FALSE;
        }*/
        if ($this->request->getVar('staff_id') == '') {
            $data['inputerror'][] = 'staff_id';
            $data['error_string'][] = 'Responsible Officer is required!';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
