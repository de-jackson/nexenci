$(document).ready(function () {
  // load user menu permissions
  show_userPermissions();
  fAuthColor = fAuth == "True" ? "success" : "light";
  $("#fAuthBtn").addClass("fAuthColor");
  view_data(staff_id);
});

$(function () {
  // $.validator.setDefaults({
  //     submitHandler: function () {
  //         save_newPassword();
  //     },
  // });
  // $("#password_form").validate({
  //     rules: {
  //         currentpassword: {
  //             required: true,
  //             minlength: 8,
  //         },
  //         newpassword: {
  //             required: true,
  //             minlength: 8,
  //         },
  //         confirmpassword: {
  //             required: true,
  //             minlength: 8,
  //             equalTo: "#newpassword"
  //         },
  //     },
  //     messages: {
  //         password: {
  //             required: "Please provide current password",
  //             minlength: "Your password must be at least 8 characters long",
  //         },
  //         password: {
  //             required: "Please provide new password",
  //             minlength: "Your password must be at least 8 characters long",
  //         },
  //         password: {
  //             required: "Please provide confirm password",
  //             minlength: "Your password must be at least 8 characters long",
  //             equalTo: "Confirm password does not match the password!",
  //         },
  //     },
  //     errorElement: "span",
  //     errorPlacement: function (error, element) {
  //         error.addClass("invalid-feedback");
  //         element.closest(".form-group").append(error);
  //     },
  //     highlight: function (element, errorClass, validClass) {
  //         $(element).addClass("is-invalid");
  //     },
  //     unhighlight: function (element, errorClass, validClass) {
  //         $(element).removeClass("is-invalid");
  //     },
  // });
});

function change_password() {
  save_method = "update";
  $("#password_form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $('[name="id"]').val(userId);
  $('[name="menu"]').val("change");
  $(".modal-title").text("Reset Password");
  $("#passwordModal").modal("show");
}
// save password
function save_newPassword() {
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnReset").html('<i class="fa fa-spinner fa-spin"></i> resetting...');
  $("#btnReset").attr("disabled", true);
  var url;
  if (save_method == "add") {
    url = "/admin/password";
  } else {
    url = "/admin/profile/change-password";
  }
  var formData = new FormData($("#password_form")[0]);
  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "JSON",
    success: function (data) {
      if (!data.inputerror) {
        if (data.status && data.error == null) {
          $("#passwordModal").modal("hide");
          Swal.fire("Success!", data.messages, "success");
        } else if (data.error != null) {
          Swal.fire(data.error, data.messages, "error");
        } else {
          Swal.fire(
            "Error",
            "Something unexpected happened, try again later",
            "error"
          );
        }
      } else {
        for (var i = 0; i < data.inputerror.length; i++) {
          $('[name="' + data.inputerror[i] + '"]')
            .parent()
            .parent()
            .addClass("has-error");
          $('[name="' + data.inputerror[i] + '"]')
            .closest(".form-group")
            .find(".help-block")
            .text(data.error_string[i]);
        }
      }
      $("#btnReset").text("Reset");
      $("#btnReset").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnReset").text("Reset");
      $("#btnReset").attr("disabled", false);
    },
  });
}

