<?= $this->extend("layout/main"); ?>
<?php
# compute report total summation of report widgets
$total = $savings + $repayments + $applications + $branches + $clients + $disbursements + $arrears + $products + $staff + $transactions + $logs + $activities;
?>
<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ucfirst($title) ?> Widgets
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- branches -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">Branch</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($branches, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i><?= ($total == 0) ? "0.42%" : round((($branches / $total) * 100), 2) . "%"; ?></span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/module/report/branches">
                                        <span class="avatar avatar-md avatar-rounded bg-secondary-transparent text-secondary fs-18">
                                            <i class="fas fa-code-branch fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- loan products -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">Loan Products</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($products, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i>0.42%</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/module/report/loanproducts">
                                        <span class="avatar avatar-md avatar-rounded bg-secondary-transparent text-secondary fs-18">
                                            <i class="fas fa-cart-flatbed-suitcase fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- staff -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">Staff</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($staff, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i>0.42%</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/module/report/staff">
                                        <span class="avatar avatar-md avatar-rounded bg-secondary-transparent text-secondary fs-18">
                                            <i class="fas fa-user-tie fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- clients -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">Client</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($clients, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i>0.42%</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/module/report/clients">
                                        <span class="avatar avatar-md avatar-rounded bg-secondary-transparent text-secondary fs-18">
                                            <i class="fas fa-users fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- savings -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">Members Statements</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($savings, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i>0.42%</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/module/report/savings">
                                        <span class="avatar avatar-md avatar-rounded bg-primary-transparent text-primary fs-18">
                                            <i class="fas fa-bank fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- loan applications -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">Loan Applications</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($applications, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i>0.42%</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/module/report/loanapplications">
                                        <span class="avatar avatar-md avatar-rounded bg-primary-transparent text-primary fs-18">
                                            <i class="fas fa-clipboard fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- disbursements -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">Disbursements</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($disbursements, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i>0.42%</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/module/report/disbursements">
                                        <span class="avatar avatar-md avatar-rounded bg-primary-transparent text-primary fs-18">
                                            <i class="fas fa-money-bill-trend-up fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- loan repayments -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">Loan Repayments</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($repayments, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i>0.42%</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/module/report/repayments">
                                        <span class="avatar avatar-md avatar-rounded bg-primary-transparent text-primary fs-18">
                                            <i class="fas fa-balance-scale fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- loan Arrears -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">Loan Arrears</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($arrears, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i>0.42%</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/module/report/arrears">
                                        <span class="avatar avatar-md avatar-rounded bg-primary-transparent text-primary fs-18">
                                            <i class="fas fa-balance-scale fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- All Transactions -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">Transactions</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($transactions, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i>0.42%</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/view/transactions">
                                        <span class="avatar avatar-md avatar-rounded bg-info-transparent text-info fs-18">
                                            <i class="bi bi-cash-stack fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- user acitivies -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">User Activities</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($activities, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i>0.42%</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/view/activities">
                                        <span class="avatar avatar-md avatar-rounded bg-info-transparent text-info fs-18">
                                            <i class="fas fa-person-digging fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- user logins -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top justify-content-between">
                                <div class="flex-fill">
                                    <p class="mb-0 text-muted">User Logins</p>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-5 fw-semibold"><?= number_format($logs, 0); ?></span>
                                        <span class="fs-12 text-success ms-2"><i class="ti ti-trending-up me-1 d-inline-block"></i>0.42%</span>
                                    </div>
                                </div>
                                <div>
                                    <a href="/admin/reports/view/logs">
                                        <span class="avatar avatar-md avatar-rounded bg-info-transparent text-info fs-18">
                                            <i class="fas fa-sign-in fs-16"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
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