var save_method;
var account_typeId = 3; // id for loan repayment particulars
var loansRevenue = 19; // id for revenue from loan particulars
var entry_menu = "financing"; //default entry menu for all disbursement payments
// table IDs
var repaymentTableId = "repaymentHistory";
var collateralsTableId = "collaterals";
// dataTables urls
var repaymentTableUrl = "/admin/transactions/repayment-history/" + disID;
var collateralsTableUrl =
  "/admin/loans/getApplicationfiles/" + appID + "/collateral";
// dataTables column configs for each table
var columnsConfig = {
  repaymentHistory: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "type" },
    { data: "payment" },
    { data: "amount", render: $.fn.DataTable.render.number(",") },
    { data: "ref_id" },
    { data: "staff_name" },
    { data: "date" },
    { data: "balance", render: $.fn.DataTable.render.number(",") },
    { data: "action", orderable: false, searchable: false },
  ],
  collaterals: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "file_name" },
    { data: "extension", orderable: false, searchable: false },
    { data: "photo", orderable: false, searchable: false },
    { data: "action", orderable: false, searchable: false },
  ],
};
// dataTables buttons config for each table
buttonsConfig = {
  repaymentHistory: [
    {
      text: '<i class="fa fa-trash"></i>',
      className: "btn btn-sm btn-danger",
      titleAttr: "Bulky Delete",
      action: function () {
        bulk_deleteParticularCollections();
      },
    },
    {
      extend: "excel",
      className: "btn btn-sm btn-success",
      titleAttr: "Export To Excel",
      text: '<i class="fas fa-file-excel"></i>',
      filename: "Disbursement Repayment History",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8],
      },
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: "Disbursement Repayment History",
      extension: ".pdf",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: "Disbursement Repayment History",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-warning",
      titleAttr: "Copy " + title + " Table Data",
      text: '<i class="fas fa-copy"></i>',
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      text: '<i class="fa fa-refresh"></i>',
      className: "btn btn-sm btn-sm btn-secondary",
      titleAttr: "Refresh Table",
      action: function () {
        reload_table("repaymentHistory");
      },
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'><?= $disbursement['name'] ?> Disbursement Repayment History</h4><h5 class='text-center'>Printed On " +
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
      className: "btn btn-primary",
      titleAttr: "Print Income Files",
      text: '<i class="fa fa-print"></i>',
      filename: "Disbursement Repayment History",
    },
  ],
  collaterals: [
    {
      text: '<i class="fa fa-trash"></i>',
      className: "btn btn-sm btn-danger",
      titleAttr: "Bulky Delete",
      action: function () {
        bulk_deleteFiles();
      },
    },
    {
      text: '<i class="fa fa-refresh"></i>',
      className: "btn btn-sm btn-secondary",
      titleAttr: "Reload Table",
      action: function () {
        reload_table("collaterals");
      },
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>Collaterals Information</h4><h5 class='text-center'>Printed On " +
        new Date().getHours() +
        " : " +
        new Date().getMinutes() +
        " " +
        new Date().toDateString() +
        "</h5>",
      exportOptions: {
        columns: [1, 2, 3, 4],
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
      },
      className: "btn btn-primary",
      titleAttr: "Print Collaterals",
      text: '<i class="fa fa-print"></i>',
      filename: "Collaterals Information",
    },
  ],
};

$(document).ready(function () {
  // redifine dataTable column length on change of data-bs-toggle tab
  $('button[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
    $($.fn.dataTable.tables(true))
      .DataTable()
      .columns.adjust()
      .responsive.recalc();
  });
  view_disbursement(disID);
  // get applicant application particulars payments
  initializeDataTable(
    repaymentTableId,
    repaymentTableUrl,
    columnsConfig["repaymentHistory"],
    buttonsConfig["repaymentHistory"]
  );
  // get applicant collateral files
  initializeDataTable(
    collateralsTableId,
    collateralsTableUrl,
    columnsConfig["collaterals"],
    buttonsConfig["collaterals"]
  );

  // load disbursement particulars
  selectParticulars(account_typeId);
  selectParticulars(account_typeId, null, 'disparticular_id')
  selectParticulars(loansRevenue, null, 'interest_particular_id')

  // load payment methods
  selectPaymentMethod();
});

