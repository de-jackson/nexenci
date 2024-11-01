<?= $this->extend("layout/app"); ?>

<?= $this->section("content"); ?>

<div class="card custom-card">
    <div class="card-body p-5">
        <p class="h5 fw-semibold mb-2 text-center">
            Hello <?= $user['name']; ?>!
        </p>
        <p class="mb-4 text-muted op-7 fw-normal text-center">
            You are only one step a way from resetting your password.
        </p>

        <span class="text-center wrong-msg msg"></span>
        <form id="form" autocomplete="off">
            <?= csrf_field() ?>
            <input type="hidden" readonly name="id" value="<?= $user['id']; ?>">
            <div class="row gy-3">
                <div class="col-xl-12">
                    <div class="form-group">
                        <label for="password" class="form-label text-default">New Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control form-control-lg" id="password" placeholder="New Password">
                            <button class="btn btn-light" type="button" onclick="createpassword('password',this)" id="button-addon21"><i class="ri-eye-off-line align-middle"></i></button>
                        </div>
                        <span class="help-block error-msg text-danger"></span>
                    </div>
                </div>
                <div class="col-xl-12 mb-2">
                    <div class="form-group">
                        <label for="password_confirm" class="form-label text-default">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" id="password_confirm" name="password_confirm" class="form-control form-control-lg" placeholder="Confirm Password">
                            <button class="btn btn-light" type="button" onclick="createpassword('password_confirm',this)" id="button-addon22"><i class="ri-eye-off-line align-middle"></i></button>
                        </div>
                        <span class="help-block error-msg text-danger"></span>
                    </div>
                </div>
                <div class="col-xl-12 d-grid mt-2">
                    <button type="submit" id="btnSubmit" class="btn btn-lg btn-success">
                        Reset Password
                    </button>
                </div>
            </div>
        </form>
        <div class="text-center">
            <p class="fs-12 text-muted mt-3">Go to <a href="<?= base_url('admin/login'); ?>" class="text-primary">Sign In</a></p>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>

<script src="/assets/reset/admin/reset-password.js"></script>

<?= $this->endSection(); ?>