var save_method;
var categories_tableId = "categories";
var cash_flows_tableId = "cash_flows";
var account_types_tableId = "account_types";
// dataTables url
var categories_tableDataUrl = "/admin/accounts/generate-list/categories/" + id;
var cash_flows_tableDataUrl =
  "/admin/accounts/generate-list/cash_flow_types/" + id;
var account_types_tableDataUrl =
  "/admin/accounts/generate-list/account_types/" + id;
// Define column configurations for each transaction type
var columnsConfig = {
  categories: [
    // { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "category_name" },
    { data: "part" },
    { data: "statement" },
    // { data: "category_type" },
    { data: "category_status" },
    { data: "action", orderable: false, searchable: false },
  ],
  cash_flows: [
    // { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "name" },
    { data: "code" },
    { data: "description" },
    { data: "status" },
    // { data: "action", orderable: false, searchable: false },
  ],
  account_types: [
    // { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "name" },
    { data: "code" },
    { data: "category_name" },
    { data: "description" },
    { data: "status" },
    // { data: "action", orderable: false, searchable: false },
  ],
};

// dataTables buttons config

// dataTables buttons configs
function createButtonConfig(table, tableId, permissions) {
  var buttonsConfig = [];
  var columns =
    tableId == "account_types" ? [0, 1, 2, 3, 4, 5] : [0, 1, 2, 3, 4];

  // Show reload table button by default
  buttonsConfig.push({
    text: '<i class="fa fa-refresh"></i>',
    className: "btn btn-sm btn-warning",
    titleAttr: "Reload  " + table + " Information",
    action: function () {
      reload_table(tableId);
    },
  });

  // show export buttons
  if (
    permissions.includes("export_" + menu.toLowerCase() + titleSlug) ||
    permissions === '"all"'
  ) {
    buttonsConfig.push(
      {
        extend: "excel",
        className: "btn btn-sm btn-success",
        titleAttr: "Export " + table + " To Excel",
        text: '<i class="fas fa-file-excel"></i>',
        filename: table + " Information",
        extension: ".xlsx",
        exportOptions: {
          columns: columns,
        },
      },
      {
        extend: "pdf",
        className: "btn btn-sm btn-danger",
        titleAttr: "Export " + table + " To PDF",
        text: '<i class="fas fa-file-pdf"></i>',
        filename: table + " Information",
        extension: ".pdf",
        exportOptions: {
          columns: columns,
        },
      },
      {
        extend: "csv",
        className: "btn btn-sm btn-info",
        titleAttr: "Export " + table + " To CSV",
        text: '<i class="fas fa-file-csv"></i>',
        filename: table + " Information",
        extension: ".csv",
        exportOptions: {
          columns: columns,
        },
      },
      {
        extend: "copy",
        className: "btn btn-sm btn-secondary",
        titleAttr: "Copy " + table + " Information",
        text: '<i class="fas fa-copy"></i>',
        exportOptions: {
          columns: columns,
        },
      },
      {
        extend: "print",
        title:
          "<h3 class='text-center text-bold'>" +
          businessName +
          "</h3><h4 class='text-center text-bold'>" +
          table +
          " Information</h4><h5 class='text-center'>Printed On " +
          new Date().getHours() +
          " : " +
          new Date().getMinutes() +
          " " +
          new Date().toDateString() +
          "</h5>",
        exportOptions: {
          columns: columns,
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
          $(win.document.head).find("title").text(table);
        },

        className: "btn btn-sm btn-primary",
        titleAttr: "Print " + table + " Information",
        text: '<i class="fa fa-print"></i>',
        filename: table + " Information",
      }
    );
  }

  return buttonsConfig;
}

var categoriesBtnsConfig = createButtonConfig(
  "Categories",
  categories_tableId,
  userPermissions
);
var cash_flowsBtnsConfig = createButtonConfig(
  "Cash Flows Types",
  cash_flows_tableId,
  userPermissions
);
var account_typesBtnsConfig = createButtonConfig(
  "Account Types",
  account_types_tableId,
  userPermissions
);

$(document).ready(function () {
  // redifine dataTable column length on change of data-bs-toggle tab
  $('button[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
    $($.fn.dataTable.tables(true))
      .DataTable()
      .columns.adjust()
      .responsive.recalc();
  });
  // call to dataTable initialization function for categories
  initializeDataTable(
    categories_tableId,
    categories_tableDataUrl,
    columnsConfig["categories"],
    categoriesBtnsConfig
  );
  // call to dataTable initialization function for cash flow types
  initializeDataTable(
    cash_flows_tableId,
    cash_flows_tableDataUrl,
    columnsConfig["cash_flows"],
    cash_flowsBtnsConfig
  );
  // call to dataTable initialization function for account types
  initializeDataTable(
    account_types_tableId,
    account_types_tableDataUrl,
    columnsConfig["account_types"],
    account_typesBtnsConfig
  );
});

function exportCategoryForm() {
  var cat_id = $('[name="id"]').val();
  window.location.assign("/admin/accounts/categoriesform/" + cat_id);
}
// pop add model
function add_category() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $('[name="id"]').val(0);
  $(".modal-title").text("Add Category"); // Set modal title
  $("#modal_form").modal("show"); // show bootstrap modal
}

// view record
function view_category(id) {
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/accounts/categories/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="category_name"]').val(data.category_name);
      $('[name="category_slug"]').val(data.category_slug);
      $('[name="part"]').val(data.part);
      $('[name="statement"]').val(data.statement);
      $('[name="category_type"]').val(data.category_type);
      $('[name="category_status"]').val(data.category_status);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $(".modal-title").text("View " + data.category_name); // Set modal title
      $("#view_modal").modal("show"); // show bootstrap modal when complete loaded
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// pop edit model
function edit_category(id) {
  save_method = "update";
  $("#export").hide();
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string

  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/accounts/categories/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="category_name"]').val(data.category_name);
      $('[name="category_slug"]').val(data.category_slug);
      $('[name="part"]').val(data.part);
      $('[name="statement"]').val(data.statement);
      $('[name="category_type"]').val(data.category_type);
      $('[name="category_status"]').val(data.category_status);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $("input#part").attr("readonly", true);
      $("input#statement").attr("readonly", true);
      $("input#category_type").attr("readonly", true);
      $("input#category_status").attr("readonly", true);
      $(".modal-title").text("Update " + data.category_name); // Set modal title
      $("#modal_form").modal("show"); // show bootstrap modal when complete loaded
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// save
function save_category() {
  catID = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSav").text("Submitting..."); //change button text
  $("#btnSav").attr("disabled", true); //set button disable
  var url, method;
  if (save_method == "add") {
    url = "/admin/accounts/categories";
  } else {
    url = "/admin/accounts/edit-chartofaccount/" + catID;
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
          reload_table(categories_tableId);
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

// delete record
function delete_category(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to " + name + " category!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/accounts/categories/" + id,
        type: "DELETE",
        dataType: "JSON",
        success: function (data) {
          //if success reload ajax table
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
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

// bulk delete
function bulk_deleteCategory() {
  var list_id = [];
  $(".data-checkCategories:checked").each(function () {
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
          "Your will not be able to recover these " +
          list_id.length +
          " category(ies) once deleted!",
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
            url: "/admin/accounts/categoriesBulk-delete",
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
    Swal.fire("Sorry!", "No category selected....", "error");
  }
}
