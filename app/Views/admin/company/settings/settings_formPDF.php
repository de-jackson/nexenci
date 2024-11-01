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
                    <form id="settingsForm" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <div class="form-body">
                            <!-- business settings -->
                            <div class="card" id="businessSettings">
                                <div class="card-header">
                                    <h5 class="card-title">Business Bio</h5>
                                </div>
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Business Settings</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="post clearfix">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group text-center">
                                                            <div class="col-sm-12">
                                                                <center>
                                                                    <img  src="/uploads/users/nophoto.jpg" alt="Photo"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                                                </center>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Name</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_name" id="business_name" class="form-control" value="<?= $settings['business_logo'] ?>">
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Short Name</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_abbr" id="business_abbr" class="form-control" value="<?= $settings['business_logo'] ?>">
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Contact</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_contact" id="business_contact" class="form-control" value="<?= $settings['business_logo'] ?>">
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Alt Contact</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_alt_contact" id="business_alt_contact" class="form-control" value="<?= $settings['business_logo'] ?>[alt]">
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Email</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_email" id="business_email" class="form-control" value="<?= $settings['business_logo'] ?>">
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Website</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_web" id="business_web" class="form-control" value="<?= $settings['business_logo'] ?>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Postal Address</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="business_pobox" id="business_pobox" class="form-control" value="<?= $settings['business_logo'] ?>">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Physical Address</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="business_address" id="business_address" class="form-control" value="<?= $settings['business_logo'] ?>">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Tax(%)</label>
                                                            <div class="col-md-12">
                                                                <input type="number" name="tax" id="tax" class="form-control" value="<?= $settings['business_logo'] ?> min="0">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Slogan</label>
                                                            <div class="col-md-12">
                                                                <textarea type="text" name="business_slogan" id="business_slogan" class="form-control" value="<?= $settings['business_logo'] ?>"></textarea>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Business About</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="post clearfix">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">About Us</label>
                                                            <div class="col-md-12">
                                                                <textarea name="business_about" value="<?= $settings['business_logo'] ?> Vision, Objectives" class="form-control" id="addSummernote">

                                                                </textarea>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
                    <form id="settingsForm" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <div class="form-body">
                            <!-- business settings -->
                            <div class="card" id="businessSettings">
                                <div class="card-header">
                                    <h5 class="card-title">Business Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Business Bio</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="post clearfix">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group text-center">
                                                            <div class="col-sm-12">
                                                                <?php if($settings['business_logo'] != ''): ?>
                                                                    <center>
                                                                        <img  src="/uploads/logo/<?= $settings['business_logo'] ?>" alt="logo"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                                                    </center>
                                                                <?php else: ?>
                                                                    <center>
                                                                        <img  src="/uploads/users/nophoto.jpg" alt="logo"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                                                    </center>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Name</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_name" id="business_name" class="form-control" value="<?= $settings['business_name'] ?>">
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Short Name</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_abbr" id="business_abbr" class="form-control" value="<?= $settings['business_abbr'] ?>">
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Contact</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_contact" id="business_contact" class="form-control" value="<?= $settings['business_contact'] ?>">
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Alt Contact</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_alt_contact" id="business_alt_contact" class="form-control" value="<?= $settings['business_alt_contact'] ?>[alt]">
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Email</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_email" id="business_email" class="form-control" value="<?= $settings['business_email'] ?>">
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-12">Website</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" name="business_web" id="business_web" class="form-control" value="<?= $settings['business_web'] ?>
                                                                        <span class="help-block text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Postal Address</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="business_pobox" id="business_pobox" class="form-control" value="<?= $settings['business_pobox'] ?>">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Physical Address</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="business_address" id="business_address" class="form-control" value="<?= $settings['business_address'] ?>">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Tax Rate(%)</label>
                                                            <div class="col-md-12">
                                                                <input type="number" name="tax_rate" id="tax_rate" class="form-control" value="<?= $settings['tax_rate'] ?>" min="0">
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Slogan</label>
                                                            <div class="col-md-12">
                                                                <textarea type="text" name="business_slogan" id="business_slogan" class="form-control"><?= $settings['business_slogan'] ?></textarea>
                                                                <span class="help-block text-danger"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Business About</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="post clearfix">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <?= $settings['business_about'] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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