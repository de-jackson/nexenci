var save_method;
// table IDs
var assetsTableId = "assetsParticulars";
var equityTableId = "equityParticulars";
var liabilitiesTableId = "liabilityParticulars";
var revenueTableId = "revenueParticulars";
var expensesTableId = "expensesParticulars";
// dataTables urls
var assetsTableDataUrl = "/admin/accounts/generate-particulars/1/" + id;
var equityTableDataUrl = "/admin/accounts/generate-particulars/2/" + id;
var liabilitiesTableDataUrl = "/admin/accounts/generate-particulars/3/" + id;
var revenueTableDataUrl = "/admin/accounts/generate-particulars/4/" + id;
var expensesTableDataUrl = "/admin/accounts/generate-particulars/5/" + id;
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "particular_name" },
  { data: "type" },
  { data: "subcategory_name" },
  { data: "cash_flow" },
  { data: "particular_code" },
  { data: "debit", render: $.fn.DataTable.render.number(",") },
  { data: "credit", render: $.fn.DataTable.render.number(",") },
  { data: "balance" },
  { data: "action", orderable: false, searchable: false },
];
// dataTables buttons configs
function createButtonConfig(category, categoryId, tableId, permissions) {
  var buttonsConfig = [];

  // Show create button
  if (
    permissions.includes("create_" + menu.toLowerCase() + titleSlug) ||
    permissions === '"all"'
  ) {
    buttonsConfig.push({
      text: '<i class="fas fa-plus"></i>',
      className: "btn btn-sm btn-secondary create" + title,
      attr: {
        id: "create" + title,
      },
      titleAttr: "Add " + category + " " + title,
      action: function () {
        add_particular(categoryId, category);
      },
    });
  }

  // show upload button
  if (
    permissions.includes("import_" + menu.toLowerCase() + titleSlug) ||
    permissions === '"all"'
  ) {
    buttonsConfig.push({
      text: '<i class="fas fa-upload"></i>',
      className: "btn btn-sm btn-info import" + title,
      attr: {
        id: "import" + title,
      },
      titleAttr: "Import " + title + "s",
      action: function () {
        import_particulars();
      },
    });
  }
  // Show bulk-delete
  if (
    permissions.includes("bulkDelete_" + menu.toLowerCase() + titleSlug) ||
    permissions === '"all"'
  ) {
    buttonsConfig.push({
      text: '<i class="fa fa-trash"></i>',
      className: "btn btn-sm btn-danger delete" + title,
      attr: {
        id: "delete" + title,
      },
      titleAttr: "Bulky Delete " + category + " " + title,
      action: function () {
        bulk_deleteParticular(categoryId);
      },
    });
  }

  // Show reload table button by default
  buttonsConfig.push({
    text: '<i class="fa fa-refresh"></i>',
    className: "btn btn-sm btn-warning",
    titleAttr: "Reload  " + category + " " + title + " Information",
    action: function () {
      reload_table(tableId);
    },
  });

  // Show export buttons
  if (
    permissions.includes("export_" + menu.toLowerCase() + titleSlug) ||
    permissions === '"all"'
  ) {
    buttonsConfig.push(
      {
        extend: "excel",
        className: "btn btn-sm btn-success",
        titleAttr: "Export " + category + " " + title + " To Excel",
        text: '<i class="fas fa-file-excel"></i>',
        filename: category + " " + title + " Information",
        extension: ".xlsx",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
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
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
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
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
        },
      },
      {
        extend: "copy",
        className: "btn btn-sm btn-secondary",
        titleAttr: "Copy " + category + " " + title + " Information",
        text: '<i class="fas fa-copy"></i>',
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
        },
      },
      {
        extend: "print",
        // <h3 class='text-center text-bold'>" + businessName + " " + systemName + "</h3>
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
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
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
  "Assets",
  1,
  assetsTableId,
  userPermissions
);
var equityBtnsConfig = createButtonConfig(
  "Equity",
  2,
  equityTableId,
  userPermissions
);
var liabilitiesBtnsConfig = createButtonConfig(
  "Liabilities",
  3,
  liabilitiesTableId,
  userPermissions
);
var revenueBtnsConfig = createButtonConfig(
  "Revenue",
  4,
  revenueTableId,
  userPermissions
);
var expensesBtnsConfig = createButtonConfig(
  "Expenses",
  5,
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
  // assets particulars
  initializeDataTable(
    assetsTableId,
    assetsTableDataUrl,
    columnsConfig,
    assetsBtnsConfig
  );
  // equity particulars
  initializeDataTable(
    equityTableId,
    equityTableDataUrl,
    columnsConfig,
    equityBtnsConfig
  );
  // liabilities  particulars
  initializeDataTable(
    liabilitiesTableId,
    liabilitiesTableDataUrl,
    columnsConfig,
    liabilitiesBtnsConfig
  );
  // revenue particulars
  initializeDataTable(
    revenueTableId,
    revenueTableDataUrl,
    columnsConfig,
    revenueBtnsConfig
  );
  // expenses particulars
  initializeDataTable(
    expensesTableId,
    expensesTableDataUrl,
    columnsConfig,
    expensesBtnsConfig
  );

  // show particular counter badges
  count_particulars();

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

  addNewChargeRows();

  // Function to remove a charge row
  // $('#chargesRow').on('click', 'button[id^="remove"]', function() {
  //     $(this).closest('.row').remove();
  //     chargeCounter = $('#chargesRow').children().length;
  //     $('#chargeCounterValue').val(chargeCounter);
  // });

  // Function to handle modal close event
  $("#modal_form").on("hidden.bs.modal", function () {
    removeChargeRows();
  });
});

