<?php

namespace App\Controllers\Admin;

use App\Controllers\MasterController;

class Login extends MasterController
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Admin Account Login';
    }

    public function index()
    {
        return view('admin/auth/admin/login', [
            'title' => $this->title,
            'settings' => $this->settingsRow,
        ]);
    }

    public function authentication()
    {
        $this->validateUserLogin();
        $input = trim($this->request->getVar('email'));
        $password = trim($this->request->getVar('password'));
        # check whether the email matches the valid phone number
        # Modify this pattern according to your phone number format
        $phonePattern = '/^[0-9*#+]+$/';
        # check whether the input matches the phone number
        if (preg_match($phonePattern, $input)) {
            $email = (preg_replace('/^0/', '+256', $input));
            # condition to check for user based on phone number
            $condition = ['mobile' => $email];
        } else {
            # default to email
            $email = $input;
            # condition to check for user based on email
            $condition = ['email' => $email];
        }
        # get the user based on the email or phone provided
        $user = $this->user->where($condition)->first();
        # $user = $this->user->where('email', $email)->orWhere('mobile', $email)->first();
        if ($user) {
            # check when the user entered email or mobile
            if ($user['email'] == $email) {
                # used email address
                $email_or_mobile = $this->maskEmail($user['email']);
                $verify_mode = 'email';
            }

            if ($user['mobile'] == $email) {
                # used email address
                $email_or_mobile = $this->maskPhoneNumber($user['mobile']);
                $verify_mode = 'phone';
            }
            # check user login access
            if (strtolower($user["access_status"]) == "active") {
                $pass = $user['password'];
                $authPassword = password_verify($password, $pass);
                if ($authPassword) {
                    # check whether 2fa: two factor authentication is enable
                    if ($user['2fa'] == 'True') {
                        # set user information on the session
                        $userSessionData = [
                            'user_id' => $user['id'],
                            'verify_mode' => $verify_mode,
                            'email_or_mobile' => $email_or_mobile,
                        ];
                        $this->session->set($userSessionData);
                        return json_encode([
                            'status' => true,
                            'error' => null,
                            'url' => "/admin/account/password/verify",
                            'messages' => "Success. Redirecting to Verification"
                        ]);
                        exit;
                    } else {
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

                            # prevent admin multiple login
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
                } else {
                    /*
                    session()->setFlashdata('failed', 'Failed! incorrect password');
                    return redirect()->to(base_url('/login'));
                    */
                    $response = [
                        'status' => 500,
                        'error' => "wrongPassword",
                        'messages' => "Wrong Password Provided. Try again with correct password"
                    ];
                }
            } else {
                # user is not authorized to access this
                $response = [
                    'status' => 500,
                    'error' => 'notAuthorized',
                    'messages' => "Not Authorised: You are denied Access to this"
                ];
            }
        } else {
            /*
            session()->setFlashdata('failed', 'Failed! incorrect email');
            return redirect()->to(base_url('/login'));
            */
            $response = [
                'status' => 500,
                'error' => "wrongEmail",
                'messages' => "Wrong Email Provided. Try again with correct email"
            ];
        }
        return $this->respond($response);
    }

    public function preventAdminMultipleLogin($user_id)
    {
        # get user information
        $user = $this->user->find($user_id);
        $datetime1 = date_create(date('Y-m-d h:i:s'));
        $datetime2 = date_create($user['token_expire_date']);
        $interval = $datetime1->diff($datetime2);
        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $days = $interval->format('%a');
        $hours = $interval->format('%h');
        $mins = $interval->format('%i');
        $secs = $interval->format('%S');
        $check_time = $secs + ($mins * 60) + ($hours * 60 * 60) + ($days * 60 * 60 * 24) + ($months * 30 * 60 * 60 * 24) + ($years * 365 * 60 * 60 * 24);

        if ($check_time < 15) {
            # user already logged in
            return true;
        } else {
            # user has not logged in
            return false;
        }
    }

    private function setUserSession($user, $userlog_id,  $token)
    {
        $data = [
            'id' => $user['id'],
            'userlog_id' => $userlog_id,
            'name' => $user['name'],
            'mobile' => $user['mobile'],
            'email' => $user['email'],
            'branch_id' => $user['branch_id'],
            'photo' => $user['photo'],
            'token' => $token,
            'loggedIn' => true,
        ];

        session()->set($data);
        return true;
    }

    private function validateUserLogin()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if (empty($this->request->getVar('password'))) {
            $data['inputerror'][] = 'password';
            $data['error_string'][] = 'Password is required';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('email') == '') {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Email Address is required';
            $data['status'] = FALSE;
        }
        if (!empty(trim($this->request->getVar('email')))) {
            $input = trim($this->request->getVar('email'));
            # check whether the email is valid
            /*
            if ($this->settings->validateEmail($this->request->getVar('email')) == FALSE) {
                $data['inputerror'][] = 'email';
                $data['error_string'][] = 'Valid Email Address is required';
                $data['status'] = FALSE;
            }
            */
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

            # valid phone number regular expression pattern
            $phonePattern = '/^[0-9*#+]+$/';

            # valid email address regular expression pattern 
            $emailPattern = '/^\S+@\S+\.\S+$/';

            if (preg_match($phonePattern, $input)) {
                # check whether the first phone number is with +256
                if (substr($input, 0, 4) == '+256') {
                    if (
                        strlen($input) > 13 ||
                        strlen($input) < 13
                    ) {
                        $data['inputerror'][] = 'email';
                        $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                        $data['status'] = FALSE;
                    }
                }
                # check whether the first phone number is with 0
                else if (substr($input, 0, 1) == '0') {
                    if (
                        strlen($input) > 10 ||
                        strlen($input) < 10
                    ) {
                        $data['inputerror'][] = 'email';
                        $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                        $data['status'] = FALSE;
                    }
                } else if (substr($input, 0, 1) == '+') {
                    if (
                        strlen($input) > 13 ||
                        strlen($input) < 13
                    ) {
                        $data['inputerror'][] = 'email';
                        $data['error_string'][] = 'Should have 13 digits with Country Code';
                        $data['status'] = FALSE;
                    }
                } else {
                    $data['inputerror'][] = 'email';
                    $data['error_string'][] = 'Valid Phone Number is required';
                    $data['status'] = FALSE;
                }
                # check whether the phone number is valid
                if ($this->settings->validatePhoneNumber($input) == FALSE) {
                    $data['inputerror'][] = 'email';
                    $data['error_string'][] = 'Valid Phone Number is required';
                    $data['status'] = FALSE;
                }
            } elseif (preg_match($emailPattern, $input)) {
                # check whether the email is valid
                if ($this->settings->validateEmail($input) == FALSE) {
                    $data['inputerror'][] = 'email';
                    $data['error_string'][] = 'Valid Email Address is required';
                    $data['status'] = FALSE;
                }
            } else {
                $data['inputerror'][] = 'email';
                $data['error_string'][] = 'Invalid Input. Try with valid phone or email.';
                $data['status'] = FALSE;
            }
        }
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function show404($message)
    {
        return view('admin/dashboard/errors', [
            'title' => 'Error!',
            'menu' => 404,
            'message' => $message,
        ]);
    }
}
