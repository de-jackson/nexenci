<?php

namespace App\Controllers\Client;

use App\Controllers\Client\MainController;

class Auth extends MainController
{
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Client Account Login';
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index(): string
    {
        return view('client/auth/login', [
            'title' => $this->title,
            'settings' => $this->settingsRow,
        ]);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    public function authentication()
    {
        $this->clientValidation("login");
        $input = trim($this->request->getVar('phone_full'));
        $phone = (preg_replace('/^0/', '+256', $input));
        $password = trim($this->request->getVar('password'));

        $client = $this->client->where('mobile', $phone)->first();
        # Check the client account existence
        if ($client) {
            # check client login access
            if (strtolower($client["access_status"]) == "active") {
                $pass = $client['password'];
                $authPassword = password_verify($password, $pass);
                if ($authPassword) {
                    # check when the user entered email or mobile
                    if ($client['email'] == $phone) {
                        # used email address
                        $client_email_or_mobile = $this->maskEmail($client['email']);
                        $client_verification_mode = 'email';
                    }

                    if ($client['mobile'] == $phone) {
                        # used email address
                        $client_email_or_mobile = $this->maskPhoneNumber($client['mobile']);
                        $client_verification_mode = 'phone';
                    }
                    # check whether 2fa: two factor authentication is enable
                    if ($client['2fa'] == 'True') {
                        # set client information on the session
                        $clientSessionData = [
                            'client_id' => $client['id'],
                            'client_verification_mode' => $client_verification_mode,
                            'client_email_or_mobile' => $client_email_or_mobile,
                            'account' => '2fa'
                        ];

                        $this->session->set($clientSessionData);

                        return json_encode([
                            'status' => true,
                            'error' => null,
                            'url' => base_url('client/account/verify'),
                            'messages' => "Success. Redirecting to Verification"
                        ]);
                    } else {

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
                                'url' => "/client/dashboard",
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
                    'messages' => "Not Authorized: You are denied Access to this"
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

    public function preventAdminMultipleLogin($client_id)
    {
        # get client information
        $client = $this->client->find($client_id);
        $datetime1 = date_create(date('Y-m-d h:i:s'));
        $datetime2 = date_create($client['token_expire_date']);
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

    private function setUserSession($client, $userlog_id,  $token)
    {
        $clientData = [
            'client_id' => $client['id'],
            'userlog_id' => $userlog_id,
            'name' => $client['name'],
            'mobile' => $client['mobile'],
            'email' => $client['email'],
            'branch_id' => $client['branch_id'],
            'photo' => $client['photo'],
            'token' => $token,
            'clientLoggedIn' => true,
        ];

        session()->set($clientData);
        return true;
    }

    public function email()
    {
        # return view('client/auth/email', [
        return view('client/auth/phone', [
            'title' => 'Password Reset',
            'settings' => $this->settingsRow,
        ]);
    }

    public function reset($id, $token, $email)
    {
        $client = $this->client->find($id);
        # check client account existence
        if ($client) {
            # get client information
            $token_expire_date = $client['token_expire_date'];
            $current_time = date('Y-m-d H:i:s');
            # check client login access
            if (strtolower($client["access_status"]) == "active") {
                # check user token
                $authToken = password_verify($token, $client['token']);
                if ($authToken) {
                    # validate the time
                    if ($current_time <= $token_expire_date) {
                        # display change password page
                        return view('client/auth/password', [
                            'title' => 'Password Reset',
                            'client' => $client,
                            'settings' => $this->settingsRow,
                        ]);
                    } else {
                        # the password reset link already expired
                        session()->setFlashdata('failed', "Password Reset Link Expired!");
                        return redirect()->to(base_url('client/login'));
                    }
                } else {
                    # invalid client token
                    session()->setFlashdata('failed', "Password Reset Link is Invalid!");
                    return redirect()->to(base_url('client/login'));
                }
            } else {
                # client was blocked from access this
                session()->setFlashdata('failed', "You are not Authorized to Access!");
                return redirect()->to(base_url('client/login'));
            }
        } else {
            # client does not exist
            session()->setFlashdata('failed', "We couldn't find your account!");
            return redirect()->to(base_url('client/login'));
        }
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function password()
    {
        $this->clientValidation("password");
        $id = $this->request->getVar('id');
        $data = [
            'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
        ];
        $passwordChange = $this->client->update($id, $data);
        if ($passwordChange) {
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Password changed successfully',
                ],
            ];
            return $this->respond($response);
        } else {
            $response = [
                'status' => 500,
                'error' => 'Update Failed',
                'message' => 'Failed to update password',
            ];
            return $this->respond($response);
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        $this->clientValidation("recovery");
        $input = trim($this->request->getVar('phone_full'));
        $phone = (preg_replace('/^0/', '+256', $input));
        $client = $this->client->where('mobile', $phone)->first();

        if ($client) {
            # check user login access
            if (strtolower($client["access_status"]) == "active") {
                $client_email_or_mobile = $this->maskPhoneNumber($client['mobile']);
                # set client information on the session
                $clientSessionData = [
                    'client_id' => $client['id'],
                    'client_verification_mode' => 'phone',
                    'client_email_or_mobile' => $client_email_or_mobile,
                    'account' => 'resetpassword'
                ];

                $this->session->set($clientSessionData);

                return json_encode([
                    'status' => true,
                    'error' => null,
                    'url' => base_url('client/account/client/verify'),
                    'messages' => "Success. Redirecting to Verification"
                ]);

                # OLD APPROACH
                # generate unique random token
                $token = $this->settings->generateRandomNumbers(32);
                # set duration for the password reset link to expire in minutes
                $duration = '20';
                $data = [
                    'token' => password_hash($token, PASSWORD_DEFAULT),
                    'token_expire_date' => date("Y-m-d H:i:s", strtotime("+" . $duration . " minutes"))
                ];

                # update client token information
                $save = $this->client->update($client["id"], $data);
                if ($save) {
                    # check internet connect
                    $checkInternet = $this->settings->checkNetworkConnection();
                    if ($checkInternet) {
                        # send email along with with token
                        $subject = "Reset Password Notification";
                        $this->settings->sendMail($client, $subject, $token, 'reset');
                        $response = [
                            'status' => 200,
                            'error' => null,
                            'messages' => "Email Sent"
                        ];
                        return $this->respond($response);
                    } else {
                        $response = [
                            'status' => 201,
                            'error' => 'notInternet',
                            'messages' => "You have No Internet Connection"
                        ];
                        return $this->respond($response);
                    }
                } else {
                    $response = [
                        'status' => 201,
                        'error' => 'tokenNotSaved',
                        'messages' => "External Error Occurred. Kindly try again later"
                    ];
                    return $this->respond($response);
                }
            } else {
                # user is not authorized to access this
                $response = [
                    'status' => 201,
                    'error' => 'notAuthorized',
                    'messages' => "You are not Authorized to access this."
                ];
                return $this->respond($response);
            }
        } else {

            $response = [
                'status' => 200,
                'error' => 'wrongPhone',
                'messages' => "We can't find your account."
            ];
            return $this->respond($response);
        }
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
