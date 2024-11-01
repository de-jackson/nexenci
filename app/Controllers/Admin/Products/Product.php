<?php

namespace App\Controllers\Admin\Products;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Product extends MasterController
{
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Products';
        $this->menuItem = [
            'title' => $this->title,
        ];
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        //
    }
    public function product_types($type)
    {
        $this->menu = ucwords($type);
        $this->menuItem['menu'] = $this->menu;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem) == true) {
            switch (strtolower($type)) {
                case 'loans':
                    $url = 'admin/products/loans/index';
                    $data = [
                        'title' => $this->title,
                        'menu' => $this->menu,
                        'settings' => $this->settingsRow,
                        'user' => $this->userRow,
                        'userMenu' => $this->load_menu(),
                        'charges' => $this->getCharges([
                            'charges.status' => 'Active',
                            'p.account_typeId' => 18,
                            'p.particular_status' => 'Active',
                            'p.deleted_at !=' => null,
                            'charges.product_id' => null
                        ]),
                    ];
                    break;
                case 'savings':
                    $url = 'admin/savings/products';
                    $data = [
                        'title' => $this->title,
                        'menu' => $this->menu,
                        'settings' => $this->settingsRow,
                        'user' => $this->userRow,
                        'userMenu' => $this->load_menu(),
                        'charges' => $this->getCharges([
                            'charges.status' => 'Active',
                            'p.account_typeId' => 18,
                            'p.particular_status' => 'Active',
                            // 'p.deleted_at IS NOT' => null,
                            'charges.product_id' => null
                        ]),
                    ];
                    break;
                default:
                    throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid Product Type.');
                    break;
            }
            return view($url, $data);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " product page!");
            return redirect()->to(base_url('/admin/dashboard'));
        }
    }
    public function product_view($id)
    {
        $product = $this->product->find($id);
        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Product could have been deleted or there might be a problem with your URL.');
        }
        $type = ucwords($product['product_type']);
        $this->menu = ucwords($type);
        $this->menuItem['menu'] = $this->menu;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $this->title = ucfirst($product['product_type']);
            $this->menuItem['title'] = $this->title;
            $product = $this->product->find($id);
            return view('admin/products/index', [
                'title' => $this->title,
                'menu' => $this->menu,
                'product' => $product,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " product page!");
            return redirect()->to(base_url('/admin/dashboard'));
        }
    }

    /**
     * return all product as table rows
     */
    public function products_list($product, $id = null)
    {
        if ($id == 0) {
            $where = ['product_type' => strtolower($product), 'deleted_at' => Null];
        } else {
            $where = ['product_type' => strtolower($product), 'deleted_at' => Null, 'id' => $id];
        }
        $products = $this->product->select('product_name, product_code, product_type, min_account_balance, max_account_balance, min_per_entry, max_per_entry, product_desc, interest_rate, interest_period, interest_type, product_period, product_duration, product_frequency, application_min_savings_balance_type, application_min_savings_balance, application_max_savings_balance_type, application_max_savings_balance, disbursement_min_savings_balance_type, disbursement_min_savings_balance, disbursement_max_savings_balance_type, disbursement_max_savings_balance, status, id')->where($where);

        return DataTable::of($products)
            ->add('checkbox', function ($product) {
                return '<div class=""><input type="checkbox" class="data-check" value="' . $product->id . '"></div>';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('productPeriod', function ($product) {
                if ($product->product_period && $product->product_duration) {
                    return $product->product_period . ' ' . $product->product_duration . ' <i>(' . $product->product_frequency . ')</i>';
                } else {
                    return '';
                }
            })
            ->add('interestRate', function ($product) {
                if ($product->interest_rate && $product->interest_period) {
                    return $product->interest_rate . '% per ' . $product->interest_period . ' <i>(' . $product->interest_type . ')</i>';
                } else {
                    return '';
                }
            })
            ->add('application_min_savings', function ($product) {
                if ($product->application_min_savings_balance_type && strtolower($product->application_min_savings_balance_type) == 'rate') {
                    return $product->application_min_savings_balance . '% of savings ';
                }
                if ($product->application_min_savings_balance_type && strtolower($product->application_min_savings_balance_type) == 'amount') {
                    return $this->settingsRow['currency'] . ' ' . $product->application_min_savings_balance . ' in savings ';
                } else {
                    return '';
                }
            })
            ->add('application_max_savings', function ($product) {
                if ($product->application_max_savings_balance_type && strtolower($product->application_max_savings_balance_type) == 'rate') {
                    return $product->application_max_savings_balance . '% of savings ';
                }
                if ($product->application_max_savings_balance_type && strtolower($product->application_max_savings_balance_type) == 'amount') {
                    return $this->settingsRow['currency'] . ' ' . $product->application_max_savings_balance . ' in savings ';
                } else {
                    return '';
                }
            })
            ->add('disbursement_min_savings', function ($product) {
                if ($product->disbursement_min_savings_balance_type && strtolower($product->disbursement_min_savings_balance_type) == 'rate') {
                    return $product->disbursement_min_savings_balance . '% of savings ';
                }
                if ($product->disbursement_min_savings_balance_type && strtolower($product->disbursement_min_savings_balance_type) == 'amount') {
                    return $this->settingsRow['currency'] . ' ' . $product->disbursement_min_savings_balance . ' in savings ';
                } else {
                    return '';
                }
            })
            ->add('disbursement_max_savings', function ($product) {
                if ($product->disbursement_max_savings_balance_type && strtolower($product->disbursement_max_savings_balance_type) == 'rate') {
                    return $product->disbursement_max_savings_balance . '% of savings ';
                }
                if ($product->disbursement_max_savings_balance_type && strtolower($product->disbursement_max_savings_balance_type) == 'amount') {
                    return $this->settingsRow['currency'] . ' ' . $product->disbursement_max_savings_balance . ' in savings ';
                } else {
                    return '';
                }
            })
            ->add('action', function ($product) {
                $type = ucwords($product->product_type);
                $this->menu = ucwords($type);
                $this->menuItem['menu'] = $this->menu;
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
                    <a href="/admin/products/product/' . $product->id . '/edit" title="Remove ' . ucwords($product->product_name) . ' Charges" class="dropdown-item"><i class="fas fa-folder text-success"></i> View ' . ucwords($product->product_name) . ' Charges</a>';
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

    public function getProductCharges($condition)
    {
        $charges = $this->chargeModel->select('charges.*, p.particular_name')
            ->join('particulars as p', 'charges.particular_id = p.id', 'left')
            ->where($condition)->findAll();
        return  $charges;
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $data = $this->product->select('products.*, savingsParticular.particular_name as savings_particular, withdrawCharges.particular_name as withdrawCharges_particular')
        ->join('particulars as savingsParticular', 'savingsParticular.id = products.savings_particular_id', 'left')->join('particulars as withdrawCharges', 'withdrawCharges.id = products.withdrawCharges_particular_id', 'left')->find($id);
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
                'messages' => 'The requsted ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
        }
    }

    /**
     * get all products by type
     */
    public function getProducts($type)
    {
        $data = $this->product->where(['product_type' => strtolower($type), 'status' => 'Active'])->findAll();
        return $this->respond($data);
    }

    /**
     * get client products
     */
    public function get_clientProducts($client_id)
    {
        $client = $this->client->find($client_id);
        if ($client) {
            $clientProducts = (!empty($client['savings_products']) ? json_decode($client['savings_products']) : null);
            if ($clientProducts) {
                $products = $this->get_productsDetails($clientProducts);
                return $this->respond(($products));
                exit;
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => "Client hasn't Subcribed to Any Product!",
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => "Client Data couldn't be found!",
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $type = ucwords(trim($this->request->getVar('product_type')));
        $this->menu = ucwords($type);
        $this->menuItem['menu'] = $this->menu;
        $mode = $this->request->getVar('mode');
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, strtolower($mode))) {
            if (strtolower($mode) == 'create') {
                $this->_validateProduct("add");

                $product_code = ((!empty($this->request->getVar('product_code'))) ? trim($this->request->getVar('product_code')) : $this->settings->generateRandomNumbers(6, 'digit'));

                $frequency = trim($this->request->getVar('product_frequency'));
                $period = trim($this->request->getVar('product_period'));
                $duration = $loanProductCharges = NULL;
                # check the loan product frequence
                if (!empty($frequency) && !empty($period)) {
                    # code...
                    $other = $this->product->getOtherLoanProduct($frequency);
                    $duration = $other['duration'];
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
                    $loanProductCharges = json_encode($productCharges);
                }

                $insert = $this->product->insert([
                    'product_name' => trim($this->request->getVar('product_name')),
                    'product_code' => $product_code,
                    'savings_particular_id' => trim($this->request->getVar('savings_particular_id')),
                    'withdrawCharges_particular_id' => trim($this->request->getVar('withdrawCharges_particular_id')),
                    'product_type' => trim($this->request->getVar('product_type')),
                    'min_account_balance' => ((!empty($this->request->getVar('min_account_balance'))) ? $this->removeCommasFromAmount($this->request->getVar('min_account_balance')) : NULL),
                    'max_account_balance' => ((!empty($this->request->getVar('max_account_balance'))) ? $this->removeCommasFromAmount($this->request->getVar('max_account_balance')) : NULL),
                    'min_per_entry' => ((!empty($this->request->getVar('min_per_entry'))) ? $this->removeCommasFromAmount($this->request->getVar('min_per_entry')) : NULL),
                    'max_per_entry' => ((!empty($this->request->getVar('max_per_entry'))) ? $this->removeCommasFromAmount($this->request->getVar('max_per_entry')) : NULL),
                    'interest_rate' => (empty(trim($this->request->getVar('interest_rate'))) ? Null : trim($this->request->getVar('interest_rate'))),
                    'interest_type' => (empty(trim($this->request->getVar('interest_type'))) ? Null : trim($this->request->getVar('interest_type'))),
                    'interest_period' => (empty(trim($this->request->getVar('interest_period'))) ? Null : trim($this->request->getVar('interest_period'))),
                    'product_frequency' => (empty($frequency)) ? NULL : $frequency,
                    'product_period' => (empty($period)) ? NULL : $period,
                    'product_duration' => $duration,
                    // 'application_min_savings_balance_type' => (!empty($this->request->getVar('application_min_savings_balance_type')) ? trim($this->request->getVar('application_min_savings_balance_type')) : NULL),
                    // 'application_min_savings_balance' => (!empty($this->request->getVar('application_min_savings_balance')) ? trim($this->request->getVar('application_min_savings_balance')) : NULL),
                    // 'application_max_savings_balance_type' => (!empty($this->request->getVar('application_max_savings_balance_type')) ? trim($this->request->getVar('application_max_savings_balance_type')) : NULL),
                    // 'application_max_savings_balance' => (!empty($this->request->getVar('application_max_savings_balance')) ? trim($this->request->getVar('application_max_savings_balance')) : NULL),
                    // 'disbursement_min_savings_balance_type' => (!empty($this->request->getVar('disbursement_min_savings_balance_type')) ? trim($this->request->getVar('disbursement_min_savings_balance_type')) : NULL),
                    // 'disbursement_min_savings_balance' => (!empty($this->request->getVar('disbursement_min_savings_balance')) ? trim($this->request->getVar('disbursement_min_savings_balance')) : NULL),
                    // 'disbursement_max_savings_balance_type' => (!empty($this->request->getVar('disbursement_max_savings_balance_type')) ? trim($this->request->getVar('disbursement_max_savings_balance_type')) : NULL),
                    // 'disbursement_max_savings_balance' => (!empty($this->request->getVar('disbursement_max_savings_balance')) ? trim($this->request->getVar('disbursement_max_savings_balance')) : NULL),
                    'product_desc' => trim($this->request->getVar('product_desc')),
                    'product_features' => trim($this->request->getVar('product_features')),
                    // 'product_charges' => $loanProductCharges,
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
                            "frequency" => 'Once',
                            'account_id' => $this->userRow['account_id'],
                        ];
                        # check the loan product charge existance
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
                    $this->chargeModel->insertBatch($chargesData);
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
                                    'product_period' => trim($input[3]),
                                    'product_duration' => $duration,
                                    'product_frequency' => trim($input[4]),
                                    'product_desc' => trim($input[5]),
                                    'product_features' => trim($input[6]),
                                    'min_per_entry' => trim($input[7]),
                                    'max_per_entry' => trim($input[8]),
                                    'status' => trim($input[7]),
                                );
                            }
                            $index++;
                        }
                        # insert imported data
                        $insert = $this->product->insertBatch($data);
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
                    'action' => '$mode',
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
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        $product = $this->product->find($id);

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
            return redirect()->to(base_url('/admin/dashboard'));
        }
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        //
    }
    public function update_product($id = null)
    {
        if (isset($id)) {
            $product = $this->product->find($id);

            $type = ucwords($product['product_type']);
            $this->menu = ucwords($type);
            $this->menuItem['menu'] = $this->menu;

            if (($this->userPermissions == 'all') || (in_array('update_' . strtolower($this->menu) . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
                $this->_validateProduct("update");

                $product = $this->product->find($id);
                $product_code = ((!empty($this->request->getVar('product_code'))) ? trim($this->request->getVar('product_code')) : $this->settings->generateRandomNumbers(6, 'digit'));

                $frequency = trim($this->request->getVar('product_frequency'));
                $period = trim($this->request->getVar('product_period'));
                $duration = $loanProductCharges = NULL;
                # check the loan product frequency
                if (!empty($frequency) && !empty($period)) {
                    # code...
                    $other = $this->product->getOtherLoanProduct($frequency);
                    $duration = $other['duration'];
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
                    $loanProductCharges = json_encode($productCharges);
                }

                $data = [
                    'product_name' => trim($this->request->getVar('product_name')),
                    'product_code' => $product_code,
                    'savings_particular_id' => trim($this->request->getVar('savings_particular_id')),
                    'withdrawCharges_particular_id' => trim($this->request->getVar('withdrawCharges_particular_id')),
                    'product_type' => trim($this->request->getVar('product_type')),
                    'min_account_balance' => ((!empty($this->request->getVar('min_account_balance'))) ? $this->removeCommasFromAmount($this->request->getVar('min_account_balance')) : NULL),
                    'max_account_balance' => ((!empty($this->request->getVar('max_account_balance'))) ? $this->removeCommasFromAmount($this->request->getVar('max_account_balance')) : NULL),
                    'min_per_entry' => ((!empty($this->request->getVar('min_per_entry'))) ? $this->removeCommasFromAmount($this->request->getVar('min_per_entry')) : NULL),
                    'max_per_entry' => ((!empty($this->request->getVar('max_per_entry'))) ? $this->removeCommasFromAmount($this->request->getVar('max_per_entry')) : NULL),
                    'interest_rate' => (empty(trim($this->request->getVar('interest_rate'))) ? Null : trim($this->request->getVar('interest_rate'))),
                    'interest_type' => (empty(trim($this->request->getVar('interest_type'))) ? Null : trim($this->request->getVar('interest_type'))),
                    'interest_period' => (empty(trim($this->request->getVar('interest_period'))) ? Null : trim($this->request->getVar('interest_period'))),
                    'product_frequency' => (empty($frequency)) ? NULL : $frequency,
                    'product_period' => (empty($period)) ? NULL : $period,
                    'product_duration' => $duration,
                    // 'application_min_savings_balance_type' => (!empty($this->request->getVar('application_min_savings_balance_type')) ? trim($this->request->getVar('application_min_savings_balance_type')) : NULL),
                    // 'application_min_savings_balance' => (!empty($this->request->getVar('application_min_savings_balance')) ? trim($this->request->getVar('application_min_savings_balance')) : NULL),
                    // 'application_max_savings_balance_type' => (!empty($this->request->getVar('application_max_savings_balance_type')) ? trim($this->request->getVar('application_max_savings_balance_type')) : NULL),
                    // 'application_max_savings_balance' => (!empty($this->request->getVar('application_max_savings_balance')) ? trim($this->request->getVar('application_max_savings_balance')) : NULL),
                    // 'disbursement_min_savings_balance_type' => (!empty($this->request->getVar('disbursement_min_savings_balance_type')) ? trim($this->request->getVar('disbursement_min_savings_balance_type')) : NULL),
                    // 'disbursement_min_savings_balance' => (!empty($this->request->getVar('disbursement_min_savings_balance')) ? trim($this->request->getVar('disbursement_min_savings_balance')) : NULL),
                    // 'disbursement_max_savings_balance_type' => (!empty($this->request->getVar('disbursement_max_savings_balance_type')) ? trim($this->request->getVar('disbursement_max_savings_balance_type')) : NULL),
                    // 'disbursement_max_savings_balance' => (!empty($this->request->getVar('disbursement_max_savings_balance')) ? trim($this->request->getVar('disbursement_max_savings_balance')) : NULL),
                    'product_desc' => trim($this->request->getVar('product_desc')),
                    'product_features' => trim($this->request->getVar('product_features')),
                    // 'product_charges' => $loanProductCharges,
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
                            "frequency" => 'Once',
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

                $update = $this->product->update($id, $data);

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
                            'messages' => 'Product updated successfully',
                        ];
                        return $this->respond($response);
                        exit;
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => 'Product updated successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Update Failed',
                        'messages' => 'Updating Product record failed, try again later!',
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
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Update Failed. Invalid ID provided, try again!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $data = $this->product->find($id);
        if ($data) {
            $type = ucwords($data['product_type']);
            $this->menu = ucwords($type);
            $this->menuItem['menu'] = $this->menu;

            if (($this->userPermissions == 'all') || (in_array('delete_' . strtolower($this->menu) . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
                $delete = $this->product->delete($id);
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
                                'description' => ('Bulk deleted ' . $data['product_name'] . 'charges'),
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
                    'status'   => 403,
                    'error'    => 'Access Denied',
                    'messages' => 'You are not authorized to delete ' . $this->title . ' record!',
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
    public function ajax_bulky_delete()
    {
        $list_id = $this->request->getVar('id');
        foreach ($list_id as $id) {
            $data = $this->product->find($id);
            if ($data) {
                $type = ucwords($data['product_type']);
                $this->menu = ucwords($type);
                $this->menuItem['menu'] = $this->menu;
                if (($this->userPermissions == 'all') || (in_array('bulkDelete_' . strtolower($this->menu) . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
                    $delete = $this->product->delete($id);
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
                                'description' => ('Bulk deleted ' . $data['product_name'] . ' charges'),
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
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'The requsted ' . $this->title . ' resource could not be found!',
                ];
                return $this->respond($response);
                exit;
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
        $productInfo = $this->product->find($this->request->getVar('id'));
        $product_type = $this->request->getVar('product_type');
        $min = $this->removeCommasFromAmount($this->request->getVar('min_per_entry'));
        $max = $this->removeCommasFromAmount($this->request->getVar('max_per_entry'));

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
                $dat = $this->product
                    ->where(['product_name' => $this->request->getVar('product_name')])->first();
                if ($dat) {
                    $data['inputerror'][] = 'product_name';
                    $data['error_string'][] = $this->request->getVar('product_name') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
            if ($method == 'update' && strtolower($productInfo['product_name']) != strtolower($this->request->getVar('product_name'))) {
                $info = $this->product
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
                $dat = $this->product
                    ->where(['product_code' => $code])->first();
                if ($dat) {
                    $data['inputerror'][] = 'product_code';
                    $data['error_string'][] = $code . ' already added!';
                    $data['status'] = FALSE;
                }
            }
            if ($method == 'update' && $productInfo['product_code'] != $this->request->getVar('product_code')) {
                $info = $this->product
                    ->where(['product_code' => $code])->first();
                if ($info) {
                    $data['inputerror'][] = 'product_code';
                    $data['error_string'][] = $code . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('savings_particular_id') == '') {
            $data['inputerror'][] = 'savings_particular_id';
            $data['error_string'][] = 'Product Savings Ledger is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('withdrawCharges_particular_id') == '') {
            $data['inputerror'][] = 'withdrawCharges_particular_id';
            $data['error_string'][] = 'Product Withdraw Charges Ledger is required!';
            $data['status'] = FALSE;
        }
        /** interest rate validation */
        if ((strtolower($product_type) == 'loans')) {
            if ($this->request->getVar('interest_rate') == '') {
                $data['inputerror'][] = 'interest_rate';
                $data['error_string'][] = 'Interest rate is required!';
                $data['status'] = FALSE;
            }

            # loan interest period
            if ($this->request->getVar('interest_period') == '') {
                $data['inputerror'][] = 'interest_period';
                $data['error_string'][] = 'Interest Period is required!';
                $data['status'] = FALSE;
            }

            /** interest method allowed */
            if ($this->request->getVar('interest_type') == '') {
                $data['inputerror'][] = 'interest_type';
                $data['error_string'][] = 'Interest method is required!';
                $data['status'] = FALSE;
            }
        }

        if (!empty($this->request->getVar('interest_rate'))) {
            $rate = $this->request->getVar('interest_rate');
            if (!preg_match("/^[0-9.']*$/", $rate)) {
                $data['inputerror'][] = 'interest_rate';
                $data['error_string'][] = 'Only valid numbers are allowed!';
                $data['status'] = FALSE;
            }
            if (($rate < 0) && (preg_match("/^[0-9.']*$/", $rate))) {
                $data['inputerror'][] = 'interest_rate';
                $data['error_string'][] = 'Interest rate can not be zero!';
                $data['status'] = FALSE;
            }
            if (($rate > 100) && (preg_match("/^[0-9.']*$/", $rate))) {
                $data['inputerror'][] = 'interest_rate';
                $data['error_string'][] = 'Maximum Interest Rate exceeded!';
                $data['status'] = FALSE;
            }
        }

        /*
        if ($this->request->getVar('product_desc') == '') {
            $data['inputerror'][] = 'product_desc';
            $data['error_string'][] = 'Description is required!';
            $data['status'] = FALSE;
        }
        */

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
        /*
        if ($this->request->getVar('product_period') == '') {
            $data['inputerror'][] = 'product_period';
            $data['error_string'][] = 'Product period is required!';
            $data['status'] = FALSE;
        }
        */

        if (!empty($this->request->getVar('product_period'))) {
            $repay = $this->request->getVar('product_period');
            if (!preg_match("/^[0-9' ]*$/", $repay)) {
                $data['inputerror'][] = 'product_period';
                $data['error_string'][] = 'Only digits allowed!';
                $data['status'] = FALSE;
            }
            if (strlen(trim($repay)) > 3) {
                $data['inputerror'][] = 'product_period';
                $data['error_string'][] = 'Maximum 3 digits required [' . strlen(trim($repay)) . ']!';
                $data['status'] = FALSE;
            }
        }
        /** loan status allowed */
        if ($this->request->getVar('status') == '') {
            $data['inputerror'][] = 'status';
            $data['error_string'][] = 'Loan status is required!';
            $data['status'] = FALSE;
        }

        /*
        if ($this->request->getVar('product_frequency') == '') {
            $data['inputerror'][] = 'product_frequency';
            $data['error_string'][] = 'Product frequency is required!';
            $data['status'] = FALSE;
        }
        */

        if (!empty($this->request->getVar('product_frequency'))) {
            $freq = $this->request->getVar('product_frequency');
            if (strlen(trim($freq)) < 4) {
                $data['inputerror'][] = 'product_frequency';
                $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($freq)) . ']!';
                $data['status'] = FALSE;
            }
        }
        if (!empty($this->request->getVar('product_frequency')) && !empty($this->request->getVar('product_period'))) {
            $freq = $this->request->getVar('product_frequency');
            $period = $this->request->getVar('product_period');
            $interval = $this->settings->generateIntervals($freq);
            $mode = fmod($period, $interval['interval']);
            if ($mode != 0) {
                $data['inputerror'][] = 'product_period';
                $data['error_string'][] = 'Frequency and Period are not compatible!';
                $data['status'] = FALSE;
            }
        }

        // validate min Transaction Amount
        if (!empty($this->request->getVar('min_per_entry'))) {
            if (!preg_match("/^[0-9']*$/", $min)) {
                $data['inputerror'][] = 'min_per_entry';
                $data['error_string'][] = 'Only digits are allowed!';
                $data['status'] = FALSE;
            }
            if ($min < 0) {
                $data['inputerror'][] = 'min_per_entry';
                $data['error_string'][] = 'Min Transaction Amount can not be less than 0!';
                $data['status'] = FALSE;
            }
        }
        // validate max Transaction Amount
        if (!empty($this->request->getVar('max_per_entry'))) {
            if (!preg_match("/^[0-9']*$/", $max)) {
                $data['inputerror'][] = 'max_per_entry';
                $data['error_string'][] = 'Only digits are allowed!';
                $data['status'] = FALSE;
            }
            if ($max < 0) {
                $data['inputerror'][] = 'max_per_entry';
                $data['error_string'][] = 'Max Transaction Amount can not be less than 0!';
                $data['status'] = FALSE;
            }
        }
        // validate min and max Transaction Amount
        if (!empty($this->request->getVar('min_per_entry')) && !empty($this->request->getVar('max_per_entry'))) {
            if ($max < $min) {
                $data['inputerror'][] = 'max_per_entry';
                $data['error_string'][] = 'Max Transaction Amount can not be less than Min Transaction Amount!';
                $data['status'] = FALSE;
            }
        }

        // validate minimum at application is set if its type is set
        if (!empty(trim($this->request->getVar('application_min_savings_balance_type')))) {
            if (empty(trim($this->request->getVar('application_min_savings_balance')))) {
                $data['inputerror'][] = 'application_min_savings_balance';
                $data['error_string'][] = 'Minimum Savings Balance at Application is required!';
                $data['status'] = FALSE;
            }
        }
        // validate minimum at application has only digits
        if (!empty(trim($this->request->getVar('application_min_savings_balance')))) {
            if (!preg_match("/^[0-9.]+$/", $this->removeCommasFromAmount($this->request->getVar('application_min_savings_balance')))) {
                $data['inputerror'][] = 'application_min_savings_balance';
                $data['error_string'][] = 'Only digits are required!';
                $data['status'] = FALSE;
            }
        }
        // validate maximum at application is set if its type is set
        if (!empty(trim($this->request->getVar('application_max_savings_balance_type')))) {
            if (empty(trim($this->request->getVar('application_max_savings_balance')))) {
                $data['inputerror'][] = 'application_max_savings_balance';
                $data['error_string'][] = 'Maximum Savings Balance at Application is required!';
                $data['status'] = FALSE;
            }
        }
        // validate maximum at application has only digits
        if (!empty(trim($this->request->getVar('application_max_savings_balance')))) {
            if (!preg_match("/^[0-9.]+$/", $this->removeCommasFromAmount($this->request->getVar('application_max_savings_balance')))) {
                $data['inputerror'][] = 'application_max_savings_balance';
                $data['error_string'][] = 'Only digits are required!';
                $data['status'] = FALSE;
            }
        }
        // validate maximum at application isnt less than the minimum
        if (!empty(trim($this->request->getVar('application_min_savings_balance'))) && !empty(trim($this->request->getVar('application_max_savings_balance')))) {
            if ($this->removeCommasFromAmount($this->request->getVar('application_min_savings_balance')) > $this->removeCommasFromAmount($this->request->getVar('application_max_savings_balance'))) {
                $data['inputerror'][] = 'application_max_savings_balance';
                $data['error_string'][] = 'Minimum Savings Balance at Application can not be greater than the Maximum!';

                $data['inputerror'][] = 'application_max_savings_balance';
                $data['error_string'][] = 'Maximum Savings Balance at Application can not be less than the Minimum!';

                $data['status'] = FALSE;
            }
        }

        // validate minimum at disbursement is set if its type is set
        if (!empty(trim($this->request->getVar('disbursement_min_savings_balance_type')))) {
            if (empty(trim($this->request->getVar('disbursement_min_savings_balance')))) {
                $data['inputerror'][] = 'disbursement_min_savings_balance';
                $data['error_string'][] = 'Minimum Savings Balance at Disbursement is required!';
                $data['status'] = FALSE;
            }
        }
        // validate minimum at disbursement has only digits
        if (!empty($this->request->getVar('disbursement_min_savings_balance'))) {
            if (!preg_match("/^[0-9.]+$/", $this->request->getVar('disbursement_min_savings_balance'))) {
                $data['inputerror'][] = 'disbursement_min_savings_balance';
                $data['error_string'][] = 'Only digits are required!';
                $data['status'] = FALSE;
            }
        }
        // validate maximum at disbursement is set if its type is set
        if (!empty(trim($this->request->getVar('disbursement_max_savings_balance_type')))) {
            if (empty(trim($this->request->getVar('disbursement_max_savings_balance')))) {
                $data['inputerror'][] = 'disbursement_max_savings_balance';
                $data['error_string'][] = 'Maximum Savings Balance at Disbursement is required!';
                $data['status'] = FALSE;
            }
        }
        // validate maximum at disbursement has only digits
        if (!empty($this->request->getVar('disbursement_max_savings_balance'))) {
            if (!preg_match("/^[0-9.]+$/", $this->request->getVar('disbursement_max_savings_balance'))) {
                $data['inputerror'][] = 'disbursement_max_savings_balance';
                $data['error_string'][] = 'Only digits are required!';
                $data['status'] = FALSE;
            }
        }
        // validate maximum at disbursement isnt less than the minimum
        if (!empty(trim($this->request->getVar('disbursement_min_savings_balance'))) && !empty(trim($this->request->getVar('disbursement_max_savings_balance')))) {
            if (trim($this->request->getVar('disbursement_min_savings_balance')) > trim($this->request->getVar('disbursement_max_savings_balance'))) {
                $data['inputerror'][] = 'disbursement_max_savings_balance';
                $data['error_string'][] = 'Minimum Savings Balance at Disbursement is greater than the maximum!';

                $data['inputerror'][] = 'disbursement_max_savings_balance';
                $data['error_string'][] = 'Maximum Savings Balance at Disbursement is less than the minimum!';

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
                    $data['error_string'][] = 'Charge Type is required!';
                    $data['status'] = FALSE;
                }
                # validate particular charge fees
                if (empty($ParticularCharge[$index])) {
                    $data['inputerror'][] = 'charges_fees[' . $index . ']';
                    $data['error_string'][] = 'Charge Fees is required!';
                    $data['status'] = FALSE;
                }

                if (!empty($ParticularCharge[$index])) {
                    if (!preg_match('/^\d+(\.\d+)?$/', $this->removeCommasFromAmount($ParticularCharge[$index]))) {
                        $data['inputerror'][] = 'charges_fees[' . $index . ']';
                        $data['error_string'][] = 'Valid Charge Fees is required!';
                        $data['status'] = FALSE;
                    }
                }

                # validate particular charge mode
                if (empty($ParticularChargeMode[$index])) {
                    $data['inputerror'][] = 'charges_reduction[' . $index . ']';
                    $data['error_string'][] = 'Charge Mode is required!';
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
