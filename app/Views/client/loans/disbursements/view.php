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
?>

<!-- Start::row-1 -->
<div class="row gx-3 gy-2 align-items-center mt-0">
    <div class="col-xl-12">
        <div class="card border border-warning custom-card">
            <div class="card-header">
                <div class="card-title">
                    <div class="d-sm-flex d-block align-items-center justify-content-between">
                        <a href="javascript: void(0)" rel="noopener" class="btn btn-icon float-end btn-secondary-light ms-2" onclick="exportApplicationForm()" title="Print Form">
                            <i class="fas fa-print"></i>
                        </a>
                        <div class="h5 fw-semibold mb-0"> Disbursement Details</div>
                        <div class="d-flex mt-sm-0 mt-2 align-items-center">

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body  p-3 product-checkout">
                <ul class="nav nav-tabs tab-style-2 d-sm-flex d-block border-bottom border-block-end-dashed" id="myTab1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="client-tab" data-bs-toggle="tab" data-bs-target="#client-tab-pane" type="button" role="tab" aria-controls="client-tab" aria-selected="true"><i class="fas fa-user-tag me-2 align-middle"></i>Client Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="loan-tab" data-bs-toggle="tab" data-bs-target="#loan-tab-pane" type="button" role="tab" aria-controls="confirmed-tab" aria-selected="false"><i class="fas fa-clipboard me-2 align-middle"></i>Loan Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="amounts-tab" data-bs-toggle="tab" data-bs-target="#amounts-tab-pane" type="button" role="tab" aria-controls="amounts-tab" aria-selected="false"><i class="fas fa-money-bill-wave me-2 align-middle"></i>Loan Amounts</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="repayments-tab" data-bs-toggle="tab" data-bs-target="#repayments-tab-pane" type="button" role="tab" aria-controls="repayments-tab" aria-selected="false"><i class="ri-refund-2-line me-2 align-middle"></i>Repayments</button>
                    </li>
                    <?php if ((unserialize($user['permissions']) == 'all') || (in_array('export' . ucwords(str_replace(' ', '', $title)), unserialize($user['permissions'])))) : ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-danger" onclick="exportDisbursementForm()" title="Export Disbursement Data">
                                <i class="fas fa-print"></i>
                            </button>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- client details -->
                    <div class="tab-pane fade show active " id="client-tab-pane" role="tabpanel" aria-labelledby="client-tab-pane" tabindex="0">
                        <form class="form-horizontal" autocomplete="off">
                            <input type="hidden" readonly value="" name="id" />
                            <div class="form-body">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-8">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Full Name</label>
                                                    <div class="col-md-12">
                                                        <input name="name" placeholder="Full Name" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Gender</label>
                                                    <div class="col-md-12">
                                                        <input name="gender" placeholder="Gender" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Religion</label>
                                                    <div class="col-md-12">
                                                        <input name="religion" placeholder="Religion" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Marital Status</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="marital_status" placeholder="Marital Status" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Nationality</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="nationality" placeholder="Nationality" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label class="control-label fw-bold  col-md-12">Profile Photo</label>
                                        <div class="form-group" id="photo-preview">
                                            <div class="col-md-12">
                                                (No photo)
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">Phone Number 1</label>
                                            <div class="col-md-12">
                                                <input name="mobile" placeholder="Phone Number" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">Phone Number 2</label>
                                            <div class="col-md-12">
                                                <input name="alt_mobile" placeholder="Phone Number 2" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">Email</label>
                                            <div class="col-md-12">
                                                <input name="email" placeholder="example@mail.com" class="form-control" type="email" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">D.O.B</label>
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
                                            <label class="control-label fw-bold  col-md-12">Occupation</label>
                                            <div class="col-md-12">
                                                <input name="occupation" placeholder="Occupation" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">Business Location</label>
                                            <div class="col-md-12">
                                                <input name="job_location" placeholder="Bussiness Location" class="form-control" type="text" readonly>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">Applicant Address</label>
                                            <div class="col-md-12">
                                                <textarea name="residence" placeholder="Applicant Address" class="form-control" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <label class="control-label fw-bold  col-md-12">ID Photo</label>
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
                                                    <label class="control-label fw-bold  col-md-12">Id Type</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="id_type" placeholder="ID Type" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Id Number</label>
                                                    <div class="col-md-12">
                                                        <input name="id_number" placeholder="Id Number" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Id Expiry Date</label>
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
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">Next of Kin Name</label>
                                            <div class="col-md-12">
                                                <input name="next_of_kin" placeholder="Next Of Kin Name" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">Next of Kin Relationship</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="nok_relationship" placeholder="Next of Kin Relationship" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">Next of Kin Address</label>
                                            <div class="col-md-12">
                                                <input name="nok_address" placeholder="Next of Kin Address" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">Next of Kin Phone 1</label>
                                            <div class="col-md-12">
                                                <input name="nok_phone" placeholder="Next of Kin Phone 1" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">Next of Kin Phone 2</label>
                                            <div class="col-md-12">
                                                <input name="nok_alt_phone" placeholder="Next of Kin Phone 2" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label fw-bold  col-md-12">Next of Kin Email</label>
                                            <div class="col-md-12">
                                                <input name="nok_email" placeholder="Next of Kin Email" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-8">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Applicant Branch</label>
                                                    <div class="col-md-12">
                                                        <input name="branch_id" placeholder="Applicant Branch" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Account Number</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="account_no" placeholder="Account Number" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Account Type</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="account_type" placeholder="Account Type" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label class="control-label fw-bold  col-md-12">Signature</label>
                                        <div class="form-group" id="signature-preview">
                                            <div class="col-md-12">
                                                (No Sign)
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- loan details -->
                    <div class="tab-pane fade " id="loan-tab-pane" role="tabpanel" aria-labelledby="loan-tab-pane" tabindex="0">
                        <div class="card border border-warning custom-card">
                            <div class="card-header">
                                <div class="card-title">
                                    Loan Details
                                </div>
                            </div>
                            <div class="card-body">
                                <nav>
                                    <div class="nav nav-tabs nav-justified tab-style-4 d-sm-flex d-block" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-loan-tab" data-bs-toggle="tab" data-bs-target="#nav-loan1" type="button" role="tab" aria-selected="true">Loan Terms</button>
                                        <button class="nav-link" id="nav-security-tab" data-bs-toggle="tab" data-bs-target="#nav-security1" type="button" role="tab" aria-selected="false">Loan Security</button>
                                        <button class="nav-link" id="nav-referees-tab" data-bs-toggle="tab" data-bs-target="#nav-referees1" type="button" role="tab" aria-selected="false">Client Referees</button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="tab-style-4">
                                    <!-- loan details -->
                                    <div class="tab-pane show active" id="nav-loan1" role="tabpanel" aria-labelledby="nav-loan-tab" tabindex="0">
                                        <form action="" class="form-horizontal" autocomplete="off">
                                            <input type="hidden" readonly name="id" value="">
                                            <div class="form-body">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold " for="product_id">Disbursement Code</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="disbursement_code" class="form-control" placeholder="Disbursement Code" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold " for="principal">Principal[<?= $settings['currency']; ?>]</label>
                                                            <div class="col-md-12">
                                                                <input name="principal" id="principal" placeholder="Principal[<?= $settings['currency']; ?>]" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold " for="actual_interest">Total Interest</label>
                                                            <div class="col-md-12">
                                                                <input name="actual_interest" id="actual_interest" placeholder="Total Interest" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold " for="product_name">Loan Product</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="product_name" class="form-control" placeholder="Loan Product" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold ">Interest Rate</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="interest_rate" class="form-control" placeholder="Interest Rate" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold ">Interest Method</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="interest_type" class="form-control" placeholder="Interest Method" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold ">Repayment Mode</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="repayment_freq" class="form-control" placeholder="Repayment Mode" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold ">Repayment Period</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="repayment_period" class="form-control" placeholder="Repayment Period" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold ">Cycle</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="cycle" class="form-control" placeholder="Cycle" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold ">Status</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="status" class="form-control" placeholder="Disbursment Status" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold ">Class</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="class" class="form-control" placeholder="Disbursment Class" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold  col-md-12">Comments</label>
                                                            <div class="col-md-12">
                                                                <input name="comments" placeholder="Comments" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold  col-md-12">Application Code</label>
                                                            <div class="col-md-12">
                                                                <input name="application_code" placeholder="Application Code" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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
                                                            <label class="control-label fw-bold " for="security_item">Security Item</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="security_item" id="security_item" class="form-control" placeholder="Security Item" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold " for="est_value">Estimated Value[<?= $settings['currency']; ?>]</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="est_value" id="est_value" class="form-control" placeholder="Estimated Value[<?= $settings['currency']; ?>]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold ">Details</label>
                                                            <div class="col-md-12">
                                                                <textarea name="vsecurity_info" id="seeSummernote" class="form-control" placeholder="Details"></textarea readonly>
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
                                                <h5 class="card-title text-bold">Referee/ Guarantors</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_name">Full Name</label>
                                                            <input type="text" name="ref_name" id="ref_name" class="form-control" placeholder="Full Name" readonly>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_relation">Relationship</label>
                                                            <input type="text" name="ref_relation" id="ref_relation" class="form-control" placeholder="Relationship" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_job">Occupation</label>
                                                            <input type="text" name="ref_job" id="ref_job" class="form-control" placeholder="Occupation" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_contact">Contact 1</label>
                                                            <input type="tel" name="ref_contact" id="ref_contact" class="form-control" placeholder="Contact 1" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_alt_contact">Contact 2</label>
                                                            <input type="tel" name="ref_alt_contact" id="ref_alt_contact" class="form-control" placeholder="Contact 2" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_email">Email</label>
                                                            <input type="email" name="ref_email" id="ref_email" class="form-control" placeholder="Email" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_address2">Address</label>
                                                            <textarea name="ref_address" id="ref_address2" class="form-control" placeholder="Address"></textarea readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_name2">Full Name</label>
                                                            <input type="text" name="ref_name2" id="ref_name2" class="form-control" placeholder="Full Name" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="relation2">Relationship</label>
                                                            <input type="text" name="ref_relation2" id="ref_relation" class="form-control" placeholder="Relationship" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_job2">Occupation</label>
                                                            <input type="text" name="ref_job2" id="ref_job2" class="form-control" placeholder="Occupation" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_contact2">Contact 1</label>
                                                            <input type="tel" name="ref_contact2" id="ref_contact2" class="form-control" placeholder="Contact 1" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_alt_contact2">Contact 2</label>
                                                            <input type="tel" name="ref_alt_contact2" id="ref_alt_contact2" class="form-control" placeholder="Contact 2" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_email2">Email</label>
                                                            <input type="email" name="ref_email2" id="ref_email2" class="form-control" placeholder="Email" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold" for="ref_address2">Address</label>
                                                            <textarea name="ref_address2" id="ref_address2" class="form-control" placeholder="Address"></textarea readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold" for="ref_alt_contact2">Officer</label>
                                    <input name="vofficer" placeholder="officer" class="form-control" type="text" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold" for="ref_alt_contact2">Created At</label>
                                    <div class="input-group">
                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                        <input name="created_at" placeholder="Created At" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label fw-bold" for="ref_alt_contact2">Updated At</label>
                                    <div class="input-group">
                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                        <input name="updated_at" placeholder="Updated At" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <button class="btn btn-primary" data-bs-target="#loan-agreement" data-bs-toggle="modal" id="btnAgreement">
                                    View Agreement
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- amounts details -->
                    <div class="tab-pane fade " id="amounts-tab-pane" role="tabpanel" aria-labelledby="amounts-tab-pane" tabindex="0">
                        <form action="" class="form-horizontal" autocomplete="off">
                            <input type="hidden" readonly name="id" value="">
                            <div class="form-body">
                                <div class="card border border-warning custom-card">
                                    <div class="card-header">
                                        <h5 class="card-title">Installments & Interest</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold " for="principal">Principal[<?= $settings['currency']; ?>]</label>
                                                    <div class="col-md-12">
                                                        <input name="principal" id="principal" placeholder="Principal[<?= $settings['currency']; ?>]" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Actual Interest</label>
                                                    <div class="col-md-12">
                                                        <input class="form-control" placeholder="Actual Interest" name="actual_interest" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Total Repayment</label>
                                                    <div class="col-md-12">
                                                        <input name="computed_repayment" placeholder="Total Repayment" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Installments N<u>o</u></label>
                                                    <div class="col-md-12">
                                                        <input name="installments_num" placeholder="Installments No" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Installments Covered</label>
                                                    <div class="col-md-12">
                                                        <input name="installments_covered" placeholder="Installments Covered" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Installments Left</label>
                                                    <div class="col-md-12">
                                                        <input name="installments_left" placeholder="Installments Left" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Computed Interest</label>
                                                    <div class="col-md-12">
                                                        <input name="computed_interest" placeholder="Computed Interest" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Computed Installment</label>
                                                    <div class="col-md-12">
                                                        <input name="computed_installment" placeholder="Computed Installment" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Computed Repayment</label>
                                                    <div class="col-md-12">
                                                        <input name="computed_repayment" placeholder="Computed Repayment" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Principal Installment</label>
                                                    <div class="col-md-12">
                                                        <input name="principal_installment" placeholder="Principal Installment" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Interest Installment</label>
                                                    <div class="col-md-12">
                                                        <input class="form-control" placeholder="Interest Installment" name="interest_installment" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Actual Installment</label>
                                                    <div class="col-md-12">
                                                        <input name="actual_installment" placeholder="Actual Installment" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- expectations -->
                                <div class="card border border-warning custom-card">
                                    <div class="card-header">
                                        <h5 class="card-title">Expectations</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">First Recovery Date</label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                            <input class="form-control" placeholder="First Recovery Date" name="first_recovery" type="text" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Expected Repayment</label>
                                                    <div class="col-md-12">
                                                        <input name="actual_repayment" placeholder="Expected Repayment" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Expected Loan Balance</label>
                                                    <div class="col-md-12">
                                                        <input name="expected_loan_balance" placeholder="Expected Loan Balance" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Expected Principal Recovered</label>
                                                    <div class="col-md-12">
                                                        <input name="expected_principal_recovered" placeholder="Expected Principal Recovered" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Expected Interest Recovered</label>
                                                    <div class="col-md-12">
                                                        <input class="form-control" placeholder="Expected Interest Recovered" name="expected_interest_recovered" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Expected Recovered Amount</label>
                                                    <div class="col-md-12">
                                                        <input name="expected_amount_recovered" placeholder="Expected Recovered Amount" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- repayed & balances -->
                                <div class="card border border-warning custom-card">
                                    <div class="card-header">
                                        <h5 class="card-title">Repayed & Balances</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- repayed-->
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Interest Repayed</label>
                                                    <div class="col-md-12">
                                                        <input class="form-control" placeholder="Interest Repayed" name="interest_collected" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Principal Repayed</label>
                                                    <div class="col-md-12">
                                                        <input name="principal_collected" placeholder="Principal Repayed" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Total Repayed</label>
                                                    <div class="col-md-12">
                                                        <input name="total_collected" placeholder="Total Repayed" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- balance-->
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Interest Balance</label>
                                                    <div class="col-md-12">
                                                        <input class="form-control" placeholder="Interest Balance" name="interest_balance" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Principal Balance</label>
                                                    <div class="col-md-12">
                                                        <input name="principal_balance" placeholder="Principal Balance" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Total Loan Balance</label>
                                                    <div class="col-md-12">
                                                        <input name="total_balance" placeholder="Total Loan Balance" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- dates -->
                                <div class="card border border-warning custom-card">
                                    <div class="card-header">
                                        <h5 class="card-title">Dates & Arrears</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- arrears & missed -->
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Principal Due</label>
                                                    <div class="col-md-12">
                                                        <input name="principal_due" placeholder="Principal Due" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Interest Due</label>
                                                    <div class="col-md-12">
                                                        <input name="interest_due" placeholder="Interest Due" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Total Arrears</label>
                                                    <div class="col-md-12">
                                                        <input name="arrears" placeholder="Total Arrears" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- missed -->
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Installments due</label>
                                                    <div class="col-md-12">
                                                        <input name="installments_due" placeholder="Installments due" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Days Due</label>
                                                    <div class="col-md-12">
                                                        <input class="form-control" placeholder="Days Due" name="days_due" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Grace Period(days)</label>
                                                    <div class="col-md-12">
                                                        <input name="grace_period" placeholder="Grace Period(days)" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- loan days data -->
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Loan Period(days)</label>
                                                    <div class="col-md-12">
                                                        <input name="loan_period_days" placeholder="Loan Period(days)" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Days Covered</label>
                                                    <div class="col-md-12">
                                                        <input name="days_covered" placeholder="Days Covered" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Days Remaining</label>
                                                    <div class="col-md-12">
                                                        <input class="form-control" placeholder="Days Remaining" name="days_remaining" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- expire data -->
                                        <div class="row gx-3 gy-2 align-items-center mt-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Date Disbursed</label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                            <input class="form-control" placeholder="Disbursement Date" name="created_at" type="text" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Loan Expiry Date</label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                            <input class="form-control" placeholder="Loan Expiry Date" name="loan_expiry_date" type="text" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold  col-md-12">Loan Expiry Day</label>
                                                    <div class="col-md-12">
                                                        <input name="expiry_day" placeholder="Loan Expiry Day" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- repayments details -->
                    <div class="tab-pane fade " id="repayments-tab-pane" role="tabpanel" aria-labelledby="repayments-tab-pane" tabindex="0">
                        <!-- make repayment button -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="float-end">
                                    <button class="nav-link text-info" onclick="add_disbursementPayment()" title="Make Installment Payment">
                                        <i class="fa fa-money-bill-1-wave"></i> Make Repayment
                                    </button><br>
                                </div>
                            </div>
                        </div>
                        <!-- repayment plan -->
                        <div class="card border border-warning custom-card">
                            <div class="card-header">
                                <h5 class="card-title">Repayment Plan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm  table-hover text-nowrap" style="width:100%">
                                                <thead class="">
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Due Date</th>
                                                        <th>O/Standing Amt[<?= $settings['currency']; ?>]</th>
                                                        <th>Principal Installment[<?= $settings['currency']; ?>]</th>
                                                        <th>Interest Installment[<?= $settings['currency']; ?>]</th>
                                                        <th>Installment[<?= $settings['currency']; ?>]</th>
                                                        <th>Balance[<?= $settings['currency']; ?>]</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="repaymentPlan">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- history -->
                        <div class="card border border-warning custom-card">
                            <div class="card-header">
                                <h5 class="card-title">Repayment History</h5>
                            </div>
                            <div class="card-body">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="repaymentHistory" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                                <thead class="">
                                                    <tr>
                                                        <th><input type="checkbox" name="" id="check-all"></th>
                                                        <th>S.No</th>
                                                        <th>Type</th>
                                                        <th>Method</th>
                                                        <th>Amount [<?= $settings['currency']; ?>]</th>
                                                        <th>Ref ID</th>
                                                        <th>Officer</th>
                                                        <th>Date</th>
                                                        <th>Balance</th>
                                                        <th width="5%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- ajax data here -->
                                                </tbody>
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
    </div>
