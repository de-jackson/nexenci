// dataTables buttons config
var buttonsConfig = [];
// add dataTable buttons
if (
  userPermissions.includes("export_" + menu.toLowerCase() + titleSlug) ||
  userPermissions === "all"
) {
  buttonsConfig.push(
    {
      extend: "excel",
      className: "btn btn-sm btn-success",
      titleAttr: "Export " + title + " To Excel",
      text: '<i class="fas fa-file-excel"></i>',
      filename: title + " Information",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2],
      },
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export " + title + " To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: title + " Information",
      extension: ".pdf",
      exportOptions: {
        columns: [1, 2],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export " + title + " To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: title + " Information",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-warning",
      titleAttr: "Copy " + title + " Information",
      text: '<i class="fas fa-copy"></i>',
      exportOptions: {
        columns: [1, 2],
      },
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center fw-bold'>" +
        businessName +
        " " +
        systemName +
        "</h3><h4 class='text-center fw-bold'>" +
        title +
        " Information</h4><h5 class='text-center'>Printed On " +
        new Date().getHours() +
        " : " +
        new Date().getMinutes() +
        " " +
        new Date().toDateString() +
        "</h5>",
      exportOptions: {
        columns: [1, 2],
      },
      customize: function (win) {
        $(win.document.body)
          .css("font-size", "10pt")
          .css("font-family", "calibri")
          .prepend(
            '<img src="' +
              baseURL +
              "/uploads/logo/" +
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

      className: "btn btn-sm btn-secondary",
      titleAttr: "Print " + title + " Information",
      text: '<i class="fa fa-print"></i>',
      filename: title + " Information",
    }
  );
}

var year = new Date().getFullYear();
var month = new Date().getMonth();

$(document).ready(function () {
  // generate statements
  switch (statement) {
    case "balancesheet":
      generate_balancesheet();
      break;
    case "incomeStatement":
      generate_incomeStatement();
      break;
    case "cashflow":
      generate_cashflow();
      break;
    case "trialbalance":
      generate_trialbalance();
      break;
    case "particularstatement":
      generate_particularstatement();
      break;
    default:
      break;
  }
  
  // load financial years
  $.ajax({
    url: "/admin/statements/financial-years",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      financialYearData = data; // store data in global variable
      const keys = Object.keys(data);
      // Clear the select box before appending new options
      $("select#f_year").empty();
      if (keys.length > 0) {
        for (let index in keys) {
          var selection = keys[index] == selected_fYear ? "selected" : "";
          $("select#f_year").append(
            '<option value="' +
              keys[index] +
              '" ' +
              selection +
              ">" +
              keys[index] +
              "</option>"
          );
        }
        updateDateInputs(data);
      } else {
        $("select#f_year").html('<option value="">No Years</option>');
      }
    },
    error: function (err) {
      $("select#f_year").html('<option value="">Error Occured</option>');
    },
  });

  // load quarters
  $.ajax({
    url: "/admin/statements/get-quarters",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      financialYearQuarters = response; // store data in global variable
      const keys = Object.keys(response);
      if (keys.length > 0) {
        $("select#f_quarter").html(
          '<option value=""> -- choose quarter -- </option>'
        );
        for (let index in keys) {
          var selection = keys[index] == selected_fQuarter ? "selected" : "";
          $("select#f_quarter").append(
            '<option value="' +
              keys[index] +
              '" ' +
              selection +
              ">" +
              keys[index] +
              "</option>"
          );
        }
        updateDateInputs_q(response);
      } else {
        $("select#f_quarter").html('<option value="">No Quarters</option>');
      }
    },
    error: function (err) {
      $("select#f_quarter").html('<option value="">Error Occured</option>');
    },
  });

  //
  $.ajax({
    url: "/admin/statements/entryMonths/" + year,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          $("<option />")
            .val(data[i])
            .text(data[i])
            .appendTo($("select#entryMonth"));
        }
      } else {
        $("select#entryMonth").html('<option value="">No Years</option>');
      }
    },
    error: function (err) {
      $("select#entryMonth").html('<option value="">Error Occured</option>');
    },
  });
});

