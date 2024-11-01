<!DOCTYPE html>
<html lang="en">

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

<body">
    <div class="wrapper border border-3 p-3">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3 class='text-bold'>
                    <?= $settings['business_name'] . ' ' . $settings['system_name']; ?>
                </h3>
                <h4 class='text-bold'><?= $title ?></h4>
                <h5 class=''>Date: <?= date('l, d F, Y'); ?></h5>
                <img src="/uploads/logo/<?= $settings['business_logo'] ?>" alt="Logo" class="brand-image img-center" style="position:absolute; top:0; left:0;width:100px;height:100px;height: 100px; width: 100px; opacity: 0.8;" />
                <br>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <?php if ($id == 0) : ?>
                    <form id="approvalForm" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <input type="hidden" readonly name="disbursement_id" />
                        <input type="hidden" readonly name="client_id" />
                        <input type="hidden" readonly name="status" />
                        <div class="form-body">
                            <!-- approve loan calculations -->
                            <div class="row" id="approvalCals" style="display: none;">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <lable for="actual_interest" class="control-label">Interest</lable>
                                                <input type="text" name="actual_interest" class="form-control">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <lable for="computed_repayment" class="control-label">Repayment</lable>
                                                <input type="text" name="computed_repayment" class="form-control">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <lable for="actual_interest" class="control-label">Total Interest</lable>
                                                <input type="text" name="actual_interest" class="form-control">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <lable for="computed_installment" class="control-label">Computed Installment[<?= $settings['currency']; ?>]</lable>
                                                <input type="text" name="computed_installment" class="form-control">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <lable class="control-label">Actual Installment</lable>
                                                <input type="text" name="actual_installment" class="form-control">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <lable class="control-label">Total Repayment</lable>
                                                <input type="text" name="actual_repayment" class="form-control">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <lable class="control-label">Total Installments</lable>
                                                <input type="text" name="installments_num" class="form-control">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <lable class="control-label">Principal Installment[<?= $settings['currency']; ?>]</lable>
                                                <input type="text" name="principal_installment" class="form-control">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <lable class="control-label">Interest Installment[<?= $settings['currency']; ?>]</lable>
                                                <input type="text" name="interest_installment" class="form-control">
                                                <span class="help-block text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- action remarks -->
                                <div class="col-md-8" id="actionRemarks">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Remarks</label>
                                                <div class="col-md-12">
                                                    <textarea name="approval_comment" class="form-control"></textarea>
                                                    <span class="help-block text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- loan agreement -->
                                <p><i><b><u>NOTE: </u></b> Attarch loan agreement where applicable</i></p>
                            </div>
                            <div class="row" id="issueParticular" style="display: none;">
                                <div class="col-12">
                                    <span>Choose Particulars:</span>
                                    <p class="" style="border-top: 1px solid;"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Module</label>
                                        <div class="col-md-12">
                                            <select id="creditModule" name="module" class="form-control select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular(From)</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="particular_id" id="cr_particular" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular(To)</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="payment_id" id="dr_particular" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Responsible Officer</label>
                                    <div class="col-md-12">
                                        <input type="text" name="empt_id" class="form-control" />
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else :
                    $dob = new DateTime($disbursement['dob']);
                    $today = new DateTime();
                    $age = $today->diff($dob)->y;
                ?>
                    <form class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly name="id" />
                        <div class="form-body">
                            <!-- client bio -->
                            <section>
                                <div class="row">
                                    <div class="col-12">
                                        <span>Client Bio</span>
                                        <p class="" style="border-top: 1px solid;"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Full Name</label>
                                                    <div class="col-md-12">
                                                        <input name="name" value="<?= $disbursement['name'] ?>" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Gender</label>
                                                    <div class="col-md-12">
                                                        <input name="gender" value="<?= $disbursement['gender'] ?>" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Religion</label>
                                                    <div class="col-md-12">
                                                        <input name="religion" value="<?= $disbursement['religion'] ?>" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Marital Status</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="marital_status" value="<?= $disbursement['marital_status'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Nationality</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="nationality" value="<?= $disbursement['nationality'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label class="control-label">PassPort Photo</label>
                                        <div class="col-sm-12">
                                            <?php if ($disbursement['photo'] != '' && file_exists("uploads/clients/passports/" . $disbursement['photo'])) : ?>
                                                <center>
                                                    <img src="/uploads/clients/passports/<?= $disbursement['photo'] ?>" alt="Photo" class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                                </center>
                                            <?php else : ?>
                                                <center>
                                                    <img src="/assets/dist/img/nophoto.jpg" alt="Photo" class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                                </center>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Phone Number 1</label>
                                            <div class="col-md-12">
                                                <input name="mobile" value="<?= $disbursement['mobile'] ?>" class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Phone Number 2</label>
                                            <div class="col-md-12">
                                                <input name="alt_mobile" value="<?= $disbursement['alternate_no'] ?>" class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <div class="col-md-12">
                                                <input name="email" value="<?= $disbursement['email'] ?>" class="form-control" type="email">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">D.O.B</label>
                                            <div class="col-md-12">
                                                <input name="dob" value="<?= $disbursement['dob'] ?>" class="form-control" type="date" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Occupation</label>
                                            <div class="col-md-12">
                                                <input name="occupation" value="<?= $disbursement['occupation'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Business Location</label>
                                            <div class="col-md-12">
                                                <input name="job_location" value="<?= $disbursement['job_location'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Applicant Address</label>
                                            <div class="col-md-12">
                                                <textarea name="residence" class="form-control" readonly>
                                                    <?= strip_tags($disbursement['residence']) ?>
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Id Type</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="id_type" value="<?= $disbursement['id_type'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Id Number</label>
                                            <div class="col-md-12">
                                                <input name="id_number" value="<?= $disbursement['id_number'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Id Expiry Date</label>
                                            <div class="col-md-12">
                                                <input name="id_expiry" value="<?= $disbursement['id_expiry_date'] ?>" class="form-control" type="date" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Next of Kin Name</label>
                                            <div class="col-md-12">
                                                <input name="next_of_kin" value="<?= $disbursement['next_of_kin_name'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Next of Kin Relationship</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="nok_relationship" value="<?= $disbursement['next_of_kin_relationship'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Next of Kin Address</label>
                                            <div class="col-md-12">
                                                <input name="nok_address" value="<?= $disbursement['nok_address'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Next of Kin Phone 1</label>
                                            <div class="col-md-12">
                                                <input name="nok_phone" value="<?= $disbursement['next_of_kin_contact'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Next of Kin Phone 2</label>
                                            <div class="col-md-12">
                                                <input name="nok_alt_phone" value="<?= $disbursement['next_of_kin_alternate_contact'] ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Age(Years)</label>
                                            <div class="col-md-12">
                                                <input name="age" value="<?= $age ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Applicant Branch</label>
                                                    <div class="col-md-12">
                                                        <input name="branch_name" value="<?= $disbursement['branch_name'] ?>" class="form-control" type="text" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Account Number</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="account_no" value="<?= $disbursement['account_no'] ?>" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Account Type</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="client_account" value="<?= $disbursement['account_type'] ?>" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label class="control-label">Client Signature</label>
                                        <div class="col-sm-12">
                                            <?php if ($disbursement['signature'] != '' && file_exists("uploads/clients/signatures/" . $disbursement['signature'])) : ?>
                                                <img src="/uploads/clients/signatures/<?= $disbursement['signature'] ?>" alt="signature" class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                            <?php else : ?>
                                                <img src="/assets/dist/img/sign.png" alt="signature" class="img-fluid thumbnail" style="width: 200px; height: 200px;" />
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <hr>
                            <!-- disbursement data -->
                            <section>
                                <div class="row">
                                    <div class="col-12">
                                        <span>Disbursement Data</span>
                                        <p class="" style="border-top: 1px solid;"></p>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Loan Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" class="form-horizontal" autocomplete="off">
                                            <input type="hidden" readonly name="id" value="">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label" for="product_id">Disbursement Code</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="disbursement_code" class="form-control" value="<?= $disbursement['disbursement_code'] ?>" placeholder="Disbursement Code" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label" for="principal">Principal[<?= $settings['currency']; ?>]</label>
                                                            <div class="col-md-12">
                                                                <input name="principal" id="principal" value="<?= $disbursement['principal'] ?>" placeholder="Principal[<?= $settings['currency']; ?>]" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label" for="actual_interest">Total Interest</label>
                                                            <div class="col-md-12">
                                                                <input name="actual_interest" id="actual_interest" value="<?= $disbursement['actual_interest'] ?> " placeholder="Total Interest" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label" for="product_name">Loan Product</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="product_name" class="form-control" value="<?= $disbursement['product_name'] ?>" placeholder="Loan Product" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Interest Rate</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="interest_rate" class="form-control" value="<?= $disbursement['interest_rate'] ?>" placeholder="Interest Rate" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Interest Method</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="interest_type" class="form-control" value="<?= $disbursement['interest_type'] ?>" placeholder="Interest Method" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Repayment Mode</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="repayment_freq" class="form-control" value="<?= $disbursement['repayment_freq'] ?>" placeholder="Repayment Mode" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Repayment Period</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="repayment_period" class="form-control" value="<?= $disbursement['repayment_period'] . ' ' . $disbursement['repayment_duration'] ?>" placeholder="Repayment Period" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Class</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="class" class="form-control" value="<?= $disbursement['class'] ?>" placeholder="Application Status" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Cycle</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="cycle" class="form-control" value="<?= $disbursement['cycle'] ?>" placeholder="Cycle" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Comments</label>
                                                            <div class="col-md-12">
                                                                <input name="comments" value="<?= $disbursement['comments'] ?>" placeholder="Comments" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Application Code</label>
                                                            <div class="col-md-12">
                                                                <input name="application_code" value="<?= $disbursement['application_code'] ?>" placeholder="Application Code" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- loan security -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title text-bold">Loan Security</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- security item details -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title text-bold">Security Item</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" for="security_item">Security Item</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="security_item" id="security_item" value="<?= $disbursement['security_item'] ?>" class="form-control" placeholder="Security Item" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" for="est_value">Estimated Value[<?= $settings['currency']; ?>]</label>
                                                            <div class="col-md-12">
                                                                <input type="text" name="est_value" id="est_value" value="<?= $disbursement['est_value'] ?>" class="form-control" placeholder="Estimated Value[<?= $settings['currency']; ?>]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Details</label>
                                                            <div class="col-md-12">
                                                                <textarea name="vsecurity_info" class="form-control" readonly>
                                                                    <?= $disbursement['security_info'] ?>
                                                                </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- collateral files -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Collateral Images</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="collaterals" class="table table-sm table-striped table-hover">
                                                    <thead class="bg-secondary">
                                                        <tr>
                                                            <th>S.No</th>
                                                            <th>File Name</th>
                                                            <th>Type</th>
                                                            <th>Preview</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (count($collaterals) > 0) :
                                                            $no = 0;
                                                            foreach ($collaterals as $collateral) :
                                                                $no++;
                                                        ?>
                                                        <?php if (file_exists("uploads/applications/collaterals/" . $collateral['file_name']) && $collateral['file_name']) : ?>
                                                            <tr>
                                                                <td><?= $no ?></td>
                                                                <td><?= $collateral['file_name'] ?></td>
                                                                <td><?= substr(strrchr($collateral['file_name'], '.'), 1) ?></td>
                                                                <td>
                                                                    <img src="/uploads/applications/collaterals/<?= $collateral['file_name'] ?>" style="width:70px;height:70px;" class="img-circle" />
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php endforeach;
                                                        endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- referees -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title text-bold">Referee/ Guarantors</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_name">Full Name</label>
                                                        <input type="text" name="ref_name" id="ref_name" value="<?= $disbursement['ref_name'] ?>" class="form-control" placeholder="Full Name" readonly>
                                                    </div>
                                                </div> 
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_relation">Relationship</label>
                                                        <input type="text" name="ref_relation" id="ref_relation" value="<?= $disbursement['ref_relation'] ?>" class="form-control" placeholder="Relationship" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_job">Occupation</label>
                                                        <input type="text" name="ref_job" id="ref_job" value="<?= $disbursement['ref_job'] ?>" class="form-control" placeholder="Occupation" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_contact">Contact 1</label>
                                                        <input type="tel" name="ref_contact" id="ref_contact" value="<?= $disbursement['ref_contact'] ?>" class="form-control" placeholder="Contact 1" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_alt_contact">Contact 2</label>
                                                        <input type="tel" name="ref_alt_contact" id="ref_alt_contact" value="<?= $disbursement['ref_alt_contact'] ?>" class="form-control" placeholder="Contact 2" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_email">Email</label>
                                                        <input type="email" name="ref_email" id="ref_email" value="<?= $disbursement['ref_email'] ?>" class="form-control" placeholder="Email" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="ref_address2">Address</label>
                                                        <textarea name="ref_address" id="ref_address" value="<?= $disbursement['ref_address'] ?>" class="form-control" placeholder="Address"></textarea readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_name2">Full Name</label>
                                                        <input type="text" name="ref_name2" id="ref_name2" value="<?= $disbursement['ref_name2'] ?>" class="form-control" placeholder="Full Name" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="relation2">Relationship</label>
                                                        <input type="text" name="ref_relation2" id="ref_relation2" value="<?= $disbursement['ref_relation2'] ?>" class="form-control" placeholder="Relationship" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_job2">Occupation</label>
                                                        <input type="text" name="ref_job2" id="ref_job2" value="<?= $disbursement['ref_job2'] ?>" class="form-control" placeholder="Occupation" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_contact2">Contact 1</label>
                                                        <input type="tel" name="ref_contact2" id="ref_contact2" value="<?= $disbursement['ref_contact2'] ?>" class="form-control" placeholder="Contact 1" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_alt_contact2">Contact 2</label>
                                                        <input type="tel" name="ref_alt_contact2" id="ref_alt_contact2" value="<?= $disbursement['ref_alt_contact2'] ?>" class="form-control" placeholder="Contact 2" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ref_email2">Email</label>
                                                        <input type="email" name="ref_email2" id="ref_email2" value="<?= $disbursement['ref_email2'] ?>" class="form-control" placeholder="Email" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="ref_address2">Address</label>
                                                        <textarea name="ref_address2" id="ref_address2" value="<?= $disbursement['ref_address2'] ?>" class="form-control" placeholder="Address"></textarea readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </section><hr>
                            <!-- amounts -->
                            <section>
                                <form action="" class="form-horizontal" autocomplete="off">
                                    <input type="hidden" readonly name="id" value="">
                                    <div class="form-body">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Installments & Interest</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label" for="principal">Principal[<?= $settings['currency']; ?>]</label>
                                                            <div class="col-md-12">
                                                                <input name="principal" id="principal" value="<?= $disbursement['principal'] ?>" placeholder="Principal[<?= $settings['currency']; ?>]" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Actual Interest</label>
                                                            <div class="col-md-12">
                                                                <input class="form-control" placeholder="Actual Interest" name="actual_interest" value="<?= $disbursement['actual_interest'] ?>" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Total Repayment</label>
                                                            <div class="col-md-12">
                                                                <input name="computed_repayment" value="<?= $disbursement['computed_repayment'] ?>" placeholder="Total Repayment" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Installments N<u>o</u></label>
                                                            <div class="col-md-12">
                                                                <input name="installments_num" <?= $disbursement['installments_num'] ?> placeholder="Installments No" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Installments Covered</label>
                                                            <div class="col-md-12">
                                                                <input name="installments_covered" <?= $disbursement['installments_covered'] ?> placeholder="Installments Covered" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Installments Left</label>
                                                            <div class="col-md-12">
                                                                <input name="installments_left" <?= ($disbursement['installments_num'] - $disbursement['installments_covered']) ?> placeholder="Installments Left" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Computed Interest</label>
                                                            <div class="col-md-12">
                                                                <input name="computed_interest" <?= $disbursement['computed_interest'] ?> placeholder="Computed Interest" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Computed Installment</label>
                                                            <div class="col-md-12">
                                                                <input name="computed_installment" <?= $disbursement['computed_installment'] ?> placeholder="Computed Installment" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Computed Repayment</label>
                                                            <div class="col-md-12">
                                                                <input name="computed_repayment" <?= $disbursement['computed_repayment'] ?> placeholder="Computed Repayment" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Principal Installment</label>
                                                            <div class="col-md-12">
                                                                <input name="principal_installment" <?= $disbursement['principal_installment'] ?> placeholder="Principal Installment" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Interest Installment</label>
                                                            <div class="col-md-12">
                                                                <input class="form-control" placeholder="Interest Installment" name="interest_installment" <?= $disbursement['interest_installment'] ?> type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Actual Installment</label>
                                                            <div class="col-md-12">
                                                                <input name="actual_installment" <?= $disbursement['actual_installment'] ?> placeholder="Actual Installment" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- expectations -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Expectations</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">First Recovery Date</label>
                                                            <div class="col-md-12">
                                                                <input class="form-control" placeholder="First Recovery Date" name="first_recovery" value="<?= $disbursement['first_recovery'] ?>" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Expected Repayment</label>
                                                            <div class="col-md-12">
                                                                <input name="actual_repayment" value="<?= $disbursement['actual_repayment'] ?>" placeholder="Expected Repayment" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Expected Loan Balance</label>
                                                            <div class="col-md-12">
                                                                <input name="expected_loan_balance" value="<?= $disbursement['expected_loan_balance'] ?>" placeholder="Expected Loan Balance" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Expected Principal Recovered</label>
                                                            <div class="col-md-12">
                                                                <input name="expected_principal_recovered" value="<?= $disbursement['expected_principal_recovered'] ?>" placeholder="Expected Principal Recovered" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Expected Interest Recovered</label>
                                                            <div class="col-md-12">
                                                                <input class="form-control" placeholder="Expected Interest Recovered" name="expected_interest_recovered" value="<?= $disbursement['expected_interest_recovered'] ?>" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Expected Recovered Amount</label>
                                                            <div class="col-md-12">
                                                                <input name="expected_amount_recovered" value="<?= $disbursement['expected_amount_recovered'] ?>" placeholder="Expected Recovered Amount" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- repayed & balances -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Repayed & Balances</h5>
                                            </div>
                                            <div class="card-body">
                                                <!-- repayed-->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Interest Repayed</label>
                                                            <div class="col-md-12">
                                                                <input class="form-control" placeholder="Interest Repayed" name="interest_collected" value="<?= $disbursement['interest_collected'] ?>" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Principal Repayed</label>
                                                            <div class="col-md-12">
                                                                <input name="principal_collected" value="<?= $disbursement['principal_collected'] ?>" placeholder="Principal Repayed" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Total Repayed</label>
                                                            <div class="col-md-12">
                                                                <input name="total_collected" value="<?= $disbursement['total_collected'] ?>" placeholder="Total Repayed" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- balance-->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Interest Balance</label>
                                                            <div class="col-md-12">
                                                                <input class="form-control" placeholder="Interest Balance" name="interest_balance" value="<?= $disbursement['interest_balance'] ?>" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Principal Balance</label>
                                                            <div class="col-md-12">
                                                                <input name="principal_balance" value="<?= $disbursement['principal_balance'] ?>" placeholder="Principal Balance" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Total Loan Balance</label>
                                                            <div class="col-md-12">
                                                                <input name="total_balance" value="<?= $disbursement['total_balance'] ?>" placeholder="Total Loan Balance" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- dates -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Dates & Arrears</h5>
                                            </div>
                                            <div class="card-body">
                                                <!-- arrears & missed -->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Arrears</label>
                                                            <div class="col-md-12">
                                                                <input name="arrears" value="<?= $disbursement['arrears'] ?>" placeholder="Arrears" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Installments due</label>
                                                            <div class="col-md-12">
                                                                <input name="installments_due" value="<?= $disbursement['installments_due'] ?>" placeholder="Installments due" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Days Due</label>
                                                            <div class="col-md-12">
                                                                <input class="form-control" placeholder="Days Due" name="days_due" value="<?= $disbursement['days_due'] ?>" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- expire data -->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Date Disbursed</label>
                                                            <div class="col-md-12">
                                                                <input class="form-control" placeholder="Disbursement Date" name="created_at" value="<?= $disbursement['created_at'] ?>" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Loan Expiry Date</label>
                                                            <div class="col-md-12">
                                                                <input class="form-control" placeholder="Loan Expiry Date" name="loan_expiry_date" value="<?= $disbursement['loan_expiry_date'] ?>" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Loan Expiry Day</label>
                                                            <div class="col-md-12">
                                                                <input name="expiry_day" value="<?= $disbursement['expiry_day'] ?>" placeholder="Expiry Day" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- loan days data -->
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Loan Period(days)</label>
                                                            <div class="col-md-12">
                                                                <input name="loan_period_days" value="<?= $disbursement['loan_period_days'] ?>" placeholder="Loan Period(days)" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Days Covered</label>
                                                            <div class="col-md-12">
                                                                <input name="days_covered" value="<?= $disbursement['days_covered'] ?>" placeholder="Days Covered" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Days Remaining</label>
                                                            <div class="col-md-12">
                                                                <input class="form-control" placeholder="Days Remaining" name="days_remaining" value="<?= $disbursement['days_remaining'] ?>" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-12">Grace Period(days)</label>
                                                            <div class="col-md-12">
                                                                <input name="grace_period" value="<?= $disbursement['grace_period'] ?>" placeholder="Grace Period(days)" class="form-control" type="text" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </section><hr>
                            <!-- payments -->
                            <section>
                                <!-- <div class="row">
                                    <div class="col-12">
                                        <span>Repayment History</span>
                                        <p class="" style="border-top: 1px solid;"></p>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="disPayments" class="table table-sm table-striped table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th>S.No</th>
                                                <th>Particular</th>
                                                <th>Payment Method</th>
                                                <th>Amount [<?= $settings['currency']; ?>]</th>
                                                <th>Ref ID</th>
                                                <th>Date</th>
                                                <th>Officer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($repayments) > 0) :
                                                $no = 0;
                                                foreach ($repayments as $repayment) :
                                                    $no++;
                                            ?>
                                            <tr>
                                                <td><?= $no ?></td>
                                                <td><?= $repayment['particular_id'] ?></td>
                                                <td><?= $repayment['payment_id'] ?></td>
                                                <td><?= $repayment['amount'] ?></td>
                                                <td><?= $repayment['ref_id'] ?></td>
                                                <td><?= $repayment['created_at'] ?></td>
                                                <td><?= $repayment['staff_name'] ?></td>
                                            </tr>
                                            <?php endforeach;
                                            else : ?>
                                                <tr>
                                                    <td colspan="7" align="center">No data found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div> -->
                            </section><hr>
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