function count_particulars() {
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/counter/particulars",
    success: function (data) {
      $("span#assets-particulars").text(
        parseInt(data.assets) > 0 ? data.assets : 0
      );
      $("span#equity-particulars").text(
        parseInt(data.equity) > 0 ? data.equity : 0
      );
      $("span#liabilities-particulars").text(
        parseInt(data.liabilities) > 0 ? data.liabilities : 0
      );
      $("span#revenue-particulars").text(
        parseInt(data.revenue) > 0 ? data.revenue : 0
      );
      $("span#expenses-particulars").text(
        parseInt(data.expenses) > 0 ? data.expenses : 0
      );
    },
    error: function (err) {
      console.log("Error Occured</option>");
    },
  });
}

function exportParticularForm() {
  var particular_id = $('[name="id"]').val();
  window.location.assign("/admin/accounts/particularform/" + particular_id);
}
//
function isChecked() {
  if ($("#charged").is(":checked")) {
    $("div#setChargeRow").show(300);
  } else {
    $("div#setChargeRow").hide(300);
    removeChargeRows();
  }
}
// pop add model
function add_particular(category_id, category) {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#formRow").show();
  $("#importRow").hide();
  $('[name="id"]').val(0);
  $('[name="category_id"]').val(category_id);
  $('[name="mode"]').val("create");
  $('[name="particular_type"]').val("Custom");
  $('[name="subcat_id"]').trigger("change");
  $('[name="subcategory_id"]').trigger("change");
  $('[name="account_typeId"]').trigger("change");
  $('[name="cash_flow_typeId"]').trigger("change");
  $('[name="particular_status"]').trigger("change");
  $('[name="charge_method"]').trigger("change");
  $('[name="charge_mode"]').trigger("change");
  subcategories(category_id);
  account_types(category_id);
  cash_flow_types();
  generate_chargeOptions();
  $("div#setChargeRow").hide();
  $("div#subcategory_id").show();
  $(".modal-title").text("Add " + category.toUpperCase() + " Particular"); // Set modal title
  $("#modal_form").modal("show"); // show bootstrap modal
}
function import_particulars() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#formRow").hide();
  $("#export").hide();
  $("#importRow").show();
  $('[name="id"]').val(0);
  $('[name="mode"]').val("import");
  $('[name="particular_type"]').val("Custom");
  $(".modal-title").text("Import Particular(s)");
  $("#modal_form").modal("show"); // show bootstrap modal
}

