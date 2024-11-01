(function ($) {
  /* "use strict" */

  var dzChartlist = (function () {
    var screenWidth = $(window).width();
    let draw = Chart.controllers.line.__super__.draw; //draw shadow

    var NewCustomers = function (element, color) {
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

      var chartBar1 = new ApexCharts(
        document.querySelector("#" + element),
        options
      );
      chartBar1.render();
    };
    var NewExperience = function () {
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
          colors: ["#FF5E5E"],
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
          colors: "#FF5E5E",
          type: "gradient",
          gradient: {
            colorStops: [
              {
                offset: 0,
                color: "#FF5E5E",
                opacity: 0.5,
              },
              {
                offset: 0.6,
                color: "#FF5E5E",
                opacity: 0.5,
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

      var chartBar1 = new ApexCharts(
        document.querySelector("#NewExperience"),
        options
      );
      chartBar1.render();
    };
    var AllProject = function () {
      var options = {
        series: [12, 30, 20],
        chart: {
          type: "donut",
          width: 150,
        },
        plotOptions: {
          pie: {
            donut: {
              size: "80%",
              labels: {
                show: true,
                name: {
                  show: true,
                  offsetY: 12,
                },
                value: {
                  show: true,
                  fontSize: "22px",
                  fontFamily: "Arial",
                  fontWeight: "500",
                  offsetY: -17,
                },
                total: {
                  show: true,
                  fontSize: "11px",
                  fontWeight: "500",
                  fontFamily: "Arial",
                  label: "Compete",

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
        },
        colors: ["#3AC977", "var(--primary)", "var(--secondary)"],
        labels: ["Compete", "Pending", "Not Start"],
        dataLabels: {
          enabled: false,
        },
      };
      var chartBar1 = new ApexCharts(
        document.querySelector("#AllProject"),
        options
      );
      chartBar1.render();
    };

    var overiewChart = function (data) {
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

      var chart = new ApexCharts(
        document.querySelector("#overiewChart"),
        options
      );
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
              var monthData = seriesTypeTotals.find(
                (item) => item.month == i + 1
              );
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
              var monthData = seriesTypeTotals.find(
                (item) => item.month == i + 1
              );
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
    };
    var earningChart = function () {
      var chartWidth = $("#earningChart").width();
      /* console.log(chartWidth); */

      var options = {
        series: [
          {
            name: "Net Profit",
            data: [700, 650, 680, 600, 700, 610, 710, 620],
            /* radius: 30,	 */
          },
        ],
        chart: {
          type: "area",
          height: 350,
          width: chartWidth + 55,
          toolbar: {
            show: false,
          },
          offsetX: -45,
          zoom: {
            enabled: false,
          },
          /* sparkline: {
				enabled: true
			} */
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
          colors: ["var(--primary)"],
        },
        grid: {
          show: true,
          borderColor: "#eee",
          xaxis: {
            lines: {
              show: true,
            },
          },
          yaxis: {
            lines: {
              show: false,
            },
          },
        },
        yaxis: {
          show: true,
          tickAmount: 4,
          min: 0,
          max: 800,
          labels: {
            offsetX: 50,
          },
        },
        xaxis: {
          categories: ["", "May ", "June", "July", "Aug", "Sep ", "Oct"],
          overwriteCategories: undefined,
          axisBorder: {
            show: false,
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: true,
            offsetX: 5,
            style: {
              fontSize: "12px",
            },
          },
        },
        fill: {
          opacity: 0.5,
          colors: "var(--primary)",
          type: "gradient",
          gradient: {
            colorStops: [
              {
                offset: 0.6,
                color: "var(--primary)",
                opacity: 0.2,
              },
              {
                offset: 0.6,
                color: "var(--primary)",
                opacity: 0.15,
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
          enabled: true,
          style: {
            fontSize: "12px",
          },
          y: {
            formatter: function (val) {
              return "$" + val + "";
            },
          },
        },
      };

      var chartBar1 = new ApexCharts(
        document.querySelector("#earningChart"),
        options
      );
      chartBar1.render();

      $(".earning-chart .nav-link").on("click", function () {
        var seriesType = $(this).attr("data-series");
        var columnData = [];
        switch (seriesType) {
          case "day":
            columnData = [700, 650, 680, 650, 700, 610, 710, 620];
            break;
          case "week":
            columnData = [700, 700, 680, 600, 700, 620, 710, 620];
            break;
          case "month":
            columnData = [700, 650, 690, 640, 700, 670, 710, 620];
            break;
          case "year":
            columnData = [700, 650, 590, 650, 700, 610, 710, 630];
            break;
          default:
            columnData = [700, 650, 680, 650, 700, 610, 710, 620];
        }
        chartBar1.updateSeries([
          {
            name: "Net Profit",
            data: columnData,
          },
        ]);
      });
    };

    var projectChart = function () {
      var options = {
        chart: {
          height: 127,
          width: 100,
          type: "radialBar",
        },

        series: [chartData.percentage],
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
      var chart = new ApexCharts(
        document.querySelector("#projectChart"),
        options
      );
      chart.render();
    };

    // Function to generate random colors
    function generateRandomColors(numColors) {
      var colors = [];
      for (var i = 0; i < numColors; i++) {
        let color;
        do {
          // Generate a random color in hexadecimal format
          color =
            "#" +
            Math.floor(Math.random() * 16777215)
              .toString(16)
              .padStart(6, "0");
        } while (parseInt(color.slice(1), 16) > 0xeeeeee); // Avoid colors too close to white

        colors.push(color);
      }
      return colors;
    }

    var liquidityChart = function (data) {
      // Extracting series and labels from JSON data
      var series = data.map((item) =>
        Number(parseFloat(item.total_balance).toFixed(2))
      ); // Extracting total_balance for series
      var labels = data.map((item) => item.particular_name); // Extracting particular_name for labels

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
                    const total = w.globals.seriesTotals.reduce(
                      (a, b) => a + b,
                      0
                    );
                    if (total >= 1_000_000_000_000) {
                      return (total / 1_000_000_000_000).toFixed(2) + "T";
                    } else if (total >= 1_000_000_000) {
                      return (total / 1_000_000_000).toFixed(2) + "B";
                    } else if (total >= 1_000_000) {
                      return (total / 1_000_000).toFixed(2) + "M";
                    } else {
                      return total.toFixed(2);
                    }
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
                width: 300,
              },
            },
          },
        ],
      };

      if ($("#liquidityChart").length > 0) {
        var liquidityChartData = new ApexCharts(
          document.querySelector("#liquidityChart"),
          options
        );
        liquidityChartData.render();
      }
    };

    var membershipChart = function (data) {
      // Extracting series and labels from JSON data
      var series = data.map((item) =>
        Number(parseFloat(item.total_balance).toFixed(2))
      ); // Extracting total_balance for series
      var labels = data.map((item) => item.particular_name); // Extracting particular_name for labels

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
                    const total = w.globals.seriesTotals.reduce(
                      (a, b) => a + b,
                      0
                    );
                    if (total >= 1_000_000_000_000) {
                      return (total / 1_000_000_000_000).toFixed(2) + "T";
                    } else if (total >= 1_000_000_000) {
                      return (total / 1_000_000_000).toFixed(2) + "B";
                    } else if (total >= 1_000_000) {
                      return (total / 1_000_000).toFixed(2) + "M";
                    } else {
                      return total.toFixed(2);
                    }
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
                width: 300,
              },
            },
          },
        ],
      };

      if ($("#membershipChart").length > 0) {
        var membershipChartData = new ApexCharts(
          document.querySelector("#membershipChart"),
          options
        );
        membershipChartData.render();
      }
    };

    var applicationStatusChart = function (jsonData) {
      var options = {
        series: [
          jsonData.pending,
          jsonData.processing,
          jsonData.declined,
          jsonData.disbursed,
          jsonData.cancelled,
        ],
        chart: {
          type: "donut",
          width: 250,
        },
        plotOptions: {
          pie: {
            donut: {
              size: "90%",
              labels: {
                show: true,
                name: {
                  show: true,
                  offsetY: 12,
                },
                value: {
                  show: true,
                  fontSize: "24px",
                  fontFamily: "Arial",
                  fontWeight: "500",
                  offsetY: -17,
                },
                total: {
                  show: true,
                  fontSize: "11px",
                  fontWeight: "500",
                  fontFamily: "Arial",
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
        },
        colors: [
          "var(--secondary)",
          "var(--primary)",
          "#FF9F00",
          "#3AC977",
          "#FF5E5E",
        ],
        labels: ["Pending", "Processing", "Declined", "Approved", "Cancelled"],
        dataLabels: {
          enabled: false,
        },
      };

      if ($("#applicationStatusChart").length > 0) {
        var chartBar1 = new ApexCharts(
          document.querySelector("#applicationStatusChart"),
          options
        );
        chartBar1.render();
      }
    };

    var disbursementStatusChart = function (jsonData) {
      var options = {
        series: [
          jsonData.running,
          jsonData.arrears,
          jsonData.expired,
          jsonData.cleared,
          jsonData.defaulted,
        ],
        chart: {
          type: "donut",
          width: 250,
        },
        plotOptions: {
          pie: {
            donut: {
              size: "90%",
              labels: {
                show: true,
                name: {
                  show: true,
                  offsetY: 12,
                },
                value: {
                  show: true,
                  fontSize: "24px",
                  fontFamily: "Arial",
                  fontWeight: "500",
                  offsetY: -17,
                },
                total: {
                  show: true,
                  fontSize: "11px",
                  fontWeight: "500",
                  fontFamily: "Arial",
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
        },
        colors: [
          "var(--primary)",
          "var(--secondary)",
          "#FF9F00",
          "#3AC977",
          "#FF5E5E",
        ],
        labels: ["Pending", "Processing", "Declined", "Approved", "Cancelled"],
        dataLabels: {
          enabled: false,
        },
      };

      if ($("#disbursementStatusChart").length > 0) {
        var chartBar2 = new ApexCharts(
          document.querySelector("#disbursementStatusChart"),
          options
        );
        chartBar2.render();
      }
    };

    var simpleChart = function (element) {
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
          colors: ["var(--primary)"],
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

      var chartBar1 = new ApexCharts(
        document.querySelector("#" + element),
        options
      );
      chartBar1.render();
    };

    var handleWorldMap = function (trigger = "load") {
      var vmapSelector = $("#world-map");
      if (trigger == "resize") {
        vmapSelector.empty();
        vmapSelector.removeAttr("style");
      }

      vmapSelector
        .delay(500)
        .unbind()
        .vectorMap({
          map: "world_en",
          backgroundColor: "transparent",
          borderColor: "rgb(239, 242, 244)",
          borderOpacity: 0.25,
          borderWidth: 1,
          color: "rgb(239, 242, 244)",
          enableZoom: true,
          hoverColor: "rgba(239, 242, 244 0.9)",
          hoverOpacity: null,
          normalizeFunction: "linear",
          scaleColors: ["#b6d6ff", "#005ace"],
          selectedColor: "rgba(239, 242, 244 0.9)",
          selectedRegions: null,
          showTooltip: true,
          onRegionClick: function (element, code, region) {
            var message =
              'You clicked "' +
              region +
              '" which has the code: ' +
              code.toUpperCase();

            alert(message);
          },
        });
    };

    /* Function ============ */
    return {
      init: function () {},

      load: function () {
        NewCustomers("principalDisbursed", "var(--primary)");
        NewCustomers("principalCollected", "#3AC977");
        NewCustomers("actualInterest", "#FF5E5E");
        NewCustomers("interestCollected", "#3AC977");
        NewCustomers("savingsChart", "#3AC977");
        NewCustomers("depositsChart", "#3AC977");
        NewCustomers("withdrawChart", "var(--primary)");
        NewCustomers("withdrawChargesChart", "#FF5E5E");
        NewCustomers("applicationsChart", "var(--primary)");
        NewCustomers("disbursementsChart", "var(--primary)");
        NewCustomers("clientsChart", "var(--primary)");
        NewCustomers("loanChart", "#FF5E5E");

        applicationStatusChart(chartData.summary.applications);
        disbursementStatusChart(chartData.summary.disbursements);

        NewExperience();
        AllProject();
        overiewChart(chartData.summary.savings);
        earningChart();
        projectChart();
        handleWorldMap();

        liquidityChart(chartData.liquidity);
        membershipChart(chartData.membership);
      },

      resize: function () {
        handleWorldMap();
        earningChart();
      },
    };
  })();

  jQuery(window).on("load", function () {
    setTimeout(function () {
      dzChartlist.load();
    }, 1000);
  });
})(jQuery);
