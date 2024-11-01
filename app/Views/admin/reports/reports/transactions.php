<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<!-- date filter -->
<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <div class="contact-header">
                <div class="d-sm-flex d-block align-items-center justify-content-between">
                    <div class="h5 fw-semibold mb-0">Filter Data:</div>
                    <div class="d-flex mt-sm-0 mt-2 align-items-center">
                        <form action="/admin/reports/view/<?= $report; ?>" class="form-horizontal" method="post" autocomplete="off">
                            <?= csrf_field() ?>
                            <div class="form-body">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-2" id="filterCols">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <label class="control-label fw-bold col-12 text-info text-center" for="val">Filters</label>
                                            <div class="col-md-12">
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
                                    <div class="col-md-9" id="filterOpts">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <!-- filter inputs -->
                                            <div class="col-md-6">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold text-info col-12 text-center">Amount Applied</label>
                                                    <div class="col-md-6" id="balCol">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <label class="control-label fw-bold col-4 text-right" for="bal">From:</label>
                                                            <div class="col-8">
                                                                <input type="number" name="bal" class="form-control form-control-sm" placeholder="Amount" id="bal" value="<?= isset($bal) ? $bal : ''; ?>" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="btnCol">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <label class="control-label fw-bold col-4 text-right" for="btn">To:</label>
                                                            <div class="col-8">
                                                                <input type="number" name="btn" class="form-control form-control-sm" placeholder="Amount" id="btn" value="<?= isset($btn) ? $btn : ''; ?>" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- date inputs -->
                                            <div class="col-md-6">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-12 text-info text-center">Date Applied</label>
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
                <?= ucfirst($title) ?> Report
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <div class="row gx-3 gy-2 align-items-center mt-0">
                <div class="col-md-12">
                    <!-- table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Transactions Report Table</h5>
                        </div>
                        <div class="card-body">
                            <table id="transactionsReport" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                <thead class="">
                                    <tr>
                                        <th><input type="checkbox" name="" id="check-all"></th>
                                        <th>S.No</th>
                                        <th>Particular</th>
                                        <th>Payment</th>
                                        <th>Acc. Type</th>
                                        <th>Amount [<?= $settings['currency']; ?>]</th>
                                        <th>Ref ID</th>
                                        <th>Officer</th>
                                        <th>Date</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <!-- graphical report -->
            <div class="row gx-3 gy-2 align-items-center mt-0">
                <div class="col-md-12">
                    <div class="card p-2">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Transactions Report Graph from 01 Jan, <?= date('Y') . ' - ' . date('d M, Y') ?></h5>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <!-- Total Financing -->
                            <div class="col-lg-4 col-sm-6 col-md-4 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon success px-0">
                                                <span class="rounded p-3 bg-success-transparent">
                                                    <i class="ti ti-transfer-in"></i>
                                                </span>
                                            </div>
                                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                                <div class="mb-2">Total Financing</div>
                                                <div class="text-muted mb-1 fs-12">
                                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                                        <?= number_format($financingAmt, 0); ?>
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="fs-12 mb-0">Representing <span class="badge bg-success-transparent text-success mx-1"><?= ($totalEntries != 0) ? round((($financing / $totalEntries) * 100)) : 0; ?></span>%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Total Expenses -->
                            <div class="col-lg-4 col-sm-6 col-md-4 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon success px-0">
                                                <span class="rounded p-3 bg-danger-transparent">
                                                    <i class="ti ti-transfer-out"></i>
                                                </span>
                                            </div>
                                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                                <div class="mb-2">Total Expenses</div>
                                                <div class="text-muted mb-1 fs-12">
                                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                                        <?= number_format($expenseAmt, 0); ?>
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="fs-12 mb-0">Representing <span class="badge bg-success-transparent text-success mx-1"><?= ($totalEntries != 0) ? round((($expenses / $totalEntries) * 100)) : 0; ?></span>%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Total Transfer -->
                            <div class="col-lg-4 col-sm-6 col-md-4 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon success px-0">
                                                <span class="rounded p-3 bg-secondary-transparent">
                                                    <i class="fas fa-money-bill-transfer"></i>
                                                </span>
                                            </div>
                                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                                <div class="mb-2">Total Transfers</div>
                                                <div class="text-muted mb-1 fs-12">
                                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                                        <?= number_format($transferAmt, 0); ?>
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="fs-12 mb-0">Representing <span class="badge bg-success-transparent text-success mx-1"><?= ($totalEntries != 0) ? round((($transfers / $totalEntries) * 100)) : 0; ?></span>%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-9">
                                    <div class="card custom-card">
                                        <div class="card-header">
                                            <div class="card-title">Transactions: 01 Jan, <?= date('Y') ?> - <?= date('d M, Y') ?></div>
                                        </div>
                                        <div class="card-body">
                                            <div id="monthly-transactions"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-center">
                                        <strong>Transaction Totals</strong>
                                    </p>
                                    <!-- Total Financing -->
                                    <div class="progress-group">
                                        Total Financing
                                        <span class="float-right">
                                            <b><?= $financing; ?></b>/<?= $totalEntries; ?>
                                        </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: <?= ($totalEntries != 0) ? round((($financing / $totalEntries) * 100)) : 0; ?>%"></div>
                                        </div>
                                    </div><br>
                                    <!-- Total Expenses -->
                                    <div class="progress-group">
                                        Total Expenses
                                        <span class="float-right">
                                            <b><?= $expenses; ?></b>/<?= $totalEntries; ?>
                                        </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-danger" style="width: <?= ($totalEntries != 0) ? round((($expenses / $totalEntries) * 100)) : 0; ?>%"></div>
                                        </div>
                                    </div><br>
                                    <!-- Total Transfer -->
                                    <div class="progress-group">
                                        <span class="progress-text">Total Transfer</span>
                                        <span class="float-right">
                                            <b><?= $transfers; ?></b>/<?= $totalEntries; ?>
                                        </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-info" style="width: <?= ($totalEntries != 0) ? round((($transfers / $totalEntries) * 100)) : 0; ?>%"></div>
                                        </div>
                                    </div>
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
                        <!-- client -->
                        <div class="row gx-3 gy-2 align-items-centermt-0" id="clientData" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">client Name</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vclient_name" id="client_name" placeholder="client Name" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Account Number</label>
                                    <div class="col-md-12">
                                        <input name="vaccount_no" id="account_no" placeholder="Account Number" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Contact</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vcontact" id="contact" class="form-control" placeholder="Contact" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- particular -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Acount Type</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vaccount_type" id="account_type" placeholder="Account Type" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Particular</label>
                                    <div class="col-md-12">
                                        <input name="vparticular_name" id="particular_name" placeholder="Particular" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Trans Type</label>
                                    <div class="col-md-12">
                                        <input type="text" name="ventry_type" id="entry_type" class="form-control" placeholder="Trans Type" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- state -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Date</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vdate" id="date" placeholder="Date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Branch Name</label>
                                    <div class="col-md-12">
                                        <input name="vbranch_name" id="branch_name" placeholder="branch_name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Status</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vstatus" id="status" class="form-control" placeholder="Trans Type" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- disbursement -->
                        <div class="row gx-3 gy-2 align-items-centermt-0" id="disbursementData" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Loan Product</label>
                                    <div class="col-md-12">
                                        <input name="vproduct_name" id="product_name" placeholder="Loan Product" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Disbursement Code</label>
                                    <div class="col-md-12">
                                        <input name="vdisbursement_id" id="disbursement_id" placeholder="Disbursement Code" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Class</label>
                                    <div class="col-md-12">
                                        <input name="vclass" id="class" placeholder="Class" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- application -->
                        <div class="row gx-3 gy-2 align-items-centermt-0" id="applicationData" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Loan Product</label>
                                    <div class="col-md-12">
                                        <input name="vproduct_name" id="product_name" placeholder="Loan Product" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Application Code</label>
                                    <div class="col-md-12">
                                        <input name="vapplication_id" id="application_id" placeholder="Application Code" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Application Status</label>
                                    <div class="col-md-12">
                                        <input name="vapplication_status" id="application_status" placeholder="Application Status" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- amount -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Ref ID</label>
                                    <div class="col-md-12">
                                        <input name="vref_id" placeholder="Ref ID" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Amount</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vamount" id="amount" class="form-control" placeholder="Amount" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Payment</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vpayment" id="payment" class="form-control" placeholder="Payment" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- details -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Transaction Details</label>
                                    <div class="col-md-12">
                                        <textarea name="ventry_details" class="form-control" id="viewSummernote" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- remarks -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Transaction Remarks</label>
                                    <div class="col-md-12">
                                        <textarea name="vremarks" class="form-control" placeholder="Transaction"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- accounting -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <p class="col-md-12 pl-2">For Accounting</p>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Trans Menu</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="ventry_menu" placeholder="Trans Menu" id="entry_menu" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Balance</label>
                                    <div class="col-md-12">
                                        <input type="text" name="vbalance" id="balance" class="form-control" placeholder="Balance" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- created -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Officer</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="vstaff_id" placeholder="Officer" id="officer" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Transaction Date</label>
                                    <div class="col-md-12">
                                        <input type="text" name="created_at" id="created_at" class="form-control" placeholder="Transaction Date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Updated At</label>
                                    <div class="col-md-12">
                                        <input type="text" name="updated_at" id="updated_at" class="form-control" placeholder="Updated At" readonly>
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
<!-- transaction chart -->
<script type="text/javascript">
    var selected = '<?= $selected ?>';
    var report = '<?= $report ?>';
</script>
<script src="/assets/scripts/reports/transactions.js"></script>
<script src="/assets/dist/js/charts/transaction.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>

<?= $this->endSection() ?>