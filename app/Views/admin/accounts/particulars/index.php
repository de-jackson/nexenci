<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ucwords($menu) . ' ' . ucfirst($title) ?> Information
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs tab-style-2 d-sm-flex d-block border-bottom border-block-end-dashed" id="myTab1" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="assets-tab" data-bs-toggle="tab" data-bs-target="#assets-tab-pane" type="button" role="tab" aria-controls="assets-tab" aria-selected="true">
                        <i class="ri-building-line me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Assets
                            <span class="badge bg-success badge-sm badge-circle" id="assets-particulars"></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="equity-tab" data-bs-toggle="tab" data-bs-target="#equity-tab-pane" type="button" role="tab" aria-controls="equity-tab" aria-selected="false">
                        <i class="bx bx-briefcase me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Equity
                            <span class="badge bg-success badge-sm badge-circle" id="equity-particulars"></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="liabilities-tab" data-bs-toggle="tab" data-bs-target="#liabilities-tab-pane" type="button" role="tab" aria-controls="liabilities-tab" aria-selected="false">
                        <i class="ti ti-cash-off me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Liabilities
                            <span class="badge bg-success badge-sm badge-circle" id="liabilities-particulars"></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="income-tab" data-bs-toggle="tab" data-bs-target="#income-tab-pane" type="button" role="tab" aria-controls="income-tab" aria-selected="false">
                        <i class="ti ti-receipt-2 me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Income
                            <span class="badge bg-success badge-sm badge-circle" id="revenue-particulars"></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses-tab-pane" type="button" role="tab" aria-controls="expenses-tab" aria-selected="false">
                        <i class="ti ti-receipt-tax  me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Expenses
                            <span class="badge bg-success badge-sm badge-circle" id="expenses-particulars"></span>
                        </span>
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- assets particulars -->
                <div class="tab-pane fade show active " id="assets-tab-pane" role="tabpanel" aria-labelledby="assets-tab-pane" tabindex="0">
                    <h4 class="pt-4 mb-4">Assets Particulars</h4>
                    <table id="assetsParticulars" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-all1"></th>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Subcategory</th>
                                <th>Cash Flow</th>
                                <th>Code</th>
                                <th>Debit [<?= $settings['currency']; ?>]</th>
                                <th>Credit [<?= $settings['currency']; ?>]</th>
                                <th>Balance [<?= $settings['currency']; ?>]</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- equity particulars -->
                <div class="tab-pane fade " id="equity-tab-pane" role="tabpanel" aria-labelledby="equity-tab-pane" tabindex="0">
                    <h4 class="pt-4 mb-4">Enquity Particulars</h4>
                    <table id="equityParticulars" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-all2"></th>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Subcategory</th>
                                <th>Cash Flow</th>
                                <th>Code</th>
                                <th>Debit [<?= $settings['currency']; ?>]</th>
                                <th>Credit [<?= $settings['currency']; ?>]</th>
                                <th>Balance [<?= $settings['currency']; ?>]</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- liabilities particulars -->
                <div class="tab-pane fade " id="liabilities-tab-pane" role="tabpanel" aria-labelledby="liabilities-tab-pane" tabindex="0">
                    <h4 class="pt-4 mb-4">Liabilities Particulars</h4>
                    <table id="liabilityParticulars" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-all3"></th>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Subcategory</th>
                                <th>Cash Flow</th>
                                <th>Code</th>
                                <th>Debit [<?= $settings['currency']; ?>]</th>
                                <th>Credit [<?= $settings['currency']; ?>]</th>
                                <th>Balance [<?= $settings['currency']; ?>]</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- income particulars -->
                <div class="tab-pane fade " id="income-tab-pane" role="tabpanel" aria-labelledby="income-tab-pane" tabindex="0">
                    <h4 class="pt-4 mb-4">Income Particulars</h4>
                    <table id="revenueParticulars" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-all4"></th>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Subcategory</th>
                                <th>Cash Flow</th>
                                <th>Code</th>
                                <th>Debit [<?= $settings['currency']; ?>]</th>
                                <th>Credit [<?= $settings['currency']; ?>]</th>
                                <th>Balance [<?= $settings['currency']; ?>]</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- expenses particulars -->
                <div class="tab-pane fade " id="expenses-tab-pane" role="tabpanel" aria-labelledby="expenses-tab-pane" tabindex="0">
                    <h4 class="pt-4 mb-4">Expenses Particulars</h4>
                    <table id="expensesParticulars" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-all5"></th>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Subcategory</th>
                                <th>Cash Flow</th>
                                <th>Code</th>
                                <th>Debit [<?= $settings['currency']; ?>]</th>
                                <th>Credit [<?= $settings['currency']; ?>]</th>
                                <th>Balance [<?= $settings['currency']; ?>]</th>
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
<!-- add\edit model -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly name="id" />
                    <input type="hidden" readonly name="mode" />
                    <input type="hidden" readonly name="category_id" />
                    <input type="hidden" readonly name="particular_type" />
                    <div class="form-body">
                        <div id="importRow" style="display: none;">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label> Upload Particular(s) <span class="text-danger">*</span>
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
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="particular_name" class="control-label fw-bold col-md-12">
                                            Particular Name <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-12">
                                            <input name="particular_name" id="particular_name" placeholder="Particular Name" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" id="subcategory_id">
                                    <div class="form-group">
                                        <label for="subcategory_id" class="control-label fw-bold col-md-12">Sub category <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="subcategory_id" id="subcategory_id" style="width: 100%;">

                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_typeId" class="control-label fw-bold col-md-12">
                                            Account Type <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-12">
                                            <select name="account_typeId" id="account_typeId" class="form-control select2bs4" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cash_flow_typeId" class="control-label fw-bold col-md-12">
                                            Cash Flow Type <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-12">
                                            <select name="cash_flow_typeId" id="cash_flow_typeId" class="form-control select2bs4" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="particular_status" class="control-label fw-bold col-md-12">
                                            Particular Status <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-12">
                                            <select name="particular_status" id="particular_status" class="form-control select2bs4">
                                                <option value="">-- select --</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="particular_code" class="control-label fw-bold col-md-12">Particular Code</label>
                                        <div class="col-md-12">
                                            <input type="text" name="particular_code" id="particular_code" class="form-control" placeholder="Particular Code" min="0">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="fw-semibold pt-3 text-primary">
                                <input type="checkbox" name="charged" value="yes" id="charged" onclick="isChecked()"> <label class="" for="charged">Set Charges applicable to this Accounting Particular if any.</label>
                            </p>
                            <div id="setChargeRow" style="display: none;">
                                <input type="hidden" id="chargeCounter" name="chargeCounter[0]" value="1">
                                <input type="hidden" id="operation" name="operation[0]" value="create">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Frequency <span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <select name="charge_frequency[0]" id="charge_frequency" class="form-control select2bs4" style="width: 100%;">

                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Charge Method <span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <select name="charge_method[0]" id="charge_method[0]" class="form-control select2bs4" style="width: 100%;">
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
                                                <select name="charge_mode[0]" id="charge_mode" class="form-control select2bs4" style="width: 100%;">

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
                                                <input name="charge[0]" placeholder="Particular Charge" class="form-control amount" type="text" min="" max="">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Charge Limit(Amount)</label>
                                            <div class="col-md-12">
                                                <input name="charge_limits[0]" placeholder="Charge Limit(Amount)" class="form-control amount" type="text" min="" max="">
                                                <i><small class="fw-semibold">Starting Amount(Lower limit) for the charge.</small></i>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="effective_date" class="control-label fw-bold col-sm-12">Effective Date <span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                    <input type="text" name="effective_date[0]" id="effective_date" class="form-control getDatePicker" value="<?= date('Y-m-d'); ?>" placeholder="Effective Date">
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
                                                    <input type="text" name="cutoff_date[0]" id="cutoff_date" class="form-control getDatePicker" placeholder="Cutoff Date">
                                                </div>
                                                <i><small class="fw-semibold">Date for the charge reduction.</small></i>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Charge Status <span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <select name="charge_status[0]" id="charge_status" class="form-control select2bs4">
                                                    <option value="">-- select --</option>
                                                    <option value="Active">Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold col-md-12">Click here to
                                            </label>
                                            <button id="addCharge" class="btn btn-primary btn-block form-control" type="button">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="chargesRow"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSav" onclick="save_particular()" class="btn btn-outline-success">Submit</button>
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
                                    <label for="" class="control-label fw-bold col-md-12">Particular Name</label>
                                    <div class="col-md-12">
                                        <input name="vparticular_name" placeholder="Particular Name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Account Type</label>
                                    <div class="col-md-12">
                                        <input name="vaccount_type" placeholder="Particular Account Type" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Category</label>
                                    <div class="col-md-12">
                                        <input name="vcategory_name" placeholder="Category Name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Subcategory</label>
                                    <div class="col-md-12">
                                        <input name="vsubcategory_name" placeholder="SubCategory Name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Statement</label>
                                    <div class="col-md-12">
                                        <input name="vstatement" placeholder="Statement" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Particular Code</label>
                                    <div class="col-md-12">
                                        <input name="vparticular_code" placeholder="Particular Code" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Cash Flow</label>
                                    <div class="col-md-12">
                                        <input name="vcash_flow_type" placeholder="Cash Flow" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Particular Type</label>
                                    <div class="col-md-12">
                                        <input name="vparticular_type" placeholder="Particular Type" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Part</label>
                                    <div class="col-md-12">
                                        <input name="vpart" placeholder="Part" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Particular Slug</label>
                                    <div class="col-md-12">
                                        <input name="vparticular_slug" placeholder="Particular Slug" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Opening Balance</label>
                                    <div class="col-md-12">
                                        <input name="vopening_balance" placeholder="Opening Balance" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Debit</label>
                                    <div class="col-md-12">
                                        <input name="vdebit" placeholder="Debit" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Credit</label>
                                    <div class="col-md-12">
                                        <input name="vcredit" placeholder="Credit" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Balance</label>
                                    <div class="col-md-12">
                                        <input name="vbalance" placeholder="Balance" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Created At</label>
                                    <div class="col-md-12">
                                        <input name="vcreated_at" class="form-control" placeholder="Created At" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Updated At</label>
                                    <div class="col-md-12">
                                        <input name="vupdated_at" class="form-control" placeholder="Updated At" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="control-label fw-bold col-md-12">Status</label>
                                    <div class="col-md-12">
                                        <input name="vparticular_status" placeholder="Particular Status" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="fw-semibold pt-3 text-primary">
                            Charges applicable to this Accounting Particular if any.
                        </p>
                        <div id="viewChargesRow"></div>
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
    var id = '<?= isset($particular) ? $particular['id'] : 0; ?>';
</script>
<script src="/assets/scripts/accounting/particulars.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/main/auto-updates.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>

<?= $this->endSection() ?>