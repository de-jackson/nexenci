<?php

namespace App\Controllers\Admin\Company;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Position extends MasterController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Company';
        $this->title = 'Positions';
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
            return view('admin/company/positions/index', [
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
    public function position_view($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $position = $this->position->find($id);
            if ($position) {
                return view('admin/company/positions/index', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'position' => $position,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Position could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    function position_forms($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            if ($id == 0) {
                $title = "Add Position Form";
            } else {
                $title = "Position View Form";
            }
            return view('admin/company/positions/position_formPDF', [
                'title' => $title,
                'id' => $id,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'position' => $this->position->select('positions.*, departments.department_name')->join('departments', 'positions.department_id = departments.id', 'left')->find($id),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    public function positions_list($id = null)
    {
        if ($id == 0) {
            $where = ['positions.deleted_at' => Null];
        } else {
            $where = ['positions.deleted_at' => Null, 'positions.id' => $id];
        }
        $positions = $this->position
            ->select('positions.position, positions.position_slug, positions.position_status, departments.department_name, positions.created_at, positions.updated_at, positions.id')->join('departments', 'departments.id = positions.department_id', 'left')->where($where);

        return DataTable::of($positions)
            ->add('checkbox', function ($position) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $position->id . '">
                    </div>
                ';
            })
            ->addNumbering("no")
            ->add('action', function ($position) {
                if (strtolower($position->position_status) == 'active') {
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
                    $actions .= '<a href="javascript:void(0)" onclick="view_position(' . "'" . $position->id . "'" . ')" title="view '. ucwords($position->position).'" class="dropdown-item"><i class="fas fa-eye text-success"></i> View '. ucwords($position->position).'</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="edit_position(' . "'" . $position->id . "'" . ')" title="edit '. ucwords($position->position).'" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit '. ucwords($position->position).'</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="delete_position(' . "'" . $position->id . "'" . ',' . "'" . ucwords($position->position) . "'" . ')" title="delete '. ucwords($position->position).'" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete '. ucwords($position->position).'</a>';
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
        $position = $this->position->find($id);
        if ($position) {
            $data = $this->position->select('positions.*, departments.department_name')->join('departments', 'departments.id = positions.department_id', 'left')->find($id);
            if ($position['permissions']) {
                $data['allowed'] = unserialize($position['permissions']);
            } else {
                $data['allowed'] = null;
            }
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

    public function departmentPositions($department_id)
    {
        # check the account type
        if ($this->userRow['account_id'] == 1) {
            $condition = ['department_id' => $department_id];
        } else {
            $condition = ['department_id' => $department_id, 'position_status' => 'Active'];
        }

        $data = $this->position->where($condition)->findAll();;
        return $this->respond($data);
    }

    public function getPositions()
    {
        $data = $this->position->where(['position_status' => 'Active'])->findAll();;
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
                $this->_validatePosition('add');
                $data = [
                    'department_id' => trim($this->request->getVar('department_id')),
                    'position' => trim($this->request->getVar('position')),
                    'position_slug' => trim(url_title(strtolower($this->request->getVar('position')), '-', true)),
                    'position_status' => trim($this->request->getVar('position_status')),
                    'permissions' => serialize($this->request->getVar('permissions')),
                    'account_id' => $this->userRow['account_id'],
                ];
                $insert = $this->position->insert($data);
                $index = 1;
            } else {
                if (!empty($_FILES['file']['name']) && !empty($this->request->getVar('departmentID'))) {
                    $file = $this->request->getFile("file");
                    $file_name = $file->getTempName();
                    $file_data = array_map('str_getcsv', file($file_name));
                    if (count($file_data) > 0) {
                        $index = 0;
                        $data = [];
                        foreach ($file_data as $input) {
                            if ($index > 0) {
                                $data[] = array(
                                    'department_id' => trim($this->request->getVar('departmentID')),
                                    'position' => trim($input[0]),
                                    'position_slug' => trim(url_title(strtolower($input[0]), '-', true)),
                                    'position_status' => trim($input[1]),
                                    'permissions' => serialize($this->request->getVar('permissions')),
                                    'account_id' => $this->userRow['account_id'],
                                );
                            }
                            $index++;
                        }
                        # insert imported data
                        $insert = $this->position->insertBatch($data);
                    }
                } else {
                    # validation
                    $data = array();
                    $data['error_string'] = array();
                    $data['inputerror'] = array();
                    $data['status'] = TRUE;

                    if ($this->request->getVar('departmentID') == '') {
                        $data['inputerror'][] = 'departmentID';
                        $data['error_string'][] = 'Department is required!';
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
                // insert into activity logs
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => $mode,
                    'description' => ucfirst($mode . ' ' . $index . ' ' . $this->title . ' record(s)'),
                    'module' => strtolower('Positions'),
                    'referrer_id' => $insert,
                ];
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => $this->title . ' record ' . $mode . ' successfully',
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
    public function update_position($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validatePosition("update");
                $permissions = serialize($this->request->getVar('permissions'));
                $data = [
                    'position' => trim($this->request->getVar('position')),
                    'department_id' => trim($this->request->getVar('department_id')),
                    'position_slug' => trim(url_title(strtolower($this->request->getVar('position')), '-', true)),
                    'position_status' => trim($this->request->getVar('position_status')),
                    'permissions' => $permissions,
                ];
                $update = $this->position->update($id, $data);
                if ($update) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'update',
                        'description' => ucfirst('updated ' . $this->title . ', ' . $data['position']),
                        'module' => strtolower('Positions'),
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
            $data = $this->position->find($id);
            if ($data) {
                $delete = $this->position->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', ' . $data['position']),
                        'module' => strtolower('Positions'),
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
            foreach ($list_id as $id) {
                $data = $this->position->find($id);
                if ($data) {
                    $delete = $this->position->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['position']),
                            'module' => strtolower('Positions'),
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

    private function _validatePosition($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $positionInfo = $this->position->find($this->request->getVar('id'));

        if ($this->request->getVar('department_id') == '') {
            $data['inputerror'][] = 'department_id';
            $data['error_string'][] = 'Department is required';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('position') == '') {
            $data['inputerror'][] = 'position';
            $data['error_string'][] = 'Position is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('position'))) {
            $position = $this->request->getVar('position');
            if ($this->settings->validateName($position) == TRUE) {
                if (strlen(trim($position)) < 4) {
                    $data['inputerror'][] = 'position';
                    $data['error_string'][] = 'Minimum 4 letters required [' . strlen($position) . ']';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($this->request->getVar('position')) == FALSE) {
                $data['inputerror'][] = 'position';
                $data['error_string'][] = 'Valid Position Name is required';
                $data['status'] = FALSE;
            }
            if ($method == "add") {
                $positionRow = $this->position
                    ->where(['position' => $this->request->getVar('position')])->first();
                if ($positionRow) {
                    if ($positionRow['department_id'] == $this->request->getVar('department_id')) {
                        $data['inputerror'][] = 'position';
                        $data['error_string'][] = $this->request->getVar('position') . ' already added';
                        $data['status'] = FALSE;
                    }
                }
            }
            if (
                $method == "update" &&
                strtolower($positionInfo['position']) != strtolower($this->request->getVar('position'))
            ) {
                $positionRow = $this->position
                    ->where(['position' => $this->request->getVar('position')])->first();
                if ($positionRow) {
                    if (strtolower($positionRow['department_id']) == strtolower($this->request->getVar('department_id'))) {
                        $data['inputerror'][] = 'position';
                        $data['error_string'][] = $this->request->getVar('position') . ' already added';
                        $data['status'] = FALSE;
                    }
                }
            }
        }
        // if ($this->request->getVar('position_status') == '') {
        //     $data['inputerror'][] = 'position_status';
        //     $data['error_string'][] = 'Position Status is required';
        //     $data['status'] = FALSE;
        // }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
