// compute totals
var totals = function income_expense_totals() {
  var salary = Number(removeCommasFromAmount($("#salary").val()));
  var farming = Number(removeCommasFromAmount($("#farming").val()));
  var business = Number(removeCommasFromAmount($("#business").val()));
  var others = Number(removeCommasFromAmount($("#others").val()));
  var rent = Number(removeCommasFromAmount($("#rent").val()));
  var education = Number(removeCommasFromAmount($("#education").val()));
  var medical = Number(removeCommasFromAmount($("#medical").val()));
  var transport = Number(removeCommasFromAmount($("#transport").val()));
  var exp_others = Number(removeCommasFromAmount($("#exp_others").val()));
  var total =
    parseFloat(salary) +
    parseFloat(farming) +
    parseFloat(business) +
    parseFloat(others);
  var exp_total =
    parseFloat(rent) +
    parseFloat(education) +
    parseFloat(medical) +
    parseFloat(transport) +
    parseFloat(exp_others);
  var difference = Number(total - exp_total);
  $("#total").attr("readonly", "readonly");
  $("#total").val(total.toLocaleString());
  $("#exp_total").attr("readonly", "readonly");
  $("#exp_total").val(exp_total.toLocaleString());
  if (difference > 0) {
    $("#difference").attr("readonly", "readonly");
    $("#difference").val(difference.toLocaleString());
    $("#dif_status").attr("readonly", "readonly");
    $("#dif_status").val("Surplus");
  } else if (difference == 0) {
    $("#difference").attr("readonly", "readonly");
    $("#difference").val(difference);
    $("#dif_status").attr("readonly", "readonly");
    $("#dif_status").val("Balanced");
  } else {
    $("#difference").attr("readonly", "readonly");
    $("#difference").val(-+difference);
    $("#dif_status").attr("readonly", "readonly");
    $("#dif_status").val("Deficit");
  }
};

// interval between loan no_of_installments
var getInterval = function (frequency) {
  switch (frequency.toLowerCase()) {
    case "daily":
      interval = Number(1 / 30);
      break;
    case "weekly":
      interval = Number(1 / 4);
      break;
    case "bi-weekly":
      interval = Number(1 / 2);
      break;
    case "monthly":
      interval = Number(1);
      break;
    case "bi-monthly":
      interval = Number(2);
      break;
    case "quarterly":
      interval = Number(3);
      break;
    case "termly":
      interval = Number(4);
      break;
    case "bi-annual":
      interval = Number(6);
      break;
    case "annually":
      interval = Number(12);
      break;
    default:
      interval = Number(1);
  }
  return interval;
};

// interval between loan no_of_installments
var getIntervalInDays = function (frequency) {
  switch (frequency.toLowerCase()) {
    case "daily":
      interval = Number(1);
      break;
    case "weekly":
      interval = Number(7);
      break;
    case "bi-weekly":
      interval = Number(14);
      break;
    case "monthly":
      interval = Number(30);
      break;
    case "bi-monthly":
      interval = Number(60);
      break;
    case "quarterly":
      interval = Number(90);
      break;
    case "termly":
      interval = Number(120);
      break;
    case "bi-annual":
      interval = Number(180);
      break;
    case "annually":
      interval = Number(365);
      break;
    default:
      interval = Number(1);
  }
  return interval;
};

