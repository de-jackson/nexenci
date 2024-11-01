var save_method;
var account_typeId = 18; // revenue from loans particulars
var entry_menu = "financing"; //default entry menu for all application payments
// table IDs
var chargesPaymentsId = "chargesPayments";
var collateralsTableId = "collaterals";
var filesTableId = "files";
// dataTables urls
var chargesPaymentsUrl = "/admin/transactions/applicant-transactions/" + appID;
var collateralsTableUrl =
  "/admin/loans/getApplicationfiles/" + appID + "/collateral";
var filesTableUrl = "/admin/loans/getApplicationfiles/" + appID + "/files";
// dataTables column configs for each table
var columnsConfig = {
  chargesPayments: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "particular_name" },
    { data: "amount", render: $.fn.DataTable.render.number(",") },
    { data: "ref_id" },
    { data: "created_at" },
    { data: "staff_name" },
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
  files: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "file_name" },
    { data: "extension", orderable: false, searchable: false },
    { data: "type" },
    { data: "photo", orderable: false, searchable: false },
    { data: "action", orderable: false, searchable: false },
  ],
};
// dataTables buttons config for each table
buttonsConfig = {
  chargesPayments: [
    {
      extend: "excel",
      className: "btn btn-sm btn-success",
      titleAttr: "Export To Excel",
      text: '<i class="fas fa-file-excel"></i>',
      filename: "Application Payments",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4],
      },
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: "Application Payments",
      extension: ".pdf",
      exportOptions: {
        columns: [1, 2, 3, 4],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: "Application Payments",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2, 3, 4],
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
      className: "btn btn-sm btn-secondary",
      titleAttr: "Refresh Table",
      action: function () {
        reload_table("chargesPayments");
      },
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>Application Payments Information</h4><h5 class='text-center'>Printed On " +
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
      className: "btn btn-sm btn-primary",
      titleAttr: "Print Payments",
      text: '<i class="fa fa-print"></i>',
      filename: "Application Payments",
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
        // Replace the page title with the actual page title
        $(win.document.head).find("title").text(title);
      },
      className: "btn btn-sm btn-primary",
      titleAttr: "Print Collaterals",
      text: '<i class="fa fa-print"></i>',
      filename: "Collaterals Information",
    },
  ],
  files: [
    {
      text: '<i class="fa fa-upload"></i>',
      className: "btn btn-sm btn-info",
      titleAttr: "Add Income File",
      action: function () {
        add_files("income");
      },
    },
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
        reload_table("files");
      },
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>Income Files Information</h4><h5 class='text-center'>Printed On " +
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
      className: "btn btn-sm btn-primary",
      titleAttr: "Print Income Files",
      text: '<i class="fa fa-print"></i>',
      filename: "Income Files Information",
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
  view_application(appID);
  // get applicant collateral files
  initializeDataTable(
    collateralsTableId,
    collateralsTableUrl,
    columnsConfig["collaterals"],
    buttonsConfig["collaterals"]
  );
  // get applicant income files
  initializeDataTable(
    filesTableId,
    filesTableUrl,
    columnsConfig["files"],
    buttonsConfig["files"]
  );
  // get applicant application particulars payments
  initializeDataTable(
    chargesPaymentsId,
    chargesPaymentsUrl,
    columnsConfig["chargesPayments"],
    buttonsConfig["chargesPayments"]
  );

  // load application remarks
  load_applicationRemarks();

  // generate loan application particular payables
  application_particularPayables(account_typeId);

  // load payment methods
  selectPaymentMethod();
});

