var statement = "balancesheet";
var tableId = "balancesheet";
var year = new Date().getFullYear();
var month = new Date().getMonth();
// dataTables buttons config
var buttonsConfig = [];
// add dataTable buttons
if (userPermissions.includes("export_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
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
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>" +
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
}

$(document).ready(function () {
  // Call the generate_balancesheet function when the button is clicked
  $("#statementBtn").on("click", function () {
    generate_balancesheet();
  });
  generate_balancesheet();

  // convert balancesheet to dataTables
  // convertDataTable(tableId, buttonsConfig)

  // load entry years and months
  $.ajax({
    url: "/admin/statements/entryYears",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          $("<option />")
            .val(data[i])
            .text(data[i])
            .appendTo($("select#entryYear"));
        }
      } else {
        $("select#entryYear").html('<option value="">No Years</option>');
      }
    },
    error: function (err) {
      $("select#entryYear").html('<option value="">Error Occured</option>');
    },
  });
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
// populate table
function generate_balancesheet() {
  $.ajax({
    url: "/admin/statements/generate-statement",
    method: "GET",
    dataType: "json",
    data: {
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
            html += "<tr>";
            html +=
              '<th class="text-info text-bold p-3" colspan="6">' +
              category.category_name +
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
                      '<th class="text-bold p-3" colspan="2">' +
                      subcategory.subcategory_name +
                      "</th>";
                    html += "</tr>";

                    // Particulars for each subcategory
                    if (response.particularData.length > 0) {
                      $.each(
                        response.particularData,
                        function (particularIndex, particular) {
                          if (particular.subcategory_id == subcategory.id) {
                            html += "<tr>";
                            html +=
                              '<td class="pl-5"><a href="/admin/statements/particular-statement/' +
                              particular.id +
                              "/" +
                              response.start_date +
                              "/" +
                              response.end_date +
                              '" class="text"><i>' +
                              particular.particular_name +
                              "</i></a></td>";
                            html +=
                              '<td align="right"><i>' +
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
                      '<td class="p-3"><i>Total ' +
                      subcategory.subcategory_name +
                      "</i></td>";
                    html +=
                      '<td class="" align="right"><i><u>' +
                      subcategory.balance +
                      " </u></i></td>";
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
              '<td class="text-info p-3"><i>Total ' +
              category.category_name +
              "</i></td>";
            html +=
              '<td class="text-info" align="right"><i><u>' +
              category.balance +
              " </u></i></td>";
            html += "</tr>";
            // retained earnings under equity
            if (category.id == 2) {
              // retained earnings row
              html += "<tr>";
              html += '<td class="p-3"><i>Retained Earning</i></td>';
              html +=
                '<td class="" align="right"><i><u>' +
                response.getTotals.grossIncome +
                " </u></i></td>";
              html += "</tr>";
              // equity + retained earnings total row
              html += '<tr class="text-info">';
              html += '<td class="p-3"><i>Equity + Retained</i></td>';
              html +=
                '<td align="right"><i><u>' +
                response.getTotals.equityRetainedTotal +
                " </u></i></td>";
              html += "</tr>";
            }
          }
        });
        // compare assets and equity + liability
        var textColor =
          response.getTotals.assetsTotal !=
          response.getTotals.equityLiabilityTotalSurplus
            ? "text-danger"
            : "text-success";
        html +=
          '<tr class="text-bold">' +
          '<td align="center">Total Assets</td>' +
          '<td align="center">Equity + Liabilities</td>' +
          "</tr>";
        html +=
          '<tr class="text-bold ' +
          textColor +
          '">' +
          '<td align="center">' +
          response.getTotals.assetsTotal +
          "</td>" +
          '<td align="center">' +
          response.getTotals.equityLiabilityTotalSurplus +
          "</td>" +
          "</tr>";
      } else {
        html +=
          '<tr><th class="text-center" colspan="2">No Categories Found</th></tr>';
      }

      // Insert the generated HTML into the table body
      $("#balancesheet-body").html(html);
    },
    error: function () {
      // Handle error case
    },
  });
}

$("select#entryYear").on("change", function () {
  var year = $(this).val();
  if (year != "") {
    $.ajax({
      url: "/admin/statements/entryMonths/" + year,
      dataType: "GET",
      dataType: "JSON",
      success: function (data) {
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i])
              .text(data[i])
              .appendTo($("select#entryMonth"));
          }
        }
      },
      error: function (error) {
        $("select#entryMonth").html('<option value="">Error Occured</option>');
      },
    });
  } else {
    $("select#entryMonth").html(
      '<option value="' + month + '">' + month + "</option>"
    );
  }
});
