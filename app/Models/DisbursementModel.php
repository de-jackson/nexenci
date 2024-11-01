<?php

namespace App\Models;

use CodeIgniter\Model;

class DisbursementModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'disbursements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'disbursement_code',
        'cycle',
        'application_id',
        'product_id',
        'branch_id',
        'client_id',
        'staff_id',
        'particular_id',
        'interest_particular_id',
        'payment_id',
        'disbursed_by',
        'principal',
        'computed_interest',
        'actual_interest',
        'computed_installment',
        'actual_installment',
        'principal_installment',
        'interest_installment',
        'computed_repayment',
        'actual_repayment',
        'loan_period_days',
        'days_covered',
        'days_remaining',
        'grace_period',
        'installments_num',
        'installments_covered',
        'first_recovery',
        'loan_expiry_date',
        'expiry_day',
        'expected_interest_recovered',
        'expected_principal_recovered',
        'expected_amount_recovered',
        'expected_loan_balance',
        'interest_collected',
        'principal_collected',
        'total_collected',
        'interest_balance',
        'principal_balance',
        'total_balance',
        'arrears',
        'arrears_info',
        'principal_due',
        'interest_due',
        'installments_due',
        'days_due',
        'status',
        'class',
        'comments',
        'date_disbursed',
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

    /*
    * Calculates the loan repayment based on the given loan details and repayment amount.//+

    * @param array $loan An associative array containing loan details such as repayment, monthlyInstallment, monthlyInterest, totalInterestCollected, totalInterest, principal, interestBalance, originalPrincipal, totalPrincipalPaid.

    * @return array An associative array containing the calculated loan repayment details such as principal, interestBalance, repayment, principalRepayment, interestRepayment, principalBalance, interestPayableBalance, totalLoanBalance, installmentNumber, overpaymentBalance, interestCollected, principalCollected, totalLoanCollection.
    */
    public function calculateLoanRepayment($loan): array
    {
        $repayment = $loan['repayment'];
        $monthlyInstallment = $loan['monthlyInstallment'];
        $monthlyInterest = $loan['monthlyInterest'];
        $totalInterestPaid = $loan['totalInterestCollected'];
        $totalInterest = $loan['totalInterest'];
        $principal = $loan['principal'];
        $interestBalance = $loan['interestBalance'];
        $originalPrincipal = $loan['originalPrincipal'];
        $totalPrincipalPaid = $loan['totalPrincipalPaid'];
        $overPayment = 0;

        # compute the number of installments based on the repayment amount
        $installmentNumber = round((($repayment) / ($monthlyInstallment)));
        # $installmentNumber = floor((($repayment)/($monthlyInstallment)));
        $mode = round((($repayment) % ($monthlyInstallment)));
        # check whether the installment number is greater than zero
        if ($installmentNumber > 0) {
            # Calculate interest based on the number of installments the repayment amount can take
            $interest = $monthlyInterest * $installmentNumber;
        } else {
            # Calculate interest for the current month
            $interest = $monthlyInterest;
        }
        
        # check the total interest to be covered
        $interestRecovered = ($totalInterestPaid + $interest);
        if ($interestRecovered <= $totalInterest) {
            $interest = $interest;
        } else {
            $interest = $totalInterest - $totalInterestPaid;
        }

        # record the amount as interest if principal balance is 0 yet interest balance isnt
        if (($principal == 0) && ($interestBalance > 0)) {
            $interest = $repayment;
        }

        # Calculate principal reduction
        $principalReduction = $repayment - $interest;

        # check whether the principal paid is in negatives meaning 
        # the repayment amount can not cover the monthly interest
        if ($principalReduction < 0) {
            # Update the interest paid to repayment because the repayment amount does not cover the monthly interest 
            $interest = $repayment;
            # Update the principal paid to 0 to avoid pushing the interest balance 
            # to the principal balance
            $principalReduction = 0;
        }

        # Update the principal balance
        $principalBalance = $principal - $principalReduction;

        # Update the Interest Balance
        $interestPayableBalance = $interestBalance - $interest;


        # check whether the loan is fully paid
        if ($principalBalance < 0) {
            # Update the overpayment balance on loan repayment
            $overPayment = abs($principalBalance);
            # check whether the installment number is greater than zero
            if ($installmentNumber > 0) {
                # $principalReduction = $principalBalance = 0;
            }
            # Update the principal paid 
            $principalReduction = $originalPrincipal - $totalPrincipalPaid;
            # Update the loan balance to 0 since there is overpayment
            $principalBalance = 0;
        }

        # Update the total Interest and Principal Collected
        $interestCollected = $totalInterestPaid + $interest;
        $principalRepayment = ($principalReduction < 0) ? 0 : $principalReduction;
        $principalCollected = $totalPrincipalPaid + $principalRepayment;
        $totalLoanCollection = $interestCollected + $principalCollected;

        return [
            # Total Principal and Interest Outstanding Balance
            'principal' => $principal,
            'interestBalance' => $interestBalance,
            # Total Loan Repayment, Principal and Interest
            'repayment' => $repayment,
            'principalRepayment' => $principalRepayment,
            'interestRepayment' => $interest,
            # Total Principal and Interest Balance (B/f)
            'principalBalance' => $principalBalance,
            'interestPayableBalance' => $interestPayableBalance,
            'totalLoanBalance' => $principalBalance + $interestPayableBalance,
            'installmentNumber' => $installmentNumber,
            'overpaymentBalance' => $overPayment,
            # Total Loan Interest and Principal Collected
            'interestCollected' => $interestCollected,
            'principalCollected' => $principalCollected,
            'totalLoanCollection' => $totalLoanCollection

        ];
    }
}
