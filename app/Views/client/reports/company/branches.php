<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<!-- Content Header (Page header) -->
<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <h1 class="page-title fw-semibold fs-18 mb-0">Branches Report</h1>
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
<!-- report body -->
<!-- Start::row-1 -->
<div class="row gx-3 gy-2 align-items-center mt-0">
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
                                            <input type="text" name="others" class=" form-control" id="others" placeholder="Client Name or Occupation">
                                            <i><small class="fw-semibold">Click in the box above to enter client name</small></i>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <select class="select2" data-placeholder="Select Gender" style="width: 100%;" id="gender" name="gender">
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
                                            <select class="select2" data-placeholder="Select Payment Method" style="width: 100%;" id="payment_id" name="payment_id">
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
                                            <select class="select2" data-placeholder="Select Officer" style="width: 100%;" id="staff_id" name="staff_id">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <select class="select2 branch_id" multiple="multiple" data-placeholder="Select Branch or Branches" style="width: 100%;" id="branch_id" name="branch_id[]">
                                                <option value="">Select</option>
                                            </select>
                                            <i><small class="fw-semibold">Click in the box above to select multiple branches</small></i>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <select class="select2" data-placeholder="Select Transaction Type" style="width: 100%;" id="entry_typeId" name="entry_typeId">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <select class="select2" data-placeholder="Select Year" style="width: 100%;" id="year" name="year">
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
</div>
<!-- Start::row-2 -->
<div class="row gx-3 gy-2 align-items-center mt-0">
    <div class="col-xl-12">
        <!-- branches monthly clients savings table -->
        <div class="card border border-warning custom-card">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Monthly Branches Savings Collection</div>
                </div>
                <div class="card-body">
                    <div id="branches_savings">
                    </div>
                </div>
            </div>
        </div>
        <!-- clients monthly savings table -->
        <div class="card border border-warning custom-card">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Monthly Savings Collection</div>
                </div>
                <div class="card-body">
                    <div id="filter_savings">

                    </div>
                </div>
            </div>
        </div>
        <!-- clients counter table -->
        <div class="card border border-warning custom-card">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Monthly Number of Clients Savings Collection</div>
                </div>
                <div class="card-body">
                    <div id="client_counter">

                    </div>
                </div>
            </div>
        </div>
        <!-- clients savings table -->
        <div class="card border border-warning custom-card">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Clients Savings Collection</div>
                </div>
                <div class="card-body">
                    <div id="client_savings">

                    </div>
                </div>
            </div>
        </div>
        <!-- clients savings account -->
        <div class="card border border-warning custom-card">
            <div class="card custom-card">
                <div class="card-header custom-card">
                    <div class="card-title">Graphical Monthly Savings Collection</div>
                </div>
                <div class="card-body">
                    <div class="row gx-3 gy-2 align-items-center mt-0">
                        <!-- AREA CHART -->
                        <div class="col-md-6">
                            <div class="card border border-primary custom-card">
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
                            <div class="card border border-info custom-card">
                                <div class="card-header">
                                    <div class="card-title">Line Chart</div>
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
                                    <div class="card-title">Column Chart</div>
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
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">

</script>
<script src="/assets/scripts/reports/company/branches.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>

<?= $this->endSection() ?>