// view record
function view_particular(id) {
  //Ajax Load data from ajax
  $("div#charged").hide(300);
  $.ajax({
    url: "/admin/accounts/particular/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      var balance, bal;
      if (data.charged.toLowerCase() == "yes") {
        $("div#charged").show(300);
        $("input#charged").attr("checked", true);
        if (data.particular_charges) {
          $.each(data.particular_charges, function (index, column) {
            var charge_frequency = column["frequency"];
            var charge_method = column["charge_method"];
            var charge_mode = column["charge_mode"];
            var charge = column["charge"];
            var limit = column["charge_limits"];
            var effective_date = column["effective_date"];
            var cutoff_date = column["cutoff_date"];
            var charge_status = column["status"];
            var chargeCounter = index;
            // charge row inputs
            var divOption = $(
              '<hr><div class="row gx-3 gy-2 align-items-center mt-0">' +
                // frequency
                '<div class="col-md-4">' +
                '<div class="form-group">' +
                '<label class="control-label fw-bold col-md-12">Frequency</label>' +
                '<div class="col-md-12">' +
                '<input type="text" class="form-control" value="' +
                charge_frequency +
                '" placeholder="Charge Frequency" readonly>' +
                "</div>" +
                "</div>" +
                "</div>" +
                // method
                '<div class="col-md-4">' +
                '<div class="form-group">' +
                '<label class="control-label fw-bold col-md-12">Charge Method</label>' +
                '<div class="col-md-12">' +
                '<input type="text" class="form-control" value="' +
                charge_method +
                '" placeholder="Charge Method" readonly>' +
                "</div>" +
                "</div>" +
                "</div>" +
                // mode
                '<div class="col-md-4">' +
                '<div class="form-group">' +
                '<label class="control-label fw-bold col-md-12">Charge Mode</label>' +
                '<div class="col-md-12">' +
                '<input type="text" class="form-control" value="' +
                charge_mode +
                '" placeholder="Charge Mode" readonly>' +
                "</div>" +
                "</div>" +
                "</div>" +
                // charge
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label class="control-label fw-bold col-md-12">Amount/Rate</label>' +
                '<div class="col-md-12">' +
                '<input name="charge[' +
                chargeCounter +
                ']" placeholder="Charge Fee" class="form-control" type="number" min="" max="" value="' +
                charge.toLocaleString() +
                '" readonly>' +
                "</div>" +
                "</div>" +
                "</div>" +
                // charge limit
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label class="control-label fw-bold col-md-12">Charge Limit(Amount)</label>' +
                '<div class="col-md-12">' +
                '<input name="charge_limits[' +
                chargeCounter +
                ']" placeholder="Charge Limit(Amount)" class="form-control" type="number" min="" max="" value="' +
                limit +
                '" readonly>' +
                "</div>" +
                "</div>" +
                "</div>" +
                //
                '<div class="col-md-4" style="display: none;">' +
                '<div class="form-group">' +
                '<label class="control-label fw-bold col-md-12">Grace Period(days)</label>' +
                '<div class="col-md-12">' +
                ' <input name="grace_period" placeholder="Grace Period(days)" class="form-control" type="text" readonly>' +
                '<span class="help-block text-danger"></span>' +
                "</div>" +
                "</div>" +
                "</div>" +
                // effective date
                '<div class="col-md-4">' +
                '<div class="form-group">' +
                '<label for="effective_date" class="control-label fw-bold col-sm-12">Effective Date</label>' +
                '<div class="col-sm-12">' +
                '<div class="input-group">' +
                '<div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>' +
                '<input type="text" name="effective_date[' +
                chargeCounter +
                ']" id="effective_date" class="form-control" value="' +
                effective_date +
                '" placeholder="Effective Date" readonly>' +
                "</div>" +
                '<span class="help-block text-danger"></span>' +
                "</div>" +
                "</div>" +
                "</div>" +
                // cutoff date
                '<div class="col-md-4">' +
                '<div class="form-group">' +
                '<label for="cutoff_date[' +
                chargeCounter +
                ']" class="control-label fw-bold col-sm-12">Cutoff Date</label>' +
                '<div class="col-sm-12">' +
                '<div class="input-group">' +
                '<div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>' +
                '<input type="text" name="cutoff_date[' +
                chargeCounter +
                ']" id="cutoff_date[' +
                chargeCounter +
                ']" class="form-control" value="' +
                cutoff_date +
                '" placeholder="Cutoff Date" readonly>' +
                "</div>" +
                '<i><small class="fw-semibold">Date for the charge reduction.</small></i' +
                '<span class="help-block text-danger"></span>' +
                "</div>" +
                "</div>" +
                "</div>" +
                // status
                '<div class="col-md-4">' +
                '<div class="form-group">' +
                '<label class="control-label fw-bold col-md-12">Charge Status</label>' +
                '<div class="col-md-12">' +
                '<input type="text" class="form-control" value="' +
                charge_status +
                '" placeholder="Charge Status" readonly>' +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>"
            );

            $("#viewChargesRow").append(divOption);
          });
        }
      } else {
        $("div#charged").hide();
        $("input#charged").attr("checked", false);
      }
      $('[name="vid"]').val(data.id);
      $('[name="vcategory_name"]').val(data.category_name);
      $('[name="vsubcategory_name"]').val(data.subcategory_name);
      $('[name="vparticular_name"]').val(data.particular_name);
      $('[name="vaccount_type"]').val(data.account_type);
      $('[name="vcash_flow_type"]').val(data.cash_flow_type);
      $('[name="vparticular_slug"]').val(data.particular_slug);
      $('[name="vparticular_type"]').val(data.particular_type);
      $('[name="vparticular_status"]').val(data.particular_status);
      $('[name="vstatement"]').val(data.statement);
      $('[name="vpart"]').val(data.part);
      $('[name="vparticular_code"]').val(data.particular_code);
      $('[name="vopening_balance"]').val(
        Number(data.opening_balance).toLocaleString()
      );
      $('[name="vdebit"]').val(Number(data.debit).toLocaleString());
      $('[name="vcredit"]').val(Number(data.credit).toLocaleString());
      $('[name="vcharge"]').val(data.charge);
      $('[name="vcharge_method"]').val(data.charge_method);
      $('[name="vcharge_mode"]').val(data.charge_mode);
      $('[name="vgrace_period"]').val(data.grace_period);
      $('[name="vcreated_at"]').val(data.created_at);
      $('[name="vupdated_at"]').val(data.updated_at);
      if (data.part == "debit") {
        bal = Number(data.opening_balance + data.debit - data.credit);
        if (bal < 0) {
          balance = "(" + -bal + ")";
        } else {
          balance = bal;
        }
      } else {
        bal = Number(data.opening_balance + data.debit - data.credit);
        if (bal <= 0) {
          balance = -bal;
        } else {
          balance = "(" + bal + ")";
        }
      }
      $('[name="vbalance"]').val(balance.toLocaleString());
      $(".modal-title").text("View " + data.particular_name); // Set modal title
      $("#view_modal").modal("show"); // show bootstrap modal when complete loaded
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// pop edit model
function edit_particular(id) {
  save_method = "update";
  $("#export").hide();
  $("#formRow").show();
  $("#importRow").hide();
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty();
  $('[name="mode"]').val("update");
  $.ajax({
    url: "/admin/accounts/particular/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.particular_type == "System") {
        $("div#category_id").hide();
      }

      if (data.charged.toLowerCase() == "yes") {
        if (data.particular_charges) {
          $("#chargeCounter").val(data.particular_charges);
          appendChargeRow(data.particular_charges);
          $("div#showRow").show(300);
          $("div#setChargeRow").show(300);
          $("input#charged").attr("checked", true);
        } else {
          $("div#showRow").hide();
          $("input#charged").attr("checked", false);
        }
      } else {
        $("div#showRow").hide();
        $("input#charged").attr("checked", false);
      }
      $('[name="id"]').val(data.id);
      $('[name="category_id"]').val(data.category_id);
      $('[name="cash_flow_typeId"]')
        .val(data.cash_flow_typeId)
        .trigger("change");
      $('[name="category_name"]').val(data.category_name);
      $('[name="subcategory_id"]').val(data.subcategory_id).trigger("change");
      $('[name="particular_name"]').val(data.particular_name);
      $('[name="account_typeId"]').val(data.account_typeId).trigger("change");
      $('[name="particular_slug"]').val(data.particular_slug);
      $('[name="particular_type"]').val(data.particular_type);
      $('[name="particular_status"]')
        .val(data.particular_status)
        .trigger("change");
      $('[name="statement"]').val(data.statement);
      $('[name="part"]').val(data.part);
      $('[name="particular_code"]').val(data.particular_code);
      $('[name="opening_balance"]').val(Number(data.opening_balance));
      $('[name="debit"]').val(Number(data.debit));
      $('[name="credit"]').val(Number(data.credit));
      // $('[name="charge"]').val(data.charge);
      // $('[name="charge_frequency"]').val(data.charge_frequency).trigger('change');
      // $('[name="charge_method"]').val(data.charge_method).trigger('change');
      // $('[name="charge_mode"]').val(data.charge_mode).trigger('change');
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      generate_chargeOptions();
      select_category_options(data.c_id, data.s_id, data.type_id);
      cash_flow_types(data.cash_flow_typeId);
      $(".modal-title").text("Update " + data.particular_name); // Set modal title
      $("#modal_form").modal("show"); // show bootstrap modal when complete loaded
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// save
function save_particular() {
  pID = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSav").text("Submitting..."); //change button text
  $("#btnSav").attr("disabled", true); //set button disable
  var url;
  if (save_method == "add") {
    url = "/admin/accounts/particular";
  } else {
    url = "/admin/accounts/edit-particular/" + pID;
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
          removeChargeRows();
          Swal.fire("Success!", data.messages, "success");
          reload_table("assetsParticulars");
          reload_table("equityParticulars");
          reload_table("liabilityParticulars");
          reload_table("revenueParticulars");
          reload_table("expensesParticulars");
          count_particulars();
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
function delete_particular(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to " + name + " particular!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/accounts/particular/" + id,
        type: "DELETE",
        dataType: "JSON",
        success: function (data) {
          //if success reload ajax table
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
            reload_table("assetsParticulars");
            reload_table("equityParticulars");
            reload_table("liabilityParticulars");
            reload_table("revenueParticulars");
            reload_table("expensesParticulars");
            count_particulars();
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
function bulk_deleteParticular(categoryId) {
  var list_id = [];
  $(".data-check" + categoryId + ":checked").each(function () {
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
          " particular(s) once deleted!",
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
            url: "/admin/accounts/particularBulk-delete",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Success!", data.messages, "success");
                reload_table("assetsParticulars");
                reload_table("equityParticulars");
                reload_table("liabilityParticulars");
                reload_table("revenueParticulars");
                reload_table("expensesParticulars");
                count_particulars();
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
    Swal.fire("Sorry!", "No particular selected....", "error");
  }
}

function select_category_options(
  category_id = null,
  subcategory_id = null,
  account_typeId = null
) {
  // select category
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/admin/accounts/all-categories",
    success: function (response) {
      if (response.length > 0) {
        $("select#category_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == category_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#category_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["category_name"] +
              "</option>"
          );
        });
      } else {
        $("select#category_id").html('<option value="">No Category</option>');
      }
    },
  });
  // select subcategory
  $.ajax({
    type: "POST",
    dataType: "JSON",
    url: "/admin/accounts/subcategories/" + category_id,
    success: function (response) {
      if (response.length > 0) {
        $("select#subcategory_id").find("option").not(":first").remove();
        $.each(response, function (index, data) {
          if (data["id"] == subcategory_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#subcategory_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["subcategory_name"] +
              "</option>"
          );
        });
      } else {
        $("select#subcategory_id").html(
          '<option value="">No Subcategory</option>'
        );
      }
    },
  });
  // select account type
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/admin/accounts/category-account-types/" + category_id + "/" + null,
    success: function (response) {
      if (response.length > 0) {
        $("select#account_typeId").find("option").not(":first").remove();
        $.each(response, function (index, data) {
          if (data["id"] == account_typeId) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#account_typeId").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["name"] +
              "</option>"
          );
        });
      } else {
        $("select#account_typeId").html(
          '<option value="">No Account Type</option>'
        );
      }
    },
  });
}
// load particular account types
function account_types(category_id = null) {
  if (category_id) {
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "/admin/accounts/category-account-types/" + category_id + "/" + null,
      success: function (data) {
        $("select#account_typeId").html(
          '<option value="">-- select --</option>'
        );
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].name)
              .appendTo($("select#account_typeId"));
          }
        } else {
          $("select#account_typeId").html(
            '<option value="">No Account Type</option>'
          );
        }
      },
      error: function (err) {
        $("select#account_typeId").html(
          '<option value="">Error Occured</option>'
        );
      },
    });
  } else {
    $("select#account_type").html(
      '<option value="">No Category Selected</option>'
    );
  }
}
// particular cash flow types
function cash_flow_types(cash_flow_id = null) {
  var $cashFlowSelect = $("select#cash_flow_typeId");

  // Clear existing options
  $cashFlowSelect.html("");

  // Add default option
  $cashFlowSelect.append('<option value="">-- select --</option>');

  $.ajax({
    url: "/admin/accounts/cash-flow-types",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length > 0) {
        $.each(data, function (index, item) {
          var option = $("<option>", {
            value: item.id,
            text: item.name,
          });

          if (item.id == cash_flow_id) {
            option.attr("selected", "selected");
          }

          $cashFlowSelect.append(option);
        });
      } else {
        $cashFlowSelect.html('<option value="">No Cash Flow Type</option>');
      }
    },
    error: function (err) {
      $cashFlowSelect.html('<option value="">Error Occurred</option>');
    },
  });
}
// load particular account types
function account_types_byPart(part = null) {
  if (part) {
    $.ajax({
      url: "/admin/accounts/account-types-by-part/" + part,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $("select#account_typeId").html(
          '<option value="">-- select --</option>'
        );
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].name)
              .appendTo($("select#account_typeId"));
          }
        } else {
          $("select#account_typeId").html(
            '<option value="">No Account Type</option>'
          );
        }
      },
      error: function (err) {
        $("select#account_typeId").html(
          '<option value="">Error Occured</option>'
        );
      },
    });
  } else {
    $("select#account_type").html('<option value="">No Part Selected</option>');
  }
}

