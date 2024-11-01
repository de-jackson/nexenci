<?php

namespace App\Controllers\Admin\Accounts;

use App\Controllers\MasterController;
use \Hermawan\DataTables\DataTable;

class Categories extends MasterController
{

    public function __construct()
    {
        parent::__construct();
        $this->title = 'Chart of Accounts';
        $this->menu = 'Accounting';
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
            return view('admin/accounts/categories/index', [
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
    public function category_view($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $category = $this->category->find($id);
            if ($category) {
                return view('admin/accounts/categories/index', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'category' => $category,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Item could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    function category_forms($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            if ($id == 0) {
                $title = "Add Chart of Accounts Form";
            } else {
                $title = "Chart of Accounts View Form";
            }
            return view('admin/accounts/categories/category_formPDF', [
                'title' => $title,
                'id' => $id,
                'category' => $this->category->find($id),
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    public function load_lists($list = 'categories', $id = null)
    {
        switch (strtolower($list)) {
            case 'categories':
                return $this->categories_list($id);
                break;
            case 'cash_flow_types':
                return $this->cash_flow_types_list($id);
                break;
            case 'account_types':
                return $this->account_types_list($id);
                break;
            default:
                session()->setFlashdata('failed', "Invalid List Parameter Provided!");
                return redirect()->to(base_url('admin/accounts/categories'));
                break;
        };
    }
    /**
     * return all categories as rows
     */
    protected function categories_list($id = null)
    {
        if ($id == 0) {
            $where = ['categories.deleted_at' => Null];
        } else {
            $where = ['categories.id' => $id, 'categories.deleted_at' => Null];
        }
        $categories = $this->category->select('categories.category_name, categories.category_slug, categories.part, statements.name as statement, categories.category_type, categories.category_status, categories.id')
            ->join('statements', 'statements.id = categories.statement_id', 'left')->orderBy('id', 'asc')->where($where);

        return DataTable::of($categories)
            // ->add('checkbox', function ($category) {
            //     return '<div class=""><input type="checkbox" class="data-checkCategories" value="' . $category->id . '"></div>';
            // })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('action', function ($category) {
                if (strtolower($category->category_type) == 'system') {
                    $text = "success";
                } elseif (strtolower($category->category_status) == 'inactive') {
                    $text = "danger";
                } else {
                    $text = "info";
                }
                // show buttons based on user permissions
                $actions = '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">';
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                    $actions .= '<a href="javascript:void(0)" onclick="view_category(' . "'" . $category->id . "'" . ')" title="view '.ucwords($category->category_name) .'" class="dropdown-item"><i class="fas fa-eye text-success"></i> View '.ucwords($category->category_name) .'</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="edit_category(' . "'" . $category->id . "'" . ')" title="edit '.ucwords($category->category_name) .'" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit '.ucwords($category->category_name) .'</a>';
                }
                /* if(($this->userPermissions == 'all') || (in_array('delete'.$this->title,  $this->userPermissions))){
                                $actions .= '<div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" onclick="delete_category(' . "'" . $category->id . "'" . ',' . "'" . $category->category_name . "'" . ')" title="delete '.ucwords($category->category_name) .'" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete '.ucwords($category->category_name) .'</a>';  
                            } */
                $actions .= ' 
                        </div>
                </div>';
                return $actions;
            })
            ->toJson(true);
    }
    /**
     * return all cash flow as rows
     */
    protected function cash_flow_types_list($id = null)
    {
        if ($id == 0) {
            $where = ['cash_flow_types.deleted_at' => Null];
        } else {
            $where = ['cash_flow_types.id' => $id, 'cash_flow_types.deleted_at' => Null];
        }
        $cash_flow_types = $this->cashFlow->select('cash_flow_types.name, cash_flow_types.code, cash_flow_types.description, cash_flow_types.status, cash_flow_types.id')->orderBy('id', 'asc')->where($where);

        return DataTable::of($cash_flow_types)
            // ->add('checkbox', function ($cashFlow) {
            //     return '<div class=""><input type="checkbox" class="data-checkCashFlows" value="' . $cashFlow->id . '"></div>';
            // })
            ->addNumbering("no") //it will return data output with numbering on first column
            // ->add('action', function ($cashFlow) {
            //     if (strtolower($cashFlow->status) == 'inactive') {
            //         $text = "danger";
            //     } else {
            //         $text = "info";
            //     }
            //     // show buttons based on user permissions
            //     $actions = '
            //     <div class="dropdown custom-dropdown mb-0">
            //         <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
            //             <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
            //         </div>
            //         <div class="dropdown-menu dropdown-menu-end">';
            //     if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
            //         $actions .= '<a href="javascript:void(0)" onclick="view_cashFlow(' . "'" . $cashFlow->id . "'" . ')" title="view cash flow" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Cash Flow Type</a>';
            //     }
            //     if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            //         $actions .= '<div class="dropdown-divider"></div>
            //                         <a href="javascript:void(0)" onclick="edit_cashFlow(' . "'" . $cashFlow->id . "'" . ')" title="edit cash flow" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit Cash Flow Type</a>';
            //     }
            //     $actions .= ' 
            //             </div>
            //     </div>';
            //     return $actions;
            // })
            ->toJson(true);
    }

    /**
     * return all cash flow as rows
     */
    protected function account_types_list($id = null)
    {
        if ($id == 0) {
            $where = ['account_types.deleted_at' => Null];
        } else {
            $where = ['account_types.id' => $id, 'account_types.deleted_at' => Null];
        }
        $account_types = $this->accountType->select('account_types.name, account_types.code, account_types.description, account_types.status, categories.category_name, categories.part, account_types.id')
            ->join('categories', 'categories.id = account_types.category_id', 'left')->orderBy('id', 'asc')->where($where);

        return DataTable::of($account_types)
            // ->add('checkbox', function ($account_type) {
            //     return '<div class=""><input type="checkbox" class="data-checkAccountTypes" value="' . $account_type->id . '"></div>';
            // })
            ->addNumbering("no") //it will return data output with numbering on first column
            // ->add('action', function ($account_type) {
            //     if (strtolower($account_type->status) == 'inactive') {
            //         $text = "danger";
            //     } else {
            //         $text = "info";
            //     }
            //     // show buttons based on user permissions
            //     $actions = '
            //     <div class="dropdown custom-dropdown mb-0">
            //         <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
            //             <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
            //         </div>
            //         <div class="dropdown-menu dropdown-menu-end">';
            //     if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
            //         $actions .= '<a href="javascript:void(0)" onclick="view_accountType(' . "'" . $account_type->id . "'" . ')" title="view account type" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Account Type</a>';
            //     }
            //     if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            //         $actions .= '<div class="dropdown-divider"></div>
            //                         <a href="javascript:void(0)" onclick="edit_accountType(' . "'" . $account_type->id . "'" . ')" title="edit account type" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit Account Type</a>';
            //     }
            //     $actions .= ' 
            //             </div>
            //     </div>';
            //     return $actions;
            // })
            ->toJson(true);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->category->select('categories.*, statements.name as statement')->join('statements', 'statements.id = categories.statement_id', 'left')->find($id);
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
     * get all categories
     */
    public function getCategories()
    {
        $data = $this->category->where(['category_status' => 'Active'])->findAll();
        return $this->respond($data);
    }

    public function statement_categories($statement_id)
    {
        if (strtolower($statement_id) == 'all') {
            $categories = $this->category->where(['category_status' => 'Active'])->findAll();
        } else {
            $categories = $this->category->where(['statement_id' => $statement_id, 'category_status' => 'Active'])->findAll();
        }
        return $this->respond($categories);
    }
    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'create')) {
            $this->_validateCategory("add");
            $data = [
                'category_name' => trim($this->request->getVar('category_name')),
                'category_slug' => trim(url_title(strtolower($this->request->getVar('category_name')), '-', true)),
                'part' => trim($this->request->getVar('part')),
                'statement' => $this->request->getVar('statement'),
                'category_type' => trim($this->request->getVar('category_type')),
                'category_status' => trim($this->request->getVar('category_status')),
            ];
            $insert = $this->category->insert($data);
            # Activity
            $this->saveUserActivity([
                'user_id' => $this->userRow['id'],
                'action' => 'create',
                'description' => ($this->title . ': ' . $data['category_name']),
                'module' => strtolower('Categories'),
                'referrer_id' => $insert,
                'title' => $this->title,
            ]);
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
    public function update_category($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateCategory("update");
                $data = [
                    'category_name' => trim($this->request->getVar('category_name')),
                    'category_slug' => trim(url_title(strtolower($this->request->getVar('category_name')), '-', true)),
                    'part' => trim($this->request->getVar('part')),
                    'statement' => $this->request->getVar('statement'),
                    'category_type' => trim($this->request->getVar('category_type')),
                    'category_status' => trim($this->request->getVar('category_status')),
                ];
                $update = $this->category->update($id, $data);
                # Activity
                $this->saveUserActivity([
                    'user_id' => $this->userRow['id'],
                    'action' => 'update',
                    'description' => ($this->title . ': ' . $data['category_name']),
                    'module' => strtolower('Categories'),
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
        return $this->respond([
            'status' => 500,
            'error' => 'Not Allowed',
            'messages' => 'Deleting ' . $this->title . ' record is not allowed'
        ]);
        exit;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
            $data = $this->category->find($id);
            if ($data) {
                $delete = $this->category->delete($id);
                if ($delete) {
                    # Activity
                    $this->saveUserActivity([
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ($this->title . ': ' . $data['category_name']),
                        'module' => strtolower('Categories'),
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
        return $this->respond([
            'status' => 500,
            'error' => 'Not Allowed',
            'messages' => 'Deleting ' . $this->title . ' record is not allowed'
        ]);
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
            exit;
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->category->find($id);
                if ($data) {
                    if (strtolower($data['category_type']) == 'system') {
                        continue;
                    }
                    $delete = $this->category->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ': ' . $data['category_name']),
                            'module' => 'categories',
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
    private function _validateCategory($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $categoryInfo = $this->category->find($this->request->getVar('id'));

        # category validation
        if ($this->request->getVar('category_name') == '') {
            $data['inputerror'][] = 'category_name';
            $data['error_string'][] = 'Category Name is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('category_name'))) {
            $name = $this->request->getVar('category_name');
            if ($this->settings->validateName($name) == TRUE) {
                if (strlen($name) < 5) {
                    $data['inputerror'][] = 'category_name';
                    $data['error_string'][] = 'Minimum 5 letters required [' . strlen($name) . ']!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($name) == FALSE) {
                $data['inputerror'][] = 'category_name';
                $data['error_string'][] = 'Valid Category Name is required!';
                $data['status'] = FALSE;
            }
            # unique category name
            if ($method == 'add') {
                $dat = $this->category
                    ->where(['category_name' => $this->request->getVar('category_name')])->first();
                if ($dat) {
                    $data['inputerror'][] = 'category_name';
                    $data['error_string'][] = $this->request->getVar('category_name') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
            if ($method == 'update' && strtolower($categoryInfo['category_name']) != strtolower($this->request->getVar('category_name'))) {
                $info = $this->category
                    ->where(['category_name' => $this->request->getVar('category_name')])->first();
                if ($info) {
                    $data['inputerror'][] = 'category_name';
                    $data['error_string'][] = $this->request->getVar('category_name') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        # category part validation
        if ($this->request->getVar('part') == '') {
            $data['inputerror'][] = 'part';
            $data['error_string'][] = 'Category part is required!';
            $data['status'] = FALSE;
        }

        # category statement validation
        if ($this->request->getVar('statement') == '') {
            $data['inputerror'][] = 'statement';
            $data['error_string'][] = 'Statement is required!';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('category_status') == '') {
            $data['inputerror'][] = 'category_status';
            $data['error_string'][] = 'Category status is required!';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
