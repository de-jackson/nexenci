$(document).ready(function () {
    generateCustomeSettings("relationships");
    generateCustomSettings("occupation");

    if (product_id && principal) {
        loanProductsByLoanPrincipal(principal, product_id);
    }
});

let loanLoanProductsByLoanAmount = (principal) => {
    // remove errors on input fields
    $("input").on("input", function () {
        $(this).parent().removeClass("has-error");
        $(this).next().empty();
    });

    if (principal == 0 || principal == "") {
        $('[name="interest_rate"]').val("");
        $('[name="interest_type"]').val("");
        $('[name="repayment_period"]').val("");
        $('[name="repayment_freq"]').val("");
        $("select#product_id").html('<option value="">-- Select --</option>');
        $('[name="total_charges"]').val("");
        $("#productCharges").html("");
    } else {
        if (!isNaN(principal)) {
            loanProductsByLoanPrincipal(principal, product_id);
        } else {
            alert("Only digit is needed!");
            $("#principal").val("");
        }
    }
};

$("select#product_id").on("change", function () {
    var product_id = this.value;
    var principalAmount = $("#principal").val();

    if (product_id == 0 || product_id == "") {
        $('[name="interest_rate"]').val("");
        $('[name="interest_type"]').val("");
        $('[name="repayment_period"]').val("");
        $('[name="repayment_freq"]').val("");
        $('[name="total_charges"]').val("");
        $("#productCharges").html("");

    } else {
        // totalApplicationCharges(principalAmount);
        $.ajax({
            url: "/client/products/" + product_id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('[name="interest_rate"]').val(data.interest_rate);
                $('[name="interest_type"]').val(data.interest_type);
                $('[name="repayment_period"]').val(data.repayment_period + " " + data.repayment_duration);
                $('[name="repayment_freq"]').val(data.repayment_freq);

                // charges
                var html = "";
                if (data.charges) {
                    var totalCharges = 0;
                    $.each(data.charges, function (index, charge) {
                        var chargeId = charge.particular_id;
                        var chargeName = charge.particular_name;
                        var chargeType = charge.charge_method;
                        var chargeValue = charge.charge;
                        var chargeMode = charge.charge_mode;
                        if (chargeType.toLowerCase() == "amount") {
                            var symbol = " " + currency;
                            charge = Number(chargeValue);
                        }
                        if (chargeType.toLowerCase() == "percent") {
                            var symbol = "% of the principal amount";
                            charge = Number((chargeValue / 100) * principalAmount);

                        }
                        totalCharges += charge;

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

                    // display total charges in form
                    $('[name="total_charges"]').val(totalCharges);
                } else {
                    html += '<p class="fw-semibold text-primary text-center mb-3">No Applicable Charges found</p>';
                }
                $("#productCharges").html(html);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.fire(textStatus, errorThrown, "error");
            },
        });
    }
});

var loanFinancialTotal = function income_expense_totals() {
    var salary = Number($("#salary").val());
    var farming = Number($("#farming").val());
    var business = Number($("#business").val());
    var others = Number($("#others").val());
    var rent = Number($("#rent").val());
    var education = Number($("#education").val());
    var medical = Number($("#medical").val());
    var transport = Number($("#transport").val());
    var exp_others = Number($("#exp_others").val());
    var total = parseFloat(salary) + parseFloat(farming) + parseFloat(business) + parseFloat(others);
    var exp_total = parseFloat(rent) + parseFloat(education) + parseFloat(medical) + parseFloat(transport) + parseFloat(exp_others);
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

function submitApplication(save_method) {
    $("#btnSubmit").text("submitting..."); //change button text
    $("#btnSubmit").attr("disabled", true); //set button disable
    $(".form-group").removeClass("has-error"); // clear error class
    $(".help-block").empty(); // clear error string
    aId = $('[name="id"]').val();
    var url;
    if (save_method == "add") {
        url = "/client/loan/application/store/create";
    } else {
        url = "/client/loan/application/store/update";
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
                    Swal.fire("Success!", data.messages, "success");
                    setInterval(function () {
                        window.location.replace("/client/loans/module/applications");
                    }, 2000);
                } else if (data.error != null) {
                    Swal.fire(data.error, data.messages, "error");
                } else {
                    Swal.fire("Error", "Something unexpected happened, try again later", "error");
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
            $("#btnSubmit").text("Submit Application"); //change button text
            $("#btnSubmit").attr("disabled", false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, "error");
            $("#btnSubmit").text("Submit Application"); //change button text
            $("#btnSubmit").attr("disabled", false); //set button enable
        },
    });
}

function cancelLoanApplication(id, code) {
    Swal.fire({
        title: "Cancel Application?",
        text: "Do you wish to CANCEL Loan Application " + code + "?",
        icon: "warning",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Cancel!",
        preConfirm: () => {
            return new Promise((resolve) => {
                Swal.showLoading();
                $.ajax({
                    url: "/client/reports/types/cancelapplication/" + id,
                    type: "post",
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
                            resolve();
                        } else if (data.error != null) {
                            Swal.fire(data.error, data.messages, "error");
                            Swal.close();
                        } else {
                            Swal.fire("Error", "Something unexpected happened, try again later", "error");
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

let totalApplicationCharges = (principal) => {
    // remove errors on input fields
    $("input").on("input", function () {
        $(this).parent().removeClass("has-error");
        $(this).next().empty();
    });

    var productId = $('[name="product_id"]').val();
    if (productId) {
        $.ajax({
            url: "/client/products/" + productId,
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
                            charge = Number((chargeValue / 100) * principal);
                        }
                        totalCharges += charge;
                    });
                }
                // display total charges in form
                $('[name="total_charges"]').val(totalCharges);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('[name="total_charges"]').val("Error Occurred");
            },
        });
    }
};
