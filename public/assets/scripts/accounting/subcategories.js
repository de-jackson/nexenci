var save_method;
// table IDs
var assetsTableId = "assetsSubcategories";
var equityTableId = "equitySubcategories";
var liabilitiesTableId = "liabilitySubcategories";
var revenueTableId = "revenueSubcategories";
var expensesTableId = "expensesSubcategories";
// dataTables urls
var assetsTableDataUrl = "/admin/accounts/generate-subcategories/1/" + id;
var equityTableDataUrl = "/admin/accounts/generate-subcategories/2/" + id;
var liabilitiesTableDataUrl = "/admin/accounts/generate-subcategories/3/" + id;
var revenueTableDataUrl = "/admin/accounts/generate-subcategories/4/" + id;
var expensesTableDataUrl = "/admin/accounts/generate-subcategories/5/" + id;
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "subcategory_name" },
  { data: "category_name" },
  { data: "part" },
  { data: "statement" },
  { data: "subcategory_type" },
  { data: "subcategory_code" },
  // { data: "id" },
  { data: "action", orderable: false, searchable: false },
];
// dataTables buttons configs
function createButtonConfig(category, categoryId, tableId, permissions) {
  var buttonsConfig = [];

  // Show create button
  if (permissions.includes("create_" + menu.toLowerCase() + titleSlug) || permissions === '"all"') {
    buttonsConfig.push({
      text: '<i class="fas fa-plus"></i>',
      className: "btn btn-sm btn-secondary create" + title,
      attr: {
        id: "create" + title,
      },
      titleAttr: "Add " + title,
      action: function () {
        add_subcategory(categoryId);
      },
    });
  }
  // show upload button
  if (permissions.includes("import_" + menu.toLowerCase() + titleSlug) || permissions === '"all"') {
    buttonsConfig.push({
      text: '<i class="fas fa-upload"></i>',
      className: "btn btn-sm btn-info import" + title,
      attr: {
        id: "import" + title,
      },
      titleAttr: "Import " + title + "s",
      action: function () {
        import_subcategories();
      },
    });
  }
  // Show bulk-delete
  if (permissions.includes("bulkDelete_" + menu.toLowerCase() + titleSlug) || permissions === '"all"') {
    buttonsConfig.push({
      text: '<i class="fa fa-trash"></i>',
      className: "btn btn-sm btn-danger delete" + title,
      attr: {
        id: "delete" + title,
      },
      titleAttr: "Bulky Delete " + title,
      action: function () {
        bulk_deleteSubCategories(categoryId);
      },
    });
  }

  // Show reload table button by default
  buttonsConfig.push({
    text: '<i class="fa fa-refresh"></i>',
    className: "btn btn-sm btn-warning",
    titleAttr: "Reload " + " " + category + " " + title + " Information",
    action: function () {
      reload_table(tableId);
    },
  });

  // Show export buttons
  if (permissions.includes("export_" + menu.toLowerCase() + titleSlug) || permissions === '"all"') {
    buttonsConfig.push(
      {
        extend: "excel",
        className: "btn btn-sm btn-success",
        titleAttr: "Export " + category + " " + title + " To Excel",
        text: '<i class="fas fa-file-excel"></i>',
        filename: category + " " + title + " Information",
        extension: ".xlsx",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7],
        },
      },
      {
        extend: "pdf",
        className: "btn btn-sm btn-danger",
        titleAttr: "Export " + category + " " + title + " To PDF",
        text: '<i class="fas fa-file-pdf"></i>',
        filename: category + " " + title + " Information",
        extension: ".pdf",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7],
        },
      },
      {
        extend: "csv",
        className: "btn btn-sm btn-info",
        titleAttr: "Export " + category + " " + title + " Information To CSV",
        text: '<i class="fas fa-file-csv"></i>',
        filename: category + " " + title + " Information",
        extension: ".csv",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7],
        },
      },
      {
        extend: "copy",
        className: "btn btn-sm btn-secondary",
        titleAttr: "Copy " + category + " " + title + " Information",
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
          "</h3><h4 class='text-center text-bold'> " +
          category +
          " " +
          title +
          "  Information</h4><h5 class='text-center'>Printed On " +
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
        filename: category + " " + title + " Information",
      }
    );
  }

  return buttonsConfig;
}

