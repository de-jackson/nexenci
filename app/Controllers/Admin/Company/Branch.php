<?php

namespace App\Controllers\Admin\Company;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Branch extends MasterController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Company';
        $this->title = 'Branches';
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
            return view('admin/company/branches/index', [
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
    public function branch_view($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $branch = $this->branch->find($id);
            if ($branch) {
                return view('admin/company/branches/index', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'branch' => $branch,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Branch could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    function branch_formPDF($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            if ($id == 0) {
                $title = "New Branch Form";
            } else {
                $title = "Branch View Form";
            }
            return view('admin/company/branches/branch_formPDF', [
                'title' => $title,
                'id' => $id,
                'branch' => $this->branch->find($id),
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
     * return all braches as rows
     */
    public function branches_list($id = null)
    {
        if ($id == 0) {
            $where = ['deleted_at' => Null];
        } else {
            $where = ['deleted_at' => Null, 'id' => $id];
        }
        $branches = $this->branch
            ->select('branch_name, branch_mobile, branch_email, branch_address, branch_code, branch_status, id')->where($where);
        return DataTable::of($branches)
            ->add('checkbox', function ($branch) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $branch->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('action', function ($branch) {
                if (strtolower($branch->branch_status) == 'active') {
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
                    $actions .= '<li><a href="javascript:void(0)" onclick="view_branch(' . "'" . $branch->id . "'" . ')" title="view '. ucwords($branch->branch_name).'" class="dropdown-item"><i class="fas fa-eye text-success"></i> View '. ucwords($branch->branch_name).'</a></li>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <li><a href="javascript:void(0)" onclick="edit_branch(' . "'" . $branch->id . "'" . ')" title="edit '. ucwords($branch->branch_name).'" class="dropdown-item update' . $this->title . '"><i class="fas fa-edit text-info"></i> Edit '. ucwords($branch->branch_name).'</a></li>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                <li><a href="javascript:void(0)" onclick="delete_branch(' . "'" . $branch->id . "'" . ',' . "'" . ucwords($branch->branch_name) . "'" . ')" title="delete '. ucwords($branch->branch_name).'" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete '. ucwords($branch->branch_name).'</a></li>';
                }
                $actions .= ' 
                        </div>
                </div>';
                return $actions;
            })
            ->toJson(true);
    }
    public function branches_report($from = null, $to = null)
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
        $branches = $this->branch->select('branch_name, branch_mobile, branch_email, branch_address, branch_code, branch_status, id')->where($where);
        return DataTable::of($branches)
            ->add('checkbox', function ($branch) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $branch->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('action', function ($branch) {
                if (strtolower($branch->branch_status) == 'active') {
                    $text = "info";
                } else {
                    $text = "danger";
                }
                return '
                    <div class="text-center">
                        <a href="javascript:void(0)" onclick="view_branch(' . "'" . $branch->id . "'" . ')" title="view branch" class="' . $text . '"><i class="fas fa-eye"></i></a>
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
        $data = $this->branch->find($id);
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
    public function getBranches()
    {
        $data = $this->branch->where(['branch_status' => 'Active'])->findAll();;
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
            $name = $this->request->getVar('branch_name');
            if (strtolower($mode) == 'create') {
                $this->_validateBranch('add');
                $data = [
                    'branch_name' => trim($name),
                    'slug' => trim(url_title(strtolower($name), '-', true)),
                    'branch_mobile' => trim(preg_replace('/^0/', '+256', $this->request->getVar('branch_mobile_full'))),
                    'alternate_mobile' => trim(preg_replace('/^0/', '+256', $this->request->getVar('mobile_full'))),
                    'branch_address' => trim($this->request->getVar('branch_address')),
                    'branch_email' => trim($this->request->getVar('branch_email')),
                    'branch_status' => trim($this->request->getVar('branch_status')),
                    'branch_code' => "B" . $this->settings->generateReference(4),
                    'account_id' => $this->userRow['account_id'],
                    // 'account_id' => "B" . $this->settings->generateReference(4),
                ];
                $insert = $this->branch->insert($data);
                $index = 1;
            } else {
                if (!empty($_FILES['file']['name'])) {
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
                            # return print_r($file_data);
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

                                # check the branch existence
                                $branch = $this->branch->where([
                                    'branch_name' => trim($column[0])
                                ])->countAllResults();

                                # ignore branch with the same names
                                if ($branch) {
                                    continue;
                                }

                                $data[] = [
                                    'branch_name' => trim($column[0]),
                                    'slug' => trim(url_title(strtolower($column[0]), '-', true)),
                                    'branch_email' => trim($column[1]),
                                    'branch_mobile' => trim(preg_replace('/^0/', '+256', $column[2])),
                                    'alternate_mobile' => trim(preg_replace('/^0/', '+256', $column[3])),
                                    'branch_address' => trim($column[4]),
                                    'branch_status' => "Active",
                                    'branch_code' => "B" . $this->settings->generateReference(4),
                                    'account_id' => $this->userRow['account_id'],
                                ];
                            }
                            # insert imported data
                            $insert = $this->branch->insertBatch($data);
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
                    'module' => strtolower('Branches'),
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
    public function update_branch($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateBranch("update");
                $name = $this->request->getVar('branch_name');
                $data = [
                    'branch_name' => trim($name),
                    'slug' => trim(url_title(strtolower($name), '-', true)),
                    'branch_mobile' => trim(preg_replace('/^0/', '+256', $this->request->getVar('branch_mobile_full'))),
                    'alternate_mobile' => trim(preg_replace('/^0/', '+256', $this->request->getVar('mobile_full'))),
                    'branch_address' => trim($this->request->getVar('branch_address')),
                    'branch_email' => trim($this->request->getVar('branch_email')),
                ];
                $update = $this->branch->update($id, $data);
                if ($update) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'update',
                        'description' => ucfirst('updated ' . $this->title . ', ' . $name),
                        'module' => strtolower('Branches'),
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
            $data = $this->branch->find($id);
            if ($data) {
                $delete = $this->branch->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', ' . $data['branch_name']),
                        'module' => strtolower('Branches'),
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
                $data = $this->branch->find($id);
                if ($data) {
                    $delete = $this->branch->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['branch_name']),
                            'module' => strtolower('Branches'),
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
    private function _validateBranch($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        # trimmed the white space between between country code and phone number
        $branch_mobile = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('branch_mobile_country_code'),
            'phone' => $this->request->getVar('branch_mobile')
        ]);

        # trimmed the white space between between country code and phone number
        $mobile = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('mobile_country_code'),
            'phone' => $this->request->getVar('mobile')
        ]);

        $branchInfo = $this->branch->find($this->request->getVar('id'));

        if ($this->request->getVar('branch_name') == '') {
            $data['inputerror'][] = 'branch_name';
            $data['error_string'][] = 'Branch Name is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('branch_name'))) {
            $branch_name = $this->request->getVar('branch_name');
            if ($this->settings->validateName($branch_name) == TRUE) {
                if (strlen(trim($branch_name)) < 5) {
                    $data['inputerror'][] = 'branch_name';
                    $data['error_string'][] = 'Minimum 5 letters required [' . strlen($branch_name) . ']';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($this->request->getVar('branch_name')) == FALSE) {
                $data['inputerror'][] = 'branch_name';
                $data['error_string'][] = 'Valid Full Name is required';
                $data['status'] = FALSE;
            }
            if ($method == "add") {
                # check branch name existence
                $branchRow = $this->branch
                    ->where(['branch_name' => $this->request->getVar('branch_name')])->first();
                if ($branchRow) {
                    $data['inputerror'][] = 'branch_name';
                    $data['error_string'][] = $this->request->getVar('branch_name') . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                strtolower($branchInfo['branch_name']) != strtolower($this->request->getVar('branch_name'))
            ) {
                # check branch name existence
                $branchRow = $this->branch
                    ->where(['branch_name' => $this->request->getVar('branch_name')])->first();
                if ($branchRow) {
                    $data['inputerror'][] = 'branch_name';
                    $data['error_string'][] = $this->request->getVar('branch_name') . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('branch_email') == '') {
            $data['inputerror'][] = 'branch_email';
            $data['error_string'][] = 'Branch email is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('branch_email'))) {
            # check whether the branch_email is valid
            if ($this->settings->validateEmail($this->request->getVar('branch_email')) == FALSE) {
                $data['inputerror'][] = 'branch_email';
                $data['error_string'][] = 'Valid Email Address is required';
                $data['status'] = FALSE;
            }
            if ($method == "add") {
                # check branch email address existence
                $branchRow = $this->branch
                    ->where(['branch_email' => $this->request->getVar('branch_email')])->first();
                if ($branchRow) {
                    $data['inputerror'][] = 'branch_email';
                    $data['error_string'][] = $this->request->getVar('branch_email') . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                $branchInfo['branch_email'] != $this->request->getVar('branch_email')
            ) {
                # check branch email address existence
                $branchRow = $this->branch
                    ->where(['branch_email' => $this->request->getVar('branch_email')])->first();
                if ($branchRow) {
                    $data['inputerror'][] = 'branch_email';
                    $data['error_string'][] = $this->request->getVar('branch_email') . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('branch_mobile') == '') {
            $data['inputerror'][] = 'branch_mobile';
            $data['error_string'][] = 'Branch Mobile is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('branch_mobile'))) {

            $this->validPhoneNumber([
                'phone' => $branch_mobile,
                'input' => 'branch_mobile',
            ]);
            # check the phone number existence
            if ($method == "add") {
                # check branch phone number existence
                $branchRow = $this->branch->where(['branch_mobile' => $branch_mobile])->first();
                if ($branchRow) {
                    $data['inputerror'][] = 'branch_mobile';
                    $data['error_string'][] = $branch_mobile . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                $branchInfo['branch_mobile'] != $branch_mobile
            ) {
                # check branch phone number existence
                $branchRow = $this->branch->where(['branch_mobile' => $branch_mobile])->first();
                if ($branchRow) {
                    $data['inputerror'][] = 'branch_mobile';
                    $data['error_string'][] = $branch_mobile . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if (!empty($this->request->getVar('mobile'))) {
            $this->validPhoneNumber([
                'phone' => $mobile,
                'input' => 'mobile',
            ]);
            # check the method to check the phone number existence
            if ($method == "add") {
                # check branch phone number existence
                $branchRow = $this->branch->where(['alternate_mobile' => $mobile])->first();
                if ($branchRow) {
                    $data['inputerror'][] = 'mobile';
                    $data['error_string'][] = $mobile . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                $branchInfo['alternate_mobile'] != $mobile
            ) {
                # check branch phone number existence
                $branchRow = $this->branch
                    ->where(['alternate_mobile' => $mobile])->first();
                if ($branchRow) {
                    $data['inputerror'][] = 'mobile';
                    $data['error_string'][] = $mobile . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('branch_address') == '') {
            $data['inputerror'][] = 'branch_address';
            $data['error_string'][] = 'Branch address is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('branch_address'))) {
            if ($this->settings->validateAddress($this->request->getVar('branch_address')) == TRUE) {
                if (strlen(trim($this->request->getVar('branch_address'))) < 4) {
                    $data['inputerror'][] = 'branch_address';
                    $data['error_string'][] = 'Address is too short';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateAddress($this->request->getVar('branch_address')) == FALSE) {
                $data['inputerror'][] = 'branch_address';
                $data['error_string'][] = 'Valid Address is required';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('branch_status') == '') {
            $data['inputerror'][] = 'branch_status';
            $data['error_string'][] = 'Branch Status is required';
            $data['status'] = FALSE;
        }
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
