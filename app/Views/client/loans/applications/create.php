<?= $this->extend("layout/client"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card border custom-card">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    New Loan Application
                </div>
            </div>
            <div class="card-body">
                <form id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" name="id" readonly />
                    <input type="hidden" name="application_id" value="<?= (isset($application)) ? $application['id'] : "0"; ?>" readonly />
                    <input type="hidden" name="mode" readonly />
                    <input type="hidden" name="step_no" readonly />
                    <div class="form-body">
                        <!-- CLIENT BIO -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-3 text-center">
                                <label class="control-label fw-bold col-md-12">
                                    Passport Photo <span class="text-danger">*</span>
                                </label>
                                <div class="text-center">
                                    <?php if ($user['photo'] && file_exists('uploads/clients/passports/' . $user['photo'])) : ?>
                                        <img src="/uploads/clients/passports/<?= $user['photo']; ?>" class="img-fluid rounded-pill" alt="Profile" id="viewProfileImage" style="height:100px; width: 100px;" />
                                    <?php else : ?>
                                        <img src="/assets/dist/img/nophoto.jpg" class="img-fluid rounded-pill" alt="Passport Photo" id="viewProfileImage">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="client_id">
                                                Applicant Full Name <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <select name="client_id" id="client_id" class="form-control select2bs4 client_id">
                                                    <option value="<?= $user['id'] ?>" selected>
                                                        <?= $user['name'] ?>
                                                    </option>
                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="account_no">
                                                Account Number <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <input type="text" name="account_no" id="account_no" class="form-control" placeholder="Account No." value="<?= $user['account_no'] ?>" readonly>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="mobile">
                                                Phone Number <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Contact No." value="<?= $user['mobile'] ?>" readonly>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <label class="control-label fw-bold col-md-12">
                                    Applicant ID (Front) <span class="text-danger">*</span>
                                </label>
                                <div class="text-center">
                                    <div class="col-sm-12">
                                        <?php if ($user['id_photo_front'] && file_exists('uploads/clients/ids/front/' . $user['id_photo_front'])) : ?>
                                            <img src="/uploads/clients/ids/front/<?= $user['id_photo_front']; ?>" class="img-fluid" alt="ID PHOTO (Front)" id="previewIdFront" style="height:100px; width: auto;" />
                                        <?php else : ?>
                                            <img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail" alt="ID PHOTO (Front)" id="previewIdFront" style="height:100px; width: auto;" />
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- LOAN TERMS -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="application_date" class="control-label fw-bold col-sm-12">
                                        Application Date <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <input type="date" name="application_date" id="application_date" class="form-control" value="<?= (isset($application)) ? $application['application_date'] : date('Y-m-d'); ?>" placeholder="Application Date" readonly>
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12" for="principal">
                                        Loan Principal [<?= $settings['currency']; ?>] <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <input name="principal" id="principal" placeholder="Loan Principal [<?= $settings['currency']; ?>]" class="form-control" type="text" onkeyup="loanLoanProductsByLoanAmount(this.value)" value="<?= (isset($application)) ? $application['principal'] : ""; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12" for="product_id">
                                        Loan Product <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <select name="product_id" id="product_id" class="form-control select2">
                                            <option value="">-- Select --</option>
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4" style="display: none;">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12" for="interest_type">
                                        Interest Method <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <input type="text" name="interest_type" id="interest_type" class="form-control" placeholder="Interest Method" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12" for="interest_rate">
                                        Interest Rate <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <input type="text" name="interest_rate" id="interest_rate" class="form-control" placeholder="Interest Rate" value="<?= (isset($application)) ? $application['interest_rate'] : ""; ?>" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12" for="repayment_period">
                                        Repayment Period <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <input type="text" name="repayment_period" id="repayment_period" class="form-control" placeholder="Repayment Period" value="<?= (isset($application)) ? $application['repayment_period'] . ' ' . $application['repayment_duration'] : ""; ?>" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12" for="repayment_freq">
                                        Repayment Frequency <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <input type="text" name="repayment_freq" id="repayment_freq" class="form-control" placeholder="Repayment Freq." value="<?= (isset($application)) ? $application['repayment_freq'] : ""; ?>" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12" for="purpose">
                                        Loan Purpose <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <textarea name="purpose" id="purpose" placeholder="Loan Purpose" class="form-control"><?= (isset($application)) ? $application['purpose'] : ""; ?></textarea>
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
                                    <label class="control-label fw-bold col-md-12">Total Charges</label>
                                    <div class="col-md-12">
                                        <input type="text" name="total_charges" id="total_charges" class="form-control" placeholder="Total Charges" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Deduct Charges <span class="text-danger">*</span></label>
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
                        <hr>
                        <p class="fw-semibold text-primary mb-3">Product Applicable Charges</p>
                        <div class="row gx-3 gy-2 align-items-center mt-0" id="productCharges">
                        </div>
                        <hr>
                        <!-- LOAN SECURITY -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <p class="col-md-12 fw-bold">
                                Loan Security <span class="text-danger">*</span>
                            </p>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12" for="security_item">Security Item</label>
                                    <div class="col-md-12">
                                        <input type="text" name="security_item" id="security_item" class="form-control" placeholder="Security Item" value="<?= (isset($application)) ? $application['security_item'] : ""; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12" for="est_value">
                                        Estimated Value [<?= $settings['currency']; ?>] <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <input type="number" name="est_value" id="est_value" class="form-control" placeholder="Estimated Value [<?= $settings['currency']; ?>]" min="0" value="<?= (isset($application)) ? $application['est_value'] : ""; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-sm-12">
                                        Collateral Files (<span class="text-danger">Images/PDF</span>) <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-12">
                                        <input type="file" name="collateral[]" class="form-control" multiple>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12" for="security_info">Details <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <textarea name="security_info" id="" class="form-control" placeholder="Details"><?= (isset($application)) ? $application['security_info'] : ""; ?></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- REFEREES -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <p class="col-md-12 fw-bold">Applicant Guarantors</p>
                            <div class="col-md-12">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="ref_name">
                                                Full Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="ref_name" id="ref_name" class="form-control" placeholder="Full Name" value="<?= (isset($application)) ? $application['ref_name'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="relation">
                                                Relationship <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control select2bs4" name="ref_relation" id="ref_relationships" style="width: 100%;">
                                                <?php
                                                if (count($relations) > 0) :
                                                    foreach ($relations as $key => $value) : ?>
                                                        <option value="<?= $key; ?>" <?= (isset($application) && ($application['ref_relation'] == $key)) ? "selected" : ""; ?>>
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
                                            <label class="control-label fw-bold col-md-12" for="ref_job">
                                                Occupation <span class="text-danger">*</span>
                                            </label>
                                            <select id="ref_job" name="ref_job" class="form-control select2bs4 occupation" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="ref_contact">
                                                Phone Contact <span class="text-danger">*</span>
                                            </label>
                                            <input type="tel" name="ref_contact" id="ref_contact" class="form-control" placeholder="Contact 1" value="<?= (isset($application)) ? $application['ref_contact'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="ref_alt_contact">Phone Contact 2</label>
                                            <input type="tel" name="ref_alt_contact" id="ref_alt_contact" class="form-control" placeholder="Contact 2" value="<?= (isset($application)) ? $application['ref_alt_contact'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="ref_email">Email</label>
                                            <input type="email" name="ref_email" id="ref_email" class="form-control" placeholder="Email" value="<?= (isset($application)) ? $application['ref_email'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="ref_address2">
                                                Address <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="ref_address" id="ref_address2" class="form-control" placeholder="Address"><?= (isset($application)) ? $application['ref_address'] : ""; ?></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="ref_name2">
                                                Full Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="ref_name2" id="ref_name2" class="form-control" placeholder="Full Name" value="<?= (isset($application)) ? $application['ref_name2'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="relation2">
                                                Relationship <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control select2bs4" name="ref_relation2" id="relation2" style="width: 100%;">
                                                <option value="">-- select --</option>
                                                <?php
                                                if (count($relations) > 0) :
                                                    foreach ($relations as $key => $value) : ?>
                                                        <option value="<?= $key; ?>" <?= (isset($application) && ($application['ref_relation2'] == $key)) ? "selected" : ""; ?>>
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
                                            <label class="control-label fw-bold col-md-12" for="ref_job2">
                                                Occupation <span class="text-danger">*</span>
                                            </label>
                                            <select id="ref_job2" name="ref_job2" class="form-control select2bs4 occupation" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="ref_contact2">
                                                Phone Contact <span class="text-danger">*</span>
                                            </label>
                                            <input type="tel" name="ref_contact2" id="ref_contact2" class="form-control" placeholder="Contact 1" value="<?= (isset($application)) ? $application['ref_contact2'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="ref_alt_contact2">Contact 2</label>
                                            <input type="tel" name="ref_alt_contact2" id="ref_alt_contact2" class="form-control" placeholder="Contact 2" value="<?= (isset($application)) ? $application['ref_alt_contact2'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="ref_email2">Email</label>
                                            <input type="email" name="ref_email2" id="ref_email2" class="form-control" placeholder="Email" value="<?= (isset($application)) ? $application['ref_email2'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12" for="ref_address2">
                                                Address <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="ref_address2" id="ref_address2" class="form-control" placeholder="Address"><?= (isset($application)) ? $application['ref_address2'] : ""; ?></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <p class="col-md-12 fw-bold">Applicant Financial Information</p>
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
                                            <input type="number" name="net_salary" class="form-control" id="salary" placeholder="Net Salary" min="0" onkeyup="loanFinancialTotal()" value="<?= (isset($application)) ? $application['net_salary'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="farming" class="col-md-12 form-label">Farming</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="number" name="farming" class="form-control" id="farming" placeholder="Farming" min="0" onkeyup="loanFinancialTotal()" value="<?= (isset($application)) ? $application['farming'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="business" class="col-md-12 form-label">Business</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="number" name="business" class="form-control" id="business" placeholder="Business" min="0" onkeyup="loanFinancialTotal()" value="<?= (isset($application)) ? $application['business'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="others" class="col-md-12 form-label">Others</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="number" name="others" class="form-control" id="others" placeholder="Others" min="0" onkeyup="loanFinancialTotal()" value="<?= (isset($application)) ? $application['others'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="col-md-12 form-label"></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="number" name="" class="form-control" id="" placeholder="" min="0" onkeyup="loanFinancialTotal()" disabled>
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
                                            <input type="number" name="rent" class="form-control" id="rent" placeholder="Rent" min="0" onkeyup="loanFinancialTotal()" value="<?= (isset($application)) ? $application['rent'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="education" class="col-md-12 form-label">Education</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="number" name="education" class="form-control" id="education" placeholder="Education" min="0" onkeyup="loanFinancialTotal()" value="<?= (isset($application)) ? $application['education'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="medical" class="col-md-12 form-label">Medical</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="number" name="medical" class="form-control" id="medical" placeholder="Medical" min="0" onkeyup="loanFinancialTotal()" value="<?= (isset($application)) ? $application['medical'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="transport" class="col-md-12 form-label">Transport</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="number" name="transport" class="form-control" id="transport" placeholder="Transport" min="0" onkeyup="loanFinancialTotal()" value="<?= (isset($application)) ? $application['transport'] : ""; ?>">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="exp_others" class="col-md-12 form-label">Others</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="number" name="exp_others" class="form-control" id="exp_others" placeholder="Others" min="0" onkeyup="loanFinancialTotal()" value="<?= (isset($application)) ? $application['exp_others'] : ""; ?>">
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
                                            <input type="number" name="difference" class="form-control" id="difference" placeholder="Difference" min="0" value="<?= (isset($application)) ? $application['difference'] : ""; ?>" readonly>
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
                                            <input type="text" class="form-control" name="dif_status" placeholder="Difference Status" id="dif_status" value="<?= (isset($application)) ? $application['dif_status'] : ""; ?>">
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
                                        Income Files (<span class="text-danger">Images/PDF</span>) <span class="text-danger">*</span>
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
                                        Expenses Files (<span class="text-danger">Images/PDF</span>) <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="expense[]" class="form-control" multiple>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- CLIENT OTHER ACCOUNTS -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <p class="col-md-12 fw-bold">Applicant's Accounts In Other Financial Institutions</p>
                            <label class="control-label fw-bold col-4" for="institution">Institution</label>
                            <label class="control-label fw-bold col-4" for="branch">Branch</label>
                            <label class="control-label fw-bold col-4" for="state">Account Type</label>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="text" name="institute_name" id="institution" class="form-control" placeholder="Institution" value="<?= (isset($application)) ? $application['institute_name'] : ""; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="text" name="institute_branch" id="branch" class="form-control" placeholder="Branch" value="<?= (isset($application)) ? $application['institute_branch'] : ""; ?>">
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
                                        <input type="text" name="institute_name2" id="institution2" class="form-control" placeholder="Institution" value="<?= (isset($application)) ? $application['institute_name2'] : ""; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="text" name="institute_branch2" id="branch2" class="form-control" placeholder="Branch" value="<?= (isset($application)) ? $application['institute_branch2'] : ""; ?>">
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
                            <p class="col-md-12 fw-bold">Applicant's Loans In Other Financial Institutions</p>
                            <label class="control-label fw-bold col-3" for="amt_advance">Amount Advance [<?= $settings['currency']; ?>]</label>
                            <label class="control-label fw-bold col-3" for="date_advance">Date Advance</label>
                            <label class="control-label fw-bold col-3" for="loan_duration">Loan Duration</label>
                            <label class="control-label fw-bold col-3" for="amt_outstanding">Outstanding Amount [<?= $settings['currency']; ?>]</label>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="number" name="amt_advance" id="amt_advance" class="form-control" placeholder="Amount Advance [<?= $settings['currency']; ?>]" min="0" value="<?= (isset($application)) ? $application['amt_advance'] : ""; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <input type="date" name="date_advance" id="date_advance" class="form-control" placeholder="Date Advance" value="<?= (isset($application)) ? $application['date_advance'] : ""; ?>">
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="number" name="loan_duration" id="loan_duration" class="form-control" placeholder="Loan Duration" min="0" value="<?= (isset($application)) ? $application['loan_duration'] : ""; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="number" name="amt_outstanding" id="amt_outstanding" class="form-control" placeholder="Outstanding Amount [<?= $settings['currency']; ?>]" min="0" value="<?= (isset($application)) ? $application['amt_outstanding'] : ""; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="number" name="amt_advance2" id="amt_advance2" class="form-control" placeholder="Amount Advance [<?= $settings['currency']; ?>]" min="0" value="<?= (isset($application)) ? $application['amt_advance2'] : ""; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <input type="date" name="date_advance2" id="date_advance2" class="form-control" placeholder="Date Advance" value="<?= (isset($application)) ? $application['date_advance2'] : ""; ?>">
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="number" name="loan_duration2" id="loan_duration2" class="form-control" placeholder="Loan Duration" min="0" value="<?= (isset($application)) ? $application['loan_duration2'] : ""; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="number" name="amt_outstanding2" id="amt_outstanding2" class="form-control" placeholder="Outstanding Amount [<?= $settings['currency']; ?>]" min="0" value="<?= (isset($application)) ? $application['amt_outstanding2'] : ""; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- SIGNaTURE -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6 float-right">
                                <label class="control-label fw-bold col-md-12">
                                    Applicant Signature <span class="text-danger">*</span>
                                </label>
                                <div class="text-center">
                                    <div class="col-sm-12">
                                        <?php if ($user['signature'] && file_exists('uploads/clients/signatures/' . $user['signature'])) : ?>
                                            <img src="/uploads/clients/signatures/<?= $user['signature']; ?>" class="img-fluid bd-placeholder-img bd-placeholder-img rounded-4" alt="signature" id="preview-sign" style="height:100px; width: 100px;" />
                                        <?php else : ?>
                                            <img src="/assets/dist/img/sign.png" class="img-fluid thumbnail" alt="signature" id="preview-sign" style="height:100px; width: auto;">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label fw-bold col-md-12">
                                    Applicant ID Photo (Back) <span class="text-danger">*</span>
                                </label>
                                <div class="text-center">
                                    <div class="col-sm-12">
                                        <?php if ($user['id_photo_back'] && file_exists('uploads/clients/ids/back/' . $user['id_photo_back'])) : ?>
                                            <img src="/uploads/clients/ids/back/<?= $user['id_photo_back']; ?>" class="img-fluid bd-placeholder-img bd-placeholder-img rounded-4 thumbnail" alt="ID PHOTO (Back)" id="previewIdBack" style="height:100px; width: auto;" />
                                        <?php else : ?>
                                            <img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail bd-placeholder-img bd-placeholder-img rounded-4" alt="ID Back" id="previewIdBack" style="height:100px; width: auto;" />
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input name="staff_id" placeholder="Loan Officer" class="form-control" value="<?= $user['staff_id']; ?>" type="hidden">
                            <span class="help-block text-danger"></span>
                        </div>
                        <hr>
                        <div class="row gx-3 gy-2 mt-0 float-end">
                            <div class="col-md-12">
                                <button type="button" id="btnSubmit" onclick="submitApplication('<?= (isset($application)) ? 'update' : 'add'; ?>')" class="btn btn-outline-primary">
                                    Submit Application
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    var product_id = '<?= (isset($application)) ? $application['id'] : null; ?>';
    var principal = '<?= (isset($application)) ? $application['principal'] : null; ?>';
</script>

<!-- <script src="/assets/client/main/auto.js"></script> -->
<script src="/assets/client/loans/applications/create.js"></script>
<script src="/assets/client/main/options.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>

<?= $this->endSection() ?>