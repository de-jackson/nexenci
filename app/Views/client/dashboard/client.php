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

<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <h1 class="page-title fw-semibold fs-18 mb-0">
        Welcome back, <?= $user['name']; ?>!
    </h1>
</div>
<!-- Page Header Close -->
<?php if (strtolower($user['account']) == 'approved') : ?>
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xxl-12 col-xl-12">
            <div class="row">
                <?php
                if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
                        <i class="fas fa-check-double"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php elseif (session()->getFlashdata('failed')) : ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?= session()->getFlashdata('failed') ?>
                    </div>
                <?php endif; ?>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon px-0">
                                    <span class="avatar avatar-md avatar-rounded bg-info">
                                        <i class="ti ti-wallet fs-16"></i>
                                    </span>
                                </div>
                                <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">My Account</div>
                                    <div class="text-muted mb-1 fs-12">
                                        <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                            <?= $settings['currency'] . ' ' . number_format($user['account_balance'], 2); ?>
                                        </span>
                                    </div>
                                    <!-- <div>
                                        <span class="fs-12 mb-0">Increase by <span class="badge bg-success-transparent text-success mx-1">+4.2%</span> this
                                            month</span>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                    <span class="avatar avatar-md avatar-rounded bg-warning">
                                        <i class="ti ti-wallet fs-16"></i>
                                    </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">I Owe</div>
                                    <div class="text-muted mb-1 fs-12">
                                        <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                            <?= $settings['currency'] . ' ' . number_format($totalLoan, 2); ?>
                                        </span>
                                    </div>
                                    <!-- <div>
                                        <span class="fs-12 mb-0">Increase by <span class="badge bg-success-transparent text-success mx-1">+12.0%</span> this
                                            month</span>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($totalLoan) : ?>
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon success px-0">
                                        <span class="avatar avatar-md avatar-rounded bg-success">
                                            <i class="ti ti-wallet fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                        <div class="mb-2">Total Loan Paid</div>
                                        <div class="text-muted mb-1 fs-12">
                                            <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                                <?= $settings['currency'] . ' ' . number_format($totalLoanPaid, 2); ?>
                                            </span>
                                        </div>
                                        <!-- <div>
                                            <span class="fs-12 mb-0">Decreased by <span class="badge bg-danger-transparent text-danger mx-1">-7.6%</span> this
                                                month</span>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0">
                                        <span class="avatar avatar-md avatar-rounded bg-secondary">
                                            <i class="ti ti-wallet fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                        <div class="mb-2">Total Loan Outstanding Balance</div>
                                        <div class="text-muted mb-1 fs-12">
                                            <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                                <?= $settings['currency'] . ' ' . number_format($totalLoanBalance, 2); ?>
                                            </span>
                                        </div>
                                        <!-- <div>
                                            <span class="fs-12 mb-0">Increased by <span class="badge bg-success-transparent text-success mx-1">+2.5%</span> this
                                                month</span>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (count($disbursements) > 0) : ?>
                        <div class="col-xl-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Loan Repayment Plans</div>
                                </div>
                                <div class="card-body">

                                    <div class="bd-example">
                                        <div class="row">
                                            <div class="col-md-2 col-12">
                                                <div id="list-example" class="list-group">
                                                    <?php
                                                    $counter = 1;
                                                    foreach ($disbursements as $key => $disbursement) : ?>
                                                        <a class="list-group-item list-group-item-action" href="#list-item-<?= $key; ?>">
                                                            <?= number_format($disbursement['principal']); ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-10 col-12">
                                                <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" class="scrollspy-example-3" tabindex="0">
                                                    <?php
                                                    foreach ($disbursements as $key => $disbursement) : ?>

                                                        <h6 class="fw-semibold" id="list-item-<?= $key; ?>">
                                                            <?= $disbursement['product_name'] . " - " . number_format($disbursement['principal']); ?>
                                                        </h6>

                                                        <!-- <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                            Vel,
                                                            laborum
                                                            reiciendis sunt officia doloribus, soluta ratione id
                                                            reprehenderit
                                                            autem
                                                            temporibus cupiditate necessitatibus atque similique quam ex
                                                            minus,
                                                            sint
                                                            ipsum deleniti sed assumenda fugiat numquam saepe incidunt
                                                            perferendis.
                                                            Aliquid, quas.
                                                        </p> -->
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-bordered" style="width: 100%">
                                                                <thead>
                                                                    <tr class="bg-dark text-white">
                                                                        <th scope="col" class="text-center">S.No</th>
                                                                        <th scope="col">Due Date</th>
                                                                        <th scope="col">Principal</th>
                                                                        <th scope="col">Principal Payable</th>
                                                                        <th scope="col">Interest Payable</th>
                                                                        <th scope="col">Installment</th>
                                                                        <th scope="col" class="text-center">
                                                                            Principal Balance
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="top-selling">
                                                                    <?php

                                                                    $applicantProduct = unserialize($disbursement['applicant_products']);
                                                                    $xcounter = 1;

                                                                    $disbursement_date = strtotime("2023-03-01");

                                                                    $number_of_installment = (int)$disbursement['installments_num'];

                                                                    $principal = $original_principal = (int)$disbursement['principal'];

                                                                    # $instalment = 169106;

                                                                    $interest_rate = $applicantProduct['InterestRate'] / 100;
                                                                    $interestPeriod = $applicantProduct['InterestPeriod'];
                                                                    $method = strtolower($applicantProduct['InterestType']);
                                                                    $rate = $applicantProduct['InterestRate'];
                                                                    $period = $applicantProduct['RepaymentPeriod'];

                                                                    # check the loan interest period
                                                                    if (strtolower($interestPeriod) == "year") {
                                                                        # code...
                                                                        $interval = getLoanInterval($applicantProduct['LoanFrequency']);

                                                                        # Calculate the total number of payments in a year.
                                                                        $pairs_outs = (12 / $interval);
                                                                    } else {
                                                                        # code...
                                                                        $interval = 1;

                                                                        # Calculate the total number of payments.
                                                                        $pairs_outs = ($interval);
                                                                    }

                                                                    $instalment = (pmt($method, $principal, $rate, $period, $interval, $interestPeriod));
                                                                    $total_principal_instalment = $total_insterest_instalment = $total_instalment = 0;

                                                                    for ($counter = 1; $counter <= $number_of_installment; $counter++) :

                                                                        // if ($key == 1) {
                                                                        //     continue;
                                                                        // }

                                                                        if (strtolower($method) == 'reducing') {
                                                                            // code...
                                                                            $insterest_instalment = (($principal * $interest_rate) / $pairs_outs);

                                                                            $principal_instalment = ($instalment - $insterest_instalment);

                                                                            $principal_balance = (($principal - $principal_instalment) > 0) ? ($principal - ($principal_instalment)) : "(0)";
                                                                        } else {
                                                                            $principal_instalment = ($original_principal / $number_of_installment);
                                                                            $insterest_instalment = ($instalment - $principal_instalment);
                                                                            $principal_balance = $principal - $principal_instalment;
                                                                            $principal_balance = (($principal - $principal_instalment) > 0) ? ($principal - ($principal_instalment)) : "(0)";
                                                                        }

                                                                        # calculate the total
                                                                        $total_principal_instalment += ($principal_instalment);
                                                                        $total_insterest_instalment += ($insterest_instalment);
                                                                        $total_instalment += $instalment;

                                                                        # loan expected payment date
                                                                        # $date = strtotime(date("Y-m-d"));
                                                                        if (!empty($disbursement['date_disbursed'])) {
                                                                            $date = strtotime($disbursement['date_disbursed']);
                                                                        } else {
                                                                            $date = strtotime(date("Y-m-d"));
                                                                        }
                                                                        /*
                                                                        $expiry_date = date("Y-m-d", strtotime("+" . $counter . " month", $date));
                                                                        */
                                                                        $expiry_date = date("Y-m-d", strtotime("+" . $counter . " " . $interestPeriod, $date));

                                                                    ?>
                                                                        <tr class="<?= ($expiry_date == $date) ? 'bg-danger text-white' : '' ?>">
                                                                            <td class="text-center lh-1">
                                                                                <?= $counter; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?= date('D', strtotime($expiry_date)) . ", " .
                                                                                    date("d-m-Y", strtotime($expiry_date)); ?>
                                                                            </td>
                                                                            <td>
                                                                                <span class="fw-semibold">
                                                                                    <?= number_format($principal); ?>
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <!-- <span class="badge badge-sm bg-success-transparent text-success"> -->
                                                                                <span class="fw-semibold">
                                                                                    <?= number_format(round($principal_instalment)); ?>
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <span class="fw-semibold">
                                                                                    <?= number_format(round(($insterest_instalment))); ?>
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <span class="fw-semibold">
                                                                                    <?= number_format($instalment); ?>
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <?= (($principal_balance) > 0) ? number_format($principal_balance) : $principal_balance; ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                        # update the principal balance
                                                                        $principal = $principal_balance;
                                                                    endfor; ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr class="bg-dark text-white">
                                                                        <th colspan="2" class="text-center">TOTAL</th>
                                                                        <td></td>
                                                                        <td><?= number_format(round($total_principal_instalment)); ?></td>
                                                                        <td><?= number_format(round($total_insterest_instalment)); ?></td>
                                                                        <td><?= number_format($total_instalment); ?></td>
                                                                        <td></td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        <hr>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6">
                    <div class="card custom-card overflow-hidden">
                        <div class="ecommerce-sale-image">
                            <img src="/assets/dist/img/35.png" class="card-img-top" alt="Client">
                            <div class="card-img-overlay d-flex flex-column p-4 text-fixed-white">
                                <p class="mb-1 op-7 fs-12">What would you like to do today?</p>
                                <h6 class="fw-semibold mb-4">BIG SAVING DAYS</h6>
                                <p class="ecommerce-bankoffer-details">
                                    Saving today is the key to a brighter tomorrow. Your financial discipline today will pave the way for your dreams to come true.
                                    <a href="javascript:void(0);" class="text-decoration-underline text-fixed-white">T&amp;C</a>
                                </p>
                                <p class="ecommerce-sale-days mb-0">
                                    <?php
                                    $currentDate = date("Y-m-d");
                                    $futureDate = date("Y-m-d", strtotime($currentDate . "+7 days"));
                                    echo date('d, M Y') . ' - ' . date("d, M Y", strtotime($futureDate));
                                    ?>
                                </p>
                                <p class="ecommerce-sales-calendar text-center mb-0">
                                    <span><?= date('jS'); ?></span>
                                    <span class="d-block fs-15 lh-1 fw-semibold">
                                        <?= date('F'); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="javascript:void(0);" class="text-primary fs-15 fw-semibold">
                                What would you like to do today?
                            </a>
                            <p class="mb-3">
                                Financial growth is a journey of balance. Save diligently to build your foundation, and when opportunities arise, consider wisely whether to invest in your dreams. Remember, your financial choices today shape your prosperous tomorrow.
                            </p>
                            <a href="<?= base_url('client/transactions/type/deposit'); ?>" class="btn btn-success btn-wave me-2">
                                Make Savings
                            </a>
                            <a href="<?= base_url('client/applications'); ?>" class="btn btn-outline-primary btn-wave" <?= ($totalLoan) ? 'style="display: none;"' : ''; ?>>
                                Apply for a new loan
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Your Recent Logs
                            </div>
                            <!-- <div class="dropdown">
                                <a aria-label="anchor" href="javascript:void(0);" class="btn btn-icon btn-sm btn-light" data-bs-toggle="dropdown">
                                    <i class="fe fe-more-vertical"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                </ul>
                            </div> -->
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <?php if (count($logs) > 0) :
                                    foreach ($logs as $key => $log) : ?>
                                        <li class="mb-3">
                                            <a href="javascript:void(0);">
                                                <div class="d-flex algn-items-center">
                                                    <div class="flex-fill ms-2">
                                                        <p class="fw-semibold mb-0">
                                                            <i class="ri-computer-line"></i> <?= $log['operating_system'] ?> using <?= $log['browser'] ?>
                                                        </p>
                                                        <p class="fs-12 text-muted mb-0">
                                                            <b>IP Address:</b> <?= $log['ip_address']; ?><br />
                                                            <b>Location:</b> <?= $log['location']; ?>
                                                            <br>
                                                            <b>Login Time:</b> <?= date('l jS, F Y H:i:s', strtotime($log['login_at'])); ?>
                                                            <?php if (!empty($log['logout_at'])) : ?>
                                                                <br>
                                                                <b>Logout Time:</b> <?= date('l jS, F Y H:i:s', strtotime($log['logout_at'])); ?>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <span class="<?= ($log['status'] == 'online') ? 'text-success' : 'text-danger' ?> fw-semibold">
                                                            <?= ucwords($log['status']); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <hr>
                                <?php endforeach;
                                endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between d-sm-flex d-block">
                            <div class="card-title">
                                Orders
                            </div>
                            <div>
                                <ul class="nav nav-tabs justify-content-end nav-tabs-header mb-0 d-sm-flex d-block mt-sm-0 mt-2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page" href="#active-orders" aria-selected="true">Active Orders</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#completed-orders" aria-selected="true">Completed</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#cancelled-orders" aria-selected="true">Cancelled</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content">
                                <div class="tab-pane show active text-muted border-0 p-0" id="active-orders" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table card-table table-vcenter text-nowrap mb-0">
                                            <tbody class="active-tab">
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md online avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/4.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Amanda Nanes</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$229.99</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Delivery Date</span>
                                                            <p class="mb-0">24 May 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/1.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md online avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/10.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Peter Parkour</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$135.29</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Delivery Date</span>
                                                            <p class="mb-0">18 May 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/2.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md offline avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/12.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Jackie Chen</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$1,299.99</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Delivery Date</span>
                                                            <p class="mb-0">29 May 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/3.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md online avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/5.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Ryan Gercia</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$249.29</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Delivery Date</span>
                                                            <p class="mb-0">05 Jun 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/4.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md offline avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/14.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Hugh Jackma</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$499.99</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Delivery Date</span>
                                                            <p class="mb-0">15 May 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/5.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane text-muted border-0 p-0" id="completed-orders" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table card-table table-vcenter text-nowrap mb-0">
                                            <tbody class="active-tab">
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md offline avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/2.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Lisa Rebecca</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$1,199.99</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-success">Delivered On</span>
                                                            <p class="mb-0">24 Dec 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/6.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md offline avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/13.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Matt Martin</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$799.99</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-success">Delived On</span>
                                                            <p class="mb-0">18 Nov 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/7.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md online avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/7.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Mitchell Osama</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$279.00</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-success">Delivery On</span>
                                                            <p class="mb-0">29 Dec 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/8.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md online avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/12.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Cornor Mcgood</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$79.99</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-success">Delivered On</span>
                                                            <p class="mb-0">05 Dec 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/9.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md online avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/15.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Kishan Patel</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$1449.29</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-success">Delivered On</span>
                                                            <p class="mb-0">20 Nov 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/10.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane text-muted border-0 p-0" id="cancelled-orders" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table card-table table-vcenter text-nowrap mb-0">
                                            <tbody class="active-tab">
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md online avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/6.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Hailey Bobber</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$199.99</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-danger">Cancelled Date</span>
                                                            <p class="mb-0">09 Dec 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/11.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md offline avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/14.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Anthony Mansion</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$179.99</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-danger">Cancelled Date</span>
                                                            <p class="mb-0">28 Dec 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/12.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md online avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/16.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Simon Carter</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$149.99</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-danger">Cancelled Date</span>
                                                            <p class="mb-0">30 Dec 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/13.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md online avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/3.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Sofia Sekh</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$1439.99</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-danger">Cancelled Date</span>
                                                            <p class="mb-0">03 Dec 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/14.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="lh-1">
                                                                <span class="avatar avatar-md offline avatar-rounded me-2">
                                                                    <img src="/dist/assets/images/faces/9.jpg" alt="">
                                                                </span>
                                                            </div>
                                                            <div class="align-items-center">
                                                                <span class="fs-12 text-muted">Name</span>
                                                                <p class="mb-0">Kimura Kai</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-muted">Price</span>
                                                            <p class="mb-0 fw-semibold">$1092.99</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <span class="fs-12 text-danger">Cancelled Date</span>
                                                            <p class="mb-0">02 Dec 2022</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="avatar avatar-md">
                                                            <img src="/dist/assets/images/ecommerce/png/15.png" alt="">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a aria-label="anchor" href="javascript:void(0);">
                                                            <span class="orders-arrow"><i class="ri-arrow-right-s-line fs-18"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="col-xxl-6 col-xl-12">
            <div class="row">
                <!--<div class="col-xl-12">-->
                <!--    <div class="card custom-card">-->
                <!--        <div class="card-header justify-content-between">-->
                <!--            <div class="card-title">Earnings</div>-->
                <!--            <div class="dropdown">-->
                <!--                <a href="javascript:void(0);" class="p-2 fs-12 text-muted" data-bs-toggle="dropdown" aria-expanded="false">-->
                <!--                    View All<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>-->
                <!--                </a>-->
                <!--                <ul class="dropdown-menu" role="menu">-->
                <!--                    <li><a class="dropdown-item" href="javascript:void(0);">Download</a></li>-->
                <!--                    <li><a class="dropdown-item" href="javascript:void(0);">Import</a></li>-->
                <!--                    <li><a class="dropdown-item" href="javascript:void(0);">Export</a></li>-->
                <!--                </ul>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--        <div class="card-body">-->
                <!--            <div class="row ps-lg-5 mb-4 pb-4 gy-sm-0 gy-3">-->
                <!--                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">-->
                <!--                    <div class="mb-1 earning first-half ms-3">First Half</div>-->
                <!--                    <div class="mb-0">-->
                <!--                        <span class="mt-1 fs-16 fw-semibold">$51.94k</span>-->
                <!--                        <span class="text-success"><i class="fa fa-caret-up mx-1"></i>-->
                <!--                            <span class="badge bg-success-transparent text-success px-1 py-2 fs-10">+0.9%</span></span>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">-->
                <!--                    <div class="mb-1 earning top-gross ms-3">Top Gross</div>-->
                <!--                    <div class="mb-0">-->
                <!--                        <span class="mt-1 fs-16 fw-semibold">$18.32k</span>-->
                <!--                        <span class="text-success"><i class="fa fa-caret-up mx-1"></i>-->
                <!--                            <span class="badge bg-success-transparent text-success px-1 py-2 fs-10">+0.39%</span></span>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">-->
                <!--                    <div class="mb-1 earning second-half ms-3">Second Half</div>-->
                <!--                    <div class="mb-0">-->
                <!--                        <span class="mt-1 fs-16 fw-semibold">$38k</span>-->
                <!--                        <span class="text-danger"><i class="fa fa-caret-up mx-1"></i>-->
                <!--                            <span class="badge bg-danger-transparent text-danger px-1 py-2 fs-10">-0.15%</span></span>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--            <div id="earnings"></div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <?php if (count($products) > 0) : ?>
                    <div class="col-xl-12" style="display:none;">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">Loan Products</div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">S.No</th>
                                                <th scope="col">Product Name</th>
                                                <th scope="col">Interest (%)</th>
                                                <th scope="col">Repayment Frequency</th>
                                                <th scope="col">Duration</th>
                                                <th scope="col">Loan Amount</th>
                                                <th scope="col" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="top-selling">
                                            <?php
                                            $counter = 1;
                                            foreach ($products as $key => $product) : ?>
                                                <tr>
                                                    <td class="text-center lh-1">
                                                        <?= $counter++; ?>
                                                    </td>
                                                    <td>
                                                        <?= $product['product_name']; ?>
                                                    </td>
                                                    <td>
                                                        <span class="fw-semibold">
                                                            <?= $product['interest_rate']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-sm bg-success-transparent text-success">
                                                            <?= $product['repayment_freq']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fw-semibold">
                                                            <?= $product['repayment_period'] . ' ' . $product['repayment_duration']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fw-semibold">
                                                            <?php if (!empty($product['min_principal'])) : ?>
                                                                Starts from <?= number_format($product['min_principal']);
                                                                            if (!empty($product['max_principal'])) {
                                                                                echo " and " . number_format($product['max_principal']);
                                                                            } else {
                                                                                echo " and above";
                                                                            } ?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('client/applications'); ?>" class="btn btn-outline-primary btn-wave" <?= ($totalLoan) ? 'style="display: none;"' : ''; ?>>
                                                            Apply now
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!--<div class="col-xl-6">-->
                <!--    <div class="card custom-card">-->
                <!--        <div class="card-header justify-content-between">-->
                <!--            <div class="card-title">-->
                <!--                Top Countries By Sales-->
                <!--            </div>-->
                <!--            <div class="dropdown">-->
                <!--                <a aria-label="anchor" href="javascript:void(0);" class="btn btn-icon btn-sm btn-light" data-bs-toggle="dropdown">-->
                <!--                    <i class="fe fe-more-vertical"></i>-->
                <!--                </a>-->
                <!--                <ul class="dropdown-menu">-->
                <!--                    <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>-->
                <!--                    <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>-->
                <!--                    <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>-->
                <!--                </ul>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--        <div class="card-body">-->
                <!--            <div class="d-flex align-items-center mb-3">-->
                <!--                <div>-->
                <!--                    <h4 class="mb-0 lh-1">38,256</h4>-->
                <!--                </div>-->
                <!--                <div><span class="badge bg-primary-transparent mx-1">12.24%</span><span class="text-muted fs-11">Since last week</span></div>-->
                <!--            </div>-->
                <!--            <ul class="mb-0 pt-3 list-unstyled">-->
                <!--                <li class="mb-3">-->
                <!--                    <div class="d-flex justify-content-between align-items-center">-->
                <!--                        <div class="d-flex align-items-center lh-1">-->
                <!--                            <span class="avatar avatar-xs me-2 avatar-rounded">-->
                <!--                                <img src="/dist/assets/images/flags/french_flag.jpg" alt="">-->
                <!--                            </span>-->
                <!--                            <span class="top-country-name">France</span>-->
                <!--                        </div>-->
                <!--                        <div>-->
                <!--                            <i class="ti ti-trending-up fs-16 text-success"></i>-->
                <!--                        </div>-->
                <!--                        <div class="fs-14">5,932</div>-->
                <!--                    </div>-->
                <!--                </li>-->
                <!--                <li class="mb-3">-->
                <!--                    <div class="d-flex justify-content-between align-items-center">-->
                <!--                        <div class="d-flex align-items-center lh-1">-->
                <!--                            <span class="avatar avatar-xs me-2 avatar-rounded">-->
                <!--                                <img src="/dist/assets/images/flags/spain_flag.jpg" alt="">-->
                <!--                            </span>-->
                <!--                            <span class="top-country-name">spain</span>-->
                <!--                        </div>-->
                <!--                        <div>-->
                <!--                            <i class="ti ti-trending-down fs-16 text-danger"></i>-->
                <!--                        </div>-->
                <!--                        <div class="fs-14">5,383</div>-->
                <!--                    </div>-->
                <!--                </li>-->
                <!--                <li class="mb-3">-->
                <!--                    <div class="d-flex justify-content-between align-items-center">-->
                <!--                        <div class="d-flex align-items-center lh-1">-->
                <!--                            <span class="avatar avatar-xs me-2 avatar-rounded">-->
                <!--                                <img src="/dist/assets/images/flags/argentina_flag.jpg" alt="">-->
                <!--                            </span>-->
                <!--                            <span class="top-country-name">Argentina</span>-->
                <!--                        </div>-->
                <!--                        <div>-->
                <!--                            <i class="ti ti-trending-up fs-16 text-success"></i>-->
                <!--                        </div>-->
                <!--                        <div class="fs-14">4,825</div>-->
                <!--                    </div>-->
                <!--                </li>-->
                <!--                <li class="mb-3">-->
                <!--                    <div class="d-flex justify-content-between align-items-center">-->
                <!--                        <div class="d-flex align-items-center lh-1">-->
                <!--                            <span class="avatar avatar-xs me-2 avatar-rounded">-->
                <!--                                <img src="/dist/assets/images/flags/uae_flag.jpg" alt="">-->
                <!--                            </span>-->
                <!--                            <span class="top-country-name">Uae</span>-->
                <!--                        </div>-->
                <!--                        <div>-->
                <!--                            <i class="ti ti-trending-up fs-16 text-success"></i>-->
                <!--                        </div>-->
                <!--                        <div class="fs-14">4,527</div>-->
                <!--                    </div>-->
                <!--                </li>-->
                <!--                <li class="mb-0">-->
                <!--                    <div class="d-flex justify-content-between align-items-center">-->
                <!--                        <div class="d-flex align-items-center lh-1">-->
                <!--                            <span class="avatar avatar-xs me-2 avatar-rounded">-->
                <!--                                <img src="/dist/assets/images/flags/germany_flag.jpg" alt="">-->
                <!--                            </span>-->
                <!--                            <span class="top-country-name">Germany</span>-->
                <!--                        </div>-->
                <!--                        <div>-->
                <!--                            <i class="ti ti-trending-down fs-16 text-danger"></i>-->
                <!--                        </div>-->
                <!--                        <div class="fs-14">4,501</div>-->
                <!--                    </div>-->
                <!--                </li>-->
                <!--            </ul>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <!--<div class="col-xl-6">-->
                <!--    <div class="card custom-card">-->
                <!--        <div class="card-header justify-content-between">-->
                <!--            <div class="card-title">-->
                <!--                Top Customers-->
                <!--            </div>-->
                <!--            <div class="dropdown">-->
                <!--                <a href="javascript:void(0);" class="p-2 fs-12 text-muted" data-bs-toggle="dropdown">-->
                <!--                    View All<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>-->
                <!--                </a>-->
                <!--                <ul class="dropdown-menu" role="menu">-->
                <!--                    <li><a class="dropdown-item" href="javascript:void(0);">Download</a></li>-->
                <!--                    <li><a class="dropdown-item" href="javascript:void(0);">Import</a></li>-->
                <!--                    <li><a class="dropdown-item" href="javascript:void(0);">Export</a></li>-->
                <!--                </ul>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--        <div class="card-body">-->
                <!--            <ul class="list-unstyled mb-0">-->
                <!--                <li class="mb-3">-->
                <!--                    <a href="javascript:void(0);">-->
                <!--                        <div class="d-flex align-items-center justify-content-between">-->
                <!--                            <div class="d-flex align-items-top justify-content-center">-->
                <!--                                <div class="me-2">-->
                <!--                                    <span class="avatar avatar-md avatar-rounded">-->
                <!--                                        <img src="/dist/assets/images/faces/4.jpg" alt="">-->
                <!--                                    </span>-->
                <!--                                </div>-->
                <!--                                <div>-->
                <!--                                    <p class="mb-0 fw-semibold">Emma Wilson</p>-->
                <!--                                    <p class="mb-0 text-muted fs-12">15 Purchases<i class="bi bi-patch-check-fill fs-14 text-primary ms-2"></i></p>-->
                <!--                                </div>-->
                <!--                            </div>-->
                <!--                            <div>-->
                <!--                                <span class="fs-14">$1,835</span>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </a>-->
                <!--                </li>-->
                <!--                <li class="mb-3">-->
                <!--                    <a href="javascript:void(0);">-->
                <!--                        <div class="d-flex align-items-center justify-content-between">-->
                <!--                            <div class="d-flex align-items-top justify-content-center">-->
                <!--                                <div class="me-2">-->
                <!--                                    <span class="avatar avatar-md avatar-rounded">-->
                <!--                                        <img src="/dist/assets/images/faces/15.jpg" alt="">-->
                <!--                                    </span>-->
                <!--                                </div>-->
                <!--                                <div>-->
                <!--                                    <p class="mb-0 fw-semibold">Robert Lewis</p>-->
                <!--                                    <p class="mb-0 text-muted fs-12">18 Purchases<i class="bi bi-patch-check-fill fs-14 text-primary ms-2"></i></p>-->
                <!--                                </div>-->
                <!--                            </div>-->
                <!--                            <div>-->
                <!--                                <span class="fs-14">$2,415</span>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </a>-->
                <!--                </li>-->
                <!--                <li class="mb-3">-->
                <!--                    <a href="javascript:void(0);">-->
                <!--                        <div class="d-flex align-items-center justify-content-between">-->
                <!--                            <div class="d-flex align-items-top justify-content-center">-->
                <!--                                <div class="me-2">-->
                <!--                                    <span class="avatar avatar-md avatar-rounded">-->
                <!--                                        <img src="/dist/assets/images/faces/5.jpg" alt="">-->
                <!--                                    </span>-->
                <!--                                </div>-->
                <!--                                <div>-->
                <!--                                    <p class="mb-0 fw-semibold">Angelina Hose</p>-->
                <!--                                    <p class="mb-0 text-muted fs-12">21 Purchases<i class="bi bi-patch-check-fill fs-14 text-primary ms-2"></i></p>-->
                <!--                                </div>-->
                <!--                            </div>-->
                <!--                            <div>-->
                <!--                                <span class="fs-14">$2,341</span>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </a>-->
                <!--                </li>-->
                <!--                <li class="mb-0">-->
                <!--                    <a href="javascript:void(0);">-->
                <!--                        <div class="d-flex align-items-center justify-content-between">-->
                <!--                            <div class="d-flex align-items-top justify-content-center">-->
                <!--                                <div class="me-2">-->
                <!--                                    <span class="avatar avatar-md avatar-rounded">-->
                <!--                                        <img src="/dist/assets/images/faces/2.jpg" alt="">-->
                <!--                                    </span>-->
                <!--                                </div>-->
                <!--                                <div>-->
                <!--                                    <p class="mb-0 fw-semibold">Samantha Sam</p>-->
                <!--                                    <p class="mb-0 text-muted fs-12">24 Purchases<i class="bi bi-patch-check-fill fs-14 text-primary ms-2"></i></p>-->
                <!--                                </div>-->
                <!--                            </div>-->
                <!--                            <div>-->
                <!--                                <span class="fs-14">2,624</span>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </a>-->
                <!--                </li>-->
                <!--            </ul>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
    <!--End::row-1 -->

    <!-- Start:: row-2 -->
    <div class="row">
        <?php if (count($repayments) > 0) : ?>
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Loan Portfolio
                        </div>
                        <!-- <div class="d-flex">
                            <div class="me-3">
                                <input class="form-control form-control-sm" type="text" placeholder="Search" aria-label=".form-control-sm example">
                            </div>
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-wave waves-effect waves-light" data-bs-toggle="dropdown" aria-expanded="false">
                                    Sort By<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a class="dropdown-item" href="javascript:void(0);">New</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Popular</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Relevant</a></li>
                                </ul>
                            </div>
                        </div> -->
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No</th>
                                        <th scope="col">Particular</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Account No</th>
                                        <!-- <th scope="col">Method</th> -->
                                        <th scope="col">Status</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Balance</th>
                                        <th scope="col">Txt Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sno = 1;
                                    $debitBal = $creditBal = $acc_balance = 0;
                                    foreach ($repayments as $key => $entry) :
                                        if (strtolower($entry['status']) == 'debit') {
                                            $debitBal += $entry['amount'];
                                        } else {
                                            $creditBal += $entry['amount'];
                                        }
                                        $acc_balance = abs($debitBal - $creditBal);
                                    ?>
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">
                                                    <?= $sno++; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="fs-14">
                                                        <?= ($entry['account_type_name']); ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?= strtoupper($entry['type'] . ' of ' . $entry['product_name']); ?>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">
                                                    <?= ($entry['account_no']); ?>
                                                </span>
                                            </td>
                                            <!-- <td>
                                                <? $entry['payment_method']; ?>
                                            </td> -->
                                            <td>
                                                <span class="badge <? (strtolower($entry['status']) == 'credit') ? 'bg-success-transparent' : 'bg-danger-transparent'; ?> <?= (strtolower($entry['status']) == 'credit') ? 'bg-danger-transparent' : 'bg-success-transparent'; ?>">
                                                    <? strtoupper($entry['status']); ?>
                                                    <?= (strtolower($entry['status']) == 'credit') ? 'DEBIT' : 'CREDIT'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="">
                                                    <?= number_format($entry['amount'], 2); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="">
                                                    <?= number_format($acc_balance, 2); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="">
                                                    <?= date('d-m-Y', strtotime($entry['date'])); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex align-items-center">

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- End:: row-2 -->
<?php else : ?>
    <!-- Start::row-2 -->
    <?php include_once(APPPATH . 'Views/client/dashboard/kyc.php'); ?>
<?php endif; ?>
<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>
<script>
    var table = $(".table").DataTable({
        pageLength: 25
    });
    var staff_id = '<?= $user["staff_id"]; ?>';
    var gender = '<?= $user["gender"]; ?>';
</script>
<!--home-js -->
<!-- <script src="/assets/scripts/home.js"></script> -->
<script src="/assets/client/main/main.js"></script>
<script src="/assets/client/main/options.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>
<?= $this->endSection(); ?>