var save_method;
var table = "users";

// dataTables url
var tableData = "/admin/generate-users";
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false },
  { data: "photo", orderable: false, searchable: false },
  { data: "gender" },
  { data: "mobile" },
  { data: "email" },
  { data: "account_type" },
  { data: "position" },
  { data: "access_status" },
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
      add_user();
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
    titleAttr: "Import " + title + " Information",
    action: function () {
      import_user();
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
      bulk_delete_user();
    },
  });
}
// show reload table button by default
buttonsConfig.push({
  text: '<i class="fa fa-refresh"></i>',
  className: "btn btn-sm btn-warning",
  titleAttr: "Reload " + title + " Information",
  action: function () {
    reload_table(table);
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
      titleAttr: "Export " + title + " Information To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: title + "  Information",
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
        '<h3 class="text-center text-bold">' +
        businessName +
        "</h3>" +
        '<h4 class="text-center text-bold">' +
        title +
        "  Information</h4>" +
        '<h5 class="text-center">Printed On ' +
        new Date().toDateString() +
        " " +
        new Date().getHours() +
        ":" +
        new Date().getMinutes() +
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
          )
          .find("table")
          .addClass("compact")
          .css("font-size", "inherit");
        // Hide the page title
        //$(win.document.head).find('title').remove();
        $(win.document.head).find("title").text(title);
      },

      className: "btn btn-sm btn-primary",
      titleAttr: "Print " + title + " Information",
      text: '<i class="fa fa-print"></i>',
      filename: title + "  Information",
    }
  );
}

$(document).ready(function () {
  //check all
  $("#check-all").click(function () {
    $(".data-check").prop("checked", $(this).prop("checked"));
  });

  // call to dataTable initialization function
  initializeDataTable(table, tableData, columnsConfig, buttonsConfig);

  $.ajax({
    type: "POST",
    dataType: "JSON",
    url: "/admin/departments/all-departments",
    success: function (data) {
      $("select#departmentID").html('<option value="">-- select --</option>');
      $("select#positionID").html('<option value="">-- select --</option>');
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
});

function reload_table(table) {
  $("#" + table)
    .DataTable()
    .ajax.reload();
  /* This is to uncheck the column header check box */
  $("input[type=checkbox]").each(function () {
    this.checked = false;
  });
}

function userForm() {
  var id = $('[name="id"]').val();
  window.location.assign("/admin/user-Forms/" + id);
}
function add_user() {
  save_method = "add";
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#modal_form").modal("show"); // show bootstrap modal
  $(".modal-title").text("Add New User");
  $("#btnSave").text("Add");
  $("#upload-label").text("Upload Photo");
  $("#user-photo-preview").html(
    '<img src="/assets/dist/img/nophoto.jpg" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
  );
  $('[name="id"]').val(0);
  $("#userRow").show();
  $("#importRow").hide();
  $("#permissionsRow").hide();
  $("#passwordRow").hide();
  $("#departmentRow").show();
}

function import_user() {
  save_method = "import";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#modal_form").modal("show"); // show bootstrap modal
  $(".modal-title").text("Import User(s)");
  $("#btnSave").text("Import");
  $("#userRow").hide();
  $("#permissionsRow").hide();
  $("#importRow").show();
}
// generate new user password
function create_password(id, password) {
  save_method = "password";
  $("#userRow").hide();
  $("#importRow").hide();
  $("#permissionsRow").hide();
  $("#passwordRow").show();
  $("#departmentRow").hide();
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/admin/user/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(id);
      $('[name="menu"]').val("regenerate");
      $('[name="username"]').val(data.name);
      $('[name="mobile"]').val(data.mobile);
      $('[name="user_email"]').val(data.email);
      $('[name="password"]').val(password);
      $('[name="c_password"]').val(password);
      $("#modal_form").modal("show");
      $("#btnSave").text("Submit");
      $(".modal-title").text("Generate " + data.name + " New Password");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      swal(textStatus, errorThrown, "error");
    },
  });
}

