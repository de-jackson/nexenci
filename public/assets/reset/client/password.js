$(function () {
    $.validator.setDefaults({
        submitHandler: function () {
            resetClientPassword();
        },
    });

    $("#form").validate({
        rules: {
            password: {
                required: true,
                minlength: 8,
            },
            password_confirm: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            },
        },
        messages: {
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long",
            },
            password_confirm: {
                required: "Please provide a confirm password",
                minlength: "Your confirm password must be at least 8 characters long",
                equalTo: "Confirm password does not match the password!",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });
});

function resetClientPassword(){
    $('#btnSubmit').html('<i class="fa fa-spinner fa-spin"></i> resetting...');
    $('#btnSubmit').attr('disabled', true);
    var url = "/client/account/password/create";
    var formData = new FormData($('#form')[0]);
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data)
        {
            if (data.status)
            {
                var dashboard = "/client/login";
                $("#form")[0].reset();
                $(".msg").addClass("text-success").text("Password Reset was Successful");
                hide_notify();
                window.location.assign(dashboard);
            }else if (data.status == 201) {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text("Password Reset was Unsuccessful. Try Again");
                hide_notify();
            }else if (data.messages == "wrongEmail") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text("We couldn't find your account");
                hide_notify();
            }else{
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('is-invalid'); //select parent twice to select div form-group class and add has-error class
                    $('[name="' + data.inputerror[i] + '"]').closest(".form-group").find(".help-block").text(data.error_string[i]); //select span help-block class set text error string
                }
            }

            $('#btnSubmit').text('Reset password');
            $('#btnSubmit').attr('disabled', false);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $("#form")[0].reset();
            $(".msg").addClass("text-danger").text("External Server Error Occured. Try again");
            hide_notify();
            $('#btnSubmit').text('Reset password');
            $('#btnSubmit').attr('disabled', false);
        }
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
        $(this).parent().parent().removeClass('is-invalid');
        //$(this).next().empty();
        $(this).closest(".form-group").find(".help-block").empty();
    });
});