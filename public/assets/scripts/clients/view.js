var save_method;
var tableId = "clients";
var account_typeId = 12; // set id for client savings particular
var repayment_accountId = 3; // id for loan repayment particulars
var loansRevenue = 19; // id for revenue from loan particulars
// dataTables column config for each table
var columnsConfig = {
  savings: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "date" },
    { data: "type" },
    { data: "payment_method" },
    { data: "amount", render: $.fn.DataTable.render.number(",") },
    { data: "ref_id" },
    { data: "staff_name" },
    { data: "account_bal", render: $.fn.DataTable.render.number(",") },
    { data: "action", orderable: false, searchable: false },
  ],
  membership: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "particular_name" },
    { data: "payment_method" },
    { data: "type" },
    { data: "amount", render: $.fn.DataTable.render.number(",") },
    { data: "ref_id" },
    { data: "date" },
    // { data: "account_bal", render: $.fn.DataTable.render.number(",") },
    { data: "action", orderable: false, searchable: false },
  ],
};

$(document).ready(function () {
  view_client(clientId);

  selectBranch();
  // load clients
  selectClient();
  // load products
  selectProducts("savings");
  selectProducts("loans");

  $.ajax({
    url: "/admin/branches/get-branches",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("select#branchID").html('<option value="">-- select --</option>');
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          $("<option />")
            .val(data[i].id)
            .text(data[i].branch_name)
            .appendTo($("select#branchID"));
        }
      } else {
        $("select#branchID").html('<option value="">No Branch</option>');
      }
    },
    error: function (err) {
      $("select#branchID").html('<option value="">Error Occured</option>');
    },
  });
  
  // load payment methods
  selectPaymentMethod();
  // load ajax options
  // generateCustomeSettings('interest_period');
  generateCustomeSettings("gender");
  generateCustomeSettings("nationality");
  generateCustomeSettings("maritalstatus");
  generateCustomeSettings("religion");
  generateCustomeSettings("relationships");
  generateCustomeSettings("idtypes");
  generateCustomeSettings("occupation");
  generateCustomSettings("occupation");
  generateCustomeSettings("repayments");
  // reset form on modal close event
  $("#modal_form").on("hidden.bs.modal", function (e) {
    // Clear the selected options from the select box
    $("#savings_products").val(null).trigger("change");
  });
});

// ajax load client transactions
function client_transactions(clientId, name, account_typeId, table) {
  // dataTables buttons config
  var buttons = [];
  buttons.push({
    text: '<i class="fa fa-refresh"></i>',
    className: "btn btn-sm btn-warning",
    titleAttr: "Reload Transactions",
    action: function () {
      reload_table(table);
    },
  });
  // show export buttons
  if (
    userPermissions.includes("export_savingsTransactions") ||
    userPermissions === '"all"'
  ) {
    buttons.push(
      {
        extend: "excel",
        className: "btn btn-sm btn-success",
        titleAttr: "Export To Excel",
        text: '<i class="fas fa-file-excel"></i>',
        filename: name + " Transactions",
        extension: ".xlsx",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8],
        },
      },
      {
        extend: "pdf",
        className: "btn btn-sm btn-danger",
        titleAttr: " Export To PDF",
        text: '<i class="fas fa-file-pdf"></i>',
        filename: name + " Transactions",
        extension: ".pdf",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8],
        },
      },
      {
        extend: "csv",
        className: "btn btn-sm btn-info",
        titleAttr: "Export To CSV",
        text: '<i class="fas fa-file-csv"></i>',
        filename: name + " Transactions",
        extension: ".csv",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8],
        },
      },
      {
        extend: "copy",
        className: "btn btn-sm btn-secondary",
        titleAttr: "Copy " + name + " Transactions",
        text: '<i class="fas fa-copy"></i>',
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8],
        },
      },
      {
        extend: "print",
        title:
          "<h3 class='text-center text-bold'>" +
          businessName +
          "</h3><h4 class='text-center text-bold'>" +
          name +
          " Transactions Information</h4><h5 class='text-center'>Printed On " +
          new Date().getHours() +
          " : " +
          new Date().getMinutes() +
          " " +
          new Date().toDateString() +
          "</h5>",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8],
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

  var url =
    "/admin/transactions/client-transactions/" +
    clientId +
    "/" +
    account_typeId;
  // call to dataTable initialization function
  initializeDataTable(table, url, columnsConfig[table], buttons);
}

// generate new user password
function create_password(id, password) {
  save_method = "password";
  $("#importRow").hide();
  $("#formRow").hide();
  $("#passwordRow").show();
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="mode"]').val("regenerate");
      $('[name="name"]').val(data.name);
      $('[name="phone"]').val(data.mobile);
      setPhoneNumberWithCountryCode($("#phone"), data.mobile);
      $('[name="email"]').val(data.email);
      $('[name="password"]').val(password);
      $('[name="c_password"]').val(password);
      $(".modal-title").text("Generate " + data.name + " New Password");
      $("#modal_form").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// view client info