function save_user_method() {
  var url, type;
  var id = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  if (save_method == "add") {
    $("#btnSave").text("adding...");
    $("#btnSave").attr("disabled", true);
    url = "/admin/store";
  } else if (save_method == "import") {
    $("#btnSave").text("importing...");
    $("#btnSave").attr("disabled", true);
    url = "/admin/user";
  } else if (save_method == "permissions") {
    $("#btnSave").text("assigning...");
    $("#btnSave").attr("disabled", true);
    url = "/admin/permissions";
  } else if (save_method == "password") {
    $("#btnSave").text("submitting...");
    $("#btnSave").attr("disabled", true);
    url = "/admin/user";
  } else {
    $("#btnSave").text("updating...");
    $("#btnSave").attr("disabled", true);
    url = "/admin/store";
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
      if (!data.inputerror) {
        if (data.status && data.error == null) {
          $("#modal_form").modal("hide");
          Swal.fire("Success!", data.messages, "success");
          reload_table(table);
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
      if (save_method == "add") {
        $("#btnSave").text("Add");
        $("#btnSave").attr("disabled", false);
      } else if (save_method == "import") {
        $("#btnSave").text("Import");
        $("#btnSave").attr("disabled", false);
      } else if (save_method == "permissions") {
        $("#btnSave").text("Assign");
        $("#btnSave").attr("disabled", false);
      } else if (save_method == "password") {
        $("#btnSave").text("Submit");
        $("#btnSave").attr("disabled", false);
      } else {
        $("#btnSave").text("Update");
        $("#btnSave").attr("disabled", false);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      if (save_method == "add") {
        swal(textStatus, errorThrown, "error");
        $("#btnSave").text("Add");
        $("#btnSave").attr("disabled", false);
      } else if (save_method == "import") {
        swal(textStatus, errorThrown, "error");
        $("#btnSave").text("Import");
        $("#btnSave").attr("disabled", false);
      } else if (save_method == "permissions") {
        swal(textStatus, errorThrown, "error");
        $("#btnSave").text("Assing");
        $("#btnSave").attr("disabled", false);
      } else {
        swal(textStatus, errorThrown, "error");
        $("#btnSave").text("Update");
        $("#btnSave").attr("disabled", false);
      }
    },
  });
}

function edit_user(id) {
  save_method = "update";
  $("#importRow").hide();
  $("#permissionsRow").hide();
  $('[name="menu"]').val("update");
  $("#userRow").show();
  $("#passwordRow").hide();
  $("#departmentRow").hide();
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/admin/user/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(id);
      $('[name="staff_id"]').val(data.staff_id);
      $('[name="name"]').val(data.name);
      $('[name="phone"]').val(data.mobile);
      setPhoneNumberWithCountryCode($("#phone"), data.mobile);
      $('[name="email"]').val(data.email);
      $('[name="oldemail"]').val(data.email);
      $('[name="address"]').val(data.address);
      $('[name="branch_id"]').val(data.branch_id);
      $('[name="account_type"]').val(data.account_type).trigger("change");
      $('[name="access_status"]').val(data.access_status).trigger("change");
      $("#modal_form").modal("show");
      $("#btnSave").text("Update");
      $(".modal-title").text("Update " + data.name);
      if (data.photo) {
        $("#upload-label").text("Upload New Photo");
        $("#user-photo-preview").html(
          '<img src="/uploads/users/' +
            data.photo +
            '" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#upload-label").text("Upload Photo");
        $("#user-photo-preview").html(
          '<img src="/assets/dist/img/nophoto.jpg" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      selectBranch(data.branch_id);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      swal(textStatus, errorThrown, "error");
    },
  });
}

function edit_userStatus(id, name) {
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
        url: "/admin/update-userStatus/" + id,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
            reload_table(table);
          } else if (data.error != null) {
            Swal.fire(data.error, data.messages, "error");
          } else {
            Swal.fire(
              "Error",
              "Something unexpected happened, try again later",
              "error"
            );
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    }
  });
}

function user_permissions(id) {
  save_method = "permissions";
  $("#userRow").hide();
  $("#importRow").hide();
  $("#permissionsRow").show();
  $("#departmentRow").hide();
  $("#passwordRow").hide();
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/admin/user/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      load_permissions(data.allowed);
      $('[name="id"]').val(id);
      $("#modal_form").modal("show");
      $("#btnSave").text("Assign");
      $(".modal-title").text("Assign " + data.name + "  permissions");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      swal(textStatus, errorThrown, "error");
    },
  });
}

function view_user(id) {
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/admin/user/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="vname"]').val(data.name);
      $('[name="vphone"]').val(data.mobile);
      $('[name="vemail"]').val(data.email);
      $('[name="oldemail"]').val(data.email);
      $('[name="vaddress"]').val(data.address);
      $('[name="vbranch_name"]').val(data.branch_name);
      $('[name="vaccount_type"]').val(data.account_type);
      $('[name="vaccess_status"]').val(data.access_status);
      if (data.photo && imageExists("/uploads/users/" + data.photo)) {
        $("#photo-preview div").html(
          '<img src="/uploads/users/' +
            data.photo +
            '" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      }
      $("#view_modal_form").modal("show");
      $(".modal-title").text("View " + data.name);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      swal(textStatus, errorThrown, "error");
    },
  });
}

function view_user_photo(id) {
  $.ajax({
    url: "/admin/user/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $("#photo_modal_form").modal("show");
      $(".modal-title").text(data.name);
      $("#photo-preview").show();

      if (data.photo) {
        $("#photo-preview div").html(
          '<img src="/uploads/users/' +
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
      swal(textStatus, errorThrown, "error");
    },
  });
}

function delete_user(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You want to delete " + name,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/user/" + id,
        type: "DELETE",
        dataType: "JSON",
        success: function (data) {
          if (data.status && data.error == null) {
            Swal.fire("Deleted!", name + " " + data.messages, "success");
            reload_table(table);
          } else if (data.error != null) {
            Swal.fire(data.error, data.messages, "error");
          } else {
            Swal.fire(
              "Error",
              "Something unexpected happened, try again later",
              "error"
            );
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          swal(textStatus, errorThrown, "error");
        },
      });
    }
  });
}

function bulk_delete_user() {
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
        text: "You won't be able to revert this " + list_id.length + " users!",
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
            data: { id: list_id },
            url: "/admin/bulk-delete-user",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                swalWithBootstrapButtons.fire(
                  "Selected Users Deleted!",
                  "Selected Users deleted Successfully",
                  "success"
                );
                reload_table(table);
              } else if (data.error != null) {
                Swal.fire(data.error, data.messages, "error");
              } else {
                Swal.fire(
                  "Error",
                  "Something unexpected happened, try again later",
                  "error"
                );
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              swalWithBootstrapButtons.fire(textStatus, errorThrown, "error");
            },
          });
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            "Cancelled",
            "Selected User Records are NOT Deleted :)",
            "error"
          );
        }
      });
  } else {
    Swal.fire(
      "Sorry!",
      "At least select one record to complete this process!",
      "error"
    );
    reload_table(table);
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
        $("select#positionID").html('<option value="">Error Occurred</option>');
      },
    });
  }
});
