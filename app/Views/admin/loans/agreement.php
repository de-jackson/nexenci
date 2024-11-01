<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= isset($title) ? esc($title) : ''; ?></title>
    <meta name="Description" content="<?= $settings['system_slogan']; ?>">
    <meta name="Author" content="<?= $settings['author']; ?>">
    <meta name="keywords" content="<?= $settings['system_name'] . ',' . $settings['system_slogan']; ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= (isset($settings) && file_exists('uploads/logo/' . $settings['business_logo'])) ? '/uploads/logo/' . $settings['business_logo'] : '/assets/dist/img/default.jpg'; ?>" />

    <!-- Choices JS -->
    <script src="/dist/assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- Main Theme Js -->
    <script src="/dist/assets/js/main.js"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="/dist/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Style Css -->
    <link href="/dist/assets/css/styles.min.css" rel="stylesheet">

    <!-- Icons Css -->
    <link href="/dist/assets/css/icons.css" rel="stylesheet">

    <!-- Node Waves Css -->
    <link href="/dist/assets/libs/node-waves/waves.min.css" rel="stylesheet">

    <!-- Simplebar Css -->
    <link href="/dist/assets/libs/simplebar/simplebar.min.css" rel="stylesheet">

    <!-- Color Picker Css -->
    <link rel="stylesheet" href="/dist/assets/libs/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="/dist/assets/libs/@simonwep/pickr/themes/nano.min.css">

    <!-- Choices Css -->
    <link rel="stylesheet" href="/dist/assets/libs/choices.js/public/assets/styles/choices.min.css">

</head>

