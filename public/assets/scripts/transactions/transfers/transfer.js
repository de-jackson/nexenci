var save_method;
var tableId = "transfers";
var entry_typeId = 1; // Id for transfers transaction type
// dataTables url
var tableDataUrl = "/admin/transactions/generate-transactions/transfer";
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "date" },
  { data: "crParticular_name" },
  { data: "drParticular_name" },
  { data: "type" },
  { data: "amount", render: $.fn.DataTable.render.number(",") },
  { data: "ref_id" },
  { data: "staff_name" },
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
      bulk_deleteTransfers();
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
  // load all account types
  load_accountTypes();
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
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#modal_form").modal("show"); // show bootstrap modal
  $(".modal-title").text("Add Transaction"); // Set Title to Bootstrap modal title
  $('[name="id"]').val(0);
  $('[name="entry_typeId"]').val(entry_typeId);
  $('[name="entry_menu"]').val("transfer");
  $("select#credit_accountType").trigger("change");
  $("select#crParticular_id").trigger("change");
  $("select#debit_accountType").trigger("change");
  $("select#drParticular_id").trigger("change");
  $("textarea#summernote").summernote("reset");
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
        $("div#fromParticular").removeClass("col-md-6");
        $("div#toParticular").removeClass("col-md-6");
        $("div#fromParticular").addClass("col-md-4");
        $("div#toParticular").addClass("col-md-4");
        $("div#clientID").show();
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
      $("textarea#viewSummernote").summernote("code", data.entry_details);
      $('[name="vmodule"]').val(data.module);
      $('[name="vamount"]').val(total_amount);
      $('[name="vcontact"]').val(data.contact);
      $('[name="vstatus"]').val(data.status);
      $('[name="vbalance"]').val(data.balance);
      $('[name="vremarks"]').val(data.remarks);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $("#view_modal").modal("show");
      $(".modal-title").text("View transaction " + data.ref_id);
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
      $('[name="crParticular_id"]').val(data.particular_id);
      $('[name="drParticular_id"]').val(data.payment_id);
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
      load_accountTypes(data.account_typeId); // select account type
      load_creditParticular(data.account_typeId, data.particular_id); // select credit particular
      load_debitParticular(data.account_typeId, data.payment_id); // select debit particular
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
  $("#btnSav").text("Submitting..."); //change button text
  $("#btnSav").attr("disabled", true); //set button disable
  var url, method;
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
function bulk_deleteTransfers() {
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

  $.ajax({
    url: "/admin/accounts/category-account-types/" + null + "/" + null,
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
          drParticularSelect.append(drOption);
        });
      } else {
        crParticularSelect.html('<option value="">No Account Type</option>');
        drParticularSelect.html('<option value="">No Account Type</option>');
      }
    },
    error: function (err) {
      crParticularSelect.html('<option value="">Error Occurred</option>');
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
