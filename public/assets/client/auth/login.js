$(function () {
    $.validator.setDefaults({
        submitHandler: function () {
            clientAuthentication();
        },
    });

    $("#form").validate({
        rules: {
            phone: {
                required: true,
                pattern: '[0-9\-\(\)\s ]+',
                // email: true,
                minlength: 10,
                maxlength: 13,
                
            },
            password: {
                required: true,
                minlength: 6,
            },
        },
        messages: {
            phone: {
                required: "Please provide your registered phone number",
                pattern: "Please provide a valid phone number",
                // email: "Please enter a valid email address",
                minlength: "Your phone number must be at least 10 digits long",
                maxlength: "Your phone number shouldn't be more than 13 digits long",
                
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long",
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

function clientAuthentication(){
    $('#btnSubmit').text('signing...');
    $('#btnSubmit').attr('disabled', true);
    var url = "/client/account/authentication";
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
            if (data.status && data.error == null)
            {
                var dashboard = "/client/dashboard";
                $("#form")[0].reset();
                $(".msg").addClass("text-success").text(data.messages);
                $('#btnSubmit').text('redirecting...');
                $('#btnSubmit').attr('disabled', true);
                hide_notify();
                window.location.assign(data.url);
            }else if (data.error == "notAuthorized") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                $('#btnSubmit').text('Sign In');
                $('#btnSubmit').attr('disabled', false);
                // hide_notify();
            }else if (data.error == "wrongEmail") {
                // $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                $('#btnSubmit').text('Sign In');
                $('#btnSubmit').attr('disabled', false);
                hide_notify();
            }else if (data.error == "wrongPassword") {
                // $("#form")[0].reset();
                $('#password').val('');
                $(".msg").addClass("text-danger").text(data.messages);
                $('#btnSubmit').text('Sign In');
                $('#btnSubmit').attr('disabled', false);
                // hide_notify();
            }else if (data.error == "tokenNotSaved") {
                // $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                $('#btnSubmit').text('Sign In');
                $('#btnSubmit').attr('disabled', false);
                hide_notify();
            }else if (data.error == "alreadylogin") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                $('#btnSubmit').text('Sign In');
                $('#btnSubmit').attr('disabled', false);
                hide_notify();
            }else{
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('is-invalid'); //select parent twice to select div form-group class and add has-error class
                    $('[name="' + data.inputerror[i] + '"]').closest(".mb-4").find(".help-block").text(data.error_string[i]); //select span help-block class set text error string
                }
                $('#btnSubmit').text('Sign In');
                $('#btnSubmit').attr('disabled', false);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $("#form")[0].reset();
            $(".msg").addClass("text-danger").text(errorThrown+". Try again");
            hide_notify();
            $('#btnSubmit').text('Sign In');
            $('#btnSubmit').attr('disabled', false);
        }
    });
}

function hide_notify() {
    setTimeout(function () {
        $(".msg").removeClass("text-success").text("");
        $(".msg").removeClass("text-danger").text("");
    }, 10000);
}

$(document).ready(function () {
    $("input").change(function () {
        $(this).parent().parent().removeClass('is-invalid');
        //$(this).next().empty();
        $(this).closest(".form-group").find(".help-block").empty();
    });
    var remember = $.cookie("remember");
    if (remember == "true") {
        var username = $.cookie("email");
        var password = $.cookie("password");
        // autofill the fields 
        $("#email").val(username);
        $("#dz-password").val(password);
        $("#remember").attr("checked", true);
    }

    $("#form").submit(function () {
        if ($("#remember").is(":checked")) {
            var username = $("#email").val();
            var password = $("#dz-password").val();
            // set cookies to expire in 14 days
            $.cookie("email", username, { expires: 14 });
            $.cookie("password", password, { expires: 14 });
            $.cookie("remember", true, { expires: 14 });
        } else {
            // reset cookies
            $.cookie("email", null);
            $.cookie("password", null);
            $.cookie("remember", null);
        }
    });
});