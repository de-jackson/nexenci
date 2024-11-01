// dataTables buttons config
var buttonsConfig = [];
if (userPermissions.includes("export_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
  buttonsConfig.push(
    {
      extend: "excel",
      className: "btn btn-sm btn-success",
      titleAttr: "Export " + title + " To Excel",
      text: '<i class="fas fa-file-excel"></i>',
      filename: "Clients " + title + " Information",
      extension: ".xlsx",
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export Clients " + title + " To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: "Clients " + title + " Information",
      extension: ".pdf",
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export Clients " + title + " To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: "Clients " + title + " Information",
      extension: ".csv",
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-warning",
      titleAttr: "Copy Clients " + title + " Information",
      text: '<i class="fas fa-copy"></i>',
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>Clients " +
        title +
        " Information</h4><h5 class='text-center'>Printed On " +
        new Date().getHours() +
        " : " +
        new Date().getMinutes() +
        " " +
        new Date().toDateString() +
        "</h5>",
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

      className: "btn btn-sm btn-secondary",
      titleAttr: "Print Clients " + title + " Information",
      text: '<i class="fa fa-print"></i>',
      filename: "Clients " + title + " Information",
    }
  );
}

$(function () {
  // get the running branches
  selectBranch();

  // get the years of the entries
  savingEntryYears();

  // gender
  generateGender();

  // transaction entry type
  transactionEntryTypes();

  // auto load the savings report
  filterSavings();

  /* ChartJS
   * -------
   * Here we will create a few charts using ChartJS
   */

  var ticksStyle = {
    // fontColor: '#495057',
    fontStyle: "bold",
  };

  //--------------
  //- AREA CHART -
  //--------------

  // Get context with jQuery - using jQuery's .get() method.
  var areaChartCanvas = $("#areaChart").get(0).getContext("2d");

  var areaChartData = {
    labels: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sept",
      "Oct",
      "Nov",
      "Dec",
    ],
    datasets: [
      {
        label: "Debit Transactions",
        backgroundColor: "rgba(60,141,188,0.9)",
        borderColor: "rgba(60,141,188,0.8)",
        pointRadius: false,
        pointColor: "#3b8bba",
        pointStrokeColor: "rgba(60,141,188,1)",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(60,141,188,1)",
        data: [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1100, 1200],
      },
      {
        label: "Credit Transactions",
        backgroundColor: "rgba(210, 214, 222, 1)",
        borderColor: "rgba(210, 214, 222, 1)",
        pointRadius: false,
        pointColor: "rgba(210, 214, 222, 1)",
        pointStrokeColor: "#c1c7d1",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1100, 1200],
      },
    ],
  };

  var areaChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false,
    },
    scales: {
      xAxes: [
        {
          gridLines: {
            display: false,
          },
        },
      ],
      yAxes: [
        {
          gridLines: {
            display: false,
          },
          // added this to show the currency
          ticks: $.extend(
            {
              beginAtZero: true,
              // Include a currency sign in the ticks
              callback: function (value) {
                if (value >= 1000000) {
                  value /= 1000000;
                  value += "M";
                }
                if (value >= 1000) {
                  value /= 1000;
                  value += "K";
                }

                return currency + " " + value;
              },
            },
            ticksStyle
          ),
        },
      ],
    },
  };

  // This will get the first returned node in the jQuery collection.
  new Chart(areaChartCanvas, {
    type: "line",
    data: areaChartData,
    options: areaChartOptions,
  });

  //-------------
  //- LINE CHART -
  //--------------
  var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
  var lineChartOptions = $.extend(true, {}, areaChartOptions);
  var lineChartData = $.extend(true, {}, areaChartData);
  lineChartData.datasets[0].fill = false;
  lineChartData.datasets[1].fill = false;
  lineChartOptions.datasetFill = false;

  var lineChart = new Chart(lineChartCanvas, {
    type: "line",
    data: lineChartData,
    options: lineChartOptions,
  });

  //-------------
  //- DONUT CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var donutChartCanvas = $("#donutChart").get(0).getContext("2d");
  var donutData = {
    labels: ["Chrome", "IE", "FireFox", "Safari", "Opera", "Navigator"],
    datasets: [
      {
        data: [700, 500, 400, 600, 300, 100],
        backgroundColor: [
          "#f56954",
          "#00a65a",
          "#f39c12",
          "#00c0ef",
          "#3c8dbc",
          "#d2d6de",
        ],
      },
    ],
  };
  var donutOptions = {
    maintainAspectRatio: false,
    responsive: true,
  };
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  new Chart(donutChartCanvas, {
    type: "doughnut",
    data: donutData,
    options: donutOptions,
  });

  //-------------
  //- PIE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
  var pieData = donutData;
  var pieOptions = {
    maintainAspectRatio: false,
    responsive: true,
  };
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  new Chart(pieChartCanvas, {
    type: "pie",
    data: pieData,
    options: pieOptions,
  });

  //-------------
  //- BAR CHART -
  //-------------
  var barChartCanvas = $("#barChart").get(0).getContext("2d");
  var barChartData = $.extend(true, {}, areaChartData);
  var temp0 = areaChartData.datasets[0];
  var temp1 = areaChartData.datasets[1];
  barChartData.datasets[0] = temp1;
  barChartData.datasets[1] = temp0;

  var barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    datasetFill: false,
  };

  new Chart(barChartCanvas, {
    type: "bar",
    data: barChartData,
    options: barChartOptions,
  });

  //---------------------
  //- STACKED BAR CHART -
  //---------------------
  var stackedBarChartCanvas = $("#stackedBarChart").get(0).getContext("2d");
  var stackedBarChartData = $.extend(true, {}, barChartData);

  var stackedBarChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      xAxes: [
        {
          stacked: true,
        },
      ],
      yAxes: [
        {
          stacked: true,

          // added this to show the currency
          ticks: $.extend(
            {
              beginAtZero: true,
              // Include a currency sign in the ticks
              callback: function (value) {
                if (value >= 1000000) {
                  value /= 1000000;
                  value += "M";
                }
                if (value >= 1000) {
                  value /= 1000;
                  value += "K";
                }

                return currency + " " + value;
              },
            },
            ticksStyle
          ),
        },
      ],
    },
  };

  new Chart(stackedBarChartCanvas, {
    type: "bar",
    data: stackedBarChartData,
    options: stackedBarChartOptions,
  });
});

