<?= $this->extend("layout/main"); ?>
<script type="text/javascript">
    var signature = $('#signature').signature({
        syncField: '#sigpad',
        syncFormat: 'PNG'
    });

    $('#disable').click(function(e) {
        e.preventDefault();
        var disable = $(this).text() === 'Disable';
        $(this).text(disable ? 'Enable' : 'Disable');
        signature.signature(disable ? 'disable' : 'enable');
    });

    $('#clear').click(function(e) {
        e.preventDefault();
        signature.signature('clear');
        $("#sigpad").val('');
    });
</script>
<?= $this->section("content"); ?>
<div class="container-fluid">
    <div class="card profile-overview profile-overview-wide">
        <div class="card-body d-flex">
            <div class="clearfix">
                <div class="d-inline-block position-relative me-sm-4 me-3 mb-3 mb-lg-0">
                    <?php if (file_exists('uploads/clients/passports/' . $client['photo']) && $client['photo']) : ?>
                        <img src="/uploads/clients/passports/<?= $client['photo']; ?>" class="rounded-4 profile-avatar" alt="Photo" />
                    <?php else : ?>
                        <img src="/assets/dist/img/nophoto.jpg" class="rounded-4 profile-avatar" alt="Photo" />
                    <?php endif; ?>
                    <span class="fa fa-circle border border-3 border-white text-<?= (strtolower($client['access_status']) == 'active') ? 'success' : 'danger'; ?> position-absolute bottom-0 end-0 rounded-circle"></span>
                </div>
            </div>
            <div class="clearfix d-xl-flex flex-grow-1">
                <div class="clearfix pe-md-5">
                    <h3 class="fw-semibold mb-1"><?= (isset($client['title'])) ? $client['title'] . ' ' . $client['name'] : $client['name']; ?></h3>
                    <ul class="d-flex flex-wrap fs-6 align-items-center">
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-la-code-branch me-1 fs-18"></i><?= $client['branch_name']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-genderless me-1 fs-18"></i><?= $client['gender']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-tag me-1 fs-18"></i><?= $client['account_type']; ?>
                        </li>
                    </ul>

                    <ul class="d-flex flex-wrap fs-6 align-items-center mt-2">
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-phone me-1 fs-18"></i><?= ($client['alternate_no']) ? $client['mobile'] . ', ' . $client['alternate_no'] : $client['mobile']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-envelope me-1 fs-18"></i><?= $client['email']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-map-marker me-1 fs-18"></i><?= $client['residence']; ?>
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
                                <h3 class="mb-0 fw-semibold lh-1"><?= $client['account_no']; ?></h3>
                                <span class="fs-14">Account No.</span>
                            </div>
                        </div>
                        <div class="border outline-dashed rounded p-2 d-flex align-items-center me-3 mt-3">
                            <div class="avatar avatar-md bg-primary-light text-primary rounded d-flex align-items-center justify-content-center">
                                <svg width="18" height="18" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.8496 4.25031V6.67031" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.8496 17.7601V19.7841" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.8496 14.3246V9.5036" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M18.7021 20C20.5242 20 22 18.5426 22 16.7431V14.1506C20.7943 14.1506 19.8233 13.1917 19.8233 12.001C19.8233 10.8104 20.7943 9.85039 22 9.85039L21.999 7.25686C21.999 5.45745 20.5221 4 18.7011 4H5.29892C3.47789 4 2.00104 5.45745 2.00104 7.25686L2 9.93485C3.20567 9.93485 4.17668 10.8104 4.17668 12.001C4.17668 13.1917 3.20567 14.1506 2 14.1506V16.7431C2 18.5426 3.4758 20 5.29787 20H18.7021Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="clearfix ms-2">
                                <h3 class="mb-0 fw-semibold lh-1"><?= number_format($client['sharesBalance']); ?></h3>
                                <span class="fs-14">Shares Balance</span>
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
                                <h3 class="mb-0 fw-semibold lh-1"><?= number_format($client['account_balance']); ?></h3>
                                <span class="fs-14">Savings Balance</span>
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
                                <h3 class="mb-0 fw-semibold lh-1"><?= number_format($disbursements['totalBalance']) ?></h3>
                                <span class="fs-14">Loan Balance</span>
                            </div>
                        </div>
                    </div>
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
                                <!-- savings option -->
                                <?php if ((unserialize($user['permissions']) == 'all') || (in_array('create_savingsTransactions', unserialize($user['permissions'])))): ?>
                                    <a href="javascript:void(0);" id="savingsBtn" class="dropdown-item" onclick="client_savings(<?= $client['id'] ?>)" title="New Savings Transaction"><i class="fas fa-bank text-info"></i> New Savings</a>
                                    <div class="dropdown-divider"></div>
                                <?php endif; ?>
                                <!-- loans options -->
                                <?php if (($disbursements['disbursements'] > 0) && ($disbursements['totalBalance'] > 0) && ((unserialize($user['permissions']) == 'all') || (in_array('create_loansRepayments', unserialize($user['permissions']))))): ?>
                                    <a href="javascript:void(0);" id="repaymentsBtn" class="dropdown-item" onclick="add_disbursementPayment(<?= $client['id'] ?>)" title="Installment Repayment Transaction"><i class="fa fa-money-bill-trend-up text-primary"></i> Pay Installment</a>
                                <?php elseif ((unserialize($user['permissions']) == 'all') || (in_array('create_loansApplications', unserialize($user['permissions'])))): ?>
                                    <a href="javascript:void(0);" id="repaymentsBtn" class="dropdown-item" onclick="add_application()" title="New Loan Application"><i class="fas fa-folder text-info"></i> New Applications</a>
                                <?php endif; ?>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" id="editBtn" class="dropdown-item" onclick="edit_client(<?= $client['id'] ?>)" title="Edit <?= ucwords($client['name']) ?>"><i class="fa fa-edit text-info"></i> Edit <?= ucwords($client['name']) ?></a>
                                <div class="dropdown-divider"></div>
                                <?php if (strtolower($client['access_status']) == 'active'): ?>
                                    <a href="javascript:void(0)" onclick="edit_clientStatus(<?= $client['id'] ?>, '<?= ucwords($client['name']) ?>')" title="de-activate <?= ucwords($client['name']) ?>" class="dropdown-item">
                                        <i class="fas fa-user-slash text-secondary"></i> De-activate <?= ucwords($client['name']) ?>
                                    </a>';
                                    <div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" class="dropdown-item" onclick="create_password(<?= $client['id'] ?>, '<?= $newPassword ?>')" title="Generate Password"><i class="fas fa-lock text-warning"></i> New Password</a>
                                <?php else: ?>
                                    <div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="edit_clientStatus(<?= $client['id'] ?>, '<?= ucwords($client['name']) ?>')" title="activate client" class="dropdown-item">
                                        <i class="fas fa-user-check text-success"></i> Activate <?= ucwords($client['name']) ?>
                                    </a>
                                <?php endif; ?>
                                <?php if ((unserialize($user['permissions']) == 'all') || (in_array('delete_' . strtolower($menu) . 'Clients', unserialize($user['permissions']))) || (in_array('bulkDelete_' . strtolower($menu) . 'Clients', unserialize($user['permissions'])))): ?>
                                    <div class="dropdown-divider"></div>
                                    <!-- delete option -->
                                    <a href="javascript:void(0);" id="deleteBtn" class="dropdown-item" onclick="delete_client(<?= $client['id'] ?>, '<?= ucwords($client['name']) ?>')" title="Delete <?= ucwords($client['name']) ?>"><i class="fa fa-trash text-danger"></i> Delete <?= ucwords($client['name']) ?></a>
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
                        <a href="javascript:void(0)" id="kycBtn" class="nav-link py-3 border-3 text-dark active" data-bs-toggle="tab" data-bs-target="#kyc-tab" type="button" role="tab" aria-controls="kyc-tab" aria-selected="true">Profile</a>
                    </li>
                    <!-- <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="membershipBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#membership-tab" type="button" role="tab" aria-controls="membership-tab" aria-selected="true">Membership</a>
                    </li> -->
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="savingsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#savings-tab" type="button" role="tab" aria-controls="savings-tab" aria-selected="true">Tansactions</a>
                    </li>
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="applicationsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#applications-tab" type="button" role="tab" aria-controls="applications-tab" aria-selected="false">Applications</a>

                    </li>
                    <li class="nav-item ms-1" id="" role="presentation">
                        <a href="javascript:void(0)" id="disbursementsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#disbursements-tab" type="button" role="tab" aria-controls="disbursements-tab" aria-selected="false">Disbursements</a>
                    </li>
                </ul>
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
                            <!-- kyc -->
                            <div class="tab-pane fade show active" id="kyc-tab" role="tabpanel" aria-labelledby="kyc-tab" tabindex="0">
                                <form class="form-horizontal" autocomplete="off">
                                    <input type="hidden" readonly value="" name="id" />
                                    <div class="form-body">
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Account Number</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vaccount_no" class="form-control" placeholder="ccount Number" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Account Type</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vaccount_type" class="form-control" placeholder="Account Type" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
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
                                                    <label class="control-label fw-bold col-md-12">Occupation</label>
                                                    <div class="col-md-12">
                                                        <input name="voccupation" placeholder="Occupation" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
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
                                        <div class="row mb-3">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">
                                                                Registration Date
                                                            </label>
                                                            <div class="col-md-12">
                                                                <input name="view_reg_date" placeholder="Registration Date" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Responsible Officer</label>
                                                            <div class="col-md-12">
                                                                <input name="staff_name" placeholder="Responsible officer" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Created At</label>
                                                            <div class="col-md-12">
                                                                <input name="created_at" placeholder="Created At" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Last Updated At</label>
                                                            <div class="col-md-12">
                                                                <input name="updated_at" placeholder="Last Updated At" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <label class="control-label fw-bold col-md-12">Client Signature</label>
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
                            <!-- membership -->
                            <div class="tab-pane fade" id="membership-tab" role="tabpanel" aria-labelledby="membership-tab" tabindex="0">
                                <div class="card-header">
                                    <h5 class="card-title">Membership Transactions History</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="membership" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                                <thead class="">
                                                    <tr>
                                                        <th><input type="checkbox" name="" id="check-all"></th>
                                                        <th>S.No</th>
                                                        <th>Type</th>
                                                        <th>Payment</th>
                                                        <th>Amount [<?= $settings['currency']; ?>]</th>
                                                        <th>Ref ID</th>
                                                        <th>Date</th>
                                                        <th>Officer</th>
                                                        <th width="5%">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- savings -->
                            <div class="tab-pane fade" id="savings-tab" role="tabpanel" aria-labelledby="savings-tab" tabindex="0">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title"><?= ucwords($client['name']) ?> Transactions History</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="savings" class="table table-sm  table-hover text-nowrap" style="width:100%">
                                                    <thead class="">
                                                        <tr>
                                                            <th><input type="checkbox" name="" id="check-all"></th>
                                                            <th>S.No</th>
                                                            <th>Date</th>
                                                            <th>Type</th>
                                                            <th>Payment</th>
                                                            <th>Amount [<?= $settings['currency']; ?>]</th>
                                                            <th>Ref ID</th>
                                                            <th>Officer</th>
                                                            <th>Savings Bal.[<?= $settings['currency']; ?>]</th>
                                                            <th width="5%">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- applications -->
                            <div class="tab-pane fade" id="applications-tab" role="tabpanel" aria-labelledby="applications-tab" tabindex="0">
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
                            <!-- disbursements -->
                            <div class="tab-pane fade" id="disbursements-tab" role="disbursements-tab" aria-labelledby="all-tab3" tabindex="0">
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