// change financial year
$("select#f_year").change(function () {
  updateDateInputs(financialYearData, $(this).val()); // access data from global variable
});
// change financial year quarter
$("select#f_quarter").change(function () {
  updateDateInputs_q(financialYearQuarters, $(this).val()); // access data from global variable
});
// change start and end values for input fields
let updateDateInputs = (years) => {
  var selectedYearRange = $("#f_year").val();
  // clear current values for start and end input boxes
  $("#start_date_input").empty();
  $("#end_date_input").empty();
  var y_startDate = years[selectedYearRange]["start"];
  var y_endDate = years[selectedYearRange]["end"];

  // update new start and end dates for inputs
  $('[name="start_date"]').val(y_startDate);
  $('[name="end_date"]').val(y_endDate);
};
let updateDateInputs_q = (quarters) => {
  var selectedQuarterRange = $("#f_quarter").val();
  if (selectedQuarterRange) {
    // clear current values for start and end input boxes
    $("#start_date_input").empty();
    $("#end_date_input").empty();
    var q_startDate = quarters[selectedQuarterRange]["start"];
    var q_endDate = quarters[selectedQuarterRange]["end"];

    // update new start and end dates for inputs
    $('[name="start_date"]').val(q_startDate);
    $('[name="end_date"]').val(q_endDate);
  }
};

