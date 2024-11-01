$(function () {
    $.validator.setDefaults({
        submitHandler: function () {
            registerClient();
        },
    });

    $("#form").validate({
        rules: {
            name: {
                required: true,
                minlength: 6,
                maxlength: 30,
            },
            phone: {
                required: true,
                pattern: '[0-9\-\(\)\s ]+',
                minlength: 10,
                maxlength: 13,
            },
            password: {
                required: true,
                minlength: 6,
            },
            confirm_password: {
                required: true,
                minlength: 6,
                maxlength: 25,
                equalTo: "#dz-password"
            },
            terms: {
                required: true,
            }
        },
        messages: {
            name: {
                required: "Please provide your full name",
                minlength: "Your full name must be at least 6 characters long",
                maxlength: "Your full name shouldn't be more than 30 characters long",
            },
            phone: {
                required: "Please provide your phone number",
                pattern: "Please provide a valid phone number",
                minlength: "Your phone number must be at least 10 digits long",
                maxlength: "Your phone number shouldn't be more than 13 digits long",
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long",
            },
            confirm_password: {
                required: "Please provide a confirm password",
                minlength: "Your confirm password must be at least 6 characters long",
                equalTo: "Your confirm password must match the password field"
            },
            terms:{
                required: "Please check Terms & Conditions and Privacy Policy",
            }
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

function registerClient(){
    $('#btnSubmit').text('creating...');
    $('#btnSubmit').attr('disabled', true);
    var url = "/client/register";
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
                $('#btnSubmit').text('Create Account');
                $('#btnSubmit').attr('disabled', false);
                hide_notify();
            }else if (data.error == "wrongEmail") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                $('#btnSubmit').text('Create Account');
                $('#btnSubmit').attr('disabled', false);
                hide_notify();
            }else if (data.error == "wrongPassword") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                $('#btnSubmit').text('Create Account');
                $('#btnSubmit').attr('disabled', false);
                hide_notify();
            }else if (data.error == "tokenNotSaved") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                $('#btnSubmit').text('Create Account');
                $('#btnSubmit').attr('disabled', false);
                hide_notify();
            }else if (data.error == "alreadylogin") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                $('#btnSubmit').text('Create Account');
                $('#btnSubmit').attr('disabled', false);
                hide_notify();
            }else{
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('is-invalid'); //select parent twice to select div form-group class and add has-error class
                    $('[name="' + data.inputerror[i] + '"]').closest(".form-group").find(".help-block").text(data.error_string[i]); //select span help-block class set text error string
                }
                $('#btnSubmit').text('Create Account');
                $('#btnSubmit').attr('disabled', false);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $("#form")[0].reset();
            $(".msg").addClass("text-danger").text(errorThrown+". Try again");
            hide_notify();
            $('#btnSubmit').text('Create Account');
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