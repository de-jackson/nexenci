<?php

namespace App\Controllers\Admin;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class User extends MasterController
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Users';
        $this->title = 'Users';
        $this->subTitle1 = 'Logins';
        $this->subTitle2 = 'Activity';
        $this->menuItem = [
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
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            return view('admin/users/users', [
                'title' => $this->title,
                'menu' => $this->menu,
                'accounts' => $this->settings->generateAccountTypes(),
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'permissions' => $this->userPermissions,
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    // user log view
    public function user_views($menu)
    {
        $this->menuItem['title'] = ucfirst($menu);
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $data = [
                'title' => ucfirst($menu),
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
            ];
            switch (strtolower($menu)) {
                case 'logs':
                    return view('admin/users/logs/index', $data);
                    break;
                case 'activity':
                    return view('admin/users/activity/index', $data);
                    break;
                default:
                    session()->setFlashdata('failed', 'Page requested can not be found');
                    return redirect()->to(base_url('admin/dashboard'));;
                    break;
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    function user_forms($menu, $id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            if ($id == 0) {
                if (strtolower($menu) == 'user') {
                    $title = "Add User Form";
                } elseif (strtolower($menu) == 'log') {
                    $title = "Add User Log Form";
                } else {
                    $title = "Add User Activity Form";
                }
                $data = [
                    'id' => $id,
                    'title' => $title,
                    'menu' => $menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ];
            } else {
                $data = [
                    'id' => $id,
                    'menu' => $menu,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ];
                if (strtolower($menu) == 'user') {
                    $title = "User View Form";
                    $data['title'] = $title;
                    $data['user'] = $this->user->select(['users.*', 'branches.branch_name'])
                        ->join('branches', 'branches.id = users.branch_id', 'left')->find($id);
                } elseif (strtolower($menu) == 'log') {
                    $title = "User Log View Form";
                    $data['title'] = $title;
                    $data['log'] = $this->userLog->select('userlogs.*, users.name')
                        ->join('users', 'users.id = userlogs.user_id', 'left')->find($id);
                } else {
                    $title = "User Activity View Form";
                    $data['title'] = $title;
                    $data['activity'] = $this->userActivity->select('useractivities.*, users.name')
                        ->join('users', 'users.id = useractivities.user_id', 'left')->find($id);
                }
            }
            return view('admin/users/user_formsPDF', $data);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

    public function users_list()
    {
        $users = $this->user->select('users.name, users.mobile, users.email, users.access_status, users.account_type, positions.position, users.photo, users.id, staffs.gender')
            ->join('staffs', 'staffs.id = users.staff_id', 'left')->join('positions', 'positions.id = staffs.position_id', 'left')->where(['users.deleted_at' => null]);

        return DataTable::of($users)
            ->add('checkbox', function ($user) {
                return '
                <div class="text-center"><input type="checkbox" class="data-check text-center" value="' . $user->id . '">
                </div>
                ';
            })
            ->addNumbering('no') # it will return data output with numbering on first column
            ->add('photo', function ($user) {
                # check whether the photo exist
                if (file_exists("uploads/users/" . $user->photo) && $user->photo) {
                    # display the current photo
                    $photo = '
                    <a href="javascript:void(0)" onclick="view_user_photo(' . "'" . $user->id . "'" . ')"><img src="' . base_url('uploads/users/' . $user->photo) . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                } else {
                    # display the default photo
                    $photo = '
                    <a href="javascript:void(0)" onclick="view_user_photo(' . "'" . $user->id . "'" . ')"><img src="' . base_url('assets/dist/img/nophoto.jpg') . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                }

                return '<div class="products">
                ' . $photo . '
                    <div>
                        <h6>' . strtoupper($user->name) . '</h6>
                        <span>' . $user->position . '</span>	
                    </div>	
                </div>';
            })
            ->add('action', function ($user) {
                $this->menuItem['title'] = $this->title;
                $password =  $this->settings->generateRandomNumbers(8);
                if (strtolower($user->access_status) == 'active') {
                    $text = 'info';
                    $statusBtn = '<div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" onclick="edit_userStatus(' . "'" . $user->id . "'" . ',' . "'" . $user->name . "'" . ')" title="de-activate user" class="dropdown-item">
                    <i class="fas fa-user-slash text-danger"></i> De-activate User
                    </a>';
                    $passwordBtn = '<div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" class="dropdown-item" onclick="create_password(' . "'" . $user->id . "'" . ', ' . "'" . $password . "'" . ')" title="Generate Password"><i class="fas fa-lock text-warning"></i> New Password</a>';
                    $permissionBtn = '<div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" class="dropdown-item" onclick="user_permissions(' . "'" . $user->id . "'" . ', ' . "'" . $user->name . "'" . ')"><i class="fas fa-user-secret text-secondary"></i> Permissions </a>';
                } else {
                    $text = 'danger';
                    $statusBtn = '<div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" onclick="edit_userStatus(' . "'" . $user->id . "'" . ',' . "'" . $user->name . "'" . ')" title="activate user" class="dropdown-item">
                      <i class="fas fa-user-check text-success"></i> Activate User
                    </a>';
                    $passwordBtn = '<div class="dropdown-divider"></div>';
                    $permissionBtn = '<div class="dropdown-divider"></div>';
                }

                // show buttons based on user permissions
                $actions = '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">';
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                    $actions .= '<a href="javascript:void(0)" class="dropdown-item" onclick="view_user(' . "'" . $user->id . "'" . ')"><i class="fas fa-eye text-success"></i> View User</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    $actions .= $statusBtn . '<div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="edit_user(' . "'" . $user->id . "'" . ')"><i class="fas fa-edit text-info"></i> Edit User</a>' . $passwordBtn . $permissionBtn;
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '<div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="delete_user(' . "'" . $user->id . "'" . ', ' . "'" . $user->name . "'" . ')"><i class="fas fa-trash text-danger"></i> Delete User
                        </a>';
                }
                $actions .= ' 
                </div>
                </div>';
                return $actions;
            })
            ->toJson(true);
    }

    public function logs_list()
    {
        $logs = $this->userLog->select('users.name, userlogs.login_at, userlogs.logout_at, userlogs.ip_address, userlogs.browser, userlogs.status, userlogs.id')
            ->join('users', 'users.id = userlogs.user_id', 'left')->where(['userlogs.deleted_at' => null])->orderBy('userlogs.id', 'desc');

        return DataTable::of($logs)
            ->add('checkbox', function ($log) {
                return '
                <div class="text-center"><input type="checkbox" class="data-check text-center" value="' . $log->id . '">
                </div>
                ';
            })
            ->addNumbering('no')
            ->add('action', function ($log) {
                if (strtolower($log->status) == 'online') {
                    $text = "success";
                } else {
                    $text = "primary";
                }
                // show buttons based on user permissions
                $actions = '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">';
                if (($this->userPermissions == 'all') || (in_array('view_' . strtolower($this->menu) . $this->subTitle1, $this->userPermissions))) {
                    $actions .= '<a href="javascript:void(0)" class="dropdown-item" onclick="view_log(' . "'" . $log->id . "'" . ')"><i class="fas fa-eye text-success"></i> View Log</a>';
                }
                if (($this->userPermissions == 'all') || (in_array('update_' . strtolower($this->menu) . $this->subTitle1, $this->userPermissions))) {
                    $actions .= '';
                }
                if (($this->userPermissions == 'all') || (in_array('delete_' . strtolower($this->menu) . $this->subTitle1, $this->userPermissions))) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" class="dropdown-item" onclick="delete_log(' . "'" . $log->id . "'" . ', ' . "'" . $log->name . "'" . ')"><i class="fas fa-trash text-danger"></i> Delete Log
                                    </a>';
                }
                $actions .= ' 
                </div>
                </div>';
                return $actions;
            })
            ->toJson(true);
    }

    public function logs_report($from = null, $to = null)
    {
        if ($from != 0 && $to == 0) {
            $where = ['DATE_FORMAT(userlogs.created_at, "%Y-%m-%d") >=' => $from, 'userlogs.deleted_at' => Null];
        } elseif ($from == 0 && $to != 0) {
            $where = ['DATE_FORMAT(userlogs.created_at, "%Y-%m-%d") >=' => $to, 'userlogs.deleted_at' => Null];
        } elseif ($from != 0 && $to != 0) {
            $where = ['DATE_FORMAT(userlogs.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(userlogs.created_at, "%Y-%m-%d") <=' => $to, 'userlogs.deleted_at' => Null];
        } else {
            $where = ['userlogs.deleted_at' => Null];
        }
        $logs = $this->userLog->select('users.name, userlogs.login_at, userlogs.logout_at, userlogs.ip_address, userlogs.browser, userlogs.status, userlogs.id')->join('users', 'users.id = userlogs.user_id', 'left')->where($where)->orderBy('userlogs.id', 'desc');
        return DataTable::of($logs)
            ->add('checkbox', function ($log) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $log->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('action', function ($log) {
                if (strtolower($log->status) == 'online') {
                    $text = "text-success";
                } else {
                    $text = "text-primary";
                }
                return '
                    <div class="text-center">
                        <a href="javascript:void(0)" onclick="view_log(' . "'" . $log->id . "'" . ')" title="view log" class="' . $text . '"><i class="fas fa-eye"></i></a>
                    </div>
                ';
            })
            ->toJson(true);
    }

    public function activity_list()
    {
        $activities = $this->userActivity->select('users.name, useractivities.action, useractivities.module, useractivities.referrer_id, useractivities.created_at, useractivities.id')
            ->join('users', 'users.id = useractivities.user_id', 'left')->where(['useractivities.deleted_at' => null])->orderBy('id', 'desc');

        return DataTable::of($activities)
            ->add('checkbox', function ($activity) {
                return '
                <div class="text-center"><input type="checkbox" class="data-check text-center" value="' . $activity->id . '">
                </div>
                ';
            })
            ->addNumbering('no')
            ->add('go_to', function ($activity) {
                switch (strtolower($activity->module)) {
                    case 'menus':
                        return '
                            <a href="/menus/info/' . $activity->referrer_id . '" class="font-italic" title="go to menu"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'branches':
                        return '
                            <a href="/admin/branch/info/' . $activity->referrer_id . '" class="font-italic" title="go to branch"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'departments':
                        return '
                            <a href="/admin/department/info/' . $activity->referrer_id . '" class="font-italic" title="go to department"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'positions':
                        return '
                            <a href="/admin/position/info/' . $activity->referrer_id . '" class="font-italic" title="go to position"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'staff':
                        return '
                            <a href="/admin/staff/info/' . $activity->referrer_id . '" class="font-italic" title="go to staff"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'clients':
                        return '
                            <a href="/admin/client/info/' . $activity->referrer_id . '" class="font-italic" title="go to client"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'categories':
                        return '
                            <a href="/admin/category/info/' . $activity->referrer_id . '" class="font-italic" title="go to category"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'subcategories':
                        return '
                            <a href="/admin/subcategory/info/' . $activity->referrer_id . '" class="font-italic" title="go to subcategory"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'particulars':
                        return '
                            <a href="/admin/particular/info/' . $activity->referrer_id . '" class="font-italic" title="go to particular"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'transactions':
                        return '
                            <a href="/admin/transaction/info/' . $activity->referrer_id . '" class="font-italic" title="go to subcategory"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'savings':
                        return '
                                <a href="/admin/transaction/info/' . $activity->referrer_id . '" class="font-italic" title="go to subcategory"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                            ';
                        break;
                    case 'products':
                        return '
                            <a href="/admin/product/info/' . $activity->referrer_id . '" class="font-italic" title="go to product"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'applications':
                        return '
                            <a href="/admin/application/info/' . $activity->referrer_id . '" class="font-italic" title="go to application"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'settings':
                        return '
                            <a href="admin/settings/setting' . $activity->referrer_id . '" class="font-italic" title="go to settings"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'disbursements':
                        return '
                            <a href="/admin/disbursement/info/' . $activity->referrer_id . '" class="font-italic" title="go to application"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    default:
                        return '
                            <a href="javascript: void(0)" class="font-italic" title="go to particular">#' . $activity->referrer_id . '</a>
                        ';
                        break;
                }
            })
            ->add('actions', function ($activity) {
                $text = "info";
                // show buttons based on user permissions
                $actions = '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">';
                if (($this->userPermissions == 'all') || (in_array('view_' . strtolower($this->menu) . $this->subTitle2, $this->userPermissions))) {
                    $actions .= '<a href="javascript:void(0)" class="dropdown-item" onclick="view_activity(' . "'" . $activity->id . "'" . ')"><i class="fas fa-eye text-success"></i> View Activity</a>';
                }
                if (($this->userPermissions == 'all') || (in_array('update_' . strtolower($this->menu) . $this->subTitle2, $this->userPermissions))) {
                    $actions .= '';
                }
                if (($this->userPermissions == 'all') || (in_array('delete_' . strtolower($this->menu) . $this->subTitle2, $this->userPermissions))) {
                    $actions .= '<div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="delete_activity(' . "'" . $activity->id . "'" . ', ' . "'" . $activity->name . "'" . ')"><i class="fas fa-trash text-danger"></i> Delete Activity
                        </a>';
                }
                $actions .= ' 
                </div>
                </div>';
                return $actions;
            })
            ->toJson(true);
    }

    public function activity_report($from = null, $to = null)
    {
        if ($from != 0 && $to == 0) {
            $where = ['DATE_FORMAT(useractivities.created_at, "%Y-%m-%d") >=' => $from, 'useractivities.deleted_at' => Null];
        } elseif ($from == 0 && $to != 0) {
            $where = ['DATE_FORMAT(useractivities.created_at, "%Y-%m-%d") >=' => $to, 'useractivities.deleted_at' => Null];
        } elseif ($from != 0 && $to != 0) {
            $where = ['DATE_FORMAT(useractivities.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(useractivities.created_at, "%Y-%m-%d") <=' => $to, 'useractivities.deleted_at' => Null];
        } else {
            $where = ['useractivities.deleted_at' => Null];
        }
        $logs = $this->userActivity
            ->select('users.name, useractivities.action, useractivities.module, useractivities.referrer_id, useractivities.created_at, useractivities.id')
            ->join('users', 'users.id = useractivities.user_id', 'left')
            ->where($where)->orderBy('useractivities.id', 'desc');
        return DataTable::of($logs)
            ->add('checkbox', function ($log) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $log->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('refer', function ($activity) {
                switch (strtolower($activity->module)) {
                    case 'menus':
                        return '
                            <a href="/menus/info/' . $activity->referrer_id . '" class="font-italic" title="go to menu"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'branches':
                        return '
                            <a href="/admin/branch/info/' . $activity->referrer_id . '" class="font-italic" title="go to branch"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'departments':
                        return '
                            <a href="/admin/department/info/' . $activity->referrer_id . '" class="font-italic" title="go to department"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'positions':
                        return '
                            <a href="/admin/department/info/' . $activity->referrer_id . '" class="font-italic" title="go to position"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'staff':
                        return '
                            <a href="/admin/staff/info/' . $activity->referrer_id . '" class="font-italic" title="go to staff"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'clients':
                        return '
                            <a href="/admin/client/info/' . $activity->referrer_id . '" class="font-italic" title="go to client"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'categories':
                        return '
                            <a href="/admin/category/info/' . $activity->referrer_id . '" class="font-italic" title="go to category"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'subcategories':
                        return '
                            <a href="/admin/subcategory/info/' . $activity->referrer_id . '" class="font-italic" title="go to subcategory"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'particulars':
                        return '
                            <a href="/admin/particular/info/' . $activity->referrer_id . '" class="font-italic" title="go to particular"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'transactions':
                        return '
                            <a href="/admin/transaction/info/' . $activity->referrer_id . '" class="font-italic" title="go to subcategory"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'products':
                        return '
                            <a href="/admin/product/info/' . $activity->referrer_id . '" class="font-italic" title="go to product"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'applications':
                        return '
                            <a href="/admin/application/info/' . $activity->referrer_id . '" class="font-italic" title="go to application"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'settings':
                        return '
                            <a href="admin/settings/setting' . $activity->referrer_id . '" class="font-italic" title="go to settings"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    case 'disbursements':
                        return '
                            <a href="/admin/disbursement/info/' . $activity->referrer_id . '" class="font-italic" title="go to application"><i class="fas fa-eye text-success"></i>#' . $activity->referrer_id . '</a>
                        ';
                        break;
                    default:
                        return '
                            <a href="javascript: void(0)" class="font-italic" title="go to particular">#' . $activity->referrer_id . '</a>
                        ';
                        break;
                }
            })
            ->add('actions', function ($activity) {
                return '
                    <div class="text-center">
                        <a href="javascript:void(0)" onclick="view_activity(' . "'" . $activity->id . "'" . ')" title="view activity" class="text-info"><i class="fas fa-eye"></i></a>
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
        $user = $this->user->find($id);
        if ($user) {
            $data = $this->user->select('users.staff_id, users.branch_id, users.name, users.email, users.mobile, users.address, users.account_type, users.photo, users.access_status, branches.branch_name, staffs.staffID, staffs.account_type, positions.position, departments.department_name')
                ->join('branches', 'branches.id = users.branch_id', 'left')
                ->join('staffs', 'staffs.id = users.staff_id', 'left')
                ->join('positions', 'positions.id = staffs.position_id', 'left')
                ->join('departments', 'departments.id = positions.department_id', 'left')
                ->find($id);
            if ($user['permissions']) {
                $data['allowed'] = unserialize($user['permissions']);
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
            exit();
        }
    }

    public function show_log($id = null)
    {
        $data = $this->userLog->select('userlogs.*, users.name')
            ->join('users', 'users.id = userlogs.user_id', 'left')->find($id);
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requested ' . $this->title . ' Logs resource could not be found!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    public function show_activity($id = null)
    {
        $data = $this->userActivity->select('useractivities.*, users.name')
            ->join('users', 'users.id = useractivities.user_id', 'left')->find($id);
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requested ' . $this->title . ' Activity resource could not be found!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        $data = $this->user->find(1);
        $dompdf = new \Dompdf\Dompdf();
        /*$template = view("admin/auth/templates/demo", [
            'user' => $data,
            'token' => '12345434322',
        ]);*/
        $template = '';

        $template .= '
            <link rel="stylesheet" href="/assets/dist/css/bootstrap.min.css" />
            <h1 class="text-primary">Sample Demo</h1>
        ';
        $dompdf->loadHtml($template);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $file_name = "users.pdf";
        $dompdf->stream($file_name, array("Attachment" => false));
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $id = $this->request->getVar('id');
        $menu = $this->request->getVar('menu');
        $email = $this->request->getVar('user_email');
        // $password = trim($this->request->getVar('c_password'));
        $password = $this->settings->generateRandomNumbers(8);
        $userRow = $this->user->find($id);
        if ($userRow) {
            $data = [
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];
            $change = $this->user->update($id, $data);
            if ($change) {
                $txt = '';
                $checkInternet = $this->settings->checkNetworkConnection();
                # check the internet connection
                if ($checkInternet) {
                    # check the email existence
                    if (!empty($email)) {
                        $subject = "Password Update";
                        $userRow['menu'] = $menu;
                        $message = $userRow;
                        $token = 'passwords';
                        $mail = $this->settings->sendMail($message, $subject, $token, $password);
                        $txt .= 'Email Sent ';
                    } 
                    # check the phone number existence 
                    if (!empty($userRow['mobile'])) {
                        # send sms
                        $sms = $this->sendSMS([
                            'mobile' => trim($userRow['mobile']),
                            'text' => 'Your login for ' . strtoupper($this->settingsRow["system_abbr"]) . ': ' . $password
                        ]);
                        $txt .= ' SMS Sent';
                    }
                } 
                
                $response = [
                    'status' => 200,
                    'error' => null,
                    'messages' => 'Password changed successfully '.$txt,
                ];
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Update Failed',
                    'messages' => 'Changing User password failed',
                ];
            }
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'User account could not be found!'
            ];
        }
        return $this->respond($response);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function store()
    {
        $this->menuItem['title'] = $this->title;
        $this->validateUser($this->request->getVar('id'));
        $password = $this->settings->generateRandomNumbers(8);
        $id = $this->request->getVar('id');
        $position_id = trim($this->request->getVar('position_id'));
        $positionRow = $this->position->find($position_id);
        # check the position existence
        if (!$positionRow && $this->request->getVar('id') == 0) {
            # code...
            echo json_encode([
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Position Information could not be found!',
            ]);
            exit;
        }

        $data = [
            'name' => trim($this->request->getVar('name')),
            'email' => $this->request->getVar('email'),
            'mobile' => trim(preg_replace('/^0/', '+256', $this->request->getVar('phone_full'))),
            'address' => $this->request->getVar('address'),
            'account_type' => $this->request->getVar('account_type'),
            'access_status' => $this->request->getVar('access_status'),
            'branch_id' => trim($this->request->getVar('branch_id')),
            'account_id' => $this->userRow['account_id'],
        ];
        # check the user id
        if ($id) {
            if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                # check whether the photo has been uploaded
                if (!empty($_FILES['photo']['name'])) {
                    $upload_photo = $this->_doUploadPhoto();
                    # get user information
                    $userRow = $this->user->find($id);
                    if (file_exists("uploads/users/" . $userRow['photo']) && $userRow['photo']) {
                        # delete or remove the previous photo
                        unlink("uploads/users/" . $userRow['photo']);
                    }
                    # save the new photo
                    $data['photo'] = $upload_photo;
                }
                # update user information
                $save = $this->user->update($id, $data);
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'update',
                    'description' => ucfirst('updated ' . $this->title . ', ' . $data['name']),
                    'module' => $this->menu,
                    'referrer_id' => $id,
                ];
            } else {
                $response = [
                    'status'   => 403,
                    'error'    => 'Access Denied',
                    'messages' => 'You are not authorized to update ' . $this->title . ' records!',
                ];
                return $this->respond($response);
                exit();
            }
        } else {
            if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'create')) {
                $data['staff_id'] = 1;
                # check whether the photo has been uploaded
                if (!empty($_FILES['photo']['name'])) {
                    $photo_filename = $this->_doUploadPhoto();
                    $data['photo'] = $photo_filename;
                }
                # set default position permissions
                $data['permissions'] = $positionRow['permissions'];
                # save auto generated password
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                # create new user information
                $save = $this->user->insert($data);
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'create',
                    'description' => ucfirst('created ' . $this->title . ', ' . $data['name']),
                    'module' => $this->menu,
                    'referrer_id' => $save,
                ];
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
        if ($save) {
            # insert into activity logs
            $activity = $this->insertActivityLog($activityData);
            if ($activity) {
                if ($id) {
                    $response = [
                        'status' => 200,
                        'error' => null,
                        'messages' => "User Updated Successfully"
                    ];
                    return $this->respond($response);
                    exit();
                } else {
                    # check the email to send login credentials
                    if (!empty($data['email'])) {
                        $subject = ucfirst($data['account_type']) . " Registration";
                        $message =  '
                        <p>Welcome to ' . $this->settingsRow['business_name'] . '</p>
                        <p>
                            You have successfully been registered as <b>' . $data['account_type'] . '</b>
                        </p>
                        <p>Log into your account to get started using below login credentials; </p>
                        <p>Email    : ' . $data['email'] . '</p>
                        <p>Password : ' . $password . '</p>
                    ';
                        $token = 'registration';
                        # send login credentials to the email
                        $this->settings->sendMailNotify($message, $subject, $data['email']);
                    }

                    # check the phone number to send login credentials
                    if (!empty($data['mobile'])) {

                        # Set the numbers you want to send to in international format
                        $recipient = $this->phoneNumberWithCountryCode($data['mobile']);
                        # Set the text message
                        $text = 'Your ' . strtoupper($this->settingsRow["system_abbr"]) . ' Login Password: ' . $password;
                        /*
                        $apiResponse   = $this->smsAPI->send([
                            'to'      => $recipient,
                            'message' => $text
                        ]);
                        */
                        $apiResponse = $this->egoAPI->initiate('sms', [
                            "number" => $recipient,
                            "message" => $text
                        ]);

                        $response = [
                            'status' => 200,
                            'error' => null,
                            'messages' => $this->title . " created successfully. Email Sent"
                        ];
                        return $this->respond($response);
                    } else {
                        $response = [
                            'status' => 200,
                            'error' => null,
                            'messages' => $this->title . " created successfully. No Internet"
                        ];
                        return $this->respond($response);
                        exit();
                    }
                }
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => 'User Saved Successfully. loggingFailed'
                ];
                return $this->respond($response);
                exit();
            }
        } else {
            $response = [
                'status' => 500,
                'error' => 'Create Failed',
                'messages' => "User could not be created, try again later"
            ];
            return $this->respond($response);
            exit();
        }
    }

    public function update_userStatus($id = null)
    {
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            $data = $this->user->find($id);
            if ($data) {
                if (strtolower($data['access_status']) == 'active') {
                    $status = "Inactive";
                    $edit = $this->user->update($id, ['access_status' => $status]);
                } else {
                    $status = "Active";
                    $edit = $this->user->update($id, ['access_status' => $status]);
                }
                if ($edit) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'update',
                        'description' => ucfirst('updated ' . $this->title . ', access status, ' . $data['name']),
                        'module' => $this->menu,
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        if (!empty($data['email'])) {
                            $checkInternet = $this->settings->checkNetworkConnection();
                            if ($checkInternet) {
                                $subject = $this->title . " Access Status";
                                $data['status'] = $status;
                                $message = $data;
                                $token = 'access_status';
                                $this->settings->sendMail($message, $subject, $token);
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => $this->title . " record access status updated successfully. Email Sent"
                                ];
                                return $this->respond($response);
                                exit();
                            } else {
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => $this->title . " record access status updated successfully. No Internet"
                                ];
                                return $this->respond($response);
                                exit();
                            }
                        } else {
                            $response = [
                                'status'   => 200,
                                'error'    => null,
                                'messages' => $this->title . ' record access status updated successfully',
                            ];
                            return $this->respond($response);
                            exit();
                        }
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record access status updated successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit();
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Update Failed',
                        'messages' => 'Updating ' . $this->title . ' record  access status failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit();
                }
            } else {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'The requested ' . $this->title . ' resource not found',
                ];
                return $this->respond($response);
                exit();
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to update ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    public function user_permissions()
    {
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            $id = $this->request->getVar('id');
            $data = $this->user->find($id);
            if ($data) {
                if (strtolower($data['access_status']) == 'active') {
                    $edit = $this->user->update($id, ['permissions' => serialize($this->request->getVar('permissions'))]);
                } else {
                    $edit = $this->user->update($id, ['permissions' => serialize($this->request->getVar('permissions'))]);
                }
                if ($edit) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'update',
                        'description' => ucfirst('Updated ' . $this->title . ', permissions, ' . $data['name']),
                        'module' => $this->menu,
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => 'User permissions updated successfully'
                        ];
                        return $this->respond($response);
                        exit();
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => 'User permissions updated successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit();
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Edit Failed',
                        'messages' => 'Editing ' . $this->title . ' permissions failed!',
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
                'messages' => 'You are not authorized to import ' . $this->title . ' records!',
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
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
            $data = $this->user->find($id);
            if ($data) {
                if (file_exists("uploads/users/" . $data['photo']) && $data['photo']) {
                    # delete or remove the previous photo
                    unlink("uploads/users/" . $data['photo']);
                }
                # delete the user information
                $delete = $this->user->delete($id);
                if ($delete) {
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', ' . $data['name']),
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

    public function delete_log($id = null)
    {
        $this->menuItem['title'] = ucfirst($this->subTitle1);
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
            $data = $this->userLog->find($id);
            if ($data) {
                $delete = $this->userLog->delete($id);
                if ($delete) {
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->subTitle1 . ' log record, ' . $data['loginfo']),
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
                        'messages' => 'Deleting ' . $this->subTitle1 . ' record failed, try again later!',
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

    public function delete_activity($id = null)
    {
        $this->menuItem['title'] = ucfirst($this->subTitle2);
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
            $data = $this->userActivity->find($id);
            if ($data) {
                $delete = $this->userActivity->delete($id);
                if ($delete) {
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ' activity record, ' . $data['action']),
                        'module' => $this->menu,
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->subTitle2 . ' record deleted successfully',
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
                        'messages' => 'Deleting ' . $this->subTitle2 . ' record failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit();
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'The requested ' . $this->subTitle2 . ' resource could not be found!',
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

    public function bulkDeleteUser()
    {
        $this->menuItem['title'] = $this->title;
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
            # access granted
            $users = $this->request->getVar('id');
            foreach ($users as $id) {
                $userRow = $this->user->where(['id' => $id])->first();
                if ($userRow) {
                    if (file_exists("uploads/users/" . $userRow['photo']) && $userRow['photo']) {
                        # delete or remove the previous photo
                        unlink("uploads/users/" . $userRow['photo']);
                    }
                    # delete the user information
                    $delete = $this->user->delete($id);
                    if ($delete) {
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $userRow['name']),
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
                'messages' => 'You are not authorized to delete ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    public function bulkDeleteLogs()
    {
        $this->menuItem['title'] = ucfirst($this->subTitle1);
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
            # access granted
            $logs = $this->request->getVar('id');
            foreach ($logs as $id) {
                $data = $this->userLog->find($id);
                if ($data) {
                    $delete = $this->userLog->delete($id);
                    if ($delete) {
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->subTitle1 . ' log(s),  ' . $data['loginfo']),
                            'module' => 'logs',
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
                'messages' => 'You are not authorized to delete ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit();
        }
    }

    public function bulkDeleteActivity()
    {
        $this->menuItem['title'] = ucfirst($this->subTitle2);
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
            # access granted
            $activities = $this->request->getVar('id');
            foreach ($activities as $id) {
                $data = $this->userActivity->find($id);
                if ($data) {
                    $delete = $this->userActivity->delete($id);
                    if ($delete) {
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->subTitle2 . ' activity(ies),  ' . $data['action']),
                            'module' => 'activity',
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
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit();
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
    }

    private function _doUploadPhoto()
    {
        $validationRule = [
            'photo' => [
                "rules" => "uploaded[photo]|max_size[photo,1024]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]",
                "label" => "Profile Image",
                "errors" => [
                    'max_size' => 'The size of this image is too large. The image must have less than 1MB size',
                    'mime_in' => 'Your upload does not have a valid image format',
                    'is_image' => 'Your file is not allowed! Please use an image!'
                ]
            ],
        ];
        if (!$this->validate($validationRule)) {
            $data['inputerror'][] = 'photo';
            $data['error_string'][] = $this->validator->getError("photo");
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
        $file = $this->request->getFile('photo');
        $profile_image = $file->getName();
        # Renaming file before upload
        $temp = explode(".", $profile_image);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        if ($file->move("uploads/users", $newfilename)) {
            return $newfilename;
        } else {
            $data['inputerror'][] = 'photo';
            $data['error_string'][] = "Failed to upload Image";
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
    }

    private function validateUser($menu)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        # trimmed the white space between between country code and phone number
        $phone = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('phone_country_code'),
            'phone' => $this->request->getVar('phone')
        ]);

        if (trim($this->request->getVar('name')) == '') {
            $data['inputerror'][] = 'name';
            $data['error_string'][] = 'Full Name is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('name'))) {
            if ($this->settings->validateName($this->request->getVar('name')) == TRUE) {
                if (strlen(trim($this->request->getVar('name'))) < 6) {
                    $data['inputerror'][] = 'name';
                    $data['error_string'][] = 'Full Name is too short';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($this->request->getVar('name')) == FALSE) {
                $data['inputerror'][] = 'name';
                $data['error_string'][] = 'Valid Full Name is required';
                $data['status'] = FALSE;
            }
        }
        if (empty($this->request->getVar('phone'))) {
            $data['inputerror'][] = 'phone';
            $data['error_string'][] = 'Phone Number is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('phone'))) {
            $this->validPhoneNumber([
                'phone' => $phone,
                'input' => 'phone',
            ]);
            # check the phone number existence
            $user = $this->user->where(['mobile' => $phone])
                ->findAll();
            if ($user) {
                if (strcmp($user[0]['mobile'], $phone) != 0 && $menu) {
                    $data['inputerror'][] = 'phone';
                    $data['error_string'][] = 'Phone Number already exists';
                    $data['status'] = FALSE;
                }

                # check the method i.e add
                if ($menu == 0) {
                    # code...
                    $data['inputerror'][] = 'phone';
                    $data['error_string'][] = 'Phone Number already exists';
                    $data['status'] = FALSE;
                }
            }


            // echo json_encode([
            //     'user' => $menu,
            //     'phoneInput' => $phone,
            //     'phone' => trim($this->request->getVar('phone_full')),
            //     'setting' => $this->settingsRow['email_template_logo']
            // ]);
            // exit;
        }
        if ($this->request->getVar('address') == '') {
            $data['inputerror'][] = 'address';
            $data['error_string'][] = 'Address is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('address'))) {
            if ($this->settings->validateAddress($this->request->getVar('address')) == TRUE) {
                if (strlen(trim($this->request->getVar('address'))) < 4) {
                    $data['inputerror'][] = 'address';
                    $data['error_string'][] = 'Address is too short';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateAddress($this->request->getVar('address')) == FALSE) {
                $data['inputerror'][] = 'address';
                $data['error_string'][] = 'Valid Address is required';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('access_status') == '') {
            $data['inputerror'][] = 'access_status';
            $data['error_string'][] = 'Access Status is required';
            $data['status'] = FALSE;
        }
        if (empty($this->request->getVar('branch_id'))) {
            $data['inputerror'][] = 'branch_id';
            $data['error_string'][] = 'Branch Name is required';
            $data['status'] = FALSE;
        }
        if ($menu == 0) {
            # code...
            if ($this->request->getVar('position_id') == '') {
                $data['inputerror'][] = 'position_id';
                $data['error_string'][] = 'Position is required';
                $data['status'] = FALSE;
            }
            if (empty($this->request->getVar('department_id'))) {
                $data['inputerror'][] = 'department_id';
                $data['error_string'][] = 'Department is required';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('email') == '') {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Email Address is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('email'))) {
            # check whether the email is valid
            if ($this->settings->validateEmail($this->request->getVar('email')) == FALSE) {
                $data['inputerror'][] = 'email';
                $data['error_string'][] = 'Valid Email is required';
                $data['status'] = FALSE;
            }
        }
        if ($menu == 0 && !empty($this->request->getVar('email'))) {
            # validate email
            $userRow = $this->user->where(['email' => $this->request->getVar('email')])->findAll();
            if ($userRow) {
                $data['inputerror'][] = 'email';
                $data['error_string'][] = $this->request->getVar('email') . ' already added';
                $data['status'] = FALSE;
            }
        }
        if (!$menu == 0 && !empty($this->request->getVar('email'))) {
            if ($this->request->getVar('email') != $this->request->getVar('oldemail')) {
                # validate email
                $userRow = $this->user->where(['email' => $this->request->getVar('email')])
                    ->first();
                if ($userRow) {
                    $data['inputerror'][] = 'email';
                    $data['error_string'][] = $this->request->getVar('email') . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
