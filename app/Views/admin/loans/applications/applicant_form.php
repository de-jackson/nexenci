<?= $this->extend("layout/main"); ?>

<?php

$applicantProduct = ($application['applicantProduct']);
switch (strtolower($application['status'])) {
    case 'pending':
        $statusColor = 'text-info';
        break;
    case 'processing':
        $statusColor = 'text-warning';
        break;
    case 'declined':
        $statusColor = 'text-danger';
        break;
    case 'approved':
        $statusColor = 'text-success';
        break;
    case 'disbursed':
        $statusColor = 'text-primary';
        break;
    case 'cancelled':
        $statusColor = 'text-danger';
        break;
    default:
        $statusColor = 'text-secondary';
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
    /* Hundreds (Hector) */
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

switch (strtolower($application['repayment_frequency'])) {
    case 'daily':
        $grace_period = 1;
        break;
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

<?= $this->section("content"); ?>
<div class="container-fluid">
    <div class="card profile-overview profile-overview-wide">
        <div class="card-body d-flex">
            <div class="clearfix">
                <div class="d-inline-block position-relative me-sm-4 me-3 mb-3 mb-lg-0">
                    <?php if (file_exists('uploads/clients/passports/' . $application['photo']) && $application['photo']) : ?>
                        <img src="/uploads/clients/passports/<?= $application['photo']; ?>" class="rounded-4 profile-avatar" alt="Photo" />
                    <?php else : ?>
                        <img src="/assets/dist/img/nophoto.jpg" class="rounded-4 profile-avatar" alt="Photo" />
                    <?php endif; ?>
                    <span class="fa fa-circle border border-3 border-white <?= $statusColor; ?> position-absolute bottom-0 end-0 rounded-circle" title="Status: <?= $application['status']; ?>"></span>
                </div>
            </div>
            <div class="clearfix d-xl-flex flex-grow-1">
                <div class="clearfix pe-md-5">
                    <h3 class="fw-semibold mb-1"><?= (isset($application['title'])) ? $application['title'] . ' ' . $application['name'] : $application['name']; ?></h3>
                    <ul class="d-flex flex-wrap fs-6 align-items-center">
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-la-code-branch me-1 fs-18"></i><?= $application['branch_name']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-genderless me-1 fs-18"></i><?= $application['gender']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-tag me-1 fs-18"></i><?= $application['account_type']; ?>
                        </li>
                    </ul>

                    <ul class="d-flex flex-wrap fs-6 align-items-center mt-2">
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-phone me-1 fs-18"></i><?= ($application['alternate_no']) ? $application['mobile'] . ', ' . $application['alternate_no'] : $application['mobile']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-envelope me-1 fs-18"></i><?= $application['email']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-map-marker me-1 fs-18"></i><?= $application['residence']; ?>
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
                                <h3 class="mb-0 fw-semibold lh-1"><?= $application['application_code']; ?></h3>
                                <span class="fs-14">Application Code</span>
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
                                <h3 class="mb-0 fw-semibold lh-1"><?= $application['product_name'] ?></h3>
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
                                <h3 class="mb-0 fw-semibold lh-1"><?= number_format($application['principal']); ?></h3>
                                <span class="fs-14">Loan Principal</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix mt-3 mt-xl-0 ms-auto d-flex flex-column col-xl-3">
                    <div class="clearfix mb-3 text-xl-end">
                        <div class="dropdown custom-dropdown mb-0">
                            <div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown" id="actions-liBtn">
                                Actions <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.75 12C2.75 17.108 6.891 21.25 12 21.25C17.108 21.25 21.25 17.108 21.25 12C21.25 6.892 17.108 2.75 12 2.75C6.891 2.75 2.75 6.892 2.75 12Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8.52881 10.5577L11.9998 14.0437L15.4708 10.5577" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- disburse application -->
                                <?php if ((unserialize($user['permissions']) == 'all') || (in_array('create_' . strtolower($menu) . 'Disbursement', unserialize($user['permissions'])))): ?>
                                    <div id="disbursementBtn">
                                        <a href="javascript:void(0);" class="dropdown-item d-flex text-success" onclick="add_applicationRemarks('<?= $application['id'] ?>','disburse')" title="Disburse <?= $application['application_code'] ?>">
                                            Disburse <?= $application['application_code'] ?>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    </div>
                                <?php endif; ?>
                                <!-- loans status options -->
                                <?php if ((unserialize($user['permissions']) == 'all') || (in_array('update_' . strtolower($menu) . 'Applications', unserialize($user['permissions'])))): ?>
                                    <div id="editBtn">
                                        <a href="javascript:void(0);" class="dropdown-item" onclick="edit_application(<?= $application['id'] ?>)" title="Edit <?= $application['application_code'] ?>"><i class="fa fa-edit text-info"></i> Edit <?= ($application['application_code']) ?></a>
                                        <div class="dropdown-divider"></div>
                                    </div>
                                    <!-- <div id="processApplication">
                                        <a href="javascript:void(0);" class="dropdown-item d-flex text-info" onclick="application_status('<?= $application['id'] ?>', '<?= $application['application_code'] ?>', 'Processing')" title="Process Loan Application">Process</a> 
                                        <div class="dropdown-divider"></div>
                                    </div> -->
                                    <div id="cancelApplication">
                                        <a href="javascript:void(0);" class="dropdown-item d-flex text-warning" onclick="application_status('<?= $application['id'] ?>', '<?= $application['application_code'] ?>', 'Cancelled')" title="Cancel Application <?= $application['application_code'] ?>"> Cancel <?= ($application['application_code']) ?></a>
                                        <div class="dropdown-divider"></div>
                                    </div>

                                    <div id="declineApplication">
                                        <a href="javascript:void(0);" class="dropdown-item d-flex text-danger" onclick="application_status('<?= $application['id'] ?>', '<?= $application['application_code'] ?>', 'Declined')" title="Decline Application <?= $application['application_code'] ?>"> Declined <?= ($application['application_code']) ?></a>
                                        <div class="dropdown-divider"></div>
                                    </div>
                                <?php endif; ?>
                                <!-- delete option -->
                                <?php if ((unserialize($user['permissions']) == 'all') || (in_array('delete_' . strtolower($menu) . 'Applications', unserialize($user['permissions']))) || (in_array('bulkDelete_' . strtolower($menu) . 'Applications', unserialize($user['permissions'])))): ?>
                                    <div id="deleteBtn">
                                        <a href="javascript:void(0);" class="dropdown-item" onclick="delete_client(<?= $application['id'] ?>, '<?= $application['application_code'] ?>')" title="Delete <?= $application['application_code'] ?>"><i class="fa fa-trash text-danger"></i> Delete <?= $application['application_code'] ?></a>
                                        <div class="dropdown-divider"></div>
                                    </div>
                                <?php endif; ?>
                                <?php if ((unserialize($user['permissions']) == 'all') || (in_array('export_' . strtolower($menu) . 'Applications', unserialize($user['permissions'])))) : ?>
                                    <div id="printBtn">
                                        <a href="javascript:void(0);" class="dropdown-item d-flex text-dark" onclick="exportApplicationForm()" title="Export Application Details">
                                            Print <?= ($application['application_code']) ?>
                                        </a>
                                    </div>
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
                        <a href="javascript:void(0)" id="kycBtn" class="nav-link py-3 border-3 text-dark active" data-bs-toggle="tab" data-bs-target="#kyc-tab" type="button" role="tab" aria-controls="kyc-tab" aria-selected="true">Applicant Profile</a>
                    </li>
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="applicationBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#application-tab" type="button" role="tab" aria-controls="application-tab" aria-selected="true">Application</a>
                    </li>
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="chargesBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#charges-tab" type="button" role="tab" aria-controls="charges-tab" aria-selected="false">Charges</a>

                    </li>
                    <li class="nav-item ms-1" id="" role="presentation">
                        <a href="javascript:void(0)" id="amortizerBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#amortizer-tab" type="button" role="tab" aria-controls="amortizer-tab" aria-selected="false">Amortizer</a>
                    </li>
                    <li class="nav-item ms-1" id="" role="presentation">
                        <a href="javascript:void(0)" id="historyBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#history-tab" type="button" role="tab" aria-controls="history-tab" aria-selected="false">History</a>
                    </li>
                    <li class="nav-item ms-1" id="" role="presentation">
                        <a href="javascript:void(0)" id="remarksBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#remarks-tab" type="button" role="tab" aria-controls="remarks-tab" aria-selected="false">Remarks</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="tab-content" id="tabContentMyProfileBottom">
    <div class="row">
        <div class="col-xxl-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div id="contentSPinner">
                            <!-- placement for spinner as content loads -->
                        </div>
                        <!-- applicant profile -->
                        <div class="tab-pane fade show active" id="kyc-tab" role="tabpanel" aria-labelledby="kyc-tab" tabindex="0">
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
                        <!-- application terms -->
                        <div class="tab-pane fade" id="application-tab" role="tabpanel" aria-labelledby="application-tab" tabindex="0">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                    <div class="h5 fw-semibold mb-0"> Application Details</div>
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
                                                    <button class="nav-link" id="nav-financial-tab" data-bs-toggle="tab" data-bs-target="#nav-financial1" type="button" role="tab" aria-selected="false">Financial Position</button>
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
                                                                            <div class="h5 fw-semibold mb-0"> Loan Terms Details</div>
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
                                                                                <label class="control-label fw-bold" for="principal">Principal [<?= $settings['currency']; ?>]</label>
                                                                                <div class="col-md-12">
                                                                                    <input name="vprincipal" id="principal" placeholder="Principal [<?= $settings['currency']; ?>]" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="product_id">Loan Product</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" name="vproduct_id" class="form-control" placeholder="Loan Product" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold">
                                                                                    Interest Rate(%)
                                                                                </label>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <input type="text" name="<?= (isset($application['interest_rate'])) ? "vpinterest_rate" : "vinterest_rate"; ?>" class="form-control" placeholder="Interest Rate" value="<?= (isset($application['interest_rate'])) ? $application['interest_rate'] : ""; ?>" readonly>
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
                                                                                <label class="control-label fw-bold">Interest Method</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" name="<?= (isset($application['interest_type'])) ? "vpinterest_type" : "vinterest_type"; ?>" class="form-control" placeholder="Interest Method" readonly value="<?= (isset($application['interest_type'])) ? $application['interest_type'] : ""; ?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold">Loan Period and Frequency</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" name="<?= (isset($application['repayment_frequency'])) ? "vprepayment_freq" : "vrepayment_freq"; ?>" class="form-control" placeholder="Repayment Frequency" readonly value="<?= (isset($application['loan_period'])) ? $application['loan_period'] . " (" . $application['loan_frequency'] . ")" : ""; ?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold">Repayment Period and Frequency</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" name="<?= (isset($application['repayment_period'])) ? $application['repayment_period'] : "vrepayment_period"; ?>" class="form-control" placeholder="Repayment Period" value="<?= (isset($application['repayment_period'])) ? $application['repayment_period'] . " (" . $application['repayment_frequency'] . ")" : ""; ?>" readonly>
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
                                                                    </div>
                                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="viewSummernote">Details</label>
                                                                                <div class="col-md-12">
                                                                                    <textarea name="vsecurity_info" id="viewSummernote" placeholder="Security Item Details" class="form-control" readonly></textarea>
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
                                                                                <input type="text" name="vrelation_name" id="relation_name" class="form-control" placeholder="Full Name" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="relation_relationship">Relationship</label>
                                                                                <input type="text" name="vrelation_relationship" id="relation_relationship" class="form-control" placeholder="Relationship" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="relation_occupation">Occupation</label>
                                                                                <input type="text" name="vrelation_occupation" id="relation_occupation" class="form-control" placeholder="Occupation" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label col-12 fw-bold" for="relation_contact">Contact 1</label>
                                                                                <input type="tel" name="vrelation_contact" id="relation_contact" class="form-control" placeholder="Contact 1" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="relation_alt_contact">Contact 2</label>
                                                                                <input type="tel" name="vrelation_alt_contact" id="relation_alt_contact" class="form-control" placeholder="Contact 2" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="relation_email">Email</label>
                                                                                <input type="email" name="vrelation_email" id="relation_email" class="form-control" placeholder="Email" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="vrelation_address">Address</label>
                                                                                <div class="col-md-12">
                                                                                    <textarea name="vrelation_address" id="vrelation_address" placeholder="Address" class="form-control" readonly></textarea>
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
                                                                                <input type="text" name="vrelation_name2" id="relation_name2" class="form-control" placeholder="Full Name" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="relation2">Relationship</label>
                                                                                <input type="text" name="vrelation_relationship2" id="relation_relationship" class="form-control" placeholder="Relationship" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="relation_occupation2">Occupation</label>
                                                                                <input type="text" name="vrelation_occupation2" id="relation_occupation2" class="form-control" placeholder="Occupation" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="relation_contact2">Contact 1</label>
                                                                                <input type="tel" name="vrelation_contact2" id="relation_contact2" class="form-control" placeholder="Contact 1" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="relation_alt_contact2">Contact 2</label>
                                                                                <input type="tel" name="vrelation_alt_contact2" id="relation_alt_contact2" class="form-control" placeholder="Contact 2" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="relation_email2">Email</label>
                                                                                <input type="email" name="vrelation_email2" id="relation_email2" class="form-control" placeholder="Email" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold" for="vrelation_address2">Address</label>
                                                                                <div class="col-md-12">
                                                                                    <textarea name="vrelation_address2" id="vrelation_address2" placeholder="Address" class="form-control" readonly></textarea>
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
                                                                                        <input type="text" name="vincome_total" class="form-control amount" id="total" placeholder="Total" readonly>
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
                        <!-- applicable charges -->
                        <div class="tab-pane fade" id="charges-tab" role="tabpanel" aria-labelledby="charges-tab" tabindex="0">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                    <div class="h5 fw-semibold mb-0"> Charges Details</div>
                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Deduct Charges From</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="reduct_charges" id="reduct_charges" class="form-control" placeholder="Deduct Charges From" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Total Charges</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="total_charges" id="total_charges" class="form-control" placeholder="Total Charges" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Total Paid</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="total_paid" id="total_paid" class="form-control" placeholder="Total Paid" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Total Balance</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="total_balance" id="total_balance" class="form-control" placeholder="Total Balance" readonly>
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
                                                    <div class="h5 fw-semibold mb-0"> Product Applicable Charges</div>
                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="applicationCharges" class="row">

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
                                                    <div class="h5 fw-semibold mb-0"> Payment History</div>
                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table id="chargesPayments" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                                <thead class="">
                                                    <tr>
                                                        <th><input type="checkbox" name="" id="check-all"></th>
                                                        <th>S.No</th>
                                                        <th>Ledger</th>
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
                        </div>
                        <!-- amortization schedule -->
                        <div class="tab-pane fade" id="amortizer-tab" role="tabpanel" aria-labelledby="amortizer-tab" tabindex="0">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                    <div class="h5 fw-semibold mb-0"> Amortization Details</div>
                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" readonly id="applicant_interest_period" />
                                            <div class="row">
                                                <div class="col-md-4 mb-3" id="totalPrincipal">
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
                                                        <lable for="principal_receivable" class="control-label fw-bold">Principal Receivable</lable>
                                                        <input type="text" name="principal_receivable" id="principal_receivable" class="form-control" placeholder="Principal Receivable" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="reductCharges" style="display: none;">
                                                    <div class="form-group">
                                                        <lable for="ledger_id" class="control-label fw-bold">Deduct Charges</lable>
                                                        <select name="reduct_ledger" id="ledger_id" class="form-control select2bs4 ledger_id">
                                                        </select>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <!-- row 2 -->
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-group">
                                                        <lable for="computed_installment" class="control-label fw-bold">Computed Installment [<?= $settings['currency']; ?>]</lable>
                                                        <input type="text" name="computed_installment" id="computed_installment" class="form-control" placeholder="Computed Installment" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <lable for="computed_interest" class="control-label fw-bold">Computed Interest [<?= $settings['currency']; ?>]</lable>
                                                        <input type="text" name="computed_interest" id="computed_interest" class="form-control" placeholder="Interest" readonly>
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
                                                <!-- row 3 -->
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-group">
                                                        <lable for="actual_installment" class="control-label fw-bold">Actual Installment [<?= $settings['currency']; ?>]</lable>
                                                        <input type="text" name="actual_installment" id="actual_installment" class="form-control" placeholder="Actual Installment" readonly>
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
                                                        <lable for="actual_repayment" class="control-label fw-bold">Actual Repayment [<?= $settings['currency']; ?>]</lable>
                                                        <input type="text" name="actual_repayment" id="actual_repayment" class="form-control" placeholder="Actual Repayment" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <!-- row 4 -->
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <lable for="principal_installment" class="control-label fw-bold">Principal Installment [<?= $settings['currency']; ?>]</lable>
                                                        <input type="text" name="principal_installment" id="principal_installment" class="form-control" placeholder="Principal Installment" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <lable for="interest_installment" class="control-label fw-bold">Interest Installment [<?= $settings['currency']; ?>]</lable>
                                                        <input type="text" name="interest_installment" id="interest_installment" class="form-control" placeholder="Interest Installment" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <lable for="installments_num" class="control-label fw-bold">Installments N<u>o</u></lable>
                                                        <input type="text" name="installments_num" id="installments_num" class="form-control" placeholder="Total Installments" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Disbursement Date</label>
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                <input type="text" name="date_disbursed" id="date_disbursed2" class="form-control" value="<?= date('Y-m-d') ?>" placeholder="Date Disbursed" readonly>
                                                            </div>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <div class="d-sm-flex d-block align-items-center justify-content-between">
                                                    <div class="h5 fw-semibold mb-0"> Amortization Schedule</div>
                                                    <div class="d-flex float-right mt-sm-0 mt-2 align-items-center">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" id="showRepaymentPlan">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- applicant loan history -->
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
                        <!-- appraisal remarks -->
                        <div class="tab-pane fade" id="remarks-tab" role="tabpanel" aria-labelledby="remarks-tab" tabindex="0">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h4 class="h5 fw-semibold mb-0"> Remarks Details</h4>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Add Remarks button -->
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-12">
                                                    <div class="float-end">
                                                        <button class="nav-link text-primary" onclick="add_applicationRemarks('<?= $application['id'] ?>')" id="applicationRemarksBtn" title="Add Action" style="display:<?= (strtolower($application['status']) == "processing") ? "" : "none"; ?>">
                                                            <i class="fas fa-comments"></i> Add Action
                                                        </button><br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="remarksCard">

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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">
                                            Application Date
                                        </label>
                                        <div class="col-md-12">
                                            <input name="application_date" placeholder="Application Date" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Responsible Officer</label>
                                        <div class="col-md-12">
                                            <input name="vloan_officer" placeholder="Responsible officer" class="form-control" type="text" readonly>
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

<!-- add\edit model -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <ul class="nav nav-tabs justify-content-center mb-5 tab-style-3" id="myTab2" role="tablist">
                    <!--  step-1 tab active -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active step-1 py-1 disabled" id="step1-tab" data-bs-toggle="tab" data-bs-target="#step1-tab-pane" type="button" role="tab" aria-controls="step1-tab-pane" aria-selected="true">Terms</button>
                    </li>
                    <!-- step-2 tab -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link step-2 py-1 disabled" id="step2-tab" data-bs-toggle="tab" data-bs-target="#step2-tab-pane" type="button" role="tab" aria-controls="step2-tab-pane" aria-selected="false">Security</button>
                    </li>
                    <!-- step-3 tab -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link step-3 py-1 disabled" id="step3-tab" data-bs-toggle="tab" data-bs-target="#step3-tab-pane" type="button" role="tab" aria-controls="step3-tab-pane" aria-selected="false">Income</button>
                    </li>
                    <!-- step-4 tab -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link step-4 py-1 disabled" id="step4-tab" data-bs-toggle="tab" data-bs-target="#step4-tab-pane" type="button" role="tab" aria-controls="step4-tab-pane" aria-selected="false">Others</button>
                    </li>
                </ul>
                <form id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" name="id" readonly />
                    <input type="hidden" name="application_id" readonly />
                    <input type="hidden" name="applicant_product_id" readonly />
                    <input type="hidden" name="applicant_loan_frequency" readonly />
                    <input type="hidden" name="applicant_loan_period" readonly />
                    <input type="hidden" name="applicant_repayment_period" readonly />
                    <input type="hidden" name="applicant_repayment_frequency" readonly />
                    <input type="hidden" name="applicant_interest_rate" readonly />
                    <input type="hidden" name="applicant_interest_period" readonly />
                    <input type="hidden" name="mode" readonly />
                    <input type="hidden" name="step_no" readonly />
                    <div class="form-body">
                        <!-- import applications -->
                        <div id="importRow" style="display: none;">
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Branch Name <span class="text-danger">*</span< /label>
                                                    <select id="branchID" name="branch_id" class="form-control select2 branch_id">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12" for="loan_product_id">Loan Product</label>
                                        <div class="col-md-12">
                                            <select name="loan_product_id" id="product_id" class="form-control select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label> Upload Application(s)
                                                <span class="text-white"> ( <span class="text-danger"> CSV Format </span> ) </span>
                                            </label>
                                            <input type="file" name="file" class="form-control" accept=".csv">
                                            <span class="help-block text-danger"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- fill application form -->
                        <div id="formRow">
                            <!-- steps tab contents -->
                            <div class="tab-content" id="myTabContent1">
                                <!--  step 1 content -->
                                <div class="tab-pane fade show active text-muted" id="step1-tab-pane" role="tabpanel" aria-labelledby="step1-tab" tabindex="0">
                                    <!-- CLIENT BIO -->
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <div class="col-md-3 text-center">
                                            <label class="control-label fw-bold col-md-12">Profile Photo</label>
                                            <div class="form-group" id="photo-preview">
                                                <div class="col-md-12">
                                                    (No photo)
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="application_date" class="control-label fw-bold col-sm-12">
                                                            Application Date <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="col-sm-12">
                                                            <div class="input-group">
                                                                <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                                <input type="text" name="application_date" id="application_date" class="form-control" value="<?= date('Y-m-d'); ?>" placeholder="Application Date" readonly>
                                                            </div>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12" for="client_id">Client Name <span class="text-danger">*</span></label>
                                                        <div class="col-md-12">
                                                            <select name="client_id" id="client_id" class="form-control select2bs4 client_id">
                                                            </select>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12" for="account_no">Account No. <span class="text-danger">*</span></label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="account_no" id="account_no" class="form-control" placeholder="Account No." readonly>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12" for="mobile">Contact</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Contact" readonly>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12" for="account_bal">Account Balance</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="account_bal" id="account_bal" class="form-control" placeholder="Account Balance" readonly>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12" for="shares_bal">Shares Balance</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="shares_bal" id="shares_bal" class="form-control" placeholder="Shares Balance" readonly>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12" for="loan_bal">Loan Balance</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="loan_bal" id="loan_bal" class="form-control" placeholder="Loan Balance" readonly>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <label class="control-label fw-bold col-md-12">Applicant ID(Front)</label>
                                            <div class="form-group" id="id-preview">
                                                <div class="col-md-12">
                                                    (No photo)
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- LOAN TERMS -->
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12" for="product_id">Loan Product <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select name="product_id" id="product_id" class="form-control select2bs4">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12" for="principal">Loan Principal [<?= $settings['currency']; ?>] <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input name="principal" id="principal" placeholder="Loan Principal [<?= $settings['currency']; ?>]" class="form-control amount" type="text" onkeyup="total_applicationCharges(this.value)" min="0">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label fw-bold col-md-12">Interest Rate(%) <span class="text-danger">*</span></label>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <input type="text" name="interest_rate" id="interest_rate" class="form-control" placeholder="Interest Rate" readonly>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <select class="form-control select2bs4" name="interest_period" id="interest_period" style="width: 100%;">
                                                        <option value="">--Select---</option>
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12" for="interest_type">Interest Method <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="text" name="interest_type" id="interest_type" class="form-control" placeholder="Interest Method" readonly>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label fw-bold col-md-12">Loan Period and Frequency <span class="text-danger">*</span></label>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <input type="text" name="loan_period" id="loan_period" class="form-control" placeholder="Loan Period">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <select class="form-control select2bs4" name="loan_frequency" id="loan_frequency" style="width: 100%;">
                                                        <option value="">--Select---</option>
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label fw-bold col-md-12" for="repayment_period">Repayment Period and Frequency<span class="text-danger">*</span></label>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <input type="text" name="repayment_period" id="repayment_period" class="form-control" placeholder="Repayment Period">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <select class="form-control select2bs4" name="repayment_freq" id="repayments" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12" for="purpose">Loan Purpose <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <textarea name="purpose" id="purpose" placeholder="Loan Purpose" class="form-control"></textarea>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- charges -->
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Total Charges</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="total_charges" id="total_charges" class="form-control" placeholder="Total Charges" readonly>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Deduct Charges <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select name="reduct_charges" id="reduct_charges" class="form-control select2bs4">
                                                        <option value="Savings">From Savings</option>
                                                        <option value="Principal">From Principal</option>
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="fw-semibold text-primary mb-3">Product Applicable Charges</p>
                                    <div class="row gx-3 gy-2 align-items-center mt-0" id="productCharges">
                                    </div>
                                </div>
                                <!-- step-2 content -->
                                <div class="tab-pane fade text-muted" id="step2-tab-pane" role="tabpanel" aria-labelledby="step2-tab" tabindex="0">
                                    <!-- LOAN SECURITY -->
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <p class="col-md-12">Loan Security</p>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12" for="security_item">Security Item <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="text" name="security_item" id="security_item" class="form-control" placeholder="Security Item">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12" for="est_value">Estimated Value [<?= $settings['currency']; ?>] <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="text" name="est_value" id="est_value" class="form-control amount" placeholder="Estimated Value [<?= $settings['currency']; ?>]" min="0">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-sm-12">
                                                    Collateral Files (<span class="text-danger">Images/PDF</span>)
                                                </label>
                                                <div class="col-sm-12">
                                                    <input type="file" name="collateral[]" class="form-control" multiple>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12" for="security_info">Details <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <textarea name="security_info" class="form-control" placeholder="Details"></textarea>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- REFEREES  -->
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <p class="col-md-12 fw-bold">Guarantors</p>
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation_name">Full Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="relation_name" id="relation_name" class="form-control" placeholder="Full Name">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation">Relationship <span class="text-danger">*</span></label>
                                                        <select class="form-control select2bs4" name="relation_relationship" id="relationships" style="width: 100%;">
                                                        </select>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation_occupation">Occupation <span class="text-danger">*</span></label>
                                                        <select class="form-control select2bs4 occupation" name="relation_occupation" id="relation_occupation" style="width: 100%;">
                                                        </select>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation_contact" class="control-label col-md-12">Contact <span class="text-danger">*</span></label>
                                                        <div class="col-md-12">
                                                            <input type="tel" name="relation_contact" id="relation_contact" class="form-control phone-input" placeholder="Contact 1">
                                                            <input type="hidden" readonly id="relation_contact_full" name="relation_contact_full">
                                                            <input type="hidden" name="relation_contact_country_code" id="relation_contact_country_code" readonly>
                                                        </div>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation_alt_contact" class="control-label col-md-12">Contact 2</label>
                                                        <div class="col-md-12">
                                                            <input type="tel" name="relation_alt_contact" id="relation_alt_contact" class="form-control phone-input" placeholder="Contact 2">
                                                            <input type="hidden" readonly id="relation_alt_contact_full" name="relation_alt_contact_full">
                                                            <input type="hidden" name="relation_alt_contact_country_code" id="relation_alt_contact_country_code" readonly>
                                                        </div>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation_email">Email</label>
                                                        <input type="email" name="relation_email" id="relation_email" class="form-control" placeholder="Email">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="relation_address2">Address <span class="text-danger">*</span></label>
                                                        <textarea name="relation_address" id="relation_address2" class="form-control" placeholder="Address"></textarea>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation_name2">Full Name <!-- <span class="text-danger">*</span> --></label>
                                                        <input type="text" name="relation_name2" id="relation_name2" class="form-control" placeholder="Full Name">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation2">Relationship<!--  <span class="text-danger">*</span> --></label>
                                                        <select class="form-control select2bs4" name="relation_relationship2" id="relation2" style="width: 100%;">
                                                            <option value="">-- select --</option>
                                                            <?php
                                                            if (count($relations) > 0) :
                                                                foreach ($relations as $key => $value) : ?>
                                                                    <option value="<?= $key; ?>">
                                                                        <?= $value; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation_occupation2">Occupation<!--  <span class="text-danger">*</span> --></label>
                                                        <select class="form-control select2bs4 occupation" name="relation_occupation2" id="relation_occupation2" style="width: 100%;">
                                                        </select>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation_contact2" class="control-label col-md-12">Contact<!--  <span class="text-danger">*</span> --></label>
                                                        <div class="col-md-12">
                                                            <input type="tel" name="relation_contact2" id="relation_contact2" class="form-control phone-input" placeholder="Contact 1">
                                                            <input type="hidden" readonly id="relation_contact2_full" name="relation_contact2_full">
                                                            <input type="hidden" name="relation_contact2_country_code" id="relation_contact2_country_code" readonly>
                                                        </div>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation_alt_contact2" class="control-label col-md-12">Contact 2</label>
                                                        <div class="col-md-12">
                                                            <input type="tel" name="relation_alt_contact2" id="relation_alt_contact2" class="form-control phone-input" placeholder="Contact 2">
                                                            <input type="hidden" readonly id="relation_alt_contact2_full" name="relation_alt_contact2_full">
                                                            <input type="hidden" name="relation_alt_contact2_country_code" id="relation_alt_contact2_country_code" readonly>
                                                        </div>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation_email2">Email</label>
                                                        <input type="email" name="relation_email2" id="relation_email2" class="form-control" placeholder="Email">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="relation_address2">Address<!--  <span class="text-danger">*</span> --></label>
                                                        <textarea name="relation_address2" id="relation_address2" class="form-control" placeholder="Address"></textarea>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- step-3 content -->
                                <div class="tab-pane fade text-muted" id="step3-tab-pane" role="tabpanel" aria-labelledby="step3-tab" tabindex="0">
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <p class="col-md-12 text-bold">Client Financial Data</p>
                                        <!-- INCOME -->
                                        <div class="col-md-6">
                                            <div class="row gx-3 gy-2">
                                                <div class="col-md-12">
                                                    <div class="form-group text-center">
                                                        <p class="col-md-12">Income</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="salary" class="col-md-12 form-label">Net Salary</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="net_salary" class="form-control amount" id="salary" placeholder="Net Salary" min="0" onkeyup="totals()">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="farming" class="col-md-12 form-label">Farming</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="farming" class="form-control amount" id="farming" placeholder="Farming" min="0" onkeyup="totals()">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="business" class="col-md-12 form-label">Business</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="business" class="form-control amount" id="business" placeholder="Business" min="0" onkeyup="totals()">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="others" class="col-md-12 form-label">Others</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="others" class="form-control amount" id="others" placeholder="Others" min="0" onkeyup="totals()">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="" class="col-md-12 form-label"></label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="" class="form-control amount" id="" placeholder="" min="0" onkeyup="totals()" disabled>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="total" class="col-md-12 form-label">Total</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="income_total" class="form-control amount" id="total" placeholder="Total" min="0" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- EXPENSES -->
                                        <div class="col-md-6 border-left border-1">
                                            <div class="row gx-3 gy-2">
                                                <div class="col-md-12">
                                                    <div class="form-group text-center">
                                                        <p class="col-md-12">Expenses</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="rent" class="col-md-12 form-label">Rent</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="rent" class="form-control amount" id="rent" placeholder="Rent" min="0" onkeyup="totals()">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="education" class="col-md-12 form-label">Education</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="education" class="form-control amount" id="education" placeholder="Education" min="0" onkeyup="totals()">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="medical" class="col-md-12 form-label">Medical</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="medical" class="form-control amount" id="medical" placeholder="Medical" min="0" onkeyup="totals()">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="transport" class="col-md-12 form-label">Transport</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="transport" class="form-control amount" id="transport" placeholder="Transport" min="0" onkeyup="totals()">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="exp_others" class="col-md-12 form-label">Others</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="exp_others" class="form-control amount" id="exp_others" placeholder="Others" min="0" onkeyup="totals()">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="exp_total" class="col-md-12 form-label">Total</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="exp_total" class="form-control amount" id="exp_total" placeholder="Total" min="0" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- FINANCIAL STATUS -->
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="difference" class="col-md-12 form-label">Difference</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" name="difference" class="form-control amount" id="difference" placeholder="Difference" min="0" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="dif_status" class="col-md-12 form-label">Difference
                                                        Status</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="dif_status" placeholder="Difference Status" id="dif_status">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- FINANCIAL PROOF -->
                                        <p class="col-md-12">
                                            <i><b>NOTE:</b> Upload receipts/invoices/slips where applicable</i>
                                        </p>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="income">
                                                    Income Files (<span class="text-danger">Images/PDF</span>)
                                                </label>
                                                <div class="col-md-12">
                                                    <input type="file" name="income[]" class="form-control" multiple>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="expense">
                                                    Expenses Files (<span class="text-danger">Images/PDF</span>)
                                                </label>
                                                <input type="file" name="expense[]" class="form-control" multiple>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- step-4 content -->
                                <div class="tab-pane fade text-muted" id="step4-tab-pane" role="tabpanel" aria-labelledby="step4-tab" tabindex="0">
                                    <!-- CLIENT OTHER ACCOUNTS -->
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <p class="col-md-12">Client's Accounts In Other Financial Institutions</p>
                                        <label class="control-label fw-bold col-4" for="institution">Institution</label>
                                        <label class="control-label fw-bold col-4" for="branch">Branch</label>
                                        <label class="control-label fw-bold col-4" for="state">Account Type</label>
                                    </div>
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input type="text" name="institute_name" id="institution" class="form-control" placeholder="Institution">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input type="text" name="institute_branch" id="branch" class="form-control" placeholder="Branch">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <select name="account_type" class="form-control select2bs4">
                                                        <option value="">-- select --</option>
                                                        <option value="Credit">Credit</option>
                                                        <option value="Debit">Debit</option>
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input type="text" name="institute_name2" id="institution2" class="form-control" placeholder="Institution">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input type="text" name="institute_branch2" id="branch2" class="form-control" placeholder="Branch">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <select name="account_type2" id="state2" class="form-control select2bs4">
                                                        <option value="">-- select --</option>
                                                        <option value="Credit">Credit</option>
                                                        <option value="Debit">Debit</option>
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- CLIENT OTHER LOANS -->
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <p class="col-md-12">Client's Loans In Other Financial Institutions</p>
                                        <label class="control-label fw-bold col-3" for="amt_advance">Amount Advance [<?= $settings['currency']; ?>]</label>
                                        <label class="control-label fw-bold col-3" for="date_advance">Date Advance</label>
                                        <label class="control-label fw-bold col-3" for="loan_duration">Loan Duration</label>
                                        <label class="control-label fw-bold col-3" for="amt_outstanding">Outstanding Amount [<?= $settings['currency']; ?>]</label>
                                    </div>
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input type="text" name="amt_advance" id="amt_advance" class="form-control amount" placeholder="Amount Advance [<?= $settings['currency']; ?>]" min="0">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                        <input type="date" name="date_advance" id="date_advance" class="form-control" placeholder="Date Advance">
                                                    </div>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input type="number" name="loan_duration" id="loan_duration" class="form-control" placeholder="Loan Duration" min="0">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input type="text" name="amt_outstanding" id="amt_outstanding" class="form-control amount" placeholder="Outstanding Amount [<?= $settings['currency']; ?>]" min="0">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-3 gy-2 align-items-center mt-0">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input type="text" name="amt_advance2" id="amt_advance2" class="form-control amount" placeholder="Amount Advance [<?= $settings['currency']; ?>]" min="0">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                        <input type="date" name="date_advance2" id="date_advance2" class="form-control" placeholder="Date Advance">
                                                    </div>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input type="number" name="loan_duration2" id="loan_duration2" class="form-control" placeholder="Loan Duration" min="0">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <input type="text" name="amt_outstanding2" id="amt_outstanding2" class="form-control amount" placeholder="Outstanding Amount [<?= $settings['currency']; ?>]" min="0">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- Signiture -->
                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                <div class="col-md-12 float-right">
                                    <label class="control-label fw-bold col-md-12">Client Signature</label>
                                    <div class="form-group" id="signature-preview">
                                        <div class="col-md-12">
                                            (No Sign)
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input name="staff_id" placeholder="Loan Officer" class="form-control" value="<?= session()->get('id'); ?>" type="hidden">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- previous step -->
                <button type="button" id="btnBack" onclick="submitStep('back')" class="btn btn-outline-warning float-start" style="display: none;">Back</button>
                <!-- next step -->
                <button type="button" id="btnNext" onclick="submitStep()" class="btn btn-outline-info">Next</button>
                <!-- submit form -->
                <button type="button" id="btnSav" onclick="save_application()" class="btn btn-outline-success" style="display: none;">Submit</button>
                <!-- close modal -->
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- file add model -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form">
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
<div class="modal fade" data-bs-backdrop="static" id="pay_modal_form">
    <div class="modal-dialog modal-lg">
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
                    <input type="hidden" readonly name="entry_menu" id="entry_menu" />
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
                                        <input id="contact" name="contact" placeholder="Contact" class="form-control phone-input" type="tel">
                                        <input type="hidden" readonly id="contact_full" name="contact_full">
                                        <input type="hidden" name="contact_country_code" id="contact_country_code" readonly>
                                    </div>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Charge [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input type="text" name="charge" class="form-control amount" placeholder="Charge [<?= $settings['currency']; ?>]" min="0" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Amount to be Paid [<?= $settings['currency']; ?>]</label>
                                    <div class="col-md-12">
                                        <input type="text" id="amount" name="amount" class="form-control amount" placeholder="Transaction Amount" min="0">
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
                <!-- <div class="close">
                    <btn type="button" class="btn btn-md btn-danger">
                        <i class="fas fa-file-pdf text-light"></i>
                    </btn>
                </div> -->
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-info" id="modal-title"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                    <input type="hidden" readonly id="applicant_interest_period">
                    <input type="hidden" readonly id="mode">
                    <div class="form-body">
                        <!-- status row -->
                        <div class="row gx-3 gy-2 align-items-center mt-0" id="statusRow">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Officer Level</label>
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable for="total_charges" class="control-label fw-bold">Total Charges</lable>
                                                <input type="text" name="total_charges" id="total_charges" class="form-control amount" placeholder="Total Charges" readonly>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Deduct Charges <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select name="reduct_charges" id="reductcharges" class="form-control select2bs4">
                                                        <option value="">-- select --</option>
                                                        <option value="Savings">From Savings</option>
                                                        <option value="Principal">From Principal</option>
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" id="principalRecievable" style="display: none;">
                                    <div class="form-group">
                                        <lable for="principal_receivable" class="control-label fw-bold">Principal Recievable</lable>
                                        <input type="text" name="principal_receivable" id="principal_receivable" class="form-control" placeholder="Principal Recievable" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4" id="reduct-Charges" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Savings Product <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select name="product_id" id="productId" class="form-control select2bs4">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable for="particular_id" class="control-label fw-bold">Reduction Particular <span class="text-danger">*</span></lable>
                                                <select name="reduct_particular_id" id="particularId" class="form-control select2bs4 particular_id">
                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Principal Particular <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="particular_id" id="disparticular_id" class="form-control select2bs4" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Interest Particular <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="interest_particular_id" id="interest_particular_id" class="form-control select2bs4" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Disbursement By <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="disbursed_by" id="disbursed_by" class="form-control select2bs4" style="width: 100%;">
                                                <!-- <option value="">-- select --</option> -->
                                                <option value="Deposited into Client Account">Deposited into Client Account</option>
                                                <option value="Client Mobile Money Account">Client Mobile Money Account</option>
                                                <option value="Client Bank Account">Client Bank Account</option>
                                                <option value="Paid in Cash">Paid in Cash</option>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Payment Method <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="payment_id" id="payment_id" class="form-control select2bs4" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Date Disbursed <span class="text-danger">*</span></label>
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
                                <label for="showPlan">View Amortization Schedule</label>
                            </div>
                            <div class="row gx-3 gy-2 align-items-center mt-0" id="showRepaymentPlan" style="display: none;">
                                <div class="col-md-12">
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
                        <!-- remarks -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold">Remarks <span class="text-danger">*</span></label>
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
                                    <?= $settings['business_contact']; ?> <?= isset($settings['business_alt_contact']) ? " | " . $settings['business_alt_contact'] : ""; ?>
                                </p>
                                <p class="fw-semibold mb-2 fs-12">
                                    <?= $settings['business_email']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-start">
                            <p class="fw-bold text-default terms-heading"><u>YOUR REF: <?= $application['account_no']; ?></u></p>
                            <div class="mb-4">
                                <p class="fw-semibold mb-2 fs-16">
                                    <?= (strtolower($application['gender']) == 'male') ? 'Mr. ' : 'Mrs. '; ?><?= $application['name']; ?>
                                </p>
                                <p class="mb-2 fs-14">
                                    <i class="fas fa-home"></i>&nbsp;<?= $application['residence']; ?>
                                </p>
                                <p class="mb-2 fs-14">
                                    <i class="fas fa-phone"></i>&nbsp;<?= $application['mobile']; ?> <?= isset($application['alternate_no']) ? " | " . $application['alternate_no'] : ""; ?>
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
                            <b>4. Frequency of payment:</b> <?= $application['repayment_frequency']; ?>.
                        </p>
                        <p class="mb-2">
                            <b>5. Total number of loan installments:</b> <span id="installments"></span>.
                        </p>
                        <p class="mb-2">
                            <span class="fw-semibold">6. Interest Rate:</span> <?= $application['interest_rate'] . '% per ' . $application['interest_period']; ?> calculated on a <?= $application['interest_type']; ?> basis.
                        </p>
                        <p class="mb-2">
                            <b>7. Total amount to repay (principal + interest):</b> <?= $settings['currency']; ?> <span class="total-repayment"></span>.
                        </p>
                        <?php if (count($charges) > 0 && count($loanCharges) > 0) : ?>
                            <p class="mb-2">
                                <b>8. Total Loan charges:</b> <?= $settings['currency'] . ' ' . $application['total_charges'] . '= (' . convert_number($application['total_charges']) . ')'; ?> including the following;
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
                            <b>9. Actual Amount received less charges (1–8)</b> is <?= $settings['currency'] . ' ' . number_format(((float)$application['principal'] - (float)$application['total_charges'])) . '= (' . convert_number(((float)$application['principal'] - (float)$application['total_charges'])) . ')'; ?>.
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
                                    1. <?= $application['relation_name'] . ' (' . $application['relation_relationship'] . ')'; ?>, <?= $application['relation_occupation']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-phone"></i>&nbsp;<?= $application['relation_contact'] . ' | ' . $application['relation_alt_contact']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-envelope"></i>&nbsp;<?= $application['relation_email']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-home"></i>&nbsp;<?= $application['relation_address']; ?>
                                </p>
                            </div>
                            <div class="col-6">
                                <p class="mb-2">
                                    2. <?= $application['relation_name2'] . ' (' . $application['relation_relationship2'] . ')'; ?>, <?= $application['relation_occupation2']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-phone"></i>&nbsp;<?= $application['relation_contact2'] . ' | ' . $application['relation_alt_contact2']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-envelope"></i>&nbsp;<?= $application['relation_email2']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-home"></i>&nbsp;<?= $application['relation_address2']; ?>
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
                                    <?php if ($application['signature'] && file_exists('uploads/staffs/employees/signatures/' . $application['signature'])) : ?>
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
    var appID = '<?= isset($application) ? $application['id'] : 0; ?>';
    disID = '';
    var clientId = <?= $application['client_id'] ?>;
    var appStatus = '<?= isset($application) ? strtolower($application['status']) : 'pending'; ?>';
    var principal = Number(<?= $application['principal'] ?>);
    var level = '<?= $application['level'] ?>';
    var userPosition = '<?= (strtolower($user['position'])); ?>';
    var officerID = '<?= ($user['staff_id']); ?>';
    var applicantProduct = <?= json_encode($application['applicantProduct']); ?>;
    var loanApplicationCharges = <?= json_encode(unserialize($application['overall_charges'])); ?>;
    var overallCharges = (loanApplicationCharges.ParticularID.length);
</script>
<script src="/assets/scripts/loans/index.js"></script>
<script src="/assets/scripts/main/auto-updates.js"></script>
<script src="/assets/scripts/loans/applications/view.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/transactions/index.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>
<script src="/assets/scripts/main/phone.js"></script>

<?= $this->endSection() ?>