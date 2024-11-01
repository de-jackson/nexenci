var save_method;
var tableId = "admins";
// dataTables url
var tableDataUrl = "/admin/staff/generate-admins/" + id;
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "photo", orderable: false, searchable: false },
  { data: "gender" },
  // { data: "department_name" },
  { data: "position" },
  { data: "mobile" },
  { data: "email" },
  { data: "address" },
  { data: "action", orderable: false, searchable: false },
];
// dataTables buttons config
var buttonsConfig = [];
// show create button
if (userPermissions.includes("create_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
  buttonsConfig.push({
    text: '<i class="fas fa-plus"></i>',
    className: "btn btn-sm btn-secondary create" + title,
    attr: {
      id: "create" + title,
    },
    titleAttr: "Add " + title,
    action: function () {
      add_administrator();
    },
  });
}
// show upload button
if (userPermissions.includes("import_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
  buttonsConfig.push({
    text: '<i class="fas fa-upload"></i>',
    className: "btn btn-sm btn-info import" + title,
    attr: {
      id: "import" + title,
    },
    titleAttr: "Import " + title + "(s/es)",
    action: function () {
      import_staff();
    },
  });
}
// show bulk-delete
if (
  userPermissions.includes("bulkDelete_" + menu.toLowerCase() + titleSlug) ||
  userPermissions === '"all"'
) {
  buttonsConfig.push({
    text: '<i class="fa fa-trash"></i>',
    className: "btn btn-sm btn-danger delete" + title,
    attr: {
      id: "delete" + title,
    },
    titleAttr: "Bulky Delete " + title,
    action: function () {
      bulk_deleteAdministrator();
    },
  });
}
// show reload table button by default
buttonsConfig.push({
  text: '<i class="fa fa-refresh"></i>',
  className: "btn btn-sm btn-warning",
  titleAttr: "Reload " + title + " Table",
  action: function () {
    reload_table(tableId);
  },
});
// show export buttons
if (userPermissions.includes("export_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
  buttonsConfig.push(
    {
      extend: "excel",
      className: "btn btn-sm btn-success",
      titleAttr: "Export " + title + " To Excel",
      text: '<i class="fas fa-file-excel"></i>',
      filename: title + " Information",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export " + title + " To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: title + " Information",
      extension: ".pdf",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-info",
      titleAttr: "Export " + title + " To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: title + " Information",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-secondary",
      titleAttr: "Copy " + title + " Information",
      text: '<i class="fas fa-copy"></i>',
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>" +
        title +
        " Information</h4><h5 class='text-center'>Printed On " +
        new Date().getHours() +
        " : " +
        new Date().getMinutes() +
        " " +
        new Date().toDateString() +
        "</h5>",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
      customize: function (win) {
        $(win.document.body)
          .css("font-size", "10pt")
          .css("font-family", "calibri")
          .prepend(
            '<img src="' +
              logo +
              '" style="position:absolute; top:0; left:0;width:100px;height:100px;" />'
          );
        $(win.document.body)
          .find("table")
          .addClass("compact")
          .css("font-size", "inherit");
        // Replace the page title with the actual page title
        $(win.document.head).find("title").text(title);
      },

      className: "btn btn-sm btn-primary",
      titleAttr: "Print " + title + " Information",
      text: '<i class="fa fa-print"></i>',
      filename: title + " Information",
    }
  );
}

$(document).ready(function () {
  // call to dataTable initialization function
  initializeDataTable(tableId, tableDataUrl, columnsConfig, buttonsConfig);
  // fetch branches
  selectBranch();
  $.ajax({
    url: "/admin/branches/get-branches",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("select#branchID").html('<option value="">-- select --</option>');
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          $("<option />")
            .val(data[i].id)
            .text(data[i].branch_name)
            .appendTo($("select#branchID"));
        }
      } else {
        $("select#branchID").html('<option value="">No Branch</option>');
      }
    },
    error: function (err) {
      $("select#branchID").html('<option value="">Error Occurred</option>');
    },
  });
  // get departments
  selectDepartment();
  $.ajax({
    type: "POST",
    dataType: "JSON",
    url: "/admin/departments/all-departments",
    success: function (data) {
      $("select#departmentID").html('<option value="">-- select --</option>');
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          $("<option />")
            .val(data[i].id)
            .text(data[i].department_name)
            .appendTo($("select#departmentID"));
        }
      } else {
        $("select#departmentID").html(
          '<option value="">No Department</option>'
        );
      }
    },
    error: function (err) {
      $("select#departmentID").html('<option value="">Error Occurred</option>');
    },
  });

  // load ajax options
  generateCustomeSettings("gender");
  generateCustomeSettings("nationality");
  generateCustomeSettings("maritalstatus");
  generateCustomeSettings("religion");
  generateCustomeSettings("relationships");
  generateCustomeSettings("idtypes");
  generateCustomeSettings("appointmenttypes");
});

function exportAdminForm() {
  var admin_id = $('[name="id"]').val();
  window.location.assign("/admin/staff/form/" + admin_id);
}

