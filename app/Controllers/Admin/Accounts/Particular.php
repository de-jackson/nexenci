<?php

namespace App\Controllers\Admin\Accounts;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Particular extends MasterController
{
    protected $particularsCounter;
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Accounting';
        $this->title = 'Particulars';
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
            return view('admin/accounts/particulars/index', [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                // 'particularsCounter' => $this->counter('particulars'),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    public function particular_view($particular_id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $particular = $this->particularDataRow($particular_id);
            if ($particular) {
                return view('admin/accounts/particulars/index', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'particular' => $particular,
                    // 'chargeMode' => $this->settings->generateChargeModes(),
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                    // 'particularsCounter' => $this->counter('particulars'),
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Particular could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    function particular_forms($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            if ($id == 0) {
                $title = "Add Particular Form";
            } else {
                $title = "Particular View Form";
            }
            return view('admin/accounts/particulars/particular_formPDF', [
                'title' => $title,
                'id' => $id,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'particular' => $this->particularDataRow($id),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    /**
     * return all particulars as rows
     */
    public function particulars_list($category_id = null, $particular_id = null)
    {
        if ($category_id != 0) {
            $where = ['particulars.deleted_at' => Null, 'particulars.category_id' => $category_id];
        }
        if ($particular_id != 0) {
            $where = ['particulars.deleted_at' => Null, 'particulars.id' => $particular_id];
        }
        $particulars = $this->particular
            ->select('particulars.particular_name, particulars.particular_code, particulars.debit, particulars.credit, particulars.particular_type, particulars.particular_status, particulars.category_id, subcategories.subcategory_name, categories.part, account_types.name as type, cash_flow_types.name as cash_flow, particulars.id, particulars.charged')
            ->join('subcategories', 'subcategories.id = particulars.subcategory_id', 'left')
            ->join('categories', 'categories.id = particulars.category_id', 'left')
            ->join('account_types', 'account_types.id = particulars.account_typeId', 'left')
            ->join('cash_flow_types', 'cash_flow_types.id = particulars.cash_flow_typeId', 'left')
            ->where($where);

        return DataTable::of($particulars)
            ->add('checkbox', function ($particular) {
                return '<div class=""><input type="checkbox" class="data-check' . $particular->category_id . '" value="' . $particular->id . '"></div>';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add("balance", function ($particular) {
                if (strtolower($particular->part) == 'debit') {
                    // $balance = (($particular->opening_balance + $particular->debit) - $particular->credit);
                    $balance = ($particular->debit - $particular->credit);
                } else {
                    // $balance = ($particular->debit - ($particular->opening_balance + $particular->credit));
                    $balance = ($particular->debit - $particular->credit);
                }
                if (strtolower($particular->part) == 'debit') {
                    if ($balance < 0) {
                        $balance = '(' . number_format(abs($balance)) . ')';
                    } else {
                        $balance = number_format($balance);
                    }
                } else {
                    if ($balance <= 0) {
                        $balance = number_format(abs($balance));
                    } else {
                        $balance = '(' . number_format($balance) . ')';
                    }
                }
                return $balance;
            })
            ->add('action', function ($particular) {
                if (strtolower($particular->particular_status) == 'active') {
                    $text = "success";
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
                    $actions .= '<a href="javascript:void(0)" onclick="view_particular(' . "'" . $particular->id . "'" . ')" title="view ' . ucwords($particular->particular_name) . '" class="dropdown-item"><i class="fas fa-eye text-success"></i> View ' . ucwords($particular->particular_name) . '</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="edit_particular(' . "'" . $particular->id . "'" . ')" title="edit ' . ucwords($particular->particular_name) . '" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit ' . ucwords($particular->particular_name) . '</a>';
                }
                if ((strtolower($particular->charged) == 'yes') &&
                    ($this->userPermissions == 'all')
                ) {
                    $actions .= '
                    <div class="dropdown-divider"></div>
                    <a href="/admin/settings/charges/' . $particular->id . '/edit" title="Set ' . ucwords($particular->particular_name) . ' Charges" class="dropdown-item"><i class="fas fa-pencil text-success"></i> Set Charges</a>';
                }
                if ((strtolower($particular->particular_type) != 'system') && $this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="delete_particular(' . "'" . $particular->id . "'" . ',' . "'" . $particular->particular_name . "'" . ')" title="delete ' . ucwords($particular->particular_name) . '" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete ' . ucwords($particular->particular_name) . '</a>';
                }
                $actions .= ' 
                        </div>
                </div>';
                return $actions;
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
        $data = $this->particular->find($id);
        if ($data) {
            $particular = $this->particularDataRow($id);
            return $this->respond(($particular));
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

    public function subParticulars($subcategory_id)
    {
        $data = $this->particular->where(['subcategory_id' => $subcategory_id, 'particular_status' => 'Active'])->findAll();;
        return $this->respond($data);
    }
    /**
     * get particulars
     */
    public function getParticulars()
    {
        $data = $this->particular->where(['particular_status' => 'Active'])->findAll();
        return $this->respond($data);
    }
    // get account types by category id
    public function getCategoryAccountTypes($category_id = null, $module = null)
    {
        // only load ccount types suitable for investmets
        if (strtolower($module) == 'investmet') {
            $investmetAccounts = [1, 2, 6, 7];
            $data = $this->accountType->find($investmetAccounts);
        } else {
            $where = ($category_id == "null") ? ['status' => 'Active'] : ['category_id' => $category_id, 'status' => 'Active'];
            $data = $this->accountType->where($where)->findAll();
        }
        return $this->respond($data);
    }

    // get account types based on the category part
    public function account_types_by_part($part = null)
    {
        if (strtolower($part) == 'debit') {
            $where = ['categories.part' => $part, 'account_types.id !=' => 1, 'account_types.status' => 'Active'];
            $data = $this->accountType->select('account_types.*, categories.part, categories.statement_id')
                ->join('categories', 'categories.id = account_types.category_id', 'left')
                ->where($where)->where('account_types.id !=', 3)->findAll();
        } else {
            $where = ['categories.part' => $part, 'account_types.status' => 'Active'];
            $data = $this->accountType->select('account_types.*, categories.part, categories.statement_id')
                ->join('categories', 'categories.id = account_types.category_id', 'left')
                ->where($where)->findAll();
        }
        return $this->respond($data);
    }
    // get particular cash flow type
    public function getParticularCashFlowType()
    {
        $data = $this->cashFlow->where(['status' => 'Active'])->findAll();
        return $this->respond($data);
    }
    // get mode of payment particulars
    public function paymet_methods()
    {
        $data = $this->particular->select('particulars.id, particulars.particular_name, particulars.particular_type, particulars.particular_status, particulars.charged, particulars.charge, particulars.charge_method, particulars.charge_mode, particulars.grace_period')->where(['particulars.account_typeId' => 1, 'particulars.particular_status' => 'Active'])->findAll();
        return $this->respond($data);
    }
    public function getParticulars_by_categoryPart($part)
    {
        $data = $this->particular
            ->select('particulars.*, categories.category_name, categories.part, categories.statement')
            ->join('categories', 'categories.id = particulars.category_id', 'left')
            ->where(['categories.part' => $part, 'particulars.account_typeId !=' => 1, 'particulars.particular_status' => 'Active'])->findAll();
        return $this->respond($data);
    }
    public function accountType_particulars($account_type = null)
    {
        $data = $this->particular
            ->select('particulars.*, subcategories.id as s_id, subcategories.subcategory_name, subcategories.subcategory_status, subcategories.subcategory_slug, subcategories.created_at as screated, subcategories.updated_at as supdated, categories.id as c_id, categories.category_name,categories.category_slug, categories.part, statements.name as statement, account_types.name as account_type')
            ->join('subcategories', 'subcategories.id = particulars.subcategory_id', 'left')
            ->join('categories', 'categories.id = particulars.category_id', 'left')
            ->join('statements', 'statements.id = categories.statement_id', 'left')
            ->join('account_types', 'account_types.id = particulars.account_typeId', 'left')
            ->join('cash_flow_types', 'cash_flow_types.id = particulars.cash_flow_typeId', 'left')
            ->where(['particulars.account_typeId' => $account_type, 'particulars.particular_status' => 'Active'])
            ->findAll();
        return $this->respond($data);
    }

    public function particular_chargeOptions($option)
    {
        return $this->respond(($this->settings->generateChargeOptions($option)));
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
                $this->_validateParticular("add");
                // $charge_frequency = (!empty($this->request->getVar('charge_frequency')) ? trim($this->request->getVar('charge_frequency')) : null);
                // $charge_method = (!empty($this->request->getVar('charge_method')) ? trim($this->request->getVar('charge_method')) : null);
                // $charge_mode = (!empty($this->request->getVar('charge_mode')) ? trim($this->request->getVar('charge_mode')) : null);

                $charge_frequency = $this->request->getVar('charge_frequency[]');
                $charge_method = $this->request->getVar('charge_method[]');
                $charge_mode = $this->request->getVar('charge_mode[]');
                $charge = $this->request->getVar('charge[]');
                $charge_limit = $this->request->getVar('charge_limits[]');
                $effective_date = $this->request->getVar('effective_date[]');
                $cutoff_date = $this->request->getVar('cutoff_date[]');
                $charge_status = $this->request->getVar('charge_status[]');

                $particular_code = ((!empty($this->request->getVar('particular_code'))) ? trim($this->request->getVar('particular_code')) : $this->settings->generateRandomNumbers(6, 'digit'));

                $charged = 'No';
                if (strtolower($this->request->getVar('charged')) == "yes") {
                    $charged = $this->request->getVar('charged');
                }

                $data = [
                    'category_id' => trim($this->request->getVar('category_id')),
                    'subcategory_id' => trim($this->request->getVar('subcategory_id')),
                    'account_typeId' => trim($this->request->getVar('account_typeId')),
                    'cash_flow_typeId' => trim($this->request->getVar('cash_flow_typeId')),
                    'particular_name' => trim($this->request->getVar('particular_name')),
                    'particular_code' => $particular_code,
                    'particular_slug' => trim(url_title(strtolower($this->request->getVar('particular_name')), '-', true)),
                    '' => trim($this->request->getVar('particular_code')),
                    'particular_type' => trim($this->request->getVar('particular_type')),
                    'particular_status' => trim($this->request->getVar('particular_status')),
                    'charged' => $charged,
                    'account_id' => $this->userRow['account_id'],
                    // 'charge_method' => $charge_method,
                    // 'charge_mode' => $charge_mode,
                    // 'charge_frequency' => $charge_frequency,
                ];

                $insert = $this->particular->insert($data);
                $index = 1;
                # Check whether the set particular charges is set
                if (!empty($this->request->getVar('charged'))) {
                    # Loop through to retrieve each particular charge
                    for ($i = 0; $i < count($charge_frequency); $i++) {
                        # set an associated array to capture the charges
                        $chargesData[] = [
                            "particular_id" => $insert,
                            "frequency" => $charge_frequency[$i],
                            "charge_method" => $charge_method[$i],
                            "charge_mode" => $charge_mode[$i],
                            "charge" => $this->removeCommasFromAmount($charge[$i]),
                            "charge_limit" => $this->removeCommasFromAmount($charge_limit[$i]),
                            "effective_date" => $effective_date[$i],
                            "cutoff_date" => (!empty($cutoff_date[$i]) ? $cutoff_date[$i] : null),
                            "status" => $charge_status[$i]
                        ];
                    }
                    # Save the particular charges
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
                        foreach ($file_data as $column) {
                            $index++;
                            # ignore the column headers
                            if ($index == 1) {
                                continue;
                            }

                            # ignore empty row in excel sheets
                            if ((string) $column[0] != '0' && empty($column[0])) {
                                continue;
                            }
                            // get particular subcategory
                            $subcategory_code = trim($column[4]);
                            $subcategory = $this->subcategory->where(['subcategory_code' => $subcategory_code])->first();
                            if (!$subcategory) {
                                continue;
                            }

                            // get particular account type
                            $account_type_code = trim($column[5]);
                            $account_type = $this->accountType->where(['code' => $account_type_code])->first();
                            if (!$account_type) {
                                continue;
                            }

                            # generate particular code
                            $particular_code = (!empty($column[1]) ? trim($column[1]) : $this->settings->generateRandomNumbers(6, 'digit'));

                            $importData = [
                                'particular_name' => trim($column[0]),
                                'particular_code' => $particular_code,
                                'particular_slug' => trim(url_title(strtolower($column[0]), '-', true)),
                                'particular_type' => trim($this->request->getVar('particular_type')),
                                'particular_status' => trim($column[2]),
                                'category_id' => trim($column[3]),
                                'subcategory_id' => $subcategory['id'],
                                'account_typeId' => $account_type['id'],
                                'cash_flow_typeId' => trim($column[6]),
                                'account_id' => $this->userRow['account_id'],
                            ];
                            # save the particular information
                            $insert = $this->particular->insert($importData);
                        }
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
                # Activity
                $record = ($index == 1) ? " record" : " records";
                $this->saveUserActivity([
                    'user_id' => $this->userRow['id'],
                    'action' => $mode,
                    'description' => ($index . ' particular ' . $record),
                    'module' => strtolower('particulars'),
                    'referrer_id' => $insert,
                    'title' => $this->title,
                ]);
            } else {
                $response = [
                    'status' => 500,
                    'error' => ucfirst($mode) . ' Failed',
                    'messages' => ucfirst($mode) . ' ' . $this->title . ' record failed, try again later!',
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
    public function update_particular($id = null)
    {
        $mode = $this->request->getVar('mode');
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateParticular("update");
                $particular_code = ((!empty($this->request->getVar('particular_code'))) ? trim($this->request->getVar('particular_code')) : $this->settings->generateRandomNumbers(6, 'digit'));

                $charge_id = $this->request->getVar('charge_id[]');
                $operation = $this->request->getVar('operation[]');
                $charge_frequency = $this->request->getVar('charge_frequency[]');
                $charge_method = $this->request->getVar('charge_method[]');
                $charge_mode = $this->request->getVar('charge_mode[]');
                $charge = $this->request->getVar('charge[]');
                $charge_limits = $this->request->getVar('charge_limits[]');
                $effective_date = $this->request->getVar('effective_date[]');
                $cutoff_date = $this->request->getVar('cutoff_date[]');
                $charge_status = $this->request->getVar('charge_status[]');
                $charged = 'No';
                if (strtolower($this->request->getVar('charged')) == "yes") {
                    $charged = $this->request->getVar('charged');
                }

                $data = [
                    'category_id' => trim($this->request->getVar('category_id')),
                    'subcategory_id' => trim($this->request->getVar('subcategory_id')),
                    'account_typeId' => trim($this->request->getVar('account_typeId')),
                    'cash_flow_typeId' => trim($this->request->getVar('cash_flow_typeId')),
                    'particular_name' => trim($this->request->getVar('particular_name')),
                    'particular_slug' => trim(url_title(strtolower($this->request->getVar('particular_name')), '-', true)),
                    'particular_code' => $particular_code,
                    'particular_type' => trim($this->request->getVar('particular_type')),
                    'particular_status' => trim($this->request->getVar('particular_status')),
                    'charged' => trim($charged),
                    // 'charge_method' => $charge_method,
                    // 'charge_mode' => $charge_mode,
                    // 'charge_frequency' => $charge_frequency,
                ];

                $update = $this->particular->update($id, $data);

                # Check whether the set particular charges is set
                if (!empty($this->request->getVar('charged'))) {
                    # Loop through to retrieve each particular charge
                    for ($i = 0; $i < count($charge); $i++) {
                        # set an associated array to capture the charges
                        $chargesData = [
                            "frequency" => $charge_frequency[$i],
                            "charge_method" => $charge_method[$i],
                            "charge_mode" => $charge_mode[$i],
                            "charge" => $this->removeCommasFromAmount($charge[$i]),
                            "charge_limits" => $this->removeCommasFromAmount($charge_limits[$i]),
                            "effective_date" => $effective_date[$i],
                            "cutoff_date" => (!empty($cutoff_date[$i]) ? $cutoff_date[$i] : null),
                            "status" => $charge_status[$i]
                        ];

                        if (strtolower($mode) == "update") {
                            if ($i == 0) {
                                # Check whether any of them is empty
                                if (
                                    empty($charge_frequency[$i]) || empty($charge_method[$i]) || empty($charge_mode[$i]) || empty($charge[$i]) || empty($charge_limits[$i]) || empty($effective_date[$i]) || empty($cutoff_date[$i]) || 
                                    empty($charge_status[$i])
                                ) {
                                    continue;
                                }
                            }
                        }
                        # Check the operation module matches create
                        if (strtolower($operation[$i]) == 'create') {
                            # Save the particular charges
                            $chargesData["particular_id"] = $id;
                            $this->chargeModel->insert($chargesData);
                        }

                        # Check the operation module matches update
                        if (strtolower($operation[$i]) == 'update') {
                            $chargeId = $charge_id[$i];
                            # Update the particular charges
                            $this->chargeModel->update($chargeId, $chargesData);
                        }
                    }
                }
                # Activity
                $this->saveUserActivity([
                    'user_id' => $this->userRow['id'],
                    'action' => $mode,
                    'description' => ('particular: ' . $data['particular_name']),
                    'module' => strtolower('particulars'),
                    'referrer_id' => $id,
                    'title' => $this->title,
                ]);
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
            $data = $this->particular->find($id);
            if ($data) {
                if (strtolower($data['particular_type']) == 'system') {
                    $response = [
                        'status' => 500,
                        'error' => 'Not Allowed',
                        'messages' => 'Deleting ' . $this->title . ' system record is not allowed'
                    ];
                    return $this->respond($response);
                    exit;
                } else {
                    $delete = $this->particular->delete($id);
                    if ($delete) {
                        # Activity
                        $this->saveUserActivity([
                            'user_id' => $this->userRow['id'],
                            'action' => 'delete',
                            'description' => ($this->title . ': ' . $data['particular_name']),
                            'module' => strtolower('particulars'),
                            'referrer_id' => $id,
                            'title' => $this->title,
                        ]);
                        # delete particular's corresponding charges
                        $particularCharges = $this->chargeModel->where(['particular_id' => $id])->findAll();
                        if ($particularCharges) {
                            foreach ($particularCharges as $charge) {
                                $deleteCharges = $this->chargeModel->delete($charge['id']);
                                if ($deleteCharges) {
                                    $this->saveUserActivity([
                                        'user_id' => $this->userRow['id'],
                                        'action' => 'delete',
                                        'description' => ('Deleted ' . $data['particular_name'] . 'charges'),
                                        'module' => strtolower('particulars'),
                                        'referrer_id' => $id,
                                        'title' => $this->title,
                                    ]);
                                } else {
                                    continue;
                                }
                            }
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
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'The requested ' . $this->title . ' resource could not be found!',
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
            $delete = '';
            foreach ($list_id as $id) {
                $data = $this->particular->find($id);
                if ($data) {
                    if (strtolower($data['particular_type']) == 'system') {
                        continue;
                    } else {
                        $delete = $this->particular->delete($id);
                        if ($delete) {
                            // insert into activity logs
                            $activityData = [
                                'user_id' => $this->userRow['id'],
                                'action' => 'bulk-delete',
                                'description' => ucfirst('bulk deleted ' . $this->title . ': ' . $data['particular_name']),
                                'module' => strtolower('particulars'),
                                'referrer_id' => $id,
                            ];
                            $activity = $this->insertActivityLog($activityData);
                            # delete particular's corresponding charges
                            $particularCharges = $this->chargeModel->where(['particular_id' => $id])->findAll();
                            if ($particularCharges) {
                                foreach ($particularCharges as $charge) {
                                    $deleteCharges = $this->chargeModel->delete($charge['id']);
                                    if ($deleteCharges) {
                                        $this->saveUserActivity([
                                            'user_id' => $this->userRow['id'],
                                            'action' => 'delete',
                                            'description' => ('Deleted ' . $data['particular_name'] . 'charges'),
                                            'module' => strtolower('particulars'),
                                            'referrer_id' => $id,
                                            'title' => $this->title,
                                        ]);
                                    } else {
                                        continue;
                                    }
                                }
                            }
                        } else {
                            continue;
                        }
                    }
                } else {
                    continue;
                }
            }
            if ($activity) {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' Deleted Successfully',
                ];
                return $this->respond($response);
                exit;
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' Deleted Successfully. loggingFailed'
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
    private function _validateParticular($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $particularInfo = $this->particular->find($this->request->getVar('id'));

        if ($this->request->getVar('particular_name') == '') {
            $data['inputerror'][] = 'particular_name';
            $data['error_string'][] = 'Particular Name is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('particular_name'))) {
            $name = $this->request->getVar('particular_name');
            if ($this->settings->validateName($name) == TRUE) {
                if (strlen($name) < 4) {
                    $data['inputerror'][] = 'particular_name';
                    $data['error_string'][] = 'Minimum 4 letters required [' . strlen($name) . ']!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($name) == FALSE) {
                $data['inputerror'][] = 'particular_name';
                $data['error_string'][] = 'Valid Particular Name is required!';
                $data['status'] = FALSE;
            }
            if ($method == 'add') {
                $dat = $this->particular
                    ->where(['particular_name' => $this->request->getVar('particular_name')])->first();
                if ($dat) {
                    $data['inputerror'][] = 'particular_name';
                    $data['error_string'][] = $this->request->getVar('particular_name') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
            if ($method == 'update' && strtolower($particularInfo['particular_name']) != strtolower($this->request->getVar('particular_name'))) {
                $info = $this->particular
                    ->where(['particular_name' => $this->request->getVar('particular_name')])->first();
                if ($info) {
                    $data['inputerror'][] = 'particular_name';
                    $data['error_string'][] = $this->request->getVar('particular_name') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        if (!empty($this->request->getVar('particular_code'))) {
            $code = $this->request->getVar('particular_code');
            if ($code < 0) {
                $data['inputerror'][] = 'particular_code';
                $data['error_string'][] = 'Valid Code should be a positive integer!';
                $data['status'] = FALSE;
            }
            if (strlen($code) < 4) {
                $data['inputerror'][] = 'particular_code';
                $data['error_string'][] = 'Minimum 4 letters required [' . strlen($code) . ']!';
                $data['status'] = FALSE;
            }
            if (preg_match('/^[-0-9- ]+$/', trim($code)) == FALSE) {
                $data['inputerror'][] = 'particular_code';
                $data['error_string'][] = 'Code can only be digits and -!';
                $data['status'] = FALSE;
            }
            if ($method == 'add') {
                $dat = $this->particular->where(['particular_code' => $this->request->getVar('particular_code')])->first();
                if ($dat) {
                    $data['inputerror'][] = 'particular_code';
                    $data['error_string'][] = $this->request->getVar('particular_code') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
            if ($method == 'update' && $particularInfo['particular_code'] != $this->request->getVar('particular_code')) {
                $info = $this->particular->where(['particular_code' => $this->request->getVar('particular_code')])->first();
                if ($info) {
                    $data['inputerror'][] = 'particular_code';
                    $data['error_string'][] = $this->request->getVar('particular_code') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        # status validation
        if ($this->request->getVar('particular_status') == '') {
            $data['inputerror'][] = 'particular_status';
            $data['error_string'][] = 'Particular status is required!';
            $data['status'] = FALSE;
        }
        # subcategory id validation
        if ($this->request->getVar('subcategory_id') == '') {
            $data['inputerror'][] = 'subcategory_id';
            $data['error_string'][] = 'SubCategory is required!';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('account_typeId') == '') {
            $data['inputerror'][] = 'account_typeId';
            $data['error_string'][] = 'Account Type is required!';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('cash_flow_typeId') == '') {
            $data['inputerror'][] = 'cash_flow_typeId';
            $data['error_string'][] = 'Cash Flow Type is required!';
            $data['status'] = FALSE;
        }

        if (strtolower($this->request->getVar('charged')) == "yes") {
            # if (!empty($this->request->getVar('charged'))) {
            $mode = $this->request->getVar('mode');
            $charge_frequency = $this->request->getVar('charge_frequency');
            $charge_method = $this->request->getVar('charge_method');
            $charge_mode = $this->request->getVar('charge_mode');
            $charge = $this->request->getVar('charge[]');
            $charge_limits = $this->request->getVar('charge_limits[]');
            $effective_date = $this->request->getVar('effective_date[]');
            $cutoff_date = $this->request->getVar('cutoff_date[]');
            $charge_status = $this->request->getVar('charge_status[]');

            # count the number of subcharges
            foreach ($charge as $index => $charges) {
                # Check the operation module
                if (strtolower($mode) == "update") {
                    if ($index == 0) {
                        if ($charge_frequency ||!empty($charge_method) || !empty($charge_mode) || !empty($charge[$index]) || !empty($charge_limits[$index]) || !empty($effective_date[$index]) || !empty($cutoff_date[$index]) || !empty($charge_status[$index])
                        ) {
                            break;
                        }
                    }
                }
                # validate particular charge type
                // if (empty($charge[$index])) {
                //     $data['inputerror'][] = 'charge_method[' . $index . ']';
                //     $data['error_string'][] = 'Charge Method is required';
                //     $data['status'] = FALSE;
                // }
                # validate particular charge fees
                if (empty($charge[$index])) {
                    $data['inputerror'][] = 'charge[' . $index . ']';
                    $data['error_string'][] = 'Charge Fees is required';
                    $data['status'] = FALSE;
                }

                if (!empty($charge[$index])) {
                    if (!preg_match('/^\d+(\.\d+)?$/', $this->removeCommasFromAmount($charge[$index]))) {
                        $data['inputerror'][] = 'charge[' . $index . ']';
                        $data['error_string'][] = 'Valid Charge Fees is required';
                        $data['status'] = FALSE;
                    }

                    $fee = $charge[$index];
                    if ($fee == 0) {
                        $data['inputerror'][] = 'charge[' . $index . ']';
                        $data['error_string'][] = 'Charge can not be 0!';
                        $data['status'] = FALSE;
                    }
                    if (!empty($charge_method[$index])) {
                        if (strtolower($charge_method[$index]) == 'percent' && $fee > 100) {
                            $data['inputerror'][] = 'charge[' . $index . ']';
                            $data['error_string'][] = 'Maximum percentage (100) exceeded!';
                            $data['status'] = FALSE;
                        }
                    }
                }

                if (!empty($charge_limits[$index])) {
                    if (!preg_match('/^\d+(\.\d+)?$/', $this->removeCommasFromAmount($charge_limits[$index]))) {
                        $data['inputerror'][] = 'charge_limits[' . $index . ']';
                        $data['error_string'][] = 'Valid Charge Limit is required';
                        $data['status'] = FALSE;
                    }

                    $limit = $charge_limits[$index];
                    if ($limit < 0) {
                        $data['inputerror'][] = 'charge_limits[' . $index . ']';
                        $data['error_string'][] = 'Charge can not be less than 0!';
                        $data['status'] = FALSE;
                    }
                }

                # validate particular charge mode
                // if (empty($charge_mode[$index])) {
                //     $data['inputerror'][] = 'charge_mode[' . $index . ']';
                //     $data['error_string'][] = 'Charge Mode is required';
                //     $data['status'] = FALSE;
                // }

                if (empty($effective_date[$index])) {
                    $data['inputerror'][] = 'effective_date[' . $index . ']';
                    $data['error_string'][] = 'Effective Date is required';
                    $data['status'] = FALSE;
                }

                // if (empty($charge_frequency[$index])) {
                //     $data['inputerror'][] = 'charge_frequency[' . $index . ']';
                //     $data['error_string'][] = 'Charge Frequency is required';
                //     $data['status'] = FALSE;
                // }

                if (empty($charge_status[$index])) {
                    $data['inputerror'][] = 'charge_status[' . $index . ']';
                    $data['error_string'][] = 'Charge Status is required';
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
