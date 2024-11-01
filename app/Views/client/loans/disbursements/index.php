<?= $this->extend("layout/client"); ?>

<?= $this->section("content"); ?>

<div class="col-xl-12">
    <div class="card border border-warning custom-card">
        <div class="card-header">
            <div class="card-title">
                <?= ucfirst($title) ?> Information
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs tab-style-2 d-sm-flex d-block border-bottom border-block-end-dashed" id="myTab1" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="running-tab" data-bs-toggle="tab" data-bs-target="#running-tab-pane" type="button" role="tab" aria-controls="running-tab" aria-selected="true">
                        <i class="ri-timer-2-line me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Running
                            <span class="badge badge-info badge-sm badge-circle"><?= $user['disbursementsCounter']['running']; ?></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="arrears-tab" data-bs-toggle="tab" data-bs-target="#arrears-tab-pane" type="button" role="tab" aria-controls="confirmed-tab" aria-selected="false">
                        <i class="fa fa-clock-rotate-left me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Arrears
                            <span class="badge badge-warning badge-sm badge-circle"><?= $user['disbursementsCounter']['arrears']; ?></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="cleared-tab" data-bs-toggle="tab" data-bs-target="#cleared-tab-pane" type="button" role="tab" aria-controls="cleared-tab" aria-selected="false">
                        <i class="ri-check-double-line me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Cleared
                            <span class="badge badge-success badge-sm badge-circle"><?= $user['disbursementsCounter']['cleared']; ?></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="expired-tab" data-bs-toggle="tab" data-bs-target="#expired-tab-pane" type="button" role="tab" aria-controls="expired-tab" aria-selected="false">
                        <i class="ti ti-clock-off me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Expired
                            <span class="badge badge-danger badge-sm badge-circle"><?= $user['disbursementsCounter']['expired']; ?></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-danger" id="defaulted-tab" data-bs-toggle="tab" data-bs-target="#defaulted-tab-pane" type="button" role="tab" aria-controls="defaulted-tab" aria-selected="false">
                        <i class="ti ti-clock-off me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Defaulted
                            <span class="badge badge-danger badge-sm badge-circle"><?= $user['disbursementsCounter']['defaulted']; ?></span>
                        </span>
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- running disbursements -->
                <div class="tab-pane fade show active " id="running-tab-pane" role="tabpanel" aria-labelledby="running-tab-pane" tabindex="0">
                    <h4 class="mt-4 mb-4">Running Loans</h4>
                    <table id="runningDisbursements" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-allRunning"></th>
                                <th>S.No</th>
                                <th>C/Name</th>
                                <th>Product</th>
                                <th>D/Code</th>
                                <th>Principal [<?= $settings['currency']; ?>]</th>
                                <th>Interest [<?= $settings['currency']; ?>]</th>
                                <th>T/Loan [<?= $settings['currency']; ?>]</th>
                                <th>Installment [<?= $settings['currency']; ?>]</th>
                                <th>Paid [<?= $settings['currency']; ?>]</th>
                                <th>Balance [<?= $settings['currency']; ?>]</th>
                                <th>Days Left</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- arrears disbursements -->
                <div class="tab-pane fade " id="arrears-tab-pane" role="tabpanel" aria-labelledby="arrears-tab-pane" tabindex="0">
                    <h4 class="mt-4 mb-4">Loans Arrears</h4>
                    <table id="arrearsDisbursements" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-allArrears"></th>
                                <th>S.No</th>
                                <th>C/Name</th>
                                <th>Product</th>
                                <th>D/Code</th>
                                <th>Principal [<?= $settings['currency']; ?>]</th>
                                <th>Interest [<?= $settings['currency']; ?>]</th>
                                <th>T/Loan [<?= $settings['currency']; ?>]</th>
                                <th>Installment [<?= $settings['currency']; ?>]</th>
                                <th>Paid [<?= $settings['currency']; ?>]</th>
                                <th>Balance [<?= $settings['currency']; ?>]</th>
                                <th>Days Left</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- cleared disbursements -->
                <div class="tab-pane fade " id="cleared-tab-pane" role="tabpanel" aria-labelledby="cleared-tab-pane" tabindex="0">
                    <h4 class="mt-4 mb-4">Cleared Loans </h4>
                    <table id="clearedDisbursements" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-allCleared"></th>
                                <th>S.No</th>
                                <th>C/Name</th>
                                <th>Product</th>
                                <th>D/Code</th>
                                <th>Principal [<?= $settings['currency']; ?>]</th>
                                <th>Interest [<?= $settings['currency']; ?>]</th>
                                <th>T/Loan [<?= $settings['currency']; ?>]</th>
                                <th>Installment [<?= $settings['currency']; ?>]</th>
                                <th>Paid [<?= $settings['currency']; ?>]</th>
                                <th>Balance [<?= $settings['currency']; ?>]</th>
                                <th>Days Left</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- expired disbursements -->
                <div class="tab-pane fade " id="expired-tab-pane" role="tabpanel" aria-labelledby="expired-tab-pane" tabindex="0">
                    <h4 class="mt-4 mb-4">Expired Loans</h4>
                    <table id="expiredDisbursements" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-allExpired"></th>
                                <th>S.No</th>
                                <th>C/Name</th>
                                <th>Product</th>
                                <th>D/Code</th>
                                <th>Principal [<?= $settings['currency']; ?>]</th>
                                <th>Interest [<?= $settings['currency']; ?>]</th>
                                <th>T/Loan [<?= $settings['currency']; ?>]</th>
                                <th>Installment [<?= $settings['currency']; ?>]</th>
                                <th>Paid [<?= $settings['currency']; ?>]</th>
                                <th>Balance [<?= $settings['currency']; ?>]</th>
                                <th>Days Left</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody> </tbody>
                    </table>
                </div>
                <!-- defaulted disbursements -->
                <div class="tab-pane fade " id="defaulted-tab-pane" role="tabpanel" aria-labelledby="defaulted-tab-pane" tabindex="0">
                    <h4 class="mt-4 mb-4">Defaulted Loans</h4>
                    <table id="defaultedDisbursements" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-allDefaulted"></th>
                                <th>S.No</th>
                                <th>C/Name</th>
                                <th>Product</th>
                                <th>D/Code</th>
                                <th>Principal [<?= $settings['currency']; ?>]</th>
                                <th>Interest [<?= $settings['currency']; ?>]</th>
                                <th>T/Loan [<?= $settings['currency']; ?>]</th>
                                <th>Installment [<?= $settings['currency']; ?>]</th>
                                <th>Paid [<?= $settings['currency']; ?>]</th>
                                <th>Balance [<?= $settings['currency']; ?>]</th>
                                <th>Days Left</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody> </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- upload model -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly name="id" />
                    <input type="hidden" readonly name="mode" />
                    <div class="form-body">
                        <div id="importRow" style="display: none;">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Branch Name</label>
                                            <select id="branch_id" name="branch_id" class="form-control branch_id select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label> Upload Disbursement(s)
                                                <span class="text-white"> ( <span class="text-danger"> CSV Format </span> ) </span>
                                            </label>
                                            <input type="file" name="file" class="form-control" accept=".csv">
                                            <span class="help-block text-danger"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="formRow">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSubmit" onclick="save_disbursement()" class="btn btn-outline-success">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- payment add model -->
