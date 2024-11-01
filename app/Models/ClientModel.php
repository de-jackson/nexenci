<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'title', 'branch_id', 'staff_id', 'account_no', 'account_type', 'savings_products', 'account_balance', 'email', 'mobile', 'alternate_no', 'gender', 'dob', 'marital_status', 'religion', 'nationality', 'occupation', 'job_location', 'residence', 'closest_landmark', 'id_type', 'id_number', 'id_expiry_date', 'next_of_kin_name', 'next_of_kin_relationship', 'next_of_kin_contact', 'next_of_kin_alternate_contact', 'nok_email', 'nok_address', 'photo', 'id_photo_front', 'id_photo_back', 'password', 'signature', 'access_status', 'token', 'token_expire_date', 'reg_date', '2fa', 'account', 'account_id'
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
