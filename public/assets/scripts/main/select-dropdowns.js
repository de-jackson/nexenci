$(document).ready(function () {
  //check all
  selectBranch();
});

// get\show selected branches
function selectBranch(branch_id = null) {
  if (!branch_id) {
    $.ajax({
      url: "/admin/branches/get-branches",
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $(".branch_id").html('<option value="">-- select --</option>');
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].branch_name)
              .appendTo($(".branch_id"));
          }
        } else {
          $(".branch_id").html('<option value="">No Branch</option>');
        }
      },
      error: function (err) {
        $(".branch_id").html('<option value="">Error Occurred</option>');
      },
    });
  } else {
    $.ajax({
      url: "/admin/branches/get-branches",
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
    });
  }
}
// get\show selected departments
function selectDepartment(department_id = null) {
  if (!department_id) {
    $.ajax({
      url: "/admin/departments/all-departments",
      type: "POST",
      dataType: "JSON",
      success: function (data) {
        $(".department_id").html('<option value="">-- select --</option>');
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].department_name)
              .appendTo($(".department_id"));
          }
        } else {
          $(".department_id").html('<option value="">No Department</option>');
        }
      },
      error: function (err) {
        $(".department_id").html('<option value="">Error Occurred</option>');
      },
    });
  } else {
    $.ajax({
      url: "/admin/departments/all-departments",
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
    });
  }
}
// all positions for a department
$("select#department_id").on("change", function () {
  var department_id = this.value;
  if (department_id == 0 || department_id == "") {
    $(".position_id").html('<option value="">-- select department--</option>');
  } else {
    // select positions from db
    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: "/admin/positions/department-positions/" + department_id,
      success: function (data) {
        $(".position_id").html('<option value="">-- select --</option>');
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].position)
              .appendTo($(".position_id"));
          }
        } else {
          $(".position_id").html('<option value="">No Position</option>');
        }
      },
      error: function (err) {
        $(".position_id").html('<option value="">Error Occurred</option>');
      },
    });
  }
});
// get\show selected currencies
function selectCurrency(currency_id = null) {
  if (!currency_id || currency_id == null) {
    $.ajax({
      url: "/admin/settings/get-currencies",
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $(".currency_id").html('<option value="">-- select --</option>');
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].symbol + " ~ " + data[i].currency)
              .appendTo($(".currency_id"));
          }
        } else {
          $(".currency_id").html('<option value="">No Currency</option>');
        }
      },
      error: function (err) {
        $(".currency_id").html('<option value="">Error Occurred</option>');
      },
    });
  } else {
    $.ajax({
      url: "/admin/settings/get-currencies",
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
    });
  }
}
// get\show selected clients
function selectClient(client_id = null) {
  if (!client_id) {
    $.ajax({
      url: "/admin/clients/all-clients",
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $(".client_id").html('<option value="">-- select --</option>');
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].name + " - " + data[i].account_no)
              .appendTo($(".client_id"));
          }
        } else {
          $(".client_id").html('<option value="">No client</option>');
        }
      },
      error: function (err) {
        $(".client_id").html('<option value="">Error Occurred</option>');
      },
    });
  } else {
    $.ajax({
      url: "/admin/clients/all-clients",
      type: "GET",
      dataType: "JSON",
      success: function (response) {
        if (response.length > 0) {
          $(".client_id").find("option").not(":first").remove();
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
                " - " +
                data["account_no"] +
                "</option>"
            );
          });
        } else {
          $(".client_id").html('<option value="">No Client</option>');
        }
      },
    });
  }
}

// get loans/savings products
function selectProducts(product, product_id = null) {
  var url;
  var productSelector;
  if (product.toLowerCase() == "savings") {
    url = "/admin/products/all-products/savings";
    productSelector = $("select#savings_products");
  }
  if (product.toLowerCase() == "loans") {
    url = "/admin/loans/all-products";
    productSelector = $("select#product_id");
  }

  $.ajax({
    url: url,
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        // productSelector.find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == product_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          productSelector.append(
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
        productSelector.html(
          '<option value="">No ' +
            capitalizeFirstLetter(product) +
            " Product</option>"
        );
      }
    },
    error: function (err) {
      productSelector.html('<option value="">Error Occured</option>');
    },
  });
}

