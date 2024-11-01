<?= $this->extend("layout/error"); ?>

<?= $this->section("content"); ?>
<div class="dz-error" data-text="403">403</div>
<h4 class="error-head"><i class="fa fa-times-circle text-danger"></i> Forbidden Error!</h4>
<p class="error-head">You do not have permission to view this resource.</p>
<?= $this->endSection(); ?>