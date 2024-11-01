$(function () {
  // get the running branches
  selectBranch();

  // get the years of the entries
  savingEntryYears();

  // gender
  generateGender();

  // transaction entry type
  transactionEntryTypes();

  // load the staff officer
  generateStaff();

  // transaction payment methods
  selectPaymentMethod();

  generateClientAccountNo();

  filterDashboardAccount();

  widgetChart("principalDisbursed", "var(--primary)");
  widgetChart("principalCollected", "#3AC977");
  widgetChart("actualInterest", "#FF5E5E");
  widgetChart("interestCollected", "#3AC977");
  widgetChart("savingsChart", "#3AC977");
  widgetChart("depositsChart", "#3AC977");
  widgetChart("withdrawChart", "var(--primary)");
  widgetChart("withdrawChargesChart", "#FF5E5E");
  widgetChart("applicationsChart", "var(--primary)");
  widgetChart("disbursementsChart", "var(--primary)");
  widgetChart("clientsChart", "var(--primary)");
  widgetChart("loanChart", "#FF5E5E");
  widgetChart("membershipChart1", "#3AC977");
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
      $("select#year").html('<option value="">Error Occurred</option>');
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
          '<option value="">Error Occurred</option>'
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

// Declare global variable
var chartData = {
  applications: "",
  disbursements: "",
  monthlySum: "",
  liquidity: "",
  membership: "",
  summary: "",
  percentage: "",
};

function filterDashboardAccount() {
  $("#btnSearch").html('<i class="fa fa-spinner fa-spin"></i> searching...');
  $("#btnSearch").attr("disabled", true);

  var year = $("select#year").val();
  var start_date = $("input#start_date").val();
  var end_date = $("input#end_date").val();
  var gender = $("select#gender").val();
  var account_no = $("select#account_no").val();
  var others = $("input#others").val();
  var status = $("select#status").val();
  var location = $("input#location").val();
  var reference_id = $("input#reference_id").val();
  var branch_id = $("select#branch_id").val();
  var staff_id = $("select#staff_id").val();
  // JS  total clients data objects
  var data = {
    year: year,
    start_date: start_date,
    end_date: end_date,
    gender: gender,
    account_no: account_no,
    others: others,
    status: status,
    location: location,
    reference_id: reference_id,
    branch_id: branch_id,
    staff_id: staff_id,
  };

  var url = baseURL + "/admin/reports/module/get-report-type/dashboard";
  // ajax adding data to database
  $.ajax({
    url: url,
    type: "POST",
    data: $("#form").serialize(),
    dataType: "JSON",
    beforeSend: function () {
      // load spinner where there is clients counter table report
      $("#clients_counter").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>'
      );
      // load spinner where there is clients table report
      $("#clients_table").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>'
      );
    },
    success: function (results) {
      $("#membersCounter").text(results.members.counter);
      $("#savingsBalance").text(currency + " " + results.savings.totalBalance);
      $("#savingsDeposited").text(currency + " " + results.savings.totalCredit);
      $("#savingsWithdrew").text(currency + " " + results.savings.totalDebit);
      $("#withdrawCharges").text(currency + " " + results.revenue.totalCredit);
      $("#totalMembership").text(
        currency + " " + results.membership.totalCredit
      );

      // Update global variable with response data
      chartData.liquidity = results.liquidity.particulars;
      chartData.membership = results.membership.particulars;
      chartData.summary = results.summary;
      chartData.percentage = 0;
      // Other properties can also be updated here
      // Reload the chart after updating data
      liquidityChart(results.liquidity.particulars);
      membershipChart(results.membership.particulars);
      loanRepaymentChart(0);
      transactionsChart(results.summary.savings);
      // console.log(results.liquidity.particulars);

      $("#btnSearch").html('<i class="fa fa-search fa-fw"></i>');
      $("#btnSearch").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $("#btnSearch").html('<i class="fa fa-search fa-fw"></i>');
      $("#btnSearch").attr("disabled", false);
    },
  });
}