// category subcategories
function subcategories(category_id) {
  if (category_id == "0" || category_id == "") {
    $("select#subcategory_id").html(
      '<option value="">-- no category--</option>'
    );
  } else {
    // select subcategories from db
    $.ajax({
      type: "POST",
      dataType: "JSON",
      url: "/admin/accounts/subcategories/" + category_id,
      success: function (data) {
        $("select#subcategory_id").html(
          '<option value="">-- select --</option>'
        );
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].subcategory_name)
              .appendTo($("select#subcategory_id"));
          }
        } else {
          $("select#subcategory_id").html(
            '<option value="">No Subcategory</option>'
          );
        }
      },
      error: function (err) {
        $("select#subcategory_id").html(
          '<option value="">Error Occurred</option>'
        );
      },
    });
  }
}
// position belonging to a department[]
function department_position(department_id, position_id) {
  $.ajax({
    type: "POST",
    dataType: "JSON",
    url: "/admin/departments/all-departments",
    success: function (response) {
      if (response.length > 0) {
        $("#department_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["id"] == department_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("#department_id").append(
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
        $("select#department_id").html(
          '<option value="">No Department</option>'
        );
      }
    },
  });

  $.ajax({
    type: "POST",
    dataType: "JSON",
    url: "/admin/positions/department-positions/" + department_id,
    success: function (response) {
      if (response.length > 0) {
        $("#position_id").find("option").not(":first").remove();
        $.each(response, function (index, data) {
          if (data["id"] == position_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("#position_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["position"] +
              "</option>"
          );
        });
      } else {
        $("select#position_id").html('<option value="">No Position</option>');
      }
    },
  });
}

function generateTitles() {
  $.ajax({
    url: "/admin/reports/module/types/titles",
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      $("select#titles").html('<option value="">-- Select --</option>');
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#titles"));
        }
      } else {
        $("select#titles").html('<option value="">No Titley</option>');
      }
    },
    error: function (err) {
      $("select#titles").html('<option value="">Error Occured</option>');
    },
  });
}

function generateGender() {
  $.ajax({
    url: "/admin/reports/module/types/gender",
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      $("select#gender").html('<option value="">-- Select --</option>');
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#gender"));
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
    url: "/admin/reports/module/types/accountstatus",
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      $("select#status").html('<option value="">-- Select --</option>');
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#status"));
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
    url: "/admin/reports/module/types/staff",
    type: "POST",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        // $("select#staff_id").find("option").not(":first").remove();
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

function generateClientAccountNo(account_no = null) {
  $.ajax({
    url: "/admin/reports/module/types/clients",
    type: "POST",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        $("select#account_no").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (account_no && data["account_no"] == account_no) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#account_no").append(
            '<option value="' +
              data["account_no"] +
              '" ' +
              selection +
              ">" +
              data["name"] +
              " [" +
              data["account_no"] +
              "]" +
              "</option>"
          );
        });
      } else {
        $("select#account_no").html('<option value="">No Client</option>');
      }
    },
  });
}

function generateAppointmentType() {
  $.ajax({
    url: "/admin/reports/module/types/appointmenttypes",
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
    url: "/admin/reports/module/types/staffaccounttypes",
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

function generateAccountTypes(account_id = null) {
  $.ajax({
    url: "/admin/reports/module/types/accounts",
    type: "POST",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        $("select#account_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (account_id && data["id"] == account_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $("select#account_id").append(
            '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["name"] +
              " [" +
              data["code"] +
              "]" +
              "</option>"
          );
        });
      } else {
        $("select#account_id").html('<option value="">No Account</option>');
      }
    },
  });
}

function generateCustomeSettings(menu, menuKey = null) {
  $.ajax({
    url: "/admin/reports/module/types/" + menu,
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      $("select#" + menu).html('<option value="">--Select---</option>');
      // convert object to key's array
      const keys = Object.keys(data);
      var options = '<option value="">--Select---</option>';
      if (keys.length > 0) {
        /*
                for (let index in keys) {
                    $("<option />")
                        .val(keys[index])
                        .text(keys[index])
                        .appendTo($("select#" + menu));
                }
                */
        keys.forEach((key) => {
          const value = data[key];
          // console.log(`Key: ${key}, Value: ${value}`);

          if (key == menuKey) {
            // select only menu key from the list
            options +=
              '<option value="' + key + '" selected>' + value + "</option>";
          }

          if (menuKey == null) {
            options += '<option value="' + key + '">' + value + "</option>";
          }
        });
        $("select#" + menu).html(options);
      } else {
        $("select#" + menu).html('<option value="">No ' + menu + "</option>");
      }
    },
    error: function (err) {
      $("select#" + menu).html('<option value="">Error Occurred</option>');
    },
  });
}