// load particular charge options['mode, frequency]
function generate_chargeOptions() {
  // load mode
  $.ajax({
    url: "/admin/accounts/charge-options/mode",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("select#charge_mode").html('<option value="">-- Select --</option>');
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#charge_mode"));
        }
      } else {
        $("select#charge_mode").html('<option value="">No Mode</option>');
      }
    },
    error: function (err) {
      $("select#charge_mode").html('<option value="">Error Occured</option>');
    },
  });
  // load frequencies
  $.ajax({
    url: "/admin/accounts/charge-options/frequency",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("select#charge_frequency").html(
        '<option value="">-- Select --</option>'
      );
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#charge_frequency"));
        }
      } else {
        $("select#charge_frequency").html('<option value="">No Mode</option>');
      }
    },
    error: function (err) {
      $("select#charge_frequency").html(
        '<option value="">Error Occured</option>'
      );
    },
  });
}

// $('select#charge_mode').on('change', function () {
//     mode = this.value;
//     if (mode.toLowerCase() == 'manual' || mode == '') {
//         $('select#charge_frequency').attr('disabled', true);
//     } else {
//         $('select#charge_frequency').attr('disabled', false);
//     }
// });
$("select#charge_frequency").on("change", function () {
  var freq = this.value;
  if (freq == 0 || freq == "") {
    $('[name="grace_period"]').val("");
  } else {
    switch (freq.toLowerCase()) {
      case "one-time":
        $('[name="grace_period"]').val("0");
        break;
      case "weekly":
        $('[name="grace_period"]').val("7");
        break;
      case "bi-weekly":
        $('[name="grace_period"]').val("14");
        break;
      case "monthly":
        $('[name="grace_period"]').val("30");
        break;
      case "bi-monthly":
        $('[name="grace_period"]').val("60");
        break;
      case "quarterly":
        $('[name="grace_period"]').val("90");
        break;
      case "termly":
        $('[name="grace_period"]').val("120");
        break;
      case "bi-annual":
        $('[name="grace_period"]').val("180");
        break;
      case "annually":
        $('[name="grace_period"]').val("365");
        break;
      default:
        $('[name="grace_period"]').val(Null);
        break;
    }
  }
});

