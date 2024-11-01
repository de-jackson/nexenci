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
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="others" class="form-control" id="others" placeholder="Loan Product Name or Loan Frequency">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" data-placeholder="Select Status" style="width: 100%;" id="status" name="status">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="start_date" value="" class="form-control getDatePicker" id="start_date" placeholder="Start Date">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="end_date" value="" class="form-control getDatePicker" id="end_date" placeholder="End Date">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" multiple="multiple" data-placeholder="Select Loan Repayment Durations" style="width: 100%;" id="loanrepaymentdurations" name="loanrepaymentdurations[]">
                                            <option value="">Select</option>
                                        </select>
                                        <i><small class="fw-semibold">Click in the box above to select multiple durations</small></i>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" data-placeholder="Select Interest Type" style="width: 100%;" id="interesttypes" name="interesttypes">
                                            <option value="">Select</option>
                                        </select>
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
                                        <button type="button" class="btn btn-primary btn-block" id="searchButton" value="filter" onclick="filterLoanProduct()"><i class="fa fa-search fa-fw"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- loan prodcuts counter table -->
    <!-- <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Monthly Number of Loan Products
                    </div>
                </div>
                <div class="card-body">
                    <div id="loan_products_counter">

                    </div>
                </div>
            </div> -->
    <!-- loan products table -->
</div>
<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Loan Products Report
            </div>
        </div>
        <div class="card-body">
            <div id="loan_products_table">

            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- charts of number of loan products -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Graphical Monthly Loan Products Report
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
                            <h3 class="card-title">
                                Line Chart
                            </h3>
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

<!-- view modal -->
<div class="modal fade" data-bs-backdrop="static" id="view_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title text-success"> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Product Name</label>
                                    <div class="col-md-12">
                                        <input name="product_name" placeholder="Product Name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Repayment Frequency</label>
                                    <div class="col-md-12">
                                        <input class="form-control" name="repayment_freq" placeholder="Repayment Frequency" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Interest Rate(%)</label>
                                    <div class="col-md-12">
                                        <input name="interest_rate" placeholder="interest rate" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Interest Method</label>
                                    <div class="col-md-12">
                                        <input name="interest_type" placeholder="interest method" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Repayment Period</label>
                                    <div class="col-md-12">
                                        <input name="repayment_period" placeholder="Repayment Period" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Status</label>
                                    <div class="col-md-12">
                                        <input class="form-control" placeholder="Status" name="status" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Product Description</label>
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
                                    <label class="control-label col-md-12">Product Features</label>
                                    <div class="col-md-12">
                                        <textarea name="product_features" placeholder="Product Features" class="form-control" id="seeSummernote" readonly>
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Created At</label>
                                    <div class="col-md-12">
                                        <input name="created_at" placeholder="created at" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Updated At</label>
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
    var accountStatus;
</script>
<script src="/assets/client/reports/loan/products.js"></script>
<script src="/assets/client/main/options.js"></script>

<?= $this->endSection() ?>