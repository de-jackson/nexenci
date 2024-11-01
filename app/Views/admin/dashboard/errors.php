<?php
$title = '404 - Page Not Found';
?>

<?= $this->extend("layout/error"); ?>

<?= $this->section("content"); ?>
<div class="dz-error" data-text="404">404</div>
<h4 class="error-head">
    <i class="fa fa-exclamation-triangle text-warning"></i> The page you were looking for is not found!
</h4>
<?= $this->endSection(); ?>