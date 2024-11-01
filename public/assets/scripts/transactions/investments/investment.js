var save_method;
var tableId = "investments";
var assetsID = 1; // category id for assets
var equityID = 2; // category id for equity
var entry_typeId = 9 // entry type id for entry
// dataTables url
var tableDataUrl = "/admin/transactions/generate-transactions/investment";
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "crParticular_name" },
  { data: "type" },
  { data: "drParticular_name" },
  { data: "dr_module" },
  // { data: "entry_menu" },
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
    titleAttr: "Add " + (title),
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
      bulk_deleteFinancial();
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
  initializeDataTable(tableId, tableDataUrl, columnsConfig, buttonsConfig);
  // load form account types
  load_accountTypes();
});

function transactionForm() {
  var id = $('[name="id"]').val();
  window.location.assign(
    "/admin/transactions/transactionform/" + id + "/" + transaction
  );
}

// pop add model
function add_transaction() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $('[name="id"]').val(0);
  $('[name="entry_typeId"]').val(entry_typeId);
  $('[name="entry_menu"]').val(transaction);
  $('select#credit_accountType').trigger('change');
  $('select#crParticular_id').trigger('change');
  $('select#debit_accountType').trigger('change');
  $('select#drParticular_id').trigger('change');
  $('textarea#newSummernote').summernote('reset');
  $(".modal-title").text(
    "Add " + capitalizeFirstLetter(transaction) + " Transaction"
  ); // Set Title to Bootstrap modal title
  $("#modal_form").modal("show"); // show bootstrap modal
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
        $('textarea#newSummernote').summernote('code', data.entry_details);
        $('[name="remarks"]').val(data.remarks);
        $('[name="entry_menu"]').val(data.entry_menu);
        $('[name="status"]').val(data.status);
        $('[name="created_at"]').val(data.created_at);
        $('[name="updated_at"]').val(data.updated_at);
        $('[name="product_name"]').val(data.product_name);
        $('[name="cycle"]').val(data.cycle);
        $('[name="application_status"]').val(data.application_status);
        load_accountTypes(data.account_typeId); // select account type
        load_creditParticular(data.account_typeId, data.payment_id); // select credit particular
        load_debitParticular(data.account_typeId, data.particular_id); // select debit particular
        $('#modal_form').modal('show');
        $('.modal-title').text('Update ' + data.ref_id + ' transaction'); // Set title to Bootstrap modal title
    },
    error: function (jqXHR, textStatus, errorThrown) {
        Swal.fire(textStatus, errorThrown, 'error');
    }
});
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
              $('div#fromParticular').removeClass('col-md-6');
              $('div#toParticular').removeClass('col-md-6');
              $('div#fromParticular').addClass('col-md-4');
              $('div#toParticular').addClass('col-md-4');
              $('div#clientID').show();
          }
          if (data.disbursement_id != null) {
              $("div#disbursementData").show(300);
          }
          if (data.application_id != null) {
              $("div#applicationData").show(300);
          }
          $('[name="id"]').val(data.id);
          $('[name="vparticular_id"]').val(data.particular_name);
          $('[name="vpayment_id"]').val(data.payment);
          $('[name="vstaff_id"]').val(data.staff_name);
          $('[name="vclient_id"]').val(data.name);
          $('[name="vdisbursement_id"]').val(data.disbursement_code);
          $('[name="vclass"]').val(data.class);
          $('[name="vapplication_id"]').val(data.application_code);
          $('[name="vapplication_status"]').val(data.status);
          $('[name="entry_menu"]').val(data.entry_menu);
          $('[name="vref_id"]').val(data.ref_id);
          $('textarea#viewSummernote').summernote('code', data.entry_details);
          $('[name="vmodule"]').val(data.module);
          $('[name="vamount"]').val(total_amount);
          $('[name="vcontact"]').val(data.contact);
          $('[name="vstatus"]').val(data.status);
          $('[name="vbalance"]').val(data.balance);
          $('[name="vremarks"]').val(data.remarks);
          $('[name="created_at"]').val(data.created_at);
          $('[name="updated_at"]').val(data.updated_at);
          $('#view_modal').modal('show');
          $('.modal-title').text('View transaction ' + data.ref_id);
      },
      error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, 'error');
      }
  });
}

