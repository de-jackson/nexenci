<?php

function getLoanInterval($frequency)
{
    switch (strtolower($frequency)) {
        case "weekly":
            $interval = (1 / 4);
            break;
        case "bi-weekly":
            $interval = (1 / 2);
            break;
        case "monthly":
            $interval = (1);
            break;
        case "bi-monthly":
            $interval = (2);
            break;
        case "quarterly":
            $interval = (3);
            break;
        case "termly":
            $interval = (4);
            break;
        case "bi-annual":
            $interval = (6);
            break;
        case "annually":
            $interval = (12);
            break;
        default:
            $interval = (1);
    }
    return (int)$interval;
};

function pmt($method, $principal, $rate, $period, $interval, $interestPeriod)
{
    if (strtolower($interestPeriod) === "year") {
        # Calculate the total number of payments in a year.
        $payouts = (12 / (int)$interval);
        # convert period to years
        $loanTerm = (($period) / $payouts);
        # Calculate the total number of monthly payments
        $numberOfPayments = (($period / $interval));
    } else {
        # Calculate the total number of payments in a year.
        $payouts = (int)$interval;
        # Convert period to years
        $loanTerm = (($period) / $payouts);
        # Calculate the total number of monthly payments
        $numberOfPayments = (($period / $interval));
    }


    if (strtolower($method) == 'reducing') {
        /**
         * M = ([P * r * (1 + r)^n] / [((1 + r)^n - 1)])
         * Where:
         * M is the monthly installment
         * P is the loan amount (principal)
         * r is the monthly interest rate (calculated by dividing the annual interest rate by 12)
         * n is the total number of monthly payments (calculated by multiplying the loan term by 12)
         */

        // Convert annual interest rate to monthly interest rate
        $monthlyInterestRate = (($rate / 100) / $payouts);
        // Calculate the monthly installment using the formula
        $numerator = ($principal * $monthlyInterestRate * pow((1 + $monthlyInterestRate), $numberOfPayments));
        $denominator = ((pow((1 + $monthlyInterestRate), $numberOfPayments)) - 1);

        $monthlyInstallment = ($numerator / $denominator);
    } else {
        /** 
         * M = (P + (P * r * n)) / (n * 12)
         * Where:
         * M is the monthly installment
         * P is the loan amount (principal)
         * r is the flat interest rate
         * n is the loan term in years
         */

        // Calculate the interest amount
        $interestAmount = $principal * ($rate / 100) * $loanTerm;
        // Calculate the total amount repayable
        $totalAmountRepayable = $principal + $interestAmount;
        // Calculate the monthly installment using the formula
        $monthlyInstallment = $totalAmountRepayable / $numberOfPayments;
    }
    // Round the monthly installment to two decimal places
    $monthlyInstallment = $monthlyInstallment;
    // Return the monthly installment
    return $monthlyInstallment;
}

?>

<?= $this->extend("layout/client"); ?>

<?= $this->section("content"); ?>

<?php if (strtolower($user['account']) == 'approved') : ?>
    <?php include_once(APPPATH . 'Views/client/dashboard/home.php'); ?>
<?php else : ?>
    <!-- Start::row-2 -->
    <?php include_once(APPPATH . 'Views/client/dashboard/kyc.php'); ?>
<?php endif; ?>
<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>
<script>
    // var table = $(".table").DataTable({
    //     pageLength: 25
    // });
    var staff_id = '<?= $user["staff_id"]; ?>';
    var gender = '<?= $user["gender"]; ?>';

    var chartData = {
        'summary': <?= json_encode($summary); ?>,
    };
</script>

<!-- Dashboard 1 -->
<script src="/assets/vendor/apexchart/apexchart.js"></script>

<!--home-js -->
<script src="/assets/client/dashboard/home.js"></script>

<script src="/assets/client/main/main.js"></script>
<script src="/assets/client/main/options.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>
<?= $this->endSection(); ?>