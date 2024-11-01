var save_method;
var account_typeId = 18; //revenue from loans particulars
// table IDs
var pendingTableId = "pendingApplications";
var processTableId = "processApplications";
var reviewTableId = "reviewApplications";
var declinedTableId = "declinedApplications";
var approvedTableId = "approvedApplications";
var cancelledTableId = "cancelledApplications";
// dataTables urls
var pendingTableDataUrl = "/admin/loans/generate-loanApplications/pending";
var processTableDataUrl = "/admin/loans/generate-loanApplications/processing";
var reviewTableDataUrl = "/admin/loans/generate-loanApplications/review";
var declinedTableDataUrl = "/admin/loans/generate-loanApplications/declined";
var approvedTableDataUrl = "/admin/loans/generate-loanApplications/approved";
var cancelledTableDataUrl = "/admin/loans/generate-loanApplications/cancelled";
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "application_code" },
  { data: "principal", render: $.fn.DataTable.render.number(",") },
  { data: "name" },
  { data: "product_name" },
  { data: "rate" },
  { data: "period" },
  { data: "level" },
  { data: "action" },
  { data: "actions", orderable: false, searchable: false },
];
// dataTables buttons configs
function createButtonConfig(status, tableId, permissions) {
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
      titleAttr: "Add New (Pending) " + title,
      action: function () {
        add_application();
      },
    });
  }

  // Show import button
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
      titleAttr: "Import " + title,
      action: function () {
        import_applications();
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
      titleAttr: "Bulky Delete " + status + " " + title,
      action: function () {
        bulk_deleteApplications();
      },
    });
  }

  // Show reload table button by default
  buttonsConfig.push({
    text: '<i class="fa fa-refresh"></i>',
    className: "btn btn-sm btn-warning",
    titleAttr: "Reload  " + status + " " + title + " Information",
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
        titleAttr: "Export " + status + " " + title + " To Excel",
        text: '<i class="fas fa-file-excel"></i>',
        filename: status + " " + title + " Information",
        extension: ".xlsx",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        },
      },
      {
        extend: "pdf",
        className: "btn btn-sm btn-danger",
        titleAttr: "Export " + status + " " + title + " To PDF",
        text: '<i class="fas fa-file-pdf"></i>',
        filename: status + " " + title + " Information",
        extension: ".pdf",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        },
      },
      {
        extend: "csv",
        className: "btn btn-sm btn-info",
        titleAttr: "Export " + status + " " + title + " Information To CSV",
        text: '<i class="fas fa-file-csv"></i>',
        filename: status + " " + title + " Information",
        extension: ".csv",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        },
      },
      {
        extend: "copy",
        className: "btn btn-sm btn-secondary",
        titleAttr: "Copy " + status + " " + title + " Information",
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
          "</h3><h4 class='text-center text-bold'> " +
          status +
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
        filename: status + " " + title + " Information",
      }
    );
  }

  return buttonsConfig;
}

