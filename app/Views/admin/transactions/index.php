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
            <table id="transactions" class="table table-sm  table-hover text-nowrap" style="width:100%">
                <thead class="">
                    <tr>
                        <th><input type="checkbox" name="" id="check-all"></th>
                        <th>S.No</th>
                        <th>Particular</th>
                        <th>Payment</th>
                        <th>Acc. Type</th>
                        <th>Amount [<?= $settings['currency']; ?>]</th>
                        <th>Ref ID</th>
                        <th>Officer</th>
                        <th>Date</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- add\edit model -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">

                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Expense Particular</label>
                                    <div class="col-md-12">
                                        <select class="form-control select2bs4" name="payment_id" id="payment_id" style="width: 100%;">

                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Transaction Details</label>
                                    <div class="col-md-12">
                                        <textarea name="entry_details" class="form-control" id="addSummernote"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Amount</label>
                                    <div class="col-md-12">
                                        <input type="text" name="amount" id="amount" class="form-control amount" placeholder="Transaction Amount">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Payment Method</label>
                                    <div class="col-md-12">
                                        <select name="particular_id" id="particular_id" class="form-control select2bs4" style="width: 100%;">
                                            <option value="">-- select --</option>
                                            <?php
                                            if (count($payments) > 0) :
                                                foreach ($payments as $payment) : ?>
                                                    <option value="<?= $payment['id']; ?>">
                                                        <?= $payment['particular_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
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
                                        <textarea name="remarks" class="form-control" placeholder="Expense Remarks"></textarea>
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
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Expense SubCategory</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="subcategory_name" placeholder="Expense SubCategory" id="subcategory" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Expense Particular</label>
                                    <div class="col-md-12">
                                        <input name="vpayment_id" placeholder="Expense Particular" class="form-control" type="text" readonly>
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
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Expense Details</label>
                                    <div class="col-md-12">
                                        <textarea name="ventry_details" class="form-control" id="viewSummernote" readonly></textarea>
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
                                    <label class="control-label fw-bold col-md-12">Payment Mode</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vparticular_id" id="particular_id" class="form-control" placeholder="Payment Mode" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Status</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vstatus" id="status" class="form-control" placeholder="Status" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Balance</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vbalance" id="balance" class="form-control" placeholder="Balance" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Created At</label>
                                    <div class="col-md-12">
                                        <input type="text" name="created_at" id="created_at" class="form-control" placeholder="Created At" readonly>
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
                                    <label class="control-label fw-bold col-md-12">Expense Remarks</label>
                                    <div class="col-md-12">
                                        <textarea name="vremarks" class="form-control" placeholder="Expense"></textarea>
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

</script>
<script src="/assets/scripts/main/auto-updates.js"></script>
<script src="/assets/scripts/transactions/index.js"></script>
<script src="/assets/scripts/main/auto-updates.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>

<?= $this->endSection() ?>