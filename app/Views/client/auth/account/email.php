<?= $this->extend("layout/app"); ?>

<?= $this->section("content"); ?>

<div class="card custom-card">
    <div class="card-body p-5">
        <p class="h5 fw-semibold mb-2 text-center">Reset Password</p>
        <p class="mb-4 text-muted op-7 fw-normal text-center">
            To reset your password, enter the email address associated with your account.
        </p>
        <div class="row gy-1">
            <span class="text-center wrong-msg msg"></span>
            <form id="form" autocomplete="off">
                <?= csrf_field() ?>
                <div id="emailRow" class="col-xl-12">
                    <div class="form-group">
                        <label for="email" class="form-label text-default">Email</label>
                        <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email Address">
                        <span class="help-block error-msg"></span>
                    </div>
                </div>
                <div class="col-xl-12 d-grid mt-2">
                    <!-- <a href="index.html" class="btn btn-lg btn-primary">Sign In</a> -->
                    <button type="submit" id="btnSubmit" class="btn btn-lg btn-success">
                        Send Password Reset Link
                    </button>
                </div>
            </form>
            <div>
                <p class="fs-14 text-muted fw-normal mt-3">Go to <a href="/admin/login" class="text-primary">
                        Sign In</a>
                </p>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>

<script src="/assets/reset/admin/reset-password-link.js"></script>

<?= $this->endSection(); ?>