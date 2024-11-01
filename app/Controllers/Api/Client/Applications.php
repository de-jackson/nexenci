<?php

namespace App\Controllers\Api\Client;

use Hermawan\DataTables\DataTable;
use App\Controllers\Api\Client\MainController;


class Applications extends MainController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Loans';
        $this->title = 'Applications';
        $this->subTitle1 = 'Disbursements';
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
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {

            return view('client/loans/applications/create', [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'relations' => $this->settings->generateRelationships(),
                'charges' => $this->getApplicationChargeParticulars(),
                'userMenu' => $this->load_menu(),

            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('client/dashboard'));
        }
    }

    public function store($mode)
    {
        $rules = [
            'name' => 'required|min_length[6]|max_length[50]|regex_match[/^[-a-zA-Z ]+$/]',
            'client_id' => 'required|min_length[1]|max_length[15]|regex_match[/^[0-9*#+]+$/]',
            'password' => 'required|min_length[8]|max_length[15]',
            'confirm_password' => 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            return $this->sendError('Validation Error.', $this->validator->getErrors(), 200);
        }

        if ($this->checkPermissions($this->userPermissions, $this->menuItem, strtolower($mode))) {
            $action = ($mode == 'create') ? "add" : "update";
            $this->clientValidation("application", $action);
            $client_id = trim($this->request->getVar('client_id'));
            # $client = $this->client->find($client_id);
            $clientRow = $this->getClientByID($client_id);
            $application_id = trim($this->request->getVar('application_id'));
            if ($clientRow) {
                $product_id = trim($this->request->getVar('product_id'));
                $productRow = $this->loanProduct->find($product_id);
                if ($productRow) {
                    $principal = trim($this->request->getVar('principal'));
                    $particulars = unserialize($productRow['product_charges']);
                    # Get Applicantion charges
                    $charges = $this->report->getLoanProductCharges($particulars, $principal);
                    # Create applicant loan product in a multidimensional array
                    $applicantProduct = [
                        'ProductID' => trim($productRow['id']),
                        'InterestRate' => trim($productRow['interest_rate']),
                        'InterestPeriod' => trim($productRow['interest_period']),
                        'InterestType' => trim($productRow['interest_type']),
                        'LoanFrequency' => trim($productRow['repayment_freq']),
                        'RepaymentPeriod' => trim($productRow['repayment_period']),
                    ];

                    # Serialize the multidimensional array
                    $applicant_products = serialize($applicantProduct);

                    $data = [
                        'application_code' => $this->settings->generateUniqueNo('application'),
                        'application_date' => trim($this->request->getVar('application_date')),
                        'client_id' => $client_id,
                        'staff_id' => $this->userRow['staff_id'],
                        'product_id' => $product_id,
                        'branch_id' => $this->userRow['branch_id'],
                        'principal' => $principal,
                        'purpose' => trim($this->request->getVar('purpose')),
                        'overall_charges' => serialize($particulars),
                        'total_charges' => $charges['totalCharges'],
                        'applicant_products' => $applicant_products,
                        # 'reduct_charges' => trim($this->request->getVar('reduct_charges')),
                        'security_item' => trim($this->request->getVar('security_item')),
                        'security_info' => trim($this->request->getVar('security_info')),
                        'est_value' => trim($this->request->getVar('est_value')),
                        'ref_name' => trim($this->request->getVar('ref_name')),
                        'ref_relation' => trim($this->request->getVar('ref_relation')),
                        'ref_job' => trim($this->request->getVar('ref_job')),
                        'ref_contact' => preg_replace('/^0/', '+256', $this->request->getVar('ref_contact')),
                        'ref_alt_contact' => preg_replace('/^0/', '+256', $this->request->getVar('ref_alt_contact')),
                        'ref_email' => trim($this->request->getVar('ref_email')),
                        'ref_address' => trim($this->request->getVar('ref_address')),
                        'ref_name2' => trim($this->request->getVar('ref_name2')),
                        'ref_relation2' => trim($this->request->getVar('ref_relation2')),
                        'ref_job2' => trim($this->request->getVar('ref_job2')),
                        'ref_contact2' => preg_replace('/^0/', '+256', $this->request->getVar('ref_contact2')),
                        'ref_alt_contact2' => preg_replace('/^0/', '+256', $this->request->getVar('ref_alt_contact2')),
                        'ref_email2' => trim($this->request->getVar('ref_email2')),
                        'ref_address2' => trim($this->request->getVar('ref_address2')),

                        'net_salary' => trim($this->request->getVar('net_salary')),
                        'farming' => trim($this->request->getVar('farming')),
                        'business' => trim($this->request->getVar('business')),
                        'others' => trim($this->request->getVar('others')),
                        'rent' => trim($this->request->getVar('rent')),
                        'education' => trim($this->request->getVar('education')),
                        'medical' => trim($this->request->getVar('medical')),
                        'transport' => trim($this->request->getVar('transport')),
                        'exp_others' => trim($this->request->getVar('exp_others')),
                        'difference' => trim($this->request->getVar('difference')),
                        'dif_status' => trim($this->request->getVar('dif_status')),
                        'institute_name' => trim($this->request->getVar('institute_name')),
                        'institute_branch' => trim($this->request->getVar('institute_branch')),
                        'account_type' => $this->request->getVar('account_type'),
                        'institute_name2' => trim($this->request->getVar('institute_name2')),
                        'institute_branch2' => trim($this->request->getVar('institute_branch2')),
                        'account_type2' => $this->request->getVar('account_type2'),
                        'amt_advance' => trim($this->request->getVar('amt_advance')),
                        'date_advance' => trim($this->request->getVar('date_advance')),
                        'loan_duration' => trim($this->request->getVar('loan_duration')),
                        'amt_outstanding' => trim($this->request->getVar('amt_outstanding')),
                        'amt_advance2' => trim($this->request->getVar('amt_advance2')),
                        'date_advance2' => trim($this->request->getVar('date_advance2')),
                        'loan_duration2' => trim($this->request->getVar('loan_duration2')),
                        'amt_outstanding2' => trim($this->request->getVar('amt_outstanding2')),
                    ];

                    if ($application_id) {
                        $application_id = $this->loanApplication->update($application_id, $data);
                    } else {
                        $application_id = $this->loanApplication->insert($data);
                    }

                    if ($application_id) {
                        $activityData = [
                            'client_id' => $this->userRow['id'],
                            'action' => $mode,
                            'description' => ucfirst($mode . ' ' . ' ' . $this->title . ' record(s)'),
                            'module' => strtolower('applications'),
                            'referrer_id' => $application_id,
                        ];
                        if ($mode == 'create') {
                            # collateral or security uploaded files
                            $this->clientApplicationFiles($application_id, 'collateral');
                            # income uploaded files
                            $this->clientApplicationFiles($application_id, 'income');
                            # expense receipt uploaded files
                            $this->clientApplicationFiles($application_id, 'expense');
                            # send mail notification
                            if (!empty($clientRow['email'])) {
                                $data['name'] = $clientRow['name'];
                                $data['email'] = $clientRow['email'];
                                $data['account_type'] = $clientRow['account_type'];
                                $data['branch_name'] = $this->userRow['branch_name'];
                                $data['product_name'] = $productRow['product_name'];
                                $data['frequency'] = $productRow['repayment_freq'];
                                $data['rate'] = $productRow['interest_rate'];
                                $data['period'] = $productRow['repayment_period'] . ' ' . $productRow['repayment_duration'];
                                $data['module'] = 'apply';
                                $data['date'] = date('d-m-Y H:i:s');
                                $checkInternet = $this->settings->checkNetworkConnection();
                                if ($checkInternet) {
                                    $subject = "Loan Application";
                                    $message = $data;
                                    $token = 'application';
                                    $this->settings->sendMail($message, $subject, $token);
                                    $response = [
                                        'status' => 200,
                                        'error' => null,
                                        'messages' => $this->title . ' record(s) ' . $mode . ' successfully. Email Sent'
                                    ];
                                    return $this->respond($response);
                                    exit;
                                } else {
                                    $response = [
                                        'status' => 200,
                                        'error' => null,
                                        'messages' => $this->title . ' record(s) ' . $mode . ' successfully.  No Internet'
                                    ];
                                    return $this->respond($response);
                                    exit;
                                }
                            } else {
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => $this->title . ' record(s) ' . $mode . ' successfully',
                                ];
                                return $this->respond($response);
                                exit;
                            }
                            // insert into activity logs
                            $activity = $this->insertActivityLog($activityData);
                        } else {
                            $activity = $this->insertActivityLog($activityData);
                            if ($activity) {
                                $response = [
                                    'status'   => 200,
                                    'error'    => null,
                                    'messages' => $this->title . ' record(s) ' . $mode . ' successfully',
                                ];
                                return $this->respond($response);
                                exit;
                            } else {
                                $response = [
                                    'status'   => 200,
                                    'error'    => null,
                                    'messages' => $this->title . ' record(s) ' . $mode . ' successfully.'
                                ];
                                return $this->respond($response);
                                exit;
                            }
                        }
                    } else {
                        $response = [
                            'status' => 500,
                            'error' => ucwords($mode) . ' Failed',
                            'messages' => ucfirst($mode) . ' ' . $this->title . ' record(s) failed, try again later!',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Loan Product could not be found!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'Client Information could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to ' . $mode . ' ' . $this->title . ' record(s)!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->loanApplication
            ->select('loanapplications.*, clients.id as c_id, clients.name, clients.account_no, clients.email, clients.mobile, clients.residence, clients.photo, clients.id_number, clients.next_of_kin_name, clients.next_of_kin_contact, clients.gender, clients.religion, clients.marital_status, clients.nationality, clients.alternate_no, clients.dob, clients.occupation, clients.job_location, clients.id_type, clients.id_expiry_date, clients.next_of_kin_relationship, clients.next_of_kin_alternate_contact, clients.nok_email, clients.nok_address, clients.account_type as acc_type,, clients.signature as sign, clients.id_photo_front, clients.id_photo_back, loanproducts.product_name, loanproducts.interest_rate, loanproducts.interest_type, loanproducts.repayment_period, loanproducts.repayment_duration, loanproducts.repayment_freq, branches.branch_name, staffs.staff_name, staffs.staffID')
            ->join('clients', 'clients.id = loanapplications.client_id')
            ->join('staffs', 'staffs.id = loanapplications.staff_id')
            ->join('loanproducts', 'loanproducts.id = loanapplications.product_id')
            ->join('branches', 'branches.id = clients.branch_id')
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

    public function cancelLoanApplication($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            $applicationRow = $this->getLoanApplicationById($id);
            if ($applicationRow) {
                $clientRow  = $this->client->find($applicationRow['client_id']);
                if ($clientRow) {
                    $productRow = $this->loanProduct->find($applicationRow['product_id']);
                    if ($productRow) {
                        if (strtolower($applicationRow['status']) == 'cancelled') {
                            $response = [
                                'status' => 500,
                                'error' => 'Application Cancelled!',
                                'messages' => 'Application is already Cancelled!',
                            ];
                            return $this->respond($response);
                            exit;
                        } else {
                            $applicationStatus = [
                                'status' => 'Cancelled',
                            ];
                            $update = $this->loanApplication->update($id, $applicationStatus);
                            if ($update) {
                                // insert into activity logs
                                $activityData = [
                                    'client_id' => $this->userRow['id'],
                                    'action' => 'update',
                                    'description' => ucfirst('updated ' . $this->title . ', status: Cancelled, application: ' . $applicationRow['application_code']),
                                    'module' => strtolower('applications'),
                                    'referrer_id' => $id,
                                ];
                                $activity = $this->insertActivityLog($activityData);
                                if ($activity) {
                                    # send mail notification
                                    if ($clientRow['email'] != '') {
                                        $applicationRow['branch_name'] = $this->userRow['branch_name'];
                                        $applicationRow['module'] = 'processing';
                                        $applicationRow['date'] = date('d-m-Y H:i:s');
                                        $checkInternet = $this->settings->checkNetworkConnection();
                                        if ($checkInternet) {
                                            $subject = "Loan Application";
                                            $message = $applicationRow;
                                            $token = 'application';
                                            $this->settings->sendMail($message, $subject, $token);
                                            $response = [
                                                'status' => 200,
                                                'error' => null,
                                                'messages' => "Application Cancelled successfully. Email Sent"
                                            ];
                                            return $this->respond($response);
                                            exit;
                                        } else {
                                            $response = [
                                                'status' => 200,
                                                'error' => null,
                                                'messages' => "Application Cancelled successfully. No Internet"
                                            ];
                                            return $this->respond($response);
                                            exit;
                                        }
                                    } else {
                                        $response = [
                                            'status'   => 200,
                                            'error'    => null,
                                            'messages' => "Application Cancelled successfully"
                                        ];
                                        return $this->respond($response);
                                        exit;
                                    }
                                } else {
                                    $response = [
                                        'status'   => 200,
                                        'error'    => null,
                                        'messages' => 'Application Cancelled successfully. loggingFailed'
                                    ];
                                    return $this->respond($response);
                                    exit;
                                }
                            } else {
                                $response = [
                                    'status' => 500,
                                    'error' => 'Update Failed',
                                    'messages' => 'Cancelling application failed, try again later!',
                                ];
                                return $this->respond($response);
                                exit;
                            }
                        }
                    } else {
                        $response = [
                            'status'   => 404,
                            'error'    => 'Not Found',
                            'messages' => 'Product record not found',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status'   => 404,
                        'error'    => 'Not Found',
                        'messages' => 'Client record not found',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'Application record not found',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to update ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    public function view_applicant($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
            $application = $this->getLoanApplicationById($id);
            if ($application) {
                return view('client/loans/applications/view', [
                    'title' => $this->title,
                    'application' => $application,
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                    'relations' => $this->settings->generateRelationships(),
                    'charges' => $this->getApplicationChargeParticulars(),
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Application could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('/admin/dashboard'));
        }
    }

    public function getApplicationFilesAttachments($app_id = null, $type = null)
    {
        if (strtolower($type) == 'collateral') {
            $where = [
                'application_id' => $app_id,
                'type' => 'collateral', 'deleted_at' => null,
                'client_id' => $this->userRow['id']
            ];
        } else if (strtolower($type) == 'income') {
            $where = [
                'application_id' => $app_id, 'type' => 'income', 'deleted_at' => null,
                'client_id' => $this->userRow['id']
            ];
        } else if (strtolower($type) == 'expense') {
            $where = [
                'application_id' => $app_id, 'type' => 'expense', 'deleted_at' => null,
                'client_id' => $this->userRow['id']
            ];
        } else if (strtolower($type) == 'files') {
            $where = [
                'application_id' => $app_id, 'type !=' => 'collateral', 'deleted_at' => null,
                'client_id' => $this->userRow['id']
            ];
        } else {
            $where = [
                'application_id' => $app_id, 'deleted_at' => null,
                'client_id' => $this->userRow['id']
            ];
        }
        $files = $this->file->select('application_id, file_name, type, id')
            ->where($where);
        return DataTable::of($files)
            ->add('checkbox', function ($file) {
                return '<div class=""><input type="checkbox" class="data-check" value="' . $file->id . '"></div>';
            })
            ->addNumbering("no")
            ->add('extension', function ($file) {
                return substr(strrchr($file->file_name, '.'), 1);
            })
            ->add('photo', function ($file) {
                # check whether the photo exist
                if (file_exists("uploads/applications/collaterals/" . $file->file_name) && $file->file_name) {
                    # display the current photo
                    return '
                    <a href="javascript:void(0)" onclick="view_file(' . "'" . $file->id . "'" . ')"><img src="' . base_url('uploads/applications/collaterals/' . $file->file_name) . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                } elseif (file_exists("uploads/applications/income/" . $file->file_name) && $file->file_name) {
                    # display the current photo
                    return '
                    <a href="javascript:void(0)" onclick="view_file(' . "'" . $file->id . "'" . ')"><img src="' . base_url('uploads/applications/income/' . $file->file_name) . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                } elseif (file_exists("uploads/applications/expense/" . $file->file_name) && $file->file_name) {
                    # display the current photo
                    return '
                    <a href="javascript:void(0)" onclick="view_file(' . "'" . $file->id . "'" . ')"><img src="' . base_url('uploads/applications/expense/' . $file->file_name) . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                } else {
                    # display the default photo
                    return '
                    <a href="javascript:void(0)" onclick="view_file(' . "'" . $file->id . "'" . ')"><img src="' . base_url('assets/dist/img/nophoto.jpg') . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                }
            })
            ->add('action', function ($file) {
                $text = "info";
                return '<div class="btn-group dropend my-1">
                <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu">
                        <a href="javascript:void(0)" onclick="view_file(' . "'" . $file->id . "'" . ')" title="view file" class="dropdown-item"><i class="fas fa-eye text-success"></i> View File</a>
                        <div class="dropdown-divider"></div>
                        <a href="' . base_url('admin/loan/application/files/download/' . $file->id) . '" title="download file" class="dropdown-item"><i class="fas fa-download text-info"></i> Download File</a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="delete_file(' . "'" . $file->id . "'" . ',' . "'" . $file->file_name . "'" . ')" title="delete file" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete File</a>
                        </ul>
                </div>';
            })
            ->toJson(true);
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
        $mode = $this->request->getVar('mode');
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, strtolower($mode))) {
            if (strtolower($mode) == 'create') {
                $this->_validateApplication("add");
                $client_id = trim($this->request->getVar('client_id'));
                $clientRow = $this->client->find($client_id); // client data
                // get loan application payables

                if ($clientRow) {
                    $product_id = trim($this->request->getVar('product_id'));
                    $productRow = $this->loanProduct->find($product_id);
                    if ($productRow) {
                        $principal = trim($this->request->getVar('principal'));
                        // application charges
                        $charges = $this->getApplicationChargeParticulars();
                        // application data
                        $data = [
                            'application_code' => $this->settings->generateUniqueNo('application'),
                            'application_date' => trim($this->request->getVar('application_date')),
                            'client_id' => $client_id,
                            'staff_id' => $this->userRow['staff_id'],
                            'product_id' => $product_id,
                            'branch_id' => $this->userRow['branch_id'],
                            'principal' => $principal,
                            'purpose' => trim($this->request->getVar('purpose')),
                            'overall_charges' => serialize($charges['particularIDs']),
                            'total_charges' => trim($this->request->getVar('total_charges')),
                            'reduct_charges' => trim($this->request->getVar('reduct_charges')),

                            'security_item' => trim($this->request->getVar('security_item')),
                            'security_info' => trim($this->request->getVar('security_info')),
                            'est_value' => trim($this->request->getVar('est_value')),
                            'ref_name' => trim($this->request->getVar('ref_name')),
                            'ref_relation' => trim($this->request->getVar('ref_relation')),
                            'ref_job' => trim($this->request->getVar('ref_job')),
                            'ref_contact' => preg_replace('/^0/', '+256', $this->request->getVar('ref_contact')),
                            'ref_alt_contact' => preg_replace('/^0/', '+256', $this->request->getVar('ref_alt_contact')),
                            'ref_email' => trim($this->request->getVar('ref_email')),
                            'ref_address' => trim($this->request->getVar('ref_address')),
                            'ref_name2' => trim($this->request->getVar('ref_name2')),
                            'ref_relation2' => trim($this->request->getVar('ref_relation2')),
                            'ref_job2' => trim($this->request->getVar('ref_job2')),
                            'ref_contact2' => preg_replace('/^0/', '+256', $this->request->getVar('ref_contact2')),
                            'ref_alt_contact2' => preg_replace('/^0/', '+256', $this->request->getVar('ref_alt_contact2')),
                            'ref_email2' => trim($this->request->getVar('ref_email2')),
                            'ref_address2' => trim($this->request->getVar('ref_address2')),

                            'net_salary' => trim($this->request->getVar('net_salary')),
                            'farming' => trim($this->request->getVar('farming')),
                            'business' => trim($this->request->getVar('business')),
                            'others' => trim($this->request->getVar('others')),
                            'rent' => trim($this->request->getVar('rent')),
                            'education' => trim($this->request->getVar('education')),
                            'medical' => trim($this->request->getVar('medical')),
                            'transport' => trim($this->request->getVar('transport')),
                            'exp_others' => trim($this->request->getVar('exp_others')),
                            'difference' => trim($this->request->getVar('difference')),
                            'dif_status' => trim($this->request->getVar('dif_status')),
                            'institute_name' => trim($this->request->getVar('institute_name')),
                            'institute_branch' => trim($this->request->getVar('institute_branch')),
                            'account_type' => $this->request->getVar('account_type'),
                            'institute_name2' => trim($this->request->getVar('institute_name2')),
                            'institute_branch2' => trim($this->request->getVar('institute_branch2')),
                            'account_type2' => $this->request->getVar('account_type2'),
                            'amt_advance' => trim($this->request->getVar('amt_advance')),
                            'date_advance' => trim($this->request->getVar('date_advance')),
                            'loan_duration' => trim($this->request->getVar('loan_duration')),
                            'amt_outstanding' => trim($this->request->getVar('amt_outstanding')),
                            'amt_advance2' => trim($this->request->getVar('amt_advance2')),
                            'date_advance2' => trim($this->request->getVar('date_advance2')),
                            'loan_duration2' => trim($this->request->getVar('loan_duration2')),
                            'amt_outstanding2' => trim($this->request->getVar('amt_outstanding2')),
                        ];
                        $insert = $this->loanApplication->insert($data);
                        $index = 1;
                    } else {
                        $response = [
                            'status' => 404,
                            'error' => 'Not Found',
                            'messages' => 'Product data could not be found!',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Client data could not be found!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                if (!empty($_FILES['file']['name'])  && !empty($this->request->getVar('branch_id')) && !empty($this->request->getVar('loan_product_id'))) {
                    # get uploaded file extension
                    $path_parts = pathinfo($_FILES["file"]["name"]);
                    $ext = $path_parts['extension'];
                    # check whether the uploaded file extension matches with csv
                    if ($ext == 'csv') {
                        $file = $this->request->getFile("file");
                        $file_name = $file->getTempName();
                        $file_data = array_map('str_getcsv', file($file_name));
                        if (count($file_data) > 0) {
                            $index = 0;
                            $data = [];
                            foreach ($file_data as $key => $column) {

                                $index++;

                                # ignore the column headers
                                if ($index < 2) {
                                    continue;
                                }

                                # ignore empty row in excel sheets
                                if ((string) $column[0] != '0' && empty($column[0])) {
                                    continue;
                                }

                                # get the client information
                                $client = $this->client->where([
                                    'account_no' => trim($column[0])
                                ])->find();

                                if ($client) {
                                    $client_id = $client['id'];
                                } else {
                                    continue;
                                }

                                # generate application code for each applicant
                                $applicate_date = trim($column[1]);
                                $application_code = $this->settings->generateUniqueNo('application', $applicate_date);

                                $incomes = ((float)trim($column[26]) + (float)trim($column[27]) + (float)trim($column[28]) + (float)trim($column[29]));
                                $expenses = ((float)trim($column[30]) + (float)trim($column[31]) + (float)trim($column[32]) + (float)trim($column[33]) + (float)trim($column[34]));

                                $dif = ($incomes - $expenses);

                                if ($dif > 0) {
                                    $dif_stat = "Surplus";
                                } elseif ($dif == 0) {
                                    $dif_stat = "Balanced";
                                } else {
                                    $dif_stat = "Deficit";
                                }

                                $data = [
                                    'application_code' => $application_code,
                                    'staff_id' => $this->userRow['staff_id'],
                                    'branch_id' => trim($this->request->getVar('branch_id')),
                                    'product_id' => trim($this->request->getVar('loan_product_id')),

                                    'client_id' => $client_id,
                                    'applicate_date' => trim($column[1]),
                                    'principal' => trim($column[2]),
                                    'purpose' => trim($column[3]),
                                    'status' => trim($column[4]),
                                    'level' => trim($column[5]),
                                    'action' => trim($column[6]),
                                    'total_charges' => trim($column[7]),
                                    'reduct_charges' => trim($column[8]),
                                    'security_item' => trim($column[9]),
                                    'security_info' => trim($column[10]),
                                    'est_value' => trim($column[11]),
                                    'ref_name' => trim($column[12]),
                                    'ref_address' => trim($column[13]),
                                    'ref_job' => trim($column[14]),
                                    'ref_contact' => trim($column[15]),
                                    'ref_alt_contact' => trim($column[16]),
                                    'ref_email' => trim($column[17]),
                                    'ref_relation' => trim($column[18]),
                                    'ref_name2' => trim($column[19]),
                                    'ref_address2' => trim($column[20]),
                                    'ref_job2' => trim($column[21]),
                                    'ref_contact2' => trim($column[22]),
                                    'ref_alt_contact2' => trim($column[23]),
                                    'ref_email2' => trim($column[24]),
                                    'ref_relation2' => trim($column[25]),
                                    'net_salary' => trim($column[26]),
                                    'farming' => trim($column[27]),
                                    'business' => trim($column[28]),
                                    'others' => trim($column[29]),
                                    'rent' => trim($column[30]),
                                    'education' => trim($column[31]),
                                    'medical' => trim($column[32]),
                                    'transport' => trim($column[33]),
                                    'exp_others' => trim($column[34]),
                                    'difference' => $dif,
                                    'dif_status' => $dif_stat,
                                    'institute_name' => trim($column[35]),
                                    'institute_branch' => trim($column[36]),
                                    'account_type' => trim($column[37]),
                                    'institute_name2' => trim($column[38]),
                                    'institute_branch2' => trim($column[39]),
                                    'account_type2' => trim($column[40]),
                                    'amt_advance' => trim($column[41]),
                                    'date_advance' => trim($column[42]),
                                    'loan_duration' => trim($column[43]),
                                    'amt_outstanding' => trim($column[44]),
                                    'amt_advance2' => trim($column[45]),
                                    'date_advance2' => trim($column[46]),
                                    'loan_duration2' => trim($column[47]),
                                    'amt_outstanding2' => trim($column[48]),
                                ];

                                # save the applicant application information
                                $insert = $this->loanApplication->insert($data);
                            }
                            # insert imported data
                            # $insert = $this->loanApplication->insertBatch($data);
                        }
                    } else {
                        # mismatch of the uploaded file type
                        $data['inputerror'][] = 'file';
                        $data['error_string'][] = 'Upload Error: The filetype you are attempting to upload is not allowed.';
                        $data['status'] = FALSE;
                        echo json_encode($data);
                        exit();
                    }
                } else {
                    # validation
                    $data = array();
                    $data['error_string'] = array();
                    $data['inputerror'] = array();
                    $data['status'] = TRUE;

                    if (empty(trim($this->request->getVar('loan_product_id')))) {
                        $data['inputerror'][] = 'loan_product_id';
                        $data['error_string'][] = 'Loan Product is required!';
                        $data['status'] = FALSE;
                    }

                    if (empty(trim($this->request->getVar('branch_id')))) {
                        $data['inputerror'][] = 'branch_id';
                        $data['error_string'][] = 'Branch is required!';
                        $data['status'] = FALSE;
                    }

                    if (empty($_FILES['file']['name'])) {
                        # Please browse for the file to be uploaded
                        $data['inputerror'][] = 'file';
                        $data['error_string'][] = 'Upload Error: CSV File is required!';
                        $data['status'] = FALSE;
                    }

                    if ($data['status'] === FALSE) {
                        echo json_encode($data);
                        exit;
                    }
                }
            }
            if ($insert) {
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => $mode,
                    'description' => ucfirst($mode . ' ' . $index . ' ' . $this->title . ' record(s)'),
                    'module' => strtolower('applications'),
                    'referrer_id' => $insert,
                ];
                if ($mode == 'create') {
                    # collateral or security uploaded files
                    $this->clientApplicationFiles($insert, 'collateral');
                    # income uploaded files
                    $this->clientApplicationFiles($insert, 'income');
                    # expense receipt uploaded files
                    $this->clientApplicationFiles($insert, 'expense');
                    # send mail notification
                    if ($clientRow['email'] != '') {
                        $data['name'] = $clientRow['name'];
                        $data['email'] = $clientRow['email'];
                        $data['account_type'] = $clientRow['account_type'];
                        $data['branch_name'] = $this->userRow['branch_name'];
                        $data['product_name'] = $productRow['product_name'];
                        $data['frequency'] = $productRow['repayment_freq'];
                        $data['rate'] = $productRow['interest_rate'];
                        $data['period'] = $productRow['repayment_period'] . ' ' . $productRow['repayment_duration'];
                        $data['module'] = 'apply';
                        $data['date'] = date('d-m-Y H:i:s');
                        $checkInternet = $this->settings->checkNetworkConnection();
                        if ($checkInternet) {
                            $subject = "Loan Application";
                            $message = $data;
                            $token = 'application';
                            $this->settings->sendMail($message, $subject, $token);
                            $response = [
                                'status' => 200,
                                'error' => null,
                                'messages' => $this->title . ' record(s) ' . $mode . ' successfully. Email Sent'
                            ];
                            return $this->respond($response);
                            exit;
                        } else {
                            $response = [
                                'status' => 200,
                                'error' => null,
                                'messages' => $this->title . ' record(s) ' . $mode . ' successfully.  No Internet'
                            ];
                            return $this->respond($response);
                            exit;
                        }
                    } else {
                        $response = [
                            'status' => 200,
                            'error' => null,
                            'messages' => $this->title . ' record(s) ' . $mode . ' successfully',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                    // insert into activity logs
                    $activity = $this->insertActivityLog($activityData);
                } else {
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record(s) ' . $mode . ' successfully',
                        ];
                        return $this->respond($response);
                        exit;
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record(s) ' . $mode . ' successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => ucwords($mode) . ' Failed',
                    'messages' => ucfirst($mode) . ' ' . $this->title . ' record(s) failed, try again later!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to ' . $mode . ' ' . $this->title . ' record(s)!',
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
        $application = $this->loanApplication
            ->select('loanapplications.*, clients.id as c_id, clients.name, clients.account_no, clients.email, clients.mobile, clients.residence, clients.photo, clients.id_number, clients.next_of_kin_name, clients.next_of_kin_contact, clients.gender, clients.religion, clients.marital_status, clients.nationality, clients.alternate_no, clients.dob, clients.occupation, clients.job_location, clients.id_type, clients.id_expiry_date, clients.next_of_kin_relationship, clients.next_of_kin_alternate_contact, clients.nok_email, clients.nok_address, clients.account_type as acc_type,, clients.signature as sign, clients.id_photo_front, clients.id_photo_back, loanproducts.product_name, loanproducts.interest_rate, loanproducts.interest_type, loanproducts.repayment_period, loanproducts.repayment_duration, loanproducts.repayment_freq, branches.branch_name, staffs.staff_name, staffs.staffID')
            ->join('clients', 'clients.id = loanapplications.client_id')
            ->join('staffs', 'staffs.id = loanapplications.staff_id')
            ->join('loanproducts', 'loanproducts.id = loanapplications.product_id')
            ->join('branches', 'branches.id = clients.branch_id')
            ->find($id);
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {

            if ($application) {
                $view = 'client/loans/applications/create';
            } else {
                $view = 'layout/404';
            }

            return view($view, [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'relations' => $this->settings->generateRelationships(),
                'charges' => $this->getApplicationChargeParticulars(),
                'application' => $application,
                'userMenu' => $this->load_menu(),

            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('client/dashboard'));
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateApplication("update");
                $applicationData = $this->loanApplication->find($id);
                if ($applicationData) {
                    // application charges
                    $charges = $this->getApplicationChargeParticulars();
                    $data = [
                        'application_date' => trim($this->request->getVar('application_date')),
                        'client_id' => trim($this->request->getVar('client_id')),
                        'client_id' => trim($this->request->getVar('client_id')),
                        // 'staff_id' =>  $this->userRow['staff_id'],
                        'product_id' => trim($this->request->getVar('product_id')),
                        'principal' => trim($this->request->getVar('principal')),
                        'purpose' => trim($this->request->getVar('purpose')),
                        'overall_charges' => serialize($charges['particularIDs']),
                        'total_charges' => trim($this->request->getVar('total_charges')),
                        'reduct_charges' => trim($this->request->getVar('reduct_charges')),

                        'security_item' => trim($this->request->getVar('security_item')),
                        'security_info' => trim($this->request->getVar('security_info')),
                        'est_value' => trim($this->request->getVar('est_value')),
                        'ref_name' => trim($this->request->getVar('ref_name')),
                        'ref_relation' => trim($this->request->getVar('ref_relation')),
                        'ref_job' => trim($this->request->getVar('ref_job')),
                        'ref_contact' => preg_replace('/^0/', '+256', $this->request->getVar('ref_contact')),
                        'ref_alt_contact' => preg_replace('/^0/', '+256', $this->request->getVar('ref_alt_contact')),
                        'ref_email' => trim($this->request->getVar('ref_email')),
                        'ref_address' => trim($this->request->getVar('ref_address')),
                        'ref_name2' => trim($this->request->getVar('ref_name2')),
                        'ref_relation2' => trim($this->request->getVar('ref_relation2')),
                        'ref_job2' => trim($this->request->getVar('ref_job2')),
                        'ref_contact2' => preg_replace('/^0/', '+256', $this->request->getVar('ref_contact2')),
                        'ref_alt_contact2' => preg_replace('/^0/', '+256', $this->request->getVar('ref_alt_contact2')),
                        'ref_email2' => trim($this->request->getVar('ref_email2')),
                        'ref_address2' => trim($this->request->getVar('ref_address2')),

                        'net_salary' => trim($this->request->getVar('net_salary')),
                        'farming' => trim($this->request->getVar('farming')),
                        'business' => trim($this->request->getVar('business')),
                        'others' => trim($this->request->getVar('others')),
                        'rent' => trim($this->request->getVar('rent')),
                        'education' => trim($this->request->getVar('education')),
                        'medical' => trim($this->request->getVar('medical')),
                        'transport' => trim($this->request->getVar('transport')),
                        'exp_others' => trim($this->request->getVar('exp_others')),
                        'difference' => trim($this->request->getVar('difference')),
                        'dif_status' => trim($this->request->getVar('dif_status')),
                        'institute_name' => trim($this->request->getVar('institute_name')),
                        'institute_branch' => trim($this->request->getVar('institute_branch')),
                        'account_type' => $this->request->getVar('account_type'),
                        'institute_name2' => trim($this->request->getVar('institute_name2')),
                        'institute_branch2' => trim($this->request->getVar('institute_branch2')),
                        'account_type2' => $this->request->getVar('account_type2'),
                        'amt_advance' => trim($this->request->getVar('amt_advance')),
                        'date_advance' => trim($this->request->getVar('date_advance')),
                        'loan_duration' => trim($this->request->getVar('loan_duration')),
                        'amt_outstanding' => trim($this->request->getVar('amt_outstanding')),
                        'amt_advance2' => trim($this->request->getVar('amt_advance2')),
                        'date_advance2' => trim($this->request->getVar('date_advance2')),
                        'loan_duration2' => trim($this->request->getVar('loan_duration2')),
                        'amt_outstanding2' => trim($this->request->getVar('amt_outstanding2')),
                    ];
                    # check whether the client national id has been uploaded
                    $update = $this->loanApplication->update($id, $data);
                    if ($update) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'update',
                            'description' => ucfirst('updated ' . $this->title . ', ' . $applicationData['application_code']),
                            'module' => strtolower('applications'),
                            'referrer_id' => $id,
                        ];
                        $activity = $this->insertActivityLog($activityData);
                        if ($activity) {
                            $response = [
                                'status'   => 200,
                                'error'    => null,
                                'messages' => $this->title . ' updated successfully',
                            ];
                            return $this->respond($response);
                            exit;
                        } else {
                            $response = [
                                'status'   => 200,
                                'error'    => null,
                                'messages' => $this->title . ' updated successfully. loggingFailed'
                            ];
                            return $this->respond($response);
                            exit;
                        }
                    } else {
                        $response = [
                            'status' => 500,
                            'error' => 'Update Failed',
                            'messages' => 'Updating ' . $this->title . ' record failed, try again later!',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Loan Application not found, try again!',
                    ];
                    return $this->respond($response);
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'Update Failed. Invalid ID provided, try again!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to update ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
            $appRow = $this->loanApplication->find($id);
            if ($appRow) {
                if (file_exists("uploads/applications/collaterals/" . $appRow['security_photo']) && $appRow['security_photo']) {
                    # delete or remove the previous photo
                    unlink("uploads/applications/collaterals/" . $appRow['security_photo']);
                }
                if (file_exists("uploads/applications/nationalIDs/" . $appRow['ID_photo']) && $appRow['ID_photo']) {
                    # delete or remove the previous photo
                    unlink("uploads/applications/nationalIDs/" . $appRow['ID_photo']);
                }
                if (file_exists("uploads/applications/agreements/" . $appRow['loan_agreement']) && $appRow['loan_agreement']) {
                    # delete or remove the previous agreement
                    unlink("uploads/applications/agreements/" . $appRow['loan_agreement']);
                }
                if (file_exists("uploads/applications/signatures/" . $appRow['signature']) && $appRow['signature']) {
                    # delete or remove the previous signature
                    unlink("uploads/applications/signatures/" . $appRow['signature']);
                }
                $delete = $this->loanApplication->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', ' . $appRow['application_code']),
                        'module' => strtolower('applications'),
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

    public function clientApplicationFiles($application_id, $type)
    {
        # check whether client application files was uploaded
        if ($this->request->getFileMultiple($type)) {
            $saveFile = false;
            foreach ($this->request->getFileMultiple($type) as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $FileType = $this->_doUploadCollateralFile($file, $type);
                    $fileData[] = [
                        'application_id' => $application_id,
                        'file_name' =>  $FileType,
                        'type'  => $type
                    ];
                    $saveFile = true;
                }
            }
            # check whether the valid files was uploaded and ready to be saved
            if ($saveFile) {
                return $this->file->insertBatch($fileData);
            }
        }
    }

    private function _doUploadCollateralFile($file, $module)
    {
        switch (strtolower($module)) {
            case "collateral":
                $collateralFile = $file->getClientName();
                # Renaming file before upload
                $temp = explode(".", $collateralFile);
                $collateralFileName = $this->settings->generateRandomNumbers(10) . '.' . end($temp);
                return $collateralFileName;
                # check whether the collateral files was uploaded successfully
                if ($file->move("uploads/applications/collaterals/", $collateralFileName)) {
                    return $collateralFileName;
                } else {
                    $data['inputerror'][] = 'collateral[]';
                    $data['error_string'][] = "Failed to upload Image!";
                    $data['status'] = FALSE;
                    echo json_encode($data);
                    exit;
                }
                break;
            case "income":
                $incomeReceipts = $file->getClientName();
                # Renaming file before upload
                $tempReceipt = explode(".", $incomeReceipts);
                $newReceiptName = $this->settings->generateRandomNumbers(10) . '.' . end($tempReceipt);
                # check whether the income receipts files was uploaded successfully
                if ($file->move("uploads/applications/income/", $newReceiptName)) {
                    return $newReceiptName;
                } else {
                    $data['inputerror'][] = 'income[]';
                    $data['error_string'][] = "Failed to upload Income Receipts!";
                    $data['status'] = FALSE;
                    echo json_encode($data);
                    exit;
                }
                break;
            case "expense":
                $expenseReceipts = $file->getClientName();
                # Renaming file before upload
                $tempExpense = explode(".", $expenseReceipts);
                // $expenseFileName = $this->settings->generateRandomNumbers(8) . '.' . end($tempExpense);
                $expenseFileName = $this->settings->generateRandomNumbers(10) . '.' . end($tempExpense);
                # check whether the expense receipts files was uploaded successfully
                if ($file->move("uploads/applications/expense/", $expenseFileName)) {
                    return $expenseFileName;
                } else {
                    $data['inputerror'][] = 'expense[]';
                    $data['error_string'][] = "Failed to upload Expense Receipts!";
                    $data['status'] = FALSE;
                    echo json_encode($data);
                    exit;
                }
                break;
            default:
                break;
        }
    }

    private function _validateApplication($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $productRow = $this->loanProduct->find($this->request->getVar('product_id'));
        $appInfo = $this->loanApplication->find($this->request->getVar('id'));
        $clientInfo = $this->client->find($this->request->getVar('client_id'));
        $step = $this->request->getVar('step_no');
        $data['step'] = $step;
        // respose if step validation was successful
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Step ' . $step . ' validation successful',
        ];
        if ($this->request->getVar('step_no') == '') {
            $data['inputerror'][] = 'client_id';
            $data['error_string'][] = 'Step is required!';
            $data['status'] = FALSE;
        }
        # step-wise validation
        switch ($step) {
            case 1: // client & product validation
                if ($this->request->getVar('client_id') == '') {
                    $data['inputerror'][] = 'client_id';
                    $data['error_string'][] = 'Client Name is required!';
                    $data['status'] = FALSE;
                }
                if ($method == "add") {
                    $application = $this->loanApplication
                        ->where(['client_id' => trim($this->request->getVar('client_id')), 'status !=' => 'Disbursed'])
                        ->where(['client_id' => trim($this->request->getVar('client_id')), 'status !=' => 'Declined'])
                        ->findAll();
                    if (count($application) > 0) {
                        $data['inputerror'][] = 'client_id';
                        $data['error_string'][] = 'Client has ' . count($application) . ' running application(s)!';
                        $data['status'] = FALSE;;
                    }
                    $loan = $this->disbursement
                        ->where(['client_id' => trim($this->request->getVar('client_id')), 'class !=' => 'Cleared'])
                        ->findAll();
                    if (count($loan) > 0) {
                        $data['inputerror'][] = 'client_id';
                        $data['error_string'][] = 'Client has a running loan!';
                        $data['status'] = FALSE;;
                    }
                }
                if ($method == "update" && $appInfo['client_id'] != $this->request->getVar('client_id')) {
                    $app = $this->loanApplication->where(array('client_id' => trim($this->request->getVar('client_id')), 'status' => 'pending'))->find();
                    if ($app) {
                        $data['inputerror'][] = 'client_id';
                        $data['error_string'][] = 'Client has pending application!';
                        $data['status'] = FALSE;;
                    }
                }
                if ($this->request->getVar('product_id') == '') {
                    $data['inputerror'][] = 'product_id';
                    $data['error_string'][] = 'Loan Product is required!';
                    $data['status'] = FALSE;
                }
                if ($this->request->getVar('principal') == '') {
                    $data['inputerror'][] = 'principal';
                    $data['error_string'][] = 'principal is required!';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('principal'))) {
                    $principal = $this->request->getVar('principal');
                    if (!preg_match("/^[0-9.' ]*$/", $principal)) {
                        $data['inputerror'][] = 'principal';
                        $data['error_string'][] = 'Invalid format for principal!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('principal')) && !empty($this->request->getVar('product_id'))) {
                    if (($productRow['min_principal'] > 0) && ($this->request->getVar('principal') < $productRow['min_principal'])) {
                        $data['inputerror'][] = 'principal';
                        $data['error_string'][] = 'Pricipal is below minimum allowed of ' . $productRow['product_name'] . '!';
                        $data['status'] = FALSE;
                    }
                    if (($productRow['max_principal'] > 0) && ($this->request->getVar('principal') > $productRow['max_principal'])) {
                        $data['inputerror'][] = 'principal';
                        $data['error_string'][] = 'Pricipal is above maximum allowed of ' . $productRow['product_name'] . '!';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('purpose') == '') {
                    $data['inputerror'][] = 'purpose';
                    $data['error_string'][] = 'Purpose is required!';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('purpose'))) {
                    if (strlen($this->request->getVar('purpose')) < 7) {
                        $data['inputerror'][] = 'purpose';
                        $data['error_string'][] = 'Minimum length should is 7!';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('total_charges') == '') {
                    $data['inputerror'][] = 'total_charges';
                    $data['error_string'][] = 'Total Charges is required!';
                    $data['status'] = FALSE;
                }
                if ($this->request->getVar('reduct_charges') == '') {
                    $data['inputerror'][] = 'reduct_charges';
                    $data['error_string'][] = 'Reduct charges from is required!';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('reduct_charges') == '')) {
                    if (strtolower($this->request->getVar('reduct_charges')) == 'savings') {
                        if ($clientInfo['account_balance'] < $this->request->getVar('reduct_charges')) {
                            $data['inputerror'][] = 'reduct_charges';
                            $data['error_string'][] = 'Account Balance is insufficient!';
                            $data['status'] = FALSE;
                        }
                    }
                }
                // return response
                if ($data['status'] === FALSE) {
                    echo json_encode($data);
                    exit;
                } else {
                    echo json_encode($response);
                    exit;
                }
                break;
            case 2: // security and referees validation
                if ($this->request->getVar('security_item') == '') {
                    $data['inputerror'][] = 'security_item';
                    $data['error_string'][] = 'Security Item is required!';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('security_item'))) {
                    if (strlen($this->request->getVar('security_item')) < 3) {
                        $data['inputerror'][] = 'security_item';
                        $data['error_string'][] = 'Minimum length should is 3!';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('est_value') == '') {
                    $data['inputerror'][] = 'est_value';
                    $data['error_string'][] = 'Item Estimated Value is required!';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('est_value'))) {
                    if (!preg_match("/^[0-9' ]*$/", $this->request->getVar('est_value'))) {
                        $data['inputerror'][] = 'est_value';
                        $data['error_string'][] = 'Invalid format for est_value!';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('security_info') == '') {
                    $data['inputerror'][] = 'security_info';
                    $data['error_string'][] = 'Item Details is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('security_info'))) {
                    if ($this->settings->validateAddress($this->request->getVar('security_info')) == TRUE) {
                        if (strlen(trim($this->request->getVar('security_info'))) < 4) {
                            $data['inputerror'][] = 'security_info';
                            $data['error_string'][] = 'Security Details is too short';
                            $data['status'] = FALSE;
                        }
                    }
                }
                if ($this->request->getVar('ref_name') == '') {
                    $data['inputerror'][] = 'ref_name';
                    $data['error_string'][] = 'Referee Name is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_name'))) {
                    if ($this->settings->validateName($this->request->getVar('ref_name')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_name'))) < 6) {
                            $data['inputerror'][] = 'ref_name';
                            $data['error_string'][] = 'Referee Name is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('ref_name')) == FALSE) {
                        $data['inputerror'][] = 'ref_name';
                        $data['error_string'][] = 'Valid Referee Name is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('ref_relation') == '') {
                    $data['inputerror'][] = 'ref_relation';
                    $data['error_string'][] = 'Referee Relationship is required';
                    $data['status'] = FALSE;
                }
                if ($this->request->getVar('ref_job') == '') {
                    $data['inputerror'][] = 'ref_job';
                    $data['error_string'][] = 'Referee Job is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_job'))) {
                    if ($this->settings->validateName($this->request->getVar('ref_job')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_job'))) < 4) {
                            $data['inputerror'][] = 'ref_job';
                            $data['error_string'][] = 'Referee Job is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('ref_job')) == FALSE) {
                        $data['inputerror'][] = 'ref_job';
                        $data['error_string'][] = 'Valid Referee Job is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('ref_contact') == '') {
                    $data['inputerror'][] = 'ref_contact';
                    $data['error_string'][] = 'Phone Number is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_contact'))) {
                    # check whether the first phone number is with +256
                    if (substr($this->request->getVar('ref_contact'), 0, 4) == '+256') {
                        if (
                            strlen($this->request->getVar('ref_contact')) > 13 ||
                            strlen($this->request->getVar('ref_contact')) < 13
                        ) {
                            $data['inputerror'][] = 'ref_contact';
                            $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                            $data['status'] = FALSE;
                        }
                    }
                    # check whether the first phone number is with 0
                    else if (substr($this->request->getVar('ref_contact'), 0, 1) == '0') {
                        if (
                            strlen($this->request->getVar('ref_contact')) > 10 ||
                            strlen($this->request->getVar('ref_contact')) < 10
                        ) {
                            $data['inputerror'][] = 'ref_contact';
                            $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                            $data['status'] = FALSE;
                        }
                    } else if (substr($this->request->getVar('ref_contact'), 0, 1) == '+') {
                        if (
                            strlen($this->request->getVar('ref_contact')) > 13 ||
                            strlen($this->request->getVar('ref_contact')) < 13
                        ) {
                            $data['inputerror'][] = 'ref_contact';
                            $data['error_string'][] = 'Should have 13 digits with Country Code';
                            $data['status'] = FALSE;
                        }
                    } else {
                        $data['inputerror'][] = 'ref_contact';
                        $data['error_string'][] = 'Valid Phone Number is required';
                        $data['status'] = FALSE;
                    }
                    # check whether the phone number is valid
                    if ($this->settings->validatePhoneNumber($this->request->getVar('ref_contact')) == FALSE) {
                        $data['inputerror'][] = 'ref_contact';
                        $data['error_string'][] = 'Valid Phone Number is required';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('ref_alt_contact'))) {
                    # check whether the first phone number is with +256
                    if (substr($this->request->getVar('ref_alt_contact'), 0, 4) == '+256') {
                        if (
                            strlen($this->request->getVar('ref_alt_contact')) > 13 ||
                            strlen($this->request->getVar('ref_alt_contact')) < 13
                        ) {
                            $data['inputerror'][] = 'ref_alt_contact';
                            $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                            $data['status'] = FALSE;
                        }
                    }
                    # check whether the first phone number is with 0
                    else if (substr($this->request->getVar('ref_alt_contact'), 0, 1) == '0') {
                        if (
                            strlen($this->request->getVar('ref_alt_contact')) > 10 ||
                            strlen($this->request->getVar('ref_alt_contact')) < 10
                        ) {
                            $data['inputerror'][] = 'ref_alt_contact';
                            $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                            $data['status'] = FALSE;
                        }
                    } else if (substr($this->request->getVar('ref_alt_contact'), 0, 1) == '+') {
                        if (
                            strlen($this->request->getVar('ref_alt_contact')) > 13 ||
                            strlen($this->request->getVar('ref_alt_contact')) < 13
                        ) {
                            $data['inputerror'][] = 'ref_alt_contact';
                            $data['error_string'][] = 'Should have 13 digits with Country Code';
                            $data['status'] = FALSE;
                        }
                    } else {
                        $data['inputerror'][] = 'ref_alt_contact';
                        $data['error_string'][] = 'Valid Phone Number is required';
                        $data['status'] = FALSE;
                    }
                    # check whether the phone number is valid
                    if ($this->settings->validatePhoneNumber($this->request->getVar('ref_alt_contact')) == FALSE) {
                        $data['inputerror'][] = 'ref_alt_contact';
                        $data['error_string'][] = 'Valid Phone Number is required';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('ref_email'))) {
                    # check whether the email is valid
                    if ($this->settings->validateEmail($this->request->getVar('ref_email')) == FALSE) {
                        $data['inputerror'][] = 'ref_email';
                        $data['error_string'][] = 'Valid Email is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('ref_address') == '') {
                    $data['inputerror'][] = 'ref_address';
                    $data['error_string'][] = 'Referee Address is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_address'))) {
                    if ($this->settings->validateAddress($this->request->getVar('ref_address')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_address'))) < 4) {
                            $data['inputerror'][] = 'ref_address';
                            $data['error_string'][] = 'Referee Address is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateAddress($this->request->getVar('ref_address')) == FALSE) {
                        $data['inputerror'][] = 'ref_address';
                        $data['error_string'][] = 'Valid Referee Address is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('ref_name2') == '') {
                    $data['inputerror'][] = 'ref_name2';
                    $data['error_string'][] = 'Referee Name is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_name2'))) {
                    if ($this->settings->validateName($this->request->getVar('ref_name2')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_name2'))) < 6) {
                            $data['inputerror'][] = 'ref_name2';
                            $data['error_string'][] = 'Referee Name is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('ref_name2')) == FALSE) {
                        $data['inputerror'][] = 'ref_name2';
                        $data['error_string'][] = 'Valid Referee Name is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('ref_relation2') == '') {
                    $data['inputerror'][] = 'ref_relation2';
                    $data['error_string'][] = 'Referee Relationship is required';
                    $data['status'] = FALSE;
                }
                if ($this->request->getVar('ref_job2') == '') {
                    $data['inputerror'][] = 'ref_job2';
                    $data['error_string'][] = 'Referee Job is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_job2'))) {
                    if ($this->settings->validateName($this->request->getVar('ref_job2')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_job2'))) < 4) {
                            $data['inputerror'][] = 'ref_job2';
                            $data['error_string'][] = 'Referee Job is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('ref_job2')) == FALSE) {
                        $data['inputerror'][] = 'ref_job2';
                        $data['error_string'][] = 'Valid Referee Job is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('ref_contact2') == '') {
                    $data['inputerror'][] = 'ref_contact2';
                    $data['error_string'][] = 'Phone Number is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_contact2'))) {
                    # check whether the first phone number is with +256
                    if (substr($this->request->getVar('ref_contact2'), 0, 4) == '+256') {
                        if (
                            strlen($this->request->getVar('ref_contact2')) > 13 ||
                            strlen($this->request->getVar('ref_contact2')) < 13
                        ) {
                            $data['inputerror'][] = 'ref_contact';
                            $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                            $data['status'] = FALSE;
                        }
                    }
                    # check whether the first phone number is with 0
                    else if (substr($this->request->getVar('ref_contact2'), 0, 1) == '0') {
                        if (
                            strlen($this->request->getVar('ref_contact2')) > 10 ||
                            strlen($this->request->getVar('ref_contact2')) < 10
                        ) {
                            $data['inputerror'][] = 'ref_contact2';
                            $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                            $data['status'] = FALSE;
                        }
                    } else if (substr($this->request->getVar('ref_contact2'), 0, 1) == '+') {
                        if (
                            strlen($this->request->getVar('ref_contact2')) > 13 ||
                            strlen($this->request->getVar('ref_contact2')) < 13
                        ) {
                            $data['inputerror'][] = 'ref_contact2';
                            $data['error_string'][] = 'Should have 13 digits with Country Code';
                            $data['status'] = FALSE;
                        }
                    } else {
                        $data['inputerror'][] = 'ref_contact2';
                        $data['error_string'][] = 'Valid Phone Number is required';
                        $data['status'] = FALSE;
                    }
                    # check whether the phone number is valid
                    if ($this->settings->validatePhoneNumber($this->request->getVar('ref_contact2')) == FALSE) {
                        $data['inputerror'][] = 'ref_contact2';
                        $data['error_string'][] = 'Valid Phone Number is required';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('ref_alt_contact2'))) {
                    # check whether the first phone number is with +256
                    if (substr($this->request->getVar('ref_alt_contact2'), 0, 4) == '+256') {
                        if (
                            strlen($this->request->getVar('ref_alt_contact2')) > 13 ||
                            strlen($this->request->getVar('ref_alt_contact2')) < 13
                        ) {
                            $data['inputerror'][] = 'ref_alt_contact2';
                            $data['error_string'][] = 'Valid Phone Number should have 13 digits';
                            $data['status'] = FALSE;
                        }
                    }
                    # check whether the first phone number is with 0
                    else if (substr($this->request->getVar('ref_alt_contact2'), 0, 1) == '0') {
                        if (
                            strlen($this->request->getVar('ref_alt_contact2')) > 10 ||
                            strlen($this->request->getVar('ref_alt_contact2')) < 10
                        ) {
                            $data['inputerror'][] = 'ref_alt_contact2';
                            $data['error_string'][] = 'Valid Phone Number should have 10 digits';
                            $data['status'] = FALSE;
                        }
                    } else if (substr($this->request->getVar('ref_alt_contact2'), 0, 1) == '+') {
                        if (
                            strlen($this->request->getVar('ref_alt_contact2')) > 13 ||
                            strlen($this->request->getVar('ref_alt_contact2')) < 13
                        ) {
                            $data['inputerror'][] = 'ref_alt_contact2';
                            $data['error_string'][] = 'Should have 13 digits with Country Code';
                            $data['status'] = FALSE;
                        }
                    } else {
                        $data['inputerror'][] = 'ref_alt_contact2';
                        $data['error_string'][] = 'Valid Phone Number is required';
                        $data['status'] = FALSE;
                    }
                    # check whether the phone number is valid
                    if ($this->settings->validatePhoneNumber($this->request->getVar('ref_alt_contact2')) == FALSE) {
                        $data['inputerror'][] = 'ref_alt_contact2';
                        $data['error_string'][] = 'Valid Phone Number is required';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('ref_email2'))) {
                    # check whether the email is valid
                    if ($this->settings->validateEmail($this->request->getVar('ref_email2')) == FALSE) {
                        $data['inputerror'][] = 'ref_email2';
                        $data['error_string'][] = 'Valid Email is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('ref_address2') == '') {
                    $data['inputerror'][] = 'ref_address2';
                    $data['error_string'][] = 'Referee Address is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_address2'))) {
                    if ($this->settings->validateAddress($this->request->getVar('ref_address2')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_address2'))) < 4) {
                            $data['inputerror'][] = 'ref_address2';
                            $data['error_string'][] = 'Referee Address is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateAddress($this->request->getVar('ref_address2')) == FALSE) {
                        $data['inputerror'][] = 'ref_address2';
                        $data['error_string'][] = 'Valid Referee Address is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getFileMultiple('collateral')) {
                    # set validation rule
                    $validationRule = [
                        'collateral[]' => [
                            "rules" => "uploaded[collateral]|max_size[collateral,5120]",
                            "label" => "Collateral Files",
                            "errors" => [
                                'max_size' => 'The size of this image(s) is too large. The image must have less than 5MB size',
                            ]
                        ],
                    ];
                    if (!$this->validate($validationRule) && strtolower($method) == 'add') {
                        $data['inputerror'][] = 'collateral[]';
                        $data['error_string'][] = $this->validator->getError("collateral[]") . '!';
                        $data['status'] = FALSE;
                        echo json_encode($data);
                        exit;
                    }
                    if (count($this->request->getFileMultiple('collateral')) > 5) {
                        $data['inputerror'][] = 'collateral[]';
                        $data['error_string'][] = "Maximum 5 Collateral Files allowed!";
                        $data['status'] = FALSE;
                        echo json_encode($data);
                        exit;
                    }
                }
                // return response
                if ($data['status'] === FALSE) {
                    echo json_encode($data);
                    exit;
                } else {
                    echo json_encode($response);
                    exit;
                }
                break;
            case 3: // client credit worthiness validation
                if (!empty($this->request->getVar('net_salary'))) {
                    $sal = $this->request->getVar('net_salary');
                    if (!preg_match("/^[0-9']*$/", $sal)) {
                        $data['inputerror'][] = 'net_salary';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('farming'))) {
                    $farm = $this->request->getVar('farming');
                    if (!preg_match("/^[0-9']*$/", $farm)) {
                        $data['inputerror'][] = 'farming';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('business'))) {
                    $buz = $this->request->getVar('business');
                    if (!preg_match("/^[0-9']*$/", $buz)) {
                        $data['inputerror'][] = 'business';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('others'))) {
                    $others = $this->request->getVar('others');
                    if (!preg_match("/^[0-9']*$/", $others)) {
                        $data['inputerror'][] = 'others';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (empty($this->request->getVar('net_salary')) && empty($this->request->getVar('farming')) && empty($this->request->getVar('business')) && empty($this->request->getVar('others'))) {
                    $data['inputerror'][] = 'others';
                    $data['error_string'][] = 'At Least one income is required';
                }
                if (!empty($this->request->getVar('rent'))) {
                    $rent = $this->request->getVar('rent');
                    if (!preg_match("/^[0-9']*$/", $rent)) {
                        $data['inputerror'][] = 'rent';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('education'))) {
                    $educ = $this->request->getVar('education');
                    if (!preg_match("/^[0-9']*$/", $educ)) {
                        $data['inputerror'][] = 'education';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('medical'))) {
                    $med = $this->request->getVar('medical');
                    if (!preg_match("/^[0-9']*$/", $med)) {
                        $data['inputerror'][] = 'medical';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('transport'))) {
                    $tp = $this->request->getVar('transport');
                    if (!preg_match("/^[0-9']*$/", $tp)) {
                        $data['inputerror'][] = 'transport';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('exp_others'))) {
                    $exp_o = $this->request->getVar('exp_others');
                    if (!preg_match("/^[0-9']*$/", $exp_o)) {
                        $data['inputerror'][] = 'exp_others';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (empty($this->request->getVar('rent')) && empty($this->request->getVar('education')) && empty($this->request->getVar('medical')) && empty($this->request->getVar('transport')) && empty($this->request->getVar('exp_others'))) {
                    $data['inputerror'][] = 'exp_others';
                    $data['error_string'][] = 'At Least one expense is required';
                }
                if ($this->request->getFileMultiple('income')) {
                    $validationRule = [
                        'income[]' => [
                            "rules" => "uploaded[income]|max_size[income,5120]",
                            "label" => "Income Files",
                            "errors" => [
                                'max_size' => 'The size of this image is too large. The image must have less than 5MB size',
                            ]
                        ],
                    ];
                    if (!$this->validate($validationRule) && strtolower($method) == 'add') {
                        $data['inputerror'][] = 'income[]';
                        $data['error_string'][] = $this->validator->getError("income[]") . '!';
                        $data['status'] = FALSE;
                        // echo json_encode($data);
                        // exit;
                    }
                    if (count($this->request->getFileMultiple('income')) > 5) {
                        $data['inputerror'][] = 'income[]';
                        $data['error_string'][] = "Maximum 5 income Files allowed!";
                        $data['status'] = FALSE;
                        // echo json_encode($data);
                        // exit;
                    }
                }
                if ($this->request->getFileMultiple('expense')) {
                    $validationRule = [
                        'expense[]' => [
                            "rules" => "uploaded[expense]|max_size[expense,5120]",
                            "label" => "Expense Files",
                            "errors" => [
                                'max_size' => 'The size of this image is too large. The image must have less than 5MB size',
                            ]
                        ],
                    ];
                    if (!$this->validate($validationRule) && strtolower($method) == 'add') {
                        $data['inputerror'][] = 'expense[]';
                        $data['error_string'][] = $this->validator->getError("expense[]") . '!';
                        $data['status'] = FALSE;
                        echo json_encode($data);
                        exit;
                    }
                    if (count($this->request->getFileMultiple('expense')) > 5) {
                        $data['inputerror'][] = 'expense[]';
                        $data['error_string'][] = "Maximum 5 expense Files allowed!";
                        $data['status'] = FALSE;
                        echo json_encode($data);
                        exit;
                    }
                }
                // return response
                if ($data['status'] === FALSE) {
                    echo json_encode($data);
                    exit;
                } else {
                    echo json_encode($response);
                    exit;
                }
                break;
            case 4: // client other accounts & loan else where validation
                if (!empty($this->request->getVar('institute_name'))) {
                    if ($this->settings->validateName($this->request->getVar('institute_name')) == TRUE) {
                        if (strlen(trim($this->request->getVar('institute_name'))) < 5) {
                            $data['inputerror'][] = 'institute_name';
                            $data['error_string'][] = 'Institute Name is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('institute_name')) == FALSE) {
                        $data['inputerror'][] = 'institute_name';
                        $data['error_string'][] = 'Valid Institute Name is required';
                        $data['status'] = FALSE;
                    }
                    if ($this->request->getVar('institute_branch') == '') {
                        $data['inputerror'][] = 'institute_branch';
                        $data['error_string'][] = 'Institute Branch is required';
                        $data['status'] = FALSE;
                    }
                    if (!empty($this->request->getVar('institute_branch'))) {
                        if ($this->settings->validateName($this->request->getVar('institute_branch')) == TRUE) {
                            if (strlen(trim($this->request->getVar('institute_branch'))) < 5) {
                                $data['inputerror'][] = 'institute_branch';
                                $data['error_string'][] = 'Branch Name is too short';
                                $data['status'] = FALSE;
                            }
                        }
                        if ($this->settings->validateName($this->request->getVar('institute_branch')) == FALSE) {
                            $data['inputerror'][] = 'institute_branch';
                            $data['error_string'][] = 'Valid Branch Name is required';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->request->getVar('account_type') == '') {
                        $data['inputerror'][] = 'account_type';
                        $data['error_string'][] = 'Account Type is required';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('institute_name2'))) {
                    if ($this->settings->validateName($this->request->getVar('institute_name2')) == TRUE) {
                        if (strlen(trim($this->request->getVar('institute_name2'))) < 5) {
                            $data['inputerror'][] = 'institute_name2';
                            $data['error_string'][] = 'Institute Name is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('institute_name2')) == FALSE) {
                        $data['inputerror'][] = 'institute_name2';
                        $data['error_string'][] = 'Valid Institute Name is required';
                        $data['status'] = FALSE;
                    }
                    if ($this->request->getVar('institute_branch2') == '') {
                        $data['inputerror'][] = 'institute_branch2';
                        $data['error_string'][] = 'Branch name is required!';
                        $data['status'] = FALSE;
                    }
                    if (!empty($this->request->getVar('institute_branch2'))) {
                        if ($this->settings->validateName($this->request->getVar('institute_branch2')) == TRUE) {
                            if (strlen(trim($this->request->getVar('institute_branch2'))) < 5) {
                                $data['inputerror'][] = 'institute_branch2';
                                $data['error_string'][] = 'Branch Name is too short';
                                $data['status'] = FALSE;
                            }
                        }
                        if ($this->settings->validateName($this->request->getVar('institute_branch2')) == FALSE) {
                            $data['inputerror'][] = 'institute_branch2';
                            $data['error_string'][] = 'Valid Branch Name is required';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->request->getVar('account_type2') == '') {
                        $data['inputerror'][] = 'account_type2';
                        $data['error_string'][] = 'Account Type is required!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('amt_advance'))) {
                    if (!preg_match("/^[0-9 ]+$/", $this->request->getVar('amt_advance'))) {
                        $data['inputerror'][] = 'amt_advance';
                        $data['error_string'][] = 'Valid Amount is required';
                        $data['status'] = FALSE;
                    }
                    if ($this->request->getVar('date_advance') == '') {
                        $data['inputerror'][] = 'date_advance';
                        $data['error_string'][] = 'Date Advance is required';
                        $data['status'] = FALSE;
                    }
                    if ($this->request->getVar('loan_duration') == '') {
                        $data['inputerror'][] = 'loan_duration';
                        $data['error_string'][] = 'Loan Duration is required';
                        $data['status'] = FALSE;
                    }
                    if (!empty($this->request->getVar('loan_duration'))) {
                        if (!preg_match("/^[0-9.]+$/", $this->request->getVar('loan_duration'))) {
                            $data['inputerror'][] = 'loan_duration';
                            $data['error_string'][] = 'Valid duration is required';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->request->getVar('amt_outstanding') == '') {
                        $data['inputerror'][] = 'amt_outstanding';
                        $data['error_string'][] = 'Total Amount is required';
                        $data['status'] = FALSE;
                    }
                    if (!empty($this->request->getVar('amt_outstanding'))) {
                        if (!preg_match("/^[0-9 ]+$/", $this->request->getVar('amt_outstanding'))) {
                            $data['inputerror'][] = 'amt_outstanding';
                            $data['error_string'][] = 'Valid Amount is required';
                            $data['status'] = FALSE;
                        }
                    }
                }
                if (!empty($this->request->getVar('amt_advance2'))) {
                    if (!preg_match("/^[0-9 ]+$/", $this->request->getVar('amt_advance2'))) {
                        $data['inputerror'][] = 'amt_advance2';
                        $data['error_string'][] = 'Valid Amount is required';
                        $data['status'] = FALSE;
                    }
                    if ($this->request->getVar('date_advance2') == '') {
                        $data['inputerror'][] = 'date_advance2';
                        $data['error_string'][] = 'Date Advance is required';
                        $data['status'] = FALSE;
                    }
                    if ($this->request->getVar('loan_duration2') == '') {
                        $data['inputerror'][] = 'loan_duration2';
                        $data['error_string'][] = 'Loan Duration is required';
                        $data['status'] = FALSE;
                    }
                    if (!empty($this->request->getVar('loan_duration2'))) {
                        if (!preg_match("/^[0-9.]+$/", $this->request->getVar('loan_duration2'))) {
                            $data['inputerror'][] = 'loan_duration2';
                            $data['error_string'][] = 'Valid duration is required';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->request->getVar('amt_outstanding2') == '') {
                        $data['inputerror'][] = 'amt_outstanding2';
                        $data['error_string'][] = 'Total Amount is required';
                        $data['status'] = FALSE;
                    }
                    if (!empty($this->request->getVar('amt_outstanding2'))) {
                        if (!preg_match("/^[0-9 ]+$/", $this->request->getVar('amt_outstanding2'))) {
                            $data['inputerror'][] = 'amt_outstanding2';
                            $data['error_string'][] = 'Valid Amount is required';
                            $data['status'] = FALSE;
                        }
                    }
                }
                // return response
                if ($data['status'] === FALSE) {
                    echo json_encode($data);
                    exit;
                }
                break;
        }
    }
}
