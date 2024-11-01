var filter = $('[name="filter"]').val();
var from = $('[name="from"]').val();
var to = $('[name="to"]').val();
if (filter == "") {
  filter = null;
}
if (from == "") {
  from = 0;
}
if (to == "") {
  to = 0;
}
var tableId = "staffReport";
// dataTables url
var tableDataUrl =
  "/admin/staff/staffs-report/" +
  filter +
  "/" +
  selected +
  "/" +
  from +
  "/" +
  to;
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "staff_name" },
  { data: "staffID" },
  { data: "branch_name" },
  { data: "position" },
  { data: "mobile" },
  { data: "email" },
  { data: "photo", orderable: false, searchable: false },
  { data: "id" },
  { data: "action", orderable: false, searchable: false },
];
// dataTables buttons config
var buttonsConfig = [];
// show reload table button by default
buttonsConfig.push({
  text: '<i class="fa fa-refresh"></i>',
  className: "btn btn-sm btn-warning",
  titleAttr: "Reload Staff " + title + " Table",
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
      filename: "Staff " + title + " Information",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export Staff " + title + " To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: "Staff " + title + " Information",
      extension: ".pdf",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export Staff " + title + " To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: "Staff " + title + " Information",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-warning",
      titleAttr: "Copy Staff " + title + " Information",
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
        "</h3><h4 class='text-center text-bold'>Staff " +
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
      titleAttr: "Print Staff " + title + " Information",
      text: '<i class="fa fa-print"></i>',
      filename: "Staff " + title + " Information",
    }
  );
}

$(document).ready(function () {
  selected_value(selected);
  // call to dataTable initialization function
  initializeDataTable(tableId, tableDataUrl, columnsConfig, buttonsConfig);
});

function exportStaffForm() {
  var staff_id = $('[name="id"]').val();
  window.location.assign("/admin/staff/form/" + staff_id);
}

// view record
function view_staff(id) {
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/staff/employee/" + id,
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
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      if (data.account_type == "Employee") {
        if (
          data.photo &&
          imageExists("/uploads/staffs/employees/passports/" + data.photo)
        ) {
          $("#photo-preview div").html(
            '<img src="/uploads/staffs/employees/passports/' +
              data.photo +
              '" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
          );
        } else {
          $("#photo-preview div").html(
            '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
          );
        }
        if (
          data.signature &&
          imageExists("/uploads/staffs/employees/signatures/" + data.signature)
        ) {
          $("#signature-preview div").html(
            '<img src="/uploads/staffs/employees/signatures/' +
              data.signature +
              '" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
          );
        } else {
          $("#signature-preview div").html(
            '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
          );
        }
      } else {
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
      }
      $("#view_modal").modal("show"); // show bootstrap modal when complete loaded
      $(".modal-title").text("View " + data.staff_name); // Set title to Bootstrap modal title
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// view Staff photo
function view_staff_photo(id) {
  $.ajax({
    url: "/admin/staff/employee/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $("#photo_modal_form").modal("show");
      $(".modal-title").text(data.name);
      $("#photo-preview").show(300);
      if (data.account_type == "Employee") {
        if (data.photo) {
          $("#photo-preview div").html(
            '<img src="/uploads/staffs/employees/passports/' +
              data.photo +
              '" class="img-fluid thumbnail">'
          );
        } else {
          $("#photo-preview div").html(
            '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail">'
          );
        }
      } else {
        if (data.photo) {
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
      }
    },

    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

$("select#filter").on("change", function () {
  var filter = $("select#filter").val();
  // dynamically add options
  switch (filter.toLowerCase()) {
    case "role":
      $.ajax({
        url: "/admin/reports/filter-options/" + report + "/" + filter,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          $("select#selectOpt").html('<option value="">select role</option>');
          if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
              $("<option />")
                .val(data[i])
                .text(data[i])
                .appendTo($("select#selectOpt"));
            }
          } else {
            $("select#selectOpt").html('<option value="">No Role</option>');
          }
        },
        error: function (err) {
          $("select#selectOpt").html('<option value="">Error Occured</option>');
        },
      });
      break;
    case "department":
      $.ajax({
        url: "/admin/departments/all-departments",
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          $("select#selectOpt").html(
            '<option value="">select department</option>'
          );
          if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
              $("<option />")
                .val(data[i].id)
                .text(data[i].department_name)
                .appendTo($("select#selectOpt"));
            }
          } else {
            $("select#selectOpt").html(
              '<option value="">No Department</option>'
            );
          }
        },
        error: function (err) {
          $("select#selectOpt").html('<option value="">Error Occured</option>');
        },
      });
      break;
    case "position":
      $.ajax({
        url: "/admin/positions/all-positions",
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          $("select#selectOpt").html(
            '<option value="">select position</option>'
          );
          if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
              $("<option />")
                .val(data[i].id)
                .text(data[i].position)
                .appendTo($("select#selectOpt"));
            }
          } else {
            $("select#selectOpt").html(
              '<option value="">No Department</option>'
            );
          }
        },
        error: function (err) {
          $("select#selectOpt").html('<option value="">Error Occured</option>');
        },
      });
      break;
    default:
      $("select#selectOpt").html('<option value="">choose filter</option>');
      break;
  }
});

function selected_value(selected) {
  var filter = $("select#filter").val();
  // dynamically add options
  switch (filter.toLowerCase()) {
    case "role":
      $.ajax({
        url: "/admin/reports/account-types",
        type: "POST",
        dataType: "JSON",
        success: function (response) {
          if (response.length > 0) {
            $("select#selectOpt").find("option").not(":first").remove();
            // Add options
            $.each(response, function (index, data) {
              if (data == selected) {
                var selection = "selected";
              } else {
                var selection = "";
              }
              $("select#selectOpt").append(
                '<option value="' +
                  data +
                  '" ' +
                  selection +
                  ">" +
                  data +
                  "</option>"
              );
            });
          } else {
            $("select#selectOpt").html(
              '<option value="">No Department</option>'
            );
          }
        },
      });
      break;
    case "department":
      $.ajax({
        url: "/admin/departments/all-departments",
        type: "POST",
        dataType: "JSON",
        success: function (response) {
          if (response.length > 0) {
            $("select#selectOpt").find("option").not(":first").remove();
            // Add options
            $.each(response, function (index, data) {
              if (data["id"] == selected) {
                var selection = "selected";
              } else {
                var selection = "";
              }
              $("select#selectOpt").append(
                '<option value="' +
                  data["id"] +
                  '" ' +
                  selection +
                  ">" +
                  data["department_name"] +
                  "</option>"
              );
            });
          } else {
            $("select#selectOpt").html(
              '<option value="">No Department</option>'
            );
          }
        },
      });
      break;
    case "position":
      $.ajax({
        url: "/admin/positions/all-positions",
        type: "POST",
        dataType: "JSON",
        success: function (response) {
          if (response.length > 0) {
            $("select#selectOpt").find("option").not(":first").remove();
            // Add options
            $.each(response, function (index, data) {
              if (data["id"] == selected) {
                var selection = "selected";
              } else {
                var selection = "";
              }
              $("select#selectOpt").append(
                '<option value="' +
                  data["id"] +
                  '" ' +
                  selection +
                  ">" +
                  data["position"] +
                  "</option>"
              );
            });
          } else {
            $("select#selectOpt").html(
              '<option value="">No Department</option>'
            );
          }
        },
      });
      break;
    default:
      $("select#selectOpt").html('<option value="">choose filter</option>');
      break;
  }
}
