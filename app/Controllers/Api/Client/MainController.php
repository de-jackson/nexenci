<?php

namespace App\Controllers\Api\Client;

use App\Controllers\Api\BaseController as BaseController;

class MainController extends BaseController
{

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->settingsRow) {
            # load user data if session is set
            $client_id = session()->get('client_id');
            $clientLoggedIn = session()->get('client');
            if (isset($client_id) && isset($clientLoggedIn)) {
                # $this->userRow = $this->client->select('clients.*, branches.branch_name')->join('branches', 'branches.id = clients.branch_id')->find($client_id);

                $this->userRow = $this->getClientByID($client_id);

                # Manual Assign Positions
                $permissions = serialize([
                    "viewDashboard", "viewBranches", "exportBranches", "viewReports",
                    "viewTransactions", "viewLoans", "viewDisbursements", "exportDisbursements",
                    "createApplications", "exportApplications", "viewApplications", "updateApplications",
                    "createClients"

                ]);
                # Add custom permissions to the client user information
                $this->userRow["permissions"] = $permissions;

                $this->userPermissions = unserialize($permissions);

                # end session for the first user
                $this->checkClientMultipleLogin();

                # Check 
                if (!isset($userPermissions)) {
                    # redirect the user to login screen again
                    session()->setFlashdata('failed', "Client Session Has Expired. Kindly Login Again!");
                    return redirect()->to('/api/client/auth?session=expired');
                }
            } else {
                # redirect the user to login screen again
                return redirect()->to('/api/client/auth?session=expired');
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
        if (session()->get('client') && session()->get('client_id')) {
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
                    $_SESSION['client'],
                    $_SESSION['name'],
                    $_SESSION['client_id'],
                    $_SESSION['userlog_id'],
                );
                // $session = session();
                // $session->destroy();
                return redirect()->to('');
            }
        } else {
            # redirect the user to login screen again
            return redirect()->to('/api/client/login');
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
                'client' => true,
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

    protected function clientValidation($menu, $action = null)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if (strtolower($menu) == "login") {
            # Validate Login Credentials
            if (empty($this->request->getVar('password'))) {
                $data['inputerror'][] = 'password';
                $data['error_string'][] = 'Password is required';
                $data['status'] = FALSE;
            }

            if ($this->request->getVar('email') == '') {
                $data['inputerror'][] = 'email';
                $data['error_string'][] = 'Phone Number is required';
                $data['status'] = FALSE;
            }

            if (!empty($this->request->getVar('email'))) {

                $mobile = trim($this->request->getVar('email'));

                # check whether the first phone number is with +256
                if (substr($mobile, 0, 4) == '+256') {
                    if (
                        strlen(trim($mobile)) > 13 ||
                        strlen(trim($mobile)) < 13
                    ) {
                        $data['inputerror'][] = 'email';
                        $data['error_string'][] = 'Valid Phone Number should have 13 digits!';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with 0
                else if (substr($mobile, 0, 1) == '0') {
                    if (
                        strlen(trim($mobile)) > 10 ||
                        strlen(trim($mobile)) < 10
                    ) {
                        $data['inputerror'][] = 'email';
                        $data['error_string'][] = 'Valid Phone Number should have 10 digits!';
                        $data['status'] = FALSE;
                    }
                } else if (substr($mobile, 0, 1) == '+') {
                    if (
                        strlen(trim($mobile)) > 13 ||
                        strlen(trim($mobile)) < 13
                    ) {
                        $data['inputerror'][] = 'email';
                        $data['error_string'][] = 'Should have 13 digits with Country Code!';
                        $data['status'] = FALSE;
                    }
                } else {
                    $data['inputerror'][] = 'email';
                    $data['error_string'][] = 'Valid Phone Number is required!';
                    $data['status'] = FALSE;
                }
                # check whether the phone number is valid
                if ($this->settings->validatePhoneNumber($mobile) == FALSE) {
                    $data['inputerror'][] = 'email';
                    $data['error_string'][] = 'Valid Phone Number is required!';
                    $data['status'] = FALSE;
                }

                # check whether the phone number is valid
                if ($this->settings->validatePhoneNumber($mobile) == TRUE) {
                    # count the client information by the phone number
                    $client = $this->client
                        ->where(['mobile' => trim(preg_replace('/^0/', '+256', $mobile))])
                        ->first();
                    # check the client account existance
                    if ($client) {
                        # check client login access
                        if (strtolower($client["access_status"]) == "inactive") {
                            $data['inputerror'][] = 'email';
                            $data['error_string'][] = 'You are not Authorised to Access!';
                            $data['status'] = FALSE;
                        }
                    } else {
                        $data['inputerror'][] = 'email';
                        $data['error_string'][] = 'We could not find your account!';
                        $data['status'] = FALSE;
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
                # check whether the email is valid
                if ($this->settings->validatePhoneNumber($this->request->getVar('phone')) == FALSE) {
                    $data['inputerror'][] = 'phone';
                    $data['error_string'][] = 'Valid phone number is required';
                    $data['status'] = FALSE;
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
                    $data['error_string'][] = 'Valid Client Name is required!';
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
                    # check the client email existance
                    if ($client) {
                        $data['inputerror'][] = 'c_email';
                        $data['error_string'][] = $this->request->getVar('c_email') . ' already added!';
                        $data['status'] = FALSE;
                    }
                }

                if ($action == 'update' && $clientInfo['email'] != $email) {
                    # check the client email existance
                    if ($client) {
                        $data['inputerror'][] = 'c_email';
                        $data['error_string'][] = $this->request->getVar('c_email') . ' already added!';
                        $data['status'] = FALSE;
                    }
                }
            }

            if ($this->request->getVar('c_mobile') == '') {
                $data['inputerror'][] = 'c_mobile';
                $data['error_string'][] = 'Phone Number is required!';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('c_mobile'))) {
                $mobile = $this->request->getVar('c_mobile');
                # count the number of clients by the phone number
                $client = $this->client
                    ->where(['mobile' => trim(preg_replace('/^0/', '+256', $mobile))])
                    ->countAllResults();

                if ($action == 'add') {
                    if ($client) {
                        $data['inputerror'][] = 'c_mobile';
                        $data['error_string'][] = $this->request->getVar('c_mobile') . ' already added!';
                        $data['status'] = FALSE;
                    }
                }
                if ($action == 'update' && $clientInfo['mobile'] != $mobile) {
                    if ($client) {
                        $data['inputerror'][] = 'c_mobile';
                        $data['error_string'][] = $mobile . ' already added!';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with +256
                if (substr($mobile, 0, 4) == '+256') {
                    if (
                        strlen(trim($mobile)) > 13 ||
                        strlen(trim($mobile)) < 13
                    ) {
                        $data['inputerror'][] = 'c_mobile';
                        $data['error_string'][] = 'Valid Phone Number should have 13 digits!';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with 0
                else if (substr($mobile, 0, 1) == '0') {
                    if (
                        strlen(trim($mobile)) > 10 ||
                        strlen(trim($mobile)) < 10
                    ) {
                        $data['inputerror'][] = 'c_mobile';
                        $data['error_string'][] = 'Valid Phone Number should have 10 digits!';
                        $data['status'] = FALSE;
                    }
                } else if (substr($mobile, 0, 1) == '+') {
                    if (
                        strlen(trim($mobile)) > 13 ||
                        strlen(trim($mobile)) < 13
                    ) {
                        $data['inputerror'][] = 'c_mobile';
                        $data['error_string'][] = 'Should have 13 digits with Country Code!';
                        $data['status'] = FALSE;
                    }
                } else {
                    $data['inputerror'][] = 'c_mobile';
                    $data['error_string'][] = 'Valid Phone Number is required!';
                    $data['status'] = FALSE;
                }
                # check whether the phone number is valid
                if ($this->settings->validatePhoneNumber($mobile) == FALSE) {
                    $data['inputerror'][] = 'c_mobile';
                    $data['error_string'][] = 'Valid Phone Number is required!';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('alternate_no'))) {
                # get the client alternate phone number from the form
                $alternate_mobile = $this->request->getVar('alternate_no');
                # get the client by the alternate phone number
                $client = $this->client
                    ->where(
                        [
                            'alternate_no' => trim(preg_replace('/^0/', '+256', $alternate_mobile))
                        ]
                    )
                    ->countAllResults();
                # check the action to be taken on the client registration form
                if ($action == 'add') {
                    # check the client existance by the alternate phone number
                    if ($client) {
                        $data['inputerror'][] = 'alternate_no';
                        $data['error_string'][] = $alternate_mobile . ' already added!';
                        $data['status'] = FALSE;
                    }
                }
                # check the action to be taken on the client registration form
                if ($action == 'update' && $clientInfo['alternate_no'] != $alternate_mobile) {
                    # check the client existance by the alternate phone number
                    if ($client) {
                        $data['inputerror'][] = 'alternate_no';
                        $data['error_string'][] = $alternate_mobile . ' already added!';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with +256
                if (substr($alternate_mobile, 0, 4) == '+256') {
                    if (
                        strlen(trim($alternate_mobile)) > 13 ||
                        strlen(trim($alternate_mobile)) < 13
                    ) {
                        $data['inputerror'][] = 'alternate_no';
                        $data['error_string'][] = 'Valid Phone Number should have 13 digits!';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with 0
                else if (substr($alternate_mobile, 0, 1) == '0') {
                    if (
                        strlen(trim($alternate_mobile)) > 10 ||
                        strlen(trim($alternate_mobile)) < 10
                    ) {
                        $data['inputerror'][] = 'alternate_no';
                        $data['error_string'][] = 'Valid Phone Number should have 10 digits!';
                        $data['status'] = FALSE;
                    }
                } else if (substr($alternate_mobile, 0, 1) == '+') {
                    if (
                        strlen(trim($alternate_mobile)) > 13 ||
                        strlen(trim($alternate_mobile)) < 13
                    ) {
                        $data['inputerror'][] = 'alternate_no';
                        $data['error_string'][] = 'Should have 13 digits with Country Code!';
                        $data['status'] = FALSE;
                    }
                } else {
                    $data['inputerror'][] = 'alternate_no';
                    $data['error_string'][] = 'Valid Phone Number is required!';
                    $data['status'] = FALSE;
                }
                # check whether the phone number is valid
                if ($this->settings->validatePhoneNumber($alternate_mobile) == FALSE) {
                    $data['inputerror'][] = 'alternate_no';
                    $data['error_string'][] = 'Valid Phone Number is required!';
                    $data['status'] = FALSE;
                }
            }

            if (empty($this->request->getVar('residence'))) {
                $data['inputerror'][] = 'residence';
                $data['error_string'][] = 'Adress is required!';
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
                # Validate the client occuption
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

            if (!empty($this->request->getVar('id_number'))) {
                $id_number = trim($this->request->getVar('id_number'));
                if ((strlen(trim($id_number)) != 14)) {
                    $data['inputerror'][] = 'id_number';
                    $data['error_string'][] = 'ID Number should be 14 characters [' . strlen($id_number) . ']!';
                    $data['status'] = FALSE;
                }
                if ($action == 'add') {
                    # get  client by ID number
                    $clientIDNumber = $this->client
                        ->where(['id_number' => $id_number])
                        ->countAllResults();
                    # check the client existance by ID Number
                    if ($client) {
                        $data['inputerror'][] = 'id_number';
                        $data['error_string'][] = $id_number . ' already added!';
                        $data['status'] = FALSE;
                    }
                }
                if ($action == 'update' && !empty($clientInfo['id_number']) && $clientInfo['id_number'] != $id_number) {
                    # check the client existance by ID Number
                    if ($clientIDNumber) {
                        $data['inputerror'][] = 'id_number';
                        $data['error_string'][] = $id_number . ' already added!';
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

            if ($this->request->getVar('nok_phone') == '') {
                $data['inputerror'][] = 'nok_phone';
                $data['error_string'][] = 'Next of Kin mobile is required!';
                $data['status'] = FALSE;
            }

            if (!empty($this->request->getVar('nok_phone'))) {
                $nok_mobile = trim($this->request->getVar('nok_phone'));
                # check whether the first phone number is with +256
                if (substr($nok_mobile, 0, 4) == '+256') {
                    if (
                        strlen(trim($nok_mobile)) > 13 ||
                        strlen(trim($nok_mobile)) < 13
                    ) {
                        $data['inputerror'][] = 'nok_phone';
                        $data['error_string'][] = 'Valid Phone Number should have 13 digits!';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with 0
                else if (substr($nok_mobile, 0, 1) == '0') {
                    if (
                        strlen(trim($nok_mobile)) > 10 ||
                        strlen(trim($nok_mobile)) < 10
                    ) {
                        $data['inputerror'][] = 'nok_phone';
                        $data['error_string'][] = 'Valid Phone Number should have 10 digits!';
                        $data['status'] = FALSE;
                    }
                } else if (substr($nok_mobile, 0, 1) == '+') {
                    if (
                        strlen(trim($nok_mobile)) > 13 ||
                        strlen(trim($nok_mobile)) < 13
                    ) {
                        $data['inputerror'][] = 'nok_phone';
                        $data['error_string'][] = 'Should have 13 digits with Country Code!';
                        $data['status'] = FALSE;
                    }
                } else {
                    $data['inputerror'][] = 'nok_phone';
                    $data['error_string'][] = 'Valid Phone Number is required!';
                    $data['status'] = FALSE;
                }
                # check whether the phone number is valid
                if ($this->settings->validatePhoneNumber($nok_mobile) == FALSE) {
                    $data['inputerror'][] = 'nok_phone';
                    $data['error_string'][] = 'Valid Phone Number is required!';
                    $data['status'] = FALSE;
                }
            }
            if (!empty($this->request->getVar('nok_alt_phone'))) {
                $nok_alt_phone = trim($this->request->getVar('nok_alt_phone'));
                # check whether the first phone number is with +256
                if (substr($nok_alt_phone, 0, 4) == '+256') {
                    if (
                        strlen(trim($nok_alt_phone)) > 13 ||
                        strlen(trim($nok_alt_phone)) < 13
                    ) {
                        $data['inputerror'][] = 'nok_alt_phone';
                        $data['error_string'][] = 'Valid Phone Number should have 13 digits!';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with 0
                else if (substr($nok_alt_phone, 0, 1) == '0') {
                    if (
                        strlen(trim($nok_alt_phone)) > 10 ||
                        strlen(trim($nok_alt_phone)) < 10
                    ) {
                        $data['inputerror'][] = 'nok_alt_phone';
                        $data['error_string'][] = 'Valid Phone Number should have 10 digits!';
                        $data['status'] = FALSE;
                    }
                } else if (substr($nok_alt_phone, 0, 1) == '+') {
                    if (
                        strlen(trim($nok_alt_phone)) > 13 ||
                        strlen(trim($nok_alt_phone)) < 13
                    ) {
                        $data['inputerror'][] = 'nok_alt_phone';
                        $data['error_string'][] = 'Should have 13 digits with Country Code!';
                        $data['status'] = FALSE;
                    }
                } else {
                    $data['inputerror'][] = 'nok_alt_phone';
                    $data['error_string'][] = 'Valid Phone Number is required!';
                    $data['status'] = FALSE;
                }
                # check whether the phone number is valid
                if ($this->settings->validatePhoneNumber($nok_alt_phone) == FALSE) {
                    $data['inputerror'][] = 'nok_alt_phone';
                    $data['error_string'][] = 'Valid Phone Number is required!';
                    $data['status'] = FALSE;
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
                        'client_id' => trim($this->request->getVar('client_id')), 'status' => 'Processing'
                    ])->orWhere([
                        'client_id' => trim($this->request->getVar('client_id')), 'status' => 'Pending'
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
}
