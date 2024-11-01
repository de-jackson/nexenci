<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card">
        <!-- description -->
        <div class="card-header">
            <h4>Clients</h4>
        </div>
        <!-- client advanced search form -->
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
                                        <!-- <i><small class="fw-semibold">Click in the box above to enter client name</small></i> -->
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Gender" style="width: 100%;" id="gender" name="gender">
                                            <option value="">Select</option>
                                        </select>
                                        <!-- <i><small class="fw-semibold">Click in the box above to enter select gender</small></i> -->
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="start_date" value="" class="form-control getDatePicker" id="start_date" placeholder="Start Date">
                                        <!-- <i><small class="fw-semibold">Click in the box above to select the start date</small></i> -->
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="end_date" value="" class="form-control getDatePicker" id="end_date" placeholder="End Date">
                                        <!-- <i><small class="fw-semibold">Click in the box above to select the end date</small></i> -->
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="account_no" class=" form-control" id="account_no" placeholder="Client Account Number">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Account Status" style="width: 100%;" id="status" name="status">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="reference_id" class=" form-control" id="reference_id" placeholder="NIN Number">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 form-control" data-placeholder="Select Officer" style="width: 100%;" id="staff_id" name="staff_id">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <select class="select2 branch_id" multiple="multiple" data-placeholder="Select a Branch or Branches" style="width: 100%;" id="branch_id" name="branch_id[]">
                                            <option value="">Select</option>
                                        </select>
                                        <i><small class="fw-semibold">Click in the box above to select multiple branches</small></i>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="location" class=" form-control" id="location" placeholder="Business Location">
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
                                        <button type="button" class="btn btn-primary btn-block" id="btnSearch" value="filter" onclick="filterClientsAccount()"><i class="fa fa-search fa-fw"></i></button>
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
    <!-- clients counter table -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Monthly Number of Clients</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="clients_counter">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- clients table -->
    <div class="card">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Clients Report</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="clients_table">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <!-- charts of number of clients account -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Graphical Monthly Clients Report</div>
        </div>
        <div class="card-body">
            <!-- charts row 1 -->
            <div class="row">
                <!-- AREA CHART -->
                <div class="col-md-6">
                    <div class="card border border-primary custom-card">
                        <div class="card-header">
                            <div class="card-title">Area Chart</div>
                        </div>
                        <div class="card-body">
                            <div id="area-spline"></div>
                            <!-- <div class="chart">
                                        <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div> -->
                        </div>
                    </div>
                </div>
                <!-- LINE CHART -->
                <div class="col-md-6">
                    <div class="card border border-primary custom-card">
                        <div class="card-header">
                            <div class="card-title">Line Chart</div>
                        </div>
                        <div class="card-body">
                            <div id="line-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- charts row 2 -->
            <div class="row">
                <div class="col-md-6">
                    <!-- Column Chart -->
                    <div class="card border border-info custom-card">
                        <div class="card-header">
                            <div class="card-title">Column Chart</div>
                        </div>
                        <div class="card-body">
                            <div id="column-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Polar Area Chart -->
                    <div class="card border border-info custom-card">
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

<!-- view modal -->
<div class="modal fade" data-bs-backdrop="static" id="view_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title text-success"> </h4>
                <div class="close">
                    <btn type="button" class="btn btn-md btn-secondary" onclick="printClientInfo()" id="export">
                        <i class="fas fa-print text-light"></i>
                    </btn>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body form">
                <form class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Full Name</label>
                                            <div class="col-md-12">
                                                <input name="vname" placeholder="Full Name" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Branch</label>
                                            <div class="col-md-12">
                                                <input name="vbranch_id" placeholder="branch" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Gender</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="vgender" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Nationality</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="vnationality" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Marital Status</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="vmarital_status" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Religion</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="vreligion" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <label class="control-label col-md-12">Profile Photo</label>
                                <div class="form-group" id="photo-preview">
                                    <div class="col-md-12">
                                        (No photo)
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Phone Number 1</label>
                                    <div class="col-md-12">
                                        <input name="vmobile" placeholder="Phone Number" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Phone Number 2</label>
                                    <div class="col-md-12">
                                        <input name="valternate_no" placeholder="Phone Number 2" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <input name="vemail" placeholder="example@mail.com" class="form-control" type="email" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Account Number</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vaccount_no" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Account Type</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vaccount_type" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Account Balance</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vaccount_balance" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Address</label>
                                    <div class="col-md-12">
                                        <textarea name="vresidence" placeholder="address" class="form-control" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">D.O.B</label>
                                    <div class="col-md-12">
                                        <input name="vdob" placeholder="" class="form-control" type="date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Occupation</label>
                                    <div class="col-md-12">
                                        <input name="voccupation" placeholder="occupation" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Age(Years)</label>
                                    <div class="col-md-12">
                                        <input name="age" placeholder="next of kin phone2" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Id Type</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vid_type" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Id Number</label>
                                    <div class="col-md-12">
                                        <input name="vid_number" placeholder="id number" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Id Expiry Date</label>
                                    <div class="col-md-12">
                                        <input name="vid_expiry" placeholder="" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Business Location</label>
                                    <div class="col-md-12">
                                        <textarea name="vjob_location" placeholder="address" class="form-control" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Next of Kin</label>
                                    <div class="col-md-12">
                                        <input name="vnext_of_kin" placeholder="next of kin" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Next of Kin Relationship</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vnok_relationship" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Next Of Kin Address</label>
                                    <div class="col-md-12">
                                        <textarea name="vnok_address" placeholder="address" class="form-control" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Next of Kin Phone1</label>
                                            <div class="col-md-12">
                                                <input name="vnok_phone" placeholder="next of kin phone" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Next of Kin Phone2</label>
                                            <div class="col-md-12">
                                                <input name="vnok_alt_phone" placeholder="next of kin phone2" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Next of Kin Email</label>
                                            <div class="col-md-12">
                                                <input name="vnok_email" placeholder="Next of Kin Email" class="form-control" type="text" readonly>
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
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Registration Date</label>
                                    <div class="col-md-12">
                                        <input name="created_at" placeholder="next of kin phone2" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Updated At</label>
                                    <div class="col-md-12">
                                        <input name="update_at" placeholder="next of kin phone2" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Responsible Officer</label>
                                    <div class="col-md-12">
                                        <input name="staff_name" placeholder="responsible officer" class="form-control" type="text" readonly>
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
<script src="/assets/scripts/reports/clients/clients.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>

<?= $this->endSection() ?>