<div class="row">
    <div class="col-xl-12">
        <div class="card border custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    KYC Account Setup
                </div>
            </div>
            <div class="card-body">
                <form id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-bold text-center">
                        Let's Setup Your Account In A Few Simple Steps
                    </p>
                    <input type="hidden" readonly name="id" value="<?= $user['id']; ?>" />
                    <input type="hidden" readonly name="mode" />
                    <div class="form-body">
                        <div class="row gy-4">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                                <div class="row gy-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">
                                                Registration Date <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                    <input type="text" name="reg_date" class="form-control" value="<?= date("Y-m-d", strtotime($user['reg_date'])); ?>" placeholder="Registration Date" readonly>
                                                </div>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">
                                                Full Name <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <input name="c_name" placeholder="Full Name (as per ID)" class="form-control" type="text" value="<?= $user['name']; ?>">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">
                                                Gender <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <select class="form-control select2bs4" name="gender" id="gender" style="width: 100%;">
                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">
                                                Nationality <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <select class="form-control select2bs4" name="nationality" id="nationality" style="width: 100%;">
                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">
                                                Marital Status <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <select class="form-control select2bs4" name="marital_status" id="maritalstatus" style="width: 100%;">
                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Religion</label>
                                            <div class="col-md-12">
                                                <select class="form-control select2bs4" name="religion" id="religion" style="width: 100%;" id="religion">
                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-sm-12" for="profileImage">
                                        PASSPORT PHOTO <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-12">
                                        <!-- <div id="user-photo-preview"></div> -->
                                        <?php if ($user['photo'] && file_exists('uploads/clients/passports/' . $user['photo'])) : ?>
                                            <img src="/uploads/clients/passports/<?= $user['photo']; ?>" class="img-fluid rounded-pill" alt="Profile" id="viewProfileImage" />
                                        <?php else : ?>
                                            <img src="/assets/dist/img/nophoto.jpg" class="img-fluid" alt="Passport Photo" id="viewProfileImage">
                                        <?php endif; ?>
                                        <label id="upload-label" class="control-label fw-bold" for="profileImage"></label>
                                        <input type="file" name="photo" accept="image/*" required onchange="previewAttachment('profileImage', 'viewProfileImage')" class="form-control" id="profileImage">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Primary Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <input id="mobile" name="mobile" placeholder="Primary Phone Number" class="form-control phone-input" type="tel" value="<?= $user['mobile']; ?>" readonly>
                                        <input type="hidden" name="mobile_full" id="mobile_full" readonly>
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
                                        <input id="alternate_mobile" name="alternate_mobile" placeholder="Alternate Phone Number" class="form-control phone-input" type="tel" value="<?= $user['alternate_no']; ?>">
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
                                        <input name="c_email" placeholder="example@mail.com" class="form-control" type="email" value="<?= $user['email']; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Residence Address <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <textarea name="residence" placeholder="Please give full details - plot, street name, area etc" class="form-control"><?= $user['residence']; ?></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Branch <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <select id="branch_id" name="branch_id" class="form-control select2bs4 branch_id" style="width: 100%;">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Occupation <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <select id="occupation" name="occupation" class="form-control select2bs4 occupation" style="width: 100%;">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Date Of Birth <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                            <input type="date" name="dob" class="form-control getDatePicker" value="<?= $user['dob']; ?>" placeholder="Date Of Birth">
                                        </div>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-sm-12" for="idImageFront">
                                        ID PHOTO (Front) <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-12">
                                        <!-- <div id="id-previewFront"></div> -->
                                        <?php if ($user['id_photo_front'] && file_exists('uploads/clients/ids/front/' . $user['id_photo_front'])) : ?>
                                            <img src="/uploads/clients/ids/front/<?= $user['id_photo_front']; ?>" class="img-fluid" alt="ID PHOTO (Front)" id="previewIdFront" />
                                        <?php else : ?>
                                            <img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail" alt="ID PHOTO (Front)" id="previewIdFront">
                                        <?php endif; ?>
                                        <label id="id-labelFront" class="control-label fw-bold" for="idImageFront"></label>
                                        <input type="file" name="id_photo_front" accept="image/*" required onchange="previewAttachment('idImageFront', 'previewIdFront')" class="form-control" id="idImageFront">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">
                                                ID Type <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <select class="form-control select2bs4" name="id_type" id="idtypes" style="width: 100%;">
                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">
                                                ID Number <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <input name="id_number" placeholder="ID Number" class="form-control" type="text" value="<?= $user['id_number']; ?>">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">
                                                ID Expiry Date <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                                    <input name="id_expiry" placeholder="ID Expiry Date" class="form-control getDatePicker" type="date" value="<?= $user['id_expiry_date']; ?>">
                                                </div>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-sm-12" for="idImageBack">
                                        ID PHOTO (Back) <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-12">
                                        <!-- <div id="id-previewBack"></div> -->
                                        <?php if ($user['id_photo_back'] && file_exists('uploads/clients/ids/back/' . $user['id_photo_back'])) : ?>
                                            <img src="/uploads/clients/ids/back/<?= $user['id_photo_back']; ?>" class="img-fluid" alt="ID PHOTO (Back)" id="previewIdBack" />
                                        <?php else : ?>
                                            <img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail" alt="ID Back" id="previewIdBack" />
                                        <?php endif; ?>

                                        <label id="id-labelBack" class="control-label fw-bold" for="idImageBack"></label>
                                        <input type="file" name="id_photo_back" accept="image/*" required onchange="previewAttachment('idImageBack', 'previewIdBack')" class="form-control" id="idImageBack">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Job Location <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <textarea name="job_location" placeholder="Please give full details - plot, Street name, area etc" class="form-control"><?= $user['job_location']; ?></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Next of Kin Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <input name="next_of_kin" placeholder="Next of Kin Name" class="form-control" type="text" value="<?= $user["next_of_kin_name"]; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Next of kin Relationship <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <select class="form-control select2bs4" name="nok_relationship" id="relationships" style="width: 100%;">
                                        </select>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-md-12">
                                        Next of Kin Address <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-12">
                                        <textarea name="nok_address" placeholder="Next of Kin Address" class="form-control"><?= $user["nok_address"]; ?></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">
                                                Next of Kin Phone Number <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <input id="nok_phone" name="nok_phone" placeholder="Next of Kin Phone Number 1" class="form-control phone-input" type="tel" value="<?= $user["next_of_kin_contact"]; ?>">
                                                <input type="hidden" readonly id="nok_phone_full" name="nok_phone_full">
                                                <input type="hidden" name="nok_phone_country_code" id="nok_phone_country_code" readonly>
                                            </div>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Next of Kin Phone Number 2
                                            </label>
                                            <div class="col-md-12">
                                                <input id="nok_alt_phone" name="nok_alt_phone" placeholder="Next of Kin Phone Number 2" class="form-control phone-input" type="tel" value="<?= $user["next_of_kin_alternate_contact"]; ?>">
                                                <input type="hidden" readonly id="nok_alt_phone_full" name="nok_alt_phone_full">
                                                <input type="hidden" name="nok_alt_phone_country_code" id="nok_alt_phone_country_code" readonly>
                                            </div>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-md-12">Next of Kin Email
                                            </label>
                                            <div class="col-md-12">
                                                <input name="nok_email" placeholder="Next of Kin Email" class="form-control" type="text" value="<?= $user["nok_email"]; ?>">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label fw-bold col-sm-12" for="staff_id">
                                                Responsible Officer <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-12">
                                                <select class="form-control select2" data-placeholder="Select Officer" style="width: 100%;" id="staff_id" name="staff_id">
                                                    <option value="">Select</option>
                                                </select>
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label fw-bold col-sm-12" for="signature">
                                        Signature <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-12">
                                        <!-- <div id="signature-preview"></div> -->
                                        <?php if ($user['signature'] && file_exists('uploads/clients/signatures/' . $user['signature'])) : ?>
                                            <img src="/uploads/clients/signatures/<?= $user['signature']; ?>" class="img-fluid" alt="signature" id="preview-sign" />
                                        <?php else : ?>
                                            <img src="/assets/dist/img/sign.png" class="img-fluid thumbnail" alt="signature" id="preview-sign">
                                        <?php endif; ?>
                                        <label id="sign-label" class="control-label fw-bold" for="signature"></label>
                                        <input type="file" name="signature" accept="image/*" required onchange="previewAttachment('signature', 'preview-sign')" class="form-control" id="signature">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="row gx-3 gy-2 mt-0 float-end">
                    <div class="col-md-12">
                        <button type="button" id="btnSubmit" onclick="clientAccount('add')" class="btn btn-outline-success">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>