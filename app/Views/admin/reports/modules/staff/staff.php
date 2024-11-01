<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card">
        <!-- description -->
        <div class="card-header">
            <h4>Staff</h4>
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
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="others" class=" form-control" id="others" placeholder="Name or Marital Status or Nationality">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Gender" style="width: 100%;" id="gender" name="gender">
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
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="account_no" class=" form-control" id="account_no" placeholder="ID Number">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Account Status" style="width: 100%;" id="status" name="status">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="reference_id" class=" form-control" id="reference_id" placeholder="NIN Number">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Officer" style="width: 100%;" id="staff_id" name="staff_id">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2 department_id" name="department_id" id="department_id" style="width: 100%;" data-placeholder="Select Department">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2 position_id" name="position_id" id="position_id" style="width: 100%;" data-placeholder="Select Position">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Appointment Type" style="width: 100%;" id="appointment_type" name="appointment_type">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Account Type" style="width: 100%;" id="staff_account_type" name="staff_account_type">
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
                                        <input type="text" name="qualification" class=" form-control" id="qualification" placeholder="Qualifications">
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
                                        <button type="button" class="btn btn-primary btn-block" id="searchButton" value="filter" onclick="filterStaffsAccount()"><i class="fa fa-search fa-fw"></i></button>
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
    <!-- staff counter table -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Monthly Number of Staff
            </div>
        </div>
        <div class="card-body">
            <div id="staff_counter">

            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- staff table -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Staff Report
            </div>
        </div>
        <div class="card-body">
            <div id="staff_table">

            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- charts of number of staff account -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Graphical Monthly Staff Report
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- AREA CHART -->
                <div class="col-md-6">
                    <div class="card card-primary">
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
                    <div class="card card-info">
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
            <div class="row">
                <div class="col-md-6">
                    <!-- COLUMN CHART -->
                    <div class="card card-success">
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
                    <!-- RADAR CHART -->
                    <div class="card card-success">
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title text-success"> </h4>
                <div class="close">
                    <btn type="button" class="btn btn-md btn-secondary" onclick="printStaffAccount()" id="export">
                        <i class="fas fa-print text-light"></i>
                    </btn>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Name</label>
                                            <div class="col-md-12">
                                                <input name="staff_name" placeholder="Name" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Gender</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="gender" placeholder="Gender" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Nationality</label>
                                            <div class="col-md-12">
                                                <input type="text" name="nationality" placeholder="Nationality" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Marital Status</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="marital_status" placeholder="Marital Status" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Religion</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="religion" placeholder="Religion" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label col-md-12 text-center">Profile Photo</label>
                                <center>
                                    <div class="form-group" id="photo-preview">
                                        <div class="col-md-12">
                                            (No photo)
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </center>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Phone Number</label>
                                    <div class="col-md-12">
                                        <input name="mobile" placeholder="Phone Number" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Alternate Phone Number</label>
                                    <div class="col-md-12">
                                        <input name="alternate_mobile" placeholder="Alternate Phone Number" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <input name="email" placeholder="Email" class="form-control" type="email" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">ID Number</label>
                                    <div class="col-md-12">
                                        <input name="staffID" placeholder="ID Number" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Department</label>
                                    <div class="col-md-12">
                                        <input type="text" name="department" class="form-control" placeholder="Department" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Position</label>
                                    <div class="col-md-12">
                                        <input type="text" name="position_id" class="form-control" placeholder="Position" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="control-label col-md-12">ID Photo</label>
                                <div class="form-group" id="id-preview">
                                    <div class="col-md-12">
                                        (No photo)
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">ID Type</label>
                                            <div class="col-md-12">
                                                <input name="id_type" placeholder="ID Type" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">NIN Number</label>
                                            <div class="col-md-12">
                                                <input name="id_number" placeholder="NIN Number" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">ID Expiry Date</label>
                                            <div class="col-md-12">
                                                <input name="id_expiry" placeholder="ID Expiry Date" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Address</label>
                                    <div class="col-md-12">
                                        <textarea name="address" placeholder="Address" class="form-control" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Qualifications</label>
                                    <div class="col-md-12">
                                        <textarea name="qualifications" placeholder="Qualifications" class="form-control" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Branch</label>
                                    <div class="col-md-12">
                                        <input type="text" name="branch_id" class="form-control" placeholder="Branch" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Salary [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="salary_scale" placeholder="Salary Scale" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Appointment Type</label>
                                    <div class="col-md-12">
                                        <input name="appointment_type" placeholder="Appointment Type" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Bank Name</label>
                                    <div class="col-md-12">
                                        <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Bank Branch</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="bank_branch" placeholder="Bank Branch" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Bank Account Number</label>
                                    <div class="col-md-12">
                                        <input name="bank_account" class="form-control" type="text" placeholder="Bank Account Number" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">D.O.B</label>
                                            <div class="col-md-12">
                                                <input name="date_of_birth" class="form-control" type="date" placeholder="Date of Date" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Registered Date</label>
                                            <div class="col-md-12">
                                                <input name="created_at" placeholder="Registration Date" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Updated At</label>
                                            <div class="col-md-12">
                                                <input name="updated_at" placeholder="Updated Date" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <label class="control-label col-md-12">Signature</label>
                                <div class="form-group" id="signature-preview">
                                    <div class="col-md-12">
                                        (No Sign)
                                        <span class="help-block"></span>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form well well-lg one_well ">
                <form action="#" id="viewform" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <center>
                            <div class="form-group" id="photo-preview">
                                <label class="control-label col-md-12"></label>
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
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">

</script>
<script src="/assets/scripts/reports/staff/staff.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>

<?= $this->endSection() ?>