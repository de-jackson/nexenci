<?php

namespace App\Controllers\Client;

use CodeIgniter\I18n\Time;

use \Hermawan\DataTables\DataTable;
use App\Controllers\Client\MainController;

class Loans extends MainController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Loans';
        $this->title = 'Applications';
        $this->menuItem = [
            'title' => $this->title,
            'menu' => $this->menu,
        ];

        if (strtolower($this->userRow["account_type"]) == 'client') {
            $this->report->setUserAccountType([
                # Index: 0 account type i.e Administrator, Employee, Client
                strtolower($this->userRow["account_type"]),
                # Index: 1
                $this->userRow["id"]
            ]);
        }
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function module($menu)
    {
        // $this->menuItem['title'] = ucfirst($menu);
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {

            switch (strtolower($menu)) {

                case 'products':
                    $view = 'client/reports/loan/products';
                    break;

                case 'applications':
                    $view = 'client/loans/applications/index';
                    break;

                case 'disbursements':
                    # $view = 'client/reports/loan/disbursements';
                    $view = 'client/loans/disbursements/index';
                    break;

                default:
                    $view = 'layout/404';
                    break;
            }

            return view($view, [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'relations' => $this->settings->generateRelationships(),
                'charges' => $this->getApplicationChargeParticulars(),
                'userMenu' => $this->load_menu(),
                // 'applicationsCounter' => $this->getTableCounts('applications'),
                // 'disbursementsCounter' => $this->getTableCounts('disbursements'),

            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('/client/dashboard'));
        }
    }

    public function type($menu, $class, $principal = null)
    {
        switch (strtolower($menu)) {
            case 'applications':
                switch (strtolower($class)) {
                    case 'bulkdelete':
                        # bulky delete loan applications information
                        $results = $this->bulkDeleteLoanApplications();
                        break;

                    case 'products':
                        $results = $this->loanProduct->where([
                            'min_principal <=' => $principal,
                            'max_principal >=' => $principal,
                            'status' => 'Active',
                            'deleted_at' => NULL
                        ])->orderBy('id', 'desc')->findAll();

                        return $this->respond(($results));
                        break;

                    default:
                        # generate loan applications datatables
                        $results = $this->getLoanApplicationsList($class);
                        break;
                }
                break;

            case 'disbursements':
                switch (strtolower($class)) {
                    case 'auto':
                        # update the disbursement fields automatically
                        $results = $this->updateDisbursementAutoFields();
                        break;

                    case 'balance':
                        # update the disbursement total amount collected automatically
                        $results = $this->autoUpdateDisbursementBalance();
                        break;

                    case 'ledgers':
                        # update the particular total balance automatically
                        $results = $this->autoUpdateParticularBalance();
                        break;

                    case 'savings':
                        # update the client savings account balance
                        $results = $this->autoUpdateClientAccountBalance();
                        break;

                    case 'bulkdelete':
                        # delete the disbursements information
                        $results = $this->bulkDeleteDisbursements();
                        break;

                    default:
                        # generate disbursements databtables
                        $results = $this->getDisbursementsList($class);
                        break;
                }
                break;
            case 'accounts':
                # generate disbursement data by id
                $results = $this->getAccountTypesByPart($class);
                break;

            case 'view':
                # generate disbursement data by id
                $results = $this->getLoanDisbursementById($class);
                break;
            default:
                # no report found thus, set empty array
                $results = [];
                break;
        }

        return $results;
    }

    public function getLoanApplicationsList($status)
    {
        $where = [
            'loanapplications.deleted_at' => null,
            'loanapplications.status' => ucfirst($status),
            'client_id' => $this->userRow['id']
        ];
        $applications = $this->loanApplication->select('clients.id as client_id, clients.name, 
            clients.photo, loanproducts.product_name, loanproducts.interest_rate, loanproducts.repayment_freq, loanproducts.repayment_period, loanproducts.repayment_duration, loanapplications.principal, loanapplications.application_code, loanapplications.status, loanapplications.level, loanapplications.action, loanapplications.id')
            ->join('clients', 'clients.id = loanapplications.client_id')
            ->join('loanproducts', 'loanproducts.id = loanapplications.product_id')
            ->where($where);

        return DataTable::of($applications)
            ->add('checkbox', function ($application) {
                return '<div class=""><input type="checkbox" class="data-check' . ucfirst($application->status) . '" value="' . $application->id . '"></div>';
            })
            ->addNumbering("no")
            ->add('period', function ($application) {
                return $application->repayment_period . ' ' . $application->repayment_duration;
            })
            ->add('photo', function ($application) {
                # check whether the photo exist
                if (file_exists("uploads/clients/passports/" . $application->photo) && $application->photo) {
                    # display the current photo
                    return '
                    <a href="javascript:void(0)" onclick="view_client_photo(' . "'" . $application->id . "'" . ')" title="' . $application->name . '"><img src="' . base_url('uploads/clients/passports/' . $application->photo) . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                } else {
                    # display the default photo
                    return '
                    <a href="javascript:void(0)" onclick="view_client_photo(' . "'" . $application->id . "'" . ')" title="no photo"><img src="' . base_url('assets/dist/img/nophoto.jpg') . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                }
            })
            ->add('actions', function ($application) {
                $application_status = strtolower($application->status);

                /*
                if (empty($application->level)) {
                    $text = "secondary";
                } else {
                    $text = "warning";
                }
                */

                switch (strtolower($application->status)) {
                    case "approved":
                        $text = "info";
                        break;
                    case "disbursed":
                        $text = "success";
                        break;
                    case "declined":
                        $text = "danger";
                        break;
                    case "pending":
                        $text = "secondary";
                        break;
                    case "cancelled":
                        $text = "warning";
                        break;
                    default:
                        $text = "info";
                        break;
                }


                $actions = '
                <div class="btn-group dropend my-1">
                <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu">';
                # show buttons based on user permissions
                if (($this->userPermissions == 'all') || (in_array('create_loansApplications', $this->userPermissions))) {
                    // $actions .= '
                    //     <a href="/client/application/info/' . $application->id . '" title="View Application" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Application</a>';
                }

                if ($application_status == "approved") {
                    # show view agreement button
                    if (($this->userPermissions == 'all') || (in_array('create_loansDisbursements', $this->userPermissions))) {
                        // $actions .= '
                        // <div class="dropdown-divider"></div>
                        // <a href="/admin/loan/agreement/application/' . $application->id . '" target="_blank" title="View Loan Agreement" class="dropdown-item"><i class="fas fa-clipboard-list text-primary"></i> View Agreement</a>';
                    }
                    # show quick disbursement button
                    if (($this->userPermissions == 'all') || (in_array('create_loansDisbursements', $this->userPermissions))) {
                        // $actions .= '
                        //     <div class="dropdown-divider"></div>
                        //     <a href="javascript:void(0)" onclick="add_applicationRemarks(' . "'" . $application->id . "'" . ',' . "'disburse'" . ')" title="Disburse Loan" class="dropdown-item"><i class="fas fa-money-bill-trend-up text-info"></i> Quick Disburse</a>';
                    }
                }

                if ($application_status == "pending" || $application_status == "processing") {

                    if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                        $actions .= '
                            <div class="dropdown-divider"></div>
                            <a href="' . base_url('client/applications/' . $application->id . '/edit') . '" title="edit application" class="dropdown-item"><i class="fas fa-edit text-info"></i> 
                                Edit Application
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0)" onclick="cancelLoanApplication(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ')" title="Cancel Application" class="dropdown-item"><i class="fa fa-cancel text-danger"></i> 
                                Cancel Application
                            </a>';
                    }
                }

                if ($application_status == "processing") {
                    if (!empty($application->level)) {
                        # show add remarks button
                        // if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                        //     $actions .= '
                        //     <div class="dropdown-divider"></div>
                        //     <a href="javascript:void(0)" onclick="add_applicationRemarks(' . "'" . $application->id . "'" . ')" title="Add Application Remarks" class="dropdown-item"><i class="fas fa-comments text-warning"></i> Add Remarks</a>';
                        // }
                    }
                }

                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    // $actions .= '
                    // <div class="dropdown-divider"></div>
                    // <a href="javascript:void(0)" onclick="delete_application(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ')" title="Delete Application" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Application</a>';
                }

                $actions .= ' 
                    </ul>
                </div>';

                return $actions;
            })
            /*
            ->add('actions', function ($application) {
                // show buttons based on user permissions
                switch (strtolower($application->status)) {
                    case "approved":
                        $text = "info";
                        // show buttons based on user permissions
                        $actions = '<div class="btn-group dropend my-1">
                            <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">';
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                            $actions .= '<a href="/client/application/info/' . $application->id . '" title="view application" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Application</a>
                            <div class="dropdown-divider"></div>
                            <a href="/admin/loan/agreement/application/' . $application->id . '" target="_blank" title="View Loan Agreement" class="dropdown-item"><i class="fas fa-clipboard-list text-primary"></i> View Agreement</a>';
                        }
                        if (($this->userPermissions == 'all') || (in_array('update' . $this->subTitle1, $this->userPermissions))) {
                            $actions .= '<div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="edit_application(' . "'" . $application->id . "'" . ')" title="edit application" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit Application</a>
                                <div class="dropdown-divider"></div>';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="delete_application(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ')" title="delete application" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Application</a>';
                        }
                        $actions .= ' 
                            </ul>
                        </div>';
                        break;
                    case "disbursed":
                        $text = "success";
                        // show buttons based on user permissions
                        $actions = '<div class="btn-group dropend my-1">
                        <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">';
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                            $actions .= '<a href="/client/application/info/' . $application->id . '" title="view application" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Application</a>';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '<div class="dropdown-divider"></div>
                                        <a href="javascript:void(0)" onclick="delete_application(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ')" title="delete application" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Application</a>';
                        }
                        $actions .= ' 
                            </ul>
                        </div>';
                        break;
                    case "declined":
                        $text = "danger";
                        // show buttons based on user permissions
                        $actions = '<div class="btn-group dropend my-1">
                        <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">';
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                            $actions .= '<a href="/client/application/info/' . $application->id . '" title="view application" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Application</a>';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '<div class="dropdown-divider"></div>
                                        <a href="javascript:void(0)" onclick="delete_application(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ')" title="delete application" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Application</a>';
                        }
                        $actions .= ' 
                            </ul>
                        </div>';
                        break;
                    case "pending":
                        if (strtolower($application->level) == null) {
                            $text = "secondary";
                            // show buttons based on user permissions
                            $actions = '<div class="btn-group dropend my-1">
                            <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu">';
                            if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                                $actions .= '<a href="/client/application/info/' . $application->id . '" title="view application" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Application</a>';
                            }
                            if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                                $actions .= '
                                    <div class="dropdown-divider"></div>
                                    <a href="' . base_url('client/applications/' . $application->id . '/edit') . '" title="edit application" class="dropdown-item"><i class="fas fa-edit text-info"></i> 
                                        Edit Application
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="cancelLoanApplication(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ')" title="Cancel Application" class="dropdown-item"><i class="fa fa-cancel text-danger"></i> 
                                        Cancel Application
                                    </a>
                                    <div class="dropdown-divider"></div>
                                ';
                            }
                            if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                                $actions .= '<a href="javascript:void(0)" onclick="delete_application(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ')" title="delete application" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Application</a>';
                            }
                            $actions .= ' 
                                </ul>
                            </div>';
                        } else {
                            $text = "warning";

                            // show buttons based on user permissions
                            $actions = '
                            <div class="btn-group dropend my-1">
                                <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu">';
                            if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                                $actions .= '
                                    <a href="/client/application/info/' . $application->id . '" title="view application" class="dropdown-item"><i class="fas fa-eye text-success"></i> 
                                        View Application
                                    </a>
                                ';
                            }
                            if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                                $actions .= '
                                    <div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="edit_application(' . "'" . $application->id . "'" . ')" title="edit application" class="dropdown-item"><i class="fas fa-edit text-info"></i> 
                                        Edit Application
                                    </a>
                                ';
                            }
                            if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                                $actions .= '
                                    <div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="delete_application(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ')" title="delete application" class="dropdown-item"><i class="fas fa-trash text-danger"></i> 
                                        Delete Application
                                    </a>
                                ';
                            }
                            $actions .= ' 
                                </ul>
                            </div>';
                        }
                        break;
                    default:
                        $text = "info";
                        $actions = '
                        <div class="btn-group dropend my-1">
                            <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">';
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                            $actions .= '
                                <a href="' . base_url('client/application/info/' . $application->id) . '" title="view application" class="dropdown-item"><i class="fas fa-eye text-success"></i> 
                                    View Application
                                </a>
                            ';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="edit_application(' . "'" . $application->id . "'" . ')" title="edit application" class="dropdown-item"><i class="fas fa-edit text-info"></i> 
                                    Edit Application
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="cancelLoanApplication(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ')" title="Cancel Application" class="dropdown-item"><i class="fa fa-cancel text-danger"></i> 
                                    Cancel Application
                                </a>
                            ';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="delete_application(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ')" title="delete application" class="dropdown-item"><i class="fas fa-trash text-danger"></i> 
                                    Delete Application
                                </a>
                            ';
                        }
                        $actions .= ' 
                            </ul>
                        </div>';
                        break;
                }
                return $actions;
            })
            */
            ->toJson(true);
    }

    public function bulkDeleteLoanApplications()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->loanApplication->find($id);
                if ($data) {
                    if (file_exists("uploads/applications/collaterals/" . $data['security_photo']) && $data['security_photo']) {
                        # delete or remove the previous photo
                        unlink("uploads/applications/collaterals/" . $data['security_photo']);
                    }
                    if (file_exists("uploads/applications/nationalIDs/" . $data['ID_photo']) && $data['ID_photo']) {
                        # delete or remove the previous photo
                        unlink("uploads/applications/nationalIDs/" . $data['ID_photo']);
                    }
                    if (file_exists("uploads/applications/agreements/" . $data['loan_agreement']) && $data['loan_agreement']) {
                        # delete or remove the previous agreement
                        unlink("uploads/applications/agreements/" . $data['loan_agreement']);
                    }
                    if (file_exists("uploads/applications/signatures/" . $data['signature']) && $data['signature']) {
                        # delete or remove the previous signature
                        unlink("uploads/applications/signatures/" . $data['signature']);
                    }
                    $delete = $this->loanApplication->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['application_code']),
                            'module' => strtolower('applications'),
                            'referrer_id' => $id,
                        ];
                        $activity = $this->insertActivityLog($activityData);
                    } else {
                        $response = [
                            'status' => 500,
                            'error' => 'Delete Failed',
                            'messages' => 'Deleting ' . $this->title . ' failed, try again later!',
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
            }
            if ($activity) {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' record(s) deleted successfully',
                ];
                return $this->respond($response);
                exit;
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' record(s) deleted successfully. loggingFailed'
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }


    public function getDisbursementsList($class)
    {
        if (isset($class)) {
            $where = ['disbursements.deleted_at' => null, 'disbursements.class' => ucfirst($class)];
        } else {
            $where = ['disbursements.deleted_at' => null];
        }

        $disbursements = $this->disbursement->select('clients.id as client_id, clients.name, loanproducts.product_name, disbursements.disbursement_code,disbursements.principal, disbursements.actual_interest, disbursements.actual_repayment, disbursements.total_balance, disbursements.total_collected, disbursements.actual_installment, disbursements.class, disbursements.days_remaining, disbursements.id')
            ->join('clients', 'clients.id = disbursements.client_id')->join('loanproducts', 'loanproducts.id = disbursements.product_id')
            ->where($where);
        return DataTable::of($disbursements)
            ->add('checkbox', function ($disbursement) {
                return '<div class=""><input type="checkbox" class="data-check' . ucfirst($disbursement->class)  . '" value="' . $disbursement->id . '"></div>';
            })
            ->addNumbering("no")
            ->add('loan_balance', function ($disbursement) {
                $balance = $disbursement->total_balance;
                if ($balance > 0) {
                    if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                        $loan_balance =  number_format($balance) . '<br>
                            <a href="javascript:void(0)" onclick="adjust_disbursementBalance(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->disbursement_code . "'" . ')" title="Make Total Loan Balance Zero">
                                <i class="fa fa-scale-balanced text-primary"></i> Clear Balance
                            </a>';
                    } else {
                        $loan_balance = number_format($balance);
                    }
                } else {
                    $loan_balance = number_format($balance);
                }
                return $loan_balance;
            })
            ->add('expiry', function ($disbursement) {
                if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                    $expiry =  $disbursement->days_remaining . '<br>
                        <a href="javascript:void(0)" onclick="adjust_expiryDate(' . "'" . $disbursement->id . "'" . ')" title="Adjust Loan Expiry Date">
                            <i class="fa fa-calendar-day text-primary"></i> Adjust Date
                        </a>';
                } else {
                    $expiry = $disbursement->days_remaining;
                }
                return $expiry;
            })
            ->add('action', function ($disbursement) {
                switch (strtolower($disbursement->class)) {
                    case "running":
                        $text = "info";

                        // show buttons based on user permissions
                        $actions = '
                        <div class="btn-group dropend my-1">
                            <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">';
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                            $actions .= '
                                <a href="/client/disbursement/info/' . $disbursement->id . '" title="view disbursement" class="dropdown-item">
                                    <i class="fas fa-eye text-success"></i> View Disbursement
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="/admin/loan/agreement/disbursement/' . $disbursement->id . '" target="_blank" title="View Loan Agreement" class="dropdown-item">
                                    <i class="fas fa-clipboard-list text-primary"></i> View Agreement
                                </a>
                            ';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="add_disbursementPayment(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->client_id . "'" . ')" title="Make Installment Payment" class="dropdown-item">
                                    <i class="fa fa-money-bill-trend-up text-primary"></i> Pay Installment
                                </a>';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="delete_disbursement(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->name . "'" . ')" title="delete disbursement" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Disbursement</a>';
                        }
                        $actions .= ' 
                            </ul>
                        </div>';
                        break;
                    case "cleared":
                        $text = "success";

                        // show buttons based on user permissions
                        $actions = '
                        <div class="btn-group dropend my-1">
                            <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">';
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                            $actions .= '
                                <a href="/client/disbursement/info/' . $disbursement->id . '" title="view disbursement" class="dropdown-item">
                                    <i class="fas fa-eye text-success"></i> View Disbursement
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="/admin/loan/agreement/disbursement/' . $disbursement->id . '" target="_blank" title="View Loan Agreement" class="dropdown-item">
                                    <i class="fas fa-clipboard-list text-primary"></i> View Agreement
                                </a>';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="delete_disbursement(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->name . "'" . ')" title="delete disbursement" class="dropdown-item">
                                    <i class="fas fa-trash text-danger"></i> Delete Disbursement
                                </a>';
                        }
                        $actions .= ' 
                            </ul>
                        </div>';
                        break;
                    case "expired":
                        $text = "danger";

                        // show buttons based on user permissions
                        $actions = '
                        <div class="btn-group dropend my-1">
                            <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">';
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                            $actions .= '
                                <a href="/client/disbursement/info/' . $disbursement->id . '" title="view disbursement" class="dropdown-item">
                                    <i class="fas fa-eye text-success"></i> View Disbursement
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="/admin/loan/agreement/disbursement/' . $disbursement->id . '" target="_blank" title="View Loan Agreement" class="dropdown-item">
                                <i class="fas fa-clipboard-list text-primary"></i> View Agreement
                                </a>';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="delete_disbursement(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->name . "'" . ')" title="delete disbursement" class="dropdown-item">
                                    <i class="fas fa-trash text-danger"></i> Delete Disbursement
                                </a>';
                        }
                        $actions .= ' 
                            </ul>
                        </div>';
                        break;
                    case "arrears":
                        $text = "danger";

                        // show buttons based on user permissions
                        $actions = '
                        <div class="btn-group dropend my-1">
                            <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">';
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                            $actions .= '
                                <a href="/client/disbursement/info/' . $disbursement->id . '" title="view disbursement" class="dropdown-item">
                                    <i class="fas fa-eye text-success"></i> View Disbursement
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="/admin/loan/agreement/disbursement/' . $disbursement->id . '" target="_blank" title="View Loan Agreement" class="dropdown-item">
                                    <i class="fas fa-clipboard-list text-primary"></i> View Agreement
                                </a>';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="add_disbursementPayment(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->client_id . "'" . ')" title="Make Installment Payment" class="dropdown-item">
                                    <i class="fa fa-money-bill-trend-up text-primary"></i> Pay Installment
                                </a>';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="delete_disbursement(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->name . "'" . ')" title="delete disbursement" class="dropdown-item">
                                    <i class="fas fa-trash text-danger"></i> Delete Disbursement
                                </a>';
                        }
                        $actions .= ' 
                             </ul>
                        </div>';
                        break;
                    default:
                        $text = "info";

                        // show buttons based on user permissions
                        $actions = '
                        <div class="btn-group dropend my-1">
                            <i class="fa fa-ellipsis-v btn btn-sm btn-outline-' . $text . '" dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">';
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                            $actions .= '
                                <a href="/client/disbursement/info/' . $disbursement->id . '" title="view disbursement" class="dropdown-item">
                                    <i class="fas fa-eye text-success"></i> View Disbursement
                                </a>';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '';
                        }
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                            $actions .= '
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="delete_disbursement(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->name . "'" . ')" title="delete disbursement" class="dropdown-item">
                                    <i class="fas fa-trash text-danger"></i> Delete Disbursement
                                </a>';
                        }
                        $actions .= ' 
                            </ul>
                        </div>';
                        break;
                }
                return $actions;
            })
            ->toJson(true);
    }

    public function bulkDeleteDisbursements()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->disbursement->find($id);
                if ($data) {
                    $delete = $this->disbursement->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['disbursement_code']),
                            'module' => strtolower('disbursements'),
                            'referrer_id' => $id,
                        ];
                        $activity = $this->insertActivityLog($activityData);
                    } else {
                        $response = [
                            'status' => 500,
                            'error' => 'Delete Failed',
                            'messages' => 'Deleting ' . $this->title . ' failed, try again later!',
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
            }
            if ($activity) {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' deleted successfully',
                ];
                return $this->respond($response);
                exit;
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' record(s) deleted successfully. loggingFailed'
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }
}
