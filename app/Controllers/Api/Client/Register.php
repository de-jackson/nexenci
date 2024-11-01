<?php

namespace App\Controllers\Api\Client;

use App\Controllers\Api\Client\MainController;

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
    public function index()
    {
        return $this->sendResponse([], 'You are required to register first');
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        return $this->sendError('You\'re not Authorized to perform this.', [], 401);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        return $this->sendError('You\'re not Authorized to perform this.', [], 401);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $rules = [
            'name' => 'required|min_length[6]|max_length[50]|regex_match[/^[-a-zA-Z ]+$/]',
            'phone' => 'required|min_length[10]|max_length[15]|regex_match[/^[0-9*#+]+$/]|is_unique[clients.mobile]',
            'password' => 'required|min_length[8]|max_length[15]',
            'confirm_password' => 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            return $this->sendError('Validation Error.', $this->validator->getErrors(), 200);
        }

        $phone = trim(preg_replace('/^0/', '+256', $this->request->getVar('phone')));
        $countPhones = $this->client->where(['mobile' => $phone])->countAllResults();
        if ($countPhones) {
            # code...
            return $this->sendError('Validation Error.', [
                'phone' => $this->request->getVar('phone') . ' already added!'
            ], 200);
        }


        $password = trim($this->request->getVar('password'));
        # generate unique random token
        $token = $this->settings->generateRandomNumbers(6);
        # set duration for vertification token to expire in minutes
        $duration = '15';
        $data = [
            'staff_id' => 1,
            'branch_id' => 1,
            'account' => 'Pending',
            'reg_date' => date('Y-m-d'),
            'name' => trim($this->request->getVar('name')),
            'mobile' => trim(preg_replace('/^0/', '+256', $this->request->getVar('phone'))),
            'token' => password_hash($token, PASSWORD_DEFAULT),
            'token_expire_date' => date("Y-m-d H:i:s", strtotime("+" . $duration . " minutes")),
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];
        # create new user information
        $client_id = $this->client->insert($data);
        # check the client save status
        if ($client_id) {
            $mobile = trim(preg_replace('/^0/', '+256', $this->request->getVar('phone')));
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
                # 'error' => null,
                # 'url' => base_url('api/client/account/verify'),
            ];
            return $this->sendResponse($response, 'Client Account Registration Successfully');
        } else {
            return $this->sendError('Client could not be registered at the moment, please try again later', [
                'error' => 'registrationFailed'
            ]);
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
        return $this->sendError('You\'re not Authorized to perform this with id ' . $id, [], 401);
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
