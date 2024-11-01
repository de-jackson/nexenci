var save_method;
var tableId = title.toLowerCase() + "Table";

// dataTables url
var tableDataUrl =
  "/admin/transactions/generate-transactions/" + transaction + "/" + part;
// Define column configurations for each transaction type
var columnsConfig = {
  savings: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "name" },
    { data: "type" },
    // { data: "particular_name" },
    { data: "amount", render: $.fn.DataTable.render.number(",") },
    { data: "payment_method" },
    { data: "status" },
    { data: "ref_id" },
    { data: "date" },
    { data: "staff_name" },
    { data: "action", orderable: false, searchable: false },
  ],
  repayments: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "name" },
    { data: "disbursement_code" },
    { data: "type" },
    // { data: "particular_name" },
    { data: "amount", render: $.fn.DataTable.render.number(",") },
    { data: "payment_method" },
    { data: "ref_id" },
    { data: "date" },
    { data: "staff_name" },
    { data: "action", orderable: false, searchable: false },
  ],
  applicationcharges: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "name" },
    { data: "application_code" },
    { data: "type" },
    // { data: "particular_name" },
    { data: "amount", render: $.fn.DataTable.render.number(",") },
    { data: "payment_method" },
    { data: "ref_id" },
    { data: "date" },
    { data: "staff_name" },
    { data: "action", orderable: false, searchable: false },
  ],
  membership: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "name" },
    { data: "type" },
    // { data: "particular_name" },
    { data: "amount", render: $.fn.DataTable.render.number(",") },
    { data: "payment_method" },
    { data: "status" },
    { data: "ref_id" },
    { data: "date" },
    { data: "staff_name" },
    { data: "action", orderable: false, searchable: false },
  ],
  shares: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "name" },
    { data: "type" },
    // { data: "particular_name" },
    { data: "amount", render: $.fn.DataTable.render.number(",") },
    { data: "payment_method" },
    { data: "shares" },
    { data: "ref_id" },
    { data: "date" },
    { data: "staff_name" },
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
      add_transaction();
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
    className: "btn btn-sm btn-info import" + titleSlug,
    attr: {
      id: "import" + titleSlug,
    },
    titleAttr: "Import " + title,
    action: function () {
      import_transactions();
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
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
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
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
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
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-secondary",
      titleAttr: "Copy " + title + " Information",
      text: '<i class="fas fa-copy"></i>',
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
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
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
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
    columnsConfig[transaction],
    buttonsConfig
  );
  // load form resources
  if (transaction == "repayments") {
    pendingDisbursements();
  } else if (transaction == "applicationcharges") {
    pendingApplications();
  } else if (transaction == "shares" && part == "debit") {
    clientsWithShares();
  } else {
    selectClient();
  }
  // payment methods
  selectPaymentMethod();
  // reset form on modal close event
  $("#" + transaction + "_modalForm").on("hidden.bs.modal", function (e) {
    $("#client_id").val(null).trigger("change");
    $("#product_id").val(null).trigger("change");
  });
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
  $("#" + title.toLowerCase() + "Form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $('[name="id"]').val(0);
  $('[name="account_typeId"]').val(account_typeId);
  $('[name="mode"]').val("create");
  $('[name="entry_menu"]').val("financing");
  $("select#entry_typeId").trigger("change");
  $('[name="client_id"]').trigger("change");
  $("select#payment_id").trigger("change");
  $("select#product_id").trigger("change");
  $("textarea#summernote").summernote("reset");
  $("#importRow").hide();
  $("#formRow").show();
  $(".modal-title").text(
    "Add " + capitalizeFirstLetter(title) + " Transaction"
  ); // Set Title to Bootstrap modal title
  $("#" + title.toLowerCase() + "_modalForm").modal("show"); // show bootstrap modal
}
function import_transactions() {
  save_method = "add";
  $("#" + title.toLowerCase() + "Form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $('[name="id"]').val(0);
  $('[name="account_typeId"]').val(account_typeId);
  $('[name="mode"]').val("import");
  $('[name="entry_menu"]').val("financing");
  $("#importRow").show();
  $("select#branchID").trigger("change");
  $("#formRow").hide();
  $("#export").hide();
  $(".modal-title").text(
    "Import " + capitalizeFirstLetter(title) + " Transactions"
  );
  $("#" + title.toLowerCase() + "_modalForm").modal("show"); // show bootstrap modal
}
// pop edit model
function edit_transaction(id, total_amount) {
  save_method = "update";
  $("#importRow").hide();
  $("#formRow").show();
  $("#export").hide();
  $("#" + title.toLowerCase() + "Form")[0].reset(); // reset form on modals
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
      $('[name="productID"]').val(data.product_id);
      $('[name="date"]').val(data.date);
      $('[name="account_typeId"]').val(data.account_typeId);
      $('[name="entry_typeId"]').val(data.entry_typeId);
      $('[name="product_id"]').val(data.product_id);
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
      // select already selected data
      selectClient(data.client_id);
      accountType_items(account_typeId, data.particular_id, data.entry_typeId);
      $(".modal-title").text(
        "Update " + capitalizeFirstLetter(title) + " Transaction"
      ); // Set title to Bootstrap modal title
      $("#" + title.toLowerCase() + "_modalForm").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
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
      $("#view_" + title.toLowerCase() + "Modal").modal("show");
      $(".modal-title").text("View " + data.ref_id + " transaction");
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
  $("#" + title.toLowerCase() + "Btn").text("Submitting..."); //change button text
  $("#" + title.toLowerCase() + "Btn").attr("disabled", true); //set button disable
  var url;
  if (save_method == "add") {
    url = "/admin/transactions/transaction";
  } else {
    url = "/admin/transactions/edit-transaction/" + tID;
  }

  var formData = new FormData($("#" + title.toLowerCase() + "Form")[0]);
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
          $("#" + title.toLowerCase() + "_modalForm").modal("hide");
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
      $("#" + title.toLowerCase() + "Btn").text("Submit"); //change button text
      $("#" + title.toLowerCase() + "Btn").attr("disabled", false); //set button enable
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#" + title.toLowerCase() + "Btn").text("Submit"); //change button text
      $("#" + title.toLowerCase() + "Btn").attr("disabled", false); //set button enable
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
  var clientDisbursementsData = {}; // Object to store DisbursementsData for the selected client
  clientsSelect.trigger("change");
  disbursementsSelect.trigger("change");
  console.log('Disbursements');
  
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
            text: item.name + "(" + item.account_no + ")",
          });
          if (item.id == clientId) {
            option.attr("selected", true);
          }
          clientsSelect.append(option);
        });
      } else {
        clientsSelect.html(
          '<option value="">No Clients Withs Disbursement</option>'
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
            clientDisbursementsData[client_ID] = response; // Store the response data
            disbursementsSelect.empty(); // Clear existing options
            // if (response.length > 1) {
            disbursementsSelect.html('<option value="">-- select --</option>');
            // }
            $.each(response, function (index, item) {
              var option = $("<option>", {
                value: item.id,
                text: item.disbursement_code + "(" + item.product_name + ")",
              });
              if (item.id == disbursementId) {
                option.attr("selected", true);
              }
              disbursementsSelect.append(option);
              $('[name="principal_taken"]').val(item.principal);
              $('[name="particular_id"]').val(item.particular_id);
              $('[name="product_name"]').val(item.product_name);
              $('[name="class"]').val(item.class);
              $('[name="amount"]').val(
                Number(item.actual_installment).toLocaleString()
              );
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
      $('[name="particular_id"]').val("");
      $('[name="product_name"]').val("");
      $('[name="class"]').val("");
      $('[name="amount"]').val("");
    }
  });

  // Load application data without making another server call
  disbursementsSelect.on("change", function () {
    disbursement_ID = this.value;

    if (disbursement_ID) {
      // Use the stored clientDisbursementsData to get the data for the selected application
      var selectedClientDisbursements = clientDisbursementsData[client_ID];
      if (selectedClientDisbursements) {
        var selectedDisbursement = selectedClientDisbursements.find(
          (item) => item.id == disbursement_ID
        );
        if (selectedDisbursement) {
          $('[name="principal_taken"]').val(
            selectedDisbursement.principal.toLocaleString()
          );
          $('[name="particular_id"]').val(selectedDisbursement.particular_id);
          $('[name="product_name"]').val(selectedDisbursement.product_name);
          $('[name="class"]').val(selectedDisbursement.class);
          $('[name="amount"]').val(
            Number(selectedDisbursement.actual_installment).toLocaleString()
          );
          accountType_items(
            account_typeId,
            selectedDisbursement.particular_id
          );
        } else {
          $('[name="principal_taken"]').val("");
          $('[name="particular_id"]').val("");
          $('[name="product_name"]').val("");
          $('[name="class"]').val("");
          $('[name="amount"]').val("");
        }
      } else {
        applicationsSelect.html('<option value="">Error Occurred</option>');
      }
    } else {
      $('[name="principal_taken"]').val("");
      $('[name="particular_id"]').val("");
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
  var clientApplicationsData = {}; // Object to store ApplicationsData for the selected client
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
            text: item.name + "(" + item.account_no + ")",
          });
          if (item.id == clientId) {
            option.attr("selected", true);
          }
          clientsSelect.append(option);
        });
      } else {
        clientsSelect.html(
          '<option value="">No Clients With Applications</option>'
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
            clientApplicationsData[client_ID] = response; // Store the response data
            applicationsSelect.empty(); // Clear existing options
            // if (response.length > 1) {
            applicationsSelect.html('<option value="">-- select --</option>');
            // }
            $.each(response, function (index, item) {
              var option = $("<option>", {
                value: item.id,
                text: item.application_code + "(" + item.product_name + ")",
              });
              if (item.id == applicationId) {
                option.attr("selected", true);
              }
              applicationsSelect.append(option);
              // $('[name="product_name"]').val(item.product_name);
              // $('[name="status"]').val(item.status);
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

  // Load application data without making another server call
  applicationsSelect.on("change", function () {
    application_ID = this.value;

    if (application_ID) {
      // Use the stored clientApplicationsData to get the data for the selected application
      var selectedClientApplications = clientApplicationsData[client_ID];
      if (selectedClientApplications) {
        var selectedApplication = selectedClientApplications.find(
          (item) => item.id == application_ID
        );
        if (selectedApplication) {
          $('[name="product_name"]').val(selectedApplication.product_name);
          $('[name="status"]').val(selectedApplication.status);
          // show charge
          particularCharge(selectedApplication.principal);
        } else {
          $('[name="product_name"]').val("");
          $('[name="status"]').val("");
          $('[name="charge"]').val("");
          $('[name="amount"]').val("");
        }
      } else {
        applicationsSelect.html('<option value="">Error Occurred</option>');
      }
    } else {
      $('[name="product_name"]').val("");
      $('[name="status"]').val("");
      $('[name="charge"]').val("");
      $('[name="amount"]').val("");
    }
  });
}
// client with shares purchase && thier shares particulars
function clientsWithShares(clientId = null, particularId = null) {
  var clientsSelect = $("select#client_id");
  var sharesParticularSelect = $("select#particular_id");
  var clientSharesData = {}; // Object to store the shares data for the selected client
  clientsSelect.trigger("change");
  sharesParticularSelect.trigger("change");
  select_transactionType(account_typeId);
  // clients
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/admin/shares/shares-clients",
    success: function (data) {
      if (data.length > 0) {
        // if(data.length > 1){
        clientsSelect.html('<option value="">-- select --</option>');
        // }
        $.each(data, function (index, item) {
          var option = $("<option>", {
            value: item.id,
            text: item.name + "(" + item.account_no + ")",
          });
          if (item.id == clientId) {
            option.attr("selected", true);
          }
          clientsSelect.append(option);
        });
      } else {
        clientsSelect.html('<option value="">No Clients With Shares</option>');
      }
    },
    error: function (err) {
      clientsSelect.html('<option value="">Error Occured</option>');
    },
  });

  // Load client shares particulars on client change
  clientsSelect.on("change", function () {
    clientID = this.value;
    if (clientID) {
      sharesParticularSelect.trigger("change");
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/admin/shares/client-shares/" + clientID,
        success: function (response) {
          if (response.length > 0) {
            clientSharesData[clientID] = response; // Store the response data
            sharesParticularSelect.empty(); // Clear existing options
            $('[name="shares_balance"]').val("");
            $('[name="amount"]').val("");
            // if (response.length > 1) {
            sharesParticularSelect.html(
              '<option value="">-- select --</option>'
            );
            // }
            $.each(response, function (index, item) {
              var option = $("<option>", {
                value: item.id,
                text: item.particular_code + " - " + item.particular_name,
              });
              if (item.id == particularId) {
                option.attr("selected", true);
              }
              sharesParticularSelect.append(option);
            });
          } else {
            sharesParticularSelect.html(
              '<option value="">No Shares Particulars</option>'
            );
          }
        },
        error: function (err) {
          sharesParticularSelect.html(
            '<option value="">Error Occured</option>'
          );
        },
      });
    } else {
      sharesParticularSelect.empty(); // Clear existing options
      $('[name="shares_balance"]').val("");
      $('[name="amount"]').val("");
    }
  });

  // Load particular data without making another server call
  sharesParticularSelect.on("change", function () {
    particularID = this.value;

    if (particularID) {
      // Use the stored clientSharesData to get the data for the selected particular
      var selectedClientShares = clientSharesData[clientID];
      if (selectedClientShares) {
        var selectedParticular = selectedClientShares.find(
          (item) => item.id == particularID
        );
        if (selectedParticular) {
          $('[name="shares_balance"]').val(
            selectedParticular.clientSharesBalanceUnits +
              " Units ~ " +
              selectedParticular.clientSharesBalance.toLocaleString()
          );
        } else {
          $('[name="shares_balance"]').val("");
          $('[name="amount"]').val("");
        }
      }
    } else {
      $('[name="shares_balance"]').val("");
      $('[name="amount"]').val("");
    }
  });
}