// save
function save_transaction() {
  tID = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#" + transaction + "Btn").text("Submitting..."); //change button text
  $("#" + transaction + "Btn").attr("disabled", true); //set button disable
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
          $("#modal_form").modal("hide");
          Swal.fire("Success!", data.messages, "success");
          $("newSummernote").summernote("reset");
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
        data: {
          menu: menu,
          title: title,
        },
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
function bulk_deleteTransaction() {
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
          " " +
          transaction +
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
              menu: menu,
              title: title,
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

// client with pending disbursements && thier disbursements
function pendingDisbursements(clientId = null, disbursementId = null) {
  var clientsSelect = $("select#client_id");
  var disbursementsSelect = $("select#disbursement_id");
  clientsSelect.trigger("change");
  disbursementsSelect.trigger("change");
  // clients
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/admin/loans/pendingDisbursements-clients",
    success: function (data) {
      if (data.length > 0) {
        // if(data.length > 1){
        clientsSelect.html('<option value="">-- select --</option>');
        // }
        $.each(data, function (index, item) {
          var option = $("<option>", {
            value: item.id,
            text: item.name,
          });
          if (item.id == clientId) {
            option.attr("selected", true);
          }
          clientsSelect.append(option);
        });
      } else {
        disbursementsSelect.html(
          '<option value="">No Disbursement Found</option>'
        );
      }
    },
    error: function (err) {
      clientsSelect.html('<option value="">Error Occured</option>');
    },
  });

  // client pending disbursements
  clientsSelect.on("change", function () {
    client_ID = this.value;
    if (client_ID) {
      disbursementsSelect.trigger("change");
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/admin/loans/pending-disbursements/" + client_ID,
        success: function (response) {
          if (response.length > 0) {
            disbursementsSelect.empty(); // Clear existing options
            if (response.length > 1) {
              disbursementsSelect.html(
                '<option value="">-- select --</option>'
              );
            }
            $.each(response, function (index, item) {
              var option = $("<option>", {
                value: item.id,
                text: item.disbursement_code,
              });
              if (item.id == disbursementId) {
                option.attr("selected", true);
              }
              disbursementsSelect.append(option);
              $('[name="product_name"]').val(item.product_name);
              $('[name="class"]').val(item.class);
              $('[name="amount"]').val(item.actual_installment);
              // select particular
              accountType_items(account_typeId, item.particular_id);
            });
          } else {
            disbursementsSelect.html(
              '<option value="">No Disbursement Found</option>'
            );
          }
        },
        error: function (err) {
          disbursementsSelect.html('<option value="">Error Occured</option>');
        },
      });
    } else {
      disbursementsSelect.empty(); // Clear existing options
      $('[name="product_name"]').val("");
      $('[name="class"]').val("");
      $('[name="amount"]').val("");
    }
  });

  // pending disbursement data
  disbursementsSelect.on("change", function () {
    disbursement_ID = this.value;
    if (disbursement_ID) {
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/admin/loans/disbursement/" + disbursement_ID,
        success: function (disbursement) {
          $('[name="product_name"]').val(disbursement.product_name);
          $('[name="class"]').val(disbursement.class);
          $('[name="amount"]').val(disbursement.actual_installment);
        },
        error: function (err) {
          disbursementsSelect.html('<option value="">Error Occurred</option>');
        },
      });
    } else {
      $('[name="product_name"]').val("");
      $('[name="class"]').val("");
      $('[name="amount"]').val("");
    }
  });
}
// client with pending applications && thier applications
function pendingApplications(clientId = null, applicationId = null) {
  var clientsSelect = $("select#client_id");
  var applicationsSelect = $("select#application_id");
  clientsSelect.trigger("change");
  applicationsSelect.trigger("change");
  // clients
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/admin/loans/pendingApplications-clients",
    success: function (data) {
      if (data.length > 0) {
        // if(data.length > 1){
        clientsSelect.html('<option value="">-- select --</option>');
        // }
        $.each(data, function (index, item) {
          var option = $("<option>", {
            value: item.id,
            text: item.name,
          });
          if (item.id == clientId) {
            option.attr("selected", true);
          }
          clientsSelect.append(option);
        });
      } else {
        applicationsSelect.html(
          '<option value="">No Applications Found</option>'
        );
      }
    },
    error: function (err) {
      clientsSelect.html('<option value="">Error Occured</option>');
    },
  });

  // pending applications
  clientsSelect.on("change", function () {
    client_ID = this.value;
    if (client_ID) {
      applicationsSelect.trigger("change");
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/admin/loans/pending-applications/" + client_ID,
        success: function (response) {
          if (response.length > 0) {
            applicationsSelect.empty(); // Clear existing options
            if (response.length > 1) {
              applicationsSelect.html('<option value="">-- select --</option>');
            }
            $.each(response, function (index, item) {
              var option = $("<option>", {
                value: item.id,
                text: item.application_code,
              });
              if (item.id == applicationId) {
                option.attr("selected", true);
              }
              applicationsSelect.append(option);
              $('[name="product_name"]').val(item.product_name);
              $('[name="status"]').val(item.status);
              // select particular
              accountType_items(account_typeId);
              // show charge
              particularCharge(item.principal);
            });
          } else {
            applicationsSelect.html(
              '<option value="">No Applications Found</option>'
            );
          }
        },
        error: function (err) {
          applicationsSelect.html('<option value="">Error Occured</option>');
        },
      });
    } else {
      applicationsSelect.empty(); // Clear existing options
      $('[name="product_name"]').val("");
      $('[name="status"]').val("");
      $('[name="charge"]').val("");
      $('[name="amount"]').val("");
    }
  });

  // pending application data
  applicationsSelect.on("change", function () {
    application_ID = this.value;
    if (application_ID) {
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/admin/loans/application/" + application_ID,
        success: function (application) {
          $('[name="product_name"]').val(application.product_name);
          $('[name="status"]').val(application.status);
          // show charge
          particularCharge(item.principal);
        },
        error: function (err) {
          applicationsSelect.html('<option value="">Error Occurred</option>');
        },
      });
    } else {
      $('[name="product_name"]').val("");
      $('[name="status"]').val("");
      $('[name="charge"]').val("");
      $('[name="amount"]').val("");
    }
  });
}

