<?= $this->extend("layout/client"); ?>

<?= $this->section("content"); ?>
<?php
function convert_number($number = null)
{
    if (($number < 0) || ($number > 999999999)) {
        return "Number is out of range";
    }
    $Gn = floor($number / 1000000);
    /* Millions (giga) */
    $number -= $Gn * 1000000;
    $kn = floor($number / 1000);
    /* Thousands (kilo) */
    $number -= $kn * 1000;
    $Hn = floor($number / 100);
    /* Hundreds (hecto) */
    $number -= $Hn * 100;
    $Dn = floor($number / 10);
    /* Tens (deca) */
    $n = $number % 10;
    /* Ones */
    $res = "";
    if ($Gn) {
        $res .= convert_number($Gn) .  " Million";
    }
    if ($kn) {
        $res .= (empty($res) ? "" : " ") . convert_number($kn) . " Thousand";
    }
    if ($Hn) {
        $res .= (empty($res) ? "" : " ") . convert_number($Hn) . " Hundred";
    }
    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
    if ($Dn || $n) {
        if (!empty($res)) {
            $res .= " and ";
        }
        if ($Dn < 2) {
            $res .= $ones[$Dn * 10 + $n];
        } else {
            $res .= $tens[$Dn];
            if ($n) {
                $res .= "-" . $ones[$n];
            }
        }
    }
    if (empty($res)) {
        $res = "zero";
    }
    return $res;
}

switch (strtolower($application['repayment_freq'])) {
    case 'weekly':
        $grace_period = 7;
        break;
    case 'bi-weekly':
        $grace_period = 14;
        break;
    case 'monthly':
        $grace_period = 30;
        break;
    case 'bi-monthly':
        $grace_period = 60;
        break;
    case 'quarterly':
        $grace_period = 90;
        break;
    case 'termly':
        $grace_period = 120;
        break;
    case 'bi-annual':
        $grace_period = 180;
        break;
    case 'annually':
        $grace_period = 365;
        break;
}
?>


