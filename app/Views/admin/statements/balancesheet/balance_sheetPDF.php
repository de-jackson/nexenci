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
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="table-responsive">
                    <table id="balancesheet" class="table table-sm table-stripped">
                            <thead class="bg-secondary">
                                <tr>
                                    <td><b>Particulars</b></td>
                                    <td align="right"><b>Amount</b>[<?= $settings['currency']?>]</td>
                                </tr>
                            </thead>
                            <tbody id="balancesheet-body">
                                <!-- load all categories -->
                                <?php if (count($statements['categoryData']) > 0) :
                                    foreach ($statements['categoryData'] as $category) :
                                        // filter balance sheet items only
                                        if($category['statement_id'] == 1) :
                                ?>
                                <tr>
                                    <!-- filter 1 categories -->
                                    <th class=" text-info text-bold p-3" colspan="2">
                                        <?= $category['category_name'] ?>
                                    </th>
                                    <!-- load all subcategories -->
                                    <?php if (count($statements['subcategoryData']) > 0) :
                                        foreach ($statements['subcategoryData'] as $subcategory):
                                            // filter category subcategories -->
                                            if (($subcategory['category_id'] == $category['id'])) :
                                    ?>
                                    <!-- subcategories for each category -->
                                    <tr>
                                        <td colspan="2" class=" text-bold pl-4">
                                            <?= $subcategory['subcategory_name'] ?>
                                        </td>
                                    </tr>
                                    <!-- particulars for each subcategory -->
                                    <?php
                                    if (count($statements['particularData']) > 0) :
                                        foreach ($statements['particularData'] as $particular) :
                                            // filter subcategory particulars -->
                                            if (($particular['subcategory_id'] == $subcategory['id'])) :
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
                                    <?php endif; ?> <!-- #end filter subcategory particulars  -->
                                    <?php endforeach; ?> <!-- #end particulars loop -->
                                    <?php else : ?> <!-- particular counter else -->
                                    <tr>
                                        <td colspan="2" class="text-center">No Particulars found</td>
                                    </tr>
                                    <?php endif; ?> <!-- #end particular counter -->
                                    <!-- subcategory total -->
                                    <tr>
                                        <td class="pl-4">
                                            <i><u>Total <?= $subcategory['subcategory_name'] ?></u></i>
                                        </td>
                                        <td align="right">
                                            <!-- print total for each subcategory -->
                                            <i><u> <?= $subcategory['balance']; ?> </u></i>
                                        </td>
                                    </tr>
                                    <?php endif; ?> <!-- #end filter category subcategories  -->
                                    <?php endforeach; ?> <!-- #end subcategories loop -->
                                    <?php else : ?> <!--  subcategories counter else -->
                                    <tr>
                                        <td colspan="2" class="text-center">No Subcategories found</td>
                                    </tr>
                                    <?php endif; ?> <!-- #end subcategories counter -->
                                </tr>
                                <!-- category totals -->
                                <tr>
                                    <td class=" text-info text-bold p-3">
                                        <i>Total <?= $category['category_name'] ?></i>
                                    </td>
                                    <td class="text-info" align="right">
                                        <!-- print total for each particular -->
                                        <i><u><?= $category['balance']; ?> </u></i>
                                    </td>
                                </tr>
                                <!-- put surplus under equity -->
                                <?php if($category['id'] == 2): ?>
                                    <tr>
                                        <td>Retained Earning</td> <td align="right"><?= ($statements['getTotals']['grossIncome']); ?> </td>
                                    </tr>
                                    <tr class="text-info">
                                        <td>Equity + Retained</td> <td align="right"><?= ($statements['getTotals']['equitySurplusTotal']); ?> </td>
                                    </tr>
                                <?php endif; ?> <!-- #end put surplus under equity -->
                                <?php endif; ?> <!-- #end filter balance sheet items -->
                                <?php endforeach;?> <!-- #end categories loop -->
                                <!-- compare assets and equity + liability -->
                                <tr class="text-bold">
                                    <td align="center">
                                        Total Assets
                                    </td>
                                    <td align="center">
                                        Equity + Liabilities
                                    </td>
                                </tr>
                                <tr class="<?= (($statements['getTotals']['assetsTotal'] == $statements['getTotals']['equityLiabilityTotalSurplus']) ? 'text-success' : 'text-danger'); ?>">
                                    <td align="center" align="">
                                        <?= $statements['getTotals']['assetsTotal']; ?> 
                                    </td>
                                    <td align="center">
                                        <?= $statements['getTotals']['equityLiabilityTotalSurplus']; ?> 
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <tr><th class="text-center" colspan="2">No Categories Found</th></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                </div>
            </div>
            <div class="col-md-1"></div>
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