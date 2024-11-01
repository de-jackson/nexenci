<?php

namespace App\Controllers\Admin;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Menu extends MasterController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Menu';
        $this->title = 'Menus';
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
            return view('admin/menu/index', [
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

    public function menu_view($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $menu = $this->menuModel->find($id);
            if ($menu) {
                return view('admin/menu/index', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ]);
            } else {
                session()->setFlashdata('failed', 'Menu can not be found');
                return redirect()->to(base_url('admin/dashboard'));
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    public function menu_list($id = null)
    {
        if ($id == 0) {
            $where = ['deleted_at' => Null];
        } else {
            $where = ['deleted_at' => Null, 'id' => $id];
        }
        $menus = $this->menuModel->select('order, parent_id, title, menu, icon, url, status, create, import, view, update, delete, bulkDelete, export, id')->where($where)->orderBy('order');
        return DataTable::of($menus)
            ->add('checkbox', function ($menu) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $menu->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('action', function ($menu) {
                if (strtolower($menu->status) == 'active') {
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
                    $actions .= '<a href="javascript:void(0)" onclick="view_menu(' . "'" . $menu->id . "'" . ')" title="view menu" class="dropdown-item" id="view' . $this->menu . '"><i class="fas fa-eye text-success"></i> View Menu</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="edit_menu(' . "'" . $menu->id . "'" . ')" title="edit menu" class="dropdown-item" id="edit' . $this->menu . '"><i class="fas fa-edit text-info"></i> Edit Menu</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="delete_menu(' . "'" . $menu->id . "'" . ',' . "'" . $menu->title . "'" . ')" title="delete menu" class="dropdown-item" id="delete' . $this->menu . '"><i class="fas fa-trash text-danger"></i> Delete Menu</a>';
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
        $data = $this->menuModel->find($id);
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requested ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    public function allowed_menus()
    {
        $menus = $this->menuModel->where([
            'status' => 'Active'
        ])->orderBy('order')->findAll();

        $permissions = unserialize($this->userRow['permissions']);

        if ($menus) {
            $data = [];
            foreach ($menus as $menu) {
                /*
                if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                    $data[] = $menu;
                }
                */

                if (($permissions == 'all') || (in_array('create' . $menu['slug'], $permissions) || in_array('view' . $menu['slug'], $permissions) || in_array('update' . $menu['slug'], $permissions) || in_array('delete' . $menu['slug'], $permissions) || in_array('bulkDelete' . $menu['slug'], $permissions))) {
                    $data[] = $menu;
                }
            }
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    public function search_menu($search)
    {
        $data = $this->menuModel->select('title, url')->like(['title' => $search])->findAll();
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Menu could not be found!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    public function childMenus($parent_id)
    {
        $data = $this->menuModel->where(['status' => 'Active', 'parent_id' => $parent_id])->orderBy('order')->findAll();
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requested ' . $this->title . ' could not be found!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'create')) {
            $this->_validateMenu('add');
            $title = $this->request->getVar('title');
            $data = [
                'title' => trim(ucfirst($title)),
                'slug' => trim(ucwords(str_replace(' ', '', $title))),
                'menu' => trim($this->request->getVar('menu')),
                'icon' => trim($this->request->getVar('icon')),
                'order' => trim($this->request->getVar('order')),
                'parent_id' => trim($this->request->getVar('parent_id')),
                'url' => trim($this->request->getVar('url')),
                'status' => trim($this->request->getVar('status')),
                'create' => $this->request->getVar('create'),
                'import' => $this->request->getVar('import'),
                'view' => $this->request->getVar('view'),
                'update' => $this->request->getVar('update'),
                'delete' => $this->request->getVar('delete'),
                'bulkDelete' => $this->request->getVar('bulkDelete'),
                'export' => $this->request->getVar('export'),
            ];
            $insert = $this->menuModel->insert($data);
            if ($insert) {
                // insert into activity logs
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => ucfirst('Created ' . $this->title . ', ' . $data['title']),
                    'module' => $this->menu,
                    'referrer_id' => $insert,
                ];
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => $this->title . ' record created successfully',
                    ];
                    return $this->respond($response);
                    exit();
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => $this->title . ' record created successfully. loggingFailed'
                    ];
                    return $this->respond($response);
                    exit();
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Create Failed',
                    'messages' => 'Creating ' . $this->title . ' record failed, try again later!',
                ];
                return $this->respond($response);
                exit();
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to create ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update_menu($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateMenu("update");
                $title = $this->request->getVar('title');
                $data = [
                    'title' => trim(ucfirst($title)),
                    'slug' => trim(ucwords(str_replace(' ', '', $title))),
                    'menu' => trim($this->request->getVar('menu')),
                    'icon' => trim($this->request->getVar('icon')),
                    'order' => trim($this->request->getVar('order')),
                    'parent_id' => trim($this->request->getVar('parent_id')),
                    'url' => trim($this->request->getVar('url')),
                    'status' => trim($this->request->getVar('status')),
                    'create' => $this->request->getVar('create'),
                    'import' => $this->request->getVar('import'),
                    'view' => $this->request->getVar('view'),
                    'update' => $this->request->getVar('update'),
                    'delete' => $this->request->getVar('delete'),
                    'bulkDelete' => $this->request->getVar('bulkDelete'),
                    'export' => $this->request->getVar('export'),
                ];
                $update = $this->menuModel->update($id, $data);
                if ($update) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => ucfirst('updated ' . $this->title . ', ' . $data['title']),
                        'module' => $this->menu,
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record updated successfully',
                        ];
                        return $this->respond($response);
                        exit();
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record updated successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit();
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Update Failed',
                        'messages' => 'Updating ' . $this->title . ' record failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit();
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'Update Failed. Invalid ID provided, try again!',
                ];
                return $this->respond($response);
                exit();
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to update ' . $this->title . ' record!',
            ];
            return $this->respond($response);
            exit();
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
            $data = $this->menuModel->find($id);
            if ($data) {
                $delete = $this->menuModel->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => ucfirst('deleted ' . $this->title . ', ' . $data['title']),
                        'module' => $this->menu,
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
                        exit();
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record deleted successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit();
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Delete Failed',
                        'messages' => 'Deleting ' . $this->title . ' record failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit();
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'The requested ' . $this->title . ' resource could not be found!',
                ];
                return $this->respond($response);
                exit();
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' record!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    public function ajax_bulky_delete()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->menuModel->find($id);
                if ($data) {
                    $delete = $this->menuModel->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['title']),
                            'module' => $this->menu,
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
                exit();
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' record(s) deleted successfully. loggingFailed'
                ];
                return $this->respond($response);
                exit();
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' record(s)!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    /**
     * validate form inputs
     */
    private function _validateMenu($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $menunfo = $this->menuModel->find($this->request->getVar('id'));
        if ($this->request->getVar('title') == '') {
            $data['inputerror'][] = 'title';
            $data['error_string'][] = 'Menu Title is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('title'))) {
            $title = $this->request->getVar('title');
            if ($this->settings->validateName($title) == TRUE) {
                if (strlen(trim($title)) < 3) {
                    $data['inputerror'][] = 'title';
                    $data['error_string'][] = 'Minimum 3 letters required [' . strlen($title) . ']';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($this->request->getVar('title')) == FALSE) {
                $data['inputerror'][] = 'title';
                $data['error_string'][] = 'Valid Menu Title is required';
                $data['status'] = FALSE;
            }
            if ($method == "add") {
                $menuRow = $this->menuModel->where(['title' => $this->request->getVar('title')])->first();
                if ($menuRow) {
                    $data['inputerror'][] = 'title';
                    $data['error_string'][] = $this->request->getVar('title') . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if ($method == "update" && strtolower($menunfo['title']) != strtolower($this->request->getVar('title'))) {
                $menuRow = $this->menuModel->where(['title' => $this->request->getVar('title')])->first();
                if ($menuRow) {
                    $data['inputerror'][] = 'title';
                    $data['error_string'][] = $this->request->getVar('title') . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('order') == '') {
            $data['inputerror'][] = 'order';
            $data['error_string'][] = 'Menu Order is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('order'))) {
            if (!preg_match("/^[0-9]+$/", trim($this->request->getVar('order')))) {
                $data['inputerror'][] = 'order';
                $data['error_string'][] = 'Valid order required';
                $data['status'] = FALSE;
            }
            if ($method == "add") {
                $menuRow = $this->menuModel->where(['order' => $this->request->getVar('order')])->first();
                if ($menuRow) {
                    $data['inputerror'][] = 'order';
                    $data['error_string'][] = $this->request->getVar('order') . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if ($method == "update" && ($menunfo['order'] != $this->request->getVar('order'))) {
                $menuRow = $this->menuModel->where(['order' => $this->request->getVar('order')])->first();
                if ($menuRow) {
                    $data['inputerror'][] = 'order';
                    $data['error_string'][] = $this->request->getVar('order') . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('icon') == '') {
            $data['inputerror'][] = 'icon';
            $data['error_string'][] = 'Menu icon is required';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('url') == '') {
            $data['inputerror'][] = 'url';
            $data['error_string'][] = 'Menu url is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('url'))) {
            if ($this->settings->validateAddress($this->request->getVar('url')) == TRUE) {
                if (strlen(trim($this->request->getVar('url'))) < 1) {
                    $data['inputerror'][] = 'url';
                    $data['error_string'][] = 'URL is too short';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateAddress($this->request->getVar('url')) == FALSE) {
                $data['inputerror'][] = 'url';
                $data['error_string'][] = 'Valid URL is required';
                $data['status'] = FALSE;
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
