<?php

namespace App\Controllers\Client;

use App\Controllers\Client\MainController;

class Register extends MainController
{
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Client Account Registration';
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index(): string
    {
        return view('client/auth/register', [
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

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
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
        $this->clientValidation("registration");
        $password = trim($this->request->getVar('password'));
        # generate unique random token
        $token = $this->settings->generateRandomNumbers(6);
        # set duration for verification token to expire in minutes
        $duration = '15';
        $data = [
            'staff_id' => 1,
            'branch_id' => 1,
            'account_id' => 1,
            'account' => 'Pending',
            'reg_date' => date('Y-m-d'),
            'name' => trim($this->request->getVar('name')),
            'mobile' => trim(preg_replace('/^0/', '+256', $this->request->getVar('phone_full'))),
            'token' => password_hash($token, PASSWORD_DEFAULT),
            'token_expire_date' => date("Y-m-d H:i:s", strtotime("+" . $duration . " minutes")),
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];
        # create new user information
        $client_id = $this->client->insert($data);
        # check the client save status
        if ($client_id) {
            $mobile = trim(preg_replace('/^0/', '+256', $this->request->getVar('phone_full')));
            # used email address
            $client_email_or_mobile = $this->maskPhoneNumber($mobile);
            $client_verification_mode = 'phone';
            # create the user activity logs
            $activityData = [
                'client_id' => $client_id,
                'action' => 'create',
                'description' => ucfirst('created ' . $this->title . ', ' . $data['name']),
                'module' => 'Clients',
                'referrer_id' => $client_id,
            ];
            # save the client activity
            $user_activity_id = $this->insertActivityLog($activityData);
            # create the session data to store client information
            $clientSessionData = [
                'client_id' => $client_id,
                'client_verification_mode' => $client_verification_mode,
                'client_email_or_mobile' => $client_email_or_mobile,
                'account' => 'registration'
            ];
            $this->session->set($clientSessionData);

            $response = [
                'status' => 200,
                'error' => null,
                'url' => base_url('client/account/verify'),
                'messages' => "Client Account Registration Successfully"
            ];
            return $this->respond($response);
            exit();
        } else {
            $response = [
                'status' => 500,
                'error' => 'registrationFailed',
                'messages' => "Client could not be registered at the moment, please try again later"
            ];
            return $this->respond($response);
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
}
