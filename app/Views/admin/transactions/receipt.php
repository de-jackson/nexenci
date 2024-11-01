<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? esc($title) : ''; ?></title>

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
            <div class="col-md-1"></div>
            <div class="col-md-10 text-center">
                <h3 class='text-bold'>
                    <?= $settings['business_name'] . ' ' . $settings['system_name']; ?>
                </h3>
                <h4 class='text-bold'>
                    <?= $entry['branch_name']; ?> Branch
                </h4>
                <h5 class='text-bold'><?= $title; ?></h5>
                <?php if (isset($settings) && file_exists('uploads/logo/' . $settings['business_logo']) && $settings['business_logo']) : ?>
                    <img src="/uploads/logo/<?= $settings['business_logo']; ?>" class="brand-image img-center" style="position:absolute; top:0; left:0;width:100px;height:100px;height: 100px; width: 100px; opacity: 0.8;" alt="Logo" />
                <?php else : ?>
                    <img src="/assets/dist/img/default.jpg" class="brand-image img-center" style="position:absolute; top:0; left:0;width:100px;height:100px;height: 100px; width: 100px; opacity: 0.8;" alt="Logo" />
                <?php endif; ?>
                <br>
            </div>
            <div class="col-md-1"></div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="border p-3">
                    <?php if ($entry['client_id'] != null) {
                        $name = $entry['name'];
                    } else {
                        $name = $settings['business_name'];
                    }
                    ?>
                    <h5>
                        <b>Name: </b><?= '<i><u>' . $name . '</u></i>'; ?>
                    </h5>
                    <h5>
                        <b>Particular: </b><?= '<i><u>' . $entry['particular_name'] . '</u></i>'; ?>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" style="width: 100%;">
                        <thead class="bg-">
                            <tr>
                                <th>S.No</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount [<?= $settings['currency']; ?>]</th>
                                <th>Ref ID</th>
                                <th>Balance[<?= $settings['currency']; ?>]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><?= $entry['date']; ?></td>
                                <td><?= $entry['type']; ?></td>
                                <td><?= number_format($entry['amount']); ?></td>
                                <td><?= $entry['ref_id']; ?></td>
                                <td><?= number_format($balance); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="border p-3">
                    <p>
                        <span>Paid In:</span> &nbsp;&nbsp;&nbsp;
                        <strong><?= $entry['payment']; ?></strong>
                    </p>
                    <div class="row">
                        <div class="col-6">
                            <p>
                                <span><?= $entry['type']; ?> For:</span> &nbsp;&nbsp;&nbsp;
                                <strong><?= $name; ?></strong>
                            </p>
                            <p>
                                <span>Signature:</span> &nbsp;&nbsp;&nbsp;
                                <?php
                                if ($entry['sign'] && $entry['client_id'] != null) :
                                ?>
                                    <img src="<?= base_url('uploads/clients/signatures/' . $entry['sign']); ?>" alt="signature" class="img-fluid thumbnail" style="width: 140px; height: 140px;">
                                <?php else : ?>
                                    <span><i><?= $name; ?></i></span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-6">
                            <p>
                                <span>Recieved By:</span> &nbsp;&nbsp;&nbsp;
                                <strong><?= $entry['staff_name']; ?></strong>
                            </p>
                            <p>
                                <span>Signature:</span> &nbsp;&nbsp;&nbsp;
                                <?php
                                if (strtolower($entry['account_type']) == "employee") {
                                    $path = "uploads/staffs/employees/signatures/";
                                } else {
                                    $path = "uploads/staffs/admins/signatures/";
                                }
                                if ($entry['signature']) :
                                ?>
                                    <img src="<?= base_url($path . $entry['signature']); ?>" alt="signature" class="img-fluid thumbnail" style="width: 140px; height: 140px;">
                                <?php else : ?>
                                    <span><i><?= $entry['staff_name']; ?></i></span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p class="text-center">
            &copy;<?= date('Y') .' '. $settings['system_name'] ?>. All rights reserved.
        </p>
        <p class='float-right'>Printed <?= date('l, d-M, Y') ?></p>
    </footer>
    <script>
        window.addEventListener("load", window.print());
        window.oncancel = function() {
            history.back(-1);
        }
        window.onafterprint = function() {
            history.back(-1);
        };
    </script>
</body>

</html>