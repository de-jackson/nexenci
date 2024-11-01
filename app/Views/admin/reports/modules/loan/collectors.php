<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<!-- Start::row-1 -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <!-- description -->
            <div class="card-header">
                You can this page to see the performance of your collectors and how much money they are collecting.
            </div>
            <!--  advanced search form -->
            <div class="card-body">
                <div class="h5 fw-semibold mb-0">Advanced Search:</div>
                <div class="contact-header">
                    <div class="d-sm-flex d-block align-items-center justify-content-between">
                        <div class="d-flex mt-sm-0 mt-2 align-items-center">
                            <form autocomplete="off">
                                <div class="row p-2">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="text" name="start_date" value="" class="form-control getDatePicker" value="<?= date('Y-m-d') ?>" id="start-date" placeholder="Start Date">
                                            <i><small class="fw-semibold">Click in the box above to select the start days</small></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="text" name="end_date" value="" class="form-control getDatePicker" value="<?= date('Y-m-d') ?>" id="end-date" placeholder="End Date">
                                            <i><small class="fw-semibold">Click in the box above to select the end days</small></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row p-2">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <select class="select2" multiple="multiple" data-placeholder="Select Loan Products" style="width: 100%;">
                                                    <option value="">Select</option>
                                                    <option>Alabama</option>
                                                    <option>Alaska</option>
                                                    <option>California</option>
                                                    <option>Delaware</option>
                                                    <option>Tennessee</option>
                                                    <option>Texas</option>
                                                    <option>Washington</option>
                                                </select>
                                                <i><small class="fw-semibold">Click in the box above to select multiple loan products</small></i>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <select class="select2" multiple="multiple" data-placeholder="Select Loan officers" style="width: 100%;">
                                                    <option value="">Select</option>
                                                    <option>Alabama</option>
                                                    <option>Alaska</option>
                                                    <option>California</option>
                                                    <option>Delaware</option>
                                                    <option>Tennessee</option>
                                                    <option>Texas</option>
                                                    <option>Washington</option>
                                                </select>
                                                <i><small class="fw-semibold">Click in the box above to select multiple loan officers</small></i>
                                            </div>
                                        </div>
                                    </div> -->
                                <div class="row p-2">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <select class="form-control select2bs4 currency_id" name="currency_id" id="currency_id" style="width: 100%;">
                                            </select>
                                            <i><small class="fw-semibold">Convert all figures to a specific currency</small></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <select class="select2" multiple="multiple" data-placeholder="Select a branch" style="width: 100%;">
                                                <option value="">Select</option>
                                                <option>Alabama</option>
                                                <option>Alaska</option>
                                                <option>California</option>
                                                <option>Delaware</option>
                                                <option>Tennessee</option>
                                                <option>Texas</option>
                                                <option>Washington</option>
                                            </select>
                                            <i><small class="fw-semibold">Click in the box above to select multiple branches</small></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary btn-block" id="filter-clients" value="filter"><i class="fa fa-search fa-fw"></i></button>
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
</div>
<!-- Start::row-2 -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <!-- collector table -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">

                    </div>
                </div>
                <div class="card-body">

                    <table id="clients9" class="table table-sm table-striped table-hover">
                        <thead class="bg-secondary">
                            <tr>
                                <th>Collector Name</th>
                                <th>Total Principal</th>
                                <th>Total Interest</th>
                                <th>Total Fees</th>
                                <th>Total Penalty</th>
                                <th>Total Collections</th>
                                <th width="5%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><b>Jackson Eyamu</b></td>
                                <td>1,000,000.00</td>
                                <td>300,000.00</td>
                                <td>0</td>
                                <td>20,000.00</td>
                                <td>1,320,000.00</td>
                                <td>
                                    <div class="btn-group-horizontal">
                                        <a type="button" class="btn bg-white btn-xs text-bold" href="https://x.loandisk.com/reports/collector_report.php?collector_id=61325&start_date=31/05/2022&end_date=31/05/2023&branches_select%5B0%5D=46415">View Logs</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- client table -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">

                </div>
            </div>
            <div class="card-body">

                <small class="fw-semibold">** Principal At Risk is the Principal Released amount after deducting Principal Payments for the date range selected above.</small>
                <table id="clients9" class="table table-sm table-striped table-hover">
                    <thead class="bg-secondary">
                        <tr>
                            <th>Date</th>
                            <th>Collector</th>
                            <th>Category</th>
                            <th>Borrower</th>
                            <th>Loan</th>
                            <th>Principal</th>
                            <th><b>Interest</b></th>
                            <th><b>Fees</b></th>
                            <th><b>Penalty</b></th>
                            <th width="5%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>18/05/2023</td>
                            <td><b>Jackson Eyamu</b>
                            </td>
                            <td>Business Loan</td>
                            <td>Sarah Apio</td>
                            <td>1000001</td>
                            <td>140,000.00</td>
                            <td>0</td>
                            <td>0</td>
                            <td>20,000.00</td>
                            <td>160,000.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    selectCurrency();
</script>
<!-- <script src="/assets/scripts/reports/clients.js"></script> -->
<script src="/assets/scripts/main/select-dropdowns.js"></script>

<?= $this->endSection() ?>