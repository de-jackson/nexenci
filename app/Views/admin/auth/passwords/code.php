<?= $this->extend("layout/app"); ?>

<?= $this->section("content"); ?>

<div class="card custom-card">
    <div class="card-body p-5">
        <div class="row gy-1 text-center">
            <p class="h2 fw-semibold mb-2 text-center text-primary">Verify your identity?</p>
            <p class="mb-4 text-muted op-7 fw-normal text-center">
                A temporary verification code has been sent to your <?= ($verify_mode == "email") ? "email" : "phone number"; ?>  ending with
            </p>
            <p class="h6 fw-semibold mb-2 text-center">
                <?= $email_or_mobile; ?>
            </p>
            <span class="text-center wrong-msg msg"></span>
            <form id="form" autocomplete="off">
                <?= csrf_field() ?>
                <div id="emailRow" class="col-xl-12">
                    <div class="form-group">
                        <label for="code" class="form-label text-default">
                            Verification Code
                        </label>
                        <input type="text" id="code" name="code" class="form-control form-control-lg" placeholder="Enter your 6-digit code here">
                        <span class="help-block error-msg text-danger"></span>
                    </div>
                </div>
                <div class="col-xl-12 d-grid mt-2">
                    <button type="submit" id="btnSubmit" class="btn btn-lg btn-success" onclick="sendTokenCode('submitcode')">
                        Submit
                    </button>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <span class="fs-14 fw-normal mt-3 float-start">Didn't get the code?
                        <a href="javascript:void(0)" onclick="sendTokenCode('sendcode')" class="text-primary">
                            Resend code
                        </a>
                    </span>
                    <span class="fs-14 fw-normal mt-3 float-end">Go to
                        <a href="javascript:void(0)" onclick="clearSessionsBtn()" class="text-primary">
                            Sign In
                        </a>
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>

<script src="/assets/auth/admin.js"></script>

<?= $this->endSection(); ?>