/* global Chart:false */

$(function () {
  'use strict'

  /* column chart with datalabels */
  var options = {
    series: [{
      name: 'Financing',
      data: monthly_totals('financing')
    },
    {
      name: 'Expenses',
      data: monthly_totals('expenses')
    },
    {
      name: 'Transfers',
      data: monthly_totals('transfer')
    },
    ],
    chart: {
      height: 320,
      type: 'bar',
    },
    grid: {
      borderColor: '#f2f5f7',
    },
    plotOptions: {
      bar: {
        borderRadius: 10,
        dataLabels: {
          position: 'top', // top, center, bottom
        },
      }
    },
    dataLabels: {
      enabled: true,
      formatter: function (val) {
        if (val >= 1000000000) {
          val /= 1000000
          val += 'B'
        }
        if (val >= 1000000) {
          val /= 1000000
          val += 'M'
        }
        if (val >= 1000) {
          val /= 1000
          val += 'K'
        }
        return val;
      },
      offsetY: -20,
      style: {
        fontSize: '12px',
        colors: ["#8c9097"]
      }
    },
    colors: ["#07A817", "#9D0707F2", "#f5b849"],
    xaxis: {
      categories: entry_months(),
      position: 'bottom',
      axisBorder: {
        show: false
      },
      axisTicks: {
        show: false
      },
      crosshairs: {
        fill: {
          type: 'gradient',
          gradient: {
            colorFrom: '#D8E3F0',
            colorTo: '#BED1E6',
            stops: [0, 100],
            opacityFrom: 0.4,
            opacityTo: 0.5,
          }
        }
      },
      tooltip: {
        enabled: true,
      },
      labels: {
        show: true,
        style: {
          colors: "#8c9097",
          fontSize: '11px',
          fontWeight: 600,
          cssClass: 'apexcharts-xaxis-label',
        },
      }
    },
    yaxis: {
      axisBorder: {
        show: false
      },
      axisTicks: {
        show: false,
      },
      labels: {
        show: false,
        formatter: function (val) {
          return currency + val;
        }
      }
  
    },
    title: {
      text: 'Monthly Total Transactions',
      floating: true,
      offsetY: 330,
      align: 'center',
      style: {
        color: '#444'
      }
    }
  };
  var chart = new ApexCharts(document.querySelector("#monthly-transactions"), options);
  chart.render();

})

function monthly_totals(entry_menu) {
  const total = [];
  $.ajax({
    url: "/admin/reports/monthly-transactions-report/" + entry_menu,
    async: false,
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      if (data.length > 0) {
        data.forEach(function (value, index) {
          total.push(value);
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, 'error');
    }
  });
  return total;
}

function entry_months() {
  const year = new Date().getFullYear();
  const months = [];
  $.ajax({
    url: '/admin/statements/entryMonths/' + year,
    async: false,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      for (var i in data) {
        months.push(data[i]);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, 'error');
    }
  });
  return months;
}

  // lgtm [js/unused-local-variable]
