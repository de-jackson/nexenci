<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= $particularName . ' ' . ucfirst($title) ?> Information
            </div>
        </div>
        <div class="card-body">
            <table id="charges" class="table table-sm  table-hover text-nowrap" style="width:100%">
                <thead class="">
                    <tr>
                        <th><input type="checkbox" name="" id="check-all"></th>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Charge Method</th>
                        <th>Charge</th>
                        <th>Mode</th>
                        <th>Frequency</th>
                        <th>Effective Date</th>
                        <th>Status</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- start: add\edit model -->
<div class="modal fade" id="modal_form" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <!-- <div class="close">
                    <btn type="button" class="btn btn-md btn-secondary" onclick="exportBranchForm()" id="export">
                        <i class="fas fa-print text-light"></i>
                    </btn>
                </div> -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" name="id" readonly />
                    <input type="hidden" name="mode" readonly />
                    <input type="hidden" readonly value="<?= (isset($particular_id) ? $particular_id : ''); ?>" name="particular_id" />
                    <div class="form-body">
                        <!-- import row -->
                        <div id="importRow" style="display: none;">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label> Upload Branch(es)
                                                <span class=""> ( <span class="text-danger"> CSV Format </span> ) </span>
                                            </label>
                                            <input type="file" name="file" class="form-control" accept=".csv">
                                            <span class="help-block text-danger"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end import -->
                        <!-- fill form row -->
                        <div id="formRow" style="display: none;">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Frequency <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            '<select name="charge_frequency" id="charge_frequency" class="form-control select2bs4" style="width: 100%;">
                                                <option value="">-- select --</option>
                                                <option value="One-Time">One-Time</option>
                                                <option value="Weekly">Weekly</option>
                                                <option value="Monthly">Monthly</option>
                                                <option value="Annually">Annually</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Charge Method <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="charge_method" id="charge_method" class="form-control select2bs4" style="width: 100%;">
                                                <option value="">-- select --</option>
                                                <option value="Amount">Amount</option>
                                                <option value="Percent">Percent</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Charge Mode <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="charge_mode" id="charge_mode" class="form-control select2bs4" style="width: 100%;">
                                                <option value="">-- select --</option>
                                                <option value="Manual">Manual</option>
                                                <option value="Auto">Auto</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Amount/Rate <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input name="charge" placeholder="Charge (Amount/Rate)" class="form-control amount" type="text" min="" max="">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Charge Limit(Amount)</label>
                                        <div class="col-md-12">
                                            <input name="charge_limits" placeholder="Charge Limit(Amount)" class="form-control amount" type="text" min="" max="">
                                            <i><small class="fw-semibold">Starting Amount(Lower limit) for the charge.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="effective_date" class="control-label fw-bold col-sm-12">Effective Date <span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                <input type="text" name="effective_date" id="effective_date" class="form-control getDatePicker" value="<?= date('Y-m-d'); ?>" placeholder="Effective Date">
                                            </div>
                                            <i><small class="fw-semibold">Date for the charge to take effect.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cutoff_date" class="control-label fw-bold col-sm-12">Cutoff Date</label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                <input type="text" name="cutoff_date" id="cutoff_date" class="form-control getDatePicker" placeholder="Cutoff Date">
                                            </div>
                                            <i><small class="fw-semibold">Date for the charge reduction.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Charge Status <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="charge_status" id="charge_status" class="form-control select2bs4">

                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- fill form row -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSubmit" class="btn btn-outline-success" onclick="saveCharge()">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end: add\edit model -->

<!-- view branch modal -->
<div class="modal fade" id="view_modal" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <!-- <div class="close">
                    <btn type="button" class="btn btn-md btn-secondary" onclick="exportBranchForm()" id="export">
                        <i class="fas fa-file-pdf text-light"></i>
                    </btn>
                </div> -->
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Charge Frequency</label>
                                    <div class="col-md-12">
                                        <input name="vcharge_frequency" placeholder="Charge Frequency" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Charge Method</label>
                                    <div class="col-md-12">
                                        <input name="vcharge_method" placeholder="Charge Method" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Charge Mode</label>
                                    <div class="col-md-12">
                                        <input name="vcharge_mode" placeholder="Charge Mode" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Charge</label>
                                    <div class="col-md-12">
                                        <input name="vcharge" placeholder="Charge" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Charge Limit</label>
                                    <div class="col-md-12">
                                        <input name="vcharge_limits" placeholder="Charge Limit" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Effective Date</label>
                                    <div class="col-md-12">
                                        <input name="veffective_date" placeholder="Effective Date" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Cutoff Date</label>
                                    <div class="col-md-12">
                                        <input name="vcutoff_date" placeholder="Cutoff Date" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Charge Status</label>
                                    <div class="col-md-12">
                                        <input name="vcharge_status" placeholder="Charge Status" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Date Created</label>
                                    <div class="col-md-12">
                                        <input type="text" name="created_at" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Date Updated</label>
                                    <div class="col-md-12">
                                        <input type="text" name="updated_at" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    var id = '<?= isset($particular_id) ? $particular_id : 0; ?>';
    var pageMenu = <?= json_encode($module); ?>;
</script>
<script src="/assets/scripts/charges/index.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>

<?= $this->endSection() ?>