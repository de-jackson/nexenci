<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<!-- date filter -->
<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <div class="contact-header">
                <div class="d-sm-flex d-block align-items-center justify-content-between">
                    <div class="h5 fw-semibold mb-0">Filter Date of Creation:</div>
                    <div class="d-flex mt-sm-0 mt-2 align-items-center">
                        <form action="/admin/reports/view/<?= $report; ?>" class="form-horizontal" method="post" autocomplete="off">
                            <?= csrf_field() ?>
                            <div class="form-body">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-3" id="filterCols">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <label class="control-label fw-bold col-12 text-info text-center" for="val">Filters</label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="filter" id="filter">
                                                    <?php
                                                    if (count($filters) > 0) :
                                                        foreach ($filters as $key => $opt) :
                                                    ?>
                                                            <option value="<?= $key ?>" <?= ($opt == $filter) ? "selected" : ''; ?>>
                                                                <?= $opt; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <option value="">No Filter</option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control" name="opted" id="selectOpt">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8" id="filterOpts">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <!-- filter inputs -->
                                            <div class="col-md-6">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold text-info col-12 text-center">Amount Applied</label>
                                                    <div class="col-md-6" id="balCol">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <label class="control-label fw-bold col-4 text-right" for="bal">From:</label>
                                                            <div class="col-8">
                                                                <input type="number" name="bal" class="form-control form-control-sm" placeholder="Amount" id="bal" value="<?= isset($bal) ? $bal : ''; ?>" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="btnCol">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <label class="control-label fw-bold col-4 text-right" for="btn">To:</label>
                                                            <div class="col-8">
                                                                <input type="number" name="btn" class="form-control form-control-sm" placeholder="Amount" id="btn" value="<?= isset($btn) ? $btn : ''; ?>" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- date inputs -->
                                            <div class="col-md-6">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-12 text-info text-center">Date Applied</label>
                                                    <div class="col-md-6" id="fromCol">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <label class="control-label fw-bold col-4 text-right" for="from">From:</label>
                                                            <div class="col-8">
                                                                <input type="date" name="from" class="form-control form-control-sm" id="from" value="<?= isset($from) ? $from : ''; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="toCol">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <label class="control-label fw-bold col-4 text-right" for="to">To:</label>
                                                            <div class="col-8">
                                                                <input type="date" name="to" class="form-control form-control-sm" id="to" value="<?= isset($to) ? $to : ''; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <label class="control-label fw-bold col-12 text-center"></label>
                                        <button type="submit" class="btn btn-md btn-outline"><i class="fas fa-filter text-info"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                <?= ucfirst($report) ?> Report
            </div>
        </div>
        <div class="card-body">
            <div class="row gx-3 gy-2 align-items-center mt-0">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12">
                                    <table id="disbursementsReport" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                        <thead class="">
                                            <tr>
                                                <th><input type="checkbox" name="" id="check-all"></th>
                                                <th>S.No</th>
                                                <th>Name</th>
                                                <th>Loan</th>
                                                <th>Code</th>
                                                <th>Tot Loan</th>
                                                <th>Installment[<?= $settings['currency']; ?>]</th>
                                                <th>Balance[<?= $settings['currency']; ?>]</th>
                                                <th>Paid[<?= $settings['currency']; ?>]</th>
                                                <th>Class</th>
                                                <th>#ID</th>
                                                <th width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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
<script type="text/javascript">
    var selected = '<?= $selected ?>';
    var report = '<?= $report ?>';
</script>
<script src="/assets/scripts/reports/disbursements.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>

<?= $this->endSection() ?>