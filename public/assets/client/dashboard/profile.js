// dataTables column config for each table
var columnsConfig = {
  myTransactions: [
    {
      data: "checkbox",
      orderable: false,
      searchable: false,
    },
    {
      data: "no",
      orderable: false,
      searchable: false,
    },
    {
      data: "date",
    },
    {
      data: "type",
    },
    {
      data: "payment_method",
    },
    {
      data: "amount",
      render: $.fn.DataTable.render.number(","),
    },
    {
      data: "ref_id",
    },
    {
      data: "staff_name",
    },
    {
      data: "account_bal",
      render: $.fn.DataTable.render.number(","),
    },
    {
      data: "action",
      orderable: false,
      searchable: false,
    },
  ],
};

$(document).ready(function () {
  // call to dataTable initialization function
  client_transactions(Id, username, table);
  // fetch client loan history
  clientLoanHistory(Id);
});

// ajax load client transactions
function client_transactions(clientId, name, table) {
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

  var url = "/client/my-transactions";
  // call to dataTable initialization function
  initializeDataTable(table, url, columnsConfig[table], buttons);
}

function clientLoanHistory(clientId = null) {
  $.ajax({
    url: "/client/my-history",
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

function change_password() {
  save_method = "update";
  $("#password_form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $('[name="menu"]').val("change");
  $(".modal-title").text("Reset Password");
  $("#passwordModal").modal("show");
}
// save password
function save_newPassword() {
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnReset").html('<i class="fa fa-spinner fa-spin"></i> resetting...');
  $("#btnReset").attr("disabled", true);
  var url;
  if (save_method == "add") {
    url = "/client/password";
  } else {
    url = "/client/profile/change-password";
  }
  var formData = new FormData($("#password_form")[0]);
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
          $("#passwordModal").modal("hide");
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
      $("#btnReset").text("Reset");
      $("#btnReset").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnReset").text("Reset");
      $("#btnReset").attr("disabled", false);
    },
  });
}

function two_factorAuth(oldStatus) {
  Swal.fire({
    title: "Update 2-FA?",
    text: "Do you wish to update Two Factor Authentication status!",
    icon: "warning",
    showCancelButton: true,
    allowOutsideClick: false,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Update!",
    preConfirm: () => {
      return new Promise((resolve) => {
        Swal.showLoading();
        $.ajax({
          url: "/client/profile/two-factor-auth/" + Id,
          type: "post",
          dataType: "JSON",
          success: function (data) {
            //if success reload ajax table
            if (data.status && data.error == null) {
              toogle_2faBtn(oldStatus)
              Swal.fire("Success!", data.messages, "success");
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
        text: "Two Factor Authentication updated successfully!",
        icon: "success",
      });
    }
  });
}

let toogle_2faBtn = (status) => {
  if (status.toLowerCase() === 'true') {
    // 2-FA is currently enabled, so show option to disable it
    $('#fAuthBtn').removeClass('btn-danger').addClass('btn-info');
    $('#fAuthBtn').text('Enable 2-FA');
  } else {
    // 2-FA is currently disabled, so show option to enable it
    $('#fAuthBtn').removeClass('btn-info').addClass('btn-danger');
    $('#fAuthBtn').text('Disable 2-FA');
  }
};
