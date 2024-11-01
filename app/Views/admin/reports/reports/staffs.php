<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<!-- date filter -->
<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <div class="contact-header">
                <div class="d-sm-flex d-block align-items-center justify-content-between">
                    <div class="h5 fw-semibold mb-0">Filter Date of Creation:</div>
                    <div class="d-flex mt-sm-0 mt-2 align-items-center">
                        <form action="/admin/reports/view/<?= $report; ?>" class="form-horizontal" method="post" autocomplete="off">
                            <?= csrf_field() ?>
                            <div class="form-body">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <!-- filter inputs -->
                                    <div class="col-md-5">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <label class="control-label fw-bold text-info col-12 text-center">Filters</label>
                                            <div class="col-md-6">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-4 text-right" for="val">Filter:</label>
                                                    <div class="col-8">
                                                        <select class="control-label fw-bold" name="filter" id="filter">
                                                            <?php
                                                            if (count($filters) > 0) :
                                                                foreach ($filters as $key => $opt) :
                                                            ?>
                                                                    <option value="<?= $key ?>" <?= ($opt == $filter) ? "selected" : ''; ?>>
                                                                        <?= $opt; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            <?php else : ?>
                                                                <option value="">No Filter</option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="selectVal">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-4 text-right" for="value">Options:</label>
                                                    <div class="col-8">
                                                        <select class="control-label fw-bold" name="opted" id="selectOpt">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- date inputs -->
                                    <div class="col-md-6">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <label class="control-label fw-bold col-12 text-info text-center">Date Created</label>
                                            <div class="col-md-6" id="fromCol">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-4 text-right" for="from">From:</label>
                                                    <div class="col-8">
                                                        <input type="date" name="from" class="form-control form-control-sm" id="from" value="<?= isset($from) ? $from : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="toCol">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-4 text-right" for="to">To:</label>
                                                    <div class="col-8">
                                                        <input type="date" name="to" class="form-control form-control-sm" id="to" value="<?= isset($to) ? $to : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <label class="control-label fw-bold col-12 text-center"></label>
                                        <button type="submit" class="btn btn-md btn-outline"><i class="fas fa-filter text-info"></i></button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- statement body -->
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ucfirst($report) ?> Report
            </div>
        </div>
        <div class="card-body">
            <div class="row gx-3 gy-2 align-items-center mt-0">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12">
                                    <table id="staffReport" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                        <thead class="">
                                            <tr>
                                                <th><input type="checkbox" name="" id="check-all"></th>
                                                <th>S.No</th>
                                                <th>Name</th>
                                                <th>Staff ID</th>
                                                <th>Branch</th>
                                                <th>Position</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Photo</th>
                                                <th>#ID</th>
                                                <th width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
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
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-8">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Name</label>
                                            <div class="col-md-12">
                                                <input name="staff_name" placeholder="Name" class="control-label fw-bold" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Gender</label>
                                            <div class="col-md-12">
                                                <input type="text" class="control-label fw-bold" name="gender" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Nationality</label>
                                            <div class="col-md-12">
                                                <input type="text" name="nationality" placeholder="Nationality" class="control-label fw-bold" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Marital Status</label>
                                            <div class="col-md-12">
                                                <input type="text" class="control-label fw-bold" name="marital_status" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Religion</label>
                                            <div class="col-md-12">
                                                <input type="text" class="control-label fw-bold" name="religion" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label fw-bold col-md-12 text-center">Profile Photo</label>
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
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Phone1</label>
                                    <div class="col-md-12">
                                        <input name="mobile" placeholder="Phone Number" class="control-label fw-bold" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Phone2</label>
                                    <div class="col-md-12">
                                        <input name="alternate_mobile" placeholder="Phone Number 2" class="control-label fw-bold" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <input name="email" placeholder="example@mail.com" class="control-label fw-bold" type="email" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Staff ID</label>
                                    <div class="col-md-12">
                                        <input name="staffID" placeholder="Staff ID" class="control-label fw-bold" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Department</label>
                                    <div class="col-md-12">
                                        <input type="text" name="department" class="control-label fw-bold" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Position</label>
                                    <div class="col-md-12">
                                        <input type="text" name="position_id" class="control-label fw-bold" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">ID Type</label>
                                    <div class="col-md-12">
                                        <input name="id_type" placeholder="ID Type" class="control-label fw-bold" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">NIN Number</label>
                                    <div class="col-md-12">
                                        <input name="id_number" placeholder="NIN number" class="control-label fw-bold" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">ID Expiry</label>
                                    <div class="col-md-12">
                                        <input name="id_expiry" placeholder="ID Expiry Date" class="control-label fw-bold" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Address</label>
                                    <div class="col-md-12">
                                        <textarea name="address" placeholder="address" class="control-label fw-bold" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Qualifications</label>
                                    <div class="col-md-12">
                                        <textarea name="qualifications" placeholder="qualifications" class="control-label fw-bold" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Branch</label>
                                    <div class="col-md-12">
                                        <input type="text" name="branch_id" class="control-label fw-bold" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Salary Scale</label>
                                    <div class="col-md-12">
                                        <input type="text" class="control-label fw-bold" name="salary_scale" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Appointment</label>
                                    <div class="col-md-12">
                                        <input name="appointment_type" placeholder="appointment type" class="control-label fw-bold" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Bank Name</label>
                                    <div class="col-md-12">
                                        <input type="text" name="bank_name" class="control-label fw-bold" placeholder="Bank Name" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Bank Branch</label>
                                    <div class="col-md-12">
                                        <input type="text" class="control-label fw-bold" name="bank_branch" placeholder="Bank Branch" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Bank Account No.</label>
                                    <div class="col-md-12">
                                        <input name="bank_account" class="control-label fw-bold" type="text" placeholder="Bank Account No." readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-8">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Employe D.O.B</label>
                                            <div class="col-md-12">
                                                <input name="date_of_birth" placeholder="" class="control-label fw-bold" type="date" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Registed Date</label>
                                            <div class="col-md-12">
                                                <input name="created_at" placeholder="created at" class="control-label fw-bold" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Updated At</label>
                                            <div class="col-md-12">
                                                <input name="updated_at" placeholder="updated at" class="control-label fw-bold" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <label class="control-label fw-bold col-md-12">Signature</label>
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
                                <label class="control-label fw-bold col-md-12"></label>
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
<!-- /.content -->
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    // already selected value
    var selected = '<?= $selected ?>';
    var report = '<?= $report ?>';
</script>
<script src="/assets/scripts/reports/staffs.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>

<?= $this->endSection() ?>