// populate balancesheet table
function generate_balancesheet() {
  $("#statementBtn").html('<i class="fa fa-spinner fa-spin"></i> loading...');
  $("#statementBtn").attr("disabled", true);

  $.ajax({
    url: "/admin/statements/generate-statement",
    method: "GET",
    dataType: "json",
    beforeSend: function () {
      $("#statementBtn").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></div>'
      );
    },
    data: {
      f_year: $('[name="f_year"]').val(),
      f_quarter: $('[name="f_quarter"]').val(),
      start_date: $('[name="start_date"]').val(),
      end_date: $('[name="end_date"]').val(),
    },
    success: function (response) {
      // Insert currency symbol
      var startDate = response.start_date;
      var endDate = response.end_date;
      // replace page with the response output
      $("#currency").text(currency);
      $("#startDate").text(formatDate(startDate, "long"));
      $("#endDate").text(formatDate(endDate, "long"));
      $('[name="start_date"]').val(startDate);
      $('[name="end_date"]').val(endDate);
      var printURL =
        "/admin/statements/export-statement/" +
        statement +
        "/" +
        startDate +
        "/" +
        endDate;
      $("#printURL").attr("href", printURL);
      // Generate table rows
      var html = "";
      // categories level
      if (response.categoryData.length > 0) {
        $.each(response.categoryData, function (categoryIndex, category) {
          // filter balance sheet items only
          if (category.statement_id == 1) {
            // category name
            html += '<tr class="table-primary">';
            html +=
              '<th class="fw-bold px-3" colspan="6">' +
              category.category_name.toUpperCase() +
              "</th>";
            html += "</tr>";

            // subcategories for each category
            if (response.subcategoryData.length > 0) {
              $.each(
                response.subcategoryData,
                function (subcategoryIndex, subcategory) {
                  if (subcategory.category_id == category.id) {
                    // subcategory name
                    html += "<tr>";
                    html +=
                      '<th class="px-3" colspan="2">' +
                      subcategory.subcategory_name +' - '+ subcategory.subcategory_code +
                      "</th>";
                    html += "</tr>";

                    // particulars for each subcategory
                    if (response.particularData.length > 0) {
                      $.each(
                        response.particularData,
                        function (particularIndex, particular) {
                          if (particular.subcategory_id == subcategory.id) {
                            html += "<tr>";
                            html +=
                              '<td class="px-5"><a href="/admin/statements/particular-statement/' +
                              particular.id +'/'+
                              startDate +'/'+
                              endDate +
                              '" class="text-info">' +
                              particular.particular_code +' - '+ particular.particular_name +
                              "</i></a></td>";
                            html +=
                              '<td align="right">' +
                              particular.balance +
                              " </i></td>";
                            html += "</tr>";
                          }
                        }
                      );
                    } else {
                      html +=
                        '<tr><th class="text-center" colspan="6">No Particulars Found</th></tr>';
                    }

                    // Subcategory total
                    html += "<tr>";
                    html +=
                      '<td class="px-3">Total ' +
                      subcategory.subcategory_name +
                      "</td>";
                    html +=
                      '<td class="" align="right"><u>' +
                      subcategory.balance +
                      " </u></td>";
                    html += "</tr>";
                  }
                }
              );
            } else {
              html +=
                '<tr><th class="text-center" colspan="2">No Subcategories Found</th></tr>';
            }

            // Category total
            html += "<tr>";
            html +=
              '<td class="fw-bold px-3">Total ' +
              category.category_name +
              "</td>";
            html +=
              '<td class="fw-bold" align="right"><u>' +
              category.balance +
              " </u></td>";
            html += "</tr>";
            // retained earnings under equity
            if (category.id == 2) {
              // retained earnings row
              html += "<tr>";
              html += '<td class="px-3">Retained Earning</td>';
              html +=
                '<td class="" align="right"><u>' +
                response.getTotals.netIncome +
                " </u></td>";
              html += "</tr>";
              // equity + retained earnings total row
              html += "<tr>";
              html += '<td class="fw-bold px-3">Equity + Retained</td>';
              html +=
                '<td class="fw-bold" align="right"><u>' +
                response.getTotals.equityRetainedTotal +
                " </u></td>";
              html += "</tr>";
            }
            // tax payable under liabilities
            if (category.id == 3) {
              // Tax Payable row
              html += "<tr>";
              html += '<td class="px-3">Tax Payable</td>';
              html +=
                '<td class="" align="right"><u>' +
                response.getTotals.taxPayableTotal +
                " </u></td>";
              html += "</tr>";
              // Liabitites + Tax Payable total row
              html += "<tr>";
              html += '<td class="fw-bold px-3">Liabilities + Tax</td>';
              html +=
                '<td class="fw-bold" align="right"><u>' +
                response.getTotals.liabilityTaxTotal +
                " </u></td>";
              html += "</tr>";
            }
          }
        });
        // compare assets and equity + liability
        var textColor =
          response.getTotals.assetsTotal !=
          response.getTotals.equityLiabilityTotal_iS
            ? "text-danger"
            : "text-success";
        html +=
          '<tr class="fw-bold">' +
          '<td align="center">Total Assets</td>' +
          '<td align="center">Equity + Liabilities</td>' +
          "</tr>";
        html +=
          '<tr class="fw-bold ' +
          textColor +
          '">' +
          '<td align="center">' +
          response.getTotals.assetsTotal +
          "</td>" +
          '<td align="center">' +
          response.getTotals.equityLiabilityTotal_iS +
          "</td>" +
          "</tr>";
      } else {
        html +=
          '<tr><th class="text-center" colspan="2">No Categories Found</th></tr>';
      }

      // Insert the generated HTML into the table body
      $("#balancesheet-body").html(html);

      $("#statementBtn").html('<i class="fa fa-search fa-fw"></i>');
      $("#statementBtn").attr("disabled", false);
    },
    error: function () {
      $("#statementBtn").html('<i class="fa fa-search fa-fw"></i>');
      $("#statementBtn").attr("disabled", false);
    },
  });
}

