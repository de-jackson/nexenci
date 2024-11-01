<?php

namespace App\Controllers\Admin;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Dashboard extends MasterController
{

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Dashboard';
        $this->title = 'Dashboard';
        $this->menuItem = [
            'title' => $this->title,
            'menu' => $this->menu,
        ];
        # end session for the first user
        $this->checkAdminMultipleLogin();
    }

    public function index()
    {
        /*
        $transaction = $this->yoAPI->ac_transaction_check_status(NULL, '70402820');
        print_r($transaction); exit;
        
        */
        $charge = $this->vLookUpWithdrawalsCharge(21, 100000);
        $data = $this->computeTotalAmount($charge, 100000);

        $saving = $this->entry->calculateTotalBalance([
            'module' => 'account_type',
            'module_id' => 12, # account type id is 1 for cash and bank
            'status' => 'credit',
            'deleted_at' => null
        ]);




        // print_r($saving);
        // exit;

        // application data
        $applicationArray = [
            'applications' => $this->settings->countResults('loanapplications', ['deleted_at' => null]),
            'pending' => $this->settings->countResults('loanapplications', ['status' => 'Pending', 'deleted_at' => null]),
            'processing' => $this->settings->countResults('loanapplications', ['status' => 'Processing', 'deleted_at' => null]),
            'declined' => $this->settings->countResults('loanapplications', ['status' => 'Declined', 'deleted_at' => null]),
            'approved' => $this->settings->countResults('loanapplications', ['status' => 'Approved', 'deleted_at' => null]),
            'disbursed' => $this->settings->countResults('loanapplications', ['status' => 'Disbursed', 'deleted_at' => null]),
            'new' => $this->settings->countResults('loanapplications', ['MONTH(created_at)' => date('m')]),
            'applicationsSum' => $this->settings->sum_column('loanapplications', 'principal', ['deleted_at' => null]),
            'monthlyApplications' => $this->report->get_monthly_summation('loanapplications'),
        ];
        // disbursement data 
        $disbursmentArray = [
            'disbursements' => $this->settings->countResults('disbursements', ['deleted_at' => null]),
            'running' => $this->settings->countResults('disbursements', ['class' => "Running"]),
            'arrears' => $this->settings->countResults('disbursements', ['class' => "Arrears"]),
            'cleared' => $this->settings->countResults('disbursements', ['class' => "Cleared"]),
            'expired' => $this->settings->countResults('disbursements', ['class' => "Expired"]),
            'new' => $this->settings->countResults('disbursements', ['MONTH(created_at)' => date('m')]),
            'disbursementsSum' => $this->settings->sum_column('disbursements', 'principal', [
                'deleted_at' => null
            ]),
            'monthlyDisbursements' => $this->report->get_monthly_summation('disbursements'),

            'principal' => $this->settings->sum_column('disbursements', 'principal', [
                'deleted_at' => null
            ]),
            'principalCollected' => $this->settings->sum_column('disbursements', 'principal_collected', ['deleted_at' => null]),

            # compute the total interest
            'actualInterest' => $this->settings->sum_column('disbursements', 'actual_interest', ['deleted_at' => null]),
            'interestCollected' => $this->settings->sum_column('disbursements', 'interest_collected', ['deleted_at' => null]),
            # get the total loan
            'totalLoan' => $this->settings->sum_column('disbursements', 'actual_repayment', [
                'deleted_at' => null
            ], 'disbursements'),
            'totalLoanCollected' => $this->settings->sum_column('disbursements', 'total_collected', ['deleted_at' => null], 'disbursements'),
            'totalLoanBalance' => $this->settings->sum_column('disbursements', 'total_balance', ['deleted_at' => null], 'disbursements'),
        ];
        # liquidity data
        $liquidityArray = [
            'totalLiquidity' => $this->entry->calculateTotalBalance([
                'module' => 'account_type',
                'module_id' => 1, # account type id is 1 for cash and bank
                'status' => 'debit',
                'deleted_at' => null
            ]),
            'particulars' => $this->get_liquidityParticulars(),
        ];
        # Membership data
        $membershipArray = [
            'accounting' => $this->entry->calculateTotalBalance([
                'module' => 'account_type',
                'module_id' => 24, # account type id is 24 for Membership
                'status' => 'credit',
                'deleted_at' => null
            ]),
            'particulars' => $this->getParticularsAccount(24),
        ];
        # compute total deposit
        $deposit = $this->settings->sum_column('entries', 'amount', [
            'account_typeId' => 12,
            'status' => 'credit',
            'deleted_at' => null
        ], 'deposit');
        # compute total revenue from deposit
        $deposit = $this->settings->sum_column('entries', 'amount', [
            'account_typeId' => 12,
            'status' => 'credit',
            'deleted_at' => null
        ], 'deposit');
        # compute total withdraws
        $withdraw = $this->settings->sum_column('entries', 'amount', [
            'account_typeId' => 12,
            'status' => 'debit',
            'deleted_at' => null
        ], 'withdraw');
        # compute total deposit
        $withdrawCharges = $this->settings->sum_column('entries', 'amount', [
            'account_typeId' => 20,
            'status' => 'credit',
            'deleted_at' => null
        ], 'charges');
        # compute total member savings balance
        # $totalSavings = $deposit - ($withdraw + $withdrawCharges);
        $totalSavings = $this->entry->calculateTotalBalance([
            'module' => 'account_type',
            'module_id' => 12, # account type id is 1 for cash and bank
            'status' => 'credit',
            'deleted_at' => null
        ]);

        $entriesData = [
            'entries' => $this->get_entries(),
            'monthly-entries' => $this->report->get_monthly_summation('entries'),
            'totalEntries' => $this->settings->countResults('entries', ['deleted_at' => null]),
            'savings' => $this->settings->countResults('entries', ['account_typeId' => 12, 'deleted_at' => null]),
            'newSavings' => $this->settings->countResults('entries', ['account_typeId' => 12, 'MONTH(date)' => date('m'), 'deleted_at' => null]),
            'totalOveralSavings' => $this->settings->sum_column('entries', 'amount', [
                'account_typeId' => 12
            ]),
            'deposit' => $deposit,
            'withdraw' => $withdraw,
            'withdrawCharges' => $withdrawCharges,
            'totalSavings' => $totalSavings['totalBalance'],
        ];
        # admin/dashboard/admin
        return view('admin/dashboard/home', [
            'title' => $this->title,
            'menu' => $this->menu,
            // clients
            'clients' => $this->settings->countResults('clients', ['deleted_at' => null]),
            'newClients' => $this->settings->countResults('clients', ['MONTH(created_at)' => date('m')]),
            // transactions\entries
            'transactions' => $entriesData,
            // loan products
            'products' => $this->loanProduct->orderBy('id', 'desc')->findAll(5),
            'topProducts' => $this->loanProduct->get_topProducts($this->settings->get_topPerformers('loanapplications', 'product_id')),
            // applications
            'applicationsData' => $applicationArray,
            // disbursements
            'disbursementsData' => $disbursmentArray,
            // disbursements
            'liquidityData' => $liquidityArray,
            'membershipData' => $membershipArray,
            // settings
            'settings' => $this->settingsRow,
            // loggedin user data
            'user' => $this->userRow,
            'userMenu' => $this->load_menu(),
            'permissions' => $this->userPermissions,
            'activities' => $this->userActivity->where(['user_id' => $this->userRow['id']])->orderBy('created_at', 'desc')->limit(5)->find(),

            'summary' => [
                'sms' => [
                    'apiResponse' => $this->egoAPI->initiate('balance')
                ],
                'savings' => [
                    'savings' => $this->report->getMonthlySavingsTotals([
                        'entries.account_typeId' => 12,
                        'entries.deleted_at' => NULL
                    ]),
                    'filter' => [
                        'week' => $this->report->getMonthlySavingsTotals([
                            'entries.account_typeId' => 12,
                            'entries.deleted_at' => NULL
                        ], 'week'),
                        'month' => $this->report->getMonthlySavingsTotals([
                            'entries.account_typeId' => 12,
                            'entries.deleted_at' => NULL
                        ], 'month'),
                        'year' => $this->report->getMonthlySavingsTotals([
                            'entries.account_typeId' => 12,
                            'entries.deleted_at' => NULL
                        ], 'year'),
                    ]
                ],
                'applications' => [
                    'pending' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status' => "Pending"
                    ]),
                    'processing' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status' => "Processing"
                    ]),
                    'declined' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status' => "Declined"
                    ]),
                    'disbursed' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status' => "Disbursed"
                    ]),
                    'cancelled' => $this->settings->countResults('loanapplications', [
                        'account_id' => $this->userRow['account_id'],
                        'status' => "Cancelled"
                    ]),
                ],
                'disbursements' => [
                    'running' => $this->settings->countResults('disbursements', [
                        'account_id' => $this->userRow['account_id'],
                        'class' => "Running"
                    ]),
                    'arrears' => $this->settings->countResults('disbursements', [
                        'account_id' => $this->userRow['account_id'],
                        'class' => "Arrears"
                    ]),
                    'cleared' => $this->settings->countResults('disbursements', [
                        'account_id' => $this->userRow['account_id'],
                        'class' => "Cleared"
                    ]),
                    'expired' => $this->settings->countResults('disbursements', [
                        'account_id' => $this->userRow['account_id'],
                        'class' => "Expired"
                    ]),
                    'defaulted' => $this->settings->countResults('disbursements', [
                        'account_id' => $this->userRow['account_id'],
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
        $userRow = $this->user->select('users.*, branches.branch_name, staffs.staffID, staffs.alternate_mobile, staffs.gender, staffs.marital_status, staffs.religion, staffs.nationality, staffs.date_of_birth, staffs.appointment_type, positions.position, departments.department_name')
            ->join('branches', 'branches.id = users.branch_id', 'left')
            ->join('staffs', 'staffs.id = users.staff_id', 'left')
            ->join('positions', 'positions.id = staffs.position_id', 'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->find($this->userRow['id']);

        // $serializedData = 'a:6:{s:9:"ProductID";s:1:"1";s:12:"InterestRate";s:4:"8.00";s:14:"InterestPeriod";s:5:"month";s:12:"InterestType";s:4:"Flat";s:13:"LoanFrequency";s:7:"Monthly";s:15:"RepaymentPeriod";s:1:"4";}';

        // $unserializedData = unserialize($serializedData);

        // print_r($unserializedData);

        // exit;

        # loan repayment calculations
        # include_once(APPPATH.'Views/site/repayment.php');
        # exit;
        /*

        $data0 = [
            'ParticularID' => 25,
            'ParticularCharge' => 0,
            'ParticularChargeMethod' => 'Percent',
        ];

        $applicantProduct = [
            'ProductID' => 8,
            'InterestRate' => 12,
            'InterestPeriod' => 'week',
            'InterestType' => 'Flat',
            'LoanFrequency' => 'Weekly',
            'RepaymentPeriod' => 8,
        ];

        # echo $data0['ParticularID'];

        # Create a multidimensional array
        $data1 = [
            'ParticularID' => [25],
            'ParticularCharge' => [0],
            'ParticularChargeMethod' => ['Percent'],
        ];

        echo "<br><br>";

        print_r($applicantProduct);

        echo "<br><br>";

        $serializedProductData = serialize($applicantProduct);

        echo $serializedProductData;

        echo "<br><br>";

        $serializedData = 'a:6:{s:9:"ProductID";s:1:"1";s:12:"InterestRate";s:4:"8.00";s:14:"InterestPeriod";s:5:"month";s:12:"InterestType";s:4:"Flat";s:13:"LoanFrequency";s:7:"Monthly";s:15:"RepaymentPeriod";s:1:"4";}';

        $unserializedData = unserialize($serializedData);

        print_r($unserializedData);

        exit;

       

        // Process the data
        foreach ($data['ParticularID'] as $index => $particularId) {
            $charge = $data['ParticularCharge'][$index];
            $chargeMethod = $data['ParticularChargeMethod'][$index];

            # Handle the charge method based on the condition
            if (strtolower($chargeMethod) === 'percent') {
                # Calculate the charge as a percentage of the product
                $totalCharge = $charge;
            } elseif (strtolower($chargeMethod) === 'amount') {
                # Use the fixed amount as the charge
                $totalCharge = $charge;
            } else {
                # Handle other charge methods here
                $totalCharge = 0;
            }

            # Use $totalCharge as needed
            echo "Particular ID: $particularId, Total Charge: $totalCharge,  Total Method: $chargeMethod<br>";
        }



        # Serialize the multidimensional array
        $serializedData = serialize($data);

        echo "<br><br>";

        # Store the serialized data in a file or a database, for example
        # For demonstration purposes, let's just echo it
        echo $serializedData;

        # Unserialize the data when you need to use it
        $unserializedData = unserialize($serializedData);

        # Access the individual arrays within the multidimensional array
        $ParticularID = $unserializedData['ParticularID'];
        $ParticularCharge = $unserializedData['ParticularCharge'];
        $chargeMethods = $unserializedData['ParticularChargeMethod'];

        echo "<br><br>";

        # Now you can work with these arrays
        print_r($ParticularID);

        echo "<br><br>";
        print_r($ParticularCharge);

        echo "<br><br>";
        print_r($chargeMethods);


        exit;
     
        */

        // logged in user logs
        $userLogs = $this->userLog->where(['user_id' => $this->userRow['id']])->orderBy('id', 'desc')->paginate(5);
        // logged in user activities
        $userActivity = $this->userActivity->where(['user_id' => $this->userRow['id']])->orderBy('id', 'desc')->paginate(5);

        if ($userRow) {
            return view('admin/dashboard/profile', [
                'title' => 'Profile',
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $userRow,
                'userMenu' => $this->load_menu(),
                'activities' => $userActivity,
                'activityCount' => $this->settings->countResults('useractivities', ['user_id' => $userRow['id']]),
                'activityPager' => $this->userActivity->pager,
                'logs' => $userLogs,
                'logsCount' => $this->settings->countResults('userlogs', ['user_id' => $userRow['id']]),
                'logsPager' => $this->userLog->pager,
            ]);
        } else {
            session()->setFlashdata('failed', 'Page requested can not be found');
            return redirect()->to(base_url('admin/dashboard'));;
        }
    }

    public function search_logs()
    {
        $user = $this->user->select('users.*, branches.branch_name, staffs.staffID, staffs.account_type, positions.position, departments.department_name')
            ->join('branches', 'branches.id = users.branch_id', 'left')
            ->join('staffs', 'staffs.id = users.staff_id', 'left')
            ->join('positions', 'positions.id = staffs.position_id', 'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->find($this->userRow['id']);

        $search = $this->request->getVar('search_logs');
        // logged in user activities
        $userActivity = $this->userActivity->where(['user_id' => $this->userRow['id']])->orderBy('id', 'desc')->paginate(5);
        if ($search == '') {
            $userLogs = $this->userLog->where(['user_id' => $this->userRow['id']])->orderBy('id', 'desc')->paginate(5);
        } else {
            $userLogs = $this->userLog->select('*')
                ->orLike('browser', $search)
                ->orLike('operating_system', $search)
                ->orLike('loginfo', $search)
                ->orLike('status', $search)
                ->orLike('created_at', date('Y-m-d', strtotime($search)))
                ->orLike('logout_at', date('Y-m-d', strtotime($search)))
                ->orLike('location', $search)
                ->where(['user_id' => $this->userRow['id']])
                ->orderBy('id', 'desc')
                ->paginate(5);
        }
        if ($user) {
            return view('admin/dashboard/profile', [
                'title' => $user['name'],
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $user,
                'userMenu' => $this->load_menu(),
                'activities' => $userActivity,
                'activityPager' => $this->userActivity->pager,
                'logs' => $userLogs,
                'logsPager' => $this->userLog->pager,
                'search' => $search
            ]);
        } else {
            session()->setFlashdata('failed', 'Page requested can not be found');
            return redirect()->to(base_url('admin/dashboard'));;
        }
    }
    public function search_activities()
    {
        $user = $this->user->select('users.*, branches.branch_name, staffs.staffID, staffs.account_type, positions.position, departments.department_name')
            ->join('branches', 'branches.id = users.branch_id', 'left')
            ->join('staffs', 'staffs.id = users.staff_id', 'left')
            ->join('positions', 'positions.id = staffs.position_id', 'left')
            ->join('departments', 'departments.id = positions.department_id', 'left')
            ->find($this->userRow['id']);

        $search = $this->request->getVar('search_activity');
        // logged in user logs
        $userLogs = $this->userLog->where(['user_id' => $this->userRow['id']])->orderBy('id', 'desc')->paginate(5);
        // searched activity
        if ($search == '') {
            $userActivity = $this->userActivity->where(['user_id' => $this->userRow['id']])->orderBy('id', 'desc')->paginate(5);
        } else {
            $userActivity = $this->userActivity->select('*')
                ->orLike('module', $search)
                ->orLike('action', $search)
                ->orLike('referrer_id', $search)
                ->orLike('created_at', date('Y-m-d', strtotime($search)))
                ->where(['user_id' => $this->userRow['id']])
                ->orderBy('id', 'desc')
                ->paginate(5);
        }
        if ($user) {
            return view('admin/dashboard/profile', [
                'title' => $user['name'],
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $user,
                'userMenu' => $this->load_menu(),
                'activities' => $userActivity,
                'activityPager' => $this->userActivity->pager,
                'logs' => $userLogs,
                'logsPager' => $this->userLog->pager,
                'search' => $search
            ]);
        } else {
            session()->setFlashdata('failed', 'Page requested can not be found');
            return redirect()->to(base_url('admin/dashboard'));;
        }
    }

    // latest 10 transactions
    private function get_entries()
    {
        $data = $this->entry
            ->select('entries.ref_id, entries.entry_menu, entries.amount, entries.date, entries.status, particulars.particular_name, payments.particular_name as payment_method, account_types.name as account, entrytypes.type')
            ->join('account_types', 'account_types.id = entries.account_typeId', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            ->join('particulars as payments', 'payments.id = entries.payment_id', 'left')
            ->where(['staff_id' => $this->userRow['staff_id']])->orderBy('entries.id', 'desc')->findAll(10);
        return $data;
    }
    // particulars belonging to account type
    private function get_liquidityParticulars()
    {
        $liquidityParticulars = [];
        $particulars = $this->particular->select('particulars.*, categories.part')->where([
            'particulars.account_typeId' => 1,
            'particulars.particular_status' => 'Active'
        ])->join('categories', 'categories.id = particulars.category_id', 'left')->findAll();
        if ($particulars) {
            // get totals foreach liquidity particular
            foreach ($particulars as $particular) {
                $particularTotals = $this->entry->calculateTotalBalance([
                    'module' => 'particular',
                    'module_id' => $particular['id'],
                    'status' => $particular['part']
                ]);
                $particular['total_balance'] = $particularTotals['totalBalance'];

                $liquidityParticulars[] = $particular;
            }
        }
        return $liquidityParticulars;
    }

    private function getParticularsAccount($account_typeId)
    {
        $accounts = [];
        $particulars = $this->particular->select('particulars.*, categories.part')
            ->where([
                'particulars.account_typeId' => $account_typeId,
                'particulars.particular_status' => 'Active'
            ])
            ->join('categories', 'categories.id = particulars.category_id', 'left')
            ->findAll();
        if ($particulars) {
            // get totals foreach liquidity particular
            foreach ($particulars as $particular) {
                $particularTotals = $this->entry->calculateTotalBalance([
                    'module' => 'particular',
                    'module_id' => $particular['id'],
                    'status' => $particular['part']
                ]);
                $particular['total_balance'] = $particularTotals['totalBalance'];

                $accounts[] = $particular;
            }
        }
        return $accounts;
    }

    public function checkAdminMultipleLogin()
    {
        if (session()->get('loggedIn') && session()->get('id')) {
            $id = session()->get('id');
            $user = $this->user->find($id);
            if ($user) {
                # check user login access
                if (strtolower($user["access_status"]) == "active") {
                    $token = $user['token'];
                    $authorized = password_verify(session()->get('token'), $token);
                    if (!$authorized) {
                        # logout user automatically from the first device 
                        # when multiple login is deducted 
                        $this->logout();
                    }
                } else {
                    # user is not authorized to access this
                    session()->setFlashdata('failed', 'Failed! You are Not Authorized to Access this!');
                    return redirect()->to(base_url('/login'));
                }
            } else {
                session()->setFlashdata('failed', 'Failed! We could not find your account!');
                return redirect()->to(base_url('/login'));
            }
        }
    }

    public function change_password()
    {
        $this->validate_newPassword();
        $id = $this->request->getVar('id');
        $menu = $this->request->getVar('menu');
        $password = trim($this->request->getVar('confirmpassword'));
        $userRow = $this->user->find($id);
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
        $userData = $this->user->find($this->userRow['id']);
        $fa = (strtolower($userData['2fa']) == 'true') ? 'False' : 'True';
        $update = $this->user->update($id, ['2fa' => $fa]);
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
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete_user_log($id = null)
    {
        $data = $this->userLog->find($id);
        if ($data) {
            $delete = $this->userLog->delete($id);
            if ($delete) {
                // insert into activity logs
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => ucfirst('deleted user login log' . $data['ref_id']),
                    'module' => $this->menu,
                    'referrer_id' => $data['loginfo'],
                ];
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => 'User log successfully deleted',
                    ];
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => 'User log successfully deleted. loggingFailed'
                    ];
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Delete Failed',
                    'messages' => 'Deleting ' . $this->title . ' Log failed!',
                ];
            }
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The User Log could not be found!',
            ];
        }
        return $this->respond($response);
    }

    private function validate_newPassword()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $id = $this->request->getVar('id');
        $userRow = $this->user->find($id);
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
            if ($userlog) {
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
                    $session = session();
                    $session->destroy();
                    // unset(
                    //     $_SESSION['loggedIn'],
                    //     $_SESSION['name'],
                    //     $_SESSION['id'],
                    // );
                    return redirect()->to('/admin/login');
                }
            } else {
                # destroy user session
                $session = session();
                $session->destroy();
                return redirect()->to('/admin/login');
            }
        } else {
            # redirect the user to login screen again
            return redirect()->to('/admin/login');
        }
    }
}
