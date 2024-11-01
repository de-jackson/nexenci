<?php

namespace App\Models;

use CodeIgniter\Model;

class ParticularModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'particulars';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'particular_name', 'particular_code', 'particular_slug', 'particular_type', 'particular_status', 'category_id', 'subcategory_id', 'account_typeId', 'cash_flow_typeId', 'charged', 'particular_charges', 'charge', 'charge_method', 'charge_mode', 'charge_frequency', 'grace_period', 'opening_balance', 'debit', 'credit', 'account_id'
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
}
