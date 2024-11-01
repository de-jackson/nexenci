<?php

namespace App\Controllers\Admin;

use App\Controllers\MasterController;

class Password extends MasterController
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        return view('admin/auth/passwords/email', [
            'title' => 'Password Reset',
            'settings' => $this->settingsRow,
        ]);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        $this->validateUserEmail("reset");
        $email = trim($this->request->getVar('email'));
        $user = $this->user->where('email', $email)->first();

        if ($user) {
            # check user login access
            if (strtolower($user["access_status"]) == "active") {
                # generate unique random token
                $token = $this->settings->generateRandomNumbers(32);
                # set duration for the password reset link to expire in minutes
                $duration = '15';
                $data = [
                    'token' => password_hash($token, PASSWORD_DEFAULT),
                    'token_expire_date' => date("Y-m-d H:i:s", strtotime("+" . $duration . " minutes"))
                ];

                # update user token information
                $save = $this->user->update($user["id"], $data);
                if ($save) {
                    # check internet connect
                    $checkInternet = $this->settings->checkNetworkConnection();
                    if ($checkInternet) {
                        # send email along with with token
                        $subject = "Reset Password Notification";
                        $this->settings->sendMail($user, $subject, $token, 'reset');
                        $response = [
                            'status' => 200,
                            'error' => null,
                            'messages' => "Email Sent"
                        ];
                        return $this->respond($response);
                    } else {
                        $response = [
                            'status' => 201,
                            'error' => null,
                            'messages' => "No Internet"
                        ];
                        return $this->respond($response);
                    }
                } else {
                    $response = [
                        'status' => 201,
                        'error' => 'error',
                        'messages' => "tokenNotSaved"
                    ];
                    return $this->respond($response);
                }
            } else {
                # user is not authorized to access this
                $response = [
                    'status' => 201,
                    'error' => null,
                    'messages' => "notAuthorized"
                ];
                return $this->respond($response);
            }
        } else {

            $response = [
                'status' => 201,
                'error' => null,
                'messages' => "wrongEmail"
            ];
            return $this->respond($response);
        }
    }

    private function validateUserEmail($menu)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

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
                $data['error_string'][] = 'Your OTP Code is required';
                $data['status'] = FALSE;
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function reset($id, $token, $email)
    {
        $user = $this->user->find($id);
        # check user existence
        if ($user) {
            # get user information
            $token_expire_date = $user['token_expire_date'];
            $current_time = date('Y-m-d H:i:s');
            # check user login access
            if (strtolower($user["access_status"]) == "active") {
                # check user token
                $authToken = password_verify($token, $user['token']);
                if ($authToken) {
                    # validate the time
                    if ($current_time <= $token_expire_date) {
                        # display change password page
                        return view('admin/auth/passwords/reset', [
                            'title' => 'Password Reset',
                            'user' => $user,
                            'settings' => $this->settingsRow,
                        ]);
                    } else {
                        # the password reset link already expired
                        session()->setFlashdata('failed', "Password Reset Link Expired!");
                        return redirect()->to(base_url('admin/login'));
                    }
                } else {
                    # invalid user token
                    session()->setFlashdata('failed', "Password Reset Link is Invalid!");
                    return redirect()->to(base_url('admin/login'));
                }
            } else {
                # user was blocked from access this
                session()->setFlashdata('failed', "You are not Authorized to Access!");
                return redirect()->to(base_url('admin/login'));
            }
        } else {
            # user does not exist
            session()->setFlashdata('failed', "We couldn't find your account!");
            return redirect()->to(base_url('admin/login'));
        }
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


    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $this->validateResetPassword();
        $id = $this->request->getVar('id');
        $data = [
            'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
        ];
        $passwordChange = $this->user->update($id, $data);
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

    private function validateResetPassword()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

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
            /*
            if (!preg_match("/^[a-zA-Z0-9 ']*$/", $pwd)) {
                $data['inputerror'][] = 'password';
                $data['error_string'][] = 'Illegal character. Letters & numbers allowed!';
                $data['status'] = FALSE;
            }
            */
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
            /*
            if (!preg_match("/^[a-zA-Z0-9 ']*$/", $pwd)) {
                $data['inputerror'][] = 'password_confirm';
                $data['error_string'][] = 'Illegal character. Letters & numbers allowed!';
                $data['status'] = FALSE;
            }
            */
        }
        if ($this->request->getVar('password') != $this->request->getVar('password_confirm')) {
            $data['inputerror'][] = 'password_confirm';
            $data['error_string'][] = 'Confirm Password didn\'t match!';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
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

    public function verify()
    {
        $user_id = session()->get('user_id');
        $verify_mode = session()->get('verify_mode');
        $email_or_mobile = session()->get('email_or_mobile');
        # check the user session
        if (!isset($user_id) || !isset($verify_mode) || !isset($email_or_mobile)) {
            # redirect the user to login screen again
            return redirect()->to('/admin/login');
        }
        $user = $this->user->find($user_id);
        # check the user existence
        if ($user) {
            return view('admin/auth/passwords/verify', [
                'title' => 'Verify your identity?',
                'user' => $user,
                'verify_mode' => $verify_mode,
                'email_or_mobile' => $email_or_mobile,
                'settings' => $this->settingsRow,
            ]);
        } else {
            return view('layout/404', [
                'title' => 'Verify your identity?',
                'settings' => $this->settingsRow,
            ]);
        }
    }


    public function sendTokenCode()
    {
        $user_id = session()->get('user_id');
        $verify_mode = session()->get('verify_mode');
        $mode = ($verify_mode == "phone") ? $verify_mode : "email";
        $user = $this->user->find($user_id);
        # check the user existence
        if ($user) {
            # user name
            $names = preg_split("/ /", $user['name']);
            $sendTo = $user['email'];

            # check user login access
            if (strtolower($user["access_status"]) == "active") {
                # generate unique random token
                # $token = $this->settings->generateRandomNumbers(6);
                $token = $this->settings->generateRandomNumbers(6, 'otp');
                # set duration for the verification token to expire in minutes
                $duration = '15';
                $data = [
                    'token' => password_hash($token, PASSWORD_DEFAULT),
                    'token_expire_date' => date("Y-m-d H:i:s", strtotime("+" . $duration . " minutes"))
                ];

                # update user token information
                $save = $this->user->update($user["id"], $data);
                if ($save) {
                    # check internet connect
                    $checkInternet = $this->settings->checkNetworkConnection();
                    if ($checkInternet) {
                        # email subject
                        $subject = "Your OTP Code";

                        # email body
                        $message = '<h2>Dear ' . ucwords(strtolower($user['name'])) . '</h2>
                        <p>A sign-in attempt requires further verification because we want to protect your account. To complete the sign-in, please enter the verification code on the OTP page screen as shown below.</p>
                        <p><b>' . $token . '</b></p>
                        <p class="error">
                            <b>The verification code will valid up to next ' . $duration . ' minutes</b>
                        </p>
                        <p>Regards,</p>
                        <p><b>Administrator</b></p>
                        <hr>
                        ';

                        # check the email existence
                        if (!empty($sendTo)) {
                            # send email notification
                            $user["user_token"] = $token;
                            $user["duration"] = $duration;
                            $this->settings->sendMail($user, $subject, "2fa", 'reset');
                            # $this->settings->sendMailNotify($message, $subject, $sendTo);
                        }

                        # send sms via the phone
                        # check the phone number existence
                        if (!empty($user['mobile']) && $verify_mode == 'phone') {
                            # Set the numbers you want to send to in international format
                            /*
                            $recipient = trim(preg_replace('/^0/', '+256', $user['mobile']));
                            # Set the text message
                            $text = 'Your ' . strtoupper($this->settingsRow["system_abbr"]) . ' OTP Code: ' . $token . ' Valid for ' . $duration . ' minutes.';

                            $apiResponse   = $this->smsAPI->send([
                                'to'      => $recipient,
                                'message' => $text
                            ]);
                            */
                            $recipient = $this->phoneNumberWithCountryCode($user['mobile']);
                            # Set the text message
                            $text = 'Your ' . strtoupper($this->settingsRow["system_abbr"]) . ' OTP Code: ' . $token . '. Valid for ' . $duration . ' minutes.';
                            $apiResponse = $this->egoAPI->initiate('sms', [
                                "number" => $recipient,
                                "message" => $text
                            ]);

                            # decode the JSON response
                            # $decodedResponse = json_decode($apiResponse);
                            # $statusCode = $apiResponse->SMSMessageData->Recipients[0]->statusCode;

                            /*
                                if ($statusCode == 101) {
                                    # code...
                                } else {

                                }

                                */
                            # print_r($apiResponse);

                        }


                        # store the token on the session
                        $this->session->set(['user_token' => $token]);

                        # check the url
                        if ((current_url() == base_url('admin/account/password/code'))) {
                            $url = "";
                        } else {
                            $url = "/admin/account/password/code";
                        }

                        $response = [
                            'status' => 200,
                            'error' => null,
                            'url' => $url,
                            'messages' => "OTP Code sent. Your Code has been sent to your " . $mode
                        ];
                        return $this->respond($response);
                    } else {
                        $response = [
                            'status' => 201,
                            'error' => "noInternet",
                            'messages' => "Error Occurred: Your device has no internet connection"
                        ];
                        return $this->respond($response);
                    }
                } else {
                    $response = [
                        'status' => 201,
                        'error' => "tokenNotSaved",
                        'messages' => "An External Error Occurred: Kindly try again"
                    ];
                    return $this->respond($response);
                }
            } else {
                # user is not authorized to access this
                $response = [
                    'status' => 201,
                    'error' => null,
                    'messages' => "notAuthorized"
                ];
                return $this->respond($response);
            }
        } else {
            return view('layout/404', [
                'title' => 'Verify your identity?',
                'settings' => $this->settingsRow,
            ]);
        }
    }

    public function code()
    {
        $user_id = session()->get('user_id');
        $verify_mode = session()->get('verify_mode');
        $email_or_mobile = session()->get('email_or_mobile');
        # check the user session
        if (!isset($user_id) || !isset($verify_mode) || !isset($email_or_mobile)) {
            # redirect the user to login screen again
            return redirect()->to('/admin/login');
        }
        $user = $this->user->find($user_id);
        # check the user existence
        if ($user) {
            return view('admin/auth/passwords/code', [
                'title' => 'Verify your identity?',
                'user' => $user,
                'verify_mode' => $verify_mode,
                'email_or_mobile' => $email_or_mobile,
                'settings' => $this->settingsRow,
            ]);
        } else {
            return view('layout/404', [
                'title' => 'Verify your identity?',
                'settings' => $this->settingsRow,
            ]);
        }
    }

    public function authCode()
    {
        $this->validateUserEmail("otp");
        $user_id = session()->get('user_id');
        $user = $this->user->find($user_id);
        $user_token = session()->get('user_token');
        $token = trim($this->request->getVar('code'));
        # check user existence
        if ($user) {
            # get user information
            $token_expire_date = $user['token_expire_date'];
            $current_time = date('Y-m-d H:i:s');
            # check user login access
            if (strtolower($user["access_status"]) == "active") {
                # check user token
                $authToken = password_verify($token, $user['token']);
                if ($authToken) {
                    # validate the time
                    if ($current_time <= $token_expire_date) {
                        # login the user now
                        $this->loginUserNow($user);
                        $response = [
                            'status' => 200,
                            'error' => null,
                            'url' => "/admin/dashboard",
                            'messages' => "Success. Redirecting to dashboard"
                        ];
                    } else {
                        # the token expired
                        $response = [
                            'status' => 500,
                            'error' => "codeExpired",
                            'messages' => "Sorry! Your OTP code already Expired!"
                        ];
                    }
                } else {
                    # invalid user token
                    $response = [
                        'status' => 500,
                        'error' => "wrongCode",
                        'messages' => "Wrong Code Provided. Try again with the correct Code"
                    ];
                }
            } else {
                # user was blocked from access this
                $response = [
                    'status' => 500,
                    'error' => 'notAuthorized',
                    'messages' => "Not Authorised: You not Authorised Access to this!"
                ];
            }
        } else {
            # user account does not exist
            $response = [
                'status' => 500,
                'error' => "noAccount",
                'messages' => "We couldn't find your account"
            ];
        }

        return $this->respond($response);
    }

    public function clearUserSession()
    {
        $user_id = session()->get('user_id');
        if (isset($user_id)) {
            # destroy user session
            $session = session();
            # unset all session variables
            session_unset();

            # destroy the session

            $session->destroy();
            // unset(
            //     $_SESSION['loggedIn'],
            //     $_SESSION['name'],
            //     $_SESSION['id'],
            // );

            # create a response array
            $response = [
                'status' => 200,
                'message' => 'Sessions cleared successfully.',
            ];

            # send the response as JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
}