function liquidityChart(data) {
  // Extracting series and labels from JSON data
  var series = data.map((item) => parseFloat(item.total_balance)); // Extracting total_balance for series
  var labels = data.map((item) => item.particular_name); // Extracting particular_name for labels

  // Function to generate random colors
  function generateRandomColors(numColors) {
    var colors = [];
    for (var i = 0; i < numColors; i++) {
      // Generate a random color in hexadecimal format
      var color = "#" + Math.floor(Math.random() * 16777215).toString(16);
      colors.push(color);
    }
    return colors;
  }

  // Generate random colors based on the number of data points
  var numColors = data.length;
  var colors = generateRandomColors(numColors);

  var options = {
    series: series,
    chart: {
      type: "donut",
      width: 320,
    },
    plotOptions: {
      pie: {
        donut: {
          size: "90%",
          labels: {
            show: true,
            name: {
              show: true,
              offsetY: 20,
            },
            value: {
              show: true,
              fontSize: "24px",
              fontWeight: "600",
              offsetY: -16,
            },
            total: {
              show: true,
              fontSize: "14px",
              color: "#888888",
              fontWeight: "500",
              label: "Total",

              formatter: function (w) {
                return w.globals.seriesTotals.reduce((a, b) => {
                  return a + b;
                }, 0);
              },
            },
          },
        },
      },
    },
    legend: {
      show: false,
      // position: 'bottom', // You can change the position as needed
      // markers: {
      // 	// width: 12,
      // 	// height: 12,
      // 	strokeWidth: 0,
      // 	radius: 12,
      // 	fillColors: colors
      // },
      // itemMargin: {
      // 	horizontal: 10,
      // 	vertical: 5
      // },
      // formatter: function(seriesName, opts) {
      // 	return seriesName + ": " + opts.w.globals.series[opts.seriesIndex];
      // }
    },
    colors: colors,
    labels: labels,
    dataLabels: {
      enabled: false,
    },
    stroke: {
      width: 3,
      colors: ["var(--bs-white)"],
    },
    responsive: [
      {
        breakpoint: 1480,
        options: {
          chart: {
            type: "donut",
            width: 250,
            // width: 480,
          },
        },
      },
    ],
  };
  $("#liquidityChart").empty();
  if ($("#liquidityChart").length > 0) {
    var liquidityChartData = new ApexCharts(
      document.querySelector("#liquidityChart"),
      options
    );
    liquidityChartData.render();
  }
}

function membershipChart(data) {
  // Extracting series and labels from JSON data
  var series = data.map((item) => parseFloat(item.total_balance)); // Extracting total_balance for series
  var labels = data.map((item) => item.particular_name); // Extracting particular_name for labels

  // Function to generate random colors
  function generateRandomColors(numColors) {
    var colors = [];
    for (var i = 0; i < numColors; i++) {
      // Generate a random color in hexadecimal format
      var color = "#" + Math.floor(Math.random() * 16777215).toString(16);
      colors.push(color);
    }
    return colors;
  }

  // Generate random colors based on the number of data points
  var numColors = data.length;
  var colors = generateRandomColors(numColors);

  var options = {
    series: series,
    chart: {
      type: "donut",
      width: 320,
    },
    plotOptions: {
      pie: {
        donut: {
          size: "90%",
          labels: {
            show: true,
            name: {
              show: true,
              offsetY: 20,
            },
            value: {
              show: true,
              fontSize: "24px",
              fontWeight: "600",
              offsetY: -16,
            },
            total: {
              show: true,
              fontSize: "14px",
              color: "#888888",
              fontWeight: "500",
              label: "Total",

              formatter: function (w) {
                return w.globals.seriesTotals.reduce((a, b) => {
                  return a + b;
                }, 0);
              },
            },
          },
        },
      },
    },
    legend: {
      show: false,
      // position: 'bottom', // You can change the position as needed
      // markers: {
      // 	// width: 12,
      // 	// height: 12,
      // 	strokeWidth: 0,
      // 	radius: 12,
      // 	fillColors: colors
      // },
      // itemMargin: {
      // 	horizontal: 10,
      // 	vertical: 5
      // },
      // formatter: function(seriesName, opts) {
      // 	return seriesName + ": " + opts.w.globals.series[opts.seriesIndex];
      // }
    },
    colors: colors,
    labels: labels,
    dataLabels: {
      enabled: false,
    },
    stroke: {
      width: 3,
      colors: ["var(--bs-white)"],
    },
    responsive: [
      {
        breakpoint: 1480,
        options: {
          chart: {
            type: "donut",
            width: 250,
            // width: 480,
          },
        },
      },
    ],
  };

  $("#membershipChart").empty();

  if ($("#membershipChart").length > 0) {
    var membershipChartData = new ApexCharts(
      document.querySelector("#membershipChart"),
      options
    );
    membershipChartData.render();
  }
}

