<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
            <?php if (strtolower($token) != "notification") : ?>
                <h2>Hello
                    <?php
                    # check only for password rest
                    if ($password === 'reset') {
                        # code...
                        $names = preg_split("/ /", $data['name']);
                    } else {
                        if (strtolower($menu) == 'staff') {
                            $names = preg_split("/ /", $data['staff_name']);
                        } else {
                            $names = preg_split("/ /", $data['name']);
                        }
                    }
                    $firstname = $names[0];
                    if (count($names) == 3) {
                        $lastname = $names[count($names) - 2] . " " . $names[count($names) - 1];
                    } else {
                        $lastname = $names[count($names) - 1];
                    }
                    echo $lastname; ?>,
                </h2>
            <?php endif; ?>
            <?php if (strtolower($token) == "signup") : ?><!-- signup email -->
                <p>
                    Welcome to <?= $data['system_name'] ?>. We're excited to have you on board. <br>
                    Below are the brief details of your account, personal along with your login credentials to help you get started
                </p>
                <p>
                    <b>Account Information:</b> <br>
                    Account Type: <?= $data['account'] ?> <br>
                    Account Name: <?= $data['business_name'] ?> <br>
                    Account Code: <?= $data['code'] ?> <br>
                    Business Contact: <?= $data['business_contact'] ?> <br>
                    Business Address: <?= $data['business_address'] ?> <br>
                </p>
                <p>
                    <b>Personal Information:</b> <br>
                    Full Name: <?= $data['staff_name'] ?> <br>
                    Contact: <?= $data['mobile'] ?> <br>
                    Address: <?= $data['address'] ?> <br>
                    Branch Name: <?= $data['branch_name'] ?> <br>
                    Position: <?= $data['position'] ?> <br>
                </p>
                <p>
                    <b>Login credentials:</b> <br>
                    Email: <?= $data['email'] ?> <br>
                    Password: <?= $password ?>
                </p>
                <p>You can log in to your account by clicking the button below.</p>
                <div class="btn-small">
                    <a href="<?= base_url(); ?>/admin">
                        <button id="reset-password">
                            Login
                        </button>
                    </a>
                </div>

                <p>
                    We're confident <b><?= $data['system_name'] ?></b> will be a valuable tool for you and your <?= $data['account'] ?>. We look forward to seeing what you achieve!
                </p>
                <p>Sincerely,</p>
                <p><b>The <?= $data['system_name'] ?> Team</b></p>
                <hr>
                <p>
                    If you're having trouble clicking the "Login" button, copy and paste
                    the URL below into your web browser: <br>
                    <?= base_url('/admin'); ?>
                </p>
                
            <?php
            elseif (strtolower($token) == "registration") : ?><!-- registration email -->
                <p>
                    Welcome to <?= $settings['business_name'] ?> <br>
                </p>
                <p>
                    You have successfully been registered as a(an) <b><?= strtolower($data['account_type']) ?></b>
                    <?php if (strtolower($data['account_type']) != 'client') : ?>
                        department <b><?= strtolower($data['department_name']) ?></b>, position <b><?= strtolower($data['position']) ?></b>
                        <?php endif; ?>.
                </p>
                <p>
                    Log into your account to get started. Login credentials are; <br>
                    Email: <?= $data['email'] ?> <br>
                    Password: <?= $password ?>
                </p>
                <p> Click the button below to log into your account.</p>
                <div class="btn-small">
                    <a href="<?= base_url(); ?>">
                        <button id="reset-password">
                            Login
                        </button>
                    </a>
                </div>
                <p>Regards,</p>
                <p><b>Administrator</b></p>
                <hr>
                <p>
                    If you're having trouble clicking the "Login" button, copy and paste
                    the URL below into your web browser: <br>
                    <?= base_url(); ?>
                </p>
                
            <?php
            elseif (strtolower($token) == 'transaction') : ?><!-- transaction email -->
                <?php if (strtolower($data['account_typeID']) == 3) : ?> <!-- repayment -->
                    <p>
                        A <b><?= $data['payment_mode'] . " " . $data['type']; ?></b> of <b><?= $settings['currency'] . " " . $settings['symbol'] . " " . number_format($data['amount'], 2); ?></b> towards clearing your <b><?= $data['repayment_frequency'] . " " . $data['product_name']; ?></b> loan valid for a period of <b><?= $data['repayment_period'] . " " . $data['repayment_duration']; ?></b> until <b><?= $data['expiry_day'] . ", " . $data['loan_expiry_date']; ?></b>, disbursement code <b><?= $data['disbursement_code']; ?></b> has been processed by <?= $settings['business_name']; ?> via <b><?= $data['branch_name']; ?></b> branch on <?= $data['date']; ?>. Reference ID <b><?= $data['ref_id']; ?></b>.
                    </p>
                    <p>Your current loan standing is as follows;</p>
                    <hr>
                    <p><strong>Collected</strong></p>
                    <p>Interest Collected: <?= $settings['currency'] . " " . $settings['symbol'] . " " . number_format($data['interestCollected'], 2); ?></p>
                    <p>Principal Collected: <?= $settings['currency'] . " " . $settings['symbol'] . " " . number_format($data['principalCollected'], 2); ?></p>
                    <p>Total Collected: <?= $settings['currency'] . " " . $settings['symbol'] . " " . number_format($data['totalCollected'], 2); ?>
                    </p>
                    <p><strong>Balances</strong></p>
                    <p>Interest Balance: <?= $settings['currency'] . " " . $settings['symbol'] . " " . number_format(($data['interestBalance']), 2); ?></p>
                    <p>Principal Balance: <?= $settings['currency'] . " " . $settings['symbol'] . " " . number_format(($data['principalBalance']), 2); ?></p>
                    <p>Total Loan Outstanding Balance: <?= $settings['currency']  . " " . $settings['symbol'] . " " . number_format(($data['totalLoanBalance']), 2); ?>
                    </p>
                    <p> <strong>Status</strong></p>
                    <p>Total Days: <?= $data['loan_period_days']; ?></p>
                    <p>Days Covered: <?= $data['days_covered']; ?></p>
                    <p>Days Remaing: <?= $data['days_remaining']; ?></p>
                    <p>Status: <?= $data['status']; ?></p>
                    <p>Class: <?= $data['class']; ?></p>
                    <p>Comment: <?= $data['comments']; ?></p>
                    <hr>
                <?php endif; ?>
                <?php if (strtolower($data['account_typeID']) == 8) : ?> <!-- shares -->
                    <p>
                        A <b><?= $data['particular_name'] . " " . $data['type']; ?></b> of <b><?= $settings['currency'] . " " . $settings['symbol'] . " " . number_format($data['amount'], 2); ?></b> paid through <b><?= $data['payment_mode'] ?></b> has been processed by <?= $settings['business_name'] ?> via <b><?= $data['branch_name'] ?></b> on <?= $data['date']; ?>. Reference ID <b><?= $data['ref_id'] ?></b>.
                    </p>
                <?php endif; ?>
                <?php if (strtolower($data['account_typeID']) == 12) : ?> <!-- savings -->
                    <p>
                        A <b><?= $data['particular_name'] . " " . $data['type']; ?></b> of <b><?= $settings['currency'] . " " . $settings['symbol'] . " " . number_format($data['amount'], 2); ?></b> paid through <b><?= ($data['payment_mode']) ?></b> has been processed by <?= $settings['business_name'] ?> via <b><?= $data['branch_name'] ?></b> on <?= $data['date']; ?>. Reference ID <b><?= $data['ref_id'] ?></b>.<br>Transaction details <b><?= $data['entry_details'] ?></b>
                    </p>
                    <p>Your new account balance is <b><?= $settings['currency']  ?>. <?= number_format($data['balance'], 2) ?><?= $settings['symbol']  ?></b></p>
                <?php endif; ?>
                <?php if (strtolower($data['account_typeID']) == 18) : ?> <!-- application -->
                    <p>
                        A <b><?= $data['particular_name'] . " " . $data['type']; ?></b> of <b><?= $settings['currency'] . " " . $settings['symbol'] . " " . number_format($data['amount'], 2); ?></b> paid through <b><?= $data['payment_mode'] ?></b>, Loan Application Code: <b><?= $data['application_code']; ?></b>, for a(an) <b><?= $data['repayment_frequency'] . " " . $data['product_name']; ?></b> valid for a duration of <b><?= $data['repayment_period'] . " " . $data['repayment_duration']; ?></b>, has been processed by <?= $settings['business_name'] ?> via <b><?= $data['branch_name'] ?></b> branch on <?= $data['date']; ?>. Reference ID <b><?= $data['ref_id'] ?></b>.
                    </p>
                    <p>
                        Total amount payable is <b><?= $settings['currency']  ?>. <?= number_format($data['charge'], 2) ?><?= $settings['symbol']  ?></b>, your total amount paid is <b><?= $settings['currency']  ?>. <?= number_format(($data['totalCollected']), 2) ?><?= $settings['symbol']  ?></b> and balance is <b><?= $settings['currency']  ?>. <?= number_format((($data['charge']) - $data['totalCollected']), 2) ?><?= $settings['symbol']  ?></b>
                    </p>
                <?php endif; ?>
                <?php if (strtolower($data['account_typeID']) == 24) : ?> <!-- membership -->
                    <p>
                        A <b><?= $data['particular_name'] . " " . $data['type']; ?></b> of <b><?= $settings['currency'] . " " . $settings['symbol'] . " " . number_format($data['amount'], 2); ?></b> paid through <b><?= $data['payment_mode'] ?></b> has been processed by <?= $settings['business_name'] ?> via <b><?= $data['branch_name'] ?></b> branch on <?= $data['date']; ?>. Reference ID <b><?= $data['ref_id'] ?></b>.
                    </p>
                <?php endif; ?>
                <p>Click the button below to log into your account and continue using our other servirces</p>
                <div class="btn-small">
                    <a href="<?= base_url(); ?>">
                        <button id="reset-password">
                            Login
                        </button>
                    </a>
                </div>
                <p>Regards,</p>
                <p><b>Administrator</b></p>
                <hr>
                <p>
                    If you're having trouble clicking the "Login" button, copy and paste
                    the URL below into your web browser: <br>
                    <?= base_url(); ?>
                </p>
                
            <?php
            elseif (strtolower($token) == 'application') : ?><!-- loan application notification email -->
                <?php if (strtolower($data['module']) == 'apply') : ?> <!-- application -->
                    <p>This is to notify you that loan application for a(an) <b><?= $data['frequency'] . " " . $data['product_name'] ?></b> loan of <b><?= $settings['currency']  ?>. <?= number_format($data['principal'], 2); ?><?= $settings['symbol']  ?></b> at an interest rate of <b><?= $data['rate']; ?></b> valid for a period of <b><?= $data['period']; ?></b>, purpose <b><?= $data['purpose'] ?></b> was submitted successfully via <?= $settings['business_name'] . " " . $data['branch_name'] ?></b> branch on <?= $data['date']; ?>. Your application code is <b><?= $data['application_code'] ?></b></p>
                    <p>Processing of your application shall start as soon as possible. You will be notified of each operation made during the entire period of processing your application</p>
                <?php endif;
                if (strtolower($data['module']) == 'processing') : ?> <!-- processing started -->
                    <p>This is to notify you that Processing of your loan application of <b><?= $settings['currency']  ?>. <?= number_format($data['principal'], 2) ?> <?= $settings['symbol']  ?></b> for a(an) <b><?= $data['repayment_frequency'] . " " . $data['product_name'] ?></b> valid for a duration of <b><?= $data['repayment_period'] . " " . $data['repayment_duration']; ?></b>, Application Code: <b><?= $data['application_code'] ?></b>, Application Code: <b><?= $data['application_code'] ?></b>, purpose <b><?= $data['purpose'] ?></b> via <b><?= $settings['business_name'] . " " . $data['branch_name'] ?></b> branch on <?= $data['date']; ?>. You will be notified of each operation made during the entire period of processing your application</p>
                <?php endif;
                if (strtolower($data['module']) == 'remarks') : ?> <!-- status/remarks update -->
                    <p>This is to notify you that action was taken towards your loan application of <b><?= $settings['currency']  ?>. <?= number_format($data['principal'], 2) ?> <?= $settings['symbol']  ?></b> for a(an) <b><?= $data['repayment_frequency'] . " " . $data['product_name'] ?></b> valid for a duration of <b><?= $data['repayment_period'] . " " . $data['repayment_duration']; ?></b>, Application Code: <b><?= $data['application_code'] ?></b>, purpose <b><?= $data['purpose'] ?> via <b><?= $settings['business_name'] . " " . $data['branch_name'] ?></b> branch on <?= $data['date']; ?>.</p><br>
                    <h4>Action Details</h4>
                    <p>Status: <i><?= $data['newStatus'] ?></i></p>
                    <p>Level: <i><?= $data['newLevel'] ?></i></p>
                    <p>Action: <i><?= $data['newAction'] ?></i></p>
                    <p>Remarks: <i><?= $data['remarks'] ?></i></p>
                    <?php if (strtolower(($data['newStatus']) == 'approved')) : ?>
                        <p>You will be able to access your loan funds after loan is issued. This shall be done in the shortest time possible.</p>
                    <?php endif; ?>
                <?php endif; ?>
                <p><b>Note:</b> All loan durations are automatically converted to the product frequency.</p>
                <p>Click the button below to log into your account and continue using our other servirces</p>
                <div class="btn-small">
                    <a href="<?= base_url(); ?>">
                        <button id="reset-password">
                            Login
                        </button>
                    </a>
                </div>
                <p>Regards,</p>
                <p><b>Administrator</b></p>
                <hr>
                
            <?php
            elseif (strtolower($token) == 'disbursement') : ?><!-- loan issue notification email -->
                <p>This is to notify you that your application for a(an) <b><?= $data['frequency'] . " " . $data['product_name'] ?></b> loan of <b><?= $settings['currency']  ?>. <?= number_format($data['principal'], 2); ?><?= $settings['symbol']  ?></b> at an interest rate of <b><?= $data['rate']; ?></b> valid for a period of <b><?= $data['period']; ?></b>, purpose <b><?= $data['purpose'] ?></b> has been <b>disbursed</b> via <b><?= $settings['business_name'] . " " . $data['branch_name'] ?></b> branch on <?= $data['date']; ?>.</p>
                <p>Below are the details of of new loan</p><br>
                <hr>
                <p>Loan principal <b><?= $data['principal'] ?></b>, total interest <b><?= $data['actual_interest'] ?></b>, total loan <b><?= $data['actual_repayment'] ?></b>, disbursement code <b><?= $data['disbursement_code'] ?></b>, cycle <b><?= $data['cycle'] ?></b> number of installments <b><?= $data['installments_num'] ?></b>, installment amount <b><?= $data['actual_installment'] ?></b></p>
                <p>Loan expiry date <b><?= $data['loan_expiry_date'] ?></b>, total loan days <b><?= $data['loan_period_days'] ?></b>, first recovery date <b><?= $data['first_recovery'] ?></b>, grace period <b><?= $data['grace_period'] ?></b> days.</p>
                <hr><br>
                <?php if (strtolower($data['disbursed_by']) == 'deposited to account') : ?>
                    <p>Your funds have been deposited into your account.</p>
                <?php else : ?>
                    <p>Collect your funds from our staff before leaving.</p>
                <?php endif; ?>
                
            <?php
            elseif (strtolower($token) == "passwords") : ?><!-- password change email notification -->
                <?php if (strtolower($data['menu']) == 'regenerate') : ?>
                    <p>
                        You are receiving this email because you requested for a new login password for your account. <br>
                        Your new login in Password is: <b><?= $password ?></b><br>
                    </p>
                <?php else : ?>
                    <p>
                        Your are receiving this email because your account's login password was changed. <br>
                        Your new credentials are: <br>
                        Email: <?= $data['email'] ?> <br>
                        Password: <?= $password ?>
                    </p>
                    <p>
                        If you didn't do ths yourself, please login and change your password. Ingore this email if it was you.
                    </p>
                <?php endif; ?>
                <p> Click the button below to log into your account.</p>
                <div class="btn-small">
                    <a href="<?= base_url(); ?>">
                        <button id="reset-password">
                            Login
                        </button>
                    </a>
                </div>
                <p>Regards,</p>
                <p><b>Administrator</b></p>
                <hr>
                <p>
                    If you're having trouble clicking the "Login" button, copy and paste
                    the URL below into your web browser: <br>
                    <?= base_url(); ?>
                </p>
                
            <?php
            elseif (strtolower($token) == "access_status") : ?><!-- active status email notification -->
                <?php if (strtolower($data['status']) == 'active') : ?>
                    <p>
                        You are receiving this email to let you know that your account has been activated <br>
                        You can now log into your account and start doing your operations.
                    </p>
                <?php else : ?>
                    <p>
                        You are receiving this email to let you know that your account has been de-activated and therefore, you can no longer log into your account for the time being until it is activated again.
                    </p>
                <?php endif; ?>
                <p> Click the button below to log into your account.</p>
                <div class="btn-small">
                    <a href="<?= base_url(); ?>">
                        <button id="reset-password">
                            Login
                        </button>
                    </a>
                </div>
                <p>Regards,</p>
                <p><b>Administrator</b></p>
                <hr>
                <p>
                    If you're having trouble clicking the "Login" button, copy and paste
                    the URL below into your web browser: <br>
                    <?= base_url(); ?>
                </p>
                
            <?php
            elseif (strtolower($token) == "notification") : ?><!-- notification email -->
                <?= $data; ?>
                <?php if ($attachments != null && count($attachments) > 0) :
                    // convert attachment size
                    function convertBytes($bytes, $precision = 2)
                    {
                        $units = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");

                        $exp = floor(log($bytes, 1024)) | 0;

                        return round($bytes / (1024 ** $exp), $precision) . ' ' . $units[$exp];
                    }
                ?>
                    <!-- <div class="btn-small" style="display: inline-block;"> -->
                    <?php foreach ($attachments as $attachment) : ?>
                        <button class="download-btn">
                            <a href="<?= base_url('/admin/attachments/download/' . $attachment["id"]); ?>">
                                <?= $attachment['attachment'] . '(' . convertBytes($attachment['size']) . ')'; ?>
                            </a>
                        </button>
                    <?php endforeach; ?>
                    <!-- </div> -->
                <?php endif ?>
                
            <?php
            elseif (strtolower($token) == "2fa") : ?><!-- 2 step authentification email -->
                <p>A signin attempt requires further verification because we want to proctect your account. To complete the signin, please enter the verification code on the OTP page screen as shown below.
                </p>
                <div class="btn-small">
                    <a href="javascript:void(0)">
                        <button id="reset-password">
                            <?= $data["user_token"]; ?>
                        </button>
                    </a>
                </div>
                <p class="error">
                    <b>The verification code will valid up to <?= $data["duration"]; ?> minutes.</b>
                </p>
                <p>Regards,</p>
                <p><b>Administrator</b></p>
                <hr>
                
            <?php
            else: ?><!-- password reset email -->
                <p>
                    You are receiving this email because we received a password reset request for your account.
                </p>
                <div class="btn-small">
                    <a href="<?= base_url('/admin/account/password/reset/' . $data["id"] . '/' . $token . '/' . preg_replace("/(?!^).(?=[^@]+@)/", "*", $data["email"])); ?>">
                        <button id="reset-password">
                            Reset Password
                        </button>
                    </a>
                </div>
                <p class="error">This <b>Password Reset Link</b> will expire in <b>5 minutes</b></p>
                <p>If you did not request a password reset, no further action is requred.</p>
                <p>Regards,</p>
                <p><b>Administrator</b></p>
                <hr>
                <p>
                    If you're having trouble clicking the "Reset Password" button, copy and paste
                    the URL below into your web browser: <br>
                    <?= base_url('/admin/account/password/reset/' . $data["id"] . '/' . $token . '/' .
                        preg_replace("/(?!^).(?=[^@]+@)/", "*", $data["email"])); ?>
                </p>
                
            <?php endif; ?>
        </div>
    </div>
    <!-- footer -->
    <div class="footer">
        <p class="footer">&copy; <?= date('Y'); ?> Microfinance. All rights reserved.</p>
    </div>
</body>

</html>