var pendingBtnsConfig = createButtonConfig(
  "Pending",
  pendingTableId,
  userPermissions
);
var processBtnsConfig = createButtonConfig(
  "Processing",
  processTableId,
  userPermissions
);
var reviewBtnsConfig = createButtonConfig(
  "Review",
  reviewTableId,
  userPermissions
);
var declinedBtnsConfig = createButtonConfig(
  "Declined",
  declinedTableId,
  userPermissions
);
var approvedBtnsConfig = createButtonConfig(
  "Approved",
  approvedTableId,
  userPermissions
);
var cancelledBtnsConfig = createButtonConfig(
  "Cancelled",
  cancelledTableId,
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
  // pending applications
  initializeDataTable(
    pendingTableId,
    pendingTableDataUrl,
    columnsConfig,
    pendingBtnsConfig
  );
  // process applications
  initializeDataTable(
    processTableId,
    processTableDataUrl,
    columnsConfig,
    processBtnsConfig
  );
  // review  applications
  initializeDataTable(
    reviewTableId,
    reviewTableDataUrl,
    columnsConfig,
    reviewBtnsConfig
  );
  // declined applications
  initializeDataTable(
    declinedTableId,
    declinedTableDataUrl,
    columnsConfig,
    declinedBtnsConfig
  );
  // approved applications
  initializeDataTable(
    approvedTableId,
    approvedTableDataUrl,
    columnsConfig,
    approvedBtnsConfig
  );
  // cancelled applications
  initializeDataTable(
    cancelledTableId,
    cancelledTableDataUrl,
    columnsConfig,
    cancelledBtnsConfig
  );

  // default agreement place holder
  $("#agreement-label").text("Upload Photo");
  $("#agreement-preview").html(
    '<img src="/assets/dist/img/nophoto.jpg" alt="Image preview" id="preview-agreement" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
  );
  // load clients
  selectClient();
  // load loan products
  selectProducts("loans");
  // load ajax options
  generateCustomeSettings("relationships");
  generateCustomeSettings("repayments");
  generateCustomSettings("occupation");
  generateCustomeSettings("loan_frequency");
  // generateCustomeSettings('interest_period');
  // load application particulars
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/admin/accounts/accountType-particulars/" + account_typeId,
    success: function (data) {
      $("select#particular_id").html('<option value="">-- select --</option>');
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          $("<option />")
            .val(data[i].id)
            .text(data[i].particular_name)
            .appendTo($("select#particular_id"));
        }
      } else {
        $("select#particular_id").html(
          '<option value="">No Particular</option>'
        );
      }
    },
    error: function (err) {
      $("select#particular_id").html(
        '<option value="">Error Occurred</option>'
      );
    },
  });

  // show application counter badges
  count_applications();

  remarksTextEditor();

  //check all table inputs
  $("#check-allPending").click(function () {
    $(".data-checkPending").prop("checked", $(this).prop("checked"));
  });
  $("#check-allProcessing").click(function () {
    $(".data-checkProcessing").prop("checked", $(this).prop("checked"));
  });
  $("#check-allDeclined").click(function () {
    $(".data-checkDeclined").prop("checked", $(this).prop("checked"));
  });
  $("#check-allReview").click(function () {
    $(".data-checkReview").prop("checked", $(this).prop("checked"));
  });
  $("#check-allApproved").click(function () {
    $(".data-checkApproved").prop("checked", $(this).prop("checked"));
  });
  $("#check-allCancelled").click(function () {
    $(".data-checkCancelled").prop("checked", $(this).prop("checked"));
  });
});

function count_applications() {
  $.ajax({
    type: "GET",
    dataType: "JSON",
    url: "/counter/applications",
    success: function (data) {
      $("span#pending-applications").text(
        parseInt(data.pending) > 0 ? data.pending : 0
      );
      $("span#processing-applications").text(
        parseInt(data.processing) > 0 ? data.processing : 0
      );
      $("span#declined-applications").text(
        parseInt(data.declined) > 0 ? data.declined : 0
      );
      $("span#approved-applications").text(
        parseInt(data.approved) > 0 ? data.approved : 0
      );
      $("span#cancelled-applications").text(
        parseInt(data.cancelled) > 0 ? data.cancelled : 0
      );
      $("span#review-applications").text(
        parseInt(data.review) > 0 ? data.review : 0
      );
    },
    error: function (err) {
      console.log("Error Occured</option>");
    },
  });
}
function remarksTextEditor() {
  /* Summernote Validation */

  var summernoteForm = $(".form-validate-summernote");
  var summernoteElement = $(".summernote");

  var summernoteValidator = summernoteForm.validate({
    errorElement: "div",
    errorClass: "is-invalid",
    validClass: "is-valid",
    ignore: ":hidden:not(.summernote),.note-editable.card-block",
    errorPlacement: function (error, element) {
      // Add the `help-block` class to the error element
      error.addClass("invalid-feedback");
      // console.log(element);
      if (element.prop("type") === "checkbox") {
        error.insertAfter(element.siblings("label"));
      } else if (element.hasClass("summernote")) {
        error.insertAfter(element.siblings(".note-editor"));
      } else {
        error.insertAfter(element);
      }
    },
  });

  summernoteElement.summernote({
    height: 300,
    callbacks: {
      onChange: function (contents, $editable) {
        // Note that at this point, the value of the `textarea` is not the same as the one
        // you entered into the summernote editor, so you have to set it yourself to make
        // the validation consistent and in sync with the value.
        summernoteElement.val(
          summernoteElement.summernote("isEmpty") ? "" : contents
        );

        // You should re-validate your element after change, because the plugin will have
        // no way to know that the value of your `textarea` has been changed if the change
        // was done programmatically.
        summernoteValidator.element(summernoteElement);
      },
    },
  });
}