function addNewChargeRows() {
  $("button#addCharge").click(function () {
    var counter = Number($("#chargeCounter").val());
    console.log("counter " + counter);
    var chargeCounter = counter++;
    console.log("chargeCounter " + chargeCounter);
    var divOption = $(
      '<hr><div id="setChargeRow' +
        chargeCounter +
        '" class="row gx-3 gy-2 align-items-center mt-0"><hr>' +
        '<input type="hidden" readonly name="operation[' +
        chargeCounter +
        ']" value="create" />'
    );
    selectInputsOption = $(
      // frequency
      '<div class="col-md-4">' +
        '<div class="form-group">' +
        '<label class="control-label fw-bold col-md-12">Frequency <span class="text-danger">*</span></label>' +
        '<div class="col-md-12">' +
        '<select name="charge_frequency[' +
        chargeCounter +
        ']" id="charge_frequency' +
        chargeCounter +
        '" class="form-control select2bs4" style="width: 100%;">' +
        '<option value="">-- select --</option>' +
        '<option value="One-Time">One-Time</option>' +
        '<option value="Weekly">Weekly</option>' +
        '<option value="Monthly">Monthly</option>' +
        '<option value="Annually">Annually</option>' +
        "</select>" +
        '<span class="help-block text-danger"></span>' +
        "</div>" +
        "</div>" +
        "</div>" +
        // method
        '<div class="col-md-4">' +
        '<div class="form-group">' +
        '<label class="control-label fw-bold col-md-12">Charge Method ' +
        chargeCounter +
        ' <span class="text-danger">*</span></label>' +
        '<div class="col-md-12">' +
        '<select name="charge_method[' +
        chargeCounter +
        ']" id="charge_method' +
        chargeCounter +
        '" class="form-control select2bs4" style="width: 100%;">' +
        '<option value="">-- select --</option>' +
        '<option value="Amount">Amount</option>' +
        '<option value="Percent">Percent</option>' +
        "</select>" +
        '<span class="help-block text-danger"></span>' +
        "</div>" +
        "</div>" +
        "</div>" +
        // mode
        '<div class="col-md-4">' +
        '<div class="form-group">' +
        '<label class="control-label fw-bold col-md-12">Charge Mode <span class="text-danger">*</span></label>' +
        '<div class="col-md-12">' +
        '<select name="charge_mode[' +
        chargeCounter +
        ']" id="charge_mode' +
        chargeCounter +
        '" class="form-control select2bs4" style="width: 100%;">' +
        '<option value="">-- select --</option>' +
        '<option value="Manual">Manual</option>' +
        '<option value="Auto">Auto</option>' +
        "</select>" +
        '<span class="help-block text-danger"></span>' +
        "</div>" +
        "</div>" +
        "</div>"
    );
    var chargesInputsOption = $(
      // charge
      '<div class="col-md-6">' +
        '<div class="form-group">' +
        '<label class="control-label fw-bold col-md-12">Amount/Rate <span class="text-danger">*</span></label>' +
        '<div class="col-md-12">' +
        '<input name="charge[' +
        chargeCounter +
        ']" placeholder="Particular Charge" class="form-control amount" type="text" min="" max="">' +
        '<span class="help-block text-danger"></span>' +
        "</div>" +
        "</div>" +
        "</div>" +
        // charge limit
        '<div class="col-md-6">' +
        '<div class="form-group">' +
        '<label class="control-label fw-bold col-md-12">Charge Limit(Amount)</label>' +
        '<div class="col-md-12">' +
        '<input name="charge_limits[' +
        chargeCounter +
        ']" placeholder="Charge Limit(Amount)" class="form-control amount" type="text" min="" max="">' +
        '<i><small class="fw-semibold">Starting Amount(Lower limit) for the charge.</small></i>' +
        '<span class="help-block text-danger"></span>' +
        "</div>" +
        "</div>" +
        "</div>"
    );
    var dateNstatusOption = $(
      // effective date
      '<div class="col-md-3">' +
        '<div class="form-group">' +
        '<label for="effective_date' +
        chargeCounter +
        ']" class="control-label fw-bold col-sm-12">Effective Date <span class="text-danger">*</span></label>' +
        '<div class="col-sm-12">' +
        '<div class="input-group">' +
        '<div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>' +
        '<input type="text" name="effective_date[' +
        chargeCounter +
        ']" id="effective_date' +
        chargeCounter +
        ']" class="form-control getDatePicker" placeholder="Effective Date">' +
        "</div>" +
        '<i><small class="fw-semibold">Date for the charge to take effect.</small></i>' +
        '<span class="help-block text-danger"></span>' +
        "</div>" +
        "</div>" +
        "</div>" +
        // cutoff date
        '<div class="col-md-4">' +
        '<div class="form-group">' +
        '<label for="cutoff_date' +
        chargeCounter +
        ']" class="control-label fw-bold col-sm-12">Cutoff Date</label>' +
        '<div class="col-sm-12">' +
        '<div class="input-group">' +
        '<div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>' +
        '<input type="text" name="cutoff_date[' +
        chargeCounter +
        ']" id="cutoff_date' +
        chargeCounter +
        ']" class="form-control getDatePicker" placeholder="Cutoff Date">' +
        "</div>" +
        '<i><small class="fw-semibold">Date for the charge reduction.</small></i>' +
        '<span class="help-block text-danger"></span>' +
        "</div>" +
        "</div>" +
        "</div>" +
        // status
        '<div class="col-md-3">' +
        '<div class="form-group">' +
        '<label class="control-label fw-bold col-md-12">Charge Status <span class="text-danger">*</span></label>' +
        '<div class="col-md-12">' +
        '<select name="charge_status[' +
        chargeCounter +
        ']" id="charge_status' +
        chargeCounter +
        '" class="form-control select2bs4">' +
        '<option value="">-- select --</option>' +
        '<option value="Active">Active</option>' +
        '<option value="Inactive">Inactive</option>' +
        "</select>" +
        '<span class="help-block text-danger"></span>' +
        "</div>" +
        "</div>" +
        "</div>"
    );
    var buttonOption = $(
      '<div class="col-2">' +
        '<div class="col-md-12">' +
        '<label class="control-label fw-bold col-md-12">' +
        "Click here to</label>" +
        '<button id="remove[]" class="btn btn-danger btn-block" type="button">Remove</button>' +
        "</div>" +
        "</div>" +
        "</div>"
    );

    buttonOption.click(function () {
      $(this).parent().remove();
      chargeCounter--;
      $("#chargeCounter").val(chargeCounter);
    });

    $("#chargesRow").append(divOption);
    $("#setChargeRow" + chargeCounter).append(selectInputsOption);
    $("#setChargeRow" + chargeCounter).append(chargesInputsOption);
    $("#setChargeRow" + chargeCounter).append(dateNstatusOption);
    $("#setChargeRow" + chargeCounter).append(buttonOption);

    // initialize select2 elements for debit accounts
    $(".select2bs4").each(function () {
      $(this).select2({
        // theme: 'bootstrap-5',
        dropdownParent: $(this).parent(),
      });
    });

    $("input").on("input", function () {
      $(this).parent().removeClass("has-error");
      $(this).next().empty();
    });
    // remove errors on select boxes
    $("select").on("input", function () {
      $(this).parent().removeClass("has-error");
      $(this).next().next().empty();
    });

    flatpickr(".getDatePicker", {
      altInput: true,
      altFormat: "Y-m-d",
      dateFormat: "Y-m-d",
    });

    chargeCounter++;
    $("#chargeCounter").val(chargeCounter); // Update hidden field value
  });
}

