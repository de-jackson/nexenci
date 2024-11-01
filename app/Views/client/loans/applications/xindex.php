<?= $this->extend("layout/client"); ?>

<?= $this->section("content"); ?>
<!-- Content Header (Page header) -->
<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <h1 class="page-title fw-semibold fs-18 mb-0">Loan</h1>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript: void(0)" class="text-danger" onclick="history.back(-1);"><i class="fas fa-circle-left"></i> Back</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)"><?= ucfirst($menu) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= ucfirst($title) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Start::row-2 -->
<div class="row">
    <div class="col-xl-12">
        <div class="card border custom-card">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Loan Application
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <ul class="nav nav-tabs justify-content-center mb-5 tab-style-3" id="myTab2" role="tablist">
                                <!--  step-1 tab active -->
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active step-1 py-1 disabled" id="step1-tab" data-bs-toggle="tab" data-bs-target="#step1-tab-pane" type="button" role="tab" aria-controls="step1-tab-pane" aria-selected="true">Terms</button>
                                </li>
                                <!-- step-2 tab -->
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link step-2 py-1 disabled" id="step2-tab" data-bs-toggle="tab" data-bs-target="#step2-tab-pane" type="button" role="tab" aria-controls="step2-tab-pane" aria-selected="false">Security</button>
                                </li>
                                <!-- step-3 tab -->
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link step-3 py-1 disabled" id="step3-tab" data-bs-toggle="tab" data-bs-target="#step3-tab-pane" type="button" role="tab" aria-controls="step3-tab-pane" aria-selected="false">Income</button>
                                </li>
                                <!-- step-4 tab -->
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link step-4 py-1 disabled" id="step4-tab" data-bs-toggle="tab" data-bs-target="#step4-tab-pane" type="button" role="tab" aria-controls="step4-tab-pane" aria-selected="false">Others</button>
                                </li>
                            </ul>
                            <form id="form" class="form-horizontal" autocomplete="off">
                                <?= csrf_field() ?>
                                <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                                <input type="hidden" name="id" readonly />
                                <input type="hidden" name="application_id" readonly />
                                <input type="hidden" name="mode" readonly />
                                <input type="hidden" name="step_no" readonly />
                                <div class="form-body">
                                    <!-- import applications -->
                                    <div id="importRow" style="display: none;">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label>Branch Name</label>
                                                        <select id="branchID" name="branch_id" class="form-control select2 branch_id">
                                                        </select>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12" for="loan_product_id">Loan Product</label>
                                                    <div class="col-md-12">
                                                        <select name="loan_product_id" id="product_id" class="form-control select2bs4">
                                                        </select>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label> Upload Application(s)
                                                            <span class="text-white"> ( <span class="text-danger"> CSV Format </span> ) </span>
                                                        </label>
                                                        <input type="file" name="file" class="form-control" accept=".csv">
                                                        <span class="help-block text-danger"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- fill application form -->
                                    <div id="formRow">
                                        <!-- steps tab contents -->
                                        <div class="tab-content" id="myTabContent1">
                                            <!--  step 1 content -->
                                            <div class="tab-pane fade show active text-muted" id="step1-tab-pane" role="tabpanel" aria-labelledby="step1-tab" tabindex="0">
                                                <!-- CLIENT BIO -->
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-3 text-center">
                                                        <label class="control-label fw-bold col-md-12">Profile Photo</label>
                                                        <div class="form-group" id="photo-preview">
                                                            <div class="col-md-12">
                                                                (No photo)
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="application_date" class="control-label fw-bold col-sm-12">Application Date</label>
                                                                    <div class="col-sm-12">
                                                                        <div class="input-group">
                                                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                            <input type="text" name="application_date" id="application_date" class="form-control getDatePicker" value="<?= date('Y-m-d'); ?>" placeholder="Application Date">
                                                                        </div>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label fw-bold col-md-12" for="client_id">Client Name</label>
                                                                    <div class="col-md-12">
                                                                        <select name="client_id" id="client_id" class="form-control select2bs4 client_id">
                                                                        </select>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label fw-bold col-md-12" for="account_no">Account No.</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="account_no" id="account_no" class="form-control" placeholder="Account No." readonly>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label fw-bold col-md-12" for="mobile">Contact No.</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Contact No." readonly>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 text-center">
                                                        <label class="control-label fw-bold col-md-12">Applicant ID(Front)</label>
                                                        <div class="form-group" id="id-preview">
                                                            <div class="col-md-12">
                                                                (No photo)
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- LOAN TERMS -->
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12" for="principal">Loan Principal [<?= $settings['currency']; ?>]</label>
                                                            <div class="col-md-12">
                                                                <input name="principal" id="principal" placeholder="Loan Principal [<?= $settings['currency']; ?>]" class="form-control" type="number" onkeyup="total_applicationCharges(this.value)" min="0">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12" for="product_id">Loan Product</label>
                                                            <div class="col-md-12">
                                                                <select name="product_id" id="product_id" class="form-control select2bs4">
                                                                </select>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12" for="interest_rate">Interest Rate</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="interest_rate" id="interest_rate" class="form-control" placeholder="Interest Rate" readonly>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12" for="interest_type">Interest Method</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="interest_type" id="interest_type" class="form-control" placeholder="Interest Method" readonly>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12" for="repayment_period">Repayment Period</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="repayment_period" id="repayment_period" class="form-control" placeholder="Repayment Period" readonly>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12" for="repayment_freq">Repayment Freq.</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="repayment_freq" id="repayment_freq" class="form-control" placeholder="Repayment Freq." readonly>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12" for="purpose">Loan Purpose</label>
                                                            <div class="col-md-12">
                                                                <textarea name="purpose" id="purpose" placeholder="Loan Purpose" class="form-control"></textarea>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- charges -->
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Application Charges List</label>
                                                            <div class="col-md-12">
                                                                <ul class="list-group">
                                                                    <?php
                                                                    if (count($charges['particulars']) > 0) :
                                                                        foreach ($charges['particulars'] as $charge) :
                                                                    ?>
                                                                            <li class="list-group-item">
                                                                                <div class="d-flex align-items-center">
                                                                                    <div>
                                                                                        <span class="fs-15">
                                                                                            <i class="fas fa-money-bill-wave"></i>
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="ms-2">
                                                                                        <b><?= $charge['particular_name'] ?></b>, charge
                                                                                        <i>
                                                                                            <?= number_format($charge['charge']); ?>
                                                                                            <?= (strtolower($charge['charge_method']) == 'amount') ? $settings['currency'] : '% of the principal'; ?>
                                                                                        </i>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                    <?php endforeach;
                                                                    endif;
                                                                    ?>
                                                                </ul>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label fw-bold col-md-12">Overall Charges</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="overall_charges" id="charges" class="form-control" placeholder="Overall Charges" value="<?= count($charges['particularIDs']); ?>" readonly>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label fw-bold col-md-12">Total Charges</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="total_charges" id="total_charges" class="form-control" placeholder="Total Charges" readonly>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label fw-bold col-md-12">Reduct Charges</label>
                                                                    <div class="col-md-12">
                                                                        <select name="reduct_charges" id="reduct_charges" class="form-control select2bs4">
                                                                            <option value="Principal">From Principal</option>
                                                                            <option value="Savings">From Savings</option>
                                                                        </select>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- step-2 content -->
                                            <div class="tab-pane fade text-muted" id="step2-tab-pane" role="tabpanel" aria-labelledby="step2-tab" tabindex="0">
                                                <!-- LOAN SECURITY -->
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <p class="col-md-12">Loan Security</p>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12" for="security_item">Security Item</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="security_item" id="security_item" class="form-control" placeholder="Security Item">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12" for="est_value">Estimated Value [<?= $settings['currency']; ?>]</label>
                                                            <div class="col-md-12">
                                                                <input type="number" name="est_value" id="est_value" class="form-control" placeholder="Estimated Value [<?= $settings['currency']; ?>]" min="0">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-sm-12">
                                                                Collateral Files (<span class="text-danger">Images/PDF</span>)
                                                            </label>
                                                            <div class="col-sm-12">
                                                                <input type="file" name="collateral[]" class="form-control" multiple>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12" for="security_info">Details</label>
                                                            <div class="col-md-12">
                                                                <textarea name="security_info" id="addSummernote" class="form-control" placeholder="Details"></textarea>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- REFEREES -->
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <p class="col-md-12 text-bold">References</p>
                                                    <hr>
                                                    <div class="col-md-12">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="ref_name">Full Name</label>
                                                                    <input type="text" name="ref_name" id="ref_name" class="form-control" placeholder="Full Name">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="relation">Relationship</label>
                                                                    <select class="form-control select2bs4" name="ref_relation" id="relationships" style="width: 100%;">
                                                                    </select>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="ref_job">Occupation</label>
                                                                    <input type="text" name="ref_job" id="ref_job" class="form-control" placeholder="Occupation">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="ref_contact">Contact 1</label>
                                                                    <input type="tel" name="ref_contact" id="ref_contact" class="form-control" placeholder="Contact 1">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="ref_alt_contact">Contact 2</label>
                                                                    <input type="tel" name="ref_alt_contact" id="ref_alt_contact" class="form-control" placeholder="Contact 2">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="ref_email">Email</label>
                                                                    <input type="email" name="ref_email" id="ref_email" class="form-control" placeholder="Email">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="ref_address2">Address</label>
                                                                    <textarea name="ref_address" id="ref_address2" class="form-control" placeholder="Address"></textarea>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="ref_name2">Full Name</label>
                                                                    <input type="text" name="ref_name2" id="ref_name2" class="form-control" placeholder="Full Name">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="relation2">Relationship</label>
                                                                    <select class="form-control select2bs4" name="ref_relation2" id="relation2" style="width: 100%;">
                                                                        <option value="">-- select --</option>
                                                                        <?php
                                                                        if (count($relations) > 0) :
                                                                            foreach ($relations as $key => $value) : ?>
                                                                                <option value="<?= $key; ?>">
                                                                                    <?= $value; ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </select>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="ref_job2">Occupation</label>
                                                                    <input type="text" name="ref_job2" id="ref_job2" class="form-control" placeholder="Occupation">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="ref_contact2">Contact 1</label>
                                                                    <input type="tel" name="ref_contact2" id="ref_contact2" class="form-control" placeholder="Contact 1">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="ref_alt_contact2">Contact 2</label>
                                                                    <input type="tel" name="ref_alt_contact2" id="ref_alt_contact2" class="form-control" placeholder="Contact 2">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="ref_email2">Email</label>
                                                                    <input type="email" name="ref_email2" id="ref_email2" class="form-control" placeholder="Email">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="ref_address2">Address</label>
                                                                    <textarea name="ref_address2" id="ref_address2" class="form-control" placeholder="Address"></textarea>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- step-3 content -->
                                            <div class="tab-pane fade text-muted" id="step3-tab-pane" role="tabpanel" aria-labelledby="step3-tab" tabindex="0">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <p class="col-md-12 text-bold">Client Financial Data</p>
                                                    <!-- INCOME -->
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group text-center">
                                                                    <p class="col-md-12">Income</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="salary" class="col-md-12 form-label">Net Salary</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="net_salary" class="form-control" id="salary" placeholder="Net Salary" min="0" onkeyup="totals()">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="farming" class="col-md-12 form-label">Farming</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="farming" class="form-control" id="farming" placeholder="Farming" min="0" onkeyup="totals()">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="business" class="col-md-12 form-label">Business</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="business" class="form-control" id="business" placeholder="Business" min="0" onkeyup="totals()">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="others" class="col-md-12 form-label">Others</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="others" class="form-control" id="others" placeholder="Others" min="0" onkeyup="totals()">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="" class="col-md-12 form-label"></label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="" class="form-control" id="" placeholder="" min="0" onkeyup="totals()" disabled>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="total" class="col-md-12 form-label">Total</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="income_total" class="form-control" id="total" placeholder="Total" min="0" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- EXPENSES -->
                                                    <div class="col-md-6 border-left border-1">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group text-center">
                                                                    <p class="col-md-12">Expenses</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="rent" class="col-md-12 form-label">Rent</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="rent" class="form-control" id="rent" placeholder="Rent" min="0" onkeyup="totals()">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="education" class="col-md-12 form-label">Education</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="education" class="form-control" id="education" placeholder="Education" min="0" onkeyup="totals()">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="medical" class="col-md-12 form-label">Medical</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="medical" class="form-control" id="medical" placeholder="Medical" min="0" onkeyup="totals()">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="transport" class="col-md-12 form-label">Transport</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="transport" class="form-control" id="transport" placeholder="Transport" min="0" onkeyup="totals()">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="exp_others" class="col-md-12 form-label">Others</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="exp_others" class="form-control" id="exp_others" placeholder="Others" min="0" onkeyup="totals()">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="exp_total" class="col-md-12 form-label">Total</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="exp_total" class="form-control" id="exp_total" placeholder="Total" min="0" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- FINANCIAL STATUS -->
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label for="difference" class="col-md-12 form-label">Difference</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="difference" class="form-control" id="difference" placeholder="Difference" min="0" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label for="dif_status" class="col-md-12 form-label">Difference
                                                                    Status</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="dif_status" placeholder="Difference Status" id="dif_status">
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- FINANCIAL PROOF -->
                                                    <p class="col-md-12">
                                                        <i><b>NOTE:</b> Upload receipts/invoices/slips where applicable</i>
                                                    </p>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="income">
                                                                Income Files (<span class="text-danger">Images/PDF</span>)
                                                            </label>
                                                            <div class="col-md-12">
                                                                <input type="file" name="income[]" class="form-control" multiple>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="expense">
                                                                Expenses Files (<span class="text-danger">Images/PDF</span>)
                                                            </label>
                                                            <input type="file" name="expense[]" class="form-control" multiple>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- step-4 content -->
                                            <div class="tab-pane fade text-muted" id="step4-tab-pane" role="tabpanel" aria-labelledby="step4-tab" tabindex="0">
                                                <!-- CLIENT OTHER ACCOUNTS -->
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <p class="col-md-12">Client's Accounts In Other Financial Institutions</p>
                                                    <label class="control-label fw-bold col-4" for="instution">Institution</label>
                                                    <label class="control-label fw-bold col-4" for="branch">Branch</label>
                                                    <label class="control-label fw-bold col-4" for="state">Account Type</label>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="institute_name" id="instution" class="form-control" placeholder="Institution">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="institute_branch" id="branch" class="form-control" placeholder="Branch">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <select name="account_type" id="state" class="form-control select2bs4">
                                                                    <option value="">-- select --</option>
                                                                    <option value="Credit">Credit</option>
                                                                    <option value="Debit">Debit</option>
                                                                </select>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="institute_name2" id="instution2" class="form-control" placeholder="Institution">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="institute_branch2" id="branch2" class="form-control" placeholder="Branch">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <select name="account_type2" id="state2" class="form-control select2bs4">
                                                                    <option value="">-- select --</option>
                                                                    <option value="Credit">Credit</option>
                                                                    <option value="Debit">Debit</option>
                                                                </select>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- CLIENT OTHER LOANS -->
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <p class="col-md-12">Client's Loans In Other Financial Institutions</p>
                                                    <label class="control-label fw-bold col-3" for="amt_advance">Amount Advance [<?= $settings['currency']; ?>]</label>
                                                    <label class="control-label fw-bold col-3" for="date_advance">Date Advance</label>
                                                    <label class="control-label fw-bold col-3" for="loan_duration">Loan Duration</label>
                                                    <label class="control-label fw-bold col-3" for="amt_outstanding">Outstanding Amount [<?= $settings['currency']; ?>]</label>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="number" name="amt_advance" id="amt_advance" class="form-control" placeholder="Amount Advance [<?= $settings['currency']; ?>]" min="0">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="input-group">
                                                                    <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                    <input type="date" name="date_advance" id="date_advance" class="form-control" placeholder="Date Advance">
                                                                </div>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="number" name="loan_duration" id="loan_duration" class="form-control" placeholder="Loan Duration" min="0">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="number" name="amt_outstanding" id="amt_outstanding" class="form-control" placeholder="Outstanding Amount [<?= $settings['currency']; ?>]" min="0">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="number" name="amt_advance2" id="amt_advance2" class="form-control" placeholder="Amount Advance [<?= $settings['currency']; ?>]" min="0">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="input-group">
                                                                    <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                    <input type="date" name="date_advance2" id="date_advance2" class="form-control" placeholder="Date Advance">
                                                                </div>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="number" name="loan_duration2" id="loan_duration2" class="form-control" placeholder="Loan Duration" min="0">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="number" name="amt_outstanding2" id="amt_outstanding2" class="form-control" placeholder="Outstanding Amount [<?= $settings['currency']; ?>]" min="0">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- SIGNITURE -->
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-12 float-right">
                                                <label class="control-label fw-bold col-md-12">Client Signature</label>
                                                <div class="form-group" id="signature-preview">
                                                    <div class="col-md-12">
                                                        (No Sign)
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input name="staff_id" placeholder="Loan Officer" class="form-control" value="<?= session()->get('id'); ?>" type="hidden">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer justify-content-between">
                    <!-- previous step -->
                    <button type="button" id="btnBack" onclick="submitStep('back')" class="btn btn-outline-warning float-start" style="display: none;">Back</button>
                    <!-- next step -->
                    <button type="button" id="btnNext" onclick="submitStep()" class="btn btn-outline-info float-end">Next</button>
                    <!-- submit form -->
                    <button type="button" id="btnSav" onclick="save_application()" class="btn btn-outline-success" style="display: none;">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- payment add model -->
