<?php

namespace App\Models;

use CodeIgniter\Model;

class LoanApplicationModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'loanapplications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'application_code', 'application_date', 'client_id', 'staff_id', 'product_id', 'branch_id', 'principal', 'purpose', 'status', 'level', 'action', 'overall_charges', 'total_charges', 'reduct_charges', 'security_item', 'security_info', 'est_value', 'ref_name', 'ref_address', 'ref_job', 'ref_contact', 'ref_alt_contact', 'ref_email', 'ref_relation', 'ref_name2', 'ref_address2', 'ref_job2', 'ref_contact2', 'ref_alt_contact2', 'ref_email2', 'ref_relation2', 'net_salary', 'farming', 'business', 'others', 'rent', 'education', 'medical', 'transport', 'exp_others', 'difference', 'dif_status', 'institute_name', 'institute _branch', 'account_type', 'institute_name2', 'institute_branch2', 'account_type2', 'amt_advance', 'date_advance', 'loan_duration', 'amt_outstanding', 'amt_advance2', 'date_advance2', 'loan_duration2', 'amt_outstanding2', 'loan_agreement', 'applicant_products', 'account_id'
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

    public function setApplicationRemarkProcess($oldLevel, $action, $status) {
        $applicationStatus = [];
        switch (strtolower($action)) {
            case 'approved':
                if ($oldLevel == 'Credit Officer') {
                    # Update the Loan Application status
                    if (strtolower($status) == 'pending') {
                        $status = 'Processing';
                    } else {
                        $status = $status; // maintain status
                    }
                    $newLevel = 'Supervisor'; // change to Supervisor level
                    $newAction = 'Processing'; // change action
                } elseif ($oldLevel == 'Supervisor') {
                    $status = $status; // maintain status
                    $newLevel = 'Operations Officer'; // change to Operations Officer level
                    $newAction = 'Processing'; // change action
                } elseif ($oldLevel == 'Operations Officer') {
                    $status = $status; // maintain status
                    $newLevel = 'Accounts Officer'; // change to Accounts Officer level
                    $newAction = 'Processing'; // change action
                } else {
                    $status = 'Approved'; // change status
                    $newLevel = 'Accounts Officer'; // change to Accounts Officer  level
                    $newAction = $action; // change action
                }
                break;
            case 'declined':
                $status = 'Declined'; // change status
                $newLevel = $oldLevel; // maintain level
                $newAction = $action; // change action
                break;
            default:
                $status = $status; // maintain status
                $newLevel = $oldLevel; // maintain level
                $newAction = $action; // change action
                break;
        }
        $applicationStatus = [
            'level' => $newLevel,
            'status' => $status,
            'action' => $newAction,
        ];

        return $applicationStatus;
    }
}
