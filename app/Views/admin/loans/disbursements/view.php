<?= $this->extend("layout/main"); ?>

<?php
$applicantProduct = ($disbursement['applicantProduct']);
$loanCharges = unserialize($disbursement['overall_charges']);
switch (strtolower($disbursement['class'])) {
    case 'running':
        $statusColor = 'text-info';
        break;
    case 'arrears':
        $statusColor = 'text-warning';
        break;
    case 'cleared':
        $statusColor = 'text-success';
        break;
    case 'expired':
        $statusColor = 'text-danger';
        break;
    case 'defaulted':
        $statusColor = 'text-danger';
        break;
    default:
        $statusColor = 'text-info';
        break;
}

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

<?= $this->section("content"); ?>
<div class="container-fluid">
    <div class="card profile-overview profile-overview-wide">
        <div class="card-body d-flex">
            <div class="clearfix">
                <div class="d-inline-block position-relative me-sm-4 me-3 mb-3 mb-lg-0">
                    <?php if (file_exists('uploads/clients/passports/' . $disbursement['photo']) && $disbursement['photo']) : ?>
                        <img src="/uploads/clients/passports/<?= $disbursement['photo']; ?>" class="rounded-4 profile-avatar" alt="Photo" />
                    <?php else : ?>
                        <img src="/assets/dist/img/nophoto.jpg" class="rounded-4 profile-avatar" alt="Photo" />
                    <?php endif; ?>
                    <span class="fa fa-circle border border-3 border-white <?= $statusColor; ?> position-absolute bottom-0 end-0 rounded-circle" title="Loan Class: <?= $disbursement['class'] ?>"></span>
                </div>
            </div>
            <div class="clearfix d-xl-flex flex-grow-1">
                <div class="clearfix pe-md-5">
                    <h3 class="fw-semibold mb-1"><?= (isset($disbursement['title'])) ? $disbursement['title'] . ' ' . $disbursement['name'] : $disbursement['name']; ?></h3>
                    <ul class="d-flex flex-wrap fs-6 align-items-center">
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-la-code-branch me-1 fs-18"></i><?= $disbursement['branch_name']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-genderless me-1 fs-18"></i><?= $disbursement['gender']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-tag me-1 fs-18"></i><?= $disbursement['account_type']; ?>
                        </li>
                    </ul>

                    <ul class="d-flex flex-wrap fs-6 align-items-center mt-2">
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-phone me-1 fs-18"></i><?= ($disbursement['alternate_no']) ? $disbursement['mobile'] . ', ' . $disbursement['alternate_no'] : $disbursement['mobile']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-envelope me-1 fs-18"></i><?= $disbursement['email']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-map-marker me-1 fs-18"></i><?= $disbursement['residence']; ?>
                        </li>
                    </ul>
                    <div class="d-md-flex d-none flex-wrap">
                        <div class="border outline-dashed rounded p-2 d-flex align-items-center me-3 mt-3">
                            <div class="avatar avatar-md bg-primary-light text-primary rounded d-flex align-items-center justify-content-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9849 15.3462C8.11731 15.3462 4.81445 15.931 4.81445 18.2729C4.81445 20.6148 8.09636 21.2205 11.9849 21.2205C15.8525 21.2205 19.1545 20.6348 19.1545 18.2938C19.1545 15.9529 15.8735 15.3462 11.9849 15.3462Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9849 12.0059C14.523 12.0059 16.5801 9.94779 16.5801 7.40969C16.5801 4.8716 14.523 2.81445 11.9849 2.81445C9.44679 2.81445 7.3887 4.8716 7.3887 7.40969C7.38013 9.93922 9.42394 11.9973 11.9525 12.0059H11.9849Z" stroke="var(--primary)" stroke-width="1.42857" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="clearfix ms-2">
                                <h3 class="mb-0 fw-semibold lh-1"><?= $disbursement['disbursement_code']; ?></h3>
                                <span class="fs-14">Disbursement Code</span>
                            </div>
                        </div>
                        <div class="border outline-dashed rounded p-2 d-flex align-items-center me-3 mt-3">
                            <div class="avatar avatar-md bg-primary-light text-primary rounded d-flex align-items-center justify-content-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M21.25 16.3341V7.66512C21.25 4.64512 19.111 2.75012 16.084 2.75012H7.916C4.889 2.75012 2.75 4.63512 2.75 7.66512L2.75 16.3341C2.75 19.3641 4.889 21.2501 7.916 21.2501H16.084C19.111 21.2501 21.25 19.3641 21.25 16.3341Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.0861 12.0001H7.91406" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12.3223 8.25211L16.0863 12.0001L12.3223 15.7481" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="clearfix ms-2">
                                <h3 class="mb-0 fw-semibold lh-1"><?= $disbursement['product_name'] ?></h3>
                                <span class="fs-14">Loan Product</span>
                            </div>
                        </div>
                        <div class="border outline-dashed rounded p-2 d-flex align-items-center me-3 mt-3">
                            <div class="avatar avatar-md bg-primary-light text-primary rounded d-flex align-items-center justify-content-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 1V23" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M17 5H9.5C8.57174 5 7.6815 5.36875 7.02513 6.02513C6.36875 6.6815 6 7.57174 6 8.5C6 9.42826 6.36875 10.3185 7.02513 10.9749C7.6815 11.6313 8.57174 12 9.5 12H14.5C15.4283 12 16.3185 12.3687 16.9749 13.0251C17.6313 13.6815 18 14.5717 18 15.5C18 16.4283 17.6313 17.3185 16.9749 17.9749C16.3185 18.6313 15.4283 19 14.5 19H6" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="clearfix ms-2">
                                <h3 class="mb-0 fw-semibold lh-1"><?= number_format($disbursement['total_balance']); ?></h3>
                                <span class="fs-14">Loan Balance</span>
                            </div>
                        </div>
                    </div>
                    <ul class="d-flex flex-wrap fs-6 align-items-center mt-2">
                        <li class="me-3 d-inline-flex align-items-center" title="Total Loan">
                            <i class="fas fa-money-bill me-1 fs-18"></i><?= number_format($disbursement['actual_repayment']); ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center" title="Principal Taken">
                            <i class="fas fa-money-bill-1 me-1 fs-18"></i><?= number_format($disbursement['principal']); ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center" title="Total Interest">
                            <i class="fas fa-money-bill-1-wave me-1 fs-18"></i><?= number_format($disbursement['actual_interest']); ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center" title="Installment Amount">
                            <i class="fas fa-money-bill-1-wave me-1 fs-18"></i><?= number_format($disbursement['actual_installment']); ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center" title="Total Repayed">
                            <i class="fas fa-bank me-1 fs-18"></i><?= number_format($disbursement['total_collected']); ?>
                        </li>
                    </ul>
                </div>
                <div class="clearfix mt-3 mt-xl-0 ms-auto d-flex flex-column col-xl-3">
                    <div class="clearfix mb-3 text-xl-end">
                        <div class="dropdown custom-dropdown mb-0">
                            <div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown">
                                Actions <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.75 12C2.75 17.108 6.891 21.25 12 21.25C17.108 21.25 21.25 17.108 21.25 12C21.25 6.892 17.108 2.75 12 2.75C6.891 2.75 2.75 6.892 2.75 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8.52881 10.5577L11.9998 14.0437L15.4708 10.5577" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="dropdown-menu dropdown-menu-end">
                                <?php if (($disbursement['total_balance'] > 0) && ((unserialize($user['permissions']) == 'all') || (in_array('create_transactionsRepayments', unserialize($user['permissions']))))): ?>
                                    <a href="javascript:void(0);" id="repaymentsBtn" class="dropdown-item" onclick="add_disbursementPayment()" title="Installment Repayment Transaction"><i class="fa fa-money-bill-trend-up text-primary"></i> Pay Installment</a>
                                    <div class="dropdown-divider"></div>
                                <?php endif; ?>
                                <?php if ((unserialize($user['permissions']) == 'all') || (in_array('export_' . strtolower($menu) . 'Disbursements', unserialize($user['permissions'])))) : ?>
                                    <a href="javascript:void(0);" class="dropdown-item d-flex text-dark" onclick="exportDisbursementForm()" title="Export Disbursement Details">
                                        Print <?= ($disbursement['disbursement_code']) ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer py-0 d-flex flex-wrap justify-content-between align-items-center px-0">
            <div class="card-footer py-0 d-flex flex-wrap justify-content-between align-items-center px-0">
                <ul class="nav nav-underline nav-underline-primary nav-underline-text-dark nav-underline-gap-x-0" id="tabMyProfileBottom" role="tablist">
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="kycBtn" class="nav-link py-3 border-3 text-dark active" data-bs-toggle="tab" data-bs-target="#profile-tab" type="button" role="tab" aria-controls="profile-tab" aria-selected="true">Client Profile</a>
                    </li>
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="termsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#terms-tab" type="button" role="tab" aria-controls="terms-tab" aria-selected="true">Loan Profile</a>
                    </li>
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="amountsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#amounts-tab" type="button" role="tab" aria-controls="amounts-tab" aria-selected="false">Loan Amounts</a>

                    </li>
                    <li class="nav-item ms-1" id="" role="presentation">
                        <a href="javascript:void(0)" id="amortizationBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#amortization-tab" type="button" role="tab" aria-controls="amortization-tab" aria-selected="false">Repayments</a>
                    </li>
                    <li class="nav-item ms-1" id="" role="presentation">
                        <a href="javascript:void(0)" id="historyBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#history-tab" type="button" role="tab" aria-controls="history-tab" aria-selected="false">History</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="tab-content" id="tabContentMyProfileBottom">
    <div class="card">
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div id="contentSPinner">
                    <!-- placement for spinner as content loads -->
                </div>
                <!-- client profile -->
                <div class="tab-pane fade show active" id="profile-tab" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mt-3">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="d-sm-flex d-block align-items-center justify-content-between">
                                            <div class="h5 fw-semibold mb-0"> Client Details</div>
                                            <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
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
                                                            <input name="vbranch_id" placeholder="Branch" class="form-control" type="text" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Gender</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" name="vgender" placeholder="Gender" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Nationality</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" name="vnationality" placeholder="Nationality" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Marital Status</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" name="vmarital_status" placeholder="Marital Status" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Religion</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" name="vreligion" placeholder="Religion" readonly>
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
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Phone Number 1</label>
                                                <div class="col-md-12">
                                                    <input id="vmobile" name="vmobile" placeholder="Phone Number 1" class="form-control phone-input" type="text" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Phone Number 2</label>
                                                <div class="col-md-12">
                                                    <input id="valternate_no" name="valternate_no" placeholder="Phone Number 2" class="form-control phone-input" type="text" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Email</label>
                                                <div class="col-md-12">
                                                    <input name="vemail" placeholder="Email Address" class="form-control" type="email" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Account Number</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="vaccount_no" class="form-control" placeholder="Account Number" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Registration Date</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="vreg_date" class="form-control" placeholder="Registration Date" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Account Type</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="vaccount_type" class="form-control" placeholder="Account Type" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Balance [<?= $settings['currency'] ?>]</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="vaccount_balance" class="form-control" placeholder="Account Balance" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Residence Address</label>
                                                <div class="col-md-12">
                                                    <textarea name="vresidence" placeholder="Residence Address" class="form-control" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Closest Land Mark</label>
                                                <div class="col-md-12">
                                                    <textarea name="vclosest_landmark" placeholder="Major Land Mark Feature nearby e.g school, church, mosque, etc" class="form-control" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">D.O.B</label>
                                                <div class="col-md-12">
                                                    <input name="vdob" placeholder="Date Of Birth" class="form-control" type="date" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Occupation</label>
                                                <div class="col-md-12">
                                                    <input name="voccupation" placeholder="Occupation" class="form-control" type="text" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Age(Years)</label>
                                                <div class="col-md-12">
                                                    <input name="age" placeholder="Age(Years)" class="form-control" type="text" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="control-label fw-bold col-md-12">ID Photo(Front)</label>
                                            <div class="form-group" id="id-previewFront">
                                                <div class="col-md-12">
                                                    (No photo)
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">ID Type</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" name="vid_type" placeholder="ID Type" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">ID Number</label>
                                                        <div class="col-md-12">
                                                            <input name="vid_number" placeholder="ID Number" class="form-control" type="text" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Id Expiry Date</label>
                                                        <div class="col-md-12">
                                                            <input name="vid_expiry" placeholder="Id Expiry Date" class="form-control" type="text" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label fw-bold col-md-12">ID Photo(Back)</label>
                                            <div class="form-group" id="id-previewBack">
                                                <div class="col-md-12">
                                                    (No photo)
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Job Location</label>
                                                <div class="col-md-12">
                                                    <textarea name="vjob_location" placeholder="Job Location" class="form-control" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Next of Kin</label>
                                                <div class="col-md-12">
                                                    <input name="vnext_of_kin" placeholder="Next of kin" class="form-control" type="text" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Next of Kin Relationship</label>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" name="vnok_relationship" placeholder="Next of Kin Relationship" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Next Of Kin Address</label>
                                                <div class="col-md-12">
                                                    <textarea name="vnok_address" placeholder="Address" class="form-control" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Next of Kin Phone1</label>
                                                <div class="col-md-12">
                                                    <input id="vnok_phone" name="vnok_phone" placeholder="next of kin phone" class="form-control phone-input" type="text" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Next of Kin Phone2</label>
                                                <div class="col-md-12">
                                                    <input id="vnok_alt_phone" name="vnok_alt_phone" placeholder="next of kin phone2" class="form-control phone-input" type="text" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Next of Kin Email</label>
                                                <div class="col-md-12">
                                                    <input name="vnok_email" placeholder="Next of Kin Email" class="form-control" type="text" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- loan terms -->
                <div class="tab-pane fade" id="terms-tab" role="tabpanel" aria-labelledby="terms-tab" tabindex="0">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mt-3">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="d-sm-flex d-block align-items-center justify-content-between">
                                            <div class="h5 fw-semibold mb-0"> Loan Details</div>
                                            <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <nav>
                                        <div class="nav nav-tabs nav-justified tab-style-4 d-sm-flex d-block" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-loan-tab" data-bs-toggle="tab" data-bs-target="#nav-loan1" type="button" role="tab" aria-selected="true">Loan Terms</button>
                                            <button class="nav-link" id="nav-security-tab" data-bs-toggle="tab" data-bs-target="#nav-security1" type="button" role="tab" aria-selected="false">Loan Security</button>
                                            <button class="nav-link" id="nav-referees-tab" data-bs-toggle="tab" data-bs-target="#nav-referees1" type="button" role="tab" aria-selected="false">Client Referees</button>
                                            <button class="nav-link" id="nav-financial-tab" data-bs-toggle="tab" data-bs-target="#nav-financial1" type="button" role="tab" aria-selected="false" style="display: none;">Financial Position</button>
                                        </div>
                                    </nav>

                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="nav-loan1" role="tabpanel" aria-labelledby="nav-loan-tab" tabindex="0">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card mt-3">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                                    <div class="h5 fw-semibold mb-0"> Disbursement Details</div>
                                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
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
                                                                        <label class="control-label fw-bold ">Interest Rate(%)</label>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="interest_rate" id="interest_rate" class="form-control" placeholder="Interest Rate" readonly>
                                                                                <span class="help-block text-danger"></span>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <select class="form-control select2bs4" name="interest_period" id="interest_period" style="width: 100%;">
                                                                                    <option value="">--Select---</option>
                                                                                </select>
                                                                                <span class="help-block text-danger"></span>
                                                                            </div>
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
                                                                        <label class="control-label fw-bold ">Repayment Frequency</label>
                                                                        <div class="col-md-12">
                                                                            <input type="text" name="repayment_freq" class="form-control" placeholder="Repayment Frequency" readonly>
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
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold ">Principal Particular</label>
                                                                        <div class="col-md-12">
                                                                            <input type="text" name="principal_particular" class="form-control" placeholder="Principal Particular" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold ">Interest Particular</label>
                                                                        <div class="col-md-12">
                                                                            <input type="text" name="interest_particular" class="form-control" placeholder="Interest Particular" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold ">Payment Method</label>
                                                                        <div class="col-md-12">
                                                                            <input type="text" name="payment_method" class="form-control" placeholder="Payment Method" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                            <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold ">Disbursement Method</label>
                                                                        <div class="col-md-12">
                                                                            <input type="text" name="disbursed_by" class="form-control" placeholder="Disbursement Method" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card mt-3">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                                    <div class="h5 fw-semibold mb-0"> Loan Application Details</div>
                                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
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
                                                                        <label class="control-label fw-bold" for="vapplication_date">Application Date</label>
                                                                        <div class="col-md-12">
                                                                            <div class="input-group">
                                                                                <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                                <input type="text" name="vapplication_date" class="form-control" placeholder="Application Date" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold">Application Status</label>
                                                                        <div class="col-md-12">
                                                                            <input type="text" name="vapp_status" class="form-control" placeholder="Application Status" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row gx-3 gy-2 align-items-center mt-0">
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
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="">Application Officer</label>
                                                                        <input name="application_officer" placeholder="Application Officer" class="form-control" type="text" readonly>
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
                                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="">Application Created At</label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                            <input name="application_created_at" placeholder="Application Created At" class="form-control" type="text" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="">Application Last Updated At</label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                            <input name="application_updated_at" placeholder="Application Last Updated At" class="form-control" type="text" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 text-center">
                                                                    <button class="btn btn-primary" data-bs-target="#loan-agreement" data-bs-toggle="modal" id="btnAgreement">
                                                                        View Agreement
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- loan security/collaterals -->
                                        <div class="tab-pane" id="nav-security1" role="tabpanel" aria-labelledby="nav-security-tab" tabindex="0">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card mt-3">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                                    <div class="h5 fw-semibold mb-0"> Security Item Details</div>
                                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="security_item">Security Item</label>
                                                                        <div class="col-md-12">
                                                                            <input type="text" name="security_item" id="security_item" class="form-control" placeholder="Security Item" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="est_value">Estimated Value [<?= $settings['currency']; ?>]</label>
                                                                        <div class="col-md-12">
                                                                            <input type="text" name="est_value" id="est_value" class="form-control" placeholder="Estimated Value [<?= $settings['currency']; ?>]" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="seeSummernote">Details</label>
                                                                        <div class="col-md-12">
                                                                            <textarea name="vsecurity_info" id="seeSummernote" placeholder="Security Item Details" class="form-control" readonly></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                                    <div class="h5 fw-semibold mb-0"> Collaterals Files</div>
                                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
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
                                        </div>
                                        <!-- referees/guarantors -->
                                        <div class="tab-pane" id="nav-referees1" role="tabpanel" aria-labelledby="nav-referees-tab" tabindex="0">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card mt-3">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                                    <div class="h5 fw-semibold mb-0"> Guarantor 1 Details</div>
                                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_name">Full Name</label>
                                                                        <input type="text" name="relation_name" id="relation_name" class="form-control" placeholder="Full Name" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_relationship">Relationship</label>
                                                                        <input type="text" name="relation_relationship" id="relation_relationship" class="form-control" placeholder="Relationship" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_occupation">Occupation</label>
                                                                        <input type="text" name="relation_occupation" id="relation_occupation" class="form-control" placeholder="Occupation" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_contact">Contact 1</label>
                                                                        <input type="tel" name="relation_contact" id="relation_contact" class="form-control" placeholder="Contact 1" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_alt_contact">Contact 2</label>
                                                                        <input type="tel" name="relation_alt_contact" id="relation_alt_contact" class="form-control" placeholder="Contact 2" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_email">Email</label>
                                                                        <input type="email" name="relation_email" id="relation_email" class="form-control" placeholder="Email" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_address">Address</label>
                                                                        <div class="col-md-12">
                                                                            <textarea name="relation_address" id="relation_address" placeholder="Address" class="form-control" readonly></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                                    <div class="h5 fw-semibold mb-0"> Guarantor 2 Details</div>
                                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_name2">Full Name</label>
                                                                        <input type="text" name="relation_name2" id="relation_name2" class="form-control" placeholder="Full Name" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation2">Relationship</label>
                                                                        <input type="text" name="relation_relationship2" id="relation_relationship" class="form-control" placeholder="Relationship" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_occupation2">Occupation</label>
                                                                        <input type="text" name="relation_occupation2" id="relation_occupation2" class="form-control" placeholder="Occupation" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_contact2">Contact 1</label>
                                                                        <input type="tel" name="relation_contact2" id="relation_contact2" class="form-control" placeholder="Contact 1" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_alt_contact2">Contact 2</label>
                                                                        <input type="tel" name="relation_alt_contact2" id="relation_alt_contact2" class="form-control" placeholder="Contact 2" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_email2">Email</label>
                                                                        <input type="email" name="relation_email2" id="relation_email2" class="form-control" placeholder="Email" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label fw-bold" for="relation_address2">Address</label>
                                                                        <div class="col-md-12">
                                                                            <textarea name="relation_address2" id="relation_address2" placeholder="Address" class="form-control" readonly></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- client financial position -->
                                        <div class="tab-pane" id="nav-financial1" role="tabpanel" aria-labelledby="nav-financial-tab" tabindex="0">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card mt-3">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                                    <div class="h5 fw-semibold mb-0"> Monthly Income and Expenses [<?= $settings['currency']; ?>] Details</div>
                                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <p class="col-md-12"></p>
                                                                <div class="col-md-6">
                                                                    <div class="row align-items-center mt-0">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group text-center">
                                                                                <p class="fw-semibold">Income</p>
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

                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-md-12 text-center">
                                                                            <p class="fw-semibold">Expenses</p>
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
                                                            </div>
                                                            <!-- difference status -->
                                                            <div class="row">
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
                                                                <!-- expenses status -->
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
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                                    <div class="h5 fw-semibold mb-0">Receipts\Invoices\Slips</div>
                                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
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
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card mt-3">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                                    <div class="h5 fw-semibold mb-0"> Other Financial Account Details</div>
                                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                <label class="control-label fw-bold col-4" for="institution">Institution</label>
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
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                                    <div class="h5 fw-semibold mb-0"> Loans In Other Financial Institutions</div>
                                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                                    </div>
                                                                </div>
                                                            </div>
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- loan amounts -->
                <div class="tab-pane fade" id="amounts-tab" role="tabpanel" aria-labelledby="amounts-tab" tabindex="0">
                    <div class="card">
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
                    <div class="card">
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
                    <div class="card">
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
                    <div class="card">
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
                <!-- amortization schedule & repayments -->
                <div class="tab-pane fade" id="amortization-tab" role="tabpanel" aria-labelledby="amortization-tab" tabindex="0">
                    <!-- Amortization Schedule -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Amortization Schedule</h5>
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
                                                    <th>O/Standing Amt [<?= $settings['currency']; ?>]</th>
                                                    <th>Principal Installment [<?= $settings['currency']; ?>]</th>
                                                    <th>Interest Installment [<?= $settings['currency']; ?>]</th>
                                                    <th>Installment [<?= $settings['currency']; ?>]</th>
                                                    <th>Balance [<?= $settings['currency']; ?>]</th>
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
                    <!-- repayment history -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Repayment History</h5>
                        </div>
                        <div class="card-body">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12">
                                    <div class="">
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
                <!-- client loan history -->
                <div class="tab-pane fade" id="history-tab" role="tabpanel" aria-labelledby="history-tab" tabindex="0">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mt-3">
                                <div class="card-header">
                                    <div class="card-title">
                                        <div class="d-sm-flex d-block align-items-center justify-content-between">
                                            <div class="h5 fw-semibold mb-0"> Applicant Loan History Details</div>
                                            <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <nav>
                                            <div class="nav nav-tabs nav-justified tab-style-4 d-sm-flex d-block" id="nav-tab" role="tablist">
                                                <button class="nav-link active" id="nav-history-appl-tab" data-bs-toggle="tab" data-bs-target="#nav-history-appl" type="button" role="tab" aria-selected="true">Applications</button>
                                                <button class="nav-link" id="nav-history-dis-tab" data-bs-toggle="tab" data-bs-target="#nav-history-dis" type="button" role="tab" aria-selected="false">Disbursements</button>
                                            </div>
                                        </nav>
                                        <div class="tab-content" id="tab-style-4">
                                            <!-- Application History details -->
                                            <div class="tab-pane show active" id="nav-history-appl" role="tabpanel" aria-labelledby="history-appl-tab" tabindex="0">
                                                <div class="card mt-3">
                                                    <div class="card-header">
                                                        <h5 class="card-title text-bold">Application History</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0" id="applicationsHistory">
                                                            <!-- AJAX Applications History Response Here -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Disbursement History -->
                                            <div class="tab-pane" id="nav-history-dis" role="tabpanel" aria-labelledby="nav-history-dis-tab" tabindex="0">
                                                <div class="card mt-3">
                                                    <div class="card-header">
                                                        <h5 class="card-title text-bold">Disbursement History</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row gx-3 gy-2 align-items-center mt-0" id="disbursementsHistory">
                                                            <!-- AJAX Disbursements History Response Here -->
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
            </div>
        </div>
        <div class="card-footer">
            <!-- footer[dates and signature] -->
            <div class="row">
                <div class="col-md-8">
                    <div class="row gx-3 gy-2 align-items-center mt-0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label fw-bold col-md-12">
                                    Disbursement Date
                                </label>
                                <div class="col-md-12">
                                    <input name="date_disbursed" placeholder="Disbursement Date" class="form-control" type="text" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label fw-bold col-md-12">Disbursment Officer</label>
                                <div class="col-md-12">
                                    <input name="vofficer" placeholder="Disbursment officer" class="form-control" type="text" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label fw-bold col-md-12">Created At</label>
                                <div class="col-md-12">
                                    <input name="vcreated_at" placeholder="Created At" class="form-control" type="text" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label fw-bold col-md-12">Last Updated At</label>
                                <div class="col-md-12">
                                    <input name="vupdated_at" placeholder="Last Updated At" class="form-control" type="text" readonly>
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
                                    <?= $settings['business_contact'] . ' | ' . $settings['business_alt_contact']; ?>
                                </p>
                                <p class="fw-semibold mb-2 fs-12">
                                    <?= $settings['business_email']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-start">
                            <p class="fw-bold text-default terms-heading"><u>YOUR REF: <?= $disbursement['account_no']; ?></u></p>
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
                            <b>3. Term/Period:</b> <?= $disbursement['repayment_period'] . '(' . convert_number($disbursement['repayment_period']) . ')'; ?> <?= $disbursement['repayment_duration']; ?> from the date of disbursement.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">4. Frequency of payment:</span> <?= $disbursement['repayment_frequency']; ?>.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">5. Total number of loan installments:</span> <?= $disbursement['installments_num'] . '(' . convert_number($disbursement['installments_num']) . ')'; ?> .
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">6. Interest Rate:</span> <?= $disbursement['interest_rate'] . '% per ' . $disbursement['interest_period']; ?> calculated on a <?= $disbursement['interest_type']; ?> basis.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">7. Total amount to repay (principal + interest):</span> <?= $settings['currency'] . ' ' . number_format($disbursement['actual_repayment']) . '(' . convert_number($disbursement['actual_repayment']) . ')'; ?>.
                        </p>
                        <?php if (count($charges) > 0 && count($loanCharges) > 0) : ?>
                            <p class="mb-2">
                                <b>8. Total Loan charges:</b> <?= $settings['currency'] . ' ' . $disbursement['total_charges'] . '= (' . convert_number($disbursement['total_charges']) . ')'; ?> including the following;
                            <ul class="list-group list-group-horizontal">
                                <?php
                                foreach ($charges as $charge) :
                                    foreach ($loanCharges as $index => $loanCharge) :
                                        $lanCharge = $loanCharge['charge'];
                                        $chargeMethod = $loanCharge['charge_method'];
                                        $particularId = $loanCharge['particular_id'];
                                        if ($charge['particular_id'] == $particularId) :
                                ?>
                                            <li class="list-group-item">
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-2">
                                                        <b><?= $charge['particular_name'] ?></b>, charge
                                                        <i>
                                                            <?= $lanCharge; ?>
                                                            <?= (strtolower($chargeMethod) == 'amount') ? $settings['currency'] : '% of the principal'; ?>
                                                        </i>
                                                    </div>
                                                </div>
                                            </li>
                                <?php
                                        endif;
                                    endforeach;
                                endforeach;
                                ?>
                            </ul>
                            </p>
                        <?php endif; ?>
                        <p class="mb-2">
                            <span class="fw-semibold">9. Actual Amount received less charges (1–8)</span> is <?= $settings['currency'] . ' ' . number_format(((float)$disbursement['principal'] - (float)$disbursement['total_charges'])) . '= (' . convert_number(((float)$disbursement['principal'] - (float)$disbursement['total_charges'])) . ')'; ?>.
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
                            The Facility will be secured by the pledged chattels/collateral of <span class="fw-semibold"><?= $disbursement['security_item'] ?></span> with estimated values of <span class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($disbursement['est_value']) . '(' . convert_number($disbursement['est_value']) . ')' ?> </span>
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">Description: </span> <?= strip_tags($disbursement['security_info']) ?>
                        </p>

                        <p class="mb-2 fw-semibold">Personal Guarantor(s):</p>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-2">
                                    <span class="fw-semibold">1.</span> <?= $disbursement['relation_name'] . ' (' . $disbursement['relation_relationship'] . ')'; ?>, <?= $disbursement['relation_occupation']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-phone"></i>&nbsp;<?= $disbursement['relation_contact'] . ' | ' . $disbursement['relation_alt_contact']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-envelope"></i>&nbsp;<?= $disbursement['relation_email']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-home"></i>&nbsp;<?= $disbursement['relation_address']; ?>
                                </p>
                            </div>
                            <div class="col-6">
                                <p class="mb-2">
                                    <span class="fw-semibold">2.</span> <?= $disbursement['relation_name2'] . ' (' . $disbursement['relation_relationship2'] . ')'; ?>, <?= $disbursement['relation_occupation2']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-phone"></i>&nbsp;<?= $disbursement['relation_contact2'] . ' | ' . $disbursement['relation_alt_contact2']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-envelope"></i>&nbsp;<?= $disbursement['relation_email2']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-home"></i>&nbsp;<?= $disbursement['relation_address2']; ?>
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
                <button class="btn btn-primary" data-bs-target="#remarks_form" data-bs-toggle="modal">Back</button>
            </div>
        </div>
    </div>