<!-- edit client modal -->
<div class="modal fade" data-bs-backdrop="static" id="modal_form" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <!-- <div class="close">
                    <btn type="button" class="btn btn-sm btn-secondary mb-4" onclick="exportClientForm()" id="export">
                        <i class="fas fa-print text-light"></i>
                    </btn>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div> -->
            </div>
            <div class="modal-body form">
                <form id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">
                        Required fields are marked with an asterisk <span class="text-danger">(*)</span>.
                    </p>
                    <input type="hidden" readonly name="id" />
                    <input type="hidden" readonly name="mode" />
                    <div class="form-body">
                        <div id="formRow">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row  mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">
                                                    Registration Date <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                        <input type="date" name="reg_date" class="form-control getDatePicker" value="<?= date("Y-m-d"); ?>" placeholder="Registration Date">
                                                    </div>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Full Name <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input name="c_name" placeholder="Full Name" class="form-control" type="text">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Gender <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="gender" id="gender" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Nationality <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="nationality" id="nationality" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Marital Status <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="marital_status" id="maritalstatus" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Religion <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="religion" id="religion" style="width: 100%;" id="religion">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-sm-12" for="profileImage">PASSPORT PHOTO</label>
                                        <div class="col-sm-12">
                                            <div id="user-photo-preview"></div>
                                            <label id="upload-label" class="control-label fw-bold" for="profileImage"></label>
                                            <input type="file" name="photo" accept="image/*" required onchange="previewImageFile(event)" class="form-control" id="profileImage">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Phone Number <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-12">
                                            <input id="mobile" name="mobile" placeholder="Primary Phone Number" class="form-control phone-input" type="tel">
                                            <input type="hidden" readonly id="mobile_full" name="mobile_full">
                                            <input type="hidden" name="mobile_country_code" id="mobile_country_code" readonly>
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Alternate Phone Number
                                        </label>
                                        <div class="col-md-12">
                                            <input id="alternate_mobile" name="alternate_mobile" placeholder="Alternate Phone Number" class="form-control phone-input" type="tel">
                                            <input type="hidden" readonly id="alternate_mobile_full" name="alternate_mobile_full">
                                            <input type="hidden" name="alternate_mobile_country_code" id="alternate_mobile_country_code" readonly>
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Email Address</label>
                                        <div class="col-md-12">
                                            <input name="c_email" placeholder="example@mail.com" class="form-control" type="email">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Residence Address <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea name="residence" placeholder="Please give full details - plot, street name, area etc" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Closest Land Mark <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea name="closest_landmark" placeholder="Major Land Mark Feature nearby e.g school, church, mosque, etc" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Branch Name <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select id="branch_id" name="branch_id" class="form-control select2bs4 branch_id" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Savings Product(s) <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select id="savings_products" name="savings_products[]" class="form-control select2bs4 savings_products" style="width: 100%;" multiple>
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Occupation <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="occupation" id="occupation" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Date Of Birth <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                <input type="date" name="dob" class="form-control getDatePicker">
                                            </div>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-sm-12" for="id-imageFront">ID PHOTO(Front)</label>
                                        <div class="col-sm-12">
                                            <div id="id-preview-front"></div>
                                            <label id="id-label-front" class="control-label fw-bold" for="id-imageFront"></label>
                                            <input type="file" name="id_photo_front" accept="image/*" required onchange="previewIdImage(event, 'Front')" class="form-control" id="id-imageFront">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row mb-3">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">ID Type <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="id_type" id="idtypes" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">ID Number <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input name="id_number" placeholder="ID Number" class="form-control" type="text">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">ID Expiry Date</label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                        <input name="id_expiry" placeholder="ID Expiry Date" class="form-control getDatePicker" type="date">
                                                    </div>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-sm-12" for="id-imageBack">ID PHOTO(Back)</label>
                                        <div class="col-sm-12">
                                            <div id="id-preview-back"></div>
                                            <label id="id-label-back" class="control-label fw-bold" for="id-imageBack"></label>
                                            <input type="file" name="id_photo_back" accept="image/*" required onchange="previewIdImage(event, 'Back')" class="form-control" id="id-imageBack">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Job Location <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea name="job_location" placeholder="Please give full details - plot, street name, area etc" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Next of Kin Name <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input name="next_of_kin" placeholder="Next of Kin Name" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Next of kin Relationship <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="nok_relationship" id="relationships" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Next of Kin Address <span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea name="nok_address" placeholder="Please give full details - plot, street name, area etc" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="row mb-3">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Next of Kin Phone Number <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-12">
                                                    <input id="nok_phone" name="nok_phone" placeholder="Next of Kin Phone Number" class="form-control phone-input" type="tel">
                                                    <input type="hidden" readonly id="nok_phone_full" name="nok_phone_full">
                                                    <input type="hidden" name="nok_phone_country_code" id="nok_phone_country_code" readonly>
                                                </div>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Next of Kin Phone Number 2
                                                </label>
                                                <div class="col-md-12">
                                                    <input id="nok_alt_phone" name="nok_alt_phone" placeholder="Next of Kin Phone Number 2" class="form-control phone-input" type="tel">
                                                    <input type="hidden" readonly id="nok_alt_phone_full" name="nok_alt_phone_full">
                                                    <input type="hidden" name="nok_alt_phone_country_code" id="nok_alt_phone_country_code" readonly>
                                                </div>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Next of Kin Email
                                                </label>
                                                <div class="col-md-12 mb-3">
                                                    <input name="nok_email" placeholder="Next of Kin Email" class="form-control" type="text">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-sm-12" for="signature">E-Signature</label>
                                        <div class="col-sm-12">
                                            <div id="signature"></div>
                                            <div class="col-sm-12">
                                                <button class="btn btn-primary btn-sm" id="disable">Disable</button>
                                                <button class="btn btn-danger btn-sm" id="clear">Clear Signature</button>
                                                <textarea id="sigpad" name="signature_image" style="display: none"></textarea>
                                            </div>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-sm-12" for="signature">Signature</label>
                                        <div class="col-sm-12">
                                            <div id="signature-preview2"></div>
                                            <label id="sign-label2" class="control-label fw-bold" for="signature2"></label>
                                            <input type="file" name="signature" accept="image/*" required onchange="previewSignature(event)" class="form-control" id="signature2">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="passwordRow" style="display: none;">
                            <input type="hidden" readonly value="" name="password" />
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Name</label>
                                        <div class="col-md-12">
                                            <input type="text" name="name" class="form-control" placeholder="Name" readonly>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Mobile</label>
                                        <div class="col-md-12">
                                            <input type="tel" id="phone" name="phone" class="form-control phone-input" placeholder="Mobile" readonly>
                                            <input type="hidden" readonly id="phone_full" name="phone_full" readonly>
                                            <input type="hidden" name="phone_country_code" id="phone_country_code" readonly>
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Email</label>
                                        <div class="col-md-12">
                                            <input type="email" name="email" class="form-control" placeholder="Email" readonly>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Password</label>
                                        <div class="col-md-12">
                                            <input type="password" name="c_password" class="form-control" placeholder="Password" readonly autocomplete="off">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input name="staff_id" placeholder="responsible officer" class="form-control" type="hidden" value="<?= session()->get('id'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSav" onclick="save_client()" class="btn btn-outline-success">Submit</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- add\edit application model -->
