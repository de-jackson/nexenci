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
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="others" class=" form-control" id="others" placeholder="Client name or occupation" value="<?= $user["name"]; ?>" readonly>
                                        <!-- <i><small class="fw-semibold">Click in the box above to enter client name</small></i> -->
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" data-placeholder="Select gender" style="width: 100%;" id="gender" name="gender">
                                            <option value="">Select</option>
                                        </select>
                                        <!-- <i><small class="fw-semibold">Click in the box above to enter select gender</small></i> -->
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="start_date" value="" class="form-control getDatePicker" id="start_date" placeholder="Start date">
                                        <!-- <i><small class="fw-semibold">Click in the box above to select the start date</small></i> -->
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="end_date" value="" class="form-control getDatePicker" id="end_date" placeholder="End date">
                                        <!-- <i><small class="fw-semibold">Click in the box above to select the end date</small></i> -->
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="account_no" class=" form-control" id="account_no" placeholder="Client account number" value="<?= $user["account_no"]; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select payment method" style="width: 100%;" id="payment_id" name="payment_id">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="reference_id" class=" form-control" id="reference_id" placeholder="Reference number">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select officer" style="width: 100%;" id="staff_id" name="staff_id">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2 branch_id" multiple="multiple" data-placeholder="Select a branch" style="width: 100%;" id="branch_id" name="branch_id[]">
                                            <option value="">Select</option>
                                        </select>
                                        <i><small class="fw-semibold">Click in the box above to select multiple branches</small></i>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" data-placeholder="Select transaction type" style="width: 100%;" id="entry_typeId" name="entry_typeId">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" data-placeholder="Select year" style="width: 100%;" id="year" name="year">
                                            <option value="">Select</option>
                                        </select>
                                        <i>
                                            <small class="fw-semibold">Click in the box above</small>
                                        </i>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary btn-block" id="filter-savings" value="filter" onclick="filterClientSavingsReport()"><i class="fa fa-search fa-fw"></i></button>
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
<!-- clients monthly savings table
<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Monthly Savings Collection
            </div>
        </div>
        <div class="card-body">
            <div id="filter_savings">

            </div>
        </div>
    </div>
</div>

<div class="col-xl-12">
    clients counter table
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Monthly Number of Clients Savings Collection
            </div>
        </div>
        <div class="card-body">
            <div id="client_counter">

            </div>
        </div>
    </div>
</div> -->

<!-- member statement -->
<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Member Statement
            </div>
        </div>
        <div class="card-body">
            <div id="client_savings">

            </div>
        </div>
    </div>
</div>

<!-- <div class="col-xl-12">
    clients savings account
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Graphical Monthly Savings Collection
            </div>
        </div>
        <div class="card-body">
            <div class="row gx-3 gy-2 align-items-center mt-0">
                AREA CHART
                <div class="col-md-6">
                    <div class="card border border-primary">
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
                LINE CHART
                <div class="col-md-6">
                    <div class="card border border-primary">
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
                <div class="col-md-6">
                    COLUMN CHART
                    <div class="card border border-success">
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
                <div class="col-md-6">
                    RADAR CHART
                    <div class="card border border-success">
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
</div> -->

<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">

</script>
<script src="/assets/client/reports/savings/savings.js"></script>
<script src="/assets/client/main/options.js"></script>

<?= $this->endSection() ?>