function view_client(id) {
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    // show spinner as content is loading
    beforeSend: function () {
      $("#contentSPinner").html(
        '<div class="text-center mt-3">' +
          '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>'
      );
    },
    success: function (data) {
      var dob = data.dob;
      $('[name="id"]').val(data.id);
      $('[name="vname"]').val(data.name);
      $('[name="vaccount_no"]').val(data.account_no);
      $('[name="vaccount_type"]').val(data.account_type);
      $('[name="vaccount_balance"]').val(data.account_balance);
      $('[name="vemail"]').val(data.email);
      $('[name="vmobile"]').val(data.mobile);
      $('[name="valternate_no"]').val(data.alternate_no);
      setPhoneNumberWithCountryCode($("#vmobile"), data.mobile);
      setPhoneNumberWithCountryCode($("#valternate_no"), data.alternate_no);
      $('[name="vgender"]').val(data.gender);
      $('[name="vnationality"]').val(data.nationality);
      $('[name="vdob"]').val(data.dob);
      $('[name="view_reg_date"]').val(data.reg_date);
      $('[name="vmarital_status"]').val(data.marital_status);
      $('[name="vreligion"]').val(data.religion);
      $('[name="staff_name"]').val(data.staff_name);
      $('[name="voccupation"]').val(data.occupation);
      $('[name="vjob_location"]').val(data.job_location);
      $('[name="vresidence"]').val(data.closest_landmark);
      $('[name="vclosest_landmark"]').val(data.closest_landmark);
      $('[name="vid_type"]').val(data.id_type);
      $('[name="vid_number"]').val(data.id_number);
      $('[name="vid_expiry"]').val(data.id_expiry_date);
      $('[name="vnext_of_kin"]').val(data.next_of_kin_name);
      $('[name="vnok_relationship"]').val(data.next_of_kin_relationship);
      $('[name="vnok_phone"]').val(data.next_of_kin_contact);
      $('[name="vnok_alt_phone"]').val(data.next_of_kin_alternate_contact);
      setPhoneNumberWithCountryCode($("#vnok_phone"), data.next_of_kin_contact);
      setPhoneNumberWithCountryCode(
        $("#vnok_alt_phone"),
        data.next_of_kin_alternate_contact
      );
      $('[name="vnok_email"]').val(data.nok_email);
      $('[name="vnok_address"]').val(data.nok_address);
      $('[name="vbranch_id"]').val(data.branch_name);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $('[name="age"]').val(data.age);
      // $('[name="age"]').val(new Date().getYear() - new Date(dob).getYear());
      // passport photo
      if (
        data.photo &&
        imageExists("/uploads/clients/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/clients/passports/' +
            data.photo +
            '" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      }
      // id photos
      if (
        data.id_photo_front &&
        imageExists("/uploads/clients/ids/front/" + data.id_photo_front)
      ) {
        $("#id-previewFront div").html(
          '<img src="/uploads/clients/ids/front/' +
            data.id_photo_front +
            '" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      } else {
        $("#id-previewFront div").html(
          '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      }
      if (
        data.id_photo_back &&
        imageExists("/uploads/clients/ids/back/" + data.id_photo_back)
      ) {
        $("#id-previewBack div").html(
          '<img src="/uploads/clients/ids/back/' +
            data.id_photo_back +
            '" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      } else {
        $("#id-previewBack div").html(
          '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      }
      // signiture
      if (
        data.signature &&
        imageExists("/uploads/clients/signatures/" + data.signature)
      ) {
        $("#signature-preview div").html(
          '<img src="/uploads/clients/signatures/' +
            data.signature +
            '" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#signature-preview div").html(
          '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      // load client savings history
      client_transactions(data.id, data.name, account_typeId, "savings");
      // load client membership history
      // client_transactions(data.id, data.name, 24, "membership");
      // load client loan history(application & disbursement)
      clientLoanHistory(clientId);
      // hide spinner after content has loaded
      $("#contentSPinner").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
function view_client_photo(id) {
  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $("#photo-preview").show();
      if (
        data.photo &&
        imageExists("/uploads/clients/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/clients/passports/' +
            data.photo +
            '" class="img-fluid thumbnail">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail">'
        );
      }
      $(".modal-title").text(data.name);
      $("#photo_modal_form").modal("show");
    },

    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// generate new user password
function create_password(id, password) {
  save_method = "password";
  $("#importRow").hide();
  $("#formRow").hide();
  $("#passwordRow").show();
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="mode"]').val("regenerate");
      $('[name="name"]').val(data.name);
      $('[name="phone"]').val(data.mobile);
      setPhoneNumberWithCountryCode($("#phone"), data.mobile);
      $('[name="email"]').val(data.email);
      $('[name="password"]').val(password);
      $('[name="c_password"]').val(password);
      $(".modal-title").text("Generate " + data.name + " New Password");
      $("#modal_form").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// edit client data
function edit_client(id) {
  save_method = "update";
  $("#export").hide();
  $("#form")[0].reset();
  $("#importRow").hide();
  $("#formRow").show();
  $("#passwordRow").hide();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="c_name"]').val(data.name);
      $('[name="account_no"]').val(data.account_no);
      $('[name="account_type"]').val(data.account_type);
      $('[name="c_email"]').val(data.email);
      $('[name="mobile"]').val(data.mobile);
      $('[name="alternate_mobile"]').val(data.alternate_no);
      setPhoneNumberWithCountryCode($("#mobile"), data.mobile);
      setPhoneNumberWithCountryCode($("#alternate_mobile"), data.alternate_no);
      $('[name="dob"]').val(data.dob);
      $('[name="reg_date"]').val(data.reg_date);
      $('[name="staff_id"]').val(data.staff_id);
      $('[name="occupation"]').val(data.occupation).trigger("change");
      $('[name="job_location"]').val(data.job_location);
      $('[name="residence"]').val(data.residence);
      $('[name="closest_landmark"]').val(data.closest_landmark);
      $('[name="id_number"]').val(data.id_number);
      $('[name="id_expiry"]').val(data.id_expiry_date);
      $('[name="next_of_kin"]').val(data.next_of_kin_name);
      $('[name="nok_phone"]').val(data.next_of_kin_contact);
      $('[name="nok_alt_phone"]').val(data.next_of_kin_alternate_contact);
      setPhoneNumberWithCountryCode($("#nok_phone"), data.next_of_kin_contact);
      setPhoneNumberWithCountryCode(
        $("#nok_alt_phone"),
        data.next_of_kin_alternate_contact
      );
      $('[name="nok_email"]').val(data.nok_email);
      $('[name="nok_address"]').val(data.nok_address);
      $('[name="branch_id"]').val(data.branch_id).trigger("change");
      $('[name="gender"]').val(data.gender).trigger("change");
      $('[name="nationality"]').val(data.nationality).trigger("change");
      $('[name="id_type"]').val(data.id_type).trigger("change");
      $('[name="marital_status"]').val(data.marital_status).trigger("change");
      $('[name="religion"]').val(data.religion).trigger("change");
      // if data.savingsProducts is not empty
      if (data.savingsProducts) {
        // loop thru data.savingsProducts
        var selectedProductIds = data.savingsProducts.map(function (product) {
          return product.product_id;
        });

        $("#savings_products").val(selectedProductIds).trigger("change");
      }
      // passport
      $('[name="nok_relationship"]')
        .val(data.next_of_kin_relationship)
        .trigger("change");
      if (
        data.photo &&
        imageExists("/uploads/clients/passports/" + data.photo)
      ) {
        $("#upload-label").text("Update Photo");
        $("#user-photo-preview").html(
          '<img src="/uploads/clients/passports/' +
            data.photo +
            '" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#upload-label").text("Upload Photo");
        $("#user-photo-preview").html(
          '<img src="/assets/dist/img/nophoto.jpg" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      // id photos
      if (
        data.id_photo_front &&
        imageExists("/uploads/clients/ids/front/" + data.id_photo_front)
      ) {
        $("#id-label-front").text("Update Front ID Photo");
        $("#id-preview-front").html(
          '<img src="/uploads/clients/ids/front/' +
            data.id_photo_front +
            '" alt="Image preview" id="preview-idFront" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      } else {
        $("#id-label-front").text("Upload Front ID Photo");
        $("#id-preview-front").html(
          '<img src="/assets/dist/img/id.jpg" alt="Image preview" id="preview-idFront" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      }
      if (
        data.id_photo_back &&
        imageExists("/uploads/clients/ids/back/" + data.id_photo_back)
      ) {
        $("#id-label-back").text("Update Back ID Photo");
        $("#id-preview-back").html(
          '<img src="/uploads/clients/ids/back/' +
            data.id_photo_back +
            '" alt="Image preview" id="preview-idBack" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      } else {
        $("#id-label-back").text("Upload Back ID Photo");
        $("#id-preview-back").html(
          '<img src="/assets/dist/img/id.jpg" alt="Image preview" id="preview-idBack" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      }
      // signiture
      if (
        data.signature &&
        imageExists("/uploads/clients/signatures/" + data.signature)
      ) {
        $("#sign-label2").text("Update Signature");
        $("#signature-preview2").html(
          '<img src="/uploads/clients/signatures/' +
            data.signature +
            '" alt="Image preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#sign-label2").text("Upload Signature");
        $("#signature-preview2").html(
          '<img src="/assets/dist/img/sign.png" alt="Image preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      selectBranch(data.branch_id);
      $(".modal-title").text("Update " + data.name);
      $("#modal_form").modal("show"); // show bootstrap modal when complete loaded
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

function edit_clientStatus(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: name + " access status will be changed!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Update!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/clients/update-clientStatus/" + id,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
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

function save_client() {
  id = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSav").text("submitting...");
  $("#btnSav").attr("disabled", true);
  var url, method;
  if (save_method == "add") {
    url = "/admin/clients/client";
  } else if (save_method == "password") {
    url = "/admin/clients/update-clientStatus/" + id;
  } else {
    url = "/admin/clients/edit-client/" + id;
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
          view_client(clientId);
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

function delete_client(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to recover " + name + "!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/clients/client/" + id,
        type: "DELETE",
        dataType: "JSON",
        success: function (data) {
          if (data.status && data.error == null) {
            Swal.fire("Deleted!", name + " " + data.messages, "success");
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

// hide\show add transaction form
$("button#btnTransaction").click(function () {
  $("div#addTransactionCard").toggle(function (e) {
    if ($(this).is(":visible")) {
      $("div#addTransactionCard").show();
      $("button#btnTransaction").text("Hide Form");
    } else {
      $("div#addTransactionCard").hide();
      $("button#btnTransaction").html(
        '<i class="fas fa-plus"></i> Add Transaction'
      );
    }
  });
});
// pop up model for client savings
function client_savings(id) {
  $("#savingsForm")[0].reset();
  var clientSavingsProducts = {}; // Object to store SavingsProducts for the selected client

  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $(".modal-title").text(data.name);
      $("#savings_modal_form").modal("show");
      transaction_types();
      client_transactions(data.id, data.name);
      $("strong#cName").text(data.name);
      $("strong#cContact").text(data.mobile);
      $("strong#cContact2").text(data.alternate_no);
      $("strong#cEmail").text(data.email);
      $("strong#cAccountNo").text(data.account_no);
      $("strong#cBalance").text(data.account_balance);
      $("strong#cAddress").text(data.residence);
      $("strong#cRegDate").text(data.reg_date);
      // fill transaction form
      $('[name="client_id"]').val(data.id);
      $('[name="account_typeId"]').val(account_typeId);
      $('[name="contact"]').val(data.mobile);
      setPhoneNumberWithCountryCode($("#contact"), data.mobile);
      $("select#entry_typeId").trigger("change");
      $("select#particular_id").trigger("change");
      $("select#payment_id").trigger("change");
      $("select#entry_typeId").trigger("change");
      $("select#product_id").trigger("change");
      $("select#product_id").empty();

      $("select#product_id").empty();
      $("select#product_id").html('<option value="">-- select --</option>');
      if (data.savingsProducts) {
        clientSavingsProducts[id] = data.savingsProducts; // Store data.savingsProducts
        // Add options
        $.each(data.savingsProducts, function (index, product) {
          // Append to product_id
          var selection = product.product_id == product_id ? "selected" : "";
          $("select#product_id").append(
            '<option value="' +
              product.product_id +
              '" ' +
              selection +
              ">" +
              product.product_name +
              "</option>"
          );
        });

        $("select#product_id").on("change", function () {
          var productId = $(this).val();
          if (productId) {
            var selectedProduct = clientSavingsProducts[id].find(
              (item) => item.product_id == productId
            );
            selectParticulars(
              account_typeId,
              selectedProduct.savings_particular_id
            );
          } else {
            $("select#particular_id").empty();
          }
        });
      } else {
        $("select#product_id").html('<option value="">No Product</option>');
      }

      // $("textarea#addSummernote").summernote("reset");
      if (
        data.photo &&
        imageExists("/uploads/clients/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/clients/passports/' +
            data.photo +
            '" class="img-fluid thumbnail" style="height: 100px; width: 100px;">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail" style="height: 50px; width: 50px;">'
        );
      }
      $("div#addTransactionCard").hide(); // hide transaction form
      $("span.help-block").empty(); // remove error messages
      $("button#btnTransaction").html(
        '<i class="fas fa-plus"></i> Add Transaction'
      ); // change text of form button
    },

    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

function save_Savingstransaction() {
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSavings").text("submitting...");
  $("#btnSavings").attr("disabled", true);
  // ajax adding data to database
  var formData = new FormData($("#savingsForm")[0]);
  $.ajax({
    url: "/admin/transactions/transaction",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "JSON",
    success: function (data) {
      if (!data.inputerror) {
        if (data.status && data.error == null) {
          $("#savings_modal_form").modal("hide");
          Swal.fire("Success!", data.messages, "success");
          reload_table("savings");
          // reload_table("membership");
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
      $("#btnSavings").text("Submit"); //change button text
      $("#btnSavings").attr("disabled", false); //set button enable
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnSavings").text("Submit"); //change button text
      $("#btnSavings").attr("disabled", false); //set button enable
    },
  });
}

// pop add application model
function add_application() {
  save_method = "add";
  $("ul#myTab2").show();
  $("button#btnNext").show();
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#formRow").show();
  $("#importRow").hide();
  $('[name="client_id"]').val(clientId);

  $("#photo-preview div").html(
    '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail" style="width: 190px; height: 190px;">'
  );
  $("#id-preview div").html(
    '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
  );
  $("#signature-preview div").html(
    '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
  );
  // reset step tabs
  var step = $('[name="step_no"]').val();
  if (step > 1) {
    // remove active class to current step
    $("button#step" + step + "-tab").removeClass("active");
    $("div#step" + step + "-tab-pane").removeClass("active");
    $("div#step" + step + "-tab-pane").removeClass("show");
    // add active class to first step
    $("button#step1-tab").addClass("active");
    $("div#step1-tab-pane").addClass("active");
    $("div#step1-tab-pane").addClass("show");
  }
  // set form default data
  $('[name="id"]').val(0);
  $('[name="mode"]').val("create");
  $('[name="step_no"]').val("1");
  $("select#client_id").trigger("change");
  $("select#product_id").trigger("change");
  $("select#state").trigger("change");
  $("select#state2").trigger("change");
  $("select#relation").trigger("change");
  $("select#relation2").trigger("change");
  $("textarea#addSummernote").summernote("reset");
  // hide back n submit button
  $("#btnSubApplication").hide();
  $("#btnBack").hide();
  $(".application-modalTitle").text("New Loan Application"); // Set Title to Bootstrap modal title
  $("#application_modalForm").modal("show"); // show bootstrap modal
  setPhoneNumberWithCountryCode($("#ref_contact"), "");
  setPhoneNumberWithCountryCode($("#ref_alt_contact"), "");
  setPhoneNumberWithCountryCode($("#ref_contact2"), "");
  setPhoneNumberWithCountryCode($("#ref_alt_contact2"), "");
}
// pop edit application model
function edit_application(id) {
  save_method = "update";
  $("#export").hide();
  $("#formRow").show();
  $("#importRow").hide();
  // hide submit button
  $("#btnSubApplication").hide();
  $("#btnBack").hide();
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string

  // reset step tabs
  var step = $('[name="step_no"]').val();
  if (step > 1) {
    // remove active class to current step
    $("button#step" + step + "-tab").removeClass("active");
    $("div#step" + step + "-tab-pane").removeClass("active");
    $("div#step" + step + "-tab-pane").removeClass("show");
    // add active class to first step
    $("button#step1-tab").addClass("active");
    $("div#step1-tab-pane").addClass("active");
    $("div#step1-tab-pane").addClass("show");
  }
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/loans/application/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      // disbursements(data.client_id);
      $('[name="step_no"]').val("1");
      $('[name="id"]').val(data.id);
      $('[name="applicant_product_id"]').val(data.applicant_product_id);
      $('[name="application_id"]').val(data.application_id);
      $('[name="application_code"]').val(data.application_code);
      $('[name="application_date"]').val(data.application_date);
      $('[name="interest_rate"]').val(data.interest_rate);
      $('[name="client_id"]').val(data.client_id).trigger("change");
      $('[name="product_id"]').val(data.product_id).trigger("change");
      generateCustomeSettings("interest_period", data.interest_period);
      $('[name="repayment_freq"]')
        .val(data.repayment_frequency)
        .trigger("change");
      $('[name="principal"]').val(data.principal);
      $('[name="purpose"]').val(data.purpose);
      $('[name="purpose"]').val(data.purpose);
      $('[name="repayment_period"]').val(data.repayment_period);
      selectClient(data.client_id);
      // load loan products
      selectProducts("loans", data.product_id);
      $('[name="status"]').val(data.status);
      $('[name="reduct_charges"]').val(data.reduct_charges);
      total_applicationCharges(data.principal);

      setPhoneNumberWithCountryCode($("#ref_contact"), data.ref_contact);
      setPhoneNumberWithCountryCode(
        $("#ref_alt_contact"),
        data.ref_alt_contact
      );
      setPhoneNumberWithCountryCode($("#ref_contact2"), data.ref_contact2);
      setPhoneNumberWithCountryCode(
        $("#ref_alt_contact2"),
        data.ref_alt_contact2
      );

      $('[name="security_item"]').val(data.security_item);
      $("textarea#addSummernote").summernote("code", data.security_info);
      $('[name="est_value"]').val(data.est_value);
      $('[name="ref_name"]').val(data.ref_name);
      $('[name="ref_address"]').val(data.ref_address);
      $('[name="ref_job"]').val(data.ref_job);
      $('[name="ref_contact"]').val(data.ref_contact);
      $('[name="ref_alt_contact"]').val(data.ref_alt_contact);
      $('[name="ref_email"]').val(data.ref_email);
      $('[name="ref_relation"]').val(data.ref_relation).trigger("change");
      $('[name="ref_name2"]').val(data.ref_name2);
      $('[name="ref_address2"]').val(data.ref_address2);
      $('[name="ref_job2"]').val(data.ref_job2);
      $('[name="ref_contact2"]').val(data.ref_contact2);
      $('[name="ref_alt_contact2"]').val(data.ref_alt_contact2);
      $('[name="ref_email2"]').val(data.ref_email2);
      $('[name="ref_relation2"]').val(data.ref_relation2).trigger("change");

      $('[name="net_salary"]').val(data.net_salary);
      $('[name="farming"]').val(data.farming);
      $('[name="business"]').val(data.business);
      $('[name="others"]').val(data.others);
      $('[name="income_total"]').val(
        Number(
          parseFloat(data.net_salary) +
            parseFloat(data.farming) +
            parseFloat(data.business) +
            parseFloat(data.others)
        )
      );
      $('[name="rent"]').val(data.rent);
      $('[name="education"]').val(data.education);
      $('[name="medical"]').val(data.medical);
      $('[name="transport"]').val(data.transport);
      $('[name="exp_others"]').val(data.exp_others);
      $('[name="exp_total"]').val(
        Number(
          parseFloat(data.rent) +
            parseFloat(data.education) +
            parseFloat(data.medical) +
            parseFloat(data.transport) +
            parseFloat(data.exp_others)
        )
      );
      $('[name="difference"]').val(data.difference);
      $('[name="dif_status"]').val(data.dif_status);

      $('[name="institute_name"]').val(data.institute_name);
      $('[name="institute_branch"]').val(data.institute_branch);
      $('[name="account_type"]').val(data.account_type);
      $('[name="institute_name2"]').val(data.institute_name2);
      $('[name="institute_branch2"]').val(data.institute_branch2);
      $('[name="account_type2"]').val(data.account_type2);
      $('[name="amt_advance"]').val(data.amt_advance);
      $('[name="date_advance"]').val(data.date_advance);
      $('[name="loan_duration"]').val(data.loan_duration);
      $('[name="amt_outstanding"]').val(data.amt_outstanding);
      $('[name="amt_advance2"]').val(data.amt_advance2);
      $('[name="date_advance2"]').val(data.date_advance2);
      $('[name="loan_duration2"]').val(data.loan_duration2);
      $('[name="amt_outstanding2"]').val(data.amt_outstanding2);

      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      if (
        data.photo &&
        imageExists("/uploads/clients/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/clients/passports/' +
            data.photo +
            '" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
        );
      }
      if (
        data.id_photo_front &&
        imageExists("/uploads/clients/ids/front/" + data.id_photo_front)
      ) {
        $("#id-preview div").html(
          '<img src="/uploads/clients/ids/front/' +
            data.id_photo_front +
            '" class="img-fluid thumbnail"  style="width: 200px; height: 140px;">'
        );
      } else {
        $("#id-preview div").html(
          '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 140px;">'
        );
      }
      if (
        data.signature &&
        imageExists("/uploads/clients/signatures/" + data.signature)
      ) {
        $("#signature-preview div").html(
          '<img src="/uploads/clients/signatures/' +
            data.signature +
            '" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#signature-preview div").html(
          '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      $(".modal-title").text(
        "Update Application: " + data.application_code + " - " + data.name
      ); // Set modal title
      $("#application_modalForm").modal("show"); // show bootstrap modal when complete loaded
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// validate current step & go to the next step
function submitStep(action = "next") {
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string

  var step = $('[name="step_no"]').val(); // get application step
  if (action == "next") {
    var next = Number(step) + 1;
    $("#btnNext").text("validating..."); //change button text
    $("#btnNext").attr("disabled", true); //set button disable

    aId = $('[name="id"]').val();
    var url;
    if (save_method == "add") {
      url = "/admin/loans/application";
    } else {
      url = "/admin/loans/edit-application/" + aId;
    }
    // ajax adding data to database
    var formData = new FormData($("#applicationForm")[0]);
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
            // update step in the form
            $('[name="step_no"]').val(next);
            // show\hide back btn
            if (next >= 2) {
              $("#btnBack").show();
            }
            // hide\show submit and next buttons by current step
            if (next == 4) {
              $("#btnSubApplication").show();
              $("#btnNext").hide();
            } else {
              $("#btnSubApplication").hide();
              $("#btnNext").show();
            }
            // remove active class to current step
            $("button#step" + step + "-tab").removeClass("active");
            $("div#step" + step + "-tab-pane").removeClass("active");
            $("div#step" + step + "-tab-pane").removeClass("show");
            // add active class to next step
            $("button#step" + next + "-tab").addClass("active");
            $("div#step" + next + "-tab-pane").addClass("active");
            $("div#step" + next + "-tab-pane").addClass("show");
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
        $("#btnNext").text("Next"); //change button text
        $("#btnNext").attr("disabled", false); //set button enable
      },
      error: function (jqXHR, textStatus, errorThrown) {
        Swal.fire(textStatus, errorThrown, "error");
        $("#btnNext").text("Next"); //change button text
        $("#btnNext").attr("disabled", false); //set button enable
      },
    });
  } else {
    var next = Number(step) - 1;
    // reset application step
    $('[name="step_no"]').val(next);

    // remove active class to current step
    $("button#step" + step + "-tab").removeClass("active");
    $("div#step" + step + "-tab-pane").removeClass("active");
    $("div#step" + step + "-tab-pane").removeClass("show");
    // add active class to next step
    $("button#step" + next + "-tab").addClass("active");
    $("div#step" + next + "-tab-pane").addClass("active");
    $("div#step" + next + "-tab-pane").addClass("show");
    // show\hide back btn
    if (next < 2) {
      $("#btnBack").hide();
    }
    // show\hide back btn
    if (next < 4) {
      $("#btnSubApplication").hide();
      $("#btnNext").show();
    }
  }
}
// save application
function save_application() {
  $("#btnSubApplication").text("submitting..."); //change button text
  $("#btnSubApplication").attr("disabled", true); //set button disable
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  aId = $('[name="id"]').val();
  var url;
  if (save_method == "add") {
    url = "/admin/loans/application";
  } else {
    url = "/admin/loans/edit-application/" + aId;
  }

  // ajax adding data to database
  var formData = new FormData($("#applicationForm")[0]);
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
          $("#application_modalForm").modal("hide");
          Swal.fire("Success!", data.messages, "success");
          clientLoanHistory(clientId);
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
      $("#btnSubApplication").text("Submit"); //change button text
      $("#btnSubApplication").attr("disabled", false); //set button enable
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnSubApplication").text("Submit"); //change button text
      $("#btnSubApplication").attr("disabled", false); //set button enable
    },
  });
}
// delete application record
function delete_application(id, code) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to recover " + code + " application!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/loans/application/" + id,
        type: "DELETE",
        dataType: "JSON",
        success: function (data) {
          //if success reload ajax table
          if (data.status && data.error == null) {
            Swal.fire("Success!", code + " " + data.messages, "success");
            count_applications();
            reload_table("pendingApplications");
            reload_table("processApplications");
            reload_table("reviewApplications");
            reload_table("cancelledApplications");
            reload_table("declinedApplications");
            reload_table("approvedApplications");
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

function get_applicantionOptions(level = null, action = null, mode = null) {
  // application levels
  $.ajax({
    url: "/admin/applications/get-levels",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      const levelKeys = Object.keys(response);
      // check the response existance
      if (levelKeys.length > 0) {
        // select level
        $("select#level").html('<option value="">-- select --</option>');
        // Add options
        var levelOptions = "";
        $.each(response, function (index, data) {
          if (userPosition == "super admin") {
            // get all the application level options
            if (level == null && data == "Credit Officer") {
              levelOptions +=
                '<option value="' + data + '">' + data + "</option>";
            }
            // get only the current application level
            if (data == level) {
              var selection = "selected";
              levelOptions +=
                '<option value="' +
                data +
                '" ' +
                selection +
                ">" +
                data +
                "</option>";
            }
          } else {
            if (
              data.toLowerCase() == userPosition ||
              data == "Credit Officer"
            ) {
              var selection = "selected";
              levelOptions +=
                '<option value="' +
                data +
                '" ' +
                selection +
                ">" +
                data +
                "</option>";
            }
            /*else {
              var selection = "selected";
              levelOptions =
                '<option value="" ' +
                selection +
                ">Application already passed your role"
              "</option>";
            }*/
          }
        });
        // append the level options to the select levels options
        $("select#level").html(levelOptions);
      } else {
        $("select#level").html('<option value="">No Level</option>');
      }
    },
    error: function (err) {
      $("select#level").html('<option value="">Error Occured</option>');
    },
  });
  // application actions
  $.ajax({
    url: "/admin/applications/get-actions",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      const actionKeys = Object.keys(data);
      // select current action
      if (actionKeys.length > 0) {
        //$("select#action").find("option").not(":first").remove();
        $("select#action").html('<option value="">-- select --</option>');
        // Add options
        var actionOptions = "";
        $.each(data, function (index, result) {
          if (result == action) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          actionOptions +=
            '<option value="' +
            result +
            '" ' +
            selection +
            ">" +
            result +
            "</option>";
        });
        // append the application status options
        $("select#action").html(actionOptions);
      } else {
        $("select#action").html('<option value="">No Action</option>');
      }
    },
    error: function (err) {
      $("select#action").html('<option value="">Error Occured</option>');
    },
  });
}

// auto fill client details on selecting a client
$("select#client_id").on("change", function () {
  var cID = this.value;
  if (cID == 0 || cID == "") {
    $('[name="account_no"]').val("");
    $('[name="mobile"]').val("");
    $('[name="account_bal"]').val("");
    $("#photo-preview div").html(
      '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
    );
    $("#id-preview div").html(
      '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
    );
    $("#signature-preview div").html(
      '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
    );
  } else {
    $.ajax({
      url: "/admin/clients/client/" + cID,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $('[name="account_no"]').val(data.account_no);
        $('[name="mobile"]').val(data.mobile);
        $('[name="account_bal"]').val(
          Number(data.account_balance).toLocaleString()
        );
        if (
          data.photo &&
          imageExists("/uploads/clients/passports/" + data.photo)
        ) {
          $("#photo-preview div").html(
            '<img src="/uploads/clients/passports/' +
              data.photo +
              '" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
          );
        } else {
          $("#photo-preview div").html(
            '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
          );
        }
        if (
          data.id_photo_front &&
          imageExists("/uploads/clients/ids/front/" + data.id_photo_front)
        ) {
          $("#id-preview div").html(
            '<img src="/uploads/clients/ids/front/' +
              data.id_photo_front +
              '" class="img-fluid thumbnail"  style="width: 200px; height: 140px;">'
          );
        } else {
          $("#id-preview div").html(
            '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 140px;">'
          );
        }
        if (
          data.signature &&
          imageExists("/uploads/clients/signatures/" + data.signature)
        ) {
          $("#signature-preview div").html(
            '<img src="/uploads/clients/signatures/' +
              data.signature +
              '" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
          );
        } else {
          $("#signature-preview div").html(
            '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
          );
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        Swal.fire(textStatus, errorThrown, "error");
      },
    });
  }
});
// auto fill product details on selecting a product
$("select#product_id").on("change", function () {
  var pID = this.value;
  if (pID == 0 || pID == "") {
    $('[name="total_charges"]').val("");
    $('[name="interest_rate"]').val("");
    $('[name="interest_type"]').val("");
    $("select#interest_period").html('<option value="">--Select---</option>');
    $('[name="repayment_period"]').val("");
    $('[name="repayment_freq"]').val("");
    $('[name="total_charges"]').val("");
    $("#productCharges").html("");
  } else {
    $.ajax({
      url: "/admin/loans/product/" + pID,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        // generateCustomeSettings('repayments', data.repayment_freq);
        // $('[name="interest_period"]').val(data.interest_period).trigger("change");
        if (save_method == "add") {
          generateCustomeSettings("interest_period", data.interest_period);
          $('[name="interest_rate"]').val(data.interest_rate);
          $('[name="interest_type"]').val(data.interest_type);
          $('[name="repayment_period"]').val(
            // data.repayment_period + " " + data.repayment_duration
            data.repayment_period
          );
        }
        $('[name="repayment_freq"]').val(data.repayment_frequency);
        var html = "";
        if (data.charges) {
          $.each(data.charges, function (index, charge) {
            var chargeId = charge.particular_id;
            var chargeName = charge.particular_name;
            var chargeType = charge.charge_method;
            var chargeValue = charge.charge;
            var chargeMode = charge.charge_mode;
            if (chargeType.toLowerCase() == "amount") {
              var symbol = " " + currency;
            }
            if (chargeType.toLowerCase() == "percent") {
              var symbol = "% of the principal";
            }
            html +=
              '<div class="col-xl-4 task-card">' +
              '<div class="card custom-card task-pending-card">' +
              '<div class="card-body">' +
              '<div class="d-flex justify-content-between flex-wrap">' +
              "<div>" +
              '<p class="fw-semibold mb-3 d-flex align-items-center">' +
              '<span class="form-check form-check-md form-switch">' +
              '<input type="checkbox" name="product_charges[]" value="' +
              chargeId +
              '" id="vproduct_charge' +
              chargeId +
              '" class="form-check-input form-checked-success vproduct_charge" checked disabled>' +
              "</span>&nbsp;" +
              chargeName +
              "</p>" +
              '<p class="mb-3">' +
              'Type : <span class="fs-12 mb-1 text-muted">' +
              chargeType +
              "</span>" +
              "</p>" +
              '<p class="mb-3">' +
              'Charge : <span class="fs-12 mb-1 text-muted">' +
              chargeValue.toLocaleString() +
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
        $("#productCharges").html(html);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        Swal.fire(textStatus, errorThrown, "error");
      },
    });
  }
});

// make payment
function add_disbursementPayment(client_id) {
  $("#repaymentForm")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $('[name="client_id"]').val(client_id);
  $('[name="account_typeId"]').val(repayment_accountId);
  $('[name="entry_menu"]').val("financing");
  $('[name="transaction_menu"]').val("repayments");
  $("strong#cName").text(client["name"]);
  $("strong#cContact").text(client["mobile"]);
  // setPhoneNumberWithCountryCode($("#mobile"), data.mobile);
  $("strong#cContact2").text(client["alternate_no"]);
  $("strong#cEmail").text(client["email"]);
  $("strong#cAccountNo").text(client["account_no"]);
  $("strong#cBalance").text(client["account_balance"]);
  $("strong#cAddress").text(client["residence"]);
  $("strong#cRegDate").text(client["reg_date"]);
  if (
    client["photo"] &&
    imageExists("/uploads/clients/passports/" + client["photo"])
  ) {
    $("#cPhoto-preview div").html(
      '<img src="/uploads/clients/passports/' +
        client["photo"] +
        '" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
    );
  } else {
    $("#cPhoto-preview div").html(
      '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
    );
  }
  // $('[name="contact"]').val(client["mobile"]);
  setPhoneNumberWithCountryCode($("#mobile-full"), client["mobile"]);

  // load loan info n auto fill form where applicable
  pendingDisbursements(client_id);
  // load loan repayment entry types
  // accountType_items(repayment_accountId);
  $(".modal-title").text("Add Disbursement Payment");
  $("#repayment_modal_form").modal("show");
}

// pending client disbursements
function pendingDisbursements(clientId = null, disbursementId = null) {
  var disbursementsSelect = $("select#disbursement_id");
  disbursementsSelect.trigger("change");

  // client pending disbursements
  if (clientId) {
    disbursementsSelect.trigger("change");
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "/admin/loans/pending-disbursements/" + clientId,
      success: function (response) {
        if (response.length > 0) {
          disbursementsSelect.empty(); // Clear existing options
          if (response.length > 1) {
            disbursementsSelect.html('<option value="">-- select --</option>');
          }
          $.each(response, function (index, item) {
            var option = $("<option>", {
              value: item.id,
              text: item.disbursement_code,
            });
            if (item.id == disbursementId) {
              option.attr("selected", true);
            }
            // select particular
            accountType_items(repayment_accountId, item.particular_id);
            $('[name="particular_id"]').val(item.particular_id);
            $('[name="product_name"]').val(item.product_name);
            $('[name="principal_taken"]').val(
              Number(item.principal).toLocaleString()
            );
            $('[name="class"]').val(item.class);
            $('[name="amount"]').val(
              Number(item.actual_installment).toLocaleString()
            );
            $("#tLoan").text(Number(item.actual_repayment).toLocaleString());
            $("#lBalance").text(Number(item.total_balance).toLocaleString());
            $("#lInstallment").text(
              Number(item.actual_installment).toLocaleString()
            );
            $("#lDuration").text(item.loan_period_days);
            $("#lDaysCovered").text(item.days_covered);
            $("#lDaysRemaining").text(item.days_remaining);
            disbursementsSelect.append(option);
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

  // pending disbursement data
  disbursementsSelect.on("change", function () {
    disbursement_ID = this.value;
    if (disbursement_ID) {
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/admin/loans/disbursement/" + disbursement_ID,
        success: function (disbursement) {
          $('[name="particular_id"]').val(item.particular_id);
          $('[name="product_name"]').val(disbursement.product_name);
          $('[name="class"]').val(disbursement.class);
          $('[name="amount"]').val(disbursement.actual_installment);
        },
        error: function (err) {
          disbursementsSelect.html('<option value="">Error Occurred</option>');
        },
      });
    } else {
      $('[name="particular_id"]').val("");
      $('[name="product_name"]').val("");
      $('[name="class"]').val("");
      $('[name="amount"]').val("");
    }
  });
}

// save payment
function save_payments() {
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnPay").text("submitting...");
  $("#btnPay").attr("disabled", true);
  // ajax adding data to database
  var formData = new FormData($("#repaymentForm")[0]);
  $.ajax({
    url: "/admin/transactions/transaction",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "JSON",
    success: function (data) {
      if (!data.inputerror) {
        if (data.status && data.error == null) {
          $("#repayment_modal_form").modal("hide");
          Swal.fire("Success!", data.messages, "success");
          reload_table("savings");
          reload_table("membership");
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
      $("#btnPay").text("Submit");
      $("#btnPay").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnPay").text("Submit");
      $("#btnPay").attr("disabled", false);
    },
  });
}

// loan account type particulars and entry/transaction types
function accountType_items(
  acc_typeId,
  particularID = null,
  entry_typeId = null
) {
  var particularSelect = $("select#particular_id");
  var chargeSelect = $("select#particular_charge");
  var entry_typeSelect = $("select#repay_entry_typeId");
  if (!acc_typeId) {
    particularSelect.html('<option value="">Choose Account Type</option>');
    entry_typeSelect.html('<option value="">Choose Account Type</option>');
  } else {
    // select account type particulars
    selectParticulars(acc_typeId, particularID);

    // select account type entry\transaction types
    $.ajax({
      url: "/admin/transactions/transaction-types/" + acc_typeId,
      type: "POST",
      dataType: "JSON",
      data: { transaction_menu: "repayments" },
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