function exportDisbursementForm(menu) {
  var disbursement_id = $('[name="id"]').val();
  window.location.assign("/admin/loans/disbursementform/" + disID);
}
// particular collections
function particular_payments(application_id, particular_id) {
  var totalCollection = 0;
  $.ajax({
    async: false,
    url:
      "/admin/transactions/applicant-payments/" +
      application_id +
      "/" +
      particular_id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          totalCollection += Number(data[i].amount);
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
  return totalCollection;
}

function add_disbursementPayment() {
  $("#repaymentForm")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $('[name="disbursement_id"]').val(id);
  $('[name="client_id"]').val(clientId);
  $('[name="account_typeId"]').val(account_typeId);
  $('[name="entry_menu"]').val("financing");
  $('[name="transaction_menu"]').val("repayments");
  // load loan info n auto fill form where applicable
  disbursementRecord(id);
  // load loan repayment entry types
  transaction_types();
  $(".modal-title").text("Add Disbursement Payment");
  $("#repayment_modal_form").modal("show");
}
// view repayment
function view_transaction(id) {
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
      $('[name="vamount"]').val(data.amount);
      $('[name="vstatus"]').val(data.status);
      $("textarea#viewSummernote").summernote("code", data.entry_details);
      $('[name="vremarks"]').val(data.remarks);
      $('[name="ventry_menu"]').val(data.entry_menu);
      $('[name="vbalance"]').val(data.balance);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $(".modal-title").text("View transaction " + data.ref_id);
      $("#view_pay_modal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
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
          reload_table("repaymentHistory");
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

// view disbursement data
function view_disbursement(id) {
  $.ajax({
    url: "/admin/loans/disbursement/" + id,
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
      if (data.class == "Cleared") {
        $("button#payButton").attr("disabled", true);
      }
      var installments_left = Number(
        data.installments_num - data.installments_covered
      );
      calculateInterest(
        data.interest_type,
        Number(data.principal),
        Number(data.computed_installment),
        Number(data.interest_rate),
        data.repayment_frequency,
        Number(data.installments_num),
        Number(data.repayment_period),
        data.interest_period,
        data.date_disbursed,
        Number(data.installments_covered),
        Number(data.installments_due)
      );
      $('[name="id"]').val(data.id);
      // client data
      $('[name="client_id"]').val(data.client_id);
      $('[name="name"]').val(data.name);
      $('[name="account_no"]').val(data.account_no);
      $('[name="account_type"]').val(data.account_type);
      $('[name="email"]').val(data.email);
      $('[name="mobile"]').val(data.mobile);
      $('[name="alt_mobile"]').val(data.alternate_no);
      $('[name="gender"]').val(data.gender);
      $('[name="religion"]').val(data.religion);
      $('[name="nationality"]').val(data.nationality);
      $('[name="dob"]').val(data.dob);
      $('[name="marital_status"]').val(data.marital_status);
      $('[name="occupation"]').val(data.occupation);
      $('[name="job_location"]').val(data.job_location);
      $('[name="residence"]').val(data.residence);
      $('[name="closest_landmark"]').val(data.closest_landmark);
      $('[name="id_type"]').val(data.id_type);
      $('[name="id_number"]').val(data.id_number);
      $('[name="id_expiry"]').val(data.id_expiry_date);
      $('[name="next_of_kin"]').val(data.next_of_kin_name);
      $('[name="nok_relationship"]').val(data.next_of_kin_relationship);
      $('[name="nok_phone"]').val(data.next_of_kin_contact);
      $('[name="nok_alt_phone"]').val(data.next_of_kin_alternate_contact);
      $('[name="nok_email"]').val(data.nok_email);
      $('[name="nok_address"]').val(data.nok_address);
      $('[name="branch_id"]').val(data.branch_name);
      $('[name="mobile"]').val(data.mobile);
      // product data
      $('[name="product_name"]').val(data.product_name);
      $('[name="interest_rate"]').val(data.interest_rate);
      generateCustomeSettings("interest_period", data.interest_period);
      $('[name="interest_type"]').val(data.interest_type);
      $('[name="repayment_freq"]').val(data.repayment_frequency);
      $('[name="repayment_period"]').val(
        data.repayment_period + " " + data.repayment_duration
      );
      // application data
      $('[name="vapplication_code"]').val(data.application_code);
      $('[name="security_item"]').val(data.security_item);
      $('[name="est_value"]').val(data.est_value);
      $("textarea#seeSummernote").summernote("code", data.security_info);
      $('[name="relation_name"]').val(data.relation_name);
      $('[name="relation_address"]').val(data.relation_address);
      $('[name="relation_occupation"]').val(data.relation_occupation);
      $('[name="relation_contact"]').val(data.relation_contact);
      $('[name="relation_alt_contact"]').val(data.relation_alt_contact);
      $('[name="relation_email"]').val(data.relation_email);
      $('[name="relation_relationship"]').val(data.relation_relationship);
      $('[name="relation_name2"]').val(data.relation_name2);
      $('[name="relation_address2"]').val(data.relation_address2);
      $('[name="relation_occupation2"]').val(data.relation_occupation2);
      $('[name="relation_contact2"]').val(data.relation_contact2);
      $('[name="relation_alt_contact2"]').val(data.relation_alt_contact2);
      $('[name="relation_email2"]').val(data.relation_email2);
      $('[name="relation_relationship2"]').val(data.relation_relationship2);
      $('[name="total_charges"]').val(data.total_charges);
      $('[name="reduct_charges"]').val(data.reduct_charges);
      $('[name="vpurpose"]').val(data.purpose);
      $('[name="application_officer"]').val(data.application_officer);
      $('[name="vapplication_date"]').val(data.application_date);
      $('[name="vapp_status"]').val(data.app_status);
      $('[name="application_created_at"]').val(data.app_created_at);
      $('[name="application_updated_at"]').val(data.app_updated_at);
      // disbursement data
      $('[name="disbursement_code"]').val(data.disbursement_code);
      $('[name="principal"]').val(data.principal);
      $('[name="principal_particular"]').val(data.principal_particular);
      $('[name="interest_particular"]').val(data.interest_particular);
      $('[name="payment_method"]').val(data.payment_method);
      $('[name="disbursed_by"]').val(data.disbursed_by);
      $('[name="status"]').val(data.status);
      $('[name="class"]').val(data.class);
      $('[name="cycle"]').val(data.cycle);
      $('[name="computed_interest"]').val(data.computed_interest);
      $('[name="computed_installment"]').val(data.computed_installment);
      $('[name="actual_installment"]').val(data.actual_installment);
      $('[name="computed_repayment"]').val(data.computed_repayment);
      $('[name="actual_repayment"]').val(data.actual_repayment);
      $('[name="actual_interest"]').val(data.actual_interest);
      $('[name="principal_installment"]').val(data.principal_installment);
      $('[name="interest_installment"]').val(data.interest_installment);
      $('[name="actual_interest"]').val(data.actual_interest);
      $('[name="first_recovery"]').val(data.first_recovery);
      $('[name="installments_due"]').val(data.installments_due);
      $('[name="installments_num"]').val(data.installments_num);
      $('[name="installments_covered"]').val(data.installments_covered);
      $('[name="installments_left"]').val(installments_left);
      $('[name="expiry_day"]').val(data.expiry_day);
      $('[name="grace_period"]').val(data.grace_period);
      $('[name="loan_expiry_date"]').val(data.loan_expiry_date);
      $('[name="loan_period_days"]').val(data.loan_period_days);
      $('[name="days_covered"]').val(data.days_covered);
      $('[name="days_remaining"]').val(data.days_remaining);
      $('[name="expected_amount_recovered"]').val(
        data.expected_amount_recovered
      );
      $('[name="expected_interest_recovered"]').val(
        data.expected_interest_recovered
      );
      $('[name="expected_principal_recovered"]').val(
        data.expected_principal_recovered
      );
      $('[name="expected_loan_balance"]').val(data.expected_loan_balance);
      $('[name="interest_collected"]').val(data.interest_collected);
      $('[name="principal_collected"]').val(data.principal_collected);
      $('[name="total_collected"]').val(data.total_collected);
      $('[name="interest_balance"]').val(data.interest_balance);
      $('[name="principal_balance"]').val(data.principal_balance);
      $('[name="total_balance"]').val(data.total_balance);
      $('[name="arrears"]').val(data.arrears);
      $('[name="principal_due"]').val(data.principal_due);
      $('[name="interest_due"]').val(data.interest_due);
      $('[name="days_due"]').val(data.days_due);
      $('[name="comments"]').val(data.comments);
      $('[name="vofficer"]').val(data.staff_name);
      $('[name="date_disbursed"]').val(data.date_disbursed);
      $('[name="vcreated_at"]').val(data.created_at);
      $('[name="vupdated_at"]').val(data.updated_at);
      // fetch client loan history
      clientLoanHistory(data.client_id);
      //  client data
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
      $('[name="vreg_date"]').val(data.reg_date);
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
      $('[name="age"]').val(
        new Date().getYear() - new Date(data.dob).getYear()
      );
      // passport photo
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
      // sign
      if (data.sign && imageExists("/uploads/clients/signatures/" + data.sign)) {
        $("#signature-preview div").html(
          '<img src="/uploads/clients/signatures/' +
            data.sign +
            '" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#signature-preview div").html(
          '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      $("h1#title").text(
        data.name + "[" + data.disbursement_code + "] Disbursement Info"
      );

      $("#contentSPinner").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// view files
function view_file(id) {
  $.ajax({
    url: "/admin/loans/view-file/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="applicant_name"]').val(data.name);
      $('[name="file_name"]').val(data.file_name);
      $('[name="created_at"]').val(data.created_at);
      if (data.type == "collateral") {
        if (
          data.file_name &&
          imageExists("/uploads/applications/collaterals/" + data.filename)
        ) {
          $("#file-preview div").html(
            '<img src="/uploads/applications/collaterals/' +
              data.file_name +
              '" class="img-fluid thumbnail">'
          );
        } else {
          $("#file-preview div").html(
            '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail">'
          );
        }
      }
      if (data.type == "income") {
        if (
          data.file_name &&
          imageExists("/uploads/applications/income/" + data.filename)
        ) {
          $("#file-preview div").html(
            '<img src="/uploads/applications/income/' +
              data.file_name +
              '" class="img-fluid thumbnail">'
          );
        } else {
          $("#photo-preview div").html(
            '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail">'
          );
        }
      }
      if (data.type == "expense") {
        if (
          data.file_name &&
          imageExists("/uploads/applications/expenses/" + data.filename)
        ) {
          $("#file-preview div").html(
            '<img src="/uploads/applications/expense/' +
              data.file_name +
              '" class="img-fluid thumbnail">'
          );
        } else {
          $("#file-preview div").html(
            '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail">'
          );
        }
      }
      $(".modal-title").text(data.file_name);
      $("#file_view").modal("show");
    },

    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// delete file
function delete_file(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to recover " + name + "file!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/files/delete-file/" + id,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          //if success reload ajax table
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
            reload_table("collaterals");
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

function delete_particularCollection(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to " + name + " live collection!",
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
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
            reload_payments_table();
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
function bulk_deleteFiles() {
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
          " file(s) once deleted!",
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
            url: "/admin/files/fileBulk-delete",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Deleted!", data.messages, "success");
                reload_table("collaterals");
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
    Swal.fire("Sorry!", "No file selected....", "error");
  }
}

function bulk_deleteParticularCollections() {
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
          " collection(s) once deleted!",
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
                reload_table("collaterals");
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
    Swal.fire("Sorry!", "No collection selected :)", "error");
  }
}

