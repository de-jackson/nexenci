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
                <img src="/uploads/logo/<?= $settings['business_logo'] ?>" alt="Logo" class="brand-image img-center" style="height: 100px; width: 100px; opacity: 0.8;" />
                <br>
                <div class="">
                    <h4><?= $settings['business_name'] ?></h4>
                    <h5 class=""><?= $title ?></h5>
                    <p><i>Printed: <?= date('H:i:s. D d-M, Y') ?></i>.</p>
                </div>
            </div>
        </div><hr>
        <div class="row">
            <div class="col-md-12">
                <?php if ($id == 0 && strtolower($menu) == 'user') : ?>
                    <form id="form" autocomplete="off">
                        <input type="hidden" readonly name="id">
                        <div id="userRow">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label>Full Name</label>
                                                    <input type="text" name="name" class="form-control" >
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label>Email Address</label>
                                                    <input type="text" name="email" class="form-control" >
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <label>Phone Number</label>
                                                    <input type="text" name="phone" class="form-control">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-12" for="profileImage">PASSPORT PHOTO</label>
                                        <div class="col-sm-12">
                                            <center>
                                                <img  src="/assets/dist/img/nophoto.jpg" alt="Photo"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                            </center>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label">Address</label>
                                    <textarea name="address" class="form-control"></textarea>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Access Status</label>
                                            <select name="access_status" class="form-control select2">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Branch Name</label>
                                            <select id="branch_id" name="branch_id" class="form-control select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($id != 0 && strtolower($menu) == 'user') : ?>
                    <form id="viewform" autocomplete="off">
                        <input type="hidden" readonly name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>Full Name</label>
                                        <input type="text" name="name" class="form-control" value="<?= $user['name']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>Email Address</label>
                                        <input type="text" name="email" class="form-control" value="<?= $user['email']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>Phone Number</label>
                                        <input type="text" name="vphone" class="form-control" value="<?= $user['mobile']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control"><?= $user['address']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>Access Status</label>
                                        <input type="text" name="vaccess_status" class="form-control" value="<?= $user['access_status']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>Branch Name</label>
                                        <input type="text" name="vbranch_name" class="form-control" value="<?= $user['branch_name']; ?>">
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($id == 0 && strtolower($menu) == 'log') : ?>
                    <form class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Username</label>
                                        <div class="col-md-12">
                                            <input name="vuser_id" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Login Time</label>
                                        <div class="col-md-12">
                                            <input name="vlogin_at" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Logout Time</label>
                                        <div class="col-md-12">
                                            <input name="vlogout_at" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Browser</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vbrowser" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Broswer Version</label>
                                        <div class="col-md-12">
                                            <input name="vbrowser_version" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Operating System</label>
                                        <div class="col-md-12">
                                            <input name="voperating_system" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">IP Address</label>
                                        <div class="col-md-12">
                                            <input name="vip_address" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Duration</label>
                                        <div class="col-md-12">
                                            <input name="vduration" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Status</label>
                                        <div class="col-md-12">
                                            <input name="vstatus" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Log Info</label>
                                        <div class="col-md-12">
                                            <textarea name="vloginfo" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Latitude</label>
                                        <div class="col-md-12">
                                            <input name="vlatitude" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Longitude</label>
                                        <div class="col-md-12">
                                            <input name="vlongitude" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Location</label>
                                        <div class="col-md-12">
                                            <input name="vlocation" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Referrer Link</label>
                                        <div class="col-md-12">
                                            <textarea name="vreferrer_link" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($id != 0 && strtolower($menu) == 'log') : ?>
                    <form class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Username</label>
                                        <div class="col-md-12">
                                            <input name="vuser_id" value="<?= $log['name'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Login Time</label>
                                        <div class="col-md-12">
                                            <input name="vlogin_at" value="<?= $log['login_at'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Logout Time</label>
                                        <div class="col-md-12">
                                            <input name="vlogout_at" value="<?= $log['logout_at'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Browser</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vbrowser" class="form-control" value="<?= $log['browser'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Broswer Version</label>
                                        <div class="col-md-12">
                                            <input name="vbrowser_version" value="<?= $log['browser_version'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Operating System</label>
                                        <div class="col-md-12">
                                            <input name="voperating_system" value="<?= $log['operating_system'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">IP Address</label>
                                        <div class="col-md-12">
                                            <input name="vip_address" value="<?= $log['ip_address'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Duration</label>
                                        <div class="col-md-12">
                                            <input name="vduration" value="<?= $log['duration'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Status</label>
                                        <div class="col-md-12">
                                            <input name="vstatus" value="<?= $log['status'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Log Info</label>
                                        <div class="col-md-12">
                                            <textarea name="vloginfo" class="form-control"><?= $log['loginfo'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Latitude</label>
                                        <div class="col-md-12">
                                            <input name="vlatitude" value="<?= $log['latitude'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Longitude</label>
                                        <div class="col-md-12">
                                            <input name="vlongitude" value="<?= $log['longitude'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Location</label>
                                        <div class="col-md-12">
                                            <input name="vlocation" value="<?= $log['location'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Referrer Link</label>
                                        <div class="col-md-12">
                                            <textarea name="vreferrer_link" class="form-control"><?= $log['referrer_link'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Created Date</label>
                                        <div class="col-md-12">
                                            <input type="text" name="created_at" class="form-control" value="<?= $log['created_at'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Updated At</label>
                                        <div class="col-md-12">
                                            <input type="text" name="updated_at" class="form-control" value="<?= $log['updated_at'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($id == 0 && strtolower($menu) == 'activity') : ?>
                    <form class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Username</label>
                                        <div class="col-md-12">
                                            <input name="vuser_id" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Module</label>
                                        <div class="col-md-12">
                                            <input name="vmodule" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Referrer ID</label>
                                        <div class="col-md-12">
                                            <input name="vreferrer_id" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Action</label>
                                        <div class="col-md-12">
                                            <textarea name="vaction" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Created Date</label>
                                        <div class="col-md-12">
                                            <input type="text" name="created_at" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Updated At</label>
                                        <div class="col-md-12">
                                            <input type="text" name="updated_at" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($id != 0 && strtolower($menu) == 'activity') : ?>
                    <form action="#" id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Username</label>
                                        <div class="col-md-12">
                                            <input name="vuser_id" value="<?= $activity['name']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Module</label>
                                        <div class="col-md-12">
                                            <input name="vmodule" value="<?= $activity['module']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Referrer ID</label>
                                        <div class="col-md-12">
                                            <input name="vreferrer_id" value="<?= '#'.$activity['referrer_id']; ?>" class="form-control text-info" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Action</label>
                                        <div class="col-md-12">
                                            <textarea name="vaction" class="form-control"><?= $activity['action']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Created At</label>
                                        <div class="col-md-12">
                                            <input type="text" name="created_at" value="<?= $activity['created_at']; ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Updated At</label>
                                        <div class="col-md-12">
                                            <input type="text" name="updated_at" value="<?= $activity['updated_at']; ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
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