function exportApplicationForm() {
  var application_id = $('[name="id"]').val();
  window.location.assign("/admin/loans/applicationform/" + application_id);
}
// show repayment plan
function showPlan() {
  if ($("#checkboxId").is(":checked")) {
    $("div#showRepaymentPlan").show(300);
  } else {
    $("div#showRepaymentPlan").hide(300);
  }
}
// pop add model
function add_application() {
  save_method = "add";
  $("ul#myTab2").show();
  $("button#btnNext").show();
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#formRow").show();
  $("#importRow").hide();
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
  $("select#loan_frequency").trigger("change");
  $("select#state").trigger("change");
  $("select#state2").trigger("change");
  $("select#relation").trigger("change");
  $("select#relation2").trigger("change");
  $("textarea#addSummernote").summernote("reset");
  // hide back n submit button
  $("#btnSav").hide();
  $("#btnBack").hide();
  $(".modal-title").text("New Loan Application"); // Set Title to Bootstrap modal title
  $("#modal_form").modal("show"); // show bootstrap modal
  setPhoneNumberWithCountryCode($("#relation_contact"), "");
  setPhoneNumberWithCountryCode($("#relation_alt_contact"), "");
  setPhoneNumberWithCountryCode($("#relation_contact2"), "");
  setPhoneNumberWithCountryCode($("#relation_alt_contact2"), "");
}

function import_applications() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("button#btnNext").hide();
  $("button#btnSav").show();
  $("ul#myTab2").hide();
  $("#importRow").show();
  $("#formRow").hide();
  $("#export").hide();
  $('[name="id"]').val(0);
  $('[name="mode"]').val("import");
  // hide submit button
  $("#btnSav").hide();
  $("#btnBack").hide();
  $(".modal-title").text("Import Application(s)");
  $("#modal_form").modal("show"); // show bootstrap modal
}
// pop edit model
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
// Image preview
var previewIDImageFile = function (event) {
  var output = document.getElementById("preview-Id");
  output.removeAttribute("class");
  output.src = URL.createObjectURL(event.target.files[0]);
  output.onload = function () {
    URL.revokeObjectURL(output.src);
  };
};
// view employee photo
function view_collateralPhoto(id) {
  $.ajax({
    url: "/admin/loans/application/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $("#photo-preview").show();
      if (data.security_photo) {
        $("#photo-view div").html(
          '<img src="/uploads/pendingApplications/collaterals/' +
            data.security_photo +
            '" class="img-fluid thumbnail">'
        );
      } else {
        $("#photo-view div").html(
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
// bulk delete
function bulk_deleteApplications() {
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
          " application(s) once deleted!",
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
            url: "/admin/loans/applicationBulk-delete",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Success!", data.messages, "success");
                count_applications();
                reload_table("pending");
                reload_table("process");
                reload_table("review");
                reload_table("approved");
                reload_table("declined");
                reload_table("issued");
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
    Swal.fire("Sorry!", "No loan application selected....", "error");
  }
}

// pop payment
function add_Payment(id, client_id, principal) {
  $("#paymentForm")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $('[name="application_id"]').val(id);
  $('[name="account_typeId"]').val(account_typeId);
  $('[name="client_id"]').val(client_id);
  $('[name="entry_menu"]').val("financing");
  particularCharge(principal);
  $(".modal-title").text("Add Application Payment");
  $("#pay_modal_form").modal("show");
}
// particular charges
function particularCharge(principal) {
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
            $('[name="charge"]').val((data.charge / 100) * principal);
            $('[name="amount"]').val((data.charge / 100) * principal);
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire("Error!", "", "error");
        },
      });
    }
  });
}
// save payment
function save_payments() {
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnAppPay").text("submitting...");
  $("#btnAppPay").attr("disabled", true);
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
          count_applications();
          reload_table("pending");
          reload_table("process");
          reload_table("review");
          reload_table("approved");
          reload_table("declined");
          reload_table("issued");
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
      $("#btnAppPay").text("Submit");
      $("#btnAppPay").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnAppPay").text("Submit");
      $("#btnAppPay").attr("disabled", false);
    },
  });
}

// cancel application
function update_applicationStatus(id, code, status) {
  Swal.fire({
    title: "Update Application?",
    text: "Do you wish to update Loan Application " + code + " to "+ status +"?",
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
            application_id: id,
            status: status,
          },
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
    $('[name="shares_bal"]').val("");
    $('[name="loan_bal"]').val("");
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
        $('[name="shares_bal"]').val(Number(data.sharesBalance).toLocaleString());
        $('[name="loan_bal"]').val(Number(data.disbursements.totalBalance).toLocaleString());
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

