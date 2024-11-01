<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>

<!-- Start::row-1 -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <?= ucwords($menu) . ' ' . ucfirst($title) ?> Information
                </div>
            </div>
            <div class="card-body">
                <table id="positions" class="table table-sm  table-hover text-nowrap" style="width:100%">
                    <thead class="">
                        <tr>
                            <th>
                                <div class="form-check custom-checkbox ms-0">
                                    <input type="checkbox" class="form-check-input checkAllInput" id="check-all" required="">
                                    <label class="form-check-label" for="check-all"></label>
                                </div>
                            </th>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <!-- <th>#ID</th> -->
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--End::row-1 -->

<!-- add\edit model -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <!-- <div class="close">
                    <btn type="button" class="btn btn-md btn-secondary" onclick="exportPositionForm()" id="export">
                        <i class="fas fa-print text-light"></i>
                    </btn>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div> -->
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
                                        <label class="control-label fw-bold col-md-12">Department <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4 department_id" name="departmentID" id="departmentID" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold"> Upload Positions <span class="text-danger">*</span>
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
                            <div class="row mb-3">
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
                                            <input name="position" placeholder="Position" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Status <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="position_status" id="position_status" class="form-control select2bs4">
                                                <option value="">-- select --</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label fw-bold col-md-12">Assign Permissions</label>
                            <div class="col-md-12">
                                <div class="table-responsive" id="permissions">

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSubmit" onclick="save_position()" class="btn btn-outline-success">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
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
                <form class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Position</label>
                                    <div class="col-md-12">
                                        <input name="vposition" placeholder="Position" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Department Name</label>
                                    <div class="col-md-12">
                                        <input name="vdepartment_id" placeholder="Department Name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Status</label>
                                    <div class="col-md-12">
                                        <input name="vposition_status" placeholder="Status" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Slug</label>
                                    <div class="col-md-12">
                                        <input name="vposition_slug" placeholder="Slug" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Date Created</label>
                                    <div class="col-md-12">
                                        <input type="text" name="created_at" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Date Updated</label>
                                    <div class="col-md-12">
                                        <input type="text" name="updated_at" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label fw-bold col-md-12">Position Permissions</label>
                            <div class="col-md-12">
                                <div class="table-responsive" id="viewPermissions">

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
    var id = '<?= isset($position) ? $position['id'] : 0; ?>'
</script>
<script src="/assets/scripts/main/permissions.js"></script>
<script src="/assets/scripts/company/positions.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>

<?= $this->endSection() ?>