// populate incomeStatement table
function generate_incomeStatement() {
  $("#statementBtn").html('<i class="fa fa-spinner fa-spin"></i> loading...');
  $("#statementBtn").attr("disabled", true);

  $.ajax({
    url: "/admin/statements/generate-statement",
    method: "GET",
    dataType: "json",
    beforeSend: function () {
      $("#statementBtn").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></div>'
      );
    },
    data: {
      f_year: $('[name="f_year"]').val(),
      f_quarter: $('[name="f_quarter"]').val(),
      start_date: $('[name="start_date"]').val(),
      end_date: $('[name="end_date"]').val(),
    },
    success: function (response) {
      // Insert currency symbol
      var startDate = response.start_date;
      var endDate = response.end_date;
      // replace page with the response output
      $("#currency").text(currency);
      $("#startDate").text(formatDate(startDate, "long"));
      $("#endDate").text(formatDate(endDate, "long"));
      $('[name="start_date"]').val(startDate);
      $('[name="end_date"]').val(endDate);
      var printURL =
        "/admin/statements/export-statement/" +
        statement +
        "/" +
        startDate +
        "/" +
        endDate;
      $("#printURL").attr("href", printURL);
      // Generate table rows
      var html = "";
      // categories level
      if (response.categoryData.length > 0) {
        $.each(response.categoryData, function (categoryIndex, category) {
          // filter profit n loss items only
          if (category.statement_id == 2) {
            // category name
            html += '<tr class="table-primary">';
            html +=
              '<th class="fw-bold px-3" colspan="6">' +
              category.category_name.toUpperCase() +
              "</th>";
            html += "</tr>";

            // subcategories for each category
            if (response.subcategoryData.length > 0) {
              $.each(
                response.subcategoryData,
                function (subcategoryIndex, subcategory) {
                  if (subcategory.category_id == category.id) {
                    // subcategory name
                    html += "<tr>";
                    html +=
                      '<th class="px-3" colspan="6">' +
                      subcategory.subcategory_name +' - '+ subcategory.subcategory_code +
                      "</th>";
                    html += "</tr>";

                    // particulars for each subcategory
                    if (response.particularData.length > 0) {
                      $.each(
                        response.particularData,
                        function (particularIndex, particular) {
                          if (particular.subcategory_id == subcategory.id) {
                            html += "<tr>";
                            html +=
                              '<td colspan="4" class="px-5"><a href="/admin/statements/particular-statement/' +
                              particular.id + +'/'+
                              startDate +'/'+
                              endDate +
                              '" class="text-info">' +
                              particular.particular_code +' - '+ particular.particular_name +
                              "</a></td>";
                            html +=
                              '<td colspan="2" align="right">' +
                              particular.balance +
                              " </td>";
                            html += "</tr>";
                          }
                        }
                      );
                    } else {
                      html +=
                        '<tr><th class="text-center" colspan="6">No Particulars Found</th></tr>';
                    }

                    // Subcategory total
                    html += "<tr>";
                    html +=
                      '<td colspan="4" class="px-3">Total ' +
                      subcategory.subcategory_name +
                      "</td>";
                    html +=
                      '<td colspan="2" class="" align="right"><u>' +
                      subcategory.balance +
                      " </u></td>";
                    html += "</tr>";
                  }
                }
              );
            } else {
              html +=
                '<tr><th class="text-center" colspan="6">No Subcategories Found</th></tr>';
            }

            // Category total
            html += "<tr>";
            html +=
              '<td colspan="4" class="fw-bold px-3">Total ' +
              category.category_name +
              "</td>";
            html +=
              '<td colspan="2" class="fw-bold" align="right"><u>' +
              category.balance +
              " </u></td>";
            html += "</tr>";
          }
        });
        // colorize net income
        var textColor =
          response.getTotals.netIncome < 0 ? "text-danger" : "text-success";
        // income summary
        html +=
          '<tr class="fw-bold ' +
          textColor +
          '">' +
          // gross income
          '<td align="center">Gross Income</td>' +
          '<td align="center">' +
          response.getTotals.grossIncome +
          "</td>" +
          // tax provision
          '<td align="center">Total Tax(' +
          taxRate +
          "%)</td>" +
          '<td align="center">' +
          response.getTotals.taxPayableTotal +
          "</td>" +
          // net income
          '<td align="center">Net Income</td>' +
          '<td align="center">' +
          response.getTotals.netIncome +
          "</td>" +
          "</tr>";
      } else {
        html +=
          '<tr><th class="text-center" colspan="6">No Categories Found</th></tr>';
      }

      // Insert the generated HTML into the table body
      $("#incomestatement-body").html(html);

      $("#statementBtn").html('<i class="fa fa-search fa-fw"></i>');
      $("#statementBtn").attr("disabled", false);
    },
    error: function () {
      $("#statementBtn").html('<i class="fa fa-search fa-fw"></i>');
      $("#statementBtn").attr("disabled", false);
    },
  });
}

