$(document).ready(function () {
  //check all
  selectBranch();
});

// get\show selected branches
function selectBranch(branch_id = null) {
  $.ajax({
    url: "/client/reports/types/branches",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        $(".branch_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == branch_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $(".branch_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["branch_name"] +
              "</option>"
          );
        });
      } else {
        $(".branch_id").html('<option value="">No Branch</option>');
      }
    },
    error: function (err) {
      $(".branch_id").html('<option value="">Error Occurred</option>');
    },
  });
}
// get\show selected departments
function selectDepartment(department_id = null) {
  $.ajax({
    url: "/client/reports/types/departments",
    type: "POST",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        $(".department_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == department_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $(".department_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["department_name"] +
              "</option>"
          );
        });
      } else {
        $(".department_id").html('<option value="">No Department</option>');
      }
    },
    error: function (err) {
      $(".department_id").html('<option value="">Error Occurred</option>');
    },
  });
}

// get\show selected currencies
function selectCurrency(currency_id = null) {
  $.ajax({
    url: "/client/reports/types/currencies",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        $(".currency_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == currency_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $(".currency_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["symbol"] +
              " ~ " +
              data["currency"] +
              "</option>"
          );
        });
      } else {
        $(".currency_id").html('<option value="">No currency</option>');
      }
    },
    error: function (err) {
      $(".currency_id").html('<option value="">Error Occurred</option>');
    },
  });
}
// get\show selected clients
function selectClient(client_id = null) {
  $.ajax({
    url: "/client/reports/types/clients",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        // $(".client_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == client_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $(".client_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["name"] +
              "</option>"
          );
        });
      } else {
        $(".client_id").html('<option value="">No Client</option>');
      }
    },
    error: function (err) {
      $(".client_id").html('<option value="">Error Occurred</option>');
    },
  });
}
// get loan products
function selectProduct(product_id = null) {
  $.ajax({
    url: "/client/reports/types/products",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        $("select#product_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == product_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#product_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["product_name"] +
              "</option>"
          );
        });
      } else {
        $("select#product_id").html('<option value="">No Product</option>');
      }
    },
    error: function (err) {
      $("select#product_id").html('<option value="">Error Occurred</option>');
    },
  });
}

function generateGender() {
  $.ajax({
    url: "/client/reports/types/gender",
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      $("select#gender").html('<option value="">-- Select --</option>');
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          if (keys[index] == gender) {
            var selection = "selected";

            $("select#gender").append(
              '<option value="' +
                keys[index] +
                '" ' +
                selection +
                ">" +
                keys[index] +
                "</option>"
            );
          } else {
            $("<option />")
              .val(keys[index])
              .text(keys[index])
              .appendTo($("select#gender"));
          }
        }
      } else {
        $("select#gender").html('<option value="">No Gender</option>');
      }
    },
    error: function (err) {
      $("select#gender").html('<option value="">Error Occurred</option>');
    },
  });
}

function generateStatus() {
  $.ajax({
    url: "/client/reports/types/accountstatus",
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      $("select#status").html('<option value="">-- Select --</option>');
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          if (keys[index] == accountStatus) {
            var selection = "selected";

            $("select#status").append(
              '<option value="' +
                keys[index] +
                '" ' +
                selection +
                ">" +
                keys[index] +
                "</option>"
            );
          }

          // $("<option />")
          //     .val(keys[index])
          //     .text(keys[index])
          //     .appendTo($("select#status"));
        }
      } else {
        $("select#status").html('<option value="">No Status</option>');
      }
    },
    error: function (err) {
      $("select#status").html('<option value="">Error Occurred</option>');
    },
  });
}

function generateStaff(staff_id = null) {
  $.ajax({
    url: "/client/reports/types/staff",
    type: "POST",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        $("select#staff_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (staff_id && data["id"] == staff_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#staff_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["staff_name"] +
              " [" +
              data["staffID"] +
              "]" +
              "</option>"
          );
        });
      } else {
        $("select#staff_id").html('<option value="">No Staff</option>');
      }
    },
  });
}

