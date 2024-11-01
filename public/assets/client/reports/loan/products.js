// dataTables buttons config
var buttonsConfig = [];
if (userPermissions.includes('export' + title) || userPermissions === '"all"') {
    buttonsConfig.push(
        {
            extend: 'excel',
            className: 'btn btn-sm btn-success',
            titleAttr: 'Export ' + title + ' To Excel',
            text: '<i class="fas fa-file-excel"></i>',
            filename: 'Loan Products ' + title + ' Information',
            extension: '.xlsx',
        },
        {
            extend: 'pdf',
            className: 'btn btn-sm btn-danger',
            titleAttr: 'Export Loan Products ' + title + ' To PDF',
            text: '<i class="fas fa-file-pdf"></i>',
            filename: 'Loan Products ' + title + ' Information',
            extension: '.pdf',
        },
        {
            extend: 'csv',
            className: 'btn btn-sm btn-success',
            titleAttr: 'Export Loan Products ' + title + ' To CSV',
            text: '<i class="fas fa-file-csv"></i>',
            filename: 'Loan Products ' + title + ' Information',
            extension: '.csv',
        },
        {
            extend: 'copy',
            className: 'btn btn-sm btn-warning',
            titleAttr: 'Copy Loan Products ' + title + ' Information',
            text: '<i class="fas fa-copy"></i>',
        },
        {
            extend: 'print',
            title: "<h3 class='text-center text-bold'>" + businessName + "</h3><h4 class='text-center text-bold'>Loan Products " + title + " Information</h4><h5 class='text-center'>Printed On " + new Date().getHours() + " : " + new Date().getMinutes() + " " + new Date().toDateString() + "</h5>",
            customize: function (win) {
                $(win.document.body)
                    .css('font-size', '10pt')
                    .css('font-family', 'calibri')
                    .prepend(
                        '<img src="' +  logo + '" style="position:absolute; top:0; left:0;width:100px;height:100px;" />'
                    );
                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
                // Replace the page title with the actual page title
                $(win.document.head).find('title').text(title);
            },

            className: 'btn btn-sm btn-primary',
            titleAttr: 'Print Loan Products ' + title + ' Information',
            text: '<i class="fa fa-print"></i>',
            filename: 'Loan Products ' + title + ' Information'
        }
    );
}

$(function () {
    // get loan product years
    yearsInLoanProductsTable()
    // load account status
    generateStatus();
    // load loan interest types
    generateCustomeSettings('interesttypes');
    // load loan repayment durations
    generateCustomeSettings('loanrepaymentdurations');

    // load the staff officer
    generateStaff();

    // JS active loan products data objects
    var active = {
        entry_status: "",
        year: "",
        start_date: "",
        end_date: "",
        loanrepaymentdurations: "",
        others: "",
        status: "",
        interesttypes: ""
    };

    // JS inactive loan products data object
    var inactive = {
        entry_status: "",
        year: "",
        start_date: "",
        end_date: "",
        loanrepaymentdurations: "",
        others: "",
        status: "",
        interesttypes: ""
    };

    // JS total number of loan products data object
    var totalAccounts = {
        entry_status: "",
        year: "",
        start_date: "",
        end_date: "",
        loanrepaymentdurations: "",
        others: "",
        status: "",
        interesttypes: ""
    };

    // auto load the loan products table report
    filterLoanProduct();

    // auto load the loan products chart
    loanProductsChartsReport(active, inactive, totalAccounts);

});

