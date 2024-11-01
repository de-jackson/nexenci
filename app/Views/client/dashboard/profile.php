<?= $this->extend("layout/client"); ?>

<?= $this->section("content"); ?>
<div class="container-fluid">
    <div class="card profile-overview profile-overview-wide">
        <div class="card-body d-flex">
            <div class="clearfix">
                <div class="d-inline-block position-relative me-sm-4 me-3 mb-3 mb-lg-0">
                    <?php if (file_exists('uploads/clients/passports/' . $user['photo']) && $user['photo']) : ?>
                        <img src="/uploads/clients/passports/<?= $user['photo']; ?>" class="img-fluid rounded-pill" alt="Photo" />
                    <?php else : ?>
                        <img src="/assets/dist/img/nophoto.jpg" class="img-fluid rounded-pill" alt="Photo" />
                    <?php endif; ?>
                    <span class="fa fa-circle border border-3 border-white text-<?= (strtolower($user['access_status']) == 'active') ? 'success' : 'danger'; ?> position-absolute bottom-0 end-0 rounded-circle"></span>
                </div>
            </div>
            <div class="clearfix d-xl-flex flex-grow-1">
                <div class="clearfix pe-md-5">
                    <h3 class="fw-semibold mb-1"><?= (isset($user['title'])) ? $user['title'] . ' ' . $user['name'] : $user['name']; ?></h3>
                    <ul class="d-flex flex-wrap fs-6 align-items-center">
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-la-code-branch me-1 fs-18"></i><?= $user['branch_name']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-genderless me-1 fs-18"></i><?= $user['gender']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-tag me-1 fs-18"></i><?= $user['account_type']; ?>
                        </li>
                    </ul>

                    <ul class="d-flex flex-wrap fs-6 align-items-center mt-2">
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-phone me-1 fs-18"></i><?= ($user['alternate_no']) ? $user['mobile'] . ', ' . $user['alternate_no'] : $user['mobile']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-envelope me-1 fs-18"></i><?= $user['email']; ?>
                        </li>
                        <li class="me-3 d-inline-flex align-items-center">
                            <i class="las la-map-marker me-1 fs-18"></i><?= $user['residence']; ?>
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
                                <h3 class="mb-0 fw-semibold lh-1"><?= $user['account_no']; ?></h3>
                                <span class="fs-14">Account No.</span>
                            </div>
                        </div>
                        <!-- shares balance -->
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
                                <h3 class="mb-0 fw-semibold lh-1"><?= number_format($user['sharesBalance']); ?></h3>
                                <span class="fs-14">Shares Balance</span>
                            </div>
                        </div>
                        <!-- account balance -->
                        <div class="border outline-dashed rounded p-2 d-flex align-items-center me-3 mt-3">
                            <div class="avatar avatar-md bg-primary-light text-primary rounded d-flex align-items-center justify-content-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 1V23" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M17 5H9.5C8.57174 5 7.6815 5.36875 7.02513 6.02513C6.36875 6.6815 6 7.57174 6 8.5C6 9.42826 6.36875 10.3185 7.02513 10.9749C7.6815 11.6313 8.57174 12 9.5 12H14.5C15.4283 12 16.3185 12.3687 16.9749 13.0251C17.6313 13.6815 18 14.5717 18 15.5C18 16.4283 17.6313 17.3185 16.9749 17.9749C16.3185 18.6313 15.4283 19 14.5 19H6" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="clearfix ms-2">
                                <h3 class="mb-0 fw-semibold lh-1"><?= number_format($user['account_balance']); ?></h3>
                                <span class="fs-14">Savings Balance</span>
                            </div>
                        </div>
                        <!-- loan balance -->
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
                        <a href="javascript:void(0);" id="fAuthBtn" class="btn <?= (strtolower($user['2fa']) == 'false') ? 'btn-info' : 'btn-danger'; ?> ms-2" onclick="two_factorAuth('<?= $user['2fa'] ?>')"><?= (strtolower($user['2fa']) == 'false') ? 'Enable 2-FA' : 'Disable 2-FA'; ?></a>
                        <a href="javascript:void(0);" class="btn btn-primary ms-2" onclick="change_password()">Change Password</a>
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
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="tansactionsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#tansactions-tab" type="button" role="tab" aria-controls="tansactions-tab" aria-selected="true">My Tansactions</a>
                    </li>
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="applicationsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#applications-tab" type="button" role="tab" aria-controls="applications-tab" aria-selected="false">My Applications</a>
                    </li>
                    <li class="nav-item ms-1" id="" role="presentation">
                        <a href="javascript:void(0)" id="disbursementsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#disbursements-tab" type="button" role="tab" aria-controls="disbursements-tab" aria-selected="false">My Disbursements</a>
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
                                                                <input name="vname" placeholder="Full Name" class="form-control" type="text" value="<?= $user['name']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Account Branch</label>
                                                            <div class="col-md-12">
                                                                <input name="vbranch_id" placeholder="Account Branch" class="form-control" type="text" value="<?= $user['branch_name']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Gender</label>
                                                            <div class="col-md-12">
                                                                <input type="text" class="form-control" name="vgender" placeholder="Gender" value="<?= $user['gender']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Nationality</label>
                                                            <div class="col-md-12">
                                                                <input type="text" class="form-control" name="vnationality" placeholder="Nationality" value="<?= $user['nationality']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Marital Status</label>
                                                            <div class="col-md-12">
                                                                <input type="text" class="form-control" name="vmarital_status" placeholder="Marital Status" value="<?= $user['marital_status']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Religion</label>
                                                            <div class="col-md-12">
                                                                <input type="text" class="form-control" name="vreligion" placeholder="Religion" value="<?= $user['religion']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <label class="control-label fw-bold col-md-12">Profile Photo</label>
                                                <div class="form-group" id="photo-preview">
                                                    <div class="col-md-12">
                                                        <?php if ($user['photo'] && file_exists('uploads/clients/passports/' . $user['photo'])) : ?>
                                                            <img src="/uploads/clients/passports/<?= $user['photo']; ?>" class="rounded-4 profile-avatar" alt="Profile" id="viewProfileImage" style="height:100px; width: 100px;" />
                                                        <?php else : ?>
                                                            <img src="/assets/dist/img/nophoto.jpg" class="rounded-4 profile-avatar" alt="Passport Photo" id="viewProfileImage">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Phone Number 1</label>
                                                    <div class="col-md-12">
                                                        <input id="vmobile" name="vmobile" class="form-control phone-input" type="text" placeholder="Phone Number 1" value="<?= $user['mobile']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Phone Number 2</label>
                                                    <div class="col-md-12">
                                                        <input id="valternate_no" name="valternate_no" placeholder="Phone Number 2" class="form-control phone-input" type="text" value="<?= $user['alternate_no']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Email</label>
                                                    <div class="col-md-12">
                                                        <input name="vemail" placeholder="Email Address" class="form-control" type="email" value="<?= $user['email']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Account Number</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vaccount_no" class="form-control" placeholder="Account Number" value="<?= $user['account_no']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Account Type</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vaccount_type" class="form-control" placeholder="Account Type" value="<?= $user['account_type']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Account Balance [<?= $settings['currency'] ?>]</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="vaccount_balance" class="form-control" placeholder="Account Balance" value="<?= $user['account_balance']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Residence Address</label>
                                                    <div class="col-md-12">
                                                        <textarea name="vresidence" placeholder="Residence Address" class="form-control" readonly><?= $user['residence']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Closest Land Mark</label>
                                                    <div class="col-md-12">
                                                        <textarea name="vclosest_landmark" placeholder="Major Land Mark Feature nearby e.g school, church, mosque, etc" class="form-control" readonly><?= $user['closest_landmark']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Occupation</label>
                                                    <div class="col-md-12">
                                                        <input name="voccupation" placeholder="Occupation" class="form-control" type="text" value="<?= $user['occupation']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">D.O.B</label>
                                                    <div class="col-md-12">
                                                        <input name="vdob" placeholder="Date Of Birth" class="form-control" type="date" value="<?= $user['dob']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Age(Years)</label>
                                                    <div class="col-md-12">
                                                        <input name="age" placeholder="Age(Years)" class="form-control" type="text" value="<?= $user['age']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="control-label fw-bold col-md-12">ID Photo(Front)</label>
                                                <div class="form-group" id="id-previewFront">
                                                    <div class="col-md-12">
                                                        <?php if ($user['id_photo_front'] && file_exists('uploads/clients/ids/front/' . $user['id_photo_front'])) : ?>
                                                            <img src="/uploads/clients/ids/front/<?= $user['id_photo_front']; ?>" class="img-fluid" alt="ID PHOTO (Front)" id="previewIdFront" style="height:100px; width: auto;" />
                                                        <?php else : ?>
                                                            <img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail" alt="ID PHOTO (Front)" id="previewIdFront" style="height:100px; width: auto;" />
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">ID Type</label>
                                                            <div class="col-md-12">
                                                                <input type="text" class="form-control" name="vid_type" placeholder="ID Type" value="<?= $user['id_type']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">ID Number</label>
                                                            <div class="col-md-12">
                                                                <input name="vid_number" placeholder="ID Number" class="form-control" type="text" value="<?= $user['id_number']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Id Expiry Date</label>
                                                            <div class="col-md-12">
                                                                <input name="vid_expiry" placeholder="Id Expiry Date" class="form-control" type="text" value="<?= $user['id_expiry_date']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label fw-bold col-md-12">ID Photo(Back)</label>
                                                <div class="form-group" id="id-previewBack">
                                                    <div class="col-md-12">
                                                        <?php if ($user['id_photo_back'] && file_exists('uploads/clients/ids/back/' . $user['id_photo_back'])) : ?>
                                                            <img src="/uploads/clients/ids/back/<?= $user['id_photo_back']; ?>" class="img-fluid" alt="ID PHOTO (Back)" id="previewIdBack" style="height:100px; width: auto;" />
                                                        <?php else : ?>
                                                            <img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail" alt="ID Back" id="previewIdBack" style="height:100px; width: auto;" />
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Job Location</label>
                                                    <div class="col-md-12">
                                                        <textarea name="vjob_location" placeholder="Job Location" class="form-control" readonly><?= $user['job_location']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Next of Kin</label>
                                                    <div class="col-md-12">
                                                        <input name="vnext_of_kin" placeholder="Next of kin" class="form-control" type="text" value="<?= $user['next_of_kin_name']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Next of Kin Relationship</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="vnok_relationship" placeholder="Next of Kin Relationship" value="<?= $user['next_of_kin_relationship']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Next Of Kin Address</label>
                                                    <div class="col-md-12">
                                                        <textarea name="vnok_address" placeholder="Address" class="form-control" readonly><?= $user['nok_address']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Next of Kin Phone1</label>
                                                    <div class="col-md-12">
                                                        <input id="vnok_phone" name="vnok_phone" placeholder="next of kin phone" class="form-control phone-input" type="text" value="<?= $user['next_of_kin_contact']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Next of Kin Phone2</label>
                                                    <div class="col-md-12">
                                                        <input id="vnok_alt_phone" name="vnok_alt_phone" placeholder="next of kin phone2" class="form-control phone-input" type="text" value="<?= $user['next_of_kin_alternate_contact']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Next of Kin Email</label>
                                                    <div class="col-md-12">
                                                        <input name="vnok_email" placeholder="Next of Kin Email" class="form-control" type="text" value="<?= $user['nok_email']; ?>" readonly>
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
                                                                <input name="view_reg_date" placeholder="Registration Date" class="form-control" type="text" value="<?= $user['reg_date']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Responsible Officer</label>
                                                            <div class="col-md-12">
                                                                <input name="staff_name" placeholder="Responsible officer" class="form-control" type="text" value="<?= $user['staff_name']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Created At</label>
                                                            <div class="col-md-12">
                                                                <input name="created_at" placeholder="Created At" class="form-control" type="text" value="<?= $user['name']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label fw-bold col-md-12">Last Updated At</label>
                                                            <div class="col-md-12">
                                                                <input name="updated_at" placeholder="Last Updated At" class="form-control" type="text" value="<?= $user['name']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <label class="control-label fw-bold col-md-12">Client Signature</label>
                                                <div class="form-group" id="signature-preview">
                                                    <div class="col-md-12">
                                                        <?php if ($user['signature'] && file_exists('uploads/clients/signatures/' . $user['signature'])) : ?>
                                                            <img src="/uploads/clients/signatures/<?= $user['signature']; ?>" class="img-fluid" alt="signature" id="preview-sign" style="height:100px; width: 100px;" />
                                                        <?php else : ?>
                                                            <img src="/assets/dist/img/sign.png" class="img-fluid thumbnail" alt="signature" id="preview-sign" style="height:100px; width: auto;">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- tansactions -->
                            <div class="tab-pane fade" id="tansactions-tab" role="tabpanel" aria-labelledby="tansactions-tab" tabindex="0">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title"><?= ucwords($user['name']) ?> Transactions History</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="myTransactions" class="table table-sm  table-hover text-nowrap" style="width:100%">
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

