var save_method;
var tableId = product + "-products";
// dataTables url
var tableDataUrl = "/admin/products/generate-products/" + product + "/" + id;
// dataTables column config
var columnsConfig = {
  savings: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "product_name" },
    { data: "product_code" },
    { data: "min_account_balance", render: $.fn.DataTable.render.number(",") },
    { data: "max_account_balance", render: $.fn.DataTable.render.number(",") },
    { data: "min_per_entry", render: $.fn.DataTable.render.number(",") },
    { data: "max_per_entry", render: $.fn.DataTable.render.number(",") },
    { data: "productPeriod" },
    { data: "interestRate" },
    { data: "status" },
    { data: "action", orderable: false, searchable: false },
  ],
  loans: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "product_name" },
    { data: "product_code" },
    { data: "productPeriod" },
    { data: "interestRate" },
    { data: "min_per_entry", render: $.fn.DataTable.render.number(",") },
    { data: "max_per_entry", render: $.fn.DataTable.render.number(",") },
    {
      data: "application_min_savings",
      render: $.fn.DataTable.render.number(","),
    },
    {
      data: "application_max_savings",
      render: $.fn.DataTable.render.number(","),
    },
    {
      data: "disbursement_min_savings",
      render: $.fn.DataTable.render.number(","),
    },
    {
      data: "'disbursement_max_savings",
      render: $.fn.DataTable.render.number(","),
    },
    { data: "status" },
    { data: "action", orderable: false, searchable: false },
  ],
};
// dataTables buttons config
var buttonsConfig = [];
// show create button
if (
  userPermissions.includes("create_" + menu.toLowerCase() + titleSlug) ||
  userPermissions === '"all"'
) {
  buttonsConfig.push({
    text: '<i class="fas fa-plus"></i>',
    className: "btn btn-sm btn-secondary create" + title,
    attr: {
      id: "create" + title,
    },
    titleAttr: "Add " + title,
    action: function () {
      add_product();
    },
  });
}
// show upload button
if (
  userPermissions.includes("import_" + menu.toLowerCase() + titleSlug) ||
  userPermissions === '"all"'
) {
  buttonsConfig.push({
    text: '<i class="fas fa-upload"></i>',
    className: "btn btn-sm btn-info import" + title,
    attr: {
      id: "import" + title,
    },
    titleAttr: "Import " + title + "(s/es)",
    action: function () {
      import_products();
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
      bulk_deleteProducts();
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
if (
  userPermissions.includes("export_" + menu.toLowerCase() + titleSlug) ||
  userPermissions === '"all"'
) {
  buttonsConfig.push(
    {
      extend: "excel",
      className: "btn btn-sm btn-success",
      titleAttr: "Export " + title + " To Excel",
      text: '<i class="fas fa-file-excel"></i>',
      filename: title + " Information",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
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
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
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
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-secondary",
      titleAttr: "Copy " + title + " Information",
      text: '<i class="fas fa-copy"></i>',
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
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
      filename: title + " Information",
    }
  );
}

$(document).ready(function () {
  // call to dataTable initialization function
  initializeDataTable(
    tableId,
    tableDataUrl,
    columnsConfig[product],
    buttonsConfig
  );
  generateCustomeSettings("interest_period");
  generateCustomeSettings("repayments");
  generateCustomeSettings("interesttypes");
});

function exportProductForm() {
  var product_id = $('[name="id"]').val();
  window.location.assign("/admin/products/productform/" + product_id);
}

// enable/disable product charges' properties based on checkbox state
let setProductCharges = (charge_id) => {
  if ($("#product_charge" + charge_id).is(":checked")) {
    $("#charges_types" + charge_id).prop("disabled", false);
    $("#charges_fees" + charge_id).prop("disabled", false);
    $("#charges_reduction" + charge_id).prop("disabled", false);
  } else {
    $("#charges_types" + charge_id).prop("disabled", true);
    $("#charges_fees" + charge_id).prop("disabled", true);
    $("#charges_reduction" + charge_id).prop("disabled", true);
  }
};

// pop add model
function add_product() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#formRow").show();
  $("#importRow").hide();
  $('[name="id"]').val(0);
  $('[name="mode"]').val("create");
  $('[name="product_type"]').val(product);
  $("select#repayments").trigger("change");
  $("select#interest_period").trigger("change");
  $("select#interest_type").trigger("change");
  $("select#status").trigger("change");
  $("select#savings_particular_id").trigger("change");
  $("select#withdrawCharges_particular_id").trigger("change");
  $("textarea#summernote").summernote("reset");
  selectParticulars(particular_accountTypeId, null, "savings_particular_id"); // load savings particulars
  selectParticulars(
    charge_accountTypeId,
    null,
    "withdrawCharges_particular_id"
  ); // load withdraw charges particulars

  // load ajax options
  generateCustomeSettings("repayments");
  generateCustomeSettings("interesttypes");
  generateCustomeSettings("interest_period");
  $(".modal-title").text("Add " + product + " Product"); // Set Title to Bootstrap modal title
  $("#modal_form").modal("show"); // show bootstrap modal
}
function import_products() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#formRow").hide();
  $("#export").hide();
  $('[name="id"]').val(0);
  $('[name="mode"]').val("import");
  $(".modal-title").text("Import " + product + " Product(s)");
  $("#modal_form").modal("show"); // show bootstrap modal
  $("#importRow").show();
}

// view record
function view_product(id) {
  $.ajax({
    url: "/admin/products/product/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="product_name"]').val(data.product_name);
      $('[name="product_code"]').val(data.product_code);
      $('[name="savings_particular"]').val(data.savings_particular);
      $('[name="withdrawCharges_particular"]').val(data.withdrawCharges_particular);
      $('[name="product_type"]').val(data.product_type);
      $('[name="min_account_balance"]').val(data.min_account_balance);
      $('[name="max_account_balance"]').val(data.max_account_balance);
      $('[name="min_per_entry"]').val(data.min_per_entry);
      $('[name="max_per_entry"]').val(data.max_per_entry);
      $('[name="interest_rate"]').val(data.interest_rate);
      $('[name="interest_period"]').val(data.interest_period);
      $('[name="interest_type"]').val(data.interest_type);
      $('[name="product_period"]').val(data.product_period);
      $('[name="product_duration"]').val(data.product_duration);
      $('[name="product_frequency"]').val(data.product_frequency);
      $('[name="application_min_savings_balance_type"]').val(
        data.application_min_savings_balance_type
      );
      $('[name="application_min_savings_balance"]').val(
        data.application_min_savings_balance
      );
      $('[name="application_max_savings_balance_type"]').val(
        data.application_max_savings_balance_type
      );
      $('[name="application_max_savings_balance"]').val(
        data.application_max_savings_balance
      );
      $('[name="disbursement_min_savings_balance_type"]').val(
        data.disbursement_min_savings_balance_type
      );
      $('[name="disbursement_min_savings_balance"]').val(
        data.disbursement_min_savings_balance
      );
      $('[name="disbursement_max_savings_balance_type"]').val(
        data.disbursement_max_savings_balance_type
      );
      $('[name="disbursement_max_savings_balance"]').val(
        data.disbursement_max_savings_balance
      );
      $('[name="product_desc"]').val(data.product_desc);
      $("textarea#newSummernote").summernote("code", data.product_features);
      $('[name="status"]').val(data.status).trigger("change");
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      // check product charges
      var html = "";
      if (data.charges.length > 0) {
        $.each(data.charges, function (index, charge) {
          var chargeId = charge.particular_id;
          var chargeName = charge.particular_name;
          var chargeMethod = charge.charge_method;
          var chargeValue = charge.charge;
          var chargeMode = charge.charge_mode;
          if (chargeMethod.toLowerCase() == "amount") {
            var symbol = " " + currency;
          } else {
            var symbol = "%";
          }

          html +=
            '<div class="col-xl-4 task-card">' +
            '<div class="card custom-card task-pending-card">' +
            '<div class="card-body">' +
            '<div class="d-flex justify-content-between flex-wrap">' +
            "<div>" +
            '<p class="fw-semibold mb-3 d-flex align-items-center">' +
            '<span class="form-check form-check-md form-switch">' +
            '<input type="checkbox" name="vproduct_charges[]" value="' +
            chargeId +
            '" id="vproduct_charge' +
            chargeId +
            '" class="form-check-input form-checked-success vproduct_charge" checked disabled>' +
            "</span>&nbsp;" +
            chargeName +
            "</p>" +
            '<p class="mb-3">' +
            'Type : <span class="fs-12 mb-1 text-muted">' +
            chargeMethod +
            "</span>" +
            "</p>" +
            '<p class="mb-3">' +
            'Charge : <span class="fs-12 mb-1 text-muted">' +
            chargeValue +
            symbol +
            "</span>" +
            "</p>" +
            '<p class="mb-0">' +
            'Deduction : <span class="fs-12 mb-1 text-muted">' +
            chargeMode +
            "</span>" +
            "</p>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";
        });
      } else {
        html +=
          '<p class="fw-semibold text-primary text-center mb-3">No Applicable Charges found</p>';
      }
      $("#vcharges").html(html);
      $(".modal-title").text("View " + data.product_name);
      $("#view_modal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// pop edit model
function edit_product(id) {
  save_method = "update";
  $("#export").hide();
  $("#formRow").show();
  $("#importRow").hide();
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/products/product/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      selectParticulars(
        particular_accountTypeId,
        data.savings_particular_id,
        "savings_particular_id"
      ); // load savings particulars
      selectParticulars(
        charge_accountTypeId,
        data.withdrawCharges_particular_id,
        "withdrawCharges_particular_id"
      ); // load withdraw charges particulars
      $('[name="id"]').val(data.id);
      $('[name="product_name"]').val(data.product_name);
      $('[name="product_code"]').val(data.product_code);
      $('[name="product_type"]').val(data.product_type);
      $('[name="min_account_balance"]').val(data.min_account_balance);
      $('[name="max_account_balance"]').val(data.max_account_balance);
      $('[name="min_per_entry"]').val(data.min_per_entry);
      $('[name="max_per_entry"]').val(data.max_per_entry);
      $('[name="interest_rate"]').val(data.interest_rate);
      $('[name="interest_period"]').val(data.interest_period).trigger("change");
      $('[name="interest_type"]').val(data.interest_type).trigger("change");
      $('[name="product_period"]').val(data.product_period);
      $('[name="product_duration"]').val(data.product_duration);
      $('[name="product_frequency"]')
        .val(data.product_frequency)
        .trigger("change");
      $('[name="application_min_savings_balance_type"]')
        .val(data.application_min_savings_balance_type)
        .trigger("change");
      $('[name="application_min_savings_balance"]').val(
        data.application_min_savings_balance
      );
      $('[name="application_max_savings_balance_type"]')
        .val(data.application_max_savings_balance_type)
        .trigger("change");
      $('[name="application_max_savings_balance"]').val(
        data.application_max_savings_balance
      );
      $('[name="disbursement_min_savings_balance_type"]')
        .val(data.disbursement_min_savings_balance_type)
        .trigger("change");
      $('[name="disbursement_min_savings_balance"]').val(
        data.disbursement_min_savings_balance
      );
      $('[name="disbursement_max_savings_balance_type"]')
        .val(data.disbursement_max_savings_balance_type)
        .trigger("change");
      $('[name="disbursement_max_savings_balance"]').val(
        data.disbursement_max_savings_balance
      );
      $('[name="product_desc"]').val(data.product_desc);
      $("textarea#summernote").summernote("code", data.product_features);
      $('[name="status"]').val(data.status).trigger("change");
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $(".product_charge").prop("checked", false);
      $(".charges_types").prop("disabled", true);
      $(".charges_fees").prop("disabled", true);
      $(".charges_reduction").prop("disabled", true);
      // check product charge
      if (data.charges.length < 0) {
        $(".product_charge").prop("checked", false);
      } else {
        $.each(data.charges, function (index, item) {
          // console.log(index);
          var chargeId = item.particular_id;
          var chargeMethod = item.charge_method;
          var chargeMode = item.charge_mode;
          var charge = item.charge;
          // Check the checkbox based on the chargeId
          $("#product_charge" + chargeId).prop("checked", true);
          // Update the value, type, and mode based on the chargeId
          $("#charges_types" + chargeId)
            .val(chargeMethod)
            .trigger("change");
          $("#charges_fees" + chargeId).val(charge);
          $("#charges_reduction" + chargeId)
            .val(chargeMode)
            .trigger("change");
          $("#charge_id" + item.particular_id).val(item.id);
          // $('[name="charge_id' + index + '"]').val(item.id);
          // Enable/disable product charges based on checkbox state
          setProductCharges(chargeId);
        });
      }

      $(".modal-title").text("Update " + data.product_name);
      $("#modal_form").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// save
function save_product() {
  pID = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSav").text("Submitting..."); //change button text
  $("#btnSav").attr("disabled", true); //set button disable
  var url, method;
  if (save_method == "add") {
    url = "/admin/products/product";
  } else {
    url = "/admin/products/edit-product/" + pID;
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
function delete_product(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to recover" + name + " product!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/products/product/" + id,
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
function bulk_deleteProducts() {
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
          "Your will not be able to recover these " +
          list_id.length +
          " product(s) once deleted!",
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
            url: "/admin/products/bulk-delete",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Deleted!", data.messages, "success");
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
    Swal.fire("Sorry!", "No loan product selected....", "error");
  }
}