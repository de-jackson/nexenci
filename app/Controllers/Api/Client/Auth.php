<?php

namespace App\Controllers\Api\Client;

use App\Controllers\Api\Client\MainController;

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
    public function index()
    {
        # return $this->sendResponse([], 'You are required to login first');
        return $this->sendError('You are required to login first', [], 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        return $id;
        $client = $this->getClientByID($id);
        if ($client) {
            return $this->respond($client);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        $rules = [
            'phone' => 'required|min_length[10]|max_length[15]|regex_match[/^[0-9*#+]+$/]'
        ];

        if (!$this->validate($rules)) {
            return $this->sendError('Validation Error.', $this->validator->getErrors());
        }

        $input = trim($this->request->getVar('phone'));
        $phone = trim(preg_replace('/^0/', '+256', $input));

        $client = $this->client
            ->where(['mobile' => $phone])
            ->first();

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
                return $this->sendResponse([], 'Success. Redirecting you to verification process');

                # Email Forgot Password Approach
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
                        return $this->sendResponse([], 'We have emailed you Successfully');
                    } else {
                        # token not updated
                        return $this->sendError('Whoops. You have no internet connection.', [
                            'error' => 'noInternet'
                        ]);
                    }
                } else {
                    # token not updated
                    return $this->sendError('Internal Error Occured.', [
                        'error' => 'tokenNotSaved'
                    ], 500);
                }
            } else {
                # user is not authorized to access this
                return $this->sendError('You are not authorized to access this', [
                    'error' => 'notAuthorized'
                ], 401);
            }
        } else {
            return $this->sendError('We can\'t find your account', [
                'error' => 'unknownPhone'
            ]);
        }
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $rules = [
            'phone' => 'required|min_length[10]|max_length[15]|regex_match[/^[0-9*#+]+$/]',
            'password' => 'required|min_length[4]|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return $this->sendError('Validation Error.', $this->validator->getErrors());
        }

        $phone = trim($this->request->getVar('phone'));
        $password = trim($this->request->getVar('password'));

        $client = $this->client
            ->where(['mobile' => trim(preg_replace('/^0/', '+256', $phone))])
            ->first();

        # Check the client account existance
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

                        return $this->sendResponse([], 'Success. Redirecting to Verification');
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
                            # set user information on the session
                            $this->session->set($clientSessionData);
                            return $this->sendResponse($clientSessionData, 'Success. Redirecting to dashboard');
                        } else {
                            return $this->sendError('Token caused External Error. Try again', [
                                'error' => 'tokenNotSaved'
                            ]);
                        }
                    }
                } else {
                    return $this->sendError('Wrong Password Provided. Try again with correct password', [
                        'error' => 'wrongPassword'
                    ], 200);
                }
            } else {
                return $this->sendError('Not Authorised: You are denied Access to this', [
                    'error' => 'notAuthorized'
                ], 401);
            }
        } else {
            return $this->sendError('Wrong Phone Number Provided. Try again with correct phone number', [
                'error' => 'wrongPhone'
            ], 200);
        }
    }

    public function email()
    {
        return $this->sendResponse([], 'Password Reset');
        return view('client/auth/email', [
            'title' => 'Password Reset',
            'settings' => $this->settingsRow,
        ]);
    }

    public function reset($client_id, $token, $email)
    {
        $id = session()->get('client_id');

        $client_id = session()->get('client_id');
        $client_email_or_mobile = session()->get('client_email_or_mobile');
        # check the client session
        if (!isset($client_id) || !isset($client_email_or_mobile)) {
            # redirect the client to login screen again
            return $this->sendError('Your verification process has timedout!', [], 200);
        }

        # $client = $this->client->find($client_id);
        $client = $this->getClientByID($client_id);
        # check client account existance
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
                        # Remove the 'password' field
                        unset($client['password']);
                        unset($client['token']);
                        unset($client['token_expire_date']);
                        unset($client['deleted_at']);
                        # display change password page
                        return $this->sendResponse([
                            'title' => 'Password Reset',
                            'user' => $client,
                            'settings' => $this->settingsRow,
                        ], 'Reset Password' . $token_expire_date);
                    } else {
                        # the password reset link already expired
                        return $this->sendError('Password Reset Link Expired', [
                            'error' => 'notAuthorized'
                        ], 401);
                    }
                } else {
                    # invalid client token
                    return $this->sendError('Password Reset Link is Invalid', [
                        'error' => 'notAuthorized'
                    ]);
                }
            } else {
                # client was blocked from access this
                return $this->sendError('Not Authorised: You are denied Access to this', [
                    'error' => 'notAuthorized'
                ], 401);
            }
        } else {
            # client doesnot exist
            return $this->sendError('We couldn\'t find your account', [
                'error' => 'noAccount'
            ]);
        }
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function password()
    {
        $rules = [
            'password' => 'required|min_length[8]|max_length[20]',
            'confirm_password' => 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            return $this->sendError('Validation Error.', $this->validator->getErrors());
        }

        # $id = $this->request->getVar('client_id');
        $id = session()->get('client_id');
        $data = [
            'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
        ];
        $passwordChange = $this->client->update($id, $data);
        if ($passwordChange) {
            return $this->sendResponse([], 'Password changed successfully');
        } else {
            return $this->sendError('Failed to update password', [], 500);
        }
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
        $rules = [
            'password' => 'required|min_length[8]|max_length[20]',
            'confirm_password' => 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            return $this->sendError('Validation Error.', $this->validator->getErrors());
        }

        # $client = $this->client->find($id);
        $client = $this->getClientByID($id);
        # check client account existance
        if ($client) {
            $data = [
                'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $passwordChange = $this->client->update($id, $data);
            if ($passwordChange) {
                return $this->sendResponse([], 'Password changed successfully');
            } else {
                return $this->sendError('Failed to update password', [], 500);
            }
        } else {
            # return $this->failNotFound('No Data Found with id '.$id);
            return $this->sendError('We can\'t find your account with id ' . $id);
        }
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
}
