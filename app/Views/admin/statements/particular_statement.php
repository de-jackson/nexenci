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
                                        <button type="button" class="btn btn-primary btn-block" id="statementBtn" value="Submit" onclick="generate_particularstatement()"><i class="fa fa-search fa-fw"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php if ((unserialize($user['permissions']) == 'all') || (in_array('export' . (str_replace(' ', '', $title)), unserialize($user['permissions'])))) : ?>
                            <a href="/admin/statements/export-statement/particular" rel="noopener" class="btn btn-icon btn-secondary-light ms-2" id="printURL" title="Print Statement">
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
                <?= ucfirst($title) ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row gx-3 gy-2 align-items-center mt-0">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12 text-center">
                                    <div class="d-inline-block">
                                        <img src="<?= isset($settings) && file_exists('uploads/logo/' . $settings['business_logo']) && $settings['business_logo'] ? '/uploads/logo/' . $settings['business_logo'] : '/assets/dist/img/default.jpg'; ?>" class="brand-image" style="width: 100px; height: 100px; opacity: 0.8;" alt="Logo" />
                                    </div>
                                    <div class="d-inline-block align-middle px-3 py-3">
                                        <h6 class="fw-bold text-default"><?= $settings['business_name'] . '(' . $settings['business_abbr'] . ')'; ?></h6>
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
                                    <h6 class="fw-bold text-default"><?= $particular['particular_name'] ?> Particular Statement</h6>
                                    <h6 class="fw-bold text-default">
                                        For a Period Between &nbsp;&nbsp; <i><u><span id="startDate"></span></u></i> &nbsp;&nbsp; and &nbsp;&nbsp; <i><u><span id="endDate"></span></u></i>
                                    </h6>
                                </div>
                            </div>
                            <hr>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12">
                                    <table id="particularstatement" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                        <thead class="">
                                            <tr>
                                                <th rowspan="2">Date</th>
                                                <th rowspan="2">Type</th>
                                                <!-- <th rowspan="2">Status</th>
                                                            <th rowspan="2">Description</th> -->
                                                <th colspan="2" class="text-center border-left border-right">Opening Balance[<?= $settings['currency']; ?>]</th>
                                                <th colspan="2" class="text-center border-left border-right">Transactions[<?= $settings['currency']; ?>]</th>
                                                <th colspan="2" class="text-center border-left border-right">Closing Balance[<?= $settings['currency']; ?>]</th>
                                                <!-- <th rowspan="2" class="text-center"><i>#Ref ID</i></th> -->
                                            </tr>
                                            <tr>
                                                <th class=" border-left text-right">Debit</th>
                                                <th class="text-right">Credit</th>
                                                <th class="border-left text-right">Debit</th>
                                                <th class="text-right">Credit</th>
                                                <th class="border-left text-right">Debit</th>
                                                <th class="border-right text-right">Credit</th>
                                            </tr>
                                        </thead>
                                        <tbody id="particularstatement-body">
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
    var statement = "particularstatement";
    var tableId = "particularstatement";
    var selected_fYear = <?= json_encode($selected_fYear) ?>;
    var selected_fQuarter = <?= json_encode($selected_fQuarter) ?>;
    var particularId = <?= $particular['id'] ?>;
    var particularName = "<?= $particular['particular_name'] ?>";
    var particularAcountId = <?= $particular['account_typeId'] ?>;
    var part = "<?= $particular['part'] ?>";
    var opening = Number(<?= $particular['opening_balance'] ?>);
    var start_date = "<?= $start_date ?>";
    var end_date = "<?= $end_date ?>";
    var created_at = "<?= $particular['created_at'] ?>";
</script>
<script src="/assets/scripts/statements/index.js"></script>

<?= $this->endSection() ?>