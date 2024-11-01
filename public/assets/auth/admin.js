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
        // email: true,
      },
      password: {
        required: true,
        minlength: 6,
      },
    },
    messages: {
      email: {
        required: "Please enter an email or phone number",
        // email: "Please enter a valid email address",
      },
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 6 characters long",
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
  $("#btnSubmit").text("signing...");
  $("#btnSubmit").attr("disabled", true);
  var url = "/admin/account/authentication";
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
        //var dashboard = "/admin/dashboard";
        var url = data.url;
        $("#form")[0].reset();
        $(".msg").addClass("text-success").text(data.messages);
        $("#btnSubmit").text("redirecting...");
        // $('#btnSubmit').attr('disabled', true);
        hideNotifyMessage();
        window.location.assign(url);
      } else if (data.error == "notAuthorized") {
        $("#form")[0].reset();
        $(".msg").addClass("text-danger").text(data.messages);
        $("#btnSubmit").text("Sign In");
        $("#btnSubmit").attr("disabled", false);
        hideNotifyMessage();
      } else if (data.error == "wrongEmail") {
        $("#form")[0].reset();
        $(".msg").addClass("text-danger").text(data.messages);
        $("#btnSubmit").text("Sign In");
        $("#btnSubmit").attr("disabled", false);
        hideNotifyMessage();
      } else if (data.error == "wrongPassword") {
        $("#form")[0].reset();
        $(".msg").addClass("text-danger").text(data.messages);
        $("#btnSubmit").text("Sign In");
        $("#btnSubmit").attr("disabled", false);
        hideNotifyMessage();
      } else if (data.error == "tokenNotSaved") {
        $("#form")[0].reset();
        $(".msg").addClass("text-danger").text(data.messages);
        $("#btnSubmit").text("Sign In");
        $("#btnSubmit").attr("disabled", false);
        hideNotifyMessage();
      } else if (data.error == "alreadylogin") {
        $("#form")[0].reset();
        $(".msg").addClass("text-danger").text(data.messages);
        $("#btnSubmit").text("Sign In");
        $("#btnSubmit").attr("disabled", false);
        hideNotifyMessage();
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
        $("#btnSubmit").text("Sign In");
        $("#btnSubmit").attr("disabled", false);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $("#form")[0].reset();
      $(".msg")
        .addClass("text-danger")
        .text(errorThrown + ". Try again");
      hideNotifyMessage();
      $("#btnSubmit").text("Sign In");
      $("#btnSubmit").attr("disabled", false);
    },
  });
}

function hideNotifyMessage() {
  setTimeout(function () {
    $(".msg").removeClass("text-success").text("");
    $(".msg").removeClass("text-danger").text("");
  }, 10000);
}

function sendTokenCode(menu) {
  $("#btnSubmit").text("submitting...");
  $("#btnSubmit").attr("disabled", true);
  if (menu == "sendcode") {
    var url = "/admin/account/password/account";
  } else {
    var url = "/admin/account/password/authenticate";
  }

  var formData = new FormData($("#form")[0]);
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
        $("#btnSubmit").text("redirecting...");
        hideNotifyMessage();
        if (data.url) {
          setInterval(function () {
            window.location.replace(data.url);
          }, 7000);
          //window.location.assign(data.url);
        }
      } else if (data.error === "nointernet") {
        $(".msg").addClass("text-danger").text(data.messages);
        // $('#btnSubmit').text('redirecting...');
        hideNotifyMessage();
      } else if (data.error == "notAuthorized" || data.error == "noAccount") {
        $("#form")[0].reset();
        $(".msg").addClass("text-danger").text(data.messages);
        // $('#btnSubmit').text('Sign In');
        $("#btnSubmit").attr("disabled", false);
        hideNotifyMessage();
      } else if (data.error == "wrongEmail" || data.error == "wrongCode") {
        $("#form")[0].reset();
        $(".msg").addClass("text-danger").text(data.messages);
        // $('#btnSubmit').text('Sign In');
        $("#btnSubmit").attr("disabled", false);
        hideNotifyMessage();
      } else if (data.error == "wrongPassword" || data.error == "codeExpired") {
        $("#form")[0].reset();
        $(".msg").addClass("text-danger").text(data.messages);
        // $('#btnSubmit').text('Sign In');
        $("#btnSubmit").attr("disabled", false);
        hideNotifyMessage();
      } else if (data.error == "tokenNotSaved") {
        $("#form")[0].reset();
        $(".msg").addClass("text-danger").text(data.messages);
        // $('#btnSubmit').text('Sign In');
        $("#btnSubmit").attr("disabled", false);
        hideNotifyMessage();
      } else if (data.error == "alreadylogin") {
        $("#form")[0].reset();
        $(".msg").addClass("text-danger").text(data.messages);
        // $('#btnSubmit').text('Sign In');
        $("#btnSubmit").attr("disabled", false);
        hideNotifyMessage();
      } else {
        for (var i = 0; i < data.inputerror.length; i++) {
          $('[name="' + data.inputerror[i] + '"]')
            .parent()
            .parent()
            .addClass("has-error"); //select parent twice to select div form-group class and add has-error class
          $('[name="' + data.inputerror[i] + '"]')
            .next()
            .text(data.error_string[i]); //select span help-block class set text error string
        }
      }
      $("#btnSubmit").text("Submit"); //change button text
      $("#btnSubmit").attr("disabled", false); //set button enable
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $("#btnSubmit").text("Submit"); //change button text
      $("#btnSubmit").attr("disabled", false); //set button enable
      $(".msg")
        .addClass("text-danger")
        .text("External Error Occured. Kindly try again");
      hideNotifyMessage();
    },
  });
}

function clearSessionsBtn() {
  // Send an AJAX request to clear sessions
  fetch("/admin/account/password/sessions")
    .then((response) => response.text())
    .then((data) => {
      // Display the response message
      // alert(data);
      window.location.assign("/admin/login");
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

jQuery(document).ready(function () {
  $("input").change(function () {
    $(this).parent().parent().removeClass("is-invalid");
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
