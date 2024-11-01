<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    /*
    protected $DBGroup          = 'default';
    protected $table            = 'reports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    */
    protected $db;
    private $_user;
    private $_year;
    private $_startDate;
    private $_endDate;
    private $_entry_status;
    private $_search;
    private $_account_no;
    private $_gender;
    private $_payment_id;
    private $_entry_typeId;
    private $_reference_id;
    private $_branch_id;
    private $_staff_id;
    private $_department;
    private $_position;
    private $_appointment_type;
    private $_account_type;
    private $_fieldName01;
    private $_fieldName02;
    private $_fieldName03;
    private $_fieldName04;
    private $_fieldName05;
    private $_fieldName06;
    private $_fieldName07;
    private $_fieldName08;

    public function __construct()
    {
        parent::__construct();
        # load database connection
        $this->db = \Config\Database::connect();
    }

    public function setUserAccountType($user)
    {
        $this->_user = $user;
    }

    public function setFieldName01($value)
    {
        $this->_fieldName01 = $value;
    }

    public function setFieldName02($value)
    {
        $this->_fieldName02 = $value;
    }

    public function setFieldName03($value)
    {
        $this->_fieldName03 = $value;
    }

    public function setFieldName04($value)
    {
        $this->_fieldName04 = $value;
    }

    public function setFieldName05($value)
    {
        $this->_fieldName05 = $value;
    }

    public function setFieldName06($value)
    {
        $this->_fieldName06 = $value;
    }

    public function setFieldName07($value)
    {
        $this->_fieldName07 = $value;
    }

    public function setFieldName08($value)
    {
        $this->_fieldName08 = $value;
    }


    public function setYear($year)
    {
        $this->_year = $year;
    }

    public function setStartDate($startDate)
    {
        $this->_startDate = $startDate;
    }

    public function setEndDate($endDate)
    {
        $this->_endDate = $endDate;
    }

    public function setEntryStatus($entry_status)
    {
        $this->_entry_status = $entry_status;
    }

    public function setSearch($search)
    {
        $this->_search = $search;
    }

    public function setAccountNo($account_no)
    {
        $this->_account_no = $account_no;
    }

    public function setGender($gender)
    {
        $this->_gender = $gender;
    }

    public function setPaymentMethod($payment_id)
    {
        $this->_payment_id = $payment_id;
    }

    public function setTransactionEntryType($entry_typeId)
    {
        $this->_entry_typeId = $entry_typeId;
    }

    public function setReference($reference_id)
    {
        $this->_reference_id = $reference_id;
    }

    public function setBranch($branch_id)
    {
        $this->_branch_id = $branch_id;
    }

    public function setStaff($staff_id)
    {
        $this->_staff_id = $staff_id;
    }

    public function setDepartment($department)
    {
        $this->_department = $department;
    }

    public function setPosition($position)
    {
        $this->_position = $position;
    }

    public function setAppointmentType($type)
    {
        $this->_appointment_type = $type;
    }

    public function setAccountType($account_type)
    {
        $this->_account_type = $account_type;
    }

    public function fetchData($table)
    {
        $allData = $this->db->table($table)->select($table . '.*')->get()->getResultArray();
        return $allData;
    }

    public function months()
    {
        # code...
        return ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    }

    public function getUniqueEntryYears($account_typeId)
    {
        $entries = $this->db->table('entries')
            ->where([
                'account_typeId ' => $account_typeId,
                'deleted_at' => NULL
            ])->orderBy('date', 'ASC')
            ->get()->getResultArray();

        $years = array();
        foreach ($entries as $k => $entry) {
            $years[] = date('Y', strtotime($entry['date']));
        }
        return array_unique($years);
    }

    public function getUniqueTableYears($table)
    {
        $results = $this->db->table($table)
            ->where([
                'deleted_at' => NULL
            ])->orderBy('created_at', 'ASC')
            ->get()->getResultArray();

        $years = array();
        foreach ($results as $key => $row) {
            $years[] = date('Y', strtotime($row['created_at']));
        }
        return array_unique($years);
    }

    public function getSavingsAccountReport()
    {
        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }

        $months = $this->months();

        # set the necessary columns for staff report 
        $column = [
            'entries.*',
            'clients.name',
            'clients.account_no',
            'clients.gender',
            'entrytypes.type',
            'particulars.particular_name'
        ];
        # 12: Savings
        # 20: Revenue from Deposit & Withdraw
        # 24: Membership
        $account_typeId = ["12", "20", "24"];

        $builder = $this->db->table('entries')
            ->select($column)
            ->join('clients', 'clients.id = entries.client_id', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            /*
            ->where([
                'entries.account_typeId' => 12, 'entries.deleted_at' => NULL
            ])
            */
            ->where([
                'entries.deleted_at' => NULL
            ]);
            /*->whereIn(
                'entries.account_typeId',
                $account_typeId
            );*/

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('`entries.date` BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_entry_status)) {
            if (strtolower($this->_entry_status) == "total") {
                # code...
            } else {
                $builder->where([
                    'entries.status' => $this->_entry_status
                ]);
            }
        }

        # Check the account type
        if (!empty($this->_user[0]) == 'client') {
            $builder->where([
                'entries.client_id' => $this->_user[1]
            ]);
        }
        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(entries.date)' => $this->_year
            ]);
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'clients.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'clients.account_no' => $this->_account_no
            ]);
        }

        if (!empty($this->_search)) {
            $builder->like(
                'clients.name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'clients.occupation',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'clients.nationality',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_payment_id)) {
            $builder->where([
                'entries.payment_id' => $this->_payment_id
            ]);
        }

        if (!empty($this->_entry_typeId)) {
            $builder->where([
                'entries.entry_typeId' => $this->_entry_typeId
            ]);
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'entries.ref_id' => $this->_reference_id
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'entries.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'entries.staff_id' => $this->_staff_id
            ]);
        }

        $builder->orderBy('entries.date', 'ASC');

        $entries = $builder->get()->getResultArray();

        // return $entries;
        // exit;

        $monthly_entries = [];
        foreach ($months as $month_key => $month_value) {
            $get_mon_year = $year . '-' . $month_value;

            $monthly_entries[$get_mon_year][] = '';
            foreach ($entries as $k => $entry) {
                $month_year = date('Y-m', strtotime($entry['date']));

                if ($get_mon_year == $month_year) {
                    $monthly_entries[$get_mon_year][] = $entry;
                }
            }
        }

        return [
            "monthlyEntries" => $monthly_entries,
            "entries" => $entries
        ];
    }
    public function calculateBalanceBroughtForward()
    {
        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }

        $months = $this->months();

        # set the necessary columns for staff report 
        $column = [
            'entries.*',
            'clients.name',
            'clients.account_no',
            'clients.gender',
            'entrytypes.type',
            'particulars.particular_name'
        ];
        # 12: Savings
        # 20: Revenue from Deposit & Withdraw
        # 24: Membership
        $account_typeId = ["12", "20", "24"];

        $builder = $this->db->table('entries')
            ->select($column)
            ->join('clients', 'clients.id = entries.client_id', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            /*
            ->where([
                'entries.account_typeId' => 12, 'entries.deleted_at' => NULL
            ])
            */
            ->where([
                'entries.deleted_at' => NULL
            ]);
            /*->whereIn(
                'entries.account_typeId',
                $account_typeId
            );*/

        if (!empty($this->_startDate)) {
            $builder->where('`entries.date <',$this->_startDate);
        }

        if (!empty($this->_entry_status)) {
            if (strtolower($this->_entry_status) == "total") {
                # code...
            } else {
                $builder->where([
                    'entries.status' => $this->_entry_status
                ]);
            }
        }

        # Check the account type
        if (!empty($this->_user[0]) == 'client') {
            $builder->where([
                'entries.client_id' => $this->_user[1]
            ]);
        }
        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(entries.date)' => $this->_year
            ]);
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'clients.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'clients.account_no' => $this->_account_no
            ]);
        }

        if (!empty($this->_search)) {
            $builder->like(
                'clients.name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'clients.occupation',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'clients.nationality',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_payment_id)) {
            $builder->where([
                'entries.payment_id' => $this->_payment_id
            ]);
        }

        if (!empty($this->_entry_typeId)) {
            $builder->where([
                'entries.entry_typeId' => $this->_entry_typeId
            ]);
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'entries.ref_id' => $this->_reference_id
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'entries.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'entries.staff_id' => $this->_staff_id
            ]);
        }

        $builder->orderBy('entries.date', 'ASC');

        $entries = $builder->get()->getResultArray();
        $balanceBroughtForward = 0;
        if (!empty($this->_startDate)) {
            foreach ($entries as $entry) {
                if ((strtolower($entry['status']) == "credit") && (($entry['account_typeId'] == 12) || ($entry['account_typeId'] == 16))) {
                    $balanceBroughtForward += $entry['amount'];
                } else if (strtolower($entry['status']) == "debit" && $entry['account_typeId'] == 3) {
                    $balanceBroughtForward += $entry['amount'];
                } else {
                    $balanceBroughtForward -= $entry['amount'];
                }
            }
        }
        return $balanceBroughtForward;
    }


    public function getBranchesReport()
    {
        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }

        $months = $this->months();

        $builder = $this->db->table('branches')
            ->where([
                'branches.deleted_at' => NULL
            ]);

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'branches.id',
                $this->_branch_id
            );
        }

        $builder->orderBy('branches.id', 'ASC');

        $branches = $builder->get()->getResultArray();

        $monthly_branches = [];
        foreach ($months as $month_key => $month_value) {
            $get_mon_year = $year . '-' . $month_value;

            $monthly_branches[$get_mon_year][] = '';
            foreach ($branches as $k => $branch) {
                $monthly_branches[$get_mon_year][] = $branch;
            }
        }

        return [
            "monthlyBranches" => $monthly_branches,
            "branches" => $branches
        ];
    }

    public function getEntriesSummationReport(
        $get_month_year,
        $branch_id,
        $account_typeId
    ) {

        $builder = $this->db->table('entries')
            ->join('clients', 'clients.id = entries.client_id', 'left')
            ->where([
                'entries.branch_id' => $branch_id,
                'entries.account_typeId' => $account_typeId,
                'entries.deleted_at' => NULL
            ]);

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('`entries.date` BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_entry_status)) {
            $builder->where([
                'entries.status' => $this->_entry_status
            ]);
        }

        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(entries.date)' => $this->_year
            ]);
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'clients.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'clients.account_no' => $this->_account_no
            ]);
        }

        if (!empty($this->_search)) {
            $builder->like(
                'clients.name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'clients.occupation',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'clients.nationality',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_payment_id)) {
            $builder->where([
                'entries.payment_id' => $this->_payment_id
            ]);
        }

        if (!empty($this->_entry_typeId)) {
            $builder->where([
                'entries.entry_typeId' => $this->_entry_typeId
            ]);
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'entries.ref_id' => $this->_reference_id
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'entries.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'entries.staff_id' => $this->_staff_id
            ]);
        }

        $builder->orderBy('entries.date', 'ASC');

        $entries = $builder->get()->getResultArray();

        $totalAmount = $creditTotal = $debitTotal = 0;

        foreach ($entries as $k => $entry) {
            $month_year = date('Y-m', strtotime($entry['date']));

            if (
                $get_month_year == $month_year &&
                $branch_id == $entry['branch_id']
            ) {
                # check whether the entry status is debit
                if (strtolower($entry['status']) == "debit") {
                    # debit total client amount
                    $debitTotal += $entry['amount'];
                    # count the total number clients with debit

                }
                # check whether the entry status is credit
                if (strtolower($entry['status']) == "credit") {
                    # crdeit total client amount
                    $creditTotal += $entry['amount'];
                    # count the total number clients with credit
                }
                # sum all the total amount for both credit and debit
                $totalAmount += $entry['amount'];
                # count the total number clients with credit
            }
        }

        return [
            "totalAmount" => $totalAmount,
            "creditTotalAmount" => $creditTotal,
            "debitTotalAmount" => $debitTotal,
            "entries" => $entries
        ];
    }


    public function getClientsReport()
    {
        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }

        $months = $this->months();

        $builder = $this->db->table('clients')
            //->join('staffs', 'staffs.id = clients.id', 'left')
            ->where([
                'clients.deleted_at' => NULL
            ]);

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('DATE_FORMAT(clients.created_at, "%Y-%m-%d") BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_entry_status)) {
            if ($this->_entry_status == 'clients') {
            } else {
                $builder->where([
                    'clients.access_status' => $this->_entry_status
                ]);
            }
        }

        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(clients.created_at)' => $this->_year
            ]);
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'clients.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'clients.account_no' => $this->_account_no
            ]);
        }

        if (!empty($this->_search)) {
            $builder->like(
                'clients.name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'clients.occupation',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'clients.nationality',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_entry_typeId)) {
            $builder->like(
                'clients.job_location',
                $this->_entry_typeId,
                'both'
            );
        }

        if (!empty($this->_payment_id)) {
            $builder->where([
                'clients.access_status' => $this->_payment_id
            ]);
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'clients.id_number' => $this->_reference_id
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'clients.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'clients.staff_id' => $this->_staff_id
            ]);
        }

        $builder->orderBy('clients.id', 'DESC');

        $clients = $builder->get()->getResultArray();

        $monthly_clients = [];
        foreach ($months as $month_key => $month_value) {
            $get_mon_year = $year . '-' . $month_value;

            $monthly_clients[$get_mon_year][] = '';
            foreach ($clients as $k => $client) {
                $month_year = date('Y-m', strtotime($client['created_at']));

                if ($get_mon_year == $month_year) {
                    $monthly_clients[$get_mon_year][] = $client;
                }
            }
        }

        return [
            "monthlyClients" => $monthly_clients,
            "clients" => $clients
        ];
    }

    public function getStaffsReport()
    {
        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }

        $months = $this->months();
        # set the necessary columns for staff report 
        $column = [
            'staffs.*',
            'positions.position',
            'positions.position',
            'departments.department_name'
        ];

        $builder = $this->db->table('staffs')
            ->select($column)
            ->join('positions', 'positions.id = staffs.position_id', 'left')
            ->join(
                'departments',
                'departments.id = positions.department_id',
                'left'
            )->where([
                'staffs.deleted_at' => NULL
            ]);

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('DATE_FORMAT(staffs.created_at, "%Y-%m-%d") BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_entry_status)) {
            if (strtolower($this->_entry_status) == "staff") {
                # code...
            } else {
                $builder->where([
                    'staffs.access_status' => $this->_entry_status
                ]);
            }
        }

        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(staffs.created_at)' => $this->_year
            ]);
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'staffs.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'staffs.staffID' => $this->_account_no
            ]);
        }

        if (!empty($this->_search)) {
            $builder->like(
                'staffs.staff_name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'staffs.marital_status',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'staffs.nationality',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_entry_typeId)) {
            $builder->like(
                'staffs.qualifications',
                $this->_entry_typeId,
                'both'
            );
        }

        if (!empty($this->_payment_id)) {
            $builder->where([
                'staffs.access_status' => $this->_payment_id
            ]);
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'staffs.id_number' => $this->_reference_id
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'staffs.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'staffs.officer_staff_id' => $this->_staff_id
            ]);
        }

        if (!empty($this->_department)) {
            $builder->where([
                'departments.id' => $this->_department
            ]);
        }

        if (!empty($this->_position)) {
            $builder->where([
                'staffs.position_id' => $this->_position
            ]);
        }

        if (!empty($this->_appointment_type)) {
            $builder->where(
                'staffs.appointment_type',
                $this->_appointment_type
            );
        }

        if (!empty($this->_account_type)) {
            $builder->where([
                'staffs.account_type' => $this->_account_type
            ]);
        }

        $builder->orderBy('staffs.id', 'DESC');

        $staffResults = $builder->get()->getResultArray();

        $monthlyStaffResults = [];
        foreach ($months as $month_key => $month_value) {
            $get_mon_year = $year . '-' . $month_value;

            $monthlyStaffResults[$get_mon_year][] = '';
            foreach ($staffResults as $k => $staff) {
                $month_year = date('Y-m', strtotime($staff['created_at']));

                if ($get_mon_year == $month_year) {
                    $monthlyStaffResults[$get_mon_year][] = $staff;
                }
            }
        }

        return [
            "monthlyStaffResults" => $monthlyStaffResults,
            "staffAccounts" => $staffResults
        ];
    }


    public function getLoanProductsReport()
    {
        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }

        $months = $this->months();
        # set the necessary columns for loan products report 
        $column = [
            'loanproducts.*'
        ];

        $builder = $this->db->table('loanproducts')
            ->select($column)
            ->where([
                'loanproducts.deleted_at' => NULL
            ]);

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('DATE_FORMAT(loanproducts.created_at, "%Y-%m-%d") BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_search)) {
            $builder->like(
                'loanproducts.product_name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'loanproducts.repayment_freq',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'loanproducts.repayment_period',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_entry_status)) {
            # check the condition to count the total number of loan products
            if (strtolower($this->_entry_status) == "products") {
                # code...
            } else {
                $builder->where([
                    'loanproducts.status' => $this->_entry_status
                ]);
            }
        }

        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(loanproducts.created_at)' => $this->_year
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'loanproducts.repayment_duration',
                $this->_branch_id
            );
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'loanproducts.interest_type' => $this->_reference_id
            ]);
        }



        $builder->orderBy('loanproducts.id', 'DESC');

        $productsResults = $builder->get()->getResultArray();

        $monthlyProductResults = [];
        foreach ($months as $month_key => $month_value) {
            $get_mon_year = $year . '-' . $month_value;

            $monthlyProductResults[$get_mon_year][] = '';
            foreach ($productsResults as $k => $product) {
                $month_year = date('Y-m', strtotime($product['created_at']));

                if ($get_mon_year == $month_year) {
                    $monthlyProductResults[$get_mon_year][] = $product;
                }
            }
        }

        return [
            "monthlyProductResults" => $monthlyProductResults,
            "loanProductsResults" => $productsResults
        ];
    }


    public function getLoanApplicationsReport()
    {
        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }


        $months = $this->months();
        # set the necessary columns for loan products report 
        $column = [
            'loanapplications.*',
            'loanproducts.product_name',
            'loanproducts.repayment_freq',
            'loanproducts.repayment_period',
            'loanproducts.interest_rate',
            'loanproducts.repayment_duration',
            'clients.name',
            'clients.account_no'
        ];

        $builder = $this->db->table('loanapplications')
            ->select($column)
            ->join('loanproducts', 'loanproducts.id = loanapplications.product_id', 'left')
            ->join('clients', 'clients.id = loanapplications.client_id', 'left')
            ->where([
                'loanapplications.deleted_at' => NULL
            ]);

        # Check the account type
        if (!empty($this->_user[0]) == 'client') {
            $builder->where([
                'loanapplications.client_id' => $this->_user[1]
            ]);
        }

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('DATE_FORMAT(loanapplications.created_at, "%Y-%m-%d") BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_search)) {
            $builder->like(
                'loanproducts.product_name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'loanproducts.repayment_freq',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'loanproducts.repayment_period',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'clients.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'clients.account_no' => $this->_account_no
            ]);
        }

        if (!empty($this->_entry_status)) {
            $builder->where([
                'loanproducts.status' => $this->_entry_status
            ]);
        }

        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(loanapplications.created_at)' => $this->_year
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'loanapplications.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'loanproducts.interest_type' => $this->_reference_id
            ]);
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'loanapplications.staff_id' => $this->_staff_id
            ]);
        }

        if (!empty($this->_fieldName01) && !empty($this->_fieldName02)) {
            $builder->where('loanapplications.principal BETWEEN \'' . $this->_fieldName01 . '\' AND \'' . $this->_fieldName02 . '\'');
        }

        if (!empty($this->_fieldName03)) {
            $builder->like(
                'clients.name',
                $this->_fieldName03,
                'both'
            );
        }

        if (!empty($this->_fieldName04)) {
            $builder->where([
                'loanapplications.application_code' => $this->_fieldName04
            ]);
        }

        if (!empty($this->_fieldName05)) {
            $builder->like(
                'loanapplications.level',
                $this->_fieldName05,
                'both'
            );
        }

        if (!empty($this->_fieldName06)) {
            $builder->where([
                'loanapplications.action' => $this->_fieldName06
            ]);
        }

        if (!empty($this->_fieldName07)) {
            # ignore application status condition where application match applications
            if (strtolower($this->_fieldName07) == "applications") {
                # code...
            } else {
                $builder->where([
                    'loanapplications.status' => $this->_fieldName07
                ]);
            }
        }

        if (!empty($this->_fieldName08)) {
            $builder->where([
                'clients.access_status' => $this->_fieldName08
            ]);
        }


        $builder->orderBy('loanapplications.id', 'DESC');

        $results = $builder->get()->getResultArray();

        $monthlyApplicationResults = [];
        foreach ($months as $month_key => $month_value) {
            $get_mon_year = $year . '-' . $month_value;

            $monthlyApplicationResults[$get_mon_year][] = '';
            foreach ($results as $k => $application) {
                $month_year = date('Y-m', strtotime($application['created_at']));

                if ($get_mon_year == $month_year) {
                    $monthlyApplicationResults[$get_mon_year][] = $application;
                }
            }
        }

        return [
            "monthlyApplicationResults" => $monthlyApplicationResults,
            "loanApplicationsResults" => $results
        ];
    }

    public function getDisbursementLoansReport()
    {
        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }

        $months = $this->months();
        # set the necessary columns for loan products report 
        $column = [
            'disbursements.*',
            'loanproducts.product_name',
            'loanproducts.repayment_freq',
            'loanproducts.repayment_period',
            'loanproducts.interest_rate',
            'loanproducts.repayment_duration',
            'clients.name',
            'clients.account_no',
            'particulars.particular_name',
        ];

        $builder = $this->db->table('disbursements')
            ->select($column)
            ->join('loanapplications', 'loanapplications.id = disbursements.application_id', 'left')
            ->join('loanproducts', 'loanproducts.id = disbursements.product_id', 'left')
            ->join('clients', 'clients.id = disbursements.client_id', 'left')
            ->join('particulars', 'particulars.id = disbursements.particular_id', 'left')
            ->where([
                'disbursements.deleted_at' => NULL
            ]);

        # Check the account type
        if (!empty($this->_user[0]) == 'client') {
            $builder->where([
                'disbursements.client_id' => $this->_user[1]
            ]);
        }

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('DATE_FORMAT(disbursements.created_at, "%Y-%m-%d") BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_search)) {
            $builder->like(
                'loanproducts.product_name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'loanproducts.repayment_freq',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'loanproducts.repayment_period',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'clients.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'clients.account_no' => $this->_account_no
            ]);
        }

        if (!empty($this->_entry_status)) {
            $builder->where([
                'loanproducts.status' => $this->_entry_status
            ]);
        }

        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(disbursements.created_at)' => $this->_year
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'disbursements.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'loanproducts.interest_type' => $this->_reference_id
            ]);
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'disbursements.staff_id' => $this->_staff_id
            ]);
        }

        if (!empty($this->_fieldName01) && !empty($this->_fieldName02)) {
            $builder->where('disbursements.principal BETWEEN \'' . $this->_fieldName01 . '\' AND \'' . $this->_fieldName02 . '\'');
        }

        if (!empty($this->_fieldName03)) {
            $builder->like(
                'clients.name',
                $this->_fieldName03,
                'both'
            );
        }

        if (!empty($this->_fieldName04)) {
            $builder->where([
                'disbursements.disbursement_code' => $this->_fieldName04
            ]);
        }

        if (!empty($this->_fieldName05) && !empty($this->_fieldName06)) {
            $builder->where('DATE_FORMAT(disbursements.loan_expiry_date, "%Y-%m-%d") BETWEEN \'' . $this->_fieldName05 . '\' AND \'' . $this->_fieldName06 . '\'');
        }

        if (!empty($this->_fieldName07)) {
            # ignore condition for disbursement status matches total
            if (strtolower($this->_fieldName07) == "total") {
                # code...
            } else {
                $builder->where([
                    'disbursements.class' => $this->_fieldName07
                ]);
            }
        }

        if (!empty($this->_fieldName08)) {
            $builder->where([
                'clients.access_status' => $this->_fieldName08
            ]);
        }


        $builder->orderBy('disbursements.id', 'DESC');

        $disbursementResults = $builder->get()->getResultArray();

        $monthlyDisbursements = [];
        foreach ($months as $month_key => $month_value) {
            $get_mon_year = $year . '-' . $month_value;

            $monthlyDisbursements[$get_mon_year][] = '';
            foreach ($disbursementResults as $k => $disbursement) {
                $month_year = date('Y-m', strtotime($disbursement['created_at']));

                if ($get_mon_year == $month_year) {
                    $monthlyDisbursements[$get_mon_year][] = $disbursement;
                }
            }
        }

        return [
            "monthlyDisbursementResults" => $monthlyDisbursements,
            "loanDisbursementResults" => $disbursementResults
        ];
    }

    public function getEntriesReport($frequency)
    {
        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }

        $months = $this->months();

        # set the necessary columns for staff report 
        $column = [
            'entries.*',
            'clients.name',
            'clients.account_no',
            'clients.gender',
            'entrytypes.type',
            'SUM(entries.amount) AS total'
        ];

        $builder = $this->db->table('entries')
            ->select($column)
            ->join('clients', 'clients.id = entries.client_id', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->where([
                'entries.account_typeId' => 12,
                'entries.deleted_at' => NULL
            ]);

        switch (strtolower($frequency)) {
            case 'weekly':
                $interval = 1 / 4;
                $days = 7;
                break;
            case 'bi-weekly':
                $interval = 1 / 2;
                $days = 14;
                break;
            case 'monthly':
                $interval = 1;
                $days = 30;
                break;
            case 'bi-monthly':
                $interval = 2;
                $days = 60;
                break;
            case 'quarterly':
                $interval = 3;
                $days = 90;
                break;
            case 'termly':
                $interval = 4;
                $days = 120;
                break;
            case 'bi-annual':
                $interval = 6;
                $days = 180;
                break;
            case 'annually':
                $interval = 12;
                $days = 365;
                break;
        }

        $builder->limit($days);
        $builder->groupBy("DATE(entries.date)");


        $builder->orderBy('entries.date', 'ASC');

        $entries = $builder->get()->getResultArray();

        // return $entries;
        // exit;

        $data = ['labels' => [], 'values' => []];

        foreach ($entries as $entry) {
            $data['labels'][] = date('D jS M, Y', strtotime($entry['date']));
            $data['values'][] = $entry['total'];
        }

        return $data;
    }

    public function get_monthly_summation($table)
    {
        // Initialize an empty array to store the entries && monthly transaction data
        $monthlyData = [];
        // set order column
        switch ($table) {
            case 'entries':
                $col = $table . '.date';
                break;
            case 'loanapplications':
                $col = $table . '.application_date';
                break;
            case 'disbursements':
                $col = $table . '.date_disbursed';
                break;
            default:
                $col = $table . '.id';
                break;
        }
        $builder = $this->db->table(strtolower($table));
        // Fetch data from the database
        $all_data = $builder->where([$table . '.deleted_at' => null])->orderBy($col, 'asc')->get()->getResultArray();

        // configure the parameter to use for each table
        $tableConfig = [
            'entries' => [
                'status' => ['debit', 'credit'],
                'statusSum' => ['debitSum', 'creditSum'],
                'counters' => ['debitCount', 'creditCount'],
                'fields' => ['amount', 'date', 'status']
            ],
            'loanapplications' => [
                'status' => ['pending', 'processing', 'declined', 'approved',  'disbursed'],
                'statusSum' => ['pendingSum', 'processingSum', 'declinedSum',  'approvedSum', 'disbursedSum'],
                'counters' => ['pendingCount', 'processingCount', 'declinedCount',  'approvedCount', 'disbursedCount'],
                'fields' => ['principal', 'created_at', 'status'],
            ],
            'disbursements' => [
                'status' => ['running', 'arrears', 'cleared', 'expired'],
                'statusSum' => ['runningSum', 'arrearsSum', 'clearedSum',  'expiredSum'],
                'counters' => ['runningCount', 'arrearsCount', 'clearedCount',  'expiredCount'],
                'fields' => ['principal', 'created_at', 'class'],
            ]
        ];
        // get individual configurations from main config
        $statuses = $tableConfig[$table]['status'];
        $counters = $tableConfig[$table]['counters'];
        $amtField = $tableConfig[$table]['fields'][0];
        $dateField = $tableConfig[$table]['fields'][1];
        $statusField = $tableConfig[$table]['fields'][2];
        // Iterate through the data from the database
        foreach ($all_data as $data) {
            // Extract the month and year from the data's date column
            $month = date('M', strtotime($data[$dateField]));
            $year = date('Y', strtotime($data[$dateField]));

            // Create an array for the month if it doesn't exist in the $monthlyData array
            if (!isset($monthlyData[$year][$month])) {
                // fill keys from config and assign them value 0 by default
                $monthlyData[$year][$month] = array_fill_keys($statuses, 0);
                $monthlyData[$year][$month] += array_fill_keys($counters, 0);
                // total counter
                $monthlyData[$year][$month]['total'] = 0;
                $monthlyData[$year][$month]['totalCount'] = 0;
            }

            // Update the summation n counter for the month based on status
            foreach ($statuses as $status) {
                if (strtolower($data[$statusField]) == $status) {
                    $monthlyData[$year][$month][$status] += $data[$amtField];
                    $monthlyData[$year][$month][$counters[array_search($status, $statuses)]]++;
                    break;
                }
            }
            // Update the overall summation for the month
            $monthlyData[$year][$month]['total'] += $data[$amtField];
            $monthlyData[$year][$month]['totalCount']++;
        }

        return $monthlyData;
    }

    public function getLoanProductCharges(array $charges, $principal)
    {
        # Process the data
        $totalCharges = 0;
        $particularIDs = [];
        # check the particular existence
        if (count($charges) > 0) {
            foreach ($charges as $index => $row) {
                $charge = $row['charge'];
                $chargeMethod = $row['charge_method'];

                # Handle the charge method based on the condition
                if (strtolower($chargeMethod) === 'percent') {
                    # Calculate the charge as a percentage of the product
                    $totalCharge = ($charge / 100) * $principal;
                } elseif (strtolower($chargeMethod) === 'amount') {
                    # Use the fixed amount as the charge
                    $totalCharge = $charge;
                } else {
                    # Handle other charge methods here
                    $totalCharge = 0;
                }

                # Use $totalCharge as needed
                $totalCharges += $totalCharge;
                $particularIDs[] = $row['particular_id'];
            }

            $data['particularIDs'] = array_unique($particularIDs);
            $data['totalCharges'] = $totalCharges;
        } else {
            $data['particularIDs'] = $particularIDs;
            $data['totalCharges'] = $totalCharges;
        }

        return $data;
    }

    public function loanProductCharge($particulars, $principal, $particularID)
    {
        # Process the data
        $totalCharge = 0;
        # check the particular existence
        if (count($particulars) > 0) {
            foreach ($particulars as $index => $particular) {
                # check whether the applicant product charges match the particular id
                if ($particular['particular_id'] == $particularID) {
                    $charge = $particular['charge'];
                    $chargeMethod = $particular['charge_method'];

                    # Handle the charge method based on the condition
                    if (strtolower($chargeMethod) === 'percent') {
                        # Calculate the charge as a percentage of the product
                        $totalCharge = ($charge / 100) * $principal;
                    }
                    if (strtolower($chargeMethod) === 'amount') {
                        # Use the fixed amount as the charge
                        $totalCharge = $charge;
                    }
                    break;
                } else {
                    continue;
                }
            }

            $data['totalCharge'] = $totalCharge;
        } else {
            $data['totalCharge'] = null;
        }

        return $data;
    }

    public function getMonthlySavingsTotals0($account_typeId)
    {
        $query = $this->db->query("
            SELECT 
                MONTH(date) as month, 
                SUM(CASE WHEN status = 'debit' THEN amount ELSE 0 END) as total_debit,
                SUM(CASE WHEN status = 'credit' THEN amount ELSE 0 END) as total_credit
            FROM entries
            WHERE account_typeId = ?
            GROUP BY MONTH(date)
        ", [$account_typeId]);

        return $query->getResultArray();
    }

    public function getMonthlySavingsTotals($condition, $filter = 'all')
    {
        $builder = $this->db->table('entries')
            ->select("
            MONTH(entries.date) as month, 
            SUM(CASE WHEN entries.status = 'debit' THEN entries.amount ELSE 0 END) as total_debit,
            SUM(CASE WHEN entries.status = 'credit' THEN entries.amount ELSE 0 END) as total_credit
        ")
            ->join('clients', 'clients.id = entries.client_id', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left');

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('`entries.date` BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_entry_status)) {
            if (strtolower($this->_entry_status) == "total") {
                # code...
            } else {
                $builder->where([
                    'entries.status' => $this->_entry_status
                ]);
            }
        }

        # Check the account type
        if (!empty($this->_user[0]) == 'client') {
            $builder->where([
                'entries.client_id' => $this->_user[1]
            ]);
        }
        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(entries.date)' => $this->_year
            ]);
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'clients.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'clients.account_no' => $this->_account_no
            ]);
        }

        if (!empty($this->_search)) {
            $builder->like(
                'clients.name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'clients.occupation',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'clients.nationality',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_payment_id)) {
            $builder->where([
                'entries.payment_id' => $this->_payment_id
            ]);
        }

        if (!empty($this->_entry_typeId)) {
            $builder->where([
                'entries.entry_typeId' => $this->_entry_typeId
            ]);
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'entries.ref_id' => $this->_reference_id
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'entries.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'entries.staff_id' => $this->_staff_id
            ]);
        }


        switch ($filter) {
            case 'week':
                $builder->where('date >=', date('Y-m-d', strtotime('-7 days')));
                break;
            case 'month':
                $builder->where('MONTH(date)', date('m'));
                break;
            case 'year':
                $builder->where('YEAR(date)', date('Y'));
                break;
            case 'all':
                // No additional condition needed for 'all'
                break;
            default:
                // Handle default case or error
                break;
        }

        $builder->where($condition)->groupBy('MONTH(date)');

        $builder->orderBy('entries.date', 'ASC');


        # Execute the query and get the result
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function clientsReport()
    {
        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }

        $months = $this->months();

        $builder = $this->db->table('clients')
            //->join('staffs', 'staffs.id = clients.id', 'left')
            ->where([
                'clients.deleted_at' => NULL
            ]);

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('DATE_FORMAT(clients.created_at, "%Y-%m-%d") BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_entry_status)) {
            if ($this->_entry_status == 'clients') {
            } else {
                $builder->where([
                    'clients.access_status' => $this->_entry_status
                ]);
            }
        }

        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(clients.date)' => $this->_year
            ]);
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'clients.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'clients.account_no' => $this->_account_no
            ]);
        }

        if (!empty($this->_search)) {
            $builder->like(
                'clients.name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'clients.occupation',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'clients.nationality',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_entry_typeId)) {
            $builder->like(
                'clients.job_location',
                $this->_entry_typeId,
                'both'
            );
        }

        if (!empty($this->_payment_id)) {
            $builder->where([
                'clients.access_status' => $this->_payment_id
            ]);
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'clients.id_number' => $this->_reference_id
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'clients.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'clients.staff_id' => $this->_staff_id
            ]);
        }

        $builder->orderBy('clients.id', 'DESC');

        $counter = $builder->countAllResults();

        $amount = 0;

        $savingTotalCredit = $this->entriesReport([
            'entries.account_typeId' => 12, # account type id is 12 for savings
            'entries.status' => 'credit',
        ]);

        $savingTotalDebit = $this->entriesReport([
            'entries.account_typeId' => 12, # account type id is 12 for savings
            'entries.status' => 'debit',
        ]);

        $savings = $this->calculateTotalBalance([
            'module' => 'account_type',
            'module_id' => 12, # account type id is 1 for Savings
            'status' => 'credit',
            'deleted_at' => null
        ]);

        $revenueTotalCredit = $this->entriesReport([
            'entries.account_typeId' => 20, # Revenue from Deposit & Withdraw
            'entries.status' => 'credit',
        ]);

        $revenueTotalDebit = $this->entriesReport([
            'entries.account_typeId' => 20, # Revenue from Deposit & Withdraw
            'entries.status' => 'debit',
        ]);

        $membershipTotalCredit = $this->entriesReport([
            'entries.account_typeId' => 24, # Membership
            'entries.status' => 'credit',
        ]);

        $membershipTotalDebit = $this->entriesReport([
            'entries.account_typeId' => 24, # Membership
            'entries.status' => 'debit',
        ]);

        return [
            "members" => [
                'counter' => number_format($counter)
            ],
            "savings" => [
                'totalCredit' => number_format(($savingTotalCredit) ? $savingTotalCredit : $amount),
                'totalDebit' => number_format(($savingTotalDebit) ? $savingTotalDebit : $amount),
                'totalBalance' => number_format($savings['totalBalance']),
                'accounting' => $savings,

            ],
            "revenue" => [
                'totalCredit' => number_format(($revenueTotalCredit) ? $revenueTotalCredit : $amount),
                'totalDebit' => number_format(($revenueTotalDebit) ? $revenueTotalDebit : $amount)
            ],
            "membership" => [
                'totalCredit' => number_format(($membershipTotalCredit) ? $membershipTotalCredit : $amount),
                'totalDebit' => number_format(($membershipTotalDebit) ? $$membershipTotalDebit : $amount),
                'accounting' => $this->calculateTotalBalance([
                    'module' => 'account_type',
                    'module_id' => 24, # account type id is 24 for Membership
                    'status' => 'credit',
                    'deleted_at' => null
                ]),
                'particulars' => $this->accountingLedger(24),
            ],
            'liquidity' => [
                'totalLiquidity' => $this->calculateTotalBalance([
                    'module' => 'account_type',
                    'module_id' => 1, # account type id is 1 for cash and bank
                    'status' => 'debit',
                    'deleted_at' => null
                ]),
                'particulars' => $this->accountingLedger(1),
            ],
        ];
    }

    public function entriesReport($condition)
    {
        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }

        # set the necessary columns for staff report 
        $column = [
            'entries.*',
            'clients.name',
            'clients.account_no',
            'clients.gender',
            'entrytypes.type',
            'particulars.particular_name'
        ];
        # 12: Savings
        # 20: Revenue from Deposit & Withdraw
        # 24: Membership
        $account_typeId = ["12", "20", "24", "8"];

        $builder = $this->db->table('entries')
            ->selectSum('entries.amount')
            ->join('clients', 'clients.id = entries.client_id', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            ->where([
                'YEAR(entries.date)' => $year,
                'entries.deleted_at' => NULL
            ])
            ->where($condition);
        /*
            ->whereIn(
                'entries.account_typeId',
                $account_typeId
            );
            */

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('`entries.date` BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_entry_status)) {
            if (strtolower($this->_entry_status) == "total") {
                # code...
            } else {
                $builder->where([
                    'entries.status' => $this->_entry_status
                ]);
            }
        }

        # Check the account type
        if (!empty($this->_user[0]) == 'client') {
            $builder->where([
                'entries.client_id' => $this->_user[1]
            ]);
        }
        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(entries.date)' => $this->_year
            ]);
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'clients.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'clients.account_no' => $this->_account_no
            ]);
        }

        if (!empty($this->_search)) {
            $builder->like(
                'clients.name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'clients.occupation',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'clients.nationality',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_payment_id)) {
            $builder->where([
                'entries.payment_id' => $this->_payment_id
            ]);
        }

        if (!empty($this->_entry_typeId)) {
            $builder->where([
                'entries.entry_typeId' => $this->_entry_typeId
            ]);
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'entries.ref_id' => $this->_reference_id
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'entries.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'entries.staff_id' => $this->_staff_id
            ]);
        }

        $builder->orderBy('entries.date', 'ASC');

        $entry = $builder->get()->getRowArray();

        return ($entry) ? $entry['amount'] : 0;
    }

    /** sum debit and credit then balance for modules
     * $module = summation needed i.e particular, subcategory, category or account_type
     * $module_id = primary key of the module
     * $status = accounting side of the module, i.e debit or credit
     * $entryId = primary key of the entry i.e entries.id to stop summation to
     */
    public function calculateTotalBalance(array $data)
    {
        # pick values from array
        $module = $data['module'];
        $module_id = $data['module_id'];
        $status = $data['status'];
        $entryId = (isset($data['entryId']) ? $data['entryId'] : null);
        $start_date = (isset($data['start_date']) ? $data['start_date'] : null);
        $end_date = (isset($data['end_date']) ? $data['end_date'] : null);

        # default variables to store particular totals
        $totalDebit = $totalCredit = $balance = 0;
        # default variables to store subcategory totals
        $data = [];
        $data['totalDebit'] = 0;
        $data['totalCredit'] = 0;
        $data['totalBalance'] = 0;
        /*
        // fetch entries from db
        if (!$entryId) {
            $entries = $this->findAll();
        } else {
            if (strtolower($entryId) == 'statements') {
                $entries = $this->where(['DATE_FORMAT(entries.date, "%Y-%m-%d") >=' => $start_date, 'DATE_FORMAT(entries.date, "%Y-%m-%d") <=' => $end_date])->findAll();
            } else {
                // all entries upto this entry
                $entryRow = $this->find($entryId);
                $entries = $this->where(['created_at <' => $entryRow['created_at']])->findAll();
            }
        }
        */
        # set the necessary columns for staff report 
        $column = [
            'entries.*',
            'clients.name',
            'clients.account_no',
            'clients.gender',
            'entrytypes.type',
            'particulars.particular_name'
        ];
        # 12: Savings
        # 20: Revenue from Deposit & Withdraw
        # 24: Membership
        # $account_typeId = ["12", "20", "24"];

        $builder = $this->db->table('entries')
            ->select($column)
            ->join('clients', 'clients.id = entries.client_id', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            ->where([
                'entries.deleted_at' => NULL
            ]);

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('`entries.date` BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_entry_status)) {
            if (strtolower($this->_entry_status) == "total") {
                # code...
            } else {
                $builder->where([
                    'entries.status' => $this->_entry_status
                ]);
            }
        }

        # Check the account type
        if (!empty($this->_user[0]) == 'client') {
            $builder->where([
                'entries.client_id' => $this->_user[1]
            ]);
        }
        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(entries.date)' => $this->_year
            ]);
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'clients.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'clients.account_no' => $this->_account_no
            ]);
        }

        if (!empty($this->_search)) {
            $builder->like(
                'clients.name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'clients.occupation',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'clients.nationality',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_payment_id)) {
            $builder->where([
                'entries.payment_id' => $this->_payment_id
            ]);
        }

        if (!empty($this->_entry_typeId)) {
            $builder->where([
                'entries.entry_typeId' => $this->_entry_typeId
            ]);
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'entries.ref_id' => $this->_reference_id
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'entries.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'entries.staff_id' => $this->_staff_id
            ]);
        }

        $builder->orderBy('entries.date', 'ASC');

        $entries = $builder->get()->getResultArray();

        if ($entries) {
            # default variables to store particular totals
            $directDebit = $directCredit = $reverseDebit = $reverseCredit = 0;
            # define an empty array
            $particularIDs = $accountTypeIDs = $subcategoryIDs = [];
            $categoryIDs = $cashFlowIDs = [];
            /*
            # Fetch all particular_ids and payment_ids in one go
            $particular_ids = $this->db->table('entries')->distinct()
                ->select('particular_id')->get()->getResultArray();
            # Extract particular_id into a column array
            $particularIds = array_column($particular_ids, 'particular_id');
            # Get unique payment_ids from the entries
            $payment_ids = $this->db->table('entries')->distinct()->select('payment_id')
                ->get()->getResultArray();
            # Extract payment_id into a column array
            $paymentIds = array_column($payment_ids, 'payment_id');
            */
            # Fetch all particular_ids and payment_ids in one go
            $particularIds = $paymentIds = [];
            foreach ($entries as $key => $entry) {
                $particularIds[] = $entry['particular_id'];
                $paymentIds[] = $entry['payment_id'];
            }

            $particularIDs = array_unique(array_merge($particularIds, $paymentIds));

            foreach ($particularIDs as $particular_id) {
                # Reset totals for each particular
                $directDebit = $reverseDebit = $directCredit = $reverseCredit = 0;

                # get particular data for each particular found in the entries table
                $particularTable = $this->db->table('particulars');
                $particularData = $particularTable->where('id', $particular_id)->get()->getRow();
                # skip the loop if particular data isn't found
                if (!$particularData) {
                    continue;
                }
                # skip the loop if particular is inactive
                if (strtolower($particularData->particular_status) == 'inactive') {
                    continue;
                }

                # from particularData, get subcategory, category and account type IDs

                # Check if the key already exists in $subcategoryIDs array
                if (!isset($subcategoryIDs[$particularData->subcategory_id])) {
                    $subcategoryIDs[$particularData->subcategory_id] = $particularData->subcategory_id;
                }
                # Check if the key already exists in $categoryIDs array
                if (!isset($categoryIDs[$particularData->category_id])) {
                    $categoryIDs[$particularData->category_id] = $particularData->category_id;
                }
                # Check if the key already exists in $account_typeIDs array
                if (!isset($accountTypeIDs[$particularData->account_typeId])) {
                    $accountTypeIDs[$particularData->account_typeId] = $particularData->account_typeId;
                }

                # loop through entries & get totals for each particular
                foreach ($entries as $entry) {
                    // directDebit total
                    if ($entry['particular_id'] == $particular_id && $entry['status'] == 'debit') {
                        $directDebit += $entry['amount'];
                    }
                    // reverseDebit total
                    if ($entry['payment_id'] == $particular_id && $entry['status'] == 'credit') {
                        $reverseDebit += $entry['amount'];
                    }
                    // directCredit total
                    if ($entry['particular_id'] == $particular_id && $entry['status'] == 'credit') {
                        $directCredit += $entry['amount'];
                    }
                    // reverseCredit total
                    if ($entry['payment_id'] == $particular_id && $entry['status'] == 'debit') {
                        $reverseCredit += $entry['amount'];
                    }
                }

                // totals
                $totalDebit = $directDebit + $reverseDebit;
                $totalCredit = $directCredit + $reverseCredit;

                $balance = ($totalDebit - $totalCredit);
                if ((strtolower($status) == 'credit') && $balance < 0) {
                    $totalBalance = abs($balance);
                } elseif ((strtolower($status) == 'credit') && $balance > 0) {
                    $totalBalance = -$balance;
                } else {
                    $totalBalance = $balance;
                }
                # particulars totals
                $particularTotals[$particular_id] = [
                    'totalDebit' => $totalDebit,
                    'totalCredit' => $totalCredit,
                    'totalBalance' => $totalBalance,
                ];

                // Accumulate subcategory totals
                if (isset($subcategoryIDs[$particularData->subcategory_id])) {
                    $subcategory_id = $particularData->subcategory_id;
                    if (!isset($subcategoryTotals[$subcategory_id])) {
                        $subcategoryTotals[$subcategory_id] = [
                            'totalDebit' => 0,
                            'totalCredit' => 0,
                            'totalBalance' => 0,
                        ];
                    }
                    $subcategoryTotals[$subcategory_id]['totalDebit'] += $particularTotals[$particular_id]['totalDebit'];
                    $subcategoryTotals[$subcategory_id]['totalCredit'] += $particularTotals[$particular_id]['totalCredit'];
                    $subcategoryTotals[$subcategory_id]['totalBalance'] += $particularTotals[$particular_id]['totalBalance'];
                }

                // Accumulate category totals
                if (isset($categoryIDs[$particularData->category_id])) {
                    $category_id = $particularData->category_id;
                    if (!isset($categoryTotals[$category_id])) {
                        $categoryTotals[$category_id] = [
                            'totalDebit' => 0,
                            'totalCredit' => 0,
                            'totalBalance' => 0,
                        ];
                    }
                    $categoryTotals[$category_id]['totalDebit'] += $particularTotals[$particular_id]['totalDebit'];
                    $categoryTotals[$category_id]['totalCredit'] += $particularTotals[$particular_id]['totalCredit'];
                    $categoryTotals[$category_id]['totalBalance'] += $particularTotals[$particular_id]['totalBalance'];
                }

                // Accumulate account_type totals
                if (isset($accountTypeIDs[$particularData->account_typeId])) {
                    $account_typeId = $particularData->account_typeId;
                    if (!isset($accountTypeTotals[$account_typeId])) {
                        $accountTypeTotals[$account_typeId] = [
                            'totalDebit' => 0,
                            'totalCredit' => 0,
                            'totalBalance' => 0,
                        ];
                    }
                    $accountTypeTotals[$account_typeId]['totalDebit'] += $particularTotals[$particular_id]['totalDebit'];
                    $accountTypeTotals[$account_typeId]['totalCredit'] += $particularTotals[$particular_id]['totalCredit'];
                    $accountTypeTotals[$account_typeId]['totalBalance'] += $particularTotals[$particular_id]['totalBalance'];
                }

                // Accumulate cash_flow totals
                if (isset($cashFlowIDs[$particularData->cash_flow_typeId])) {
                    $cash_flowId = $particularData->cash_flow_typeId;
                    if (!isset($cashFlowTotals[$cash_flowId])) {
                        $cashFlowTotals[$cash_flowId] = [
                            'totalDebit' => 0,
                            'totalCredit' => 0,
                            'totalBalance' => 0,
                        ];
                    }
                    $cashFlowTotals[$cash_flowId]['totalDebit'] += $particularTotals[$particular_id]['totalDebit'];
                    $cashFlowTotals[$cash_flowId]['totalCredit'] += $particularTotals[$particular_id]['totalCredit'];
                    $cashFlowTotals[$cash_flowId]['totalBalance'] += $particularTotals[$particular_id]['totalBalance'];
                }
            }

            switch ($module) {
                    // Calculate totals for particular
                case 'particular':
                    if (in_array($module_id, $particularIDs)) {
                        $data = $particularTotals[$module_id];
                    } else {
                        $data = [
                            'totalDebit' => 0,
                            'totalCredit' => 0,
                            'totalBalance' => 0,
                        ];
                    }
                    break;
                case 'account_type':
                    if (in_array($module_id, $accountTypeIDs)) {
                        $data = $accountTypeTotals[$module_id];
                    } else {
                        $data = [
                            'totalDebit' => 0,
                            'totalCredit' => 0,
                            'totalBalance' => 0,
                        ];
                    }
                    break;
                case 'subcategory':
                    if (in_array($module_id, $subcategoryIDs)) {
                        $data = $subcategoryTotals[$module_id];
                    } else {
                        $data = [
                            'totalDebit' => 0,
                            'totalCredit' => 0,
                            'totalBalance' => 0,
                        ];
                    }
                    break;
                case 'category':
                    if (in_array($module_id, $categoryIDs)) {
                        $data = $categoryTotals[$module_id];
                    } else {
                        $data = [
                            'totalDebit' => 0,
                            'totalCredit' => 0,
                            'totalBalance' => 0,
                        ];
                    }
                    break;
                case 'cash_flow':
                    if (in_array($module_id, $cashFlowIDs)) {
                        $data = $cashFlowTotals[$module_id];
                    }
                    break;
                default:
                    $data = [
                        'totalDebit' => 0,
                        'totalCredit' => 0,
                        'totalBalance' => 0,
                    ];
                    break;
            }
        }

        return $data;
    }

    public function accountingLedger3($account_typeId)
    {
        $accounts = [];

        $year = date('Y');

        if (!empty($this->_year)) {
            $year = $this->_year;
        }

        # set the necessary columns for staff report 
        $column = [
            'entries.*',
            'clients.name',
            'clients.account_no',
            'clients.gender',
            'entrytypes.type',
            'particulars.particular_name'
        ];
        # 12: Savings
        # 20: Revenue from Deposit & Withdraw
        # 24: Membership
        # $account_typeId = ["12", "20", "24"];

        $builder = $this->db->table('entries')
            ->select($column)
            ->join('clients', 'clients.id = entries.client_id', 'left')
            ->join('entrytypes', 'entrytypes.id = entries.entry_typeId', 'left')
            ->join('particulars', 'particulars.id = entries.particular_id', 'left')
            ->where([
                // 'entries.account_typeId' => $account_typeId,
                'YEAR(entries.date)' => $year,
                'entries.deleted_at' => NULL
            ]);

        if (!empty($this->_startDate) && !empty($this->_endDate)) {
            $builder->where('`entries.date` BETWEEN \'' . $this->_startDate . '\' AND \'' . $this->_endDate . '\'');
        }

        if (!empty($this->_entry_status)) {
            if (strtolower($this->_entry_status) == "total") {
                # code...
            } else {
                $builder->where([
                    'entries.status' => $this->_entry_status
                ]);
            }
        }

        # Check the account type
        if (!empty($this->_user[0]) == 'client') {
            $builder->where([
                'entries.client_id' => $this->_user[1]
            ]);
        }
        if (!empty($this->_year)) {
            $builder->where([
                'YEAR(entries.date)' => $this->_year
            ]);
        }

        if (!empty($this->_gender)) {
            $builder->where([
                'clients.gender' => $this->_gender
            ]);
        }

        if (!empty($this->_account_no)) {
            $builder->where([
                'clients.account_no' => $this->_account_no
            ]);
        }

        if (!empty($this->_search)) {
            $builder->like(
                'clients.name',
                $this->_search,
                'both'
            )
                ->orLike(
                    'clients.occupation',
                    $this->_search,
                    'both'
                )
                ->orLike(
                    'clients.nationality',
                    $this->_search,
                    'both'
                );
        }

        if (!empty($this->_payment_id)) {
            $builder->where([
                'entries.payment_id' => $this->_payment_id
            ]);
        }

        if (!empty($this->_entry_typeId)) {
            $builder->where([
                'entries.entry_typeId' => $this->_entry_typeId
            ]);
        }

        if (!empty($this->_reference_id)) {
            $builder->where([
                'entries.ref_id' => $this->_reference_id
            ]);
        }

        if (!empty($this->_branch_id)) {
            $builder->whereIn(
                'entries.branch_id',
                $this->_branch_id
            );
        }

        if (!empty($this->_staff_id)) {
            $builder->where([
                'entries.staff_id' => $this->_staff_id
            ]);
        }

        $builder->orderBy('entries.date', 'ASC');

        $entries = $builder->get()->getResultArray();
        # Fetch all particular_ids and payment_ids in one go
        $particularIds = $paymentIds = [];
        foreach ($entries as $key => $entry) {
            $particularIds[] = $entry['particular_id'];
            $paymentIds[] = $entry['payment_id'];
        }

        $particulars = array_unique(array_merge($particularIds, $paymentIds));

        if ($particulars) {
            # get totals foreach liquidity particular
            foreach ($particulars as $particular_id) {
                $particular = $this->db->table('particulars')
                    ->select('particulars.*, categories.part')
                    ->join('categories', 'categories.id = particulars.category_id', 'left')
                    ->where([
                        'particulars.id' => $particular_id,
                        'particulars.account_typeId' => $account_typeId,
                        'particulars.particular_status' => 'Active',
                        'particulars.deleted_at' => NULL
                    ])
                    ->get()->getRowArray();

                # skip the loop if particular data isn't found
                if (!$particular) {
                    continue;
                }
                # skip the loop if particular is inactive
                if (strtolower($particular['particular_status']) == 'inactive') {
                    continue;
                }

                if ($particular['account_typeId'] == $account_typeId) {

                    $particularTotals = $this->calculateTotalBalance([
                        'module' => 'particular',
                        'module_id' => $particular_id,
                        'status' => $particular['part'],
                        'deleted_at' => null

                    ]);
                    $particular['total_balance'] = $particularTotals['totalBalance'];

                    $accounts[] = $particular;
                }
            }
        }
        return $particulars;
    }

    public function accountingLedger($account_typeId)
    {
        $accounts = [];
        $builder = $this->db->table('particulars')->select('particulars.*, categories.part')
            ->join('categories', 'categories.id = particulars.category_id', 'left')
            ->where([
                'particulars.account_typeId' => $account_typeId,
                'particulars.particular_status' => 'Active',
                'particulars.deleted_at' => NULL

            ]);
        $particulars = $builder->get()->getResultArray();
        if ($particulars) {
            # get totals foreach liquidity particular
            foreach ($particulars as $particular) {
                $particularTotals = $this->calculateTotalBalance([
                    'module' => 'particular',
                    'module_id' => $particular['id'],
                    'status' => $particular['part'],
                    'deleted_at' => null

                ]);
                $particular['total_balance'] = $particularTotals['totalBalance'];

                $accounts[] = $particular;
            }
        }
        return $accounts;
    }
}
