<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
    <!-- <link rel="stylesheet" href="./assets/dist/css/reset-pwd.css"> -->
    <style type="text/css">
        html{
            overflow-x: hidden;
            scroll-behavior: smooth;
            padding-top: 2rem;
        }
        body{
            background-color: rgb(215, 229, 230);
        }
        p{
            font-size: 15px;
        }
        .reset-password{
            background-color: #fff;
            padding: 40px;
            width: 50%;
            margin: 2rem auto;
        }
        .logo , .footer{
            /*display: flex;*/
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .logo{
            margin-top: 2rem;
            margin-bottom: -60px;
        }
        .btn-small{
            /*display: flex;*/
            text-align: center;
            align-items: center;
            justify-content: center;
        }
        .repwd > .error{
            color: #f01;
        }
        .btn-small > button{
            border: none;
            outline: none;
            padding: 12px 20px;
            width: 200px;
            background: rgb(14, 13, 13);
            color: #fff;
            border-radius: 5px;
            margin: 5px;
        }
        .footer > p{
            color: rgb(124, 123, 123);
            margin-bottom: 2rem;
        }
        @media only screen and (max-width: 600px){
            .reset-password{
                background-color: #fff;
                padding: 40px;
                width: 70%;
                margin: 8rem auto 2rem auto;
            }
            p{
                font-size: 19px;
                line-height: 1.5;
            }
            h1{
                font-size: 50px;
            }
            .btn-small{
                display: flex;
                align-items: flex-start;
                justify-content: flex-start;
            }
            .btn-small > button{
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
        <img src="https://www.saipali.education/wp-content/uploads/2020/06/saipali-logo_new.png" alt="no-img">
    </div>
    <div class="reset-password">
        <div class="repwd">
            <h1>Hello!</h1>
            <p>
                You are receiving this email because we received a Password
                reset request for your account.
            </p>
            <div class="btn-small">
                <a href=""><button id="reset-password">Reset Password</button></a>
            </div>
            <p class="error">This Password reset link will expire in 60 minutes</p>
            <p>If you did not request a password reset, no further action is requred.</p>
            <p>Regards,</p>
            <p><b>Laravel</b></p>
            <hr>
            <p>
                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: http://127.0.0.1:8000/password/reset/5079e7eaa64a99fec65715f58b3fe809454982520b20696300a28378ffc0616a?email=elekudaniel98%40gmail.com
            </p>
        </div>
    </div>
    <!-- footer -->
    <div class="footer">  
        <p class="footer">&copy; 2022 Laravel. All rights reserved.</p>
    </div>
</body>
</html>