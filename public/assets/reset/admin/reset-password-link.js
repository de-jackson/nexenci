$(function () {
  $.validator.setDefaults({
    submitHandler: function () {
      adminAuthentication();
    },
  });

  $("#form").validate({
    rules: {
      email: {
        required: true,
        email: true,
      },
    },
    messages: {
      email: {
        required: "Please enter an email address",
        email: "Please enter a valid email address",
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

function adminAuthentication() {
  $("#btnSubmit").text("sending...");
  $("#btnSubmit").attr("disabled", true);
  var url = "/admin/account/password/recovery";
  var formData = new FormData($("#form")[0]);
  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "JSON",
    success: function (data) {
      if (data.status && data.messages == "Email Sent") {
        $("#form")[0].reset();
        $(".msg")
          .addClass("text-success")
          .text(
            "We have sent a password reset link to your email. Kindly check your inbox to reset your password."
          );
        // $(".msg").addClass("text-success").text("We have emailed your password reset link via your preferred recovery mode");
        // hide_notify();
        $("#emailRow").hide();
        $("#btnSubmit").text("Password Reset Link Sent");
        $("#btnSubmit").attr("disabled", true);
      } else if (data.messages == "notAuthorized") {
        $("#form")[0].reset();
        $(".msg")
          .addClass("text-danger")
          .text("You are not Authorised to Access this");
        hide_notify();
        $("#btnSubmit").text("Send Password Reset Link");
        $("#btnSubmit").attr("disabled", false);
      } else if (data.messages == "wrongEmail") {
        $("#form")[0].reset();
        $(".msg").addClass("text-danger").text("We couldn't find your account");
        hide_notify();
        $("#btnSubmit").text("Send Password Reset Link");
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
        $("#btnSubmit").text("Send Password Reset Link");
        $("#btnSubmit").attr("disabled", false);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $("#form")[0].reset();
      $(".msg")
        .addClass("text-danger")
        .text("External Error Occured. Try again");
      hide_notify();
      $("#btnSubmit").text("Send Password Reset Link");
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