<div class="modal fade" data-bs-backdrop="static" id="application_modalForm">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="application-modalTitle"></h6>
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
                <form id="applicationForm" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" name="id" readonly />
                    <input type="hidden" name="application_id" readonly />
                    <input type="hidden" name="applicant_product_id" readonly />
                    <input type="hidden" name="mode" readonly />
                    <input type="hidden" name="step_no" readonly />
                    <div class="form-body">
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
                                                <div class="col-md-12">
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
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12" for="client_id">Client Name <span class="text-danger">*</span></label>
                                                        <div class="col-md-12">
                                                            <select name="client_id" id="client_id" class="form-control select2bs4 client_id" readonly>
                                                            </select>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12" for="account_no">Account No. <span class="text-danger">*</span></label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="account_no" id="account_no" class="form-control" placeholder="Account No." readonly>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12" for="account_bal">Account Balance</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="account_bal" id="account_bal" class="form-control" placeholder="Account Balance" readonly>
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
                                                <label class="control-label fw-bold col-md-12" for="principal">Loan Principal [<?= $settings['currency']; ?>] <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input name="principal" id="principal" placeholder="Loan Principal [<?= $settings['currency']; ?>]" class="form-control amount" type="text" onkeyup="total_applicationCharges(this.value)" min="0">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
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
                                            <!-- <div class="form-group">
                                                <label class="control-label fw-bold col-md-12" for="interest_rate">Interest Rate(%)</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="interest_rate" id="interest_rate" class="form-control" placeholder="Interest Rate" readonly>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div> -->
                                            <div class="">
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
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12" for="repayment_period">Repayment Period <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="text" name="repayment_period" id="repayment_period" class="form-control" placeholder="Repayment Period">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12" for="repayment_freq">Repayment Frequency <span class="text-danger">*</span></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="repayment_freq" id="repayments" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                                <!-- <label class="control-label fw-bold col-md-12" for="repayment_freq">Repayment Freq.</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="repayment_freq" id="repayment_freq" class="form-control" placeholder="Repayment Freq." readonly>
                                                    <span class="help-block text-danger"></span>
                                                </div> -->
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
                                                        <option value="Principal">From Principal</option>
                                                        <option value="Savings">From Savings</option>
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
                                                        <label for="ref_name">Full Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="ref_name" id="ref_name" class="form-control" placeholder="Full Name">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation">Relationship <span class="text-danger">*</span></label>
                                                        <select class="form-control select2bs4" name="ref_relation" id="relationships" style="width: 100%;">
                                                        </select>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_job">Occupation <span class="text-danger">*</span></label>
                                                        <select class="form-control select2bs4 occupation" name="ref_job" id="ref_job" style="width: 100%;">
                                                        </select>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_contact" class="control-label col-md-12">Contact <span class="text-danger">*</span></label>
                                                        <div class="col-md-12">
                                                            <input type="tel" name="ref_contact" id="ref_contact" class="form-control phone-input" placeholder="Contact 1">
                                                            <input type="hidden" readonly id="ref_contact_full" name="ref_contact_full">
                                                            <input type="hidden" name="ref_contact_country_code" id="ref_contact_country_code" readonly>
                                                        </div>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_alt_contact" class="control-label col-md-12">Contact 2</label>
                                                        <div class="col-md-12">
                                                            <input type="tel" name="ref_alt_contact" id="ref_alt_contact" class="form-control phone-input" placeholder="Contact 2">
                                                            <input type="hidden" readonly id="ref_alt_contact_full" name="ref_alt_contact_full">
                                                            <input type="hidden" name="ref_alt_contact_country_code" id="ref_alt_contact_country_code" readonly>
                                                        </div>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_email">Email</label>
                                                        <input type="email" name="ref_email" id="ref_email" class="form-control" placeholder="Email">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="ref_address2">Address <span class="text-danger">*</span></label>
                                                        <textarea name="ref_address" id="ref_address2" class="form-control" placeholder="Address"></textarea>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_name2">Full Name <!-- <span class="text-danger">*</span> --></label>
                                                        <input type="text" name="ref_name2" id="ref_name2" class="form-control" placeholder="Full Name">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation2">Relationship<!--  <span class="text-danger">*</span> --></label>
                                                        <select class="form-control select2bs4" name="ref_relation2" id="relation2" style="width: 100%;">
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
                                                        <label for="ref_job2">Occupation<!--  <span class="text-danger">*</span> --></label>
                                                        <select class="form-control select2bs4 occupation" name="ref_job2" id="ref_job2" style="width: 100%;">
                                                        </select>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_contact2" class="control-label col-md-12">Contact<!--  <span class="text-danger">*</span> --></label>
                                                        <div class="col-md-12">
                                                            <input type="tel" name="ref_contact2" id="ref_contact2" class="form-control phone-input" placeholder="Contact 1">
                                                            <input type="hidden" readonly id="ref_contact2_full" name="ref_contact2_full">
                                                            <input type="hidden" name="ref_contact2_country_code" id="ref_contact2_country_code" readonly>
                                                        </div>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_alt_contact2" class="control-label col-md-12">Contact 2</label>
                                                        <div class="col-md-12">
                                                            <input type="tel" name="ref_alt_contact2" id="ref_alt_contact2" class="form-control phone-input" placeholder="Contact 2">
                                                            <input type="hidden" readonly id="ref_alt_contact2_full" name="ref_alt_contact2_full">
                                                            <input type="hidden" name="ref_alt_contact2_country_code" id="ref_alt_contact2_country_code" readonly>
                                                        </div>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_email2">Email</label>
                                                        <input type="email" name="ref_email2" id="ref_email2" class="form-control" placeholder="Email">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3 gy-2 align-items-center mt-0">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="ref_address2">Address<!--  <span class="text-danger">*</span> --></label>
                                                        <textarea name="ref_address2" id="ref_address2" class="form-control" placeholder="Address"></textarea>
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
                <button type="button" id="btnSubApplication" onclick="save_application()" class="btn btn-outline-success" style="display: none;">Submit</button>
                <!-- close modal -->
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- client savings modal -->
<div class="modal fade" data-bs-backdrop="static" id="savings_modal_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-success text-center"> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Client Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="">
                                            Name:
                                            <strong class="pl-3" id="cName"></strong>
                                        </p>
                                        <p class="">
                                            Contact:
                                            <strong class="pl-3" id="cContact"></strong>
                                        </p>
                                        <p class="">
                                            Alt Contact:
                                            <strong class="pl-3" id="cContact2"></strong>
                                        </p>
                                        <p class="">
                                            Email:
                                            <strong class="pl-3" id="cEmail"></strong>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="">
                                            Account Number:
                                            <strong class="pl-3" id="cAccountNo"></strong>
                                        </p>
                                        <p class="">
                                            Account Balance:
                                            <strong class="pl-3" id="cBalance"></strong>
                                        </p>
                                        <p class="">
                                            Address:
                                            <strong class="pl-3" id="cAddress"></strong>
                                        </p>
                                        <p class="">
                                            Registration Date:
                                            <strong class="pl-3" id="cRegDate"></strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center" id="photo-preview">
                                    <div class="col-md-12">
                                        (No photo)
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- transaction form -->
                        <div class="row mb-3">
                            <form id="savingsForm" class="form-horizontal" autocomplete="off">
                                <input type="hidden" readonly name="client_id" />
                                <input type="hidden" readonly name="account_typeId" />
                                <input type="hidden" readonly name="entry_menu" />
                                <input type="hidden" readonly name="title" />
                                <div class="form-body">
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <!-- <h5 class="card-title">New Saving Transaction</h5> -->
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Savings Product <span class="text-danger">*</span></label>
                                                        <div class="col-md-12">
                                                            <select name="product_id" id="product_id" class="form-control select2bs4">
                                                            </select>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Particular Name <span class="text-danger">*</span></label>
                                                        <div class="col-md-12">
                                                            <select name="particular_id" id="particular_id" class="form-control select2bs4">
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
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="amount" class="control-label fw-bold col-sm-12">Transaction Type <span class="text-danger">*</span></label>
                                                        <div class="col-sm-12">
                                                            <select name="entry_typeId" id="entry_typeId" class="form-control select2bs4">
                                                            </select>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="amount" class="control-label fw-bold col-sm-12">Payment Method <span class="text-danger">*</span></label>
                                                        <div class="col-sm-12">
                                                            <select name="payment_id" id="payment_id" class="form-control select2bs4" style="width: 100%;">
                                                            </select>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="amount" class="control-label fw-bold col-sm-12">Amount <span class="text-danger">*</span></label>
                                                        <div class="col-sm-12">
                                                            <input type="text" name="amount" id="amount" class="form-control amount" placeholder="Transaction Amount" min="0">
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="amount" class="control-label fw-bold col-sm-12">Transaction Date <span class="text-danger">*</span></label>
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
                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Details <span class="text-danger">*</span></label>
                                                        <div class="col-md-12">
                                                            <textarea name="entry_details" class="form-control" id=""></textarea>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
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
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSavings" onclick="save_Savingstransaction()" class="btn btn-outline-success">Submit</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- repayment add model -->
<div class="modal fade" data-bs-backdrop="static" id="repayment_modal_form">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form id="repaymentForm" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly name="account_typeId" />
                    <input type="hidden" readonly name="disbursement_id" />
                    <input type="hidden" readonly name="particular_id" />
                    <input type="hidden" readonly name="client_id" />
                    <input type="hidden" readonly name="entry_menu" />
                    <input type="hidden" readonly name="transaction_menu" />
                    <div class="form-body">
                        <!-- summary -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-md-3">
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
                            <div class="col-md-3">
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
                            <div class="col-md-3">
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
                                    <strong class="pl-3" id="lInstallment"></strong>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="">
                                    Duration(Days): <br />
                                    <strong class="pl-3" id="lDuration"></strong>
                                </p>
                                <p class="">
                                    Days Covered: <br />
                                    <strong class="pl-3" id="lDaysCovered"></strong>
                                </p>
                                <p class="">
                                    Days Remaining: <br />
                                    <strong class="pl-3" id="lDaysRemaining"></strong>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <!-- disbursement -->
                        <div class="row gx-3 gy-2 align-items-center mt-0">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Disbursement Code <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <select id="disbursement_id" name="disbursement_id" class="form-control select2bs4">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Product Name</label>
                                    <div class="col-md-12">
                                        <input name="product_name" placeholder="Product Name" class="form-control" type="text" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">Disbursement Class</label>
                                    <div class="col-md-12">
                                        <input name="class" placeholder="Disbursement Class" class="form-control" type="text" readonly>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <label class="control-label fw-bold col-md-12">Contact</label>
                                    <div class="col-md-12">
                                        <input id="mobile-full" name="contact" placeholder="Contact" class="form-control phone-input" type="tel">
                                        <input type="hidden" readonly id="contact_full" name="contact_full">
                                        <input type="hidden" name="contact_country_code" id="contact_country_code" readonly>
                                    </div>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-sm-12">Transaction Type <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select name="entry_typeId" id="repay_entry_typeId" class="form-control select2bs4">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
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

<?= $this->endSection() ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    var clientId = '<?= isset($client) ? $client['id'] : 0; ?>'
    var client = <?= json_encode($client); ?>;
    appID = '';
    disID = '';
</script>

<script src="/assets/scripts/loans/index.js"></script>
<script src="/assets/scripts/clients/view.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/main/auto-updates.js"></script>
<script src="/assets/scripts/transactions/index.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>
<script src="/assets/scripts/main/phone.js"></script>
<?= $this->endSection() ?>