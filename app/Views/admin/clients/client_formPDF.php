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
                        <input type="hidden" readonly value="" name="id" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Full Name</label>
                                                <div class="col-md-12">
                                                    <input name="name" class="form-control" type="text">
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
                                                    <input type="checkbox" class="form-check" name="" id="">&nbsp; Others &nbsp;
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Nationality</label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="nationality" style="width: 100%;">
                                                        
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Marital Status</label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="marital_status" style="width: 100%;">
                                                        
                                                    </select>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Religion</label>
                                                <div class="col-md-12">
                                                    <select class="form-control select2bs4" name="religion" style="width: 100%;" id="religion">
                                                        
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
                                        <label class="control-label col-md-12">Phone Number 1
                                        </label>
                                        <div class="col-md-12">
                                            <input name="mobile" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Phone Number 2
                                        </label>
                                        <div class="col-md-12">
                                            <input name="alternate_no" class="form-control" type="text">
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Address</label>
                                        <div class="col-md-12">
                                            <textarea name="residence" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Branch Name</label>
                                        <div class="col-md-12">
                                            <select id="branch_id" name="branch_id" class="form-control select2bs4" style="width: 100%;">

                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Occupation</label>
                                        <div class="col-md-12">
                                            <input name="occupation" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Date Of Birth</label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="date" name="dob" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask>
                                            </div>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Id Type</label>
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
                                        <label class="control-label col-md-12">Id Number</label>
                                        <div class="col-md-12">
                                            <input name="id_number" class="form-control" type="text">
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
                                                <input name="id_expiry" value="" class="form-control" type="date">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Business Location</label>
                                        <div class="col-md-12">
                                            <textarea name="job_location" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next of Kin Name</label>
                                        <div class="col-md-12">
                                            <input name="next_of_kin" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next of kin Relationship</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="nok_relationship" style="width: 100%;">
                                                
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next of Kin Phone Number 1
                                        </label>
                                        <div class="col-md-12">
                                            <input name="nok_phone" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next of Kin Phone Number 2
                                        </label>
                                        <div class="col-md-12">
                                            <input name="nok_alt_phone" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next of Kin Email
                                        </label>
                                        <div class="col-md-12">
                                            <input name="nok_email" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next of Kin Address</label>
                                        <div class="col-md-12">
                                            <textarea name="nok_address" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Client Signature</label>
                                        <div class="col-md-12">
                                        <input name="emp_id" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Responsible Officer</label>
                                        <div class="col-md-12">
                                        <input name="emp_id" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Signature</label>
                                        <div class="col-md-12">
                                        <input name="emp_id" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else : 
                $dob = new DateTime($client['dob']);
                $today = new DateTime();
                $age = $today->diff($dob)->y;
                ?>
                    <form class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Full Name</label>
                                                <div class="col-md-12">
                                                    <input name="name" value="<?= $client['name'] ?>" class="form-control" type="text" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Branch</label>
                                                <div class="col-md-12">
                                                    <input name="branch_id" value="<?= $client['branch_name'] ?>" class="form-control" type="text" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Gender</label>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" name="gender" value="<?= $client['gender'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Nationality</label>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" name="nationality" value="<?= $client['nationality'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Marital Status</label>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" name="marital_status" value="<?= $client['marital_status'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-12">Religion</label>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control" name="religion" value="<?= $client['religion'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-12 text-center" for="profileImage">PASSPORT PHOTO</label>
                                        <div class="col-sm-12">
                                            <?php if($client['photo'] != ''): ?>
                                                <center>
                                                    <img  src="/uploads/clients/passports/<?= $client['photo'] ?>" alt="Photo"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
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
                                        <label class="control-label col-md-12">Phone Number 1</label>
                                        <div class="col-md-12">
                                            <input name="mobile" value="<?= $client['mobile']?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Phone Number 2</label>
                                        <div class="col-md-12">
                                            <input name="alternate_no" value="<?= $client['alternate_no']?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Email</label>
                                        <div class="col-md-12">
                                            <input name="email" value="<?= $client['email']?>" class="form-control" type="email" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Account Number</label>
                                        <div class="col-md-12">
                                            <input type="text" name="account_no" value="<?= $client['account_no'] ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Account Type</label>
                                        <div class="col-md-12">
                                            <input type="text" name="account_type" value="<?= $client['account_type'] ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Account Balance</label>
                                        <div class="col-md-12">
                                            <input type="text" name="account_balance" value="<?= $client['account_balance'] ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Address</label>
                                        <div class="col-md-12">
                                            <textarea name="residence" class="form-control" readonly><?= $client['residence']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">D.O.B</label>
                                        <div class="col-md-12">
                                            <input name="dob" value="<?= $client['dob']; ?>" class="form-control" type="date" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Occupation</label>
                                        <div class="col-md-12">
                                            <input name="occupation" value="<?= $client['occupation']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Age</label>
                                        <div class="col-md-12">
                                            <input name="age" value="<?= $age ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Id Type</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="id_type" value="<?= $client['id_type']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Id Number</label>
                                        <div class="col-md-12">
                                            <input name="id_number" value="<?= $client['id_number']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Id Expiry Date</label>
                                        <div class="col-md-12">
                                            <input name="id_expiry" value="<?= $client['id_expiry_date']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Business Location</label>
                                        <div class="col-md-12">
                                            <textarea name="job_location" class="form-control" readonly><?= $client['job_location']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next of Kin</label>
                                        <div class="col-md-12">
                                            <input name="next_of_kin" value="<?= $client['next_of_kin_name']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next of Kin Relationship</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="next_of_kin_relationship" value="<?= $client['next_of_kin_relationship']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next of Kin Phone1</label>
                                        <div class="col-md-12">
                                            <input name="nok_phone" value="<?= $client['next_of_kin_contact']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next of Kin Phone2</label>
                                        <div class="col-md-12">
                                            <input name="nok_alt_phone" value="<?= $client['next_of_kin_alternate_contact']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next of Kin Email</label>
                                        <div class="col-md-12">
                                            <input name="nok_email" value="<?= $client['nok_email']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Next Of Kin Address</label>
                                        <div class="col-md-12">
                                            <textarea name="nok_address" class="form-control" readonly><?= $client['nok_address']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Registration Date</label>
                                        <div class="col-md-12">
                                            <input name="created_at" value="<?= $client['created_at']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Updated At</label>
                                        <div class="col-md-12">
                                            <input name="update_at" value="<?= $client['updated_at']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Responsible Officer</label>
                                        <div class="col-md-12">
                                            <input name="emp_name" value="<?= $client['staff_name']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group float-right">
                                <label class="control-label col-sm-12 ">Client Signature</label>
                                <div class="col-sm-12">
                                    <?php if($client['signature'] != ''): ?>
                                            <img  src="/uploads/clients/signatures/<?= $client['signature'] ?>" alt="signature"  class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
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