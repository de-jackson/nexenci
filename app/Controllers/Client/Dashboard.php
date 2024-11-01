<?php

namespace App\Controllers\Client;

use App\Controllers\Client\MainController;

class Dashboard extends MainController
{

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Dashboard';
        $this->title = 'Dashboard';
    }

    public function index()
    {
        $userActivity = $this->userActivity->where(['client_id' => $this->userRow['id']])->orderBy('id', 'desc')->paginate(5);
        $logs = $this->userLog->where(['client_id' => $this->userRow['id']])
            ->orderBy('id', 'desc')->paginate(3);
        # get the loan products
        $products = $this->loanProduct->where([
            'deleted_at' => NULL,
            'status' => 'Active'
        ])->orderBy('id', 'desc')->paginate();

        # loan repayment information
        $repayments = $this->entry
            ->select('particulars.particular_name, payments.particular_name as payment_method, disbursements.disbursement_code, loanproducts.product_name, account_types.code, disbursements.actual_repayment,
            account_types.name as account_type_name, 
            entrytypes.type, entries.date, SUM(entries.amount) as total, entries.ref_id, entries.status, clients.name, clients.account_no, staffs.staff_name, entries.created_at,entries.account_typeId, entries.id, entries.amount')
            ->join('clients', 'clients.id = entries.client_id', 'left')
            ->join('staffs', 'staffs.id = entries.staff_id', 'left')
            ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
            ->join('disbursements', 'disbursements.id = entries.disbursement_id', 'left')
            ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')
            ->where([
                'entries.account_typeId' => 3,
                'entries.deleted_at' => null,
                'entries.client_id' => $this->userRow['id']
            ])->groupBy('entries.ref_id')->orderBy('entries.id', 'asc')->paginate();

        return view('client/dashboard/index', [
            'title' => $this->title,
            'menu' => $this->menu,
            'settings' => $this->settingsRow,
            'user' => $this->userRow,
            'userMenu' => $this->load_menu(),
            'logs' => $logs,
            # get the total loan
            'totalLoan' => $this->clientCounter('totalLoan'),
            'totalLoanPaid' => $this->clientCounter('totalLoanPaid'),
            'totalLoanBalance' => $this->clientCounter('totalLoanBalance'),
            // 'applicationsCounter' => $this->getTableCounts('applications'),
            // 'disbursementsCounter' => $this->getTableCounts('disbursements'),
            # loan products
            'products' => $products,
            # disbursement
            'disbursements' => $this->getLoanDisbursements([
                'disbursements.client_id' => $this->userRow['id'],
                'disbursements.class !=' => 'Cleared'
            ]),

            # loan repayment
            'repayments' => $repayments,

            # report
            'summary' => [
                'applications' => [
                    'pending' => $this->settings->countResults('loanapplications', [
                        'client_id' => $this->userRow['id'],
                        'status' => "Pending"
                    ]),
                    'processing' => $this->settings->countResults('loanapplications', [
                        'client_id' => $this->userRow['id'],
                        'status' => "Processing"
                    ]),
                    'declined' => $this->settings->countResults('loanapplications', [
                        'client_id' => $this->userRow['id'],
                        'status' => "Declined"
                    ]),
                    'disbursed' => $this->settings->countResults('loanapplications', [
                        'client_id' => $this->userRow['id'],
                        'status' => "Disbursed"
                    ]),
                    'cancelled' => $this->settings->countResults('loanapplications', [
                        'client_id' => $this->userRow['id'],
                        'status' => "Cancelled"
                    ]),
                ],
                'disbursements' => [
                    'running' => $this->settings->countResults('disbursements', [
                        'client_id' => $this->userRow['id'],
                        'class' => "Running"
                    ]),
                    'arrears' => $this->settings->countResults('disbursements', [
                        'client_id' => $this->userRow['id'],
                        'class' => "Arrears"
                    ]),
                    'cleared' => $this->settings->countResults('disbursements', [
                        'client_id' => $this->userRow['id'],
                        'class' => "Cleared"
                    ]),
                    'expired' => $this->settings->countResults('disbursements', [
                        'client_id' => $this->userRow['id'],
                        'class' => "Expired"
                    ]),
                    'defaulted' => $this->settings->countResults('disbursements', [
                        'client_id' => $this->userRow['id'],
                        'class' => "Defaulted"
                    ]),
                ],
                'counter' => [
                    'applications' => $this->countClientApplications($this->userRow['id'])
                ]
            ]

        ]);
    }

    public function profile()
    {
        return view('client/dashboard/profile', [
            'title' => 'Profile',
            'menu' => $this->menu,
            'settings' => $this->settingsRow,
            'user' => $this->userRow,
            'userMenu' => $this->load_menu(),
            // 'applicationsCounter' => $this->getTableCounts('applications'),
            // 'disbursementsCounter' => $this->getTableCounts('disbursements'),
            'disbursements' => $this->client_loans($this->userRow['id']),
        ]);
    }

    public function profileO()
    {
        $client = $this->client->select('clients.*, branches.branch_name, staffs.staffID, staffs.alternate_mobile, staffs.gender, staffs.marital_status, staffs.religion, staffs.nationality, staffs.date_of_birth, staffs.appointment_type')
            ->join('branches', 'branches.id = clients.branch_id', 'left')
            ->join('staffs', 'staffs.id = clients.staff_id', 'left')
            ->find($this->userRow['id']);
        # Get user logs history information
        $userLogs = $this->userLog->where(['client_id' => $this->userRow['id']])->orderBy('id', 'desc')->paginate(5);
        # Get user activity
        $userActivity = $this->userActivity->where(['client_id' => $this->userRow['id']])->orderBy('id', 'desc')->paginate(5);

        if ($client) {
            return view('client/dashboard/profile', [
                'title' => 'Profile',
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $client,
                'userMenu' => $this->load_menu(),
                'activities' => $userActivity,
                // 'applicationsCounter' => $this->getTableCounts('applications'),
                // 'disbursementsCounter' => $this->getTableCounts('disbursements'),
                'applications' => $this->clientCounter('applications'),
                'activityCount' => $this->settings->countResults('useractivities', [
                    'client_id' => $client['id']
                ]),
                'activityPager' => $this->userActivity->pager,
                'logs' => $userLogs,
                'logsCount' => $this->settings->countResults('userlogs', [
                    'client_id' => $client['id']
                ]),
                'logsPager' => $this->userLog->pager,
            ]);
        } else {
            session()->setFlashdata('failed', 'Page requested can not be found');
            return redirect()->to(base_url('client/dashboard'));
        }
    }

    public function change_password()
    {
        $this->validate_newPassword();
        $id = $this->request->getVar('id');
        $menu = $this->request->getVar('menu');
        $password = trim($this->request->getVar('confirmpassword'));
        $userRow = $this->client->find($id);
        if ($userRow) {
            $email = $this->userRow['email'];
            $data = [
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];
            $change = $this->user->update($id, $data);
            if ($change) {
                if (!empty($email)) {
                    $checkInternet = $this->settings->checkNetworkConnection();
                    if ($checkInternet) {
                        $subject = "Password Update";
                        $userRow['menu'] = $menu;
                        $message = $userRow;
                        $token = 'passwords';
                        $this->settings->sendMail($message, $subject, $token, $password);
                        $response = [
                            'status' => 200,
                            'error' => null,
                            'messages' => "Password changed successfully. Email Sent"
                        ];
                    } else {
                        $response = [
                            'status' => 200,
                            'error' => null,
                            'messages' => "Password changed successfully. No Internet"
                        ];
                    }
                } else {
                    $response = [
                        'status' => 200,
                        'error' => null,
                        'messages' => 'Password changed successfully',
                    ];
                }
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

    // update2-fa state
    public function two_factorAuth($id = null)
    {
        $userData = $this->client->find($id);
        if ($userData) {
            $fa = (strtolower($userData['2fa']) == 'true') ? 'False' : 'True';
            // echo json_encode($userData['2fa']); exit;
            $update = $this->client->update($id, ['2fa' => $fa]);
            if ($update) {
                // insert into activity logs
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'update',
                    'description' => ucfirst('updated ' . $userData['name'] . ' 2-factor Auth to: ' . $fa . ', user id: ' . $userData['id']),
                    'module' => strtolower('profile'),
                    'referrer_id' => $id,
                ];
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => "2-Factor Authentication updated to " . $fa . " successfully"
                    ];
                    return $this->respond($response);
                    exit;
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => '2-Factor Authentication updated to  ' . $fa . ' successfully. loggingFailed'
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Update Failed',
                    'messages' => 'Updating 2-Factor Authentication to ' . $fa . ' failed, try again later!',
                ];
                return $this->respond($response);
                exit;
            }
        } else{
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'No User Data is Found!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    private function validate_newPassword()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $id = $this->request->getVar('id');
        $userRow = $this->client->find($id);
        if ($userRow) {
            $password = $userRow['password'];
            if ($this->request->getVar('currentpassword') == '') {
                $data['inputerror'][] = 'currentpassword';
                $data['error_string'][] = 'Current Password is required!';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('currentpassword'))) {
                $pass = $this->request->getVar('currentpassword');
                if (!password_verify($pass, $password)) {
                    $data['inputerror'][] = 'currentpassword';
                    $data['error_string'][] = 'Current Password is wrong!';
                    $data['status'] = FALSE;
                }
            }

            if ($this->request->getVar('newpassword') == '') {
                $data['inputerror'][] = 'newpassword';
                $data['error_string'][] = 'New Password is required!';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('newpassword'))) {
                $pwd = $this->request->getVar('newpassword');
                if (strlen($pwd) < 8) {
                    $data['inputerror'][] = 'newpassword';
                    $data['error_string'][] = 'Password should be at least 8 characters long![' . strlen($pwd) . ']';
                    $data['status'] = FALSE;
                }
                if (!preg_match("/^[a-zA-Z0-9!@ ']*$/", $pwd)) {
                    $data['inputerror'][] = 'newpassword';
                    $data['error_string'][] = 'Illegal character. !@, Letters & numbers allowed!';
                    $data['status'] = FALSE;
                }
            }

            if ($this->request->getVar('confirmpassword') == '') {
                $data['inputerror'][] = 'confirmpassword';
                $data['error_string'][] = 'Confirm Password is required!';
                $data['status'] = FALSE;
            }
            if (!empty($this->request->getVar('confirmpassword'))) {
                $pwd = $this->request->getVar('confirmpassword');
                if (strlen($pwd) < 8) {
                    $data['inputerror'][] = 'confirmpassword';
                    $data['error_string'][] = 'Confirm Password should be at least 8 characters long![' . strlen($pwd) . ']';
                    $data['status'] = FALSE;
                }
                if (!preg_match("/^[a-zA-Z0-9!@ ']*$/", $pwd)) {
                    $data['inputerror'][] = 'confirmpassword';
                    $data['error_string'][] = 'Illegal character. !@, Letters & numbers allowed!';
                    $data['status'] = FALSE;
                }
            }

            if ($this->request->getVar('newpassword') != $this->request->getVar('confirmpassword')) {
                $data['inputerror'][] = 'confirmpassword';
                $data['error_string'][] = 'Passwords do not match!';
                $data['status'] = FALSE;
            }
        } else {
            $data['inputerror'][] = 'currentpassword';
            $data['error_string'][] = 'User Data not found!';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    /**
     * logout
     * @param NA
     */
    public function logout()
    {
        $userlog_id = session()->get('userlog_id');
        # check whether the session exit
        if (isset($userlog_id)) {
            # get user log information
            $userlog = $this->userLog->find($userlog_id);
            $login_at = date_create($userlog['login_at']);
            $logout_at = date_create(date('Y-m-d H:i:s'));
            $duration = $login_at->diff($logout_at)->format('%r%H:%I:%S');
            # update user login information
            $update = $this->userLog->update($userlog_id, [
                'status' => 'offline',
                'logout_at' => date('Y-m-d H:i:s'),
                'duration' => $duration,
            ]);
            if ($update) {
                # destroy user session
                unset(
                    $_SESSION['clientLoggedIn'],
                    $_SESSION['name'],
                    $_SESSION['client_id'],
                );
                // $session = session();
                // $session->destroy();
                return redirect()->to('/client/login');
            }
        } else {
            # redirect the user to login screen again
            return redirect()->to('/client/login');
        }
    }
}