<div class="modal fade" data-bs-backdrop="static" id="pay_modal_form">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form id="paymentForm" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly name="application_id" id="application_id" />
                    <input type="hidden" readonly name="client_id" id="client_id" />
                    <input type="hidden" readonly name="account_typeId" id="account_typeId" />
                    <input type="hidden" readonly name="particular_id" id="particular_id" />
                    <input type="hidden" readonly name="entry_typeId" id="entry_typeId" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Charge [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input type="number" name="charge" class="form-control" placeholder="Charge [<?= $settings['currency']; ?>]" min="0" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Amount to be Paid [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input type="number" id="amount" name="amount" class="form-control" placeholder="Amount to be Paid [<?= $settings['currency']; ?>]" min="0">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Payment Method</label>
                                    <div class="col-md-12">
                                        <select name="payment_id" id="payment_id" class="form-control select2bs4" style="width: 100%;">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Contact</label>
                                    <div class="col-md-12">
                                        <input name="contact" placeholder="Contact" class="form-control" type="text">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Details</label>
                                    <div class="col-md-12">
                                        <textarea name="entry_details" class="form-control" id="viewSummernote"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Remarks</label>
                                    <div class="col-md-12">
                                        <textarea name="remarks" class="form-control" placeholder="Remarks"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount" class="control-label fw-bold col-sm-12">Transaction Date</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <input type="text" name="date" id="date" class="form-control getDatePicker" value="<?= date('Y-m-d'); ?>" placeholder="Transaction Date">
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-sm-12">Officer</label>
                                    <div class="col-md-12">
                                        <input name="officer" placeholder="Responsible Officer" class="form-control" type="text" value="<?= session()->get('name'); ?>" readonly>
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
                <button type="button" id="btnPay" onclick="save_applicationPayment()" class="btn btn-outline-success">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- view payment details -->
<div class="modal fade" data-bs-backdrop="static" id="view_pay_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form id="payment_form" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <!-- client -->
                        <div class="row gx-3 gy-2 align-items-center mt-0" id="clientData" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">client Name</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vclient_name" id="client_name" placeholder="client Name" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Account Number</label>
                                    <div class="col-md-12">
                                        <input name="vaccount_no" id="account_no" placeholder="Account Number" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Contact</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vcontact" id="contact" class="form-control" placeholder="Contact" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- particular -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Acount Type</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vaccount_type" id="account_type" placeholder="Account Type" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Particular</label>
                                    <div class="col-md-12">
                                        <input name="vparticular_name" id="particular_name" placeholder="Particular" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Trans Type</label>
                                    <div class="col-md-12">
                                        <input type="text" name="ventry_type" id="entry_type" class="form-control" placeholder="Trans Type" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- state -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Date</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vdate" id="date" placeholder="Date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Branch Name</label>
                                    <div class="col-md-12">
                                        <input name="vbranch_name" id="branch_name" placeholder="branch_name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Status</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vstatus" id="status" class="form-control" placeholder="Trans Type" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- disbursement -->
                        <div class="row gx-3 gy-2 align-items-center mt-0" id="disbursementData" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Loan Product</label>
                                    <div class="col-md-12">
                                        <input name="vproduct_name" id="product_name" placeholder="Loan Product" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Disbursement Code</label>
                                    <div class="col-md-12">
                                        <input name="vdisbursement_id" id="disbursement_id" placeholder="Disbursement Code" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Class</label>
                                    <div class="col-md-12">
                                        <input name="vclass" id="class" placeholder="Class" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- application -->
                        <div class="row gx-3 gy-2 align-items-center mt-0" id="applicationData" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Loan Product</label>
                                    <div class="col-md-12">
                                        <input name="vproduct_name" id="product_name" placeholder="Loan Product" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Application Code</label>
                                    <div class="col-md-12">
                                        <input name="vapplication_id" id="application_id" placeholder="Application Code" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Application Status</label>
                                    <div class="col-md-12">
                                        <input name="vapplication_status" id="application_status" placeholder="Application Status" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- amount -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Ref ID</label>
                                    <div class="col-md-12">
                                        <input name="vref_id" placeholder="Ref ID" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
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
                                    <label class="control-label fw-bold col-md-12">Payment</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vpayment" id="payment" class="form-control" placeholder="Payment" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- details -->
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
                        <!-- remarks -->
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
                        <!-- accounting -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <p class="col-md-12 pl-2">For Accounting</p>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Trans Menu</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="ventry_menu" placeholder="Trans Menu" id="entry_menu" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Balance</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vbalance" id="balance" class="form-control" placeholder="Balance" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- created -->
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
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- photo preview modal -->
<div class="modal fade" data-bs-backdrop="static" id="photo_modal_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-success text-center"> </h4>
                <div class="close">
                    <btn type="button" class="btn btn-md btn-danger" onclick="exportApplicationForm()" id="export">
                        <i class="fas fa-file-pdf text-light"></i>
                    </btn>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body form well well-lg one_well ">
                <form action="#" id="viewform" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <center>
                            <div class="form-group" id="photo-view">
                                <div class="col-md-12">
                                    (No photo)
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </center>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- update application status approve/decline/review/processing -->
<div class="modal fade" data-bs-backdrop="static" id="remarks_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-info" id="modal-title"></h3>
                <div class="close">
                    <btn type="button" class="btn btn-md btn-danger" onclick="exportApplicationActionForm('approve')" id="export">
                        <i class="fas fa-file-pdf text-light"></i>
                    </btn>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body form">
                <form id="statusForm" class="form-horizontal form-validate-summernote" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly name="remark_id" />
                    <input type="hidden" readonly name="application_id" />
                    <input type="hidden" readonly name="application_code" />
                    <input type="hidden" readonly name="client_id" />
                    <input type="hidden" readonly id="loan_interest_type" />
                    <input type="hidden" readonly id="loan_principal_amount">
                    <input type="hidden" readonly id="loan_interest_rate" />
                    <input type="hidden" readonly id="loan_frequency_type" />
                    <input type="hidden" readonly id="installments_no" />
                    <input type="hidden" readonly id="loan_repayment_period" />
                    <input type="hidden" readonly id="loan_installment">
                    <input type="hidden" readonly id="mode">
                    <div class="form-body">
                        <!-- status row -->
                        <div class="row gx-3 gy-2 align-items-center mt-0" id="statusRow">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Level</label>
                                    <div class="col-md-12">
                                        <select name="level" id="level" class="form-control select2bs4">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Action</label>
                                    <div class="col-md-12">
                                        <select name="action" id="action" class="form-control select2bs4">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- disburse loan calculations -->
                        <div class="row gx-3 gy-2 align-items-center mt-0" id="approvalCals" style="display: none;">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4" id="totalPrincipal">
                                    <div class="form-group">
                                        <lable for="principal" class="control-label fw-bold">Total Principal</lable>
                                        <input type="text" name="principal" id="principal" class="form-control" placeholder="Total Principal" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4" id="totalCharges">
                                    <div class="form-group">
                                        <lable for="total_charges" class="control-label fw-bold">Total Charges</lable>
                                        <input type="text" name="total_charges" id="total_charges" class="form-control" placeholder="Total Charges" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4" id="principalRecievable" style="display: none;">
                                    <div class="form-group">
                                        <lable for="principal_receivable" class="control-label fw-bold">Principal Recievable</lable>
                                        <input type="text" name="principal_receivable" id="principal_receivable" class="form-control" placeholder="Principal Recievable" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4" id="reductCharges" style="display: none;">
                                    <div class="form-group">
                                        <lable for="particular_id" class="control-label fw-bold">Reduct Charges</lable>
                                        <select name="reduct_particular" id="particular_id" class="form-control select2bs4 particular_id">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <lable for="installments_num" class="control-label fw-bold">Installments N<u>o</u></lable>
                                        <input type="text" name="installments_num" id="installments_num" class="form-control" placeholder="Total Installments" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <lable for="computed_installment" class="control-label fw-bold">Computed Installment [<?= $settings['currency']; ?>]</lable>
                                        <input type="text" name="computed_installment" id="computed_installment" class="form-control" placeholder="Computed Installment" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <lable for="actual_installment" class="control-label fw-bold">Actual Installment [<?= $settings['currency']; ?>]</lable>
                                        <input type="text" name="actual_installment" id="actual_installment" class="form-control" placeholder="Actual Installment" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <lable for="computed_interest" class="control-label fw-bold">Computed Interest [<?= $settings['currency']; ?>]</lable>
                                        <input type="text" name="computed_interest" id="computed_interest" class="form-control" placeholder="Interest" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <lable for="actual_interest" class="control-label fw-bold">Actual Interest [<?= $settings['currency']; ?>]</lable>
                                        <input type="text" name="actual_interest" id="actual_interest" class="form-control" placeholder="Total Interest" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <lable for="computed_repayment" class="control-label fw-bold">Computed Repayment [<?= $settings['currency']; ?>]</lable>
                                        <input type="text" name="computed_repayment" id="computed_repayment" class="form-control" placeholder="Computed Repayment" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <lable for="principal_installment" class="control-label fw-bold">Principal Installment [<?= $settings['currency']; ?>]</lable>
                                        <input type="text" name="principal_installment" id="principal_installment" class="form-control" placeholder="Principal Installment" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <lable for="interest_installment" class="control-label fw-bold">Interest Installment [<?= $settings['currency']; ?>]</lable>
                                        <input type="text" name="interest_installment" id="interest_installment" class="form-control" placeholder="Interest Installment" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <lable for="actual_repayment" class="control-label fw-bold">Actual Repayment [<?= $settings['currency']; ?>]</lable>
                                        <input type="text" name="actual_repayment" id="actual_repayment" class="form-control" placeholder="Actual Repayment" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0" id="disbursementRow" style="display: none;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Particular</label>
                                        <div class="col-md-12">
                                            <select name="particular_id" id="disparticular_id" class="form-control select2bs4" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Payment Method</label>
                                        <div class="col-md-12">
                                            <select name="payment_id" id="payment_id" class="form-control select2bs4" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Disbursement By</label>
                                        <div class="col-md-12">
                                            <select name="disbursed_by" id="disbursed_by" class="form-control select2bs4" style="width: 100%;">
                                                <!-- <option value="">-- select --</option> -->
                                                <option value="Deposited into Client Account">Deposited into Client Account</option>
                                                <option value="Paid in Cash">Paid in Cash</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Date Disbursed</label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                <input type="text" name="date_disbursed" id="date_disbursed" class="form-control getDatePicker" value="<?= date('Y-m-d') ?>" placeholder="Date Disbursed">
                                            </div>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pl-3 mt-2 text-info">
                                <input type="checkbox" name="checkbox" id="showPlan" onclick="showRepaymentPlan()">
                                View Repayment Plan
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0" id="showRepaymentPlan" style="display: none;">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover text-nowrap" style="width:100%">
                                            <thead class="">
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Due Date</th>
                                                    <th>Outstanding [<?= $settings['currency']; ?>]</th>
                                                    <th>Principal [<?= $settings['currency']; ?>]</th>
                                                    <th>Interest [<?= $settings['currency']; ?>]</th>
                                                    <th>Installment [<?= $settings['currency']; ?>]</th>
                                                    <th>Running Bal [<?= $settings['currency']; ?>]</th>
                                                </tr>
                                            </thead>
                                            <tbody id="repaymentPlan">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- remarks -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Remarks</label>
                                    <div class="col-md-12">
                                        <textarea name="loan_remarks" id="newSummernote" class="form-control" placeholder="Remarks"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" readonly name="staff_id" placeholder="officer" class="form-control text-primary" value="<?= session()->get('id'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnApprove" onclick="save_applicationRemarks()" class="btn btn-outline-success">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    var chargesData = <?= json_encode($charges); ?>;
</script>

<script src="/assets/client/main/auto.js"></script>
<script src="/assets/client/loans/applications.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/client/loans/applications.js"></script>
<script src="/assets/client/main/others.js"></script>
<script src="/assets/client/main/options.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>

<?= $this->endSection() ?>