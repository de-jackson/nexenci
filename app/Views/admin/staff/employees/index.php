<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <?= ucwords($menu) . ' ' . ucfirst($title) ?> Information
                </div>
            </div>
            <div class="card-body">
                <table id="employeesTable" class="table table-sm  table-hover text-nowrap" style="width:100%">
                    <thead class="">
                        <tr>
                            <th><input type="checkbox" name="" id="check-all"></th>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <!-- <th>Department</th> -->
                            <th>Position</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- add\edit model -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly value="" name="id" />
                    <input type="hidden" readonly value="" name="mode" />
                    <div class="form-body">
                        <div id="importRow" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold">Branch Name <span class="text-danger">*</span></label>
                                            <select id="branchID" name="branchID" class="form-control select2bs4 branch_id">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold">Department <span class="text-danger">*</span></label>
                                            <select id="departmentID" name="departmentID" class="form-control select2bs4 department_id">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold">Position <span class="text-danger">*</span></label>
                                            <select id="positionID" name="positionID" class="form-control select2bs4 position_id">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold"> Upload Administrator(s) <span class="text-danger">*</span>
                                                <span class="text-white"> ( <span class="text-danger"> CSV Format </span> ) </span>
                                            </label>
                                            <input type="file" name="file" class="form-control" accept=".csv">
                                            <span class="help-block text-danger"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="formRow">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">
                                                    Registration Date <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                        <input type="text" name="reg_date" class="form-control getDatePicker" value="<?= date("Y-m-d"); ?>">
                                                    </div>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Full Name <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input name="staff_name" placeholder="Full Name" class="form-control" type="text">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Gender <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="gender" id="gender" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Nationality <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="nationality" id="nationality" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Marital Status <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="marital_status" id="maritalstatus" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Religion <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="religion" id="religion" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-sm-12 text-center" for="profileImage">PASSPORT PHOTO</label>
                                        <div class="col-sm-12">
                                            <center>
                                                <div id="user-photo-preview"></div>
                                            </center>
                                            <label id="upload-label" class="control-label fw-bold" for="profileImage"></label>
                                            <input type="file" name="photo" accept="image/*" required onchange="previewImageFile(event)" class="form-control" id="profileImage">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Phone Number <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input id="mobile" name="mobile" placeholder="Phone Number" class="form-control phone-input" type="tel">
                                            <input type="hidden" readonly id="mobile_full" name="mobile_full">
                                            <input type="hidden" name="mobile_country_code" id="mobile_country_code" readonly>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Alternate Phone Number</label>
                                        <div class="col-md-12">
                                            <input id="alternate_mobile" name="alternate_mobile" placeholder="Alternate Phone Number" class="form-control phone-input" type="tel">
                                            <input type="hidden" readonly id="alternate_mobile_full" name="alternate_mobile_full">
                                            <input type="hidden" name="alternate_mobile_country_code" id="alternate_mobile_country_code" readonly>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Email Address <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input name="email" placeholder="example@mail.com" class="form-control" type="email">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Employee D.O.B <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                <input name="date_of_birth" placeholder="" class="form-control getDatePicker" type="date">
                                            </div>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Department <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4 department_id" name="department_id" id="department_id" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Position <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4 position_id" name="position_id" id="position_id" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-sm-12" for="idImageFront">ID PHOTO(Front)</label>
                                        <div class="col-sm-12">
                                            <div id="id-previewFront"></div>
                                            <label id="id-labelFront" class="control-label fw-bold" for="idImageFront"></label>
                                            <input type="file" name="id_photo_front" accept="image/*" required onchange="previewIdImage(event, 'Front')" class="form-control" id="idImageFront">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">ID Type <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="id_type" id="idtypes" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">ID Number <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input name="id_number" placeholder="ID Number" class="form-control" type="text">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">ID Expiry Date</label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                        <input name="id_expiry" placeholder="" class="form-control getDatePicker" type="date">
                                                    </div>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-sm-12" for="idImageBack">ID PHOTO(Back)</label>
                                        <div class="col-sm-12">
                                            <div id="id-previewBack"></div>
                                            <label id="id-labelBack" class="control-label fw-bold" for="idImageBack"></label>
                                            <input type="file" name="id_photo_back" accept="image/*" required onchange="previewIdImage(event, 'Back')" class="form-control" id="idImageBack">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Address <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea name="address" placeholder="Address" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Qualifications <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea name="qualifications" placeholder="Qualifications" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Branch Name <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4 branch_id" name="branch_id" id="branch_id" style="width: 100%;">

                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Salary [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="salary_scale" placeholder="Salary [<?= $settings['currency']; ?>]">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Appointment <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="appointment_type" id="appointmenttypes" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Bank Name</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="bank_name" class="form-control" placeholder="Bank Name">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Bank Branch</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="bank_branch" class="form-control" placeholder="Bank Branch">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Bank Account No</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="bank_account" class="form-control" placeholder="Bank Account Number">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-sm-12" for="signature">Signature</label>
                                        <div class="col-sm-12">
                                            <div id="signature-preview"></div>
                                            <label id="sign-label" class="control-label fw-bold" for="signature"></label>
                                            <input type="file" name="signature" accept="image/*" required onchange="previewSignature(event)" class="form-control" id="signature">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSav" onclick="save_employee()" class="btn btn-outline-success">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- view modal -->
<div class="modal fade" data-bs-backdrop="static" id="view_modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Name</label>
                                            <div class="col-md-12">
                                                <input name="staff_name" placeholder="Name" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Gender</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="gender" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Nationality</label>
                                            <div class="col-md-12">
                                                <input type="text" name="nationality" placeholder="Nationality" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Marital Status</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="marital_status" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Religion</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="religion" readonly>
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
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Phone Number</label>
                                    <div class="col-md-12">
                                        <input name="mobile" placeholder="Phone Number" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Phone Number 2</label>
                                    <div class="col-md-12">
                                        <input name="alternate_mobile" placeholder="Phone Number 2" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <input name="email" placeholder="example@mail.com" class="form-control" type="email" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Staff ID</label>
                                    <div class="col-md-12">
                                        <input name="staffID" placeholder="Staff ID" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Department</label>
                                    <div class="col-md-12">
                                        <input type="text" name="department" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Position</label>
                                    <div class="col-md-12">
                                        <input type="text" name="position_id" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="control-label fw-bold col-md-12">ID Photo(Front)</label>
                                <div class="form-group" id="id-previewFront">
                                    <div class="col-md-12">
                                        (No photo)
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row mb-3">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">ID Type</label>
                                            <div class="col-md-12">
                                                <input name="id_type" placeholder="ID Type" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">ID Number</label>
                                            <div class="col-md-12">
                                                <input name="id_number" placeholder="ID number" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">ID Expiry</label>
                                            <div class="col-md-12">
                                                <input name="id_expiry" placeholder="ID Expiry Date" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label fw-bold col-md-12">ID Photo(Back)</label>
                                <div class="form-group" id="id-previewBack">
                                    <div class="col-md-12">
                                        (No photo)
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Address</label>
                                    <div class="col-md-12">
                                        <textarea name="address" placeholder="address" class="form-control" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Qualifications</label>
                                    <div class="col-md-12">
                                        <textarea name="qualifications" placeholder="qualifications" class="form-control" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Branch</label>
                                    <div class="col-md-12">
                                        <input type="text" name="branch_id" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Salary [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="salary_scale" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Appointment</label>
                                    <div class="col-md-12">
                                        <input name="appointment_type" placeholder="appointment type" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Bank Name</label>
                                    <div class="col-md-12">
                                        <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Bank Branch</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="bank_branch" placeholder="Bank Branch" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Bank Account No.</label>
                                    <div class="col-md-12">
                                        <input name="bank_account" class="form-control" type="text" placeholder="Bank Account No." readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Employe D.O.B</label>
                                            <div class="col-md-12">
                                                <input name="date_of_birth" placeholder="" class="form-control" type="date" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Registered Date</label>
                                            <div class="col-md-12">
                                                <input name="created_at" placeholder="created at" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Updated At</label>
                                            <div class="col-md-12">
                                                <input name="updated_at" placeholder="updated at" class="form-control" type="text" readonly>
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
                <<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
    var id = '<?= isset($employee) ? $employee['id'] : 0; ?>';
</script>
<script src="/assets/scripts/staff/employees.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>
<script src="/assets/scripts/main/phone.js"></script>

<?= $this->endSection() ?>