// populate cashflow table
function generate_cashflow() {
  $("#statementBtn").html('<i class="fa fa-spinner fa-spin"></i> loading...');
  $("#statementBtn").attr("disabled", true);

  $.ajax({
    url: "/admin/statements/generate-statement",
    method: "GET",
    dataType: "json",
    beforeSend: function () {
      $("#statementBtn").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></div>'
      );
    },
    data: {
      f_year: $('[name="f_year"]').val(),
      f_quarter: $('[name="f_quarter"]').val(),
      start_date: $('[name="start_date"]').val(),
      end_date: $('[name="end_date"]').val(),
    },
    success: function (response) {
      // Insert currency symbol
      var startDate = response.start_date;
      var endDate = response.end_date;
      // replace page with the response output
      $("#currency").text(currency);
      $("#startDate").text(formatDate(startDate, "long"));
      $("#endDate").text(formatDate(endDate, "long"));
      $('[name="start_date"]').val(startDate);
      $('[name="end_date"]').val(endDate);
      var printURL =
        "/admin/statements/export-statement/" +
        statement +
        "/" +
        startDate +
        "/" +
        endDate;
      $("#printURL").attr("href", printURL);

      // Generate table rows
      var html = "";
      if (response.cashFlowData.length > 0) {
        $.each(response.cashFlowData, function (cashflowId, cashflowType) {
          // Cash Flow from cashflow_type
          html += "<tr>";
          html +=
            '<th class="fw-bold px-3" colspan="2">Cash Flow from ' +
            cashflowType.name.toUpperCase() +
            "</th>";
          html += "</tr>";

          // particulars for each cash flow type
          if (response.particularData.length > 0) {
            $.each(response.particularData, function (index, particular) {
              if (particular.cash_flow_typeId == cashflowType.id) {
                html += "<tr>";
                html +=
                  '<td class="px-5"><a href="/admin/statements/particular-statement/' +
                  particular.id + +'/'+
                  startDate +'/'+
                  endDate +
                  '" class="text-info">' +
                  particular.particular_code +' - '+ particular.particular_name +
                  "</a></td>";
                html +=
                  '<td align="right">' + particular.balance + " </td>";
                html += "</tr>";
              }
            });
          } else {
            html +=
              '<tr><th class="text-center" colspan="2">No Particulars Found</th></tr>';
          }

          // Cashflow-type totals
          html += "<tr>";
          html +=
            '<td class="fw-bold px-3">Total Cash Flow From ' +
            cashflowType.name +
            "</td>";
          html +=
            '<td class="fw-bold" align="right"><u>' +
            cashflowType.balance +
            " </u></td>";
          html += "</tr>";
        });
      } else {
        html +=
          '<tr><th class="text-center" colspan="2">No Cash Flow Types Found</th></tr>';
      }

      // Insert the generated HTML into the table body
      $("#cashflow-body").html(html);

      $("#statementBtn").html('<i class="fa fa-search fa-fw"></i>');
      $("#statementBtn").attr("disabled", false);
    },
    error: function () {
      $("#statementBtn").html('<i class="fa fa-search fa-fw"></i>');
      $("#statementBtn").attr("disabled", false);
    },
  });
}