// pop add model
function add_administrator() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#formRow").show();
  $("#importRow").hide();
  $("#modal_form").modal("show");
  $(".modal-title").text("Add Administrator"); // Set Title to Bootstrap modal title
  $("#upload-label").text("Upload Photo");
  $("#user-photo-preview").html(
    '<img src="/assets/dist/img/nophoto.jpg" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
  );
  $("#id-labelFront").text("Upload Front ID Photo");
  $("#id-previewFront").html(
    '<img src="/assets/dist/img/id.jpg" alt="ID Front Preview" id="preview-idFront" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
  );
  $("#id-labelBack").text("Upload Back ID Photo");
  $("#id-previewBack").html(
    '<img src="/assets/dist/img/id.jpg" alt="ID Back Preview" id="preview-idBack" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
  );
  $("#sign-label").text("Upload Signature");
  $("#signature-preview").html(
    '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
  );
  $('[name="id"]').val(0);
  $('[name="mode"]').val("create");
  $("select#branchID").trigger("change");
  $("select#departmentID").trigger("change");
  $("select#positionID").trigger("change");
  $("select#gender").trigger("change");
  $("select#nationality").trigger("change");
  $("select#marital_status").trigger("change");
  $("select#religion").trigger("change");
  $("select#department_id").trigger("change");
  $("select#position_id").trigger("change");
  $("select#id_type").trigger("change");
  $("select#branch_id").trigger("change");
  $("select#appointment_type").trigger("change");
  setPhoneNumberWithCountryCode($("#mobile"), "");
  setPhoneNumberWithCountryCode($("#alternate_mobile"), "");
}
function import_staff() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#modal_form").modal("show"); // show bootstrap modal
  $(".modal-title").text("Import Administrators(s)");
  $("#importRow").show();
  $("#formRow").hide();
  $("#export").hide();
  $('[name="id"]').val(0);
  $('[name="mode"]').val("import");
  $('[name="account_type"]').val("Administrator");
}

