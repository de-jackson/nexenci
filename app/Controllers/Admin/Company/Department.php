<?php

namespace App\Controllers\Admin\Company;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Department extends MasterController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Company';
        $this->title = 'Departments';
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
            return view('admin/company/departments/index', [
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
    public function department_view($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $department = $this->department->find($id);
            if ($department) {
                return view('admin/company/departments/index', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'department' => $department,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Department could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    function department_forms($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            if ($id == 0) {
                $title = "Add Department Form";
            } else {
                $title = "Department View Form";
            }
            return view('admin/company/departments/department_formPDF', [
                'title' => $title,
                'id' => $id,
                'department' => $this->department->find($id),
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    public function departments_list($id = null)
    {
        if ($id == 0) {
            $where = ['deleted_at' => Null];
        } else {
            $where = ['deleted_at' => Null, 'id' => $id];
        }
        $departments = $this->department
            ->select('department_name, department_slug, department_status, created_at, updated_at, id')->where($where);

        return DataTable::of($departments)
            ->add('checkbox', function ($department) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $department->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('action', function ($department) {
                if (strtolower($department->department_status) == 'active') {
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
                    $actions .= '<a href="javascript:void(0)" onclick="view_department(' . "'" . $department->id . "'" . ')" title="view '. ucwords($department->department_name).'" class="dropdown-item"><i class="fas fa-eye text-success"></i> View '. ucwords($department->department_name).'</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="edit_department(' . "'" . $department->id . "'" . ')" title="edit '. ucwords($department->department_name).'" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit '. ucwords($department->department_name).'</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="delete_department(' . "'" . $department->id . "'" . ',' . "'" . $department->department_name . "'" . ')" title="delete '. ucwords($department->department_name).'" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete '. ucwords($department->department_name).'</a>';
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
        $data = $this->department->find($id);
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

    public function getDepartments()
    {
        $data = $this->department->where(['department_status' => 'Active'])->findAll();;
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
                $this->_validateDepartment('add');
                $data = [
                    'department_name' => trim($this->request->getVar('department_name')),
                    'department_slug' => trim(url_title(strtolower($this->request->getVar('department_name')), '-', true)),
                    'department_status' => trim($this->request->getVar('department_status')),
                    'account_id' => $this->userRow['account_id'],
                ];
                $insert = $this->department->insert($data);
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
                            $data = [];
                            foreach ($file_data as $key => $column) {
                                # ignore the column headers
                                if ($key == 0) {
                                    continue;
                                }

                                # ignore empty row in excel sheets
                                if ((string) $column[0] != '0' && empty($column[0])) {
                                    continue;
                                }

                                # check the department existance
                                $department = $this->department->where([
                                    'department_name' => trim($column[0])
                                ])->countAllResults();

                                # ignore department with the same names
                                if ($department) {
                                    continue;
                                }


                                $data[] = array(
                                    'department_name' => trim($column[0]),
                                    'department_slug' => trim(url_title(strtolower($column[0]), '-', true)),
                                    'department_status' => 'Active',
                                    'account_id' => $this->userRow['account_id'],
                                );
                            }
                            # insert imported data
                            $insert = $this->department->insertBatch($data);
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
                    'module' => strtolower('Deparments'),
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
                        'messages' => $this->title . ' record ' . $mode . ' successfully. loggingFailed'
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
    public function update_department($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateDepartment("update");
                $data = [
                    'department_name' => trim($this->request->getVar('department_name')),
                    'department_slug' => trim(url_title(strtolower($this->request->getVar('department_name')), '-', true)),
                    'department_status' => trim($this->request->getVar('department_status')),
                ];
                $update = $this->department->update($id, $data);
                if ($update) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'update',
                        'description' => ucfirst('updated ' . $this->title . ', ' . $data['department_name']),
                        'module' => strtolower('Deparments'),
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
            $data = $this->department->find($id);
            if ($data) {
                $delete = $this->department->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', ' . $data['department_name']),
                        'module' => strtolower('Deparments'),
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
                $data = $this->department->find($id);
                if ($data) {
                    $delete = $this->department->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulky deleted ' . $this->title . ', ' . $data['department_name']),
                            'module' => strtolower('Deparments'),
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

    private function _validateDepartment($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $departmentInfo = $this->department->find($this->request->getVar('id'));

        if ($this->request->getVar('department_name') == '') {
            $data['inputerror'][] = 'department_name';
            $data['error_string'][] = 'Department Name is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('department_name'))) {
            $department_name = $this->request->getVar('department_name');
            if ($this->settings->validateName($department_name) == TRUE) {
                if (strlen(trim($department_name)) < 5) {
                    $data['inputerror'][] = 'department_name';
                    $data['error_string'][] = 'Minimum 5 letters required [' . strlen($department_name) . ']';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($this->request->getVar('department_name')) == FALSE) {
                $data['inputerror'][] = 'department_name';
                $data['error_string'][] = 'Valid Department Name is required';
                $data['status'] = FALSE;
            }
            if ($method == "add") {
                $departmentRow = $this->department
                    ->where(['department_name' => $this->request->getVar('department_name')])->first();
                if ($departmentRow) {
                    $data['inputerror'][] = 'department_name';
                    $data['error_string'][] = $this->request->getVar('department_name') . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                strtolower($departmentInfo['department_name']) != strtolower($this->request->getVar('department_name'))
            ) {
                # check branch name existance
                $departmentRow = $this->department
                    ->where(['department_name' => $this->request->getVar('department_name')])->first();
                if ($departmentRow) {
                    $data['inputerror'][] = 'department_name';
                    $data['error_string'][] = $this->request->getVar('department_name') . ' already added';
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