<!-- Start::row-1 -->
<div class="row gx-3 gy-2 align-items-center mt-0">
    <div class="col-xl-12">
        <div class="card border border-warning custom-card">
            <div class="card-header">
                <div class="card-title">
                    <div class="d-sm-flex d-block align-items-center justify-content-between">
                        <div class="h5 fw-semibold mb-0"> Application Details</div>
                        <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body  p-3 product-checkout">
                <ul class="nav nav-tabs tab-style-2 d-sm-flex d-block border-bottom border-block-end-dashed" id="myTab1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="applicant-tab" data-bs-toggle="tab" data-bs-target="#applicant-tab-pane" type="button" role="tab" aria-controls="applicant-tab" aria-selected="true"><i class="fas fa-user-tag me-2 align-middle"></i>Applicant</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="application-tab" data-bs-toggle="tab" data-bs-target="#application-tab-pane" type="button" role="tab" aria-controls="confirmed-tab" aria-selected="false"><i class="fas fa-clipboard me-2 align-middle"></i>Application</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="charges-tab" data-bs-toggle="tab" data-bs-target="#charges-tab-pane" type="button" role="tab" aria-controls="charges-tab" aria-selected="false"><i class="fas fa-money-bill-wave me-2 align-middle"></i>Charges</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="remarks-tab" data-bs-toggle="tab" data-bs-target="#remarks-tab-pane" type="button" role="tab" aria-controls="remarks-tab" aria-selected="false"><i class="fas fa-comments me-2 align-middle"></i>Remarks</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <!-- Disburse Loan button  -->
                        <button class="nav-link text-info" onclick="add_applicationRemarks('<?= $application['id'] ?>','disburse')" id="disburseBtn" title="Disburse Loan" style="display: none;">
                            <i class="fas fa-money-bill-trend-up"></i>Disburse
                        </button>
                    </li>
                    <li class="nav-item" role="presentation" id="cancelApplication">
                        <button class="nav-link text-danger" onclick="cancel_application()" title="Cancel Loan Application"><i class="fas fa-cancel me-2 align-middle"></i>Cancel</button>
                    </li>
                    <?php if ((unserialize($user['permissions']) == 'all') || (in_array('export' . ucwords(str_replace(' ', '', $title)), unserialize($user['permissions'])))) : ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-info" onclick="exportApplicationForm()" title="Export Application Details">
                                <i class="fas fa-print"></i>
                            </button>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- applicant details -->
                    <div class="tab-pane fade show active " id="applicant-tab-pane" role="tabpanel" aria-labelledby="applicant-tab-pane" tabindex="0">
                        <form class="form-horizontal" autocomplete="off">
                            <input type="hidden" readonly value="" name="c_id" />
                            <div class="form-body">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-8">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Full Name</label>
                                                    <div class="col-md-12">
                                                        <input name="name" placeholder="Full Name" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Gender</label>
                                                    <div class="col-md-12">
                                                        <input name="gender" placeholder="Gender" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Religion</label>
                                                    <div class="col-md-12">
                                                        <input name="religion" placeholder="Religion" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Marital Status</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="marital_status" placeholder="Marital Status" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Nationality</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="nationality" placeholder="Nationality" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label class="control-label fw-bold">PassPort Photo</label>
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
                                            <label class="control-label fw-bold">Phone Number 1</label>
                                            <div class="col-md-12">
                                                <input name="mobile" placeholder="Phone Number" class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Phone Number 2</label>
                                            <div class="col-md-12">
                                                <input name="alt_mobile" placeholder="Phone Number 2" class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Email</label>
                                            <div class="col-md-12">
                                                <input name="email" placeholder="example@mail.com" class="form-control" type="email">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">D.O.B</label>
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                    <input name="dob" placeholder="" class="form-control" type="date" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Occupation</label>
                                            <div class="col-md-12">
                                                <input name="occupation" placeholder="Occupation" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Business Location</label>
                                            <div class="col-md-12">
                                                <input name="job_location" placeholder="Bussiness Location" class="form-control" type="text" readonly readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Applicant Address</label>
                                            <div class="col-md-12">
                                                <textarea name="residence" placeholder="Applicant Address" class="form-control" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- id -->
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <label class="control-label fw-bold col-md-12">Applicant ID(Front)</label>
                                        <div class="form-group" id="id-preview">
                                            <div class="col-md-12">
                                                (No photo)
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Id Type</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="id_type" placeholder="ID Type" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Id Number</label>
                                                    <div class="col-md-12">
                                                        <input name="id_number" placeholder="Id Number" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Id Expiry Date</label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                            <input name="id_expiry" class="form-control" type="date" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- nok -->
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Next of Kin Name</label>
                                            <div class="col-md-12">
                                                <input name="next_of_kin" placeholder="Next Of Kin Name" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Next of Kin Relationship</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="nok_relationship" placeholder="Next of Kin Relationship" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Next of Kin Address</label>
                                            <div class="col-md-12">
                                                <input name="nok_address" placeholder="Next of Kin Address" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Next of Kin Phone 1</label>
                                            <div class="col-md-12">
                                                <input name="nok_phone" placeholder="Next of Kin Phone 1" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Next of Kin Phone 2</label>
                                            <div class="col-md-12">
                                                <input name="nok_alt_phone" placeholder="Next of Kin Phone 2" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Age(Years)</label>
                                            <div class="col-md-12">
                                                <input name="age" placeholder="Applicant Age" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- account -->
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Applicant Branch</label>
                                            <div class="col-md-12">
                                                <input name="branch_name" placeholder="Applicant Branch" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Account Number</label>
                                            <div class="col-md-12">
                                                <input type="text" name="account_no" placeholder="Account Number" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold">Account Type</label>
                                            <div class="col-md-12">
                                                <input type="text" name="client_account" placeholder="Account Type" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- application details -->
                    <div class="tab-pane fade " id="application-tab-pane" role="tabpanel" aria-labelledby="application-tab-pane" tabindex="0">
                        <div class="card border border-warning custom-card">
                            <div class="card-header">
                                <div class="card-title">
                                    Application Data
                                </div>
                            </div>
                            <div class="card-body">
                                <nav>
                                    <div class="nav nav-tabs nav-justified tab-style-4 d-sm-flex d-block" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-loan-tab" data-bs-toggle="tab" data-bs-target="#nav-loan1" type="button" role="tab" aria-selected="true">Loan Terms</button>
                                        <button class="nav-link" id="nav-security-tab" data-bs-toggle="tab" data-bs-target="#nav-security1" type="button" role="tab" aria-selected="false">Loan Security</button>
                                        <button class="nav-link" id="nav-referees-tab" data-bs-toggle="tab" data-bs-target="#nav-referees1" type="button" role="tab" aria-selected="false">Client Referees</button>
                                        <button class="nav-link" id="nav-financial-tab" data-bs-toggle="tab" data-bs-target="#nav-financial1" type="button" role="tab" aria-selected="false">Financial Position</button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="tab-style-4">
                                    <!-- loan details -->
                                    <div class="tab-pane show active" id="nav-loan1" role="tabpanel" aria-labelledby="nav-loan-tab" tabindex="0">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold" for="product_id">Application Code</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vapplication_code" class="form-control" placeholder="Application Code" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold" for="principal">Principal [<?= $settings['currency']; ?>]</label>
                                                    <div class="col-md-12">
                                                        <input name="vprincipal" id="principal" placeholder="Principal [<?= $settings['currency']; ?>]" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold" for="product_id">Loan Product</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vproduct_id" class="form-control" placeholder="Loan Product" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Interest Rate</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vinterest_rate" class="form-control" placeholder="Interest Rate" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Interest Method</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vinterest_type" class="form-control" placeholder="Interest Method" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Repayment Mode</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vrepayment_freq" class="form-control" placeholder="Repayment Mode" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Repayment Period</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vrepayment_period" class="form-control" placeholder="Repayment Period" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Application Status</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vstatus" class="form-control" placeholder="Application Status" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold">Level</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vlevel" class="form-control" placeholder="Level" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Overall Charges</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="overall_charges" id="charges" class="form-control" placeholder="Overall Charges" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Total Charges</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="total_charges" id="total_charges" class="form-control" placeholder="Total Charges" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Reduct Charges On</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="reduct_charges" id="reduct_charges" class="form-control" placeholder="Reduct Charges" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold" for="purpose">Loan Purpose</label>
                                                    <div class="col-md-12">
                                                        <textarea name="vpurpose" id="purpose" placeholder="Loan Purpose" class="form-control" readonly></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- loan security/collaraterals -->
                                    <div class="tab-pane" id="nav-security1" role="tabpanel" aria-labelledby="nav-security-tab" tabindex="0">
                                        <!-- security item details -->
                                        <div class="card border border-warning custom-card">
                                            <div class="card-header">
                                                <h5 class="card-title text-bold">Security Item</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="security_item">Security Item</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="vsecurity_item" id="security_item" class="form-control" placeholder="Security Item" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="est_value">Estimated Value [<?= $settings['currency']; ?>]</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="vest_value" id="est_value" class="form-control" placeholder="Estimated Value [<?= $settings['currency']; ?>]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold">Details</label>
                                                            <div class="col-md-12">
                                                                <textarea name="vsecurity_info" id="viewSummernote" class="form-control" placeholder="Details"></textarea readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- collateral files -->
                                        <div class="card border border-warning custom-card">
                                            <div class="card-header">
                                                <h5 class="card-title text-bold">Collateral Images</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="collaterals" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                                        <thead class="">
                                                            <tr>
                                                                <th><input type="checkbox" name="" id="check-all"></th>
                                                                <th>S.No</th>
                                                                <th>File Name</th>
                                                                <th>Extension</th>
                                                                <th>Preview</th>
                                                                <th width="5%">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- referees/quarantors -->
                                    <div class="tab-pane" id="nav-referees1" role="tabpanel" aria-labelledby="nav-referees-tab" tabindex="0">
                                        <div class="card border border-warning custom-card">
                                            <div class="card-header">
                                                <h5 class="card-title text-bold">Referees/ Guarantors</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_name">Full Name</label>
                                                            <input type="text" name="vref_name" id="ref_name" class="form-control" placeholder="Full Name" readonly>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_relation">Relationship</label>
                                                            <input type="text" name="vref_relation" id="ref_relation" class="form-control" placeholder="Relationship" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_job">Occupation</label>
                                                            <input type="text" name="vref_job" id="ref_job" class="form-control" placeholder="Occupation" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_contact">Contact 1</label>
                                                            <input type="tel" name="vref_contact" id="ref_contact" class="form-control" placeholder="Contact 1" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_alt_contact">Contact 2</label>
                                                            <input type="tel" name="vref_alt_contact" id="ref_alt_contact" class="form-control" placeholder="Contact 2" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_email">Email</label>
                                                            <input type="email" name="vref_email" id="ref_email" class="form-control" placeholder="Email" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_address2">Address</label>
                                                            <textarea name="vref_address" id="ref_address2" class="form-control" placeholder="Address"></textarea readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_name2">Full Name</label>
                                                            <input type="text" name="vref_name2" id="ref_name2" class="form-control" placeholder="Full Name" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="relation2">Relationship</label>
                                                            <input type="text" name="vref_relation2" id="ref_relation" class="form-control" placeholder="Relationship" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_job2">Occupation</label>
                                                            <input type="text" name="vref_job2" id="ref_job2" class="form-control" placeholder="Occupation" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_contact2">Contact 1</label>
                                                            <input type="tel" name="vref_contact2" id="ref_contact2" class="form-control" placeholder="Contact 1" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_alt_contact2">Contact 2</label>
                                                            <input type="tel" name="vref_alt_contact2" id="ref_alt_contact2" class="form-control" placeholder="Contact 2" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_email2">Email</label>
                                                            <input type="email" name="vref_email2" id="ref_email2" class="form-control" placeholder="Email" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_address2">Address</label>
                                                            <textarea name="vref_address2" id="ref_address2" class="form-control" placeholder="Address"></textarea readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- client financial position -->
                                    <div class="tab-pane" id="nav-financial1" role="tabpanel" aria-labelledby="nav-financial-tab" tabindex="0">
                                        <!-- accounts in other banks n institutes -->
                                        <div class="card border border-warning custom-card">
                                            <div class="card-header">
                                                <h5 class="card-title text-bold">
                                                    Other Financial Institutions Accounts
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-4" for="instution">Institution</label>
                                                    <label class="control-label fw-bold col-4" for="branch">Branch</label>
                                                    <label class="control-label fw-bold col-4" for="state">Account Type</label>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vinstitute_name" id="instution" class="form-control" placeholder="Institution" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vinstitute_branch" id="branch" class="form-control" placeholder="Branch" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vaccount_type" class="form-control" placeholder="Account Type" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vinstitute_name2" id="instution2" class="form-control" placeholder="Institution" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vinstitute_branch2" id="branch2" class="form-control" placeholder="Branch" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vaccount_type2" class="form-control" placeholder="Account Type" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- loans in other banks and institutes -->
                                        <div class="card border border-warning custom-card">
                                            <div class="card-header">
                                                <h5 class="card-title text-bold">
                                                    Loans In Other Financial Institutions
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <label class="control-label fw-bold col-3" for="amt_advance">Amount Advance [<?= $settings['currency']; ?>]</label>
                                                    <label class="control-label fw-bold col-3" for="date_advance">Date Advance</label>
                                                    <label class="control-label fw-bold col-3" for="loan_duration">Loan Duration</label>
                                                    <label class="control-label fw-bold col-3" for="amt_outstanding">Outstanding Amount [<?= $settings['currency']; ?>]</label>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vamt_advance" id="amt_advance" class="form-control" placeholder="Amount Advance [<?= $settings['currency']; ?>]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vdate_advance" id="date_advance" class="form-control" placeholder="Date Advance" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vloan_duration" id="loan_duration" class="form-control" placeholder="Loan Duration" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vamt_outstanding" id="amt_outstanding" class="form-control" placeholder="Outstanding Amount [<?= $settings['currency']; ?>]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vamt_advance2" id="amt_advance2" class="form-control" placeholder="Amount Advance [<?= $settings['currency']; ?>]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vdate_advance2" id="date_advance2" class="form-control" placeholder="Date Advance" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vloan_duration2" id="loan_duration2" class="form-control" placeholder="Loan Duration" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input type="text" name="vamt_outstanding2" id="amt_outstanding2" class="form-control" placeholder="Outstanding Amount [<?= $settings['currency']; ?>]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- client Income and Expenses  -->
                                        <div class="card border border-warning custom-card">
                                            <div class="card-header">
                                                <h5 class="card-title text-bold">
                                                    Income and Expenses [<?= $settings['currency']; ?>]
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <p class="col-md-12"></p>
                                                    <div class="col-md-6">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-12">
                                                                <div class="form-group text-center">
                                                                    <p class="col-md-12">Income</p>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="salary" class="col-md-12 form-label">Net Salary</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vnet_salary" class="form-control" id="salary" placeholder="Net Salary" readonly>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="farming" class="col-md-12 form-label">Farming</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="farming" class="form-control" id="farming" placeholder="Farming" readonly>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="business" class="col-md-12 form-label">Business</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vbusiness" class="form-control" id="business" placeholder="Business" readonly>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="others" class="col-md-12 form-label">Others</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vothers" class="form-control" id="others" placeholder="Others" readonly>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="" class="col-md-12 form-label"></label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="" class="form-control" id="" placeholder="" disabled>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="total" class="col-md-12 form-label">Total</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="number" name="vincome_total" class="form-control" id="total" placeholder="Total" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 border-left border-1">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-12">
                                                                <div class="form-group text-center">
                                                                    <p class="col-md-12">Expenses</p>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="rent" class="col-md-12 form-label">Rent</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vrent" class="form-control" id="rent" placeholder="Rent" readonly>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="education" class="col-md-12 form-label">Education</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="veducation" class="form-control" id="education" placeholder="Education" readonly>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="medical" class="col-md-12 form-label">Medical</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vmedical" class="form-control" id="medical" placeholder="Medical" readonly>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="transport" class="col-md-12 form-label">Transport</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vtransport" class="form-control" id="transport" placeholder="Transport" readonly>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="exp_others" class="col-md-12 form-label">Others</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vexp_others" class="form-control" id="exp_others" placeholder="Others" readonly>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="exp_total" class="col-md-12 form-label">Total</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vexp_total" class="form-control" id="exp_total" placeholder="Total" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="difference" class="col-md-12 form-label">Difference</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" name="vdifference" class="form-control" id="difference" placeholder="Difference" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                <label for="dif_status" class="col-md-12 form-label">Difference
                                                                    Status</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="vdif_status" placeholder="Difference Status" id="dif_status" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- receipts,invoices,slips -->
                                        <div class="card border border-warning custom-card">
                                            <div class="card-header">
                                                <h5 class="card-title text-bold">Reciepts\Invoices\Slips</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="files" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                                        <thead class="">
                                                            <tr>
                                                                <th><input type="checkbox" name="" id="check-all"></th>
                                                                <th>S.No</th>
                                                                <th>File Name</th>
                                                                <th>Extension</th>
                                                                <th>Type</th>
                                                                <th>Preview</th>
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
                    <!-- charges details -->
                    <div class="tab-pane fade " id="charges-tab-pane" role="tabpanel" aria-labelledby="charges-tab-pane" tabindex="0">
                        <div class="card border border-warning custom-card">
                            <div class="card-header">
                                <h5 class="card-title text-bold">Particulars Payable</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="charges" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                        <thead class="">
                                            <tr>
                                                <th>S.No</th>
                                                <th>Particular</th>
                                                <th>Charge</th>
                                                <th>Fee</th>
                                                <th>Total Charge [<?= $settings['currency']; ?>]</th>
                                                <th>Paid [<?= $settings['currency']; ?>]</th>
                                                <th>Balance [<?= $settings['currency']; ?>]</th>
                                                <th width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="applicationCharges"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card border border-warning card-custom">
                                <div class="card-header">
                                    <h5 class="card-title text-bold">Payment History</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="chargesPayments" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                            <thead class="">
                                                <tr>
                                                    <th><input type="checkbox" name="" id="check-all"></th>
                                                    <th>S.No</th>
                                                    <th>Particular</th>
                                                    <th>Amount Paid</th>
                                                    <th>Ref ID</th>
                                                    <th>Date</th>
                                                    <th>Officer</th>
                                                    <th width="5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- remarks details -->
                    <div class="tab-pane fade " id="remarks-tab-pane" role="tabpanel" aria-labelledby="remarks-tab-pane" tabindex="0">
                        <!-- Add Remarks button -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="float-end">
                                    <button class="nav-link text-primary" onclick="add_applicationRemarks('<?= $application['id'] ?>')" id="applicationRemarksBtn" title="Add Action">
                                        <i class="fas fa-comments"></i> Add Action
                                    </button><br>
                                </div>
                            </div>
                        </div>
                        <div class="" id="remarksCard">
                            <!-- ajax remarks here -->
                        </div>
                    </div>
                    <!-- footer[dates n signiture] -->
                    <div class="row gx-3 gy-2 align-items-center mt-0">
                        <div class="col-md-8">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold" for="ref_alt_contact2">Created At</label>
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <input name="vcreated_at" placeholder="Created At" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold" for="ref_alt_contact2">Updated At</label>
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <input name="vupdated_at" placeholder="Updated At" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold" for="ref_alt_contact2">Responsible Officer</label>
                                        <input name="vloan_officer" placeholder="officer"  class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <label class="control-label fw-bold col-md-12">Applicant Signature</label>
                            <div class="form-group" id="signature-preview">
                                <div class="col-md-12">
                                    (No Sign)
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- file add model -->
<div class="modal fade"  data-bs-backdrop="static" id="modal_form">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="file_form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly value="" name="id" />
                    <input type="hidden" readonly name="application_id" value="<?= $application['id']; ?>" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Select Files To Upload</label>
                                    <div class="col-md-12">
                                        <input type="file" class="form-control" name="collateral[]" id="files" multiple>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnFile" onclick="save_file()" class="btn btn-outline-success">Upload</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- file view modal -->