/**
 * Generates custom settings for a given menu.
 *
 * @param {string} menu - The name of the menu for which custom settings are to be generated.
 * @param {string} [menuKey=null] - The specific key for the menu to be selected by default.
 *
 * @returns {void}
 */
function generateCustomSettings(menu, menuKey) {
  $.ajax({
    url: "/admin/reports/module/types/" + menu,
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
 * Selects payment particulars.
 *
 * @param {number} [payment_id=null] - The ID of the payment particular to be selected by default.
 */
function selectPaymentMethod(payment_id = null) {
  var $paymentSelect = $("select#payment_id");

  // Clear existing options
  $paymentSelect.html("");

  // Add default option
  $paymentSelect.append('<option value="">-- select --</option>');

  $.ajax({
    url: "/admin/accounts/payment-method",
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

/**
 * Selects particulars based on the given account type ID.
 *
 * @param {number} account_typeId - The ID of the account type.
 * @param {number} [particular_id=null] - The ID of the particular to be selected by default.
 * @param {string} [selectTypeId='entry_typeId'] - The ID of the select element where the particulars will be populated.
 */
function selectParticulars(
  account_typeId,
  particular_id = null,
  selectParticularId = "particular_id"
) {
  var particularSelect = $("select#" + selectParticularId);
  // select account type particulars
  $.ajax({
    url: "/admin/accounts/accountType-particulars/" + account_typeId,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length > 0) {
        particularSelect.html('<option value="">-- select --</option>');
        $.each(data, function (index, item) {
          var option = $("<option>", {
            value: item.id,
            text: item.particular_code +' - '+ item.particular_name,
          });

          if (item.id == particular_id) {
            option.attr("selected", "selected");
          }

          particularSelect.append(option);
        });
      } else {
        particularSelect.html('<option value="">No Particular</option>');
      }
    },
    error: function (err) {
      particularSelect.html('<option value="">Error Occurred</option>');
    },
  });
}

/**
 * Selects transaction types based on the given account type ID.
 *
 * @param {number} account_typeId - The ID of the account type.
 * @param {number} [type_id=null] - The ID of the transaction type to be selected by default.
 * @param {string} [selectTypeId='entry_typeId'] - The ID of the select element where the transaction types will be populated.
 */
function select_transactionType(account_typeId, type_id = null, selectTypeId = 'entry_typeId') {
  var entry_typeSelect = $("select#"+ selectTypeId);
  entry_typeSelect.html('<option value="">-- select --</option>');
  $.ajax({
    url: "/admin/transactions/transaction-types/" + account_typeId,
    type: "POST",
    dataType: "JSON",
    data: { transaction_menu: transaction },
    success: function (response) {
      if (response.length > 0) {
        entry_typeSelect.html('<option value="">-- select --</option>');
        // entry_typeSelect.find("option").not(":first").remove();

        $.each(response, function (index, item) {
          var option = $("<option>", {
            value: item.id,
            text: item.type,
          });

          // Set the selected option if the entry_typeId matches
          if (item.id == entry_typeId) {
            option.attr("selected", true);
          }

          // Append the option only if 'part' is provided and matches 'item.part', or append all if 'part' is not provided
          if (!part || part.toLowerCase() == item.part.toLowerCase()) {
            entry_typeSelect.append(option);
          }

          // Set the entry_menu value
          $('[name="entry_menu"]').val(item.entry_menu);
        });
      } else {
        entry_typeSelect.html('<option value="">No Transaction Type</option>');
      }
    },
    error: function (err) {
      entry_typeSelect.html('<option value="">Error Occured</option>');
    },
  });
}

function getParticularCharges(existingChargesData) {
  var chargeSelect = $("select#particular_charge");
  // chargeSelect.html('<option value="">-- select --</option>');
  chargeSelect.empty();
  if (existingChargesData) {
    var options = '<option value="">-- Select ---</option>';

    $.each(existingChargesData, function (index, item) {
      var charge_method = item.charge_method;
      var charge = item.charge;
      var charge_mode = item.charge_mode;
      var charge_frequency = item.charge_frequency;
      var effective_date = item.effective_date;
      var charge_status = item.charge_status;

      if (charge_method.toLowerCase() == "amount") {
        var chargeFee = charge;
      } else {
        var chargeFee = charge + "%";
      }

      var option = $("<option>", {
        value: item.charge,
        text: item.chargeFee,
      });
      chargeSelect.append(option);
    });
  } else {
    chargeSelect.html('<option value="">No Particular</option>');
  }
}

function generateChargeOptions() {
  // load mode
  $.ajax({
    url: "/admin/accounts/charge-options/mode",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("select#charge_mode").html('<option value="">-- Select --</option>');
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#charge_mode"));
        }
      } else {
        $("select#charge_mode").html('<option value="">No Mode</option>');
      }
    },
    error: function (err) {
      $("select#charge_mode").html('<option value="">Error Occurred</option>');
    },
  });
  // load frequencies
  $.ajax({
    url: "/admin/accounts/charge-options/frequency",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("select#charge_frequency").html(
        '<option value="">-- Select --</option>'
      );
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#charge_frequency"));
        }
      } else {
        $("select#charge_frequency").html('<option value="">No Mode</option>');
      }
    },
    error: function (err) {
      $("select#charge_frequency").html(
        '<option value="">Error Occurred</option>'
      );
    },
  });
  // load charge status
  $.ajax({
    url: "/admin/accounts/charge-options/status",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("select#charge_status").html('<option value="">-- Select --</option>');
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#charge_status"));
        }
      } else {
        $("select#charge_status").html('<option value="">No Status</option>');
      }
    },
    error: function (err) {
      $("select#charge_status").html(
        '<option value="">Error Occurred</option>'
      );
    },
  });
  // load charge method
  $.ajax({
    url: "/admin/accounts/charge-options/method",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("select#charge_method").html('<option value="">-- Select --</option>');
      // convert object to key's array
      const keys = Object.keys(data);
      if (keys.length > 0) {
        for (let index in keys) {
          $("<option />")
            .val(keys[index])
            .text(keys[index])
            .appendTo($("select#charge_method"));
        }
      } else {
        $("select#charge_method").html('<option value="">No Method</option>');
      }
    },
    error: function (err) {
      $("select#charge_method").html(
        '<option value="">Error Occurred</option>'
      );
    },
  });
}

