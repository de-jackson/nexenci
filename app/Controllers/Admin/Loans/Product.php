<?php

namespace App\Controllers\Admin\Loans;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

use function PHPUnit\Framework\isNull;

class Product extends MasterController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Loans';
        $this->title = 'Products';
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
            return view('admin/loans/products/index', [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                // 'charges' => $this->application_chargesParticulars(),
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
    public function product_view($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $product = $this->loanProduct->find($id);
            if ($product) {
                return view('admin/loans/products/index', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'product' => $product,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Product could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    function product_forms($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            if ($id == 0) {
                $title = "Add Loan Product Form";
            } else {
                $title = "Loan Product View Form";
            }
            return view('admin/loans/products/product_formPDF', [
                'title' => $title,
                'id' => $id,
                'product' => $this->loanProduct->find($id),
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    /**
     * return all product as rows
     */
    public function products_list($id = null)
    {
        if ($id == 0) {
            $where = ['deleted_at' => Null];
        } else {
            $where = ['deleted_at' => Null, 'id' => $id];
        }
        $products = $this->loanProduct->select('product_name, interest_rate, interest_period, interest_type, repayment_period, repayment_duration, repayment_freq, min_principal, max_principal, status, id')->where($where);

        return DataTable::of($products)
            ->add('checkbox', function ($product) {
                return '<div class=""><input type="checkbox" class="data-check" value="' . $product->id . '"></div>';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('period', function ($product) {
                return $product->repayment_period . ' ' . $product->repayment_duration;
            })
            ->add('interestRate', function ($product) {
                return $product->interest_rate . '% per ' . $product->interest_period;
            })
            ->add('action', function ($product) {
                if (strtolower($product->status) == 'active') {
                    $text = "info";
                } else {
                    $text = "danger";
                }

                // show buttons based on user permissions
                $actions = '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">';
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                    $actions .= '
                    <a href="javascript:void(0)" onclick="view_product(' . "'" . $product->id . "'" . ')" title="View ' . ucwords($product->product_name) . '" class="dropdown-item"><i class="fas fa-eye text-success"></i> View ' . ucwords($product->product_name) . '</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    $actions .= '
                    <div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" onclick="edit_product(' . "'" . $product->id . "'" . ')" title="Edit ' . ucwords($product->product_name) . '" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit ' . ucwords($product->product_name) . '</a>';
                }

                if (($this->userPermissions == 'all')) {
                    $actions .= '
                    <div class="dropdown-divider"></div>
                    <a href="/admin/loans/product/' . $product->id . '/edit" title="Remove ' . ucwords($product->product_name) . ' Charges" class="dropdown-item"><i class="fas fa-folder text-success"></i> View ' . ucwords($product->product_name) . ' Charges</a>';
                }

                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '
                    <div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" onclick="delete_product(' . "'" . $product->id . "'" . ',' . "'" . ucwords($product->product_name) . "'" . ')" title="Delete ' . ucwords($product->product_name) . '" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete ' . ucwords($product->product_name) . '</a>';
                }
                $actions .= ' 
                        </div>
                </div>';
                return $actions;
            })
            ->toJson(true);
    }
    public function products_report($from = null, $to = null)
    {
        if ($from != 0 && $to == 0) {
            $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'deleted_at' => Null];
        } elseif ($from == 0 && $to != 0) {
            $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $to, 'deleted_at' => Null];
        } elseif ($from != 0 && $to != 0) {
            $where = ['DATE_FORMAT(created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(created_at, "%Y-%m-%d") <=' => $to, 'deleted_at' => Null];
        } else {
            $where = ['deleted_at' => Null];
        }
        $products = $this->loanProduct
            ->select('product_name, interest_rate, interest_type, repayment_period, repayment_freq, status, id')->where($where);
        return DataTable::of($products)
            ->add('checkbox', function ($product) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $product->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('action', function ($product) {
                if (strtolower($product->status) == 'active') {
                    $text = "text-info";
                } else {
                    $text = "text-danger";
                }
                return '
                    <div class="text-center">
                        <a href="javascript:void(0)" onclick="view_product(' . "'" . $product->id . "'" . ')" title="view product" class="' . $text . '"><i class="fas fa-eye"></i></a>
                    </div>
                ';
            })
            ->toJson(true);
    }

    public function getProductCharges($condition)
    {
        $charges = $this->chargeModel->select('charges.*, p.particular_name')
            ->join('particulars as p', 'charges.particular_id = p.id', 'left')
            ->where($condition)->findAll();
        return  $charges;
    }
    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->loanProduct->select('loanproducts.*, principalParticular.particular_name as principal_particular, interestParticular.particular_name as interest_particular')
        ->join('particulars as principalParticular', 'principalParticular.id = loanproducts.principal_particular_id', 'left')->join('particulars as interestParticular', 'interestParticular.id = loanproducts.interest_particular_id', 'left')->find($id);
        // $data = $this->product($id);
        if ($data) {
            $data['charges'] = $this->getCharges([
                'charges.product_id' => $id,
                'charges.status' => 'Active',
            ]);
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requested ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
        }
    }

    /**
     * get all customers
     */
    public function getProducts()
    {
        $data = $this->loanProduct->where(['status' => 'Active'])->findAll();
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
            if (strtolower($mode) == 'create') {
                $this->_validateProduct("add");
                $frequency = trim($this->request->getVar('repayment_freq'));
                $period = trim($this->request->getVar('repayment_period'));
                $product_code = ((!empty($this->request->getVar('product_code'))) ? trim($this->request->getVar('product_code')) : $this->settings->generateRandomNumbers(6, 'digits'));
                # check the loan repayment frequency
                if (!empty($frequency)) {
                    # code...
                    $other = $this->loanProduct->getOtherLoanProduct($frequency);
                    $duration = $other['duration'];
                } else {
                    $duration = NULL;
                }

                # particular charges
                $productCharges = [
                    'ParticularID' => $this->request->getVar('product_charges'),
                    'ParticularCharge' => $this->request->getVar('charges_fees[]'),
                    'ParticularChargeMethod' => $this->request->getVar('charges_types[]'),
                    'ParticularChargeMode' => $this->request->getVar('charges_reduction[]')
                ];

                # check the particular charge existence
                if (!empty($this->request->getVar('product_charges'))) {
                    $loanProductCharges = serialize($productCharges);
                } else {
                    $loanProductCharges = NULL;
                }

                $insert = $this->loanProduct->insert([
                    'product_name' => trim($this->request->getVar('product_name')),
                    'product_code' => $product_code,
                    'principal_particular_id' => trim($this->request->getVar('principal_particular_id')),
                    'interest_particular_id' => trim($this->request->getVar('interest_particular_id')),
                    'interest_rate' => trim($this->request->getVar('interest_rate')),
                    'interest_type' => trim($this->request->getVar('interest_type')),
                    'interest_period' => $this->request->getVar('interest_period'),
                    'loan_period' => trim($this->request->getVar('loan_period')),
                    'loan_frequency' => trim($this->request->getVar('loan_frequency')),
                    'repayment_period' => (empty($period)) ? NULL : $period,
                    'repayment_duration' => $duration,
                    'repayment_freq' => (empty($frequency)) ? NULL : $frequency,
                    'min_principal' => ((!empty($this->request->getVar('min_principal'))) ? trim($this->request->getVar('min_principal')) : NULL),
                    'max_principal' => ((!empty($this->request->getVar('max_principal'))) ? trim($this->request->getVar('max_principal')) : NULL),
                    'min_savings_balance_type_application' => (!empty($this->request->getVar('min_savings_balance_type_application')) ? trim($this->request->getVar('min_savings_balance_type_application')) : null),
                    'min_savings_balance_application' => (!empty($this->request->getVar('min_savings_balance_application')) ? trim($this->request->getVar('min_savings_balance_application')) : null),
                    'max_savings_balance_type_application' => (!empty($this->request->getVar('max_savings_balance_type_application')) ? trim($this->request->getVar('max_savings_balance_type_application')) : null),
                    'max_savings_balance_application' => (!empty($this->request->getVar('max_savings_balance_application')) ? trim($this->request->getVar('max_savings_balance_application')) : null),
                    'min_savings_balance_type_disbursement' => (!empty($this->request->getVar('min_savings_balance_type_disbursement')) ? trim($this->request->getVar('min_savings_balance_type_disbursement')) : null),
                    'min_savings_balance_disbursement' => (!empty($this->request->getVar('min_savings_balance_disbursement')) ? trim($this->request->getVar('min_savings_balance_disbursement')) : null),
                    'max_savings_balance_type_disbursement' => (!empty($this->request->getVar('max_savings_balance_type_disbursement')) ? trim($this->request->getVar('max_savings_balance_type_disbursement')) : null),
                    'max_savings_balance_disbursement' => (!empty($this->request->getVar('max_savings_balance_disbursement')) ? trim($this->request->getVar('max_savings_balance_disbursement')) : null),
                    'product_desc' => (empty($this->request->getVar('product_desc')) ? NULL : ($this->request->getVar('product_desc'))),
                    'product_features' => (empty($this->request->getVar('product_features')) ? NULL : $this->request->getVar('product_features')),
                    'product_charges' => $loanProductCharges,
                    'status' => trim($this->request->getVar('status')),
                    'account_id' => $this->userRow['account_id'],
                ]);
                $index = 1;

                $particular_id = $this->request->getVar('product_charges[]');
                $charge_method = $this->request->getVar('charges_types[]');
                $charge = $this->request->getVar('charges_fees[]');
                $charge_mode = $this->request->getVar('charges_reduction[]');
                $charge_id = $this->request->getVar('charge_id[]');
                # Check whether the set particular charges is set
                if (!empty($particular_id) && count($particular_id) > 0) {
                    # Loop through to retrieve each particular charge
                    $chargesData = [];
                    foreach ($particular_id as $key => $particularId) {
                        # set an associated array to capture the charges
                        $chargesData = [
                            "product_id" => $insert,
                            "particular_id" => $particular_id[$key],
                            "charge_method" => $charge_method[$key],
                            "charge" => $charge[$key],
                            "charge_mode" => $charge_mode[$key],
                            "status" => 'Active',
                            "frequency" => 'One-Time',
                            'account_id' => $this->userRow['account_id'],
                        ];
                        # check the loan product charge existence
                        $counter = $this->chargeModel->where([
                            'product_id' => $insert,
                            'id' => $charge_id[$key],
                            'particular_id' => $particular_id[$key],
                        ])->countAllResults();
                        if ($counter) {
                            # Update the product charges
                            $this->chargeModel->update($charge_id[$key], $chargesData);
                        } else {
                            # Save the product charges
                            $this->chargeModel->insert($chargesData);
                        }
                    }
                    # Save the product charges
                    # $this->chargeModel->insertBatch($chargesData);
                }
            } else {
                if (!empty($_FILES['file']['name'])) {
                    $file = $this->request->getFile("file");
                    $file_name = $file->getTempName();
                    $file_data = array_map('str_getcsv', file($file_name));
                    if (count($file_data) > 0) {
                        $index = 0;
                        $data = [];
                        foreach ($file_data as $input) {
                            // period
                            if (strtolower($input[4]) == 'weekly' || strtolower($input[4]) == 'bi-weekly') {
                                $duration = 'week(s)';
                            } else if (strtolower($input[4]) == 'monthly' || strtolower($input[4]) == 'bi-monthly' || strtolower($input[4]) == 'quarterly' || strtolower($input[4]) == 'termly' || strtolower($input[4]) == 'bi-annual') {
                                $duration = 'month(s)';
                            } else {
                                $duration = 'year(s)';
                            }
                            if ($index > 0) {
                                $data[] = array(
                                    'product_name' => trim($input[0]),
                                    'interest_rate' => trim($input[1]),
                                    'interest_type' => trim($input[2]),
                                    'repayment_period' => trim($input[3]),
                                    'repayment_duration' => $duration,
                                    'repayment_freq' => trim($input[4]),
                                    'product_desc' => trim($input[5]),
                                    'product_features' => trim($input[6]),
                                    'min_principal' => trim($input[7]),
                                    'max_principal' => trim($input[8]),
                                    'status' => trim($input[7]),
                                );
                            }
                            $index++;
                        }
                        # insert imported data
                        $insert = $this->loanProduct->insertBatch($data);
                    }
                } else {
                    # validation
                    $data = array();
                    $data['error_string'] = array();
                    $data['inputerror'] = array();
                    $data['status'] = TRUE;

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
                // insert into activity logs 
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => $mode,
                    'description' => ucfirst($mode . ' ' . $index . ' ' . $this->title . ' record(s)'),
                    'module' => strtolower('products'),
                    'referrer_id' => $insert,
                ];
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

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update_product($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateProduct("update");
                $product = $this->loanProduct->find($id);
                $product_code = ((!empty($this->request->getVar('product_code'))) ? trim($this->request->getVar('product_code')) : $this->settings->generateRandomNumbers(6, 'digits'));
                $frequency = trim($this->request->getVar('repayment_freq'));
                $period = trim($this->request->getVar('repayment_period'));
                # check the loan repayment frequency
                if (!empty($frequency)) {
                    # code...
                    $other = $this->loanProduct->getOtherLoanProduct($frequency);
                    $duration = $other['duration'];
                } else {
                    $duration = NULL;
                }

                # particular charges
                $productCharges = [
                    'ParticularID' => $this->request->getVar('product_charges'),
                    'ParticularCharge' => $this->request->getVar('charges_fees[]'),
                    'ParticularChargeMethod' => $this->request->getVar('charges_types[]'),
                    'ParticularChargeMode' => $this->request->getVar('charges_reduction[]')
                ];
                # check the particular charge existance
                if (!empty($this->request->getVar('product_charges'))) {
                    $loanProductCharges = serialize($productCharges);
                } else {
                    $loanProductCharges = NULL;
                }

                $data = [
                    'product_name' => trim($this->request->getVar('product_name')),
                    'product_code' => $product_code,
                    'principal_particular_id' => trim($this->request->getVar('principal_particular_id')),
                    'interest_particular_id' => trim($this->request->getVar('interest_particular_id')),
                    'interest_rate' => trim($this->request->getVar('interest_rate')),
                    'interest_type' => trim($this->request->getVar('interest_type')),
                    'interest_period' => $this->request->getVar('interest_period'),
                    'loan_period' => trim($this->request->getVar('loan_period')),
                    'loan_frequency' => trim($this->request->getVar('loan_frequency')),
                    'repayment_period' => (empty($period)) ? NULL : $period,
                    'repayment_duration' => $duration,
                    'repayment_freq' => (empty($frequency)) ? NULL : $frequency,
                    'min_principal' => ((!empty($this->request->getVar('min_principal'))) ? trim($this->request->getVar('min_principal')) : NULL),
                    'max_principal' => ((!empty($this->request->getVar('max_principal'))) ? trim($this->request->getVar('max_principal')) : NULL),
                    'min_savings_balance_type_application' => (!empty($this->request->getVar('min_savings_balance_type_application')) ? trim($this->request->getVar('min_savings_balance_type_application')) : null),
                    'min_savings_balance_application' => (!empty($this->request->getVar('min_savings_balance_application')) ? trim($this->request->getVar('min_savings_balance_application')) : null),
                    'max_savings_balance_type_application' => (!empty($this->request->getVar('max_savings_balance_type_application')) ? trim($this->request->getVar('max_savings_balance_type_application')) : null),
                    'max_savings_balance_application' => (!empty($this->request->getVar('max_savings_balance_application')) ? trim($this->request->getVar('max_savings_balance_application')) : null),
                    'min_savings_balance_type_disbursement' => (!empty($this->request->getVar('min_savings_balance_type_disbursement')) ? trim($this->request->getVar('min_savings_balance_type_disbursement')) : null),
                    'min_savings_balance_disbursement' => (!empty($this->request->getVar('min_savings_balance_disbursement')) ? trim($this->request->getVar('min_savings_balance_disbursement')) : null),
                    'max_savings_balance_type_disbursement' => (!empty($this->request->getVar('max_savings_balance_type_disbursement')) ? trim($this->request->getVar('max_savings_balance_type_disbursement')) : null),
                    'max_savings_balance_disbursement' => (!empty($this->request->getVar('max_savings_balance_disbursement')) ? trim($this->request->getVar('max_savings_balance_disbursement')) : null),
                    'product_desc' => (empty($this->request->getVar('product_desc')) ? NULL : ($this->request->getVar('product_desc'))),
                    'product_features' => (empty($this->request->getVar('product_features')) ? NULL : $this->request->getVar('product_features')),
                    'product_charges' => $loanProductCharges,
                    'status' => trim($this->request->getVar('status')),
                ];

                $particular_id = $this->request->getVar('product_charges[]');
                $charge_method = $this->request->getVar('charges_types[]');
                $charge = $this->request->getVar('charges_fees[]');
                $charge_mode = $this->request->getVar('charges_reduction[]');
                $charge_id = $this->request->getVar('charge_id[]');
                # Check whether the set particular charges is set
                if (!empty($particular_id) && count($particular_id) > 0) {
                    # Loop through to retrieve each particular charge
                    $chargesData = [];
                    foreach ($particular_id as $key => $particularId) {
                        # set an associated array to capture the charges
                        $chargesData = [
                            "product_id" => $id,
                            "particular_id" => $particular_id[$key],
                            "charge_method" => $charge_method[$key],
                            "charge" => $charge[$key],
                            "charge_mode" => $charge_mode[$key],
                            "status" => 'Active',
                            "frequency" => 'One-Time',
                            'account_id' => $this->userRow['account_id'],
                        ];
                        # check the loan product charge existance
                        $counter = $this->chargeModel->where([
                            'product_id' => $id,
                            'id' => $charge_id[$key],
                            'particular_id' => $particular_id[$key],
                        ])->countAllResults();
                        # Check the condition to save the product charge
                        if (strtolower($charge_id[$key]) == 'create') {
                            # Save the product charges
                            $this->chargeModel->insert($chargesData);
                        }
                        # Check the product charge existance
                        if ($counter) {
                            # Update the product charges
                            $this->chargeModel->update($charge_id[$key], $chargesData);
                        }
                    }
                    # Save the product charges
                    # $this->chargeModel->insertBatch($chargesData);
                }

                $update = $this->loanProduct->update($id, $data);

                if ($update) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'update',
                        'description' => ucfirst('updated ' . $this->title . ', ' . $data['product_name']),
                        'module' => strtolower('products'),
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
            $data = $this->loanProduct->find($id);
            if ($data) {
                $delete = $this->loanProduct->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', ' . $data['product_name']),
                        'module' => strtolower('products'),
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        # delete particular's corresponding charges
                        $deleteCharges = $this->chargeModel->where('particular_id', $id)->delete();
                        if ($deleteCharges) {
                            $this->saveUserActivity([
                                'user_id' => $this->userRow['id'],
                                'action' => 'delete',
                                'description' => ('Bulk deleted ' . $data['particular_name'] . 'charges'),
                                'module' => strtolower('particulars'),
                                'referrer_id' => $id,
                                'title' => $this->title,
                            ]);
                            $response = [
                                'status'   => 200,
                                'error'    => null,
                                'messages' => $this->title . ' record deleted successfully',
                            ];
                            return $this->respond($response);
                            exit;
                        }
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
                $data = $this->loanProduct->find($id);
                if ($data) {
                    $delete = $this->loanProduct->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['product_name']),
                            'module' => strtolower('products'),
                            'referrer_id' => $id,
                        ];
                        $activity = $this->insertActivityLog($activityData);
                        # delete particular's corresponding charges
                        $deleteCharges = $this->chargeModel->where('particular_id', $id)->delete();
                        if ($deleteCharges) {
                            $this->saveUserActivity([
                                'user_id' => $this->userRow['id'],
                                'action' => 'delete',
                                'description' => ('Bulk deleted ' . $data['particular_name'] . 'charges'),
                                'module' => strtolower('particulars'),
                                'referrer_id' => $id,
                                'title' => $this->title,
                            ]);
                        }
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
     * validate form inputs
     */
    private function _validateProduct($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $productInfo = $this->loanProduct->find($this->request->getVar('id'));
        /** product name validation */
        if ($this->request->getVar('product_name') == '') {
            $data['inputerror'][] = 'product_name';
            $data['error_string'][] = 'Product Name is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('product_name'))) {
            $name = $this->request->getVar('product_name');
            if ($this->settings->validateName($name) == TRUE) {
                if (strlen(trim($name)) < 5) {
                    $data['inputerror'][] = 'product_name';
                    $data['error_string'][] = 'Minimum 5 letters required [' . strlen(trim($name)) . ']!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($name) == FALSE) {
                $data['inputerror'][] = 'product_name';
                $data['error_string'][] = 'Valid Product Name is required!';
                $data['status'] = FALSE;
            }
            if ($method == 'add') {
                $dat = $this->loanProduct
                    ->where(['product_name' => $this->request->getVar('product_name')])->first();
                if ($dat) {
                    $data['inputerror'][] = 'product_name';
                    $data['error_string'][] = $this->request->getVar('product_name') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
            if ($method == 'update' && strtolower($productInfo['product_name']) != strtolower($this->request->getVar('product_name'))) {
                $info = $this->loanProduct
                    ->where(['product_name' => $this->request->getVar('product_name')])->first();
                if ($info) {
                    $data['inputerror'][] = 'product_name';
                    $data['error_string'][] = $this->request->getVar('product_name') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }

        if (!empty($this->request->getVar('product_code'))) {
            $code = $this->request->getVar('product_code');
            if ($code < 0) {
                $data['inputerror'][] = 'product_code';
                $data['error_string'][] = 'Valid Code should be a positive integer!';
                $data['status'] = FALSE;
            }
            if (strlen($code) < 4) {
                $data['inputerror'][] = 'product_code';
                $data['error_string'][] = 'Minimum 4 letters required [' . strlen($code) . ']!';
                $data['status'] = FALSE;
            }
            if (preg_match('/^[-0-9- ]+$/', trim($code)) == FALSE) {
                $data['inputerror'][] = 'product_code';
                $data['error_string'][] = 'Code can only be digits and -!';
                $data['status'] = FALSE;
            }
            if ($method == 'add') {
                $dat = $this->loanProduct->where(['product_code' => $this->request->getVar('product_code')])->first();
                if ($dat) {
                    $data['inputerror'][] = 'product_code';
                    $data['error_string'][] = $this->request->getVar('product_code') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
            if ($method == 'update' && $productInfo['product_code'] != $this->request->getVar('product_code')) {
                $info = $this->loanProduct->where(['product_code' => $this->request->getVar('product_code')])->first();
                if ($info) {
                    $data['inputerror'][] = 'product_code';
                    $data['error_string'][] = $this->request->getVar('product_code') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }

        if ($this->request->getVar('principal_particular_id') == '') {
            $data['inputerror'][] = 'principal_particular_id';
            $data['error_string'][] = 'Product Principal Ledger is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('interest_particular_id') == '') {
            $data['inputerror'][] = 'interest_particular_id';
            $data['error_string'][] = 'Product Interest Ledger is required!';
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

        if (!empty($this->request->getVar('loan_period'))) {
            # accept only digits for the loan period
            if (!preg_match("/^[0-9.' ]*$/", $this->request->getVar('loan_period'))) {
                $data['inputerror'][] = 'loan_period';
                $data['error_string'][] = 'Only digits are required for loan period!';
                $data['status'] = FALSE;
            }
        }

        /** interest rate validation */
        if ($this->request->getVar('interest_rate') == '') {
            $data['inputerror'][] = 'interest_rate';
            $data['error_string'][] = 'Interest rate is required!';
            $data['status'] = FALSE;
        }

        if (!empty($this->request->getVar('interest_rate'))) {
            $rate = $this->request->getVar('interest_rate');
            if (!preg_match("/^[0-9.']*$/", $rate)) {
                $data['inputerror'][] = 'interest_rate';
                $data['error_string'][] = 'Only valid numbers are allowed!';
                $data['status'] = FALSE;
            }
            if (($rate > 100) && (preg_match("/^[0-9.']*$/", $rate))) {
                $data['inputerror'][] = 'interest_rate';
                $data['error_string'][] = 'Maximum percentage exceeded!';
                $data['status'] = FALSE;
            }
        }

        # loan interest period
        if ($this->request->getVar('interest_period') == '') {
            $data['inputerror'][] = 'interest_period';
            $data['error_string'][] = 'Interest Period is required!';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('product_desc') == '') {
            $data['inputerror'][] = 'product_desc';
            $data['error_string'][] = 'Description is required!';
            $data['status'] = FALSE;
        }

        if (!empty($this->request->getVar('product_desc'))) {
            $pdt_f = $this->request->getVar('product_desc');
            if (strlen(trim($pdt_f)) < 4) {
                $data['inputerror'][] = 'product_desc';
                $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($pdt_f)) . ']!';
                $data['status'] = FALSE;
            }
        }
        if (!empty($this->request->getVar('product_features'))) {
            $pdt_f = $this->request->getVar('product_features');
            if (strlen(trim($pdt_f)) < 4) {
                $data['inputerror'][] = 'product_features';
                $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($pdt_f)) . ']!';
                $data['status'] = FALSE;
            }
        }

        if ($this->request->getVar('repayment_period') == '') {
            $data['inputerror'][] = 'repayment_period';
            $data['error_string'][] = 'Repayment period is required!';
            $data['status'] = FALSE;
        }

        if (!empty($this->request->getVar('repayment_period'))) {
            $repay = $this->request->getVar('repayment_period');
            if (!preg_match("/^[0-9' ]*$/", $repay)) {
                $data['inputerror'][] = 'repayment_period';
                $data['error_string'][] = 'Only digits allowed!';
                $data['status'] = FALSE;
            }
            if (strlen(trim($repay)) > 3) {
                $data['inputerror'][] = 'repayment_period';
                $data['error_string'][] = 'Maximum 3 digits required [' . strlen(trim($repay)) . ']!';
                $data['status'] = FALSE;
            }
        }
        /** interest method allowed */
        if ($this->request->getVar('interest_type') == '') {
            $data['inputerror'][] = 'interest_type';
            $data['error_string'][] = 'Interest method is required!';
            $data['status'] = FALSE;
        }
        /** loan status allowed */
        if ($this->request->getVar('status') == '') {
            $data['inputerror'][] = 'status';
            $data['error_string'][] = 'Loan status is required!';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('repayment_freq') == '') {
            $data['inputerror'][] = 'repayment_freq';
            $data['error_string'][] = 'Frequency is required!';
            $data['status'] = FALSE;
        }

        if (!empty($this->request->getVar('repayment_freq')) && !empty($this->request->getVar('repayment_period'))) {
            $freq = $this->request->getVar('repayment_freq');
            $period = $this->request->getVar('repayment_period');
            $interval = $this->settings->generateIntervals($freq);
            $mode = fmod($period, $interval['interval']);
            if ($mode != 0) {
                $data['inputerror'][] = 'repayment_period';
                $data['error_string'][] = 'Frequency and Period are not compatible!';
                $data['status'] = FALSE;
            }
        }
        // validate min principal
        if (!empty($this->request->getVar('min_principal'))) {
            $min = $this->request->getVar('min_principal');
            if (!preg_match("/^[0-9']*$/", $min)) {
                $data['inputerror'][] = 'min_principal';
                $data['error_string'][] = 'Only digits are allowed!';
                $data['status'] = FALSE;
            }
            if ($min < 100) {
                $data['inputerror'][] = 'min_principal';
                $data['error_string'][] = 'Min principal can not be less than 100!';
                $data['status'] = FALSE;
            }
        }
        // validate max principal
        if (!empty($this->request->getVar('max_principal'))) {
            $max = $this->request->getVar('max_principal');
            if (!preg_match("/^[0-9']*$/", $max)) {
                $data['inputerror'][] = 'max_principal';
                $data['error_string'][] = 'Only digits are allowed!';
                $data['status'] = FALSE;
            }
            if ($max < 100) {
                $data['inputerror'][] = 'max_principal';
                $data['error_string'][] = 'Max principal can not be less than 100!';
                $data['status'] = FALSE;
            }
        }
        // validate min and max principal
        if (!empty($this->request->getVar('min_principal')) && !empty($this->request->getVar('max_principal'))) {
            $min = $this->request->getVar('min_principal');
            $max = $this->request->getVar('max_principal');
            if ($max < $min) {
                $data['inputerror'][] = 'max_principal';
                $data['error_string'][] = 'Max principal can not be less than Min Principal!';
                $data['status'] = FALSE;
            }
        }

        $savingsBalApplication = trim($this->request->getVar('min_savings_balance_application'));
        if (!empty($savingsBalApplication)) {
            if (!preg_match("/^[0-9.]+$/", $savingsBalApplication)) {
                $data['inputerror'][] = 'min_savings_balance_application';
                $data['error_string'][] = 'Only digits are required';
                $data['status'] = FALSE;
            }
        }

        if (!empty($this->request->getVar('min_savings_balance_disbursement'))) {
            if (!preg_match("/^[0-9.]+$/", $this->request->getVar('min_savings_balance_disbursement'))) {
                $data['inputerror'][] = 'min_savings_balance_disbursement';
                $data['error_string'][] = 'Only digits are required';
                $data['status'] = FALSE;
            }
        }

        # validate the loan product charges
        $ParticularIDs = $this->request->getVar('product_charges[]');
        $ParticularCharge = $this->request->getVar('charges_fees[]');
        $ParticularChargeMode = $this->request->getVar('charges_reduction[]');
        $ParticularChargeMethod = $this->request->getVar('charges_types[]');

        if (!empty($ParticularIDs) && count($ParticularIDs) > 0) {
            # count the number of subcharges
            foreach ($ParticularIDs as $index => $particularId) {
                # validate particular charge type
                if (empty($ParticularChargeMethod[$index])) {
                    $data['inputerror'][] = 'charges_types[' . $index . ']';
                    $data['error_string'][] = 'Charge Type is required';
                    $data['status'] = FALSE;
                }
                # validate particular charge fees
                if (empty($ParticularCharge[$index])) {
                    $data['inputerror'][] = 'charges_fees[' . $index . ']';
                    $data['error_string'][] = 'Charge Fees is required';
                    $data['status'] = FALSE;
                }

                if (!empty($ParticularCharge[$index])) {
                    if (!preg_match('/^\d+(\.\d+)?$/', $ParticularCharge[$index])) {
                        $data['inputerror'][] = 'charges_fees[' . $index . ']';
                        $data['error_string'][] = 'Valid Charge Fees is required';
                        $data['status'] = FALSE;
                    }
                }

                # validate particular charge mode
                if (empty($ParticularChargeMode[$index])) {
                    $data['inputerror'][] = 'charges_reduction[' . $index . ']';
                    $data['error_string'][] = 'Charge Mode is required';
                    $data['status'] = FALSE;
                }
            }
        }


        if ($data['status'] === FALSE) {
            echo json_encode($data);
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
        $product = $this->loanProduct->find($id);

        if (($this->userPermissions == 'all')) {

            if ($product) {
                return view('admin/charges/index', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'particular_id' => $id,
                    'particularName' => $product['product_name'],
                    'module' => 'product',
                    'userMenu' => $this->load_menu(),
                ]);
            } else {
                return view('layout/404', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ]);
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
}
