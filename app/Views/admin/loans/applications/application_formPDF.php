<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? esc($title) : ''; ?></title>

    <link rel="icon" type="image/png" href="<?= (isset($settings) && file_exists('uploads/logo/' . $settings['business_logo'])) ? '/uploads/logo/' . $settings['business_logo'] : '/assets/dist/img/default.jpg'; ?>" />

    <link href="/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/assets/vendor/swiper/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="/assets/vendor/noUiSlider/css/nouislider.min.css">
    <link href="/assets/vendor/jvmap/jquery-jvectormap.css" rel="stylesheet">
    <link href="/assets/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- <link href="/assets/vendor/datatables/css/buttons.dataTables.min.css" rel="stylesheet"> -->
    <link href="/assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

    <!-- Datatables Css -->
    <link rel="stylesheet" href="/assets/dist/datatables.net-bs5/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="/assets/dist/datatables.net-bs5/css/buttons.bootstrap5.min.css">

    <!-- tagify-css -->
    <link href="/assets/vendor/tagify/dist/tagify.css" rel="stylesheet">

    <!-- Select2 -->
    <link rel="stylesheet" href="/assets/vendor/select2/css/select2.min.css">
    <!-- <link rel="stylesheet" href="/assets/dist/sweetalert2/sweetalert2.min.css"> -->

    <!-- summernote -->
    <link rel="stylesheet" href="/assets/dist/summernote/summernote-bs4.min.css">

    <!-- Style css -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body">
    <div class="wrapper border border-3 p-3">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3 class='text-bold'>
                    <?= $settings['business_name'] . ' ' . $settings['system_name']; ?>
                </h3>
                <h4 class='text-bold'><?= $title ?></h4>
                <h5 class=''>Date: <?= date('l, d F, Y'); ?></h5>
                <img src="/uploads/logo/<?= $settings['business_logo'] ?>" alt="Logo" class="brand-image img-center" style="position:absolute; top:0; left:0;width:100px;height:100px;height: 100px; width: 100px; opacity: 0.8;" />
                <br>
            </div>
        </div>
        <hr>
        
        <div class="row">
            <div class="col-md-12">
                <?php if ($id == 0) : ?>
                    <form id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <input type="hidden" readonly value="" name="application_id" />
                        <div class="form-body">
                            <!-- CLIENT BIO -->
                            <div class="row">
                                <div class="col-md-3 text-center border">
                                    <label class="control-label col-md-12">Profile Photo</label>
                                    <div class="form-group" id="photo-preview">
                                        <div class="col-md-12">
                                            (No photo)
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label col-md-12" for="client_id">Client Name</label>
                                                <div class="col-md-12">
                                                    <select name="client_id" id="client_id" class="form-control select2bs4">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12" for="account_no">Account No.</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="account_no" id="account_no" class="form-control" placeholder="Account No." readonly>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12" for="mobile">Contact No.</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Contact No." readonly>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center border">
                                    <label class="control-label col-md-12">Client ID</label>
                                    <div class="form-group" id="id-preview">
                                        <div class="col-md-12">
                                            (No photo)
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- lOAN TERMS -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" for="product_id">Loan Product</label>
                                        <div class="col-md-12">
                                            <select name="product_id" id="product_id" class="form-control select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" for="interest_rate">Interest Rate</label>
                                        <div class="col-md-12">
                                            <input type="text" name="interest_rate" id="interest_rate" class="form-control" placeholder="Interest Rate" readonly>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" for="interest_type">Interest Method</label>
                                        <div class="col-md-12">
                                            <input type="text" name="interest_type" id="interest_type" class="form-control" placeholder="Interest Method" readonly>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" for="repayment_period">Repayment Period</label>
                                        <div class="col-md-12">
                                            <input type="text" name="repayment_period" id="repayment_period" class="form-control" placeholder="Repayment Period" readonly>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" for="repayment_freq">Repayment Freq.</label>
                                        <div class="col-md-12">
                                            <input type="text" name="repayment_freq" id="repayment_freq" class="form-control" placeholder="Repayment Freq." readonly>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" for="principal">Loan Principal</label>
                                        <div class="col-md-12">
                                            <input name="principal" id="principal" placeholder="Loan Principal" class="form-control" type="number" min="0">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" for="purpose">Loan Purpose</label>
                                        <div class="col-md-12">
                                            <textarea name="purpose" id="purpose" placeholder="Loan Purpose" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- CLIENT FINANCES -->
                            <div class="row">
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
                            <!-- CLIENT OTHER ACCOUNTS -->
                            <div class="row">
                                <p class="col-md-12">Client's Accounts In Other Financial Institutions</p>
                                <label class="control-label col-4" for="instution">Institution</label>
                                <label class="control-label col-4" for="branch">Branch</label>
                                <label class="control-label col-4" for="state">Account Type</label>
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
                            <!-- CLIENT OTHER LOANS -->
                            <div class="row">
                                <p class="col-md-12">Client's Loans In Other Financial Institutions</p>
                                <label class="control-label col-3" for="amt_advance">Amount Advance[<?= $settings['currency']; ?>]</label>
                                <label class="control-label col-3" for="date_advance">Date Advance</label>
                                <label class="control-label col-3" for="loan_duration">Loan Duration</label>
                                <label class="control-label col-3" for="amt_outstanding">Outstanding Amount[<?= $settings['currency']; ?>]</label>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="number" name="amt_advance" id="amt_advance" class="form-control" placeholder="Amount Advance[<?= $settings['currency']; ?>]" min="0">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="date" name="date_advance" id="date_advance" class="form-control" placeholder="Date Advance">
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
                                            <input type="number" name="amt_outstanding" id="amt_outstanding" class="form-control" placeholder="Outstanding Amount[<?= $settings['currency']; ?>]" min="0">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="number" name="amt_advance2" id="amt_advance2" class="form-control" placeholder="Amount Advance[<?= $settings['currency']; ?>]" min="0">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="date" name="date_advance2" id="date_advance2" class="form-control" placeholder="Date Advance">
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
                                            <input type="number" name="amt_outstanding2" id="amt_outstanding2" class="form-control" placeholder="Outstanding Amount[<?= $settings['currency']; ?>]" min="0">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- LOAN SECURITY -->
                            <div class="row">
                                <p class="col-md-12">Loan Security</p>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" for="security_item">Security Item</label>
                                        <div class="col-md-12">
                                            <input type="text" name="security_item" id="security_item" class="form-control" placeholder="Security Item">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" for="est_value">Estimated Value[<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input type="number" name="est_value" id="est_value" class="form-control" placeholder="Estimated Value[<?= $settings['currency']; ?>]" min="0">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="form-group">
                                        <label class="control-label col-sm-12">
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
                                        <label class="control-label col-md-12" for="security_info">Details</label>
                                        <div class="col-md-12">
                                            <textarea name="security_info" id="addSummernote" class="form-control" placeholder="Details"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- REFEREES -->
                            <div class="row">
                                <p class="col-md-12 text-bold">References</p>
                                <div class="col-md-12">
                                    <div class="row">
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
                                                <select class="form-control select2bs4" name="ref_relation" id="relation" style="width: 100%;">
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
                                                <label for="ref_job">Occupation</label>
                                                <input type="text" name="ref_job" id="ref_job" class="form-control" placeholder="Occupation">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="ref_address2">Address</label>
                                                <textarea name="ref_address" id="ref_address2" class="form-control" placeholder="Address"></textarea>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
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
                                    <div class="row">
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
                                    <div class="row">
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
                            <!-- SIGNITURE -->
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <label class="control-label col-md-12">Client Signature</label>
                                    <div class="form-group" id="signature-preview">
                                        <div class="col-md-12">
                                            (No Sign)
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-center text-bold">For Official Use Only</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ref_address2">Responsible Officer</label>
                                        <input type="text" name="emp_id" class="form-control">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ref_address2">Officer Signature</label>
                                        <input type="text" name="emp_id" class="form-control">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else :
                    $dob = new DateTime($application['dob']);
                    $today = new DateTime();
                    $age = $today->diff($dob)->y;
                ?>
                    <form class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly name="id" />
                        <div class="form-body">
                            <!-- applicant bio -->
                            <section>
                                <div class="row">
                                    <div class="col-12">
                                        <span>Applicant Bio</span>
                                        <p class="" style="border-top: 1px solid;"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Full Name</label>
                                                    <div class="col-md-12">
                                                        <input name="name" value="<?= $application['name'] ?>" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Gender</label>
                                                    <div class="col-md-12">
                                                        <input name="gender" value="<?= $application['gender'] ?>" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Religion</label>
                                                    <div class="col-md-12">
                                                        <input name="religion" value="<?= $application['religion'] ?>" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Marital Status</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="marital_status" value="<?= $application['marital_status'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Nationality</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="nationality" value="<?= $application['nationality'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label class="control-label">PassPort Photo</label>
                                        <div class="col-sm-12">
                                            <?php if ($application['photo'] != '' && file_exists("uploads/clients/passports/" . $application['photo'])) : ?>
                                                <center>
                                                    <img src="/uploads/clients/passports/<?= $application['photo'] ?>" alt="Photo" class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                                </center>
                                            <?php else : ?>
                                                <center>
                                                    <img src="/assets/dist/img/nophoto.jpg" alt="Photo" class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                                </center>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Phone Number 1</label>
                                            <div class="col-md-12">
                                                <input name="mobile" value="<?= $application['mobile'] ?>" class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Phone Number 2</label>
                                            <div class="col-md-12">
                                                <input name="alt_mobile" value="<?= $application['alternate_no'] ?>" class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <div class="col-md-12">
                                                <input name="email" value="<?= $application['email'] ?>" class="form-control" type="email">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">D.O.B</label>
                                            <div class="col-md-12">
                                                <input name="dob" value="<?= $application['dob'] ?>" class="form-control" type="date" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Occupation</label>
                                            <div class="col-md-12">
                                                <input name="occupation" value="<?= $application['occupation'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Business Location</label>
                                            <div class="col-md-12">
                                                <input name="job_location" value="<?= $application['job_location'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Applicant Address</label>
                                            <div class="col-md-12">
                                                <textarea name="residence" class="form-control" readonly>
                                                    <?= strip_tags($application['residence']) ?>
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Id Type</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="id_type" value="<?= $application['id_type'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Id Number</label>
                                            <div class="col-md-12">
                                                <input name="id_number" value="<?= $application['id_number'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Id Expiry Date</label>
                                            <div class="col-md-12">
                                                <input name="id_expiry" value="<?= $application['id_expiry_date'] ?>" class="form-control" type="date" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Next of Kin Name</label>
                                            <div class="col-md-12">
                                                <input name="next_of_kin" value="<?= $application['next_of_kin_name'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Next of Kin Relationship</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="nok_relationship" value="<?= $application['next_of_kin_relationship'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Next of Kin Address</label>
                                            <div class="col-md-12">
                                                <input name="nok_address" value="<?= $application['nok_address'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Next of Kin Phone 1</label>
                                            <div class="col-md-12">
                                                <input name="nok_phone" value="<?= $application['next_of_kin_contact'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Next of Kin Phone 2</label>
                                            <div class="col-md-12">
                                                <input name="nok_alt_phone" value="<?= $application['next_of_kin_alternate_contact'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Age(Years)</label>
                                            <div class="col-md-12">
                                                <input name="age" value="<?= $age ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Applicant Branch</label>
                                            <div class="col-md-12">
                                                <input name="branch_name" value="<?= $application['branch_name'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Account Number</label>
                                            <div class="col-md-12">
                                                <input type="text" name="account_no" value="<?= $application['account_no'] ?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Account Type</label>
                                            <div class="col-md-12">
                                                <input type="text" name="client_account" value="<?= $application['acc_type'] ?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <hr>
                            <!-- appication data -->
                            <section>
                                <div class="row">
                                    <div class="col-12">
                                        <span>Application Data</span>
                                        <p class="" style="border-top: 1px solid;"></p>
                                    </div>
                                </div>
                                <!-- principal and product -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Loan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="product_id">Application Code</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vapplication_code" class="form-control" value="<?= $application['application_code'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="principal">Principal[<?= $settings['currency']; ?>]</label>
                                                    <div class="col-md-12">
                                                        <input name="vprincipal" id="principal" value="<?= $application['principal'] ?>" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="product_id">Loan Product</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vproduct_id" class="form-control" value="<?= $application['product_name'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Interest Rate</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vinterest_rate" class="form-control" value="<?= $application['interest_rate'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Interest Method</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vinterest_rate" class="form-control" value="<?= $application['interest_type'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Repayment Mode</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="repayment_freq" class="form-control" value="<?= $application['applicant_products']['repayment_frequency'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Repayment Period</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="repayment_period" class="form-control" value="<?= $application['repayment_period'] . ' ' . $application['repayment_duration'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Application Status</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vstatus" class="form-control" value="<?= $application['status'] ?>" placeholder="Application Status" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Level</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vlevel" class="form-control" value="<?= $application['level'] ?>" placeholder="Level" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label" for="purpose">Loan Purpose</label>
                                                    <div class="col-md-12">
                                                        <textarea name="vpurpose" id="purpose" class="form-control" readonly>
                                                            <?= $application['purpose'] ?>
                                                        </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- financial position -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Client Financial Position</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- accounts in other banks n institutes -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Other Financial Institutions Accounts</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <label class="control-label col-4 text-center">Institution</label>
                                                    <label class="control-label col-4 text-center">Branch</label>
                                                    <label class="control-label col-4 text-center">Account Type</label>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vinstitute_name" id="instution" class="form-control" value="<?= $application['institute_name'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vinstitute_branch" id="branch" class="form-control" value="<?= $application['institute_branch'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vaccount_type" class="form-control" value="<?= $application['account_type'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vinstitute_name2" id="instution2" class="form-control" value="<?= $application['institute_name2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vinstitute_branch2" id="branch2" class="form-control" value="<?= $application['institute_branch2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vaccount_type2" class="form-control" value="<?= $application['account_type2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- loans in other banks and institutes -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Loans In Other Financial Institutions</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <label class="control-label col-3 text-center">Amount Advance[<?= $settings['currency']; ?>]</label>
                                                    <label class="control-label col-3 text-center">Date Advance</label>
                                                    <label class="control-label col-3 text-center">Loan Duration</label>
                                                    <label class="control-label col-3 text-center">Outstanding Amount[<?= $settings['currency']; ?>]</label>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vamt_advance" id="amt_advance" class="form-control" value="<?= $application['amt_advance'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vdate_advance" id="date_advance" class="form-control" value="<?= $application['date_advance'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vloan_duration" id="loan_duration" class="form-control" value="<?= $application['loan_duration'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vamt_outstanding" id="amt_outstanding" class="form-control" value="<?= $application['amt_outstanding'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vamt_advance2" id="amt_advance2" class="form-control" value="<?= $application['amt_advance2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vdate_advance2" id="date_advance2" class="form-control" value="<?= $application['date_advance2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vloan_duration2" id="loan_duration2" class="form-control" value="<?= $application['loan_duration2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vamt_outstanding2" id="amt_outstanding2" class="form-control" value="<?= $application['amt_outstanding2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Income and Expenses[<?= $settings['currency']; ?>]</h5>
                                            </div>
                                            <?php
                                            $total = $exp_total = $dif = 0;
                                            $total = $application['net_salary'] + $application['farming'] + $application['business'] + $application['others'];
                                            $exp_total = $application['rent'] + $application['education'] + $application['medical'] + $application['transport'] + $application['exp_others'];
                                            $dif = $total - $exp_total;
                                            ?>
                                            <div class="card-body">
                                                <div class="row">
                                                    <p class="col-md-12"></p>
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
                                                                    <input type="text" name="vnet_salary" class="form-control" id="salary" value="<?= $application['net_salary'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="farming" class="col-md-12 form-label">Farming</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="farming" class="form-control" id="farming" value="<?= $application['farming'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="business" class="col-md-12 form-label">Business</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vbusiness" class="form-control" id="business" value="<?= $application['business'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="others" class="col-md-12 form-label">Others</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vothers" class="form-control" id="others" value="<?= $application['others'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="" class="col-md-12 form-label"></label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="" class="form-control" id="" value="" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="total" class="col-md-12 form-label">Total</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="vincome_total" class="form-control" value="<?= $total ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                                                    <input type="text" name="vrent" class="form-control" id="rent" value="<?= $application['rent'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="education" class="col-md-12 form-label">Education</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="veducation" class="form-control" value="<?= $application['education'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="medical" class="col-md-12 form-label">Medical</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vmedical" class="form-control" value="<?= $application['medical'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="transport" class="col-md-12 form-label">Transport</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vtransport" class="form-control" value="<?= $application['transport'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="exp_others" class="col-md-12 form-label">Others</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vexp_others" class="form-control" value="<?= $application['exp_others'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="exp_total" class="col-md-12 form-label">Total</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vexp_total" class="form-control" value="<?= $exp_total ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label for="difference" class="col-md-12 form-label">Difference</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vdifference" class="form-control" value="<?= $dif ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label class="col-md-12 form-label">Difference Status</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="vdif_status" value="<?= $application['dif_status'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- income files -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Income Reciepts\Invoices</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="incomes" class="table table-sm table-striped table-hover">
                                                        <thead class="bg-secondary">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>File Name</th>
                                                                <th>Type</th>
                                                                <th>Preview</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (count($incomes) > 0) :
                                                                $no = 0;
                                                                foreach ($incomes as $income) :
                                                                    $no++;
                                                            ?>
                                                                    <?php if (file_exists("uploads/applications/income/" . $income['file_name']) && $income['file_name']) : ?>
                                                                        <tr>
                                                                            <td><?= $no ?></td>
                                                                            <td><?= $income['file_name'] ?></td>
                                                                            <td><?= substr(strrchr($income['file_name'], '.'), 1) ?></td>
                                                                            <td>
                                                                                <img src="/uploads/applications/income/<?= $income['file_name'] ?>" style="width:70px;height:70px;" class="img-circle" />
                                                                            </td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                            <?php endforeach;
                                                            endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- expense files -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Expenses Reciepts\Invoices</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="expenses" class="table table-sm table-striped table-hover">
                                                        <thead class="bg-secondary">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>File Name</th>
                                                                <th>Type</th>
                                                                <th>Preview</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (count($expenses) > 0) :
                                                                $no = 0;
                                                                foreach ($expenses as $expense) :
                                                                    $no++;
                                                            ?>
                                                                    <?php if (file_exists("uploads/applications/expense/" . $expense['file_name']) && $expense['file_name']) : ?>
                                                                        <tr>
                                                                            <td><?= $no ?></td>
                                                                            <td><?= $expense['file_name'] ?></td>
                                                                            <td><?= substr(strrchr($expense['file_name'], '.'), 1) ?></td>
                                                                            <td>
                                                                                <img src="/uploads/applications/expense/<?= $expense['file_name'] ?>" style="width:70px;height:70px;" class="img-circle" />
                                                                            </td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                            <?php endforeach;
                                                            endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- security -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Loan Security</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- security item details -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Security Item</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Security Item</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="vsecurity_item" class="form-control" value="<?= $application['security_item'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Estimated Value[<?= $settings['currency']; ?>]</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="vest_value" class="form-control" value="<?= $application['est_value'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Details</label>
                                                            <div class="col-md-12">
                                                                <textarea name="vsecurity_info" class="form-control">
                                                                    <?= strip_tags($application['security_info']) ?>
                                                                </textarea readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- collateral files -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Collateral Images</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="collaterals" class="table table-sm table-striped table-hover">
                                                        <thead class="bg-secondary">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>File Name</th>
                                                                <th>Type</th>
                                                                <th>Preview</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (count($collaterals) > 0) :
                                                                $no = 0;
                                                                foreach ($collaterals as $collateral) :
                                                                    $no++;
                                                            ?>
                                                            <?php if (file_exists("uploads/applications/collaterals/" . $collateral['file_name']) && $collateral['file_name']) : ?>
                                                                <tr>
                                                                    <td><?= $no ?></td>
                                                                    <td><?= $collateral['file_name'] ?></td>
                                                                    <td><?= substr(strrchr($collateral['file_name'], '.'), 1) ?></td>
                                                                    <td>
                                                                        <img src="/uploads/applications/collaterals/<?= $collateral['file_name'] ?>" style="width:70px;height:70px;" class="img-circle" />
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <?php endforeach;
                                                            endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- referees -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Referee/ Guarantors</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="ref_name">Full Name</label>
                                                    <input type="text" name="vref_name" id="ref_name" class="form-control" value="<?= $application['ref_name'] ?>" readonly>
                                                </div>
                                            </div> 
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="ref_relation">Relationship</label>
                                                    <input type="text" name="vref_relation" id="ref_relation" class="form-control" value="<?= $application['ref_relation'] ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="ref_job">Occupation</label>
                                                    <input type="text" name="vref_job" id="ref_job" class="form-control" value="<?= $application['ref_job'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="ref_contact">Contact 1</label>
                                                    <input type="tel" name="vref_contact" id="ref_contact" class="form-control" value="<?= $application['ref_contact'] ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="ref_alt_contact">Contact 2</label>
                                                    <input type="tel" name="vref_alt_contact" id="ref_alt_contact" class="form-control" value="<?= $application['ref_alt_contact'] ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="ref_email">Email</label>
                                                    <input type="email" name="vref_email" id="ref_email" class="form-control" value="<?= $application['ref_email'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="ref_address2">Address</label>
                                                    <textarea name="vref_address" id="ref_address" class="form-control" readonly>
                                                        <?= $application['ref_address'] ?>
                                                    </textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="ref_name2">Full Name</label>
                                                                <input type="text" name="vref_name2" id="ref_name2" class="form-control" value="<?= $application['ref_name2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="relation2">Relationship</label>
                                                                <input type="text" name="vref_relation2" id="ref_relation" class="form-control" value="<?= $application['ref_relation2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="ref_job2">Occupation</label>
                                                                <input type="text" name="vref_job2" id="ref_job2" class="form-control" value="<?= $application['ref_job2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="ref_contact2">Contact 1</label>
                                                                <input type="tel" name="vref_contact2" id="ref_contact2" class="form-control" value="<?= $application['ref_contact2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="ref_alt_contact2">Contact 2</label>
                                                                <input type="tel" name="vref_alt_contact2" id="ref_alt_contact2" class="form-control" value="<?= $application['ref_alt_contact2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="ref_email2">Email</label>
                                                                <input type="email" name="vref_email2" id="ref_email2" class="form-control" value="<?= $application['ref_email2'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="ref_address2">Address</label>
                                                                <textarea name="vref_address2" id="ref_address2" class="form-control" readonly>
                                                        <?= $application['ref_address2'] ?>
                                                    </textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="ref_alt_contact2">Officer</label>
                                                            <input name="vloan_officer" value="<?= $application['staff_name'] ?>" class="form-control" type="text" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="ref_alt_contact2">Created At</label>
                                                            <input name="vcreated_at" value="<?= $application['created_at'] ?>" class="form-control" type="text" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="ref_alt_contact2">Updated At</label>
                                                            <input name="vupdated_at" value="<?= $application['updated_at'] ?>" class="form-control" type="text" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <label class="control-label col-md-12">Applicant Signature</label>
                                                <div class="form-group float-right">
                                                    <div class="col-sm-12">
                                                        <?php if ($application['signature'] != ''  && file_exists("uploads/clients/signatures/" . $application['signature'])) : ?>
                                                            <img src="/uploads/clients/signatures/<?= $application['signature'] ?>" alt="Photo" class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                                        <?php else : ?>
                                                            <img src="/assets/dist/img/sign.png" alt="Photo" class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </section>
                            <hr>
                            <!-- charges -->
                            <section>
                                <div class="row">
                                    <div class="col-12">
                                        <span>Charges</span>
                                        <p class="" style="border-top: 1px solid;"></p>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Payables</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive" id="applPayables">
                                            <table id="applPay" class="table table-sm table-stripped">
                                                <thead class="bg-secondary">
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Particular</th>
                                                        <th>Charge Method</th>
                                                        <th>Charge Fee</th>
                                                        <th>Total Amount [<?= $settings['currency']; ?>]</th>
                                                        <th>Paid[<?= $settings['currency']; ?>]</th>
                                                        <th>Balance[<?= $settings['currency']; ?>]</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="applicationPayables">
                                                    <?php if (count($payables) > 0) :
                                                        $no  = $totalCharge = 0;
                                                        foreach ($payables as $payable) :
                                                            $no++;
                                                            $paid = $balance = 0;
                                                            if (strtolower($payable['charge_method']) == 'amount') {
                                                                $totalCharge = $payable['charge'];
                                                            } else {
                                                                $totalCharge = ($payable['charge'] / 100) * $application['principal'];
                                                            }
                                                            if (count($payments) > 0) {
                                                                $paid = $balance = 0;
                                                                foreach ($payments as $payment) {
                                                                    if ($payment['particular_id'] == $payable['id']) {
                                                                        $paid += $payment['amount'];
                                                                    }
                                                                }
                                                                $balance = $totalCharge - $paid;
                                                            }
                                                    ?>
                                                            <tr>
                                                                <td><?= $no ?></td>
                                                                <td><?= $payable['particular_name'] ?></td>
                                                                <td><?= $payable['charge_method'] ?></td>
                                                                <td><?= $payable['charge'] ?></td>
                                                                <td><?= $totalCharge ?></td>
                                                                <td><?= $paid ?></td>
                                                                <td><?= $balance ?></td>
                                                                <td align="center"><?= ($balance <= 0) ? '<i class="fas fa-check-double text-success"></i>' : '<i class"fas fa-times text-danger"></i>'; ?></td>
                                                            </tr>
                                                        <?php endforeach;
                                                    else : ?>
                                                        <tr>
                                                            <td colspan="7" align="center">No data found</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Payment History</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="applPayments" class="table table-sm table-striped table-hover">
                                                <thead class="bg-secondary">
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Particular</th>
                                                        <th>Payment Method</th>
                                                        <th>Amount [<?= $settings['currency']; ?>]</th>
                                                        <th>Ref ID</th>
                                                        <th>Date</th>
                                                        <th>Officer</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (count($payments) > 0) :
                                                        $no = 0;
                                                        foreach ($payments as $payment) :
                                                            $no++;
                                                    ?>
                                                            <tr>
                                                                <td><?= $no ?></td>
                                                                <td><?= $payment['particular_id'] ?></td>
                                                                <td><?= $payment['payment_id'] ?></td>
                                                                <td><?= $payment['amount'] ?></td>
                                                                <td><?= $payment['ref_id'] ?></td>
                                                                <td><?= $payment['created_at'] ?></td>
                                                                <td><?= $payment['staff_name'] ?></td>
                                                            </tr>
                                                        <?php endforeach;
                                                    else : ?>
                                                        <tr>
                                                            <td colspan="7" align="center">No data found</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <hr>
                            <!-- remarks -->
                            <section>
                                <div class="row">
                                    <div class="col-12">
                                        <span>Remarks</span>
                                        <p class="" style="border-top: 1px solid;"></p>
                                    </div>
                                </div>
                                <?php if (count($remarks)) :
                                    $color = '';
                                    foreach ($remarks as $remark) :
                                        // change office action color based onaction choosen
                                        if (strtolower($remark['action']) == 'declined') {
                                            $color = "text-danger";
                                        } else if (strtolower($remark['action']) == 'approved') {
                                            $color = "text-success";
                                        } else {
                                            $color = "text-info";
                                        }
                                ?>
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Level: <?= $remark['level'] ?></h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="post clearfix">
                                                    <div class="user-block">
                                                        <span>
                                                            <span>Action: <b class="<?= $color ?>"><?= $remark['action'] ?></b></span>
                                                            <span class="description">Status: <?= $remark['status'] ?></span>
                                                            <span class="description">Officer: <?= $remark['staff_name'] ?></span>
                                                        </span>
                                                    </div>
                                                    <p class="p-3"><?= $remark['remarks'] ?></p>
                                                    <p class="user-block">
                                                        <span class="description float-left">Date: <?= $remark['created_at'] ?></span>
                                                        <span class="description float-right">Updated: <?= $remark['updated_at'] ?></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                <?php endforeach;
                                endif; ?>
                            </section>
                            <hr>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <footer>
        <p class="text-center">
            &copy;<?= date('Y'); ?> Sai Pali Infotech <?= $settings['system_name'] ?>. All rights reserved.
        </p>
    </footer>
    <script>
        window.addEventListener("load", window.print());
        window.oncancel = function() {
            history.back(-1);
        }
        window.onafterprint = function() {
            history.back(-1);
        };
    </script>
    </body>

</html>