function exportApplicationForm(menu) {
  var application_id = $('[name="id"]').val();
  window.location.assign("/admin/loans/applicationform/" + appID);
}
function exportApplicationActionForm(menu) {
  var application_id = $('[name="id"]').val();
  if (menu == "approve") {
    window.location.assign(
      "/loans/applicationactionform/approve/" + application_id
    );
  } else {
    window.location.assign(
      "/loans/applicationactionform/pending/" + application_id
    );
  }
}
// view application data
function view_application(application_id) {
  $.ajax({
    url: "/admin/loans/application/" + application_id,
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
      buttons_display(data.status);
      $('[name="id"]').val(data.id);
      $('[name="application_id"]').val(data.id);
      $('[name="client_id"]').val(data.c_id);
      $('[name="vapplication_code"]').val(data.application_code);
      $('[name="vapplication_date"]').val(data.application_date);
      $('[name="vproduct_id"]').val(data.product_name);
      $('[name="vinterest_rate"]').val(data.interest_rate);
      $('[name="vinterest_type"]').val(data.interest_type);
      $('[name="vrepayment_period"]').val(
        data.repayment_period + " " + data.repayment_frequency
      );
      generateCustomeSettings("interest_period", data.interest_period);
      $('[name="vrepayment_freq"]').val(data.repayment_frequency);
      $('[name="vprincipal"]').val(data.principal);
      $('[name="vstatus"]').val(data.status);
      $('[name="vlevel"]').val(level);
      $('[name="vpurpose"]').val(data.purpose);
      $('[name="overall_charges"]').val(overallCharges);
      $('[name="total_charges"]').val(data.total_charges);
      $('[name="reduct_charges"]').val(data.reduct_charges);
      if (data.charges) {
        load_applicationCharges(data.charges);
      } else {
        $("#applicationCharges").html(
          '<p class="fw-semibold text-primary text-center mb-3">No Applicable Charges found</p>'
        );
      }
      $('[name="vsecurity_item"]').val(data.security_item);
      $("textarea#viewSummernote").summernote("code", data.security_info);
      $('[name="vest_value"]').val(data.est_value);
      $('[name="vrelation_name"]').val(data.relation_name);
      $('[name="vrelation_address"]').val(data.relation_address);
      $('[name="vrelation_occupation"]').val(data.relation_occupation);
      $('[name="vrelation_contact"]').val(data.relation_contact);
      $('[name="vrelation_alt_contact"]').val(data.relation_alt_contact);
      $('[name="vrelation_email"]').val(data.relation_email);
      $('[name="vrelation_relationship"]').val(data.relation_relationship);
      $('[name="vrelation_name2"]').val(data.relation_name2);
      $('[name="vrelation_address2"]').val(data.relation_address2);
      $('[name="vrelation_occupation2"]').val(data.relation_occupation2);
      $('[name="vrelation_contact2"]').val(data.relation_contact2);
      $('[name="vrelation_alt_contact2"]').val(data.relation_alt_contact2);
      $('[name="vrelation_email2"]').val(data.relation_email2);
      $('[name="vrelation_relationship2"]').val(data.relation_relationship2);

      $('[name="vnet_salary"]').val(data.net_salary);
      $('[name="vfarming"]').val(data.farming);
      $('[name="vbusiness"]').val(data.business);
      $('[name="vothers"]').val(data.others);
      $('[name="vincome_total"]').val(
        Number(data.net_salary) +
          Number(data.farming) +
          Number(data.business) +
          Number(data.others)
      );
      $('[name="vrent"]').val(data.rent);
      $('[name="veducation"]').val(data.education);
      $('[name="vmedical"]').val(data.medical);
      $('[name="vtransport"]').val(data.transport);
      $('[name="vexp_others"]').val(data.exp_others);
      $('[name="vexp_total"]').val(
        Number(data.rent) +
          Number(data.education) +
          Number(data.medical) +
          Number(data.transport) +
          Number(data.exp_others)
      );
      $('[name="vdifference"]').val(data.difference);
      $('[name="vdif_status"]').val(data.dif_status);

      $('[name="vinstitute_name"]').val(data.institute_name);
      $('[name="vinstitute_branch"]').val(data.institute_branch);
      $('[name="vaccount_type"]').val(data.account_type);
      $('[name="vinstitute_name2"]').val(data.institute_name2);
      $('[name="vinstitute_branch2"]').val(data.institute_branch2);
      $('[name="vaccount_type2"]').val(data.account_type2);
      $('[name="vamt_advance"]').val(data.amt_advance);
      $('[name="vdate_advance"]').val(data.date_advance);
      $('[name="vloan_duration"]').val(data.loan_duration);
      $('[name="vamt_outstanding"]').val(data.amt_outstanding);
      $('[name="vamt_advance2"]').val(data.amt_advance2);
      $('[name="vdate_advance2"]').val(data.date_advance2);
      $('[name="vloan_duration2"]').val(data.loan_duration2);
      $('[name="vamt_outstanding2"]').val(data.amt_outstanding2);
      $('[name="vloan_officer"]').val(data.staff_name);
      $('[name="vstatus"]').val(data.status);
      $('[name="application_date"]').val(data.application_date);
      $('[name="vcreated_at"]').val(data.created_at);
      $('[name="vupdated_at"]').val(data.updated_at);
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
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $('[name="age"]').val(
        new Date().getYear() - new Date(data.dob).getYear()
      );

      // generate amortizer
      show_amortizer(data.id);
      clientLoanHistory(data.client_id);
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
      // sign
      if (
        data.sign &&
        imageExists("/uploads/clients/signatures/" + data.sign)
      ) {
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
      // view_appraisal(data.a_id);
      // hide spinner after content has loaded
      $("#contentSPinner").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

function edit_application(id) {
  save_method = "update";
  $("#export").hide();
  $("#formRow").show();
  $("#importRow").hide();
  // hide submit button
  $("#btnSav").hide();
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
      $('[name="applicant_loan_frequency"]').val(data.loan_frequency);
      $('[name="applicant_loan_period"]').val(data.loan_period);
      $('[name="applicant_repayment_period"]').val(data.repayment_period);
      $('[name="applicant_repayment_frequency"]').val(data.repayment_frequency);
      $('[name="applicant_interest_period"]')
        .val(data.interest_period)
        .trigger("change");
      $('[name="applicant_interest_rate"]').val(data.interest_rate);
      $('[name="application_id"]').val(data.application_id);
      $('[name="application_code"]').val(data.application_code);
      $('[name="application_date"]').val(data.application_date);
      $('[name="interest_rate"]').val(data.interest_rate);
      $('[name="client_id"]').val(data.client_id).trigger("change");
      $('[name="product_id"]').val(data.product_id).trigger("change");
      generateCustomeSettings("interest_period", data.interest_period);
      $('[name="interest_period"]').val(data.interest_period).trigger("change");
      $('[name="interest_type"]').val(data.interest_type);
      generateCustomeSettings("loan_frequency", data.loan_frequency);
      $('[name="loan_period"]').val(data.loan_period);
      $('[name="repayment_period"]').val(data.repayment_period);
      generateCustomeSettings("repayments", data.repayment_frequency);

      $('[name="repayment_freq"]')
        .val(data.repayment_frequency)
        .trigger("change");
      $('[name="principal"]').val(data.principal);
      $('[name="purpose"]').val(data.purpose);
      $('[name="security_info"]').val(data.security_info);
      $('[name="repayment_period"]').val(data.repayment_period);
      selectClient(data.client_id);
      // load loan products
      selectProducts("loans", data.product_id);
      $('[name="status"]').val(data.status);
      $('[name="reduct_charges"]').val(data.reduct_charges);
      total_applicationCharges(data.principal);

      setPhoneNumberWithCountryCode($("#relation_contact"), data.relation_contact);
      setPhoneNumberWithCountryCode(
        $("#relation_alt_contact"),
        data.relation_alt_contact
      );
      setPhoneNumberWithCountryCode($("#relation_contact2"), data.relation_contact2);
      setPhoneNumberWithCountryCode(
        $("#relation_alt_contact2"),
        data.relation_alt_contact2
      );

      $('[name="security_item"]').val(data.security_item);
      $("textarea#addSummernote").summernote("code", data.security_info);
      $('[name="est_value"]').val(data.est_value);
      $('[name="relation_name"]').val(data.relation_name);
      $('[name="relation_address"]').val(data.relation_address);
      $('[name="relation_occupation"]').val(data.relation_occupation).trigger("change");
      $('[name="relation_email"]').val(data.relation_email);
      $('[name="relation_relationship"]').val(data.relation_relationship).trigger("change");
      $('[name="relation_name2"]').val(data.relation_name2);
      $('[name="relation_address2"]').val(data.relation_address2);
      $('[name="relation_occupation2"]').val(data.relation_occupation2).trigger("change");
      $('[name="relation_email2"]').val(data.relation_email2);
      $('[name="relation_relationship2"]').val(data.relation_relationship2).trigger("change");

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
      $("#modal_form").modal("show"); // show bootstrap modal when complete loaded
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// validate current step n go to the next step
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
            // update step in the form
            $('[name="step_no"]').val(next);
            // show\hide back btn
            if (next >= 2) {
              $("#btnBack").show();
            }
            // hide\show submit and next buttons by current step
            if (next == 4) {
              $("#btnSav").show();
              $("#btnNext").hide();
            } else {
              $("#btnSav").hide();
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
      $("#btnSav").hide();
      $("#btnNext").show();
    }
  }
}
// save application
function save_application() {
  $("#btnSav").text("submitting..."); //change button text
  $("#btnSav").attr("disabled", true); //set button disable
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
          view_application(appID);
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

// cancel application
function update_applicationStatus(application_id, code, status) {
  Swal.fire({
    title: "Update Application?",
    text:
      "Do you wish to update Loan Application " + code + " to " + status + "?",
    icon: "warning",
    showCancelButton: true,
    allowOutsideClick: false,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Continue!",
    preConfirm: () => {
      return new Promise((resolve) => {
        Swal.showLoading();
        $.ajax({
          url: "/admin/loans/application-status",
          type: "POST",
          dataType: "JSON",
          data: {
            application_id: application_id,
            status: status,
          },
          success: function (data) {
            //if success reload ajax table
            if (data.status && data.error == null) {
              Swal.fire("Success!", code + " " + data.messages, "success");
              view_application(appID);
              resolve();
            } else if (data.error != null) {
              Swal.fire(data.error, data.messages, "error");
              Swal.close();
            } else {
              Swal.fire(
                "Error",
                "Something unexpected happened, try again later",
                "error"
              );
              Swal.close();
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, "error");
            Swal.close();
          },
          complete: function () {
            // Close the SweetAlert2 modal
            Swal.close();
          },
        });
      });
    },
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Success!",
        text: "Application status updated to Processing",
        icon: "success",
      });
    }
  });
}

// delete record
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
            view_application(appID);
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

// show application charges
function load_applicationCharges(charges) {
  var html = "";
  var total, paid, balance;
  var totalCharge = (totalPaid = totalBalance = 0);
  // loop thru application charges
  $.each(charges, function (index, charge) {
    var chargeId = charge.particular_id;
    var chargeName = charge.particular_name;
    var chargeType = charge.charge_method;
    var chargeValue = charge.charge;
    var chargeMode = charge.charge_mode;
    // get total paid per charge ledger
    paid = applicant_particularTransactions(appID, chargeId);
    // total charge
    if (chargeType.toLowerCase() == "amount") {
      var symbol = " " + currency;
      total = Number(chargeValue);
    }
    if (chargeType.toLowerCase() == "percent") {
      var symbol = "% of the principal";
      total = Number((chargeValue / 100) * principal);
    }
    balance = Number(total - paid); // charge balance

    totalCharge += total;
    totalPaid += paid;
    totalBalance += balance;

    // set action buttons
    if (balance <= 0) {
      actionBtn =
        '<a href="javascript:void(0)" id="addPay" class="text-success">Paid <i class="fas fa-check"></i></a>';
    } else {
      if (chargeMode.toLowerCase() == "manual") {
        actionBtn =
          '<a href="javascript:void(0)" id="addPay" class="text-danger" onclick="add_applicationPayment(' +
          "'" +
          chargeId +
          "'" +
          "," +
          "'" +
          chargeName +
          "'" +
          ')" title="Make Payment">Pay <i class="fas fa-money-bill-wave"></i></a>';
      } else {
        actionBtn =
          '<a href="javascript:void(0)" id="addPay" class="text-warning">Pending <i class="ri-timer-2-line"></i></a>';
      }
    }

    html +=
      '<div class="col-xl-4 task-card">' +
      '<div class="card custom-card task-pending-card">' +
      '<div class="card-body">' +
      '<div class="d-flex justify-content-between flex-wrap">' +
      "<div>" +
      '<p class="fw-semibold mb-3 d-flex align-items-center">' +
      chargeName +
      "&nbsp;" +
      actionBtn +
      "</p>" +
      '<p class="mb-3">' +
      'Type : <span class="fs-12 mb-1 text-muted">' +
      chargeType +
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
  $('[name="total_paid"]').val(totalPaid);
  $('[name="total_balance"]').val(totalBalance);

  // append charges html to form
  $("#applicationCharges").html(html);
}

// get all application action & statues
function get_applicantionOptions(level = null, action = null, mode = null) {
  // application levels
  $.ajax({
    url: "/admin/applications/get-levels",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      const levelKeys = Object.keys(response);
      // check the response existences
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
      $("select#level").html('<option value="">Error Occurred</option>');
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
// display application remarks
function load_applicationRemarks() {
  var color;
  $.ajax({
    url: "/admin/loans/get-applicationRemarks/" + appID,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      var remarks = "";
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          // change action color based on action Chosen
          if (
            data[i].action.toLowerCase() == "declined" ||
            data[i].action.toLowerCase() == "cancelled"
          ) {
            color = "text-danger";
          } else if (
            data[i].action.toLowerCase() == "approved" ||
            data[i].action.toLowerCase() == "disbursed"
          ) {
            color = "text-success";
          } else if (data[i].action.toLowerCase() == "review") {
            color = "text-warning";
          } else {
            color = "text-info";
          }
          // data[i].level.toLowerCase() == userPosition
          var actionButton = (editButton = deleteButton = "");
          if (data[i].staff_id == officerID || userPosition == "super admin") {
            actionButton +=
              '<div class="dropdown ms-auto">' +
              '<a href="javascript:void(0);" class="btn btn-icon btn-sm btn-light" data-bs-toggle="dropdown">' +
              '<i class="fas fa-ellipsis-h me-2 align-middle"></i>' +
              "</a>" +
              '<ul class="dropdown-menu">' +
              // edit remark button
              "<li>" +
              '<a class="dropdown-item text-info" href="javascript:void(0);"  onclick="edit_applicationRemark(' +
              data[i].id +
              ')" title="Edit Remark">' +
              '<i class="fas fa-edit"></i> Edit Remark' +
              "</a>" +
              "</li>" +
              // delete remark btn
              "<li>" +
              '<a class="dropdown-item text-danger" href="javascript:void(0);"  onclick="delete_applicationRemark(' +
              "'" +
              data[i].id +
              "'" +
              "," +
              "'" +
              data[i].level +
              "'" +
              "," +
              "'" +
              data[i].action +
              "'" +
              ')" title="Delete Remark">' +
              '<i class="fas fa-trash"></i> Delete Remark' +
              "</a>" +
              "</li>";

            actionButton += "</ul>" + "</div>";
          }
          remarks +=
            '<div class="card border border-warning custom-card">' +
            '<div class="card-header">' +
            '<div class="d-flex align-items-center w-100">' +
            '<div class="me-2">' +
            '<i class="fas fa-comment me-2 align-middle text-info"></i>' +
            "</div>" +
            '<div class="">' +
            '<div class="fs-15 fw-semibold ' +
            color +
            '">' +
            data[i].action +
            "</div>" +
            '<p class="mb-0 text-muted fs-11">Level: ' +
            data[i].level +
            ", Status: " +
            data[i].status +
            "</p>" +
            "</div>" +
            // dropdown buttons
            actionButton +
            // dropdown buttons ends
            "</div>" +
            "</div>" +
            // card body
            '<div class="card-body">' +
            data[i].remarks +
            "</div>" +
            // card footer
            '<div class="card-footer">' +
            '<div class="d-flex justify-content-between">' +
            '<div class="fs-semibold fs-14">' +
            data[i].created_at +
            "</div>" +
            '<div class="fw-semibold text-success">' +
            data[i].staff_name +
            "</div>" +
            " </div>" +
            "</div>" +
            "</div>";
        }
      } else {
        var addRemarkButtonStatus =
          appStatus == "pending" || appStatus == "processing" ? "" : "none";
        remarks +=
          '<center><a href="javascript: void(0)" class="text-info" onclick="add_applicationRemarks(' +
          "'" +
          appID +
          "'" +
          ')" title="Add Remarks" style="display: ' +
          addRemarkButtonStatus +
          '"><i class="fas fa-plus"></i> Add Remarks</a></center>';
      }
      $("#remarksCard").html(remarks);
      // hide delete and edit buttons
      if (
        appStatus == "approved" ||
        appStatus == "declined" ||
        appStatus == "disbursed"
      ) {
        $("a.editRemark").hide();
        $("a.deleteRemark").hide();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// edit application comment
function edit_applicationRemark(id) {
  save_method = "update";
  $("#statusForm")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $("#level").trigger("change");
  $("#action").trigger("change");
  $.ajax({
    url: "/admin/applications/get-remark/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="remark_id"]').val(data.id);
      $('[name="application_id"]').val(data.application_id);
      $('[name="application_code"]').val(data.application_code);
      get_applicantionOptions(data.level, data.action, "edit"); // selected
      $("textarea#newSummernote").summernote("code", data.remarks);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $(".modal-title").text("Edit " + data.application_code + " remarks");
      $("#remarks_form").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// delete application comment
function delete_applicationRemark(id, level, action) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to recover " + level + " " + action + " remark!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/remarks/delete-remark/" + id,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
            window.location.reload();
          } else {
            Swal.fire(data.error, data.messages, "error");
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    }
  });
}
// bulk delete application comment
function bulk_deleteApplicationRemarks() {
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
          " comment(s) once deleted!",
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
            url: "/admin/remarks/bulk-deleteRemarks",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Success!", data.messages, "success");
              } else if (data.error != null) {
                Swal.fire(data.error, data.messages, "error");
              } else {
                Swal.fire(
                  "Error",
                  "Something unexpected happened, try again later",
                  "error"
                );
              }
              window.location.reload();
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
    Swal.fire("Sorry!", "No comment selected :)", "error");
  }
}

// make payment
function add_applicationPayment(particular_id, particular_name) {
  particularCharge(particular_id);
  $("#paymentForm")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $('[name="application_id"]').val(id);
  $('[name="client_id"]').val(clientId);
  $('[name="account_typeId"]').val(account_typeId);
  $('[name="particular_id"]').val(particular_id);
  $("select#payment_id").trigger("change");
  transaction_types(); // load transaction types
  $("select#disbursement_method").trigger("change");
  $("textarea#viewSummernote").summernote("reset");
  $(".modal-title").text("Pay " + particular_name);
  $("#pay_modal_form").modal("show");
}

function application_particularPayables(account_typeId) {
  $.ajax({
    url: "/admin/accounts/accountType-particulars/" + account_typeId,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      var html = (chargeType = ""),
        action = "";
      if (data.length > 0 && loanApplicationCharges.length > 0) {
        var total, paid, balance;
        var totalCharge = (totalPaid = totalBalance = 0);
        for (var i = 0; i < loanApplicationCharges.length; i++) {
          var particularID = loanApplicationCharges[i].particular_id;
          if (data[i].id == particularID) {
            var charge = loanApplicationCharges[i].charge;
            var chargeMethod = loanApplicationCharges[i].charge_method;
            // if(data[i].charged.toLowerCase() == 'yes'){
            // get total paid per particular
            paid = applicant_particularTransactions(appID, data[i].id);
            // total charge
            if (chargeMethod.toLowerCase() === "amount") {
              total = Number(charge);
              chargeType = chargeMethod;
            } else if (chargeMethod.toLowerCase() === "percent") {
              total = Number((charge / 100) * principal);
              chargeType = chargeMethod + " of principal";
            } else {
              total = Number(0);
            }
            balance = Number(total - paid); // balance
            // set buttons
            if (balance <= 0) {
              action =
                '<a href="javascript:void(0)" id="addPay" class="text-success" >Paid <i class="fas fa-check"></i></a>';
            } else {
              if (data[i].charge_mode == "Manual") {
                action =
                  '<a href="javascript:void(0)" id="addPay" class="text-danger" onclick="add_applicationPayment(' +
                  "'" +
                  data[i].id +
                  "'" +
                  "," +
                  "'" +
                  data[i].particular_name +
                  "'" +
                  ')" title="Make Payment"> Pay <i class="fas fa-money-bill-wave"></i></a>';
              } else {
                action =
                  '<a href="javascript:void(0)" id="addPay" class="text-warning" title="This Payable has been set as Auto."> Pending <i class="ri-timer-2-line"></i></a>';
              }
            }
            totalCharge += total;
            totalPaid += paid;
            totalBalance += balance;
            html +=
              "<tr>" +
              "<td>" +
              Number(i + 1) +
              "</td><td>" +
              data[i].particular_name +
              "</td>" +
              "<td>" +
              charge +
              "</td><td>" +
              chargeType +
              "</td>" +
              "<td>" +
              total +
              "</td><td>" +
              paid +
              "</td>" +
              "<td>" +
              balance +
              "</td>" +
              "<td>" +
              action +
              "</td>" +
              "</tr>";
            // }
          }
        }
        html +=
          "<tr>" +
          '<td colspan="2"></td>' +
          '<td colspan="2">TOTALS</td>' +
          "<td>" +
          totalCharge +
          "</td><td>" +
          totalPaid +
          "</td>" +
          "<td>" +
          totalBalance +
          "</td>" +
          "<td></td>" +
          "</tr>";
      } else {
        var html = '<tr><td colspan="8"><center>No Data Found</center></tr>';
      }
      $("tbody#applicationCharges").html(html);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// particular payments
function applicant_particularTransactions(application_id, particular_id) {
  var totalPayments = 0;
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
          totalPayments += Number(data[i].amount);
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
  return totalPayments;
}
// particular charges
function particularCharge(particular_id) {
  var paid, total, balance;
  $.ajax({
    url: "/admin/accounts/particular/" + particular_id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      paid = applicant_particularTransactions(appID, data.id);
      if (paid != 0) {
        if (data.charge_method == "Amount") {
          total = data.charge;
          balance = Number(total - paid);
          $('[name="charge"]').val(total);
          $('[name="amount"]').val(balance);
        } else {
          total = (data.charge / 100) * principal;
          balance = Number(total - paid);
          $('[name="charge"]').val(total);
          $('[name="amount"]').val(balance);
        }
        $("input#amount").attr({ max: balance });
      } else {
        if (data.charge_method == "Amount") {
          $('[name="charge"]').val(data.charge);
          $('[name="amount"]').val(data.charge);
          $("input#amount").attr({ max: data.charge });
        } else {
          $('[name="charge"]').val((data.charge / 100) * principal);
          $('[name="amount"]').val((data.charge / 100) * principal);
          $("input#amount").attr({ max: (data.charge / 100) * principal });
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// view payment
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
// save
function save_applicationPayment() {
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnPay").text("submitting...");
  $("#btnPay").attr("disabled", true);
  // ajax adding data to database
  var formData = new FormData($("#paymentForm")[0]);
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
          $("#pay_modal_form").modal("hide");
          Swal.fire("Success!", data.messages, "success");
          application_particularPayables(account_typeId);
          view_application(appID);
          reload_table("chargesPayments");
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
function delete_particularPayment(id, name) {
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
            view_application(appID);
            reload_table("chargesPayments");
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
function bulk_deleteParticularPayments() {
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
                view_application(appID);
                reload_table("applPayments");
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

// pop add model
function add_files(menu) {
  file_module = menu;
  if (menu == "income") {
    $("#files").attr("name", "income[]");
  }
  if (menu == "expense") {
    $("#files").attr("name", "expense[]");
  }
  $("#file_form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $(".modal-title").text("Add File(s)");
  $("#modal_form").modal("show");
}
// view application files
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
        if (data.file_name) {
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
        if (data.file_name) {
          $("#file-preview div").html(
            '<img src="/uploads/applications/income/' +
              data.file_name +
              '" class="img-fluid thumbnail">'
          );
        } else {
          $("#file-preview div").html(
            '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail">'
          );
        }
      }
      if (data.type == "expense") {
        if (data.file_name) {
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
      $(".modal-title").text("View " + data.file_name);
      $("#file_view").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
function save_file() {
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnFile").text("uploading..."); //change button text
  $("#btnFile").attr("disabled", true); //set button disable
  var url;
  if (file_module == "collateral") {
    url = "/admin/loans/save-applicationFiles/" + "collateral";
  } else if (file_module == "income") {
    url = "/admin/loans/save-applicationFiles/" + "income";
  } else {
    url = "/admin/loans/save-applicationFiles/" + "expense";
  }
  // ajax adding data to database
  var formData = new FormData($("#file_form")[0]);
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
          reload_table("collaterals");
          reload_table("incomes");
          reload_table("expenses");
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
      $("#btnFile").text("Upload");
      $("#btnFile").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnFile").text("Upload");
      $("#btnFile").attr("disabled", false);
    },
  });
}
// delete
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
            reload_table("incomes");
            reload_table("expenses");
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
                Swal.fire("Sucess!", data.messages, "success");
                reload_table("collaterals");
                reload_table("incomes");
                reload_table("expenses");
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

// hide\show buttons based on application status
function buttons_display(status) {
  switch (status.toLowerCase()) {
    case "pending":
      $("#editBtn").show();
      $("div#disbursementBtn").hide();
      $("#cancelApplication").show();
      $("#declineApplication").show();
      // $(a"#processApplication").show();
      $("button#applicationRemarksBtn").show();
      $("#applicationPayments-tab").hide();
      $("#applicationRemarks-tab").hide();
      $("#actions-liBtn").removeClass("text-info");
      break;
    case "processing":
      $("#editBtn").show();
      $("div#disbursementBtn").hide();
      $("#cancelApplication").show();
      $("#declineApplication").show();
      // $("a#processApplication").hide();
      $("button#applicationRemarksBtn").show();
      // $("#applicationPayments-tab").show();
      // $("#applicationRemarks-tab").show();
      $("#actions-liBtn").removeClass("text-info");
      break;
    case "cancelled":
      $("#editBtn").hide();
      $("div#disbursementBtn").hide();
      $("#cancelApplication").hide();
      $("#declineApplication").hide();
      // $("#processApplication").hide();
      $("button#applicationRemarksBtn").hide();
      // $("#applicationPayments-tab").hide();
      // $("#applicationRemarks-tab").hide();
      $("#actions-liBtn").addClass("text-danger");
      break;
    case "approved":
      $("#editBtn").hide();
      $("div#disbursementBtn").show();
      $("#cancelApplication").show();
      $("#declineApplication").hide();
      // $("#processApplication").hide();
      $("button#applicationRemarksBtn").hide();
      // $("#applicationPayments-tab").show();
      // $("#applicationRemarks-tab").show();
      $("#actions-liBtn").addClass("text-success");
      break;
    case "disbursed":
      $("#editBtn").hide();
      $("div#disbursementBtn").hide();
      $("#cancelApplication").hide();
      $("#declineApplication").hide();
      // $("#processApplication").hide();
      $("button#applicationRemarksBtn").hide();
      // $("#applicationPayments-tab").show();
      // $("#applicationRemarks-tab").show();
      $("#actions-liBtn").addClass("text-primary");
      break;
    default:
      // Declined && disbursed
      $("#editBtn").hide();
      $("#cancelApplication").hide();
      $("div#disbursementBtn").hide();
      $("#declineApplication").hide();
      // $("#processApplication").hide();
      $("button#applicationRemarksBtn").hide();
      // $("#applicationPayments-tab").show();
      // $("#applicationRemarks-tab").show();
      $("#actions-liBtn").removeClass("text-secondary");
      break;
  }
}

function show_amortizer(id) {
  $("#statusRow").hide();
  $("div#approvalCals").show();
  $("button#btnAgreement").show();
  $("div#disbursementRow").show();
  var disbursement_date = $("input#date_disbursed").val();
  // loan calculate
  $.ajax({
    url: "/admin/loans/application/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      // console.log(data.loan_period)
      var principal = Number(data.principal);
      var interval,
        payouts,
        installments_num,
        actual_installment,
        principal_installment,
        interest_installment,
        computed_interest,
        computed_repayment,
        actual_repayment,
        actual_interest;
      if (data.interest_period === "year") {
        // space btn installments(months)
        interval = getInterval(data.repayment_frequency);
        // annual payouts based om frequency
        var payouts = Number(12 / interval);
      } else {
        interval = Number(1);
        payouts = Number(interval);
      }
      //   installments_num = Number(data.repayment_period / interval); // number of installments
      installments_num = Number(data.repayment_period); // number of installments
      // calculated installment[with decimals]
      computed_installment = Number(
        EMI(
          data.interest_type,
          principal,
          data.interest_rate,
          data.repayment_period,
          interval,
          data.interest_period,
          data.loan_period
        )
      );
      if (data.interest_type.toLowerCase() == "reducing") {
        // interest paid back per installment
        // interest_installment = Number(
        //   principal * (data.interest_rate / 100 / payouts)
        // ).toFixed(0);
        interest_installment = Number(
          (principal * (data.interest_rate / 100)) / payouts
        );
        //   principal_installment = Number(computed_installment - interest_installment);
        principal_installment = Number(principal / installments_num);
      } else {
        // interest paid back per installment
        // interest_installment = Number(
        //   principal * (data.interest_rate / 100 / payouts)
        // ).toFixed(0);

        principal_installment = Number(principal / installments_num);
        interest_installment =
          Number(computed_installment) - Number(principal_installment);
      }
      // calculated interest[with decimals]
      computed_interest = Number(
        calculateInterest(
          data.interest_type,
          principal,
          computed_installment,
          data.interest_rate,
          data.repayment_frequency,
          installments_num,
          data.repayment_period,
          data.interest_period,
          data.interest_period,
          disbursement_date
        )
      ).toFixed(2);
      // installment rounded off as set in settings
      actual_installment = Number(
        Math.ceil(computed_installment / roundOff) * roundOff
      );
      // total interest to be paid
      actual_interest = Number(
        // Math.ceil(computed_interest / roundOff) * roundOff
        Number(computed_interest % 10) == 0
          ? computed_interest
          : Math.ceil(computed_interest / roundOff) * roundOff
      );

      // installment towards principal
      //   principal_installment = Number(
      //     actual_installment - interest_installment
      //   ).toFixed(0);
      // repayment based on computed interest
      computed_repayment = Number(
        Number(principal) + Number(computed_interest)
      );
      // repayament caluclated on rounded off installment
      actual_repayment = Number(principal + actual_interest);

      var totalCharges = data.total_charges;
      var principalRecievable = principal - Number(totalCharges);

      // hide/show inputs
      if (data.reduct_charges.toLowerCase() == "savings") {
        $("div#reductCharges").show();
        $("div#principalRecievable").hide();
        selectParticulars(12);
      } else {
        $("div#reductCharges").hide();
        $("div#principalRecievable").show();
      }

      // fill form inputs
      $("input#loan_principal_amount").val(principal);
      $("input#loan_interest_type").val(data.interest_type);
      $("input#loan_interest_rate").val(data.interest_rate);
      $("input#loan_frequency_type").val(data.repayment_frequency);
      $("input#installments_no").val(installments_num);
      $("input#loan_repayment_period").val(data.repayment_period);
      $("input#loan_installment").val(computed_installment);
      $("input#applicant_interest_period").val(data.interest_period);
      $("input#applicant_interest_period").val(data.interest_period);

      $('[name="application_id"]').val(id);
      $('[name="application_code"]').val(data.application_code);
      $('[name="client_id"]').val(data.client_id);
      $('[name="principal"]').val(principal);
      $('[name="total_charges"]').val(totalCharges.toLocaleString());
      $('[name="principal_receivable"]').val(
        principalRecievable.toLocaleString()
      );
      $('[name="installments_num"]').val(installments_num.toLocaleString());
      $('[name="computed_installment"]').val(
        computed_installment.toLocaleString()
      );
      $('[name="actual_installment"]').val(actual_installment.toLocaleString());
      $('[name="computed_interest"]').val(computed_interest.toLocaleString());
      $('[name="actual_interest"]').val(actual_interest.toLocaleString());
      $('[name="computed_repayment"]').val(computed_repayment.toLocaleString());
      $('[name="principal_installment"]').val(
        principal_installment.toLocaleString()
      );
      $('[name="interest_installment"]').val(
        Number(interest_installment).toLocaleString()
      );
      $('[name="actual_repayment"]').val(actual_repayment.toLocaleString());
      $('[name="principal"]').val(principal.toLocaleString());
      //
      get_applicantionOptions(data.level, data.action, "disburse");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// show more actions on change of application status
$("select#status").on("change", function () {
  var status = this.value;
  var id = $('[name="application_id"]').val();
  if (status != "" || status != 0) {
    $.ajax({
      url: "/admin/applications/get-levels/" + id + "/" + status,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $("select#level").html('<option value="">-- select --</option>');
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i])
              .text(data[i])
              .appendTo($("select#level"));
          }
        } else {
          $("select#level").html('<option value="">No Status</option>');
        }
      },
      error: function (err) {
        $("select#level").html('<option value="">Error Occured</option>');
      },
    });

    if (status.toLowerCase() == "disbursed") {
      $("div#approvalCals").show();
      $.ajax({
        url: "/admin/loans/application/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
          var interval,
            payouts,
            installments_num,
            actual_installment,
            principal_installment,
            interest_installment,
            computed_interest,
            computed_repayment,
            actual_repayment,
            actual_interest;
          if (data.interest_period == "year") {
            // space btn installments(months)
            interval = getInterval(data.repayment_frequency);
            // annual payouts based om frequency
            var payouts = Number(12 / interval);
          } else {
            interval = Number(1);
            payouts = Number(interval);
          }
          installments_num = Number(data.repayment_period / interval);
          // calculated installment[with decimals]
          computed_installment = Number(
            EMI(
              data.interest_type,
              principal,
              data.interest_rate,
              data.repayment_period,
              interval,
              data.interest_period,
              data.loan_period
            )
          );
          if (data.interest_type.toLowerCase() == "reducing") {
            // interest paid back per installment
            interest_installment = Number(
              (principal * (data.interest_rate / 100)) / payouts
            );
            // principal_installment = Number(computed_installment - interest_installment);
            principal_installment = Number(principal / installments_num);
          } else {
            // interest paid back per installment
            principal_installment = Number(principal / installments_num);
            interest_installment = Number(
              computed_installment - principal_installment
            );
          }
          // calculated interest[with decimals]
          computed_interest = calculateInterest(
            data.interest_type,
            principal,
            computed_installment,
            data.interest_rate,
            data.repayment_frequency,
            installments_num,
            data.repayment_period,
            data.interest_period
          );
          // installment rounded off as set in settings
          actual_installment = Number(
            Math.ceil(computed_installment / roundOff) * roundOff
          );
          // total interest to be paid
          actual_interest = Number(
            Math.ceil(computed_interest / roundOff) * roundOff
          );
          // installment towards principal
          principal_installment = Number(
            actual_installment - interest_installment
          ).toFixed(0);
          // repayment based on computed interest
          computed_repayment = Number(principal + computed_interest);
          // repayament caluclated on rounded off installment
          actual_repayment = Number(principal + actual_interest);

          var totalCharges = data.total_charges;
          var principalRecievable = principal - Number(totalCharges);

          // hide/show inputs
          if (data.reduct_charges.toLowerCase() == "savings") {
            $("div#reductCharges").show();
            selectParticulars(12);
            $("div#totalPrincipal").removeClass("col-md-4");
            $("div#totalCharges").removeClass("col-md-4");
            $("div#principalRecievable").removeClass("col-md-4");
            $("div#totalPrincipal").addClass("col-md-3");
            $("div#totalCharges").addClass("col-md-3");
            $("div#principalRecievable").addClass("col-md-3");
          } else {
            $("div#reductCharges").hide();
          }

          // fill form inputs
          $("input#loan_principal_amount").val(principal);
          $("input#loan_interest_type").val(data.interest_type);
          $("input#loan_interest_rate").val(data.interest_rate);
          $("input#loan_frequency_type").val(data.repayment_frequency);
          $("input#installments_no").val(installments_num);
          $("input#loan_repayment_period").val(data.repayment_period);
          $("input#loan_installment").val(computed_installment);
          $("input#applicant_interest_period").val(data.interest_period);

          $('[name="application_id"]').val(id);
          $('[name="application_code"]').val(data.application_code);
          $('[name="client_id"]').val(data.client_id);
          $('[name="principal"]').val(principal);
          $('[name="total_charges"]').val(totalCharges.toLocaleString());
          $('[name="principal_receivable"]').val(
            principalRecievable.toLocaleString()
          );
          $('[name="installments_num"]').val(installments_num.toLocaleString());
          $('[name="computed_installment"]').val(
            computed_installment.toLocaleString()
          );
          $('[name="actual_installment"]').val(
            actual_installment.toLocaleString()
          );
          $('[name="computed_interest"]').val(
            computed_interest.toLocaleString()
          );
          $('[name="actual_interest"]').val(actual_interest.toLocaleString());
          $('[name="computed_repayment"]').val(
            computed_repayment.toLocaleString()
          );
          $('[name="principal_installment"]').val(
            principal_installment.toLocaleString()
          );
          $('[name="interest_installment"]').val(
            interest_installment.toLocaleString()
          );
          $('[name="actual_repayment"]').val(actual_repayment.toLocaleString());
          $('[name="principal"]').val(principal.toLocaleString());
          if (
            data.loan_agreement &&
            imageExists(
              "/uploads/applications/agreements/" + data.loan_agreement
            )
          ) {
            $("#upload-label").text("Upload New Agreement");
            $("#loanAgreement-preview").html(
              '<img src="/uploads/applications/agreements/' +
                data.loan_agreement +
                '" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
            );
          } else {
            $("#upload-label").text("Upload Agreement");
            $("#loanAgreement-preview").html(
              '<img src="/assets/dist/img/doc.jpg" alt="Agreement preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
            );
          }
          //
          get_applicantionOptions(data.level, data.action, "disburse");
          $(".modal-title").text(
            "Disburse " +
              principal.toLocaleString() +
              " for " +
              data.application_code
          );
          $("#remarks_form").modal("show");
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    } else {
      if (status.toLowerCase() == "approved") {
        $("div#approvalCals").hide();
        $("div#loan-agreement").show();
      } else {
        $("div#approvalCals").hide();
        $("div#loan-agreement").hide();
        $("div#actionRemarks").removeClass("col-md-8");
        $("div#actionRemarks").addClass("col-md-12");
      }
      $.ajax({
        url: "/admin/loans/application/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
          // $('textarea#newSummernote').summernote('code', data.application_remarks);
          if (data.agreement) {
            $("#upload-label").text("Upload New Agreement");
            $("#loanAgreement-preview").html(
              '<img src="/uploads/applications/agreements/' +
                data.agreement +
                '" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
            );
          } else {
            $("#upload-label").text("Upload Agreement");
            $("#loanAgreement-preview").html(
              '<img src="/assets/dist/img/nophoto.jpg" alt="Agreement preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
            );
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    }
  }
});
// get credit module particulars
$("select#creditModule").change(function () {
  var crModule = $("#creditModule").val();
  if (crModule != "") {
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "/admin/accounts/accountType-particulars/" + crModule,
      success: function (data) {
        $("select#cr_particular").html(
          '<option value="">-- select --</option>'
        );
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].particular_name)
              .appendTo($("select#cr_particular"));
          }
        } else {
          $("select#cr_particular").html(
            '<option value="">No Particular</option>'
          );
        }
      },
      error: function (err) {
        $("select#cr_particular").html(
          '<option value="">Error Occured</option>'
        );
      },
    });
  }
});

// pick data from dirsbure model and show it in the loan agreement
$("#btnAgreement").on("click", function () {
  // pick from form
  var dateDisbursed = new Date($("#date_disbursed").val());
  var dayDisbursed = dateDisbursed.getDate();
  var monthDisbursed = dateDisbursed.toLocaleString("default", {
    month: "short",
  });
  var yearDisbursed = dateDisbursed.getFullYear();
  var customDate = dayDisbursed + " " + monthDisbursed + " " + yearDisbursed;
  var totalInstallments = $("#installments_num").val();
  var installment = $("#actual_installment").val();
  var repayment = $("#actual_repayment").val();
  var disbursementMode = $("#disbursed_by").val();
  // display on agreement
  $("span#disbursement-date").text(customDate);
  $("span#day-disbursed").text(dayDisbursed);
  $("span#month-disbursed").text(monthDisbursed);
  $("span#year-disbursed").text(yearDisbursed);
  $("span#installments").text(
    totalInstallments + " (" + convertNumberToWords(totalInstallments) + ")"
  );
  $("span#installment").text(
    installment +
      " (" +
      convertNumberToWords(installment.replace(/\,/g, "")) +
      " only)"
  );
  $("span.total-repayment").text(
    repayment.toLocaleString() +
      "= (" +
      convertNumberToWords(repayment.replace(/\,/g, "")) +
      ")"
  );
  $("span.disbursement-mode").text(disbursementMode);
});

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
    $("select#loan_frequency").html('<option value="">--Select---</option>');
    $('[name="loan_period"]').val("");
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
        var applicant_product_id = $('[name="applicant_product_id"]').val();
        var applicant_loan_frequency = $(
          '[name="applicant_loan_frequency"]'
        ).val();
        var applicant_loan_period = $('[name="applicant_loan_period"]').val();
        var applicant_repayment_period = $(
          '[name="applicant_repayment_period"]'
        ).val();
        var applicant_repayment_frequency = $(
          '[name="applicant_repayment_frequency"]'
        ).val();

        var applicant_interest_period = $(
          '[name="applicant_interest_period"]'
        ).val();
        var applicant_interest_rate = $(
          '[name="applicant_interest_rate"]'
        ).val();

        if (
          save_method == "update" &&
          Number(pID) === Number(applicant_product_id)
        ) {
          $('[name="interest_rate"]').val(applicant_interest_rate);
          generateCustomeSettings("interest_period", applicant_interest_period);
          $('[name="interest_type"]').val();
          generateCustomeSettings("loan_frequency", applicant_loan_frequency);
          $('[name="loan_period"]').val(applicant_loan_period);
          $('[name="repayment_period"]').val(applicant_repayment_period);
          generateCustomeSettings("repayments", applicant_repayment_frequency);
        } else {
          $('[name="interest_rate"]').val(data.interest_rate);
          generateCustomeSettings("interest_period", data.interest_period);
          $('[name="interest_type"]').val(data.interest_type);
          generateCustomeSettings("loan_frequency", data.loan_frequency);
          $('[name="loan_period"]').val(data.loan_period);
          $('[name="repayment_period"]').val(data.repayment_period);
          generateCustomeSettings("repayments", data.repayment_freq);
        }
        /*
        if (save_method == "add") {
          generateCustomeSettings("interest_period", data.interest_period);
          $('[name="interest_rate"]').val(data.interest_rate);
          $('[name="interest_type"]').val(data.interest_type);
          $('[name="repayment_period"]').val(
            // data.repayment_period + " " + data.repayment_duration
            data.repayment_period
          );
        }
        */
        // $('[name="repayment_freq"]').val(data.repayment_freq);
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
