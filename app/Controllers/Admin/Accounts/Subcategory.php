<?php

namespace App\Controllers\Admin\Accounts;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Subcategory extends MasterController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Accounting';
        $this->title = 'Subcategories';
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
            return view('admin/accounts/subcategories/index', [
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
    public function subcategory_view($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $subcategory = $this->subcategory->find($id);
            if ($subcategory) {
                return view('admin/accounts/subcategories/subcategory_view', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'subcategory' => $subcategory,
                    'settings' => $this->settingsRow,
                    'module' => $this->settings->generateParticularModules('add'),
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Subcategory could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    function subcategory_forms($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            if ($id == 0) {
                $title = "Add Subcategory Form";
            } else {
                $title = "Subcategory View Form";
            }
            return view('admin/accounts/subcategories/subcategory_formPDF', [
                'title' => $title,
                'id' => $id,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'subcategory' => $this->subcategory->select('subcategories.*, categories.category_name, categories.part, categories.statement, categories.category_slug, categories.category_status, categories.created_at as created, categories.updated_at as updated')->join('categories', 'categories.id = subcategories.category_id', 'left')->find($id),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    /**
     * return all subcategories as rows
     */
    public function subcategories_list($category_id = null, $subcategory_id = null)
    {
        if ($subcategory_id == 0) {
            $where = ['subcategories.deleted_at' => Null, 'category_id' => $category_id];
        } else {
            $where = ['subcategories.deleted_at' => Null, 'id' => $subcategory_id];
        }
        $subcategories = $this->subcategory
            ->select('subcategories.subcategory_name, subcategories.subcategory_code, subcategories.subcategory_slug, subcategories.subcategory_type, subcategories.subcategory_status, subcategories.category_id, categories.category_name, categories.part, statements.name as statement, subcategories.id')
            ->join('categories', 'categories.id = subcategories.category_id', 'left')
            ->join('statements', 'statements.id = categories.statement_id', 'left')
            ->where($where);

        return DataTable::of($subcategories)
            ->add('checkbox', function ($subcategory) {
                return '<div class=""><input type="checkbox" class="data-check' . $subcategory->category_id . '" value="' . $subcategory->id . '"></div>';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('action', function ($subcategory) {
                if (strtolower($subcategory->subcategory_status) == 'active') {
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
                    $actions .= '<a href="javascript:void(0)" onclick="view_subcategory(' . "'" . $subcategory->id . "'" . ')" title="view '. ucwords($subcategory->subcategory_name).'" class="dropdown-item"><i class="fas fa-eye text-success"></i> View '. ucwords($subcategory->subcategory_name).'</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="edit_subcategory(' . "'" . $subcategory->id . "'" . ')" title="edit '. ucwords($subcategory->subcategory_name).'" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit '. ucwords($subcategory->subcategory_name).'</a>';
                }
                if ((strtolower($subcategory->subcategory_type) != 'system') && $this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '
                                    <div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="delete_subcategory(' . "'" . $subcategory->id . "'" . ',' . "'" . $subcategory->subcategory_name . "'" . ')" title="delete '. ucwords($subcategory->subcategory_name).'" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete '. ucwords($subcategory->subcategory_name).'</a>';
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
        $data = $this->subcategory
            ->select('subcategories.*, categories.category_name, categories.part, statements.name as statement, categories.category_slug, categories.category_status, categories.created_at as created, categories.updated_at as updated')
            ->join('categories', 'categories.id = subcategories.category_id', 'left')
            ->join('statements', 'statements.id = categories.statement_id', 'left')
            ->find($id);
        if ($data) {
            return $this->respond($data);
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

    /**
     * get all subcategories
     */
    public function catSubcategories($category_id)
    {
        $data = $this->subcategory->where(['category_id' => $category_id, 'subcategory_status' => 'Active'])->findAll();;
        return $this->respond($data);
    }

    public function revenueSubcategories()
    {
        $data = $this->subcategory
            ->select('subcategories.*, categories.category_name, categories.category_slug')
            ->join('categories', 'categories.id = subcategories.category_id', 'left')
            ->where(['category_slug' => 'revenue', 'subcategory_status' => 'Active'])->findAll();;
        return $this->respond($data);
    }
    public function getSubCategories()
    {
        $data = $this->subcategory->where(['subcategory_status' => 'Active'])->findAll();
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
                $this->_validateSubCategory("add");
                $subcategory_code = ((!empty($this->request->getVar('subcategory_code'))) ? trim($this->request->getVar('subcategory_code')) : $this->settings->generateRandomNumbers(6, 'digit'));
                $data = [
                    'subcategory_name' => trim($this->request->getVar('subcategory_name')),
                    'subcategory_code' => $subcategory_code,
                    'subcategory_slug' => trim(url_title(strtolower($this->request->getVar('subcategory_name')), '-', true)),
                    'subcategory_type' => trim($this->request->getVar('subcategory_type')),
                    'subcategory_status' => trim($this->request->getVar('subcategory_status')),
                    'category_id' => trim($this->request->getVar('category_id')),
                    'account_id' => $this->userRow['account_id'],
                ];
                $insert = $this->subcategory->insert($data);
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
                                # generate subcategory code
                                $subcategory_code = (!empty($column[1]) ? trim($column[1]) : $this->settings->generateRandomNumbers(6, 'digit'));

                                $importData = [
                                    'subcategory_name' => trim($column[0]),
                                    'subcategory_code' => $subcategory_code,
                                    'subcategory_slug' => trim(url_title(strtolower($column[0]), '-', true)),                   'subcategory_type' => trim($this->request->getVar('subcategory_type')),
                                    'subcategory_status' => trim($column[2]),
                                    'category_id' => trim($column[3]),
                                    'account_id' => $this->userRow['account_id'],
                                ];

                                # save the subcategory information
                                $insert = $this->subcategory->insert($importData);
                            }
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
                # Activity
                $record = ($index == 1) ? " record" : " records";
                $this->saveUserActivity([
                    'user_id' => $this->userRow['id'],
                    'action' => $mode,
                    'description' => ($index . ' subcategory ' . $record),
                    'module' => strtolower($this->title),
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
    public function update_subcategory($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateSubCategory("update");
                $subcategory_code = ((!empty($this->request->getVar('subcategory_code'))) ? trim($this->request->getVar('subcategory_code')) : $this->settings->generateRandomNumbers(6, 'digit'));
                $data = [
                    'subcategory_name' => trim($this->request->getVar('subcategory_name')),
                    'subcategory_code' => $subcategory_code,
                    'subcategory_slug' => trim(url_title(strtolower($this->request->getVar('subcategory_name')), '-', true)),
                    'subcategory_type' => trim($this->request->getVar('subcategory_type')),
                    'subcategory_status' => trim($this->request->getVar('subcategory_status')),
                    'category_id' => trim($this->request->getVar('category_id')),
                ];
                $update = $this->subcategory->update($id, $data);
                if ($update) {
                    # Activity
                    $this->saveUserActivity([
                        'user_id' => $this->userRow['id'],
                        'action' => 'update',
                        'description' => ('subcategory: ' . $data['subcategory_name']),
                        'module' => strtolower('subcategories'),
                        'referrer_id' => $id,
                        'title' => $this->title,
                    ]);
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
            $data = $this->subcategory->find($id);
            if ($data) {
                if (strtolower($data['subcategory_type']) == 'system') {
                    $response = [
                        'status' => 500,
                        'error' => 'Not Allowed',
                        'messages' => 'Deleting ' . $this->title . ' system record is not allowed'
                    ];
                    return $this->respond($response);
                    exit;
                } else {
                    $delete = $this->subcategory->delete($id);
                    if ($delete) {
                        # Activity
                        $this->saveUserActivity([
                            'user_id' => $this->userRow['id'],
                            'action' => 'delete',
                            'description' => ('subcategory: ' . $data['subcategory_name']),
                            'module' => strtolower('subcategories'),
                            'referrer_id' => $id,
                            'title' => $this->title,
                        ]);
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
                $data = $this->subcategory->find($id);
                if ($data) {
                    if (strtolower($data['subcategory_type']) == 'system') {
                        continue;
                    } else {
                        $delete = $this->subcategory->delete($id);
                        if ($delete) {
                            // insert into activity logs
                            $activityData = [
                                'user_id' => $this->userRow['id'],
                                'action' => 'bulk-delete',
                                'description' => ucfirst('bulk deleted subcategory: ' . $data['subcategory_name']),
                                'module' => strtolower('subcategories'),
                                'referrer_id' => $id,
                            ];
                            $activity = $this->insertActivityLog($activityData);
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
    private function _validateSubCategory($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $subcategoryInfo = $this->subcategory->find($this->request->getVar('id'));

        # sub category validation
        if ($this->request->getVar('subcategory_name') == '' || $this->request->getVar('subcategory_name') == 0) {
            $data['inputerror'][] = 'subcategory_name';
            $data['error_string'][] = 'Sub Category Name is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('subcategory_name'))) {
            $name = $this->request->getVar('subcategory_name');
            if ($this->settings->validateName($name) == TRUE) {
                if (strlen($name) < 4) {
                    $data['inputerror'][] = 'subcategory_name';
                    $data['error_string'][] = 'Minimum 4 letters required [' . strlen($name) . ']!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($name) == FALSE) {
                $data['inputerror'][] = 'subcategory_name';
                $data['error_string'][] = 'Valid Subcategory Name is required!';
                $data['status'] = FALSE;
            }
            if ($method == 'add') {
                $dat = $this->subcategory
                    ->where(['subcategory_name' => $this->request->getVar('subcategory_name')])->first();
                if ($dat) {
                    $data['inputerror'][] = 'subcategory_name';
                    $data['error_string'][] = $this->request->getVar('subcategory_name') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
            if ($method == 'update' && strtolower($subcategoryInfo['subcategory_name']) != strtolower($this->request->getVar('subcategory_name'))) {
                $info = $this->subcategory
                    ->where(['subcategory_name' => $this->request->getVar('subcategory_name')])->first();
                if ($info) {
                    $data['inputerror'][] = 'subcategory_name';
                    $data['error_string'][] = $this->request->getVar('subcategory_name') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        if (!empty($this->request->getVar('subcategory_code'))) {
            $code = $this->request->getVar('subcategory_code');
            if ($code < 0) {
                $data['inputerror'][] = 'subcategory_code';
                $data['error_string'][] = 'Valid Code should be a positive integer!';
                $data['status'] = FALSE;
            }
            if (strlen($code) < 4) {
                $data['inputerror'][] = 'subcategory_code';
                $data['error_string'][] = 'Minimum 4 letters required [' . strlen($code) . ']!';
                $data['status'] = FALSE;
            }
            if (preg_match('/^[-0-9- ]+$/', trim($code)) == FALSE) {
                $data['inputerror'][] = 'subcategory_code';
                $data['error_string'][] = 'Code can only be digits and -!';
                $data['status'] = FALSE;
            }
            if ($method == 'add') {
                $dat = $this->subcategory->where([
                    'subcategory_code' => $this->request->getVar('subcategory_code')
                ])->first();
                if ($dat) {
                    $data['inputerror'][] = 'subcategory_code';
                    $data['error_string'][] = $this->request->getVar('subcategory_code') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
            if ($method == 'update' && $subcategoryInfo['subcategory_code'] != $this->request->getVar('subcategory_code')) {
                $info = $this->subcategory->where([
                    'subcategory_code' => $this->request->getVar('subcategory_code')
                ])->first();
                if ($info) {
                    $data['inputerror'][] = 'subcategory_code';
                    $data['error_string'][] = $this->request->getVar('subcategory_code') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        # sub category status validation
        if ($this->request->getVar('subcategory_status') == '') {
            $data['inputerror'][] = 'subcategory_status';
            $data['error_string'][] = 'SubCategory status is required!';
            $data['status'] = FALSE;
        }

        # category id validation
        if ($this->request->getVar('category_id') == '') {
            $data['inputerror'][] = 'category_id';
            $data['error_string'][] = 'Category is required!';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
