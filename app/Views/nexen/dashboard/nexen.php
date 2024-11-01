<?= $this->extend("layout/main"); ?>

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

<div class="col-xl-9 wid-100">
    <div class="row">
        <?php foreach ($summary['accounts'] as $key => $account) : ?>
            <div class="col-xl-4 col-sm-6">
                <div class="card chart-grd same-card">
                    <div class="card-body depostit-card p-0">
                        <div class="depostit-card-media d-flex justify-content-between pb-0">
                            <div>
                                <h6><?= $account['name'] . 's on-boarded'; ?></h6>
                                <h3><?= number_format($account['nexen_clients_counter']); ?></h3>
                            </div>
                            <div class="icon-box bg-primary-light">
                                <svg width="18" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.79222 13.9396C12.1738 13.9396 15.0641 14.452 15.0641 16.4989C15.0641 18.5458 12.1931 19.0729 8.79222 19.0729C5.40972 19.0729 2.52039 18.5651 2.52039 16.5172C2.52039 14.4694 5.39047 13.9396 8.79222 13.9396Z" stroke="var(--primary)" stroke-linecap=" round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.79223 11.0182C6.57206 11.0182 4.77173 9.21874 4.77173 6.99857C4.77173 4.7784 6.57206 2.97898 8.79223 2.97898C11.0115 2.97898 12.8118 4.7784 12.8118 6.99857C12.8201 9.21049 11.0326 11.0099 8.82064 11.0182H8.79223Z" stroke="var(--primary)" stroke-linecap=" round" stroke-linejoin="round" />
                                    <path d="M15.1095 9.9748C16.5771 9.76855 17.7073 8.50905 17.7101 6.98464C17.7101 5.48222 16.6147 4.23555 15.1782 3.99997" stroke="var(--primary)" stroke-linecap=" round" stroke-linejoin="round" />
                                    <path d="M17.0458 13.5045C18.4675 13.7163 19.4603 14.2149 19.4603 15.2416C19.4603 15.9483 18.9928 16.4067 18.2374 16.6936" stroke="var(--primary)" stroke-linecap=" round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div id="chart-<?= $key; ?>"></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="col-xxl-6 col-lg-6 col-md-6" style="display: <?= ((strtolower($user['position']) == 'super admin') || (strtolower($user['position']) == 'accounts')) ? "" : 'none'; ?>">
    <div class="card">
        <div class="card-header pb-0 border-0">
            <div class="clearfix">
                <h4 class="card-title mb-0">Total Clients</h4>
                <small class="d-block"></small>
            </div>
        </div>
        <div class="card-body pt-1">
            <div class="row">
                <div class="col-sm-12 mb-3">
                    <center>
                        <div id="accountsChart"></div>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="<?= ((strtolower($user['position']) == 'super admin' || strtolower($user['position']) == 'accounts')) ? "col-xxl-6 col-lg-6 col-md-6" : "col-xxl-12 col-lg-12 col-md-12"; ?>">
    <div class="row">
        <div class="col-xl-6 col-sm-6">
            <div class="card chart-grd same-card">
                <div class="card-body depostit-card p-0">
                    <div class="depostit-card-media d-flex justify-content-between pb-0">
                        <div>
                            <h6>Bulk SMS Balance</h6>
                            <h3>
                                <?= $settings['currency'] . ' ' . number_format($summary['sms']['apiResponse']['Balance'], 2); ?>
                            </h3>
                        </div>
                        <div class="icon-box bg-primary-light">
                            <svg width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4642 13.7074C11.4759 12.1252 10.8504 10.8738 9.60279 9.99009C8.6392 9.30968 7.46984 8.95476 6.33882 8.6137C3.98274 7.89943 3.29927 7.52321 3.29927 6.3965C3.29927 5.14147 4.93028 4.69493 6.32655 4.69493C7.34341 4.69493 8.51331 5.01109 9.23985 5.47964L10.6802 3.24887C9.73069 2.6333 8.43112 2.21342 7.14783 2.0831V0H4.49076V2.22918C2.12884 2.74876 0.640949 4.29246 0.640949 6.3965C0.640949 7.87005 1.25327 9.03865 2.45745 9.86289C3.37331 10.4921 4.49028 10.83 5.56927 11.1572C7.88027 11.8557 8.81873 12.2813 8.80805 13.691L8.80799 13.7014C8.80799 14.8845 7.24005 15.3051 5.89676 15.3051C4.62786 15.3051 3.248 14.749 2.46582 13.9222L0.535522 15.7481C1.52607 16.7957 2.96523 17.5364 4.4907 17.8267V20.0001H7.14783V17.8735C9.7724 17.4978 11.4616 15.9177 11.4642 13.7074Z" fill="#3AC977" />
                            </svg>
                        </div>
                    </div>
                    <div id="bulkSMSBalance"></div>
                </div>
            </div>
        </div>
        <?php foreach ($summary['subAccounts'] as $subKey => $subAccount) : ?>
            <div class="col-xl-6 col-sm-6">
                <div class="card chart-grd same-card">
                    <div class="card-body depostit-card p-0">
                        <div class="depostit-card-media d-flex justify-content-between pb-0">
                            <div>
                                <h6>
                                    <?= strtoupper($subAccount['name']) . ' - ' . $subAccount['code'] ?>
                                </h6>
                                <h3>
                                    <?= number_format($account['account_clients_counter']); ?>
                                </h3>
                            </div>
                            <div class="icon-box bg-primary-light">
                                <svg width="18" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.79222 13.9396C12.1738 13.9396 15.0641 14.452 15.0641 16.4989C15.0641 18.5458 12.1931 19.0729 8.79222 19.0729C5.40972 19.0729 2.52039 18.5651 2.52039 16.5172C2.52039 14.4694 5.39047 13.9396 8.79222 13.9396Z" stroke="var(--primary)" stroke-linecap=" round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.79223 11.0182C6.57206 11.0182 4.77173 9.21874 4.77173 6.99857C4.77173 4.7784 6.57206 2.97898 8.79223 2.97898C11.0115 2.97898 12.8118 4.7784 12.8118 6.99857C12.8201 9.21049 11.0326 11.0099 8.82064 11.0182H8.79223Z" stroke="var(--primary)" stroke-linecap=" round" stroke-linejoin="round" />
                                    <path d="M15.1095 9.9748C16.5771 9.76855 17.7073 8.50905 17.7101 6.98464C17.7101 5.48222 16.6147 4.23555 15.1782 3.99997" stroke="var(--primary)" stroke-linecap=" round" stroke-linejoin="round" />
                                    <path d="M17.0458 13.5045C18.4675 13.7163 19.4603 14.2149 19.4603 15.2416C19.4603 15.9483 18.9928 16.4067 18.2374 16.6936" stroke="var(--primary)" stroke-linecap=" round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div id="client-<?= $subKey; ?>"></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>
<script>
    var chartData = {
        'summary': <?= json_encode($summary); ?>,
    };
</script>

<!-- Dashboard 1 -->
<script src="/assets/vendor/apexchart/apexchart.js"></script>
<script src="/assets/js/dashboard/nexen.js"></script>


<?= $this->endSection(); ?>