function loanProductsChartsReport(activeAccounts, inactiveAccounts, totalAccounts) {
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

    // Fetch all the keys(variables) from the object
    var activeAccountKeys = Object.keys(activeAccounts);

    //console.log(activeAccountKeys) // [entry_status, year, start_date etc]
    // Iterate over each key to update entry status to debit
    activeAccountKeys.forEach((activeAccountElement) => {
        // check whether the element is entry_status to update its value to debit
        if (activeAccountElement == "entry_status") {
            activeAccounts[activeAccountElement] = "active";
        }
    });

    var activeAccess = activeAccounts;

    // Fetch all the keys(variables) from the object
    var inactiveAccountKeys = Object.keys(inactiveAccounts);

    //console.log(inactiveAccountKeys) // [entry_status, year, start_date etc]

    // Iterate over each key to update entry status to debit
    inactiveAccountKeys.forEach((inactiveAccountElement) => {
        // check whether the element is entry_status to update its value to debit
        if (inactiveAccountElement == "entry_status") {
            inactiveAccounts[inactiveAccountElement] = "inactive";
        }
    });

    var inactiveAccess = inactiveAccounts;

    // Fetch all the keys(variables) from the object
    var totalAccountsElementKeys = Object.keys(totalAccounts);

    //console.log(totalAccountsElementKeys) // [entry_status, year, start_date etc]

    // Iterate over each key to update entry status to debit
    totalAccountsElementKeys.forEach((totalAccountsElement) => {
        // check whether the element is entry_status to update its value to debit
        if (totalAccountsElement == "entry_status") {
            totalAccounts[totalAccountsElement] = "products";
        }
    });

    var totalLoanProducts = totalAccounts;

    /* area chart */
    var areaOptions = {
        series: [{
            name: 'Active Loan Products',
            data: monthlyTotalLoanProductNumber(activeAccess)
        }, {
            name: 'Inactive Loan Products',
            data: monthlyTotalLoanProductNumber(inactiveAccess),
        }, {
            name: 'Total Loan Products',
            data: monthlyTotalLoanProductNumber(totalLoanProducts),
        }],
        chart: {
            height: 320,
            type: 'area'
        },
        colors: ["#23b7e5", "#f5b849", "#845adf"],
        dataLabels: {
            enabled: false
        },
        title: {
            text: 'Monthly Loan Products Status Report - Area Chart',
            align: 'left',
            style: {
                fontSize: '13px',
                fontWeight: 'bold',
                color: '#8c9097'
            },
        },
        stroke: {
            curve: 'smooth'
        },
        grid: {
            borderColor: '#f2f5f7',
        },
        xaxis: {
            // type: 'datetime',
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"],
            title: {
                text: 'Months',
                style: {
                    color: "#8c9097",
                }
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
            title: {
                text: 'Number of Loan Products',
                style: {
                    color: "#8c9097",
                }
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
        tooltip: {
            y: {
                formatter: function (val) {
                    return val
                }
            },
        },
        legend: {
            position: 'top',
            style: {
                color: "#8c9097",
            }
        },
    };
    $('#area-spline').empty();
    var chart = new ApexCharts(document.querySelector("#area-spline"), areaOptions);
    chart.render();

    /* line chart */
    var lineOptions = {
        series: [{
            name: 'Active Loan Products',
            data: monthlyTotalLoanProductNumber(activeAccess)
        }, {
            name: 'Inactive Loan Products',
            data: monthlyTotalLoanProductNumber(inactiveAccess),
        }, {
            name: 'Total Loan Products',
            data: monthlyTotalLoanProductNumber(totalLoanProducts),
        }],
        chart: {
            height: 320,
            type: 'line',
            zoom: {
                enabled: false
            },
            animations: {
                enabled: false
            }
        },
        grid: {
            borderColor: '#f2f5f7',
        },
        stroke: {
            width: [3, 3, 2],
            curve: 'straight'
        },
        colors: ["#23b7e5", "#f5b849", "#845adf"],
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"],
        title: {
            text: 'Monthly Loan Products Status Report - Line Graph',
            align: 'left',
            style: {
                fontSize: '13px',
                fontWeight: 'bold',
                color: '#8c9097'
            },
        },
        xaxis: {
            title: {
                text: 'Months',
                style: {
                    color: "#8c9097",
                }
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
            title: {
                text: 'Number of Loan Products',
                style: {
                    color: "#8c9097",
                }
            },
            labels: {
                show: true,
                style: {
                    colors: "#8c9097",
                    fontSize: '11px',
                    fontWeight: 600,
                    cssClass: 'apexcharts-yaxis-label',
                },
            }
        },
        legend: {
            position: 'top',
            style: {
                color: "#8c9097",
            }
        },
    };
    $('#line-chart').empty();
    var chart = new ApexCharts(document.querySelector("#line-chart"), lineOptions);
    chart.render();

    /* column chart  */
    var columnOptions = {
        series: [{
            name: 'Active Loan Products',
            data: monthlyTotalLoanProductNumber(activeAccess)
        }, {
            name: 'Inactive Loan Products',
            data: monthlyTotalLoanProductNumber(inactiveAccess),
        }, {
            name: 'Total Loan Products',
            data: monthlyTotalLoanProductNumber(totalLoanProducts),
        }],
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
                return val;
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#8c9097"]
            }
        },
        colors: ["#23b7e5", "#f5b849", "#845adf"],
        title: {
            text: 'Monthly Loan Products Status Report - Column Chart',
            align: 'left',
            style: {
                fontSize: '13px',
                fontWeight: 'bold',
                color: '#8c9097'
            },
        },
        xaxis: {
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
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
            title: {
                text: 'Months',
                style: {
                    color: "#8c9097",
                }
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
            title: {
                text: 'Number of Loan Products',
                style: {
                    color: "#8c9097",
                }
            },
            labels: {
                // show: false,
                formatter: function (val) {
                    return val;
                }
            }
        },
        legend: {
            position: 'top',
            style: {
                color: "#8c9097",
            }
        },
    };
    $('#column-chart').empty();
    var chart = new ApexCharts(document.querySelector("#column-chart"), columnOptions);
    chart.render();

    /* radar chart  */
    var radarOptions = {
        series: [{
            name: 'Active Loan Products',
            data: monthlyTotalLoanProductNumber(activeAccess)
        }, {
            name: 'Inactive Loan Products',
            data: monthlyTotalLoanProductNumber(inactiveAccess),
        }, {
            name: 'Total Loan Products',
            data: monthlyTotalLoanProductNumber(totalLoanProducts),
        }],
        chart: {
            height: 320,
            type: 'radar',
            dropShadow: {
                enabled: true,
                blur: 1,
                left: 1,
                top: 1
            }
        },
        title: {
            text: 'Monthly Loan Products Status Report - Radar Chart',
            align: 'left',
            style: {
                fontSize: '13px',
                fontWeight: 'bold',
                color: '#8c9097'
            },
        },
        colors: ["#23b7e5", "#f5b849", "#845adf"],
        stroke: {
            width: 2
        },
        fill: {
            opacity: 0.1
        },
        markers: {
            size: 0
        },
        xaxis: {
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"]
        },
        yaxis: {
            labels: {
                show: true,
                formatter: function (val) {
                    return val;
                    // return val.toFixed(2);
                }
            }
        },
        markers: {
            size: 3,
            colors: ['#fff'],
            strokeColor: '#32a83c',
            strokeWidth: 2,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val
                }
            },
        },
        legend: {
            position: 'top',
            style: {
                color: "#8c9097",
            }
        },
    };
    $('#radar-chart').empty();
    var chart = new ApexCharts(document.querySelector("#radar-chart"), radarOptions);
    chart.render();
}

function yearsInLoanProductsTable() {
    $.ajax({
        url: "/client/reports/types/loanproductyears",
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

function filterLoanProduct() {
    $('#searchButton').html('<i class="fa fa-spinner fa-spin"></i> searching...');
    $('#searchButton').attr('disabled', true);

    var year = $("select#year").val();
    var start_date = $("input#start_date").val();
    var end_date = $("input#end_date").val();
    var loanrepaymentdurations = $("select#loanrepaymentdurations").val();
    var others = $("select#product_id").val();
    var status = $("select#status").val();
    var interesttypes = $("select#interesttypes").val();

    // JS  activate number of loan products data objects
    var activeObject = {
        entry_status: "",
        year: year,
        start_date: start_date,
        end_date: end_date,
        loanrepaymentdurations: loanrepaymentdurations,
        interesttypes: interesttypes,
        others: others,
        status: status
    };

    // JS  inactive number of loan products data objects
    var inactivateObject = {
        entry_status: "",
        year: year,
        start_date: start_date,
        end_date: end_date,
        loanrepaymentdurations: loanrepaymentdurations,
        others: others,
        status: status,
        interesttypes: interesttypes
    };

    // JS total number of products data objects
    var loanProductsTotal = {
        entry_status: "",
        year: year,
        start_date: start_date,
        end_date: end_date,
        loanrepaymentdurations: loanrepaymentdurations,
        others: others,
        status: status,
        interesttypes: interesttypes
    };


    var url = baseURL + "/client/reports/view/report/products";
    // ajax adding data to database
    $.ajax({
        url: url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        beforeSend: function () {
            $("#loan_products_counter").html('<div class="text-center">' + '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>');
            // load the spinner where staff table is
            $("#loan_products_table").html('<div class="text-center">' + '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>');
        },
        success: function (results) {

            // call the loan products chart report
            loanProductsChartsReport(activeObject, inactivateObject, loanProductsTotal);
            // number of loan products counter
            var productsCounterDataTable =
                '<div class="table-responsive"><table id="productCounter" class="table table-sm table-striped table-hover"' +
                'cellspacing="0" width="100%"><tbody></tbody>' +
                '<tfoot>' +
                '<tr>' +
                '<th>Total Loan Products</th>' +
                '<th>' + results.yearlyActiveProductsTotal + '</th>' +
                '<th>' + results.yearlyInactiveProductsTotal + '</th>' +
                '<th>' + results.yearlyProductsTotal + '</th>' +
                '</tr>' +
                '</tfoot></table></div>';

            $("#loan_products_counter").html(productsCounterDataTable);

            $("#productCounter").DataTable({
                data: results.loanProductsCounter,
                bPaginate: true,
                bLengthChange: true,
                language: {
                    paginate: {
                      next: '<i class="fa-solid fa-angle-right"></i>',
                      previous: '<i class="fa-solid fa-angle-left"></i>',
                    },
                    processing: "<img src='/assets/dist/img/6.gif' height=50px width=50px>"
                },
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100],
                ],
                bFilter: true,
                responsive: true,
                bInfo: true,
                bAutoWidth: true,
                columns: [
                    { title: "Year - Month" },
                    { title: "Number of Active Loan Products" },
                    { title: "Number of Inactive Loan Products" },
                    { title: "Total Loan Products" }
                ],
                // dom: "lBfrtip",
                // buttons: ["copy", "csv", "excel", "pdf", "print"],
                dom:
                    "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",
                buttons: buttonsConfig,
            });

            // loan products table information
            var loanProductsDataTable =
                '<div class="table-responsive"><table id="loanProducts" class="table table-sm table-striped table-hover"' +
                'cellspacing="0" width="100%"><tbody></tbody>' +
                '</table></div>';

            $("#loan_products_table").html(loanProductsDataTable);

            $("#loanProducts").DataTable({
                data: results.loanProducts,
                bPaginate: true,
                bLengthChange: true,
                language: {
                    paginate: {
                      next: '<i class="fa-solid fa-angle-right"></i>',
                      previous: '<i class="fa-solid fa-angle-left"></i>',
                    },
                    processing: "<img src='/assets/dist/img/6.gif' height=50px width=50px>"
                },
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, 250, 500, -1],
                    [10, 25, 50, 100, 250, 500, "All"],
                ],
                bFilter: true,
                bInfo: true,
                responsive: true,
                bAutoWidth: true,
                columns: [
                    { title: "S.No", "width": "4%" },
                    { title: "Date Created" },
                    { title: "Name" },
                    { title: "Frequency" },
                    { title: "Period" },
                    { title: "Rate (%)" },
                    { title: "Method" },
                    { title: "Action" },
                ],
                // dom: "lBfrtip",
                // buttons: ["copy", "csv", "excel", "pdf", "print"],
                dom:
                    "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",
                buttons: buttonsConfig,

                // set column definition initialisation properties.
                "columnDefs": [
                    {
                        "targets": [0], //first column
                        "orderable": false    //set not orderable
                    },
                    {
                        "targets": [7], //last column
                        "orderable": false
                    }

                ],
            });



            $('#searchButton').html('<i class="fa fa-search fa-fw"></i>');
            $('#searchButton').attr('disabled', false);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#searchButton').html('<i class="fa fa-search fa-fw"></i>');
            $('#searchButton').attr('disabled', false);

        }
    });
}

