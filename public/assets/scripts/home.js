(function ($) {
  "use strict";

  var table = $(".data-table").DataTable({
    //dom: 'Bfrtip',
    // dom: "ZBfrltip",
    dom:
      "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
      "<'row'<'col-md-12'tr>>" +
      "<'row'<'col-md-5'i><'col-md-7'p>>",
    buttons: [
      {
        extend: "excel",
        text: '<i class="fa-solid fa-file-excel"></i> Excel Export Report',
        className: "btn btn-sm btn-info",
      },
      {
        extend: "pdf",
        className: "btn btn-sm btn-danger",
        titleAttr: "Export Transactions To PDF",
        text: '<i class="fas fa-file-pdf"></i> PDF Export Report',
        filename: "Transactions Information",
        extension: ".pdf",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7],
        },
      },
      {
        extend: "print",
        text: '<i class="fa-solid fa-print"></i> Print Export Report',
        className: "btn btn-sm btn-primary",
      },
    ],
    searching: true,
    select: false,
    pageLength: 5,
    lengthMenu: [
      [5, 10, 25, 50, 100, 250, 500],
      [5, 10, 25, 50, 100, 250, 500],
    ],
    lengthChange: false,
    language: {
      paginate: {
        next: '<i class="fa-solid fa-angle-right"></i>',
        previous: '<i class="fa-solid fa-angle-left"></i>',
      },
    },
  });
})(jQuery);

// Get the selected year from the select box
var selectedYear = new Date().getFullYear();
// var selectedYear = $('select#selectedYear').value;

/* Target Incomplete Chart */
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
document.querySelector("#crm-main").innerHTML = "";
var chart = new ApexCharts(document.querySelector("#crm-main"), options);
chart.render();
/* Target Incomplete Chart */

/* Total Customers chart */
var crm1 = {
  chart: {
    type: "line",
    height: 40,
    width: 100,
    sparkline: {
      enabled: true,
    },
  },
  stroke: {
    show: true,
    curve: "smooth",
    lineCap: "butt",
    colors: undefined,
    width: 1.5,
    dashArray: 0,
  },
  fill: {
    type: "gradient",
    gradient: {
      opacityFrom: 0.9,
      opacityTo: 0.9,
      stops: [0, 98],
    },
  },
  series: [
    {
      name: "Value",
      data: [20, 14, 19, 10, 23, 20, 22, 9, 12],
    },
  ],
  yaxis: {
    min: 0,
    show: false,
    axisBorder: {
      show: false,
    },
  },
  xaxis: {
    show: false,
    axisBorder: {
      show: false,
    },
  },
  tooltip: {
    enabled: false,
  },
  colors: ["rgb(132, 90, 223)"],
};
$("#crm-total-customers").innerHTML = "";
var crm1 = new ApexCharts(document.querySelector("#crm-total-customers"), crm1);
crm1.render();

function crmtotalCustomers() {
  crm1.updateOptions({
    colors: ["rgb(" + myVarVal + ")"],
  });
}
/* Total Customers chart */

/* Total revenue chart */
var crm2 = {
  chart: {
    type: "line",
    height: 40,
    width: 100,
    sparkline: {
      enabled: true,
    },
  },
  stroke: {
    show: true,
    curve: "smooth",
    lineCap: "butt",
    colors: undefined,
    width: 1.5,
    dashArray: 0,
  },
  fill: {
    type: "gradient",
    gradient: {
      opacityFrom: 0.9,
      opacityTo: 0.9,
      stops: [0, 98],
    },
  },
  series: [
    {
      name: "Value",
      data: [20, 14, 20, 22, 9, 12, 19, 10, 25],
    },
  ],
  yaxis: {
    min: 0,
    show: false,
    axisBorder: {
      show: false,
    },
  },
  xaxis: {
    show: false,
    axisBorder: {
      show: false,
    },
  },
  tooltip: {
    enabled: false,
  },
  colors: ["rgb(35, 183, 229)"],
};
$("#crm-total-revenue").innerHTML = "";
var crm2 = new ApexCharts(document.querySelector("#crm-total-revenue"), crm2);
crm2.render();
/* Total revenue chart */

/* Conversion ratio Chart */
var crm3 = {
  chart: {
    type: "line",
    height: 40,
    width: 100,
    sparkline: {
      enabled: true,
    },
  },
  stroke: {
    show: true,
    curve: "smooth",
    lineCap: "butt",
    colors: undefined,
    width: 1.5,
    dashArray: 0,
  },
  fill: {
    type: "gradient",
    gradient: {
      opacityFrom: 0.9,
      opacityTo: 0.9,
      stops: [0, 98],
    },
  },
  series: [
    {
      name: "Value",
      data: [20, 20, 22, 9, 14, 19, 10, 25, 12],
    },
  ],
  yaxis: {
    min: 0,
    show: false,
    axisBorder: {
      show: false,
    },
  },
  xaxis: {
    show: false,
    axisBorder: {
      show: false,
    },
  },
  tooltip: {
    enabled: false,
  },
  colors: ["rgb(38, 191, 148)"],
};
$("#crm-conversion-ratio").innerHTML = "";
var crm3 = new ApexCharts(
  document.querySelector("#crm-conversion-ratio"),
  crm3
);
crm3.render();
/* Conversion ratio Chart */

