<?= $this->extend("layout/app"); ?>

<?= $this->section("content"); ?>
<div class="text-center">
    <h3 class="title">Welcome to <?= $settings['business_name']; ?>!</h3>
</div>
<p>Setup your account in few steps</p>
<?php
if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success solid alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <polyline points="9 11 12 14 22 4"></polyline>
            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
        </svg>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        </button>
    </div>
<?php elseif (session()->getFlashdata('failed')) : ?>
    <div class="alert alert-danger solid alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
        <strong>Session Expired!</strong> <?= session()->getFlashdata('failed') ?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert">
        </button>
    </div>
<?php endif; ?>
<span class="text-center wrong-msg msg"></span>
<form id="form" autocomplete="off">
    <?= csrf_field() ?>
    <div class="row gy-3">
        <div class="col-xl-12">
            <div class="form-group">
                <div class="col-md-12">
                    <label for="name" class="form-label text-default fw-semibold">Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Full Name (as per ID)">
                    <span class="help-block error-msg text-danger"></span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-12">Phone number <span class="text-danger">*</span></label>
                <div class="col-md-12">
                    <input type="tel" id="phone" name="phone" class="form-control phone-input" placeholder="Phone number">
                    <input type="hidden" name="phone_country_code" id="phone_country_code" readonly>
                    <input type="hidden" name="phone_full" id="phone_full" readonly>
                </div>
                <span class="help-block text-danger error-msg"></span>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <div class="position-relative">
                    <label class="mb-1 text-dark col-md-12">Password <span class="text-danger">*</span></label>
                    <div class="col-md-12">
                        <input type="password" name="password" id="dz-password" class="form-control" placeholder="Password">
                        <span class="show-pass eye">
                            <i class="fa fa-eye"></i>
                            <i class="fa fa-eye-slash"></i>
                        </span>
                        <span class="help-block error-msg"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <div class="position-relative">
                    <label class="mb-1 text-dark col-md-12">Confirm Password <span class="text-danger">*</span></label>
                    <div class="col-md-12">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm password">
                        <!-- <span class="show-pass eye">
                            <i class="fa fa-eye"></i>
                            <i class="fa fa-eye-slash"></i>
                        </span> -->
                        <span class="help-block error-msg"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row d-flex justify-content-between">
            <div class="mb-4">
                <div class="form-check custom-checkbox mb-3">
                    <input type="checkbox" id="terms" name="terms" class="form-check-input" checked>
                    <label class="form-check-label" for="terms">By creating an account, you agree to our <a href="javascript:void(0)" class="text-success"><u>Terms & Conditions</u></a> and <a class="text-success"><u>Privacy Policy</u></a></label>
                </div>
            </div>
        </div>
        <div class="text-center mb-3">
            <button type="submit" id="btnSubmit" class="btn btn-primary btn-block">
                Create Account
            </button>
        </div>
        <h6 class="login-title" style="display: none;"><span>Or continue with</span></h6>
    </div>
    <div class="mb-3" style="display: none;">
        <ul class="d-flex align-self-center justify-content-center">
            <li><a target="_blank" href="https://www.facebook.com/" class="fab fa-facebook-f btn-facebook"></a></li>
            <li><a target="_blank" href="https://www.google.com/" class="fab fa-google-plus-g btn-google-plus mx-2"></a></li>
            <li><a target="_blank" href="https://www.linkedin.com/" class="fab fa-linkedin-in btn-linkedin me-2"></a></li>
            <li><a target="_blank" href="https://twitter.com/" class="fab fa-twitter btn-twitter"></a></li>
        </ul>
    </div>
    <p class="text-center">Already have an account?
        <a class="btn-link text-primary" href="<?= base_url('client/login'); ?>">Sign In</a>
    </p>
</form>

<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>
<script src="/assets/client/auth/register.js"></script>
<!-- <script src="/assets/js/show-password.js"></script> -->

<?= $this->endSection(); ?>