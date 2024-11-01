<?= $this->extend("layout/app"); ?>

<?= $this->section("content"); ?>

<div class="card custom-card">
    <div class="card-body p-5">
        <p class="h2 fw-semibold mb-2 text-center text-primary">Verify your identity?</p>
        <p class="h5 fw-semibold mb-2 text-center">
            <?= $client_email_or_mobile; ?>
        </p>
        <p class="mb-4 text-muted op-7 fw-normal text-center fw-semibold">
            We will send a temporary verification code via your preferred recovery mode.
        </p>
        <div class="row gy-1">
            <span class="text-center wrong-msg msg"></span>
            <form id="form" autocomplete="off">
                <?= csrf_field() ?>
                <div class="col-xl-12 d-grid mt-2">
                    <button type="submit" id="btnSubmit" class="btn btn-lg btn-primary" onclick="sendTokenCode('sendcode')">
                        <!-- Via <? //= $client_verification_mode; ?> -->
                        Get code
                    </button>
                </div>
            </form>
            <div class="text-center">
                <p class="fs-12 text-muted mt-3">Already have an account? <a href="javascript:void(0)" onclick="clearSessionsBtn()" class="text-primary">Login In</a></p>
            </div>
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