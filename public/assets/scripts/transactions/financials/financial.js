var save_method;
var tableId = transaction;
var part = "credit";
// dataTables url
var tableDataUrl = "/admin/transactions/generate-transactions/financing";
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "particular_name" },
  { data: "payment_method" },
  { data: "name" },
  { data: "amount", render: $.fn.DataTable.render.number(",") },
  { data: "ref_id" },
  { data: "staff_name" },
  { data: "date" },
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
      add_transaction();
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
      bulk_deleteTransaction();
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

  // load clients
  selectClient();
  // get credit account types
  account_types_byPart(part);
  // payment methods
  selectPaymentMethod();
});

function transactionForm() {
  var id = $('[name="id"]').val();
  var menu = $('[name="entry_menu"]').val();
  window.location.assign(
    "/admin/transactions/transactionform/" + id + "/" + menu
  );
}

// pop add model
function add_transaction() {
  save_method = "add";
  $("#" + transaction + "Form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#modal_form").modal("show"); // show bootstrap modal
  $(".modal-title").text("Add Transaction"); // Set Title to Bootstrap modal title
  $('[name="id"]').val(0);
  $("select#account_typeId").trigger("change");
  $("select#particular_id").trigger("change");
  $("select#entry_typeId").trigger("change");
  $("select#client_id").trigger("change");
  $("select#payment_id").trigger("change");
  $("textarea#summernote").summernote("reset");
  $("div#applicationCode").val("");
  $("div#disbursementCode").val("");
  $("div#applicationCode").hide();
  $("div#disbursementCode").hide();
}

// view record
function view_transaction(id, total_amount) {
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/transactions/transaction/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.client_id != null) {
        $("div#clientData").show();
      }
      if (data.disbursement_id != null) {
        $("div#disbursementData").show();
      }
      if (data.application_id != null) {
        $("div#applicationData").show();
      }
      $('[name="id"]').val(data.id);
      $('[name="vclient_name"]').val(data.name);
      $('[name="vaccount_no"]').val(data.account_no);
      $('[name="vcontact"]').val(data.contact);
      $('[name="vaccount_type"]').val(data.module);
      $('[name="vparticular_name"]').val(data.particular_name);
      $('[name="ventry_type"]').val(data.type);
      $('[name="vpayment"]').val(data.payment);
      $('[name="vdate"]').val(data.date);
      $('[name="vbranch_name"]').val(data.branch_name);
      $('[name="vstatus"]').val(data.status);
      $('[name="vstaff_id"]').val(data.staff_name);
      $('[name="vproduct_name"]').val(data.product_name);
      $('[name="vdisbursement_id"]').val(data.disbursement_code);
      $('[name="vclass"]').val(data.class);
      $('[name="vapplication_id"]').val(data.application_code);
      $('[name="vapplication_status"]').val(data.status);
      $('[name="vref_id"]').val(data.ref_id);
      $('[name="vamount"]').val(total_amount);
      $('[name="vstatus"]').val(data.status);
      $("textarea#viewSummernote").summernote("code", data.entry_details);
      $('[name="vremarks"]').val(data.remarks);
      $('[name="ventry_menu"]').val(data.entry_menu);
      $('[name="vbalance"]').val(data.balance);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $("#view_modal").modal("show");
      $(".modal-title").text("View " + data.ref_id + " transaction");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// pop edit model
function edit_transaction(id, total_amount) {
  save_method = "update";
  $("#export").hide();
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string

  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/transactions/transaction/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.client_id != null) {
        $("div#clientData").show();
      }
      if (data.disbursement_id != null) {
        $("div#disbursementData").show();
      }
      if (data.application_id != null) {
        $("div#applicationData").show();
      }
      $('[name="id"]').val(data.id);
      $('[name="date"]').val(data.date);
      $('[name="account_typeId"]').val(data.account_typeId);
      $('[name="entry_typeId"]').val(data.entry_typeId);
      $('[name="particular_id"]').val(data.particular_id);
      $('[name="payment_id"]').val(data.payment_id);
      $('[name="client_id"]').val(data.client_id);
      $('[name="account_no"]').val(data.account_no);
      $('[name="contact"]').val(data.contact);
      $('[name="disbursement_id"]').val(data.disbursement_id);
      $('[name="application_id"]').val(data.application_id);
      $('[name="amount"]').val(total_amount);
      $('[name="staff_id"]').val(data.staff_id);
      $("textarea#summernote").summernote("code", data.entry_details);
      $('[name="remarks"]').val(data.remarks);
      $('[name="entry_menu"]').val(data.entry_menu);
      $('[name="status"]').val(data.status);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $('[name="product_name"]').val(data.product_name);
      $('[name="cycle"]').val(data.cycle);
      $('[name="application_status"]').val(data.application_status);
      account_types_byPart(part, data.account_typeId); // select account type
      selectParticulars(data.account_typeId, data.particular_id); // select particular
      selectClient(data.client_id); // select client
      select_transactionType(data.account_typeId, data.entry_typeId); // select client
      selectPaymentMethod(data.payment_id); // select payment method
      $("#modal_form").modal("show");
      $(".modal-title").text("Update " + data.ref_id + " transaction"); // Set title to Bootstrap modal title
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// save
function save_transaction() {
  tID = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSubmit").text("Submitting..."); //change button text
  $("#btnSubmit").attr("disabled", true); //set button disable
  var url;
  if (save_method == "add") {
    url = "/admin/transactions/transaction";
  } else {
    url = "/admin/transactions/edit-transaction/" + tID;
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
          $("#" + tableId + "_modalForm").modal("hide");
          Swal.fire("Success!", data.messages, "success");
          $("#summernote").summernote("reset");
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
      $("#btnSubmit").text("Submit"); //change button text
      $("#btnSubmit").attr("disabled", false); //set button enable
    },
  });
}