var assetsBtnsConfig = createButtonConfig(
  "Assets", "1",
  assetsTableId,
  userPermissions
);
var equityBtnsConfig = createButtonConfig(
  "Equity", "2",
  equityTableId,
  userPermissions
);
var liabilitiesBtnsConfig = createButtonConfig(
  "Liabilities", "3",
  liabilitiesTableId,
  userPermissions
);
var revenueBtnsConfig = createButtonConfig(
  "Revenue", "4",
  revenueTableId,
  userPermissions
);
var expensesBtnsConfig = createButtonConfig(
  "Expenses", "5",
  expensesTableId,
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
  // assets subcategories
  initializeDataTable(
    assetsTableId,
    assetsTableDataUrl,
    columnsConfig,
    assetsBtnsConfig
  );
  // equity subcategories
  initializeDataTable(
    equityTableId,
    equityTableDataUrl,
    columnsConfig,
    equityBtnsConfig
  );
  // liabilities  subcategories
  initializeDataTable(
    liabilitiesTableId,
    liabilitiesTableDataUrl,
    columnsConfig,
    liabilitiesBtnsConfig
  );
  // revenue subcategories
  initializeDataTable(
    revenueTableId,
    revenueTableDataUrl,
    columnsConfig,
    revenueBtnsConfig
  );
  // expenses subcategories
  initializeDataTable(
    expensesTableId,
    expensesTableDataUrl,
    columnsConfig,
    expensesBtnsConfig
  );

  // select categories from db
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/admin/accounts/all-categories",
    success: function (data) {
      $("select#category_id").html('<option value="">-- select --</option>');
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          $("<option />")
            .val(data[i].id)
            .text(data[i].category_name)
            .appendTo($("select#category_id"));
        }
      } else {
        $("select#category_id").html('<option value="">No Category</option>');
      }
    },
    error: function (err) {
      $("select#category_id").html('<option value="">Error Occured</option>');
    },
  });

  // show subcategories counter badges
  count_subcategories();

  //check all table inputs
  $("#check-all1").click(function () {
    $(".data-check1").prop("checked", $(this).prop("checked"));
  });
  $("#check-all2").click(function () {
    $(".data-check2").prop("checked", $(this).prop("checked"));
  });
  $("#check-all3").click(function () {
    $(".data-check3").prop("checked", $(this).prop("checked"));
  });
  $("#check-all4").click(function () {
    $(".data-check4").prop("checked", $(this).prop("checked"));
  });
  $("#check-all5").click(function () {
    $(".data-check5").prop("checked", $(this).prop("checked"));
  });
});

function count_subcategories() {
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/counter/subcategories",
    success: function (data) {
      $("span#assets-subcategories").text(
        parseInt(data.assets) > 0 ? data.assets : 0
      );
      $("span#equity-subcategories").text(
        parseInt(data.equity) > 0 ? data.equity : 0
      );
      $("span#liabilities-subcategories").text(
        parseInt(data.liabilities) > 0 ? data.liabilities : 0
      );
      $("span#revenue-subcategories").text(
        parseInt(data.revenue) > 0 ? data.revenue : 0
      );
      $("span#expenses-subcategories").text(
        parseInt(data.expenses) > 0 ? data.expenses : 0
      );
    },
    error: function (err) {
      console.log('Error Occured</option>');
    },
  });
}

function exportSucategoryForm() {
  var subcat_id = $('[name="id"]').val();
  window.location.assign("/admin/accounts/subcategoryform/" + subcat_id);
}
// pop add model
function add_subcategory(categoryId) {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $('[name="mode"]').val("create");
  $('[name="subcategory_type"]').val("Custom");
  $('[name="id"]').val(0);
  $('[name="category_id"]').val(categoryId);
  $('[name="category_id"]').trigger("change");
  $('[name="subcategory_status"]').trigger("change");
  $("div#category_id").show();
  $("div#formRow").show();
  $("div#importRow").hide();
  $(".modal-title").text("Add Subcategory"); // Set modal title
  $("#modal_form").modal("show"); // show bootstrap modal
}

