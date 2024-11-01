<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <!-- Title -->
    <title><?= isset($title) ? esc($title) : ''; ?></title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow">
    <meta name="author" content="<?= $settings['author']; ?>">
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords" content="<?= $settings['system_name'] . ',' . $settings['system_slogan']; ?>">
    <meta name="description" content="<?= $settings['system_name']; ?>">

    <meta property="og:title" content="<?= $settings['business_name']; ?>">
    <meta property="og:description" content="<?= $settings['business_name']; ?>">
    <meta property="og:image" content="<?= $settings['email_template_logo']; ?>">

    <!-- TWITTER META -->
    <meta name="twitter:title" content="<?= $settings['business_name']; ?>">
    <meta name="twitter:description" content="<?= $settings['business_name']; ?>">
    <meta name="twitter:image" content="<?= (isset($settings) && file_exists('uploads/logo/' . $settings['business_logo'])) ? '/uploads/logo/' . $settings['business_logo'] : '/assets/dist/img/default.jpg'; ?>">
    <meta name="twitter:card" content="summary_large_image">

    <!-- FAVICONS ICON -->
    <link rel="icon" type="image/png" href="<?= (isset($settings) && file_exists('uploads/logo/' . $settings['business_logo'])) ? '/uploads/logo/' . $settings['business_logo'] : '/assets/dist/img/default.jpg'; ?>" />

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Form step -->
    <link href="/assets/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css" rel="stylesheet">
    <link href="/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">

    <!-- Select2 -->
    <link rel="stylesheet" href="/assets/vendor/select2/css/select2.min.css">
    <script src="/assets/vendor/jquery/jquery.min.js"></script>
    <!-- intlTelInput -->
    <link rel="stylesheet" href="/assets/vendor/intl-tel-input@23.7.0/build/css/intlTelInput.css" />
    <script src="/assets/vendor/intl-tel-input@23.7.0/build/js/intlTelInput.min.js"></script>

</head>

<body class="vh-100">

    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <div class="col-lg-6 col-md-12 col-sm-12 mx-auto align-self-center">
                    <div class="login-form">
                        <?= $this->renderSection("content"); ?>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="pages-left h-100">
                        <div class="login-content">
                            <a href="/">
                                <img src="<?= (isset($settings) && file_exists('uploads/logo/' . $settings['business_logo'])) ? '/uploads/logo/' . $settings['business_logo'] : '/assets/dist/img/default.jpg'; ?>" class="mb-3 logo-dark img-fluid" alt="" style="height:100px">
                            </a>
                            <a href="/">
                                <img src="/assets/images/logi-white.png" class="mb-3 logo-light" alt="">
                            </a>

                            <p><?= $settings['business_name']; ?></p>
                        </div>
                        <div class="login-media text-center">
                            <img src="<?= (isset($settings) && file_exists('uploads/logo/background/' . $settings['background_logo'])) ? '/uploads/logo/background/' . $settings['background_logo'] : '/assets/dist/img/background.jpeg'; ?>" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--**********************************
	Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="/assets/vendor/global/global.min.js"></script>
    <script src="/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="/assets/js/deznav-init.js"></script>
    <script src="/assets/js/demo.js"></script>
    <script src="/assets/js/custom.js"></script>
    <script src="/assets/js/styleSwitcher.js"></script>
    <!-- jQuery -->
    <script src="/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/assets/vendor/jquery/jquery.cookie.min.js"></script>
    <!-- jquery-validation -->
    <script src="/assets/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="/assets/vendor/jquery-validation/additional-methods.min.js"></script>
    <!-- Select2 -->
    <script src="/assets/vendor/select2/js/select2.full.min.js"></script>
    <script src="/assets/vendor/intl-tel-input@23.7.0/index.js"></script>

    <?= $this->renderSection("scripts") ?>
</body>

</html>