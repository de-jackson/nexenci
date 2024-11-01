(function ($) {
    /* "use strict" */

    var dzChartlist = (function () {
        var screenWidth = $(window).width();
        let draw = Chart.controllers.line.__super__.draw; //draw shadow

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


        var widgetChart = function (element, color) {
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
                    categories: ["Jan", "feb", "Mar", "Apr", "May", "June", "July", "August", "Sept", "Oct"],
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

            var chartBar1 = new ApexCharts(document.querySelector("#" + element), options);
            chartBar1.render();
        };

        var accountsChart = function (data) {
            // Extracting series and labels from JSON data
            var series = data.map((item) => parseFloat(item.nexen_clients_counter)); // Extracting clients for series
            var labels = data.map((item) => item.name); // Extracting particular_name for labels

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
                            },
                        },
                    },
                ],
            };

            if ($("#accountsChart").length > 0) {
                var accountsChartData = new ApexCharts(document.querySelector("#accountsChart"), options);
                accountsChartData.render();
            }
        };

        /* Function ============ */
        return {
            init: function () {},

            load: function () {

                for (var i = 0; i < chartData.summary.accounts.length; i++) {
                    widgetChart("chart-"+i, "var(--primary)");
                }

                for (var i = 0; i < chartData.summary.subAccounts.length; i++) {
                    widgetChart("client-"+i, "var(--primary)");
                }

                widgetChart("bulkSMSBalance", "#3AC977");

                accountsChart(chartData.summary.accounts);
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
