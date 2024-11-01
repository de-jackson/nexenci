$(function () {
    $.validator.setDefaults({
        submitHandler: function () {
            sendTokenCode('submitcode');
        },
    });

    $("#form").validate({
        rules: {
            code: {
                required: true,
                minlength: 6,
                maxlength: 6,
            },
        },
        messages: {
            code: {
                required: "Please provide a verification OTP Code.",
                minlength: "Your verification code must be at least 6 digits long.",
                maxlength: "Your verification code shouldn't be more than 6 digits long.",
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


function hideNotifyMessage() {
    setTimeout(function () {
        $(".msg").removeClass("text-success").text("");
        $(".msg").removeClass("text-danger").text("");
    }, 10000);
}

function sendTokenCode(menu) {
    $('#btnSubmit').attr('disabled', true);
    if (menu == "sendcode") {
        $('#btnSubmit').text('sending...');
        var url = "/client/account/token/sendcode";
    } else {
        $('#btnSubmit').text('submitting...');
        var url = "/client/account/token/authentication";
    }

    var formData = new FormData($('#form')[0]);
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data) {
            if (data.status == 200 && data.error == null) {
                $("#form")[0].reset();
                $(".msg").addClass("text-success").text(data.messages);
                $('#btnSubmit').text('redirecting...');
                $('#btnSubmit').attr('disabled', true);
                hideNotifyMessage();
                if (data.url != currentURL) {
                    // setInterval(function () {
                    //     window.location.replace(data.url);
                    // }, 3000);
                    //window.location.assign(data.url);
                    window.location.assign(data.url);
                }
            } else if (data.error === "noInternet") {
                $(".msg").addClass("text-danger").text(data.messages);
                // $('#btnSubmit').text('redirecting...');
                hideNotifyMessage();
            } else if (data.error == "notAuthorized" || data.error == "noAccount") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                // $('#btnSubmit').text('Sign In');
                $('#btnSubmit').attr('disabled', false);
                hideNotifyMessage();
            } else if (data.error == "wrongEmail" || data.error == "wrongCode") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                // $('#btnSubmit').text('Sign In');
                $('#btnSubmit').attr('disabled', false);
                hideNotifyMessage();
            } else if (data.error == "wrongPassword" || data.error == "codeExpired") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                // $('#btnSubmit').text('Sign In');
                $('#btnSubmit').attr('disabled', false);
                hideNotifyMessage();
            } else if (data.error == "tokenNotSaved") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                // $('#btnSubmit').text('Sign In');
                $('#btnSubmit').attr('disabled', false);
                hideNotifyMessage();
            } else if (data.error == "alreadylogin") {
                $("#form")[0].reset();
                $(".msg").addClass("text-danger").text(data.messages);
                // $('#btnSubmit').text('Sign In');
                $('#btnSubmit').attr('disabled', false);
                hideNotifyMessage();
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSubmit').text('Submit'); //change button text
            $('#btnSubmit').attr('disabled', false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#btnSubmit').text('Submit'); //change button text
            $('#btnSubmit').attr('disabled', false); //set button enable 
            $(".msg").addClass("text-danger").text("External Error Occured. Kindly try again");
            hideNotifyMessage();
        }
    });
}

function clearSessionsBtn() {
    // Send an AJAX request to clear sessions
    fetch("/client/account/clear/sessions")
        .then(response => response.text())
        .then(data => {
            // Display the response message
            // alert(data);
            window.location.assign('/client/login');
        })
        .catch(error => {
            console.error("Error:", error);
        });
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
        $("#password").val(password);
        $("#remember").attr("checked", true);
    }

    $("#form").submit(function () {
        if ($("#remember").is(":checked")) {
            var username = $("#email").val();
            var password = $("#password").val();
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