<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ucfirst($title) ?> Information
            </div>
        </div>
        <div class="card-body">
            <table id="categories" class="table table-sm  table-hover text-nowrap" style="width:100%">
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
                        <th>Description</th>
                        <th>Type</th>
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
                                        <label class="control-label fw-bold col-md-12">Category Name <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input name="name" placeholder="Category Name" class="form-control" type="text" required>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Status <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="status" id="status" class="form-control select2bs4">
                                                <option value="">-- select --</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Approved" selected>Approved</option>
                                                <option value="Reviewed">Reviewed</option>
                                                <option value="Cancelled">Cancelled</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="display: none;">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Image</label>
                                        <div class="col-md-12">
                                            <input name="image" placeholder="Image" class="form-control" type="file">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">
                                            Parent Category
                                        </label>
                                        <div class="col-md-12">
                                            <select name="blog_id" id="blog_id" class="form-control select2bs4 blog_id">
                                                <option value="">-- select --</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">
                                            Description
                                        </label>
                                        <div class="col-md-12">
                                            <textarea name="description" placeholder="Description" class="form-control"></textarea>
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
                <button type="button" id="btnSubmit" class="btn btn-outline-success" onclick="saveBlogCategory()">Submit</button>
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Name</label>
                                    <div class="col-md-12">
                                        <input name="vname" placeholder="Category Name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Status</label>
                                    <div class="col-md-12">
                                        <input name="vstatus" placeholder="Category Status" class="form-control" type="text">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Parent Category</label>
                                    <div class="col-md-12">
                                        <input name="vtype" placeholder="Parent Category" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Description
                                    </label>
                                    <div class="col-md-12">
                                        <textarea name="vdescription" class="form-control" placeholder="Description" readonly></textarea>
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
<script src="/assets/scripts/nexen/blogs/categories.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>

<?= $this->endSection() ?>