function generateAppointmentType() {
  $.ajax({
    url: "/client/reports/types/appointmenttypes",
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      $("select#appointment_type").html(
        '<option value="">Select Appointment Type</option>'
      );
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#appointment_type"));
        }
      } else {
        $("select#appointment_type").html(
          '<option value="">No Appointment Type</option>'
        );
      }
    },
    error: function (err) {
      $("select#appointment_type").html(
        '<option value="">Error Occurred</option>'
      );
    },
  });
}

function generateStaffAccountTypes() {
  $.ajax({
    url: "/client/reports/types/staffaccounttypes",
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      $("select#staff_account_type").html(
        '<option value="">Select Account Type</option>'
      );
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#staff_account_type"));
        }
      } else {
        $("select#staff_account_type").html(
          '<option value="">No Account Type</option>'
        );
      }
    },
    error: function (err) {
      $("select#staff_account_type").html(
        '<option value="">Error Occurred</option>'
      );
    },
  });
}

function generateCustomeSettings(menu) {
  $.ajax({
    url: "/client/reports/types/" + menu,
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      $("select#" + menu).html('<option value="">-- Select --</option>');
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#" + menu));
        }
      } else {
        $("select#" + menu).html('<option value="">No ' + menu + "</option>");
      }
    },
    error: function (err) {
      $("select#" + menu).html('<option value="">Error Occurred</option>');
    },
  });
}

function generateCustomSettings(menu, menuKey) {
  $.ajax({
    url: "/client/reports/types/" + menu,
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      // const data = JSON.parse(jsonString);
      // const data = $.parseJSON(jsonString);
      // convert object to key's array
      const keys = Object.keys(data);
      var options = '<option value="">--select---</option>';
      if (keys.length > 0) {
        keys.forEach((key) => {
          const value = data[key];
          if (key == menuKey) {
            options += '<option value="' + key + '">' + value + "</option>";
          }
          if (menuKey == null) {
            options += '<option value="' + key + '">' + value + "</option>";
          }
        });
        $("." + menu).html(options);
      } else {
        $("." + menu).html('<option value="">No ' + menu + "</option>");
      }
    },
    error: function (err) {
      $("." + menu).html('<option value="">Error Occurred</option>');
    },
  });
}

/**
 * Selects and populates payment methods in a dropdown menu.
 *
 * @param {number} [payment_id=null] - The ID of the selected payment method.
 *
 * @returns {void}
 */
function selectPaymentMethod(payment_id = null) {
  var $paymentSelect = $("select#payment_id");

  // Clear existing options
  $paymentSelect.html("");

  // Add default option
  $paymentSelect.append('<option value="">-- select --</option>');

  $.ajax({
    url: "/client/reports/types/payments",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length > 0) {
        $.each(data, function (index, item) {
          var option = $("<option>", {
            value: item.id,
            text: item.particular_name,
          });

          if (item.id == payment_id) {
            option.attr("selected", "selected");
          }

          $paymentSelect.append(option);
        });
      } else {
        $paymentSelect.html('<option value="">No Payment Method</option>');
      }
    },
    error: function (err) {
      $paymentSelect.html('<option value="">Error Occurred</option>');
    },
  });
}

function selectParticularsByModules(
  module = "account_type",
  module_id = 12,
  particularId = null
) {
  $.ajax({
    url: "/client/module-particulars/" + module + "/" + module_id,
    type: "POST",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        // $('select#particular_id').find('option').not(':first').remove();
        $("select#particular_id").append(
          '<option value="">-- select --</option>'
        );
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == particularId) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#particular_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["particular_name"] +
              "</option>"
          );
        });
      } else {
        $("select#particular_id").html(
          '<option value="">No Particulars</option>'
        );
      }
    },
    error: function (err) {
      $("select#particular_id").html(
        '<option value="">Error Occurred</option>'
      );
    },
  });
}

/**
 * Selects and populates entry types in a dropdown menu.
 *
 * @param {number} [account_typeId=12] - The ID of the account type, default is 12.
 * @param {number} [entry_typeId=null] - The ID of the selected entry type.
 *
 * @returns {void}
 */
