<?= $this->extend("layout/app"); ?>

<?= $this->section("content"); ?>


<div class="card custom-card">
    <div class="card-body p-5">
        <p class="h5 fw-semibold mb-2 text-center">Welcome to <?= $settings['business_name']; ?>!</p>
        <?php
        if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php
            session()->setFlashdata('success', "");
        elseif (session()->getFlashdata('failed')) : ?>
            <div class="mb-4 text-muted op-7 fw-normal text-center">
                <?= session()->getFlashdata('failed') ?>
            </div>
        <?php
            session()->setFlashdata('failed', "");
        endif; ?>
        <p class="mb-4 text-muted op-7 fw-normal text-center">
            <!-- Welcome and Join us by creating a free account -->
            Setup your account in few steps
        </p>
        <span class="text-center wrong-msg msg"></span>
        <form id="form" autocomplete="off">
            <div class="row gy-3">
                <div class="col-xl-12">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="name" class="form-label text-default fw-semibold">Full Name</label>
                            <input type="text" name="name" class="form-control form-control-lg " id="name" placeholder="Full Name (as per ID)">
                            <span class="help-block error-msg text-danger"></span>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="phone_full" id="phone_full" readonly>
                <div class="col-xl-12">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="phone" class="form-label text-default fw-semibold">
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <input type="tel" name="phone" class="form-control form-control-lg phone-input" id="phone" placeholder="Phone Number">
                            <span class="help-block error-msg text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="signup-password" class="form-label text-default fw-semibold">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control form-control-lg" id="signup-password" placeholder="Password">
                                <button class="btn btn-light" onclick="showPassword('signup-password',this)" type="button" id="button-addon2"><i class="fa fa-eye-slash align-middle"></i></button>
                                <span class="help-block error-msg text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 mb-2">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="signup-confirmpassword" class="form-label text-default fw-semibold">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="confirmpassword" class="form-control form-control-lg" id="signup-confirm_password" placeholder="Confirm password">
                                <button class="btn btn-light" onclick="showPassword('signup-confirm_password',this)" type="button" id="button-addon21"><i class="fa fa-eye-slash align-middle"></i>
                                </button>
                                <span class="help-block error-msg text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="terms" value="" id="terms" checked>
                            <label class="form-check-label text-muted fw-normal fw-semibold" for="terms">
                                By creating an account, you agree to our <a href="javascript:void(0)" class="text-success"><u>Terms & Conditions</u></a> and <a class="text-success"><u>Privacy Policy</u></a>
                            </label>
                            <span class="help-block error-msg text-danger"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 d-grid mt-2">
                <button type="submit" id="btnSubmit" class="btn btn-lg btn-primary">
                    Create Account
                </button>
            </div>
        </form>
        <div class="text-center">
            <p class="fs-12 text-muted mt-3">Already have an account? <a href="<?= base_url('client/login') ?>" class="text-primary">Sign In</a></p>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>
<script src="/assets/client/auth/register.js"></script>
<script src="/assets/js/show-password.js"></script>

<?= $this->endSection(); ?>