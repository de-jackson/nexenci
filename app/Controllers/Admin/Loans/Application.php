<?php

namespace App\Controllers\Admin\Loans;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;
use Config\Services;

class Application extends MasterController
{
    public function __construct()
    {
        parent::__construct();
        $this->encrypter = Services::encrypter();
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
     * @return mixed
     */

    public function index()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            return view('admin/loans/applications/index', [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'relations' => $this->settings->generateRelationships(),
                // 'charges' => $this->application_chargesParticulars(),
                'applicationsCounter' => $this->counter('applications'),
                'charges' => $this->getCharges([
                    'charges.status' => 'Active',
                    'p.account_typeId' => 18,
                    'p.particular_status' => 'Active',
                    'charges.product_id' => null
                ]),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    // show application details
    public function view_applicant($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $decryptedId = $this->encrypter->decrypt(hex2bin($id));
            $application = $this->loanApplicationRow($decryptedId);
            if ($application) {
                $disbursement = $this->disbursement->find($decryptedId);

                $principal = trim($application['principal']);
                $particulars = unserialize($application['overall_charges']);

                // print_r($particulars); exit;
                # Get Applicantion charges
                # $loanCharges = $this->report->getLoanProductCharges($particulars, $principal);

                return view('admin/loans/applications/applicant_form', [
                    'title' => $application['application_code'],
                    'application' => $application,
                    'disbursement' => $disbursement,
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                    'relations' => $this->settings->generateRelationships(),
                    'charges' => $this->getCharges([
                        'charges.status' => 'Active',
                        'p.account_typeId' => 18,
                        'p.particular_status' => 'Active',
                        'charges.product_id' => null
                    ]),
                    'loanCharges' => $particulars,
                    'decryptedId' => $decryptedId
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Application could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    public function application_forms($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            if ($id == 0) {
                $title = "Application Form";
            } else {
                $title = "Application View Form";
            }
            return view('admin/loans/applications/application_formPDF', [
                'title' => $title,
                'id' => $id,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'relations' => $this->settings->generateRelationships(),
                'application' => $this->loanApplicationRow($id),
                'incomes' => $this->file->where(['application_id' => $id, 'type' => 'income'])->findAll(),
                'expenses' => $this->file->where(['application_id' => $id, 'type' => 'expense'])->findAll(),
                'collaterals' => $this->file->where(['application_id' => $id, 'type' => 'collateral'])->findAll(),
                'payables' => $this->particular->where(['account_typeId' => 18])->findAll(),
                'payments' => $this->entry
                    ->select('entries.payment_id, entries.particular_id, entries.amount, entries.ref_id, entries.created_at, debitParticular.particular_name as payment_id, creditParticular.particular_name as particular_id, staffs.staff_name, clients.name')
                    ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                    ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('clients', 'clients.id = entries.client_id', 'left')
                    ->where(['application_id' => $id])->findAll(),
                'remarks' => $this->applicationRemark
                    ->select('applicationremarks.*, staffs.staff_name, loanapplications.application_code')
                    ->join('staffs', 'staffs.id = applicationremarks.staff_id', 'left')
                    ->join('loanapplications', 'loanapplications.id = applicationremarks.application_id', 'left')
                    ->where(['application_id' => $id])->findAll(),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    /**
     * return all applications as rows
     */
    public function applications_list($status)
    {
        $where = ['loanapplications.deleted_at' => null, 'loanapplications.status' => ucfirst($status)];
        $applications = $this->loanApplication->select('clients.id as client_id, clients.name, clients.photo, loanproducts.product_name, loanproducts.repayment_duration, loanapplications.principal, loanapplications.application_code, loanapplications.status, loanapplications.level, loanapplications.action, loanapplications.id, applicant_products.interest_rate, applicant_products.repayment_frequency, applicant_products.repayment_period, applicant_products.interest_period, clients.photo, clients.account_no')
            ->join('clients', 'clients.id = loanapplications.client_id', 'left')
            ->join('loanproducts', 'loanproducts.id = loanapplications.product_id', 'left')
            ->join('applicant_products', 'applicant_products.application_id = loanapplications.id', 'left')
            ->where($where);

        return DataTable::of($applications)
            ->add('checkbox', function ($application) {
                return '<div class=""><input type="checkbox" class="data-check' . ucfirst($application->status) . '" value="' . $application->id . '"></div>';
            })
            ->add('name', function ($client) {
                # check whether the photo exist
                if (file_exists("uploads/clients/passports/" . $client->photo) && $client->photo) {
                    # display the current photo
                    $photo = '
                    <a href="javascript:void(0)" onclick="view_client_photo(' . "'" . $client->id . "'" . ')" title="' . strtoupper($client->name) . '"><img src="' . base_url('uploads/clients/passports/' . $client->photo) . '" style="width:40px;height:40px;" class="avatar avatar-md" /></a>
                    ';
                } else {
                    # display the default photo
                    $photo =  '
                    <a href="javascript:void(0)" onclick="view_client_photo(' . "'" . $client->id . "'" . ')" title="No photo"><img src="' . base_url('assets/dist/img/nophoto.jpg') . '" style="width:40px;height:40px;" class="avatar avatar-md" /></a>
                    ';
                }

                return '<div class="products">
                ' . $photo . '
                    <div>
                        <h6>' . strtoupper($client->name) . '</h6>
                        <span>' . $client->account_no . '</span>	
                    </div>	
                </div>';
            })
            ->addNumbering("no")
            ->add('rate', function ($application) {
                if (!empty($application->interest_period)) {
                    # code...
                    $symbol = ' per ';
                } else {
                    $symbol = '';
                }

                return $application->interest_rate . $symbol . $application->interest_period;
            })
            ->add('period', function ($application) {
                # check the loan repayment frequence
                $frequency = $application->repayment_frequency;
                if (!empty($frequency)) {
                    # code...
                    $other = $this->loanProduct->getOtherLoanProduct($frequency);
                    $duration = ' ' . $other['duration'];
                } else {
                    $duration = NULL;
                }
                return $application->repayment_period . $duration;
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
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">';
                $statusBtns = '<a href="javascript:void(0)" onclick="application_status(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ',' . "'Cancelled'" . ')" title="Cancel ' . $application->application_code . '" class="dropdown-item"><i class="fa fa-cancel text-warning"></i> Cancel ' . $application->application_code . '</a>
                <div class="dropdown-divider"></div>
                <a href="javascript:void(0)" onclick="application_status(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ',' . "'Declined'" . ')" title="Decline ' . $application->application_code . '" class="dropdown-item"><i class="fa fa-circle-stop text-danger"></i> Decline ' . $application->application_code . '</a>
                <div class="dropdown-divider"></div>';
                # show buttons based on user permissions
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                    $encryptedId = bin2hex($this->encrypter->encrypt($application->id));
                    $actions .= '
                        <a href="/admin/application/info/' . $encryptedId . '" title="View ' . $application->application_code . '" class="dropdown-item"><i class="fas fa-eye text-success"></i> View ' . $application->application_code . '</a>';
                }

                if ($application_status == "approved") {
                    # show view agreement button
                    if (($this->userPermissions == 'all') || (in_array('create_loansDisbursements', $this->userPermissions))) {
                        $actions .= '
                        <div class="dropdown-divider"></div>
                        <a href="/admin/loan/agreement/application/' . $application->id . '" target="_blank" title="View Loan Agreement" class="dropdown-item"><i class="fas fa-clipboard-list text-primary"></i> View Agreement</a>
                            <div class="dropdown-divider"></div>';
                    }
                    # show quick disbursement button
                    if (($this->userPermissions == 'all') || (in_array('create_loansDisbursements', $this->userPermissions))) {
                        $actions .= '
                            <a href="javascript:void(0)" onclick="add_applicationRemarks(' . "'" . $application->id . "'" . ',' . "'disburse'" . ')" title="Disburse ' . $application->application_code . '" class="dropdown-item"><i class="fas fa-money-bill-trend-up text-info"></i> Quick Disburse ' . $application->application_code . '</a>
                <div class="dropdown-divider"></div>' . $statusBtns;
                    }
                }

                if ($application_status == "pending" || $application_status == "processing") {
                    if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                        $actions .= '
                            <a href="javascript:void(0)" onclick="edit_application(' . "'" . $application->id . "'" . ')" title="Edit ' . $application->application_code . '" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit ' . $application->application_code . '</a>
                            <div class="dropdown-divider"></div>' . $statusBtns;
                    }
                }

                if ($application_status == "processing") {
                    if (!empty($application->level)) {
                        # show add remarks button
                        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                            $actions .= '
                            <a href="javascript:void(0)" onclick="add_applicationRemarks(' . "'" . $application->id . "'" . ')" title="Add ' . $application->application_code . ' Remarks" class="dropdown-item"><i class="fas fa-comments text-warning"></i> Add ' . $application->application_code . ' Remarks</a>
                <div class="dropdown-divider"></div>';
                        }
                    }
                }

                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '
                    <a href="javascript:void(0)" onclick="delete_application(' . "'" . $application->id . "'" . ',' . "'" . $application->application_code . "'" . ')" title="Delete ' . $application->application_code . '" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete ' . $application->application_code . '</a>';
                }

                $actions .= ' 
                    </div>
                </div>';

                return $actions;
            })
            ->toJson(true);
    }
    public function applications_report($filter, $opted, $val = null, $parm = null, $from = null, $to = null)
    {
        switch (strtolower($filter)) {
            case "status":
                if ($from != 0 && $to == 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.principal >' => $val, 'loanapplications.status' => ucfirst($opted), 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.principal >' => $parm, 'loanapplications.status' => ucfirst($opted), 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.status' => ucfirst($opted), 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.status' => ucfirst($opted), 'loanapplications.deleted_at' => Null];
                    }
                } elseif ($from == 0 && $to != 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.principal >' => $val, 'loanapplications.status' => ucfirst($opted), 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.principal >' => $parm, 'loanapplications.status' => ucfirst($opted), 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.status' => ucfirst($opted), 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.status' => ucfirst($opted), 'loanapplications.deleted_at' => Null];
                    }
                } elseif ($from != 0 && $to != 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.status' => ucfirst($opted), 'loanapplications.principal >' => $val, 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.status' => ucfirst($opted), 'loanapplications.principal >' => $parm, 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.status' => ucfirst($opted), 'loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.status' => ucfirst($opted), 'loanapplications.deleted_at' => Null];
                    }
                } else {
                    if ($val != 0 && $parm == 0) {
                        $where = ['loanapplications.status' => ucfirst($opted), 'loanapplications.principal >' => $val, 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['loanapplications.status' => ucfirst($opted), 'loanapplications.principal >' => $parm, 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['loanapplications.status' => ucfirst($opted), 'loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['loanapplications.status' => ucfirst($opted), 'loanapplications.deleted_at' => Null];
                    }
                }
                break;
            case "product":
                if ($from != 0 && $to == 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.product_id' => $opted, 'loanapplications.principal >' => $val, 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.product_id' => $opted, 'loanapplications.principal >' => $parm, 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.product_id' => $opted, 'loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.product_id' => $opted, 'loanapplications.deleted_at' => Null];
                    }
                } elseif ($from == 0 && $to != 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.product_id' => $opted, 'loanapplications.principal >' => $val, 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.product_id' => $opted, 'loanapplications.principal >' => $parm, 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.product_id' => $opted, 'loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.product_id' => $opted, 'loanapplications.deleted_at' => Null];
                    }
                } elseif ($from != 0 && $to != 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.product_id' => $opted, 'loanapplications.principal >' => $val, 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.product_id' => $opted, 'loanapplications.principal >' => $parm, 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.product_id' => $opted, 'loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.product_id' => $opted, 'loanapplications.deleted_at' => Null];
                    }
                } else {
                    if ($val != 0 && $parm == 0) {
                        $where = ['loanapplications.product_id' => $opted, 'loanapplications.principal >' => $val, 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['loanapplications.product_id' => $opted, 'loanapplications.principal >' => $parm, 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['loanapplications.product_id' => $opted, 'loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['loanapplications.product_id' => $opted, 'loanapplications.deleted_at' => Null];
                    }
                }
                break;
            default:
                if ($from != 0 && $to == 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.principal >' => $val, 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.principal >' => $parm, 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'loanapplications.deleted_at' => Null];
                    }
                } elseif ($from == 0 && $to != 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.principal >' => $val, 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.principal >' => $parm, 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $to, 'loanapplications.deleted_at' => Null];
                    }
                } elseif ($from != 0 && $to != 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.principal >' => $val, 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.principal >' => $parm, 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") <=' => $to, 'loanapplications.deleted_at' => Null];
                    }
                } else {
                    if ($val != 0 && $parm == 0) {
                        $where = ['loanapplications.principal >' => $val, 'loanapplications.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['loanapplications.principal >' => $parm, 'loanapplications.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['loanapplications.principal >' => $val, 'loanapplications.principal <' => $parm, 'loanapplications.deleted_at' => Null];
                    } else {
                        $where = ['loanapplications.deleted_at' => Null];
                    }
                }
                break;
        }
        $applications = $this->loanApplication->select('clients.id as client_id ,clients.name, loanproducts.product_name, loanapplications.principal,  loanapplications.application_code, loanapplications.status, loanapplications.level, loanapplications.id')->join('clients', 'clients.id = loanapplications.client_id', 'left')->join('loanproducts', 'loanproducts.id = loanapplications.product_id', 'left')->where($where);
        return DataTable::of($applications)
            ->add('checkbox', function ($application) {
                return '<div class=""><input type="checkbox" class="data-check" value="' . $application->id . '"></div>';
            })
            ->addNumbering("no")
            ->add('action', function ($application) {
                switch (strtolower($application->status)) {
                    case 'pending':
                        if (strtolower($application->level) == null) {
                            $text = "text-secondary";
                        } else {
                            $text = "text-warning";
                        }
                        break;
                    case 'approved':
                        $text = "text-info";
                        break;
                    case 'disbursed':
                        $text = "text-success";
                        break;
                    case 'declined':
                        $text = "text-danger";
                        break;
                    default:
                        $text = "text-primary";
                        break;
                }
                return '
                    <div class="text-center">
                        <a href="/admin/application/info/' . $application->id . '" title="view application" class="' . $text . '"><i class="fas fa-eye"></i></a>
                    </div>
                ';
            })
            ->toJson(true);
    }
    /** fetch application files */
    public function applicationFiles($app_id = null, $type = null)
    {
        if (strtolower($type) == 'collateral') {
            $where = ['application_id' => $app_id, 'type' => 'collateral', 'deleted_at' => null];
        } else if (strtolower($type) == 'income') {
            $where = ['application_id' => $app_id, 'type' => 'income', 'deleted_at' => null];
        } else if (strtolower($type) == 'expense') {
            $where = ['application_id' => $app_id, 'type' => 'expense', 'deleted_at' => null];
        } else if (strtolower($type) == 'files') {
            $where = ['application_id' => $app_id, 'type !=' => 'collateral', 'deleted_at' => null];
        } else {
            $where = ['application_id' => $app_id, 'deleted_at' => null];
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

                return '<div class="dropdown custom-dropdown mb-0">
                <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                </div>
                <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0)" onclick="view_file(' . "'" . $file->id . "'" . ')" title="view file" class="dropdown-item"><i class="fas fa-eye text-success"></i> View File</a>
                        <div class="dropdown-divider"></div>
                        <a href="' . base_url('admin/loan/application/files/download/' . $file->id) . '" title="download file" class="dropdown-item"><i class="fas fa-download text-info"></i> Download File</a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="delete_file(' . "'" . $file->id . "'" . ',' . "'" . $file->file_name . "'" . ')" title="delete file" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete File</a>
                        </div>
                </div>';
            })
            ->toJson(true);
    }
    // fetch all application remarks
    public function getApplicationRemarks($appId)
    {
        $data = $this->applicationRemark->select('applicationremarks.*, staffs.staff_name, loanapplications.application_code')
            ->join('staffs', 'staffs.id = applicationremarks.staff_id', 'left')
            ->join('loanapplications', 'loanapplications.id = applicationremarks.application_id', 'left')
            ->where(['application_id' => $appId])->orderBy('id', 'DESC')->findAll();
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requsted ' . $this->title . ' Remarks resource could not be found!',
            ];
            return $this->respond($response);
        }
    }

    // download application files
    public function download($id)
    {
        helper("download");
        $fileData = $this->file->find($id);
        if ($fileData) {
            switch (strtolower($fileData['type'])) {
                case 'collateral':
                    if (
                        file_exists("uploads/applications/collaterals/" . $fileData['file_name']) &&
                        $fileData['file_name']
                    ) {
                        $path = base_url('uploads/applications/collaterals/' . $fileData['file_name']);
                        $data = file_get_contents($path);
                        force_download($fileData['file_name'], $data);
                        exit;
                    }
                    break;
                case 'income':
                    if (
                        file_exists("uploads/applications/income/" . $fileData['file_name']) &&
                        $fileData['file_name']
                    ) {
                        $incomepath = base_url('uploads/applications/income/' . $fileData['file_name']);
                        $data = file_get_contents($incomepath);
                        force_download($fileData['file_name'], $data);
                        exit;
                    }
                    break;
                case 'expense':
                    if (
                        file_exists("uploads/applications/expense/" . $fileData['file_name']) &&
                        $fileData['file_name']
                    ) {
                        $path = base_url('uploads/applications/expense/' . $fileData['file_name']);
                        $data = file_get_contents($path);
                        force_download($fileData['file_name'], $data);
                        exit;
                    }
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->loanApplication->find($id);
        if ($data) {
            return $this->respond($this->loanApplicationRow($id));
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requsted ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
        }
    }
    /**
     * get all loan applications
     */
    public function getApplications()
    {
        $data = $this->loanApplication->findAll();;
        return $this->respond($data);
    }

    // get the different application statues
    public function get_applicationActions()
    {
        return $this->respond($this->settings->generateLoanApplicationActions());
        // return $this->respond(['Approved', 'Declined']);
    }
    // get the different application levels
    public function get_applicationLevels()
    {
        // return $this->respond(['Credit Officer', 'Supervisor', 'Operations Officer', 'Accounts Officer']);
        return $this->respond($this->settings->generateLoanApplicationLevels());
    }
    /** show application files */
    public function show_file($id = null)
    {
        $data = $this->file
            ->select('files.*,clients.name')
            ->join('loanapplications', 'loanapplications.id = files.application_id', 'left')
            ->join('clients', 'clients.id = loanapplications.client_id', 'left')
            ->find($id);
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requsted ' . $this->title . ' File resource could not be found!',
            ];
            return $this->respond($response);
        }
    }
    /** show application remark */
    public function show_remark($id = null)
    {
        $data = $this->applicationRemark
            ->select('applicationremarks.*, loanapplications.application_code, staffs.staff_name, staffs.staffID')
            ->join('staffs', 'staffs.id = applicationremarks.staff_id', 'left')
            ->join('loanapplications', 'loanapplications.id = applicationremarks.application_id', 'left')
            ->find($id);
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requsted ' . $this->title . ' Remarks resource could not be found!',
            ];
            return $this->respond($response);
        }
    }
    // get client with pending applications
    public function pendingApplications_clients()
    {
        $allowedStatus = ['Pending','Processing','Approved'];
        $clientIDs = $this->loanApplication->distinct()->select('client_id')->whereIn('loanapplications.status', $allowedStatus)->findColumn('client_id');
        if ($clientIDs) {
            $clients = $this->client->find($clientIDs);
            return $this->respond(($clients));
        } else {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'No Clients With Running Applications found!',
            ];
            return $this->respond($response);
            exit;
        }
        
    }
    // client pending applications
    public function client_pendingApplications($client_id = null)
    {
        $allowedStatus = ['Pending','Processing','Approved'];
        $data = $this->loanApplication->select('loanapplications.*, loanproducts.product_name')
            ->where(['client_id' => $client_id])->whereIn('loanapplications.status', $allowedStatus)
            ->join('loanproducts', 'loanproducts.id = loanapplications.product_id', 'left')
            ->findAll();
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'No Applications found for client!',
            ];
            return $this->respond($response);
            exit;
        }
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
                // client data
                $clientRow = $this->client->find($client_id);
                // $clientRow = $this->checkArrayExistance('client', ['id' => $client_id]);
                if (!$clientRow) {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Client data could not be found!' . $client_id,
                    ];
                    return $this->respond($response);
                    exit;
                }
                $product_id = trim($this->request->getVar('product_id'));
                $productRow = $this->product($product_id);
                if (!$productRow) {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Product data could not be found!',
                    ];
                    return $this->respond($response);
                    exit;
                }
                // $principal = trim($this->request->getVar('principal'));
                $principal = $this->removeCommasFromAmount($this->request->getVar('principal'));
                $productCharges = $productRow['charges'];
                # Get Application charges
                $charges = $this->report->getLoanProductCharges($productCharges, $principal);
                $application_code = $this->settings->generateUniqueNo('application');
                $data = [
                    'application_code' =>  $application_code,
                    'application_date' => trim($this->request->getVar('application_date')),
                    'client_id' => $client_id,
                    'staff_id' => $this->userRow['staff_id'],
                    'product_id' => $product_id,
                    'branch_id' => $this->userRow['branch_id'],
                    'principal' => $principal,
                    'purpose' => trim($this->request->getVar('purpose')),
                    'overall_charges' => serialize($productCharges),
                    'total_charges' => $charges['totalCharges'],
                    'reduct_charges' => trim($this->request->getVar('reduct_charges')),
                    'security_item' => trim($this->request->getVar('security_item')),
                    'security_info' => trim($this->request->getVar('security_info')),
                    'est_value' => trim($this->request->getVar('est_value')),
                    'ref_name' => trim($this->request->getVar('ref_name')),
                    'ref_relation' => trim($this->request->getVar('ref_relation')),
                    'ref_job' => trim($this->request->getVar('ref_job')),
                    'ref_contact' => trim($this->request->getVar('ref_contact_full')),
                    'ref_alt_contact' => trim($this->request->getVar('ref_alt_contact_full')),
                    'ref_email' => trim($this->request->getVar('ref_email')),
                    'ref_address' => trim($this->request->getVar('ref_address')),
                    'ref_name2' => trim($this->request->getVar('ref_name2')),
                    'ref_relation2' => trim($this->request->getVar('ref_relation2')),
                    'ref_job2' => trim($this->request->getVar('ref_job2')),
                    'ref_contact2' => trim($this->request->getVar('ref_contact2_full')),
                    'ref_alt_contact2' => trim($this->request->getVar('ref_alt_contact2_full')),
                    'ref_email2' => trim($this->request->getVar('ref_email2')),
                    'ref_address2' => trim($this->request->getVar('ref_address2')),
                    'net_salary' =>
                    $this->removeCommasFromAmount($this->request->getVar('net_salary')),
                    'farming' =>
                    $this->removeCommasFromAmount($this->request->getVar('farming')),
                    'business' =>
                    $this->removeCommasFromAmount($this->request->getVar('business')),
                    'others' =>
                    $this->removeCommasFromAmount($this->request->getVar('others')),
                    'rent' =>
                    $this->removeCommasFromAmount($this->request->getVar('rent')),
                    'education' =>
                    $this->removeCommasFromAmount($this->request->getVar('education')),
                    'medical' =>
                    $this->removeCommasFromAmount($this->request->getVar('medical')),
                    'transport' =>
                    $this->removeCommasFromAmount($this->request->getVar('transport')),
                    'exp_others' =>
                    $this->removeCommasFromAmount($this->request->getVar('exp_others')),
                    'difference' => trim($this->request->getVar('difference')),
                    'dif_status' => trim($this->request->getVar('dif_status')),
                    'institute_name' => trim($this->request->getVar('institute_name')),
                    'institute_branch' => trim($this->request->getVar('institute_branch')),
                    'account_type' => $this->request->getVar('account_type'),
                    'institute_name2' => trim($this->request->getVar('institute_name2')),
                    'institute_branch2' => trim($this->request->getVar('institute_branch2')),
                    'account_type2' => $this->request->getVar('account_type2'),
                    'amt_advance' => $this->removeCommasFromAmount($this->request->getVar('amt_advance')),
                    'date_advance' => trim($this->request->getVar('date_advance')),
                    'loan_duration' => trim($this->request->getVar('loan_duration')),
                    'amt_outstanding' => $this->removeCommasFromAmount($this->request->getVar('amt_outstanding')),
                    'amt_advance2' => $this->removeCommasFromAmount($this->request->getVar('amt_advance2')),
                    'date_advance2' => trim($this->request->getVar('date_advance2')),
                    'loan_duration2' => trim($this->request->getVar('loan_duration2')),
                    'amt_outstanding2' => $this->removeCommasFromAmount($this->request->getVar('amt_outstanding2')),
                    'account_id' => $this->userRow['account_id'],
                ];
                $insert = $this->loanApplication->insert($data);
                # Create applicant loan product in a multidimensional array
                $applicantProduct = [
                    'application_id' => $insert,
                    'product_id' => trim($this->request->getVar('product_id')),
                    'interest_rate' => trim($this->request->getVar('interest_rate')),
                    'interest_period' => trim($this->request->getVar('interest_period')),
                    'interest_type' => trim($this->request->getVar('interest_type')),
                    'loan_period' => trim($this->request->getVar('loan_period')),
                    'loan_frequency' => trim($this->request->getVar('loan_frequency')),
                    'repayment_frequency' => trim($this->request->getVar('repayment_freq')),
                    'repayment_period' => trim($this->request->getVar('repayment_period')),
                ];
                $this->applicantProduct->insert($applicantProduct);
                $index = 1;
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
                                    'account_id' => $this->userRow['account_id'],
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
                    $checkInternet = $this->settings->checkNetworkConnection();
                    if ($checkInternet) {
                        # send mail notification
                        $data['name'] = $clientRow['name'];
                        $data['email'] = $clientRow['email'];
                        $data['account_type'] = $clientRow['account_type'];
                        $data['branch_name'] = $this->userRow['branch_name'];
                        $data['product_name'] = $productRow['product_name'];
                        # $data['frequency'] = $productRow['repayment_freq'];
                        # $data['rate'] = $productRow['interest_rate'];
                        # $data['period'] = $productRow['repayment_period'] . ' ' . $productRow['repayment_duration'];

                        $data['module'] = 'apply';
                        $data['date'] = date('d-m-Y H:i:s');

                        # get applicant loan product
                        $data['rate'] = trim($this->request->getVar('interest_rate'));
                        $data['interest_period'] = trim($this->request->getVar('interest_period'));
                        $data['interest_type'] = trim($this->request->getVar('interest_type'));
                        $data['frequency'] = trim($this->request->getVar('repayment_freq'));
                        $data['period'] = trim($this->request->getVar('repayment_period')) . ' ' . $productRow['repayment_duration'];
                        /*
                        $data = [
                            'rate' => trim($this->request->getVar('interest_rate')),
                            'interestPeriod' => trim($this->request->getVar('interest_period')),
                            'InterestType' => trim($this->request->getVar('interest_type')),
                            'frequency' => trim($this->request->getVar('repayment_freq')),
                            'period' => trim($this->request->getVar('repayment_period')) . ' ' . $productRow['repayment_duration'],
                        ];
                        */
                        # check the email existence and email notify is enabled
                        if (!empty($clientRow['email']) && $this->settingsRow['email']) {
                            $subject = "Loan Application";
                            $message = $data;
                            $token = 'application';
                            $this->settings->sendMail($message, $subject, $token);
                        }

                        # check the phone number existence and sms notify is enabled
                        if (!empty($clientRow['mobile']) && $this->settingsRow['sms']) {
                            # send sms
                            $sms = $this->sendSMS([
                                'mobile' => trim($clientRow['mobile']),
                                'text' => 'Your ' . trim($this->request->getVar('repayment_freq')) . ' loan application for ' . strtolower($productRow['product_name']) . ' of ' . $this->settingsRow['currency'] . ' ' . number_format($principal) . ' was submitted successfully. Application code: ' . $application_code . ' ~ ' . strtoupper($this->settingsRow["system_abbr"])
                            ]);
                        }
                    }
                }
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

    /** add new application files */
    public function new_file($module)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'create')) {
            $id = $this->request->getVar('application_id');
            $data = $this->loanApplication->find($id);
            $file_id = $this->clientApplicationFiles($id, $module);
            if ($file_id) {
                // insert into activity logs
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'upload',
                    'description' => ucfirst('uploaded ' . $this->title . ', ' . $module . ' file(s). application: ' . $data['application_code']),
                    'module' => strtolower('applications'),
                    'referrer_id' => $id,
                ];
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => $this->title . ' ' . $module . ' file(s) ' . ' uploaded successfully',
                    ];
                    return $this->respond($response);
                    exit;
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => $this->title . ' ' . $module . ' file(s) ' . ' uploaded successfully. loggingFailed'
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Upload Failed',
                    'messages' => 'Uploading ' . $this->title . ' ' . $module . ' file(s) ' . ' failed, try again later!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to upload ' . $this->title . ' ' . $module . ' file(s) ' . '!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update_application($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateApplication("update");
                $applicationData = $this->loanApplication->find($id);
                # Get applicant loan product data
                $applicantProduct = $this->applicantProduct->where(['application_id' => $id])->first();

                if ($applicationData) {
                    $principal = $this->removeCommasFromAmount($this->request->getVar('principal'));
                    $product_id = trim($this->request->getVar('product_id'));
                    $productRow = $this->product($product_id);
                    if ($productRow) {
                        $productCharges = $productRow['charges'];
                    } else {
                        $productCharges = unserialize($applicationData['overall_charges']);
                    }

                    // $principal = trim($this->request->getVar('principal'));
                    $productCharges = $productRow['charges'];
                    # Get Application charges
                    $charges = $this->report->getLoanProductCharges($productCharges, $principal);

                    $data = [
                        'application_date' => trim($this->request->getVar('application_date')),
                        'client_id' => trim($this->request->getVar('client_id')),
                        'client_id' => trim($this->request->getVar('client_id')),
                        // 'staff_id' =>  $this->userRow['staff_id'],
                        'product_id' => trim($this->request->getVar('product_id')),
                        'principal' => $this->removeCommasFromAmount($this->request->getVar('principal')),
                        'purpose' => trim($this->request->getVar('purpose')),
                        'overall_charges' => serialize($productCharges),
                        'total_charges' => $charges['totalCharges'],
                        'reduct_charges' => trim($this->request->getVar('reduct_charges')),
                        'security_item' => trim($this->request->getVar('security_item')),
                        'security_info' => trim($this->request->getVar('security_info')),
                        'est_value' => trim($this->request->getVar('est_value')),
                        'ref_name' => trim($this->request->getVar('ref_name')),
                        'ref_relation' => trim($this->request->getVar('ref_relation')),
                        'ref_job' => trim($this->request->getVar('ref_job')),
                        'ref_contact' => trim($this->request->getVar('ref_contact_full')),
                        'ref_alt_contact' => trim($this->request->getVar('ref_alt_contact_full')),
                        'ref_email' => trim($this->request->getVar('ref_email')),
                        'ref_address' => trim($this->request->getVar('ref_address')),
                        'ref_name2' => trim($this->request->getVar('ref_name2')),
                        'ref_relation2' => trim($this->request->getVar('ref_relation2')),
                        'ref_job2' => trim($this->request->getVar('ref_job2')),
                        'ref_contact2' => trim($this->request->getVar('ref_contact2_full')),
                        'ref_alt_contact2' => trim($this->request->getVar('ref_alt_contact2_full')),
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
                        # Update applicant loan product in a multidimensional array
                        $applicantProductData = [
                            'product_id' => trim($this->request->getVar('product_id')),
                            'interest_rate' => trim($this->request->getVar('interest_rate')),
                            'interest_period' => trim($this->request->getVar('interest_period')),
                            'interest_type' => trim($this->request->getVar('interest_type')),
                            'loan_period' => trim($this->request->getVar('loan_period')),
                            'loan_frequency' => trim($this->request->getVar('loan_frequency')),
                            'repayment_frequency' => trim($this->request->getVar('repayment_freq')),
                            'repayment_period' => trim($this->request->getVar('repayment_period')),
                        ];
                        # Get the applicant product id to update the applicant product
                        $applicant_product_id = $this->request->getVar('applicant_product_id');
                        $this->applicantProduct->update($applicant_product_id, $applicantProductData);
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

    // cancel loan application
    public function application_status()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            $id = $this->request->getVar('application_id');
            $status = $this->request->getVar('status');
            $applicationRow = $this->loanApplicationRow($id);
            if (!$applicationRow) {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'Application record not found',
                ];
                return $this->respond($response);
                exit;
            }
            $clientRow  = $this->client->find($applicationRow['client_id']);
            if (!$clientRow) {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'Client record not found',
                ];
                return $this->respond($response);
                exit;
            }
            $productRow = $this->loanProduct->find($applicationRow['product_id']);
            if (!$productRow) {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'Product record not found',
                ];
                return $this->respond($response);
                exit;
            }
            if (strtolower($applicationRow['status']) == strtolower($status)) {
                $response = [
                    'status' => 500,
                    'error' => 'Status Error!',
                    'messages' => 'Application is already ' . $status . '!',
                ];
                return $this->respond($response);
                exit;
            }
            $update = $this->loanApplication->update($id, [
                'status' => $status,
            ]);
            if ($update) {
                // insert into activity logs
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'update',
                    'description' => ucfirst('updated ' . $this->title . ', status: Cancelled, application: ' . $applicationRow['application_code']),
                    'module' => strtolower('applications'),
                    'referrer_id' => $id,
                ];
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    # send mail notification
                    $checkInternet = $this->settings->checkNetworkConnection();
                    if ($checkInternet) {
                        $applicationRow['branch_name'] = $this->userRow['branch_name'];
                        $applicationRow['module'] = 'processing';
                        $applicationRow['date'] = date('d-m-Y H:i:s');
                        # check the email existence and email notify is enabled
                        if (!empty($clientRow['email']) && $this->settingsRow['email']) {
                            $subject = "Loan Application";
                            $message = $applicationRow;
                            $token = 'application';
                            $this->settings->sendMail($message, $subject, $token);
                        }
                        # check the phone number existence and sms notify is enabled
                        if (!empty($clientRow['mobile']) && $this->settingsRow['sms']) {
                            # send sms
                            $sms = $this->sendSMS([
                                'mobile' => trim($clientRow['mobile']),
                                'text' => 'Your ' . trim($applicationRow['repayment_frequency']) . ' loan application for ' . strtolower($productRow['product_name']) . ' of ' . $this->settingsRow['currency'] . ' ' . number_format($applicationRow['principal']) . ' was ' . $applicationRow['status'] . ' successfully. Application code: ' . $applicationRow['application_code'] . ' ~ ' . strtoupper($this->settingsRow["system_abbr"])
                            ]);
                        }
                    }
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => "Application " . $applicationRow['status'] . " successfully"
                    ];
                    return $this->respond($response);
                    exit;
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => 'Application ' . $applicationRow['status'] . ' successfully. loggingFailed'
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
    // save status of loan application
    public function save_remark()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            $this->_validateApplicationRemarks();
            $application_id = trim($this->request->getVar('application_id'));
            $applicationRow = $this->loanApplicationRow($application_id);
            $client_id = trim($this->request->getVar('client_id'));
            $oldLevel = trim($this->request->getVar('level'));
            $action = trim($this->request->getVar('action'));
            $remarks = trim($this->request->getVar('loan_remarks'));
            if (!$applicationRow) {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'Application record not found',
                ];
                return $this->respond($response);
                exit;
            }
            $clientRow  = $this->client->find($client_id);
            if (!$clientRow) {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'Client record not found',
                ];
                return $this->respond($response);
                exit;
            }
            $productRow = $this->loanProduct->find($applicationRow['product_id']);
            if (!$productRow) {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'Product record not found',
                ];
                return $this->respond($response);
                exit;
            }
            # update status and level based on action
            $applicationStatus = $this->loanApplication->setApplicationRemarkProcess($oldLevel, $action, $applicationRow['status']);
            $status = $applicationStatus['status'];
            $remarksData = [
                'application_id' => $application_id,
                'staff_id' =>  $this->userRow['staff_id'],
                'status' => $status,
                'action' => $action,
                'level' => $oldLevel,
                'remarks' => $remarks,
                'account_id' => $this->userRow['account_id'],
            ];

            // update application status
            $updateStatus = $this->loanApplication->update($application_id, $applicationStatus);
            if ($updateStatus) {
                // insert into remarks table
                $insertRemark = $this->applicationRemark->insert($remarksData);
                if ($insertRemark) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'create',
                        'description' => ucfirst('added remarks for application: ' . $applicationRow['application_code']) . ' status: ' . $status . ' level: ' . $oldLevel . 'action' . $action,
                        'module' => strtolower('applications'),
                        'referrer_id' => $application_id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        # send mail notification
                        if (!empty($clientRow['email']) && $this->settingsRow['email']) {
                            $applicationRow['branch_name'] = $this->userRow['branch_name'];
                            $applicationRow['module'] = 'remarks';
                            $applicationRow['newStatus'] = $remarksData['status'];
                            $applicationRow['newLevel'] = $remarksData['level'];
                            $applicationRow['newAction'] = $remarksData['action'];
                            $applicationRow['remarks'] = $remarksData['remarks'];
                            # applicant loan product
                            $applicantProduct = $applicationRow['applicant_products'];
                            $applicationRow['rate'] = $applicantProduct['interest_rate'];
                            $applicationRow['repayment_freq'] = $applicantProduct['repayment_frequency'];
                            $applicationRow['repayment_period'] = $applicantProduct['repayment_period'];
                            # end of applicant loan product

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
                                    'messages' => "Application Status updated successfully. Email Sent"
                                ];
                                return $this->respond($response);
                                exit;
                            } else {
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => "Application Status updated successfully.  No Internet"
                                ];
                                return $this->respond($response);
                                exit;
                            }
                        } else {
                            $response = [
                                'status' => 200,
                                'error' => null,
                                'messages' => 'Application Status updated successfully.'
                            ];
                            return $this->respond($response);
                            exit;
                        }
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => 'Application Status updated successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Update Failed',
                        'messages' => 'Updated Application Status, Failed to add Remarks!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Update Failed',
                    'messages' => 'Updating Application Status failed, try again later!',
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

    public function edit_remark()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            $this->_validateApplicationRemarks();
            $remark_id = trim($this->request->getVar('remark_id'));
            $application_id = trim($this->request->getVar('application_id'));
            $applicationRow = $this->loanApplicationRow($application_id);
            $client_id = trim($this->request->getVar('client_id'));
            # $status = trim($this->request->getVar('status'));
            $status = 'Processing';
            $level = trim($this->request->getVar('level'));
            $action = trim($this->request->getVar('action'));
            $remarks = trim($this->request->getVar('loan_remarks'));
            if ($applicationRow) {
                $clientRow  = $this->client->find($client_id);
                if ($clientRow) {
                    $productRow = $this->loanProduct->find($applicationRow['product_id']);
                    if ($productRow) {
                        $remarksData = [
                            'application_id' => $application_id,
                            'staff_id' =>  $this->userRow['staff_id'],
                            'status' => $status,
                            'level' => $level,
                            'action' => $action,
                            'remarks' => $remarks,
                        ];
                        $edit = $this->applicationRemark->update($remark_id, $remarksData);
                        if ($edit) {

                            # check the application level
                            if ($applicationRow['level'] == $level) {
                                # Update the application remarks process
                                $applicationStatus = $this->loanApplication->setApplicationRemarkProcess($level, $action, $status);

                                $this->loanApplication->update($application_id, $applicationStatus);
                            }

                            // insert into activity logs
                            $activityData = [
                                'user_id' => $this->userRow['id'],
                                'action' => 'update',
                                'description' => ucfirst('edited application remarks for application: ' . $applicationRow['application_code']) . 'status: ' . $status . ' level: ' . $level . 'action' . $action,
                                'module' => strtolower('applications'),
                                'referrer_id' => $application_id,
                            ];
                            $activity = $this->insertActivityLog($activityData);
                            if ($activity) {
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => 'Application remarks updated successfully',
                                ];
                                return $this->respond($response);
                                exit;
                            } else {
                                $response = [
                                    'status'   => 200,
                                    'error'    => null,
                                    'messages' => 'Application remarks updated successfully. loggingFailed'
                                ];
                                return $this->respond($response);
                                exit;
                            }
                        } else {
                            $response = [
                                'status' => 500,
                                'error' => 'Update Failed',
                                'messages' => 'Updating application remarks failed, try again later!',
                            ];
                            return $this->respond($response);
                            exit;
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
    public function delete_file($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
            $data = $this->file->find($id);
            if ($data) {
                $application = $this->loanApplication->where(['id' => $data['application_id']])->first();
                if (strtolower($data['module']) == 'collateral') {
                    if (file_exists("uploads/applications/collaterals/" . $data['file_name']) && $data['file_name']) {
                        unlink("uploads/applications/collaterals/" . $data['file_name']);
                    }
                } elseif (strtolower($data['module']) == 'income') {
                    if (file_exists("uploads/applications/income/" . $data['file_name']) && $data['file_name']) {
                        unlink("uploads/applications/income/" . $data['file_name']);
                    }
                } else {
                    if (file_exists("uploads/applications/expense/" . $data['file_name']) && $data['file_name']) {
                        unlink("uploads/applications/expense/" . $data['file_name']);
                    }
                }
                $delete = $this->file->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', ' . $data['module'] . ' file. application: ' . $application['application_code']),
                        'module' => strtolower('applications'),
                        'referrer_id' => $data['application_id'],
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

    public function delete_remark($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
            $remark = $this->applicationRemark->find($id);
            if ($remark) {
                $application = $this->loanApplication->find($remark['application_id']);
                $delete = $this->applicationRemark->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $remark['level'] . ' ' . $remark['action'] . ' application remark for application: ' . $application['application_code']),
                        'module' => strtolower('applications'),
                        'referrer_id' => $remark['application_id'],
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => 'Application remark deleted successfully',
                        ];
                        return $this->respond($response);
                        exit;
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => 'Application remark deleted successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Delete Failed',
                        'messages' => 'Deleting Application remark failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'The requsted Application remark could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete Application remark!',
            ];
            return $this->respond($response);
            exit;
        }
    }
    /**
     * Delete the designated resource object from the model
     *
     */
    public function ajax_bulky_delete()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
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
    public function ajax_file_bulky_delete()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->file->find($id);
                if ($data) {
                    $application = $this->loanApplication->where(['id' => $data['application_id']])->first();
                    if (strtolower($data['module']) == 'collateral') {
                        if (file_exists("uploads/applications/collaterals/" . $data['file_name']) && $data['file_name']) {
                            unlink("uploads/applications/collaterals/" . $data['file_name']);
                        }
                    } elseif (strtolower($data['module']) == 'income') {
                        if (file_exists("uploads/applications/income/" . $data['file_name']) && $data['file_name']) {
                            unlink("uploads/applications/income/" . $data['file_name']);
                        }
                    } else {
                        if (file_exists("uploads/applications/expense/" . $data['file_name']) && $data['file_name']) {
                            unlink("uploads/applications/expense/" . $data['file_name']);
                        }
                    }
                    $delete = $this->file->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['module'] . ' file. application: ' . $application['application_code']),
                            'module' => strtolower('applications'),
                            'referrer_id' => $data['application_id'],
                        ];
                        $activity = $this->insertActivityLog($activityData);
                    } else {
                        continue;
                    }
                } else {
                    continue;
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
    public function ajax_bulky_deleteRemarks()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->applicationRemark->find($id);
                if ($data) {
                    $delete = $this->applicationRemark->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['comment_type'] . ' remarks. application: ' . $data['application_code']),
                            'module' => strtolower('applications'),
                            'referrer_id' => $data['application_id'],
                        ];
                        $activity = $this->insertActivityLog($activityData);
                    } else {
                        continue;
                    }
                } else {
                    continue;
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

    /**
     * upload images
     */
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
                $tempCollateral = explode(".", $collateralFile);
                $collateralFileName = $this->settings->generateRandomNumbers(10) . '.' . end($tempCollateral);
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

    /**
     * validate form inputs
     */
    private function _validateApplication($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $principal = $this->removeCommasFromAmount($this->request->getVar('principal'));
        $productRow = $this->loanProduct->find($this->request->getVar('product_id'));
        $appInfo = $this->loanApplication->find($this->request->getVar('id'));
        $clientInfo = $this->client->find($this->request->getVar('client_id'));

        # trimmed the white space between between country code and phone number
        $ref_contact = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('ref_contact_country_code'),
            'phone' => $this->request->getVar('ref_contact')
        ]);

        # trimmed the white space between between country code and phone number
        $ref_alt_contact = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('ref_alt_contact_country_code'),
            'phone' => $this->request->getVar('ref_alt_contact')
        ]);

        # trimmed the white space between between country code and phone number
        $ref_contact2 = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('ref_contact2_country_code'),
            'phone' => $this->request->getVar('ref_contact2')
        ]);

        # trimmed the white space between between country code and phone number
        $ref_alt_contact2 = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('ref_alt_contact2_country_code'),
            'phone' => $this->request->getVar('ref_alt_contact2')
        ]);

        $step = $this->request->getVar('step_no');
        $data['step'] = $step;
        # response if step validation was successful
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

                if (!empty($this->request->getVar('client_id')) && $method == "add") {
                    $processingApplication = $this->loanApplication
                        ->where([
                            'client_id' => trim($this->request->getVar('client_id')),
                            'status' => 'Processing'
                        ])
                        ->findAll();
                    if (count($processingApplication) > 0) {
                        $data['inputerror'][] = 'client_id';
                        $data['error_string'][] = 'Client has ' . count($processingApplication) . ' running application(s)!';
                        $data['status'] = FALSE;;
                    }

                    $pendingApplications = $this->loanApplication
                        ->where([
                            'client_id' => trim($this->request->getVar('client_id')),
                            'status' => 'Pending'
                        ])
                        ->findAll();
                    if (count($pendingApplications) > 0) {
                        $data['inputerror'][] = 'client_id';
                        $data['error_string'][] = 'Client has ' . count($pendingApplications) . ' running application(s)!';
                        $data['status'] = FALSE;;
                    }
                    /*
                    $loan = $this->disbursement
                        ->where(['client_id' => trim($this->request->getVar('client_id')), 'class !=' => 'Cleared'])
                        ->findAll();
                    if (count($loan) > 0) {
                        $data['inputerror'][] = 'client_id';
                        $data['error_string'][] = 'Client has a running loan!';
                        $data['status'] = FALSE;;
                    }
                    */
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

                if (!empty($this->request->getVar('product_id'))) {
                    if ($this->request->getVar('repayment_period') == '') {
                        $data['inputerror'][] = 'repayment_period';
                        $data['error_string'][] = 'Loan Repayment Period is required!';
                        $data['status'] = FALSE;
                    }

                    if ($this->request->getVar('interest_rate') == '') {
                        $data['inputerror'][] = 'interest_rate';
                        $data['error_string'][] = 'Interest Rate is required!';
                        $data['status'] = FALSE;
                    }

                    if ($this->request->getVar('repayment_freq') == '') {
                        $data['inputerror'][] = 'repayment_freq';
                        $data['error_string'][] = 'Loan Frequency is required!';
                        $data['status'] = FALSE;
                    }

                    if ($this->request->getVar('interest_period') == '') {
                        $data['inputerror'][] = 'interest_period';
                        $data['error_string'][] = 'Interest Period is required!';
                        $data['status'] = FALSE;
                    }

                    if ($this->request->getVar('loan_period') == '') {
                        $data['inputerror'][] = 'loan_period';
                        $data['error_string'][] = 'Loan Period is required!';
                        $data['status'] = FALSE;
                    }

                    if ($this->request->getVar('loan_frequency') == '') {
                        $data['inputerror'][] = 'loan_frequency';
                        $data['error_string'][] = 'Loan Frequency is required!';
                        $data['status'] = FALSE;
                    }
                }

                if (!empty($this->request->getVar('loan_period'))) {
                    # accept only digits for the loan period
                    if (!preg_match("/^[0-9.' ]*$/", $this->request->getVar('loan_period'))) {
                        $data['inputerror'][] = 'loan_period';
                        $data['error_string'][] = 'Only digits are required for loan period!';
                        $data['status'] = FALSE;
                    }
                }

                if ($this->removeCommasFromAmount($this->request->getVar('principal')) == '') {
                    $data['inputerror'][] = 'principal';
                    $data['error_string'][] = 'Principal is required!';
                    $data['status'] = FALSE;
                }

                if (!empty($this->removeCommasFromAmount($this->request->getVar('principal')))) {
                    # accept only digits for the loan principal
                    if (!preg_match("/^[0-9.' ]*$/", $principal)) {
                        $data['inputerror'][] = 'principal';
                        $data['error_string'][] = 'Invalid format for principal!';
                        $data['status'] = FALSE;
                    }
                    # check whether the principal is greater than 0
                    if ($principal < 1) {
                        $data['inputerror'][] = 'principal';
                        $data['error_string'][] = 'Principal should not be 0!';
                        $data['status'] = FALSE;
                    }
                    # check whether the loan product is selected
                    if (!empty($this->request->getVar('product_id'))) {
                        # check the loan product existence
                        if ($productRow) {
                            # validate product minimum principal
                            if ($productRow['min_principal'] || ($productRow['min_principal'] > 0)) {
                                if ($principal < $productRow['min_principal']) {
                                    $data['inputerror'][] = 'principal';
                                    $data['error_string'][] = 'Minimum ' . $productRow['product_name'] . ' Principal allowed is ' . $productRow['min_principal'] . '!';
                                    $data['status'] = FALSE;
                                }
                            }
                            # validate product maximum principal
                            if ($productRow['max_principal'] || ($productRow['max_principal'] > 0)) {
                                # check whether the loan principal matches the loan maximum principal
                                if ($principal > $productRow['max_principal']) {
                                    $data['inputerror'][] = 'principal';
                                    $data['error_string'][] = 'Maximum ' . $productRow['product_name'] . ' Principal allowed is ' . $productRow['max_principal'] . '!';
                                    $data['status'] = FALSE;
                                }
                            }
                            # calculate minimum & maximum savings balance at application
                            $clientAccountBalance = $clientInfo['account_balance'];
                            // minimum
                            if ($productRow['min_savings_balance_type_application'] && $productRow['min_savings_balance_application'] > 0) {
                                if (strtolower($productRow['min_savings_balance_type_application']) == 'rate') {
                                    $minApplicationBalance = (($productRow['min_savings_balance_application'] / 100) * $principal);
                                    $minApplBal = $productRow['min_savings_balance_application'] . '%';
                                } elseif (strtolower($productRow['min_savings_balance_type_application']) == 'multiplier') {
                                    $minApplicationBalance = ($productRow['min_savings_balance_application'] * $principal);
                                    $minApplBal = $productRow['min_savings_balance_application'] . '*';
                                } else {
                                    $minApplicationBalance = $productRow['min_savings_balance_application'];
                                    $minApplBal = number_format($productRow['min_savings_balance_application']);
                                }
                            } else {
                                $minApplicationBalance = null;
                            }
                            // maximum
                            if ($productRow['max_savings_balance_type_application'] && $productRow['max_savings_balance_application'] > 0) {
                                if (strtolower($productRow['max_savings_balance_type_application']) == 'rate') {
                                    $maxApplicationBalance = (($productRow['max_savings_balance_application'] / 100) * $principal);
                                    $maxApplBal = $productRow['max_savings_balance_application'] . '%';
                                } elseif (strtolower($productRow['max_savings_balance_type_application']) == 'multiplier') {
                                    $maxApplicationBalance = ($productRow['max_savings_balance_application'] * $principal);
                                    $maxApplBal = $productRow['max_savings_balance_application'] . '*';
                                } else {
                                    $maxApplicationBalance = $productRow['max_savings_balance_application'];
                                    $maxApplBal = number_format($productRow['max_savings_balance_application']);
                                }
                            } else {
                                $maxApplicationBalance = null;
                            }
                            # validate minimum & maximum savings balance at application
                            if ($minApplicationBalance && ($clientAccountBalance < $minApplicationBalance)) {
                                $data['inputerror'][] = 'account_bal';
                                $data['error_string'][] = 'Minimum Savings Balance at application should be ' . $minApplBal . ' ~ ' . $minApplicationBalance . '!';
                                $data['status'] = FALSE;
                            }
                            if ($maxApplicationBalance && ($clientAccountBalance > $maxApplicationBalance)) {
                                $data['inputerror'][] = 'account_bal';
                                $data['error_string'][] = 'Maximum Savings Balance at application should be ' . $maxApplBal . ' ~ ' . $maxApplicationBalance . '!';
                                $data['status'] = FALSE;
                            }
                        }
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
                        $data['error_string'][] = 'Purpose should be at least 7 characters!';
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
                    $data['error_string'][] = 'Deduct charges from is required!';
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
            case 2: // security and Guarantors validation
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
                    if (!preg_match("/^[0-9' ]*$/", $this->removeCommasFromAmount($this->request->getVar('est_value')))) {
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
                    $data['error_string'][] = 'Guarantor Name is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_name'))) {
                    if ($this->settings->validateName($this->request->getVar('ref_name')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_name'))) < 6) {
                            $data['inputerror'][] = 'ref_name';
                            $data['error_string'][] = 'Guarantor Name is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('ref_name')) == FALSE) {
                        $data['inputerror'][] = 'ref_name';
                        $data['error_string'][] = 'Valid Guarantor Name is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('ref_relation') == '') {
                    $data['inputerror'][] = 'ref_relation';
                    $data['error_string'][] = 'Guarantor Relationship is required';
                    $data['status'] = FALSE;
                }
                if ($this->request->getVar('ref_job') == '') {
                    $data['inputerror'][] = 'ref_job';
                    $data['error_string'][] = 'Guarantor Job is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_job'))) {
                    if ($this->settings->validateName($this->request->getVar('ref_job')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_job'))) < 4) {
                            $data['inputerror'][] = 'ref_job';
                            $data['error_string'][] = 'Guarantor Job is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('ref_job')) == FALSE) {
                        $data['inputerror'][] = 'ref_job';
                        $data['error_string'][] = 'Valid Guarantor Job is required';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('ref_contact') == '') {
                    $data['inputerror'][] = 'ref_contact';
                    $data['error_string'][] = 'Phone Number is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_contact'))) {
                    # validate the phone number
                    $this->validPhoneNumber([
                        'phone' => $ref_contact,
                        'input' => 'ref_contact',
                    ]);
                }
                if (!empty($this->request->getVar('ref_alt_contact'))) {
                    # validate the phone number
                    $this->validPhoneNumber([
                        'phone' => $ref_alt_contact,
                        'input' => 'ref_alt_contact',
                    ]);
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
                    $data['error_string'][] = 'Guarantor Address is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('ref_address'))) {
                    if ($this->settings->validateAddress($this->request->getVar('ref_address')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_address'))) < 4) {
                            $data['inputerror'][] = 'ref_address';
                            $data['error_string'][] = 'Guarantor Address is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    /*
                    if ($this->settings->validateAddress($this->request->getVar('ref_address')) == FALSE) {
                        $data['inputerror'][] = 'ref_address';
                        $data['error_string'][] = 'Valid Guarantor Address is required';
                        $data['status'] = FALSE;
                    }
                    */
                }
                // if ($this->request->getVar('ref_name2') == '') {
                //     $data['inputerror'][] = 'ref_name2';
                //     $data['error_string'][] = 'Guarantor Name is required';
                //     $data['status'] = FALSE;
                // }
                if (!empty($this->request->getVar('ref_name2'))) {
                    if ($this->settings->validateName($this->request->getVar('ref_name2')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_name2'))) < 6) {
                            $data['inputerror'][] = 'ref_name2';
                            $data['error_string'][] = 'Guarantor Name is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('ref_name2')) == FALSE) {
                        $data['inputerror'][] = 'ref_name2';
                        $data['error_string'][] = 'Valid Guarantor Name is required';
                        $data['status'] = FALSE;
                    }
                }
                // if ($this->request->getVar('ref_relation2') == '') {
                //     $data['inputerror'][] = 'ref_relation2';
                //     $data['error_string'][] = 'Guarantor Relationship is required';
                //     $data['status'] = FALSE;
                // }
                // if ($this->request->getVar('ref_job2') == '') {
                //     $data['inputerror'][] = 'ref_job2';
                //     $data['error_string'][] = 'Guarantor Job is required';
                //     $data['status'] = FALSE;
                // }
                if (!empty($this->request->getVar('ref_job2'))) {
                    if ($this->settings->validateName($this->request->getVar('ref_job2')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_job2'))) < 4) {
                            $data['inputerror'][] = 'ref_job2';
                            $data['error_string'][] = 'Guarantor Job is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    if ($this->settings->validateName($this->request->getVar('ref_job2')) == FALSE) {
                        $data['inputerror'][] = 'ref_job2';
                        $data['error_string'][] = 'Valid Guarantor Job is required';
                        $data['status'] = FALSE;
                    }
                }
                // if ($this->request->getVar('ref_contact2') == '') {
                //     $data['inputerror'][] = 'ref_contact2';
                //     $data['error_string'][] = 'Phone Number is required';
                //     $data['status'] = FALSE;
                // }
                if (!empty($this->request->getVar('ref_contact2'))) {
                    $this->validPhoneNumber([
                        'phone' => $ref_contact2,
                        'input' => 'ref_contact2',
                    ]);
                }
                if (!empty($this->request->getVar('ref_alt_contact2'))) {
                    # validate the phone number
                    $this->validPhoneNumber([
                        'phone' => $ref_alt_contact2,
                        'input' => 'ref_alt_contact2',
                    ]);
                }
                if (!empty($this->request->getVar('ref_email2'))) {
                    # check whether the email is valid
                    if ($this->settings->validateEmail($this->request->getVar('ref_email2')) == FALSE) {
                        $data['inputerror'][] = 'ref_email2';
                        $data['error_string'][] = 'Valid Email is required';
                        $data['status'] = FALSE;
                    }
                }
                // if ($this->request->getVar('ref_address2') == '') {
                //     $data['inputerror'][] = 'ref_address2';
                //     $data['error_string'][] = 'Guarantor Address is required';
                //     $data['status'] = FALSE;
                // }
                if (!empty($this->request->getVar('ref_address2'))) {
                    if ($this->settings->validateAddress($this->request->getVar('ref_address2')) == TRUE) {
                        if (strlen(trim($this->request->getVar('ref_address2'))) < 4) {
                            $data['inputerror'][] = 'ref_address2';
                            $data['error_string'][] = 'Guarantor Address is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    /*
                    if ($this->settings->validateAddress($this->request->getVar('ref_address2')) == FALSE) {
                        $data['inputerror'][] = 'ref_address2';
                        $data['error_string'][] = 'Valid Guarantor Address is required';
                        $data['status'] = FALSE;
                    }
                    */
                }

                /*
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
                */
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
                    $sal = $this->removeCommasFromAmount($this->request->getVar('net_salary'));
                    if (!preg_match("/^[0-9']*$/", $sal)) {
                        $data['inputerror'][] = 'net_salary';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('farming'))) {
                    $farm = $this->removeCommasFromAmount($this->request->getVar('farming'));
                    if (!preg_match("/^[0-9']*$/", $farm)) {
                        $data['inputerror'][] = 'farming';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('business'))) {
                    $buz = $this->removeCommasFromAmount($this->request->getVar('business'));
                    if (!preg_match("/^[0-9']*$/", $buz)) {
                        $data['inputerror'][] = 'business';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('others'))) {
                    $others = $this->removeCommasFromAmount($this->request->getVar('others'));
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
                    $rent = $this->removeCommasFromAmount($this->request->getVar('rent'));
                    if (!preg_match("/^[0-9']*$/", $rent)) {
                        $data['inputerror'][] = 'rent';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('education'))) {
                    $educ = $this->removeCommasFromAmount($this->request->getVar('education'));
                    if (!preg_match("/^[0-9']*$/", $educ)) {
                        $data['inputerror'][] = 'education';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('medical'))) {
                    $med = $this->removeCommasFromAmount($this->request->getVar('medical'));
                    if (!preg_match("/^[0-9']*$/", $med)) {
                        $data['inputerror'][] = 'medical';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('transport'))) {
                    $tp = $this->removeCommasFromAmount($this->request->getVar('transport'));
                    if (!preg_match("/^[0-9']*$/", $tp)) {
                        $data['inputerror'][] = 'transport';
                        $data['error_string'][] = 'Only digits allowed!';
                        $data['status'] = FALSE;
                    }
                }
                if (!empty($this->request->getVar('exp_others'))) {
                    $exp_o = $this->removeCommasFromAmount($this->request->getVar('exp_others'));
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

                if ($this->request->getVar('dif_status') == '') {
                    $data['inputerror'][] = 'dif_status';
                    $data['error_string'][] = 'Income Difference is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('dif_status')) && strtolower($this->request->getVar('dif_status')) == 'deficit') {
                    $data['inputerror'][] = 'dif_status';
                    $data['error_string'][] = 'Applicant Income should not be Deficit';
                    $data['status'] = FALSE;
                }

                /*
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
                */
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
    private function _validateApplicationRemarks()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->request->getVar('application_id') == '') {
            $data['inputerror'][] = 'application_id';
            $data['error_string'][] = 'Application ID is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('action') == '') {
            $data['inputerror'][] = 'action';
            $data['error_string'][] = 'Action is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('level') == '') {
            $data['inputerror'][] = 'level';
            $data['error_string'][] = 'Level is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('loan_remarks') == '') {
            $data['inputerror'][] = 'loan_remarks';
            $data['error_string'][] = 'Remarks are required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('loan_remarks'))) {
            $remark = $this->request->getVar('loan_remarks');
            if (strlen($remark) < 4) {
                $data['inputerror'][] = 'loan_remarks';
                $data['error_string'][] = 'Minimum characters required is 4![' . strlen($remark) . ']';
                $data['status'] = FALSE;
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
