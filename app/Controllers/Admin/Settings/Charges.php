<?php

namespace App\Controllers\Admin\Settings;

use Hermawan\DataTables\DataTable;
use App\Controllers\MasterController;


class Charges extends MasterController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Charge';
        $this->title = 'Charges';
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
        if (($this->userPermissions == 'all')) {
            return view('admin/charges/index', [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'particularName' => '',
                'userMenu' => $this->load_menu(),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    public function charges($id = null, $module = null)
    {
        if ($module == 'product') {
            $where = ['charges.deleted_at' => null, 'charges.product_id' => $id];
        } else {
            $where = [
                'charges.deleted_at' => null, 'charges.particular_id' => $id, 'charges.product_id' => null
            ];
        }

        $charges = $this->chargeModel
            ->select('charges.charge_method, charges.charge, charges.charge_mode, 
                charges.frequency, charges.effective_date, charges.status, charges.id,
                particulars.particular_name')
            ->join('particulars', 'particular_id = particulars.id', 'left')
            ->where($where)->orderBy('charges.effective_date', 'desc');

        return DataTable::of($charges)
            ->add('checkbox', function ($charge) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $charge->id . '">
                    </div>
                ';
            })
            ->addNumbering("no")
            ->add('action', function ($charge) {
                if (strtolower($charge->status) == 'active') {
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
                    $actions .= '<li>
                    <a href="javascript:void(0)" onclick="viewCharge(' . "'" . $charge->id . "'" . ')" title="View Charge" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Charge</a></li>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                    $actions .= '
                    <div class="dropdown-divider"></div>
                    <li><a href="javascript:void(0)" onclick="updateCharge(' . "'" . $charge->id . "'" . ')" title="Edit Charge" class="dropdown-item update' . $this->title . '"><i class="fas fa-edit text-info"></i> Edit Charge</a></li>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                    $actions .= '
                    <div class="dropdown-divider"></div>
                    <li><a href="javascript:void(0)" onclick="deleteCharge(' . "'" . $charge->id . "'" . ',' . "'" . $charge->charge_method . "'" . ')" title="Delete Charge" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Charge</a></li>';
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
        $charge = $this->chargeModel->find($id);
        if ($charge) {
            return $this->respond(($charge));
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
        $mode = strtolower($this->request->getVar('mode'));
        $operation = $this->getOperationPastTense($mode);
        $this->validateCharge($mode);
        if (($this->userPermissions == 'all') || (in_array($mode . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
            # check whether the csv file is uploaded
            if (!empty($_FILES['file']['name']) && $mode == 'import') {
                # get uploaded file extension
                $path_parts = pathinfo($_FILES["file"]["name"]);
                $ext = $path_parts['extension'];
                # check whether the uploaded file extension matches with csv
                if ($ext == 'csv') {
                    $file = $this->request->getFile("file");
                    $file_name = $file->getTempName();
                    $charges = array_map('str_getcsv', file($file_name));
                    if (count($charges) > 0) {
                        foreach ($charges as $key => $column) {
                            # ignore the column headers
                            if ($key == 0) {
                                continue;
                            }

                            # ignore empty row in excel sheets
                            if ((string) $column[0] != '0' && empty($column[0])) {
                                continue;
                            }
                            $data[] = [
                                "particular_id" => $this->request->getVar('particular_id'),
                                "charge_method" => $column[0],
                                "charge" => $column[1],
                                "charge_mode" => $column[2],
                                "frequency" => $column[3],
                                "effective_date" => date('Y-m-d', strtotime($column[4])),
                                "status" => $column[5],
                            ];
                        }
                        # import charges information
                        $save = $this->chargeModel->insertBatch($data);
                        $referrer_id = '';
                    }
                } else {
                    # mismatch of the uploaded file type
                    $data['inputerror'][] = 'file';
                    $data['error_string'][] = 'Upload Error: The filetype you are attempting to upload is not allowed.';
                    $data['status'] = FALSE;
                    echo json_encode($data);
                    exit();
                }
            }
            # Add new charge
            if ($mode == 'create') {
                # code..
                $data = [
                    'particular_id' => trim($this->request->getVar('particular_id')),
                    'frequency' => trim($this->request->getVar('charge_frequency')),
                    'charge_method' => trim($this->request->getVar('charge_method')),
                    'charge_mode' => trim($this->request->getVar('charge_mode')),
                    'charge' => $this->removeCommasFromAmount($this->request->getVar('charge')),
                    'charge_limits' => $this->removeCommasFromAmount($this->request->getVar('charge_limits')),
                    'effective_date' => date('Y-m-d', strtotime(trim($this->request->getVar('effective_date')))),
                    'cutoff_date' => (!empty($this->request->getVar('cutoff_date')) ? date('Y-m-d', strtotime(trim($this->request->getVar('cutoff_date')))) : null),
                    'status' => trim($this->request->getVar('charge_status')),
                    'account_id' => $this->userRow['account_id'],
                ];

                $save = $this->chargeModel->insert($data);
                $referrer_id = $save;
            }
            # Update the charge information
            if ($mode == 'update') {
                $charge_id = $this->request->getVar('id');
                $data = [
                    'frequency' => trim($this->request->getVar('charge_frequency')),
                    'charge_method' => trim($this->request->getVar('charge_method')),
                    'charge_mode' => trim($this->request->getVar('charge_mode')),
                    'charge' => $this->removeCommasFromAmount($this->request->getVar('charge')),
                    'charge_limits' => $this->removeCommasFromAmount($this->request->getVar('charge_limits')),
                    'effective_date' => date('Y-m-d', strtotime(trim($this->request->getVar('effective_date')))),
                    'cutoff_date' => (!empty($this->request->getVar('cutoff_date')) ? date('Y-m-d', strtotime(trim($this->request->getVar('cutoff_date')))) : null),
                    'status' => trim($this->request->getVar('charge_status')),
                ];
                $referrer_id = $charge_id;
                $save = $this->chargeModel->update($charge_id, $data);
            }
            $this->saveUserActivity([
                'user_id' => $this->userRow['id'],
                'action' => $mode,
                'description' => ucfirst($this->title),
                'module' => $this->menu,
                'referrer_id' => $referrer_id,
                'title' => $this->title,
            ]);
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to ' . ucwords($mode) . ' ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $particular = $this->particular->find($id);

        if (($this->userPermissions == 'all')) {

            if ($particular) {
                return view('admin/charges/index', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'particular_id' => $id,
                    'particular' => $particular,
                    'particularName' => $particular['particular_name'],
                    'module' => 'particular',
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
        $mode = 'delete';
        $operation = $this->getOperationPastTense($mode);
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $charge = $this->chargeModel->find($id);
            if ($charge) {
                $delete = $this->chargeModel->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => $mode,
                        'description' => ucfirst($operation . $this->title),
                        'module' => strtolower($this->menu),
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record ' . $operation . ' successfully',
                        ];
                        return $this->respond($response);
                        exit;
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record ' . $operation . ' successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => ucwords($mode) . ' Failed',
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
     * Delete the Charge Information
     *
     */
    public function bulkDelete()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->chargeModel->find($id);
                if ($data) {
                    $delete = $this->chargeModel->delete($id);
                } else {
                    $delete = false;
                }
            }

            $this->saveUserActivityLogs([
                'save' => $delete,
                'referrer_id' => $id,
                'mode' => 'bulk-delete',
                'menu' => $this->menu,
                'title' => $this->title,
            ]);
            exit;
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

    private function validateCharge($operation)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($operation == 'import') {
            # Import validation
            if (empty($_FILES['file']['name'])) {
                # Please browse for the file to be uploaded
                $data['inputerror'][] = 'file';
                $data['error_string'][] = 'Upload Error: User CSV File is required!';
                $data['status'] = FALSE;
            }
        } else {

            if (empty($this->request->getVar('charge_frequency'))) {
                $data['inputerror'][] = 'charge_frequency';
                $data['error_string'][] = 'Charge Frequency is required!';
                $data['status'] = FALSE;
            }

            if (empty($this->request->getVar('charge_method'))) {
                $data['inputerror'][] = 'charge_method';
                $data['error_string'][] = 'Charge Method is required!';
                $data['status'] = FALSE;
            }

            if (empty($this->request->getVar('charge_mode'))) {
                $data['inputerror'][] = 'charge_mode';
                $data['error_string'][] = 'Charge Mode is required!';
                $data['status'] = FALSE;
            }

            if (empty($this->request->getVar('charge'))) {
                $data['inputerror'][] = 'charge';
                $data['error_string'][] = 'Charge is required!';
                $data['status'] = FALSE;
            }

            if (!empty($this->request->getVar('charge'))) {
                $charge = $this->removeCommasFromAmount($this->request->getVar('charge'));
                $chargeMethod = $this->request->getVar('charge_method');
                if ($this->request->getVar('charge') == 0) {
                    $data['inputerror'][] = 'charge';
                    $data['error_string'][] = 'Charge can not be 0!';
                    $data['status'] = FALSE;
                }
                if (!preg_match("/^[0-9.']*$/", $charge)) {
                    $data['inputerror'][] = 'charge';
                    $data['error_string'][] = 'Only Digits and dot (.) are allowed!';
                    $data['status'] = FALSE;
                }
                if (strtolower($chargeMethod) == 'percent' && $charge > 100) {
                    $data['inputerror'][] = 'charge';
                    $data['error_string'][] = 'Maximum percentage exceeded 100%!';
                    $data['status'] = FALSE;
                }
            }

            if (!empty($this->request->getVar('charge_limits'))) {
                $limit = $this->removeCommasFromAmount($this->request->getVar('charge_limits'));
                if ($limit < 0) {
                    $data['inputerror'][] = 'charge_limits';
                    $data['error_string'][] = 'Charge can not be less than 0!';
                    $data['status'] = FALSE;
                }
                if (!preg_match("/^[0-9.']*$/", $limit)) {
                    $data['inputerror'][] = 'charge_limits';
                    $data['error_string'][] = 'Only Digits and dot (.) are allowed!';
                    $data['status'] = FALSE;
                }
            }

            if (empty($this->request->getVar('effective_date'))) {
                $data['inputerror'][] = 'effective_date';
                $data['error_string'][] = 'Effective Date is required!';
                $data['status'] = FALSE;
            }

            if (empty($this->request->getVar('charge_status'))) {
                $data['inputerror'][] = 'charge_status';
                $data['error_string'][] = 'Charge Status is required!';
                $data['status'] = FALSE;
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
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
}