function appendChargeRow(existingChargesData) {
  // var chargeCounter = 1;
  $("#chargeCounter").val(existingChargesData.length);
  if (existingChargesData.length) {
    $.each(existingChargesData, function (index, column) {
      var charge_id = column["id"];
      var charge_frequency = column["frequency"];
      var charge_method = column["charge_method"];
      var charge_mode = column["charge_mode"];
      var charge = column["charge"];
      var limit = column["charge_limits"];
      var effective_date = column["effective_date"];
      var cutoff_date = column["cutoff_date"];
      var charge_status = column["status"];
      var chargeCounter = index + 1;
      var sno = index;

      var divOption = $(
        '<hr><div id="editChargeRow' +
          chargeCounter +
          '" class="row gx-3 gy-2 align-items-center mt-0">' +
          '<input type="hidden" readonly name="operation[' +
          chargeCounter +
          ']" value="update" />' +
          '<input type="hidden" readonly name="charge_id[' +
          chargeCounter +
          ']" value="' +
          charge_id +
          '" />'
      );
      var selectInputOptions = $(
        // frequency
        '<div class="col-md-4">' +
          '<div class="form-group">' +
          '<label class="control-label fw-bold col-md-12">Frequency <span class="text-danger">*</span></label>' +
          '<div class="col-md-12">' +
          '<select name="charge_frequency[' +
          chargeCounter +
          ']" id="charge_frequency' +
          chargeCounter +
          '" class="form-control select2bs4" style="width: 100%;">' +
          '<option value="">-- select --</option>' +
          '<option value="One-Time"' +
          (charge_frequency === "One-Time" ? " selected" : "") +
          ">One-Time</option>" +
          '<option value="Weekly"' +
          (charge_frequency === "Weekly" ? " selected" : "") +
          ">Weekly</option>" +
          '<option value="Monthly"' +
          (charge_frequency === "Monthly" ? " selected" : "") +
          ">Monthly</option>" +
          '<option value="Annually"' +
          (charge_frequency === "Annually" ? " selected" : "") +
          ">Annually</option>" +
          "</select>" +
          '<span class="help-block text-danger"></span>' +
          "</div>" +
          "</div>" +
          "</div>" +
          // method
          '<div class="col-md-4">' +
          '<div class="form-group">' +
          '<label class="control-label fw-bold col-md-12">Charge Method <span class="text-danger">*</span></label>' +
          '<div class="col-md-12">' +
          '<select name="charge_method[' +
          chargeCounter +
          ']" id="charge_method' +
          chargeCounter +
          '" class="form-control select2bs4" style="width: 100%;">' +
          '<option value="">-- select --</option>' +
          '<option value="Amount" ' +
          (charge_method === "Amount" ? " selected" : "") +
          ">Amount</option>" +
          '<option value="Percent" ' +
          (charge_method === "Percent" ? " selected" : "") +
          ">Percent</option>" +
          "</select>" +
          '<span class="help-block text-danger"></span>' +
          "</div>" +
          "</div>" +
          "</div>" +
          // mode
          '<div class="col-md-4">' +
          '<div class="form-group">' +
          '<label class="control-label fw-bold col-md-12">Charge Mode <span class="text-danger">*</span></label>' +
          '<div class="col-md-12">' +
          '<select name="charge_mode[' +
          chargeCounter +
          ']" id="charge_mode' +
          chargeCounter +
          '" class="form-control select2bs4" style="width: 100%;">' +
          '<option value="">-- select --</option>' +
          '<option value="Manual" ' +
          (charge_mode === "Manual" ? " selected" : "") +
          ">Manual</option>" +
          '<option value="Auto" ' +
          (charge_mode === "Auto" ? " selected" : "") +
          ">Auto</option>" +
          "</select>" +
          '<span class="help-block text-danger"></span>' +
          "</div>" +
          "</div>" +
          "</div>"
      );
      var chargesInputOptions = $(
        // charge
        '<div class="col-md-6">' +
          '<div class="form-group">' +
          '<label class="control-label fw-bold col-md-12">Amount/Rate <span class="text-danger">*</span></label>' +
          '<div class="col-md-12">' +
          '<input name="charge[' +
          chargeCounter +
          ']" placeholder="Particular Charge" class="form-control amount" type="text" min="" max="" value="' +
          charge.toLocaleString() +
          '">' +
          '<span class="help-block text-danger"></span>' +
          "</div>" +
          "</div>" +
          "</div>" +
          // limit
          '<div class="col-md-6">' +
          '<div class="form-group">' +
          '<label class="control-label fw-bold col-md-12">Charge Limit(Amount)</label>' +
          '<div class="col-md-12">' +
          '<input name="charge_limits[' +
          chargeCounter +
          ']" placeholder="Particular Charge" class="form-control amount" type="text" min="" max="" value="' +
          limit.toLocaleString() +
          '">' +
          '<i><small class="fw-semibold">Starting Amount(Lower limit) for the charge.</small></i>' +
          '<span class="help-block text-danger"></span>' +
          "</div>" +
          "</div>" +
          "</div>"
      );
      var dateNstatusInputsOption = $(
        '<div class="col-md-4">' +
          '<div class="form-group">' +
          '<label for="effective_date" class="control-label fw-bold col-sm-12">Effective Date <span class="text-danger">*</span></label>' +
          '<div class="col-sm-12">' +
          '<div class="input-group">' +
          '<div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>' +
          '<input type="text" name="effective_date[' +
          chargeCounter +
          ']" id="effective_date" class="form-control getDatePicker" value="' +
          effective_date +
          '" placeholder="Effective Date">' +
          "</div>" +
          '<i><small class="fw-semibold">Date for the charge to take effect.</small></i>' +
          '<span class="help-block text-danger"></span>' +
          "</div>" +
          "</div>" +
          "</div>" +
          // cutoff date
          '<div class="col-md-4">' +
          '<div class="form-group">' +
          '<label for="cutoff_date[' +
          chargeCounter +
          ']" class="control-label fw-bold col-sm-12">Cutoff Date</label>' +
          '<div class="col-sm-12">' +
          '<div class="input-group">' +
          '<div class="input-group-text text-muted"> <i class="far fa-calendar-alt"></i> </div>' +
          '<input type="text" name="cutoff_date[' +
          chargeCounter +
          ']" id="cutoff_date[' +
          chargeCounter +
          ']" class="form-control getDatePicker" value="' +
          cutoff_date +
          '" placeholder="Cutoff Date">' +
          "</div>" +
          '<i><small class="fw-semibold">Date for the charge reduction.</small></i>' +
          '<span class="help-block text-danger"></span>' +
          "</div>" +
          "</div>" +
          "</div>" +
          // status
          '<div class="col-md-4">' +
          '<div class="form-group">' +
          '<label class="control-label fw-bold col-md-12">Charge Status <span class="text-danger">*</span></label>' +
          '<div class="col-md-12">' +
          '<select name="charge_status[' +
          chargeCounter +
          ']" id="charge_status' +
          chargeCounter +
          '" class="form-control select2bs4">' +
          '<option value="">-- select --</option>' +
          '<option value="Active" ' +
          (charge_status === "Active" ? " selected" : "") +
          ">Active</option>" +
          '<option value="Inactive" ' +
          (charge_status === "Inactive" ? " selected" : "") +
          ">Inactive</option>" +
          "</select>" +
          '<span class="help-block text-danger"></span>' +
          "</div>" +
          "</div>" +
          "</div>"
      );
      var buttonOption = $(
        '<div class="col-2" style="display: none;">' +
          '<div class="col-md-12">' +
          '<label class="control-label fw-bold col-md-12">' +
          "Click here to</label>" +
          '<button id="remove[]" class="btn btn-danger btn-block" type="button">Remove</button>' +
          "</div>" +
          "</div>" +
          "</div>"
      );

      buttonOption.click(function () {
        // $(this).parent().remove();
      });

      $("#chargesRow").append(divOption);
      $("#editChargeRow" + chargeCounter).append(selectInputOptions);
      $("#editChargeRow" + chargeCounter).append(chargesInputOptions);
      $("#editChargeRow" + chargeCounter).append(dateNstatusInputsOption);
      $("#editChargeRow" + chargeCounter).append(buttonOption);

      // chargeCounter = $('#chargesRow').children().length;
      // $('#chargeCounterValue').val(chargeCounter);

      // initialize select2 elements for debit accounts
      $(".select2bs4").each(function () {
        $(this).select2({
          // theme: 'bootstrap-5',
          dropdownParent: $(this).parent(),
        });
      });

      $("input").on("input", function () {
        $(this).parent().removeClass("has-error");
        $(this).next().empty();
      });
      // remove errors on select boxes
      $("select").on("input", function () {
        $(this).parent().removeClass("has-error");
        $(this).next().next().empty();
      });

      flatpickr(".getDatePicker", {
        altInput: true,
        altFormat: "Y-m-d",
        dateFormat: "Y-m-d",
        // defaultDate: "today",
      });

      chargeCounter++;
      $("#chargeCounter").val(chargeCounter);
    });
  }
}

function removeChargeRows() {
  // Remove all charge rows or perform logic to identify and remove specific rows
  $("#chargesRow").empty();
  // chargeCounter = 1; // Reset the counter
  $("input#charged").attr("checked", false);
}