/* Total Deals Chart */
var crm4 = {
  chart: {
    type: "line",
    height: 40,
    width: 100,
    sparkline: {
      enabled: true,
    },
  },
  stroke: {
    show: true,
    curve: "smooth",
    lineCap: "butt",
    colors: undefined,
    width: 1.5,
    dashArray: 0,
  },
  fill: {
    type: "gradient",
    gradient: {
      opacityFrom: 0.9,
      opacityTo: 0.9,
      stops: [0, 98],
    },
  },
  series: [
    {
      name: "Value",
      data: [20, 20, 22, 9, 12, 14, 19, 10, 25],
    },
  ],
  yaxis: {
    min: 0,
    show: false,
    axisBorder: {
      show: false,
    },
  },
  xaxis: {
    show: false,
    axisBorder: {
      show: false,
    },
  },
  tooltip: {
    enabled: false,
  },
  colors: ["rgb(245, 184, 73)"],
};
$("#crm-total-deals").innerHTML = "";
var crm4 = new ApexCharts(document.querySelector("#crm-total-deals"), crm4);
crm4.render();
/* Total Deals Chart */

/* Transactions Analytics Chart */

// Initialize an empty array to store the chart series
var trasactionsSeries = [
  {
    type: "line",
    name: "Debit",
    data: [],
  },
  {
    type: "line",
    name: "Credit",
    data: [],
    // chart: {
    //   dropShadow: {
    //     enabled: true,
    //     enabledOnSeries: undefined,
    //     top: 5,
    //     left: 0,
    //     blur: 3,
    //     color: "#000",
    //     opacity: 0.1,
    //   },
    // },
  },
  {
    type: "line",
    name: "Transactions",
    data: [],
    // chart: {
    //   dropShadow: {
    //     enabled: true,
    //     enabledOnSeries: undefined,
    //     top: 5,
    //     left: 0,
    //     blur: 3,
    //     color: "#000",
    //     opacity: 0.1,
    //   },
    // },
  },
];

