<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'staffs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'staffID', 'staff_name', 'mobile', 'alternate_mobile', 'email','gender','marital_status','religion', 'nationality', 'date_of_birth', 'address', 'account_type', 'branch_id', 'position_id', 'id_type', 'id_number','id_expiry_date', 'qualifications', 'salary_scale', 'bank_name','bank_branch','bank_account', 'appointment_type', 'photo', 'id_photo_front', 'id_photo_back', 'signature', 'access_status', 'officer_staff_id', 'reg_date', 'account_id'
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
