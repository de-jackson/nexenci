<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ucwords($menu) . ' ' . ucfirst($title) ?> Information
            </div>
        </div>
        <div class="card-body add-products p-0">
            <form id="settingsForm" class="form-horizontal" autocomplete="off">
                <?= csrf_field() ?>
                <input type="hidden" readonly value="" name="id" />
                <div class="form-body">
                    <div class="p-4">
                        <div class="row">
                            <div class="col-xxl-6 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0 border-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            <div class="col-xl-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Business Name <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="business_name" id="business_name" class="form-control" placeholder="Business Name">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Short Name <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="business_abbr" id="business_abbr" class="form-control" placeholder="Short Name">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Email <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="business_email" id="business_email" class="form-control" placeholder="Business Email">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Contact <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="tel" name="business_contact" id="business_contact" class="form-control" placeholder="Business Contact">
                                                        <input type="hidden" readonly id="business_contact_full" name="business_contact_full">
                                                        <input type="hidden" name="business_contact_country_code" id="business_contact_country_code" readonly>
                                                    </div>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Alternate Contact</label>
                                                    <div class="col-md-12">
                                                        <input type="tel" name="business_alt_contact" id="business_alt_contact" class="form-control" placeholder="Business Alternate Contact">
                                                        <input type="hidden" readonly id="business_alt_contact_full" name="business_alt_contact_full">
                                                        <input type="hidden" name="business_alt_contact_country_code" id="business_alt_contact_country_code" readonly>
                                                    </div>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Website</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="business_web" id="business_web" class="form-control" placeholder="Website">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Postal Address <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="business_pobox" id="business_pobox" class="form-control" placeholder="Postal Address">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Business Slogan</label>
                                                    <div class="col-md-12">
                                                        <textarea name="business_slogan" id="business_slogan" class="form-control" placeholder="Business Slogan"></textarea>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Physical Address <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <textarea name="business_address" class="form-control" id="business_address" rows="2"></textarea>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-12">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">About Us</label>
                                                    <div class="col-md-12">
                                                        <textarea name="business_about" class="form-control editor"></textarea>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-xl-12 col-lg-12 col-md-6 mt-3">
                                <div class="card custom-card shadow-none mb-0 border-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-4">
                                            <div class="col-xl-3">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Tax Rate(%) <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="number" name="tax_rate" id="tax_rate" class="form-control" placeholder="Tax Rate(%)" min="0">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Round Off[Fee] <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="number" name="round_off" id="round_off" class="form-control" placeholder="Round Off[Fee]" min="0">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Currency <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <select class="form-control select2 currency_id" name="currency_id" id="currency_id" style="width: 100%;">
                                                        </select>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Financial Year Start <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="financial_year_start" id="financial_year_start" class="form-control">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- logo -->
                                            <div class="col-xl-12 product-documents-container">
                                                <div class="form-group text-center">
                                                    <label class="control-label fw-bold col-sm-12" for="businessLogo">Business Logo <span class="text-danger">*</span></label>
                                                    <div class="col-sm-12">
                                                        <div id="user-photo-preview">
                                                            <img class="img-fluid thumbnail mt-4" id="preview-image" style="width: 100px; height: 100px;" alt="Logo">
                                                        </div>
                                                        <label id="upload-label" class="control-label fw-bold" for="businessLogo"></label>
                                                        <input type="file" name="business_logo" accept="image/*" required onchange="previewImageFile(event)" class="form-control" id="businessLogo">
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Created At</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="created_at" id="created_at" class="form-control" placeholder="Created At" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label class="control-label fw-bold col-md-12">Updated At</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="updated_at" id="updated_at" class="form-control" placeholder="Updated At" readonly>
                                                        <span class="help-block text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <h4 class="fw-bold">Notification Configuration</h4>
                                            <p class="fw-bold">If below checkboxes are un-checked, the system won't attempt to send notification to customers.</p>
                                            <div class="col-xl-6">
                                                <div class="form-check custom-checkbox mb-3">
                                                    <input type="checkbox" id="sms" name="sms" class="form-check-input" value="1">
                                                    <label class="form-check-label fw-bold" for="sms">Notify Customers via SMS</label>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 mb-4">
                                                <div class="form-check custom-checkbox mb-3">
                                                    <input type="checkbox" id="email" name="email" class="form-check-input" value="1">
                                                    <label class="form-check-label fw-bold" for="email">Notify Customers via email</label>
                                                </div>
                                            </div>
                                            <!-- system settings -->
                                            <div style="display: none;">
                                                <label class="form-label">System Details</label>
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">System Name</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="system_name" id="system_name" class="form-control" placeholder="System Name" readonly>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Short Name</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="system_abbr" id="system_abbr" class="form-control" placeholder="Short Name" readonly>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Version</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="system_version" id="system_version" class="form-control" placeholder="System Version" readonly>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">System Author</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="author" id="author" class="form-control" placeholder="System Author" readonly>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12">
                                                    <div class="form-group">
                                                        <label class="control-label fw-bold col-md-12">Slogan</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name="system_slogan" id="system_slogan" class="form-control" placeholder="System Slogan" readonly>
                                                            <span class="help-block text-danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- system settings ends -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-top border-block-start-dashed d-sm-flex justify-content-end">
                        <button class="btn btn-primary m-1" id="btnSav" onclick="save_settings()">Update Settings</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>


<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    var id = '<?= (isset($settings)) ? $settings['id'] : 0; ?>';
</script>
<script src="/assets/scripts/company/settings.js"></script>
<script src="/assets/scripts/main/images-js.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>
<script src="/assets/scripts/main/phone.js"></script>
<script src="/assets/scripts/main/editor.js"></script>
<?= $this->endSection() ?>