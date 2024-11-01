<?=$this->extend("layout/app"); ?>

<?=$this->section("content"); ?>

    <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <?php
        if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
                <?=session()->getFlashdata('success') ?>
            </div>
        <?php elseif (session()->getFlashdata('failed')) : ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <?=session()->getFlashdata('failed') ?>
            </div>
        <?php endif; ?>
        <?php $validation =  \Config\Services::validation(); ?>
        <form action="/admin/login" method="post">
            <?= csrf_field() ?>
            <div class="input-group mb-3">
                <input type="email" name="email" class="form-control <?php if ($validation->getError('email')) : ?>is-invalid<?php endif ?>" placeholder="Email" value="<?=set_value('email'); ?>" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                <?php if ($validation->getError('email')) : ?>
                    <div class="invalid-feedback">
                        <?= $validation->getError('email') ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control <?php if ($validation->getError('password')) : ?>is-invalid<?php endif ?>" placeholder="Password" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                <?php if ($validation->getError('password')) : ?>
                    <div class="invalid-feedback">
                        <?= $validation->getError('password') ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember" />
                        <label for="remember">
                            Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <!-- <div class="social-auth-links text-center mt-2 mb-3">
            <a href="#" class="btn btn-block btn-primary">
              <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
            </a>
            <a href="#" class="btn btn-block btn-danger">
              <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
            </a>
        </div> -->
        <!-- /.social-auth-links -->

        <p class="mb-1">
            <a href="<?=base_url('/admin/account/password'); ?>">I forgot my password</a>
        </p>
        <p class="mb-0">
            <a href="/admin/register" class="text-center">Register a new membership</a>
        </p>
    </div>

<?=$this->endSection(); ?>