// view
function view_administrator(id) {
  //Ajax Load data from ajax
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
function view_administrator_photo(id) {
  $.ajax({
    url: "/admin/staff/administrator/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $("#photo_modal_form").modal("show");
      $(".modal-title").text(data.name);
      $("#photo-preview").show(300);
      if (
        data.photo &&
        imageExists("/uploads/staffs/admins/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/staffs/admins/passports/' +
            data.photo +
            '" class="img-fluid thumbnail">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail">'
        );
      }
    },

    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// edit
function edit_administrator(id) {
  save_method = "update";
  $("#export").hide();
  $("#formRow").show();
  $("#importRow").hide();
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/admin/staff/administrator/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="staffID"]').val(data.staffID);
      $('[name="staff_name"]').val(data.staff_name);
      $('[name="account_type"]').val(data.account_type);
      $('[name="mobile"]').val(data.mobile);
      $('[name="alternate_mobile"]').val(data.alternate_mobile);
      setPhoneNumberWithCountryCode($("#mobile"), data.mobile);
      setPhoneNumberWithCountryCode($("#alternate_mobile"), data.alternate_mobile);
      $('[name="email"]').val(data.email);
      $('[name="address"]').val(data.address);
      $('[name="id_type"]').val(data.id_type).trigger("change");
      $('[name="id_number"]').val(data.id_number);
      $('[name="id_expiry"]').val(data.id_expiry_date);
      $('[name="position_id"]').val(data.position_id);
      $('[name="branch_id"]').val(data.branch_id).trigger("change");
      $('[name="qualifications"]').val(data.qualifications);
      $('[name="salary_scale"]').val(data.salary_scale);
      $('[name="bank_name"]').val(data.bank_name);
      $('[name="bank_branch"]').val(data.bank_branch);
      $('[name="bank_account"]').val(data.bank_account);
      $('[name="gender"]').val(data.gender).trigger("change");
      $('[name="religion"]').val(data.religion).trigger("change");
      $('[name="marital_status"]').val(data.marital_status).trigger("change");
      $('[name="nationality"]').val(data.nationality).trigger("change");
      $('[name="date_of_birth"]').val(data.date_of_birth);
      $('[name="appointment_type"]')
        .val(data.appointment_type)
        .trigger("change");
      $('[name="reg_date"]').val(data.reg_date);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      // passport photo
      if (
        data.photo &&
        imageExists("/uploads/staffs/admins/passports/" + data.photo)
      ) {
        $("#upload-label").text("Upload New Photo");
        $("#user-photo-preview").html(
          '<img src="/uploads/staffs/admins/passports/' +
            data.photo +
            '" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#upload-label").text("Upload Photo");
        $("#user-photo-preview").html(
          '<img src="/assets/dist/img/nophoto.jpg" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      // id photos
      if (
        data.id_photo_front &&
        imageExists("/uploads/staffs/admins/ids/front//" + data.id_photo_front)
      ) {
        $("#id-label").text("Update Photo");
        $("#id-previewFront").html(
          '<img src="/uploads/staffs/admins/ids/front//' +
            data.id_photo_front +
            '" alt="Image preview" id="preview-idFront" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      } else {
        $("#id-label").text("Upload Photo");
        $("#id-previewFront").html(
          '<img src="/assets/dist/img/id.jpg" alt="Image preview" id="preview-idFront" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      }
      if (
        data.id_photo_back &&
        imageExists("/uploads/staffs/admins/ids/back//" + data.id_photo_back)
      ) {
        $("#id-label").text("Update Photo");
        $("#id-previewBack").html(
          '<img src="/uploads/staffs/admins/ids/back//' +
            data.id_photo_back +
            '" alt="Image preview" id="preview-idBack" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      } else {
        $("#id-label").text("Upload Photo");
        $("#id-previewBack").html(
          '<img src="/assets/dist/img/id.jpg" alt="Image preview" id="preview-idBack" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      }
      // signiture
      if (
        data.signature &&
        imageExists("/uploads/staffs/admins/signatures/" + data.signature)
      ) {
        $("#sign-label").text("Upload Signature");
        $("#signature-preview").html(
          '<img src="/uploads/staffs/admins/signatures/' +
            data.signature +
            '" alt="Image preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#sign-label").text("Upload Signature");
        $("#signature-preview").html(
          '<img src="/assets/dist/img/sign.png" alt="Image preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      $("#modal_form").modal("show"); // show bootstrap modal when complete loaded
      $(".modal-title").text("Update " + data.staff_name); // Set title to Bootstrap modal title
      department_position(data.department_id, data.position_id);
      branch(data.branch_id);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
function edit_staffStatus(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: name + " access status will be changed!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Update!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/staff/update-staffStatus/" + id,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
            reload_table(tableId);
          } else if (data.error != null) {
            Swal.fire(data.error, data.messages, "error");
          } else {
            Swal.fire(data.error, data.messages, "error");
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    }
  });
}
// save
function save_administrator() {
  aID = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSav").text("Submitting..."); //change button text
  $("#btnSav").attr("disabled", true); //set button disable
  var url, method;
  if (save_method == "add") {
    url = "/admin/staff/administrator";
  } else {
    url = "/admin/staff/edit-administrator/" + aID;
  }
  // ajax adding data to database
  var formData = new FormData($("#form")[0]);
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
          $("#modal_form").modal("hide");
          Swal.fire("Success!", data.messages, "success");
          reload_table(tableId);
        } else if (data.error != null) {
          Swal.fire(data.error, data.messages, "error");
        } else {
          Swal.fire(data.error, data.messages, "error");
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
      $("#btnSav").text("Submit"); //change button text
      $("#btnSav").attr("disabled", false); //set button enable
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnSav").text("Submit"); //change button text
      $("#btnSav").attr("disabled", false); //set button enable
    },
  });
}
// delete
function delete_administrator(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to recover administrator" + name + "!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/staff/administrator/" + id,
        type: "DELETE",
        dataType: "JSON",
        success: function (data) {
          //if success reload ajax table
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
          } else if (data.error != null) {
            Swal.fire(data.error, data.messages, "error");
          } else {
            Swal.fire(data.error, data.messages, "error");
          }
          reload_table(tableId);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    }
  });
}
function bulk_deleteAdministrator() {
  var list_id = [];
  $(".data-check:checked").each(function () {
    list_id.push(this.value);
  });
  if (list_id.length > 0) {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger",
      },
      buttonsStyling: true,
    });

    swalWithBootstrapButtons
      .fire({
        title: "Are you sure?",
        text:
          "Your will not be able to recover selected " +
          list_id.length +
          " administrator(s) once deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: false,
      })
      .then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: "POST",
            data: {
              id: list_id,
            },
            url: "/admin/staff/administratorBulk-delete",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Success!", data.messages, "success");
                reload_table(tableId);
              } else if (data.error != null) {
                Swal.fire(data.error, data.messages, "error");
              } else {
                Swal.fire(data.error, data.messages, "error");
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              Swal.fire(textStatus, errorThrown, "error");
            },
          });
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            "Cancelled",
            "Bulky delete cancelled :)",
            "error"
          );
        }
      });
  } else {
    Swal.fire("Sorry!", "No administrator selected....", "error");
  }
}

$("select#departmentID").on("change", function () {
  if (this.value == 0) {
    $("select#positionID").html(
      '<option value="">-- select department--</option>'
    );
  } else {
    var department_id = $("select#departmentID").val();
    // select positions from db
    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: "/admin/positions/department-positions/" + department_id,
      success: function (data) {
        $("select#positionID").html('<option value="">-- select --</option>');
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].position)
              .appendTo($("select#positionID"));
          }
        } else {
          $("select#positionID").html('<option value="">No Position</option>');
        }
      },
      error: function (err) {
        $("select#positionID").html('<option value="">Error Occured</option>');
      },
    });
  }
});