function particularCharge(amount = null) {
  $("select#particular_id").on("change", function () {
    var particular_id = this.value;
    console.log(particular_id);
    if (particular_id) {
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
// loan account type particulars and entry/transaction types
function accountType_items(
  account_typeId,
  particularID = null,
  entry_typeId = null
) {
  var particularSelect = $("select#particular_id");
  var entry_typeSelect = $("select#entry_typeId");
  if (!account_typeId) {
    particularSelect.html('<option value="">Choose Account Type</option>');
    entry_typeSelect.html('<option value="">Choose Account Type</option>');
  } else {
    // select account type particulars
    $.ajax({
      url: "/admin/accounts/accountType-particulars/" + account_typeId,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        if (data.length > 0) {
          if (data.length > 1) {
            particularSelect.html('<option value="">-- select --</option>');
          }
          $.each(data, function (index, item) {
            // filter out only the partictulars that aren't deducted automatically
            if (item.charge_mode != "Auto") {
              var option = $("<option>", {
                value: item.id,
                text: item.particular_name,
              });
              if (item.id == particularID) {
                option.attr("selected", true);
              }
            }
            particularSelect.append(option);
            if (item.charged == "Yes") {
              particularCharge();
            }
          });
        } else {
          particularSelect.html('<option value="">No Particular</option>');
        }
      },
      error: function (err) {
        particularSelect.html('<option value="">Error Occured</option>');
      },
    });
    // select account type entry\transaction types
    $.ajax({
      url: "/admin/transactions/transaction-types/" + account_typeId,
      type: "POST",
      dataType: "JSON",
      data: { transaction_menu: transaction },
      success: function (response) {
        if (response.length > 0) {
          if (response.length > 1) {
            entry_typeSelect.html('<option value="">-- select --</option>');
          }
          $.each(response, function (index, item) {
            var option = $("<option>", {
              value: item.id,
              text: item.type,
            });
            if (item.id == entry_typeId) {
              option.attr("selected", true);
            }
            entry_typeSelect.append(option);
            $('[name="entry_menu"]').val(item.entry_menu);
          });
        } else {
          entry_typeSelect.html('<option value="">No Type</option>');
        }
      },
      error: function (err) {
        entry_typeSelect.html('<option value="">Error Occured</option>');
      },
    });
  }
}

// load all account types
function load_accountTypes(accountType_id = null) {
  //  get DOM elements
  var crParticularSelect = $("select#credit_accountType"); // particular from[Loosing]
  var drParticularSelect = $("select#debit_accountType"); // particular to[Gaining]

  // Clear existing options
  drParticularSelect.html("");
  crParticularSelect.html("");

  // Add default option
  crParticularSelect.append('<option value="">-- select --</option>');
  drParticularSelect.append('<option value="">-- select --</option>');
  // investment accounts
  $.ajax({
    url: "/admin/accounts/category-account-types/" + equityID +'/'+ null,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length > 0) {
        $.each(data, function (index, item) {
          var crOption = $("<option>", {
            value: item.id,
            text: item.name,
          });
          var drOption = $("<option>", {
            value: item.id,
            text: item.name,
          });

          if (item.id == accountType_id) {
            crOption.attr("selected", "selected");
          }
          if (item.id == accountType_id) {
            drOption.attr("selected", "selected");
          }

          crParticularSelect.append(crOption);
        });
      } else {
        crParticularSelect.html('<option value="">No Account Type</option>');
      }
    },
    error: function (err) {
      crParticularSelect.html('<option value="">Error Occurred</option>');
    },
  });

//   invested accounts
  $.ajax({
    url: "/admin/accounts/category-account-types/" + assetsID +'/investmet',
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length > 0) {
        $.each(data, function (index, item) {
          var crOption = $("<option>", {
            value: item.id,
            text: item.name,
          });
          var drOption = $("<option>", {
            value: item.id,
            text: item.name,
          });

          if (item.id == accountType_id) {
            crOption.attr("selected", "selected");
          }
          if (item.id == accountType_id) {
            drOption.attr("selected", "selected");
          }

          drParticularSelect.append(drOption);
        });
      } else {
        drParticularSelect.html('<option value="">No Account Type</option>');
      }
    },
    error: function (err) {
      drParticularSelect.html('<option value="">Error Occurred</option>');
    },
  });
}

