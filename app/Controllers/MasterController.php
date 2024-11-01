<?php

namespace App\Controllers;

use App\Controllers\Microfinance\BaseController as MicrofinanceBaseController;
use CodeIgniter\I18n\Time;


class MasterController extends MicrofinanceBaseController
{
    public function __construct()
    {
        parent::__construct();
        # Check the Settings Information
        if ($this->settingsRow) {
            # load user data if session is set
            $user_id = session()->get('id');

            if (!isset($user_id)) {
                // session()->setFlashdata('failed', "Your Session Has Expired. Kindly Login Again!");
                return redirect()->to('/admin/login');
            } else {
                $this->userRow = $this->user->select('users.*, branches.branch_name, 
                positions.position, positions.id as position_id, departments.department_name')
                    ->join('branches', 'branches.id = users.branch_id', 'left')
                    ->join('staffs', 'staffs.id = users.staff_id', 'left')
                    ->join('positions', 'positions.id = staffs.position_id', 'left')
                    ->join('departments', 'departments.id = positions.department_id', 'left')
                    ->find(session()->get('id'));

                $this->userPermissions = unserialize($this->userRow['permissions']);
            }

            /*

            if (isset($user_id)) {
                $this->userRow = $this->user->select('users.*, branches.branch_name, 
                positions.position, departments.department_name')
                    ->join('branches', 'branches.id = users.branch_id', 'left')
                    ->join('staffs', 'staffs.id = users.staff_id', 'left')
                    ->join('positions', 'positions.id = staffs.position_id', 'left')
                    ->join('departments', 'departments.id = positions.department_id', 'left')
                    ->find(session()->get('id'));

                $this->userPermissions = unserialize($this->userRow['permissions']);

                if (empty($this->userPermissions)) {
                    # redirect the user to login screen again
                    // session()->setFlashdata('failed', "Your Session Has Expired. Kindly Login Again!");
                    // return redirect()->to('/admin/login');
                }
            } else {
                # redirect the user to login screen again
                return redirect()->to('/admin/login');
            }
            
            */
        } else {
            # No System Settings Is Found
            return view('layout/404');
        }
    }

    public function getOperationPastTense($operation)
    {
        switch ($operation) {
            case 'create':
                $pastTense = 'created';
                break;
            case 'send':
                $pastTense = 'sent';
                break;
            case 'upload':
                $pastTense = 'uploaded';
                break;

            case 'import':
                $pastTense = 'imported';
                break;

            case 'update':
                $pastTense = 'updated';
                break;

            case 'delete':
                $pastTense = 'deleted';
                break;

            case 'bulk-delete':
                $pastTense = 'deleted';
                break;

            default:
                # code...
                $pastTense = 'bulk deleted';
                break;
        }

        return $pastTense;
    }

    public function saveUserActivity(array $activityData)
    {
        $mode = $activityData['action'];
        $operation = $this->getOperationPastTense($mode);
        $title = $activityData['title'];
        # Check the save existence
        if ($activityData['referrer_id']) {
            # Remove the title from the array
            unset($activityData['title']);
            $activityData['description'] = ucfirst($operation . " " . $activityData['description']);
            # get user information
            $agent = $this->request->getUserAgent();
            $ip_address = $this->request->getIPAddress();
            # $ip_address = "41.75.191.108";
            # check internet connection
            $checkInternet = $this->settings->checkNetworkConnection();
            if ($checkInternet) {
                # member computer has internet connection
                $geoData = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip_address));

                # check the geo data existance
                if ($geoData) {
                    # check whether the geo data status
                    if ($geoData['geoplugin_status'] === 200) {
                        # fetch the geo information
                        $city = $geoData['geoplugin_city'];
                        $region = $geoData['geoplugin_region'];
                        $country = $geoData['geoplugin_countryName'];
                        $latitude = $geoData['geoplugin_latitude'];
                        $longitude = $geoData['geoplugin_longitude'];
                        $continent = $geoData['geoplugin_continentName'];
                        $location = $city . ', ' . $region . ', ' . $country . ' ' . $continent;
                    } else {
                        # set the geo data to null incase the status is 404
                        $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = '';
                    }
                } else {
                    # set the geo data to null incase the status is 404
                    $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = '';
                }
            } else {
                # set the geo data to null incase the system is not connected
                # to the internet
                $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = '';
            }
            # append geo data to the activity data
            $activityData['ip_address'] = $ip_address;
            $activityData['browser'] = $agent->getBrowser();
            $activityData['browser_version'] = $agent->getVersion();
            $activityData['operating_system'] = $agent->getPlatform();
            $activityData['location'] = $location;
            $activityData['latitude'] = $latitude;
            $activityData['longitude'] = $longitude;
            # Save into activity logs
            $activity = $this->insertActivityLog($activityData);
            if ($activity) {
                $response = [
                    'status' => 200,
                    'error' => null,
                    'messages' => ucwords($title) . " " . ucfirst($operation) . " Successfully"
                ];
                echo json_encode($response);
                exit();
                # return $this->respond($response);
                exit();
            } else {
                $response = [
                    'status'   => 201,
                    'error'    => null,
                    'messages' => ucwords($title) . " " . ucfirst($operation) . " Successfully"
                ];
                echo json_encode($response);
                exit();
                # return $this->respond($response);
                # exit();
            }
        } else {
            $response = [
                'status' => 500,
                'error' => ucfirst($mode) . ' Failed',
                'messages' => ucfirst($title . " could not be " . $operation . ". Try again later")
            ];
            echo json_encode($response);
            exit();
        }
    }

    public function saveUserActivityLogs(array $data)
    {
        $mode = $data['mode'];
        $operation = $this->getOperationPastTense($mode);
        $referrer_id = $data['referrer_id'];
        $save = $data['save'];
        $title = $data['title'];
        $menu = $data['menu'];
        # Check the save existence
        if ($save) {
            $activityData = [
                'user_id' => $this->userRow['id'],
                'action' => $mode,
                'description' => ucfirst($operation . $title),
                'module' => $menu,
                'referrer_id' => $referrer_id
            ];
            # get user information
            $agent = $this->request->getUserAgent();
            $ip_address = $this->request->getIPAddress();
            # $ip_address = "41.75.174.122";
            # check internet connection
            $checkInternet = $this->settings->checkNetworkConnection();
            if ($checkInternet) {
                # member computer has internet connection
                $geoData = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip_address));

                # check the geo data existence
                if ($geoData) {
                    # check whether the geo data status
                    if ($geoData['geoplugin_status'] === 200) {
                        # fetch the geo information
                        $city = $geoData['geoplugin_city'];
                        $region = $geoData['geoplugin_region'];
                        $country = $geoData['geoplugin_countryName'];
                        $latitude = $geoData['geoplugin_latitude'];
                        $longitude = $geoData['geoplugin_longitude'];
                        $continent = $geoData['geoplugin_continentName'];
                        $location = $city . ', ' . $region . ', ' . $country . ' ' . $continent;
                    } else {
                        # set the geo data to null incase the status is 404
                        $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = null;
                    }
                } else {
                    # set the geo data to null incase the status is 404
                    $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = null;
                }
            } else {
                # set the geo data to null incase the system is not connected
                # to the internet
                $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = null;
            }
            # append geo data to the activity data
            $activityData['ip_address'] = $ip_address;
            $activityData['browser'] = $agent->getBrowser();
            $activityData['browser_version'] = $agent->getVersion();
            $activityData['operating_system'] = $agent->getPlatform();
            $activityData['location'] = $location;
            $activityData['latitude'] = $latitude;
            $activityData['longitude'] = $longitude;
            # Save into activity logs
            $activity = $this->insertActivityLog($activityData);
            if ($activity) {
                $response = [
                    'status' => 200,
                    'error' => null,
                    'messages' => ucwords($title) . " " . ucfirst($operation) . " Successfully"
                ];
                echo json_encode($response);
                exit();
                # return $this->respond($response);
                exit();
            } else {
                $response = [
                    'status'   => 201,
                    'error'    => null,
                    'messages' => ucwords($title) . " " . ucfirst($operation) . " Successfully"
                ];
                echo json_encode($response);
                exit();
                # return $this->respond($response);
                # exit();
            }
        } else {
            $response = [
                'status' => 500,
                'error' => ucfirst($mode) . ' Failed',
                'messages' => ucfirst($title . " could not be " . $operation . ". Try again later")
            ];
            echo json_encode($response);
            exit();
        }
    }

    // insert into activity logs
    protected function insertActivityLog($data)
    {
        # get user information
        $agent = $this->request->getUserAgent();
        # $ip_address = $this->request->getIPAddress();
        $ip_address = "41.75.174.122";
        # check internet connection
        $checkInternet = $this->settings->checkNetworkConnection();
        if ($checkInternet) {
            # member computer has internet connection
            $geoData = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip_address));

            # check the geo data existence
            if ($geoData) {
                # check whether the geo data status
                if ($geoData['geoplugin_status'] === 200) {
                    # fetch the geo information
                    $city = $geoData['geoplugin_city'];
                    $region = $geoData['geoplugin_region'];
                    $country = $geoData['geoplugin_countryName'];
                    $latitude = $geoData['geoplugin_latitude'];
                    $longitude = $geoData['geoplugin_longitude'];
                    $continent = $geoData['geoplugin_continentName'];
                    $location = $city . ', ' . $region . ', ' . $country . ' ' . $continent;
                } else {
                    # set the geo data to null incase the status is 404
                    $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = null;
                }
            } else {
                # set the geo data to null incase the status is 404
                $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = null;
            }
        } else {
            # set the geo data to null incase the system is not connected
            # to the internet
            $city = $region = $country = $latitude = $longitude = $longitude = $continent = $location = null;
        }
        # append geo data to the activity data
        $data['ip_address'] = $ip_address;
        $data['browser'] = $agent->getBrowser();
        $data['browser_version'] = $agent->getVersion();
        $data['operating_system'] = $agent->getPlatform();
        $data['location'] = $location;
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $insert = $this->userActivity->insert($data);

        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

    public function getClientMembershipCharge()
    {
        $reg_date = $this->request->getVar('reg_date');
        $particular_id = $this->request->getVar('particular_id');

        $chargeRow = $this->vlookupCharge($particular_id, $reg_date);
        $data = [];
        if ($chargeRow) {
            $data[] = $chargeRow;
        }

        return $this->respond($data);
    }

    protected function membershipTransaction(array $data)
    {
        // $ref_id = $this->settings->generateReference();
        $membershipOutsandingBalance = $data['membershipAmt'];
        $payment_id = $data['payment_id'];
        $client_id = $data['client_id'];
        $account_typeId = $data['account_typeId'];
        $parent_id = $data['parent_id'];
        $product_id = (isset($data['product_id'])) ? $data['product_id'] : null;

        # Check the particular existance that will be used as for payment
        $paymentRow = $this->checkArrayExistance('particular', [
            'id' => $payment_id,
        ]);
        # Check the account type existance
        $account_typeInfo = $this->checkArrayExistance('accountType', [
            'id' => $account_typeId,
        ]);
        # Check the client existance
        $clientInfo = $this->checkArrayExistance('client', [
            'id' => $client_id
        ]);

        $reg_date = $clientInfo['reg_date'];

        $transaction_typeRow = $this->entryType->where([
            'account_typeId' => $account_typeId,
            'part' => 'credit'
        ])->first();

        if (!$transaction_typeRow) {
            echo json_encode([
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Transaction Type could not be found!',
            ]);
            exit;
        }

        $entry_typeId = $transaction_typeRow['id'];
        $entry_menu = $transaction_typeRow['entry_menu'];
        $status = $transaction_typeRow['part'];
        $transactionType = $transaction_typeRow['type'];

        $particulars = $this->particular->where([
            'account_typeId' => $account_typeId,
            'particular_status' => 'Active',
        ])->findAll();

        $totalOverPayment = $particularBalance = $excessAmt = 0;
        $amountToBePaid = $membershipOutsandingBalance;

        foreach ($particulars as $particular) {
            if ($amountToBePaid <= 0) {
                break; // Break loop if charge to be paid is negative
            }

            $particular_id = $particular['id'];
            # Check the particular existance
            $particularRow = $this->checkArrayExistance('particular', [
                'id' => $particular_id,
            ]);

            if (!$particularRow) {
                continue; // Skip if particular is not found
            }


            $chargeRow = $this->vlookupCharge($particular['id'], $reg_date);
            if (!$chargeRow || strtolower($chargeRow['charge_mode']) == 'manual') {
                continue; // Skip if no valid charge is found or if charge shouldn't be reducted automatically
            }

            $chargeAmt = $chargeRow['charge'];
            $chargeFrequency = strtolower($particularRow['charge_frequency']);
            $chargeMode = strtolower($particularRow['charge_mode']);

            # Check if The Client Has Fully Paid or is Paying Excess Amounts
            $totalAmountPaid = $this->entry->sum_client_amountPaid($client_id, $particular_id, $status);

            # Check the charge balance
            $particularBalance = ($chargeAmt - $totalAmountPaid);

            /** Check for Excess Payments
             * calculate total paid and excess amount
             * if any excess payment, push it to client savings
             */
            if ($particularBalance < 0) {
                $response = [
                    'status' => 500,
                    'error' => 'Entry Error!',
                    'messages' => 'An error was detected in posting of ' . $particular['particular_name'] . ' entries.',
                ];

                echo json_encode($response);
                exit;
            }

            if ($particularBalance == 0) {
                continue;
            }

            $membershipCharge = min($amountToBePaid, $particularBalance);
            $excessAmt = max(0, ($amountToBePaid - $particularBalance));

            if ($membershipCharge == 0) {
                continue;
            }

            $totalOverPayment += $excessAmt;

            # calculate entries total amount per entry status & final balance
            $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $particularRow['part']]);
            $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $membershipCharge);

            /** 
             * since account type is for Revenue category,
             * credit particular & debit payment method if its gaining,
             * debit particular & credit payment method if its loosing,
             */
            $accountBalance = $this->checkArrayExistance('accountBalance', [
                'part' => strtolower($status),
                'amount' => $membershipCharge,
                'accountBalance' => $clientInfo['account_balance'],
                'debitParticularBalance' => $particular['debit'],
                'creditParticularBalance' => $particular['credit'],
                'debitPaymentBalance' => $paymentRow['debit'],
                'creditPaymentBalance' => $paymentRow['credit']
            ]);
            # Check the membership transaction data existance
            if (!empty($this->request->getVar('date'))) {
                $date = trim($this->request->getVar('date'));
            } else {
                $date = date('Y-m-d');
            }
            # Membership transaction payload
            $ref_id = $this->settings->generateReference();
            $transactionData = [
                'date' => $date,
                'payment_id' => $payment_id,
                'particular_id' => $particular_id,
                'branch_id' => $this->userRow['branch_id'],
                'staff_id' => $this->userRow['staff_id'],
                'client_id' => $client_id,
                'product_id' => $product_id,
                'entry_menu' => $entry_menu,
                'entry_typeId' => $entry_typeId,
                'account_typeId' => $account_typeId,
                'parent_id' => $parent_id,
                'ref_id' => $ref_id,
                'amount' => $membershipCharge,
                'status' => $status,
                'balance' => $accountingBalance,
                'contact' => trim($this->request->getVar('contact')),
                'entry_details' => 'Direct Deduction of ' . $this->settingsRow['currency'] . ' ' . $membershipCharge . ' from ' . $clientInfo['name'] . '\'s ' . $data['from'] . '. Reason: ' . $particularRow['particular_name'],
                'remarks' => $clientInfo['name'] . ' ' . $particularRow['particular_name'] . ' Deduction',
            ];
            # Save membership transactions
            $saveTransaction = $this->entry->insert($transactionData);

            # Update accounting particulars balances
            $updateParticularAccount = $this->particular->update($particular_id, [
                'credit' => $accountBalance['creditBalance'],
                'debit' => $accountBalance['debitBalance']
            ]);
            $updatePaymentAccount = $this->particular->update($payment_id, [
                'credit' => $accountBalance['creditBalance'],
                'debit' => $accountBalance['debitBalance']
            ]);
            # Add transaction information into the activity log
            $activity = $this->userActivity->insert([
                'user_id' => $this->userRow['id'],
                'action' => 'create',
                'description' => ucfirst('New ' . $entry_menu . ' ' . $transaction_typeRow['type'] .  $this->title . ' for ' . $particular['particular_name'] . ' Ref ID ' . $ref_id),
                'module' => $this->menu,
                'referrer_id' => $ref_id,
            ]);
            # Check the client email existance
            if (!empty($clientInfo['email'])) {
                $clientInfo['branch_name'] = $this->userRow['branch_name'];
                $clientInfo['amount'] = $membershipCharge;
                $clientInfo['charge'] = $particular['charge'];
                $clientInfo['ref_id'] = $ref_id;
                $clientInfo['date'] = date('d-m-Y H:i:s');
                $clientInfo['entry_details'] = trim($this->request->getVar('entry_details'));
                $clientInfo['account_typeID'] = $account_typeId;
                $clientInfo['type'] = $transaction_typeRow['type'];
                $clientInfo['particular_name'] = $particular['particular_name'];
                $clientInfo['payment_mode'] = $paymentRow['particular_name'];
                $message = $clientInfo;
                $checkInternet = $this->settings->checkNetworkConnection();
                # Check the internet coneection
                if ($checkInternet) {
                    $subject = 'New ' . $particular['particular_name'] . ' Transaction';
                    $message = $message;
                    $token = 'transaction';
                    # $this->settings->sendMail($message, $subject, $token);
                    $response = [
                        'status' => 200,
                        'error' => null,
                        'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. Email Sent'
                    ];
                    // return $this->respond($response);
                    // echo (json_encode($response));
                    // exit;
                } else {
                    $response = [
                        'status' => 200,
                        'error' => null,
                        'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. No Internet'
                    ];
                    // return $this->respond($response);
                    // echo (json_encode($response));
                    // exit;
                }
            } else {
                $response = [
                    'status' => 200,
                    'error' => null,
                    'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully.',
                ];
                // return $this->respond($response);
                // echo (json_encode($response));
                // exit;
            }

            $amountToBePaid -= $membershipCharge;
        }

        return [
            'overpayment' => $excessAmt,
            'membershipBalance' => $particularBalance,
            'totalOverPayment' => $totalOverPayment,
            'savingsToBePaid' => $amountToBePaid
        ];
        exit;
    }
    // lookup particular charge
    protected function vlookupCharge($particular_id, $reg_date)
    {
        // Get all charges for the particular
        $charges = $this->getCharges([
            'charges.particular_id' => $particular_id,
            'charges.status' => 'Active'
        ]);

        // If no charges are found, return null
        if (empty($charges)) {
            return null;
        }

        // Initialize variables to hold the earliest & closest available charge
        $earliestCharge = null;
        $earliestDate = null;
        $closestCharge = null;
        $closestDate = null;

        foreach ($charges as $charge) {
            $effectiveDate = strtotime($charge['effective_date']);
            $registrationDate = strtotime($reg_date);

            # skip the charges that will be handled manually
            if (strtolower($charge['charge_mode']) == 'manual') {
                continue;
            }

            // Track the earliest charge regardless of registration date
            if ($earliestDate === null || $effectiveDate < $earliestDate) {
                $earliestCharge = $charge;
                $earliestDate = $effectiveDate;
            }

            // Check if the effective date is less than or equal to the registration date
            if ($effectiveDate <= $registrationDate) {
                // Update the closest charge if this charge is more recent
                if ($closestDate === null || $effectiveDate > $closestDate) {
                    $closestCharge = $charge;
                    $closestDate = $effectiveDate;
                }
            }
        }

        // Return the closest charge, or the earliest charge if none is found before the reg_date
        return $closestCharge ? $closestCharge : $earliestCharge;
    }

    protected function push_excessAmount_to_client_savings(array $data)
    {
        $client_id = $data['client_id'];
        $amount = $data['amount'];
        $payment_id = $data['payment_id'];
        $from = $data['from'];

        $clientInfo = $this->clientDataRow($client_id);
        $ref_id = $this->settings->generateReference();
        $account_typeId = 12;
        $savingsParticular = $this->particular->where(['account_typeId' => $account_typeId])->first();
        if (!$savingsParticular) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'No Savings Particular found!',
            ];
            return $this->respond($response);
            exit;
        }
        $particular_id = $savingsParticular['id'];
        // get transaction type data
        $transaction_typeRow = $this->entryType->where(['account_typeId' => $account_typeId, 'part' => 'credit'])->first();
        if (!$transaction_typeRow) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Transaction Type could not be found!',
            ];
            return $this->respond($response);
            exit;
        }

        $entry_typeId = $transaction_typeRow['id'];
        // entry_menu of transaction based on entry type
        $entry_menu = $transaction_typeRow['entry_menu'];
        // status of transaction based on entry type
        $status = $transaction_typeRow['part'];
        // get particular data
        $particularRow = $this->particularDataRow($particular_id);
        // get payment method data
        // calculate entries total amount per entry status & final balance
        $entriesStatusTotals = $this->entry->calculateTotalBalance(['module' => 'particular', 'module_id' => $particular_id, 'status' => $particularRow['part']]);
        $accountingBalance = (float)($entriesStatusTotals['totalBalance'] + $amount); // calculate balance for primary particular as of this entry

        // check existence of accounting particulars
        if (!$particularRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Particular data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }

        $paymentRow = $this->particularDataRow($payment_id);
        if (!$paymentRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Payment method data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        /** 
         * update client balance and perform double entry
         * since account type is for Liability category,
         * credit particular & debit payment method if its gaining,
         * debit particular & credit payment method if its loosing,
         */
        if (strtolower($status) == 'credit') {
            $balance = (float)($clientInfo['account_balance'] + $amount);
            $clientBalance = ['account_balance' => $balance];
            // credit client savings particular[liability]
            $savingParticularBal = ['credit' => ((float)$particularRow['credit'] + $amount)];
            // debit selected payment method[assets]
            $paymentParticularBal = ['debit' => ((float)$paymentRow['debit'] + $amount)];
        }
        if (strtolower($status) == 'debit') {
            $balance = (float)($clientInfo['account_balance'] - $amount);
            $clientBalance = ['account_balance' => $balance];
            // debit client savings particular[liability]
            $savingParticularBal = ['debit' => ((float)$particularRow['debit'] + $amount)];
            // credit selected payment method[assets]
            $paymentParticularBal = ['credit' => ((float)$paymentRow['credit'] + $amount)];
        }

        if (!empty($this->request->getVar('date'))) {
            $date = trim($this->request->getVar('date'));
        } else {
            $date = date('Y-m-d');
        }
        // transaction\entry data
        $transactionData = [
            'date' => $date,
            'payment_id' => $payment_id,
            'particular_id' => $particular_id,
            'branch_id' => $this->userRow['branch_id'],
            'staff_id' => $this->userRow['staff_id'],
            'client_id' => $client_id,
            'entry_menu' => $entry_menu,
            'entry_typeId' => $entry_typeId,
            'account_typeId' => $account_typeId,
            'ref_id' => $ref_id,
            'amount' => $amount,
            'status' => $status,
            'balance' => $accountingBalance,
            'contact' => trim($this->request->getVar('contact')),
            'entry_details' => "Over Payment of " . $this->settingsRow['currency'] . ' ' . $amount . " from " . $from . " has been pushed to " . $clientInfo['name'] . "'s savings",
            'remarks' => "Over Payment",
        ];

        // save transaction
        $saveTransaction = $this->entry->insert($transactionData);
        if ($saveTransaction) {
            // update client account balance
            $updateClientAccount = $this->client->update($client_id, $clientBalance);
            if ($updateClientAccount) {
                // update accounting particulars balances
                $particular_idBal = $this->particular->update($particular_id, $savingParticularBal);
                $payment_idBal = $this->particular->update($payment_id, $paymentParticularBal);
                if ($particular_idBal && $payment_idBal) {
                    // add transaction into the activity log
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'create',
                        'description' => ucfirst('New ' . $transaction_typeRow['type'] . ' ' .  $this->title . ' for ' . $particularRow['particular_name'] . ' Ref ID ' . $ref_id),
                        'module' => $this->menu,
                        'referrer_id' => $ref_id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        // send transaction notification email to client
                        if ($clientInfo['email'] != '') {
                            $clientInfo['branch_name'] = $this->userRow['branch_name'];
                            $clientInfo['amount'] = $amount;
                            $clientInfo['balance'] = $balance;
                            $clientInfo['ref_id'] = $ref_id;
                            $clientInfo['date'] = date('d-m-Y H:i:s');
                            $clientInfo['entry_details'] = $transactionData['entry_details'];
                            $clientInfo['account_typeID'] = $account_typeId;
                            $clientInfo['type'] = $transaction_typeRow['type'];
                            $clientInfo['particular_name'] = $particularRow['particular_name'];
                            $clientInfo['payment_mode'] = $paymentRow['particular_name'];
                            // email message
                            $message = $clientInfo;
                            $checkInternet = $this->settings->checkNetworkConnection();
                            if ($checkInternet) {
                                $subject = 'New ' . $particularRow['particular_name'] . ' Transaction';
                                $message = $message;
                                $token = 'transaction';
                                # $this->settings->sendMail($message, $subject, $token);
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. Email Sent'
                                ];
                                return $this->respond($response);
                                exit;
                            } else {
                                $response = [
                                    'status' => 200,
                                    'error' => null,
                                    'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. No Internet'
                                ];
                                return $this->respond($response);
                                exit;
                            }
                        } else {
                            $response = [
                                'status' => 200,
                                'error' => null,
                                'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully.',
                            ];
                            return $this->respond($response);
                            exit;
                        }
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status'   => 500,
                        'error'    => 'Accounting Error',
                        'messages' => 'Implementing Double Entry failed, Client Balance updated!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Balance Update Failed',
                    'messages' => 'Updating Client account balance Failed, Transaction recorded successfully!',
                ];
                return $this->respond($response);
                exit;
            }
        }
    }

    public function run_auto_updates($module)
    {
        switch (strtolower($module)) {
            case 'savings':
                return $this->auto_update_accountBalance();
                break;
            case 'particular-totals':
                return $this->auto_calc_particularTotals();
                break;
            case 'disbursement-state':
                return $this->auto_disbursementState();
                break;
            case 'disbursement-balances':
                return $this->auto_update_disbursementBalances();
                break;
            default:
                return [
                    'savings' => $this->auto_update_accountBalance(),
                    'particular-totals' => $this->auto_calc_particularTotals(),
                    'disbursement-state' => $this->auto_disbursementState(),
                    'disbursement-balances' => $this->auto_update_disbursementBalances(),
                ];
                break;
        }
    }

    // auto calculate & update client savings account balance
    public function auto_update_accountBalance()
    {
        $savings_account_typeId = 12; // account type id for savings

        // Get distinct clients' ids for all active clients
        $allClientIds = $this->client->distinct()->select('id')->where(['access_status' => 'Active'])->findColumn('id');
        if (!$allClientIds) {
            return $this->respond([
                'status' => 404,
                'error' => 'Not Found',
                'message' => "No Client Ids Found",
            ]);
        }

        // Get distinct client IDs with savings entries
        $clientIds_entries = $this->entry->distinct()->select('client_id')->where(['account_typeId' => $savings_account_typeId])->findColumn('client_id');
        if (!$clientIds_entries) {
            return $this->respond([
                'status' => 404,
                'error' => 'Not Found',
                'message' => "No Client Savings Entries Found",
            ]);
        }

        // Remove duplicate IDs
        $entryClient_ids = array_unique($clientIds_entries);

        // Get all savings particulars once
        $savingsParticulars = $this->particular->select('particulars.id, particulars.particular_code, particulars.particular_name, particulars.category_id, categories.part')
            ->join('categories', 'categories.id = particulars.category_id', 'left')
            ->where(['account_typeId' => $savings_account_typeId])
            ->findAll();

        if (!$savingsParticulars) {
            return $this->respond([
                'status' => 404,
                'error' => 'Not Found',
                'message' => "No Savings Particulars Found",
            ]);
        }

        // Loop through clients
        foreach ($allClientIds as $clientId) {
            $clientRow = $this->client->find($clientId);
            if (!$clientRow) continue;

            // Initialize total balance and product balances
            $totalBalance = $productTotalBalance = 0;
            $savingsProducts = $clientRow['savings_products'] ? json_decode($clientRow['savings_products']) : [];

            // Check if client has savings entries
            if (in_array($clientId, $entryClient_ids)) {
                foreach ($savingsParticulars as $particular) {
                    $particularId = $particular['id'];

                    // Update product balances for each savings product
                    if (!empty($savingsProducts)) {
                        foreach ($savingsProducts as &$product) { // Pass product by reference
                            // Calculate the product balance and overwrite previous balance
                            $productTotalBalance += $this->entry->sum_client_amountPaid(
                                $clientId,
                                $particularId,
                                $particular['part'],
                                'savings-product',
                                $product->product_id
                            );
                            $product->product_balance = $productTotalBalance;
                        }
                    }

                    // Calculate total balance for this particular
                    $totalBalance += $this->entry->sum_client_amountPaid($clientId, $particularId, $particular['part']);
                }
            } else {
                // Set to '0.00' only if there are no entries for the client
                $totalBalance = '0.00';
                if (!empty($savingsProducts)) {
                    foreach ($savingsProducts as &$product) {
                        $product->product_balance = '0.00'; // Reset to 0 if there are no entries
                    }
                }
            }

            // Update client with the calculated balance and products
            $updateData = [
                'account_balance' => $totalBalance,
                'savings_products' => !empty($savingsProducts) ? json_encode($savingsProducts) : json_encode([]),
            ];
            $update = $this->client->update($clientId, $updateData);
            if (!$update) continue;
        }

        return $this->respond([
            'status' => 200,
            'error' => null,
            'message' => "Auto Client Savings Balance Update Success",
        ]);
    }

    /**
     * This function calculates the total debit and credit for each particular in the system.
     *
     * @return \CodeIgniter\HTTP\ResponseFactory The function returns a response with status code, error message, and a success message.
     */
    public function auto_calc_particularTotals()
    {
        # Fetch distinct particular_ids (both particular_id and payment_id)
        $particularIds = $this->entry->distinct()->select('particular_id')->findColumn('particular_id');
        if (!$particularIds) {
            $particularIds = []; // Assign an empty array if null or empty
        }
        $paymentIds = $this->entry->distinct()->select('payment_id')->findColumn('payment_id');
        if (!$paymentIds) {
            $paymentIds = []; // Assign an empty array if null or empty
        }

        $unique_entryParticular_ids = array_unique(array_merge($particularIds, $paymentIds)); // filter out nulls or empty values
        if (empty($unique_entryParticular_ids)) {
            // Respond or log that no particular or payment IDs were found
            return $this->respond([
                'status' => 404,
                'error' => 'Not Found',
                'message' => "No Particular or Payment IDs Found",
            ]);
        }
        // Initialize an array to hold particular IDs

        // Prepare an array to collect updates
        $updates = [];

        // Loop through unique particular IDs
        foreach ($unique_entryParticular_ids as $particular_id) {
            // Get particular data for each iteration
            $particularRow = $this->particularDataRow($particular_id);
            if ($particularRow) {
                // Get total debit, credit and particular balance
                $particularBalances = $this->entry->calculateTotalBalance([
                    'module' => 'particular',
                    'module_id' => $particular_id,
                    'status' => $particularRow['part']
                ]);

                // Prepare the update for the current particular ID
                $updates[] = [
                    'id' => $particular_id,
                    'debit' => $particularBalances['totalDebit'],
                    'credit' => $particularBalances['totalCredit'],
                ];
            }
        }

        // If updates were made, perform a batch update
        if (!empty($updates)) {
            $updateSuccess = $this->particular->updateBatch($updates, 'id');

            // Check if the batch update was successful
            if ($updateSuccess) {
                return $this->respond([
                    'status' => 200,
                    'error' => null,
                    'message' => "Auto Particular Total Debit & Credit Success",
                ]);
            } else {
                return $this->respond([
                    'status' => 500,
                    'error' => 'Action Failed',
                    'message' => "Auto Particular Total Debit & Credit Failed",
                ]);
            }
        }

        return $this->respond([
            'status' => 500,
            'error' => 'No Updates',
            'message' => "No updates were necessary.",
        ]);
    }

    public function auto_calc_particularTotalsO()
    {
        // declare variable to store particular ids
        $entryParticular_ids = [];
        // get the unique particular IDs
        $uniqueIds = [];

        // Get distinct payment_id values
        $paymentIds = $this->entry->distinct()->select('payment_id')->findColumn('payment_id');
        if ($paymentIds) {
            $uniqueIds = array_merge($uniqueIds, $paymentIds);

            // Get distinct particular_id values
            $particularIds = $this->entry->distinct()->select('particular_id')->findColumn('particular_id');
            $uniqueIds = array_merge($uniqueIds, $particularIds);

            // Remove duplicate IDs and return the array
            $entryParticular_ids = array_unique($uniqueIds);

            // loop through particular IDs
            foreach ($entryParticular_ids as $particular_id) {
                // get particular data for each iteration
                $particularRow = $this->particularDataRow($particular_id);
                if ($particularRow) {
                    // get total debit, credit and particular balance
                    $particularBalances = $this->entry->calculateTotalBalance([
                        'module' => 'particular',
                        'module_id' => $particular_id,
                        'status' => $particularRow['part']
                    ]);

                    // update  particular total debit and credit
                    $updateTotal = $this->particular->update($particular_id, [
                        'debit' => $particularBalances['totalDebit'],
                        'credit' => $particularBalances['totalCredit'],
                    ]);
                }
            }
            if ($updateTotal) {
                return $this->respond([
                    'status' => 200,
                    'error' => null,
                    'message' => "Auto Particular Total Debit & Credit Success",
                ]);
                exit;
            } else {
                return $this->respond([
                    'status' => 500,
                    'error' => 'Action Failed',
                    'message' => "Auto Particular Total Debit & Credit Failed",
                ]);
                exit;
            }
        } else {
            return $this->respond([
                'status' => 404,
                'error' => 'Not Found',
                'message' => "No Particular Ids Found",
            ]);
            exit;
        }
    }

    // auto calculate & update disbursement status
    protected function auto_disbursementStateOld()
    {
        $allDisbursements = $this->disbursement->where(['class !=' => 'Cleared'])->findAll();
        // $allDisbursements = $this->disbursement->where(['id=' => '2'])->findAll();
        $currentDate = Time::today(); // Get current date
        if (count($allDisbursements) > 0) {
            $comments = '';
            $arrears_info = [
                'arrears_count' => 0,
                'total_days' => 0,
            ]; // array to store arrears information
            $updateDays = false;
            foreach ($allDisbursements as $row) {
                // make valiables 0 by default
                $interest_installment = $principal_installment = $days_covered = $days_remaing = $installments_covered = $arrears = $installments_due = $principal_due = $interest_due = 0;
                $installments_num = $row['installments_num'];
                $installmentAmt = $row['actual_installment'];
                $status = $row['status'];
                $class = $row["class"];
                $client_id = $row["client_id"];
                // fetch disbursement product information
                $productRow = $this->loanProduct->find($row['product_id']);
                // fetch disbursement application information
                $loanApplication = $this->loanApplicationRow($row['application_id']);
                // get arrears info
                $arrearsData = json_decode($row['arrears_info']);
                $arrearsCount = ((isset($arrearsData) && $arrearsData->arrears_count) ? (int)$arrearsData->arrears_count : 0);
                $totalArrearsDays = ((isset($arrearsData) && $arrearsData->total_days) ? (int)$arrearsData->total_days : 0);
                // Get the disbursement's first recovery date
                $firstRecoveryDate = new Time($row['first_recovery']);
                // Get the disbursement's loan expiry date
                $loanExpiryDate = new Time($row['loan_expiry_date']);

                # check the application products existance
                if ($loanApplication) {
                    $frequency = $loanApplication['repayment_frequency'];
                    $rate = $loanApplication['interest_rate'];
                    $interestPeriod = $loanApplication['interest_period'];
                    $rateType = $loanApplication['interest_type'];
                    $period = $loanApplication['repayment_period'];
                } else {
                    $frequency = $productRow['repayment_freq'];
                    $rate = $productRow['interest_rate'];
                    $interestPeriod = $productRow['interest_period'];
                    $rateType = $productRow['interest_type'];
                    $period = $productRow['repayment_period'];
                }

                # convert the loan interval per year
                $loanInterval = $this->settings->generateIntervals($frequency);
                # check the loan interest period
                if (strtolower($interestPeriod) === "year") {
                    $interval = $loanInterval['interval'];
                    $payouts = (12 / $interval);
                } else {
                    # code...
                    $interval = $payouts = 1;
                }

                # calculate principal and interest value per installment
                if (strtolower($rateType) == "reducing") {
                    $interest_installment = round(($row['principal_balance'] * ($rate / 100) / $payouts), 2);
                    $principal_installment = ($row['computed_installment'] - $interest_installment);
                } else {
                    $principal_installment = round(($row['principal'] / $installments_num), 2);
                    $interest_installment = round(($row['computed_installment'] - $principal_installment), 2);
                }

                # calculate days covered/remaining to loan maturity\expiry
                $total_daysLeft = ($currentDate->diff($loanExpiryDate)->format('%R%a'));
                if ($total_daysLeft >= 0) {
                    $days_remaing = $total_daysLeft;
                    $total_daysCovered = ((int)($row['loan_period_days']) - (int)$days_remaing);
                    $days_covered = $total_daysCovered;
                } else {
                    $installments_covered = $installments_num; // all installments are covered
                    $days_remaing = "0(Expired)";
                    $total_daysCovered = "Expired";
                    $days_covered = $row['loan_period_days'] . "(Expired)"; // make days coverd expired 
                    $class = 'Expired';  // maturity is due
                }

                # calculate installments covered based on the days covered
                if (($total_daysCovered != "Expired") && ($total_daysCovered >=  $row['grace_period'])) {
                    $installments_covered = (int)($days_covered / $row['grace_period']);
                }

                # reduct loan balance from savings if loan if expired & client has some balance on his savings account

                # calculate amounts expected to have been recoved as of now
                $expected_amount_recovered = $this->rountoffTo('round', ((float)$installments_covered * (float)$installmentAmt),);
                $expected_interest_recovered = $this->rountoffTo('round', ((float)$installments_covered * (float)$row['interest_installment']));
                $expected_principal_recovered = $this->rountoffTo('round', ((float)$installments_covered * (float)$row['principal_installment']));
                $expected_loan_balance = $this->rountoffTo('round', ((float)$row['actual_repayment'] - $expected_amount_recovered));

                # calculate disbursement balances as of now
                $interest_balance = ((float)$row['actual_interest'] - (float)$row['interest_collected']);
                $principal_balance = ((float)$row['principal'] - (float)$row['principal_collected']);
                $total_balance = ($interest_balance + $principal_balance);

                # calculate disbursement arrears
                if ($total_balance > $expected_loan_balance) {
                    $arrears = ($total_balance - $expected_loan_balance);
                }

                // auto update disbursement class
                if ($total_balance <= 1) {
                    $status = 'Fully Paid';
                    $class = 'Cleared';
                    $comments = "Disbursement Cleared";
                } else {
                    $status = 'Open';
                }
                # calculate number of missed installments
                $installments_due = round(($arrears / $installmentAmt), 2);

                /**
                 * If current date is before first recovery date, there are no arrears yet,
                 * Else: calculate the number of days overdue by taking the difference between the current date and the first recovery date
                 * then subtracting the number of days equivalent to the number of missed installments (installments_due * grace_period)
                 */
                if ($currentDate < $firstRecoveryDate) {
                    $days_due = 0;
                } else {
                    // Calculate the number of days in arrears
                    // $daysInArrears = $currentDate->diff($firstRecoveryDate)->format('%a') - $row['loan_period_days'];

                    // Calculate the number of days in arrears
                    $daysInArrears = $currentDate->diff($firstRecoveryDate)->format('%a');

                    // If daysInArrears is positive, set it as days_due, otherwise, set days_due to 0
                    $days_due = ($daysInArrears > 0) ? $daysInArrears : 0;
                }

                if ($arrears > 0) {
                    // calculate principle and interest due
                    if (strtolower($rateType) == "reducing") {
                        $interest_due = round(($arrears * ($rate / 100) * ($days_due / 365)), 2);
                    } else {
                        $interest_due = round(($arrears * ($rate / 100)), 2);
                    }
                    $principal_due = ($arrears - $interest_due);

                    // update disbursement status
                    if ($comments == "Missed Over 365 Days") {
                        $status = 'Defaulted';
                    }
                    // change disbursement class to arrears
                    $class = 'Arrears';

                    // Calculate number of times disbursement went into arrears
                    if (strtolower($row['class']) != 'arrears' && strtolower($class) == 'arrears') {
                        $arrearsCount = isset($arrears_info['count']) ? $arrears_info['count'] : 0;
                        $arrearsCount++;
                    }

                    // Calculate total days spent in arrears
                    $totalArrearsDays = isset($arrears_info['total_days']) ? $arrears_info['total_days'] : 0;
                    $totalArrearsDays += $days_due;

                    // Dont Update Days if loan is cleared 
                    if ($class == 'Cleared') {
                        $days_due = 0;
                        $arrearsCount = $arrears_info['count'];
                        $totalArrearsDays = $arrears_info['total_days'];
                    }

                    // Update arrears info for the current disbursement
                    $arrears_info = [
                        'arrears_count' => $arrearsCount,
                        'total_days' => $totalArrearsDays
                    ];
                    // auto  update comments
                    switch ($days_due) {
                        case ($days_due > 0 && $days_due <= 30):
                            $comments = "Missed (1 - 30) Days";
                            break;
                        case ($days_due > 30 && $days_due <= 60):
                            $comments = "Missed (31 - 60) Days";
                            break;
                        case ($days_due > 60 && $days_due <= 90):
                            $comments = "Missed (61 - 90) Days";
                            break;
                        case ($days_due > 90 && $days_due <= 120):
                            $comments = "Missed (91 - 120) Days";
                            break;
                        case ($days_due > 120 && $days_due <= 180):
                            $comments = "Missed (120 - 180) Days";
                            break;
                        case ($days_due > 180 && $days_due <= 365):
                            $comments = "Missed (120 -  365) Days";
                            break;
                        case ($days_due > 365):
                            $comments = "Missed Over 365 Days";
                            break;
                        default:
                            if (($row['interest_balance'] || $row['principal_balance'] || $row['total_balance']) < 0) {
                                $comments = "Data Entry Error";
                            } else {
                                $comments = "Okay";
                            }
                            break;
                    }
                } else {
                    $days_due = 0;
                }

                // update class to Expired
                if (($total_daysCovered == "Expired") && ($status == 'Open')) {
                    $class = 'Expired';
                }
                // auto update data
                $autoData = [
                    'principal_installment' => $principal_installment,
                    'interest_installment' => $interest_installment,
                    'days_remaining' => $days_remaing,
                    'days_covered' => $days_covered,
                    'installments_covered' => $installments_covered,
                    'expected_amount_recovered' => $expected_amount_recovered,
                    'expected_interest_recovered' => $expected_interest_recovered,
                    'expected_principal_recovered' => $expected_principal_recovered,
                    'expected_loan_balance' => $expected_loan_balance,
                    'interest_balance' => $interest_balance,
                    'principal_balance' => $principal_balance,
                    'total_collected' => ($row['interest_collected'] + $row['principal_collected']),
                    'total_balance' => $total_balance,
                    'arrears' => $arrears,
                    'arrears_info' => json_encode($arrears_info),
                    'principal_due' => $principal_due,
                    'interest_due' => $interest_due,
                    'installments_due' => $installments_due,
                    'days_due' => $days_due,
                    'status' => $status,
                    'class' => $class,
                    'comments' => $comments,
                ];
                # reduct arrears from client savings should maturity have reached
                if (($arrears > 0) && ($class == 'Expired')) {
                    $clientData = $this->checkArrayExistance('client', ['id' => $client_id]);
                    $clientAccBalance = $clientData['account_balance'];
                    // get savings particular to act as payment method
                    $paymentParticular = $this->particular->where(['account_typeId' => 12])->first();
                    // call reduct_arrears_from_savings()
                    if ($clientAccBalance && $clientAccBalance > 0) {
                        if ($clientAccBalance > $arrears) {
                            $arrearsAmt = $arrears;
                        } else {
                            $arrearsAmt = $clientAccBalance;
                        }
                        $dataArray = [
                            'account_typeId' => 3,
                            'disbursement_id' => $row['id'],
                            'client_id' => $client_id,
                            'particular_id' => $row['particular_id'],
                            'payment_id' => $paymentParticular['id'],
                            'arrearsAmt' => $arrearsAmt,
                        ];
                        $this->reduct_arrears_from_savings($dataArray);
                    }
                }
                # update the dusbursement information 
                $updateDays = $this->disbursement->update($row['id'], $autoData);
            }

            if ($updateDays) {
                $response = [
                    'status' => 200,
                    'error' => null,
                    'message' => 'Auto Disbursment Fields Update Success',
                ];
                return $this->respond($response);
                exit;
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Update Failed',
                    'message' => 'Auto Disbursment Fields Update Failed!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'message' => 'No Disbursments Found!',
            ];
            return $this->respond($response);
            exit;
        }
    }
    protected function auto_disbursementState()
    {
        $allDisbursements = $this->disbursement->where(['class !=' => 'Cleared'])->findAll();
        if (empty($allDisbursements)) {
            return $this->respond([
                'status' => 404,
                'error' => 'Not Found',
                'message' => 'No Disbursements Found!',
            ]);
        }

        $currentDate = Time::today(); // Get current date
        $updateDays = false;
        $responseUpdates = [];

        foreach ($allDisbursements as $row) {
            $arrears_info = [
                'arrears_count' => 0,
                'total_days' => 0,
            ];

            $installmentAmt = $row['actual_installment'];
            $status = $row['status'];
            $class = $row["class"];
            $client_id = $row["client_id"];
            $principal_due = $row["principal_due"];
            $interest_due = $row["interest_due"];
            $installments_due = $row["installments_due"];

            // Fetch product and application info in one go
            $productRow = $this->loanProduct->find($row['product_id']);
            $loanApplication = $this->loanApplicationRow($row['application_id']);

            // Arrears info
            $arrearsData = json_decode($row['arrears_info'], true);
            $arrearsCount = $arrearsData['arrears_count'] ?? 0;
            $totalArrearsDays = $arrearsData['total_days'] ?? 0;

            // Dates
            $firstRecoveryDate = new Time($row['first_recovery']);
            $loanExpiryDate = new Time($row['loan_expiry_date']);

            // Setup frequency, rate, and other parameters
            if ($loanApplication) {
                $frequency = $loanApplication['repayment_frequency'];
                $rate = $loanApplication['interest_rate'];
                $interestPeriod = $loanApplication['interest_period'];
                $rateType = $loanApplication['interest_type'];
                $period = $loanApplication['repayment_period'];
            } else {
                $frequency = $productRow['repayment_freq'];
                $rate = $productRow['interest_rate'];
                $interestPeriod = $productRow['interest_period'];
                $rateType = $productRow['interest_type'];
                $period = $productRow['repayment_period'];
            }

            // Calculate loan intervals
            $loanInterval = $this->settings->generateIntervals($frequency);
            $payouts = (strtolower($interestPeriod) === "year") ? (12 / $loanInterval['interval']) : 1;

            // Calculate installment values
            if (strtolower($rateType) == "reducing") {
                $interest_installment = round(($row['principal_balance'] * ($rate / 100) / $payouts), 2);
                $principal_installment = ($row['computed_installment'] - $interest_installment);
            } else {
                $principal_installment = ($row['installments_num'] > 0) ? round(($row['principal'] / $row['installments_num']), 2) : 0;
                $interest_installment = round(($row['computed_installment'] - $principal_installment), 2);
            }

            // Calculate days covered/remaining
            $days_remaing = $currentDate->diff($loanExpiryDate)->format('%R%a');
            $days_covered = ($days_remaing >= 0) ? ((int)$row['loan_period_days'] - (int)$days_remaing) : '0(Expired)';
            $installments_covered = ($days_remaing > 0) ? (int)($days_covered / $row['grace_period']) : $row['installments_num'];

            // Calculate expected amounts and balances
            $expected_amount_recovered = $this->rountoffTo('round', $installments_covered * $installmentAmt);
            $expected_loan_balance = $this->rountoffTo('round', $row['actual_repayment'] - $expected_amount_recovered);
            $interest_balance = ((float)$row['actual_interest'] - (float)$row['interest_collected']);
            $principal_balance = ((float)$row['principal'] - (float)$row['principal_collected']);
            $total_balance = $interest_balance + $principal_balance;

            // Calculate arrears
            $arrears = max(0, $total_balance - $expected_loan_balance);
            $installments_due = ($installmentAmt > 0) ? round(($arrears / $installmentAmt), 2) : 0;
            $status = ($total_balance <= 1) ? 'Fully Paid' : 'Open';
            $class = ($total_balance <= 1) ? 'Cleared' : $class;

            // Calculate days overdue
            $days_due = ($currentDate < $firstRecoveryDate) ? 0 : max(0, $currentDate->diff($firstRecoveryDate)->format('%a'));

            // Calculate due amounts if arrears exist
            if ($arrears > 0) {
                if (strtolower($rateType) == "reducing") {
                    $interest_due = round(($arrears * ($rate / 100) * ($days_due / 365)), 2);
                } else {
                    $interest_due = round(($arrears * ($rate / 100)), 2);
                }
                $principal_due = $arrears - $interest_due;
                $class = 'Arrears';
                $comments = $this->getArrearsComments($days_due, $row); // Extracted comment logic

                // Update arrears info
                $arrears_info['arrears_count'] = ++$arrearsCount;
                $arrears_info['total_days'] += $days_due;
            } else {
                $days_due = 0;
                $comments = ($row['interest_balance'] || $row['principal_balance'] || $row['total_balance']) < 0 ? "Data Entry Error" : "Okay";
            }

            // Update class to Expired
            if ($days_covered == "Expired" && $status == 'Open') {
                $class = 'Expired';
            }

            // Prepare data for update
            $autoData = [
                'principal_installment' => $principal_installment,
                'interest_installment' => $interest_installment,
                'days_remaining' => $days_remaing,
                'days_covered' => $days_covered,
                'installments_covered' => $installments_covered,
                'expected_amount_recovered' => $expected_amount_recovered,
                'expected_loan_balance' => $expected_loan_balance,
                'interest_balance' => $interest_balance,
                'principal_balance' => $principal_balance,
                'total_balance' => $total_balance,
                'arrears' => $arrears,
                'arrears_info' => json_encode($arrears_info),
                'principal_due' => $principal_due,
                'interest_due' => $interest_due,
                'installments_due' => $installments_due,
                'days_due' => $days_due,
                'status' => $status,
                'class' => $class,
                'comments' => $comments,
            ];

            // Update disbursement
            $responseUpdates[] = $this->disbursement->update($row['id'], $autoData);
            $updateDays = $updateDays || $responseUpdates[count($responseUpdates) - 1];
        }

        return $this->respond([
            'status' => $updateDays ? 200 : 500,
            'error' => $updateDays ? null : 'Update Failed',
            'message' => $updateDays ? 'Auto Disbursement Fields Update Success' : 'Auto Disbursement Fields Update Failed!',
        ]);
    }
    private function getArrearsComments($days_due, $row)
    {
        switch (true) {
            case ($days_due > 0 && $days_due <= 30):
                return "Missed (1 - 30) Days";
            case ($days_due > 30 && $days_due <= 60):
                return "Missed (31 - 60) Days";
            case ($days_due > 60 && $days_due <= 90):
                return "Missed (61 - 90) Days";
            case ($days_due > 90 && $days_due <= 120):
                return "Missed (91 - 120) Days";
            case ($days_due > 120 && $days_due <= 180):
                return "Missed (120 - 180) Days";
            case ($days_due > 180):
                return "Missed (180+ Days)";
            default:
                return ($row['interest_balance'] || $row['principal_balance'] || $row['total_balance']) < 0 ? "Data Entry Error" : "Okay";
        }
    }

    // auto calculate & update disbursement balance
    protected function auto_update_disbursementBalances()
    {
        $loan_account_typeId  = 3; // Gross principal account Type ID
        $interest_account_typeId = 19; // Interest on loans account type

        // Get distinct disbursement IDs
        $disbursementIds = $this->entry->distinct()->select('disbursement_id')
            ->where(['account_typeId' => $loan_account_typeId])
            ->findColumn('disbursement_id');

        if (!$disbursementIds) {
            return $this->respond([
                'status' => 404,
                'error' => 'Not Found',
                'message' => "No Disbursement IDs Found",
            ]);
        }

        // Preload disbursement rows
        $disbursements = $this->disbursement->whereIn('id', $disbursementIds)->findAll();
        if (!$disbursements) {
            return $this->respond([
                'status' => 404,
                'error' => 'Not Found',
                'message' => "No Disbursements Found",
            ]);
        }
        $disbursementMap = array_column($disbursements, null, 'id'); // Maps disbursement_id to its row

        $columnSelect = 'date, payment_id, particular_id, account_typeId, client_id, disbursement_id, entry_typeId, amount, status';
        $updates = [];

        // Fetch all entries in a single query
        $entries = $this->entry->select($columnSelect)
            ->whereIn('disbursement_id', $disbursementIds)
            ->findAll();
        if (!$entries) {
            return $this->respond([
                'status' => 404,
                'error' => 'Not Found',
                'message' => "No Entries Found",
            ]);
        }
        // Preload interest particulars (used repeatedly)
        $interestParticular = $this->particular->where(['account_typeId' => $interest_account_typeId])->first();
        if (!$interestParticular) {
            return $this->respond([
                'status' => 500,
                'error' => 'Not Found',
                'message' => "No Interest Particular Found",
            ]);
        }
        $interest_particularId = $interestParticular['id'];

        // Group entries by disbursement_id
        $entriesByDisbursement = [];
        foreach ($entries as $entry) {
            $entriesByDisbursement[$entry['disbursement_id']][] = $entry;
        }

        // Loop through each disbursement and calculate the balances
        foreach ($disbursementMap as $disbursement_id => $disbursementRow) {
            if (strtolower($disbursementRow['class']) == 'cleared') {
                // Handle cleared disbursements
                $disbursementData = [
                    'principal_balance' => 0,
                    'interest_balance' => 0,
                    'total_balance' => 0,
                    'interest_collected' => $disbursementRow['actual_interest'],
                    'principal_collected' => $disbursementRow['principal'],
                    'total_collected' => $disbursementRow['actual_repayment']
                ];
            } else {
                // Calculate balances for non-cleared disbursements
                $principalCollected = $interestCollected = 0;
                foreach ($entriesByDisbursement[$disbursement_id] as $entry) {
                    if (($entry['particular_id'] == $disbursementRow['particular_id']) && ($entry['status'] == 'credit')) {
                        $principalCollected += $entry['amount'];
                    }
                    if (($entry['particular_id'] == $interest_particularId) && ($entry['status'] == 'credit')) {
                        $interestCollected += $entry['amount'];
                    }
                }

                $totalCollected = $principalCollected + $interestCollected;

                // Prepare the data to update the disbursement
                $disbursementData = [
                    'interest_collected' => round($interestCollected, 2),
                    'principal_collected' => round($principalCollected, 2),
                    'total_collected' => round($totalCollected, 2),
                ];
            }

            // Add the update to the batch
            $updates[$disbursement_id] = $disbursementData;
        }

        // Perform batch updates
        foreach ($updates as $disbursement_id => $disbursementData) {
            $this->disbursement->update($disbursement_id, $disbursementData);
        }

        return $this->respond([
            'status' => 200,
            'error' => null,
            'message' => "Auto Disbursement Balance Success",
        ]);
    }

    protected function auto_update_disbursementBalancesOld()
    {
        $loan_account_typeId  = 3; // Gross principal account Type ID
        $interest_account_typeId = 19; // revenue from loans id

        // Get distinct disbursement_id values
        $disbursementIds = $this->entry->distinct()->select('disbursement_id')->where(['account_typeId' => $loan_account_typeId])->findColumn('disbursement_id');

        if ($disbursementIds) {
            // select columns to select from entries table
            $columnSelect = 'date, payment_id, particular_id, account_typeId, client_id,, disbursement_id, entry_typeId, amount';
            $updateBalance = false;
            // loop through disbursement IDs
            foreach ($disbursementIds as $disbursement_id) {
                // get client data for each iteration
                $disbursementRow = $this->disbursement->find($disbursement_id);
                if ($disbursementRow) {
                    // update only when disbursement is not cleared
                    if ((strtolower($disbursementRow['class']) != 'cleared')) {
                        //  || (strtolower($disbursementRow['class']) != 'fully paid')
                        $principal_particularId = $disbursementRow['particular_id']; // gross loan principal particular id
                        $interestParticular = $this->particular->where([
                            'account_typeId' => $interest_account_typeId
                        ])->first();
                        if ($interestParticular) {
                            $interest_particularId = $interestParticular['id']; // interest on loans particular id
                            // initialize variables
                            $totalCollected = $principalCollected = $interestCollected = 0;
                            # calculate disbursement principal total
                            $disbursementPrincipalEntries = $this->entry->select($columnSelect)->where(['particular_id' => $principal_particularId, 'disbursement_id' => $disbursement_id, 'account_typeId' => $loan_account_typeId, 'status' => 'credit'])->findAll();
                            if (count($disbursementPrincipalEntries) > 0) {
                                // calculate total principal
                                foreach ($disbursementPrincipalEntries as $principalEntry) {
                                    $principalCollected += $principalEntry['amount'];
                                }
                            }

                            # calculate disbursement interest total
                            $disbursementInterestEntries = $this->entry->select($columnSelect)->where(['particular_id' => $interest_particularId, 'disbursement_id' => $disbursement_id, 'account_typeId' => $loan_account_typeId, 'status' => 'credit'])->findAll();
                            if (count($disbursementInterestEntries) > 0) {
                                // calculate total interest
                                foreach ($disbursementInterestEntries as $interestEntry) {
                                    $interestCollected += $interestEntry['amount'];
                                }
                            }
                            /**
                             * calculate disbursement total balance
                             *  since disbursement[loan principal] are debit[assets] by nature,
                             * put -ve if its less than 0
                             */
                            $totalCollected = ($principalCollected + $interestCollected);

                            # disbursement update data
                            $disbursementData = [
                                'interest_collected' => round($interestCollected, 2),
                                'principal_collected' => round($principalCollected, 2),
                                'total_collected' => round($totalCollected, 2),
                            ];

                            # update client disbursement balance
                            $updateBalance = $this->disbursement->update($disbursement_id, $disbursementData);
                        } else {
                            continue;
                        }
                    } else {
                        if (($disbursementRow['interest_collected'] != 0) || ($disbursementRow['principal_collected'] != 0) || ($disbursementRow['total_collected'] != 0)) {
                            $disbursementData = [
                                'principal_balance' => 0,
                                'interest_balance' => 0,
                                'total_balance' => 0,
                                'interest_collected' => $disbursementRow['actual_interest'],
                                'principal_collected' => $disbursementRow['principal'],
                                'total_collected' => $disbursementRow['actual_repayment']
                            ];

                            # update client disbursement balance
                            $updateBalance = $this->disbursement->update($disbursement_id, $disbursementData);
                        } else {
                            continue;
                        }
                    }
                } else {
                    continue;
                }
            }
            if ($updateBalance) {
                return $this->respond([
                    'status' => 200,
                    'error' => null,
                    'message' => "Auto Disbursment Balance Success",
                ]);
                exit;
            } else {
                return $this->respond([
                    'status' => 500,
                    'error' => 'Action Failed',
                    'message' => "No Auto Disbursment Balance Update Implemented",
                ]);
                exit;
            }
        } else {
            return $this->respond([
                'status' => 404,
                'error' => 'Not Found',
                'message' => "No Disbursment Ids Found",
            ]);
            exit;
        }
    }

    // validate saving product balance
    protected function validateSavingsProductBalances($data)
    {
        // get product row
        $productRow = $this->product->find($data['id']);
        // get/set product min & max balance settings
        if ($productRow) {
            $minSavingsEntry = (($productRow['min_per_entry']) ? (float)$productRow['min_per_entry'] : 0);
            $minSavingsBal = (($productRow['min_account_balance']) ? (float)$productRow['min_account_balance'] : 0);
            $maxSavingsEntry = (($productRow['max_per_entry']) ? (float)$productRow['max_per_entry'] : 0);
            $maxSavingsBal = (($productRow['max_account_balance']) ? (float)$productRow['max_account_balance'] : 0);
        } else {
            return [
                'status' => 404,
                'error' => 'Invalid Product!',
                'messages' => 'Selected Product could not be found!',
            ];
            exit;
        }
        if ($data['amount'] < $minSavingsEntry) {
            return [
                'status' => 500,
                'error' => 'Min Transaction Limit!',
                'messages' => ucwords($productRow['product_name']) . ' Minimum Transaction limit of ' . $this->settingsRow['currency'] . ' ' . $minSavingsEntry . ' not reached!',
            ];
            exit;
        }
        if ($data['amount'] > $maxSavingsEntry) {
            return [
                'status' => 500,
                'error' => 'Max Transaction Limit!',
                'messages' => ucwords($productRow['product_name']) . ' Maximum Transaction limit of ' . $this->settingsRow['currency'] . ' ' . $maxSavingsEntry . ' exceeded!',
            ];
            exit;
        }
        # validate if amount to be saved corresponds with product settings
        if (strtolower($data['status']) == 'debit') { // withdrawing savings
            if (($minSavingsBal > 0) && ($minSavingsBal >= $data['product_balance'])) {
                return [
                    'status' => 500,
                    'error' => 'Min Savings Reached!',
                    'messages' => ucwords($productRow['product_name']) . ' Minimum Balance of ' . $this->settingsRow['currency'] . ' ' . $minSavingsBal . ' reached!',
                ];
                exit;
            }
        } elseif (strtolower($data['status']) == 'credit') { // collecting savings
            if (($maxSavingsBal > 0) && ($maxSavingsBal <= $data['product_balance'])) {
                return [
                    'status' => 500,
                    'error' => 'Max Savings Reached!',
                    'messages' => ucwords($productRow['product_name']) . ' Maximum Balance of ' . $this->settingsRow['currency'] . ' ' . $maxSavingsBal . ' reached!',
                ];
                exit;
            }
        } else {
            return [
                'status' => 200,
                'error' => false,
                'messages' => '',
            ];
            // return [
            //     'status' => 500,
            //     'error' => 'Invalid Status',
            //     'messages' => "Transaction status '". strtoupper($data['status']) ."' is invalid",
            // ];
            exit;
        }
    }
    protected function reduct_arrears_from_savings(array $data)
    {
        # pick from data array
        $account_typeId = $data['account_typeId'];
        $disbursement_id = $data['disbursement_id'];
        $client_id = $data['client_id'];
        $loanParticular_id = $data['particular_id'];
        $payment_id = $data['payment_id'];
        $amount = $data['arrearsAmt'];

        # get account type data
        $account_typeInfo = $this->accountType->find($account_typeId);
        if (!$account_typeInfo) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Account Type could not be found!',
            ];
            return $this->respond($response);
            exit;
        }

        # get disbursement data
        $disbursementInfo = $this->loanDisbursementRow($disbursement_id);
        if (!$disbursementInfo) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Disbursement could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        $actualInstallment = $disbursementInfo['actual_installment'];

        # get client data
        $clientInfo = $this->client->find($client_id);
        if (!$clientInfo) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Client could not be found!',
            ];
            return $this->respond($response);
            exit;
        }

        // get transaction type data
        $transaction_typeRow = $this->entryType->where(['account_typeId' => $account_typeId, 'part' => 'credit'])->first();
        if (!$transaction_typeRow) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Transaction Type could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        $entry_typeId = $transaction_typeRow['id'];
        // entry_menu of transaction based on entry type
        $entry_menu = $transaction_typeRow['entry_menu'];
        // status of transaction based on entry type
        $status = $transaction_typeRow['part'];

        // get loan particular data
        $loanParticularRow = $this->particularDataRow($loanParticular_id);
        if (!$loanParticularRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Particular data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }

        $interest_account_typeId = 19; // revenue from loans id
        # get revenue from loan account info
        $interestAccountType = $this->accountType->where(['status' => 'Active'])
            ->find($interest_account_typeId);
        if (!$interestAccountType) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'Account Type: Revenue from Loan Repayments is not found!',
            ];
            return $this->respond($response);
            exit;
        }
        # get revenue from loan particular info
        $interestParticular = $this->particular->where([
            'account_typeId' => $interest_account_typeId
        ])->first();

        if (!$interestParticular) {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'No ' . $interestAccountType['name'] . ' Particular found!',
            ];
            return $this->respond($response);
            exit;
        }
        $interest_particularId = $interestParticular['id']; // interest on loans id

        // get interest particular data
        $interestParticularRow = $this->particularDataRow($interest_particularId);

        // get payment method data
        $paymentRow = $this->particularDataRow($payment_id);
        // check existence of accounting particulars
        if (!$paymentRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Payment method data could not be found!',
            ];
            return $this->respond($response);
            exit;
        }
        /** 
         * update loan balance and perform double entry
         * since account type is for assets category[Gross Loans - Principal],
         * debit particular & credit payment method & interest on loan[revenue] if its gaining,
         * credit particular & debit payment method & interest on loan[revenue] if its loosing,
         */
        // compute payment towards principal and interest respectively
        $interestInstallment = $interval = $payouts = 0;
        $interestCollected = $principalCollected = $totalCollected = 0;

        $frequency = $disbursementInfo['repayment_frequency'];
        $rate = $disbursementInfo['interest_rate'];
        $interestPeriod = $disbursementInfo['interest_period'];
        $rateType = $disbursementInfo['interest_type'];
        $period = $disbursementInfo['repayment_period'];

        # convert the loan internal per year
        $loanInterval = $this->settings->generateIntervals($frequency);
        # check the loan interest period
        if (strtolower($interestPeriod) == "year") {
            $interval = $loanInterval['interval'];
            $payouts = (12 / $interval);
        } else {
            # code...
            $interval = $payouts = 1;
        }

        // calculate interest installment
        if (strtolower($rateType) == 'reducing') {
            $interestInstallment = round(($disbursementInfo['principal_balance']) * ($rate / 100), 2) / ($payouts);
        }
        if (strtolower($rateType) == 'flat') {
            $interestInstallment = round(($disbursementInfo['principal']) * ($rate / 100), 2) / ($payouts);
        }
        # set parameters to calculate the loan repayment amount
        $loan = [
            'repayment' => $amount,
            'monthlyInstallment' => $actualInstallment,
            'monthlyInterest' => $interestInstallment,
            'totalInterestCollected' => $disbursementInfo['interest_collected'],
            'totalInterest' => $disbursementInfo['actual_interest'],
            'principal' => $disbursementInfo['principal_balance'],
            'interestBalance' => $disbursementInfo['interest_balance'],
            'originalPrincipal' => $disbursementInfo['principal'],
            'totalPrincipalPaid' => $disbursementInfo['principal_collected']
        ];
        # get the loan repayment calculations
        $data = $this->disbursement->calculateLoanRepayment($loan);
        # get the principal repayment
        $principalRepayment = $data['principalRepayment'];
        # get the interest repayment
        $interestRepayment = $data['interestRepayment'];
        # get the overpayment balance
        $balanceOnRepaymentAmt = $data['overpaymentBalance'];
        # get the principal Balance
        $principalBalance = $data['principalBalance'];
        # get the interest Balance
        $interestBalance = $data['interestPayableBalance'];
        # get the total loan outstanding balance
        $totalLoanBalance = $data['totalLoanBalance'];
        # get the total interest collected
        $interestCollected = $data['interestCollected'];
        # get the total principal collected
        $principalCollected = $data['principalCollected'];
        # get the total principal and interest collected
        $totalCollected = $data['totalLoanCollection'];
        # $totalCollected = ($disbursementInfo['total_collected'] + $amount);

        // calculate entries total amount per entry status & final balance
        $entriesStatusTotals = $this->entry->calculateTotalBalance([
            'module' => 'particular',
            'module_id' => $loanParticular_id,
            'status' => $loanParticularRow['part']
        ]);
        $principalAccountingBalance = (float)($entriesStatusTotals['totalBalance'] + $amount); // calculate balance for primary paticular as of this entry
        $entriesStatusTotals_interest = $this->entry->calculateTotalBalance([
            'module' => 'particular',
            'module_id' => $interest_particularId,
            'status' => $interestParticularRow['part']
        ]);
        $interestAccountingBalance = (float)($entriesStatusTotals_interest['totalBalance'] + $amount); # Implement double entry
        if (strtolower($status) == 'credit') {
            // credit\reduce Gross Loans - Principal particular[assets]
            $loanParticularBal = ['credit' => ((float)$loanParticularRow['credit'] + $principalRepayment)];
            // credit\add Interest on Loans particular[revenue]
            $interestParticularBal = ['credit' => ((float)$interestParticularRow['credit'] + $interestRepayment)];
            // debit selected payment method[assets]
            $paymentParticularBal = ['debit' => ((float)$paymentRow['debit'] + $amount)];
        }
        if (strtolower($status) == 'debit') {
            // debit/add Gross Loans - Principal particular[assets]
            $loanParticularBal = ['debit' => ((float)$loanParticularRow['debit'] + $amount)];
            // debit\reduce Interest on Loans particular[revenue]
            $interestParticularBal = ['debit' => ((float)$interestParticularRow['debit'] + $amount)];
            // credit selected payment method[assets]
            $paymentParticularBal = ['credit' => ((float)$paymentRow['credit'] + $amount)];
        }

        if (!empty($this->request->getVar('date'))) {
            $date = trim($this->request->getVar('date'));
        } else {
            $date = date('Y-m-d');
        }
        $ref_id = $this->settings->generateReference();
        # Principal installment transaction data
        $principal_installmentEntry = [
            'date' => $date,
            'payment_id' => $payment_id,
            'particular_id' => $loanParticular_id,
            'branch_id' => $this->userRow['branch_id'],
            'staff_id' => $this->userRow['staff_id'],
            'client_id' => $client_id,
            'disbursement_id' => $disbursement_id,
            'entry_menu' => $entry_menu,
            'entry_typeId' => $entry_typeId,
            'account_typeId' => $account_typeId,
            'ref_id' => $ref_id,
            'amount' => $principalRepayment,
            'status' => $status,
            'balance' => $principalAccountingBalance,
            'contact' => trim($this->request->getVar('contact')),
            'entry_details' => 'Direct Deduction of amount ' . $this->settingsRow['currency'] . ' ' . $amount . ' from ' . $clientInfo['name'] . '\'s Savings account to clear Disbursement Arrears',
            'remarks' => 'Direct Deduction',
        ];
        # Interest installment transaction data
        $interest_installmentEntry = [
            'date' => $date,
            'payment_id' => $payment_id,
            'particular_id' => $interest_particularId,
            'branch_id' => $this->userRow['branch_id'],
            'staff_id' => $this->userRow['staff_id'],
            'client_id' => $client_id,
            'disbursement_id' => $disbursement_id,
            'entry_menu' => $entry_menu,
            'entry_typeId' => $entry_typeId,
            'account_typeId' => $account_typeId,
            'ref_id' => $ref_id,
            'amount' => $interestRepayment,
            'status' => $status,
            'balance' => $interestAccountingBalance,
            'contact' => trim($this->request->getVar('contact')),
            'entry_details' => 'Direct Deduction of amount ' . $this->settingsRow['currency'] . ' ' . $amount . ' from ' . $clientInfo['name'] . '\'s Savings account to clear Disbursement Arrears',
            'remarks' => 'Direct Deduction',
        ];
        // only principal transaction data
        if ($principalRepayment > 0) {
            $transactionData[] = $principal_installmentEntry;
        }
        // only interest transaction data
        if ($interestRepayment > 0) {
            $transactionData[] = $interest_installmentEntry;
        }
        // both principal & interest transactions data
        if (($principalRepayment > 0) && ($interestRepayment > 0)) {
            $transactionData[] = $principal_installmentEntry;
            $transactionData[] = $interest_installmentEntry;
        }
        # Save only Principal Transaction
        if (($principalRepayment > 0) && ($interestRepayment == 0)) {
            $saveTransaction = $this->entry->insert($principal_installmentEntry);
        }
        # Save only Interest Transaction
        if (($principalRepayment == 0) && ($interestRepayment > 0)) {
            $saveTransaction = $this->entry->insert($interest_installmentEntry);
        }
        # Save both Loan Repayment Transactions
        if (($principalRepayment > 0) && ($interestRepayment > 0)) {
            $saveTransaction = $this->entry->insertBatch($transactionData);
        }

        if ($saveTransaction) {
            # Update the loan interest and principal collected
            $disbursementData = [
                'total_balance' => $totalLoanBalance,
                'interest_collected' => round($interestCollected, 2),
                'principal_collected' => round($principalCollected, 2),
                'total_collected' => round($totalCollected, 2),
            ];
            $updateDisbursementBal = $this->disbursement->update($disbursement_id, $disbursementData);
            if ($updateDisbursementBal) {
                #update the new client savings account balance
                $new_balance = ((float)$clientInfo['account_balance'] - $amount);
                $savingsBal = $this->client->update($client_id, ['account_balance' => $new_balance]);
                if (!$savingsBal) {
                    $response = [
                        'status'   => 500,
                        'error'    => 'Transaction Failed',
                        'messages' => 'An Error occurred while Updating Client Account Balance, Try again!',
                    ];
                    return $this->respond($response);
                    exit;
                }

                # Push overpayment to the client savings account
                if ($balanceOnRepaymentAmt > 0) {
                    $this->push_excessAmount_to_client_savings([
                        'client_id' => $client_id,
                        'amount' => $balanceOnRepaymentAmt,
                        'payment_id' => $payment_id,
                        'from' => $account_typeInfo['name']
                    ]);
                }
                # Update the accounting particulars balances
                $loanParticular_idBal = $this->particular->update($loanParticular_id, $loanParticularBal);
                $interestParticular_idBal = $this->particular->update($interest_particularId, $interestParticularBal);
                $payment_idBal = $this->particular->update($payment_id, $paymentParticularBal);
                if ($loanParticular_idBal && $interestParticular_idBal  && $payment_idBal) {
                    # Send transaction notification email to client
                    if ($disbursementInfo['email'] != '') {
                        $disbursementInfo['branch_name'] = $this->userRow['branch_name'];
                        $disbursementInfo['amount'] = $amount;
                        $disbursementInfo['interestCollected'] = $interestCollected;
                        $disbursementInfo['principalCollected'] = $principalCollected;
                        $disbursementInfo['totalCollected'] = $totalCollected;
                        $disbursementInfo['ref_id'] = $ref_id;
                        $disbursementInfo['date'] = date('d-m-Y H:i:s');
                        $disbursementInfo['entry_details'] = 'Direct Deduction of amount ' . $this->settingsRow['currency'] . ' ' . $amount . ' from ' . $clientInfo['name'] . '\'s Savings account to clear Disbursement Arrears';
                        $disbursementInfo['account_typeID'] = $account_typeId;
                        $disbursementInfo['type'] = $transaction_typeRow['type'];
                        $disbursementInfo['particular_name'] = $loanParticularRow['particular_name'];
                        $disbursementInfo['payment_mode'] = $paymentRow['particular_name'];
                        $disbursementInfo['repayment_period'] = $period;
                        # the principal balance
                        $disbursementInfo['principalBalance'] = $principalBalance;
                        # get the interest Balance
                        $disbursementInfo['interestBalance'] = $interestBalance;
                        # get the total loan outstanding balance
                        $disbursementInfo['totalLoanBalance'] = $totalLoanBalance;

                        # email message
                        $message = $disbursementInfo;
                        $checkInternet = $this->settings->checkNetworkConnection();
                        if ($checkInternet) {
                            $subject = 'New ' . $loanParticularRow['particular_name'] . ' Transaction';
                            $message = $message;
                            $token = 'transaction';
                            # $this->settings->sendMail($message, $subject, $token);
                            $response = [
                                'status' => 200,
                                'error' => null,
                                'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. Email Sent'
                            ];
                            return $this->respond($response);
                        } else {
                            $response = [
                                'status' => 200,
                                'error' => null,
                                'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully. No Internet'
                            ];
                            return $this->respond($response);
                            exit;
                        }
                    } else {
                        $response = [
                            'status' => 200,
                            'error' => null,
                            'messages' => $transaction_typeRow['type'] . ' ' . $this->title . ' created successfully.',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status'   => 500,
                        'error'    => 'Accounting Error',
                        'messages' => 'Implementing Double Entry failed, Client Loan Balance updated!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Balance Update Failed',
                    'messages' => 'Updating Client loan balance Failed, Transaction recorded successfully!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 500,
                'error'    => 'Transaction Failed',
                'messages' => 'An Error occured while creating Transaction, Try again!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    public function fetch_history()
    {
        $history = $this->request->getVar('history');
        $client_id = $this->request->getVar('client_id');
        // fetch the history based on the history menu selected
        switch (strtolower($history)) {
            case 'loans':
                // get client application history excluding the current application
                $applications = $this->loanApplication->where(['client_id' => $client_id])->findAll();
                // get client disbursement history excluding the current disbursement
                $disbursements = $this->disbursement->where(['client_id' => $client_id])->findAll();

                $historyArray = [];
                # verify that either client has got any applications or disbursements history
                if ($applications) {
                    if ($applications && (count($applications) > 0)) {
                        foreach ($applications as $application) {
                            $historyArray['applications'][] = $this->loanApplicationRow($application['id']);
                        }
                    } else {
                        $historyArray['applications'][] = [];
                    }
                }
                if ($disbursements) {
                    if ($disbursements && (count($disbursements) > 0)) {
                        foreach ($disbursements as $disbursement) {
                            $historyArray['disbursements'][] = $this->loanDisbursementRow($disbursement['id']);
                        }
                    } else {
                        $historyArray['disbursements'][] = [];
                    }
                }
                if (!$applications && !$disbursements) {
                    $historyArray = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'No Client ' . ucfirst($history) . ' History Found!',
                    ];
                }

                $response = $historyArray;
                break;
            default:
                $response = [
                    'status' => 404,
                    'error' => 'Invalid Option',
                    'messages' => ucfirst($history) . ' history couldn\'t be retrieved!',
                ];
                break;
        }
        return $this->respond($response);
    }

    // round \ ceil to next factor
    public function rountoffTo($action, $num, $factor = 1)
    {
        $quotient = $num / $factor;
        if ($action == 'round') {
            $res = round($quotient) * $factor;
        } else {
            $res = ceil($quotient) * $factor;
        }
        return $res;
    }
}