// monthly loan installment
var EMI = function (
  method,
  principal,
  rate,
  period,
  interval,
  interest_period,
  loan_period
) {
  if (interest_period === "year") {
    // Calculate the total number of payments in a year.
    payouts = Number(12 / interval);
    // convert period to years
    var loanTerm = Number(period) / payouts;
    // Calculate the total number of monthly payments
    // var numberOfPayments = Number(period / interval);
    var numberOfPayments = Number(period);
    // Number of months in a year
    var reducingLoanTerm = 12;
  } else {
    // Calculate the total number of payments based per loan interest period.
    payouts = Number(interval);
    // convert period based per loan interest period
    var loanTerm = Number(loan_period) / payouts;
    // Number based per loan interest period
    var reducingLoanTerm = 1;
    // Calculate the total number of monthly payments
    var numberOfPayments = Number(period / interval);
  }

  var loanInstallment;

  if (method.toLowerCase() == "reducing") {
    /**
     * M = ([P * r * (1 + r)^n] / [((1 + r)^n - 1)])
     * Where:
     * M is the monthly installment
     * P is the loan amount (principal)
     * r is the monthly interest rate (calculated by dividing the annual interest rate by 12)
     * n is the total number of monthly payments (calculated by multiplying the loan term by 12)
     */
    // Convert annual interest rate to monthly interest rate
    var monthlyInterestRate = parseFloat(rate / 100 / reducingLoanTerm);
    // Calculate the monthly installment using the formula
    var numerator =
      principal *
      monthlyInterestRate *
      Math.pow(1 + monthlyInterestRate, numberOfPayments);

    var denominator = Math.pow(1 + monthlyInterestRate, numberOfPayments) - 1;

    loanInstallment = numerator / denominator;
  }

  if (method.toLowerCase() == "flat") {
    /**
     * M = (P + (P * r * n)) / (n * 12)
     * Where:
     * M is the monthly installment
     * P is the loan amount (principal)
     * r is the flat interest rate
     * n is the loan term in years
     */

    // Calculate the interest amount
    var interestAmount = principal * (rate / 100) * loanTerm;
    // Calculate the total amount repayable
    var totalAmountRepayable = principal + interestAmount;
    // Calculate the monthly installment using the formula
    loanInstallment = totalAmountRepayable / numberOfPayments;
  }

  // Return the monthly installment
  return loanInstallment;
};

// show repayment plan
function showRepaymentPlan() {
  if ($("#showPlan").is(":checked")) {
    $("div#showRepaymentPlan").show(300);
  } else {
    $("div#showRepaymentPlan").hide(300);
  }
}

