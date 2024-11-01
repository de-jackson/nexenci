<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css" />
    <!-- <link rel="stylesheet" href="./assets/dist/css/reset-pwd.css"> -->
    <style type="text/css">
        html {
            overflow-x: hidden;
            scroll-behavior: smooth;
            padding-top: 2rem;
        }

        body {
            background-color: rgb(215, 229, 230);
        }

        p {
            font-size: 15px;
        }

        .reset-password {
            background-color: #fff;
            padding: 40px;
            width: 50%;
            margin: 2rem auto;
        }

        .logo,
        .footer {
            /*display: flex;*/
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .logo {
            margin-top: 2rem;
            margin-bottom: -60px;
        }

        .btn-small {
            /*display: flex;*/
            text-align: center;
            align-items: center;
            justify-content: center;
        }

        .repwd>.error {
            color: #f01;
        }

        .btn-small>a>button {
            border: none;
            outline: none;
            padding: 12px 20px;
            width: 200px;
            background: rgb(14, 13, 13);
            color: #fff;
            border-radius: 5px;
            margin: 5px;
        }

        .download-btn {
            background-color: #ddd;
            border: none;
            color: black;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 16px;
        }

        /* Darker background on mouse-over */
        .download-btn:hover {
            background-color: #f1f1f1;
        }

        .footer>p {
            color: rgb(124, 123, 123);
            margin-bottom: 2rem;
        }

        @media only screen and (max-width: 600px) {
            .reset-password {
                background-color: #fff;
                padding: 40px;
                width: 70%;
                margin: 8rem auto 2rem auto;
            }

            /* p{
                font-size: 19px;
                line-height: 1.5;
            } */
            h1 {
                font-size: 50px;
            }

            .btn-small {
                display: flex;
                align-items: flex-start;
                justify-content: flex-start;
            }

            .btn-small>a>button {
                color: #fff;
                border-radius: 5px;
                margin: 8px 0;
                padding: 15px;
                width: 100%;
                font-size: 19px;
            }
        }
    </style>
</head>

<body style="background-color: rgb(215, 229, 230); padding: 5px;">
    <div class="logo">
        <img src="<?= $settings['email_template_logo']; ?>" alt="no-img">
    </div>
    <div class="reset-password">
        <div class="repwd">
            <?= $message; ?>

            <p>Regards,</p>
            <p><b>Administrator</b></p>
            <hr>
        </div>
        <!-- footer -->
        <div class="footer">
            <p class="footer">&copy; <?= date('Y'); ?> Microfinance. All rights reserved.</p>
        </div>
    </div>
</body>

</html>