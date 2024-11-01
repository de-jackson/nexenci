<?= $this->extend("layout/error"); ?>

<?= $this->section("content"); ?>
<div class="dz-error" data-text="500">500</div>
<h4 class="text-nowrap error-head">
    <i class="fa fa-times-circle text-danger"></i> Internal Server Error
</h4>
<p class="error-head">You do not have permission to view this resource</p>
<?= $this->endSection(); ?>