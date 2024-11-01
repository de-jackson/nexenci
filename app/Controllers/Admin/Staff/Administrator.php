<?php

namespace App\Controllers\Admin\Staff;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Administrator extends MasterController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Staff';
        $this->title = 'Administrators';
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
            return view("admin/staff/administrators/index", [
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
    public function admin_view($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $admin = $this->staff->find($id);
            if ($admin) {
                return view('admin/staff/administrators/index', [
                    'title' => $this->title,
                    'menu' => $this->menu,
                    'admin' => $admin,
                    'settings' => $this->settingsRow,
                    'user' => $this->userRow,
                    'userMenu' => $this->load_menu(),
                ]);
            } else {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Administrator could have been deleted or there might be a problem with your URL.');
            }
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    /**
     * return all staffs as rows
     */
    public function administrators_list($id = null)
    {
        if ($id == 0) {
            $where = ['staffs.account_type' => 'Administrator', 'staffs.deleted_at' => null];
        } else {
            $where = ['staffs.account_type' => 'Administrator', 'staffs.id' => $id];
        }
        $administrators = $this->staff
            ->select('staffs.staffID, staffs.account_type, staffs.staff_name, staffs.mobile, staffs.email, staffs.address, staffs.photo, staffs.access_status, positions.position, departments.department_name, branches.branch_name, staffs.id, staffs.gender')
            ->join('branches', 'branches.id = staffs.branch_id', 'left')
            ->join('positions', 'positions.id = staffs.position_id', 'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->where($where);

        return DataTable::of($administrators)
            ->add('checkbox', function ($administrator) {
                return '<div class=""><input type="checkbox" class="data-check" value="' . $administrator->id . '"></div>';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('photo', function ($administrator) {
                if (file_exists("uploads/staffs/admins/passports/" . $administrator->photo) && $administrator->photo) {
                    $photo = '
                    <a href="javascript:void(0)" onclick="view_administrator_photo(' . "'" . $administrator->id . "'" . ')" title="' . ucwords($administrator->staff_name) . '"><img src="' . base_url('uploads/staffs/admins/passports/' . $administrator->photo) . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                } else {
                    $photo = '
                    <a href="javascript:void(0)" onclick="view_administrator_photo(' . "'" . $administrator->id . "'" . ')" title="no photo"><img src="' . base_url('assets/dist/img/nophoto.jpg') . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                }
                return '<div class="products">
                ' . $photo . '
                    <div>
                        <h6>' . strtoupper(ucwords($administrator->staff_name)) . '</h6>
                        <span>' . $administrator->staffID . '</span>	
                    </div>	
                </div>';
            })
            ->add('action', function ($administrator) {
                if (strtolower($administrator->access_status) == 'active') {
                    $text = "info";
                    $statusBtn = '<div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="edit_staffStatus(' . "'" . $administrator->id . "'" . ',' . "'" . ucwords($administrator->staff_name) . "'" . ')" title="de-activate '. ucwords($administrator->staff_name).'" class="dropdown-item">
                            <i class="fas fa-user-slash text-danger"></i> De-activate '. ucwords($administrator->staff_name).'
                        </a>';
                } else {
                    $text = "danger";
                    $statusBtn = '<div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="edit_staffStatus(' . "'" . $administrator->id . "'" . ',' . "'" . ucwords($administrator->staff_name) . "'" . ')" title="activate '. ucwords($administrator->staff_name).'" class="dropdown-item">
                        <i class="fas fa-user-check text-success"></i> Activate '. ucwords($administrator->staff_name).'
                        </a>';
                }

                // show buttons based on user permissions
                $actions = '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">';
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'view')) {
                    $actions .= '<a href="javascript:void(0)" onclick="view_administrator(' . "'" . $administrator->id . "'" . ')" title="view '. ucwords($administrator->staff_name).'" class="dropdown-item"><i class="fas fa-eye text-success"></i> View '. ucwords($administrator->staff_name).'</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
                    $actions .= $statusBtn . '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="edit_administrator(' . "'" . $administrator->id . "'" . ')" title="edit '. ucwords($administrator->staff_name).'" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit '. ucwords($administrator->staff_name).'</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="delete_administrator(' . "'" . $administrator->id . "'" . ',' . "'" . ucwords($administrator->staff_name) . "'" . ')" title="delete '. ucwords($administrator->staff_name).'" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete '. ucwords($administrator->staff_name).'</a>';
                }
                $actions .= ' 
                        </div>
                </div>';
                return $actions;
            })
            ->toJson(true);
    }
    public function staff_report($filter, $val = null, $from = null, $to = null)
    {
        switch (strtolower($filter)) {
            case "role":
                if ($from != 0 && $to == 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $from, 'staffs.account_type' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } elseif ($from == 0 && $to != 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $to, 'staffs.account_type' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } elseif ($from != 0 && $to != 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(staffs.created_at, "%Y-%m-%d") <=' => $to, 'staffs.account_type' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } else {
                    $where = ['staffs.account_type' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                }
                break;
            case "department":
                if ($from != 0 && $to == 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $from, 'departments.id' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } elseif ($from == 0 && $to != 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $to, 'departments.id' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } elseif ($from != 0 && $to != 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(staffs.created_at, "%Y-%m-%d") <=' => $to, 'departments.id' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } else {
                    $where = ['departments.id' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                }
                break;
            case "position":
                if ($from != 0 && $to == 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $from, 'positions.id' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } elseif ($from == 0 && $to != 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $to, 'positions.id' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } elseif ($from != 0 && $to != 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(staffs.created_at, "%Y-%m-%d") <=' => $to, 'positions.id' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } else {
                    $where = ['positions.id' => $val, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                }
                break;
            default:
                if ($from != 0 && $to == 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $from, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } elseif ($from == 0 && $to != 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $to, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } elseif ($from != 0 && $to != 0) {
                    $where = ['DATE_FORMAT(staffs.created_at, "%Y-%m-%d") >=' => $from, 'DATE_FORMAT(staffs.created_at, "%Y-%m-%d") <=' => $to, 'account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                } else {
                    $where = ['account_type !=' => "Super Administrator", 'staffs.deleted_at' => Null];
                }
                break;
        }
        $staffs = $this->staff->select('staffs.staffID, staffs.account_type, staffs.staff_name, staffs.mobile, staffs.email, staffs.photo, staffs.access_status, positions.position, departments.department_name, branches.branch_name, staffs.id, staffs.gender')->join('branches', 'branches.id = staffs.branch_id', 'left')->join('positions', 'positions.id = staffs.position_id', 'left')->join('departments', 'departments.id = positions.department_id', 'left')->where($where);
        return DataTable::of($staffs)
            ->add('checkbox', function ($staff) {
                return '<div class=""><input type="checkbox" class="data-check" value="' . $staff->id . '"></div>';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('photo', function ($administrator) {
                if (file_exists("uploads/staffs/admins/passports/" . $administrator->photo) && $administrator->photo) {
                    $photo = '
                    <a href="javascript:void(0)" onclick="view_administrator_photo(' . "'" . $administrator->id . "'" . ')" title="' . ucwords($administrator->staff_name) . '"><img src="' . base_url('uploads/staffs/admins/passports/' . $administrator->photo) . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                } else {
                    $photo = '
                    <a href="javascript:void(0)" onclick="view_administrator_photo(' . "'" . $administrator->id . "'" . ')" title="no photo"><img src="' . base_url('assets/dist/img/nophoto.jpg') . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                }
                return '<div class="products">
                ' . $photo . '
                    <div>
                        <h6>' . strtoupper(ucwords($administrator->staff_name)) . '</h6>
                        <span>' . $administrator->staffID . '</span>	
                    </div>	
                </div>';
            })
            ->add('action', function ($staff) {
                if (strtolower($staff->access_status) == 'active') {
                    $text = "text-info";
                } else {
                    $text = "text-danger";
                }
                return '
                    <div class="text-center">
                        <a href="javascript:void(0)" onclick="view_staff(' . "'" . $staff->id . "'" . ')" title="view staff" class="' . $text . '"><i class="fas fa-eye"></i></a>
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
        $data = $this->staff
            ->select('staffs.*, branches.branch_name, branches.branch_email, branches.branch_mobile, branches.branch_address, branches.branch_code, positions.position, departments.department_name, departments.id as department_id')
            ->join('branches', 'branches.id = staffs.branch_id', 'left')
            ->join('positions', 'positions.id = staffs.position_id', 'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
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
        }
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
                $this->_validateAdministrator("add");
                $staffID = $this->settings->generateUniqueNo('administrator');
                $position_id = trim($this->request->getVar('position_id'));
                $password =  $this->settings->generateRandomNumbers(8);
                // position data
                $positionRow = $this->position->find($position_id);
                $mobile = trim($this->request->getVar('mobile_full'));
                $alt_mobile = trim($this->request->getVar('alternate_mobile_full'));
                // administrator data to employee table
                $data = [
                    'staffID' => $staffID,
                    'reg_date' => trim($this->request->getVar('reg_date')),
                    'staff_name' => trim($this->request->getVar('staff_name')),
                    'mobile' => trim(preg_replace('/^0/', '+256', $mobile)),
                    'alternate_mobile' => trim(preg_replace('/^0/', '+256', $alt_mobile)),
                    'email' => trim($this->request->getVar('email')),
                    'address' => trim($this->request->getVar('address')),
                    'id_type' => trim($this->request->getVar('id_type')),
                    'account_type' => "Administrator",
                    'id_expiry_date' => trim($this->request->getVar('id_expiry')),
                    'id_number' => trim(strtoupper($this->request->getVar('id_number'))),
                    'nationality' => trim($this->request->getVar('nationality')),
                    'position_id' => $position_id,
                    'branch_id' => trim($this->request->getVar('branch_id')),
                    'qualifications' => trim($this->request->getVar('qualifications')),
                    'salary_scale' => trim($this->request->getVar('salary_scale')),
                    'bank_name' => trim($this->request->getVar('bank_name')),
                    'bank_branch' => trim($this->request->getVar('bank_branch')),
                    'bank_account' => trim($this->request->getVar('bank_account')),
                    'gender' => trim($this->request->getVar('gender')),
                    'marital_status' => trim($this->request->getVar('marital_status')),
                    'religion' => trim($this->request->getVar('religion')),
                    'date_of_birth' => trim($this->request->getVar('date_of_birth')),
                    'appointment_type' => trim($this->request->getVar('appointment_type')),
                    'officer_staff_id' => trim(($this->userRow['staff_id']) ? $this->userRow['staff_id'] : " "),
                    'account_id' => $this->userRow['account_id'],
                ];
                // administrator data to user table
                $userData = [
                    'branch_id' => trim($this->request->getVar('branch_id')),
                    'name' => trim($this->request->getVar('staff_name')),
                    'email' => trim($this->request->getVar('email')),
                    'account_type' => "Administrator",
                    'mobile' => trim(preg_replace('/^0/', '+256', $mobile)),
                    'permissions' => $positionRow['permissions'],
                    'address' => trim($this->request->getVar('address')),
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'access_status' => 'active',
                    'account_id' => $this->userRow['account_id'],
                ];
                if (!empty($_FILES['photo']['name'])) {
                    $upload = $this->settings->_doUploadPhoto("admin", $this->request->getFile('photo'));
                    $data['photo'] = $upload;
                    $sourceFilePath = "uploads/staffs/admins/passports/" . $upload;
                    $destinationFilePath = "uploads/users/" . $upload;
                    copy($sourceFilePath, $destinationFilePath);
                    $userData['photo'] = $upload;
                }
                if (!empty($_FILES['id_photo_front']['name'])) {
                    $idFront = $this->settings->_doUploadIdPhoto("admin", 'front',  $this->request->getFile('id_photo_front'));
                    $data['id_photo_front'] = $idFront;
                }
                if (!empty($_FILES['id_photo_back']['name'])) {
                    $idBack = $this->settings->_doUploadIdPhoto("admin", 'back',  $this->request->getFile('id_photo_back'));
                    $data['id_photo_back'] = $idBack;
                }
                if (!empty($_FILES['signature']['name'])) {
                    $sign = $this->settings->_doUploadSignature("admin",  $this->request->getFile('signature'));
                    $data['signature'] = $sign;
                }
                $insert = $this->staff->insert($data);
                $index = 1;
            } else {
                if (!empty($_FILES['file']['name']) && !empty($this->request->getVar('branchID')) && !empty($this->request->getVar('positionID'))) {
                    $position_id = trim($this->request->getVar('position_id'));
                    # get position information
                    $positionRow = $this->position->find($position_id);
                    # check the position permission
                    if ($positionRow) {
                        # code...
                        $permission = $positionRow['permissions'];
                    } else {
                        $permission = serialize('viewDashboard');
                    }
                    # get uploaded file extension
                    $path_parts = pathinfo($_FILES["file"]["name"]);
                    $ext = $path_parts['extension'];
                    # check whether the uploaded file extension matches with csv
                    if ($ext == 'csv') {
                        $file = $this->request->getFile("file");
                        $file_name = $file->getTempName();
                        $file_data = array_map('str_getcsv', file($file_name));
                        if (count($file_data) > 0) {
                            $staff = [];
                            foreach ($file_data as $key => $column) {
                                # ignore the column headers
                                if ($key == 0) {
                                    continue;
                                }
                                # ignore empty row in excel sheets
                                if ((string) $column[0] != '0' && empty($column[0])) {
                                    continue;
                                }

                                # check the staff email existence
                                $account = $this->staff->where([
                                    'email' => trim($column[3])
                                ])->orWhere('mobile', trim($column[1]))->countAllResults();

                                # ignore staff with the same email
                                if ($account) {
                                    continue;
                                }

                                # set phone number and the alternate phone number to null
                                $mobile = $alternate_no = null;
                                # check the phone number
                                if (!empty(trim($column[1]))) {
                                    # check whether the phone number starts with 0 or + characters
                                    if ((substr($column[1], 0, 1) == '0') ||
                                        (substr($column[1], 0, 1) == '+')
                                    ) {
                                        # replace +256 with phone number that starts with 0
                                        $mobile = preg_replace('/^0/', '+256', trim($column[1]));
                                    } else {
                                        # add +256 to the phone number since it has no leading 0
                                        $mobile = '+256' . $column[1];
                                    }
                                }

                                # check the alternate phone number
                                if (!empty(trim($column[2]))) {
                                    # check whether the phone number starts with 0 or + characters
                                    if ((substr($column[2], 0, 1) == '0') ||
                                        (substr($column[2], 0, 1) == '+')
                                    ) {
                                        # replace +256 with phone number that starts with 0
                                        $alternate_no = preg_replace('/^0/', '+256', trim($column[2]));
                                    } else {
                                        # add +256 to the phone number since it has no leading 0
                                        $alternate_no = '+256' . $column[2];
                                    }
                                }

                                # generate password for each staff
                                $password =  $this->settings->generateRandomNumbers(8);

                                # generate account number for each admin
                                $reg_date = trim($column[20]);
                                $staffID = $this->settings->generateUniqueNo('administrator', $reg_date);
                                $email = trim($column[3]);

                                $staff = array(
                                    'staffID' => $staffID,
                                    'account_type' => "Administrator",
                                    'access_status' => 'Active',
                                    'branch_id' => trim($this->request->getVar('branchID')),
                                    'position_id' => trim($this->request->getVar('positionID')),
                                    'staff_name' => trim($column[0]),
                                    'mobile' => trim($mobile),
                                    'alternate_mobile' => trim($alternate_no),
                                    'email' => trim($column[3]),
                                    'gender' => trim($column[4]),
                                    'marital_status' => trim($column[5]),
                                    'religion' => trim($column[6]),
                                    'nationality' => trim($column[7]),
                                    'date_of_birth' => date('Y-m-d', $column[8]),
                                    'address' => trim($column[9]),
                                    'id_type' => trim($column[10]),
                                    'id_number' => trim(strtoupper($column[11])),
                                    'id_expiry_date' => date('Y-m-d', $column[12]),
                                    'qualifications' => trim($column[13]),
                                    'salary_scale' => trim($column[14]),
                                    'bank_name' => trim($column[15]),
                                    'bank_branch' => trim($column[16]),
                                    'bank_account' => trim($column[17]),
                                    'appointment_type' => trim($column[18]),
                                    'account_type' => trim($column[19]),
                                    'reg_date' => trim($column[20]),
                                    'officer_staff_id' => trim(($this->userRow['staff_id']) ? $this->userRow['staff_id'] : null)
                                );

                                # save the staff information
                                $staff_id  = $this->staff->insert($staff);

                                $userData = [
                                    'staff_id' => $staff_id,
                                    'branch_id' => trim($this->request->getVar('branchID')),
                                    'name' => trim($column[0]),
                                    'email' => trim($column[3]),
                                    'account_type' => "Administrator",
                                    'mobile' => trim(preg_replace('/^0/', '+256', $column[1])),
                                    'permissions' => $permission,
                                    'address' => trim($column[9]),
                                    'password' => password_hash($password, PASSWORD_DEFAULT),
                                    'access_status' => 'active',
                                ];

                                $saveUser = $this->user->insert($userData);

                                # send email
                                $checkInternet = $this->settings->checkNetworkConnection();
                                # check the email existence and email notify is enabled
                                if (!empty($email) && $checkInternet && $this->settingsRow['email']) {
                                    $subject = $this->title . " Registration";
                                    $message = $staff;
                                    $token = 'registration';
                                    $this->settings->sendMail($message, $subject, $token, $password, 'staff');
                                }
                                # check the phone number existence
                                if (!empty($mobile) && $checkInternet && $this->settingsRow['sms']) {
                                    # send sms
                                    $sms = $this->sendSMS([
                                        'mobile' => trim($mobile),
                                        'text' => 'Your Login for ' . strtoupper($this->settingsRow["system_abbr"]) . ': ' . $password
                                    ]);
                                }
                            }
                            # insert imported staff
                            # $insert = $this->staff->insertBatch($staff);
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

                    if ($this->request->getVar('branchID') == '') {
                        $data['inputerror'][] = 'branchID';
                        $data['error_string'][] = 'Branch is required!';
                        $data['status'] = FALSE;
                    }
                    if ($this->request->getVar('positionID') == '') {
                        $data['inputerror'][] = 'positionID';
                        $data['error_string'][] = 'Position is required!';
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
                $userData['staff_id'] = $insert;
                // insert into activity logs
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => $mode,
                    'description' => ucfirst($mode . ' ' . $index . ' ' . $this->title . ' records'),
                    'module' => $this->menu,
                    'referrer_id' => $insert,
                ];
                if ($mode == 'create') {
                    $saveUser = $this->user->insert($userData);
                    if ($saveUser) {
                        $activity = $this->insertActivityLog($activityData);
                        if ($activity) {

                            $checkInternet = $this->settings->checkNetworkConnection();
                            if ($checkInternet) {
                                # check the email existence and email notify is enabled
                                if (!empty($data['email']) && $this->settingsRow['email']) {
                                    $subject = $this->menu . " Registration";
                                    $message = $data;
                                    $token = 'registration';
                                    $this->settings->sendMail($message, $subject, $token, $password, 'staff');
                                }

                                # check the phone number existence and sms notify is enabled
                                if (!empty($data['mobile']) && $this->settingsRow['sms']) {
                                    # send sms
                                    $sms = $this->sendSMS([
                                        'mobile' => trim($data['mobile']),
                                        'text' => 'Your Login for ' . strtoupper($this->settingsRow["system_abbr"]) . ': ' . $password
                                    ]);
                                }

                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => $this->title . ' ' . $mode . ' successfully. Email Sent'
                                ];
                                return $this->respond($response);
                                exit;
                            } else {
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => $this->title . ' ' . $mode . ' successfully'
                                ];
                                return $this->respond($response);
                                exit;
                            }
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
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record ' . $mode . ' successfully, userFailed',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record(s) ' . $mode . ' successfully. '
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
    public function update_administrator($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateAdministrator("update");
                $position_id = trim($this->request->getVar('position_id'));
                // position data
                $positionRow = $this->position->find($position_id);
                $mobile = trim($this->request->getVar('mobile_full'));
                $alt_mobile = trim($this->request->getVar('alternate_mobile_full'));
                $administratorRow = $this->staff->find($id);
                $userRow = $this->user->where('staff_id', $id)->first();
                // administrator data to employee table
                $data = [
                    'staff_name' => trim($this->request->getVar('staff_name')),
                    'reg_date' => trim($this->request->getVar('reg_date')),
                    'mobile' => trim(preg_replace('/^0/', '+256', $mobile)),
                    'alternate_mobile' => trim(preg_replace('/^0/', '+256', $alt_mobile)),
                    'email' => trim($this->request->getVar('email')),
                    'address' => trim($this->request->getVar('address')),
                    'id_type' => trim($this->request->getVar('id_type')),
                    'id_expiry_date' => trim($this->request->getVar('id_expiry')),
                    'id_number' => trim(strtoupper($this->request->getVar('id_number'))),
                    'position_id' => $position_id,
                    'nationality' => trim($this->request->getVar('nationality')),
                    'branch_id' => trim($this->request->getVar('branch_id')),
                    'qualifications' => trim($this->request->getVar('qualifications')),
                    'salary_scale' => trim($this->request->getVar('salary_scale')),
                    'bank_name' => trim($this->request->getVar('bank_name')),
                    'bank_branch' => trim($this->request->getVar('bank_branch')),
                    'bank_account' => trim($this->request->getVar('bank_account')),
                    'gender' => trim($this->request->getVar('gender')),
                    'marital_status' => trim($this->request->getVar('marital_status')),
                    'religion' => trim($this->request->getVar('religion')),
                    'date_of_birth' => trim($this->request->getVar('date_of_birth')),
                    'appointment_type' => trim($this->request->getVar('appointment_type')),
                ];
                // administrator data to user table
                $userData = [
                    'branch_id' => trim($this->request->getVar('branch_id')),
                    'name' => trim($this->request->getVar('staff_name')),
                    'email' => trim($this->request->getVar('email')),
                    'mobile' => $mobile,
                    'permissions' => $positionRow['permissions'],
                    'address' => trim($this->request->getVar('address')),
                    'access_status' => 'active',
                ];
                # check whether the photo has been uploaded
                if (!empty($_FILES['photo']['name'])) {
                    $upload_photo = $this->settings->_doUploadPhoto("admin", $this->request->getFile('photo'));
                    copy("uploads/staffs/admins/passports/" . $upload_photo, "uploads/users/" . $upload_photo);
                    # get user information
                    if (file_exists("uploads/staffs/admins/passports/" . $administratorRow['photo']) && $administratorRow['photo']) {
                        # delete or remove the previous photo
                        unlink("uploads/staffs/admins/passports/" . $administratorRow['photo']);
                    }
                    if ($userRow) {
                        if (file_exists("uploads/users/" . $userRow['photo']) && $userRow['photo']) {
                            # delete or remove the previous photo
                            unlink("uploads/users/" . $userRow['photo']);
                        }
                    }
                    # save the new photo
                    $data['photo'] = $upload_photo;
                    $userData['photo'] = $upload_photo;
                }
                if (!empty($_FILES['id_photo_front']['name'])) {
                    $upload_idFront = $this->settings->_doUploadIdPhoto("admin", 'front', $this->request->getFile('id_photo_front'));
                    if (file_exists("uploads/staffs/admins/ids/front/" . $administratorRow['id_photo_front']) && $administratorRow['id_photo_front']) {
                        # delete or remove the previous photo
                        unlink("uploads/staffs/admins/ids/front/" . $administratorRow['id_photo_front']);
                    }
                    $data['id_photo_front'] = $upload_idFront;
                }
                if (!empty($_FILES['id_photo_back']['name'])) {
                    $upload_idBack = $this->settings->_doUploadIdPhoto("admin", 'back', $this->request->getFile('id_photo_back'));
                    if (file_exists("uploads/staffs/admins/ids/back/" . $administratorRow['id_photo_front']) && $administratorRow['id_photo_back']) {
                        # delete or remove the previous photo
                        unlink("uploads/staffs/admins/ids/back/" . $administratorRow['id_photo_back']);
                    }
                    $data['id_photo_back'] = $upload_idBack;
                }
                if (!empty($_FILES['signature']['name'])) {
                    $upload_sign = $this->settings->_doUploadSignature("admin", $this->request->getFile('signature'));
                    if (file_exists("uploads/staffs/admins/signatures/" . $administratorRow['signature']) && $administratorRow['signature']) {
                        unlink("uploads/staffs/admins/signatures/" . $administratorRow['signature']);
                    }
                    $data['signature'] = $upload_sign;
                }
                $update = $this->staff->update($id, $data);
                if ($update) {
                    if ($userRow) {
                        $userUpdate = $this->user->update($userRow['id'], $userData);
                    } else {
                        $userUpdate = true;
                    }
                    if ($userUpdate) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'update',
                            'description' => ucfirst('updated ' . $this->title . ', ' . $data['staff_name']),
                            'module' => $this->menu,
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
                            'status'   => 200,
                            'error'    => null,
                            'messages' => 'Administrator updated User record updated failed'
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
    public function update_staffStatus($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            $data = $this->staff->find($id);
            if ($data) {
                if (strtolower($data['access_status']) == 'active') {
                    $status = "Inactive";
                    $edit = $this->staff->update($id, ['access_status' => $status]);
                } else {
                    $status = "Active";
                    $edit = $this->staff->update($id, ['access_status' => $status]);
                }
                if ($edit) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'update',
                        'description' => ucfirst('updated ' . $this->title . ', access status, ' . $data['staff_name']),
                        'module' => $this->menu,
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $checkInternet = $this->settings->checkNetworkConnection();
                        if ($checkInternet) {
                            # check the email existence and email notify is enabled
                            if (!empty($data['email']) && $this->settingsRow['email']) {
                                $subject = $this->menu . " Access Status";
                                $data['status'] = $status;
                                $message = $data;
                                $token = 'access_status';
                                $this->settings->sendMail($message, $subject, $token, 'staff');
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => $this->title . " record access status updated successfully. Email Sent"
                                ];
                                return $this->respond($response);
                                exit;
                            } else {
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => $this->title . " record access status updated successfully. No Internet"
                                ];
                                return $this->respond($response);
                                exit;
                            }
                        } else {
                            $response = [
                                'status'   => 200,
                                'error'    => null,
                                'messages' => $this->title . ' record access status updated successfully',
                            ];
                            return $this->respond($response);
                            exit;
                        }
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record access status updated successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Update Failed',
                        'messages' => 'Updating ' . $this->title . ' record  access status failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status'   => 404,
                    'error'    => 'Not Found',
                    'messages' => 'The requested ' . $this->title . ' resource not found',
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
            $data = $this->staff->find($id);
            if ($data) {
                # delete or remove t photo
                if (file_exists("uploads/staffs/admins/passports/" . $data['photo']) && $data['photo']) {
                    unlink("uploads/staffs/admins/passports/" . $data['photo']);
                }
                if (file_exists("uploads/staffs/admins/ids/front/" . $data['id_photo_front']) && $data['id_photo_front']) {
                    unlink("uploads/staffs/admins/ids/front/" . $data['id_photo_front']);
                }
                if (file_exists("uploads/staffs/admins/ids/back/" . $data['id_photo_back']) && $data['id_photo_back']) {
                    unlink("uploads/staffs/admins/ids/back/" . $data['id_photo_back']);
                }
                if (file_exists("uploads/staffs/admins/signatures/" . $data['signature']) && $data['signature']) {
                    unlink("uploads/staffs/admins/signatures/" . $data['signature']);
                }
                $userRow = $this->user->where(['staff_id' => $id])->first();
                if ($userRow) {
                    if (file_exists("uploads/users/" . $userRow['photo']) && $userRow['photo']) {
                        unlink("uploads/users/" . $userRow['photo']);
                    }
                    $this->user->delete($userRow['id']);
                }
                $delete = $this->staff->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', ' . $data['staff_name']),
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

    public function ajax_bulky_delete()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->staff->find($id);
                if ($data) {
                    $userRow = $this->user->where(['staff_id' => $id])->first();
                    # delete or remove photo
                    if (file_exists("uploads/staffs/admins/passports/" . $data['photo']) && $data['photo']) {
                        unlink("uploads/staffs/admins/passports/" . $data['photo']);
                    }
                    if (file_exists("uploads/staffs/admins/ids/front/" . $data['id_photo_front']) && $data['id_photo_front']) {
                        unlink("uploads/staffs/admins/ids/front/" . $data['id_photo_front']);
                    }
                    if (file_exists("uploads/staffs/admins/ids/back/" . $data['id_photo_back']) && $data['id_photo_back']) {
                        unlink("uploads/staffs/admins/ids/back/" . $data['id_photo_back']);
                    }
                    if (file_exists("uploads/staffs/admins/signatures/" . $data['signature']) && $data['signature']) {
                        unlink("uploads/staffs/admins/signatures/" . $data['signature']);
                    }
                    if ($userRow) {
                        if (file_exists("uploads/users/" . $userRow['photo']) && $userRow['photo']) {
                            unlink("uploads/users/" . $userRow['photo']);
                        }
                        $this->user->delete($userRow['id']);
                    }
                    $delete = $this->staff->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['staff_name']),
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
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' record(s) deleted successfully. loggingFailed'
                ];
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' records!',
            ];
        }
        return $this->respond($response);
    }

    /**
     * validate form inputs
     */
    private function _validateAdministrator($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        # get the staff information by id
        $administratorInfo = $this->staff->find($this->request->getVar('id'));
        # trimmed the white space between between country code and phone number
        $mobile = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('mobile_country_code'),
            'phone' => $this->request->getVar('mobile')
        ]);

        # trimmed the white space between between country code and phone number
        $alternate_mobile = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('alternate_mobile_country_code'),
            'phone' => $this->request->getVar('alternate_mobile')
        ]);
        # validate the staff registration form
        if ($this->request->getVar('staff_name') == '') {
            $data['inputerror'][] = 'staff_name';
            $data['error_string'][] = 'Full name is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('staff_name'))) {
            $name = $this->request->getVar('staff_name');
            if ($this->settings->validateName($name) == TRUE) {
                if (strlen(trim($name)) < 5) {
                    $data['inputerror'][] = 'staff_name';
                    $data['error_string'][] = 'Minimum 5 letters required!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($name) == FALSE) {
                $data['inputerror'][] = 'staff_name';
                $data['error_string'][] = 'Valid full name is required!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('email') == '') {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Email required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('email'))) {
            # check whether the email is valid
            if ($this->settings->validateEmail($this->request->getVar('email')) == FALSE) {
                $data['inputerror'][] = 'email';
                $data['error_string'][] = 'Valid email is required!';
                $data['status'] = FALSE;
            }
            # get staff information by email address
            $staff = $this->staff->where(['email' => $this->request->getVar('email')])->first();

            if ($method == 'add' && $staff) {
                $data['inputerror'][] = 'email';
                $data['error_string'][] = $this->request->getVar('email') . ' already added!';
                $data['status'] = FALSE;
            }
            if ($method == 'update' && $administratorInfo && $administratorInfo['email'] != $this->request->getVar('email')) {
                if ($staff) {
                    $data['inputerror'][] = 'email';
                    $data['error_string'][] = $this->request->getVar('email') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('mobile') == '') {
            $data['inputerror'][] = 'mobile';
            $data['error_string'][] = 'Administrator Mobile is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('mobile'))) {
            # validate the phone number
            $this->validPhoneNumber([
                'phone' => $mobile,
                'input' => 'mobile',
            ]);
            # check the staff information by phone number
            $staff = $this->staff->where(['mobile' => $mobile])->first();
            if ($method == 'add' && $staff) {
                $data['inputerror'][] = 'mobile';
                $data['error_string'][] = $mobile . ' already added!';
                $data['status'] = FALSE;
            }
            if ($method == 'update' && $administratorInfo && $administratorInfo['mobile'] != $mobile) {
                # check the phone number existence
                if ($staff) {
                    $data['inputerror'][] = 'mobile';
                    $data['error_string'][] = $mobile . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        if (!empty($this->request->getVar('alternate_mobile'))) {
            # validate the phone number
            $this->validPhoneNumber([
                'phone' => $alternate_mobile,
                'input' => 'alternate_mobile',
            ]);
            # check the staff information by phone number
            $staff = $this->staff->where(['alternate_mobile' => $alternate_mobile])->first();
            if ($method == 'add' && $staff) {
                $data['inputerror'][] = 'alternate_mobile';
                $data['error_string'][] = $alternate_mobile . ' already added!';
                $data['status'] = FALSE;
            }
            if ($method == 'update' && $administratorInfo) {
                if ($administratorInfo['alternate_mobile'] != $alternate_mobile && $staff) {
                    $data['inputerror'][] = 'alternate_mobile';
                    $data['error_string'][] = $alternate_mobile . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('address') == '') {
            $data['inputerror'][] = 'address';
            $data['error_string'][] = 'Administrator address is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('address'))) {
            $address = $this->request->getVar('address');
            if (strlen(trim($address)) < 4) {
                $data['inputerror'][] = 'address';
                $data['error_string'][] = 'Minimum 4 digits required [' . strlen(trim($address)) . ']!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('date_of_birth') == '') {
            $data['inputerror'][] = 'date_of_birth';
            $data['error_string'][] = 'Administrator D.O.B is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('reg_date') == '') {
            $data['inputerror'][] = 'reg_date';
            $data['error_string'][] = 'Registration Date is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('id_type') == '') {
            $data['inputerror'][] = 'id_type';
            $data['error_string'][] = 'ID type is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('id_number') == '') {
            $data['inputerror'][] = 'id_number';
            $data['error_string'][] = 'NIN Number is required!';
            $data['status'] = FALSE;
        }
        # check whether the ID type and number is not null
        if (!empty($this->request->getVar('id_type')) && !empty($this->request->getVar('id_number'))) {
            $idType = $this->request->getVar('id_type');
            $nin = $this->request->getVar('id_number');
            $staff = $this->staff->where(['id_number' => $this->request->getVar('id_number')])->first();
            # validate the id number to take only digits and alphabets
            if (!preg_match("/^[0-9a-zA-Z']*$/", $nin)) {
                $data['inputerror'][] = 'id_number';
                $data['error_string'][] = 'Only letters and numbers allowed!';
                $data['status'] = FALSE;
            }
            # check the id type matches the National ID and validate the id number length
            if (strcmp($idType, 'National ID') == 0 && strlen(trim($nin)) != 14) {
                $data['inputerror'][] = 'id_number';
                $data['error_string'][] = '14 characters accepted [' . strlen(trim($nin)) . ']!';
                $data['status'] = FALSE;
            }
            # check the id type matches the Passport and validate the id number length
            if (strcmp($idType, 'Passport') == 0 && strlen(trim($nin)) != 8) {
                $data['inputerror'][] = 'id_number';
                $data['error_string'][] = '8 characters accepted [' . strlen(trim($nin)) . ']!';
                $data['status'] = FALSE;
            }
            # check the id type matches the Driver License and validate the id number length
            if (strcmp($idType, 'Driver License') == 0 && strlen(trim($nin)) != 13) {
                $data['inputerror'][] = 'id_number';
                $data['error_string'][] = '13 characters accepted [' . strlen(trim($nin)) . ']!';
                $data['status'] = FALSE;
            }

            if ($method == 'add') {
                if ($staff) {
                    $data['inputerror'][] = 'id_number';
                    $data['error_string'][] = $this->request->getVar('id_number') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
            if ($method == 'update' && $administratorInfo && $administratorInfo['id_number'] != $this->request->getVar('id_number')) {
                if ($staff) {
                    $data['inputerror'][] = 'id_number';
                    $data['error_string'][] = $this->request->getVar('id_number') . ' already added!';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('department_id') == '') {
            $data['inputerror'][] = 'department_id';
            $data['error_string'][] = 'Department is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('department_id'))) {
            if (
                $this->request->getVar('position_id') == '' ||
                $this->request->getVar('position_id') == 0
            ) {
                $data['inputerror'][] = 'position_id';
                $data['error_string'][] = 'Position is required!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('branch_id') == '') {
            $data['inputerror'][] = 'branch_id';
            $data['error_string'][] = 'Branch is required!';
            $data['status'] = FALSE;
        }
        /*
        if ($this->request->getVar('qualifications') == '') {
            $data['inputerror'][] = 'qualifications';
            $data['error_string'][] = 'Qualification is required!';
            $data['status'] = FALSE;
        }
        */
        if (!empty($this->request->getVar('qualifications'))) {
            $branch = $this->request->getVar('qualifications');
            if (!preg_match("/^[0-9a-zA-Z ']*$/", $branch)) {
                $data['inputerror'][] = 'qualifications';
                $data['error_string'][] = 'Only letters and numbers allowed!';
                $data['status'] = FALSE;
            }
            if (strlen(trim($branch)) < 4) {
                $data['inputerror'][] = 'qualifications';
                $data['error_string'][] = 'Minimum 4 letters required!';
                $data['status'] = FALSE;
            }
        }
        // if ($this->request->getVar('salary_scale') == '') {
        //     $data['inputerror'][] = 'salary_scale';
        //     $data['error_string'][] = 'Salary scale is required!';
        //     $data['status'] = FALSE;
        // }
        if (!empty($this->request->getVar('salary_scale'))) {
            $sal = $this->request->getVar('salary_scale');
            if (!preg_match("/^[0-9']*$/", $sal)) {
                $data['inputerror'][] = 'salary_scale';
                $data['error_string'][] = 'Only numbers allowed!';
                $data['status'] = FALSE;
            }
            if (strlen(trim($sal)) < 4) {
                $data['inputerror'][] = 'salary_scale';
                $data['error_string'][] = 'Minimum 4 letters required!';
                $data['status'] = FALSE;
            }
        }
        // if ($this->request->getVar('bank_name') == '') {
        //     $data['inputerror'][] = 'bank_name';
        //     $data['error_string'][] = 'Salary scale is required!';
        //     $data['status'] = FALSE;
        // }
        if (!empty($this->request->getVar('bank_name'))) {
            $bank = $this->request->getVar('bank_name');
            if (!preg_match("/^[0-9a-zA-Z ']*$/", $bank)) {
                $data['inputerror'][] = 'bank_name';
                $data['error_string'][] = 'Only letters and numbers allowed!';
                $data['status'] = FALSE;
            }
            if (strlen(trim($bank)) < 4) {
                $data['inputerror'][] = 'bank_name';
                $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($bank)) . ']!';
                $data['status'] = FALSE;
            }
        }
        // if ($this->request->getVar('bank_branch') == '') {
        //     $data['inputerror'][] = 'bank_branch';
        //     $data['error_string'][] = 'Bank Branch is required!';
        //     $data['status'] = FALSE;
        // }
        if (!empty($this->request->getVar('bank_branch'))) {
            $bb = $this->request->getVar('bank_branch');
            if (!preg_match("/^[0-9a-zA-Z ']*$/", $bb)) {
                $data['inputerror'][] = 'bank_branch';
                $data['error_string'][] = 'Only letters and numbers allowed!';
                $data['status'] = FALSE;
            }
            if (strlen(trim($bb)) < 4) {
                $data['inputerror'][] = 'bank_branch';
                $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($bb)) . ']!';
                $data['status'] = FALSE;
            }
        }
        // if ($this->request->getVar('bank_account') == '') {
        //     $data['inputerror'][] = 'bank_account';
        //     $data['error_string'][] = 'Bank Account number is required!';
        //     $data['status'] = FALSE;
        // }
        if (!empty($this->request->getVar('bank_account'))) {
            $bankAcc = $this->request->getVar('bank_account');
            if (!preg_match("/^[0-9']*$/", $bankAcc)) {
                $data['inputerror'][] = 'bank_account';
                $data['error_string'][] = 'Only numbers allowed!';
                $data['status'] = FALSE;
            }
            if (strlen(trim($bankAcc)) < 13) {
                $data['inputerror'][] = 'bank_account';
                $data['error_string'][] = 'Minimum 13 letters required [' . strlen(trim($bankAcc)) . ']!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('gender') == '') {
            $data['inputerror'][] = 'gender';
            $data['error_string'][] = 'Gender is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('nationality') == '') {
            $data['inputerror'][] = 'nationality';
            $data['error_string'][] = 'Nationality is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('marital_status') == '') {
            $data['inputerror'][] = 'marital_status';
            $data['error_string'][] = 'Marital status is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('religion') == '') {
            $data['inputerror'][] = 'religion';
            $data['error_string'][] = 'Religion is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('appointment_type') == '') {
            $data['inputerror'][] = 'appointment_type';
            $data['error_string'][] = 'Appointment type is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('appointment_type'))) {
            $appT = $this->request->getVar('appointment_type');
            if (!preg_match("/^['a-zA-Z-' ]*$/", $appT)) {
                $data['inputerror'][] = 'appointment_type';
                $data['error_string'][] = 'Only letters allowed!';
                $data['status'] = FALSE;
            }
            if (strlen(trim($appT)) < 4) {
                $data['inputerror'][] = 'appointment_type';
                $data['error_string'][] = 'Minimum 4 letters required [' . strlen(trim($appT)) . ']!';
                $data['status'] = FALSE;
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
