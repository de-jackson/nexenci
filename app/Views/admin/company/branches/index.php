<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ucwords($menu) . ' ' . ucfirst($title) ?> Information
            </div>
        </div>
        <div class="card-body">
            <table id="branch" class="table table-sm  table-hover text-nowrap" style="width:100%">
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
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--End::row-1 -->
<!-- start: add\edit model -->
<div class="modal fade" id="modal_form" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <!-- <div class="close">
                    <btn type="button" class="btn btn-md btn-secondary" onclick="exportBranchForm()" id="export">
                        <i class="fas fa-print text-light"></i>
                    </btn>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div> -->
            </div>
            <div class="modal-body">
                <form id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">
                        Required fields are marked with an asterisk <span class="text-danger">(*)</span>.
                    </p>
                    <input type="hidden" name="id" readonly />
                    <input type="hidden" name="mode" readonly />
                    <div class="form-body">
                        <!-- import row -->
                        <div id="importRow" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold"> Upload Branches <span class="text-danger">*</span>
                                                <span class="text-white"> ( <span class="text-danger"> CSV Format </span> ) </span>
                                            </label>
                                            <input type="file" name="file" class="form-control" accept=".csv">
                                            <span class="help-block text-danger"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end import -->
                        <!-- fill form row -->
                        <div id="formRow" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Branch Name <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input name="branch_name" placeholder="Branch Name" class="form-control" type="text" required>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Phone Number <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input id="branch_mobile" name="branch_mobile" placeholder="Phone Number" class="form-control phone-input" type="tel">
                                            <input type="hidden" readonly id="branch_mobile_full" name="branch_mobile_full">
                                            <input type="hidden" name="branch_mobile_country_code" id="branch_mobile_country_code" readonly>
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Phone Number 2
                                        </label>
                                        <div class="col-md-12">
                                            <input id="mobile" name="mobile" placeholder="Phone Number 2" class="form-control phone-input" type="tel">
                                            <input type="hidden" readonly id="mobile_full" name="mobile_full">
                                            <input type="hidden" name="mobile_country_code" id="mobile_country_code" readonly>
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Branch Email <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input name="branch_email" placeholder="example@mail.com" class="form-control" type="email">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Branch Status <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="branch_status" id="branch_status" class="form-control select2bs4">
                                                <option value="">-- select --</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Branch Address <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea name="branch_address" placeholder="Address" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- fill form row -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSubmit" class="btn btn-outline-success" onclick="save_branch()">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end: add\edit model -->

<!-- view branch modal -->
<div class="modal fade" id="view_modal" data-bs-backdrop="static">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Branch Name</label>
                                    <div class="col-md-12">
                                        <input name="vbranch_name" placeholder="Branch Name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Branch Code</label>
                                    <div class="col-md-12">
                                        <input name="vbranch_code" placeholder="Branch Code" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Phone Number 1</label>
                                    <div class="col-md-12">
                                        <input name="vbranch_mobile" placeholder="Phone Number 1" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Phone Number 2</label>
                                    <div class="col-md-12">
                                        <input name="valternate_mobile" placeholder="Phone Number 2" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Branch Email</label>
                                    <div class="col-md-12">
                                        <input name="vbranch_email" placeholder="Branch Email" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Branch Status</label>
                                    <div class="col-md-12">
                                        <input name="vbranch_status" placeholder="Branch Status" class="form-control" type="text">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Branch Address</label>
                                    <div class="col-md-12">
                                        <textarea name="vbranch_address" class="form-control" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Date Created</label>
                                    <div class="col-md-12">
                                        <input type="text" name="created_at" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Date Updated</label>
                                    <div class="col-md-12">
                                        <input type="text" name="updated_at" class="form-control" readonly>
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
    var id = '<?= isset($branch) ? $branch['id'] : 0; ?>';
</script>
<script src="/assets/scripts/company/branches.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/main/phone.js"></script>

<?= $this->endSection() ?>