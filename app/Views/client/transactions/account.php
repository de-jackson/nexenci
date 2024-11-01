<?= $this->extend("layout/client"); ?>

<?= $this->section("content"); ?>
<div class="col-xl-12">
    <div class="card border custom-card">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Make <?= ucwords(strtolower($type)); ?> Transactions
                </div>
            </div>
            <div class="card-body">
                <!-- display message-->
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <!-- <strong>Success</strong>  -->
                        <?= session()->getFlashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><?= session()->getFlashdata('error'); ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form id="form" class="form-horizontal" autocomplete="off">
                    <?= csrf_field() ?>
                    <input type="hidden" readonly name="client_id" value="<?= $user['id']; ?>" />
                    <input type="hidden" readonly name="account_typeId" value="12" />
                    <input type="hidden" readonly name="entry_menu" value="<?= strtolower($type); ?>" />
                    <div class="form-body">
                        <div class="row gy-4">
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold">
                                        Account Module <span class="text-danger">*</span>
                                    </label>
                                    <select name="particular_id" id="particular_id" class="form-control select2">
                                        <option value="">Select</option>
                                    </select>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="type" class="control-label fw-bold">
                                        Transaction Type <span class="text-danger">*</span>
                                    </label>
                                    <select name="entry_typeId" id="entry_typeId" class="form-control select2">
                                        <option value="">Select</option>
                                    </select>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold">
                                        Contact <span class="text-danger">*</span>
                                    </label>
                                    <input id="contact" name="contact" placeholder="Contact" class="form-control phone-input" type="text" value="<?= $user['mobile']; ?>">
                                    <input type="hidden" readonly id="contact_full" name="contact_full">
                                    <input type="hidden" name="contact_country_code" id="contact_country_code" readonly>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="amount" class="control-label fw-bold">
                                        Amount <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="amount" id="amount" class="form-control" placeholder="Amount" min="0">
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="amount" class="control-label fw-bold">
                                        Payment Method <span class="text-danger">*</span>
                                    </label>
                                    <select name="payment_id" id="payment_id" class="form-control select2" style="width: 100%;">
                                        <option value="">Select</option>
                                    </select>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="amount" class="control-label fw-bold">
                                        Transaction Date <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>
                                        <input type="text" name="date" id="date" class="form-control" value="<?= date('Y-m-d'); ?>" placeholder="Transaction Date" readonly>
                                    </div>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label class="control-label fw-bold">
                                        Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="entry_details" class="form-control"><?= $user['name'] . ' ' . trim(ucwords(strtolower($type))); ?> Transaction</textarea>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <!-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label fw-bold">Remarks</label>
                                        <textarea name="remarks" class="form-control" placeholder="Remarks"></textarea>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div> -->
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input name="staff_id" placeholder="Responsible Officer" class="form-control" type="hidden" value="<?= $user['staff_id']; ?>">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row gy-4 mt-0 float-end">
                    <div class="col-md-12">
                        <button type="button" id="btnSavings" onclick="accountTransaction()" class="btn <?= (strtolower($type) == "withdraw") ? "btn-outline-primary" : "btn-outline-success" ?>"><?= ucfirst($type); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script type="text/javascript">
    <?php if ($type == 'repayments') : ?>
        var account_typeId = 3;
    <?php else : ?>
        var account_typeId = 12;
    <?php endif; ?>

    var transaction_menu = '<?= strtolower($type); ?>';

    function displayMessage() {
        <?php if (session()->has('success')) : ?>
            Swal.fire("Success", "<?= session()->getFlashdata('success'); ?>", "success");
        <?php endif; ?>

        <?php if (session()->has('error')) : ?>
            Swal.fire("External Error", "<?= session()->getFlashdata('error'); ?>", "error");
        <?php endif; ?>
    }
</script>
<script src="/assets/client/main/savings.js"></script>
<script src="/assets/client/main/options.js"></script>

<?= $this->endSection() ?>