</div>
<!-- file view modal -->
<div class="modal fade" data-bs-backdrop="static" id="file_view">
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
<div class="modal fade" data-bs-backdrop="static" id="repayment_modal_form" role="dialog">
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
                    <input type="hidden" readonly name="title" />
                    <input type="hidden" readonly name="particular_id" />
                    <input type="hidden" readonly name="entry_menu" />
                    <input type="hidden" readonly name="transaction_menu" />

                    <div class="form-body">
                        <!-- summary -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-8">
                                <div class="row gx-3 gy-2 align-items-center mt-0">
                                    <div class="col-md-4">
                                        <p class="">
                                            Name: <br />
                                            <strong class="pl-3" id="cName"></strong>
                                        </p>
                                        <p class="">
                                            Contact: <br />
                                            <strong class="pl-3" id="cContact"></strong>
                                        </p>
                                        <p class="">
                                            Alt Contact: <br />
                                            <strong class="pl-3" id="cContact2"></strong>
                                        </p>
                                        <p class="">
                                            Email: <br />
                                            <strong class="pl-3" id="cEmail"></strong>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="">
                                            Acc Number: <br />
                                            <strong class="pl-3" id="cAccountNo"></strong>
                                        </p>
                                        <p class="">
                                            Acc Balance: <br />
                                            <strong class="pl-3" id="cBalance"></strong>
                                        </p>
                                        <p class="">
                                            Address: <br />
                                            <strong class="pl-3" id="cAddress"></strong>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="">
                                            Total Loan: <br />
                                            <strong class="pl-3" id="tLoan"></strong>
                                        </p>
                                        <p class="">
                                            Loan Balance: <br />
                                            <strong class="pl-3" id="lBalance"></strong>
                                        </p>
                                        <p class="">
                                            Installment: <br />
                                            <strong class="pl-3" id="installment"></strong>
                                        </p>
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
                        </div>
                        <hr>
                        <!-- particular -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Principal Taken</label>
                                    <div class="col-md-12">
                                        <input name="principal_taken" placeholder="Principal Taken" class="form-control" type="text" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-sm-12">Transaction Type <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select name="entry_typeId" id="entry_typeId" class="form-control select2bs4">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Contact</label>
                                    <div class="col-md-12">
                                        <input id="contact" name="contact" placeholder="Contact" class="form-control phone-input" type="tel">
                                        <input type="hidden" readonly id="contact_full" name="contact_full">
                                        <input type="hidden" name="contact_country_code" id="contact_country_code" readonly>
                                    </div>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <!-- amount -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Installment [<?= $settings['currency']; ?>] <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" name="amount" class="form-control amount" placeholder="Installment [<?= $settings['currency']; ?>]" min="0">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Payment Method <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <select name="payment_id" id="payment_id" class="form-control select2bs4" style="width: 100%;">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-sm-12">Transaction Date <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <!-- <input type="text" name="date" id="date" class="form-control getDatePicker" value="<?= date('Y-m-d'); ?>" placeholder="Transaction Date"> -->
                                            <input type="text" name="date" id="date" class="form-control" value="<?= date('Y-m-d'); ?>" placeholder="Transaction Date" readonly>
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
                                    <label class="control-label fw-bold col-md-12">Details <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <textarea name="entry_details" class="form-control" id="addSummernote"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Remarks</label>
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
                                        <input type="text" name="vcontact" id="vcontact" class="form-control" placeholder="Contact" readonly>
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
    var disID = '<?= isset($disbursement) ? $disbursement['id'] : 0; ?>';
    var appID = '<?= isset($disbursement) ? $disbursement['application_id'] : 0; ?>';
    var clientId = <?= $disbursement['client_id'] ?>;
    var principal = <?= isset($disbursement) ? $disbursement['principal'] : 0; ?>;
    var applicantProduct = <?= json_encode(unserialize($disbursement['applicant_products'])); ?>;
    var decryptedId = '<?= $decryptedId; ?>';
</script>
<script src="/assets/scripts/main/auto-updates.js"></script>
<script src="/assets/scripts/loans/disbursements/view.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/loans/index.js"></script>
<script src="/assets/scripts/transactions/index.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>
<script src="/assets/scripts/main/phone.js"></script>

<?= $this->endSection() ?>