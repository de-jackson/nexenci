<?php

namespace App\Controllers\Admin;

use App\Controllers\MasterController;

class Register extends MasterController
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Dashboard';
        $this->title = 'Dashboard';
        # end session for the first user
    }

    public function index()
    {
        return view('admin/auth/admin/register', [
            'title' => 'Company Registration',
            'settings' => $this->settingsRow,
        ]);
    }

    public function create()
    {

        $inputs = $this->validate([
            'name' => 'required|min_length[5]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[5]',
            'password_confirm' => 'required|matches[password]',
        ]);

        if (!$inputs) {
            return view('admin/auth/register', [
                'title' => 'Admin Registration',
                'validation' => $this->validator,
                'settings' => $this->settingsRow,
            ]);
        }

        $this->user->save([
            'name' => $this->request->getVar('name'),
            'email'  => $this->request->getVar('email'),
            'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
        ]);
        session()->setFlashdata('success', 'Success! registration completed.');
        return redirect()->to(base_url('admin/register'));
    }

    public function register()
    {
        $this->_validateRegistration();

        $account_id = trim($this->request->getVar('account_id'));
        $position_id = 2;
        $accountCode = $this->settings->generateRandomNumbers(6, 'digits');
        $staffID = $this->settings->generateUniqueNo('administrator');
        $password =  $this->settings->generateRandomNumbers(8);

        $accountRow = $this->account->find($account_id);
        $positionRow = $this->position->select('positions.*, departments.department_name')->join('departments', 'departments.id = positions.department_id')->find($position_id);
        $sytemData = [
            'system_name' => 'Nexen',
            'system_abbr' => 'NX',
            'system_slogan' => 'Simpler. Faster. Friendlier.',
            'system_version' => '1.0.0',
        ];

        $dataArrays = [
            // system data
            'sytemData' => $sytemData,
            // new account data
            'accountData' => [
                'name' => trim($this->request->getVar('business_name')),
                'slug' => trim(url_title(strtolower($this->request->getVar('business_name')), '-', true)),
                'code' => $accountCode,
                'intro' => trim($this->request->getVar('intro')),
                'description' => trim($this->request->getVar('description')),
                'account_id' => $account_id,
                'status' => '1',
                'locked' => '0'
            ],
            // account settings data
            'settingsData' => [
                'system_name' => $sytemData['system_name'],
                'system_abbr' => $sytemData['system_abbr'],
                'system_slogan' => $sytemData['system_slogan'],
                'system_version' => $sytemData['system_version'],
                'business_name' => trim($this->request->getVar('business_name')),
                'business_abbr' => trim($this->request->getVar('business_abbr')),
                'business_slogan' => trim($this->request->getVar('business_slogan')),
                'business_contact' => trim(preg_replace('/^0/', '+256', $this->request->getVar('business_contact'))),
                'business_alt_contact' => trim(preg_replace('/^0/', '+256', $this->request->getVar('business_alt_contact'))),
                'business_email' => trim($this->request->getVar('business_email')),
                'business_address' => trim($this->request->getVar('business_address')),
                'tax_rate' => trim($this->request->getVar('tax_rate')),
                'round_off' => trim($this->request->getVar('round_off')),
                'currency_id' => trim($this->request->getVar('currency_id')),
            ],
            // account branch data
            'branchData' => [
                'branch_name' => "Main Branch",
                'slug' => "main-branch",
                'branch_mobile' => trim(preg_replace('/^0/', '+256', $this->request->getVar('business_contact'))),
                'alternate_mobile' => trim(preg_replace('/^0/', '+256', $this->request->getVar('business_alt_contact'))),
                'branch_address' => trim($this->request->getVar('business_address')),
                'branch_email' => trim($this->request->getVar('business_email')),
                // 'branch_status' => trim($this->request->getVar('branch_status')),
                'branch_code' => "B" . $this->settings->generateReference(4),
            ],
            // account admin staff data
            'staffData' => [
                'staffID' => $staffID,
                'reg_date' => ((!empty($this->request->getVar('reg_date'))) ? trim($this->request->getVar('reg_date')) : date('Y-m-d')),
                'staff_name' => trim($this->request->getVar('name')),
                'gender' => trim($this->request->getVar('gender')),
                'mobile' => trim(preg_replace('/^0/', '+256', $this->request->getVar('phone'))),
                'email' => trim($this->request->getVar('email')),
                'address' => trim($this->request->getVar('address')),
                'account_type' => "Administrator",
                'position_id' => $positionRow['id'],
            ],
            // account admin user data
            'userData' => [
                'name' => trim($this->request->getVar('name')),
                'email' => trim($this->request->getVar('email')),
                'account_type' => "Administrator",
                'mobile' => trim(preg_replace('/^0/', '+256', $this->request->getVar('phone'))),
                'permissions' => $positionRow['permissions'],
                'address' => trim($this->request->getVar('address')),
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'access_status' => 'active',
            ],
        ];

        // create new company account
        $account = $this->account->insert($dataArrays['accountData']);

        if (!$account) {
            $response = [
                'status' => 500,
                'error' => 'Signup Failed',
                'messages' => 'New Account registration failed, try again later!',
            ];
            return $this->respond($response);
            exit;
        }
        // create new company settings
        if (!empty($_FILES['logo']['name'])) {
            $logo = $this->upload_companyLogo();
            $dataArrays['settingsData']['business_logo'] = $logo;
        }
        $dataArrays['settingsData']['account_id'] = $account;

        $settings = $this->settings->insert($dataArrays['settingsData']);
        if (!$settings) {
            $response = [
                'status' => 500,
                'error' => 'Settings Failed',
                'messages' => 'Account Registered. Setting up Company Settings failed!',
            ];
            return $this->respond($response);
            exit;
        }

        // create a new main branch
        $dataArrays['branchData']['account_id'] = $account;

        $branch  = $this->branch->insert($dataArrays['branchData']);
        if (!$branch) {
            $response = [
                'status' => 500,
                'error' => 'Branch Creation Failed',
                'messages' => 'Account Registered. Setting up Company Main branch failed!',
            ];
            return $this->respond($response);
            exit;
        }

        // create new company admin account[staffs]
        $dataArrays['staffData']['account_id'] = $account;
        $dataArrays['staffData']['branch_id'] = $branch;
        $staff = $this->staff->insert($dataArrays['staffData']);
        if (!$staff) {
            $response = [
                'status' => 500,
                'error' => 'Admin Registration Failed',
                'messages' => 'Account Registered. Setting up Admin Staff Account failed!',
            ];
            return $this->respond($response);
            exit;
        }

        // create new company admin account[users]
        $dataArrays['userData']['account_id'] = $account;
        $dataArrays['userData']['branch_id'] = $branch;
        $dataArrays['userData']['staff_id'] = $staff;
        $user = $this->user->insert($dataArrays['userData']);
        if (!$user) {
            $response = [
                'status' => 500,
                'error' => 'Admin Registration Failed',
                'messages' => 'Account Registered. Setting up Admin User Account failed!',
            ];
            return $this->respond($response);
            exit;
        }

        $finalDataArray = [];

        // Loop through each element in $dataArrays
        foreach ($dataArrays as $dataArray) {
            // Merge the current array with the existing $finalDataArray
            $finalDataArray = array_merge($finalDataArray, $dataArray);
        }
        $finalDataArray['account'] = $accountRow['name'];
        $finalDataArray['department_name'] = $positionRow['department_name'];
        $finalDataArray['position'] = $positionRow['position'];

        $checkInternet = $this->settings->checkNetworkConnection();
        if ($checkInternet) {
            # send the email
            if (!empty($this->request->getVar('email'))) {
                $subject = "Account Registration";
                $message = $finalDataArray;
                $token = 'signup';
                $this->settings->sendMail($message, $subject, $token, $password, 'staff');
            }

            # check the phone number existance
            // if (!empty($finalDataArray['mobile'])) {
            //     # send sms
            //     $sms = $this->sendSMS([
            //         'module' => 'account',
            //         'name' => $finalDataArray['staff_name'],
            //         'mobile' => $finalDataArray['mobile'],
            //         'password' => $password
            //     ]);
            // }

            $response = [
                'status' => 200,
                'error' => null,
                'url' => "/admin",
                'messages' => 'New Account signup was successful! Email Sent'
            ];
            return $this->respond($response);
            exit;
        } else {
            $response = [
                'status' => 200,
                'url' => "/admin",
                'error' => null,
                'messages' => 'New Account signup was successful!'
            ];
            return $this->respond($response);
            exit;
        }
    }

    public function _validateRegistration()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $step = $this->request->getVar('step_no');
        $data['step'] = $step;

        // respose if step validation was successful
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Step ' . $step . ' validation successful',
        ];

        # step-wise validation
        switch ($step) {
            case 1: // admin bio validation
                if ($this->request->getVar('name') == '') {
                    $data['inputerror'][] = 'name';
                    $data['error_string'][] = 'Full Name is required!';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('name'))) {
                    $name = $this->request->getVar('name');
                    if ($this->settings->validateName($name) == TRUE) {
                        if (strlen(trim($name)) < 5) {
                            $data['inputerror'][] = 'name';
                            $data['error_string'][] = 'Minimum 5 letters required [' . strlen($name) . ']';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('name')) == FALSE) {
                        $data['inputerror'][] = 'name';
                        $data['error_string'][] = 'Valid Full Name is required';
                        $data['status'] = FALSE;
                    }
                }

                if ($this->request->getVar('gender') == '') {
                    $data['inputerror'][] = 'gender';
                    $data['error_string'][] = 'Gender is required!';
                    $data['status'] = FALSE;
                }

                if ($this->request->getVar('phone') == '') {
                    $data['inputerror'][] = 'phone';
                    $data['error_string'][] = 'Phone Number is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('phone'))) {
                    # check whether the first phone number is with +256
                    if (substr($this->request->getVar('phone'), 0, 4) == '+256') {
                        if (
                            strlen($this->request->getVar('phone')) > 13 ||
                            strlen($this->request->getVar('phone')) < 13
                        ) {
                            $data['inputerror'][] = 'phone';
                            $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                            $data['status'] = FALSE;
                        }
                    }
                    # check whether the first phone number is with 0
                    else if (substr($this->request->getVar('phone'), 0, 1) == '0') {
                        if (
                            strlen($this->request->getVar('phone')) > 10 ||
                            strlen($this->request->getVar('phone')) < 10
                        ) {
                            $data['inputerror'][] = 'phone';
                            $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                            $data['status'] = FALSE;
                        }
                    } else if (substr($this->request->getVar('phone'), 0, 1) == '+') {
                        if (
                            strlen($this->request->getVar('phone')) > 13 ||
                            strlen($this->request->getVar('phone')) < 13
                        ) {
                            $data['inputerror'][] = 'phone';
                            $data['error_string'][] = 'Should have 13 digits with Country Code';
                            $data['status'] = FALSE;
                        }
                    } else {
                        $data['inputerror'][] = 'phone';
                        $data['error_string'][] = 'Valid Phone Number is required';
                        $data['status'] = FALSE;
                    }
                    # check whether the phone number is valid
                    if ($this->settings->validatePhoneNumber($this->request->getVar('phone')) == FALSE) {
                        $data['inputerror'][] = 'phone';
                        $data['error_string'][] = 'Valid Phone Number is required';
                        $data['status'] = FALSE;
                    }

                    # check phone number existance
                    $adminRow = $this->user->where(['mobile' => $this->request->getVar('phone')])->first();
                    if ($adminRow) {
                        $data['inputerror'][] = 'phone';
                        $data['error_string'][] = $this->request->getVar('phone') . ' already added';
                        $data['status'] = FALSE;
                    }
                }

                if ($this->request->getVar('email') == '') {
                    $data['inputerror'][] = 'email';
                    $data['error_string'][] = 'Email address is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('email'))) {
                    # check whether the email is valid
                    if ($this->settings->validateEmail($this->request->getVar('email')) == FALSE) {
                        $data['inputerror'][] = 'email';
                        $data['error_string'][] = 'Valid Email Address is required';
                        $data['status'] = FALSE;
                    }
                    # check for email address existance
                    $adminRow = $this->user
                        ->where(['email' => $this->request->getVar('email')])->first();
                    if ($adminRow) {
                        $data['inputerror'][] = 'email';
                        $data['error_string'][] = $this->request->getVar('email') . ' already added';
                        $data['status'] = FALSE;
                    }
                }

                if ($this->request->getVar('address') == '') {
                    $data['inputerror'][] = 'address';
                    $data['error_string'][] = 'Address is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('address'))) {
                    if ($this->settings->validateAddress($this->request->getVar('address')) == TRUE) {
                        if (strlen(trim($this->request->getVar('address'))) < 4) {
                            $data['inputerror'][] = 'address';
                            $data['error_string'][] = 'Address is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateAddress($this->request->getVar('address')) == FALSE) {
                        $data['inputerror'][] = 'address';
                        $data['error_string'][] = 'Valid Address is required';
                        $data['status'] = FALSE;
                    }
                }

                // return response
                if ($data['status'] === FALSE) {
                    echo json_encode($data);
                    exit;
                } else {
                    echo json_encode($response);
                    exit;
                }
                break;
            case 2: // company bio
                if ($this->request->getVar('business_name') == '') {
                    $data['inputerror'][] = 'business_name';
                    $data['error_string'][] = 'Company Name is required!';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('business_name'))) {
                    $company = $this->request->getVar('business_name');
                    # check company name existance
                    $companyRow = $this->account->where(['name' => $this->request->getVar('business_name')])->first();
                    if ($companyRow) {
                        $data['inputerror'][] = 'business_name';
                        $data['error_string'][] = $this->request->getVar('business_name') . ' already added';
                        $data['status'] = FALSE;
                    }
                    if ($this->settings->validateAddress($company) == TRUE) {
                        if (strlen(trim($company)) < 5) {
                            $data['inputerror'][] = 'business_name';
                            $data['error_string'][] = 'Minimum 5 letters required [' . strlen($company) . ']';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('business_name')) == FALSE) {
                        $data['inputerror'][] = 'business_name';
                        $data['error_string'][] = 'Valid Company Name is required';
                        $data['status'] = FALSE;
                    }
                }

                if ($this->request->getVar('account_id') == '') {
                    $data['inputerror'][] = 'account_id';
                    $data['error_string'][] = 'Company Account is required!';
                    $data['status'] = FALSE;
                }

                if ($this->request->getVar('business_email') == '') {
                    $data['inputerror'][] = 'business_email';
                    $data['error_string'][] = 'Company Email is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('business_email'))) {
                    # check whether the email is valid
                    if ($this->settings->validateEmail($this->request->getVar('business_email')) == FALSE) {
                        $data['inputerror'][] = 'business_email';
                        $data['error_string'][] = 'Valid Email Address is required';
                        $data['status'] = FALSE;
                    }
                    # check for email address existance
                    $settingsRow = $this->settings
                        ->where(['business_email' => $this->request->getVar('business_email')])->first();
                    if ($settingsRow) {
                        $data['inputerror'][] = 'business_email';
                        $data['error_string'][] = $this->request->getVar('business_email') . ' already added';
                        $data['status'] = FALSE;
                    }
                }

                /** business contact validation */
                if ($this->request->getVar('business_contact') == '') {
                    $data['inputerror'][] = 'business_contact';
                    $data['error_string'][] = 'Business contanct is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('business_contact'))) {
                    # check whether the phone number is valid
                    if ($this->settings->validatePhoneNumber($this->request->getVar('business_contact')) == FALSE) {
                        $data['inputerror'][] = 'business_contact';
                        $data['error_string'][] = 'Valid Phone Number is required';
                        $data['status'] = FALSE;
                    }

                    # check phone number existance
                    $settingsRow = $this->settings->where(['business_contact' => $this->request->getVar('business_contact')])->first();
                    if ($settingsRow) {
                        $data['inputerror'][] = 'business_contact';
                        $data['error_string'][] = $this->request->getVar('business_contact') . ' already added';
                        $data['status'] = FALSE;
                    }
                }
                /** business alt contact validation */
                if (!empty($this->request->getVar('business_alt_contact'))) {
                    # check whether the first phone number is with +256
                    if (substr($this->request->getVar('business_alt_contact'), 0, 4) == '+256') {
                        if (
                            strlen($this->request->getVar('business_alt_contact')) > 13 ||
                            strlen($this->request->getVar('business_alt_contact')) < 13
                        ) {
                            $data['inputerror'][] = 'business_alt_contact';
                            $data['error_string'][] = 'Valid Phone Number 2 should have 13 digits';
                            $data['status'] = FALSE;
                        }
                    }
                    # check whether the first phone number is with 0
                    else if (substr($this->request->getVar('business_alt_contact'), 0, 1) == '0') {
                        if (
                            strlen($this->request->getVar('business_alt_contact')) > 10 ||
                            strlen($this->request->getVar('business_alt_contact')) < 10
                        ) {
                            $data['inputerror'][] = 'business_alt_contact';
                            $data['error_string'][] = 'Valid Phone Number 2 should have 10 digits';
                            $data['status'] = FALSE;
                        }
                    } else if (substr($this->request->getVar('business_alt_contact'), 0, 1) == '+') {
                        if (
                            strlen($this->request->getVar('business_alt_contact')) > 13 ||
                            strlen($this->request->getVar('business_alt_contact')) < 13
                        ) {
                            $data['inputerror'][] = 'business_alt_contact';
                            $data['error_string'][] = 'Should have 13 digits with Country Code';
                            $data['status'] = FALSE;
                        }
                    } else {
                        $data['inputerror'][] = 'business_alt_contact';
                        $data['error_string'][] = 'Valid Phone Number 2 is required';
                        $data['status'] = FALSE;
                    }
                    # check whether the phone number is valid
                    if ($this->settings->validatePhoneNumber($this->request->getVar('business_alt_contact')) == FALSE) {
                        $data['inputerror'][] = 'business_alt_contact';
                        $data['error_string'][] = 'Valid Phone Number 2 is required';
                        $data['status'] = FALSE;
                    }

                    # check phone number existance
                    $settingsRow = $this->settings->where(['business_alt_contact' => $this->request->getVar('business_alt_contact')])->first();
                    if ($settingsRow) {
                        $data['inputerror'][] = 'business_alt_contact';
                        $data['error_string'][] = $this->request->getVar('business_alt_contact') . ' already added';
                        $data['status'] = FALSE;
                    }
                }

                /** business address validation */
                if ($this->request->getVar('business_address') == '') {
                    $data['inputerror'][] = 'business_address';
                    $data['error_string'][] = 'Business address is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('business_address'))) {
                    if ($this->settings->validateAddress($this->request->getVar('business_address')) == TRUE) {
                        if (strlen(trim($this->request->getVar('business_address'))) < 4) {
                            $data['inputerror'][] = 'business_address';
                            $data['error_string'][] = 'Address is too short';
                            $data['status'] = FALSE;
                        }
                    }
                }

                // return response
                if ($data['status'] === FALSE) {
                    echo json_encode($data);
                    exit;
                } else {
                    echo json_encode($response);
                    exit;
                }
                break;
            case 3: // 
                if ($this->request->getVar('business_slogan') == '') {
                    $data['inputerror'][] = 'business_slogan';
                    $data['error_string'][] = 'Company Slogan is required!';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('business_slogan'))) {
                    $bslogan = $this->request->getVar('business_slogan');
                    if ($this->settings->validateName($bslogan) == TRUE) {
                        if (strlen(trim($bslogan)) < 5) {
                            $data['inputerror'][] = 'business_slogan';
                            $data['error_string'][] = 'Minimum length is 5 characters [' . strlen(trim($bslogan)) . ']!';
                            $data['status'] = FALSE;
                        }
                        if (strlen(trim($bslogan)) > 50) {
                            $data['inputerror'][] = 'business_slogan';
                            $data['error_string'][] = 'Maximum length is 50 characters [' . strlen(trim($bslogan)) . ']!';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($bslogan) == FALSE) {
                        $data['inputerror'][] = 'business_slogan';
                        $data['error_string'][] = 'Valid Company Slogan is required!';
                        $data['status'] = FALSE;
                    }
                }

                /** Company short name validation */
                // if ($this->request->getVar('business_abbr') == '') {
                //     $data['inputerror'][] = 'business_abbr';
                //     $data['error_string'][] = 'Company Short Name is required!';
                //     $data['status'] = FALSE;
                // }
                if (!empty($this->request->getVar('business_abbr'))) {
                    $babbr = $this->request->getVar('business_abbr');
                    if ($this->settings->validateName($babbr) == TRUE) {
                        if (strlen(trim($babbr)) < 2) {
                            $data['inputerror'][] = 'business_abbr';
                            $data['error_string'][] = 'Minimum length is 2 characters [' . strlen(trim($babbr)) . ']!';
                            $data['status'] = FALSE;
                        }
                        if (strlen(trim($babbr)) > 10) {
                            $data['inputerror'][] = 'business_abbr';
                            $data['error_string'][] = 'Maximum length is 10 characters [' . strlen(trim($babbr)) . ']!';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($babbr) == FALSE) {
                        $data['inputerror'][] = 'business_abbr';
                        $data['error_string'][] = 'Valid Business Short Name is required!';
                        $data['status'] = FALSE;
                    }
                }

                if ($this->request->getVar('currency_id') == '') {
                    $data['inputerror'][] = 'currency_id';
                    $data['error_string'][] = 'Currency is required!';
                    $data['status'] = FALSE;
                }

                if ($this->request->getVar('round_off') == '') {
                    $data['inputerror'][] = 'round_off';
                    $data['error_string'][] = 'Round Off[Fee] is required!';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('round_off'))) {
                    $round = $this->request->getVar('round_off');
                    if (!preg_match("/^[0-9.']*$/", $round)) {
                        $data['inputerror'][] = 'round_off';
                        $data['error_string'][] = 'Only digits and . allowed!';
                        $data['status'] = FALSE;
                    }
                    if ($round < 100) {
                        $data['inputerror'][] = 'round_off';
                        $data['error_string'][] = 'Maximum Round Off[Fee] exceeded!';
                        $data['status'] = FALSE;
                    }
                }

                if ($this->request->getVar('tax_rate') == '') {
                    $data['inputerror'][] = 'tax_rate';
                    $data['error_string'][] = 'Tax is required!';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('tax_rate'))) {
                    $rate = $this->request->getVar('tax_rate');
                    if (!preg_match("/^[0-9.']*$/", $rate)) {
                        $data['inputerror'][] = 'tax_rate';
                        $data['error_string'][] = 'Only digits and . allowed!';
                        $data['status'] = FALSE;
                    }
                    if ($rate < 0) {
                        $data['inputerror'][] = 'tax_rate';
                        $data['error_string'][] = 'Minimum percentage exceeded!';
                        $data['status'] = FALSE;
                    }
                    if ($rate > 100) {
                        $data['inputerror'][] = 'tax_rate';
                        $data['error_string'][] = 'Maximum percentage exceeded!';
                        $data['status'] = FALSE;
                    }
                }

                // return response
                if ($data['status'] === FALSE) {
                    echo json_encode($data);
                    exit;
                }
                break;
        }
    }

    private function upload_companyLogo()
    {
        $validationRule = [
            'logo' => [
                "rules" => "uploaded[logo]|max_size[logo,1024]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]",
                "label" => "Profile Image",
                "errors" => [
                    'max_size' => 'The size of this image is too large. The image must have less than 1MB size',
                    'mime_in' => 'Your upload does not have a valid image format',
                    'is_image' => 'Your file is not allowed! Please use an image!'
                ]
            ],
        ];
        if (!$this->validate($validationRule)) {
            $data['inputerror'][] = 'logo';
            $data['error_string'][] = $this->validator->getError("logo") . '!';
            $data['status'] = FALSE;
            echo json_encode($data);
            exit;
        }
        $file = $this->request->getFile('logo');
        $logo = $file->getName();
        # Renaming file before upload
        $temp = explode(".", $logo);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        if ($file->move("uploads/logo/", $logo)) {
            return $logo;
        } else {
            $data['inputerror'][] = 'logo';
            $data['error_string'][] = "Failed to upload Logo!";
            $data['status'] = FALSE;
            echo json_encode($data);
            exit;
        }
    }
}
