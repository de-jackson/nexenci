<?= $this->extend("layout/error"); ?>

<?= $this->section("content"); ?>
<div class="dz-error" data-text="503">503</div>
<h4 class="error-head">
    <i class="fa fa-times-circle text-danger"></i> Service Unavailable
</h4>
<?= $this->endSection(); ?>