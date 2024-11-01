<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ucfirst($menu) ?> <?= ucfirst($title) ?> Information
            </div>
        </div>
        <div class="card-body">
            <table id="<?= strtolower($menu) ?>-products" class="table table-sm  table-hover text-nowrap" style="width:100%">
                <thead class="">
                    <tr>
                        <th><input type="checkbox" name="" id="check-all"></th>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Min Account Balance [<?= $settings['currency']; ?>]</th>
                        <th>Max Account Balance [<?= $settings['currency']; ?>]</th>
                        <th>Min Per Entry [<?= $settings['currency']; ?>]</th>
                        <th>Max Per Entry [<?= $settings['currency']; ?>]</th>
                        <th>Period</th>
                        <th>Rate</th>
                        <th>Status</th>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly value="" name="id" />
                    <input type="hidden" readonly value="" name="product_type" />
                    <input type="hidden" readonly value="" name="mode" />
                    <div class="form-body">
                        <div id="importRow" style="display: none;">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label> Upload Product(s)
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Product Name <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input name="product_name" placeholder="Product Name" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Product Code</label>
                                        <div class="col-md-12">
                                            <input name="product_code" id="product_code" placeholder="Product Code" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Product Savings Particular <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="savings_particular_id" id="savings_particular_id" class="form-control select2bs4 particular_id">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Withdraw Charges Particular <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="withdrawCharges_particular_id" id="withdrawCharges_particular_id" class="form-control select2bs4 particular_id">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-6">
                                    <div class="">
                                        <label class="control-label fw-bold col-md-12">Interest Rate(%)</label>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <input name="interest_rate" placeholder="interest rate" class="form-control" type="text">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <select class="form-control select2bs4" name="interest_period" id="interest_period" style="width: 100%;">
                                                    <option value="">--Select---</option>
                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Interest Type</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="interest_type" id="interesttypes" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Savings Frequency</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="product_frequency" id="repayments" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Savings Period [Frequency]</label>
                                        <div class="col-md-12">
                                            <input name="product_period" placeholder="Savings Period [Frequency]" class="form-control" type="number" min="0">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Status</label>
                                        <div class="col-md-12">
                                            <select name="status" id="status" class="form-control select2bs4">
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Min Per Saving [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="min_per_entry" placeholder="Min Per Saving [<?= $settings['currency']; ?>]" class="form-control amount" type="text">
                                            <i><small class="fw-semibold">Min Amount Per Single Transaction.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Max Per Saving [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="max_per_entry" placeholder="Max Per Saving [<?= $settings['currency']; ?>]" class="form-control amount" type="text" min="0">
                                            <i><small class="fw-semibold">Max Amount Per Single Transaction.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Minimum Account Balance [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="min_account_balance" placeholder="Minimum Account Balance [<?= $settings['currency']; ?>]" class="form-control amount" type="text" min="0">
                                            <i><small class="fw-semibold">Min Client Savings balance.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Maximum Account Balance [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="max_account_balance" placeholder="Maximum Account Balance [<?= $settings['currency']; ?>]" class="form-control amount" type="text" min="0">
                                            <i><small class="fw-semibold">Max Client Savings Account balance.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row gx-3 gy-2 align-items-center mt-0 mb-2">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Min Savings Balance Type(Application)</label>
                                        <div class="col-md-12">
                                            <select name="application_min_savings_balance_type" id="application_min_savings_balance_type" class="form-control select2bs4">
                                                <option value="">-- choose type --</option>
                                                <option value="amount">Amount</option>
                                                <option value="rate">Rate(%)</option>
                                                <option value="multiplier">Multiplier(*)</option>
                                            </select>
                                            <i><small class="fw-semibold">Min Client Savings balance Type(Application).</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Application Min Account Balance [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="application_min_savings_balance" placeholder="Application Min Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="number" min="0">
                                            <i><small class="fw-semibold">Min Client Savings balance at & during Application.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Max Savings Balance Type(Application)</label>
                                        <div class="col-md-12">
                                            <select name="application_max_savings_balance_type" id="application_max_savings_balance_type" class="form-control select2bs4">
                                                <option value="">-- choose type --</option>
                                                <option value="amount">Amount</option>
                                                <option value="rate">Rate(%)</option>
                                                <option value="multiplier">Multiplier(*)</option>
                                            </select>
                                            <i><small class="fw-semibold">Max Client Savings balance Type(Application).</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Application Max Account Balance [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="application_max_savings_balance" placeholder="Application Max Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="number" min="0">
                                            <i><small class="fw-semibold">Max Client Savings balance at Application.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0 mb-2">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Min Savings Balance Type(Disbursement)</label>
                                        <div class="col-md-12">
                                            <select name="disbursement_min_savings_balance_type" id="disbursement_min_savings_balance_type" class="form-control select2bs4">
                                                <option value="">-- choose type --</option>
                                                <option value="amount">Amount</option>
                                                <option value="rate">Rate(%)</option>
                                                <option value="multiplier">Multiplier(*)</option>
                                            </select>
                                            <i><small class="fw-semibold">Min Client Savings balance Type(Disbursement).</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Disbursement Min Account Balance [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="disbursement_min_savings_balance" placeholder="Disbursement Min Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="number" min="0">
                                            <i><small class="fw-semibold">Min Client Savings balance at & during Disbursement.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Max Savings Balance Type(Disbursement)</label>
                                        <div class="col-md-12">
                                            <select name="disbursement_max_savings_balance_type" id="disbursement_max_savings_balance_type" class="form-control select2bs4">
                                                <option value="">-- choose type --</option>
                                                <option value="amount">Amount</option>
                                                <option value="rate">Rate(%)</option>
                                                <option value="multiplier">Multiplier(*)</option>
                                            </select>
                                            <i><small class="fw-semibold">Max Client Savings balance Type(Disbursement).</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Disbursement Max Account Balance [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="disbursement_max_savings_balance" placeholder="Disbursement Max Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="number" min="0">
                                            <i><small class="fw-semibold">Max Client Savings balance at & during Disbursement.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Product Description</label>
                                        <div class="col-md-12">
                                            <textarea name="product_desc" placeholder="Product Description" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Product Features</label>
                                        <div class="col-md-12">
                                            <textarea name="product_features" placeholder="Product Features" class="form-control" id="summernote"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSav" onclick="save_product()" class="btn btn-outline-success">Submit</button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Product Name</label>
                                    <div class="col-md-12">
                                        <input name="product_name" placeholder="Product Name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Product Code</label>
                                    <div class="col-md-12">
                                        <input name="product_code" id="product_code" placeholder="Product Code" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Product Savings Particular</label>
                                    <div class="col-md-12">
                                        <input name="savings_particular" id="savings_particular" placeholder="Product Savings Particular" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Withdraw Charges Particular</label>
                                    <div class="col-md-12">
                                        <input name="withdrawCharges_particular" id="withdrawCharges_particular" placeholder="Withdraw Charges Particular" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="">
                                    <label class="control-label fw-bold col-md-12">Interest Rate(%)</label>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <input name="interest_rate" placeholder="interest rate" class="form-control" type="text" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input name="interest_period" placeholder="Interest Rate(%)" class="form-control" id="interest_period" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Interest Type</label>
                                    <div class="col-md-12">
                                        <input name="interest_type" placeholder="Interest Type" class="form-control" id="interesttypes" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Savings Frequency</label>
                                    <div class="col-md-12">
                                        <input class="form-control" placeholder="Savings Frequency" name="product_frequency" id="repayments" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Savings Period [Frequency]</label>
                                    <div class="col-md-12">
                                        <input name="product_period" placeholder="Savings Period [Frequency]" class="form-control" type="number" min="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Status</label>
                                    <div class="col-md-12">
                                        <input name="status" id="status" class="form-control" placeholder="Status" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Min Per Saving [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="min_per_entry" placeholder="Min Per Saving [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                        <i><small class="fw-semibold">Min Amount Per Single Transaction.</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Max Per Saving [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="max_per_entry" placeholder="Max Per Saving [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                        <i><small class="fw-semibold">Max Amount Per Single Transaction.</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Minimum Account Balance [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="min_account_balance" placeholder="Minimum Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                        <i><small class="fw-semibold">Minimum Client Savings balance.</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Maximum Account Balance [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="max_account_balance" placeholder="Maximum Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                        <i><small class="fw-semibold">Max Client Savings balance.</small></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row gx-3 gy-2 align-items-center mt-0 mb-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Min Savings Balance Type(Application)</label>
                                    <div class="col-md-12">
                                        <input name="application_min_savings_balance_type" id="application_min_savings_balance_type" class="form-control" placeholder="Min Savings Balance Type(Application)" type="text" readonly>
                                        <i><small class="fw-semibold">Min Client Savings  balance Type(Application).</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Application Min Account Balance [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="application_min_savings_balance" placeholder="Application Min Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                        <i><small class="fw-semibold">Min Client Savings balance at & during Application.</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Max Savings Balance Type(Application)</label>
                                    <div class="col-md-12">
                                        <input name="application_max_savings_balance_type" id="application_max_savings_balance_type" class="form-control" placeholder="Max Savings Balance Type(Application)" type="text" readonly>
                                        <i><small class="fw-semibold">Max Client Savings balance type(Application).</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Application Max Account Balance [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="application_max_savings_balance" placeholder="Application Max Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                        <i><small class="fw-semibold">Max Client Savings balance at & during Application.</small></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0 mb-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Min Savings Balance Type(Disbursement)</label>
                                    <div class="col-md-12">
                                        <input name="disbursement_min_savings_balance_type" id="disbursement_min_savings_balance_type" class="form-control" placeholder="Min Savings Balance Type(Disbursement)" type="text" readonly>
                                        <i><small class="fw-semibold">Min Client Savings balance Type(Disbursement).</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Disbursement Min Account Balance [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="disbursement_min_savings_balance" placeholder="Disbursement Min Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                        <i><small class="fw-semibold">Min Client Savings balance at & during Disbursement.</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Max Savings Balance Type(Disbursement)</label>
                                    <div class="col-md-12">
                                        <input name="disbursement_max_savings_balance_type" id="disbursement_max_savings_balance_type" class="form-control" placeholder="Max Savings Balance Type(Disbursement)" type="text" readonly>
                                        <i><small class="fw-semibold">Max Client Savings balance type(Disbursement).</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Disbursement Max Account Balance [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="disbursement_max_savings_balance" placeholder="Disbursement Max Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                        <i><small class="fw-semibold">Max Client Savings balance at & during Disbursement.</small></i>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Product Description</label>
                                    <div class="col-md-12">
                                        <textarea name="product_desc" placeholder="Product Description" class="form-control" readonly>></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Product Features</label>
                                    <div class="col-md-12">
                                        <textarea name="product_features" placeholder="Product Features" class="form-control" id="newSummernote"></textarea>
                                        <span class="help-block text-danger"></span>
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
    var id = '<?= isset($product) ? $product['id'] : 0; ?>';
    var product = '<?= strtolower($menu) ?>';
    var particular_accountTypeId = (product =='savings') ? 12 : 3;
    var charge_accountTypeId = (product =='savings') ? 20 : 19;
    var particularLoanCharges = <?= json_encode($charges); ?>;
</script>
<script src="/assets/scripts/products/index.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/loans/index.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/auto-updates.js"></script>
<?= $this->endSection() ?>