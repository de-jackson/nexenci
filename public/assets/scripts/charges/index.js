var save_method;
var tableId = "charges";
// dataTables url
var tableDataUrl = "/admin/settings/get-charges-info/" + id + "/" + pageMenu;
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "particular_name" },
  { data: "charge_method" },
  { data: "charge" },
  { data: "charge_mode" },
  { data: "frequency" },
  { data: "effective_date" },
  { data: "status" },
  { data: "action", orderable: false, searchable: false },
];
// dataTables buttons config
var buttonsConfig = [];
// show create button
if (userPermissions.includes("create_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
  if (pageMenu === "particular") {
    buttonsConfig.push({
      text: '<i class="fas fa-plus"></i>',
      className: "btn btn-sm btn-secondary create" + title,
      attr: {
        id: "create" + title,
      },
      titleAttr: "Add " + title,
      action: function () {
        addCharge();
      },
    });
  }
}
// show upload button
if (userPermissions.includes("import_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
  if (pageMenu === "particular") {
    buttonsConfig.push({
      text: '<i class="fas fa-upload"></i>',
      className: "btn btn-sm btn-info import" + title,
      attr: {
        id: "import" + title,
      },
      titleAttr: "Import " + title + "(s/es)",
      action: function () {
        importCharges();
      },
    });
  }
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
      bulkDeleteCharges();
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
  generateChargeOptions();
});

function exportBranchForm() {
  var branch_id = $('[name="id"]').val();
  window.location.assign("/admin/branches/print-branchForm/" + branch_id);
}

function addCharge() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#formRow").show();
  $("#importRow").hide();
  $(".modal-title").text("Add New Charge");
  $('[name="id"]').val(0);
  $('[name="mode"]').val("create");
  $("textarea#summernote").summernote("reset");
  $("select#charge_frequency").trigger("change");
  $("select#charge_method").trigger("change");
  $("select#charge_mode").trigger("change");
  $("select#charge_status").trigger("change");
  $("#importRow").hide();
  $("#formRow").show();
  $("#modal_form").modal("show"); // show bootstrap modal
}
function importCharges() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#formRow").hide();
  $("#export").hide();
  $("#importRow").show();
  $('[name="id"]').val(0);
  $('[name="mode"]').val("import");
  $(".modal-title").text("Import Charges");
  $("#modal_form").modal("show"); // show bootstrap modal
}

function saveCharge() {
  charge_id = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSubmit").text("submitting..."); //change button text
  $("#btnSubmit").attr("disabled", true); //set button disable
  var url, method;
  if (save_method == "add") {
    url = "/admin/settings/charges";
    method = "POST";
  } else {
    // url = "/admin/settings/charges/" + charge_id;
    url = "/admin/settings/charges";
    method = "POST";
  }

  var formData = new FormData($("#form")[0]);
  $.ajax({
    url: url,
    type: method,
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
      $("#btnSubmit").text("Submit"); //change button text
      $("#btnSubmit").attr("disabled", false); //set button enable
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnSubmit").text("Submit");
      $("#btnSubmit").attr("disabled", false);
    },
  });
}

function updateCharge(id) {
  save_method = "update";
  $("#export").hide();
  $("#form")[0].reset(); // reset form on modals
  $("#importRow").hide();
  $("#export").hide();
  $("#formRow").show();
  $('[name="mode"]').val("update");
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/settings/charges/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="charge_method"]').val(data.charge_method).trigger("change");
      $('[name="charge_mode"]').val(data.charge_mode).trigger("change");
      $('[name="charge_frequency"]').val(data.frequency).trigger("change");
      $('[name="charge"]').val(data.charge);
      $('[name="charge_limits"]').val(data.charge_limits);
      $('[name="effective_date"]').val(data.effective_date);
      $('[name="cutoff_date"]').val(data.cutoff_date);
      $('[name="charge_status"]').val(data.status).trigger("change");
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $(".modal-title").text("Update " + data.effective_date + " charges"); // Set modal title
      $("#modal_form").modal("show"); // show bootstrap modal when complete loaded
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

function viewCharge(id) {
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/settings/charges/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="vcharge_method"]').val(data.charge_method);
      $('[name="vcharge_mode"]').val(data.charge_mode);
      $('[name="vcharge_frequency"]').val(data.frequency);
      $('[name="vcharge"]').val(data.charge);
      $('[name="vcharge_limits"]').val(data.charge_limits);
      $('[name="veffective_date"]').val(data.effective_date);
      $('[name="vcutoff_date"]').val(data.cutoff_date);
      $('[name="vcharge_status"]').val(data.status);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $(".modal-title").text("View " + data.charge_method);
      $("#view_modal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

function deleteCharge(id, chargeMethod) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to recover charge " + chargeMethod + "!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/settings/charges/" + id,
        type: "DELETE",
        dataType: "JSON",
        success: function (data) {
          if (data.status && data.error == null) {
            Swal.fire(
              "Success!",
              chargeMethod + " " + data.messages,
              "success"
            );
            reload_table(tableId);
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

function bulkDeleteCharges() {
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
          "You will not be able to recover selected " +
          list_id.length +
          " branch(es) once deleted!",
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
            url: "/admin/settings/bulk-delete-charges",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Success!", data.messages, "success");
                reload_table(tableId);
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
    Swal.fire("Sorry!", "No branch selected :)", "error");
  }
}