// get DatePicker
$(document).on("focus", ".getDatePicker", function () {
  $(this).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "yy-mm-dd",
    yearRange: "2020:2050",
  });
});

function savingEntryYears(account_type_id = null) {
  $.ajax({
    url: "/admin/reports/module/years-of-entries/12",
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
      $("select#year").html('<option value="">Error Occured</option>');
    },
  });
}

function transactionEntryTypes(entry_typeId = null) {
  if (!entry_typeId) {
    $.ajax({
      url: "/admin/transactions/transaction-types/12",
      type: "POST",
      dataType: "JSON",
      success: function (data) {
        $("select#entry_typeId").html('<option value="">-- select --</option>');
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].type)
              .appendTo($("select#entry_typeId"));
          }
        } else {
          $("select#entry_typeId").html('<option value="">No Type</option>');
        }
      },
      error: function (err) {
        $("select#entry_typeId").html(
          '<option value="">Error Occured</option>'
        );
      },
    });
  } else {
    $.ajax({
      url: "/admin/transactions/transaction-types/12",
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
    });
  }
}

// render date datewise
$(document).on("click", "#filter-savings", function () {
  $("#filter-savings").html(
    '<i class="fa fa-spinner fa-spin"></i> searching...'
  );
  $("#filter-savings").attr("disabled", true);
  var year = $("select#year").val();
  var start_date = $("input#start_date").val();
  var end_date = $("input#end_date").val();
  var gender = $("select#gender").val();
  var account_no = $("input#account_no").val();
  var others = $("input#others").val();

  // JS  debit data objects
  var data = {
    entry_status: "",
    year: year,
    start_date: start_date,
    end_date: end_date,
    gender: gender,
    account_no: account_no,
    others: others,
  };

  // JS  credit data objects
  var creditObject = {
    entry_status: "",
    year: year,
    start_date: start_date,
    end_date: end_date,
    gender: gender,
    account_no: account_no,
    others: others,
  };

  // call the filter savings report
  filterSavingsReport(data);

  // call the savings chart report
  savingsCharts(data, creditObject);
});

