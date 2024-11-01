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
                <?php if ($id == 0 && strtolower($menu) == 'financing') : ?>
                    <form id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly name="id" />
                        <input type="hidden" readonly name="entry_menu" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular Module</label>
                                        <div class="col-md-12">
                                            <select id="module" name="module" class="form-control select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Client Name</label>
                                        <div class="col-md-12">
                                            <select id="client_id" name="client_id" class="form-control select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular Name</label>
                                        <div class="col-md-12">
                                            <select name="particular_id" id="particular_id" class="form-control select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="disbursementCode">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Disbursement Code</label>
                                        <div class="col-md-12">
                                            <select id="disbursement_id" name="disbursement_id" class="form-control select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="applicationCode">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Application Code</label>
                                        <div class="col-md-12">
                                            <select id="application_id" name="application_id" class="form-control select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Amount Payable</label>
                                        <div class="col-md-12">
                                            <input type="number" name="charge" class="form-control" min="0" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Amount Paid</label>
                                        <div class="col-md-12">
                                            <input type="number" name="amount" class="form-control" min="0">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Payment Method</label>
                                        <div class="col-md-12">
                                            <select name="payment_id" id="payment_id" class="form-control select2bs4" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Details</label>
                                        <div class="col-md-12">
                                            <textarea name="entry_details" class="form-control"></textarea>
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
                                            <input name="contact" class="form-control" type="text">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Remarks</label>
                                        <div class="col-md-12">
                                            <textarea name="remarks" class="form-control" type="text"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12"> Officer</label>
                                    <div class="col-md-12">
                                        <input name="emp_name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($id != 0 && strtolower($menu) == 'financing') : ?>
                    <form class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <input type="hidden" readonly name="entry_menu" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Module</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vmodule" value="<?= $transaction['module']; ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular Name</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vparticular_id" value="<?= $transaction['particular_id']; ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Client Name</label>
                                        <div class="col-md-12">
                                            <input name="vclient_id" value="<?= $transaction['name']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Amount</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vamount" value="<?= $transaction['amount']; ?>" class="form-control" min="0" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Payment Method</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vpayment_id" value="<?= $transaction['payment_id']; ?>" class="form-control" min="0" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Contact</label>
                                        <div class="col-md-12">
                                            <input name="vcontact" value="<?= $transaction['contact']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if($transaction['disbursement_id'] != null): ?>
                                <div class="row" id="disbursementData">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Loan Product</label>
                                            <div class="col-md-12">
                                                <input name="vproduct_name" value="<?= $transaction['product_name']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Disbursement Code</label>
                                            <div class="col-md-12">
                                                <input name="vdisbursement_id" value="<?= $transaction['disbursement_code']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Class</label>
                                            <div class="col-md-12">
                                                <input name="vclass" value="<?= $transaction['class']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($transaction['application_id'] != null): ?>
                                <div class="row" id="applicationData">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Loan Product</label>
                                            <div class="col-md-12">
                                                <input name="vproduct_name" value="<?= $transaction['product_name']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Application Code</label>
                                            <div class="col-md-12">
                                                <input name="vapplication_id" value="<?= $transaction['application_code']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Application Status</label>
                                            <div class="col-md-12">
                                                <input name="vapplication_status" value="<?= $transaction['status']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Details</label>
                                        <div class="col-md-12">
                                            <textarea name="ventry_details"class="form-control" id="viewSummernote" readonly>
                                                <?= strip_tags($transaction['entry_details']); ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Responsible Officer</label>
                                        <div class="col-md-12">
                                            <input name="vemployee_id" value="<?= $transaction['staff_name']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Date</label>
                                        <div class="col-md-12">
                                            <input name="created_at" value="<?= $transaction['created_at']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Updated</label>
                                        <div class="col-md-12">
                                            <input name="updated_at" value="<?= $transaction['updated_at']; ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Remarks</label>
                                        <div class="col-md-12">
                                            <textarea name="vremarks" class="form-control" readonly><?= $transaction['remarks']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($id == 0 && strtolower($menu) == 'expense') : ?>
                    <form action="#" id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly name="id" />
                        <input type="hidden" readonly name="entry_menu" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Particular</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="payment_id" id="payment_id" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Details</label>
                                        <div class="col-md-12">
                                            <textarea name="entry_details" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Amount</label>
                                        <div class="col-md-12">
                                            <input type="text" name="amount" id="amount" class="form-control">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Payment Method</label>
                                        <div class="col-md-12">
                                            <select name="particular_id" id="particular_id" class="form-control select2bs4" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Remarks</label>
                                        <div class="col-md-12">
                                            <textarea name="remarks" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12">Officer</label>
                                    <div class="col-md-12">
                                        <input type="hidden" readonly name="employee_id" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($id != 0 && strtolower($menu) == 'expense') : ?>
                    <form action="#" id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <input type="hidden" readonly name="entry_menu" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Module</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="vmodule" value="<?= $transaction['module'] ?>" id="module" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Particular</label>
                                        <div class="col-md-12">
                                            <input name="vpayment_id" value="<?= $transaction['payment_id'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Payment Method</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vparticular_id" id="credit_psrticular" class="form-control" value="<?= $transaction['particular_id'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="<?= (($transaction['client_id'] == null) ? 'col-md-6' : 'col-md-4'); ?>" id="transAmount">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Amount</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vamount" id="amount" class="form-control" value="<?= $transaction['amount'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="<?= (($transaction['client_id'] == null) ? 'col-md-6' : 'col-md-4'); ?>" id="refID">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Reference ID</label>
                                        <div class="col-md-12">
                                            <input name="vref_id" value="<?= $transaction['ref_id'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <?php if($transaction['client_id'] != null): ?>
                                    <div class="col-md-4" id="clientID">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Client Name</label>
                                            <div class="col-md-12">
                                                <input type="text" name="vclient_id" id="client_id" class="form-control"  value="<?= $transaction['name'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if($transaction['disbursement_id'] != null): ?>
                                <div class="row" id="disbursementData">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Loan Product</label>
                                            <div class="col-md-12">
                                                <input name="vproduct_name" value="<?= $transaction['product_name']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Disbursement Code</label>
                                            <div class="col-md-12">
                                                <input name="vdisbursement_id" value="<?= $transaction['disbursement_code']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Class</label>
                                            <div class="col-md-12">
                                                <input name="vclass" value="<?= $transaction['class']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($transaction['application_id'] != null): ?>
                                <div class="row" id="applicationData">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Loan Product</label>
                                            <div class="col-md-12">
                                                <input name="vproduct_name" value="<?= $transaction['product_name']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Application Code</label>
                                            <div class="col-md-12">
                                                <input name="vapplication_id" value="<?= $transaction['application_code']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Application Status</label>
                                            <div class="col-md-12">
                                                <input name="vapplication_status" value="<?= $transaction['status']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Details</label>
                                        <div class="col-md-12">
                                            <textarea name="ventry_details" class="form-control" readonly>
                                                <?= strip_tags($transaction['entry_details']) ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Officer</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="vemployee_id" value="<?= $transaction['staff_name'] ?>" id="officer" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Date</label>
                                        <div class="col-md-12">
                                            <input type="text" name="created_at" id="created_at" class="form-control" value="<?= $transaction['created_at'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Updated At</label>
                                        <div class="col-md-12">
                                            <input type="text" name="updated_at" id="updated_at" class="form-control" value="<?= $transaction['updated_at'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Remarks</label>
                                        <div class="col-md-12">
                                            <textarea name="vremarks" class="form-control"><?= $transaction['remarks'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($id == 0 && strtolower($menu) == 'transfer') : ?>
                    <form action="#" id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly name="id" />
                        <input type="hidden" readonly name="entry_menu" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <span>From</span>
                                    <p class="" style="border-top: 1px solid;"></p>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Module</label>
                                        <div class="col-md-12">
                                            <select id="creditModule" name="module" class="form-control select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="particular_id" id="particular_id" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Amount</label>
                                        <div class="col-md-12">
                                            <input type="text" name="amount" id="amount" class="form-control">
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <span>To</span>
                                    <p class="" style="border-top: 1px solid;"></p>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Module</label>
                                        <div class="col-md-12">
                                            <select id="debitModule" name="module" class="form-control select2bs4">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular</label>
                                        <div class="col-md-12">
                                            <select class="form-control select2bs4" name="payment_id" id="payment_id" style="width: 100%;">
                                            </select>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Details</label>
                                        <div class="col-md-12">
                                            <textarea name="entry_details" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Remarks</label>
                                        <div class="col-md-12">
                                            <textarea name="remarks" class="form-control"></textarea>
                                            <span class="help-block text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12"> Officer</label>
                                    <div class="col-md-12">
                                        <input name="emp_name" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php elseif ($id != 0 && strtolower($menu) == 'transfer') : ?>
                    <form action="#" id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <input type="hidden" readonly name="entry_menu" />
                        <div class="form-body">
                            <div class="row">
                                <div class="<?= (($transaction['client_id'] == null) ? 'col-md-6' : 'col-md-4'); ?>" id="fromParticular">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular(From)</label>
                                        <div class="col-md-12">
                                            <input name="vparticular_id" value="<?= $transaction['particular_id'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="<?= (($transaction['client_id'] == null) ? 'col-md-6' : 'col-md-4'); ?>" id="toParticular">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Particular(To)</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vpayment_id" id="payment_id" class="form-control" value="<?= $transaction['payment_id'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <?php if($transaction['client_id'] != null): ?>
                                    <div class="col-md-4" id="clientID">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Client Name</label>
                                            <div class="col-md-12">
                                                <input type="text" name="vclient_id" id="client_id" class="form-control"  value="<?= $transaction['name'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Amount</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vamount" id="amount" class="form-control" value="<?= $transaction['amount'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Reference ID</label>
                                        <div class="col-md-12">
                                            <input name="vref_id" value="<?= $transaction['ref_id'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Module</label>
                                        <div class="col-md-12">
                                            <input name="vmodule" value="<?= $transaction['module'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if($transaction['disbursement_id'] != null): ?>
                                <div class="row" id="disbursementData">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Loan Product</label>
                                            <div class="col-md-12">
                                                <input name="vproduct_name" value="<?= $transaction['product_name']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Disbursement Code</label>
                                            <div class="col-md-12">
                                                <input name="vdisbursement_id" value="<?= $transaction['disbursement_code']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Class</label>
                                            <div class="col-md-12">
                                                <input name="vclass" value="<?= $transaction['class']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($transaction['application_id'] != null): ?>
                                <div class="row" id="applicationData">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Loan Product</label>
                                            <div class="col-md-12">
                                                <input name="vproduct_name" value="<?= $transaction['product_name']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Application Code</label>
                                            <div class="col-md-12">
                                                <input name="vapplication_id" value="<?= $transaction['application_code']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Application Status</label>
                                            <div class="col-md-12">
                                                <input name="vapplication_status" value="<?= $transaction['status']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Details</label>
                                        <div class="col-md-12">
                                            <textarea name="ventry_details" class="form-control" readonly>
                                                <?= strip_tags($transaction['entry_details']) ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Officer</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="vemployee_id" value="<?= $transaction['staff_name'] ?>" id="officer" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Date</label>
                                        <div class="col-md-12">
                                            <input type="text" name="created_at" id="created_at" class="form-control" value="<?= $transaction['created_at'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Updated At</label>
                                        <div class="col-md-12">
                                            <input type="text" name="updated_at" id="updated_at" class="form-control" value="<?= $transaction['updated_at'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Remarks</label>
                                        <div class="col-md-12">
                                            <textarea name="vremarks" class="form-control"><?= $transaction['remarks'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
                    <form action="#" id="form" class="form-horizontal" autocomplete="off">
                        <input type="hidden" readonly value="" name="id" />
                        <input type="hidden" readonly name="entry_menu" />
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Module</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="vmodule" value="<?= $transaction['module'] ?>" id="module" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Particular</label>
                                        <div class="col-md-12">
                                            <input name="vpayment_id" value="<?= $transaction['payment_id'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Payment Method</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vparticular_id" id="credit_psrticular" class="form-control" value="<?= $transaction['particular_id'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="<?= (($transaction['client_id'] == null) ? 'col-md-6' : 'col-md-4'); ?>" id="transAmount">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Amount</label>
                                        <div class="col-md-12">
                                            <input type="text" name="vamount" id="amount" class="form-control" value="<?= $transaction['amount'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="<?= (($transaction['client_id'] == null) ? 'col-md-6' : 'col-md-4'); ?>" id="refID">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Reference ID</label>
                                        <div class="col-md-12">
                                            <input name="vref_id" value="<?= $transaction['ref_id'] ?>" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <?php if($transaction['client_id'] != null): ?>
                                    <div class="col-md-4" id="clientID">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Client Name</label>
                                            <div class="col-md-12">
                                                <input type="text" name="vclient_id" id="client_id" class="form-control"  value="<?= $transaction['name'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if($transaction['disbursement_id'] != null): ?>
                                <div class="row" id="disbursementData">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Loan Product</label>
                                            <div class="col-md-12">
                                                <input name="vproduct_name" value="<?= $transaction['product_name']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Disbursement Code</label>
                                            <div class="col-md-12">
                                                <input name="vdisbursement_id" value="<?= $transaction['disbursement_code']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Class</label>
                                            <div class="col-md-12">
                                                <input name="vclass" value="<?= $transaction['class']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($transaction['application_id'] != null): ?>
                                <div class="row" id="applicationData">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Loan Product</label>
                                            <div class="col-md-12">
                                                <input name="vproduct_name" value="<?= $transaction['product_name']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Application Code</label>
                                            <div class="col-md-12">
                                                <input name="vapplication_id" value="<?= $transaction['application_code']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-12">Application Status</label>
                                            <div class="col-md-12">
                                                <input name="vapplication_status" value="<?= $transaction['status']; ?>" class="form-control" type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Details</label>
                                        <div class="col-md-12">
                                            <textarea name="ventry_details" class="form-control" readonly>
                                                <?= strip_tags($transaction['entry_details']) ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Officer</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="vemployee_id" value="<?= $transaction['staff_name'] ?>" id="officer" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Date</label>
                                        <div class="col-md-12">
                                            <input type="text" name="created_at" id="created_at" class="form-control" value="<?= $transaction['created_at'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Updated At</label>
                                        <div class="col-md-12">
                                            <input type="text" name="updated_at" id="updated_at" class="form-control" value="<?= $transaction['updated_at'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12">Transaction Remarks</label>
                                        <div class="col-md-12">
                                            <textarea name="vremarks" class="form-control"><?= $transaction['remarks'] ?></textarea>
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