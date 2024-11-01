<?= $this->extend("layout/app"); ?>

<?= $this->section("content"); ?>


<div class="text-center">
    <h3 class="title">Welcome to <?= $settings['system_name']; ?></h3>
    <p>
        Let's setup your account in a few simple steps
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

<div id="smartwizard" class="form-wizard order-create">
    <ul class="nav nav-wizard">
        <li>
            <a class="nav-link step-1" href="#step-1">
                <span>1</span>
            </a>
        </li>
        <li>
            <a class="nav-link step-2" href="#step-2">
                <span>2</span>
            </a>
        </li>
        <li>
            <a class="nav-link step-3" href="#step-3">
                <span>3</span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <span class="text-center wrong-msg msg"></span>
        <form action="" id="registerForm" autocomplete="FALSE">
            <input type="hidden" name="step_no" readonly />
            <div id="step-1" class="tab-pane step-1" role="tabpanel">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="mb-3 form-group">
                            <label for="name" class="text-label form-label col-md-12">Full Name<span class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Full name" required>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-2">
                        <div class="mb-3 form-group">
                            <label for="gender" class="text-label form-label col-md-12">Gender<span class="required">*</span></label>
                            <div class="col-md-12">
                                <select name="gender" id="gender" class="form-control select2bs4">
                                    <option value="">---select---</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <div class="mb-3 form-group">
                            <label for="phone" class="text-label form-label col-md-12">Phone Number<span class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="tel" name="phone" id="phone" class="form-control" placeholder="(+256)7XXXXXXXX" required>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="mb-3 form-group">
                            <label for="email" class="text-label form-label col-md-12">Email Address<span class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="email" name="email" class="form-control" id="email" aria-describedby="inputGroupPrepend2" placeholder="Email address" required>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="mb-3 form-group">
                            <label for="address" class="text-label form-label col-md-12">Address<span class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="text" name="address" id="address" class="form-control" placeholder="Plot, City">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="step-2" class="tab-pane step-2" role="tabpanel">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="mb-3 form-group">
                            <label for="business_name" class="text-label form-label col-md-12">Company Name<span class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="text" name="business_name" id="business_name" class="form-control" placeholder="Company name" required>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-2">
                        <div class="mb-3 form-group">
                            <label for="account_id" class="text-label form-label col-md-12">
                                Account<span class="required">*</span>
                            </label>
                            <div class="col-md-12">
                                <select name="account_id" id="account_id" class="form-control select2bs4" style="width: 100%;">
                                    <option value="">---select---</option>
                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <div class="mb-3 form-group">
                            <label for="business_email" class="text-label form-label col-md-12">Company Email Address<span class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="email" id="business_email" class="form-control" name="business_email" placeholder="Company email" required>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-2">
                        <div class="mb-3 form-group">
                            <label for="business_contact" class="text-label form-label col-md-12">Company Phone Number<span class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="tel" name="business_contact" id="business_contact" class="form-control" placeholder="(+256)7XXXXXXXX" required>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <div class="mb-3 form-group">
                            <label for="business_alt_contact" class="text-label form-label col-md-12">Alternate Phone Number</label>
                            <div class="col-md-12">
                                <input type="tel" name="business_alt_contact" id="business_alt_contact" class="form-control" placeholder="(+256)7XXXXXXXX" required>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="mb-3 form-group">
                            <label for="business_address" class="text-label form-label col-md-12">Physical Address<span class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="text" name="business_address" id="business_address" class="form-control" placeholder="Physical Address" required>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="step-3" class="tab-pane step-3" role="tabpanel">
                <div class="row align-items-center">
                    <div class="col-lg-12 mb-2">
                        <div class="mb-3 form-group">
                            <label for="business_slogan" class="text-label form-label col-md-12">
                                Company slogan<span class="required">*</span>
                            </label>
                            <div class="col-md-12">
                                <input type="text" name="business_slogan" id="business_slogan" class="form-control" placeholder="Company slogan" required>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-2">
                        <div class="mb-3 form-group">
                            <label for="business_abbr" class="text-label form-label col-md-12">
                                Company short name
                            </label>
                            <div class="col-md-12">
                                <input type="text" name="business_abbr" id="business_abbr" class="form-control" placeholder="Company Abbreviation">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <div class="mb-3 form-group">
                            <label for="currency_id" class="text-label form-label col-md-12">
                                Currency<span class="required">*</span>
                            </label>
                            <div class="col-md-12">
                                <select name="currency_id" id="currency_id" class="form-control select2bs4" style="width: 100%;">
                                    <option value="">---select---</option>
                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-2">
                        <div class="mb-3 form-group">
                            <label for="round_off" class="text-label form-label col-md-12">
                                Round off (Incase of decimals)<span class="required">*</span>
                            </label>
                            <div class="col-md-12">
                                <input type="number" name="round_off" id="round_off" class="form-control" placeholder="Round off" value="100" min="0">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <div class="mb-3 form-group">
                            <label for="tax_rate" class="text-label form-label col-md-12">Taxation Rate (%)</label>
                            <div class="col-md-12">
                                <input type="number" name="tax_rate" id="tax_rate" class="form-control" placeholder="Taxation" value="30" min="0" max="100">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="mb-3 form-group">
                            <label for="logo" class="text-label form-label col-md-12">
                                Company logo<span class="required">*</span>
                            </label>
                            <div class="col-md-12">
                                <input type="file" name="logo" id="logo" class="form-control" placeholder="Company logo">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="toolbar float-end">
        <button type="button" class="btn btn-sm btn-outline-secondary" id="prev-step">Previous</button>
        <button type="button" class="btn btn-sm btn-outline-info" id="next-step">Next</button>
        <button type="button" class="btn btn-sm btn-outline-success" id="submit-step" style="display:none;">Submit</button>
    </div>