<div class="modal fade" data-bs-backdrop="static" id="repayment_modal_form">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            <div class="modal-body form">
                <form id="repaymentForm" class="form-horizontal" autocomplete="off">

                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly name="account_typeId" />
                    <input type="hidden" readonly name="disbursement_id" />
                    <input type="hidden" readonly name="client_id" />
                    <input type="hidden" readonly name="entry_menu" />
                    <div class="form-body">
                        <!-- summary -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-8">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <p class="">
                                            Name: <br />
                                            <strong class="pl-3" id="cName"></strong>
                                        </p>
                                        <p class="">
                                            Contact: <br />
                                            <strong class="pl-3" id="cContact"></strong>
                                        </p>
                                        <p class="">
                                            Alt Contact: <br />
                                            <strong class="pl-3" id="cContact2"></strong>
                                        </p>
                                        <p class="">
                                            Email: <br />
                                            <strong class="pl-3" id="cEmail"></strong>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="">
                                            Acc Number: <br />
                                            <strong class="pl-3" id="cAccountNo"></strong>
                                        </p>
                                        <p class="">
                                            Acc Balance: <br />
                                            <strong class="pl-3" id="cBalance"></strong>
                                        </p>
                                        <p class="">
                                            Address: <br />
                                            <strong class="pl-3" id="cAddress"></strong>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="">
                                            Total Loan: <br />
                                            <strong class="pl-3" id="tLoan"></strong>
                                        </p>
                                        <p class="">
                                            Loan Balance: <br />
                                            <strong class="pl-3" id="lBalance"></strong>
                                        </p>
                                        <p class="">
                                            Installment: <br />
                                            <strong class="pl-3" id="installment"></strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center" id="cPhoto-preview">
                                    <div class="col-md-12">
                                        (No photo)
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- particular -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Particular Name</label>
                                    <div class="col-md-12">
                                        <select id="particular_id" name="particular_id" class="form-control select2bs4f">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-sm-12">Transaction Type</label>
                                    <div class="col-sm-12">
                                        <select name="entry_typeId" id="entry_typeId" class="form-control select2bs4f">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Contact</label>
                                    <div class="col-md-12">
                                        <input name="contact" placeholder="Contact" class="form-control" type="text">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- amount -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Installment[<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input type="number" name="amount" class="form-control" placeholder="Installment[<?= $settings['currency']; ?>]" min="0">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Payment Method</label>
                                    <div class="col-md-12">
                                        <select name="payment_id" id="payment_id" class="form-control select2bs4f" style="width: 100%;">

                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-sm-12">Date</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <input type="text" name="date" id="date" class="form-control getDatePicker" value="<?= date('Y-m-d'); ?>" placeholder="Transaction Date">
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- details & remarks -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Details</label>
                                    <div class="col-md-12">
                                        <textarea name="entry_details" class="form-control" id="addSummernote"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Remarks</label>
                                    <div class="col-md-12">
                                        <textarea name="remarks" class="form-control" placeholder="Remarks"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input name="staff_id" placeholder="Responsible Officer" class="form-control" type="hidden" value="<?= session()->get('id'); ?>">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnPay" onclick="save_payments()" class="btn btn-outline-success">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- adjust disbursement expiry date -->
<div class="modal fade" data-bs-backdrop="static" id="expiry_date_modal">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form id="expiryDateForm" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly name="id" />
                    <input type="hidden" readonly name="module" />
                    <div class="form-body">
                        <!-- summary -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-8">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <p class="">
                                            Name: <br />
                                            <strong class="pl-3" id="cName"></strong>
                                        </p>
                                        <p class="">
                                            Contact: <br />
                                            <strong class="pl-3" id="cContact"></strong>
                                        </p>
                                        <p class="">
                                            Email: <br />
                                            <strong class="pl-3" id="cEmail"></strong>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="">
                                            Acc Number: <br />
                                            <strong class="pl-3" id="cAccountNo"></strong>
                                        </p>
                                        <p class="">
                                            Acc Balance: <br />
                                            <strong class="pl-3" id="cBalance"></strong>
                                        </p>
                                        <p class="">
                                            Address: <br />
                                            <strong class="pl-3" id="cAddress"></strong>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="">
                                            Total Loan: <br />
                                            <strong class="pl-3" id="tLoan"></strong>
                                        </p>
                                        <p class="">
                                            Loan Balance: <br />
                                            <strong class="pl-3" id="lBalance"></strong>
                                        </p>
                                        <p class="">
                                            Installment: <br />
                                            <strong class="pl-3" id="installment"></strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center" id="cPhoto-preview">
                                    <div class="col-md-12">
                                        (No photo)
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <p class="">
                                    Current Period(days): <br />
                                    <strong class="pl-3" id="periodDays"></strong>
                                </p>
                                <p class="">
                                    Date Disbursed: <br />
                                    <strong class="pl-3" id="createdAt"></strong>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="">
                                    Days Covered: <br />
                                    <strong class="pl-3" id="daysCovered"></strong>
                                </p>
                                <p class="">
                                    Expiry Date: <br />
                                    <strong class="" id="expiryDate"></strong>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="">
                                    Days Left: <br />
                                    <strong class="pl-3" id="daysLeft"></strong>
                                </p>
                                <p class="">
                                    Expiry Day: <br />
                                    <strong class="pl-3" id="expiryDay"></strong>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <!-- new date -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Select New Date</label>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <input type="text" name="expiry_date" value="" class="form-control getDatePicker" id="expiry_date" placeholder="Select New Disbursement Expiry Date">
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input name="staff_id" placeholder="Officer" class="form-control" type="hidden" value="<?= session()->get('id'); ?>">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnPay" onclick="save_disbursement()" class="btn btn-outline-success">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script src="/assets/client/main/auto.js"></script>
<script src="/assets/client/loans/disbursements.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/loans/index.js"></script>
<script src="/assets/scripts/transactions/index.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>

<?= $this->endSection() ?>