<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<!-- Start::row-1 -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <!-- description -->
            <div class="card-header">
                Please note that the above might not show the actual money released to borrowers since it may include restructured loans or overriden due amount loans where the due amount is less than the principal amount.
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
            <!-- client table -->
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">

                    </div>
                </div>
                <div class="card-body">

                    <small class="fw-semibold">**Please note that the table might not show the actual money released to borrowers since it may include restructured loans or overriden due amount loans where the due amount is less than the principal amount.</small>
                    <table id="clients9" class="table table-sm table-striped table-hover table-condensed">
                        <thead class="bg-secondary">
                            <tr>
                                <th>Loan Status</th>
                                <th></th>
                                <th>Principal</th>
                                <th>Interest</th>
                                <th>Fees</th>
                                <th>Penalty</th>
                                <th width="5%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="bg-gray">
                                    <b>Open Loans (On Schedule)</b>
                                </td>
                                <td class="text-red text-right">
                                    Gross Due
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class=" text-right">
                                    0
                                </td>
                                <td class="text-red text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td class="text-green text-right">
                                    Paid
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-green text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid #000"></td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000">
                                    Net Due
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-gray">
                                    <b>Missed Repayment Loans</b>
                                </td>
                                <td class="text-red text-right">
                                    Gross Due
                                </td>
                                <td class="text-right">
                                    300,000.00
                                </td>
                                <td class="text-right">
                                    90,000.00
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class=" text-right">
                                    0
                                </td>
                                <td class="text-red text-right">
                                    390,000.00
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td class="text-green text-right">
                                    Paid
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-green text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid #000"></td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000">
                                    Net Due
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    300,000.00
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    90,000.00
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000;">
                                    390,000.00
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-gray">
                                    <b>Arrears Loans</b>
                                </td>
                                <td class="text-red text-right">
                                    Gross Due
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class=" text-right">
                                    0
                                </td>
                                <td class="text-red text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td class="text-green text-right">
                                    Paid
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-green text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid #000"></td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000">
                                    Net Due
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-gray">
                                    <b>Past Maturity Loans</b>
                                </td>
                                <td class="text-red text-right">
                                    Gross Due
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class=" text-right">
                                    0
                                </td>
                                <td class="text-red text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td class="text-green text-right">
                                    Paid
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-green text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid #000"></td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000">
                                    Net Due
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-gray">
                                    <b>Fully Paid Loans</b>
                                </td>
                                <td class="text-red text-right">
                                    Gross Due
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class=" text-right">
                                    0
                                </td>
                                <td class="text-red text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td class="text-green text-right">
                                    Paid
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-green text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid #000"></td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000">
                                    Net Due
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-gray">
                                    <b>Default Loans</b>
                                </td>
                                <td class="text-red text-right">
                                    Gross Due
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class=" text-right">
                                    0
                                </td>
                                <td class="text-red text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td class="text-green text-right">
                                    Paid
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-green text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid #000"></td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000">
                                    Net Due
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-gray">
                                    <b>Restructured Loans</b>
                                </td>
                                <td class="text-red text-right">
                                    Gross Due
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class=" text-right">
                                    0
                                </td>
                                <td class="text-red text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td class="text-green text-right">
                                    Paid
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-right">
                                    0
                                </td>
                                <td class="text-green text-right">
                                    0
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid #000"></td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000">
                                    Net Due
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
                                <td class="text-bold text-right" style="border-bottom: 1px solid #000;">
                                    0
                                </td>
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