// populate trialbalance table
function generate_trialbalance() {
  $("#statementBtn").html('<i class="fa fa-spinner fa-spin"></i> loading...');
  $("#statementBtn").attr("disabled", true);

  $.ajax({
    url: "/admin/statements/generate-statement",
    method: "GET",
    dataType: "json",
    beforeSend: function () {
      $("#statementBtn").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></div>'
      );
    },
    data: {
      f_year: $('[name="f_year"]').val(),
      f_quarter: $('[name="f_quarter"]').val(),
      start_date: $('[name="start_date"]').val(),
      end_date: $('[name="end_date"]').val(),
    },
    success: function (response) {
      // Insert currency symbol
      var startDate = response.start_date;
      var endDate = response.end_date;
      // replace page with the response output
      $("#currency").text(currency);
      $("#startDate").text(formatDate(startDate, "long"));
      $("#endDate").text(formatDate(endDate, "long"));
      $('[name="start_date"]').val(startDate);
      $('[name="end_date"]').val(endDate);
      var printURL =
        "/admin/statements/export-statement/" +
        statement +
        "/" +
        startDate +
        "/" +
        endDate;
      $("#printURL").attr("href", printURL);
      // Generate table rows
      var html = "";
      // categories level
      if (response.categoryData.length > 0) {
        $.each(response.categoryData, function (categoryIndex, category) {
          // category name
          html += '<tr class="table-primary">';
          html +=
            '<th class="fw-bold px-3" colspan="3">' +
            category.category_name.toUpperCase() +
            "</th>";
          html += "</tr>";

          // subcategories for each category
          if (response.subcategoryData.length > 0) {
            $.each(
              response.subcategoryData,
              function (subcategoryIndex, subcategory) {
                if (subcategory.category_id == category.id) {
                  // subcategory name
                  html += "<tr>";
                  html +=
                    '<th class="px-3" colspan="3">' +
                    subcategory.subcategory_name +' - '+ subcategory.subcategory_code +
                    "</th>";
                  html += "</tr>";

                  // particulars for each subcategory
                  if (response.particularData.length > 0) {
                    $.each(
                      response.particularData,
                      function (particularIndex, particular) {
                        if (particular.subcategory_id == subcategory.id) {
                          html += "<tr>";
                          html +=
                            '<td class="px-5"><a href="/admin/statements/particular-statement/' +
                            particular.id + +'/'+
                            startDate +'/'+
                            endDate +
                            '" class="text-info">' +
                            particular.particular_code +' - '+ particular.particular_name +
                            "</a></td>";
                          // put balance on debit side
                          if (particular.part == "debit") {
                            html +=
                              '<td align="right">' +
                              particular.balance +
                              "</td><td></td>";
                            html += "</tr>";
                          }
                          // put balance on credit side
                          if (particular.part == "credit") {
                            html +=
                              '<td></td><td align="right">' +
                              particular.balance +
                              "</td>";
                            html += "</tr>";
                          }
                        }
                      }
                    );
                  } else {
                    fw - semibold;
                    html +=
                      '<tr><th class="text-center" colspan="6">No Particulars Found</th></tr>';
                  }

                  // Subcategory total
                  html += "<tr>";
                  html +=
                    '<td class="px-3"><u>Total ' +
                    subcategory.subcategory_name +
                    "</u></td>";
                  // put balance on debit side
                  if (subcategory.part == "debit") {
                    html +=
                      '<td align="right"><u>' +
                      subcategory.balance +
                      " </u></td><td></td>";
                    html += "</tr>";
                  }
                  // put balance on credit side
                  if (subcategory.part == "credit") {
                    html +=
                      '<td></td><td align="right"><u>' +
                      subcategory.balance +
                      " </u></td>";
                    html += "</tr>";
                  }
                }
              }
            );
          } else {
            html +=
              '<tr><th class="text-center" colspan="2">No Subcategories Found</th></tr>';
          }

          // Category total
          html += "<tr>";
          html +=
            '<td class="fw-bold px-3">Total ' +
            category.category_name +
            "</td>";
          // put balance on debit side
          if (category.part == "debit") {
            html +=
              '<td class="fw-bold" align="right">' +
              category.balance +
              " </td><td></td>";
            html += "</tr>";
          }
          // put balance on credit side
          if (category.part == "credit") {
            html +=
              '<td></td><td class="fw-bold" align="right">' +
              category.balance +
              " </td>";
            html += "</tr>";
          }
        });
        // colorize balance
        var textColor =
          response.getTotals.totalDebits == response.getTotals.totalCredits
            ? "text-success"
            : "text-danger";
        // income summary
        // gross income
        html +=
          '<tr class="fw-bold ' +
          textColor +
          '">' +
          '<td align="center">Total</td>' +
          '<td align="right">' +
          response.getTotals.totalDebits +
          "</td>" +
          '<td align="right">' +
          response.getTotals.totalCredits +
          "</td>" +
          "</tr>";
      } else {
        html +=
          '<tr><th class="text-center" colspan="2">No Categories Found</th></tr>';
      }

      // Insert the generated HTML into the table body
      $("#trialbalance-body").html(html);

      $("#statementBtn").html('<i class="fa fa-search fa-fw"></i>');
      $("#statementBtn").attr("disabled", false);
    },
    error: function () {
      $("#statementBtn").html('<i class="fa fa-search fa-fw"></i>');
      $("#statementBtn").attr("disabled", false);
    },
  });
}

