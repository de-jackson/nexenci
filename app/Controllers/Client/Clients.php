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
        $data = $this->userRow;
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requested ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Retrieves particulars based on the provided module and module ID.
     *
     * @param string|null $module     The module to filter particulars by.
     * @param int|null    $module_id  The ID of the module to filter particulars by.
     *
     * @return mixed                  Returns an array of particulars if found, otherwise returns an empty array.
     *
     * @throws Exception             Throws an exception if the database operation fails.
     */
    public function get_particularsByModule($module = null, $module_id = null)
    {
        switch (strtolower($module)) {
            case 'account_type':
                $where = ['particulars.account_typeId' => $module_id, 'particulars.particular_status' => 'Active'];
                break;
            case 'category':
                $where = ['particulars.category_id' => $module_id, 'particulars.particular_status' => 'Active'];
                break;
            case 'subcategory':
                $where = ['particulars.subcategory_id' => $module_id, 'particulars.particular_status' => 'Active'];
                break;
            case 'part':
                $where = ['categories.part' => $module_id, 'particulars.account_typeId !=' => 1, 'particulars.particular_status' => 'Active'];
                break;
            case 'cashflow':
                $where =  ['particulars.cash_flow_typeId' => $module_id, 'particulars.particular_status' => 'Active'];
                break;
            default:
                $where = ['particulars.particular_status' => 'Active'];
                break;
        }
        $data = $this->particular
            ->select('particulars.*, categories.id as c_id, categories.category_name,categories.category_slug, categories.part, subcategories.id as s_id, subcategories.subcategory_name, subcategories.subcategory_status, subcategories.subcategory_slug, subcategories.created_at as screated, subcategories.updated_at as supdated, statements.name as statement, account_types.id as type_id ,account_types.name as account_type, cash_flow_types.id as cash_flow_id, cash_flow_types.name as cash_flow_type')
            ->join('categories', 'categories.id = particulars.category_id', 'left')
            ->join('subcategories', 'subcategories.id = particulars.subcategory_id', 'left')
            ->join('statements', 'statements.id = categories.statement_id', 'left')
            ->join('account_types', 'account_types.id = particulars.account_typeId', 'left')
            ->join('cash_flow_types', 'cash_flow_types.id = particulars.cash_flow_typeId', 'left')
            ->where($where)
            ->findAll();
        return $this->respond($data);
    }

    /**
     * Retrieves the client's membership or shares charge based on the provided registration date,
     * particular ID, and charge type.
     *
     * @param string $reg_date The client's registration date.
     * @param int $particular_id The ID of the particular for which the charge is being retrieved.
     * @param string $chargeType The type of charge to retrieve, either 'membership' or 'shares'.
     *
     * @return mixed An array containing the charge information if found, or an empty array if not found.
     */
    public function getClientMembershipCharge()
    {
        $reg_date = $this->request->getVar('reg_date');
        $particular_id = $this->request->getVar('particular_id');
        $chargeType = $this->request->getVar('chargeType');

        if(!empty($chargeType) && strtolower($chargeType) == 'membership')
        {
            $chargeRow = $this->vlookupCharge($particular_id, $reg_date);
        } else {
            $chargeRow = $this->vlookupSharesCharge($particular_id, $reg_date);
        }

        $data = [];
        if ($chargeRow) {
            $data[] = $chargeRow;
        }
        return $this->respond($data);
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
            $mobile = trim($this->request->getVar('mobile_full'));
            $email = trim($this->request->getVar('c_email'));
            $alternate_no = trim($this->request->getVar('alternate_mobile_full'));
            $nok_phone = trim($this->request->getVar('nok_phone_full'));
            $nok_phone_alt = trim($this->request->getVar('nok_alt_phone_full'));
            $firstName = (trim($this->request->getVar('c_name')));
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
                        $message = '<h4>Dear ' . $firstName . ',</h4>
                        <p>Thank you for registering with us. We\'re glad to have you on board. </p>';
                        $token = 'registration';
                        # send email notification
                        if (!empty($email)) {
                            $this->settings->sendMailNotify($message, $subject, $token, $password);
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

    /**
     * Fetches the client's loan history.
     *
     * @return mixed
     *  Returns an array of client's loan applications and disbursements if found.
     *  Returns an error message if no history is found.
     */
    public function fetchHistory()
    {
        // Fetch the history type from the request
        $history = $this->request->getVar('history');
        // Fetch the client's ID from the request
        $client_id = $this->request->getVar('client_id');

        // Fetch the history based on the history menu selected
        switch (strtolower($history)) {
            case 'loans':
                // Get client application history excluding the current application
                $applications = $this->loanApplication->where(['client_id' => $client_id])->findAll();
                // Get client disbursement history excluding the current disbursement
                $disbursements = $this->disbursement->where(['client_id' => $client_id])->findAll();

                // Verify that either client has got any applications or disbursements history
                if (!$applications && !$disbursements) {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'No Client ' . ucfirst($history) . ' History Found!',
                    ];
                }

                $historyArray = [];
                // $historyArray['applications'][] = null;
                // $historyArray['disbursements'][] = null;

                if ($applications && !$disbursements) {
                    if ($applications && (count($applications) > 0)) {
                        foreach ($applications as $application) {
                            $historyArray['applications'][] = $this->loanApplicationRow($application['id']);
                        }
                    }
                }

                if ($disbursements && !$applications) {
                    if ($disbursements && (count($disbursements) > 0)) {
                        foreach ($disbursements as $disbursement) {
                            $historyArray['disbursements'][] = $this->loanDisbursementRow($disbursement['id']);
                        }
                    }
                }

                if ($applications && $disbursements) {
                    if ($applications && (count($applications) > 0)) {
                        foreach ($applications as $application) {
                            $historyArray['applications'][] = $this->loanApplicationRow($application['id']);
                        }
                    }

                    if ($disbursements && (count($disbursements) > 0)) {
                        foreach ($disbursements as $disbursement) {
                            $historyArray['disbursements'][] = $this->loanDisbursementRow($disbursement['id']);
                        }
                    }
                }

                $response = $historyArray;

                break;
            default:
                $response = [
                    'status' => 404,
                    'error' => 'Invalid Option',
                    'messages' => ucfirst($history) . ' history couldn\'t be retrieved!',
                ];
                break;
        }

        return $this->respond($response);
    }
}