<div class="modal fade"  data-bs-backdrop="static" id="file_view">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-success text-center"> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form well well-lg one_well ">
                <form class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <center>
                                    <div class="form-group" id="file-preview">
                                        <label class="control-label fw-bold"></label>
                                        <div class="col-md-12">
                                            (No photo)
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </center>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="col-md-12 control-label fw-bold">Client Name</label>
                                    <div class="col-md-12">
                                        <input type="text" name="applicant_name" id="name" class="form-control" placeholder="Client Name" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="col-md-12 control-label fw-bold">File Name</label>
                                    <div class="col-md-12">
                                        <input type="text" name="file_name" id="file_name" class="form-control" placeholder="File Name" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date" class="col-md-12 control-label fw-bold">Upload Date</label>
                                    <div class="col-md-12">
                                        <input type="text" name="created_at" id="date" class="form-control" placeholder="Uploded date" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- payment add model -->
<div class="modal fade"  data-bs-backdrop="static" id="pay_modal_form">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form id="paymentForm" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly name="application_id" id="application_id" />
                    <input type="hidden" readonly name="client_id" id="client_id" />
                    <input type="hidden" readonly name="account_typeId" id="account_typeId" />
                    <input type="hidden" readonly name="particular_id" id="particular_id" />
                    <input type="text" readonly name="entry_menu" id="entry_menu" />
                    <div class="form-body">
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount" class="control-label fw-bold col-sm-12">Transaction Type</label>
                                    <div class="col-sm-12">
                                        <select name="entry_typeId" id="entry_typeId" class="form-control select2bs4">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Payment Method</label>
                                    <div class="col-md-12">
                                        <select name="payment_id" id="payment_id" class="form-control select2bs4" style="width: 100%;">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Contact</label>
                                    <div class="col-md-12">
                                        <input name="contact" placeholder="Contact" class="form-control" type="text">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Charge [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input type="number" name="charge" class="form-control" placeholder="Charge [<?= $settings['currency']; ?>]" min="0" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Amount to be Paid [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input type="number" id="amount" name="amount" class="form-control" placeholder="Amount to be Paid [<?= $settings['currency']; ?>]" min="0">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount" class="control-label fw-bold col-sm-12">Transaction Date</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <input type="text" name="date" id="date" class="form-control getDatePicker" value="<?= date('Y-m-d'); ?>" placeholder="Transaction Date">
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Details</label>
                                    <div class="col-md-12">
                                        <textarea name="entry_details" class="form-control" id="viewSummernote"></textarea>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold">Remarks</label>
                                                            <div class="col-md-12">
                                                                <textarea name="remarks" class="form-control" placeholder="Remarks"></textarea>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <input name="staff_id" placeholder="Responsible Officer" class="form-control" type="hidden" value="<?= session()->get('id'); ?>">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="btnPay" onclick="save_applicationPayment()" class="btn btn-outline-success">Submit</button>
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- view payment details -->
                            <div class="modal fade" data-bs-backdrop="static" id="view_pay_modal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title"></h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body form">
                                            <form id="payment_form" class="form-horizontal" autocomplete="off">
                                                <input type="hidden" readonly value="" name="id" />
                                                <div class="form-body">
                                                    <!-- client -->
                                                    <div class="row gx-3 gy-2 align-items-center mt-0" id="clientData" style="display: none;">
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
                                                    <div class="row gx-3 gy-2 align-items-center mt-0" id="disbursementData" style="display: none;">
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
                                                    <div class="row gx-3 gy-2 align-items-center mt-0" id="applicationData" style="display: none;">
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
                            <!-- view application particular -->
                            <div class="modal fade" data-bs-backdrop="static" id="view_particular_modal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content ">
                                        <div class="modal-header">
                                            <h6 class="modal-title"></h6>
                                            <div class="close">
                                                <btn type="button" class="btn btn-md btn-danger">
                                                    <i class="fas fa-file-pdf text-light"></i>
                                                </btn>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                        </div>
                                        <div class="modal-body form">
                                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                                <input type="hidden" readonly value="" name="pid" />
                                                <div class="form-body">
                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Category Name</label>
                                                                <div class="col-md-12">
                                                                    <input name="category_name" placeholder="Category Name" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">SubCategory Name</label>
                                                                <div class="col-md-12">
                                                                    <input name="subcategory_name" placeholder="SubCategory Name" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Particular Name</label>
                                                                <div class="col-md-12">
                                                                    <input name="particular_name" placeholder="Particular Name" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Particular Module</label>
                                                                <div class="col-md-12">
                                                                    <input name="module" placeholder="Particular Module" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gx-3 gy-2 align-items-center mt-0" id="charged">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Expected Charge [<?= $settings['currency']; ?>]</label>
                                                                <div class="col-md-12">
                                                                    <input name="charge" class="form-control" placeholder="Expected Charge [<?= $settings['currency']; ?>]" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Charge Method</label>
                                                                <div class="col-md-12">
                                                                    <input name="charge_method" class="form-control" placeholder="Charge Method" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Particular Status</label>
                                                                <div class="col-md-12">
                                                                    <input name="particular_status" placeholder="Particular status" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Particular Slug</label>
                                                                <div class="col-md-12">
                                                                    <input name="particular_slug" placeholder="Particular Slug" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Created At</label>
                                                                <div class="col-md-12">
                                                                    <input name="created_at" class="form-control" placeholder="Created At" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Updated At</label>
                                                                <div class="col-md-12">
                                                                    <input name="updated_at" class="form-control" placeholder="Updated At" type="text" readonly>
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
                            <!-- update application status approve/decline/review/processing -->
                            <div class="modal fade" data-bs-backdrop="static" id="remarks_form">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title text-info" id="modal-title"></h3>
                                            <div class="close">
                                                <btn type="button" class="btn btn-md btn-danger" onclick="exportApplicationActionForm('approve')" id="export">
                                                    <i class="fas fa-file-pdf text-light"></i>
                                                </btn>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                        </div>
                                        <div class="modal-body form">
                                            <form id="statusForm" class="form-horizontal form-validate-summernote" autocomplete="off">
                                                <?= csrf_field() ?>
                                                <p class="mb-4 text-muted fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                                                <input type="hidden" readonly name="remark_id" />
                                                <input type="hidden" readonly name="application_id" />
                                                <input type="hidden" readonly name="application_code" />
                                                <input type="hidden" readonly name="client_id" />
                                                <input type="hidden" readonly id="loan_interest_type" />
                                                <input type="hidden" readonly id="loan_principal_amount">
                                                <input type="hidden" readonly id="loan_interest_rate" />
                                                <input type="hidden" readonly id="loan_frequency_type" />
                                                <input type="hidden" readonly id="installments_no" />
                                                <input type="hidden" readonly id="loan_repayment_period" />
                                                <input type="hidden" readonly id="loan_installment">
                                                <input type="hidden" readonly id="mode">
                                                <div class="form-body">
                                                    <!-- status row -->
                                                    <div class="row gx-3 gy-2 align-items-center mt-0" id="statusRow">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Level</label>
                                                                <div class="col-md-12">
                                                                    <select name="level" id="level" class="form-control select2bs4">
                                                                    </select>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Action</label>
                                                                <div class="col-md-12">
                                                                    <select name="action" id="action" class="form-control select2bs4">
                                                                    </select>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- disburse loan calculations -->
                                                    <div class="row gx-3 gy-2 align-items-center mt-0" id="approvalCals" style="display: none;">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-4" id="totalPrincipal">
                                                                <div class="form-group">
                                                                    <lable for="principal" class="control-label fw-bold">Total Principal</lable>
                                                                    <input type="text" name="principal" id="principal" class="form-control" placeholder="Total Principal" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="totalCharges">
                                                                <div class="form-group">
                                                                    <lable for="total_charges" class="control-label fw-bold">Total Charges</lable>
                                                                    <input type="text" name="total_charges" id="total_charges" class="form-control" placeholder="Total Charges" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="principalRecievable" style="display: none;">
                                                                <div class="form-group">
                                                                    <lable for="principal_receivable" class="control-label fw-bold">Principal Recievable</lable>
                                                                    <input type="text" name="principal_receivable" id="principal_receivable" class="form-control" placeholder="Principal Recievable" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" id="reductCharges" style="display: none;">
                                                                <div class="form-group">
                                                                    <lable for="particular_id" class="control-label fw-bold">Reduct Charges</lable>
                                                                    <select name="reduct_particular" id="particular_id" class="form-control select2bs4 particular_id">
                                                                    </select>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <lable for="installments_num" class="control-label fw-bold">Installments N<u>o</u></lable>
                                                                    <input type="text" name="installments_num" id="installments_num" class="form-control" placeholder="Total Installments" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <lable for="computed_installment" class="control-label fw-bold">Computed Installment [<?= $settings['currency']; ?>]</lable>
                                                                    <input type="text" name="computed_installment" id="computed_installment" class="form-control" placeholder="Computed Installment" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <lable for="actual_installment" class="control-label fw-bold">Actual Installment [<?= $settings['currency']; ?>]</lable>
                                                                    <input type="text" name="actual_installment" id="actual_installment" class="form-control" placeholder="Actual Installment" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <lable for="computed_interest" class="control-label fw-bold">Computed Interest [<?= $settings['currency']; ?>]</lable>
                                                                    <input type="text" name="computed_interest" id="computed_interest" class="form-control" placeholder="Interest" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <lable for="actual_interest" class="control-label fw-bold">Actual Interest [<?= $settings['currency']; ?>]</lable>
                                                                    <input type="text" name="actual_interest" id="actual_interest" class="form-control" placeholder="Total Interest" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <lable for="computed_repayment" class="control-label fw-bold">Computed Repayment [<?= $settings['currency']; ?>]</lable>
                                                                    <input type="text" name="computed_repayment" id="computed_repayment" class="form-control" placeholder="Computed Repayment" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <lable for="principal_installment" class="control-label fw-bold">Principal Installment [<?= $settings['currency']; ?>]</lable>
                                                                    <input type="text" name="principal_installment" id="principal_installment" class="form-control" placeholder="Principal Installment" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <lable for="interest_installment" class="control-label fw-bold">Interest Installment [<?= $settings['currency']; ?>]</lable>
                                                                    <input type="text" name="interest_installment" id="interest_installment" class="form-control" placeholder="Interest Installment" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <lable for="actual_repayment" class="control-label fw-bold">Actual Repayment [<?= $settings['currency']; ?>]</lable>
                                                                    <input type="text" name="actual_repayment" id="actual_repayment" class="form-control" placeholder="Actual Repayment" readonly>
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row gx-3 gy-2 align-items-center mt-0" id="disbursementRow" style="display: none;">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label fw-bold col-md-12">Particular</label>
                                                                    <div class="col-md-12">
                                                                        <select name="particular_id" id="disparticular_id" class="form-control select2bs4" style="width: 100%;">
                                                                        </select>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label fw-bold col-md-12">Payment Method</label>
                                                                    <div class="col-md-12">
                                                                        <select name="payment_id" id="payment_id" class="form-control select2bs4" style="width: 100%;">
                                                                        </select>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label fw-bold col-md-12">Disbursement By</label>
                                                                    <div class="col-md-12">
                                                                        <select name="disbursed_by" id="disbursed_by" class="form-control select2bs4" style="width: 100%;">
                                                                            <!-- <option value="">-- select --</option> -->
                                                                            <option value="Deposited into Client Account">Deposited into Client Account</option>
                                                                            <option value="Paid in Cash">Paid in Cash</option>
                                                                        </select>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label fw-bold col-md-12">Date Disbursed</label>
                                                                    <div class="col-md-12">
                                                                        <div class="input-group">
                                                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                            <input type="text" name="date_disbursed" id="date_disbursed" class="form-control getDatePicker" value="<?= date('Y-m-d') ?>" placeholder="Date Disbursed">
                                                                        </div>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="pl-3 mt-2 text-info">
                                                            <input type="checkbox" name="checkbox" id="showPlan" onclick="showRepaymentPlan()">
                                                            View Repayment Plan
                                                        </div>
                                                        <div class="row gx-3 gy-2 align-items-center mt-0" id="showRepaymentPlan" style="display: none;">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-hover text-nowrap" style="width:100%">
                                                                        <thead class="">
                                                                            <tr>
                                                                                <th>S.No</th>
                                                                                <th>Due Date</th>
                                                                                <th>Outstanding [<?= $settings['currency']; ?>]</th>
                                                                                <th>Principal [<?= $settings['currency']; ?>]</th>
                                                                                <th>Interest [<?= $settings['currency']; ?>]</th>
                                                                                <th>Installment [<?= $settings['currency']; ?>]</th>
                                                                                <th>Running Bal [<?= $settings['currency']; ?>]</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="repaymentPlan">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- remarks -->
                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold">Remarks</label>
                                                                <div class="col-md-12">
                                                                    <textarea name="loan_remarks" id="newSummernote" class="form-control" placeholder="Remarks"></textarea>
                                                                    <!-- <textarea name="loan_remarks" class="summernote" required="required" data-msg="Please write application remarks"></textarea> -->
                                                                    <span class="help-block text-danger"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" readonly name="staff_id" placeholder="officer" class="form-control text-primary" value="<?= session()->get('id'); ?>">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-bs-target="#loan-agreement" data-bs-toggle="modal" id="btnAgreement" style="display: none;">View Agreement</button>
                                            <button type="button" id="btnApprove" onclick="save_applicationRemarks()" class="btn btn-outline-success">Submit</button>
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- loan agreement modal -->
                            <div class="modal fade" data-bs-backdrop="static" id="loan-agreement" aria-labelledby="loanAgreementToggleLabel" tabindex="-1" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title" id="loanAgreementToggleLabel">Loan Agreement
                                            </h6>
                                            <div class="close">
                                                <a href="/admin/loan/agreement/application/<?= $application['id'] ?>" target="_blank" class="btn btn-icon btn-info ms-2" title="Export Loan Agreement">
                                                    <i class="fas fa-file-pdf text-light"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="terms-conditions" id="terms-scroll">
                                                <h6 class="fw-bold text- text-center">Loan Agreement</h6>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-8 text-start">
                                                        <p class="fw-bold text-default terms-heading"><u>OUR REF:</u></p>
                                                        <div class="d-inline-block">
                                                            <img src="<?= isset($settings) && file_exists('uploads/logo/' . $settings['business_logo']) && $settings['business_logo'] ? '/uploads/logo/' . $settings['business_logo'] : '/assets/dist/img/default.jpg'; ?>" class="brand-image" style="width: 100px; height: 100px; opacity: 0.8;" alt="Logo" />
                                                        </div>
                                                        <div class="d-inline-block align-middle px-3 py-3">
                                                            <h6 class="fw-bold text-default"><?= $settings['business_name'] . '(' . $settings['business_abbr'] . ')'; ?></h6>
                                                            <p class="fw-semibold mb-2 fs-12">
                                                                <?= $settings['business_address']; ?>
                                                            </p>
                                                            <p class="fw-semibold mb-2 fs-12">
                                                                <?= $settings['business_pobox']; ?>
                                                            </p>
                                                            <p class="fw-semibold mb-2 fs-12">
                                                                <?= $settings['business_contact'] . ' || ' . $settings['business_alt_contact']; ?>
                                                            </p>
                                                            <p class="fw-semibold mb-2 fs-12">
                                                                <?= $settings['business_email']; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-start">
                                                        <p class="fw-bold text-default terms-heading"><u>YOUR REF:</u></p>
                                                        <div class="mb-4">
                                                            <p class="fw-semibold mb-2 fs-16">
                                                                <?= (strtolower($application['gender']) == 'male') ? 'Mr. ' : 'Mrs. '; ?><?= $application['name']; ?>
                                                            </p>
                                                            <p class="mb-2 fs-14">
                                                                <i class="fas fa-home"></i>&nbsp;<?= $application['residence']; ?>
                                                            </p>
                                                            <p class="mb-2 fs-14">
                                                                <i class="fas fa-phone"></i>&nbsp;<?= $application['mobile'] . ' || ' . $application['alternate_no']; ?>
                                                            </p>
                                                            <p class="mb-2 fs-14">
                                                                <i class="fas fa-envelope"></i>&nbsp;<?= $application['email']; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <h6 class="fw-bold pb-2 text-default">
                                                    <span class="terms-heading">
                                                        <u>RE: <?= strtoupper($application['product_name'])  . ' FACILITY FOR ' . $settings['currency'] . ' ' . number_format($application['principal']); ?><span class="float-end" id="disbursement-date"></span></u>
                                                    </span>
                                                </h6>

                                                <p class="mb-0">
                                                    <b><?= $settings['business_name']; ?></b> here refered to as <b><?= $settings['business_abbr']; ?></b> is pleased to inform you of its willingness to give this <b>loan facility (“The Facility”)</b> to <b><?= $application['name']; ?> (“The Borrower”)</b> outlined below on the terms and conditions set out in this letter of offer.
                                                </p>

                                                <div class="mb-4">
                                                    <p class="fw-bold text-muted mb-2 fs-14">
                                                        Below are the terms and conditions subject to the loan granted to you.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>1. Loan amount:</b> <?= $settings['currency'] . ' <b>' . number_format($application['principal']) . '</b>'; ?>= (<?= convert_number($application['principal']); ?>).
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>2. Purpose:</b> <?= $application['purpose']; ?>.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>3. Term/Period:</b> <?= $application['repayment_period'] . '(' . convert_number($application['repayment_period']) . ')'; ?> <?= $application['repayment_duration']; ?> from the date of disbursement.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>4. Frequency of payment:</b> <?= $application['repayment_freq']; ?>.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>5. Total number of loan installments:</b> <span id="installments"></span>.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>6. Interest Rate:</b> <?= $application['interest_rate']; ?>% per Month calculated on a <?= $application['interest_type']; ?> basis.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>7. Total amount to repay (principal + interest):</b> <?= $settings['currency']; ?> <span class="total-repayment"></span>.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>8. Total Loan charges:</b> <?= $settings['currency'] . ' ' . $application['total_charges'] . '= (' . convert_number($application['total_charges']) . ')'; ?> including the following;
                                                    <ul class="list-group list-group-horizontal">
                                                        <?php
                                                        if (count($charges['particulars']) > 0) :
                                                            foreach ($charges['particulars'] as $charge) :
                                                        ?>
                                                                <li class="list-group-item">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="ms-2">
                                                                            <b><?= $charge['particular_name'] ?></b>, charge
                                                                            <i>
                                                                                <?= $charge['charge']; ?>
                                                                                <?= (strtolower($charge['charge_method']) == 'amount') ? $settings['currency'] : '% of the principal'; ?>
                                                                            </i>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                        <?php endforeach;
                                                        endif;
                                                        ?>
                                                    </ul>
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>9. Actual Amount received less charges (1–8)</b> is <?= $settings['currency'] . ' ' . number_format(($application['principal'] - $application['total_charges'])) . '= (' . convert_number(($application['principal'] - $application['total_charges'])) . ')'; ?>.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>10. Proposed loan disbursement date:</b> <span id="day-disbursed"></span> of <span id="month-disbursed"></span> <span id="year-disbursed"></span>.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>11.</b> Installments will be due as per the term stated above. And will be repaid in equal installments of <?= $settings['currency'] ?> <b><span id="installment"></span></b> per installment payable <b>starting <?= $grace_period; ?> days from the date of disbursement</b> until the loan is repaid in full.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>12.</b> The Borrower agrees to make deposits of <?= $settings['currency'] ?>: <b><span class="total-repayment"></span></b> into <?= $settings['business_abbr'] ?> account during the entire term of the loan and as long as you are a borrower.
                                                    </p>
                                                </div>

                                                <div class="mb-4">
                                                    <p class="fw-bold text-muted mb-2 fs-14">
                                                        13. Security
                                                    </p>
                                                    <p class="mb-2">
                                                        The Facility will be secured by the pledged chattels/collateral of <span class="fw-semibold"><?= $application['security_item'] ?></span> with estimated values of <span class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($application['est_value']) . '(' . convert_number($application['est_value']) . ')' ?> </span>
                                                    </p>
                                                    <p class="mb-2">
                                                        <span class="fw-semibold">Description: </span> <?= strip_tags($application['security_info']) ?>
                                                    </p>

                                                    <p class="mb-2 fw-semibold">Personal Guarantor(s):</p>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p class="mb-2">
                                                                1. <?= $application['ref_name'] . ' (' . $application['ref_relation'] . ')'; ?>, <?= $application['ref_job']; ?>
                                                            </p>
                                                            <p class="mb-2">
                                                                <i class="fas fa-phone"></i>&nbsp;<?= $application['ref_contact'] . ' || ' . $application['ref_alt_contact']; ?>
                                                            </p>
                                                            <p class="mb-2">
                                                                <i class="fas fa-envelope"></i>&nbsp;<?= $application['ref_email']; ?>
                                                            </p>
                                                            <p class="mb-2">
                                                                <i class="fas fa-home"></i>&nbsp;<?= $application['ref_address']; ?>
                                                            </p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-2">
                                                                2. <?= $application['ref_name2'] . ' (' . $application['ref_relation2'] . ')'; ?>, <?= $application['ref_job2']; ?>
                                                            </p>
                                                            <p class="mb-2">
                                                                <i class="fas fa-phone"></i>&nbsp;<?= $application['ref_contact2'] . ' || ' . $application['ref_alt_contact2']; ?>
                                                            </p>
                                                            <p class="mb-2">
                                                                <i class="fas fa-envelope"></i>&nbsp;<?= $application['ref_email2']; ?>
                                                            </p>
                                                            <p class="mb-2">
                                                                <i class="fas fa-home"></i>&nbsp;<?= $application['ref_address2']; ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <p class="fw-bold mb-0">
                                                        <u>Disbursement:</u> <br>
                                                        The full amount shall be <span class="fst-italic disbursement-mode"></span> and the loan charges shall be debited from <span class="fst-italic charges-mode"><?= $application['reduct_charges'] ?></span> and the balance credited on your loan account held with <?= $settings['business_name'] ?>
                                                    </p>
                                                    <p class="fw-bold mb-0">
                                                        <u>Note:</u><br>
                                                        This agreement takes precedence over the terms of any security whether entered into before or after the execution of this agreement whether such security restricts the Bank’s rights under this agreement or not.
                                                    </p>
                                                </div>

                                                <div class="mb-4">
                                                    <p class="fw-bold text-muted mb-2 fs-14">
                                                        COVENANTS
                                                    </p>

                                                    <p class="mb-2">
                                                        The Borrower covenants with <?= $settings['business_abbr']; ?> that:
                                                    </p>
                                                    <p class="mb-2">
                                                        1. If the borrower shall default in payment of any one or more of the monthly repayments, the whole sum outstanding together with interest thereof shall become immediately due and payable on demand.
                                                    </p>
                                                    <p class="mb-2">
                                                        2. All recovery costs of any installment due on demand shall be met by The Borrower provided it does not exceed 10,000= per every visit to either The Borrower home/business location.
                                                    </p>
                                                </div>

                                                <div class="mb-4">
                                                    <p class="fw-bold text-muted mb-2 fs-14">
                                                        GENERAL TERMS AND CONDITIONS
                                                    </p>

                                                    <p class="mb-2">
                                                        <b>1. OPERATIONAL PRICING.</b>
                                                        All transactions arising from the operation of the above facility or the provision by the <?= $settings['business_abbr'] ?> of other services will be subject to the schedule of fees as set by <?= $settings['business_abbr'] ?> from time to time unless otherwise agreed with <?= $settings['business_abbr'] ?>
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>2. APPLICATION OF MONEY.</b>
                                                        Should there be any delays more than one week from date of payment, <?= $settings['business_abbr'] ?> shall automatically recover from your cash cover to clear any outstanding interest, penalties and any loan balance owing to the you without notifying you.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>3. COST EXPENSES AND FEES.</b>
                                                        The borrower agrees that all costs and expenses whatsoever including legal and auctioneers costs connected with the recovery or attempted recovery of moneys shall be borne by the customer/ borrower.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>4. PAYMENT ON DEMAND.</b>
                                                        Should you default in payment of the facility in full or part thereof, <?= $settings['business_abbr'] ?> may be forced to recall the whole loan by written notice to that effect, payable either upon demand or within a period stated in the notice.
                                                    </p>
                                                    <p class="mb-2">
                                                        <b>5. EXPIRY OF FACILITY.</b>
                                                        This loan agreement remains in force and effect till the loan is completed.
                                                        Kindly signify your acceptance of the loan terms and conditions contained in this agreement by appending your signature in the space specified below.
                                                    </p>
                                                </div>

                                                <div class="mb-0">
                                                    <p class="mb-2">
                                                        I have read, understood and do hereby accept the loan Arrangements stated herein and upon the terms and conditions subject to the covenants set out in this loan facility.
                                                    </p>
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <p class="fw-semibold text-muted mb-2 fs-14">Client</p>
                                                            <p class="mb-0">
                                                                NAME: <br>
                                                                <span class="fw-bold pl-2">
                                                                    <?= ($application['name'] ? $application['name'] : '_______________________________________') ?>
                                                                </span>
                                                                <br>
                                                                SIGNATURE: <br>
                                                                <?php if ($application['sign']) : ?>
                                                                    <img src="/uploads/clients/signatures/<?= $application['sign']; ?>" alt="Signature" class="img-fluid thumbnail" style="width: 140px; height: 140px;" />
                                                                <?php else : ?>
                                                                    _______________________________________
                                                                <?php endif; ?>
                                                                <br>
                                                                DATE: <br>
                                                                _______________________________________
                                                            </p>
                                                        </div>
                                                        <div class="col-4">
                                                            <p class="fw-semibold text-muted mb-2 fs-14">Loan Officer</p>
                                                            <p class="mb-0">
                                                                NAME: <br>
                                                                <span class="fw-bold pl-2">
                                                                    <?= ($application['staff_name'] ? $application['staff_name'] : '___________________________________') ?>
                                                                </span>
                                                                <br>
                                                                SIGNATURE: <br>
                                                                <?php if ($application['sign'] && file_exists('uploads/staffs/employees/signatures/' . $application['signature'])) : ?>
                                                                    <img src="/uploads/staffs/employees/signatures/<?= $application['signature']; ?>" alt="Signature" class="img-fluid thumbnail" style="width: 140px; height: 140px;" />
                                                                <?php else : ?>
                                                                    _______________________________________
                                                                <?php endif; ?>
                                                                <br>
                                                                DATE: <br> _______________________________________
                                                            </p>
                                                        </div>
                                                        <div class="col-4">
                                                            <p class="fw-semibold text-muted mb-2 fs-14">General Manager</p>
                                                            <p class="mb-0">
                                                                NAME: <br> _______________________________________
                                                                <br>
                                                                SIGNATURE: <br> _______________________________________
                                                                <br>
                                                                DATE: <br> _______________________________________
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-bs-target="#remarks_form" data-bs-toggle="modal">Back</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?= $this->endSection(); ?>

                            <?= $this->section("scripts") ?>
                            <script type="text/javascript">
                                var id = '<?= isset($application) ? $application['id'] : 0; ?>';
                                var clientId = <?= $application['client_id'] ?>;
                                var appStatus = '<?= isset($application) ? strtolower($application['status']) : 'pending'; ?>';
                                var principal = Number(<?= $application['principal'] ?>);
                                var level = '<?= $application['level'] ?>';
                                var overallCharges = '<?= count(unserialize($application['overall_charges'])) ?>';
                            </script>

                            <script src="/assets/client/main/auto.js"></script>
                            <script src="/assets/client/loans/applications.js"></script>
                            <script src="/assets/scripts/loans/applications/view.js"></script>
                            <!-- dataTables -->
                            <script src="/assets/scripts/main/datatables.js"></script>
                            <script src="/assets/client/loans/loans.js"></script>
                            <script src="/assets/client/main/others.js"></script>
                            <script src="/assets/client/main/options.js"></script>
                            <script src="/assets/scripts/main/images-js.js"></script>

                            <?= $this->endSection() ?>