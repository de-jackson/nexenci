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
            <table id="products" class="table table-sm  table-hover text-nowrap" style="width:100%">
                <thead class="">
                    <tr>
                        <th><input type="checkbox" name="" id="check-all"></th>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Frequency</th>
                        <th>Period</th>
                        <th>Interest</th>
                        <th>Type</th>
                        <th>Min Principal [<?= $settings['currency']; ?>]</th>
                        <th>Max Principal [<?= $settings['currency']; ?>]</th>
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
    <div class="modal-dialog modal-xl">
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
                    <input type="hidden" readonly value="" name="mode" />
                    <div class="form-body">
                        <div id="importRow" style="display: none;">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label> Upload Products <span class="text-danger">*</span>
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
                                        <label class="control-label fw-bold col-md-12">
                                            Product Name <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-12">
                                            <input name="product_name" placeholder="Product Name" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">
                                            Product Code
                                        </label>
                                        <div class="col-md-12">
                                            <input name="product_code" placeholder="Product Code" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label fw-bold col-md-12" for="repayment_period">Repayment Period and Frequency<span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <select class="form-control select2bs4" name="repayment_freq" id="repayments" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" name="repayment_period" id="repayment_period" class="form-control" placeholder="Repayment Period">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">
                                            Interest Type <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="interest_type" id="interesttypes" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label fw-bold col-md-12">
                                        Interest Rate(%) <span class="text-danger">*</span>
                                    </label>
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
                                <div class="col-md-4">
                                    <label class="control-label fw-bold col-md-12">Loan Period and Frequency <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <select class="form-control select2bs4" name="loan_frequency" id="loan_frequency" style="width: 100%;">
                                                <option value="">--Select---</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" name="loan_period" id="loan_period" class="form-control" placeholder="Loan Period">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Product Principal Particular <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="principal_particular_id" id="principal_particular_id" class="form-control select2bs4 particular_id">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Product Interest Particular <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="interest_particular_id" id="interest_particular_id" class="form-control select2bs4 particular_id">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Min Principal [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="min_principal" placeholder="Min Principal [<?= $settings['currency']; ?>]" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Max Principal [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="max_principal" placeholder="Max Principal [<?= $settings['currency']; ?>]" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">
                                            Status <span class="text-danger">*</span>
                                        </label>
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

                            <div class="row gx-3 gy-2 align-items-center mt-0 mb-2">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Min Savings Balance Type(Application)</label>
                                        <div class="col-md-12">
                                            <select name="min_savings_balance_type_application" id="min_savings_balance_type_application" class="form-control select2bs4">
                                                <option value="">-- choose type --</option>
                                                <option value="amount">Amount</option>
                                                <option value="rate">Rate(%)</option>
                                                <option value="multiplier">Multiplier(*)</option>
                                            </select>
                                            <i><small class="fw-semibold">Min Client Savings balance of the Principal(Application).</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Application Min Account Balance [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="min_savings_balance_application" placeholder="Min Account Balance [<?= $settings['currency']; ?>]" class="form-control amount" type="text">
                                            <i><small class="fw-semibold">Min Savings balance at & during Application.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Max Account Balance Type(Application)</label>
                                        <div class="col-md-12">
                                            <select name="max_savings_balance_type_application" id="max_savings_balance_type_application" class="form-control select2bs4">
                                                <option value="">-- choose type --</option>
                                                <option value="amount">Amount</option>
                                                <option value="rate">Rate(%)</option>
                                                <option value="multiplier">Multiplier(*)</option>
                                            </select>
                                            <i><small class="fw-semibold">Max Client Savings balance of the Principal(Application).</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Application Max Account Balance [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="max_savings_balance_application" placeholder="Application Max Account Balance [<?= $settings['currency']; ?>]" class="form-control amount" type="text">
                                            <i><small class="fw-semibold">Max Client Savings balance at & during Application.</small></i>
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
                                            <select name="min_savings_balance_type_disbursement" id="min_savings_balance_type_disbursement" class="form-control select2bs4">
                                                <option value="">-- choose type --</option>
                                                <option value="amount">Amount</option>
                                                <option value="rate">Rate(%)</option>
                                                <option value="multiplier">Multiplier(*)</option>
                                            </select>
                                            <i><small class="fw-semibold">Min Client Savings balance of the Principal(Disbursement).</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Disbursement Min Account Balance [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="min_savings_balance_disbursement" placeholder="Min Account Balance [<?= $settings['currency']; ?>]" class="form-control amount" type="text">
                                            <i><small class="fw-semibold">Min Savings balance at & during Disbursement.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Max Account Balance Type(Disbursement)</label>
                                        <div class="col-md-12">
                                            <select name="max_savings_balance_type_disbursement" id="max_savings_balance_type_disbursement" class="form-control select2bs4">
                                                <option value="">-- choose type --</option>
                                                <option value="amount">Amount</option>
                                                <option value="rate">Rate(%)</option>
                                                <option value="multiplier">Multiplier(*)</option>
                                            </select>
                                            <i><small class="fw-semibold">Max Client Savings balance of the Principal(Disbursement).</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Disbursement Max Account Balance [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="max_savings_balance_disbursement" placeholder="Disbursement Max Account Balance [<?= $settings['currency']; ?>]" class="form-control amount" type="text">
                                            <i><small class="fw-semibold">Max Client Savings balance at & during Disbursement.</small></i>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row gx-3 gy-2 align-items-center mt-0 mb-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Product Description <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea name="product_desc" placeholder="Product Description" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0 mb-2">
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
                        <p class="fw-semibold text-primary mb-3">Select Charges applicable to this Loan Product</p>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <?php if (count($charges) > 0) :
                                foreach ($charges as $key => $charge) :
                            ?>
                                    <div class="col-md task-card">
                                        <div class="card custom-card task-pending-card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between flex-wrap gap-2">
                                                    <div>
                                                        <input type="hidden" class="form-control charge_id" id="charge_id<?= $charge['particular_id'] ?>" name="charge_id[<?= $key ?>]" value="create" placeholder="Charge ID" readonly>
                                                        <p class="fw-semibold mb-3 d-flex align-items-center">
                                                            <span class="form-check form-check-md form-switch">
                                                                <input type="checkbox" name="product_charges[<?= $key; ?>]" value="<?= $charge['particular_id'] ?>" id="product_charge<?= $charge['particular_id'] ?>" class="form-check-input form-checked-success product_charge" onclick="setProductCharges(<?= $charge['particular_id'] ?>)">
                                                            </span>&nbsp;
                                                            <?= $charge['particular_name']; ?>
                                                            <span class="help-block text-danger"></span>
                                                        </p>
                                                        <div class="col-md mb-3">
                                                            <div class="form-group">
                                                                <label for="charges_types<?= $charge['particular_id'] ?>" class="fw-bold">Charges Type</label>
                                                                <select class="select2bs4 form-select charges_types" id="charges_types<?= $charge['particular_id'] ?>" name="charges_types[<?= $key ?>]" disabled>
                                                                    <option value="">-- select --</option>
                                                                    <option value="Amount" <?= (strtolower($charge['charge_method']) == 'amount') ? 'selected' : '' ?>>Amount</option>
                                                                    <option value="Percent" <?= (strtolower($charge['charge_method']) == 'percent') ? 'selected' : '' ?>>Percent</option>
                                                                </select>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md mb-3">
                                                            <div class="form-group">
                                                                <label for="charges_fees<?= $charge['particular_id'] ?>" class="fw-bold">Charges Fees</label>
                                                                <input type="text" class="form-control charges_fees" id="charges_fees<?= $charge['particular_id'] ?>" name="charges_fees[<?= $key ?>]" value="<?= isset($charge['charge']) ? $charge['charge'] : '' ?>" placeholder="Charges Fees" disabled>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md mb-3">
                                                            <div class="form-group">
                                                                <label for="charges_reduction<?= $charge['particular_id'] ?>" class="fw-bold">Deduction Mode</label>
                                                                <select class="select2bs4 form-select charges_reduction" id="charges_reduction<?= $charge['particular_id'] ?>" name="charges_reduction[<?= $key; ?>]" disabled>
                                                                    <option value="">-- select --</option>
                                                                    <option value="Auto" <?= (strtolower($charge['charge_mode']) == 'auto') ? 'selected' : '' ?>>Auto</option>
                                                                    <option value="Manual" <?= (strtolower($charge['charge_mode']) == 'manual') ? 'selected' : '' ?>>Manual</option>
                                                                </select>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;
                            else: ?>
                                <p class="fw-semibold text-primary text-center mb-3">No Charges Particulars For Loans</p>
                            <?php endif; ?>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Product Name</label>
                                    <div class="col-md-12">
                                        <input name="product_name" placeholder="Product Name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Product Code
                                    </label>
                                    <div class="col-md-12">
                                        <input name="product_code" placeholder="Product Code" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Loan Period and Frequency</label>
                                    <div class="col-md-12">
                                        <input class="form-control" name="loan_period" placeholder="Loan Period and Frequency" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Repayment Period and Frequency</label>
                                    <div class="col-md-12">
                                        <input name="repayment_period" placeholder="Repayment Period" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Interest Rate(%)</label>
                                    <div class="col-md-12">
                                        <input name="interest_rate" placeholder="interest rate" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Interest Type</label>
                                    <div class="col-md-12">
                                        <input name="interest_type" placeholder="Interest Type" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Product Principal Particular</label>
                                    <div class="col-md-12">
                                        <input name="principal_particular" id="principal_particular" placeholder="Product Principal Particular" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Product Interest Particular</label>
                                    <div class="col-md-12">
                                        <input name="interest_particular" id="interest_particular" placeholder="Product Interest Particular" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Min Principal [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="min_principal" placeholder="Min Principal [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Max Principal [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="max_principal" placeholder="Max Principal [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Status</label>
                                    <div class="col-md-12">
                                        <input class="form-control" placeholder="Status" name="status" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0 mb-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Min Savings Balance Type(Application)</label>
                                    <div class="col-md-12">
                                        <input name="min_savings_balance_type_application" id="min_savings_balance_type_application" class="form-control" placeholder="Min Savings Balance Type(Application)" type="text" readonly>
                                        <i><small class="fw-semibold">Min Client Savings balance of the Principal(Application).</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Application Min Account Balance [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="min_savings_balance_application" placeholder="Application Min Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                        <i><small class="fw-semibold">Min Savings balance at & during Application.</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Max Account Balance Type(Application)</label>
                                    <div class="col-md-12">
                                        <input name="max_savings_balance_type_application" id="max_savings_balance_type_application" class="form-control" placeholder="Max Account Balance Type(Application)" type="text" readonly>
                                        <i><small class="fw-semibold">Max Client Savings balance of the Principal(Application).</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Application Max Account Balance [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="max_savings_balance_application" placeholder="Application Max Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
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
                                        <input name="min_savings_balance_type_disbursement" id="min_savings_balance_type_disbursement" class="form-control" placeholder="Min Savings Balance Type(Disbursement)" type="text" readonly>
                                        <i><small class="fw-semibold">Min Client Savings balance of the Principal(Disbursement).</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Disbursement Min Account Balance [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="min_savings_balance_disbursement" placeholder="Disbursement Min Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                        <i><small class="fw-semibold">Min Savings balance at & during Disbursement.</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Max Account Balance Type(Disbursement)</label>
                                    <div class="col-md-12">
                                        <input name="max_savings_balance_type_disbursement" id="max_savings_balance_type_disbursement" class="form-control" placeholder="Max Account Balance Type(Disbursement)" type="text" readonly>
                                        <i><small class="fw-semibold">Max Client Savings balance at & during Disbursement.</small></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Disbursement Max Account Balance [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input name="max_savings_balance_disbursement" placeholder="Disbursement Max Account Balance [<?= $settings['currency']; ?>]" class="form-control" type="text" readonly>
                                        <i><small class="fw-semibold">Max Client Savings balance at & during Disbursement.</small></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Product Description</label>
                                    <div class="col-md-12">
                                        <textarea name="product_desc" placeholder="Product Description" class="form-control" readonly>
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Product Features</label>
                                    <div class="col-md-12">
                                        <textarea name="product_features" placeholder="Product Features" class="form-control" id="seeSummernote" readonly>
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p id="loanProductCharges" class="fw-semibold text-primary mb-3">Charges applicable to this Loan Product</p>
                        <div class="row gx-3 gy-2 align-items-center mt-0" id="vcharges">

                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Created At</label>
                                    <div class="col-md-12">
                                        <input name="created_at" placeholder="created at" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Updated At</label>
                                    <div class="col-md-12">
                                        <input class="form-control" name="updated_at" readonly>
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
    var particularLoanCharges = <?= json_encode($charges); ?>;
</script>
<script src="/assets/scripts/loans/products/index.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/loans/index.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/auto-updates.js"></script>
<?= $this->endSection() ?>