// get credit account type particulars
$("select#credit_accountType").change(function () {
  crParticularSelect = $("select#crParticular_id");
  var crAccount_type = $("#credit_accountType").val();
  crParticularSelect.html('<option value="">-- select --</option>');
  if (crAccount_type != "" || crAccount_type == 0) {
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "/admin/accounts/accountType-particulars/" + crAccount_type,
      success: function (data) {
        if (data.length > 0) {
          $.each(data, function (index, item) {
            var option = $("<option>", {
              value: item.id,
              text: item.particular_name,
            });

            crParticularSelect.append(option);
          });
        } else {
          crParticularSelect.html('<option value="">No Particular</option>');
        }
      },
      error: function (err) {
        crParticularSelect.html('<option value="">Choose Acount Type</option>');
      },
    });
  } else {
    $("select#crParticular_id").html(
      '<option value="">Choose Acount Type</option>'
    );
  }
});
function load_creditParticular(account_typeId, particular_id) {
  var crParticularSelect = $("#crParticular_id");
  crParticularSelect.html('<option value="">-- select --</option>');
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/admin/accounts/accountType-particulars/" + account_typeId,
    success: function (data) {
      if (data.length > 0) {
        $.each(data, function (index, item) {
          var option = $("<option>", {
            value: item.id,
            text: item.particular_name,
          });
          if (item.id == particular_id) {
            option.attr("selected", "selected");
          }

          crParticularSelect.append(option);
        });
      } else {
        crParticularSelect.html('<option value="">No Particular</option>');
      }
    },
    error: function (err) {
      crParticularSelect.html('<option value="">Choose Acount Type</option>');
    },
  });
}
// get debit account type particulars
$("select#debit_accountType").change(function () {
  var drAccount_type = $("#debit_accountType").val();
  if (drAccount_type != "" || drAccount_type == 0) {
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "/admin/accounts/accountType-particulars/" + drAccount_type,
      success: function (data) {
        $("select#drParticular_id").html(
          '<option value="">-- select --</option>'
        );
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].particular_name)
              .appendTo($("select#drParticular_id"));
          }
        } else {
          $("select#drParticular_id").html(
            '<option value="">No Particular</option>'
          );
        }
      },
      error: function (err) {
        $("select#drParticular_id").html(
          '<option value="">Choose Acount Type</option>'
        );
      },
    });
  } else {
    $("select#drParticular_id").html(
      '<option value="">Choose Acount Type</option>'
    );
  }
});
function load_debitParticular(account_typeId, particular_id) {
  var drParticularSelect = $("#drParticular_id");
  drParticularSelect.html('<option value="">-- select --</option>');
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/admin/accounts/accountType-particulars/" + account_typeId,
    success: function (data) {
      if (data.length > 0) {
        $.each(data, function (index, item) {
          var option = $("<option>", {
            value: item.id,
            text: item.particular_name,
          });
          if (item.id == particular_id) {
            option.attr("selected", "selected");
          }

          drParticularSelect.append(option);
        });
      } else {
        drParticularSelect.html('<option value="">No Particular</option>');
      }
    },
    error: function (err) {
      drParticularSelect.html('<option value="">Choose Acount Type</option>');
    },
  });
}
