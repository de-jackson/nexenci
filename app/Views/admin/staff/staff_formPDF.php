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
                    <form id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly name="id" />
                        <input type="hidden" readonly name="account_type" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Full Name</label>
                                                <div class="col-md-12">
                                                    <input name="staff_name" class="form-control" type="text">
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Gender</label>
                                                <div class="col-md-12 d-flex">
                                                    <input type="checkbox" class="form-check" name="" id="">&nbsp; Male &nbsp;
                                                    <input type="checkbox" class="form-check" name="" id="">&nbsp; Female &nbsp;
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Nationality</label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="appointment_type" style="width: 100%;">
                                                    
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Marital Status</label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="appointment_type" style="width: 100%;">
                                                    
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Religion</label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="appointment_type" style="width: 100%;">
                                                    
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-12 text-center" for="profileImage">PASSPORT PHOTO</label>
                                        <div class="col-sm-12">
                                            <center>
                                                <img  src="/assets/dist/img/nophoto.jpg" alt="Photo"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                            </center>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Phone Number 1</label>
                                        <div class="col-md-12">
                                            <input name="mobile" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Phone Number 2</label>
                                        <div class="col-md-12">
                                            <input name="alternate_mobile" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Email Address</label>
                                        <div class="col-md-12">
                                            <input name="email" class="form-control" type="email">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Employe D.O.B</label>
                                        <div class="col-md-12">
                                            <input name="date_of_birth" class="form-control" type="date">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Department Name</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="department_id" id="department_id" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Position</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="position_id" id="position_id" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">ID Type</label>
                                        <div class="col-md-12 d-flex">
                                            <input type="checkbox" class="form-check" name="" id="">&nbsp;National Id&nbsp;
                                            <input type="checkbox" class="form-check" name="" id="">&nbsp;Passport&nbsp;
                                            <input type="checkbox" class="form-check" name="" id="">&nbsp;Driver Licence&nbsp;
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">ID Number</label>
                                        <div class="col-md-12">
                                            <input name="id_number"" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Id Expiry Date</label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input name="id_expiry" class="form-control" type="date">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Address</label>
                                        <div class="col-md-12">
                                            <textarea name="address" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Qualifications</label>
                                        <div class="col-md-12">
                                            <textarea name="qualifications" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Branch Name</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="branch_id" id="branch_id" style="width: 100%;">

                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Salary [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="salary_scale">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Appointment</label>
                                        <div class="col-md-12 d-flex">
                                            <input type="checkbox" class="form-check" name="" id="">&nbsp; Full Time &nbsp;
                                            <input type="checkbox" class="form-check" name="" id="">&nbsp; Part Time &nbsp;
                                            <input type="checkbox" class="form-check" name="" id="">&nbsp; Others &nbsp;
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Bank Name</label>
                                        <div class="col-md-12">
                                            <input type="text" name="bank_name" class="form-control">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Bank Branch</label>
                                        <div class="col-md-12">
                                            <input type="text" name="bank_branch" class="form-control">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Bank Account No</label>
                                        <div class="col-md-12">
                                            <input type="text" name="bank_account" class="form-control">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group float-right">
                                <label class="control-label col-md-12">Staff Signature</label>
                                <div class="col-md-12">
                                    <input type="text" name="bank_name" class="form-control" ="Bank Name">
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
                    <form action="#" id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="<?= $staff['id'] ?>" name="id" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Name</label>
                                                <div class="col-md-12">
                                                    <input name="staff_name" value="<?= $staff['staff_name'] ?>" class="form-control" type="text" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Gender</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="gender" value="<?= $staff['gender'] ?>" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Nationality</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="nationality" value="<?= $staff['nationality'] ?>" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Marital Status</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="nationality" value="<?= $staff['nationality'] ?>" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Religion</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="religion" value="<?= $staff['religion'] ?>" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-12 text-center" for="profileImage">PASSPORT PHOTO</label>
                                        <div class="col-sm-12">
                                            <?php if(($staff['photo'] != '') && (strtolower($staff['account_type']) == 'employee')): ?>
                                                <center>
                                                    <img  src="/uploads/staffs/employees/passports/<?= $staff['photo'] ?>" alt="Photo"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                                </center>
                                            <?php elseif(($staff['photo'] != '') && (strtolower($staff['account_type']) == 'administrator')): ?>
                                            <center>
                                                <img  src="/uploads/staffs/admins/passports/<?= $staff['photo'] ?>" alt="Photo"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                            </center>
                                            <?php else: ?>
                                                <center>
                                                    <img  src="/assets/dist/img/nophoto.jpg" alt="Photo"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                                </center>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Phone1</label>
                                        <div class="col-md-12">
                                            <input name="mobile" value="<?= $staff['mobile'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Phone2</label>
                                        <div class="col-md-12">
                                            <input name="alternate_mobile" value="<?= $staff['alternate_mobile'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Email</label>
                                        <div class="col-md-12">
                                            <input name="email" value="<?= $staff['email'] ?>" class="form-control" type="email" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">staffID</label>
                                        <div class="col-md-12">
                                            <input name="staffID" value="<?= $staff['staffID'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Department</label>
                                        <div class="col-md-12">
                                            <input type="text" value="<?= $staff['department_name'] ?>" name="department" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Position</label>
                                        <div class="col-md-12">
                                            <input type="text" value="<?= $staff['position'] ?>" name="position_id" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">ID Type</label>
                                        <div class="col-md-12">
                                            <input name="id_type" value="<?= $staff['id_type'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">NIN Number</label>
                                        <div class="col-md-12">
                                            <input name="id_number" value="<?= $staff['id_number'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">ID Expiry</label>
                                        <div class="col-md-12">
                                            <input name="id_expiry" value="<?= $staff['id_expiry_date'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Address</label>
                                        <div class="col-md-12">
                                            <textarea name="address" class="form-control" readonly><?= $staff['address'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Qualifications</label>
                                        <div class="col-md-12">
                                            <textarea name="qualifications" class="form-control" readonly><?= $staff['qualifications'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Branch</label>
                                        <div class="col-md-12">
                                            <input type="text" name="branch_id" value="<?= $staff['branch_name'] ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Salary [<?= $settings['currency']; ?>]</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="salary_scale" value="<?= $staff['salary_scale'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Appointment</label>
                                        <div class="col-md-12">
                                            <input name="appointment_type" value="<?= $staff['appointment_type'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Bank Name</label>
                                        <div class="col-md-12">
                                            <input type="text" name="bank_name" class="form-control" value="<?= $staff['bank_name'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Bank Branch</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="bank_branch" value="<?= $staff['bank_branch'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Bank Account No.</label>
                                        <div class="col-md-12">
                                            <input name="bank_account" class="form-control" type="text" value="<?= $staff['bank_account'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">D.O.B</label>
                                        <div class="col-md-12">
                                            <input name="date_of_birth" value="<?= $staff['date_of_birth'] ?>" class="form-control" type="date" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Registed Date</label>
                                        <div class="col-md-12">
                                            <input name="created_at" value="<?= $staff['created_at'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Updated At</label>
                                        <div class="col-md-12">
                                            <input name="updated_at" value="<?= $staff['updated_at'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group float-right">
                                <label class="control-label col-sm-12 ">Staff Signature</label>
                                <div class="col-sm-12">
                                <?php if(($staff['signature'] != '') && (strtolower($staff['account_type']) == 'employee')): ?>
                                    <img  src="/uploads/staffs/employees/signatures/<?= $staff['signature'] ?>" alt="signature"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                <?php elseif(($staff['signature'] != '') && (strtolower($staff['account_type']) == 'administrator')): ?>
                                    <img  src="/uploads/staffs/admins/signatures/<?= $staff['signature'] ?>" alt="signature"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                <?php else: ?>
                                    <img  src="/assets/dist/img/sign.png" alt="signature"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                <?php endif; ?>
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