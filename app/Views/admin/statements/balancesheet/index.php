<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>

<!-- date filter -->
<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <div class="contact-header">
                <div class="d-sm-flex d-block align-items-center justify-content-between">
                    <div class="h5 fw-semibold mb-0">Filter Between Dates:</div>
                    <div class="d-flex mt-sm-0 mt-2 align-items-center">
                        <form id="statementForm" class="form-horizontal" autocomplete="off">
                            <?= csrf_field() ?>
                            <div class="form-body">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <!-- filter inputs -->
                                    <div class="col-md-5">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-6">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-12 text-right" for="f_year">Financial Year:</label>
                                                    <div class="col-12">
                                                        <select id="f_year" name="f_year" class="form-control select2bs4">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-12 text-right" for="f_quarter">Quarters:</label>
                                                    <div class="col-12">
                                                        <select id="f_quarter" name="f_quarter" class="form-control select2bs4">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- date filters -->
                                    <div class="col-md-6">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-6">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-12 text-right" for="start_date_input">Start:</label>
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                            <input type="date" name="start_date" class="form-control form-control-sm getDatePicker" id="start_date_input" value="<?= $start_date ?>" placeholder="Start Date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-12 text-right" for="end_date_input">End:</label>
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                            <input type="date" name="end_date" class="form-control form-control-sm getDatePicker" value="<?= $end_date ?>" id="end_date_input" placeholder="End Date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-primary btn-block" id="statementBtn" value="Submit" onclick="generate_balancesheet()"><i class="fa fa-search fa-fw"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php if ((unserialize($user['permissions']) == 'all') || (in_array('export' . (str_replace(' ', '', $title)), unserialize($user['permissions'])))) : ?>
                            <a href="/admin/statements/export-statement/balancesheet" rel="noopener" class="btn btn-icon btn-secondary-light ms-2" id="printURL" title="Print Statement">
                                <i class="fas fa-print"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- statement body -->
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ucfirst($title) ?> Statement
            </div>
        </div>
        <div class="card-body">
            <div class="row gx-3 gy-2 align-items-center mt-0">
                <div class="col-md-12">
                    <div class="card  custom-card">
                        <div class="card-body">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12 text-center">
                                    <div class="d-inline-block">
                                        <img src="<?= isset($settings) && file_exists('uploads/logo/' . $settings['business_logo']) && $settings['business_logo'] ? '/uploads/logo/' . $settings['business_logo'] : '/assets/dist/img/default.jpg'; ?>" class="brand-image" style="width: 100px; height: 100px; opacity: 0.8;" alt="Logo" />
                                    </div>
                                    <div class="d-inline-block align-middle px-3 py-3">
                                        <h6 class="fw-bold text-default"><?= $settings['business_name']; ?></h6>
                                        <p class="fw-semibold mb-2 fs-12">
                                            <?= $settings['business_address']; ?>
                                        </p>
                                        <p class="fw-semibold mb-2 fs-12">
                                            <?= $settings['business_pobox']; ?>
                                        </p>
                                        <p class="fw-semibold mb-2 fs-12">
                                            <?= $settings['business_contact'] . ' || ' . $settings['business_alt_contact']; ?>
                                        </p>
                                        <p class="fw-semibold mb-2 fs-12">
                                            <?= $settings['business_email']; ?>
                                        </p>
                                    </div>
                                    <h6 class="fw-bold text-default"><?= str_replace('-', ' ', $title) ?> Statement</h6>
                                    <h6 class="fw-bold text-default">
                                        For a Period Between &nbsp;&nbsp; <i><u><span id="startDate"></span></u></i> &nbsp;&nbsp; and &nbsp;&nbsp; <i><u><span id="endDate"></span></u></i>
                                    </h6>
                                </div>
                            </div>
                            <hr>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12">
                                    <table id="balancesheet" class="table table-sm table-hover text-nowrap" style="width:100%">
                                        <thead class="">
                                            <tr>
                                                <td><b>Item</b></td>
                                                <td align="right"><b>Amount</b>[<span id="currency"></span>]</td>
                                            </tr>
                                        </thead>
                                        <tbody id="balancesheet-body">
                                            <!-- AJAX response will be inserted here -->
                                        </tbody>
                                    </table>
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
<script>
    var statement = "balancesheet";
    var tableId = "balancesheet";
    var selected_fYear = <?= json_encode($selected_fYear) ?>;
    var selected_fQuarter = <?= json_encode($selected_fQuarter) ?>;
</script>
<script src="/assets/scripts/statements/index.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>

<?= $this->endSection() ?>