<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <!-- Title -->
    <title><?= isset($title) ? esc($title) : ''; ?></title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Nexen">
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords" content="Nexen">
    <meta name="description" content="Nexen">

    <meta property="og:title" content="Nexen">
    <meta property="og:description" content="Nexen">
    <meta property="og:image" content="/assets/dist/img/default.jpg">

    <!-- TWITTER META -->
    <meta name="twitter:title" content="Nexen">
    <meta name="twitter:description" content="Nexen">
    <meta name="twitter:image" content="/assets/dist/img/default.jpg">
    <meta name="twitter:card" content="summary_large_image">

    <!-- FAVICONS ICON -->
    <link rel="icon" type="image/png" href="/assets/dist/img/default.jpg" />

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">

</head>

<body class="vh-100">

    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="error-page">
                        <div class="error-inner text-center">
                            <?= $this->renderSection("content"); ?>
                            <div>
                                <a href="javascript: void(0)" class="btn btn-secondary" onclick="history.back(-1);">
                                    BACK TO THE PREVIOUS PAGE
                                </a>
                            </div>
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
    <?= $this->renderSection("scripts") ?>
</body>

</html>