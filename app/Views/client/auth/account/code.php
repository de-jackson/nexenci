<?= $this->extend("layout/app"); ?>

<?= $this->section("content"); ?>

<div class="card custom-card">
    <div class="card-body p-5">
        <div class="row gy-1 text-center">
            <p class="h2 fw-semibold mb-2 text-center text-primary">Verify your identity?</p>
            <p class="mb-4 text-muted op-7 fw-normal text-center">
                A temporary verification code has been sent to your <?= ($client_email_or_mobile == "email") ? "email" : "phone number"; ?> ending with
            </p>
            <p class="h6 fw-semibold mb-2 text-center">
                <?= $client_email_or_mobile; ?>
            </p>
            <span class="text-center wrong-msg msg"></span>
            <form id="form" autocomplete="off">
                <?= csrf_field() ?>
                <div id="emailRow" class="col-xl-12">
                    <div class="form-group">
                        <label for="code" class="form-label text-default fw-semibold">
                            Verification Code
                        </label>
                        <input type="number" id="code" name="code" class="form-control form-control-lg" placeholder="Enter your 6-digit code here" min="0">
                        <span class="help-block error-msg text-danger"></span>
                    </div>
                </div>
                <div class="col-xl-12 d-grid mt-2">
                    <!-- <button type="submit" id="btnSubmit" class="btn btn-lg btn-primary" onclick="sendTokenCode('submitcode')"> -->
                    <button type="submit" id="btnSubmit" class="btn btn-lg btn-primary">
                        Submit
                    </button>
                </div>
            </form>
            <div class="text-center">
                <p class="fs-12 text-muted mt-3">Didn't get the code? <a href="javascript:void(0)" onclick="sendTokenCode('sendcode')" class="text-primary">Resend code</a>
                </p>
            </div>
            <!-- <div class="row">
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
            </div> -->
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>

<script>
    var currentURL = '<?=current_url(); ?>';
</script>

<script src="/assets/client/auth/account.js"></script>

<?= $this->endSection(); ?>