function particularCharge(amount = null) {
  $("select#particular_id").on("change", function () {
    var particular_id = this.value;
    var chargeSelect = $("select#particular_charge");
    var registration_date = $('[name="registration_date"]').val();
    var charge_type = $('[name="charge_type"]').val();
    if (particular_id) {
      $.ajax({
        url: "/admin/settings/client/charges",
        data: {
          particular_id: particular_id,
          reg_date: registration_date,
          chargeType: charge_type,
        },
        type: "post",
        dataType: "JSON",
        success: function (data) {
          if (data.length > 0) {
            chargeSelect.empty();
            $.each(data, function (index, item) {
              var charge_method = item.charge_method;
              var charge = item.charge;
              var charge_mode = item.charge_mode;
              var charge_frequency = item.charge_frequency;
              var effective_date = item.effective_date;
              var charge_status = item.charge_status;

              if (charge_method.toLowerCase() == "amount") {
                var chargeFee = Number(item.charge).toLocaleString();
              } else {
                var chargeFee = charge + "%";
              }
              var option = $("<option>", {
                value: item.charge,
                text: chargeFee,
              });
              chargeSelect.append(option);
            });
            // getParticularCharges(data.particular_active_charges)
          } else {
            $("select#particular_charge").html(
              '<option value="">-- No Charge Set --</option>'
            );
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    } else {
      $("select#particular_charge").html(
        '<option value="">-- select --</option>'
      );
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
  var chargeSelect = $("select#particular_charge");
  var entry_typeSelect = $("select#entry_typeId");
  if (!account_typeId) {
    particularSelect.html('<option value="">Choose Account Type</option>');
    entry_typeSelect.html('<option value="">Choose Account Type</option>');
  } else {
    // select account type particulars
    selectParticulars(account_typeId, particularID);

    // select account type entry\transaction types
    select_transactionType(account_typeId, entry_typeId);
  }
}

// load selected particular data
$("select#particular_id").on("change", function () {
  var particular_id = this.value;
  var registration_date = $('[name="registration_date"]').val();
  var charge_type = $('[name="charge_type"]').val();
  var chargeSelect = $("select#particular_charge");
  if (particular_id == "" || particular_id == 0) {
    chargeSelect.html('<option value="">-- select --</option>');
    $('[name="shares_units"]').val("");
    $('[name="amount"]').val("");
  } else {
    $.ajax({
      url: "/admin/settings/client/charges",
      data: {
        particular_id: particular_id,
        reg_date: registration_date,
        chargeType: charge_type,
      },
      type: "POST",
      dataType: "JSON",
      success: function (response) {
        if (response.length > 0) {
          chargeSelect.empty();
          $('[name="shares_units"]').val("");
          $('[name="amount"]').val("");

          $.each(response, function (index, item) {
            var charge_method = item.charge_method;
            var charge = item.charge;
            var charge_mode = item.charge_mode;
            var charge_frequency = item.frequency;
            var effective_date = item.effective_date;
            var charge_status = item.status;

            if (charge_method.toLowerCase() == "amount") {
              var chargeFee = Number(item.charge).toLocaleString();
            } else {
              var chargeFee = charge + "%";
            }
            var option = $("<option>", {
              value: item.charge,
              text: chargeFee,
            });
            chargeSelect.append(option);
            $("span#particularCharge").text(item.charge_method);
          });
          // getParticularCharges(data.particular_active_charges)
        } else {
          $("select#particular_charge").html(
            '<option value="">-- No Charge Set--</option>'
          );
          $('[name="shares_units"]').val("");
          $('[name="amount"]').val("");
        }
      },
      error: function (err) {
        $("select#particular_charge").html(
          '<option value="">-- select --</option>'
        );
        $('[name="shares_units"]').val("");
        $('[name="amount"]').val("");
      },
    });
  }
});

$("select#particular_charge").on("change", function () {
  var particular_charge = this.value;
  if (particular_charge == "" || particular_charge == 0) {
    $('[name="charge"]').val("");
    $('[name="amount"]').val("");
  } else {
    $('[name="amount"]').val(particular_charge);
  }
});

// load selected client data
$("select#client_id").on("change", function () {
  var client_id = this.value;
  var productID = $('[name="productID"]').val();
  var clientSavingsProducts = {}; // Object to store SavingsProducts for the selected client

  if (client_id == "" || client_id == 0) {
    $('[name="account_no"]').val("");
    $('[name="contact"]').val("");
    $('[name="registration_date"]').val("");
    $("select#particular_id").html('<option value="">-- select --</option>');
    $("select#particular_charge").html(
      '<option value="">-- select --</option>'
    );
    $("select#product_id").html('<option value="">-- select --</option>');
  } else {
    $.ajax({
      url: "/admin/clients/client/" + client_id,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $('[name="account_no"]').val(data.account_no);
        $('[name="contact"]').val(data.mobile);
        // setPhoneNumberWithCountryCode($("#contact"), data.mobile);
        $('[name="registration_date"]').val(data.reg_date);
        $("select#product_id").trigger("change");
        setPhoneNumberWithCountryCode($("#contact"), data.mobile);

        $("select#product_id").empty();
        $("select#product_id").html('<option value="">-- select --</option>');
        if (data.savingsProducts) {
          clientSavingsProducts[client_id] = data.savingsProducts; // Store data.savingsProducts
          // Add options
          $.each(data.savingsProducts, function (index, product) {
            // Append to product_id
            var selection = product.product_id == productID ? "selected" : "";
            $("select#product_id").append(
              '<option value="' +
                product.product_id +
                '" ' +
                selection +
                ">" +
                product.product_name +
                "</option>"
            );

            // Also append to payment_id with a label to indicate savings
            if (account_typeId && account_typeId != 12) {
              $("select#payment_id").append(
                '<option value="' +
                  product.product_code +
                  '">Savings - ' +
                  product.product_name +
                  "</option>"
              );
            }
          });
        } else {
          $("select#product_id").html('<option value="">No Product</option>');
        }

        if ((title.toLowerCase() != "withdrawals") && (transaction != "repayments")) {
          if (account_typeId && account_typeId == 12) {
            $("select#product_id").on("change", function () {
              var productId = $(this).val();
              if (productId) {
                var selectedProduct = clientSavingsProducts[client_id].find(
                  (item) => item.product_id == productId
                );
                accountType_items(
                  account_typeId,
                  selectedProduct.savings_particular_id
                );
              } else {
                $("select#particular_id").empty();
              }
            });
          } else {
            accountType_items(account_typeId);
          }
        }
      },
      error: function (err) {},
    });
  }
});

// total shares units cost
var totalCost = () => {
  var unitCost = Number(removeCommasFromAmount($("#particular_charge").val()));
  var totalUnits = Number(removeCommasFromAmount($("#shares_units").val()));

  var totalCost = Number(unitCost * totalUnits);
  $("#total_cost").val(totalCost.toLocaleString());
};
