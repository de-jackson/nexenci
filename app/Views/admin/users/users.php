<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Users Information
            </div>
        </div>
        <div class="card-body">
            <table id="users" class="table table-sm table-hover text-nowrap" style="width:100%">
                <thead>
                    <th>
                        <div class="form-check custom-checkbox ms-0">
                            <input type="checkbox" class="form-check-input checkAllInput" id="check-all" required="">
                            <label class="form-check-label" for="check-all"></label>
                        </div>
                    </th>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Account Type</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th width="5%">Action</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" data-bs-backdrop="static" id="modal_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?= csrf_field() ?>
            <div class="modal-body">
                <form id="form" class="form" autocomplete="off">
                    <input type="hidden" readonly name="id">
                    <input type="hidden" readonly name="staff_id">
                    <input type="hidden" readonly name="oldemail">
                    <div id="userRow" style="display: none;">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="control-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" placeholder="Full Name">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="control-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                                <input type="text" name="email" class="form-control" placeholder="Email Address">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" readonly id="phone_full" name="phone_full">
                                    <input type="hidden" name="phone_country_code" id="phone_country_code" readonly>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12 fw-bold">Phone Number <span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="tel" id="phone" name="phone" class="form-control phone-input" placeholder="Phone Number">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-12 fw-bold" for="profileImage">PASSPORT PHOTO</label>
                                    <div class="col-sm-12">
                                        <div id="user-photo-preview"></div>
                                        <label id="upload-label" class="control-label fw-bold" for="profileImage"></label>
                                        <input type="file" name="photo" accept="image/*" required onchange="previewImageFile(event)" class="form-control" id="profileImage" name="profileImage">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-4 product-documents-container">
                                <div class="form-group">
                                    <label class="control-label col-md-12">PASSPORT PHOTO</label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="file" id="profileImage" name="photo" class="form-control single-fileupload" accept="image/*" data-max-file-size="3MB" required>
                                                <label class="form-label op-5 mt-3">Only 1 image of size not exceeding 3MB can be uploaded. </label>
                                            </div>
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label fw-bold">Address <span class="text-danger">*</span></label>
                                        <textarea name="address" class="form-control" placeholder="Address"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label fw-bold">Branch Name <span class="text-danger">*</span></label>
                                        <select name="branch_id" class="form-control select2bs4 branch_id">
                                            <option value="">Select Branch</option>
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label fw-bold">Account Type <span class="text-danger">*</span></label>
                                        <select class="form-control select2bs4" name="account_type">
                                            <option value="">-- select --</option>
                                            <?php
                                            if (count($accounts) > 0) :
                                                foreach ($accounts as $key => $value) : ?>
                                                    <option value="<?= $key; ?>">
                                                        <?= $value; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label fw-bold">Access Status <span class="text-danger">*</span></label>
                                        <select class="form-control select2bs4" name="access_status">
                                            <option value="">Select Status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="departmentRow" class="row" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label fw-bold">Department <span class="text-danger">*</span></label>
                                        <select id="departmentID" name="department_id" class="form-control select2bs4 department_id">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label fw-bold">Position <span class="text-danger">*</span></label>
                                        <select id="positionID" name="position_id" class="form-control select2bs4 position_id">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="importRow" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label fw-bold">Branch Name</label>
                                        <select name="user_branch_id" class="form-control select2bs4 branch_id">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label fw-bold"> Upload User(s)
                                            <span class="text-white"> ( <span class="text-danger"> CSV Format </span> ) </span>
                                        </label>
                                        <input type="file" name="file" class="form-control" accept=".csv">
                                        <span class="help-block text-danger"> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="permissionsRow" style="display: none;">
                        <div class="row">
                            <label class="control-label col-md-12 fw-bold">User Permissions</label>
                            <div class="col-md-12">
                                <div class="table-responsive" id="permissions">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="passwordRow" style="display: none;">
                        <input type="hidden" readonly value="" name="menu" />
                        <input type="hidden" readonly value="" name="password" />
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12 fw-bold">Name</label>
                                    <div class="col-md-12">
                                        <input type="text" name="username" class="form-control" placeholder="Name" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12 fw-bold">Mobile</label>
                                    <div class="col-md-12">
                                        <input type="text" name="mobile" class="form-control phone-input" placeholder="Mobile">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12 fw-bold">Email</label>
                                    <div class="col-md-12">
                                        <input type="email" name="user_email" class="form-control" placeholder="Email" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Password</label>
                                    <div class="col-md-12">
                                        <input type="password" name="c_password" class="form-control" placeholder="Password" readonly autocomplete="off">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" class="btn btn-outline-success" onclick="save_user_method()">Add</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- view modal -->
<div class="modal fade" data-bs-backdrop="static" id="view_modal_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-success"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="viewform" autocomplete="off">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold">Full Name</label>
                                            <input type="text" name="vname" class="form-control" placeholder="Name" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold">Email Address</label>
                                            <input type="text" name="vemail" class="form-control" placeholder="Email Address" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold">Phone Number</label>
                                            <input type="text" name="vphone" class="form-control" placeholder="Phone Number" readonly>
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
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label fw-bold">Address</label>
                                    <textarea name="vaddress" class="form-control" placeholder="Address" readonly></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label fw-bold">Branch Name</label>
                                    <input type="text" name="vbranch_name" class="form-control" placeholder="Branch Name" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label fw-bold">Account Type</label>
                                    <input type="text" name="vaccount_type" class="form-control" placeholder="Account Type" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label fw-bold">Access Status</label>
                                    <input type="text" name="vaccess_status" class="form-control" placeholder="Access Status" readonly>
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
<div class="modal fade" data-bs-backdrop="static" id="photo_modal_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-success text-center"> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form well well-lg one_well ">
                <form action="#" id="viewform" class="form-horizontal viewform">
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
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End::app-content -->
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    var id = '<?= isset($user) ? $user['id'] : 0; ?>';
</script>
<script src="/assets/scripts/main/permissions.js"></script>
<script src="/assets/scripts/users/user.js"></script>
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>
<script src="/assets/scripts/main/phone.js"></script>
<?= $this->endSection() ?>