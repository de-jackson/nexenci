<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<!-- Content Header (Page header) -->
<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <h1 class="page-title fw-semibold fs-18 mb-0">Borrowers Report</h1>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript: void(0)" class="text-danger" onclick="history.back(-1);"><i class="fas fa-circle-left"></i> Back</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)"><?= ucfirst($menu) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= ucfirst($title) ?></li>
            </ol>
        </nav>
    </div>
</div>
<!-- report body -->
<!-- Start::row-1 -->
<div class="row gx-3 gy-2 align-items-center mt-0">
    <div class="col-xl-12">
        <div class="card custom-card mt-4">
            <!-- description -->
            <div class="card-header">

            </div>
            <!--  advanced search form -->
            <div class="card-body">
                <div class="h5 fw-semibold mb-0">Advanced Search:</div>
                <div class="contact-header">
                    <div class="d-sm-flex d-block align-items-center justify-content-between">
                        <div class="d-flex mt-sm-0 mt-2 align-items-center">
                            <form autocomplete="off">
                                <div class="row p-2">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" name="search" class=" form-control" id="search" placeholder="Client name or occupation">
                                            <i><small class="fw-semibold">Click in the box above to enter client name</small></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" name="gender" class=" form-control" id="gender" placeholder="Client gender">
                                            <i><small class="fw-semibold">Click in the box above to enter select gender</small></i>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-1 text-center" style="padding-top: 5px;">
                                            From
                                        </div> -->
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" name="start_date" value="" class="form-control getDatePicker" id="start-date" placeholder="Start date">
                                            <i><small class="fw-semibold">Click in the box above to select the start date</small></i>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-1 text-center" style="padding-top: 5px;">
                                            To
                                        </div> -->
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" name="end_date" value="" class="form-control getDatePicker" id="end-date" placeholder="End date">
                                            <i><small class="fw-semibold">Click in the box above to select the end date</small></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="text" name="accountno" class=" form-control" id="accountno" placeholder="Client account number">
                                            <i><small class="fw-semibold">Click in the box above to enter client account number</small></i>
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
                                            <i><small class="fw-semibold">Click in the box above to select multiple branches</small></i>
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
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Start::row-2 -->
<div class="row gx-3 gy-2 align-items-center mt-0">
    <div class="col-xl-12">
        <div class="card border border-warning custom-card">
            <div class="card custom-card">
                <div class="card-body">
                    <!-- client table -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <small class="fw-semibold">** Principal At Risk is the Principal Released amount after deducting Principal Payments for the date range selected above.</small>
                                <table id="clients9" class="table table-sm table-striped table-hover">
                                    <thead class="bg-secondary">
                                        <tr>
                                            <!-- <th>S.No</th> -->
                                            <th>Name</th>
                                            <th>Num Loans Released</th>
                                            <th>Principal Released</th>
                                            <th>Principal At Risk**</th>
                                            <th>Principal[<?= $settings['currency']; ?>]</th>
                                            <th>Interest<<?= $settings['currency']; ?>]< /th>
                                            <th>Fees</th>
                                            <th>Penalty</th>
                                            <th width="5%">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            
                                        <tr>
                                            <td class="text-bold bg-gray text-left" colspan="9" data-search="1000003(Danfodio Oklello)">
                                                1000003(Danfodio Oklello)
                                            </td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                        </tr>
                                        <tr>
                                            <td class="" style="text-align:right" data-search="">
                                                1
                                            </td>
                                            <td class="" style="text-align:right">
                                                2,100,000.00
                                            </td>
                                            <td class="" style="text-align:right">
                                                1,575,000.00
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                Due Loans:
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                1,575,000.00
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                315,000.00
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                252,000.00
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                0
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                2,142,000.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td data-search="">
                                            </td>
                                            <td>
                                            </td>
                                            <td>
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                Payments(1):
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                525,000.00
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                105,000.00
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                252,000.00
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                0
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right;" class="text-green">
                                                882,000.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right; border-bottom: 1px #000000 solid;" data-search="">
                                            </td>
                                            <td style="text-align:right; border-bottom: 1px #000000 solid;">
                                            </td>
                                            <td style="text-align:right; border-bottom: 1px #000000 solid;">
                                            </td>
                                            <td class="text-bold" style="text-align:right; border-bottom: 1px #000000 solid; width:9%">
                                                Net Due:
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                1,050,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                210,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                0
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                0
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                1,260,000.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold bg-gray text-left" colspan="9" data-search="1000001(David Ssali - Ssali BIZ)">
                                                1000001(David Ssali - Ssali BIZ)
                                            </td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                        </tr>
                                        <tr>
                                            <td class="" style="text-align:right" data-search="">
                                                1
                                            </td>
                                            <td class="" style="text-align:right">
                                                1,000,000.00
                                            </td>
                                            <td class="" style="text-align:right">
                                                0
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                Due Loans:
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                750,000.00
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                150,000.00
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                120,000.00
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                0
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                1,020,000.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td data-search="">
                                            </td>
                                            <td>
                                            </td>
                                            <td>
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                Payments(1):
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                1,000,000.00
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                200,000.00
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                140,000.00
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                780,000.00
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right;" class="text-green">
                                                2,120,000.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right; border-bottom: 1px #000000 solid;" data-search="">
                                            </td>
                                            <td style="text-align:right; border-bottom: 1px #000000 solid;">
                                            </td>
                                            <td style="text-align:right; border-bottom: 1px #000000 solid;">
                                            </td>
                                            <td class="text-bold" style="text-align:right; border-bottom: 1px #000000 solid; width:9%">
                                                Net Due:
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                -250,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                -50,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                -20,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                -780,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                -1,100,000.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold bg-gray text-left" colspan="9" data-search="1000002(Opio Samuel)">
                                                1000002(Opio Samuel)
                                            </td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                        </tr>
                                        <tr>
                                            <td class="" style="text-align:right" data-search="">
                                                1
                                            </td>
                                            <td class="" style="text-align:right">
                                                2,000,000.00
                                            </td>
                                            <td class="" style="text-align:right">
                                                2,000,000.00
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                Due Loans:
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                2,000,000.00
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                280,000.00
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                0
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                0
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                2,280,000.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td data-search="">
                                            </td>
                                            <td>
                                            </td>
                                            <td>
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                Payments(0):
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                0
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                0
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                0
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                0
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right;" class="text-green">
                                                0
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right; border-bottom: 1px #000000 solid;" data-search="">
                                            </td>
                                            <td style="text-align:right; border-bottom: 1px #000000 solid;">
                                            </td>
                                            <td style="text-align:right; border-bottom: 1px #000000 solid;">
                                            </td>
                                            <td class="text-bold" style="text-align:right; border-bottom: 1px #000000 solid; width:9%">
                                                Net Due:
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                2,000,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                280,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                0
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                0
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                2,280,000.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold bg-navy disabled color-palette" colspan="9">
                                                Total
                                            </td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right">
                                                <b>3</b>
                                            </td>
                                            <td style="text-align:right">
                                                <b>5,100,000.00</b>
                                            </td>
                                            <td style="text-align:right">
                                                <b>3,575,000.00</b>
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right">
                                                Due Loans:
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right">
                                                4,325,000.00
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right">
                                                745,000.00
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right">
                                                372,000.00
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right">
                                                0
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right;">
                                                5,442,000.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            </td>
                                            <td>
                                            </td>
                                            <td>
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                Payments(2):
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                1,525,000.00
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                305,000.00
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                392,000.00
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                780,000.00
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                3,002,000.00
            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right; border-bottom: 1px solid #000">
                                            </td>
                                            <td style="text-align:right; border-bottom: 1px solid #000">
                                            </td>
                                            <td style="text-align:right; border-bottom: 1px solid #000">
                                            </td>
                                            <td class="text-bold" style="text-align:right; border-bottom: 1px #000000 solid;">
                                                Net Due:
                                            </td>
                                            <td class="text-bold" style="text-align:right; border-bottom: 1px #000000 solid;">
                                                2,800,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; border-bottom: 1px #000000 solid;">
                                                440,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; border-bottom: 1px #000000 solid;">
                                                -20,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; border-bottom: 1px #000000 solid;">
                                                -780,000.00
                                            </td>
                                            <td class="text-bold" style="text-align:right; font-weight:bold; border-bottom: 1px #000000 solid;">
                                                2,440,000.00
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">

</script>
<script src="/assets/scripts/reports/clients.js"></script>
<script src="/assets/scripts/main/select-dropdowns.js"></script>

<?= $this->endSection() ?>