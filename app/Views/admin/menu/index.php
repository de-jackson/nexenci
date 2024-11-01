<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>



<!-- Start::row-1 -->
<div class="row gx-3 gy-2 align-items-center mt-0">
    <div class="col-xl-12">
        <div class="card border border-warning custom-card">
            <div class="card-header">
                <div class="card-title">
                    <?= ucfirst($title) ?> Information
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="menusTable" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-all"></th>
                                <th>S.No</th>
                                <th>Title</th>
                                <th>Parent</th>
                                <!-- <th>Order</th> -->
                                <th>URL</th>
                                <th>Create</th>
                                <th>Import</th>
                                <th>View</th>
                                <th>Update</th>
                                <th>Delete</th>
                                <th>Bulk Del.</th>
                                <th>Export</th>
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

<!-- add\edit model -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <!-- <div class="close">
                    <btn type="button" class="btn btn-md btn-secondary" onclick="exportMenuForm()" id="export">
                        <i class="fas fa-print text-light"></i>
                    </btn>
                </div> -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Title</label>
                                    <div class="col-md-12">
                                        <input type="text" name="title" placeholder="Title" class="form-control">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Menu</label>
                                    <div class="col-md-12">
                                        <input type="text" name="menu" class="form-control">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Icon</label>
                                    <div class="col-md-12">
                                        <input type="text" name="icon" class="form-control">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Order</label>
                                    <div class="col-md-12">
                                        <input type="number" name="order" placeholder="Order" class="form-control" min="0">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Parent</label>
                                    <div class="col-md-12">
                                        <select name="parent_id" id="parent_menu" placeholder="Parent" class="form-control select2bs4">

                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Status</label>
                                    <div class="col-md-12">
                                        <select name="status" placeholder="Status" class="form-control select2bs4">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Menu URL</label>
                                    <div class="col-md-12">
                                        <input type="text" name="url" placeholder="Menu URL" class="form-control">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <label class="control-label fw-bold col-md-12">Operations/Permissions</label>
                            <!-- create -->
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="create" id="create" class="form-check-input" role="switch">
                                        </div>
                                        <label class="control-label fw-bold col-md-12">Create</label>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- import -->
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="import" id="import" class="form-check-input" role="switch">
                                        </div>
                                        <label class="control-label fw-bold col-md-12">Import</label>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- view -->
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="view" id="view" class="form-check-input" role="switch">
                                        </div>
                                        <label class="control-label fw-bold col-md-12">View</label>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- update -->
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="update" id="update" class="form-check-input" role="switch">
                                        </div>
                                        <label class="control-label fw-bold col-md-12">Update</label>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- delete -->
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="delete" id="delete" class="form-check-input" role="switch">
                                        </div>
                                        <label class="control-label fw-bold col-md-12">Delete</label>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- bulk delete -->
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="bulkDelete" id="bulkDelete" class="form-check-input" role="switch">
                                        </div>
                                        <label class="control-label fw-bold col-md-12">Bulk-Delete</label>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- export -->
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <!-- <label class="control-label fw-bold col-md-12">Export</label> -->
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="export" id="export" class="form-check-input" role="switch">
                                        </div>
                                        <label class="control-label fw-bold col-md-12">Export</label>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- all -->
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="all" id="all" onclick="allChecked()" class="form-check-input">
                                        </div>
                                        <label class="control-label fw-bold col-md-12">All</label>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSubmit" onclick="save_menu()" class="btn btn-outline-success">Submit</button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Title</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vtitle" placeholder="Title" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Menu</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vmenu" class="form-control" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Icon</label>
                                    <div class="col-md-12">
                                        <input name="vicon" placeholder="Icon" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Order</label>
                                    <div class="col-md-12">
                                        <input type="number" name="vorder" placeholder="Order" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Menu Parent</label>
                                    <div class="col-md-12">
                                        <input name="vparent_id" placeholder="Menu Parent" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Status</label>
                                    <div class="col-md-12">
                                        <input name="vstatus" placeholder="Status" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Menu URL</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vurl" class="form-control" placeholder="Menu URL" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <label class="control-label fw-bold col-md-12">Operations/Permissions</label>
                            <!-- create -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Create</label>
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="vcreate" id="vcreate" class="form-check-input" role="switch">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- import -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Import</label>
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="vimport" id="vimport" class="form-check-input" role="switch">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- view -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">View</label>
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="vview" id="vview" class="form-check-input" role="switch">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- update -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Update</label>
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="vupdate" id="vupdate" class="form-check-input" role="switch">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- delete -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Delete</label>
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="vdelete" id="vdelete" class="form-check-input" role="switch">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- bulky delete -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Bulk Del</label>
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="vbulkDelete" id="vbulkDelete" class="form-check-input" role="switch">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- export -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Export</label>
                                    <div class="col-md-12">
                                        <div class="form-check form-check-md form-switch">
                                            <input type="checkbox" name="vexport" id="vexport" class="form-check-input" role="switch">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
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
    var id = '<?= isset($menus) ? $menus['id'] : 0; ?>';
</script>
<script src="/assets/scripts/menu.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<?= $this->endSection() ?>