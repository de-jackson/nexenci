<?php

namespace App\Controllers\Client;

use App\Controllers\Client\MainController;

use \Hermawan\DataTables\DataTable;

class Disbursements extends MainController
{
    protected $account_typeId = 3;

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Loans';
        $this->title = 'Disbursements';
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
        $data = $this->disbursement
            ->select('disbursements.*, branches.branch_name,  staffs.staff_name, staffs.signature, clients.id as client_id, clients.name, clients.account_no, clients.account_balance, clients.email, clients.mobile, clients.residence, clients.photo, clients.id_number, clients.next_of_kin_name, clients.next_of_kin_contact,clients.nok_email, clients.nok_address, clients.gender, clients.religion, clients.nationality, clients.marital_status, clients.alternate_no, clients.dob, clients.occupation, clients.job_location, clients.id_type, clients.id_expiry_date, clients.next_of_kin_relationship, clients.next_of_kin_alternate_contact, clients.account_type, clients.signature as sign, clients.id_photo_front, clients.id_photo_back, loanproducts.product_name, interest_rate, loanproducts.interest_type, loanproducts.repayment_period, loanproducts.repayment_duration, loanproducts.repayment_freq, loanapplications.application_code, loanapplications.purpose,loanapplications.overall_charges, loanapplications.total_charges, loanapplications.reduct_charges, loanapplications.security_item, loanapplications.security_info, loanapplications.est_value, loanapplications.ref_name, loanapplications.ref_address, loanapplications.ref_job, loanapplications.ref_contact, loanapplications.ref_alt_contact, loanapplications.ref_email, loanapplications.ref_relation, loanapplications.ref_name2, loanapplications.ref_address2, loanapplications.ref_job2, loanapplications.ref_contact2, loanapplications.ref_alt_contact2, loanapplications.ref_email2, loanapplications.ref_relation2')
            ->join('clients', 'clients.id = disbursements.client_id')
            ->join('staffs', 'staffs.id = disbursements.staff_id')
            ->join('branches', 'branches.id = clients.branch_id')
            ->join('loanproducts', 'loanproducts.id = disbursements.product_id')
            ->join('loanapplications', 'loanapplications.id = disbursements.application_id')
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
        }
    }

    public function viewDisbursementInfo($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
            $disbursement = $this->getLoanDisbursementById($id);
            # check the disbursement existance
            if ($disbursement) {
                $chargesIDs = unserialize($disbursement['overall_charges']);
                return view('client/loans/disbursements/view', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                    'module' => 'Loan Disbursement',
                    'disbursement' => $disbursement,
                    'charges' => $this->getApplicationChargeParticulars($chargesIDs, $disbursement['principal']),
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Disbursement could have been deleted or there might be a problem with your URL.');
            }
        } else {
            # Not Authorized
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('client/dashboard'));
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
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        
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
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
            $data = $this->disbursement->find($id);
            if ($data) {
                $delete = $this->disbursement->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', ' . $data['disbursement_code']),
                        'module' => strtolower('disbursements'),
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record deleted successfully',
                        ];
                        return $this->respond($response);
                        exit;
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record deleted successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Delete Failed',
                        'messages' => 'Deleting ' . $this->title . ' record failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'The requsted ' . $this->title . ' resource could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' record!',
            ];
            return $this->respond($response);
            exit;
        }
    }
}
