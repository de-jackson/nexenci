<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registration</title>

    <link rel="icon" type="image/png" href="/uploads/logo/<?= $settings['business_logo']; ?>" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css" />
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="/assets/dist/css/adminlte.min.css" />
</head>

<body class="hold-transition register-page" class="hold-transition login-page" style="background-image: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)), url('/assets/dist/img/background.jpg'); background-size: cover;">
    <div class="register-box">
        <div class="card card-outline card-success">
            <div class="card-header text-center">
                <!-- <a href="/assets/index2.html" class="h1"><b>Admin</b>LTE</a> -->
                <a href="<?= base_url('/dashboard'); ?>" class="h1">
                    <?php if (isset($settings) && file_exists('uploads/logo/' . $settings['business_logo']) && $settings['business_logo']) : ?>
                        <img src="/uploads/logo/<?= $settings['business_logo']; ?>" class="img-fluid" alt="Logo" />
                    <?php else : ?>
                        <img src="/assets/dist/img/default.jpg" class="img-fluid" alt="Logo" />
                    <?php endif; ?>
                </a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a new membership</p>
                <?php
                if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
                        <?php echo session()->getFlashdata('success') ?>
                    </div>
                <?php elseif (session()->getFlashdata('failed')) : ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
                        <?php echo session()->getFlashdata('failed') ?>
                    </div>
                <?php endif; ?>
                <?php $validation =  \Config\Services::validation(); ?>
                <form action="/admin/account/register" method="post">
                    <?= csrf_field() ?>
                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Full name" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <?php if ($validation->getError('name')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('name') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" />
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
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirm" class="form-control <?php if ($validation->getError('password_confirm')) : ?>is-invalid<?php endif ?>" placeholder="Retype password" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <?php if ($validation->getError('password_confirm')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('password_confirm') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree" />
                                <label for="agreeTerms"> I agree to the <a href="#">terms</a> </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <!-- <div class="social-auth-links text-center">
                        <a href="#" class="btn btn-block btn-primary">
                          <i class="fab fa-facebook mr-2"></i>
                          Sign up using Facebook
                        </a>
                        <a href="#" class="btn btn-block btn-danger">
                          <i class="fab fa-google-plus mr-2"></i>
                          Sign up using Google+
                        </a>
                    </div> -->

                <a href="/admin/login" class="text-center">I already have a membership</a>
            </div>
            <!-- /.form-box -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.register-box -->

    <!-- jQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- jquery-validation -->
    <script src="/assets/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="/assets/plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/assets/dist/js/adminlte.min.js"></script>
</body>

</html>