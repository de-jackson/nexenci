<?php

namespace App\Models;

use CodeIgniter\Model;

class LoanProductsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'loanproducts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_name',
        'product_code',
        'interest_rate',
        'interest_period',
        'interest_type',
        'loan_period',
        'loan_frequency',
        'repayment_period',
        'repayment_duration',
        'repayment_freq',
        'min_principal',
        'max_principal',
        'min_savings_balance_type_application',
        'min_savings_balance_application',
        'max_savings_balance_type_application',
        'max_savings_balance_application',
        'min_savings_balance_type_disbursement',
        'min_savings_balance_disbursement',
        'max_savings_balance_type_disbursement',
        'max_savings_balance_disbursement',
        'product_desc',
        'product_features',
        'product_charges',
        'status',
        'principal_particular_id',
        'interest_particular_id',
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

    public function getOtherLoanProduct($frequency)
    {
        switch (strtolower($frequency)) {
            case 'daily':
                $interval = 1 / 30;
                $grace_period = 1;
                $duration = 'day(s)';
                break;
            case 'weekly':
                $interval = 1 / 4;
                $grace_period = 7;
                $duration = 'week(s)';
                break;
            case 'bi-weekly':
                $interval = 1 / 2;
                $grace_period = 14;
                $duration = 'week(s)';
                break;
            case 'monthly':
                $interval = 1;
                $grace_period = 30;
                $duration = 'month(s)';
                break;
            case 'bi-monthly':
                $interval = 2;
                $grace_period = 60;
                $duration = 'month(s)';
                break;
            case 'quarterly':
                $interval = 3;
                $grace_period = 90;
                $duration = 'month(s)';
                break;
            case 'termly':
                $interval = 4;
                $grace_period = 120;
                $duration = 'month(s)';
                break;
            case 'bi-annual':
                $interval = 6;
                $grace_period = 180;
                $duration = 'month(s)';
                break;
            case 'annually':
                $interval = 12;
                $grace_period = 365;
                $duration = 'year(s)';
                break;
            default:
                $interval = null;
                $grace_period = null;
                $duration = null;
                break;
        }

        $data = [
            'interval' => $interval,
            'grace_period' => $grace_period,
            'duration' => $duration,
        ];

        return $data;
    }

    public function get_topProducts($topProducts)
    {
        $products = [];
        if ($topProducts) {
            // Retrieve loan products based on performers' product IDs && count stored in $topProducts
            $loanProducts = $this->db->table('loanproducts')
                ->whereIn('id', array_keys($topProducts))
                ->orderBy('COUNT(id)', 'DESC')
                ->get()->getResult();

            foreach ($loanProducts as $product) {
                $productId = $product->id;
                $productCount = $topProducts[$productId];

                $products[] = [
                    'product' => $product,
                    'count' => $productCount,
                    // Add any additional properties you need from the loan products table
                ];
            }
        }

        return $products;
    }
}