// Check if the selected year exists in the chartData
if (selectedYear in chartData.monthlySum) {
  var monthlyData = chartData.monthlySum[selectedYear];

  // Iterate through the data and generate the series array
  for (var month in monthlyData) {
    var debitDataPoint = {
      x: month,
      y: monthlyData[month].debit,
    };
    var creditDataPoint = {
      x: month,
      y: monthlyData[month].credit,
    };
    var transactionDataPoint = {
      x: month,
      y: monthlyData[month].total,
    };

    trasactionsSeries[0].data.push(debitDataPoint);
    trasactionsSeries[1].data.push(creditDataPoint);
    trasactionsSeries[2].data.push(transactionDataPoint);
  }
}
// Use the updated series array in your chart options
var transactionOptions = {
  series: trasactionsSeries,
  chart: {
    height: 350,
    animations: {
      speed: 500,
    },
    dropShadow: {
      enabled: true,
      enabledOnSeries: undefined,
      top: 8,
      left: 0,
      blur: 3,
      color: "#000",
      opacity: 0.1,
    },
    min: 0,
  },
  grid: {
    borderColor: "#f2f5f7",
  },
  stroke: {
    width: [3, 3, 2],
    curve: "straight",
  },
  colors: ["#845adf", "#23b7e5", "#f5b849"],

  dataLabels: {
    enabled: false,
  },
  xaxis: {
    axisTicks: {
      show: false,
    },
    padding: {
      left: 20,
    },
  },
  yaxis: {
    labels: {
      formatter: function (value) {
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
  },
  tooltip: {
    y: [
      {
        formatter: function (e) {
          return void 0 !== e ? currency + " " + e.toFixed(0) : e;
        },
      },
      {
        formatter: function (e) {
          return void 0 !== e ? currency + " " + e.toFixed(0) : e;
        },
      },
      {
        formatter: function (e) {
          return void 0 !== e ? e.toFixed(0) : e;
        },
      },
    ],
  },
  legend: {
    show: true,
    customLegendItems: ["Debit", "Credit", "Transactions"],
    inverseOrder: true,
  },
  title: {
    text: "Debit Against Credit Transactions (" + currency + ")",
    align: "left",
    style: {
      fontSize: ".8125rem",
      fontWeight: "semibold",
      color: "#8c9097",
    },
  },
  markers: {
    hover: {
      sizeOffset: 5,
    },
  },
};

$("#transactions-status-analytics").empty();
var chart = new ApexCharts(
  document.querySelector("#transactions-status-analytics"),
  transactionOptions
);
chart.render();

/* END Transactions Analytics Chart */

function revenueAnalytics() {
  chart.updateOptions({
    colors: [
      "rgba(" + myVarVal + ", 1)",
      "rgba(35, 183, 229, 0.85)",
      "rgba(119, 119, 142, 0.05)",
    ],
  });
}
/* Revenue Analytics Chart */

/* Loan Applications Chart */
applicationsData = chartData.applications.monthlyApplications; // monthly data from the controller

// Prepare the chart series and colors
var applicationsSeries = [];
var colors = [];
var months = [
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
];
// is application data isnt empty
if (applicationsData[selectedYear] != null) {
  months = Object.keys(applicationsData[selectedYear]);
  // console.log(applicationsData[selectedYear])
  for (const [index, status] of Object.entries(
    Object.keys(applicationsData[selectedYear][months[0]])
  )) {
    if (status !== "total" && status !== "totalCount") {
      const data = months.map(
        (month) => applicationsData[selectedYear][month][status]
      );
      applicationsSeries.push({
        name: status,
        data: data,
      });
      colors.push(getStatusColor(index)); // Custom function to get color for each status index
    }
  }
}

// Update the 'applicationOptions' with dynamic data and colors
const applicationOptions = {
  series: applicationsSeries,
  chart: {
    type: "bar",
    height: 180,
    toolbar: {
      show: false,
    },
  },
  grid: {
    borderColor: "#f1f1f1",
    strokeDashArray: 3,
  },
  colors: colors, // Assign different colors for each status
  plotOptions: {
    bar: {
      columnWidth: "60%",
      borderRadius: 5,
    },
  },
  dataLabels: {
    enabled: false,
  },
  stroke: {
    show: true,
    width: 2,
    colors: undefined,
  },
  legend: {
    show: false,
    position: "top",
  },
  yaxis: {
    title: {
      style: {
        color: "#adb5be",
        fontSize: "13px",
        fontFamily: "poppins, sans-serif",
        fontWeight: 600,
        cssClass: "apexcharts-yaxis-label",
      },
    },
    labels: {
      formatter: function (value) {
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
  },
  xaxis: {
    type: "category",
    categories: months,
    axisBorder: {
      show: true,
      color: "rgba(119, 119, 142, 0.05)",
      offsetX: 0,
      offsetY: 0,
    },
    axisTicks: {
      show: true,
      borderType: "solid",
      color: "rgba(119, 119, 142, 0.05)",
      width: 6,
    },
    labels: {
      rotate: -90,
    },
  },
};

// Custom function to get color for each status index
function getStatusColor(index) {
  const colors = [
    "#03adfc",
    "#03fc98",
    "#fcc603",
    "#8a0f61",
    "#0ddb1e",
    "#8c6b06",
  ];
  return colors[index % colors.length];
}
$("#loan-applications").empty();
var chart1 = new ApexCharts(
  document.querySelector("#loan-applications"),
  applicationOptions
);
chart1.render();

function loanApplications() {
  chart1.updateOptions({
    colors: ["rgba(" + myVarVal + ", 1)", "#ededed"],
  });
}
/* END Loan Applications Chart */ 

/* Disbursements By Status Chart */
disbursementsData = chartData.disbursements; // data from the controller
Chart.defaults.elements.arc.borderWidth = 0;
Chart.defaults.datasets.doughnut.cutout = "85%";
var chartInstance = new Chart($("#disbursements-status"), {
  type: "doughnut",
  data: {
    datasets: [
      {
        label: "Disbursements By Status",
        data: [
          disbursementsData.running,
          disbursementsData.arrears,
          disbursementsData.expired,
          disbursementsData.cleared,
        ],
        backgroundColor: ["#0dcaf0", "#ffc107", "#dc3545", "#198754"],
      },
    ],
  },
  plugins: [
    {
      afterUpdate: function (chart) {
        const arcs = chart.getDatasetMeta(0).data;

        arcs.forEach(function (arc) {
          arc.round = {
            x: (chart.chartArea.left + chart.chartArea.right) / 2,
            y: (chart.chartArea.top + chart.chartArea.bottom) / 2,
            radius: (arc.outerRadius + arc.innerRadius) / 2,
            thickness: (arc.outerRadius - arc.innerRadius) / 2,
            backgroundColor: arc.options.backgroundColor,
          };
        });
      },
      afterDraw: (chart) => {
        const { ctx, canvas } = chart;

        chart.getDatasetMeta(0).data.forEach((arc) => {
          const startAngle = Math.PI / 2 - arc.startAngle;
          const endAngle = Math.PI / 2 - arc.endAngle;

          ctx.save();
          ctx.translate(arc.round.x, arc.round.y);
          ctx.fillStyle = arc.options.backgroundColor;
          ctx.beginPath();
          ctx.arc(
            arc.round.radius * Math.sin(endAngle),
            arc.round.radius * Math.cos(endAngle),
            arc.round.thickness,
            0,
            2 * Math.PI
          );
          ctx.closePath();
          ctx.fill();
          ctx.restore();
        });
      },
    },
  ],
});

/* END Disbursements By Status Chart */

function disbursements(myVarVal) {
  chartInstance.data.datasets[0] = {
    label: "Disbursement By Status",
    data: [1, 1, 1, 1],
    backgroundColor: [
      `rgb(${myVarVal})`,
      "rgb(35, 183, 229)",
      "rgb(245, 184, 73)",
      "rgb(38, 191, 148)",
    ],
  };
  chartInstance.update();
}
/* Disbursements By Status Chart */
