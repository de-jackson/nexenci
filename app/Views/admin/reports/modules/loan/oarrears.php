<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<!-- Start::row-1 -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <!-- description -->
            <div class="card-header">
                The Loan Arrears Aging Report shows the days in arrears of the overdue amount until today. This is calculated from the loan schedule for loans that are Missed Repayment, Arrears, or Past Maturity. We look at the last loan schedule date where the balance was 0 and then calculate the number of days between that schedule date and today.
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
                                            <input type="text" name="search" class=" form-control" id="search" placeholder="Show Loans Overdue until the following date">
                                            <i><small class="fw-semibold">Click in the box above to enter client name</small></i>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-3">
                                            <div class="form-group">
                                                <select class="form-control select2bs4" name="report_currency" id="inputCurrency">
                                                    <option value="">Select Currency (optional)</option>
                                                    <option value="AED">AED - &#1583;.&#1573;</option>
                                                    <option value="AFN">AFN - &#65;&#102;</option>
                                                    <option value="ALL">ALL - &#76;&#101;&#107;</option>
                                                    <option value="AMD">AMD - &#1423;</option>
                                                    <option value="ANG">ANG - &#402;</option>
                                                    <option value="AOA">AOA - &#75;&#122;</option>
                                                    <option value="ARS">ARS - &#36;</option>
                                                    <option value="AUD">AUD - &#36;</option>
                                                    <option value="AWG">AWG - &#402;</option>
                                                    <option value="AZN">AZN - &#1084;&#1072;&#1085;</option>
                                                    <option value="BAM">BAM - &#75;&#77;</option>
                                                    <option value="BBD">BBD - &#36;</option>
                                                    <option value="BDT">BDT - &#2547;</option>
                                                    <option value="BGN">BGN - &#1083;&#1074;</option>
                                                    <option value="BHD">BHD - .&#1583;.&#1576;</option>
                                                    <option value="BIF">BIF - &#70;&#66;&#117;</option>
                                                    <option value="BMD">BMD - &#36;</option>
                                                    <option value="BND">BND - &#36;</option>
                                                    <option value="BOB">BOB - &#36;&#98;</option>
                                                    <option value="BRL">BRL - &#82;&#36;</option>
                                                    <option value="BSD">BSD - &#36;</option>
                                                    <option value="BTN">BTN - &#78;&#117;&#46;</option>
                                                    <option value="BWP">BWP - &#80;</option>
                                                    <option value="BYR">BYR - &#112;&#46;</option>
                                                    <option value="BZD">BZD - &#66;&#90;&#36;</option>
                                                    <option value="CAD">CAD - &#36;</option>
                                                    <option value="CDF">CDF - &#70;&#67;</option>
                                                    <option value="CHF">CHF - &#67;&#72;&#70;</option>
                                                    <option value="CLF">CLF - CLF</option>
                                                    <option value="CLP">CLP - &#36;</option>
                                                    <option value="CNY">CNY - &#165;</option>
                                                    <option value="COP">COP - &#36;</option>
                                                    <option value="CRC">CRC - &#8353;</option>
                                                    <option value="CUP">CUP - &#8396;</option>
                                                    <option value="CVE">CVE - &#36;</option>
                                                    <option value="CZK">CZK - &#75;&#269;</option>
                                                    <option value="DJF">DJF - &#70;&#100;&#106;</option>
                                                    <option value="DKK">DKK - &#107;&#114;</option>
                                                    <option value="DOP">DOP - &#82;&#68;&#36;</option>
                                                    <option value="DZD">DZD - &#1583;&#1580;</option>
                                                    <option value="EGP">EGP - &#163;</option>
                                                    <option value="ETB">ETB - &#66;&#114;</option>
                                                    <option value="EUR">EUR - &#8364;</option>
                                                    <option value="FJD">FJD - &#36;</option>
                                                    <option value="FKP">FKP - &#163;</option>
                                                    <option value="GBP">GBP - &#163;</option>
                                                    <option value="GEL">GEL - &#4314;</option>
                                                    <option value="GHS">GHS - &#162;</option>
                                                    <option value="GIP">GIP - &#163;</option>
                                                    <option value="GMD">GMD - &#68;</option>
                                                    <option value="GNF">GNF - &#70;&#71;</option>
                                                    <option value="GTQ">GTQ - &#81;</option>
                                                    <option value="GYD">GYD - &#36;</option>
                                                    <option value="HKD">HKD - &#36;</option>
                                                    <option value="HNL">HNL - &#76;</option>
                                                    <option value="HRK">HRK - &#107;&#110;</option>
                                                    <option value="HTG">HTG - &#71;</option>
                                                    <option value="HUF">HUF - &#70;&#116;</option>
                                                    <option value="IDR">IDR - &#82;&#112;</option>
                                                    <option value="ILS">ILS - &#8362;</option>
                                                    <option value="INR">INR - &#8377;</option>
                                                    <option value="IQD">IQD - &#1593;.&#1583;</option>
                                                    <option value="IRR">IRR - &#65020;</option>
                                                    <option value="ISK">ISK - &#107;&#114;</option>
                                                    <option value="JEP">JEP - &#163;</option>
                                                    <option value="JMD">JMD - &#74;&#36;</option>
                                                    <option value="JOD">JOD - &#74;&#68;</option>
                                                    <option value="JPY">JPY - &#165;</option>
                                                    <option value="KES">KES - &#75;&#83;&#104;</option>
                                                    <option value="KGS">KGS - &#1083;&#1074;</option>
                                                    <option value="KHR">KHR - &#6107;</option>
                                                    <option value="KMF">KMF - &#67;&#70;</option>
                                                    <option value="KPW">KPW - &#8361;</option>
                                                    <option value="KRW">KRW - &#8361;</option>
                                                    <option value="KWD">KWD - &#1583;.&#1603;</option>
                                                    <option value="KYD">KYD - &#36;</option>
                                                    <option value="KZT">KZT - &#1083;&#1074;</option>
                                                    <option value="LAK">LAK - &#8365;</option>
                                                    <option value="LBP">LBP - &#163;</option>
                                                    <option value="LKR">LKR - &#8360;</option>
                                                    <option value="LRD">LRD - &#36;</option>
                                                    <option value="LSL">LSL - &#76;</option>
                                                    <option value="LTL">LTL - &#76;&#116;</option>
                                                    <option value="LVL">LVL - &#76;&#115;</option>
                                                    <option value="LYD">LYD - &#1604;.&#1583;</option>
                                                    <option value="MAD">MAD - &#1583;.&#1605;.</option>
                                                    <option value="MDL">MDL - &#76;</option>
                                                    <option value="MGA">MGA - &#65;&#114;</option>
                                                    <option value="MKD">MKD - &#1076;&#1077;&#1085;</option>
                                                    <option value="MMK">MMK - &#75;</option>
                                                    <option value="MNT">MNT - &#8366;</option>
                                                    <option value="MOP">MOP - &#77;&#79;&#80;&#36;</option>
                                                    <option value="MRO">MRO - &#85;&#77;</option>
                                                    <option value="MUR">MUR - &#8360;</option>
                                                    <option value="MVR">MVR - .&#1923;</option>
                                                    <option value="MWK">MWK - &#77;&#75;</option>
                                                    <option value="MXN">MXN - &#36;</option>
                                                    <option value="MYR">MYR - &#82;&#77;</option>
                                                    <option value="MZN">MZN - &#77;&#84;</option>
                                                    <option value="NAD">NAD - &#36;</option>
                                                    <option value="NGN">NGN - &#8358;</option>
                                                    <option value="NIO">NIO - &#67;&#36;</option>
                                                    <option value="NOK">NOK - &#107;&#114;</option>
                                                    <option value="NPR">NPR - &#8360;</option>
                                                    <option value="NZD">NZD - &#36;</option>
                                                    <option value="OMR">OMR - &#65020;</option>
                                                    <option value="PAB">PAB - &#66;&#47;&#46;</option>
                                                    <option value="PEN">PEN - &#83;&#47;&#46;</option>
                                                    <option value="PGK">PGK - &#75;</option>
                                                    <option value="PHP">PHP - &#8369;</option>
                                                    <option value="PKR">PKR - &#8360;</option>
                                                    <option value="PLN">PLN - &#122;&#322;</option>
                                                    <option value="PYG">PYG - &#71;&#115;</option>
                                                    <option value="QAR">QAR - &#65020;</option>
                                                    <option value="RON">RON - &#108;&#101;&#105;</option>
                                                    <option value="RSD">RSD - &#1044;&#1080;&#1085;&#46;</option>
                                                    <option value="RUB">RUB - &#1088;&#1091;&#1073;</option>
                                                    <option value="RWF">RWF - &#1585;.&#1587;</option>
                                                    <option value="SAR">SAR - &#65020;</option>
                                                    <option value="SBD">SBD - &#36;</option>
                                                    <option value="SCR">SCR - &#8360;</option>
                                                    <option value="SDG">SDG - &#163;</option>
                                                    <option value="SEK">SEK - &#107;&#114;</option>
                                                    <option value="SGD">SGD - &#36;</option>
                                                    <option value="SHP">SHP - &#163;</option>
                                                    <option value="SLL">SLL - &#76;&#101;</option>
                                                    <option value="SOS">SOS - &#83;</option>
                                                    <option value="SRD">SRD - &#36;</option>
                                                    <option value="STD">STD - &#68;&#98;</option>
                                                    <option value="SVC">SVC - &#36;</option>
                                                    <option value="SYP">SYP - &#163;</option>
                                                    <option value="SZL">SZL - &#76;</option>
                                                    <option value="THB">THB - &#3647;</option>
                                                    <option value="TJS">TJS - &#84;&#74;&#83;</option>
                                                    <option value="TMT">TMT - &#109;</option>
                                                    <option value="TND">TND - &#1583;.&#1578;</option>
                                                    <option value="TOP">TOP - &#84;&#36;</option>
                                                    <option value="TRY">TRY - &#8356;</option>
                                                    <option value="TTD">TTD - &#36;</option>
                                                    <option value="TWD">TWD - &#78;&#84;&#36;</option>
                                                    <option value="TZS">TZS - TZS</option>
                                                    <option value="UAH">UAH - &#8372;</option>
                                                    <option value="UGX">UGX - &#85;&#83;&#104;</option>
                                                    <option value="USD">USD - &#36;</option>
                                                    <option value="UYU">UYU - &#36;&#85;</option>
                                                    <option value="UZS">UZS - &#1083;&#1074;</option>
                                                    <option value="VEF">VEF - &#66;&#115;</option>
                                                    <option value="VND">VND - &#8363;</option>
                                                    <option value="VUV">VUV - &#86;&#84;</option>
                                                    <option value="WST">WST - &#87;&#83;&#36;</option>
                                                    <option value="XAF">XAF - &#70;&#67;&#70;&#65;</option>
                                                    <option value="XCD">XCD - &#36;</option>
                                                    <option value="XDR">XDR - XDR</option>
                                                    <option value="XOF">XOF - XOF</option>
                                                    <option value="XPF">XPF - &#70;</option>
                                                    <option value="YER">YER - &#65020;</option>
                                                    <option value="ZAR">ZAR - &#82;</option>
                                                    <option value="ZMK">ZMK - &#90;&#77;&#87;</option>
                                                    <option value="ZWL">ZWL - &#90;&#36;</option>
                                                </select>
                                                <i><small class="fw-semibold">Convert all figures to a specific currency</small></i>
                                            </div>
                                        </div> -->
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" name="start_date" value="" class="form-control getDatePicker" id="start-date" placeholder="Loans Overdue Start Days">
                                            <i><small class="fw-semibold">Click in the box above to select the start days</small></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" name="end_date" value="" class="form-control getDatePicker" id="end-date" placeholder="Loans Overdue End Days">
                                            <i><small class="fw-semibold">Click in the box above to select the end days</small></i>
                                        </div>
                                    </div>
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

                    <small class="fw-semibold">** Principal At Risk is the Principal Released amount after deducting Principal Payments for the date range selected above.</small>
                    <table id="clients9" class="table table-sm table-striped table-hover">
                        <thead class="bg-secondary">
                            <tr>
                                <th>Released Date</th>
                                <th>Loan Officer</th>
                                <th>Borrower</th>
                                <th>Loan#</th>
                                <th>Days in arrears</th>
                                <th>Overdue Principal</th>
                                <th>Overdue Interest</th>
                                <th>Overdue Fees</th>
                                <th>Overdue Penalty</th>
                                <th>Overdue Balance</th>
                                <th>Total Principal Outstanding</th>
                                <th width="5%">Status</th>
                            </tr>
                        </thead>
                        <tbody>

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