function monthlyTotalLoanProductNumber(data) {
    const total = [];
    $.ajax({
        url: "/client/reports/view/report/products",
        async: false,
        type: "POST",
        dataType: "JSON",
        data: {
            entry_status: data.entry_status,
            year: data.year,
            start_date: data.start_date,
            end_date: data.end_date,
            loanrepaymentdurations: data.loanrepaymentdurations,
            others: data.others,
            status: data.status,
            interesttypes: data.interesttypes
        },
        success: function (data) {
            if (data.monthlyProductsTotal.length > 0) {
                data.monthlyProductsTotal.forEach(function (value, index) {
                    total.push(value);
                });
                // for (let index in data.monthlyProductsTotal) {
                //   const element = data.monthlyProductsTotal[index];
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

// function viewLoadProduct(id) {
//     $.ajax({
//         url: "/admin/loans/product/" + id,
//         type: "GET",
//         dataType: "JSON",
//         success: function (data) {
//             $('[name="id"]').val(data.id);
//             $('[name="product_name"]').val(data.product_name);
//             $('[name="interest_rate"]').val(data.interest_rate);
//             $('[name="interest_type"]').val(data.interest_type);
//             $('[name="repayment_period"]').val(data.repayment_period);
//             $('[name="repayment_freq"]').val(data.repayment_freq);
//             $('[name="product_desc"]').val(data.product_desc);
//             $('textarea#seeSummernote').summernote('code', data.product_features);
//             $('[name="status"]').val(data.status);
//             $('[name="created_at"]').val(data.created_at);
//             $('[name="updated_at"]').val(data.updated_at);
//             $('#view_modal').modal('show');
//             $('.modal-title').text('View ' + data.product_name);
//         },
//         error: function (jqXHR, textStatus, errorThrown) {
//             Swal.fire(textStatus, errorThrown, 'error');
//         }
//     });
// }

// function printLoadProduct() {
//     var product_id = $('[name="id"]').val();
//     window.location.assign("/admin/loans/productform/" + product_id);
// }
