$(document).ready(function () {
    // update disbursement balances
    auto_update_disbursementBalances()
    // update disbursement days, status, class etc
    auto_update_disbursementDays();
});

// compute totals
var totals = function income_expense_totals() {
    var salary = Number($("#salary").val());
    var farming = Number($("#farming").val());
    var business = Number($("#business").val());
    var others = Number($("#others").val());
    var rent = Number($("#rent").val());
    var education = Number($("#education").val());
    var medical = Number($("#medical").val());
    var transport = Number($("#transport").val());
    var exp_others = Number($("#exp_others").val());
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
    $("#total").val(total);
    $("#exp_total").attr("readonly", "readonly");
    $("#exp_total").val(exp_total);
    if (difference > 0) {
        $("#difference").attr("readonly", "readonly");
        $("#difference").val(difference);
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

// monthly loan installment
var EMI = function (method, principal, rate, period, interval) {
    // Calculate the total number of payments in a year.
    payouts = Number(12 / interval);
    // convert period to years
    var loanTerm = Number(period) / payouts;
    // Calculate the total number of monthly payments
    var numberOfPayments = Number(period / interval);
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
        var monthlyInterestRate = parseFloat(rate / 100 / 12);
        // Calculate the monthly installment using the formula
        var numerator = (principal *
            monthlyInterestRate *
            Math.pow(1 + monthlyInterestRate, numberOfPayments));

        var denominator = (Math.pow(1 + monthlyInterestRate, numberOfPayments) - 1);

        loanInstallment = (numerator / denominator);
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
        loanInstallment = (totalAmountRepayable / numberOfPayments);
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
    // disbursement particulars
    disbursement_particular();
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
                $("#remarks_form").modal("show");
                $("#statusRow").show();
                $("div#approvalCals").hide();
                $("#modal-title").text(data.application_code + " Status Update");
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
            // loan calculate
            $.ajax({
                url: "/admin/loans/application/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
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
                    interval = getInterval(data.repayment_freq); // space btn installments(months)
                    payouts = Number(12 / interval); // annual payouts based om frequency
                    installments_num = Number(data.repayment_period / interval); // number of installments
                    if (data.interest_type.toLowerCase() == "reducing") {
                        // calculated installment[with decimals]
                        computed_installment = Number(
                            EMI(
                                data.interest_type,
                                principal,
                                data.interest_rate,
                                data.repayment_period,
                                interval
                            )
                        );
                        // interest paid back per installment
                        interest_installment = Number(
                            principal * (data.interest_rate / 100 / payouts)
                        ).toFixed(0);
                    } else {
                        // calculatedinstallment[with decimals]
                        computed_installment = Number(
                            EMI(
                                data.interest_type,
                                principal,
                                data.interest_rate,
                                data.repayment_period,
                                interval
                            )
                        );
                        // interest paid back per installment
                        interest_installment = Number(
                            principal * (data.interest_rate / 100 / payouts)
                        ).toFixed(0);
                    }
                    // calculated interest[with decimals]
                    computed_interest = Number(
                        calculateInterest(
                            data.interest_type,
                            principal,
                            computed_installment,
                            data.interest_rate,
                            data.repayment_freq,
                            installments_num,
                            data.repayment_period,
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
                    principal_installment = Number(
                        actual_installment - interest_installment
                    ).toFixed(0);
                    // repayment based on computed interest
                    computed_repayment = Number(
                        Number(principal) + Number(computed_interest)
                    );
                    // repayament caluclated on rounded off installment
                    actual_repayment = Number(principal + actual_interest);

                    var totalCharges = data.total_charges;
                    var principalRecievable = (principal - Number(totalCharges));

                    // hide/show inputs
                    if (data.reduct_charges.toLowerCase() == 'savings') {
                        $('div#reductCharges').show()
                        $('div#principalRecievable').hide()
                        selectParticulars(12)
                    } else {
                        $('div#reductCharges').hide()
                        $('div#principalRecievable').show()
                    }

                    // fill form inputs
                    $("input#loan_principal_amount").val(principal);
                    $("input#loan_interest_type").val(data.interest_type);
                    $("input#loan_interest_rate").val(data.interest_rate);
                    $("input#loan_frequency_type").val(data.repayment_freq);
                    $("input#installments_no").val(installments_num);
                    $("input#loan_repayment_period").val(data.repayment_period);
                    $("input#loan_installment").val(computed_installment);

                    $('[name="application_id"]').val(id);
                    $('[name="application_code"]').val(data.application_code);
                    $('[name="client_id"]').val(data.client_id);
                    $('[name="principal"]').val(principal);
                    $('[name="total_charges"]').val(totalCharges.toLocaleString());
                    $('[name="principal_receivable"]').val(principalRecievable.toLocaleString());
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

                    //
                    get_applicantionOptions(data.level, data.action, "disburse");
                    $("#remarks_form").modal("show");
                    $("#modal-title").text(
                        "Disburse " +
                        principal.toLocaleString() +
                        " for " +
                        data.application_code
                    );
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
                                view_application(id);
                                load_applicationRemarks();
                                // Handle the response here
                                resolve();
                            } else if (data.status == 500) {
                                Swal.fire(data.error, data.messages, "error");
                                // Swal.close();
                            } else {
                                Swal.fire(
                                    "Error", "Something unexpected happened, try again later", "error"
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

    var date_disbursed = this.value;
    if (date_disbursed == 0 || date_disbursed == "") {
        alert("Disbursement Date is not provided!");
        $("input#showPlan").attr('checked', true);
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
                disbursement_date
            )
        ).toFixed(2);
        // show loan repayment plan
        $("div#showRepaymentPlan").hide(300);
        $("div#showRepaymentPlan").show(300);
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
    disbursement_date = null,
    installsCovered = null,
    installsMissed = null
) {
    let total_repayment =
        (total_principal =
            total_interest =
            interest_installment =
            principal_installment =
            0);
    var date, due_date, principal_balance;
    interval = getInterval(frequency);
    var payouts = Number(12 / interval);
    var color = "";
    var html = "";
    var dateOptions = {
        weekday: "short",
        year: "numeric",
        month: "short",
        day: "numeric",
    };

    var date = new Date();

    var date =
        disbursement_date == null ? new Date() : new Date(disbursement_date);

    var originalPrincipal = Number(principal);
    principal_balance = Number(principal);
    // calculate installment payment date
    var calculateDueDate = function () {
        switch (interval) {
            case 0.25:
                pay_day = new Date(date.setDate(date.getDate() + 7));
                break;
            case 0.5:
                pay_day = new Date(date.setDate(date.getDate() + 14));
                break;
            case 1:
                pay_day = new Date(date.setDate(date.getDate() + 30));
                break;
            case 2:
                pay_day = new Date(date.setDate(date.getDate() + 60));
                break;
            case 3:
                pay_day = new Date(date.setDate(date.getDate() + 90));
                break;
            case 4:
                pay_day = new Date(date.setDate(date.getDate() + 120));
                break;
            case 6:
                pay_day = new Date(date.setDate(date.getDate() + 180));
                break;
            case 12:
                pay_day = new Date(date.setDate(date.getFullYear() + 1));
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
            "<td>" +
            index +
            "</td><td>" +
            due_date +
            "</td>" +
            '<td align="right">' +
            principal.toFixed(0).toLocaleString() +
            '</td><td align="right">' +
            principal_installment.toFixed(0).toLocaleString() +
            "</td>" +
            '<td align="right">' +
            interest_installment.toFixed(0) +
            '</td><td align="right">' +
            installment.toFixed(0).toLocaleString() +
            "</td>" +
            '<td align="right">' +
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
            principal_installment = Number(installment - interest_installment);
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
    // attarch total row
    html +=
        "<tr>" +
        '<th class="text-center" colspan="3">TOTAL</th><th class="text-right">' +
        currency +
        " " +
        total_principal.toFixed(0).toLocaleString() +
        "</th>" +
        '<th class="text-right">' +
        currency +
        " " +
        total_interest.toFixed(0).toLocaleString() +
        '</th><th class="text-right">' +
        currency +
        " " +
        totalLoan.toFixed(0).toLocaleString() +
        "</th><th></th>" +
        "</tr>";
    // attarch rows to table body
    $("tbody#repaymentPlan").html(html);

    return total_interest;
};

// load disbursement particular
function disbursement_particular(accountType_Id = 3, particularID = null) {
    var particularSelect = $("select#disparticular_id");
    // selectParticulars(accountType_Id, particularID, 'disparticular_id');
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/admin/accounts/accountType-particulars/" + accountType_Id,
        success: function (data) {
            if (data.length > 0) {
                if (data.length > 1) {
                    particularSelect.html('<option value="">-- select --</option>');
                }
                $.each(data, function (index, item) {
                    var option = $('<option>', {
                        value: item.id,
                        text: item.particular_name
                    });
                    if (item.id == particularID) {
                        option.attr("selected", true)
                    }
                    particularSelect.append(option);
                });
            } else {
                particularSelect.html('<option value="">No Particular</option>');
            }
        },
        error: function (err) {
            particularSelect.html('<option value="">Error Occured</option>');
        },
    });
}