function filterSavings() {
  $("#filter-savings").html(
    '<i class="fa fa-spinner fa-spin"></i> searching...'
  );
  $("#filter-savings").attr("disabled", true);

  var year = $("select#year").val();
  var start_date = $("input#start_date").val();
  var end_date = $("input#end_date").val();
  var gender = $("select#gender").val();
  var account_no = $("input#account_no").val();
  var others = $("input#others").val();
  var payment_id = $("select#payment_id").val();
  var entry_typeId = $("select#entry_typeId").val();
  var reference_id = $("input#reference_id").val();
  var branch_id = $("select#branch_id").val();
  var staff_id = $("select#staff_id").val();

  // JS  debit data objects
  var data = {
    entry_status: "",
    year: year,
    start_date: start_date,
    end_date: end_date,
    gender: gender,
    account_no: account_no,
    others: others,
    payment_id: payment_id,
    entry_typeId: entry_typeId,
    reference_id: reference_id,
    branch_id: branch_id,
    staff_id: staff_id,
  };

  // JS  credit data objects
  var creditObject = {
    entry_status: "",
    year: year,
    start_date: start_date,
    end_date: end_date,
    gender: gender,
    account_no: account_no,
    others: others,
    payment_id: payment_id,
    entry_typeId: entry_typeId,
    reference_id: reference_id,
    branch_id: branch_id,
    staff_id: staff_id,
  };

  var url = baseURL + "/admin/reports/module/get-report-type/savings";
  // ajax adding data to database
  $.ajax({
    url: url,
    type: "POST",
    data: $("#form").serialize(),
    dataType: "JSON",
    beforeSend: function () {
      $("#filter_savings").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>'
      );
    },
    success: function (results) {
      // call the savings chart report
      // savingsCharts(data, creditObject);

      var dataTable =
        '<div class="table-responsive"><table id="savings" class="table table-sm table-striped table-hover"' +
        'cellspacing="0" width="100%"><tbody></tbody>' +
        "<tfoot>" +
        "<tr>" +
        "<th>Total Amount</th>" +
        "<th>" +
        results.yearlyTotalCredit +
        "</th>" +
        "<th>" +
        results.yearlyTotalDebit +
        "</th>" +
        "<th>" +
        results.yearlyTotal +
        "</th>" +
        "</tr>" +
        "</tfoot></table></div>";
      $("#filter_savings").html(dataTable);
      var table = $("#savings").DataTable({
        data: results.data,
        bPaginate: true,
        bLengthChange: true,
        language: {
          paginate: {
            next: '<i class="fa-solid fa-angle-right"></i>',
            previous: '<i class="fa-solid fa-angle-left"></i>',
          },
        },
        pageLength: 25,
        lengthMenu: [
          [10, 25, 50, 100],
          [10, 25, 50, 100],
        ],
        bFilter: false,
        bInfo: true,
        bAutoWidth: true,
        columns: [
          { title: "Year - Month" },
          { title: "Total Credit [" + currency + "]" },
          { title: "Total Debit [" + currency + "]" },
          { title: "Total Amount [" + currency + "]" },
        ],
        dom: "lBfrtip",
        // dom: "lBfr<'row'<'col-sm-12'tr>>" +
        //         "<'row'<'col-sm-6'i><'col-sm-6'p>>",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
      });
      // client counter
      var dataTableClient =
        '<div class="table-responsive"><table id="clientCounter" class="table table-sm table-striped table-hover"' +
        'cellspacing="0" width="100%"><tbody></tbody>' +
        "<tfoot>" +
        "<tr>" +
        "<th>Total Number</th>" +
        "<th>" +
        results.yearlyCreditClientsNumber +
        "</th>" +
        "<th>" +
        results.yearlyDebitClientsNumber +
        "</th>" +
        "<th>" +
        results.yearlyClientsNumber +
        "</th>" +
        "</tr>" +
        "</tfoot></table></div>";
      $("#client_counter").html(dataTableClient);
      var table = $("#clientCounter").DataTable({
        data: results.clientsCounter,
        bPaginate: true,
        bLengthChange: true,
        language: {
          paginate: {
            next: '<i class="fa-solid fa-angle-right"></i>',
            previous: '<i class="fa-solid fa-angle-left"></i>',
          },
        },
        pageLength: 25,
        lengthMenu: [
          [10, 25, 50, 100],
          [10, 25, 50, 100],
        ],
        bFilter: false,
        bInfo: true,
        bAutoWidth: true,
        columns: [
          { title: "Year - Month" },
          { title: "Number of Clients [Credit]" },
          { title: "Number of Clients [Debit]" },
          { title: "Total Number" },
        ],
        dom: "lBfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
      });

      // client savings entries
      var dataTableSavings =
        '<div class="table-responsive"><table id="savings_client_entries" class="table table-sm table-striped table-hover"' +
        'cellspacing="0" width="100%"><tbody></tbody>' +
        "</table></div>";
      $("#client_savings").html(dataTableSavings);
      var table = $("#savings_client_entries").DataTable({
        data: results.entries,
        bPaginate: true,
        bLengthChange: true,
        language: {
          paginate: {
            next: '<i class="fa-solid fa-angle-right"></i>',
            previous: '<i class="fa-solid fa-angle-left"></i>',
          },
        },
        pageLength: 25,
        lengthMenu: [
          [10, 25, 50, 100, 250, 500, -1],
          [10, 25, 50, 100, 250, 500, "All"],
        ],
        bFilter: false,
        bInfo: true,
        bAutoWidth: true,
        columns: [
          { title: "S.No", width: "4%" },
          { title: "Date" },
          { title: "Client" },
          { title: "Account No" },
          { title: "Amount [" + currency + "]" },
          { title: "Status" },
          { title: "Total [" + currency + "]" },
        ],
        dom: "lBfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
      });

      $("#filter-savings").html('<i class="fa fa-search fa-fw"></i>');
      $("#filter-savings").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // swal("Sorry!", "Check your Internet Connection!", "error");
      $("#filter-savings").html('<i class="fa fa-search fa-fw"></i>');
      $("#filter-savings").attr("disabled", false);
    },
  });
}