<body>
    <?php
    function convert_number($number = null)
    {
        if (($number < 0) | ($number > 999999999)) {
            return "Number is out of range";
        }
        $Gn = floor($number / 1000000);
        /* Millions (giga) */
        $number -= $Gn * 1000000;
        $kn = floor($number / 1000);
        /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);
        /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);
        /* Tens (deca) */
        $n = $number % 10;
        /* Ones */
        $res = "";
        if ($Gn) {
            $res .= convert_number($Gn) .  " Million";
        }
        if ($kn) {
            $res .= (empty($res) ? "" : " ") . convert_number($kn) . " Thousand";
        }
        if ($Hn) {
            $res .= (empty($res) ? "" : " ") . convert_number($Hn) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($Dn | $n) {
            if (!empty($res)) {
                $res .= " and ";
            }
            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];
                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }
        if (empty($res)) {
            $res = "zero";
        }
        return $res;
    }
    ?>
    <div class="page m-3 p-3">
        <div class="" id="">
            <h6 class="fw-bold text- text-center">Loan Agreement</h6>
            <div class="row gx-3 gy-2 align-items-center mt-0">
                <div class="col-8 text-start">
                    <p class="fw-bold text-default terms-heading"><u>OUR REF: <?= $data['code']; ?></u></p>
                    <div class="d-inline-block">
                        <img src="<?= isset($settings) && file_exists('uploads/logo/' . $settings['business_logo']) && $settings['business_logo'] ? '/uploads/logo/' . $settings['business_logo'] : '/assets/dist/img/default.jpg'; ?>" class="brand-image" style="width: 100px; height: 100px; opacity: 0.8;" alt="Logo" />
                    </div>
                    <div class="d-inline-block align-middle px-3 py-3">
                        <h6 class="fw-bold text-default"><?= $settings['business_name'] . '(' . $settings['business_abbr'] . ')'; ?></h6>
                        <p class="fw-semibold mb-2 fs-12">
                            <?= $settings['business_address']; ?>
                        </p>
                        <p class="fw-semibold mb-2 fs-12">
                            <?= $settings['business_pobox']; ?>
                        </p>
                        <p class="fw-semibold mb-2 fs-12">
                            <?= $settings['business_contact'] . ' | ' . $settings['business_alt_contact']; ?>
                        </p>
                        <p class="fw-semibold mb-2 fs-12">
                            <?= $settings['business_email']; ?>
                        </p>
                    </div>
                </div>
                <div class="col-4 text-start">
                    <p class="fw-bold text-default terms-heading"><u>YOUR REF: <?= $data['account_no']; ?></u></p>
                    <div class="mb-4">
                        <p class="fw-semibold mb-2 fs-16">
                            <?= (strtolower($data['gender']) == 'male') ? 'Mr. ' : 'Mrs. '; ?><?= $data['name']; ?>
                        </p>
                        <p class="mb-2 fs-14">
                            <i class="fas fa-home"></i>&nbsp;<?= $data['residence']; ?>
                        </p>
                        <p class="mb-2 fs-14">
                            <i class="fas fa-phone"></i>&nbsp;<?= $data['mobile'] . ' | ' . $data['alternate_no']; ?>
                        </p>
                        <p class="mb-2 fs-14">
                            <i class="fas fa-envelope"></i>&nbsp;<?= $data['email']; ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- <div class="row gx-3 gy-2 align-items-center mt-0">
                <div class="col-md-12 text-center">
                    <div class="d-inline-block">
                        <img src="<?= isset($settings) && file_exists('uploads/logo/' . $settings['business_logo']) && $settings['business_logo'] ? '/uploads/logo/' . $settings['business_logo'] : '/assets/dist/img/default.jpg'; ?>" class="brand-image" style="width: 100px; height: 100px; opacity: 0.8;" alt="Logo" />
                    </div>
                    <div class="d-inline-block align-middle px-3 py-3">
                        <h6 class="fw-bold text-default"><?= $settings['business_name'] . '(' . $settings['business_abbr'] . ')'; ?></h6>
                        <p class="fw-semibold mb-2 fs-12">
                            <?= $settings['business_address']; ?>
                        </p>
                        <p class="fw-semibold mb-2 fs-12">
                            <?= $settings['business_pobox']; ?>
                        </p>
                        <p class="fw-semibold mb-2 fs-12">
                            <?= $settings['business_contact'] . ' | ' . $settings['business_alt_contact']; ?>
                        </p>
                        <p class="fw-semibold mb-2 fs-12">
                            <?= $settings['business_email']; ?>
                        </p>
                    </div>
                    <h6 class="fw-bold text-default">Loan Agreement</h6>
                </div>
            </div>
            <h6 class="fw-bold pb-3 text-default op-7">
                <span class="terms-heading"><u>Private & Confidential:</u></span>
            </h6>
            <div class="mb-4">
                <p class="fw-semibold mb-2 fs-16">
                    <?= (strtolower($data['gender']) == 'male') ? 'Mr. ' : 'Mrs. '; ?><?= $data['name']; ?>
                </p>
                <p class="mb-2 fs-14">
                    <i class="fas fa-home"></i>&nbsp;<?= $data['residence']; ?>
                </p>
                <p class="mb-2 fs-14">
                    <i class="fas fa-phone"></i>&nbsp;<?= $data['mobile'] . ' | ' . $data['alternate_no']; ?>
                </p>
                <p class="mb-2 fs-14">
                    <i class="fas fa-envelope"></i>&nbsp;<?= $data['email']; ?>
                </p>
            </div> -->
            <hr>

            <h6 class="fw-bold pb-2 text-default">
                <span class="terms-heading">
                    <u>RE: <?= strtoupper($data['product_name'])  . ' FACILITY FOR ' . $settings['currency'] . ' ' . number_format($data['principal']); ?>
                        <span class="float-end">
                            <?= ($data['date_disbursed'] ? date('d M Y', strtotime($data['date_disbursed'])) : '__________________') ?>
                        </span></u>
                </span>
            </h6>

            <p class="mb-0">
                <span class="fw-semibold"><?= $settings['business_name']; ?></span> here refered to as <span class="fw-semibold"><?= $settings['business_abbr']; ?></span> is pleased to inform you of its willingness to give this <span class="fw-semibold">loan facility (“The Facility”)</span> to <span class="fw-semibold"><?= $data['name']; ?> (“The Borrower”)</span> outlined below on the terms and conditions set out in this letter of offer.
            </p>

            <div class="mb-4">
                <p class="fw-bold mb-2 fs-14">
                    Below are the terms and conditions subject to the loan granted to you.
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">1. Loan amount:</span> <?= $settings['currency'] . ' <span class="fw-semibold">' . number_format($data['principal']) . '</span>'; ?>= (<?= convert_number($data['principal']); ?>).
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">2. Purpose:</span> <?= $data['purpose']; ?>.
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">3. Term/Period:</span> <?= $data['repayment_period'] . '(' . convert_number($data['repayment_period']) . ')' . ' ' . $data['repayment_duration']; ?> from the date of disbursement.
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">4. Frequency of payment:</span> <?= $data['repayment_frequency']; ?>.
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">5. Total number of loan installments:</span> <?= $data['installments_num'] . '(' . convert_number($data['installments_num']) . ')'; ?> .
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">6. Interest Rate:</span> <?= $data['interest_rate'] . ' % per ' . $data['interest_period']; ?> calculated on a <?= $data['interest_type']; ?> basis.
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">7. Total amount to repay (principal + interest):</span> <?= $settings['currency'] . ' ' . number_format($data['actual_repayment']) . '(' . convert_number($data['actual_repayment']) . ')'; ?>.
                </p>
                <?php if (count($charges) > 0 && count($loanCharges) > 0) : ?>
                    <p class="mb-2">
                        <b>8. Total Loan charges:</b> <?= $settings['currency'] . ' ' . $data['total_charges'] . '= (' . convert_number($data['total_charges']) . ')'; ?> including the following;
                    <ul class="list-group list-group-horizontal">
                        <?php
                        foreach ($charges as $charge) :
                            foreach ($loanCharges as $index => $loanCharge) :
                                $lanCharge = $loanCharge['charge'];
                                $chargeMethod = $loanCharge['charge_method'];
                                $particularId = $loanCharge['particular_id'];
                                if ($charge['particular_id'] == $particularId) :
                        ?>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <b><?= $charge['particular_name'] ?></b>, charge
                                                <i>
                                                    <?= $lanCharge; ?>
                                                    <?= (strtolower($chargeMethod) == 'amount') ? $settings['currency'] : '% of the principal'; ?>
                                                </i>
                                            </div>
                                        </div>
                                    </li>
                        <?php
                                endif;
                            endforeach;
                        endforeach;
                        ?>
                    </ul>
                    </p>
                <?php endif; ?>
                <p class="mb-2">
                    <span class="fw-semibold">9. Actual Amount received less charges (1–8)</span> is <?= $settings['currency'] . ' ' . number_format(($data['principal'] - $data['total_charges'])) . '= (' . convert_number(($data['principal'] - $data['total_charges'])) . ')'; ?>.
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">10. Proposed loan disbursement date:</span> <?= ($data['date_disbursed'] ? date('d M Y', strtotime($data['date_disbursed'])) : '__________________') ?>.
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">11.</span> Installments will be due as per the term stated above. And will be repaid in equal installments of <span class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($data['actual_installment']); ?></span> per installment payable <span class="fw-semibold">starting <?= $data['grace_period']; ?> days from the date of disbursement</span> until the loan is repaid in full.
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">12.</span> The Borrower agrees to make deposits of <span class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($data['actual_repayment']) . '(' . convert_number($data['actual_repayment']) . ')'; ?>:</span> into <?= $settings['business_abbr'] ?> account during the entire term of the loan and as long as you are a borrower.
                </p>
            </div>

            <div class="mb-4">
                <p class="fw-bold mb-2 fs-14">
                    13. Security
                </p>
                <p class="mb-2">
                    The Facility will be secured by the pledged chattels/collateral of <span class="fw-semibold"><?= $data['security_item'] ?></span> with estimated values of <span class="fw-semibold"><?= $settings['currency'] . ' ' . number_format($data['est_value']) . '(' . convert_number($data['est_value']) . ')' ?> </span>
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">Description: </span> <?= strip_tags($data['security_info']) ?>
                </p>

                <p class="mb-2 fw-semibold">Personal Guarantor(s):</p>
                <div class="row">
                    <div class="col-6">
                        <p class="mb-2">
                            <span class="fw-semibold">1.</span> <?= $data['ref_name'] . ' (' . $data['ref_relation'] . ')'; ?>, <?= $data['ref_job']; ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-phone"></i>&nbsp;<?= $data['ref_contact'] . ' | ' . $data['ref_alt_contact']; ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-envelope"></i>&nbsp;<?= $data['ref_email']; ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-home"></i>&nbsp;<?= $data['ref_address']; ?>
                        </p>
                    </div>
                    <div class="col-6">
                        <p class="mb-2">
                            <span class="fw-semibold">2.</span> <?= $data['ref_name2'] . ' (' . $data['ref_relation2'] . ')'; ?>, <?= $data['ref_job2']; ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-phone"></i>&nbsp;<?= $data['ref_contact2'] . ' | ' . $data['ref_alt_contact2']; ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-envelope"></i>&nbsp;<?= $data['ref_email2']; ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-home"></i>&nbsp;<?= $data['ref_address2']; ?>
                        </p>
                    </div>
                </div>

                <p class="fw-semibold mb-0">
                    <u class="fw-bold">Disbursement:</u> <br>
                    The full amount shall be <span class="fst-italic"><?= (($data['disbursed_by']) ? $data['disbursed_by'] : '____________________________________'); ?></span> and the loan charges shall be debited from <span class="fst-italic"><?= $data['reduct_charges'] ?></span> and the balance credited on your loan account held with <?= $settings['business_name'] ?>
                </p>
                <p class="fw-semibold mb-0">
                    <u class="fw-bold">Note:</u><br>
                    This agreement takes precedence over the terms of any security whether entered into before or after the execution of this agreement whether such security restricts the Bank’s rights under this agreement or not.
                </p>
            </div>

            <div class="mb-4">
                <p class="fw-bold mb-2 fs-14">
                    COVENANTS
                </p>

                <p class="fw-semibold mb-2">
                    The Borrower covenants with <?= $settings['business_abbr']; ?> that:
                </p>
                <p class="mb-2">
                    1. If the borrower shall default in payment of any one or more of the monthly repayments, the whole sum outstanding together with interest thereof shall become immediately due and payable on demand.
                </p>
                <p class="mb-2">
                    2. All recovery costs of any installment due on demand shall be met by The Borrower provided it does not exceed 10,000= per every visit to either The Borrower home/business location.
                </p>
            </div>

            <div class="mb-4">
                <p class="fw-bold mb-2 fs-14">
                    GENERAL TERMS AND CONDITIONS
                </p>

                <p class="mb-2">
                    <span class="fw-semibold">1. OPERATIONAL PRICING.</span>
                    All transactions arising from the operation of the above facility or the provision by the <?= $settings['business_abbr'] ?> of other services will be subject to the schedule of fees as set by <?= $settings['business_abbr'] ?> from time to time unless otherwise agreed with <?= $settings['business_abbr'] ?>
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">2. APPLICATION OF MONEY.</span>
                    Should there be any delays more than one week from date of payment, <?= $settings['business_abbr'] ?> shall automatically recover from your cash cover to clear any outstanding interest, penalties and any loan balance owing to the you without notifying you.
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">3. COST EXPENSES AND FEES.</span>
                    The borrower agrees that all costs and expenses whatsoever including legal and auctioneers costs connected with the recovery or attempted recovery of moneys shall be borne by the customer/ borrower.
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">4. PAYMENT ON DEMAND.</span>
                    Should you default in payment of the facility in full or part thereof, <?= $settings['business_abbr'] ?> may be forced to recall the whole loan by written notice to that effect, payable either upon demand or within a period stated in the notice.
                </p>
                <p class="mb-2">
                    <span class="fw-semibold">5. EXPIRY OF FACILITY.</span>
                    This loan agreement remains in force and effect till the loan is completed.
                    Kindly signify your acceptance of the loan terms and conditions contained in this agreement by appending your signature in the space specified below.
                </p>
            </div>

            <div class="mb-0">
                <p class="mb-2">
                    I have read, understood and do hereby accept the loan Arrangements stated herein and upon the terms and conditions subject to the covenants set out in this loan facility.
                </p>
                <div class="row">
                    <div class="col-4">
                        <p class="fw-semibold mb-2 fs-14">Client</p>
                        <p class="mb-0">
                            NAME: <br>
                            <span class="fw-bold pl-2">
                                <?= ($data['name'] ? $data['name'] : '_______________________________________') ?>
                            </span>
                            <br>
                            SIGNATURE: <br>
                            <?php if ($data['sign']) : ?>
                                <img src="/uploads/clients/signatures/<?= $data['sign']; ?>" alt="Signature" class="img-fluid thumbnail" style="width: 95px; height: 95px;" />
                            <?php else : ?>
                                _______________________________________
                            <?php endif; ?>
                            <br>
                            DATE: <br>
                            <?= ($data['date_disbursed'] ? date('d M Y', strtotime($data['date_disbursed'])) : '___________________________________') ?>
                        </p>
                    </div>
                    <div class="col-4">
                        <p class="fw-semibold mb-2 fs-14">Loan Officer</p>
                        <p class="mb-0">
                            NAME: <br>
                            <span class="fw-bold pl-2">
                                <?= ($data['staff_name'] ? $data['staff_name'] : '___________________________________') ?>
                            </span>
                            <br>
                            SIGNATURE: <br>
                            <?php if ($data['sign'] && file_exists('uploads/staffs/employees/signatures/' . $data['signature'])) : ?>
                                <img src="/uploads/staffs/employees/signatures/<?= $data['signature']; ?>" alt="Signature" class="img-fluid thumbnail" style="width: 140px; height: 140px;" />
                            <?php else : ?>
                                _______________________________________
                            <?php endif; ?>
                            <br>
                            DATE: <br> <?= ($data['date_disbursed'] ? date('d M Y', strtotime($data['date_disbursed'])) : '___________________________________') ?>
                        </p>
                    </div>
                    <div class="col-4">
                        <p class="fw-semibold mb-2 fs-14">General Manager</p>
                        <p class="mb-0">
                            NAME: <br> _______________________________________
                            <br>
                            SIGNATURE: <br> _______________________________________
                            <br>
                            DATE: <br> <?= ($data['date_disbursed'] ? date('d M Y', strtotime($data['date_disbursed'])) : '___________________________________') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener("load", window.print());
        window.oncancel = function() {
            window.close();
        }
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>

</html>