// add remarks\update application status
function add_applicationRemarks(id, action = null) {
  // payment methods
  selectPaymentMethod();
  $("button#btnAgreement").hide();
  $("select#payment_id").trigger("change");
  $("select#disbursement_method").trigger("change");
  $("select#level").trigger("change");
  $("select#action").trigger("change");
  $("textarea#newSummernote").summernote("reset");
  if (!action) {
    save_method = "add";
    $("#statusForm")[0].reset();
    $(".form-group").removeClass("has-error");
    $(".help-block").empty();
    $.ajax({
      url: "/admin/loans/application/" + id,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $('[name="application_id"]').val(id);
        $('[name="application_code"]').val(data.application_code);
        $('[name="client_id"]').val(data.client_id);
        get_applicantionOptions(data.level, data.action);
        $("#level").trigger("change");
        $("#action").trigger("change");
        $("#statusRow").show();
        $("div#approvalCals").hide();
        $("#modal-title").text(
          data.application_code + " - " + data.name + " Remarks"
        );
        $("#remarks_form").modal("show");
      },
      error: function (jqXHR, textStatus, errorThrown) {
        Swal.fire(textStatus, errorThrown, "error");
      },
    });
  } else {
    save_method = action;
    $("#statusForm")[0].reset();
    $(".form-group").removeClass("has-error");
    $(".help-block").empty();
    if (action == "disburse") {
      $("#statusRow").hide();
      $("div#approvalCals").show();
      $("button#btnAgreement").show();
      $("div#disbursementRow").show();
      var disbursement_date = $("input#date_disbursed").val();
      var savingsProductsData = {};
      // loan calculate
      $.ajax({
        url: "/admin/loans/application/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
          savingsProductsData = data.savingsProducts;

          // Trigger change event after savingsProductsData is loaded
          $('[name="reduct_charges"]')
            .val(data.reduct_charges)
            .trigger("change");
          if (data.reduct_charges.toLowerCase() == "savings") {
            $("div#reduct-Charges").show();
            $("div#principalRecievable").hide();
          } else {
            $("div#reduct-Charges").hide();
            $("div#principalRecievable").show();
          }
          // var repayment_period = (data.repayment_period);
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
          // check the loan interest period
          if (data.interest_period == "year") {
            // convert loan based per year
            interval = getInterval(data.repayment_frequency);
            payouts = Number(12 / interval); // annual payouts based om frequency
            installments_num = Number(data.repayment_period / interval);
          } else {
            interval = Number(1);
            payouts = Number(interval); // annual payouts based om frequency
            installments_num = Number(data.repayment_period / interval);
          }
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
            principal_installment = Number(
              computed_installment - interest_installment
            );
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
          // repayment calculated on rounded off installment
          actual_repayment = Number(principal + actual_interest);

          var totalCharges = data.total_charges;
          var principalRecievable = principal - Number(totalCharges);

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

          $("select#reductcharges").on("change", function () {
            var reductcharges = $(this).val();

            if (reductcharges.toLowerCase() == "savings") {
              $("div#reduct-Charges").show();
              $("div#principalRecievable").hide();
              $("select#productId").empty(); // Clear existing options

              if (savingsProductsData && savingsProductsData.length > 0) {
                // Populate savings products options
                $("select#productId").html(
                  '<option value="">--select --</option>'
                );
                $.each(savingsProductsData, function (index, product) {
                  $("select#productId").append(
                    `<option value="${product.product_id}">${product.product_name}</option>`
                  );
                });
              } else {
                $("select#productId").html(
                  '<option value="">No Product</option>'
                );
              }

              $("select#productId").on("change", function () {
                var selectedProduct = savingsProductsData.find(
                  (item) => item.product_id == $(this).val()
                );
                selectParticulars(
                  12,
                  selectedProduct.savings_particular_id,
                  "particularId"
                );
              });
            } else {
              $("div#reduct-Charges").hide();
              $("div#principalRecievable").show();
            }
          });

          // disbursement particulars
          selectParticulars(
            3,
            data.principal_particular_id,
            "disparticular_id"
          );
          selectParticulars(
            19,
            data.interest_particular_id,
            "interest_particular_id"
          );

          //
          get_applicantionOptions(data.level, data.action, "disburse");
          $("#modal-title").text(
            "Disburse: " + data.name + " - " + data.application_code
          );
          $("#remarks_form").modal("show");
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    }
  }
}

// save\update application status
function save_applicationRemarks() {
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  var url;
  if (save_method == "disburse") {
    url = "/admin/loans/disbursement";
    header = "Disburse Loan?";
    text = "Are you sure you want to disburse the loan?";
  } else if (save_method == "add") {
    url = "/admin/applications/save-remark";
    header = "Save Action?";
    text =
      "The Application Status, Level might get updated is you save this action, Do you wish to continue?";
  } else {
    url = "/admin/applications/edit-remark";
    header = "Edit Remarks?";
    text = "Remarks will be edited if you press continue?";
  }
  Swal.fire({
    title: header,
    text: text,
    icon: "warning",
    showCancelButton: true,
    allowOutsideClick: false,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Continue!",
    preConfirm: () => {
      return new Promise((resolve) => {
        Swal.showLoading();
        var formData = new FormData($("#statusForm")[0]);
        // Make AJAX request to the controller
        $.ajax({
          url: url,
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          dataType: "JSON",
          success: function (data) {
            if (!data.inputerror) {
              if (data.status == 200) {
                $("#remarks_form").modal("hide");
                Swal.fire("Success!", data.messages, "success");
                if (title.toLowerCase() == "applications") {
                  count_applications();
                  reload_table("pendingApplications");
                  reload_table("processApplications");
                  reload_table("reviewApplications");
                  reload_table("cancelledApplications");
                  reload_table("declinedApplications");
                  reload_table("approvedApplications");
                } else {
                  view_application(id);
                  load_applicationRemarks();
                }
                // Handle the response here
                resolve();
              } else if (data.status == 500) {
                Swal.fire(data.error, data.messages, "error");
                // Swal.close();
              } else {
                Swal.fire(
                  "Error",
                  "Something unexpected happened, try again later",
                  "error"
                );
                // Swal.close();
              }
              $("#btnApprove").text("Submit");
              $("#btnApprove").attr("disabled", false);
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
              $("#btnApprove").text("Submit");
              $("#btnApprove").attr("disabled", false);
              Swal.close();
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, "error");
            Swal.close();
            $("#btnApprove").text("Submit");
            $("#btnApprove").attr("disabled", false);
          },
          complete: function () {
            // Close the SweetAlert2 modal
            Swal.close();
            $("#btnApprove").text("Submit");
            $("#btnApprove").attr("disabled", false);
          },
        });
      });
    },
  }).then((result) => {
    if (result.isConfirmed) {
      if (save_method == "disburse") {
        text = "Loan Disbursed successfully";
      } else if (save_method == "add") {
        text = "Application Remarks saved successfully";
      } else {
        text = "Application Remarks edited successfully";
      }
      // Show a success message
      Swal.fire({
        title: "Success!",
        text: text,
        icon: "success",
      });
    }
  });
}

$("input#date_disbursed").on("change", function () {
  var interest_type = $("input#loan_interest_type").val();
  var principal = $("input#loan_principal_amount").val();
  var computed_installment = $("input#loan_installment").val();
  var interest_rate = $("input#loan_interest_rate").val();
  var repayment_freq = $("input#loan_frequency_type").val();
  var installments_num = $("input#installments_no").val();
  var repayment_period = $("input#loan_repayment_period").val();
  var interest_period = $("input#applicant_interest_period").val();

  var date_disbursed = this.value;
  if (date_disbursed == 0 || date_disbursed == "") {
    alert("Disbursement Date is not provided!");
    $("input#showPlan").attr("checked", true);
    $("div#showRepaymentPlan").hide(300);
  } else {
    var disbursement_date = $("input#date_disbursed").val();
    // calculated interest[with decimals]
    computed_interest = Number(
      calculateInterest(
        interest_type,
        Number(principal),
        Number(computed_installment),
        interest_rate,
        repayment_freq,
        installments_num,
        repayment_period,
        interest_period,

        disbursement_date
      )
    ).toFixed(2);
    // show loan repayment plan
    $("div#showRepaymentPlan").hide(300);
    $("div#showRepaymentPlan").show(300);

    console.log(
      interest_type,
      Number(principal),
      Number(computed_installment),
      interest_rate,
      repayment_freq,
      installments_num,
      repayment_period,
      interest_period,
      disbursement_date
    );
  }
});

// calculate total interest && generate repayment plan
var calculateInterest = function (
  interest_type,
  principal,
  installment,
  rate,
  frequency,
  no_of_installments,
  period,
  interestPeriod,
  disbursement_date = null,
  installsCovered = null,
  installsMissed = null
) {
  let total_repayment =
    (total_principal =
    total_principal =
    total_interest =
    interest_installment =
    principal_installment =
      0);
  0;
  var date, due_date, principal_balance;
  if (interestPeriod == "year") {
    interval = getInterval(frequency);
    var payouts = Number(12 / interval);
  } else {
    interval = Number(1);
    payouts = Number(interval);
  }

  var color = "";
  var html = "";
  var dateOptions = {
    weekday: "short",
    year: "numeric",
    month: "short",
    day: "numeric",
  };

  // var date = new Date();

  var date =
    disbursement_date == null ? new Date() : new Date(disbursement_date);

  var originalPrincipal = Number(principal);
  principal_balance = Number(principal);
  // calculate installment payment date
  var calculateDueDate = function () {
    switch (frequency.toLowerCase()) {
      case "daily":
        pay_day = new Date(date.setDate(date.getDate() + 1));
        break;
      case "weekly":
        pay_day = new Date(date.setDate(date.getDate() + 7));
        break;
      case "bi-weekly":
        pay_day = new Date(date.setDate(date.getDate() + 14));
        break;
      case "monthly":
        pay_day = new Date(date.setDate(date.getDate() + 30));
        break;
      case "bi-monthly":
        pay_day = new Date(date.setDate(date.getDate() + 60));
        break;
      case "quarterly":
        pay_day = new Date(date.setDate(date.getDate() + 90));
        break;
      case "termly":
        pay_day = new Date(date.setDate(date.getDate() + 120));
        break;
      case "bi-annual":
        pay_day = new Date(date.setDate(date.getDate() + 180));
        break;
      case "annually":
        pay_day = new Date(date.setDate(date.getDate() + 365));
        break;
    }

    due_date = pay_day.toLocaleDateString("en-GB", dateOptions);
  };
  // create table row
  var addToHTML = function (color) {
    var balance = principal_balance < 0 ? "(0)" : principal_balance.toFixed(0);

    html +=
      '<tr class="' +
      color +
      '">' +
      '<td class="' +
      color +
      '">' +
      index +
      "</td>" +
      '<td class="' +
      color +
      '">' +
      due_date +
      "</td>" +
      '<td class="' +
      color +
      ' text-right">' +
      principal.toLocaleString() +
      "</td>" +
      '<td class="' +
      color +
      ' text-right">' +
      principal_installment.toLocaleString() +
      "</td>" +
      '<td class="' +
      color +
      ' text-right">' +
      interest_installment.toLocaleString() +
      '<td class="' +
      color +
      ' text-right">' +
      installment.toLocaleString() +
      "</td>" +
      '<td class="' +
      color +
      ' text-right">' +
      balance.toLocaleString() +
      "</td>" +
      "</tr>";
  };

  let index = 1;
  while (index <= no_of_installments) {
    var installsPaid = Number(installsCovered - installsMissed);
    var currentInstallment = installsCovered + 1;
    // colorize installment rows
    // paid installment
    if (index <= installsPaid) {
      color = "text-success";
    }
    // current installment
    if (index == currentInstallment) {
      color = "text-info";
    }
    // missed installment
    if (
      installsMissed > 0 &&
      index > installsPaid &&
      index < currentInstallment
    ) {
      color = "text-danger";
    }

    // current installment
    if (index > currentInstallment) {
      color = "text-dark";
    }
    // calculate date for each installment payment
    calculateDueDate();
    // if (interest_type.toLowerCase() == 'reducing') {
    //     interest_installment = Number(((principal_balance * (rate / 100)) / payouts).toFixed(0));
    // } else{
    //     interest_installment = Number((originalPrincipal * (rate / 100)) / payouts).toFixed(0);
    // }
    // principal_installment = Number((installment - interest_installment).toFixed(0));
    if (interest_type.toLowerCase() == "reducing") {
      interest_installment = Number(
        (principal_balance * (rate / 100)) / payouts
      );
      //   principal_installment = Number(installment - interest_installment);
      principal_installment = Number(originalPrincipal / no_of_installments);
    }
    if (interest_type.toLowerCase() == "flat") {
      principal_installment = Number(originalPrincipal / no_of_installments);
      interest_installment = Number(installment - principal_installment);
    }
    principal = principal_balance;
    principal_balance -= principal_installment;
    // generate table rows
    addToHTML(color);
    // calculate loan total amounts
    total_principal += principal_installment;
    total_interest += Number(interest_installment);
    date = date;
    index++;
  }
  var totalLoan = Number(total_principal + total_interest);
  // attarch total row toFixed(0).
  html +=
    '<tr class="fw-bold text-right">' +
    '<th class="text-center fw-bold" colspan="3">TOTAL</th><th class="fw-bold text-right">' +
    total_principal.toLocaleString() +
    "</th>" +
    '<th class="fw-bold text-right">' +
    total_interest.toLocaleString() +
    '</th><th class="fw-bold text-right">' +
    totalLoan.toLocaleString() +
    "</th><th></th>" +
    "</tr>";
  // attarch rows to table body
  $("tbody#repaymentPlan").html(html);

  return total_interest;
};

function clientLoanHistory(clientId = null) {
  $.ajax({
    url: "/admin/client/history",
    type: "POST",
    dataType: "JSON",
    data: {
      history: "loans",
      client_id: clientId,
    },
    success: function (response) {
      if (!response.error) {
        var appl_html = (disb_html = "");
        // applications history
        if (response.applications && response.applications.length > 0) {
          // loop through application history
          $.each(response.applications, function (index, application) {
            if (appID == application.id) {
              var link =
                '<a href="javascript:void(0)" class="card-link text-primary m-1"><u>This Application</u></a>';
            } else {
              var link =
                '<a href="/admin/application/info/' +
                application.ecryptedId +
                '" class="card-link text-success m-1"><u>View Application</u></a>';
            }
            appl_html +=
              '<div class="col-md-4 col-sm-6">' +
              '<div class="card border border-success custom-card">' +
              '<div class="card-header">' +
              '<div class="card-title">' +
              application.application_code +
              "(" +
              application.product_name +
              ")</div>" +
              "</div>" +
              '<div class="card-body">' +
              '<h6 class="card-subtitle fw-semibold mb-2">' +
              currency +
              " " +
              application.principal +
              "(" +
              application.status +
              ")</h6>" +
              '<p class="card-text">' +
              application.repayment_period +
              " " +
              application.repayment_duration +
              " " +
              application.repayment_frequency +
              " loan at " +
              application.interest_rate +
              "% Per " +
              application.interest_period +
              " calculated on a " +
              application.interest_type +
              " basis</p>" +
              "</div>" +
              '<div class="card-footer">' +
              '<a href="javascript:void(0);" class="card-link m-1">Applied: <span class=" text-info">' +
              application.application_date +
              "</span></a>" +
              link +
              "</div>" +
              "</div>" +
              "</div>";
          });
          $("div#applicationsHistory").html(appl_html);
        } else {
          $("div#applicationsHistory").html(
            '<p class="fw-semibold text-info text-center mb-3">No Client Application History Found!</p>'
          );
        }

        // disbursements history
        if (response.disbursements && response.disbursements.length > 0) {
          // loop thruogh disbursement history
          $.each(response.disbursements, function (index, disbursement) {
            if (disID == disbursement.id) {
              var link =
                '<a href="javascript:void(0)" class="card-link text-primary m-1"><u>This Disbursement</u></a>';
            } else {
              var link =
                '<a href="/admin/disbursement/info/' +
                disbursement.ecryptedId +
                '" class="card-link text-success m-1"><u>View Disbursement</u></a>';
            }
            disb_html +=
              '<div class="col-md-4 col-sm-6">' +
              '<div class="card border border-success custom-card">' +
              '<div class="card-header">' +
              '<div class="card-title">' +
              disbursement.disbursement_code +
              "(" +
              disbursement.product_name +
              ")</div>" +
              "</div>" +
              '<div class="card-body">' +
              '<h6 class="card-subtitle fw-semibold mb-2">Total Loan: ' +
              currency +
              " " +
              disbursement.actual_repayment +
              "(" +
              disbursement.status +
              ")</h6>" +
              '<p class="card-text">Principal: ' +
              currency +
              " " +
              disbursement.principal +
              ", Interest: " +
              disbursement.actual_interest +
              ", Paid: " +
              currency +
              " " +
              disbursement.total_collected +
              ", Balance: " +
              currency +
              " " +
              disbursement.total_balance +
              ".</p>" +
              '<p class="card-text">' +
              disbursement.repayment_frequency +
              disbursement.repayment_period +
              " " +
              disbursement.repayment_duration +
              "(" +
              disbursement.loan_period_days +
              " days) " +
              " loan at " +
              disbursement.interest_rate +
              "% Per " +
              disbursement.interest_period +
              " calculated on a " +
              disbursement.interest_type +
              " basis</p>" +
              "</div>" +
              '<div class="card-footer">' +
              '<a href="javascript:void(0);" class="card-link m-1">' +
              disbursement.days_remaining +
              ' days left<span class=" text-' +
              (disbursement.class == "Arrears" ? "danger" : "info") +
              '">(' +
              disbursement.class +
              ")</span></a>" +
              link +
              "</div>" +
              "</div>" +
              "</div>";
          });
          $("div#disbursementsHistory").html(disb_html);
        } else {
          $("div#disbursementsHistory").html(
            '<p class="fw-semibold text-info text-center mb-3">No Client Disbursement History Found!</p>'
          );
        }
      } else {
        $("div#applicationsHistory").html(
          '<p class="fw-semibold text-info text-center mb-3"><b>' +
            response.error +
            ": </b>" +
            response.messages +
            "</p>"
        );
        $("div#disbursementsHistory").html(
          '<p class="fw-semibold text-info text-center mb-3"><b>' +
            response.error +
            ": </b>" +
            response.messages +
            "</p>"
        );
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus + " " + errorThrown + "error");
    },
  });
}

// compute total application charges
let total_applicationCharges = (principal) => {
  // remove errors on input fields
  $("input").on("input", function () {
    $(this).parent().removeClass("has-error");
    $(this).next().empty();
  });

  var productId = $('[name="product_id"]').val();
  if (productId) {
    $.ajax({
      url: "/admin/loans/product/" + productId,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        var totalCharges = 0;
        if (data.charges) {
          $.each(data.charges, function (index, charge) {
            var chargeId = charge.particular_id;
            var chargeName = charge.particular_name;
            var chargeType = charge.charge_method;
            var chargeValue = charge.charge;
            var chargeMode = charge.charge_mode;
            if (chargeType.toLowerCase() == "amount") {
              charge = Number(chargeValue);
            }
            if (chargeType.toLowerCase() == "percent") {
              charge = Number(
                (chargeValue / 100) * removeCommasFromAmount(principal)
              );
            }
            totalCharges += charge;
          });
        }
        // display total charges in form
        $('[name="total_charges"]').val(totalCharges.toLocaleString());
      },
      error: function (jqXHR, textStatus, errorThrown) {
        $('[name="total_charges"]').val("Error Occured");
      },
    });
  }
};

