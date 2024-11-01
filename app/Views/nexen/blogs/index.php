<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                <?= ucfirst($title) ?> Information
            </div>
        </div>
        <div class="card-body">
            <table id="posts" class="table table-sm table-hover text-nowrap" style="width:100%">
                <thead>
                    <th>
                        <div class="form-check custom-checkbox ms-0">
                            <input type="checkbox" class="form-check-input checkAllInput" id="check-all" required="">
                            <label class="form-check-label" for="check-all"></label>
                        </div>
                    </th>
                    <th>S.No</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Created at</th>
                    <th>Account</th>
                    <th>Category</th>
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
                <form id="form" autocomplete="off">
                    <input type="hidden" readonly name="id">
                    <input type="hidden" readonly name="operation">
                    <div id="blogPostRow" style="display: none;">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Title <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input name="title" placeholder="Blog Post Title" class="form-control" type="text" required>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Intro <span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <textarea name="intro" placeholder="Blog Post Intro" class="form-control"></textarea>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">
                                                Category
                                            </label>
                                            <div class="col-md-12">
                                                <select name="blog_id" id="blog_id" class="form-control select2bs4 blog_id">
                                                    <option value="">-- select --</option>
                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Post Status <span class="text-danger">*</span></label>
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
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="profileImage">BLOG POST IMAGE <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <div id="user-photo-preview"></div>
                                        <label id="upload-label" class="control-label" for="profileImage"><span class="text-danger">*</span></label>
                                        <input type="file" name="image" accept="image/*" required onchange="previewImageFile(event)" class="form-control" id="profileImage" name="profileImage">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Content <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <textarea id="editor" name="content" placeholder="Blog Post Content" class="form-control"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-xxl-12" style="display: none;">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Form CkEditor</h4>
                                </div>
                                <div class="card-body custom-ekeditor">
                                    <div id="ckeditor12"></div>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" class="btn btn-outline-success" onclick="saveBlogPost()">Add</button>
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
                    <input type="hidden" readonly name="id">
                    <input type="hidden" readonly name="oldemail">
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
<script src="/assets/scripts/nexen/blogs/blog.js"></script>
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>
<?= $this->endSection() ?>