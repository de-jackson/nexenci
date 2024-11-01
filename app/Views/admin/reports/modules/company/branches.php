<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card">
        <!-- description -->
        <div class="card-header">
            <h4>Branches Report</h4>
        </div>
        <!--  advanced search form -->
        <div class="card-body">
            <div class="h5 fw-semibold mb-0">Advanced Search:</div>
            <p class="mt-3">
                All fields are optional. You can type or select as many fields as you like.
            </p>
            <div class="contact-header">
                <div class="d-sm-flex d-block align-items-center justify-content-between">
                    <div class="d-flex mt-sm-0 mt-2 align-items-center">
                        <form id="form" autocomplete="off">
                            <div class="row gy-2 mt-2">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="others" class=" form-control" id="others" placeholder="Client Name or Occupation">
                                        <i><small class="fw-semibold">Click in the box above to enter client name</small></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Gender" style="width: 100%;" id="gender" name="gender">
                                            <option value="">Select</option>
                                        </select>
                                        <i><small class="fw-semibold">Click in the box above to enter select gender</small></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="start_date" value="" class="form-control getDatePicker" id="start_date" placeholder="Start Date">
                                        <i><small class="fw-semibold">Click in the box above to select the start date</small></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="end_date" value="" class="form-control getDatePicker" id="end_date" placeholder="End Date">
                                        <i><small class="fw-semibold">Click in the box above to select the end date</small></i>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="account_no" class=" form-control" id="account_no" placeholder="Client Account Number">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Payment Method" style="width: 100%;" id="payment_id" name="payment_id">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="reference_id" class=" form-control" id="reference_id" placeholder="Reference Number">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Officer" style="width: 100%;" id="staff_id" name="staff_id">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 branch_id form-control" multiple="multiple" data-placeholder="Select Branch or Branches" style="width: 100%;" id="branch_id" name="branch_id[]">
                                            <option value="">Select</option>
                                        </select>
                                        <i><small class="fw-semibold">Click in the box above to select multiple branches</small></i>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Transaction Type" style="width: 100%;" id="entry_typeId" name="entry_typeId">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Year" style="width: 100%;" id="year" name="year">
                                            <option value="">Select</option>
                                        </select>
                                        <i>
                                            <small class="fw-semibold">Click in the box above</small>
                                        </i>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary btn-block" id="filter-savings" value="filter" onclick="filterSavings()"><i class="fa fa-search fa-fw"></i></button>
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
    <!-- branches monthly clients savings table -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Monthly Branches Savings Collection
            </div>
        </div>
        <div class="card-body">
            <div id="branches_savings">
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- clients counter table -->
    <div class="card">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Monthly Number of Clients Savings Collection</div>
            </div>
            <div class="card-body">
                <div id="client_counter">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- clients savings table -->
    <div class="card">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Clients Savings Collection</div>
            </div>
            <div class="card-body">
                <div id="client_savings">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- clients savings account -->
    <div class="card">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Graphical Monthly Savings Collection</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- AREA CHART -->
                    <div class="col-md-6">
                        <div class="card border border-primary">
                            <div class="card-header">
                                <div class="card-title">Area Chart</div>
                            </div>
                            <div class="card-body">
                                <div id="area-spline"></div>
                            </div>
                        </div>
                    </div>
                    <!-- LINE CHART -->
                    <div class="col-md-6">
                        <div class="card border border-info">
                            <div class="card-header">
                                <div class="card-title">Line Chart</div>
                            </div>
                            <div class="card-body">
                                <div id="line-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- COLUMN CHART -->
                    <div class="col-md-6">
                        <div class="card border border-success">
                            <div class="card-header">
                                <div class="card-title">Column Chart</div>
                            </div>
                            <div class="card-body">
                                <div id="column-chart"></div>
                            </div>
                        </div>
                    </div>
                    <!-- RADAR CHART -->
                    <div class="col-md-6">
                        <div class="card border border-success">
                            <div class="card-header">
                                <div class="card-title">Radar Chart</div>
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
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">

</script>
<script src="/assets/scripts/reports/company/branches.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>

<?= $this->endSection() ?>