function getBlogCategoriesOLD(blog_category_id = null) {
  $.ajax({
    url: "/admin/reports/module/types/categories",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      if (response.length > 0) {
        // $(".blog_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          if (data["blog_id"] == blog_category_id) {
            var selection = "selected";
          } else {
            var selection = "";
          }
          $(".blog_id").append(
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
        $(".blog_id").html('<option value="">No Parent</option>');
      }
    },
  });
}

function getBlogCategories(blog_id = null, blog_category_id = null) {
  $.ajax({
    url: "/admin/reports/module/types/categories",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      var options = '<option value="">--select---</option>';
      if (response.length > 0) {
        // $(".blog_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          var selection = data["id"] == blog_category_id ? "selected" : "";
          // Add condition to ignore showing the same parent blog category
          if (!(data["id"] == blog_id && data["blog_id"] === null)) {
            options +=
              '<option value="' +
              data["id"] +
              '" ' +
              selection +
              ">" +
              data["name"] +
              "</option>";
          }
        });
        $("select#blog_id").html(options);
      } else {
        $("select#blog_id").html('<option value="">No Parent</option>');
      }
    },
    error: function (err) {
      $("select#blog_id").html('<option value="">Error Occurred</option>');
    },
  });
}

function getBlogPostCategories(blog_id = null) {
  $.ajax({
    url: "/admin/reports/module/types/blogs",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      var options = '<option value="">--select---</option>';
      if (response.length > 0) {
        // $(".blog_id").find("option").not(":first").remove();
        // Add options
        $.each(response, function (index, data) {
          var selection = data["id"] == blog_id ? "selected" : "";
          options +=
            '<option value="' +
            data["id"] +
            '" ' +
            selection +
            ">" +
            data["name"] +
            "</option>";
        });
        $(".blog_id").html(options);
      } else {
        $(".blog_id").html('<option value="">No Category</option>');
      }
    },
    error: function (err) {
      $(".blog_id").html('<option value="">Error Occurred</option>');
    },
  });
}
