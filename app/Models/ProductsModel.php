<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_name', 'product_code', 'product_type', 'min_account_balance', 'max_account_balance', 'min_per_entry', 'max_per_entry', 'interest_rate', 'interest_period', 'interest_type', 'product_period', 'product_duration', 'product_frequency', 'product_desc', 'product_charges', 'product_features', 'application_min_savings_balance_type', 'application_min_savings_balance', 'application_max_savings_balance_type', 'application_max_savings_balance', 'disbursement_min_savings_balance_type', 'disbursement_min_savings_balance', 'disbursement_max_savings_balance_type', 'disbursement_max_savings_balance', 'status', 'savings_particular_id', 'withdrawCharges_particular_id', 'account_id'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

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
    public function getOtherLoanProduct($frequency) {
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
        if($topProducts){
        // Retrieve loan products based on performers' product IDs && count stored in $topProducts
            $loanProducts = $this->db->table('products')
                ->whereIn('id', array_keys($topProducts))
                ->orderBy('COUNT(id)', 'DESC')
                ->get()->getResult();

            foreach ($loanProducts as $product) {
                if($product->product_type == 'loans'){
                    $productId = $product->id;
                    $productCount = $topProducts[$productId];
                }
                $products[] = [
                    'product' => $product,
                    'count' => $productCount,
                ];
            }
        }

        return $products;
    }
}
