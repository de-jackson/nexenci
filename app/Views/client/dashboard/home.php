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
<div class="col-xl-9 wid-100">
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <div class="card chart-grd same-card">
                <div class="card-body depostit-card p-0">
                    <div class="depostit-card-media d-flex justify-content-between pb-0">
                        <div>
                            <h6>My Account Balance</h6>
                            <h3>
                                <?= $settings['currency'] . ' ' . number_format(($user['account_balance']), 2); ?>
                            </h3>
                        </div>
                        <div class="icon-box bg-success-light">
                            <svg width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4642 13.7074C11.4759 12.1252 10.8504 10.8738 9.60279 9.99009C8.6392 9.30968 7.46984 8.95476 6.33882 8.6137C3.98274 7.89943 3.29927 7.52321 3.29927 6.3965C3.29927 5.14147 4.93028 4.69493 6.32655 4.69493C7.34341 4.69493 8.51331 5.01109 9.23985 5.47964L10.6802 3.24887C9.73069 2.6333 8.43112 2.21342 7.14783 2.0831V0H4.49076V2.22918C2.12884 2.74876 0.640949 4.29246 0.640949 6.3965C0.640949 7.87005 1.25327 9.03865 2.45745 9.86289C3.37331 10.4921 4.49028 10.83 5.56927 11.1572C7.88027 11.8557 8.81873 12.2813 8.80805 13.691L8.80799 13.7014C8.80799 14.8845 7.24005 15.3051 5.89676 15.3051C4.62786 15.3051 3.248 14.749 2.46582 13.9222L0.535522 15.7481C1.52607 16.7957 2.96523 17.5364 4.4907 17.8267V20.0001H7.14783V17.8735C9.7724 17.4978 11.4616 15.9177 11.4642 13.7074Z" fill="#3AC977" />
                            </svg>
                        </div>
                    </div>
                    <div id="accountBalance"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card chart-grd same-card">
                <div class="card-body depostit-card p-0">
                    <div class="depostit-card-media d-flex justify-content-between pb-0">
                        <div>
                            <h6>I Owe</h6>
                            <h3>
                                <?= $settings['currency'] . ' ' . number_format($totalLoan, 2); ?>
                            </h3>
                        </div>
                        <div class="icon-box bg-danger-light">
                            <svg width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4642 13.7074C11.4759 12.1252 10.8504 10.8738 9.60279 9.99009C8.6392 9.30968 7.46984 8.95476 6.33882 8.6137C3.98274 7.89943 3.29927 7.52321 3.29927 6.3965C3.29927 5.14147 4.93028 4.69493 6.32655 4.69493C7.34341 4.69493 8.51331 5.01109 9.23985 5.47964L10.6802 3.24887C9.73069 2.6333 8.43112 2.21342 7.14783 2.0831V0H4.49076V2.22918C2.12884 2.74876 0.640949 4.29246 0.640949 6.3965C0.640949 7.87005 1.25327 9.03865 2.45745 9.86289C3.37331 10.4921 4.49028 10.83 5.56927 11.1572C7.88027 11.8557 8.81873 12.2813 8.80805 13.691L8.80799 13.7014C8.80799 14.8845 7.24005 15.3051 5.89676 15.3051C4.62786 15.3051 3.248 14.749 2.46582 13.9222L0.535522 15.7481C1.52607 16.7957 2.96523 17.5364 4.4907 17.8267V20.0001H7.14783V17.8735C9.7724 17.4978 11.4616 15.9177 11.4642 13.7074Z" fill="#FF5E5E" />
                            </svg>
                        </div>
                    </div>
                    <div id="oweBalance"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card chart-grd same-card">
                <div class="card-body depostit-card p-0">
                    <div class="depostit-card-media d-flex justify-content-between pb-0">
                        <div>
                            <h6>Total Loan Paid</h6>
                            <h3>
                                <?= $settings['currency'] . ' ' . number_format($totalLoanPaid, 2); ?>
                            </h3>
                        </div>
                        <div class="icon-box bg-primary-light">
                            <svg width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4642 13.7074C11.4759 12.1252 10.8504 10.8738 9.60279 9.99009C8.6392 9.30968 7.46984 8.95476 6.33882 8.6137C3.98274 7.89943 3.29927 7.52321 3.29927 6.3965C3.29927 5.14147 4.93028 4.69493 6.32655 4.69493C7.34341 4.69493 8.51331 5.01109 9.23985 5.47964L10.6802 3.24887C9.73069 2.6333 8.43112 2.21342 7.14783 2.0831V0H4.49076V2.22918C2.12884 2.74876 0.640949 4.29246 0.640949 6.3965C0.640949 7.87005 1.25327 9.03865 2.45745 9.86289C3.37331 10.4921 4.49028 10.83 5.56927 11.1572C7.88027 11.8557 8.81873 12.2813 8.80805 13.691L8.80799 13.7014C8.80799 14.8845 7.24005 15.3051 5.89676 15.3051C4.62786 15.3051 3.248 14.749 2.46582 13.9222L0.535522 15.7481C1.52607 16.7957 2.96523 17.5364 4.4907 17.8267V20.0001H7.14783V17.8735C9.7724 17.4978 11.4616 15.9177 11.4642 13.7074Z" fill="var(--primary)" />
                            </svg>
                        </div>
                    </div>
                    <div id="loanPaid"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card chart-grd same-card">
                <div class="card-body depostit-card p-0">
                    <div class="depostit-card-media d-flex justify-content-between pb-0">
                        <div>
                            <h6>Total Loan Balance</h6>
                            <h3>
                                <?= $settings['currency'] . ' ' . number_format($totalLoanBalance, 2); ?>
                            </h3>
                        </div>
                        <div class="icon-box bg-warning-light">
                            <svg width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4642 13.7074C11.4759 12.1252 10.8504 10.8738 9.60279 9.99009C8.6392 9.30968 7.46984 8.95476 6.33882 8.6137C3.98274 7.89943 3.29927 7.52321 3.29927 6.3965C3.29927 5.14147 4.93028 4.69493 6.32655 4.69493C7.34341 4.69493 8.51331 5.01109 9.23985 5.47964L10.6802 3.24887C9.73069 2.6333 8.43112 2.21342 7.14783 2.0831V0H4.49076V2.22918C2.12884 2.74876 0.640949 4.29246 0.640949 6.3965C0.640949 7.87005 1.25327 9.03865 2.45745 9.86289C3.37331 10.4921 4.49028 10.83 5.56927 11.1572C7.88027 11.8557 8.81873 12.2813 8.80805 13.691L8.80799 13.7014C8.80799 14.8845 7.24005 15.3051 5.89676 15.3051C4.62786 15.3051 3.248 14.749 2.46582 13.9222L0.535522 15.7481C1.52607 16.7957 2.96523 17.5364 4.4907 17.8267V20.0001H7.14783V17.8735C9.7724 17.4978 11.4616 15.9177 11.4642 13.7074Z" fill="#FF9F00" />
                            </svg>
                        </div>
                    </div>
                    <div id="loanBalance"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-md-8">
            <div class="card">
                <div class="card-header border-0 pb-1">
                    <h4 class="heading mb-0">Calendar</h4>
                </div>
                <div class="card-body schedules-cal p-2">
                    <input type="text" class="form-control d-none" id="datetimepicker1">
                </div>
                <div class="card-footer">
                    <a href="javascript:void(0);" class="text-primary fs-18 fw-semibold">
                        What would you like to do today?
                    </a>
                    <p class="mb-3 text-justify">
                        Financial growth is a journey of balance. Save diligently to build your foundation, and when opportunities arise, consider wisely whether to invest in your dreams. Remember, your financial choices today shape your prosperous tomorrow.
                    </p>
                    <a href="<?= base_url('client/transactions/type/deposit'); ?>" class="btn btn-success btn-wave me-2">
                        Make Savings
                    </a>
                    <?php if (!$summary['counter']['applications']) : ?>
                        <a href="<?= base_url('client/applications'); ?>" class="btn btn-outline-primary btn-wave" <?= ($totalLoan) ? 'style="display: none;"' : ''; ?>>
                            Apply for a new loan
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="heading mb-0">Your Recent Logs</h4>
                    <div>
                        <a href="javascript:void(0);" class="text-primary me-2">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php if (count($logs) > 0) :
                            foreach ($logs as $key => $log) : ?>
                                <li class="mb-3">
                                    <a href="javascript:void(0);">
                                        <div class="d-flex algn-items-center">
                                            <div class="flex-fill ms-2">
                                                <p class="fw-semibold mb-0">
                                                    <i class="ri-computer-line"></i> <?= $log['operating_system'] ?> using <?= $log['browser'] ?>
                                                </p>
                                                <p class="fs-14 text-muted mb-0">
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
                                                $xcounter = 1;

                                                $disbursement_date = strtotime("2023-03-01");

                                                $number_of_installment = (int)$disbursement['installments_num'];

                                                $principal = $original_principal = (int)$disbursement['principal'];

                                                # $instalment = 169106;

                                                $interest_rate = $disbursement['interest_rate'] / 100;
                                                $interestPeriod = $disbursement['interest_period'];
                                                $method = strtolower($disbursement['interest_type']);
                                                $rate = $disbursement['interest_rate'];
                                                $period = $disbursement['repayment_period'];

                                                # check the loan interest period
                                                if (strtolower($interestPeriod) == "year") {
                                                    # code...
                                                    $interval = getLoanInterval($disbursement['repayment_frequency']);

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
<?php if (count($products) > 0) : ?>
    <div class="col-xl-6 active-p">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive active-projects shorting">
                    <div class="tbl-caption">
                        <h4 class="heading mb-0">Loan Products</h4>
                    </div>
                    <table id="projects-tbl1" class="table">
                        <thead>
                            <tr>
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">S.No</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Interest (%)</th>
                                        <th scope="col">Repayment Frequency</th>
                                        <th scope="col">Duration</th>
                                        <th scope="col">Min - Max</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </tr>
                        </thead>
                        <tbody>
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
                                    <td class="pe-0">
                                        <div class="tbl-progress-box">
                                            <div class="progress">
                                                <div class="progress-bar bg-primary" style="width:53%; height:5px; border-radius:4px;" role="progressbar"></div>
                                            </div>
                                            <span class="text-primary">
                                                <?= $product['interest_rate']; ?>
                                            </span>
                                        </div>
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

<?php if (count($disbursements) > 0) : ?>
    <div class="col-xl-5 bst-seller">
        <div class="card">
            <div class="card-header">
                <h4 class="heading mb-0">Disbursements</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive active-projects active-projects ItemsCheckboxSec selling-product shorting ">
                    <table id="product-tbl1" class="table ">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Disbursement Code</th>
                                <th>Principal</th>
                                <th>Interest</th>
                                <th>Total Loan</th>
                                <th>status</th>
                                <th>Loan Expiry</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $disbursement_counter = 1;
                            foreach ($disbursements as $key => $disbursement) : ?>
                                <tr>
                                    <td>
                                        <?= $disbursement_counter++; ?>
                                    </td>
                                    <td>
                                        <div class="products">
                                            <div>
                                                <h6><a href="javascript:void(0)">
                                                        <?= $disbursement['product_name']; ?>
                                                    </a></h6>
                                                <span><?= $disbursement['disbursement_code']; ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-primary">
                                            <?= number_format($disbursement['principal'], 2); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            <?= $disbursement['interest_rate'] . '%'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span><?= number_format($disbursement['total_balance']); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary light border-0">
                                            <?= $disbursement['class']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span><?= $disbursement['loan_expiry_date']; ?></span>
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

<div class="col-xl-3 col-md-6 up-shd">
    <div class="card">
        <div class="card-header">
            <h4 class="heading mb-0">Loan Applications Status</h4>
            <!-- <select class="default-select status-select normal-select">
                <option value="Today">Today</option>
                <option value="Week">Week</option>
                <option value="Month">Month</option>
            </select> -->
        </div>
        <div class="card-body">
            <div id="applicationStatusChart" class="project-chart"></div>
            <div class="project-date">
                <div class="project-media">
                    <p class="mb-0">
                        <svg class="me-2" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="12" height="12" rx="3" fill="var(--secondary)" />
                        </svg>
                        Pending Applications
                    </p>
                    <span><?= $summary['applications']['pending']; ?></span>
                </div>
                <div class="project-media">
                    <p class="mb-0">
                        <svg class="me-2" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="12" height="12" rx="3" fill="var(--primary)" />
                        </svg>
                        Processed Applications
                    </p>
                    <span><?= $summary['applications']['processing']; ?></span>
                </div>
                <div class="project-media">
                    <p class="mb-0">
                        <svg class="me-2" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="12" height="12" rx="3" fill="#FF9F00" />
                        </svg>
                        Declined Applications
                    </p>
                    <span><?= $summary['applications']['declined']; ?></span>
                </div>
                <div class="project-media">
                    <p class="mb-0">
                        <svg class="me-2" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="12" height="12" rx="3" fill="#3AC977" />
                        </svg>
                        Approved Applications
                    </p>
                    <span><?= $summary['applications']['disbursed']; ?></span>
                </div>
                <div class="project-media">
                    <p class="mb-0">
                        <svg class="me-2" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="12" height="12" rx="3" fill="#FF5E5E" />
                        </svg>
                        Cancelled Applications
                    </p>
                    <span><?= $summary['applications']['cancelled']; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 up-shd">
    <div class="card">
        <div class="card-header">
            <h4 class="heading mb-0">Disbursements Status</h4>
            <!-- <select class="default-select status-select normal-select">
                <option value="Today">Today</option>
                <option value="Week">Week</option>
                <option value="Month">Month</option>
            </select> -->
        </div>
        <div class="card-body">
            <div id="disbursementStatusChart" class="project-chart"></div>
            <div class="project-date">
                <div class="project-media">
                    <p class="mb-0">
                        <svg class="me-2" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="12" height="12" rx="3" fill="var(--primary)" />
                        </svg>
                        Running Loans
                    </p>
                    <span><?= $summary['disbursements']['running']; ?></span>
                </div>
                <div class="project-media">
                    <p class="mb-0">
                        <svg class="me-2" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="12" height="12" rx="3" fill="var(--secondary)" />
                        </svg>
                        Loans in Arrears
                    </p>
                    <span><?= $summary['disbursements']['arrears']; ?></span>
                </div>
                <div class="project-media">
                    <p class="mb-0">
                        <svg class="me-2" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="12" height="12" rx="3" fill="#FF9F00" />
                        </svg>
                        Expired Loans
                    </p>
                    <span><?= $summary['disbursements']['expired']; ?></span>
                </div>
                <div class="project-media">
                    <p class="mb-0">
                        <svg class="me-2" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="12" height="12" rx="3" fill="#3AC977" />
                        </svg>
                        Cleared Loans
                    </p>
                    <span><?= $summary['disbursements']['cleared']; ?></span>
                </div>
                <div class="project-media">
                    <p class="mb-0">
                        <svg class="me-2" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="12" height="12" rx="3" fill="#FF5E5E" />
                        </svg>
                        Defaulted Loans
                    </p>
                    <span><?= $summary['disbursements']['defaulted']; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (count($repayments) > 0) : ?>
    <div class="col-xl-9 bst-seller" style="display: none;">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive active-projects style-1 ItemsCheckboxSec shorting ">
                    <div class="tbl-caption">
                        <h4 class="heading mb-0">Loan Portfolio</h4>
                        <!-- <div>
                        <a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">+ Add Employee</a>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal1">+ Invite Employee
                        </button>
                    </div> -->
                    </div>
                    <table id="empoloyees-tbl1" class="table">
                        <thead>
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Particular</th>
                                <th scope="col">Client</th>
                                <th scope="col">Type</th>
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
                                        <span><?= $sno++; ?></span>
                                    </td>
                                    <td><span><?= ($entry['account_type_name']); ?></span></td>
                                    <td>
                                        <div class="products">
                                            <div class="fw-bold">
                                                <h6><a href="javascript:void(0)">
                                                        <?= strtoupper($entry['name']) ?>
                                                    </a></h6>
                                                <span><?= ($entry['account_no']); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="text-primary">
                                            <span>
                                                <?= strtoupper($entry['type'] . ' of ' . $entry['product_name']); ?>
                                            </span>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge <? (strtolower($entry['status']) == 'credit') ? 'badge-success' : 'badge-danger'; ?> <?= (strtolower($entry['status']) == 'credit') ? 'badge-danger' : 'badge-success'; ?> light border-0">
                                            <? strtoupper($entry['status']); ?>
                                            <?= (strtolower($entry['status']) == 'credit') ? 'DEBIT' : 'CREDIT'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span><?= number_format($entry['amount'], 2); ?></span>
                                    </td>
                                    <td>
                                        <span><?= number_format($entry['total'], 2); ?></span>
                                    </td>
                                    <td>
                                        <span>
                                            <?= date('d-m-Y', strtotime($entry['date'])); ?>
                                        </span>
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