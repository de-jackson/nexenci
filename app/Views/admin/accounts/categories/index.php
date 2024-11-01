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
                <ul class="nav nav-tabs tab-style-2 d-sm-flex d-block border-bottom border-block-end-dashed" id="myTab1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories-tab-pane" type="button" role="tab" aria-controls="categories-tab" aria-selected="true">
                            <i class="ri-building-line me-2 align-middle"></i>
                            <span class="text-badge me-0 m-0">
                                Categories
                            </span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cash-flow-tab" data-bs-toggle="tab" data-bs-target="#cash-flow-tab-pane" type="button" role="tab" aria-controls="cash-flow-tab" aria-selected="false">
                            <i class="bx bx-briefcase me-2 align-middle"></i>
                            <span class="text-badge me-0 m-0">
                                Cash Flow Types
                            </span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="account-types-tab" data-bs-toggle="tab" data-bs-target="#account-types-tab-pane" type="button" role="tab" aria-controls="account-types-tab" aria-selected="false">
                            <i class="ti ti-cash-off me-2 align-middle"></i>
                            <span class="text-badge me-0 m-0">
                                Account Types
                            </span>
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- categories-->
                    <div class="tab-pane fade show active " id="categories-tab-pane" role="tabpanel" aria-labelledby="categories-tab-pane" tabindex="0">
                        <h4 class="pt-4 mb-4">Categories Information</h4>
                        <table id="categories" class="table table-sm  table-hover text-nowrap" style="width:100%">
                            <thead class="">
                                <tr>
                                    <!-- <th><input type="checkbox" name="" id="check-allCategories"></th> -->
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Part</th>
                                    <th>Statement</th>
                                    <!-- <th>Type</th> -->
                                    <th>Status</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- cash flow types -->
                    <div class="tab-pane fade" id="cash-flow-tab-pane" role="tabpanel" aria-labelledby="cash-flow-tab-pane" tabindex="0">
                        <h4 class="pt-4 mb-4">Cash Flow Types Information</h4>
                        <table id="cash_flows" class="table table-sm  table-hover text-nowrap" style="width:100%">
                            <thead class="">
                                <tr>
                                    <!-- <th><input type="checkbox" name="" id="check-allCashFlows"></th> -->
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <!-- <th width="5%">Action</th> -->
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- account types -->
                    <div class="tab-pane fade" id="account-types-tab-pane" role="tabpanel" aria-labelledby="account-types-tab-pane" tabindex="0">
                        <h4 class="pt-4 mb-4">Account Types Information</h4>
                        <table id="account_types" class="table table-sm  table-hover text-nowrap" style="width:100%">
                            <thead class="">
                                <tr>
                                    <!-- <th><input type="checkbox" name="" id="check-allAccountTypes"></th> -->
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <!-- <th width="5%">Action</th> -->
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
<!--End::row-1 -->
<!-- add\edit model -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">

                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Name <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input name="category_name" placeholder="Category Name" class="form-control" type="text">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Part</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="part" id="part" placeholder="Category Part" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Statement</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="statement" id="statement" placeholder=" Category Statement" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Type</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="category_type" id="category_type" placeholder=" Category Type" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Status <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="category_status" id="category_status" placeholder=" Category Status" readonly>
                                        <!-- <select name="category_status" id="category_status" class="form-control select2bs4">
                                            <option value="">-- select --</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select> -->
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSav" onclick="save_category()" class="btn btn-outline-success">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- view modal -->
<div class="modal fade" data-bs-backdrop="static" id="view_modal">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Name</label>
                                    <div class="col-md-12">
                                        <input name="category_name" placeholder="Category Name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Part</label>
                                    <div class="col-md-12">
                                        <input name="part" placeholder="Category Part" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category. Statement</label>
                                    <div class="col-md-12">
                                        <input name="statement" placeholder="Category statement" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Status</label>
                                    <div class="col-md-12">
                                        <input name="category_type" placeholder="Category status" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Slug</label>
                                    <div class="col-md-12">
                                        <input name="category_slug" placeholder="Category slug" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category Status</label>
                                    <div class="col-md-12">
                                        <input name="category_status" placeholder="Category Status" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Created At</label>
                                    <div class="col-md-12">
                                        <input name="created_at" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Updated At</label>
                                    <div class="col-md-12">
                                        <input name="updated_at" class="form-control" type="text" readonly>
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
<!-- /.content -->
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    var id = '<?= isset($category) ? $category['id'] : 0; ?>'
</script>
<script src="/assets/scripts/accounting/categories.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>

<?= $this->endSection() ?>