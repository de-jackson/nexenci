<?php

namespace App\Controllers\Api\Client;

use App\Controllers\Api\Client\MainController;

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
            return $this->sendError('Your verification process has timedout!', [], 200);
        }
        # $client = $this->client->find($client_id);
        $client = $this->getClientByID($client_id);
        # check the client existance
        if ($client) {
            return $this->sendResponse([
                'client' => $client,
                'clientVerificationMode' => $client_verification_mode,
                'clientPhoneNumber' => $client_email_or_mobile,
                'business' => $this->settingsRow,
            ], 'Verify your identity');
        } else {
            return $this->sendError('We can\'t find your account!', [], 200);
        }
    }

    public function sendTokenCode()
    {
        $client_id = session()->get('client_id');
        $client_verification_mode = session()->get('client_verification_mode');
        $client_email_or_mobile = session()->get('client_email_or_mobile');
        # check the client session
        if (!isset($client_id) || !isset($client_verification_mode) || !isset($client_email_or_mobile)) {
            # redirect the client to login screen again
            return $this->sendError('Your verification process has timedout!', [], 200);
        }

        $mode = ($client_verification_mode == "phone") ? $client_verification_mode : "email";
        # $client = $this->client->find($client_id);
        $client = $this->getClientByID($client_id);
        # check the client existance
        if ($client) {
            # user name
            $names = preg_split("/ /", $client['name']);
            $sendTo = $client['email'];

            # check client login access
            if (strtolower($client["access_status"]) == "active") {
                # generate unique random token
                $token = $this->settings->generateRandomNumbers(6, 'otp');
                # set duration for the vertification token to expire in minutes
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
                        <p>A signin attempt requires further verification because we want to proctect your account. To complete the signin, please enter the verification code on the OTP page screen as shown below.</p>
                        <p><b>' . $token . '</b></p>
                        <p class="error">
                            <b>The verification code will valid up to next ' . $duration . ' minutes</b>
                        </p>
                        <p>Regards,</p>
                        <p><b>Administrator</b></p>
                        <hr>
                        ';

                        # check the email existance
                        if (!empty($sendTo)) {
                            # send email notification
                            $client["user_token"] = $token;
                            $client["duration"] = $duration;
                            $this->settings->sendMail($client, $subject, "2fa", 'reset');
                            # $this->settings->sendMailNotify($message, $subject, $sendTo);
                        }

                        # send sms via the phone
                        # check the phone number existance
                        if (!empty($client['mobile']) && $client_verification_mode == 'phone') {
                            # Set the numbers you want to send to in international format
                            $recipient = trim(preg_replace('/^0/', '+256', $client['mobile']));
                            # Set the text nessage
                            $text = 'Your ' . strtoupper($this->settingsRow["system_abbr"]) . ' OTP Code: ' . $token . ' Valid for ' . $duration . ' minutes.';

                            $apiResponse   = $this->smsAPI->send([
                                'to'      => $recipient,
                                'message' => $text
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
                            # 'apiResponse' => $apiResponse,
                            'url' => base_url('api/client/account/token/verification'),
                        ];
                        return $this->sendResponse('OTP Code sent. Your Code has been sent to your ' . $mode, $response);
                    } else {
                        $response = [
                            'client' => $client,
                            'error' => "noInternet",
                        ];
                        return $this->sendError('Error Occured: Your device has no internet connection', $response, 200);
                    }
                } else {
                    return $this->sendError('An External Error Occured: Kindly try again', [
                        'error' => "tokenNotSaved",
                    ], 200);
                }
            } else {
                # user is not authorized to access this
                return $this->sendError('You\'re not Authorized to access this', [
                    'error' => "notAuthorized"
                ], 200);
            }
        } else {
            return $this->sendError('We couldn\'t find your account', [
                'error' => "notAuthorized",
            ], 200);
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
            return $this->sendError('Your verification process has timedout!', [], 200);
        }
        # $client = $this->client->find($client_id);
        $client = $this->getClientByID($client_id);
        # check the user existance
        if ($client) {
            return $this->sendResponse([
                'client' => $client,
                'clientVerificationMode' => $client_verification_mode,
                'clientPhoneNumber' => $client_email_or_mobile,
                'business' => $this->settingsRow,
            ], 'Verify your identity');
        } else {
            return $this->sendError('We couldn\'t find your account', [
                'error' => "notAuthorized",
            ], 200);
        }
    }

    public function authCode()
    {
        $rules = [
            'code' => 'required|min_length[6]|max_length[6]|regex_match[/^[0-9*#+]+$/]'
        ];

        if (!$this->validate($rules)) {
            return $this->sendError('Validation Error.', $this->validator->getErrors());
        }

        $client_id = session()->get('client_id');
        $user_token = session()->get('user_token');
        $client_verification_mode = session()->get('client_verification_mode');
        $client_email_or_mobile = session()->get('client_email_or_mobile');
        $module = strtolower(session()->get('account'));
        $token = trim($this->request->getVar('code'));
        # check the client session
        if (!isset($client_id) || !isset($client_verification_mode) || !isset($client_email_or_mobile)) {
            # redirect the client to login screen again
            return $this->sendError('Your verification process has timedout!', [], 200);
        }

        $token = trim($this->request->getVar('code'));
        $client = $this->client->find($client_id);
        # $client = $this->getClientByID($client_id);
        # check client existance
        if ($client) {
            # get client information
            $phone = $this->maskPhoneNumber($client['mobile']);
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
                                return $this->sendResponse(
                                    [],
                                    'Success. Redirecting to dashboard'
                                );
                                break;

                            case 'resetpassword':
                                # password reset
                                $url = base_url('client/account/password/reset/' . $client_id . '/' . $token . '/' . $phone);
                                return $this->sendResponse(
                                    [],
                                    'Success. Redirecting you to password reset!'
                                );
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
                                # login the user now
                                $this->loginClientNow($client);
                                return $this->sendResponse(
                                    'Success. Redirecting to dashboard',
                                    [
                                        'client' => $client
                                    ]
                                );
                                break;
                        }
                    } else {
                        # the token expired
                        return $this->sendError('Sorry! Your OTP code already Expired!',[
                            'error' => "codeExpired",
                        ], 200);
                    }
                } else {
                    # invalid user token
                    return $this->sendError('Wrong Code Provided. Try again with the correct Code', [
                        'error' => "wrongCode",
                    ], 200);
                }
            } else {
                # user was blocked from access this
                return $this->sendError( 'Not Authorised: You not Authorised Access to this!',[
                    'error' => 'notAuthorized',
                ], 200);
            }
        } else {
            # user account does not exist
            return $this->sendError('We couldn\'t find your account',[
                'error' => "noAccount",
            ], 200);
        }
    }

    public function clearClientSession()
    {
        $client_id = session()->get('client_id');

        $client_email_or_mobile = session()->get('client_email_or_mobile');
        # check the client session
        if (!isset($client_id) || !isset($client_email_or_mobile)) {
            # redirect the client to login screen again
            return $this->sendError('Your Verification Process has timedout!', [], 200);
        }

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
                'message' => 'Sessions cleared successfully.',
            ];

            # send the response as JSON
            header('Content-Type: application/json');
            return $this->sendResponse('Sessions cleared successfully.', $response);
        }
    }

    public function checkVerificationSession()
    {
        $client_id = session()->get('client_id');
        # $client = $this->client->find($client_id);
        $client = $this->getClientByID($client_id);
        $user_token = session()->get('user_token');

        $client_email_or_mobile = session()->get('client_email_or_mobile');
        # check the client session
        if (!isset($client_id) || !isset($client_verification_mode) || !isset($client_email_or_mobile)) {
            # redirect the client to login screen again
            return $this->sendError('Your Verification Process has timedout!', [], 200);
        }
    }
}
