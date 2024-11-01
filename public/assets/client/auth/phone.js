$(function () {
    $.validator.setDefaults({
        submitHandler: function () {
            passwordResetLink();
        },
    });

    $("#form").validate({
        rules: {
            phone: {
                required: true,
                pattern: '[0-9\-\(\)\s -]+',
                minlength: 10,
                maxlength: 13,
            },
        },
        messages: {
            phone: {
                required: "Please provide your phone number",
                pattern: "Please provide a valid phone number",
                minlength: "Your phone number must be at least 10 digits long",
                maxlength: "Your phone number shouldn't be more than 13 digits long",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".mb-4").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });
});

function passwordResetLink() {
    $("#btnSubmit").text("submitting...");
    $("#btnSubmit").attr("disabled", true);
    var url = "/client/account/password/recovery";
    var formData = new FormData($("#form")[0]);
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data) {
            if (data.status && data.error == null) {
                console.log(data.url)
                $("#form")[0].reset();
                $(".msg").addClass("text-success").text(data.messages);
                $('#btnSubmit').text('redirecting...');
                $('#btnSubmit').attr('disabled', true);
                $('#phoneRow').hide();
                setInterval(function () {
                    window.location.replace(data.url);
                }, 3000);
                /*
                $(".msg")
                    .addClass("text-success")
                    .text("We have sent a password reset link to your email. Kindly check your inbox to reset your password.");
                // hide_notify();
                */
                $('#phoneRow').hide();
                $("#btnSubmit").text("redirecting");
                $("#btnSubmit").attr("disabled", true);
            } else if (data.error == "notAuthorized") {
                $("#form")[0].reset();
                $(".msg")
                    .addClass("text-danger")
                    .text("You are not Authorized to Access this");
                hide_notify();
                $("#btnSubmit").text("Submit");
                $("#btnSubmit").attr("disabled", false);
            } else if (data.error == "wrongPhone") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text("We couldn't find your account");
                hide_notify();
                $("#btnSubmit").text("Submit");
                $("#btnSubmit").attr("disabled", false);
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]')
                        .parent()
                        .parent()
                        .addClass("is-invalid"); //select parent twice to select div form-group class and add has-error class
                    $('[name="' + data.inputerror[i] + '"]')
                        .closest(".form-group")
                        .find(".help-block")
                        .text(data.error_string[i]); //select span help-block class set text error string
                }
                $("#btnSubmit").text("Submit");
                $("#btnSubmit").attr("disabled", false);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $("#form")[0].reset();
            $(".msg")
                .addClass("text-danger")
                .text("External Error Occurred. Try again");
            hide_notify();
            $("#btnSubmit").text("Submit");
            $("#btnSubmit").attr("disabled", false);
        },
    });
}

function hide_notify() {
    setTimeout(function () {
        $(".msg").removeClass("text-success").text("");
        $(".msg").removeClass("text-danger").text("");
    }, 7200);
}

$(document).ready(function () {
    $("input").change(function () {
        $(this).parent().parent().removeClass("is-invalid");
        //$(this).next().empty();
        $(this).closest(".form-group").find(".help-block").empty();
    });
});
