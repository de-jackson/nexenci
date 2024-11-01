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
            <div class="col-1"></div>
            <div class="col-10">
                <div class="table-responsive">
                    <table id="cashflow" class="table table-sm table-stripped">
                        <thead class="bg-secondary">
                            <tr>
                                <td><b>Particulars</b></td>
                                <td align="right"><b>Amount</b>[<?= $settings['currency']?>]</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- load all cash flow type -->
                            <?php if (count($statements['cashFlowData']) > 0) :
                                $cashflowBalance = 0;

                                foreach ($statements['cashFlowData'] as $cashflow_type) :
                            ?>
                            <tr>
                                <!-- filter 1 cashflow- types -->
                                <th class=" text-info text-bold p-3" colspan="2">
                                    Cash Flow from <?= $cashflow_type['name'] ?>
                                </th>
                                <!-- particulars for each cash flow type -->
                                <?php
                                if (count($statements['particularData']) > 0) :
                                    foreach ($statements['particularData'] as $particular) :
                                        // filter cashflow particulars -->
                                        if ($particular['cash_flow_typeId'] == $cashflow_type ['id']) :
                                ?>
                                <!-- particulars and thier totals -->
                                <tr>
                                    <td class="pl-5">
                                        <a href="/admin/statements/particular-statement/<?= $particular['id'] .'/'. $start_date .'/'. $end_date; ?>" class="text"><i><?= $particular['particular_name'] ?></i></a>
                                    </td>
                                    <!-- particular totals -->
                                    <td align="right">
                                        <!-- print total for each particular -->
                                        <i><?= $particular['balance']; ?> </i>
                                    </td>
                                </tr>
                                <?php endif; ?> <!-- #end filter those that belong to 2 subcategories -->
                                <?php endforeach; ?> <!-- #end particulars loop -->
                                <?php else : ?> <!-- particular counter else  -->
                                <tr>
                                    <td colspan="6" class="text-center">No Particulars found</td>
                                </tr>
                                <?php endif; ?> <!-- #end particulars counter -->
                            </tr>
                            <!-- cashflow-type totals -->
                            <tr>
                                <td class=" text-info text-bold p-3">
                                    <i>Total Cash Flow From  <?= $cashflow_type['name'] ?></i>
                                </td>
                                <td class="text-info" align="right">
                                    <!-- print total for each cash flow type -->
                                    <i><u><?= $cashflow_type['balance']; ?> </u></i>
                                </td>
                            </tr>
                            <?php endforeach;?>   <!-- #end cash flow types loop -->
                            <?php else: ?>  <!-- cash flow types counter else -->
                                <tr><th class="text-center" colspan="2">No Cash Flow Types Found</th></tr>
                            <?php endif; ?>  <!-- #end cash flow types counter -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-1"></div>
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