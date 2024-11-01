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

<body">
    <div class="wrapper border border-3 p-3">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3 class='text-bold'>
                    <?= $settings['business_name']. ' '. $settings['system_name']; ?>
                </h3>
                <h4 class='text-bold'><?= $title ?></h4>
                <h5 class=''>Date: <?= date('l, d F, Y'); ?></h5>
                <img src="/uploads/logo/<?= $settings['business_logo'] ?>" alt="Logo" class="brand-image img-center" style="position:absolute; top:0; left:0;width:100px;height:100px;height: 100px; width: 100px; opacity: 0.8;" />
                <br>
            </div>
        </div> <hr>
        <div class="row">
            <div class="col-md-12">
                <?php if ($id == 0) : ?>
                    <form action="#" id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="<?= $id?>" name="id" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6" id="category_id" style="display: none;">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Category</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="category_id" id="category_id" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="subcategory_id" style="display: none;">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">SubCategory</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="subcategory_id" id="subcategory_id" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular Name</label>
                                        <div class="col-md-12">
                                            <input name="particular_name" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular Module</label>
                                        <div class="col-md-12">
                                            <select name="module" class="form-control select2bs4" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="pl-3 text-info">
                                <input type="checkbox" name="checkbox" id="checkboxId" onclick="isChecked()"> Set Charge Fee.
                            </p>
                            <div class="row" id="addCharged">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Charge Method</label>
                                        <div class="col-md-12 d-flex">
                                            <input type="checkbox" class="form-check" name="" id="">&nbsp; Amount &nbsp;
                                            <input type="checkbox" class="form-check" name="" id="">&nbsp; Percent &nbsp;
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Expected Charge[<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="charge" class="form-control" type="number" min="" max="">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" id="initialBalance">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Opening Balance</label>
                                        <div class="col-md-12">
                                            <input type="number" name="opening_balance" class="form-control" min="0">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular Status</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="particular_status" id="particular_status" readonly>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else : 
                $balance = 0;
                if($particular['part'] == 'debit'){
                    $balance = (($particular['opening_balance'] + $particular['debit']) - $particular['credit']);
                    if($balance < 0){
                        $balance = '('.number_format(abs($balance)).')';
                    }else{
                        $balance = number_format($balance);
                    }
                } else{
                    $balance = ($particular['debit'] - ($particular['opening_balance'] + $particular['credit']));
                    if($balance <= 0){
                        $balance = number_format(abs($balance));
                    }else{
                        $balance = '('.number_format($balance).')';
                    }
                }
                ?>
                    <form action="#" id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="<?= $id?>" name="id" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Category Name</label>
                                        <div class="col-md-12">
                                            <input name="category_name" value="<?= $particular['category_name'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">SubCategory Name</label>
                                        <div class="col-md-12">
                                            <input name="subcategory_name" value="<?= $particular['subcategory_name'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular Name</label>
                                        <div class="col-md-12">
                                            <input name="particular_name" value="<?= $particular['particular_name'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular Module</label>
                                        <div class="col-md-12">
                                            <input name="module" value="<?= $particular['module'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular Slug</label>
                                        <div class="col-md-12">
                                            <input name="particular_slug" value="<?= $particular['particular_slug'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular Status</label>
                                        <div class="col-md-12">
                                            <input name="particular_status" value="<?= $particular['particular_status'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Statement</label>
                                        <div class="col-md-12">
                                            <input name="statement" value="<?= $particular['statement'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Part</label>
                                        <div class="col-md-12">
                                            <input name="part" value="<?= $particular['part'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Opening Balance</label>
                                        <div class="col-md-12">
                                            <input name="opening_balance" value="<?= number_format($particular['opening_balance']) ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Debit</label>
                                        <div class="col-md-12">
                                            <input name="debit" value="<?= number_format($particular['debit']) ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Credit</label>
                                        <div class="col-md-12">
                                            <input name="credit" value="<?= number_format($particular['credit']) ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Balance</label>
                                        <div class="col-md-12">
                                            <input name="balance" value="<?= $balance ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="charged">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Expected Charge[<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input name="charge" value="<?= $particular['charge'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Charge Method</label>
                                        <div class="col-md-12">
                                            <input name="charge_method" value="<?= $particular['charge_method'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Created At</label>
                                        <div class="col-md-12">
                                            <input name="created_at" value="<?= $particular['created_at'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Updated At</label>
                                        <div class="col-md-12">
                                            <input name="updated_at" value="<?= $particular['updated_at'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
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