// populate particularstatement table
function generate_particularstatement() {
  $("#statementBtn").html('<i class="fa fa-spinner fa-spin"></i> loading...');
  $("#statementBtn").attr("disabled", true);

  var html = "";
  $.ajax({
    url: "/admin/statements/particular-entries",
    dataType: "get",
    dataType: "JSON",
    beforeSend: function () {
      $("#statementBtn").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></div>'
      );
    },
    data: {
      f_year: $('[name="f_year"]').val(),
      f_quarter: $('[name="f_quarter"]').val(),
      start_date: $('[name="start_date"]').val(),
      end_date: $('[name="end_date"]').val(),
    },
    success: function (response) {
      // Insert currency symbol
      var startDate = response.start_date;
      var endDate = response.end_date;
      // replace page with the response output
      $("#currency").text(currency);
      $("#start_date_input").text(formatDate(startDate, "long"));
      $("#end_date_input").text(formatDate(endDate, "long"));
      $('[name="start_date"]').val(startDate);
      $('[name="end_date"]').val(endDate);
      var printURL =
        "/admin/statements/export-statement/" +
        statement +
        "/" +
        startDate +
        "/" +
        endDate +
        "/" +
        particularId;
      $("#printURL").attr("href", printURL);
      if (response.data.length > 0) {
        var balanceTotal =
          (debitAmount =
          creditAmount =
          debitOpening =
          creditOpening =
            0);
        var debit = (credit = debitClosing = creditClosing = 0);
        var opningDebitbal = (opningCreditbal = 0);
        var status = "";
        // assing initial opening balance
        if (part == "debit") {
          opningDebitbal = debitAmount = opening;
          creditOpening = 0;
        } else {
          opningCreditbal = creditAmount = opening;
          debitOpening = 0;
        }
        // debit opening
        if (opningDebitbal >= 0) {
          debitOpening = opningDebitbal;
        } else {
          creditOpening = opningCreditbal;
        }
        // credit opening
        if (opningCreditbal >= 0) {
          creditOpening = opningCreditbal;
        } else {
          debitOpening = opningDebitbal;
        }
        // closing balance
        balanceTotal = debitAmount - creditAmount;
        if (balanceTotal >= 0) {
          debitClosing = balanceTotal;
        } else {
          creditClosing = -balanceTotal;
        }
        //  add openinng balance row  as the first row in the table
        // html +=
        //   "<tr>" +
        //   "<td>" +
        //   created_at +
        //   "</td>" +
        //   "<td>Opening Balance</td>" +
        //   '<td>' + part + '</td>' +
        //   '<td>' + particularName + ' Opening Balance</td>' +
        //   '<td class="text-right">' +
        //   debitOpening.toLocaleString() +
        //   "</td>" +
        //   '<td class="text-right">' +
        //   creditOpening.toLocaleString() +
        //   "</td>" +
        //   '<td class="text-right">' +
        //   debit.toLocaleString() +
        //   "</td>" +
        //   '<td class="text-right">' +
        //   credit.toLocaleString() +
        //   "</td>" +
        //   '<td class="text-right">' +
        //   debitClosing.toLocaleString() +
        //   "</td>" +
        //   '<td class="text-right">' +
        //   creditClosing.toLocaleString() +
        //   "</td>" +
        //   '<td><a href="/admin/particular/info/' + particularId + '" class="font-italic" title="Go to particular"><i class="fas fa-eye text-success"></i>#' + "<?= $particular['id'] ?>" + '</a></td>' +
        //   "</tr>";
        $.each(response.data, function (i, data) {
          // assing amount to respective part based on the transaction status
          // put amount to debit if status is credit n particularID == payment_id
          if (data.payment_id == particularId && data.status == "credit") {
            debit = Number(data.amount);
            credit = 0;
            debitAmount += debit;
          }
          // put amount to credit if status is debit n particularID == payment_id
          if (data.payment_id == particularId && data.status == "debit") {
            debit = 0;
            credit = Number(data.amount);
            creditAmount += credit;
          }
          // put amount to credit if status is credit n particularID == particular_id
          if (data.particular_id == particularId && data.status == "credit") {
            debit = 0;
            credit = Number(data.amount);
            creditAmount += credit;
          }
          // put amount to credit if status is debit n particularID == particular_id
          if (data.particular_id == particularId && data.status == "debit") {
            debit = Number(data.amount);
            credit = 0;
            debitAmount += debit;
          }
          // debit opening
          if (balanceTotal >= 0) {
            debitOpening = balanceTotal;
            creditOpening = 0;
          } else {
            creditOpening = -balanceTotal;
            debitOpening = 0;
          }
          // closing balance
          balanceTotal = debitAmount - creditAmount;
          if (balanceTotal >= 0) {
            debitClosing = balanceTotal;
            creditClosing = 0;
          } else {
            creditClosing = -balanceTotal;
            debitClosing = 0;
          }
          // set transaction status for payment && non-payment particulars
          if (particularAcountId == 1) {
            // payment particular
            // reciprocate the status for payment particulars
            if (data.status == "credit") {
              status = "debit";
            }
            if (data.status == "debit") {
              status = "credit";
            }
          } else {
            // non-payment particular
            status = data.status;
          }
          if (
            data.payment_id == particularId ||
            data.particular_id == particularId
          ) {
            // generate table rows
            html +=
              "<tr>" +
              "<td>" +
              data.date +
              "</td>" +
              '<td><a href="/admin/transaction/info/' +
              data.ref_id +
              '" class="text-info font-italic" title="Go to Transaction">' +
              data.entry_details +
              "</a></td>" +
              // '<td>' + status + '</td>' +
              // '<td>' + data.entry_details + '</td>' +
              '<td class="text-right">' +
              debitOpening.toLocaleString() +
              "</td>" +
              '<td class="text-right">' +
              creditOpening.toLocaleString() +
              "</td>" +
              '<td class="text-right">' +
              debit.toLocaleString() +
              "</td>" +
              '<td class="text-right">' +
              credit.toLocaleString() +
              "</td>" +
              '<td class="text-right">' +
              debitClosing.toLocaleString() +
              "</td>" +
              '<td class="text-right">' +
              creditClosing.toLocaleString() +
              "</td>" +
              // '<td><i class="fas fa-eye text-success"></i>#' + data.ref_id + '</td>' +
              "</tr>";
          }
        });
        if (balanceTotal < 0) {
          balanceTotal = (-balanceTotal).toLocaleString() + " <i>(Credit)</i>";
        } else {
          balanceTotal = balanceTotal.toLocaleString() + " <i>(Debit)</i>";
        }
        html +=
          "<tr>" +
          '<th class="text-center" colspan="4">Total[' +
          currency +
          "]</th>" +
          '<th class="text-right">' +
          debitAmount.toLocaleString() +
          "</th>" +
          '<th class="text-right">' +
          creditAmount.toLocaleString() +
          "</th>" +
          '<th class="text-center" colspan="2">Closing Balance: ' +
          balanceTotal +
          "</th>" +
          "</tr>";
      } else {
        html +=
          "<tr>" +
          '<td class="text-center tex-bold" colspan="8"> No Data Found</th>' +
          "</tr>";
      }
      $("tbody#particularstatement-body").html(html);

      $("#statementBtn").html('<i class="fa fa-search fa-fw"></i>');
      $("#statementBtn").attr("disabled", false);
    },
    error: function () {
      $("#statementBtn").html('<i class="fa fa-search fa-fw"></i>');
      $("#statementBtn").attr("disabled", false);
    },
  });
}