</div>

<!-- loan agreement modal -->
<div class="modal fade" data-bs-backdrop="static" id="loan-agreement"
    aria-labelledby="loanAgreementToggleLabel" tabindex="-1" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="loanAgreementToggleLabel">Loan Agreement
                </h6>
                <div class="close">
                    <a href="/admin/loan/agreement/disbursement/<?= $disbursement['id'] ?>" target="_blank" class="btn btn-icon btn-info ms-2" title="Export Loan Agreement">
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
                                    <?= (strtolower($disbursement['gender']) == 'male') ? 'Mr. ' : 'Mrs. '; ?><?= $disbursement['name']; ?>
                                </p>
                                <p class="mb-2 fs-14">
                                    <i class="fas fa-home"></i>&nbsp;<?= $disbursement['residence']; ?>
                                </p>
                                <p class="mb-2 fs-14">
                                    <i class="fas fa-phone"></i>&nbsp;<?= $disbursement['mobile'] . ' | ' . $disbursement['alternate_no']; ?>
                                </p>
                                <p class="mb-2 fs-14">
                                    <i class="fas fa-envelope"></i>&nbsp;<?= $disbursement['email']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <h6 class="fw-bold pb-2 text-default">
                        <span class="terms-heading">
                            <u>RE: <?= strtoupper($disbursement['product_name'])  . ' FACILITY FOR ' . $settings['currency'] . ' ' . number_format($disbursement['principal']); ?>
                                <span class="float-end">
                                    <?= ($disbursement['date_disbursed'] ? date('d M Y', strtotime($disbursement['date_disbursed'])) : '__________________') ?>
                                </span></u>
                        </span>
                    </h6>

                    <p class="mb-0">
                        <span class="fw-semibold"><?= $settings['business_name']; ?></span> here refered to as <span class="fw-semibold"><?= $settings['business_abbr']; ?></span> is pleased to inform you of its willingness to give this <span class="fw-semibold">loan facility (“The Facility”)</span> to <span class="fw-semibold"><?= $disbursement['name']; ?> (“The Borrower”)</span> outlined below on the terms and conditions set out in this letter of offer.
                    </p>

                    <div class="mb-4">
                        <p class="fw-bold mb-2 fs-14">
                            Below are the terms and conditions subject to the loan granted to you.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">1. Loan amount:</span> <?= $settings['currency'] . ' <span class="fw-semibold">' . number_format($disbursement['principal']) . '</span>'; ?>= (<?= convert_number($disbursement['principal']); ?>).
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">2. Purpose:</span> <?= $disbursement['purpose']; ?>.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">3. Term/Period:</span> <?= $disbursement['repayment_period'] . '(' . convert_number($disbursement['repayment_period']) . ')' . ' ' . $disbursement['repayment_duration']; ?> from the date of disbursement.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">4. Frequency of payment:</span> <?= $disbursement['repayment_freq']; ?>.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">5. Total number of loan installments:</span> <?= $disbursement['installments_num'] . '(' . convert_number($disbursement['installments_num']) . ')'; ?> .
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">6. Interest Rate:</span> <?= $disbursement['interest_rate']; ?>% per Month calculated on a <?= $disbursement['interest_type']; ?> basis.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">7. Total amount to repay (principal + interest):</span> <?= $settings['currency'] . ' ' . number_format($disbursement['actual_repayment']) . '(' . convert_number($disbursement['actual_repayment']) . ')'; ?>.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">8. Total Loan charges:</span> <?= $settings['currency'] . ' ' . number_format($disbursement['total_charges']) . '= (' . convert_number($disbursement['total_charges']) . ')'; ?> including the following;
                        <ul class="list-group list-group-horizontal">
                            <?php
                            if (count($charges['particulars']) > 0) :
                                foreach ($charges['particulars'] as $charge) :
                            ?>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <span class="fw-semibold"><?= $charge['particular_name'] ?></span>, charge
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
                            <span class="fw-semibold">9. Actual Amount received less charges (1–8)</span> is <?= $settings['currency'] . ' ' . number_format(($disbursement['principal'] - $disbursement['total_charges'])) . '= (' . convert_number(($disbursement['principal'] - $disbursement['total_charges'])) . ')'; ?>.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">10. Proposed loan disbursement date:</span> <?= ($disbursement['date_disbursed'] ? date('d M Y', strtotime($disbursement['date_disbursed'])) : '__________________') ?>.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">11.</span> Installments will be due as per the term stated above. And will be repaid in equal installments of <span class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($disbursement['actual_installment']); ?></span> per installment payable <span class="fw-semibold">starting <?= $disbursement['grace_period']; ?> days from the date of disbursement</span> until the loan is repaid in full.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">12.</span> The Borrower agrees to make deposits of <span class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($disbursement['actual_repayment']) . '(' . convert_number($disbursement['actual_repayment']) . ')'; ?>:</span> into <?= $settings['business_abbr'] ?> account during the entire term of the loan and as long as you are a borrower.
                        </p>
                    </div>

                    <div class="mb-4">
                        <p class="fw-bold mb-2 fs-14">
                            13. Security
                        </p>
                        <p class="mb-2">
                            The Facility will be secured by the pledged chattels/collateral of <span class="fw-semibold"><?= $disbursement['security_item'] ?></span>  with estimated values of <span class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($disbursement['est_value']) . '(' . convert_number($disbursement['est_value']) . ')' ?> </span>
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">Description: </span> <?= strip_tags($disbursement['security_info']) ?>
                        </p>

                        <p class="mb-2 fw-semibold">Personal Guarantor(s):</p>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-2">
                                    <span class="fw-semibold">1.</span> <?= $disbursement['ref_name'] . ' (' . $disbursement['ref_relation'] . ')'; ?>, <?= $disbursement['ref_job']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-phone"></i>&nbsp;<?= $disbursement['ref_contact'] . ' || ' . $disbursement['ref_alt_contact']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-envelope"></i>&nbsp;<?= $disbursement['ref_email']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-home"></i>&nbsp;<?= $disbursement['ref_address']; ?>
                                </p>
                            </div>
                            <div class="col-6">
                                <p class="mb-2">
                                    <span class="fw-semibold">2.</span> <?= $disbursement['ref_name2'] . ' (' . $disbursement['ref_relation2'] . ')'; ?>, <?= $disbursement['ref_job2']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-phone"></i>&nbsp;<?= $disbursement['ref_contact2'] . ' || ' . $disbursement['ref_alt_contact2']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-envelope"></i>&nbsp;<?= $disbursement['ref_email2']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-home"></i>&nbsp;<?= $disbursement['ref_address2']; ?>
                                </p>
                            </div>
                        </div>

                        <p class="fw-semibold mb-0">
                            <u class="fw-bold">Disbursement:</u> <br>
                            The full amount shall be <span class="fst-italic"><?= (($disbursement['disbursed_by']) ? $disbursement['disbursed_by'] : '____________________________________'); ?></span> and the loan charges shall be debited from <span class="fst-italic"><?= $disbursement['reduct_charges'] ?></span> and the balance credited on your loan account held with <?= $settings['business_name'] ?>
                        </p>
                        <p class="fw-semibold mb-0">
                            <u class="fw-bold">Note:</u><br>
                            This agreement takes precedence over the terms of any security whether entered into before or after the execution of this agreement whether such security restricts the Bank’s rights under this agreement or not.
                        </p>
                    </div>

                    <div class="mb-4">
                        <p class="fw-bold mb-2 fs-14">
                            COVENANTS
                        </p>

                        <p class="fw-semibold mb-2">
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
                        <p class="fw-bold mb-2 fs-14">
                            GENERAL TERMS AND CONDITIONS
                        </p>

                        <p class="mb-2">
                            <span class="fw-semibold">1. OPERATIONAL PRICING.</span>
                            All transactions arising from the operation of the above facility or the provision by the <?= $settings['business_abbr'] ?> of other services will be subject to the schedule of fees as set by <?= $settings['business_abbr'] ?> from time to time unless otherwise agreed with <?= $settings['business_abbr'] ?>
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">2. APPLICATION OF MONEY.</span>
                            Should there be any delays more than one week from date of payment, <?= $settings['business_abbr'] ?> shall automatically recover from your cash cover to clear any outstanding interest, penalties and any loan balance owing to the you without notifying you.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">3. COST EXPENSES AND FEES.</span>
                            The borrower agrees that all costs and expenses whatsoever including legal and auctioneers costs connected with the recovery or attempted recovery of moneys shall be borne by the customer/ borrower.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">4. PAYMENT ON DEMAND.</span>
                            Should you default in payment of the facility in full or part thereof, <?= $settings['business_abbr'] ?> may be forced to recall the whole loan by written notice to that effect, payable either upon demand or within a period stated in the notice.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">5. EXPIRY OF FACILITY.</span>
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
                                <p class="fw-semibold mb-2 fs-14">Client</p>
                                <p class="mb-0">
                                    NAME: <br>
                                    <span class="fw-bold pl-2">
                                        <?= ($disbursement['name'] ? $disbursement['name'] : '_______________________________________') ?>
                                    </span>
                                    <br>
                                    SIGNATURE: <br>
                                    <?php if ($disbursement['sign']) : ?>
                                        <img src="/uploads/clients/signatures/<?= $disbursement['sign']; ?>" alt="Signature" class="img-fluid thumbnail" style="width: 140px; height: 140px;" />
                                    <?php else : ?>
                                        _______________________________________
                                    <?php endif; ?>
                                    <br>
                                    DATE: <br>
                                    <?= ($disbursement['date_disbursed'] ? date('d M Y', strtotime($disbursement['date_disbursed'])) : '___________________________________') ?>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="fw-semibold mb-2 fs-14">Loan Officer</p>
                                <p class="mb-0">
                                    NAME: <br>
                                    <span class="fw-bold pl-2">
                                        <?= ($disbursement['staff_name'] ? $disbursement['staff_name'] : '___________________________________') ?>
                                    </span>
                                    <br>
                                    SIGNATURE: <br>
                                    <?php if ($disbursement['sign'] && file_exists('uploads/staffs/employees/signatures/' . $disbursement['signature'])) : ?>
                                        <img src="/uploads/staffs/employees/signatures/<?= $disbursement['signature']; ?>" alt="Signature" class="img-fluid thumbnail" style="width: 140px; height: 140px;" />
                                    <?php else : ?>
                                        _______________________________________
                                    <?php endif; ?>
                                    <br>
                                    DATE: <br> <?= ($disbursement['date_disbursed'] ? date('d M Y', strtotime($disbursement['date_disbursed'])) : '___________________________________') ?>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="fw-semibold mb-2 fs-14">General Manager</p>
                                <p class="mb-0">
                                    NAME: <br> _______________________________________
                                    <br>
                                    SIGNATURE: <br> _______________________________________
                                    <br>
                                    DATE: <br> <?= ($disbursement['date_disbursed'] ? date('d M Y', strtotime($disbursement['date_disbursed'])) : '___________________________________') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-target="#remarks_form"
                    data-bs-toggle="modal">Back</button>
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
                                        <div class="col-md-12">
                                            (No photo)
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
<div class="modal fade"  data-bs-backdrop="static" id="repayment_modal_form" role="dialog">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form id="repaymentForm" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly name="account_typeId" />
                    <input type="hidden" readonly name="disbursement_id" />
                    <input type="hidden" readonly name="client_id" />
                    <input type="hidden" readonly name="entry_menu" />
                    <div class="form-body">
                        <!-- summary -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-8">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <p class="">Name: <strong id="cName"></strong></p>
                                        <p class="">Contact: <strong id="cContact"></strong></p>
                                        <p class="">Email: <strong id="cEmail"></strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="">Acc Number: <strong id="cAccountNo"></strong></p>
                                        <p class="">Acc Balance: <strong id="cBalance"></strong></p>
                                        <p class="">Address: <strong id="cAddress"></strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="">Total Loan: <strong id="tLoan"></strong></p>
                                        <p class="">Loan Balance: <strong id="lBalance"></strong></p>
                                        <p class="">Installment: <strong id="installment"></strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center" id="cPhoto-preview">
                                    <div class="col-md-12">
                                        (No photo)
                                    </div>
                                </div>
                            </div>
                        </div><hr>
                        <!-- particular -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold  col-md-12">Particular Name</label>
                                    <div class="col-md-12">
                                        <select id="particular_id" name="particular_id" class="form-control select2bs4f">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount" class="control-label fw-bold  col-sm-12">Transaction Type</label>
                                    <div class="col-sm-12">
                                        <select name="entry_typeId" id="entry_typeId" class="form-control select2bs4f">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold  col-md-12">Contact</label>
                                    <div class="col-md-12">
                                        <input name="contact" placeholder="Contact" class="form-control" type="text">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- amount -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold  col-md-12">Installment[<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input type="number" name="amount" class="form-control" placeholder="Installment[<?= $settings['currency']; ?>]" min="0">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold  col-md-12">Payment Method</label>
                                    <div class="col-md-12">
                                        <select name="payment_id" id="payment_id" class="form-control select2bs4f" style="width: 100%;">
                                            
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount" class="control-label fw-bold  col-sm-12">Date</label>
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
                        <!-- details & remarks -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold  col-md-12">Details</label>
                                    <div class="col-md-12">
                                        <textarea name="entry_details" class="form-control" id="addSummernote"></textarea>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold  col-md-12">Remarks</label>
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
                                            <button type="button" id="btnPay" onclick="save_payments()" class="btn btn-outline-success">Submit</button>
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- view payment details -->
                            <div class="modal fade" data-bs-backdrop="static" id="view_pay_modal" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title"></h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body form">
                                            <form id="disbursement_form" class="form-horizontal" autocomplete="off">
                                                <input type="hidden" readonly name="disbursement_id" value="<?= $disbursement['id']; ?>" />
                                                <input type="hidden" readonly name="vclient_id" value="<?= $disbursement['client_id']; ?>" />
                                                <div class="form-body">
                                                    <!-- client -->
                                                    <div class="row gx-3 gy-2 align-items-center mt-0" id="clientData" style="display: none;">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">client Name</label>
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" name="vclient_name" id="client_name" placeholder="client Name" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Account Number</label>
                                                                <div class="col-md-12">
                                                                    <input name="vaccount_no" id="account_no" placeholder="Account Number" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Contact</label>
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
                                                                <label class="control-label fw-bold  col-md-12">Acount Type</label>
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" name="vaccount_type" id="account_type" placeholder="Account Type" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Particular</label>
                                                                <div class="col-md-12">
                                                                    <input name="vparticular_name" id="particular_name" placeholder="Particular" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Trans Type</label>
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
                                                                <label class="control-label fw-bold  col-md-12">Transaction Date</label>
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                        <input type="text" class="form-control" name="vdate" id="date" placeholder="Date" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Branch Name</label>
                                                                <div class="col-md-12">
                                                                    <input name="vbranch_name" id="branch_name" placeholder="branch_name" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Status</label>
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
                                                                <label class="control-label fw-bold  col-md-12">Loan Product</label>
                                                                <div class="col-md-12">
                                                                    <input name="vproduct_name" id="product_name" placeholder="Loan Product" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Disbursement Code</label>
                                                                <div class="col-md-12">
                                                                    <input name="vdisbursement_id" id="disbursement_id" placeholder="Disbursement Code" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Class</label>
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
                                                                <label class="control-label fw-bold  col-md-12">Loan Product</label>
                                                                <div class="col-md-12">
                                                                    <input name="vproduct_name" id="product_name" placeholder="Loan Product" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Application Code</label>
                                                                <div class="col-md-12">
                                                                    <input name="vapplication_id" id="application_id" placeholder="Application Code" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Application Status</label>
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
                                                                <label class="control-label fw-bold  col-md-12">Ref ID</label>
                                                                <div class="col-md-12">
                                                                    <input name="vref_id" placeholder="Ref ID" class="form-control" type="text" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Amount</label>
                                                                <div class="col-md-12">
                                                                    <input type="text" name="vamount" id="amount" class="form-control" placeholder="Amount" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Payment</label>
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
                                                                <label class="control-label fw-bold  col-md-12">Transaction Details</label>
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
                                                                <label class="control-label fw-bold  col-md-12">Transaction Remarks</label>
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
                                                                <label class="control-label fw-bold  col-md-12">Trans Menu</label>
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" name="ventry_menu" placeholder="Trans Menu" id="entry_menu" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Balance</label>
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
                                                                <label class="control-label fw-bold  col-md-12">Officer</label>
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" name="vstaff_id" placeholder="Officer" id="officer" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Created Date</label>
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                        <input type="text" name="created_at" id="created_at" class="form-control" placeholder="Transaction Date" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label fw-bold  col-md-12">Updated At</label>
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                        <input type="text" name="updated_at" id="updated_at" class="form-control" placeholder="Updated At" readonly>
                                                                    </div>
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
                                var id = '<?= isset($disbursement) ? $disbursement['id'] : 0; ?>';
                                var appID = '<?= isset($disbursement) ? $disbursement['application_id'] : 0; ?>';
                                var clientId = <?= $disbursement['client_id'] ?>;
                                var principal = <?= isset($disbursement) ? $disbursement['principal'] : 0; ?>;
                            </script>
                            <script src="/assets/client/main/auto.js"></script>
                            nj<script src="/assets/client/loans/repayment.js"></script>
                            <!-- dataTables -->
                            <script src="/assets/scripts/main/datatables.js"></script>
                            <script src="/assets/scripts/loans/index.js"></script>
                            <script src="/assets/scripts/transactions/index.js"></script>
                            <script src="/assets/client/main/options.js"></script>
                            <script src="/assets/scripts/main/images-js.js"></script>

                            <?= $this->endSection() ?>