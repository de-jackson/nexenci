<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? esc($title): ''; ?></title>

    <link rel="icon" type="image/png" href="/uploads/logo/<?= isset($settings) ? $settings['business_logo'] : 'default.jpg'; ?>" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css" />
    <!-- daterange picker -->
    <link rel="stylesheet" href="/assets/plugins/daterangepicker/daterangepicker.css" />
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" />
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" />
  <!-- summernote -->
  <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css" />
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" />
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="/assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css" />
    <!-- BS Stepper -->
    <link rel="stylesheet" href="/assets/plugins/bs-stepper/css/bs-stepper.min.css" />
    <!-- dropzonejs -->
    <link rel="stylesheet" href="/assets/plugins/dropzone/min/dropzone.min.css" />
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/assets/dist/css/adminlte.min.css" />
</head>

<body>
    <div class="wrapper border border-3 p-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
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
                                        <?= $settings['business_contact'] . ' || ' . $settings['business_alt_contact']; ?>
                                    </p>
                                    <p class="fw-semibold mb-2 fs-12">
                                        <?= $settings['business_email']; ?>
                                    </p>
                                </div>
                                <h6 class="fw-bold text-default"><?= str_replace('-', ' ', $title) ?> Statement</h6>
                                <h6 class="fw-bold text-default">
                                    For a Period Between &nbsp;&nbsp; <i><u><?= date('F d, Y', strtotime($start_date)); ?></u></i> &nbsp;&nbsp; and &nbsp;&nbsp; <i><u><?= date('F d, Y', strtotime($end_date)); ?></u></i>
                                </h6>
                            </div>
                        </div> <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-sm table-stripped" style="width: 100%;">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th rowspan="2">Date</th>
                                                <th rowspan="2">Type</th>
                                                <!-- <th rowspan="2">Status</th>
                                                <th rowspan="2">Description</th> -->
                                                <th colspan="2" class="text-center border-left border-right">Opening Balance[<?= $settings['currency']; ?>]</th>
                                                <th colspan="2" class="text-center border-left border-right">Transactions[<?= $settings['currency']; ?>]</th>
                                                <th colspan="2" class="text-center border-left border-right">Closing Balance[<?= $settings['currency']; ?>]</th>
                                                <!-- <th rowspan="2" class="text-center"><i>#Ref ID</i></th> -->
                                            </tr>
                                            <tr>
                                                <th class=" border-left text-right">Debit</th>
                                                <th class="text-right">Credit</th>
                                                <th class="border-left text-right">Debit</th>
                                                <th class="text-right">Credit</th>
                                                <th class="border-left text-right">Debit</th>
                                                <th class="border-right text-right">Credit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $opening = $particular['opening_balance'];
                                            $balanceTotal = $debitAmount = $creditAmount = $debitOpening = $creditOpening = 0;
                                            $debit = $credit = $debitClosing = $creditClosing = 0;
                                            $opningDebitbal = $opningCreditbal = 0;
                                            // assing initial opening balance
                                            if(strtolower($particular['part']) == 'debit'){
                                                $opningDebitbal = $debitAmount = $opening;
                                                $creditOpening = 0;
                                            } else{
                                                $opningCreditbal = $creditAmount = $opening;
                                                $debitOpening = 0;
                                            }
                                            // credit opening
                                            if($opningCreditbal >= 0){
                                                $creditOpening = $opningCreditbal;
                                            }else{
                                                $debitOpening = $opningDebitbal;
                                            }
                                            // closing balance
                                            $balanceTotal = $debitAmount - $creditAmount;
                                            if($balanceTotal >= 0){
                                                $debitClosing = $balanceTotal;
                                            }else{
                                                $creditClosing = -($balanceTotal);
                                            }
                                            if(count($entries) > 0):
                                                echo '<tr>' .
                                                    '<td>'. $particular['created_at'] .'</td>'.
                                                    '<td>Opening Balance</td>'.
                                                    /** '<td>'. $particular['part'] .'</td>'.
                                                    * '<td>'. $particular['particular_name']. " Opening Balance" .'</td>'. */
                                                    '<td class="text-right">'. number_format($debitOpening) .'</td>'.
                                                    '<td class="text-right">'. number_format($creditOpening) .'</td>' .
                                                    '<td class="text-right">'. number_format($debit) .'</td>'.
                                                    '<td class="text-right">'. number_format($credit) .'</td>' .
                                                    '<td class="text-right">'. number_format($debitClosing) .'</td>'.
                                                    '<td class="text-right">'. number_format($creditClosing) .'</td>' .
                                                    /* '<td>'.
                                                      '<a href="/admin/accounts/particular-row/'.$particular['id'].'"><i>#'.$particular['id'].'</i></a>'.
                                                     '</td>' . */
                                                '</tr>';
                                                foreach($entries as $entry){
                                                    // assing amount to respective part based on the transaction status
                                                    // put amount to debit if status is credit n $particular['id'] == payment_id
                                                    if (($entry['payment_id'] == $particular['id']) && ($entry['status'] == "credit")) {
                                                        $debit = $entry['amount'];
                                                        $credit = 0;
                                                        $debitAmount += $debit;
                                                    }
                                                    // put amount to credit if status is debit n $particular['id'] == payment_id
                                                    if (($entry['payment_id'] == $particular['id']) && ($entry['status'] == "debit")) {
                                                        $debit = 0;
                                                        $credit = $entry['amount'];
                                                        $creditAmount += $credit;
                                                    }
                                                    // put amount to credit if status is credit n $particular['id'] == particular_id
                                                    if (($entry['particular_id'] == $particular['id']) && ($entry['status'] == "credit")) {
                                                        $debit = 0;
                                                        $credit = $entry['amount'];
                                                        $creditAmount += $credit;
                                                    }
                                                    // put amount to credit if status is debit n $particular['id'] == particular_id
                                                    if (($entry['particular_id'] == $particular['id']) && ($entry['status'] == "debit")) {
                                                        $debit = $entry['amount'];
                                                        $credit = 0;
                                                        $debitAmount += $debit;
                                                    }
                                                    // debit opening
                                                    if ($balanceTotal >= 0) {
                                                        $debitOpening = $balanceTotal;
                                                        $creditOpening = 0;
                                                    } else {
                                                        $creditOpening = -($balanceTotal);
                                                        $debitOpening = 0;
                                                    }
                                                    // closing balance
                                                    $balanceTotal = ($debitAmount - $creditAmount);
                                                    if ($balanceTotal >= 0) {
                                                        $debitClosing = $balanceTotal;
                                                        $creditClosing = 0;
                                                    } else {
                                                        $creditClosing = -($balanceTotal);
                                                        $debitClosing = 0;
                                                    }
                                                    // set transaction status for payment && non-payment particulars
                                                    if ($particular['account_typeId'] == 1) { // payment particular
                                                        // reciprocate the status for payment particulars
                                                        if ($entry['status'] == "credit") {
                                                            $status = "debit";
                                                        }
                                                        if ($entry['status'] == "debit") {
                                                            $status = "credit";
                                                        }
                                                    } else { // non-payment particular
                                                        $status = $entry['status'];
                                                    }

                                                    if($entry['payment_id'] == $particular['id'] || $entry['particular_id'] == $particular['id']){
                                                        echo '<tr>'.
                                                            '<td>'. $entry['date'] .'</td>'.
                                                            '<td>'. $entry['type'] .'</td>'.
                                                            /*'<td>'. $entry['status'] .'</td>'.
                                                            '<td>'. $entry['entry_details'] .'</td>'.*/
                                                            '<td class="text-right">'. number_format($debitOpening) .'</td>'.
                                                            '<td class="text-right">'. number_format($creditOpening) .'</td>'.
                                                            '<td class="text-right">'. number_format($debit) .'</td>'.
                                                            '<td class="text-right">'. number_format($credit) .'</td>'.
                                                            '<td class="text-right">'. number_format($debitClosing) .'</td>'.
                                                            '<td class="text-right">'. number_format($creditClosing) .'</td>'.
                                                            /*'<td>'.
                                                                '<a href="/admin/transaction/info/'. $entry['ref_id'] .'" class="text"><i>#'. $entry['ref_id'] .'</i></a>'.
                                                            '</td>'. */
                                                        '</tr>';
                                                    }
                                                }
                                                if($balanceTotal < 0){
                                                    $balanceTotal = (number_format(-$balanceTotal))." <i>(Credit)</i>";
                                                }else{
                                                    $balanceTotal = number_format($balanceTotal)." <i>(Debit)</i>";
                                                }
                                                echo '<tr>'.
                                                    '<th colspan="4" class="text-center">Total['.$settings['currency'].']</th>'.
                                                    '<th class="text-right">'. number_format($debitAmount) .'</th>'.
                                                    '<th class="text-right">'. number_format($creditAmount) .'</th>'.
                                                    '<th class="text-center" colspan="2">Closing Balance: '. $balanceTotal .'</th>'.
                                                '</tr>';
                                            ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="8"><center>No Data found</center></td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p class="text-center">
            &copy;<?= date('Y'); ?> Sai Pali Infotech <?= $settings['system_name'] ?>. All rights reserved. 
        </p>
    </footer>
    <script>
        window.addEventListener("load", window.print());
        window.oncancel = function(){
            history.back(-1);
        }
        window.onafterprint = function() {
            history.back(-1);
        };
    </script>
</body>

</html>