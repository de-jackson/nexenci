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
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="assets-tab" data-bs-toggle="tab" data-bs-target="#assets-tab-pane" type="button" role="tab" aria-controls="assets-tab" aria-selected="true">
                        <i class="ri-building-line me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Assets
                            <span class="badge bg-success badge-sm badge-circle" id="assets-subcategories"></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="equity-tab" data-bs-toggle="tab" data-bs-target="#equity-tab-pane" type="button" role="tab" aria-controls="equity-tab" aria-selected="false">
                        <i class="bx bx-briefcase me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Equity
                            <span class="badge bg-success badge-sm badge-circle" id="equity-subcategories"></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="liabilities-tab" data-bs-toggle="tab" data-bs-target="#liabilities-tab-pane" type="button" role="tab" aria-controls="liabilities-tab" aria-selected="false">
                        <i class="ti ti-cash-off me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Liabilities
                            <span class="badge bg-success badge-sm badge-circle" id="liabilities-subcategories"></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="income-tab" data-bs-toggle="tab" data-bs-target="#income-tab-pane" type="button" role="tab" aria-controls="income-tab" aria-selected="false">
                        <i class="ti ti-receipt-2 me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Income
                            <span class="badge bg-success badge-sm badge-circle" id="revenue-subcategories"></span>
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses-tab-pane" type="button" role="tab" aria-controls="expenses-tab" aria-selected="false">
                        <i class="ti ti-receipt-tax  me-2 align-middle"></i>
                        <span class="text-badge me-0 m-0">
                            Expenses
                            <span class="badge bg-danger badge-sm badge-circle" id="expenses-subcategories"></span>
                        </span>
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- assets subcategories -->
                <div class="tab-pane fade show active " id="assets-tab-pane" role="tabpanel" aria-labelledby="assets-tab-pane" tabindex="0">
                    <h4 class="pt-4 mb-4">Assets Subcategories</h4>
                    <table id="assetsSubcategories" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-all1"></th>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Part
                                <th>Statement</th>
                                <th>Type</th>
                                <th>Code</th>
                                <!-- <th>#ID</th> -->
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- equity subcategories -->
                <div class="tab-pane fade " id="equity-tab-pane" role="tabpanel" aria-labelledby="equity-tab-pane" tabindex="0">
                    <h4 class="pt-4 mb-4">Equity Subcategories</h4>
                    <table id="equitySubcategories" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-all2"></th>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Part
                                <th>Statement</th>
                                <th>Type</th>
                                <th>Code</th>
                                <!-- <th>#ID</th> -->
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- liabilities subcategories -->
                <div class="tab-pane fade " id="liabilities-tab-pane" role="tabpanel" aria-labelledby="liabilities-tab-pane" tabindex="0">
                    <h4 class="pt-4 mb-4">Liabilities Subcategories</h4>
                    <table id="liabilitySubcategories" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-all3"></th>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Part
                                <th>Statement</th>
                                <th>Type</th>
                                <th>Code</th>
                                <!-- <th>#ID</th> -->
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- income subcategories -->
                <div class="tab-pane fade " id="income-tab-pane" role="tabpanel" aria-labelledby="income-tab-pane" tabindex="0">
                    <h4 class="pt-4 mb-4">Income Subcategories</h4>
                    <table id="revenueSubcategories" class="table table-sm  table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-all4"></th>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Part
                                <th>Statement</th>
                                <th>Type</th>
                                <th>Code</th>
                                <!-- <th>#ID</th> -->
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- expenses subcategories -->
                <div class="tab-pane fade " id="expenses-tab-pane" role="tabpanel" aria-labelledby="expenses-tab-pane" tabindex="0">
                    <h4 class="pt-4 mb-4">Expenses Subcategories</h4>
                    <table id="expensesSubcategories" class="table table-sm table-hover text-nowrap" style="width:100%">
                        <thead class="">
                            <tr>
                                <th><input type="checkbox" name="" id="check-all5"></th>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Part
                                <th>Statement</th>
                                <th>Type</th>
                                <th>Code</th>
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
</div>

<!-- add\edit model -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form" role="dialog">
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
                    <input type="hidden" readonly value="" name="subcategory_type" />
                    <input type="hidden" readonly name="mode" />
                    <div class="form-body">
                        <div id="importRow" style="display: none;">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold"> Upload Subcategory(ies) <span class="text-danger">*</span>
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
                                <div class="col-md-6" id="categoryRow">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Category <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="category_id" id="category_id" style="width: 100%;">
                                            </select>
                                            <!-- <input type="text" name="category_id" id="category_id" class="form-control" placeholder="Category" style="display: none;"> -->
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Subcategory <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input name="subcategory_name" placeholder="Subcategory" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Subcategory Code</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="subcategory_code" id="subcategory_code" placeholder="Subcategory Code">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Subcategory Status <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="subcategory_status" id="subcategory_status" class="form-control select2bs4">
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
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSav" onclick="save_subcategory()" class="btn btn-outline-success">Submit</button>
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
                <form action="#" id="form" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Subcategory</label>
                                    <div class="col-md-12">
                                        <input name="subcategory_name" placeholder="SubCategory" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Subcategory Code</label>
                                    <div class="col-md-12">
                                        <input name="subcategory_code" placeholder="SubCategory Code" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Category</label>
                                    <div class="col-md-12">
                                        <input name="category_id" placeholder="Category" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Statement</label>
                                    <div class="col-md-12">
                                        <input name="statement" placeholder="Statement" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Part</label>
                                    <div class="col-md-12">
                                        <input name="part" placeholder="Part" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Subcategory Type</label>
                                    <div class="col-md-12">
                                        <input name="subcategory_type" placeholder="Subcategory Type" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Subcategory Status</label>
                                    <div class="col-md-12">
                                        <input name="subcategory_status" placeholder="SubCategory status" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Subcategory Slug</label>
                                    <div class="col-md-12">
                                        <input name="subcategory_slug" placeholder="Subcategory slug" class="form-control" type="text" readonly>
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
    var id = '<?= isset($subcategory) ? $subcategory['id'] : 0; ?>'
</script>
<script src="/assets/scripts/accounting/subcategories.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>

<?= $this->endSection() ?>