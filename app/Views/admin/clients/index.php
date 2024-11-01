<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    <?= ucfirst($title) ?> Information
                </div>
            </div>
            <div class="card-body">
                <table id="clients" class="table table-sm table-hover text-nowrap" style="width:100%">
                    <thead class="">
                        <tr>
                            <th>
                                <div class="form-check custom-checkbox ms-0">
                                    <input type="checkbox" class="form-check-input checkAllInput" id="check-all" required="">
                                    <label class="form-check-label" for="check-all"></label>
                                </div>
                            </th>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Branch</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Account No.</th>
                            <th>Address</th>
                            <th>Balance [<?= $settings['currency']; ?>]</th>
                            <th>Products</th>
                            <th>Membership</th>
                            <th>Shares</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--End::row-1 -->

<!-- add or edit model -->
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
                    <input type="hidden" readonly name="relation_id" />
                    <input type="hidden" readonly name="mode" />
                    <div class="form-body">
                        <div id="importRow" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold col-md-12">Branch Name <span class="text-danger">*</span></label>
                                            <select name="branchID" id="branchID" class="form-control select2bs4 branch_id">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label fw-bold"> Upload Client(s) <span class="text-danger">*</span>
                                                <span class="text-white"> ( <span class="text-danger"> CSV Format </span> ) </span>
                                            </label>
                                            <input type="file" name="file" class="form-control" accept=".csv">
                                            <span class="help-block text-danger"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="formRow">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row  mb-3">
                                        <div class="col-md-4">
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
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">Title</label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="title" id="title" style="width: 100%;">
                                                    </select>
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
                                            <span class="help-block text-danger"></span>
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
                                        <label class="control-label fw-bold col-md-12">Date Of Birth <!-- <span class="text-danger">*</span> --></label>
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
                                        <label class="control-label fw-bold col-sm-12" for="idImageFront">ID PHOTO(Front)</label>
                                        <div class="col-sm-12">
                                            <div id="id-previewFront"></div>
                                            <label id="id-labelFront" class="control-label fw-bold" for="idImageFront"></label>
                                            <input type="file" name="id_photo_front" accept="image/*" required onchange="previewIdImage(event, 'Front')" class="form-control" id="idImageFront">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row mb-3">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">ID Type <!-- <span class="text-danger">*</span> --></label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="id_type" id="idtypes" style="width: 100%;">
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">ID Number <!-- <span class="text-danger">*</span> --></label>
                                                <div class="col-md-12">
                                                    <input name="id_number" placeholder="ID Number" class="form-control" type="text">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="control-label fw-bold col-md-12">ID Expiry Date <!-- <span class="text-danger">*</span> --></label>
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
                                        <label class="control-label fw-bold col-sm-12" for="idImageBack">ID PHOTO(Back)</label>
                                        <div class="col-sm-12">
                                            <div id="id-previewBack"></div>
                                            <label id="id-labelBack" class="control-label fw-bold" for="idImageBack"></label>
                                            <input type="file" name="id_photo_back" accept="image/*" required onchange="previewIdImage(event, 'Back')" class="form-control" id="idImageBack">
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
                                        <label class="control-label fw-bold col-md-12">Next of Kin Name <!-- <span class="text-danger">*</span> --></label>
                                        <div class="col-md-12">
                                            <input name="next_of_kin" placeholder="Next of Kin Name" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label fw-bold col-md-12">Next of kin Relationship <!-- <span class="text-danger">*</span> --></label>
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
                                        <label class="control-label fw-bold col-md-12">Next of Kin Address <!-- <span class="text-danger">*</span> --></label>
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
                                                <label class="control-label fw-bold col-md-12">Next of Kin Phone Number <!-- <span class="text-danger">*</span> -->
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
                                            <div id="signature-preview"></div>
                                            <label id="sign-label" class="control-label fw-bold" for="signature"></label>
                                            <input type="file" name="signature" accept="image/*" required onchange="previewSignature(event)" class="form-control" id="signature">
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
<!-- view modal -->
<div class="modal fade" data-bs-backdrop="static" id="view_modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <div class="close">
                    <!-- <btn type="button" class="btn btn-sm btn-secondary" onclick="exportClientForm()" id="export">
                        <i class="fas fa-print text-light"></i>
                    </btn> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body form">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- photo preview modal -->
<div class="modal fade" data-bs-backdrop="static" id="photo_modal_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-success text-center"> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form well well-lg one_well ">
                <form action="#" id="viewform" class="form-horizontal" autocomplete="off">
                    <input type="hidden" readonly value="" name="id" />
                    <div class="form-body">
                        <center>
                            <div class="form-group" id="photo-preview">
                                <label class="control-label fw-bold col-md-12"></label>
                                <div class="col-md-12">
                                    (No photo)
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </center>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
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
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" id="btnTransaction" class="btn btn-sm btn-outline-info float-right"><i class="fas fa-plus"></i> Add Transaction</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- add savings form -->
                <div id="addTransactionCard" style="display: none;">
                    <form id="savingsForm" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly name="client_id" />
                        <input type="hidden" readonly name="account_typeId" />
                        <input type="hidden" readonly name="entry_menu" />
                        <input type="hidden" readonly name="title" />
                        <div class="form-body">
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Add Savings Transaction</h5>
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
                                <div class="card-footer">
                                    <div class="col-md-12">
                                        <button type="button" id="btnSavings" onclick="save_Savingstransaction()" class="btn btn-outline-success">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Transaction History</h5>
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
                                            <th>Amount [<?= $settings['currency']; ?>]</th>
                                            <th>Ref ID</th>
                                            <th>Payment</th>
                                            <th>Balance[<?= $settings['currency']; ?>]</th>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" data-bs-backdrop="static" id="savings_modal_form0">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-success text-center"> </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">

                    </div>
                    <!-- savings history card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Transaction History</h5>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>

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
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    var id = '<?= isset($client) ? $client['id'] : 0; ?>'
</script>
<script src="/assets/scripts/clients/clients.js"></script>
<!-- dataTables -->
<script src="/assets/scripts/main/datatables.js"></script>
<script src="/assets/scripts/main/auto-updates.js"></script>
<script src="/assets/scripts/transactions/index.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>
<script src="/assets/scripts/main/phone.js"></script>

<?= $this->endSection() ?>