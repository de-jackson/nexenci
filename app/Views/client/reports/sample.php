<?= $this->extend("layout/dashboard"); ?>

<?= $this->section("content"); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Borrowers Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                    <li class="breadcrumb-item"><?= ucfirst($title) ?></li>
                    <li class="breadcrumb-item active">Borrowers</li>
                    <!-- <li class="breadcrumb-item"><a href="javascript: void(0)" class="text-danger" onclick="history.back(-1);"><i class="fas fa-circle-left"></i> Back</a></li> -->
                </ol>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- search form -->
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form autocomplete="off">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <input type="text" name="search" class=" form-control" id="search" placeholder="Client name or occupation">
                                                <i><small>Click in the box above to enter client name</small></i>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <input type="text" name="gender" class=" form-control" id="gender" placeholder="Client gender">
                                                <i><small>Click in the box above to enter select gender</small></i>
                                            </div>
                                        </div>
                                        <!-- <div class="col-lg-1 text-center" style="padding-top: 5px;">
                                            From
                                        </div> -->
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <input type="text" name="start_date" value="" class="form-control getDatePicker" id="start-date" placeholder="Start date">
                                                <i><small>Click in the box above to select the start date</small></i>
                                            </div>
                                        </div>
                                        <!-- <div class="col-lg-1 text-center" style="padding-top: 5px;">
                                            To
                                        </div> -->
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <input type="text" name="end_date" value="" class="form-control getDatePicker" id="end-date" placeholder="End date">
                                                <i><small>Click in the box above to select the end date</small></i>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="text" name="accountno" class=" form-control" id="accountno" placeholder="Client account number">
                                                <i><small>Click in the box above to enter client account number</small></i>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <select class="select2" multiple="multiple" data-placeholder="Select a branch" style="width: 100%;">
                                                    <option value="">Select</option>
                                                    <option>Alabama</option>
                                                    <option>Alaska</option>
                                                    <option>California</option>
                                                    <option>Delaware</option>
                                                    <option>Tennessee</option>
                                                    <option>Texas</option>
                                                    <option>Washington</option>
                                                </select>
                                                <i><small>Click in the box above to select multiple branches</small></i>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary btn-block" id="filter-clients" value="filter"><i class="fa fa-search fa-fw"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- client table -->
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="clients9" class="table table-sm table-striped table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th>S.No</th>
                                                <th>Name</th>
                                                <th>Num Loans Released</th>
                                                <th>Principal Released</th>
                                                <th>Principal At Risk**</th>
                                                <th>Principal[<?= $settings['currency']; ?>]</th>
                                                <th>Interest<<?= $settings['currency']; ?>]</th>
                                                <th>Fees</th>
                                                <th>Penalty</th>
                                                <th width="5%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Name</th>
                                                <th>Num Loans Released</th>
                                                <th>Principal Released</th>
                                                <th>Principal At Risk**</th>
                                                <th>Principal[<?= $settings['currency']; ?>]</th>
                                                <th>Interest<<?= $settings['currency']; ?>]</th>
                                                <th>Fees</th>
                                                <th>Penalty</th>
                                                <th width="5%">Total</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <small>** Principal At Risk is the Principal Released amount after deducting Principal Payments for the date range selected above.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">

</script>
<script src="/assets/scripts/reports/clients.js"></script>

<?= $this->endSection() ?>