// update application status
function application_status(application_id, code, status) {
  Swal.fire({
    title: "Update Status?",
    text: code + " Loan Application status will be updated to " + status + "!",
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
          type: "post",
          dataType: "JSON",
          data: { application_id: application_id, status: status },
          success: function (data) {
            if (!data.inputerror) {
              if (data.status && data.error == null) {
                Swal.fire("Success!", code + " " + data.messages, "success");
                if (title.toLowerCase() == "applications") {
                  count_applications();
                  reload_table("pendingApplications");
                  reload_table("processApplications");
                  reload_table("reviewApplications");
                  reload_table("cancelledApplications");
                  reload_table("declinedApplications");
                  reload_table("approvedApplications");
                } else {
                  view_application(appID);
                }
                resolve();
              } else if (data.error != null) {
                Swal.fire(data.error, data.messages, "error");
              } else {
                Swal.fire(
                  "Error",
                  "Something unexpected happened, try again later",
                  "error"
                );
              }
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
        text: "Application status updated Successfully",
        icon: "success",
      });
    }
  });
}

/**
 * Fetches and displays disbursement record details.
 *
 * @param {number} id - The unique identifier of the disbursement record.
 * @returns {void}
 */
function disbursementRecord(id) {
  $.ajax({
    url: "/admin/loans/disbursement/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("strong#cName").text(data.name);
      $("strong#cContact").text(data.mobile);
      $("strong#cNontact2").text(data.alternate_no);
      $("strong#cEmail").text(data.email);
      $("strong#cAccountNo").text(data.account_no);
      $("strong#cBalance").text(Number(data.account_balance).toLocaleString());
      $("strong#cAddress").text(data.residence);
      $("strong#tLoan").text(Number(data.actual_repayment).toLocaleString());
      $("strong#lBalance").text(Number(data.total_balance).toLocaleString());
      $("strong#installment").text(
        Number(data.actual_installment).toLocaleString()
      );
      $("strong#periodDays").text(data.loan_period_days);
      $("strong#daysCovered").text(data.days_covered);
      $("strong#daysLeft").text(data.days_remaining);
      $("strong#createdAt").text(data.created_at);
      $("strong#expiryDate").text(data.loan_expiry_date);
      $("strong#expiryDay").text(data.expiry_day);
      $('[name="particular_id"]').val(data.particular_id);
      // $('[name="contact"]').val(data.mobile);
      setPhoneNumberWithCountryCode($("#contact"), data.mobile);
      $('[name="amount"]').val(
        Number(data.actual_installment).toLocaleString()
      );
      $('[name="principal_taken"]').val(
        Number(data.principal).toLocaleString()
      );
      if (
        data.photo &&
        imageExists("/uploads/clients/passports/" + data.photo)
      ) {
        $("#cPhoto-preview div").html(
          '<img src="/uploads/clients/passports/' +
            data.photo +
            '" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
        );
      } else {
        $("#cPhoto-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
        );
      }

      if (data.savingsProducts) {
        // Add options
        $.each(data.savingsProducts, function (index, product) {
          // Also append to payment_id with a label to indicate savings
          if (account_typeId && account_typeId != 12) {
            $("select#payment_id").append(
              '<option value="' +
                product.product_code +
                '"><b>Savings - ' +
                product.product_name +
                "</b></option>"
            );
          }
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