<div class="modal fade" id="passwordModal" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="password_form" class="form-horizontal" autocomplete="off">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly value="<?= $user['id']; ?>" name="id" />
                    <input type="hidden" readonly value="" name="menu" />
                    <div class="form-body">
                        <div class="row gy-3">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="currentpassword" class="form-label text-default">Current Password</label>
                                    <div class="input-group">
                                        <input type="password" name="currentpassword" class="form-control form-control-lg" id="currentpassword" placeholder="current password" autocomplete="off">
                                        <button class="btn btn-light" type="button" onclick="createpassword('currentpassword',this)" id="btncurrentpassword"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <span class="help-block error-msg text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="newpassword" class="form-label text-default">New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="newpassword" class="form-control form-control-lg" id="newpassword" placeholder="new password" autocomplete="off">
                                        <button class="btn btn-light" type="button" onclick="createpassword('newpassword',this)" id="btnnewpassword"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <span class="help-block error-msg text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-2">
                                <div class="form-group">
                                    <label for="confirmpassword" class="form-label text-default">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" name="confirmpassword" class="form-control form-control-lg" id="confirmpassword" placeholder="confirm password" autocomplete="off">
                                        <button class="btn btn-light" type="button" onclick="createpassword('confirmpassword',this)" id="btn-confirmpassword"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <span class="help-block error-msg text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" onclick="save_newPassword()" class="btn btn-outline-success" id="btnReset">Reset</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    var Id = <?= $user['id']; ?>;
    var username = '<?= $user['name']; ?>';
    var account_typeId = 12;
    var table = 'myTransactions';
    var appID = '';
    var disID = '';
</script>

<script src="/assets/client/dashboard/profile.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<?= $this->endSection() ?>