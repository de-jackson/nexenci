<?php

$principal = $original_principal = 2000000; # Initial principal amount
$annualInterestRate = 0.08; # Annual interest rate (8%)
$monthlyInstallment = 493400; # Monthly installment amount
$repaymentPeriod = 6; # Loan Repayment Period
# $monthlyInterest = ($principal * $annualInterestRate) / $repaymentPeriod; # Lona monthly interest
$monthlyInterest = ($principal * $annualInterestRate); # Since the rate is per month
$monthlyPrincipal = $monthlyInstallment - $monthlyInterest; # Loan monthly principal
$totalInterest = $interestBalance = 960000; # Loan total interest
$totalLoanRepayment = 2960000; # Loan total repayment

# $repaymentAmounts = [54000, 108000, 54000, 270000, 162000];
# $repaymentAmounts = [54000, 108000, 50, 48000, 4000, 4000, 50000, 379950];
# $repaymentAmounts = [40000, 2000, 50000, 120000, 54000, 80000, 4000, 282000, 10000, 4000, 2000];
# $repaymentAmounts = [4000, 2000, 6000, 4000, 4000, 4000, 2000, 6000, 4000, 4000, 4000, 4000, 600000];
# $repaymentAmounts = [74000, 108000, 48000, 50, 4000, 4000, 50000, 60000, 70000, 100000, 3950, 126000];

$repaymentAmounts = [493400, 600000, 2000000];

echo '<table border="1">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Principal (B/f)</th>
                <th>Interest (B/f)</th>
                <th>Repayment Amount</th>
                <th>Principal Paid</th>
                <th>Interest Paid</th>
                <th>Principal Balance</th>
                <th>Interest Balance</th>
                <th>Number of Installment</th>
                <th>Total Loan Balance</th>
                <th>Overpayment Balance</th>
            </tr>
        </thead>
        <tbody>';
$sno = 1;
$totalInterestPaid  = $totalPrincipalPaid = $totalRepayment = 0;
$totalInstallmentNumber = $overPayment = 0;
foreach ($repaymentAmounts as $repayment) :
    $loan = [
        'repayment' => $repayment,
        'monthlyInstallment' => $monthlyInstallment,
        'monthlyInterest' => $monthlyInterest,
        'totalInterestCollected' => $totalInterestPaid,
        'totalInterest' => $totalInterest,
        'principal' => $principal,
        'interestBalance' => $interestBalance,
        'originalPrincipal' => $original_principal,
        'totalPrincipalPaid' => $totalPrincipalPaid
    ];
    # total repayment
    $totalRepayment += $repayment;
    $data = $this->disbursement->calculateLoanRepayment($loan);
    $overPayment += $data['overpaymentBalance'];
    echo '
                <tr>
                    <td >' . $sno . '</td>
                    <td>' . number_format($data['principal']) . '</td>
                    <td>' . number_format($data['interestBalance']) . '</td>
                    <td>' . number_format($repayment) . '</td>
                    <td>' . number_format($data['principalRepayment']) . '</td>
                    <td>' . number_format(($data['interestRepayment'])) . '</td>
                    <td>' . number_format($data['principalBalance']) . '</td>
                    <td>' . number_format($data['interestPayableBalance']) . '</td>
                    <td>' .  number_format($data['installmentNumber']) . '</td>
                    <td>' .  number_format($data['totalLoanBalance']) . '</td>
                    <td>' .  number_format($overPayment) . '</td>
                </tr>';
    # update the principal balance
    $principal = $data['principalBalance'];
    # Update the Interest Balance
    $interestBalance = $data['interestPayableBalance'];
    # Update the interest paid
    $totalInterestPaid  += $data['interestRepayment'];
    # Update the principal paid
    $totalPrincipalPaid += $data['principalRepayment'];
    # Update the total number of installments
    $totalInstallmentNumber += $data['installmentNumber'];
    $sno++;
endforeach;
echo '</tbody>
        <tfoot>
            <tr class="bg-dark text-white">
                <th colspan="3" class="text-center">TOTAL</th>
                <td>' . number_format(round($totalRepayment)) . '</td>
                <td>' . number_format(round($totalPrincipalPaid)) . '</td>
                <td>' . number_format(round($totalInterestPaid)) . '</td>
                <td></td>
                <td></td>
                <td>' . number_format($totalInstallmentNumber) . '</td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>';
