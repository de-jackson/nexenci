<?php

namespace App\Controllers\Client;

use App\Controllers\Client\MainController;

class Account extends MainController
{

    public function __construct()
    {
        parent::__construct();
        $this->title = 'Client Account Verification';
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $client_id = session()->get('client_id');
        $client_verification_mode = session()->get('client_verification_mode');
        $client_email_or_mobile = session()->get('client_email_or_mobile');
        # check the client session
        if (!isset($client_id) || !isset($client_verification_mode) || !isset($client_email_or_mobile)) {
            # redirect the client to login screen again
            return redirect()->to(base_url('client/login'));
        }
        $client = $this->client->find($client_id);
        # check the client existence
        if ($client) {
            return view('client/auth/account/verify', [
                'title' => 'Verify your identity?',
                'user' => $client,
                'client_verification_mode' => $client_verification_mode,
                'client_email_or_mobile' => $client_email_or_mobile,
                'settings' => $this->settingsRow,
            ]);
        } else {
            return view('layout/404', [
                'title' => 'Verify your Account Identity?',
                'settings' => $this->settingsRow,
            ]);
        }
    }

    public function sendTokenCode()
    {
        $client_id = session()->get('client_id');
        $client_verification_mode = session()->get('client_verification_mode');
        $mode = ($client_verification_mode == "phone") ? $client_verification_mode : "email";
        $client = $this->client->find($client_id);
        # check the client existence
        if ($client) {
            # user name
            $names = preg_split("/ /", $client['name']);
            $sendTo = $client['email'];

            # check client login access
            if (strtolower($client["access_status"]) == "active") {
                # generate unique random token
                $token = $this->settings->generateRandomNumbers(6, 'otp');
                # set duration for the verification token to expire in minutes
                $duration = '15';
                $data = [
                    'token' => password_hash($token, PASSWORD_DEFAULT),
                    'token_expire_date' => date("Y-m-d H:i:s", strtotime("+" . $duration . " minutes"))
                ];

                # update client token information
                $update = $this->client->update($client["id"], $data);
                if ($update) {
                    # check internet connect
                    $checkInternet = $this->settings->checkNetworkConnection();
                    if ($checkInternet) {
                        # email subject
                        $subject = "Your Verification OTP Code";

                        # email body
                        $message = '<h2>Dear ' . ucwords(strtolower($client['name'])) . '</h2>
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
                            $client["user_token"] = $token;
                            $client["duration"] = $duration;
                            $this->settings->sendMail($client, $subject, "2fa", 'reset');
                            # $this->settings->sendMailNotify($message, $subject, $sendTo);
                        }

                        # send sms via the phone
                        # check the phone number existence
                        if (!empty($client['mobile']) && $client_verification_mode == 'phone') {
                            # Set the numbers you want to send to in international format
                            // $recipient = trim(preg_replace('/^0/', '+256', $client['mobile']));
                            $recipient = $this->phoneNumberWithCountryCode($client['mobile']);
                            # Set the text message
                            $text = 'Your ' . strtoupper($this->settingsRow["system_abbr"]) . ' OTP Code: ' . $token . '. Valid for ' . $duration . ' minutes.';
                            /*
                            $apiResponse   = $this->smsAPI->send([
                                'to'      => $recipient,
                                'message' => $text
                            ]);
                            */
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

                            "apiResponse": {
                                "status": "success",
                                "data": {
                                    "SMSMessageData": {
                                        "Message": "Sent to 1/1 Total Cost: UGX 27.0000",
                                        "Recipients": [
                                            {
                                                "cost": "UGX 27.0000",
                                                "messageId": "ATXid_95836e5ca5204f0a25032e816d50a53f",
                                                "messageParts": 1,
                                                "number": "+256774649641",
                                                "status": "Success",
                                                "statusCode": 101
                                            }
                                        ]
                                    }
                                }
                            }

                            */
                            # print_r($apiResponse);

                        }

                        # store the token on the session
                        $this->session->set(['client_token' => $token]);

                        $response = [
                            'status' => 200,
                            'error' => null,
                            # 'apiResponse' => $apiResponse,
                            'url' => base_url('client/account/token/verification'),
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
            $response = [
                'status' => 201,
                'error' => "notAuthorized",
                'messages' => "We couldn't find your account"
            ];
            return $this->respond($response);
        }
    }

    public function code()
    {
        $client_id = session()->get('client_id');
        $client_verification_mode = session()->get('client_verification_mode');
        $client_email_or_mobile = session()->get('client_email_or_mobile');
        # check the user session
        if (!isset($client_id) || !isset($client_verification_mode) || !isset($client_email_or_mobile)) {
            # redirect the user to login screen again
            return redirect()->to(base_url('client/login'));
        }
        $client = $this->client->find($client_id);
        # check the user existence
        if ($client) {
            return view('client/auth/account/code', [
                'title' => 'Verify your identity?',
                'user' => $client,
                'client_verification_mode' => $client_verification_mode,
                'client_email_or_mobile' => $client_email_or_mobile,
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
        $this->clientValidation("otp");
        $client_id = session()->get('client_id');
        $client = $this->client->find($client_id);
        $user_token = session()->get('user_token');
        $module = strtolower(session()->get('account'));
        $token = trim($this->request->getVar('code'));
        # check client existence
        if ($client) {
            $phone = $this->maskPhoneNumber($client['mobile']);
            # get client information
            $token_expire_date = $client['token_expire_date'];
            $current_time = date('Y-m-d H:i:s');
            # check client login access
            if (strtolower($client["access_status"]) == "active") {
                # check client token
                $authToken = password_verify($token, $client['token']);
                if ($authToken) {
                    # validate the time
                    if ($current_time <= $token_expire_date) {
                        # check the module  
                        switch ($module) {
                            case '2fa':
                                # login the user now
                                $this->loginClientNow($client);
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'url' => base_url('client/dashboard'),
                                    'messages' => "Success. Redirecting to dashboard"
                                ];
                                break;

                            case 'resetpassword':
                                # login the user now
                                $url = base_url('client/account/password/reset/' . $client_id . '/' . $token . '/' . $phone);
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'url' => $url,
                                    'messages' => "Redirecting you to password reset!"
                                ];
                                break;

                            default:
                                /*
                                # redirect to the login after registration
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'url' => base_url('client/login'),
                                    'messages' => "Success. Redirecting to Login"
                                ];
                                */
                                $this->loginClientNow($client);
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'url' => base_url('client/dashboard'),
                                    'messages' => "Success. Redirecting to dashboard"
                                ];
                                break;
                        }
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
                    'messages' => "Not Authorized: You not Authorized Access to this!"
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

    public function clearClientSession()
    {
        $client_id = session()->get('client_id');
        if (isset($client_id)) {
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
                'error' => null,
                'message' => 'Sessions cleared successfully.',
            ];

            # send the response as JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
}