function widgetChart(element, color) {
  var options = {
    series: [
      {
        name: "Net Profit",
        data: [100, 300, 200, 250, 200, 240, 180, 230, 200, 250, 300],
        /* radius: 30,	 */
      },
    ],
    chart: {
      type: "area",
      height: 40,
      //width: 400,
      toolbar: {
        show: false,
      },
      zoom: {
        enabled: false,
      },
      sparkline: {
        enabled: true,
      },
    },

    colors: ["var(--primary)"],
    dataLabels: {
      enabled: false,
    },

    legend: {
      show: false,
    },
    stroke: {
      show: true,
      width: 2,
      curve: "straight",
      colors: [color],
    },

    grid: {
      show: false,
      borderColor: "#eee",
      padding: {
        top: 0,
        right: 0,
        bottom: 0,
        left: -1,
      },
    },
    states: {
      normal: {
        filter: {
          type: "none",
          value: 0,
        },
      },
      hover: {
        filter: {
          type: "none",
          value: 0,
        },
      },
      active: {
        allowMultipleDataPointsSelection: false,
        filter: {
          type: "none",
          value: 0,
        },
      },
    },
    xaxis: {
      categories: [
        "Jan",
        "feb",
        "Mar",
        "Apr",
        "May",
        "June",
        "July",
        "August",
        "Sept",
        "Oct",
      ],
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
      labels: {
        show: false,
        style: {
          fontSize: "12px",
        },
      },
      crosshairs: {
        show: false,
        position: "front",
        stroke: {
          width: 1,
          dashArray: 3,
        },
      },
      tooltip: {
        enabled: true,
        formatter: undefined,
        offsetY: 0,
        style: {
          fontSize: "12px",
        },
      },
    },
    yaxis: {
      show: false,
    },
    fill: {
      opacity: 0.9,
      colors: "var(--primary)",
      type: "gradient",
      gradient: {
        colorStops: [
          {
            offset: 0,
            color: "var(--primary)",
            opacity: 0.4,
          },
          {
            offset: 0.6,
            color: "var(--primary)",
            opacity: 0.4,
          },
          {
            offset: 100,
            color: "white",
            opacity: 0,
          },
        ],
      },
    },
    tooltip: {
      enabled: false,
      style: {
        fontSize: "12px",
      },
      y: {
        formatter: function (val) {
          return "$" + val + " thousands";
        },
      },
    },
  };

  $("#" + element).empty();

  var chartBar1 = new ApexCharts(
    document.querySelector("#" + element),
    options
  );
  chartBar1.render();
}

function loanRepaymentChart(percentage) {
  var options = {
    chart: {
      height: 127,
      width: 100,
      type: "radialBar",
    },

    series: [percentage],
    colors: ["rgba(255,255,255,0.9)"],
    plotOptions: {
      radialBar: {
        hollow: {
          margin: 0,
          size: "55%",
          background: "#fff",
        },
        dataLabels: {
          name: {
            offsetY: -10,
            color: "#4b9bfa",
            fontSize: ".625rem",
            show: false,
          },
          value: {
            offsetY: 5,
            color: "#4b9bfa",
            fontSize: ".875rem",
            show: true,
            fontWeight: 600,
          },
        },
      },
    },
    stroke: {
      lineCap: "round",
    },
    labels: ["Status"],
  };

  $("#loanRepaymentChart").empty();

  var chart = new ApexCharts(
    document.querySelector("#loanRepaymentChart"),
    options
  );
  chart.render();
}

