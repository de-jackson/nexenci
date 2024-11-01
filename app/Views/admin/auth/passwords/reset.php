<?= $this->extend("layout/app"); ?>

<?= $this->section("content"); ?>


<div class="text-center">
    <h3 class="title">Hello <?= $user['name']; ?>!</h3>
    <p>
        You are only one step a way from resetting your password.
    </p>
</div>
<?php
if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success solid alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <polyline points="9 11 12 14 22 4"></polyline>
            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
        </svg>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        </button>
    </div>
<?php 
    session()->setFlashdata('success', "");
    elseif (session()->getFlashdata('failed')) : ?>
    <div class="alert alert-dark solid alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
        <strong>Session Expired!</strong> <?= session()->getFlashdata('failed') ?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        </button>
    </div>
<?php 
session()->setFlashdata('failed', "");
endif; ?>
<span class="text-center wrong-msg msg"></span>
<form id="form" autocomplete="off">
    <?= csrf_field() ?>
    <div class="mb-4">
        <input type="hidden" name="id" value="<?=$user['id']; ?>" readonly>
        <div class="position-relative">
            <label class="mb-1 text-dark">New Password <span class="text-danger">*</span></label>
            <input type="password" name="password" id="dz-password" class="form-control" placeholder="Password">
            <span class="show-pass eye">
                <i class="fa fa-eye-slash"></i>
                <i class="fa fa-eye"></i>
            </span>
            <span class="help-block error-msg"></span>
        </div>
    </div>
    <div class="mb-4 ">
        <div class="position-relative">
            <label class="mb-1 text-dark">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Confirm Password">
            <!-- <span class="show-pass eye">
                <i class="fa fa-eye-slash"></i>
                <i class="fa fa-eye"></i>
            </span> -->
            <span class="help-block error-msg"></span>
        </div>
    </div>
    <div class="text-center mb-4">
        <button type="submit" id="btnSubmit" class="btn btn-primary btn-block">
            Reset Password
        </button>
    </div>
</form>

<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>

<script src="/assets/reset/admin/reset-password.js"></script>

<?= $this->endSection(); ?>