function two_factorAuth(oldStatus) {
  Swal.fire({
    title: "Update 2-FA?",
    text: "Do you wish to update Two Factor Authentication status!",
    icon: "warning",
    showCancelButton: true,
    allowOutsideClick: false,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Update!",
    preConfirm: () => {
      return new Promise((resolve) => {
        Swal.showLoading();
        $.ajax({
          url: "/admin/profile/two-factor-auth/" + userId,
          type: "post",
          dataType: "JSON",
          success: function (data) {
            toogle_2faBtn(oldStatus)
            //if success reload ajax table
            if (data.status && data.error == null) {
              Swal.fire("Success!", data.messages, "success");
              resolve();
            } else if (data.error != null) {
              Swal.fire(data.error, data.messages, "error");
              Swal.close();
            } else {
              Swal.fire(
                "Error",
                "Something unexpected happened, try again later",
                "error"
              );
              Swal.close();
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, "error");
            Swal.close();
          },
          complete: function () {
            // Close the SweetAlert2 modal
            Swal.close();
          },
        });
      });
    },
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Success!",
        text: "Two Factor Authentication updated successfully!",
        icon: "success",
      });
    }
  });
}
// load staff data
function view_data(id) {
  $.ajax({
    url: "/admin/staff/administrator/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="staffID"]').val(data.staffID);
      $('[name="staff_name"]').val(data.staff_name);
      $('[name="mobile"]').val(data.mobile);
      $('[name="alternate_mobile"]').val(data.alternate_mobile);
      $('[name="email"]').val(data.email);
      $('[name="address"]').val(data.address);
      $('[name="id_type"]').val(data.id_type);
      $('[name="id_number"]').val(data.id_number);
      $('[name="id_expiry"]').val(data.id_expiry_date);
      $('[name="position_id"]').val(data.position);
      $('[name="department"]').val(data.department_name);
      $('[name="branch_id"]').val(data.branch_name);
      $('[name="qualifications"]').val(data.qualifications);
      $('[name="salary_scale"]').val(data.salary_scale);
      $('[name="bank_name"]').val(data.bank_name);
      $('[name="bank_branch"]').val(data.bank_branch);
      $('[name="bank_account"]').val(data.bank_account);
      $('[name="gender"]').val(data.gender);
      $('[name="religion"]').val(data.religion);
      $('[name="marital_status"]').val(data.marital_status);
      $('[name="nationality"]').val(data.nationality);
      $('[name="date_of_birth"]').val(data.date_of_birth);
      $('[name="appointment_type"]').val(data.appointment_type);
      $('[name="view_reg_date"]').val(data.reg_date);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      // passport photo
      if (
        data.photo &&
        imageExists("/uploads/staffs/admins/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/staffs/admins/passports/' +
            data.photo +
            '" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      }
      // id photos
      if (
        data.id_photo_front &&
        imageExists("/uploads/staffs/admins/ids/front/" + data.id_photo_front)
      ) {
        $("#id-previewFront div").html(
          '<img src="/uploads/staffs/admins/ids/front/' +
            data.id_photo_front +
            '" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      } else {
        $("#id-previewFront div").html(
          '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      }
      if (
        data.id_photo_back &&
        imageExists("/uploads/staffs/admins/ids/back/" + data.id_photo_back)
      ) {
        $("#id-previewBack div").html(
          '<img src="/uploads/staffs/admins/ids/back/' +
            data.id_photo_back +
            '" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      } else {
        $("#id-previewBack div").html(
          '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      }
      // signiture
      if (
        data.signature &&
        imageExists("/uploads/staffs/admins/signatures/" + data.signature)
      ) {
        $("#signature-preview div").html(
          '<img src="/uploads/staffs/admins/signatures/' +
            data.signature +
            '" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#signature-preview div").html(
          '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      $("#view_modal").modal("show"); // show bootstrap modal when complete loaded
      $(".modal-title").text("View " + data.staff_name); // Set title to Bootstrap modal title
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// load staff data
function view_data(id) {
  $.ajax({
    url: "/admin/staff/administrator/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="staffID"]').val(data.staffID);
      $('[name="staff_name"]').val(data.staff_name);
      $('[name="mobile"]').val(data.mobile);
      $('[name="alternate_mobile"]').val(data.alternate_mobile);
      $('[name="email"]').val(data.email);
      $('[name="address"]').val(data.address);
      $('[name="id_type"]').val(data.id_type);
      $('[name="id_number"]').val(data.id_number);
      $('[name="id_expiry"]').val(data.id_expiry_date);
      $('[name="position_id"]').val(data.position);
      $('[name="department"]').val(data.department_name);
      $('[name="branch_id"]').val(data.branch_name);
      $('[name="qualifications"]').val(data.qualifications);
      $('[name="salary_scale"]').val(data.salary_scale);
      $('[name="bank_name"]').val(data.bank_name);
      $('[name="bank_branch"]').val(data.bank_branch);
      $('[name="bank_account"]').val(data.bank_account);
      $('[name="gender"]').val(data.gender);
      $('[name="religion"]').val(data.religion);
      $('[name="marital_status"]').val(data.marital_status);
      $('[name="nationality"]').val(data.nationality);
      $('[name="date_of_birth"]').val(data.date_of_birth);
      $('[name="appointment_type"]').val(data.appointment_type);
      $('[name="view_reg_date"]').val(data.reg_date);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      // passport photo
      if (
        data.photo &&
        imageExists("/uploads/staffs/admins/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/staffs/admins/passports/' +
            data.photo +
            '" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      }
      // id photos
      if (
        data.id_photo_front &&
        imageExists("/uploads/staffs/admins/ids/front/" + data.id_photo_front)
      ) {
        $("#id-previewFront div").html(
          '<img src="/uploads/staffs/admins/ids/front/' +
            data.id_photo_front +
            '" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      } else {
        $("#id-previewFront div").html(
          '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      }
      if (
        data.id_photo_back &&
        imageExists("/uploads/staffs/admins/ids/back/" + data.id_photo_back)
      ) {
        $("#id-previewBack div").html(
          '<img src="/uploads/staffs/admins/ids/back/' +
            data.id_photo_back +
            '" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      } else {
        $("#id-previewBack div").html(
          '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      }
      // signiture
      if (
        data.signature &&
        imageExists("/uploads/staffs/admins/signatures/" + data.signature)
      ) {
        $("#signature-preview div").html(
          '<img src="/uploads/staffs/admins/signatures/' +
            data.signature +
            '" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#signature-preview div").html(
          '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      $("#view_modal").modal("show"); // show bootstrap modal when complete loaded
      $(".modal-title").text("View " + data.staff_name); // Set title to Bootstrap modal title
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}


let toogle_2faBtn = (status) => {
  if (status.toLowerCase() === 'true') {
    // 2-FA is currently enabled, so show option to disable it
    $('#fAuthBtn').removeClass('btn-danger').addClass('btn-info');
    $('#fAuthBtn').text('Enable 2-FA');
  } else {
    // 2-FA is currently disabled, so show option to enable it
    $('#fAuthBtn').removeClass('btn-info').addClass('btn-danger');
    $('#fAuthBtn').text('Disable 2-FA');
  }
};