function filterSavingsReport(element) {
  // var logo = baseURL + "uploads/logo/" + syslogo;
  var startDate = $("input#start_date").val();
  var end_date = $("input#end_date").val();
  var year = $("select#year").val();

  $.ajax({
    url: baseURL + "/admin/reports/module/generate-savings-report",
    data: {
      year: element.year,
      start_date: element.start_date,
      end_date: element.end_date,
      gender: element.gender,
      account_no: element.account_no,
      others: element.others,
    },
    type: "post",
    dataType: "json",
    beforeSend: function () {
      $("#filter_savings").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>'
      );
    },
    success: function (results) {
      var dataTable =
        '<div class="table-responsive"><table id="savings" class="table table-sm table-striped table-hover"' +
        'cellspacing="0" width="100%"><tbody></tbody>' +
        "<tfoot>" +
        "<tr>" +
        "<th>Total Amount</th>" +
        "<th>" +
        results.yearlyTotalCredit +
        "</th>" +
        "<th>" +
        results.yearlyTotalDebit +
        "</th>" +
        "<th>" +
        results.yearlyTotal +
        "</th>" +
        "</tr>" +
        "</tfoot></table></div>";
      $("#filter_savings").html(dataTable);
      var table = $("#savings").DataTable({
        data: results.data,
        bPaginate: true,
        bLengthChange: true,
        language: {
          paginate: {
            next: '<i class="fa-solid fa-angle-right"></i>',
            previous: '<i class="fa-solid fa-angle-left"></i>',
          },
        },
        pageLength: 25,
        lengthMenu: [
          [10, 25, 50, 100],
          [10, 25, 50, 100],
        ],
        bFilter: false,
        bInfo: true,
        bAutoWidth: true,
        columns: [
          { title: "Year - Month" },
          { title: "Total Credit [" + currency + "]" },
          { title: "Total Debit [" + currency + "]" },
          { title: "Total Amount [" + currency + "]" },
        ],
        dom: "lBfrtip",
        // dom: "lBfr<'row'<'col-sm-12'tr>>" +
        //         "<'row'<'col-sm-6'i><'col-sm-6'p>>",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
      });

      $("#filter-savings").html('<i class="fa fa-search fa-fw"></i>');
      $("#filter-savings").attr("disabled", false);
    },
  });
}

function monthly_totals(data) {
  const total = [];
  $.ajax({
    url: "/admin/reports/module/get-report-type/savings",
    async: false,
    type: "POST",
    dataType: "JSON",
    data: {
      entry_status: data.entry_status,
      year: data.year,
      start_date: data.start_date,
      end_date: data.end_date,
      gender: data.gender,
      account_no: data.account_no,
      others: data.others,
      payment_id: data.payment_id,
      entry_typeId: data.entry_typeId,
      reference_id: data.reference_id,
      branch_id: data.branch_id,
      staff_id: data.staff_id,
    },
    success: function (data) {
      if (data.monthlyTotal.length > 0) {
        data.monthlyTotal.forEach(function (value, index) {
          total.push(value);
        });
        // for (let index in data.report_data) {
        //   const element = data.report_data[index];
        //   total.push(element)

        // }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });

  return total;
}