function transactionsChart(data) {
  var monthlyTotals = data.savings;
  var creditData = [];
  var debitData = [];
  var months = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
  ];

  for (var i = 0; i < 12; i++) {
    var monthData = monthlyTotals.find((item) => item.month == i + 1);
    creditData.push(monthData ? parseInt(monthData.total_credit) : 0);
    debitData.push(monthData ? parseInt(monthData.total_debit) : 0);
  }
  var options = {
    series: [
      {
        name: "Total Transactions",
        type: "column",
        data: creditData.map((val, index) => val + debitData[index]),
      },
      {
        name: "Credit",
        type: "area",
        data: creditData,
      },
      {
        name: "Debit",
        type: "line",
        data: debitData,
      },
    ],
    chart: {
      height: 300,
      type: "line",
      stacked: false,
      toolbar: {
        show: false,
      },
    },
    stroke: {
      width: [0, 1, 1],
      curve: "straight",
      dashArray: [0, 0, 5],
    },
    legend: {
      fontSize: "13px",
      fontFamily: "poppins",
      labels: {
        colors: "#888888",
      },
    },
    plotOptions: {
      bar: {
        columnWidth: "18%",
        borderRadius: 6,
      },
    },

    fill: {
      //opacity: [0.1, 0.1, 1],
      type: "gradient",
      gradient: {
        inverseColors: false,
        shade: "light",
        type: "vertical",
        /* opacityFrom: 0.85,
          opacityTo: 0.55, */
        colorStops: [
          [
            {
              offset: 0,
              color: "var(--primary)",
              opacity: 1,
            },
            {
              offset: 100,
              color: "var(--primary)",
              opacity: 1,
            },
          ],
          [
            {
              offset: 0,
              color: "#3AC977",
              opacity: 1,
            },
            {
              offset: 0.4,
              color: "#3AC977",
              opacity: 0.15,
            },
            {
              offset: 100,
              color: "#3AC977",
              opacity: 0,
            },
          ],
          [
            {
              offset: 0,
              color: "#FF5E5E",
              opacity: 1,
            },
            {
              offset: 100,
              color: "#FF5E5E",
              opacity: 1,
            },
          ],
        ],
        stops: [0, 100, 100, 100],
      },
    },
    colors: ["var(--primary)", "#3AC977", "#FF5E5E"],
    labels: months,
    markers: {
      size: 0,
    },
    xaxis: {
      type: "month",
      labels: {
        style: {
          fontSize: "13px",
          colors: "#888888",
        },
      },
    },
    yaxis: {
      min: 0,
      tickAmount: 4,
      labels: {
        style: {
          fontSize: "13px",
          colors: "#888888",
        },
      },
    },
    tooltip: {
      shared: true,
      intersect: false,
      y: {
        formatter: function (y) {
          if (typeof y !== "undefined") {
            return currency + " " + y.toFixed(0);
          }
          return currency + " " + y;
        },
      },
    },
  };

  $("#transactionsChart").empty();

  var chart = new ApexCharts(document.querySelector("#transactionsChart"), options);
  chart.render();

  $(".mix-chart-tab .nav-link").on("click", function () {
    var seriesType = $(this).attr("data-series");
    var columnData = [];
    var areaData = [];
    var lineData = [];
    switch (seriesType) {
      case "week":
        var seriesTypeTotals = data.filter.week;
        for (var i = 0; i < 12; i++) {
          var monthData = seriesTypeTotals.find((item) => item.month == i + 1);
          areaData.push(monthData ? parseInt(monthData.total_credit) : 0);
          lineData.push(monthData ? parseInt(monthData.total_debit) : 0);
        }
        break;
      case "month":
        var monthlyTotals = data.filter.month;
        for (var i = 0; i < 12; i++) {
          var monthData = monthlyTotals.find((item) => item.month == i + 1);
          areaData.push(monthData ? parseInt(monthData.total_credit) : 0);
          lineData.push(monthData ? parseInt(monthData.total_debit) : 0);
        }

        break;
      case "year":
        var yearlyTotals = data.filter.year;
        for (var i = 0; i < 12; i++) {
          var monthData = yearlyTotals.find((item) => item.month == i + 1);
          areaData.push(monthData ? parseInt(monthData.total_credit) : 0);
          lineData.push(monthData ? parseInt(monthData.total_debit) : 0);
        }
        break;
      case "all":
        var seriesTypeTotals = data.savings;
        for (var i = 0; i < 12; i++) {
          var monthData = seriesTypeTotals.find((item) => item.month == i + 1);
          areaData.push(monthData ? parseInt(monthData.total_credit) : 0);
          lineData.push(monthData ? parseInt(monthData.total_debit) : 0);
        }
        break;
      default:
        columnData = [75, 80, 72, 100, 50, 100, 80, 30, 95, 35, 75, 100];
        areaData = [44, 65, 55, 75, 45, 55, 40, 60, 75, 45, 50, 42];
        lineData = [30, 25, 45, 30, 25, 35, 20, 45, 35, 30, 35, 20];
    }
    chart.updateSeries([
      {
        name: "Total Transactions",
        type: "column",
        data: areaData.map((val, index) => val + lineData[index]),
      },
      {
        name: "Credit",
        type: "area",
        data: areaData,
      },
      {
        name: "Debit",
        type: "line",
        data: lineData,
      },
    ]);
  });
}
