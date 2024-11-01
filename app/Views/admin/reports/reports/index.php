<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>

<?php
# compute report total summation of report widgets
$total = $savings + $repayments + $applications + $branches + $clients + $disbursements + $arrears + $products + $staff + $transactions + $logs + $activities;
?>

<!-- Start::row-1 -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <?= ucfirst($title) ?> Widgets
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-primary text-primary">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3 6.5C3 3.87479 3.02811 3 6.5 3C9.97189 3 10 3.87479 10 6.5C10 9.12521 10.0111 10 6.5 10C2.98893 10 3 9.12521 3 6.5Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14 6.5C14 3.87479 14.0281 3 17.5 3C20.9719 3 21 3.87479 21 6.5C21 9.12521 21.0111 10 17.5 10C13.9889 10 14 9.12521 14 6.5Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3 17.5C3 14.8748 3.02811 14 6.5 14C9.97189 14 10 14.8748 10 17.5C10 20.1252 10.0111 21 6.5 21C2.98893 21 3 20.1252 3 17.5Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14 17.5C14 14.8748 14.0281 14 17.5 14C20.9719 14 21 14.8748 21 17.5C21 20.1252 21.0111 21 17.5 21C13.9889 21 14 20.1252 14 17.5Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/module/report/branches">
                            <p class="mb-1">Branches</p>
                            <h4 class="mb-0">
                                <?= number_format($branches, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-primary">
                                <?php ($total == 0) ? "0.42%" : round((($branches / $total) * 100), 2) . "%"; ?>
                            </span> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-warning text-warning">
                        <svg id="icon-orders" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/module/report/loanproducts">
                            <p class="mb-1">Loan Products</p>
                            <h4 class="mb-0">
                                <?= number_format($products, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-warning">+3.5%</span> -->
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body  p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-danger text-danger">
                        <svg id="icon-revenue" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/module/report/savings">
                            <p class="mb-1">Savings</p>
                            <h4 class="mb-0">
                                <?= number_format($savings, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-danger">-3.5%</span> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-success text-success">
                        <svg id="icon-database-widget" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/module/report/loanapplications">
                            <p class="mb-1">Applications</p>
                            <h4 class="mb-0">
                                <?= number_format($applications, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-success">-3.5%</span> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-primary text-primary">
                        <!-- <i class="ti-user"></i> -->
                        <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/module/report/staff">
                            <p class="mb-1">Staff</p>
                            <h4 class="mb-0">
                                <?= number_format($staff, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-primary">+3.5%</span> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-warning text-warning">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.8877 10.8967C19.2827 10.7007 20.3567 9.50467 20.3597 8.05567C20.3597 6.62767 19.3187 5.44367 17.9537 5.21967" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M19.7285 14.2503C21.0795 14.4523 22.0225 14.9253 22.0225 15.9003C22.0225 16.5713 21.5785 17.0073 20.8605 17.2813" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.8867 14.6638C8.67273 14.6638 5.92773 15.1508 5.92773 17.0958C5.92773 19.0398 8.65573 19.5408 11.8867 19.5408C15.1007 19.5408 17.8447 19.0588 17.8447 17.1128C17.8447 15.1668 15.1177 14.6638 11.8867 14.6638Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.8869 11.8879C13.9959 11.8879 15.7059 10.1789 15.7059 8.06888C15.7059 5.95988 13.9959 4.24988 11.8869 4.24988C9.7779 4.24988 8.0679 5.95988 8.0679 8.06888C8.0599 10.1709 9.7569 11.8809 11.8589 11.8879H11.8869Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5.88509 10.8967C4.48909 10.7007 3.41609 9.50467 3.41309 8.05567C3.41309 6.62767 4.45409 5.44367 5.81909 5.21967" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M4.044 14.2503C2.693 14.4523 1.75 14.9253 1.75 15.9003C1.75 16.5713 2.194 17.0073 2.912 17.2813" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/module/report/clients">
                            <p class="mb-1">Clients</p>
                            <h4 class="mb-0">
                                <?= number_format($clients, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-warning">+3.5%</span> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body  p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-danger text-danger">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.37121 10.2017V17.0619" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.0382 6.91913V17.0618" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M16.6285 13.8268V17.0619" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.6857 2H7.31429C4.04762 2 2 4.31208 2 7.58516V16.4148C2 19.6879 4.0381 22 7.31429 22H16.6857C19.9619 22 22 19.6879 22 16.4148V7.58516C22 4.31208 19.9619 2 16.6857 2Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/module/report/disbursements">
                            <p class="mb-1">Disbursements</p>
                            <h4 class="mb-0">
                                <?= number_format($disbursements, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-danger">-3.5%</span> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-success text-success">
                        <svg id="icon-database-widget" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/module/report/repayments">
                            <p class="mb-1">Repayments</p>
                            <h4 class="mb-0">
                                <?= number_format($repayments, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-success">-3.5%</span> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-primary text-primary">
                        <!-- <i class="ti-user"></i> -->
                        <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/module/report/arrears">
                            <p class="mb-1">Loan Arrears</p>
                            <h4 class="mb-0">
                                <?= number_format($arrears, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-primary">+3.5%</span> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-warning text-warning">
                        <svg id="icon-orders" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/view/transactions">
                            <p class="mb-1">Transactions</p>
                            <h4 class="mb-0">
                                <?= number_format($transactions, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-warning">+3.5%</span> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body  p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-danger text-danger">
                        <svg id="icon-revenue" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/view/activities">
                            <p class="mb-1">Activities</p>
                            <h4 class="mb-0">
                                <?= number_format($activities, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-danger">-3.5%</span> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3  col-lg-6 col-sm-6">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-success text-success">
                        <svg id="icon-database-widget" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                        </svg>
                    </span>
                    <div class="media-body">
                        <a href="/admin/reports/view/logs">
                            <p class="mb-1">User Logs</p>
                            <h4 class="mb-0">
                                <?= number_format($logs, 0); ?>
                            </h4>
                            <!-- <span class="badge badge-success">-3.5%</span> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">

</script>
<script src="/assets/scripts/reports/index.js"></script>

<?= $this->endSection() ?>