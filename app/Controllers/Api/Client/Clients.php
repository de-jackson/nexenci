<?php

namespace App\Controllers\Client;

use App\Controllers\Client\MainController;

use \Hermawan\DataTables\DataTable;

\Config\Services::email();

class Clients extends MainController
{
    protected $account_typeId = 12; # Savings

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Clients';
        $this->title = 'Clients';
        $this->menuItem = [
            'title' => $this->title,
            'menu' => $this->menu,
        ];
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->client
            ->select('clients.id, clients.name, clients.branch_id, clients.staff_id, clients.account_no, clients.account_type, clients.account_balance, clients.email, clients.mobile, clients.alternate_no, clients.gender, clients.dob, clients.marital_status, clients.religion, clients.nationality, clients.occupation, clients.job_location, clients.residence, clients.id_type, clients.id_number, clients.id_expiry_date, clients.next_of_kin_name, clients.next_of_kin_relationship, clients.next_of_kin_contact, clients.next_of_kin_alternate_contact, clients.nok_email, clients.nok_address, clients.photo, clients.id_photo_front, clients.id_photo_back, clients.signature, clients.access_status, clients.reg_date,clients.created_at, clients.updated_at, branches.branch_name, staffs.staff_name, staffs.staffID')
            ->join('branches', 'branches.id = clients.branch_id')
            ->join('staffs', 'staffs.id = clients.staff_id')
            ->find($id);
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requsted ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
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
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $this->clientValidation('client', 'update');
            $client_id = $this->request->getVar('id');
            $reg_date = trim($this->request->getVar('reg_date'));
            $acc_number = $this->settings->generateUniqueNo('client', $reg_date);
            $password = $this->settings->generateRandomNumbers(8);
            $mobile = trim($this->request->getVar('c_mobile'));
            $email = trim($this->request->getVar('c_email'));
            $alternate_no = trim($this->request->getVar('alternate_no'));
            $nok_phone = trim($this->request->getVar('nok_phone'));
            $nok_phone_alt = trim($this->request->getVar('nok_alt_phone'));
            $data = [
                'name' => trim($this->request->getVar('c_name')),
                'account' => 'Approved',
                'account_type' => "Client",
                'account_no' => $acc_number,
                'mobile' => preg_replace('/^0/', '+256', $mobile),
                'email' => $email,
                'alternate_no' => preg_replace('/^0/', '+256', $alternate_no),
                'gender' => trim($this->request->getVar('gender')),
                'dob' => trim($this->request->getVar('dob')),
                'reg_date' => trim($this->request->getVar('reg_date')),
                'marital_status' => trim($this->request->getVar('marital_status')),
                'religion' => trim($this->request->getVar('religion')),
                'nationality' => trim($this->request->getVar('nationality')),
                'staff_id' => $this->userRow['staff_id'],
                'occupation' => trim($this->request->getVar('occupation')),
                'job_location' => trim($this->request->getVar('job_location')),
                'residence' => trim($this->request->getVar('residence')),
                'id_type' => trim($this->request->getVar('id_type')),
                'id_number' => trim(strtoupper($this->request->getVar('id_number'))),
                'id_expiry_date' => trim($this->request->getVar('id_expiry')),
                'next_of_kin_name' => trim($this->request->getVar('next_of_kin')),
                'next_of_kin_relationship' => trim($this->request->getVar('nok_relationship')),
                'next_of_kin_contact' => preg_replace('/^0/', '+256', $nok_phone),
                'next_of_kin_alternate_contact' => preg_replace('/^0/', '+256', $nok_phone_alt),
                'nok_email' => trim($this->request->getVar('nok_email')),
                'nok_address' => trim($this->request->getVar('nok_address')),
                'branch_id' => trim($this->request->getVar('branch_id')),
                'staff_id' => trim($this->request->getVar('staff_id'))
            ];
            # update the attachment if uploaded
            if (!empty($_FILES['photo']['name'])) {
                $upload = $this->settings->_doUploadPhoto("client",  $this->request->getFile('photo'));
                $data['photo'] = $upload;
            }
            if (!empty($_FILES['id_photo_front']['name'])) {
                $idFront = $this->settings->_doUploadIdPhoto("client", 'front',  $this->request->getFile('id_photo_front'));
                $data['id_photo_front'] = $idFront;
            }
            if (!empty($_FILES['id_photo_back']['name'])) {
                $idBack = $this->settings->_doUploadIdPhoto("client", 'back',  $this->request->getFile('id_photo_back'));
                $data['id_photo_back'] = $idBack;
            }
            if (!empty($_FILES['signature']['name'])) {
                $sign = $this->settings->_doUploadSignature("client",  $this->request->getFile('signature'));
                $data['signature'] = $sign;
            }
            # update client token information
            $update = $this->client->update($client_id, $data);

            if ($update) {
                # create the client activity in the system
                $activityData = [
                    'client_id' => $this->userRow['id'],
                    'action' => 'update',
                    'description' => '',
                    'module' => $this->menu,
                    'referrer_id' => $update,
                ];
                # save the client activity
                $activity = $this->insertActivityLog($activityData);
                # check the client activity status
                if ($activity) {
                    # check the internet connection status
                    $checkInternet = $this->settings->checkNetworkConnection();
                    if ($checkInternet) {
                        $subject = $this->title . " Registration";
                        $message = $data;
                        $token = 'registration';
                        # send email notification
                        if (!empty($email)) {
                            $this->settings->sendMail($message, $subject, $token, $password);
                        }
                    }
                } 

                $response = [
                    'status' => 200,
                    'error' => null,
                    'messages' => "Your Account has been Setup Successfully."
                ];
                return $this->respond($response);
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Account Failed',
                    'messages' => "Your Account couldn't be setup at the moment. Try again later!",
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => "You are not authorized to setup your account at the moment!",
            ];
            return $this->respond($response);
            exit;
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
