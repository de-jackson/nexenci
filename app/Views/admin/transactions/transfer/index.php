<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ucfirst($title) ?> Information
            </div>
        </div>
        <div class="card-body">
            <table id="transfers" class="table table-sm  table-hover text-nowrap" style="width:100%">
                <thead class="">
                    <tr>
                        <th><input type="checkbox" name="" id="check-all"></th>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Particular From</th>
                        <th>Particular To</th>
                        <th>Type[P/To]</th>
                        <th>Amount [<?= $settings['currency']; ?>]</th>
                        <th>Ref ID</th>
                        <th>Officer</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- add\edit model -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-info"> </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly name="id" />
                    <input type="hidden" readonly name="menu" value="<?= $menu ?>" />
                    <input type="hidden" readonly name="title" value="<?= $title ?>" />
                    <input type="hidden" readonly name="entry_menu" />
                    <input type="hidden" readonly name="entry_typeId" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-12">
                                <span class="fw-bold">Transfer From Details[Credited]</span>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Account Type <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <select name="credit_accountType" id="credit_accountType" class="form-control select2bs4">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Particular Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <select class="form-control select2bs4" name="crParticular_id" id="crParticular_id" style="width: 100%;">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-12">
                                <span class="fw-bold">Transfer To Details[Debited]</span>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Account Type <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <select id="debit_accountType" name="debit_accountType" class="form-control select2bs4">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Particular Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <select class="form-control select2bs4" name="drParticular_id" id="drParticular_id" style="width: 100%;">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Amount <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <input type="text" name="amount" id="amount" class="form-control amount" placeholder="Transaction Amount">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Transfer Date <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <input type="text" name="date" id="date" class="form-control getDatePicker" value="<?= date('Y-m-d'); ?>" placeholder="Transfer Date">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Transaction Details <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <textarea name="entry_details" class="form-control" id="summernote"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Remarks</label>
                                    <div class="col-md-12">
                                        <textarea name="remarks" class="form-control" placeholder="Transaction Remarks"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="hidden" readonly name="staff_id" placeholder="employee ID" class="form-control" value="<?= session()->get('id') ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSav" onclick="save_transaction()" class="btn btn-outline-success">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- view modal -->
<div class="modal fade" data-bs-backdrop="static" id="view_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title text-info"> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <input type="hidden" readonly name="entry_menu" />
                    <input type="hidden" readonly name="entry_typeId" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6" id="fromParticular">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Particular(From)</label>
                                    <div class="col-md-12">
                                        <input name="vparticular_id" placeholder="Particular(From)" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="toParticular">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Particular(To)</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vpayment_id" id="payment_id" class="form-control" placeholder="Particular(To)" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" id="clientID" style="display: none;">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Client Name</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vclient_id" id="client_id" class="form-control" placeholder="Client Name" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Amount</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vamount" id="amount" class="form-control" placeholder="Amount" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Reference ID</label>
                                    <div class="col-md-12">
                                        <input name="vref_id" placeholder="Reference ID" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Module</label>
                                    <div class="col-md-12">
                                        <input name="vmodule" placeholder="Module" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0" id="disbursementData" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Loan Product</label>
                                    <div class="col-md-12">
                                        <input name="vproduct_name" placeholder="Loan Product" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Disbursement Code</label>
                                    <div class="col-md-12">
                                        <input name="vdisbursement_id" placeholder="Disbursement Code" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Class</label>
                                    <div class="col-md-12">
                                        <input name="vclass" placeholder="Class" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0" id="applicationData" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Loan Product</label>
                                    <div class="col-md-12">
                                        <input name="vproduct_name" placeholder="Loan Product" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Application Code</label>
                                    <div class="col-md-12">
                                        <input name="vapplication_id" placeholder="Application Code" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Application Status</label>
                                    <div class="col-md-12">
                                        <input name="vapplication_status" placeholder="Application Status" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Transaction Details</label>
                                    <div class="col-md-12">
                                        <textarea name="ventry_details" class="form-control" id="viewSummernote" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Officer</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vstaff_id" placeholder="Officer" id="officer" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Transaction Date</label>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <input type="text" name="created_at" id="created_at" class="form-control" placeholder="Transaction Date" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Updated At</label>
                                    <div class="col-md-12">
                                        <input type="text" name="updated_at" id="updated_at" class="form-control" placeholder="Updated At" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Transaction Remarks</label>
                                    <div class="col-md-12">
                                        <textarea name="vremarks" class="form-control" placeholder="Transaction"></textarea>
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
<!-- /.content -->
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>

<script type="text/javascript">
    var id = '<?= isset($transaction) ? $transaction['id'] : 0; ?>';
    var entry_menu = '<?= $title; ?>';
    var part = '<?= isset($part) ? strtolower($part) : null; ?>';
</script>
<script src="/assets/scripts/main/auto-updates.js"></script>
<script src="/assets/scripts/transactions/index.js"></script>
<script src="/assets/scripts/transactions/transfers/transfer.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/phone.js"></script>

<?= $this->endSection() ?>