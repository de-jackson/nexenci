<?php

namespace App\Controllers\Admin\Loans;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;
use CodeIgniter\I18n\Time;
use Config\Services;

class Disbursement extends MasterController
{
    protected $account_typeId = 3;
    public function __construct()
    {
        parent::__construct();
        $this->encrypter = Services::encrypter();
        $this->menu = 'Loans';
        $this->title = 'Disbursements';
        $this->menuItem = [
            'title' => $this->title,
            'menu' => $this->menu,
        ];
    }
    public function index()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            return view('admin/loans/disbursements/index', [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    // show disbursement details 
    public function view_disbursement($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $decryptedId = $this->encrypter->decrypt(hex2bin($id));
            $disbursement = $this->loanDisbursementRow($decryptedId);
            $charges['particulars'] = [];
            $charges['totalCharges'] = [];
            if ($disbursement) {
                $chargesIDs = unserialize($disbursement['overall_charges']);

                return view('admin/loans/disbursements/view', [
                    'title' => $disbursement['disbursement_code'],
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                    'module' => 'Loan Disbursement',
                    'disbursement' => $disbursement,
                    'charges' => $this->getCharges([
                        'charges.status' => 'Active',
                        'p.account_typeId' => 18,
                        'p.particular_status' => 'Active',
                        'charges.product_id' => null
                    ]),
                    'decryptedId' => $decryptedId,
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Disbursement could have been deleted or there might be a problem with your URL.');
            }
        } else {
            # Not Authorized
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    function disbursement_forms($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $collaterals = $disbursement = $repayments = $payables = '';
            if ($id == 0) {
                $title = "Add Disbursement Form";
            } else {
                $title = "Disbursement View Form";
                // load disbursement data
                $disbursement = $this->loanDisbursementRow($id);
                // load applicant collaterals
                $collaterals = $this->file->where(['application_id' => $disbursement['application_id'], 'type' => 'collateral'])->findAll();
                // load repayments
                $repayments = $this->entry
                    ->select('entries.payment_id, entries.particular_id, entries.amount, entries.ref_id, entries.created_at, debitParticular.particular_name as payment_id, creditParticular.particular_name as particular_id, staffs.staff_name, clients.name')
                    ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                    ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('clients', 'clients.id = entries.client_id', 'left')
                    ->where(['disbursement_id' => $id])->findAll();
                // load payments towards application
                $app_payments = $this->entry
                    ->select('entries.payment_id, entries.particular_id, entries.amount, entries.ref_id, entries.created_at, debitParticular.particular_name as payment_id, creditParticular.particular_name as particular_id, staffs.staff_name, clients.name')
                    ->join('particulars as debitParticular', 'debitParticular.id = entries.payment_id', 'left')
                    ->join('particulars as creditParticular', 'creditParticular.id = entries.particular_id', 'left')
                    ->join('staffs', 'staffs.id = entries.staff_id', 'left')
                    ->join('clients', 'clients.id = entries.client_id', 'left')
                    ->where(['application_id' => $disbursement['application_id']])->findAll();
                // load application payable particulars
                $payables = $this->particular->where(['account_typeId' => '18'])->findAll();
            }
            return view('admin/loans/disbursements/disbursement_formPDF', [
                'title' => $title,
                'id' => $id,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'disbursement' => $disbursement,
                'collaterals' => $collaterals,
                'repayments' => $repayments,
                'app_payments' => $app_payments,
                'payables' => $payables,
            ]);
        } else {
            # Not Authorized
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    // show loan agreement
    public function loan_agreement($module, $id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            # check the module existance
            if (strtolower($module) == 'application') {
                # get loan application information
                $data = $this->loanApplicationRow($id);
                $data['code'] = $data['application_code'];
            } else {
                # get disbursement information
                $data = $this->loanDisbursementRow($id);
                $data['code'] = $data['disbursement_code'];
            }
            if ($data) {

                $principal = trim($data['principal']);
                $particulars = unserialize($data['overall_charges']);
                # Get Applicantion charges
                # $loanCharges = $this->report->getLoanProductCharges($particulars, $principal);

                # convert the loan internal per year
                $loanInterval = $this->settings->generateIntervals($data['repayment_frequency']);
                # check the loan interest period
                if (strtolower($data['interest_period']) === "year") {
                    $interval = $loanInterval['interval'];
                } else {
                    # code...
                    $interval = 1;
                }

                $installments = ($data['repayment_period'] / $interval);

                $principalAmt = $data['principal'];
                // amount per installment as computed
                $installmentAmt = $this->calculateEMI($data['interest_type'], $principalAmt, $data['interest_rate'], $data['repayment_period'], $interval, $data['interest_period'], $data['loan_period']);
                // total interest as computed
                $interestAmt = $this->calculateInterest($data['interest_type'], $principalAmt, $installmentAmt, $data['interest_rate'], $data['repayment_frequency'], $installments, $data['interest_period'],  $data['loan_period']);

                $actualInterest = (($interestAmt % 10 == 0) ? $interestAmt : $this->rountoffTo('ceil', $interestAmt, $this->settingsRow['round_off']));
                $actualInstallment = (($installmentAmt % 10 == 0) ? $installmentAmt : $this->rountoffTo('ceil', $installmentAmt, $this->settingsRow['round_off']));
                $actualRepayment = ($principalAmt + $actualInterest);

                $data['installments_num'] = $installments;
                $data['actual_installment'] = $actualInstallment;
                $data['actual_repayment'] = $actualRepayment;
                $data['grace_period'] = $loanInterval['grace_period'];
                $data['disbursed_by'] = '';

                if (strtolower($module) == 'application') {
                    # set disbursement date
                    $data['date_disbursed'] = '';
                } else {
                    # set disbursement date
                    $data['date_disbursed'] = (isset($data['date_disbursed'])) ? $data['date_disbursed'] : "";
                }


                return view('admin/loans/agreement', [
                    'title' => $data['name'] . ' Loan Agreement',
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'module' => $module,
                    'charges' => $this->getCharges([
                        'charges.status' => 'Active',
                        'p.account_typeId' => 18,
                        'p.particular_status' => 'Active',
                        'charges.product_id' => null
                    ]),
                    'data' => $data,
                    'loanCharges' => $particulars
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Loan Agreement could not be found or there might be a problem with your URL.');
            }
        } else {
            # Not Authorized
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    /**
     * return all disbursements as rows
     */
    public function disbursements_list($class)
    {
        if (isset($class)) {
            $where = ['disbursements.deleted_at' => null, 'disbursements.class' => ucfirst($class)];
        } else {
            $where = ['disbursements.deleted_at' => null];
        }
        $disbursements = $this->disbursement
            ->select('clients.id as client_id, clients.name, loanproducts.product_name, disbursements.disbursement_code,disbursements.principal, disbursements.actual_interest, disbursements.actual_repayment, disbursements.total_balance, disbursements.total_collected, disbursements.actual_installment, disbursements.class, disbursements.days_remaining, disbursements.id, disbursements.application_id, clients.photo, clients.account_no')
            ->join('clients', 'clients.id = disbursements.client_id', 'left')
            ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')->where($where);
        return DataTable::of($disbursements)
            ->add('checkbox', function ($disbursement) {
                return '<div class=""><input type="checkbox" class="data-check' . ucfirst($disbursement->class)  . '" value="' . $disbursement->id . '"></div>';
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
            ->add('loan_balance', function ($disbursement) {
                if ((strtolower($disbursement->class) != "cleared")  || ($disbursement->total_balance > 0)) {
                    if (($this->userPermissions == 'all') || (in_array('create_loansRepayments', $this->userPermissions))) {
                        $loan_balance =  number_format($disbursement->total_balance, 2) . '<br>
                            <!-- <a href="javascript:void(0)" onclick="adjust_disbursementBalance(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->disbursement_code . "'" . ')" title="Repay Installment">
                                <i class="fa fa-scale-balanced text-primary"></i> Repay Installment
                            </a> -->
                            <a href="javascript:void(0)" onclick="add_disbursementPayment(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->client_id . "'" . ')" title="Make Installment Payment" class="dropdown-item"><i class="fa fa-money-bill-trend-up text-primary"></i> Repayment</a>';
                    } else {
                        $loan_balance = number_format($disbursement->total_balance, 2);
                    }
                } else {
                    $loan_balance = number_format($disbursement->total_balance, 2);
                }
                return $loan_balance;
            })
            ->add('expiry', function ($disbursement) {
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
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
                $disbursementStatus = strtolower($disbursement->class);

                switch (strtolower($disbursement->class)) {
                    case "running":
                        $text = "info";
                        break;
                    case "cleared":
                        $text = "success";
                        break;
                    case "expired":
                        $text = "danger";
                        break;
                    case "pending":
                        $text = "secondary";
                        break;
                    case "arrears":
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
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                    $encryptedId = bin2hex($this->encrypter->encrypt($disbursement->id));
                    $actions .= '
                        <a href="/admin/disbursement/info/' . $encryptedId . '" title="view ' . $disbursement->disbursement_code . '" class="dropdown-item"><i class="fas fa-eye text-success"></i> View ' . $disbursement->disbursement_code . '</a>
                        <div class="dropdown-divider"></div>

                        <a href="/admin/loan/agreement/disbursement/' . $disbursement->id . '" target="_blank" title="View ' . $disbursement->disbursement_code . ' Agreement" class="dropdown-item"><i class="fas fa-clipboard-list text-primary"></i> View ' . $disbursement->disbursement_code . ' Agreement</a>
                        <div class="dropdown-divider"></div>';
                }
                # view disbursement application
                if (($this->userPermissions == 'all') || (in_array('view_loansApplications', $this->userPermissions))) {
                    $encryptedApplId = bin2hex($this->encrypter->encrypt($disbursement->application_id));
                    $actions .= '
                        <a href="/admin/application/info/' . $encryptedApplId . '" title="View ' . $disbursement->disbursement_code . ' Application" class="dropdown-item"><i class="fas fa-folder text-info"></i> View ' . $disbursement->disbursement_code . ' Application</a>
                        <div class="dropdown-divider"></div>';
                }

                # running disbursement
                if (($disbursementStatus != "cleared") || ($disbursement->total_balance > 0)) {
                    # enable repayment button
                    if (($this->userPermissions == 'all') || (in_array('create_loansRepayments', $this->userPermissions))) {
                        $actions .= '
                            <a href="javascript:void(0)" onclick="add_disbursementPayment(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->client_id . "'" . ')" title="Make Installment Payment" class="dropdown-item"><i class="fa fa-money-bill-trend-up text-primary"></i> Repay Installment</a>
                            <div class="dropdown-divider"></div>';
                    }
                }

                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '
                        <a href="javascript:void(0)" onclick="delete_disbursement(' . "'" . $disbursement->id . "'" . ',' . "'" . $disbursement->disbursement_code . "'" . ')" title="delete ' . $disbursement->disbursement_code . '" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete ' . $disbursement->disbursement_code . '</a>';
                }

                $actions .= ' 
                    </div>
                </div>';

                return $actions;
            })
            ->toJson(true);
    }

    public function disbursements_report($filter, $opted, $val = null, $parm = null, $from = null, $to = null)
    {
        switch (strtolower($filter)) {
            case "class":
                if (strtolower($opted) == 'expired') {
                    if ($from != 0 && $to == 0) {
                        if ($val != 0 && $parm == 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.deleted_at' => Null];
                        }
                    } elseif ($from == 0 && $to != 0) {
                        if ($val != 0 && $parm == 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.deleted_at' => Null];
                        }
                    } elseif ($from != 0 && $to != 0) {
                        if ($val != 0 && $parm == 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.deleted_at' => Null];
                        }
                    } else {
                        if ($val != 0 && $parm == 0) {
                            $where = ['disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['disbursements.days_covered' => 'Expired', 'disbursements.class !=' => 'Cleared', 'disbursements.deleted_at' => Null];
                        }
                    }
                } elseif (strtolower($opted) == 'pending') {
                    if ($from != 0 && $to == 0) {
                        if ($val != 0 && $parm == 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.arrears !=' => '0', 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.arrears !=' => '0', 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.arrears !=' => '0', 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.arrears !=' => '0', 'disbursements.deleted_at' => Null];
                        }
                    } elseif ($from == 0 && $to != 0) {
                        if ($val != 0 && $parm == 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.arrears !=' => '0', 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.arrears !=' => '0', 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.arrears !=' => '0', 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.arrears !=' => '0', 'disbursements.deleted_at' => Null];
                        }
                    } elseif ($from != 0 && $to != 0) {
                        if ($val != 0 && $parm == 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.arrears !=' => '0', 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.arrears !=' => '0', 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.arrears !=' => '0', 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.arrears !=' => '0', 'disbursements.deleted_at' => Null];
                        }
                    } else {
                        if ($val != 0 && $parm == 0) {
                            $where = ['disbursements.arrears !=' => '0', 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['disbursements.arrears !=' => '0', 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['disbursements.arrears !=' => '0', 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['disbursements.arrears !=' => '0', 'disbursements.deleted_at' => Null];
                        }
                    }
                } else {
                    if ($from != 0 && $to == 0) {
                        if ($val != 0 && $parm == 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.class' => ucfirst($opted), 'disbursements.deleted_at' => Null];
                        }
                    } elseif ($from == 0 && $to != 0) {
                        if ($val != 0 && $parm == 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.class' => ucfirst($opted), 'disbursements.deleted_at' => Null];
                        }
                    } elseif ($from != 0 && $to != 0) {
                        if ($val != 0 && $parm == 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.class' => ucfirst($opted), 'disbursements.deleted_at' => Null];
                        }
                    } else {
                        if ($val != 0 && $parm == 0) {
                            $where = ['disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                        } elseif ($val == 0 && $parm != 0) {
                            $where = ['disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                        } elseif ($val != 0 && $parm != 0) {
                            $where = ['disbursements.class' => ucfirst($opted), 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                        } else {
                            $where = ['disbursements.class' => ucfirst($opted), 'disbursements.deleted_at' => Null];
                        }
                    }
                }
                break;
            case "product":
                if ($from != 0 && $to == 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'loanproducts.id' => $opted, 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'loanproducts.id' => $opted, 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'loanproducts.id' => $opted, 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'loanproducts.id' => $opted, 'disbursements.deleted_at' => Null];
                    }
                } elseif ($from == 0 && $to != 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'loanproducts.id' => $opted, 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'loanproducts.id' => $opted, 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'loanproducts.id' => $opted, 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'loanproducts.id' => $opted, 'disbursements.deleted_at' => Null];
                    }
                } elseif ($from != 0 && $to != 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'loanproducts.id' => $opted, 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'loanproducts.id' => $opted, 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'loanproducts.id' => $opted, 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'loanproducts.id' => $opted, 'disbursements.deleted_at' => Null];
                    }
                } else {
                    if ($val != 0 && $parm == 0) {
                        $where = ['loanproducts.id' => $opted, 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['loanproducts.id' => $opted, 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['loanproducts.id' => $opted, 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                    } else {
                        $where = ['loanproducts.id' => $opted, 'disbursements.deleted_at' => Null];
                    }
                }
                break;
            default:
                if ($from != 0 && $to == 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'disbursements.deleted_at' => Null];
                    }
                } elseif ($from == 0 && $to != 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $to, 'disbursements.deleted_at' => Null];
                    }
                } elseif ($from != 0 && $to != 0) {
                    if ($val != 0 && $parm == 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                    } else {
                        $where = ['DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") <=' => $to, 'disbursements.deleted_at' => Null];
                    }
                } else {
                    if ($val != 0 && $parm == 0) {
                        $where = ['disbursements.principal >' => $val, 'disbursements.deleted_at' => Null];
                    } elseif ($val == 0 && $parm != 0) {
                        $where = ['disbursements.principal >' => $parm, 'disbursements.deleted_at' => Null];
                    } elseif ($val != 0 && $parm != 0) {
                        $where = ['disbursements.principal >' => $val, 'disbursements.principal <' => $parm, 'disbursements.deleted_at' => Null];
                    } else {
                        $where = ['disbursements.deleted_at' => Null];
                    }
                }
                break;
        }
        $disbursements = $this->disbursement->select('clients.id as client_id, clients.name, loanproducts.product_name, disbursements.disbursement_code, disbursements.actual_repayment, disbursements.total_balance, disbursements.total_collected, disbursements.actual_installment, disbursements.class, disbursements.id')->join('clients', 'clients.id = disbursements.client_id', 'left')->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')->where($where);
        return DataTable::of($disbursements)
            ->add('checkbox', function ($disbursement) {
                return '<div class=""><input type="checkbox" class="data-check" value="' . $disbursement->id . '"></div>';
            })
            ->addNumbering("no")
            ->add('action', function ($disbursement) {
                switch (strtolower($disbursement->status)) {
                    case 'active':
                        $text = "text-info";
                        break;
                    case 'cleared':
                        $text = "text-success";
                        break;
                    case 'expired':
                        $text = "text-danger";
                        break;
                    default:
                        $text = "text-primary";
                        break;
                }
                return '
                    <div class="text-center">
                        <a href="' . base_url('/admin/disbursement/info/' . $disbursement->id) . '" title="view disbursement" class="' . $text . '"><i class="fas fa-eye"></i></a>
                    </div>
                ';
            })
            ->toJson(true);
    }
    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->disbursement->find($id);
        if ($data) {
            return $this->respond(($this->loanDisbursementRow($id)));
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
     * get all loan disbursements
     */
    public function getdisbursements()
    {
        $data = $this->disbursement->findAll();;
        return $this->respond($data);
    }
    // get client with pending loans
    public function pendingDisbursements_clients()
    {
        $clientIDs = $this->disbursement->distinct()->select('client_id')->where(['class !=' => 'Cleared'])->findColumn('client_id');
        if ($clientIDs) {
            $clients = $this->client->find($clientIDs);
            return $this->respond(($clients));
        } else {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'No Clients With Running Loans found!',
            ];
            return $this->respond($response);
            exit;
        }
    }
    // get client pending loans
    public function client_pendingDisbursements($client_id = null)
    {
        $data = $this->disbursement->select('disbursements.*, loanproducts.product_name')
            ->where(['client_id' => $client_id, 'class !=' => 'Cleared'])
            ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')
            ->findAll();
        return $this->respond($data);
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
            if (strtolower($mode) == 'imported') {
            } else {
                $this->_validateDisbursement();
                /** set defaults */
                $cycle = 1; // number of disbursed loans for a client
                $principalReceivable = 0;
                $ref_id = $this->settings->generateReference(5); // transaction ref ID
                // form data
                $application_id = trim($this->request->getVar('application_id'));
                /** get application data */
                $applicationRow = $this->loanApplicationRow($application_id);
                if (!$applicationRow) {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Application could not found!'
                    ];
                    return $this->respond($response);
                    exit;
                }
                // check if application hasn't been disbursed yet
                if (strtolower($applicationRow['status']) == 'disbursed') {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Application Has Already Been Disbursed!'
                    ];
                    return $this->respond($response);
                    exit;
                }

                /** get loan product data */
                $productRow = $this->loanProduct->find($applicationRow['product_id']);
                if (!$productRow) {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Product could not be found!'
                    ];
                    return $this->respond($response);
                    exit;
                }

                $client_id = trim($this->request->getVar('client_id'));
                # applicant loan information
                $clientInfo = $this->clientDataRow($client_id);
                if (!$clientInfo) {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Client could not be found!'
                    ];
                    return $this->respond($response);
                    exit;
                }
                $savingsProducts = (($clientInfo['savingsProducts']) ? $clientInfo['savingsProducts'] : null);

                $principal = $this->removeCommasFromAmount($this->request->getVar('principal')); //remove commas
                $principal_receivable = str_replace(',', '', trim($this->request->getVar('principal_receivable'))); //remove commas
                $date_disbursed = trim($this->request->getVar('date_disbursed'));
                $disbursed_by = trim($this->request->getVar('disbursed_by'));
                $reductCharges = trim($this->request->getVar('reduct_charges'));
                $savings_productId = trim($this->request->getVar('product_id'));
                $savingspParticularId = trim($this->request->getVar('reduct_particular_id'));
                $savingsParticular = ($savingspParticularId) ? $this->particular->find($savingspParticularId) : null; // savings particular data

                $payment_id = trim($this->request->getVar(('payment_id')));
                // disbursement method
                $paymentRow = $this->particularDataRow($payment_id);
                if (!$paymentRow) {
                    $response = [
                        'status'   => 404,
                        'error'    => 'Not Found',
                        'messages' => 'Payment could not Found!'
                    ];
                    return $this->respond($response);
                    exit;
                }

                $particular_id = trim($this->request->getVar(('particular_id')));
                $particularRow = $this->particularDataRow($particular_id);
                // check principal particular existence
                if (!$particularRow) {
                    $response = [
                        'status'   => 404,
                        'error'    => 'Not Found',
                        'messages' => 'Particular could not Found!'
                    ];
                    return $this->respond($response);
                    exit;
                }

                # charges reduction particular
                if (strtolower($reductCharges) == 'savings') {
                    $charges_reductionParticular = $savingsParticular;
                } else {
                    $charges_reductionParticular = $particularRow;
                }

                $interest_particular_id = trim($this->request->getVar(('interest_particular_id')));
                $interest_particularRow = $this->particularDataRow($interest_particular_id);
                // check interest particular existence
                if (!$interest_particularRow) {
                    $response = [
                        'status'   => 404,
                        'error'    => 'Not Found',
                        'messages' => 'Interest Particular could not Found!'
                    ];
                    return $this->respond($response);
                    exit;
                }

                // get transaction/entry type for disbursements
                $entry_typeData = $this->entryType->where(['account_typeId' => $this->account_typeId, 'part' => 'debit'])->first();
                if (!$entry_typeData) {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'No Disbursement Entry Type found!'
                    ];
                    return $this->respond($response);
                    exit;
                }

                $entry_typeID = $entry_typeData['id']; # entry_typeId for disbursement entry(ies).
                $status = $entry_typeData['part']; # status for disbursement entry(ies).

                // calculate entries total amount per entry status & final balance
                $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $particularRow['part']]);
                $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $principal); // calculate balance for primary particular as of this entry

                # check the applicant loan repayment duration
                if ($applicationRow['repayment_period']) {
                    # applicant loan repayment period
                    $period = $applicationRow['repayment_period'];
                } else {
                    # loan product repayment period
                    $period = $productRow['repayment_period'];
                }

                # check the applicant repayment frequency
                if ($applicationRow['repayment_frequency']) {
                    # applicant loan repayment frequency
                    $frequency = $applicationRow['repayment_frequency'];
                } else {
                    # loan product repayment frequency
                    $frequency = $productRow['repayment_freq'];
                }

                $loanData = $this->loanProduct->getOtherLoanProduct($frequency);

                # get client previous disbursements then compute cycle
                $cDisbursements = $this->disbursement->where(['client_id' => $applicationRow['client_id']])->findAll();
                if (count($cDisbursements) > 0) {
                    $cycle += count($cDisbursements);
                }

                /** 
                 * get applicable charges
                 * check how charges should be deducted 
                 * compute the charges
                 * if savings, check if the client has enough money to cover them then deduct them
                 *  save the deducted savings into entries n do double entry for them
                 */
                $chargesParticularIds = unserialize($applicationRow['overall_charges']);
                $chargeAmt = $total_chargeAmt = $chargeBalance = 0;
                if (count($chargesParticularIds) > 0) {
                    /**
                     * loop thru the charge ids, 
                     * get corresponding particular data,
                     * compute total charges
                     */
                    $chargesTransactionData = []; // set array for batch charges transaction
                    // set array for performing batch charges particular double entry
                    $chargesDoubleEntryData = [];
                    $chargesPaymentDoubleEntryData = [];
                    foreach ($chargesParticularIds as $key => $row) {
                        $charge_id = $row['particular_id'];
                        $charge = $row['charge'];
                        $chargeMethod = $row['charge_method'];

                        # Handle the charge method based on the condition
                        if (strtolower($chargeMethod) === 'percent') {
                            # Calculate the charge as a percentage of the product
                            $chargeAmt = ($charge / 100) * $principal;
                        } elseif (strtolower($chargeMethod) === 'amount') {
                            # Use the fixed amount as the charge
                            $chargeAmt = $charge;
                        } else {
                            # Handle other charge methods here
                            $chargeAmt = 0;
                        }

                        $chargeRow = $this->particular->select('particulars.*, categories.part')->join('categories', 'categories.id = particulars.category_id', 'left')->find($charge_id);
                        if ($chargeRow) {
                            $totalChargePayments = $chargeBalance = 0;
                            $totalChargePayments += $this->entry->sum_client_amountPaid(
                                $client_id,
                                $charge_id,
                                $chargeRow['part'],
                                'application',
                                $application_id
                            );
                            // check if charge is already paid in full
                            if ($totalChargePayments < $chargeAmt) {
                                // get balance on charge
                                $chargeBalance = (float)($chargeAmt - $totalChargePayments);
                            }

                            /*
                            // check if charge has any payment made
                            $chargePayments = $this->entry->where(['application_id' => $applicationRow['id'], 'particular_id' => $chargeRow['id']])->findAll();
                            if (count($chargePayments) > 0) {
                                // get total charge payments
                                foreach ($chargePayments as $payment) {
                                    $totalChargePayments += $payment['amount'];
                                }
                                // check if charge is already paid in full
                                if ($chargeAmt < $totalChargePayments) {
                                    // get balance on charge
                                    $chargeBalance = (float)($chargeAmt - $totalChargePayments);
                                }
                            } else {
                                $chargeBalance = $chargeAmt;
                            }
                            */

                            // if charges are fully paid, skip double entry
                            if ($chargeBalance == 0) {
                                continue;
                            } else {
                                // get accounting balance for charge particular
                                $entriesStatusTotals_charg = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $chargeRow['id'], 'status' => $chargeRow['part']]);
                                // update accounting balance for charge particular
                                $accountingBalance_charge = (float)($entriesStatusTotals_charg['totalBalance'] + $chargeBalance);

                                # get charge transaction type
                                $chargeEntryType = $this->entryType->where(['account_typeId' => $chargeRow['account_typeId'], 'part' => $chargeRow['part'], 'status' => 'active'])->first();

                                // batch charges transaction data for db insertion in entries table
                                $chargesTransactionData[] = [
                                    'date' => $date_disbursed,
                                    'particular_id' => $charge_id,
                                    'payment_id' => $charges_reductionParticular['id'],
                                    'product_id' => ((strtolower($reductCharges) == 'savings') && !empty($savings_productId)) ? $savings_productId : null,
                                    'branch_id' => $this->userRow['branch_id'],
                                    'staff_id' => $this->userRow['staff_id'],
                                    'client_id' => $client_id,
                                    'application_id' => $application_id,
                                    'entry_menu' => $chargeEntryType['entry_menu'],
                                    'entry_typeId' => $chargeEntryType['id'],
                                    'account_typeId' => $chargeRow['account_typeId'],
                                    'ref_id' => strtolower(substr($chargeEntryType['type'], 0, 3)) . '-' . date('ym') . '-' . $this->settings->generateReference(5),
                                    'amount' => $chargeAmt,
                                    'status' => $chargeEntryType['part'],
                                    'balance' => $accountingBalance_charge,
                                    'contact' => $clientInfo['mobile'],
                                    'entry_details' => $chargeEntryType['type'] . ' for ' . $chargeRow['particular_name'] . ' as Charges from ' . $charges_reductionParticular['particular_name'] . ' at disbursement',
                                    'remarks' => 'Charge Reduction at Disbursement',
                                ];

                                // double entry for each charge, store batch data for db update of the charge particulars
                                /** 
                                 * since account type is for Revenue category,
                                 * credit particular & debit payment method if its gaining,
                                 * debit particular & credit payment method if its loosing,
                                 */
                                if (strtolower($chargeEntryType['part']) == 'credit') {
                                    // credit current charge particular
                                    $chargeParticularBal = [
                                        'id' => $charge_id,
                                        'credit' => $chargeRow['credit'] + $chargeAmt
                                    ];
                                    // debit selected savings particular
                                    $chargePaymentParticularBal = [
                                        'id' => $charges_reductionParticular['id'],
                                        'debit' => $charges_reductionParticular['debit'] + $chargeAmt
                                    ];
                                }
                                if (strtolower($chargeEntryType['part']) == 'debit') {
                                    // debit current charge  particular
                                    $chargeParticularBal = [
                                        'id' => $charge_id,
                                        'debit' => $chargeRow['debit'] + $chargeAmt
                                    ];
                                    // credit selected savings particular
                                    $chargePaymentParticularBal = [
                                        'id' => $charges_reductionParticular['id'],
                                        'credit' => $charges_reductionParticular['credit'] + $chargeAmt
                                    ];
                                }
                                // batch storing of double entry data for charges
                                $chargesDoubleEntryData[] = $chargeParticularBal;
                                $chargesPaymentDoubleEntryData[] = $chargePaymentParticularBal;
                            }

                            // calculate total charges
                            $total_chargeAmt += $chargeAmt;
                        }
                    }

                    # if charges are to be deducted from client savings, check if the client has enough balance before proceeding
                    if (strtolower($reductCharges) == 'savings') {
                        // get selected client product balance
                        $productbalance = 0;
                        if ($savingsParticular && $savingsProducts) {
                            // Re-index savingsProducts by product_id without looping
                            $productsById = array_column($savingsProducts, null, 'product_id');
                            // Retrieve the entire product array for the selected product code
                            $selectedProduct = &$productsById[$savings_productId] ?? null;
                            $productbalance = $selectedProduct['product_balance'];

                            // client has insufficient balance, terminate process
                            if ($total_chargeAmt > $productbalance) {
                                return $this->respond([
                                    'status'   => 500,
                                    'error'    => 'Insufficient Balance',
                                    'messages' => 'Reduction of Total Charges(' . number_format($total_chargeAmt, 2) . ') from ' . $selectedProduct['product_name'] . ' failed because client balance(' . number_format($productbalance, 2) . ') is insufficient, Loan Not Disbursed!',
                                ]);
                                exit;
                            }
                            // client has sufficient balance, reduct charge from client savings
                            else {
                                // Update the product balance (or other properties as needed)
                                $newProductBalance = (float)($productbalance - $total_chargeAmt);
                                $selectedProduct['product_balance'] = $newProductBalance;
                                // Convert back to an indexed array Reflecting the update
                                $savingsProducts = array_values($productsById);
                        
                                // new client balance
                                $clientAccBal = [
                                    'account_balance' => (float)($clientInfo['account_balance'] - $total_chargeAmt),
                                    'savings_products' => json_encode($savingsProducts)
                                ];
                                
                                // update client balance
                                $updateClientAccBal = $this->client->update($clientInfo['id'], $clientAccBal);
    
                                // terminate process if savings balance update fails
                                if (!$updateClientAccBal) {
                                    $response = [
                                        'status'   => 500,
                                        'error'    => 'Balance Update Error',
                                        'messages' => 'Failed to update Client Account Balance, Loan Not Disbursed !',
                                    ];
                                    return $this->respond($response);
                                    exit;
                                }
                            }
                        }

                        // maximum principal a client can get[same as loan principal]
                        $principalReceivable = $principal;
                    }
                    if (strtolower($applicationRow['reduct_charges']) == 'principal') {
                        // maximum principal a client can get[principal - total charges]
                        $principalReceivable = ($principal - $total_chargeAmt);
                    }

                    // batch update particulars credit & debit balances for the charges double entry for each transaction
                    $chargesDoubleEntry_update = $this->particular->updateBatch($chargesDoubleEntryData, 'id'); // charges particulars
                    $chargesPaymentDoubleEntry_update = $this->particular->updateBatch($chargesPaymentDoubleEntryData, 'id'); // payment particulars

                    /**
                     *  if update is successful,
                     * save charges entries,
                     * add to activity log then continue 
                     */
                    if ($chargesDoubleEntry_update && $chargesPaymentDoubleEntry_update) {
                        $saveChargesEntries = $this->entry->insertBatch($chargesTransactionData);
                        if ($saveChargesEntries) {
                            // insert status update activity into activity logs
                            $activityData = [
                                'user_id' => $this->userRow['id'],
                                'action' => 'update',
                                'description' => ucfirst('auto computed, deducted and saved application charges from ' . $applicationRow['reduct_charges'] . 'for application ' . $applicationRow['application_code']),
                                'module' => strtolower('applications'),
                                'referrer_id' => $application_id,
                            ];
                            $activity = $this->insertActivityLog($activityData);
                            if (!$activity) {
                                $response = [
                                    'status'   => 500,
                                    'error'    => 'Logging Failed',
                                    'messages' => 'Application charges deducted and saved, Saving activity failed, Loan Not Disbursed!'
                                ];
                                return $this->respond($response);
                                // exit;
                            }
                        } else {
                            $response = [
                                'status'   => 500,
                                'error'    => 'Charges Entries Failed',
                                'messages' => 'Saving Charges Entries\transactions from  ' . $applicationRow['reduct_charges'] . ' failed, Loan Not Disbursed!',
                            ];
                            return $this->respond($response);
                            exit;
                        }
                    } else {
                        $response = [
                            'status'   => 500,
                            'error'    => 'Double Entry Failed',
                            'messages' => 'Double Entry of charges from  ' . $reductCharges . ' failed, Loan Not Disbursed!',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                }

                // applications status update data
                $applicationData = [
                    'status' => 'Disbursed',
                    'level' => trim($this->request->getVar(('level'))),
                    'action' => 'Disbursed',
                    'total_charges' => $total_chargeAmt
                ];
                // applications remarks data
                $applRemarksData = [
                    'application_id' => $application_id,
                    'staff_id' =>  $this->userRow['staff_id'],
                    'status' => 'Disbursed',
                    'level' => trim($this->request->getVar(('level'))),
                    'action' => 'Disbursed',
                    'remarks' => trim($this->request->getVar(('loan_remarks'))),
                    'account_id' => $this->userRow['account_id'],
                ];
                // compute first recovery & loan expiry date
                if (!empty($this->request->getVar('date_disbursed'))) {
                    $date_disbursed = new Time(trim($this->request->getVar('date_disbursed')));
                } else {
                    $date_disbursed = Time::now();
                }

                /**
                 * date for first installment payment(first recovery)
                 * loan expiry date
                 * number of days between each installment(grace period)
                 */
                switch (strtolower($frequency)) {
                    case 'daily':
                        $first_recovery = $date_disbursed->modify('+1 day')->format('Y-m-d');
                        $loan_expiry_date = $date_disbursed->modify('+' . $period . ' day');
                        $grace_period = 1;
                        break;
                    case 'weekly':
                        $first_recovery = $date_disbursed->modify('+1 week')->format('Y-m-d');
                        $loan_expiry_date = $date_disbursed->modify('+' . $period . ' week');
                        $grace_period = 7;
                        break;
                    case 'bi-weekly':
                        $first_recovery = $date_disbursed->modify('+2 week')->format('Y-m-d');
                        $loan_expiry_date = $date_disbursed->modify('+' . $period . ' week');
                        $grace_period = 14;
                        break;
                    case 'monthly':
                        $first_recovery = $date_disbursed->modify('+1 month')->format('Y-m-d');
                        $loan_expiry_date = $date_disbursed->modify('+' . $period . ' month');
                        $grace_period = 30;
                        break;
                    case 'bi-monthly':
                        $first_recovery = $date_disbursed->modify('+2 month')->format('Y-m-d');
                        $loan_expiry_date = $date_disbursed->modify('+' . $period . ' month');
                        $grace_period = 60;
                        break;
                    case 'quarterly':
                        $first_recovery = $date_disbursed->modify('+3 month')->format('Y-m-d');
                        $loan_expiry_date = $date_disbursed->modify('+' . $period . ' month');
                        $grace_period = 90;
                        break;
                    case 'termly':
                        $first_recovery = $date_disbursed->modify('+4 month')->format('Y-m-d');
                        $loan_expiry_date = $date_disbursed->modify('+' . $period . ' month');
                        $grace_period = 120;
                        break;
                    case 'bi-annual':
                        $first_recovery = $date_disbursed->modify('+6 month')->format('Y-m-d');
                        $loan_expiry_date = $date_disbursed->modify('+' . $period . ' month');
                        $grace_period = 180;
                        break;
                    case 'annually':
                        $first_recovery = $date_disbursed->modify('+1 year')->format('Y-m-d');
                        $loan_expiry_date = $date_disbursed->modify('+' . $period . ' year');
                        $grace_period = 365;
                        break;
                    default:
                        $first_recovery = Time::today();
                        $loan_expiry_date = new Time('+' . $period . ' month');
                        $grace_period = ($date_disbursed->diff(Time::createFromFormat('Y-m-d', $first_recovery)))->format('%a');
                        break;
                }

                // data to create new disbursement
                $data = [
                    'disbursement_code' => $this->settings->generateUniqueNo('disbursement'),
                    'cycle' => $cycle,
                    'application_id' => $application_id,
                    'product_id' => $applicationRow['product_id'],
                    'branch_id' => $this->userRow['branch_id'],
                    'client_id' => $applicationRow['client_id'],
                    'staff_id' => $this->userRow['staff_id'],
                    'particular_id' => $particular_id,
                    'interest_particular_id' => $interest_particular_id,
                    'payment_id' => trim($this->request->getVar(('payment_id'))),
                    'disbursed_by' => $disbursed_by,
                    'principal' => $principal,
                    # 'principal' => $principalReceivable,
                    'computed_interest' => str_replace(',', '', trim($this->request->getVar('computed_interest'))),
                    'actual_interest' => str_replace(',', '', trim($this->request->getVar('actual_interest'))),
                    'installments_num' => trim($this->request->getVar('installments_num')),
                    'computed_installment' => str_replace(',', '', trim($this->request->getVar('computed_installment'))),
                    'actual_installment' => str_replace(',', '', trim($this->request->getVar('actual_installment'))),
                    'computed_repayment' => str_replace(',', '', trim($this->request->getVar('computed_repayment'))),
                    'actual_repayment' => str_replace(',', '', trim($this->request->getVar('actual_repayment'))),
                    'principal_installment' => str_replace(',', '', trim($this->request->getVar('principal_installment'))),
                    'interest_installment' => str_replace(',', '', trim($this->request->getVar('interest_installment'))),
                    'first_recovery' => $first_recovery,
                    'expiry_day' => trim(date('l', strtotime($loan_expiry_date))),
                    'grace_period' => $grace_period,
                    'loan_expiry_date' => date('Y-m-d', strtotime($loan_expiry_date)),
                    'loan_period_days' => ($date_disbursed->diff($loan_expiry_date)->format('%a')),
                    'date_disbursed' => (trim($this->request->getVar('date_disbursed'))) ? date('Y-m-d', strtotime($this->request->getVar('date_disbursed'))) : date('Y-m-d'),
                    'account_id' => $this->userRow['account_id'],
                ];
                // get latest client data after possible changes from the charges.
                $newClientInfo = $this->client->find($clientInfo['id']);

                /** 
                 * perform double entry
                 * update client balance
                 * since account type is for Asset category,
                 * debit particular & credit disbursement method if its gaining,
                 * credit particular & debit disbursement method if its loosing,
                 */
                if (strtolower($entry_typeData['part']) == 'debit') {
                    // debit loan particular
                    $loanParticularBal = ['debit' => ($particularRow['debit'] + $principal)];
                    // credit selected disbursement method
                    $paymentParticularBal = ['credit' => ($paymentRow['credit'] + $principal)];
                }
                if (strtolower($entry_typeData['part']) == 'credit') {
                    // credit loan particular
                    // debit client savings particular[liability]
                    $loanParticularBal = ['credit' => ($particularRow['credit'] + $principal)];
                    // debit selected disbursement method[assets]
                    $paymentParticularBal = ['debit' => ($paymentRow['debit'] + $principal)];
                }

                // update application status to disbursed
                $disburse = $this->loanApplication->update($application_id, $applicationData);
                if ($disburse) {
                    // insert into application remarks table
                    $remarks = $this->applicationRemark->insert($applRemarksData);
                    if ($remarks) {
                        // insert status update activity into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'update',
                            'description' => ucfirst('disbursed ' . $principal . ' to ' . $clientInfo['name'] . ', application ' . $applicationRow['application_code']),
                            'module' => strtolower('applications'),
                            'referrer_id' => $application_id,
                        ];
                        $activity = $this->insertActivityLog($activityData);

                        if ($activity) {
                            // insert into disbursement table
                            $insert = $this->disbursement->insert($data);

                            if ($insert) {
                                // insert disbursement activity into activity logs
                                $disActivityData = [
                                    'user_id' => $this->userRow['id'],
                                    'action' => 'create',
                                    'description' => ucfirst('created new ' . $this->title . ' record, code ' . $data['disbursement_code']),
                                    'module' => strtolower('disbursements'),
                                    'referrer_id' => $insert,
                                ];
                                $disActivity = $this->insertActivityLog($disActivityData);

                                if ($disActivity) {
                                    // record disbursement as a transaction
                                    $entryData = [
                                        'date' => date('Y-m-d'),
                                        'payment_id' => $payment_id,
                                        'particular_id' => $particular_id,
                                        'branch_id' => $this->userRow['branch_id'],
                                        'staff_id' => $this->userRow['staff_id'],
                                        'client_id' => $client_id,
                                        'application_id' => $application_id,
                                        'disbursement_id' => $insert,
                                        'entry_menu' => $entry_typeData['entry_menu'],
                                        'entry_typeId' => $entry_typeID,
                                        'ref_id' => strtolower(substr($entry_typeData['type'], 0, 3)) . '-' . date('ym') . '-' . $ref_id,
                                        'entry_details' => "<p>Disbursement of " . $this->settingsRow['currency'] . ". " . number_format($principal, 2) . " to " . $clientInfo['name'] . ", application " . $applicationRow['application_code'] . "</p>",
                                        'account_typeId' => $this->account_typeId,
                                        'amount' => $principal,
                                        'contact' => $clientInfo['mobile'],
                                        'status' => $status,
                                        'balance' => $accountingBalance,
                                        'remarks' => (!empty($this->request->getVar('loan_remarks'))) ? $this->request->getVar('loan_remarks') : "Disbursement of " . $principal . " to " . $clientInfo['name'],
                                        'account_id' => $this->userRow['account_id'],
                                    ];
                                    // save entry
                                    $entry = $this->entry->insert($entryData);

                                    if ($entry) {
                                        // insert into activity logs
                                        $entryActivityData = [
                                            'user_id' => $this->userRow['id'],
                                            'action' => 'create',
                                            'description' => ucfirst('deposited issued ' . $this->title . ', to client, ' . $clientInfo['name']),
                                            'module' => strtolower('transactions'),
                                            'referrer_id' => $ref_id,
                                        ];
                                        $entryActivity = $this->insertActivityLog($entryActivityData);

                                        if ($entryActivity) {
                                            // deposit amount to client's account?
                                            if (strtolower($disbursed_by) == 'deposited into client account') {
                                                // new client account balance update data
                                                $balance = (float)($newClientInfo['account_balance'] + $principalReceivable);
                                                $clientBalance = ['account_balance' => $balance];
                                                $updateClientBalance = $this->client->update($clientInfo['id'], $clientBalance);
                                            } else {
                                                $updateClientBalance = true;
                                            }

                                            if ($updateClientBalance) {
                                                // update particulars' balances after double entry
                                                $particular_idBal = $this->particular->update($particular_id, $loanParticularBal);

                                                $payment_idBal = $this->particular->update($payment_id, $paymentParticularBal);

                                                if (!$particular_idBal) {
                                                    $response = [
                                                        'status' => 500,
                                                        'error' => 'Double Entry Failed',
                                                        'messages' => 'Loan disbursed, Particular Double entry failed to be implemented!',
                                                    ];
                                                    return $this->respond($response);
                                                    exit;
                                                }
                                                if (!$payment_idBal) {
                                                    $response = [
                                                        'status' => 500,
                                                        'error' => 'Double Entry Failed',
                                                        'messages' => 'Loan disbursed, Payment Double entry failed to be implemented!',
                                                    ];
                                                    return $this->respond($response);
                                                    exit;
                                                }
                                                $txt = '';
                                                $checkInternet = $this->settings->checkNetworkConnection();

                                                if ($checkInternet) {
                                                    // send email if client has email
                                                    $data['name'] = $clientInfo['name'];
                                                    $data['email'] = $clientInfo['email'];
                                                    $data['account_type'] = $clientInfo['account_type'];
                                                    $data['branch_name'] = $this->userRow['branch_name'];
                                                    $data['product_name'] = $productRow['product_name'];
                                                    $data['frequency'] = $frequency;

                                                    $data['rate'] = $applicationRow['interest_rate'];
                                                    $data['period'] = $period . ' ' . $loanData['duration'];
                                                    $data['application_code'] = $applicationRow['application_code'];
                                                    $data['purpose'] = $applicationRow['purpose'];
                                                    $data['date'] = date('d-m-Y H:i:s');

                                                    # check the email existence and email notify is enabled
                                                    if (!empty($clientRow['email']) && $this->settingsRow['email']) {
                                                        $subject = "Loan Disbursement";
                                                        $message = $data;
                                                        $token = 'disbursement';
                                                        $this->settings->sendMail($message, $subject, $token);
                                                        $txt .= 'Email Sent';
                                                    }
                                                    # check the mobile existence and sms notify is enabled
                                                    if (!empty($clientInfo['mobile']) && $this->settingsRow['sms']) {
                                                        # send sms
                                                        $sms = $this->sendSMS([
                                                            'mobile' => trim($clientInfo['mobile']),
                                                            'text' => ($productRow['product_name']) . ' loan of ' . $this->settingsRow["currency"] . ' ' . number_format($principal, 2) . ' via ' . strtolower($paymentRow['particular_name']) . ' has been disbursed by ' . strtolower($this->settingsRow['business_name']) . ' on ' . (trim($this->request->getVar('date_disbursed'))) ? date('Y-m-d', strtotime($this->request->getVar('date_disbursed'))) : date('Y-m-d') . ' . ID: ' . $ref_id . ' ~ ' . strtoupper($this->settingsRow["system_abbr"])
                                                        ]);
                                                        $txt .= ' SMS Sent';
                                                    }
                                                }
                                                $response = [
                                                    'status' => 200,
                                                    'error' => null,
                                                    'messages' => $applicationRow['application_code'] . " disbursed successfully." . $txt
                                                ];
                                                return $this->respond($response);
                                                exit;
                                            } else {
                                                $response = [
                                                    'status'   => 500,
                                                    'error'    => 'Balance Update Failed',
                                                    'messages' => $this->title . ' disbursed, updating client balance failed!'
                                                ];
                                                return $this->respond($response);
                                                exit;
                                            }
                                        } else {
                                            $response = [
                                                'status'   => 500,
                                                'error'    => 'Logging Failed',
                                                'messages' => $this->title . ' record created, transaction record created, updating client balance failed'
                                            ];
                                            return $this->respond($response);
                                            exit;
                                        }
                                    } else {
                                        $response = [
                                            'status'   => 500,
                                            'error'    => 'Transaction Failed',
                                            'messages' => $this->title . ' record created, creating transaction record failed!'
                                        ];
                                        return $this->respond($response);
                                        exit;
                                    }
                                } else {
                                    $response = [
                                        'status'   => 500,
                                        'error'    => 'Logging Failed',
                                        'messages' => $this->title . ' record created, transaction failed.!'
                                    ];
                                    return $this->respond($response);
                                    exit;
                                }
                            } else {
                                $response = [
                                    'status' => 500,
                                    'error' => 'Create Failed',
                                    'messages' => 'Creating ' . $this->title . ' record failed, Application status updated successfully!',
                                ];
                                return $this->respond($response);
                                exit;
                            }
                        } else {
                            $response = [
                                'status'   => 500,
                                'error'    => 'Logging Failed',
                                'messages' => 'Application status updated, Saving activity failed, Loan Not Disbursed!'
                            ];
                            return $this->respond($response);
                            exit;
                        }
                    } else {
                        $response = [
                            'status'   => 500,
                            'error'    => 'Remarks Failed',
                            'messages' => 'Saving Application remarks failed, Loan Not Disbursed!'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status'   => 500,
                        'error'    => 'Update Status Failed',
                        'messages' => 'Application status update failed, Loan Not Disbursed!'
                    ];
                    return $this->respond($response);
                    exit;
                }
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to create ' . $this->title . ' records!',
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
    public function update_disbursement($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateDisbursementAdjustment();
                $disbursementInfo = $this->disbursement->find($id);
                if ($disbursementInfo) {
                    $module = trim($this->request->getVar('module'));
                    $loan_expiry_date = new Time(trim($this->request->getVar('expiry_date')));
                    $disbursed_date = new Time($disbursementInfo['created_at']);
                    // adjust disbursement expiry date
                    if (strtolower($module) == "date") {
                        $data = [
                            'loan_period_days' => ($disbursed_date->diff($loan_expiry_date)->format('%a')),
                            'loan_expiry_date' => date('Y-m-d', strtotime($loan_expiry_date)),
                            'expiry_day' => trim(date('l', strtotime($loan_expiry_date))),
                        ];
                    }
                    // adjust disbursement balance
                    if (strtolower($module) == "balance") {
                        $interest_collected = $disbursementInfo['actual_interest'];
                        $principal_collected = $disbursementInfo['principal'];
                        $data = [
                            'interest_collected' => $interest_collected,
                            'principal_collected' => $principal_collected,
                            'total_collected' => ($interest_collected + $principal_collected),
                            'interest_balance' => 0,
                            'principal_balance' => 0,
                            'total_balance' => 0,
                            'arrears' => 0,
                            'principal_due' => 0,
                            'interest_due' => 0,
                            'installments_due' => 0,
                            'days_due' => 0,
                            'status' => 'Fully Paid',
                            'class' => 'Cleared',
                            'comments' => 'Disbursement Cleared',
                        ];
                    }

                    $update = $this->disbursement->update($id, $data);
                    if ($update) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'update',
                            'description' => ucfirst('adjusted disbursement ' . $module . ', ' . $disbursementInfo['disbursement_code']),
                            'module' => strtolower('disbursements'),
                            'referrer_id' => $id,
                        ];
                        $activity = $this->insertActivityLog($activityData);
                        if ($activity) {
                            $response = [
                                'status'   => 200,
                                'error'    => null,
                                'messages' => $this->title . ' ' . $module . ' adjusted successfully',
                            ];
                            return $this->respond($response);
                            exit;
                        } else {
                            $response = [
                                'status'   => 200,
                                'error'    => null,
                                'messages' => $this->title . ' ' . $module . ' adjusted successfully. loggingFailed'
                            ];
                            return $this->respond($response);
                            exit;
                        }
                    } else {
                        $response = [
                            'status' => 500,
                            'error' => 'Update Failed',
                            'messages' => $this->title . ' ' . $module . ' adjustment failed, try again later!',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'Disbursement record could not be found!',
                    ];
                    return $this->respond($response);
                    exit;
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
    /**
     * Delete the designated resource object from the model
     *
     */
    public function ajax_bulky_delete()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
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

    private function _validateDisbursement()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $principal = $this->removeCommasFromAmount($this->request->getVar('principal'));
        $client_id = trim($this->request->getVar('client_id'));
        $clientInfo = $this->client->find($client_id);
        $application_id = $this->request->getVar('application_id');
        $applicationRow = $this->loanApplication->find($application_id);
        $particular_id = trim($this->request->getVar(('particular_id')));
        $interest_particular_id = trim($this->request->getVar(('interest_particular_id')));
        $payment_id = trim($this->request->getVar('payment_id'));

        $particularInfo = $this->particularDataRow($particular_id); // particular info
        $paymentInfo = $this->particularDataRow($payment_id); // payment method info

        $disbursements = $this->disbursement->where(['client_id' => $client_id, 'class !=' => 'Cleared'])->findAll();
        if (count($disbursements) > 0) {
            $data['inputerror'][] = 'payment_id';
            $data['error_string'][] = 'Client has running loan!';
            $data['status'] = FALSE;;
        }
        if ($this->request->getVar('client_id') == '') {
            $data['inputerror'][] = 'payment_id';
            $data['error_string'][] = 'Client ID is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('application_id') == '') {
            $data['inputerror'][] = 'payment_id';
            $data['error_string'][] = 'Application ID is required!';
            $data['status'] = FALSE;
        }
        if ($this->removeCommasFromAmount($this->request->getVar('principal')) == '') {
            $data['inputerror'][] = 'principal';
            $data['error_string'][] = 'Principal is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('total_charges') == '') {
            $data['inputerror'][] = 'total_charges';
            $data['error_string'][] = 'Total Charges is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('reduct_charges') == '') {
            $data['inputerror'][] = 'reduct_charges';
            $data['error_string'][] = 'Deduct Charges is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('reduct_charges') == '')) {
            $reduct = $this->request->getVar('reduct_charges');
            if (strtolower($reduct) == 'savings') {
                if ($this->request->getVar('product_id') == '') {
                    $data['inputerror'][] = 'product_id';
                    $data['error_string'][] = 'Reduction Product is required!';
                    $data['status'] = FALSE;
                }
                # validate product has sufficient balance
                if (!empty($this->request->getVar('product_id') == '')) {
                    $productRow = $this->product->find($this->request->getVar('product_id'));
                    $total_charges = $this->removeCommasFromAmount($this->request->getVar('total_charges'));
                    $savingsProducts = (($clientInfo['savingsProducts']) ? $clientInfo['savingsProducts'] : null);
                    if ($productRow && $savingsProducts) {
                        $productbalance = 0;
                        // Re-index savingsProducts by product_id without looping
                        $productsByCode = array_column($savingsProducts, null, 'product_code');
                        // Retrieve the entire product array for the selected product code
                        $selectedProduct = $productsByCode[$payment_id] ?? null;
                        $productbalance = $selectedProduct['product_balance'];

                        if ($productbalance < $total_charges) {
                            $data['inputerror'][] = 'product_id';
                            $data['error_string'][] = 'Insufficient ' . $productRow['product_name'] . ' balance. Available balance: ' . number_format($productbalance, 2) . '!';
                            $data['status'] = FALSE;
                        }
                    } else {
                        $data['inputerror'][] = 'product_id';
                        $data['error_string'][] = 'Invalid Selection, try another option!';
                        $data['status'] = FALSE;
                    }
                }
                if ($this->request->getVar('reduct_particular_id') == '') {
                    $data['inputerror'][] = 'reduct_particular_id';
                    $data['error_string'][] = 'Reduction Particular is required!';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('principal_receivable') == '') {
            $data['inputerror'][] = 'principal_receivable';
            $data['error_string'][] = 'Principal Receivable is required!';
            $data['status'] = FALSE;
        }
        if ($applicationRow) {
            $productRow = $this->loanProduct->find($applicationRow['product_id']);
            if (strtolower($applicationRow['reduct_charges']) == 'savings') {
                if ($this->request->getVar('reduct_particular_id') == '') {
                    $data['inputerror'][] = 'reduct_particular_id';
                    $data['error_string'][] = 'Deduct Charges Particular is required!';
                    $data['status'] = FALSE;
                }
            }
            # calculate minimum & maximum savings balance at application
            $clientAccountBalance = $clientInfo['account_balance'];
            // minimum
            if ($productRow['min_savings_balance_type_disbursement'] && $productRow['min_savings_balance_disbursement'] > 0) {
                if (strtolower($productRow['min_savings_balance_type_disbursement']) == 'rate') {
                    $minDisbursementBalance = (($productRow['min_savings_balance_disbursement'] / 100) * $principal);
                } elseif (strtolower($productRow['min_savings_balance_type_disbursement']) == 'multiplier') {
                    $minDisbursementBalance = ($productRow['min_savings_balance_disbursement'] * $principal);
                } else {
                    $minDisbursementBalance = $productRow['min_savings_balance_disbursement'];
                }
            } else {
                $minDisbursementBalance = null;
            }
            // maximum
            if ($productRow['max_savings_balance_type_disbursement'] && $productRow['max_savings_balance_disbursement'] > 0) {
                if (strtolower($productRow['max_savings_balance_type_disbursement']) == 'rate') {
                    $maxDisbursementBalance = (($productRow['max_savings_balance_disbursement'] / 100) * $principal);
                } elseif (strtolower($productRow['max_savings_balance_type_disbursement']) == 'multiplier') {
                    $maxDisbursementBalance = ($productRow['max_savings_balance_disbursement'] * $principal);
                } else {
                    $maxDisbursementBalance = $productRow['max_savings_balance_disbursement'];
                }
            } else {
                $maxDisbursementBalance = null;
            }
            # validate minimum & maximum savings balance at Disbursement
            if ($minDisbursementBalance && ($clientAccountBalance < $minDisbursementBalance)) {
                $data['inputerror'][] = 'principal';
                $data['error_string'][] = 'Minimum Savings Balance at Disbursement allowed is ' . $minDisbursementBalance . '!';
                $data['status'] = FALSE;
            }
            if ($maxDisbursementBalance && ($clientAccountBalance > $maxDisbursementBalance)) {
                $data['inputerror'][] = 'principal';
                $data['error_string'][] = 'Maximum Savings Balance at Disbursement allowed is ' . $maxDisbursementBalance . '!';
                $data['status'] = FALSE;
            }
        }
        if (!empty($this->removeCommasFromAmount($this->request->getVar('principal')))) {
            if (!preg_match("/^[0-9,. ]*$/", $principal)) {
                $data['inputerror'][] = 'principal';
                $data['error_string'][] = 'Only Digits allowed!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('installments_num') == '') {
            $data['inputerror'][] = 'installments_num';
            $data['error_string'][] = 'Total Installments is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('installments_num'))) {
            $num = $this->request->getVar('installments_num');
            if (!preg_match("/^[0-9,. ]*$/", $num)) {
                $data['inputerror'][] = 'installments_num';
                $data['error_string'][] = 'Only Digits allowed!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('computed_installment') == '') {
            $data['inputerror'][] = 'computed_installment';
            $data['error_string'][] = 'Computed Installment is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('computed_installment'))) {
            $ci = $this->request->getVar('computed_installment');
            if (!preg_match("/^[0-9,. ]*$/", $ci)) {
                $data['inputerror'][] = 'computed_installment';
                $data['error_string'][] = 'Only Digits allowed!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('actual_installment') == '') {
            $data['inputerror'][] = 'actual_installment';
            $data['error_string'][] = 'Actual Installment is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('actual_installment'))) {
            $ai = $this->request->getVar('actual_installment');
            if (!preg_match("/^[0-9,. ]*$/", $ai)) {
                $data['inputerror'][] = 'actual_installment';
                $data['error_string'][] = 'Only Digits allowed!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('computed_interest') == '') {
            $data['inputerror'][] = 'computed_interest';
            $data['error_string'][] = 'Computed Interest is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('computed_interest'))) {
            $ti = $this->request->getVar('computed_interest');
            if (!preg_match("/^[0-9,. ]*$/", $ti)) {
                $data['inputerror'][] = 'computed_interest';
                $data['error_string'][] = 'Only Digits allowed!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('actual_interest') == '') {
            $data['inputerror'][] = 'actual_interest';
            $data['error_string'][] = 'Interest Payable is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('actual_interest'))) {
            $ip = $this->request->getVar('actual_interest');
            if (!preg_match("/^[0-9,. ]*$/", $ip)) {
                $data['inputerror'][] = 'actual_interest';
                $data['error_string'][] = 'Only Digits allowed!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('computed_repayment') == '') {
            $data['inputerror'][] = 'computed_repayment';
            $data['error_string'][] = 'Total Repayment is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('computed_repayment'))) {
            $tp = $this->request->getVar('computed_repayment');
            if (!preg_match("/^[0-9,. ]*$/", $tp)) {
                $data['inputerror'][] = 'computed_repayment';
                $data['error_string'][] = 'Only Digits allowed!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('actual_repayment') == '') {
            $data['inputerror'][] = 'actual_repayment';
            $data['error_string'][] = 'Expected Repayment is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('actual_repayment'))) {
            $ep = $this->request->getVar('actual_repayment');
            if (!preg_match("/^[0-9,. ]*$/", $ep)) {
                $data['inputerror'][] = 'actual_repayment';
                $data['error_string'][] = 'Only Digits allowed!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('principal_installment') == '') {
            $data['inputerror'][] = 'principal_installment';
            $data['error_string'][] = 'Principle Installment is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('principal_installment'))) {
            $pi = $this->request->getVar('principal_installment');
            if (!preg_match("/^[0-9,. ]*$/", $pi)) {
                $data['inputerror'][] = 'principal_installment';
                $data['error_string'][] = 'Only Digits allowed!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('interest_installment') == '') {
            $data['inputerror'][] = 'interest_installment';
            $data['error_string'][] = 'Interest Installment is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('interest_installment'))) {
            $ii = $this->request->getVar('interest_installment');
            if (!preg_match("/^[0-9,. ]*$/", $ii)) {
                $data['inputerror'][] = 'interest_installment';
                $data['error_string'][] = 'Only Digits allowed!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('disbursed_by') == '') {
            $data['inputerror'][] = 'disbursed_by';
            $data['error_string'][] = 'Disbursement method is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('date_disbursed') == '') {
            $data['inputerror'][] = 'date_disbursed';
            $data['error_string'][] = 'Disbursement Date is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('particular_id') == '') {
            $data['inputerror'][] = 'particular_id';
            $data['error_string'][] = 'Principal Particular is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('interest_particular_id') == '') {
            $data['inputerror'][] = 'interest_particular_id';
            $data['error_string'][] = 'Interest Particular is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('payment_id') == '') {
            $data['inputerror'][] = 'payment_id';
            $data['error_string'][] = 'Disbursement Method is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('payment_id'))) {
            // payment balance
            if (strtolower($paymentInfo['part']) == 'debit') {
                $paymentBalance = (float)(($paymentInfo['opening_balance'] + $paymentInfo['debit']) - $paymentInfo['credit']);
            }
            if (strtolower($particularInfo['part']) == 'credit') {
                $paymentBalance = (float)($paymentInfo['debit'] - ($paymentInfo['opening_balance'] + $paymentInfo['credit']));
            }
            if ($paymentBalance < $principal) {
                $data['inputerror'][] = 'payment_id';
                $data['error_string'][] = $paymentInfo['particular_name'] . ' has insufficient balance of ' . $paymentBalance;
                $data['status'] = FALSE;
            }
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
    private function _validateDisbursementAdjustment()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->request->getVar('id') == '') {
            $data['inputerror'][] = 'id';
            $data['error_string'][] = 'Disbursement ID is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('module') == '') {
            $data['inputerror'][] = 'module';
            $data['error_string'][] = 'Module is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('module'))) {
            $module = $this->request->getVar('module');
            if (strtolower($module) == "date") {
                if ($this->request->getVar('expiry_date') == '') {
                    $data['inputerror'][] = 'expiry_date';
                    $data['error_string'][] = 'Expiry Date is required!';
                    $data['status'] = FALSE;
                }
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
