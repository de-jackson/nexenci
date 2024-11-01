<?= $this->extend("layout/client"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card custom-card mt-4">
        <!-- description -->
        <div class="card-header">
            All fields are optional. You can type or select as many fields as you like.
        </div>
        <!--  advanced search form -->
        <div class="card-body">
            <div class="h5 fw-semibold mb-0">Advanced Search:</div>
            <div class="contact-header">
                <div class="d-sm-flex d-block align-items-center justify-content-between">
                    <div class="d-flex mt-sm-0 mt-2 align-items-center">
                        <form id="form" autocomplete="off">
                            <div class="row gy-2 mt-2">
                                <!-- First row -->
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" data-placeholder="Select Gender" style="width: 100%;" id="gender" name="gender">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="others" class="form-control" id="others" placeholder="Loan Product or Frequency">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="start_date" value="" class="form-control getDatePicker" id="start_date" placeholder="Disbursement Start Date">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <input type="text" name="end_date" value="" class="form-control getDatePicker" id="end_date" placeholder="Disbursement End Date">
                                    </div>
                                </div>
                                <!-- Second row -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="account_no" value="<?= $user['account_no']; ?>" class=" form-control" id="account_no" placeholder="Client Account No" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" data-placeholder="Select Account Status" style="width: 100%;" id="status" name="client_account_status">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="disbursement_code" value="" class=" form-control" id="disbursement_code" placeholder="Disbursement Code">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" data-placeholder="Select Officer" style="width: 100%;" id="staff_id" name="staff_id">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Third row -->
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="client_name" value="<?= $user['name']; ?>" class=" form-control" id="client_name" placeholder="Client Full Name" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="start_expiry_date" value="" class="form-control getDatePicker" id="start_expiry_date" placeholder="Start Loan Expiry Date">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="end_expiry_date" value="" class="form-control getDatePicker" id="end_expiry_date" placeholder="End Loan Expiry Date">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" data-placeholder="Select Disbursement Status" style="width: 100%;" id="disbursement_status" name="disbursement_status">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Fourth row -->
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2 branch_id" multiple="multiple" data-placeholder="Select Branch or Branches" style="width: 100%;" id="branch_id" name="branch_id[]">
                                            <option value="">Select</option>
                                        </select>
                                        <i><small class="fw-semibold">Click in the box above to select multiple branches</small></i>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="start_amount_applied" class=" form-control" id="start_amount_applied" placeholder="Disbursed (From)">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="end_amount_applied" class=" form-control" id="end_amount_applied" placeholder="Disbursed (To)">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" data-placeholder="Select Year" style="width: 100%;" id="year" name="year">
                                            <option value="">Select</option>
                                        </select>
                                        <i>
                                            <small class="fw-semibold">Click in the box above</small>
                                        </i>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary btn-block" id="searchButton" value="filter" onclick="searchDisbursementLoans()"><i class="fa fa-search fa-fw"></i></button>
                                    </div>
                                </div>
                                <!-- End of fourth row -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- loan Repayments amount table -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Monthly Loan Repayments Report
            </div>
        </div>
        <div class="card-body">
            <div id="loan_repayments">

            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- loan disbursement counter table -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Monthly Total Number of Loan Disbursement
            </div>
        </div>
        <div class="card-body">
            <div id="loan_disbursement_counter">

            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- loan disbursement amount table -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Monthly Loan Principal Report
            </div>
        </div>
        <div class="card-body">
            <div id="loan_principal_counter">

            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- loan interest amount table -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Monthly Loan Interest Report
            </div>
        </div>
        <div class="card-body">
            <div id="loan_interest_counter">

            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- loan disbursement table -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Loan Disbursement Report
            </div>
        </div>
        <div class="card-body">
            <div id="loan_disbursements_table">

            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- charts of number of total loan Repayment -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Graphical Monthly Loan Repayment Report
            </div>
        </div>
        <div class="card-body">
            <div class="row gx-3 gy-2 align-items-center mt-0">
                <!-- AREA CHART -->
                <div class="col-md-6">
                    <div class="card border border-primary custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                Area Chart
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="area-spline"></div>
                        </div>
                    </div>
                </div>
                <!-- LINE CHART -->
                <div class="col-md-6">
                    <div class="card border border-primary custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                Line Chart
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="line-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gx-3 gy-2 align-items-center mt-0">
                <!-- COLUMN CHART -->
                <div class="col-md-6">
                    <div class="card border border-success custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                Column Chart
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="column-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- RADAR CHART -->
                <div class="col-md-6">
                    <div class="card border border-success custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                Radar Chart
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="radar-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">

</script>
<script src="/assets/client/reports/loan/repayments.js"></script>
<script src="/assets/client/main/options.js"></script>

<?= $this->endSection() ?>