function transactionEntryTypes(account_typeId = 12, entry_typeId = null) {
  var entry_typeSelect = $("select#entry_typeId");
  $.ajax({
    url: "/client/reports/transactions/" + account_typeId,
    type: "POST",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        entry_typeSelect.find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          var option = $("<option>", {
            value: data['id'],
            text: data['type'],
          });
          // Set the selected option if the entry_typeId matches
          if (data['id'] == entry_typeId) {
            option.attr("selected", true);
          }

          // Append the option only if 'part' is provided and matches 'data['part']', or append all if 'part' is not provided
          if (!part || part.toLowerCase() == data['part'].toLowerCase()) {
            entry_typeSelect.append(option);
          }
        });
      } else {
        entry_typeSelect.html('<option value="">No Type</option>');
      }
    },
    error: function (err) {
      entry_typeSelect.html('<option value="">Error Occurred</option>');
    },
  });
}

function disbursementLoanYears() {
  $.ajax({
    url: "/client/reports/types/disbursementyears",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("select#year").html('<option value="">-- Select --</option>');
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          $("<option />").val(data[i]).text(data[i]).appendTo($("select#year"));
        }
      } else {
        $("select#year").html('<option value="">No Year</option>');
      }
    },
    error: function (err) {
      $("select#year").html('<option value="">Error Occurred</option>');
    },
  });
}

function loadDisbursementStatus() {
  $.ajax({
    url: "/client/reports/types/disbursementstatus",
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      $("select#disbursement_status").html(
        '<option value="">Select Disbursement Status</option>'
      );
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#disbursement_status"));
        }
      } else {
        $("select#disbursement_status").html(
          '<option value="">No Disbursement Status</option>'
        );
      }
    },
    error: function (err) {
      $("select#disbursement_status").html(
        '<option value="">Error Occurred</option>'
      );
    },
  });
}

function accountLedgers(account_typeId, entry_typeId = null) {
  $.ajax({
    url: "/client/reports/types/particulars/" + account_typeId,
    type: "POST",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        $("select#particular_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == entry_typeId) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#particular_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["particular_name"] +
              "</option>"
          );
        });
      } else {
        $("select#particular_id").html('<option value="">No Account</option>');
      }
    },
    error: function (err) {
      $("select#particular_id").html(
        '<option value="">Error Occurred</option>'
      );
    },
  });
}

function transactionTypes(account_typeId, module) {
  $.ajax({
    url: "/client/reports/types/transactions/" + account_typeId + "/" + module,
    type: "POST",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        $("select#entry_typeId").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == entry_typeId) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#entry_typeId").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["type"] +
              "</option>"
          );
        });
      } else {
        $("select#entry_typeId").html('<option value="">No Type</option>');
      }
    },
    error: function (err) {
      $("select#entry_typeId").html('<option value="">Error Occurred</option>');
    },
  });
}

function loanProductsByLoanPrincipal(principal, product_id = null) {
  $.ajax({
    url: "/client/loans/type/applications/products/" + principal,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length > 0) {
        var loanProducts = '<option value="">-- Select --</option>';
        $.each(data, function (index, result) {
          if (result["id"] == product_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          loanProducts +=
            '<option value="' +
            result["id"] +
            '" ' +
            selection +
            ">" +
            result["product_name"] +
            "</option>";
        });
        $("select#product_id").html(loanProducts);
      } else {
        $("select#product_id").html(
          '<option value="">No Loan Product</option>'
        );
        $('[name="interest_rate"]').val("");
        $('[name="interest_type"]').val("");
        $('[name="repayment_period"]').val("");
        $('[name="repayment_freq"]').val("");
      }
    },
    error: function (err) {
      $("select#product_id").html('<option value="">Error Occurred</option>');
      $('[name="interest_rate"]').val("");
      $('[name="interest_type"]').val("");
      $('[name="repayment_period"]').val("");
      $('[name="repayment_freq"]').val("");
    },
  });
}