// delete record
function delete_transaction(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to recover " + name + " transaction!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/transactions/transaction/" + id,
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
function bulk_deleteFinancial() {
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
          "You will not be able to recover these " +
          list_id.length +
          " transaction(s) once deleted!",
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
            url: "/admin/transactions/transactionsBulk-delete",
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
    Swal.fire("Sorry!", "No transaction selected....", "error");
  }
}

function module_particulars(module, particular_id) {
  $.ajax({
    type: "POST",
    dataType: "JSON",
    url: "/admin/accounts/all-categories",
    success: function (response) {
      if (response.length > 0) {
        $("select#module").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == category_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#module").append(
            '<option value="' +
              data["module"] +
              '" ' +
              selection +
              ">" +
              data["module"] +
              "</option>"
          );
        });
      } else {
        $("select#module").html('<option value="">No Module</option>');
      }
    },
  });

  $.ajax({
    type: "POST",
    dataType: "JSON",
    url: "/admin/accounts/accountType-particulars/" + module,
    success: function (response) {
      if (response.length > 0) {
        $("select#particular_id").find("option").not(":first").remove();
        $.each(response, function (index, data) {
          if (data["id"] == subcategory_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#particular_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["particular_name"] +
              "</option>"
          );
        });
      } else {
        $("select#particular_id").html(
          '<option value="">No Particular</option>'
        );
      }
    },
  });
}
// client pending disbursements\applications
function clientPendingDisbursements() {
  $("select#client_id").change(function () {
    clientID = $(this).val();
    if (clientID != 0 || clientID != "") {
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/admin/loans/pending-disbursements/" + clientID,
        success: function (data) {
          if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
              $("<option />")
                .val(data[i].id)
                .text(data[i].disbursement_code)
                .appendTo($("select#disbursement_id"));
              $('[name="charge"]').val(data[i].actual_installment);
              $('[name="amount"]').val(data[i].actual_installment);
            }
          } else {
            $("select#disbursement_id").html(
              '<option value="">No loan found</option>'
            );
          }
        },
        error: function (err) {
          $("select#disbursement_id").html(
            '<option value="">Error Occured</option>'
          );
        },
      });
    } else {
      $("select#disbursement_id").html(
        '<option value="">select client</option>'
      );
      $('[name="charge"]').val("");
      $('[name="amount"]').val("");
    }
  });
}
function clientPendingApplications() {
  $("select#client_id").change(function () {
    var clientID = $(this).val();
    if (clientID != "" || clientID != 0) {
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/admin/loans/pending-applications/" + clientID,
        success: function (data) {
          $("select#application_id").html(
            '<option value="">Select application</option>'
          );
          if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
              $("<option />")
                .val(data[i].id)
                .text(data[i].application_code)
                .appendTo($("select#application_id"));
              particularCharge(data[i].principal);
            }
          } else {
            $("select#application_id").html(
              '<option value="">No application</option>'
            );
          }
        },
        error: function (err) {
          $("select#application_id").html(
            '<option value="">Error Occured</option>'
          );
        },
      });
    } else {
      $("select#application_id").html(
        '<option value="">Select client</option>'
      );
      $('[name="charge"]').val("");
      $('[name="amount"]').val("");
    }
  });
}
function particularCharge(amount) {
  $("select#particular_id").on("change", function () {
    var particular_id = $(this).val();
    if (particular_id != 0 || particular_id != "") {
      $.ajax({
        url: "/admin/accounts/particular/" + particular_id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
          if (data.charge_method == "Amount") {
            $('[name="charge"]').val(data.charge);
            $('[name="amount"]').val(data.charge);
          } else {
            $('[name="charge"]').val((data.charge / 100) * amount);
            $('[name="amount"]').val((data.charge / 100) * amount);
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    } else {
      $('[name="charge"]').val("");
      $('[name="amount"]').val("");
    }
  });
}

// load selected particular data
$("select#particular_id").on("change", function () {
  var particular_id = this.value;
  if (particular_id == "" || particular_id == 0) {
    $('[name="charge"]').val("");
    $('[name="amount"]').val("");
  } else {
    $.ajax({
      url: "/admin/accounts/particular/" + particular_id,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        if (data.charged.toLowerCase() == "yes") {
          if (data.charge_method.toLowerCase() == "amount") {
            $('[name="charge"]').val(data.charge);
          } else {
            $('[name="charge"]').val(data.charge + "%");
          }
        } else {
          $('[name="charge"]').val("None");
        }
      },
      error: function (err) {
        $('[name="charge"]').val("Error Occured");
      },
    });
  }
});
// load selected client data
$("select#client_id").on("change", function () {
  var client_id = this.value;
  if (client_id == "" || client_id == 0) {
    $('[name="account_no"').val("");
    $('[name="contact"').val("");
  } else {
    $.ajax({
      url: "/admin/clients/client/" + client_id,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $('[name="account_no"').val(data.account_no);
        $('[name="contact"').val(data.mobile);
      },
      error: function (err) {},
    });
  }
});