</div>
<p class="text-center">Already registered?
    <a class="btn-link text-primary" href="/admin">Sign in</a>
</p>

<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>

<script src="/assets/vendor/jquery-steps/build/jquery.steps.min.js"></script>
<script src="/assets/vendor/jquery-validation/jquery.validate.min.js"></script>
<!-- Form validate init -->
<script src="/assets/js/plugins-init/jquery.validate-init.js"></script>


<!-- Form Steps -->
<script src="/assets/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js"></script>
<script src="/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function() {
        $("#prev-step").hide();
        // Initialize the plugin with custom toolbar settings
        $('#smartwizard').smartWizard({
            theme: 'dots',
            transitionEffect: 'fade',
            toolbarSettings: {
                toolbarPosition: 'none' // Hide the default toolbar
            },
            keyboardSettings: {
                keyNavigation: false, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
            },
        });

        // Event listeners for custom buttons
        $("#next-step").on("click", function() {
            submitCurrentStepData('next');
        });

        $("#prev-step").on("click", function() {
            submitCurrentStepData('prev');
        });

        $("#submit-step").on("click", function() {
            submitCurrentStepData('submit');
        });

        // Hook into the step changed event
        $("#smartwizard").on("stepChanged", function(e, anchorObject, stepIndex, stepDirection) {
            var stepCount = $('#smartwizard').smartWizard("getStepCount");

            if (stepIndex === stepCount - 1) { // Last step
                $("#next-step").hide();
                $("#submit-step").show();
            } else {
                $("#next-step").show();
                $("#submit-step").hide();
            }
        });

        // Initial step check
        var initialStepIndex = $('#smartwizard').smartWizard("getStepIndex");
        var initialStepCount = $('#smartwizard').smartWizard("getStepCount");
        if (initialStepIndex === initialStepCount - 1) {
            $("#next-step").hide();
            $("#submit-step").show();
        } else {
            $("#next-step").show();
            $("#submit-step").hide();
        }

        $.ajax({
            url: "/admin/fetch-utilities/all",
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                if (response.accounts && (response.accounts.length > 0)) {
                    $("#account_id").html('<option value="">-- select --</option>');
                    $.each(response.accounts, function(index, account) {
                        $("#account_id").append('<option value="' + account["id"] + '">' + account["name"] + '</option>');
                    });
                } else {
                    $("#account_id").html('<option value="">No Account</option>');
                }

                if (response.currency && (response.currency.length > 0)) {
                    $("#currency_id").html('<option value="">-- select --</option>');
                    $.each(response.currency, function(index, currency) {
                        $("#currency_id").append('<option value="' + currency["id"] + '">' + currency["currency"] + ' ~ ' + currency["symbol"] + '</option>');
                    });
                } else {
                    $("#currency_id").html('<option value="">No Currency</option>');
                }
            },
            error: function(err) {
                $("#account_id").html('<option value="">Error Occurred</option>');
                $("#currency_id").html('<option value="">Error Occurred</option>');
            },
        });

        // remove errors on input fields
        $("input").on("input", function() {
            $(this).parent().removeClass('has-error');
            $(this).next().empty();
        });
        // remove errors on select boxes
        $("select").on("change", function() {
            $(this).parent().removeClass('has-error');
            $(this).next().next().empty();
        });

        //Initialize Select2 Elements
        $(".select2").select2();
        $('.select2bs5').select2({
            theme: 'bootstrap-5',
        });

        $('.select2bs4').each(function() {
            $(this).select2({
                // theme: 'bootstrap-5',
                dropdownParent: $(this).parent(),
            });
        });
    });

    function submitCurrentStepData(action = 'prev') {
        $(".form-group").removeClass("has-error"); // clear error class
        $(".help-block").empty(); // clear error string

        // Get the current step
        var currentStepIndex = $('#smartwizard').smartWizard("getStepIndex");
        $('[name="step_no"]').val(currentStepIndex + 1);
        // pick step number
        var step = $('[name="step_no"]').val();

        if (action == 'prev') {
            var next = Number(step) - 1;
            // reset application step
            $('[name="step_no"]').val(next);

            // remove active class to current step
            $("a.step-" + step).removeClass("active");
            $("a.step-" + step).addClass("inactive");
            // add active class to pev step
            $("a.step-" + next).addClass("active");
            $("a.step-" + next).removeClass("inactive");
            // show\hide back btn
            if (currentStepIndex == 1) {
                $("#prev-step").hide();
            }
            // show\hide back btn
            if (next < 3) {
                $("#submit-step").hide();
                $("#next-step").show();
            }
            $('#smartwizard').smartWizard("prev");
        } else {
            var next = Number(step) + 1;
            // show\hide back btn
            if (next > 1) {
                $("#prev-step").show();
            }
            if (action === 'next') {
                $("#next-step").text("validating..."); //change button text
                $("#next-step").attr("disabled", true); //set button disable
            }
            if (action === 'submit') {
                $("#submit-step").text("submitting..."); //change button text
                $("#submit-step").attr("disabled", true); //set button disable
            }

            var formData = new FormData($("#registerForm")[0]);

            var url = "/admin/account/signup";

            // Send data to the server
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: "JSON",
                success: function(response) {
                    if (!response.inputerror) {
                        if (response.status && response.error == null) {
                            if (action === 'next') {
                                // hide\show submit and next buttons by current step
                                if (next == 3) {
                                    $("#submit-step").show();
                                    $("#next-step").hide();
                                } else {
                                    $("#submit-step").hide();
                                    $("#next-step").show();
                                }
                                $('#smartwizard').smartWizard("next");
                                $("#next-step").text("Next");
                                $("#next-step").attr("disabled", false);
                            } else if (action === 'submit') {
                                var redirect = response.url;

                                $("#registerForm")[0].reset();
                                $(".msg").addClass("text-success").text(response.messages);
                                $('#submit-step').text('redirecting...');
                                $('#submit-step').attr('disabled', true);
                                hideNotifyMessage();
                                window.location.assign(redirect);
                                $("#submit-step").text("Submit");
                                $("#submit-step").attr("disabled", false);
                            }
                        } else if (response.error != null) {
                            $(".msg").addClass("text-danger").text(response.error + ": " + response.messages);
                            $("#next-step").text("Next");
                            $("#next-step").attr("disabled", false);
                            $("#submit-step").text("Submit");
                            $("#submit-step").attr("disabled", false);
                            hideNotifyMessage();
                        } else {
                            $(".msg").addClass("text-danger").text("Something unexpected happened, try again later");
                            $("#next-step").text("Next");
                            $("#next-step").attr("disabled", false);
                            $("#submit-step").text("Submit");
                            $("#submit-step").attr("disabled", false);
                            hideNotifyMessage();
                        }
                    } else {
                        for (var i = 0; i < response.inputerror.length; i++) {
                            $('[name="' + response.inputerror[i] + '"]')
                                .parent()
                                .parent()
                                .addClass("has-error");
                            $('[name="' + response.inputerror[i] + '"]')
                                .closest(".form-group")
                                .find(".help-block")
                                .text(response.error_string[i]);
                        }
                        $("#next-step").text("Next");
                        $("#next-step").attr("disabled", false);
                        $("#submit-step").text("Submit");
                        $("#submit-step").attr("disabled", false);
                    }
                },
                error: function(xhr, status, error) {
                    $(".msg").addClass("text-danger").text('An error occurred: ' + error);
                    $("#next-step").text("Next");
                    $("#next-step").attr("disabled", false);
                    $("#submit-step").text("Submit");
                    $("#submit-step").attr("disabled", false);
                    hideNotifyMessage();
                }
            });
        }
    }

    function hideNotifyMessage() {
        setTimeout(function() {
            $(".msg").removeClass("text-success").text("");
            $(".msg").removeClass("text-danger").text("");
        }, 10000);
    }
</script>
<?= $this->endSection(); ?>