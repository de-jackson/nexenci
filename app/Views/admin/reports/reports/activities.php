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
                                    <div class="col-md-5">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <label class="control-label fw-bold col-4 text-right" for="from">From</label>
                                            <div class="col-8">
                                                <input type="date" name="from" class="form-control form-control-sm" id="from" value="<?= isset($from) ? $from : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <label class="control-label fw-bold col-4 text-right" for="to">To</label>
                                            <div class="col-8">
                                                <input type="date" name="to" class="form-control form-control-sm" id="to" value="<?= isset($to) ? $to : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <button type="submit" class="btn btn-sm btn-outline"><i class="fas fa-filter text-info"></i></button>
                                    </div>
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
                                    <table id="userActivityReport" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                        <thead class="">
                                            <tr>
                                                <th><input type="checkbox" name="" id="check-all"></th>
                                                <th>S.No</th>
                                                <th>Username</th>
                                                <th>Action</th>
                                                <th>Module</th>
                                                <th>Referrer ID</th>
                                                <th>Created At</th>
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
                <h4 class="modal-title text-info"> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Username</label>
                                    <div class="col-md-12">
                                        <input name="vuser_id" placeholder="Username" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Module</label>
                                    <div class="col-md-12">
                                        <input name="vmodule" placeholder="Module" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Referrer ID</label>
                                    <div class="col-md-12">
                                        <input name="vreferrer_id" placeholder="Referrer ID" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Action</label>
                                    <div class="col-md-12">
                                        <textarea name="vaction" class="form-control" placeholder="Action"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Created At</label>
                                    <div class="col-md-12">
                                        <input type="text" name="created_at" class="form-control" placeholder="Created At" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Updated At</label>
                                    <div class="col-md-12">
                                        <input type="text" name="updated_at" class="form-control" placeholder="Updated At" readonly>
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

</script>
<script src="/assets/scripts/reports/activities.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>

<?= $this->endSection() ?>