function import_subcategories() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#importRow").show();
  $("#formRow").hide();
  $("#export").hide();
  $('[name="id"]').val(0);
  $('[name="mode"]').val("import");
  $('[name="subcategory_type"]').val("Custom");
  $(".modal-title").text("Import Subcategory(ies)");
  $("#modal_form").modal("show"); // show bootstrap modal
}
// view record
function view_subcategory(id) {
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/accounts/subcategory/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="subcategory_name"]').val(data.subcategory_name);
      $('[name="subcategory_code"]').val(data.subcategory_code);
      $('[name="category_id"]').val(data.category_name);
      $('[name="subcategory_slug"]').val(data.subcategory_slug);
      $('[name="part"]').val(data.part);
      $('[name="statement"]').val(data.statement);
      $('[name="subcategory_type"]').val(data.subcategory_type);
      $('[name="subcategory_status"]').val(data.subcategory_status);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $(".modal-title").text("View " + data.subcategory_name);
      $("#view_modal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// pop edit model
function edit_subcategory(id) {
  save_method = "update";
  $("#export").hide();
  $("div#formRow").show();
  $("div#importRow").hide();
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string

  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/accounts/subcategory/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.subcategory_type == "System") {
        $("div#category_id").hide();
      }
      $('[name="id"]').val(data.id);
      $('[name="subcategory_name"]').val(data.subcategory_name);
      $('[name="subcategory_code"]').val(data.subcategory_code);
      $('[name="subcategory_slug"]').val(data.subcategory_slug);
      $('[name="category_id"]').val(data.category_id).trigger("change");
      $('[name="subcategory_type"]').val(data.subcategory_type);
      $('[name="subcategory_status"]')
        .val(data.subcategory_status)
        .trigger("change");
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);

      $(".modal-title").text("Update " + data.subcategory_name); // Set modal title
      $("#modal_form").modal("show"); // show bootstrap modal when complete loaded
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// save
function save_subcategory() {
  sID = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSav").text("Submitting...");
  $("#btnSav").attr("disabled", true);
  var url, method;
  if (save_method == "add") {
    url = "/admin/accounts/subcategory";
  } else {
    url = "/admin/accounts/edit-subcategory/" + sID;
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
          reload_table("assetsSubcategories");
          reload_table("equitySubcategories");
          reload_table("liabilitySubcategories");
          reload_table("revenueSubcategories");
          reload_table("expensesSubcategories");
          count_subcategories();
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
      $("#btnSav").text("Submit");
      $("#btnSav").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnSav").text("Submit"); //change button text
      $("#btnSav").attr("disabled", false);
    },
  });
}

// delete record
function delete_subcategory(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to " + name + " subcategory!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/accounts/subcategory/" + id,
        type: "DELETE",
        dataType: "JSON",
        success: function (data) {
          //if success reload ajax table
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
            reload_table("assetsSubcategories");
            reload_table("equitySubcategories");
            reload_table("liabilitySubcategories");
            reload_table("revenueSubcategories");
            reload_table("expensesSubcategories");
            count_subcategories();
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
function bulk_deleteSubCategories(cat_id) {
  var list_id = [];
  $(".data-check"+ cat_id +":checked").each(function () {
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
          " subcategory(ies) once deleted!",
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
            url: "/admin/accounts/subCategoryBulk-delete",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Success!", data.messages, "success");
                reload_table("assetsSubcategories");
                reload_table("equitySubcategories");
                reload_table("liabilitySubcategories");
                reload_table("revenueSubcategories");
                reload_table("expensesSubcategories");
                count_subcategories();
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
    Swal.fire("Sorry!", "No subcotegory selected....", "error");
  }
}
