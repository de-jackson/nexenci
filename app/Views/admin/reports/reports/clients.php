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
                                    <div class="col-md-2" id="filterCols">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <label class="control-label fw-bold col-12 text-info text-center" for="val">Filters</label>
                                            <div class="col-12">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-2 text-right" for="filter"></label>
                                                    <div class="col-10">
                                                        <select class="form-control" name="filter" id="filter">
                                                            <?php
                                                            if (count($filters) > 0) :
                                                                foreach ($filters as $key => $opt) :
                                                            ?>
                                                                    <option value="<?= $key ?>" <?= ($opt == $filter) ? "selected" : ''; ?>>
                                                                        <?= $opt; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            <?php else : ?>
                                                                <option value="">No Filter</option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9" id="filterOpts">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <!-- balances inputs -->
                                            <div class="col-md-6">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold text-info col-12 text-center">Outstanding Balance</label>
                                                    <div class="col-md-10" id="balCol">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <label class="control-label fw-bold col-4 text-right" for="bal">From:</label>
                                                            <div class="col-8">
                                                                <input type="number" name="bal" class="form-control form-control-sm" placeholder="Balance" id="bal" value="<?= isset($bal) ? $bal : ''; ?>" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="btnCol" style="display: none;">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <label class="control-label fw-bold col-4 text-right" for="btn">To:</label>
                                                            <div class="col-8">
                                                                <input type="number" name="btn" class="form-control form-control-sm" placeholder="Balance" id="btn" value="<?= isset($btn) ? $btn : ''; ?>" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- date inputs -->
                                            <div class="col-md-6">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-12 text-info text-center">Date Created</label>
                                                    <div class="col-md-6" id="fromCol">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <label class="control-label fw-bold col-4 text-right" for="from">From:</label>
                                                            <div class="col-8">
                                                                <input type="date" name="from" class="form-control form-control-sm" id="from" value="<?= isset($from) ? $from : ''; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="toCol">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <label class="control-label fw-bold col-4 text-right" for="to">To:</label>
                                                            <div class="col-8">
                                                                <input type="date" name="to" class="form-control form-control-sm" id="to" value="<?= isset($to) ? $to : ''; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <label class="control-label fw-bold col-12 text-center"></label>
                                        <button type="submit" class="btn btn-md btn-outline"><i class="fas fa-filter text-info"></i></button>
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
                                    <table id="clientsReport" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                        <thead class="">
                                            <tr>
                                                <th><input type="checkbox" name="" id="check-all"></th>
                                                <th>S.No</th>
                                                <th>Name</th>
                                                <th>Account N<u>o</u></th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Account Bal.</th>
                                                <th>Photo</th>
                                                <th>#ID</th>
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
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-8">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Full Name</label>
                                            <div class="col-md-12">
                                                <input name="vname" placeholder="Full Name" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Branch</label>
                                            <div class="col-md-12">
                                                <input name="vbranch_id" placeholder="branch" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Gender</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="vgender" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Nationality</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="vnationality" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Marital Status</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="vmarital_status" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Religion</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="vreligion" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <label class="control-label fw-bold col-md-12">Profile Photo</label>
                                <div class="form-group" id="photo-preview">
                                    <div class="col-md-12">
                                        (No photo)
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Phone Number 1</label>
                                    <div class="col-md-12">
                                        <input name="vmobile" placeholder="Phone Number" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Phone Number 2</label>
                                    <div class="col-md-12">
                                        <input name="valternate_no" placeholder="Phone Number 2" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <input name="vemail" placeholder="example@mail.com" class="form-control" type="email" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Account Number</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vaccount_no" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Account Type</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vaccount_type" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Account Balance</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vaccount_balance" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Address</label>
                                    <div class="col-md-12">
                                        <textarea name="vresidence" placeholder="address" class="form-control" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">D.O.B</label>
                                    <div class="col-md-12">
                                        <input name="vdob" placeholder="" class="form-control" type="date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Occupation</label>
                                    <div class="col-md-12">
                                        <input name="voccupation" placeholder="occupation" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Age(Years)</label>
                                    <div class="col-md-12">
                                        <input name="age" placeholder="next of kin phone2" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Id Type</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vid_type" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Id Number</label>
                                    <div class="col-md-12">
                                        <input name="vid_number" placeholder="id number" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Id Expiry Date</label>
                                    <div class="col-md-12">
                                        <input name="vid_expiry" placeholder="" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Business Location</label>
                                    <div class="col-md-12">
                                        <textarea name="vjob_location" placeholder="address" class="form-control" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Next of Kin</label>
                                    <div class="col-md-12">
                                        <input name="vnext_of_kin" placeholder="next of kin" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Next of Kin Relationship</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vnok_relationship" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Next Of Kin Address</label>
                                    <div class="col-md-12">
                                        <textarea name="vnok_address" placeholder="address" class="form-control" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-8">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Next of Kin Phone1</label>
                                            <div class="col-md-12">
                                                <input name="vnok_phone" placeholder="next of kin phone" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Next of Kin Phone2</label>
                                            <div class="col-md-12">
                                                <input name="vnok_alt_phone" placeholder="next of kin phone2" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Next of Kin Email</label>
                                            <div class="col-md-12">
                                                <input name="vnok_email" placeholder="Next of Kin Email" class="form-control" type="text" readonly>
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
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Registration Date</label>
                                    <div class="col-md-12">
                                        <input name="created_at" placeholder="next of kin phone2" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Updated At</label>
                                    <div class="col-md-12">
                                        <input name="update_at" placeholder="next of kin phone2" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Responsible Officer</label>
                                    <div class="col-md-12">
                                        <input name="staff_name" placeholder="responsible officer" class="form-control" type="text" readonly>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">

</script>
<script src="/assets/scripts/reports/clients.js"></script>

<?= $this->endSection() ?>