<?php

namespace App\Models;

use CodeIgniter\Model;

class EntriesModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'entries';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'date',
        'payment_id',
        'particular_id',
        'branch_id',
        'staff_id',
        'client_id',
        'product_id',
        'disbursement_id',
        'application_id',
        'entry_menu',
        'entry_typeId',
        'ref_id',
        'entry_details',
        'account_typeId',
        'amount',
        'status',
        'balance',
        'contact',
        'remarks',
        'transaction_reference',
        'parent_id',
        'account_id'
    ];

    // Dates
    protected $useTimestamps = true;
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

    public function __construct()
    {
        parent::__construct();
        # load database connection
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
    }

    /**
     * Calculate the total debit, total credit, and balance for a particular.
     *
     * @param int $particular_id The ID of the particular.
     * @param int|null $entryId The ID of the entry to stop the calculation at.
     * @param string|null $start_date The start date for filtering entries.
     * @param string|null $end_date The end date for filtering entries.
     *
     * @return array An associative array containing the total debit, total credit, and balance.
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

        # Initialize variables to store aggregated totals
        $particularTotals = [];
        $subcategoryTotals = [];
        $categoryTotals = [];
        $accountTypeTotals = [];
        $cashFlowTotals = [];

        # Fetch distinct particular_ids (both particular_id and payment_id)
        $particularIds = $this->distinct()->select('particular_id')->findColumn('particular_id');
        if (!$particularIds) {
            $particularIds = []; // Assign an empty array if null or empty
        }
        $paymentIds = $this->distinct()->select('payment_id')->findColumn('payment_id');
        if (!$paymentIds) {
            $paymentIds = []; // Assign an empty array if null or empty
        }

        $particularIDs = array_unique(array_merge($particularIds, $paymentIds));
        if (empty($particularIDs)) {
            // Respond or log that no particular or payment IDs were found
            return [
                'status' => 404,
                'error' => 'Not Found',
                'message' => "No Particular or Payment IDs Found",
                'totalDebit' => 0,
                'totalCredit' => 0,
                'totalBalance' => 0
            ];
        }
        # Use a single query to fetch totals for all particular_ids at once
        $totalsQuery1 = $this->db->table('entries')
            ->select(' particular_id as particular_id, SUM(CASE WHEN entries.status = "debit" THEN entries.amount ELSE 0 END) as directDebit, SUM(CASE WHEN entries.status = "credit" THEN entries.amount ELSE 0 END) as directCredit, 0 as reverseDebit, 0 as reverseCredit')
            ->whereIn('entries.particular_id', $particularIDs)
            ->groupBy('entries.particular_id');

        $totalsQuery2 = $this->db->table('entries')
            ->select('payment_id as particular_id, 0 as directDebit, 0 as directCredit, SUM(CASE WHEN entries.status = "credit" THEN entries.amount ELSE 0 END) as reverseDebit, SUM(CASE WHEN entries.status = "debit" THEN entries.amount ELSE 0 END) as reverseCredit')
            ->whereIn('entries.payment_id', $particularIDs)
            ->groupBy('entries.payment_id');
        # Apply filters for entryId and date range
        if ($entryId) {
            if (strtolower($entryId) == 'statements') {
                if ($start_date && $end_date) {
                    $totalsQuery1->where('DATE(entries.date) >=', $start_date)
                        ->where('deleted_at', null)
                        ->where('DATE(entries.date) <=', $end_date);
                    $totalsQuery2->where('DATE(entries.date) >=', $start_date)
                        ->where('deleted_at', null)
                        ->where('DATE(entries.date) <=', $end_date);
                }
            } else {
                $entryRow = $this->find($entryId);
                if ($entryRow) {
                    $totalsQuery1->where('entries.created_at <', $entryRow['created_at'])->where('deleted_at', null);
                    $totalsQuery2->where('entries.created_at <', $entryRow['created_at'])->where('deleted_at', null);
                }
            }
        }

        $finalQuery = $this->db->query(
            "SELECT particular_id, 
                        SUM(directDebit) as directDebit, 
                        SUM(directCredit) as directCredit, 
                        SUM(reverseDebit) as reverseDebit, 
                        SUM(reverseCredit) as reverseCredit 
                FROM (
                    {$totalsQuery1->getCompiledSelect()} 
                    UNION ALL 
                    {$totalsQuery2->getCompiledSelect()}
                ) as combined 
                GROUP BY particular_id"
        );

        $totalsResults = $finalQuery->getResult();

        if (empty($totalsResults)) {
            return [
                'message' => 'No entries found.',
                'totalDebit' => 0,
                'totalCredit' => 0,
                'totalBalance' => 0,
            ];
        }
        # Process the totals for each particular_id
        foreach ($totalsResults as $result) {
            $particular_id = $result->particular_id;
            $directDebit = $result->directDebit;
            $reverseDebit = $result->reverseDebit;
            $directCredit = $result->directCredit;
            $reverseCredit = $result->reverseCredit;

            $totalDebit = $directDebit + $reverseDebit;
            $totalCredit = $directCredit + $reverseCredit;
            $balance = $totalDebit - $totalCredit;

            if ((strtolower($status) == 'credit') && $balance < 0) {
                $totalBalance = abs($balance);
            } elseif ((strtolower($status) == 'credit') && $balance > 0) {
                $totalBalance = -$balance;
            } else {
                $totalBalance = $balance;
            }

            # Cache the totals for the particular_id
            $particularTotals[$particular_id] = [
                'totalDebit' => $totalDebit,
                'totalCredit' => $totalCredit,
                'totalBalance' => $totalBalance
            ];

            # Now aggregate the totals by subcategory, category, account_type, cash_flow
            $columns = ['id', 'particular_name', 'debit', 'credit', 'particular_status', 'category_id', 'subcategory_id', 'account_typeId', 'cash_flow_typeId'];
            $particularData = $this->db->table('particulars')->select($columns)->where('id', $particular_id)->get()->getRow();
            // skip iteration if particular data isn't found or particular status is inactive
            if (!$particularData || strtolower($particularData->particular_status) == 'inactive') {
                continue;
            }
            // Subcategory Totals
            $subcategoryId = $particularData->subcategory_id;
            if (!isset($subcategoryTotals[$subcategoryId])) {
                $subcategoryTotals[$subcategoryId] = ['totalDebit' => 0, 'totalCredit' => 0, 'totalBalance' => 0];
            }
            $subcategoryTotals[$subcategoryId]['totalDebit'] += $totalDebit;
            $subcategoryTotals[$subcategoryId]['totalCredit'] += $totalCredit;
            $subcategoryTotals[$subcategoryId]['totalBalance'] += $totalBalance;

            // Category Totals
            $categoryId = $particularData->category_id;
            if (!isset($categoryTotals[$categoryId])) {
                $categoryTotals[$categoryId] = ['totalDebit' => 0, 'totalCredit' => 0, 'totalBalance' => 0];
            }
            $categoryTotals[$categoryId]['totalDebit'] += $totalDebit;
            $categoryTotals[$categoryId]['totalCredit'] += $totalCredit;
            $categoryTotals[$categoryId]['totalBalance'] += $totalBalance;

            // Account Type Totals
            $accountTypeId = $particularData->account_typeId;
            if (!isset($accountTypeTotals[$accountTypeId])) {
                $accountTypeTotals[$accountTypeId] = ['totalDebit' => 0, 'totalCredit' => 0, 'totalBalance' => 0];
            }
            $accountTypeTotals[$accountTypeId]['totalDebit'] += $totalDebit;
            $accountTypeTotals[$accountTypeId]['totalCredit'] += $totalCredit;
            $accountTypeTotals[$accountTypeId]['totalBalance'] += $totalBalance;

            // Cash Flow Totals
            $cashFlowId = $particularData->cash_flow_typeId;
            if (!isset($cashFlowTotals[$cashFlowId])) {
                $cashFlowTotals[$cashFlowId] = ['totalDebit' => 0, 'totalCredit' => 0, 'totalBalance' => 0];
            }
            $cashFlowTotals[$cashFlowId]['totalDebit'] += $totalDebit;
            $cashFlowTotals[$cashFlowId]['totalCredit'] += $totalCredit;
            $cashFlowTotals[$cashFlowId]['totalBalance'] += $totalBalance;
        }

        # Return the results based on the module
        switch ($module) {
            case 'particular':
                $data = $particularTotals[$module_id] ?? ['totalDebit' => 0, 'totalCredit' => 0, 'totalBalance' => 0];
                break;
            case 'account_type':
                $data = $accountTypeTotals[$module_id] ?? ['totalDebit' => 0, 'totalCredit' => 0, 'totalBalance' => 0];
                break;
            case 'subcategory':
                $data = $subcategoryTotals[$module_id] ?? ['totalDebit' => 0, 'totalCredit' => 0, 'totalBalance' => 0];
                break;
            case 'category':
                $data = $categoryTotals[$module_id] ?? ['totalDebit' => 0, 'totalCredit' => 0, 'totalBalance' => 0];
                break;
            case 'cash_flow':
                $data = $cashFlowTotals[$module_id] ?? ['totalDebit' => 0, 'totalCredit' => 0, 'totalBalance' => 0];
                break;
            default:
                $data = ['totalDebit' => 0, 'totalCredit' => 0, 'totalBalance' => 0];
                break;
        }

        return $data;
    }
    public function calculateTotalBalanceOld(array $data)
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

        if ($entries) {
            # default variables to store particular totals
            $directDebit = $directCredit = $reverseDebit = $reverseCredit = 0;
            // default arrays to store IDs for particulars subcategories, categories and accountTypes
            $particularIDs = $accountTypeIDs = $subcategoryIDs = $categoryIDs = $cashFlowIDs = [];

            # Fetch all particular_ids and payment_ids in one go
            $particularIds = $this->distinct()->select('particular_id')->findColumn('particular_id');
            $paymentIds = $this->distinct()->select('payment_id')->findColumn('payment_id');
            $particularIDs = array_unique(array_merge($particularIds, $paymentIds));

            foreach ($particularIDs as $particular_id) {
                // Reset totals for each particular
                $directDebit = $reverseDebit = $directCredit = $reverseCredit = 0;

                // get particular data for each particular found in the entries table
                $particularTable = $this->db->table('particulars');
                $particularData = $particularTable->where('id', $particular_id)->get()->getRow();
                // skip the loop if particular data isn't found
                if (!$particularData) {
                    continue;
                }
                // skip the loop if particular is inactive
                if (strtolower($particularData->particular_status) == 'inactive') {
                    continue;
                }

                # from particularData, get subcategory, category and account type IDs

                // Check if the key already exists in $subcategoryIDs array
                if (!isset($subcategoryIDs[$particularData->subcategory_id])) {
                    $subcategoryIDs[$particularData->subcategory_id] = $particularData->subcategory_id;
                }
                // Check if the key already exists in $categoryIDs array
                if (!isset($categoryIDs[$particularData->category_id])) {
                    $categoryIDs[$particularData->category_id] = $particularData->category_id;
                }
                // Check if the key already exists in $account_typeIDs array
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

    /** Sum up Total Amount Paid by Client for a Certain Ledger
     * $module = summation needed i.e application, disbursement
     * $module_id = primary key of the module
     * $status = accounting side of the module, i.e debit or credit
     * $entryId = primary key of the entry i.e entries.id to stop summation to
     */
    public function sum_client_amountPaid($client_id, $particular_id, $status, $module = null, $module_id = null)
    {
        // Default totals for direct and reverse entries
        $directDebit = $directCredit = $reverseDebit = $reverseCredit = 0;

        // Build the base query for fetching all relevant entries
        $builder = $this->db->table('entries')
            ->select("
                SUM(CASE WHEN particular_id = $particular_id AND status = 'debit' THEN amount ELSE 0 END) as directDebit, 
                SUM(CASE WHEN payment_id = $particular_id AND status = 'credit' THEN amount ELSE 0 END) as reverseDebit, 
                SUM(CASE WHEN particular_id = $particular_id AND status = 'credit' THEN amount ELSE 0 END) as directCredit, 
                SUM(CASE WHEN payment_id = $particular_id AND status = 'debit' THEN amount ELSE 0 END) as reverseCredit, 
                (SUM(CASE WHEN particular_id = $particular_id AND status = 'debit' THEN amount ELSE 0 END) + SUM(CASE WHEN payment_id = $particular_id AND status = 'credit' THEN amount ELSE 0 END)) AS totalDebit, 
                (SUM(CASE WHEN particular_id = $particular_id AND status = 'credit' THEN amount ELSE 0 END) + SUM(CASE WHEN payment_id = $particular_id AND status = 'debit' THEN amount ELSE 0 END)) as totalCredit,
                ((SUM(CASE WHEN particular_id = $particular_id AND status = 'debit' THEN amount ELSE 0 END) + SUM(CASE WHEN payment_id = $particular_id AND status = 'credit' THEN amount ELSE 0 END)) - (SUM(CASE WHEN particular_id = $particular_id AND status = 'credit' THEN amount ELSE 0 END) + SUM(CASE WHEN payment_id = $particular_id AND status = 'debit' THEN amount ELSE 0 END))) as balance
            ")
            ->where('client_id', $client_id);

        // Add module-specific conditions
        switch ($module) {
            case 'application':
                $builder->where('application_id', $module_id);
                break;
            case 'disbursement':
                $builder->where('disbursement_id', $module_id);
                break;
            case 'savings-product':
                $builder->where('product_id', $module_id);
                break;
            case 'shares':
                $builder->where('account_typeId', $module_id);
                break;
        }

        // Fetch the summed results
        $result = $builder->get()->getRowArray();

        // Extract the sums from the query result
        $directDebit = $result['directDebit'] ?? 0;
        $reverseDebit = $result['reverseDebit'] ?? 0;
        $directCredit = $result['directCredit'] ?? 0;
        $reverseCredit = $result['reverseCredit'] ?? 0;
        $totalDebit = $result['totalDebit'] ?? 0;
        $totalCredit = $result['totalCredit'] ?? 0;
        $paid = $result['balance'] ?? 0;

        // Calculate total debits, credits & final balance (debit - credit)
        // $totalDebit = $directDebit + $reverseDebit;
        // $totalCredit = $directCredit + $reverseCredit;
        // $paid = $totalDebit - $totalCredit;

        // Adjust the result based on the status
        if (strtolower($status) == 'credit' && $paid < 0) {
            $totalPaid = abs($paid);
        } elseif (strtolower($status) == 'credit' && $paid > 0) {
            $totalPaid = -$paid;
        } else {
            $totalPaid = $paid;
        }
        # check whether the module is for savings transaction
        if ($module == 'savings' && $totalPaid < 0) {
            $totalPaid = abs($paid);
        }

        if ($module == 'savings' && $totalPaid > 0) {
            $totalPaid = -$paid;
        }

        return $totalPaid;
    }

    public function sum_client_amountPaid0($client_id, $particular_id, $status, $module = null, $module_id = null)
    {
        # default variables to store ledger totals
        $totalPaid = $totalDebit = $totalCredit = $paid = 0;
        // fetch entries from db
        switch ($module) {
                // Calculate totals for ledger
            case 'application':
                $entries = $this->where(['client_id' => $client_id, 'application_id' => $module_id])->findAll();
                break;
            case 'disbursement':
                $entries = $this->where(['client_id' => $client_id, 'disbursement_id' => $module_id])->findAll();
                break;
            case 'savings-product':
                $product = $this->db->table('products')
                    ->where([
                        'id ' => $module_id,
                        'deleted_at' => NULL
                    ])->get()->getResultArray();
                if ($product) {
                    $entries = $this->where(['client_id' => $client_id, 'product_id' => $module_id])->findAll();
                }
                break;
            case 'shares':
                $entries = $this->where(['client_id' => $client_id, 'account_typeId' => $module_id])->findAll();
                break;
            default:
                $entries = $this->where(['client_id' => $client_id])->findAll();
                break;
        }
        # check if the module has any entries returned
        if ($entries) {
            # default variables to store ledger totals
            $directDebit = $directCredit = $reverseDebit = $reverseCredit = 0;
            # loop through entries & get totals for each ledger
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

            $paid = ($totalDebit - $totalCredit);
            if ((strtolower($status) == 'credit') && $paid < 0) {
                $totalPaid = abs($paid);
            } elseif ((strtolower($status) == 'credit') && $paid > 0) {
                $totalPaid = -$paid;
            } else {
                $totalPaid = $paid;
            }
        }
        # check whether the module is for savings transaction
        if ($module == 'savings' && $totalPaid < 0) {
            $totalPaid = abs($paid);
        }

        if ($module == 'savings' && $totalPaid > 0) {
            $totalPaid = -$paid;
        }

        return $totalPaid;
    }
}
