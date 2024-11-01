// dataTables buttons config
var buttonsConfig = [];
if (userPermissions.includes('export' + title) || userPermissions === '"all"') {
    buttonsConfig.push(
        {
            extend: 'excel',
            className: 'btn btn-sm btn-success',
            titleAttr: 'Export ' + title + ' To Excel',
            text: '<i class="fas fa-file-excel"></i>',
            filename: 'Disbursements ' + title + ' Information',
            extension: '.xlsx',
        },
        {
            extend: 'pdf',
            className: 'btn btn-sm btn-danger',
            titleAttr: 'Export Disbursements ' + title + ' To PDF',
            text: '<i class="fas fa-file-pdf"></i>',
            filename: 'Disbursements ' + title + ' Information',
            extension: '.pdf',
        },
        {
            extend: 'csv',
            className: 'btn btn-sm btn-success',
            titleAttr: 'Export Disbursements ' + title + ' To CSV',
            text: '<i class="fas fa-file-csv"></i>',
            filename: 'Disbursements ' + title + ' Information',
            extension: '.csv',
        },
        {
            extend: 'copy',
            className: 'btn btn-sm btn-warning',
            titleAttr: 'Copy Disbursements ' + title + ' Information',
            text: '<i class="fas fa-copy"></i>',
        },
        {
            extend: 'print',
            title: "<h3 class='text-center text-bold'>" + businessName + "</h3><h4 class='text-center text-bold'>Disbursements " + title + " Information</h4><h5 class='text-center'>Printed On " + new Date().getHours() + " : " + new Date().getMinutes() + " " + new Date().toDateString() + "</h5>",
            customize: function (win) {
                $(win.document.body)
                    .css('font-size', '10pt')
                    .css('font-family', 'calibri')
                    .prepend(
                        '<img src="' + logo + '" style="position:absolute; top:0; left:0;width:100px;height:100px;" />'
                    );
                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
                // Replace the page title with the actual page title
                $(win.document.head).find('title').text(title);
            },

            className: 'btn btn-sm btn-primary',
            titleAttr: 'Print Disbursements ' + title + ' Information',
            text: '<i class="fa fa-print"></i>',
            filename: 'Disbursements ' + title + ' Information'
        }
    );
}

$(function () {
    generateStaff();
    // get the running branches
    selectBranch();
    // get client years
    disbursementLoanYears()
    // gender
    generateGender();
    // load account status
    generateStatus();
    loadDisbursementStatus()
    // load the staff officer
    generateStaff();
    // JS data objects
    var cleared = {
        year: "",
        start_date: "",
        end_date: "",
        gender: "",
        account_no: "",
        others: "",
        client_account_status: "",
        client_name: "",
        disbursement_code: "",
        branch_id: "",
        staff_id: "",
        start_expiry_date: "",
        end_expiry_date: "",
        disbursement_status: "",
        start_amount_applied: "",
        end_amount_applied: "",
    };

    // JS data object
    var expired = {
        year: "",
        start_date: "",
        end_date: "",
        gender: "",
        account_no: "",
        others: "",
        client_account_status: "",
        client_name: "",
        disbursement_code: "",
        branch_id: "",
        staff_id: "",
        start_expiry_date: "",
        end_expiry_date: "",
        disbursement_status: "",
        start_amount_applied: "",
        end_amount_applied: "",
    };

    // JS total data object
    var total = {
        year: "",
        start_date: "",
        end_date: "",
        gender: "",
        account_no: "",
        others: "",
        client_account_status: "",
        client_name: "",
        disbursement_code: "",
        branch_id: "",
        staff_id: "",
        start_expiry_date: "",
        end_expiry_date: "",
        disbursement_status: "",
        start_amount_applied: "",
        end_amount_applied: "",
    };

    // load the loan disbursement report
    searchDisbursementLoans();

    // load the loan disbursement chart
    disbursementsChartsReport(cleared, expired, total);

});

function disbursementsChartsReport(cleared, expired, total) {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */
    var ticksStyle = {
        // fontColor: '#495057',
        fontStyle: "bold",
    };

    // Fetch all the keys(variables) from the object
    var clearedKeys = Object.keys(cleared);

    //console.log(clearedKeys) // [disbursement_status, year, start_date etc]
    // Iterate over each key to update Disbursement status to cleared
    clearedKeys.forEach((clearedElement) => {
        // check whether the element is disbursement_status to update its value to cleared
        if (clearedElement == "disbursement_status") {
            cleared[clearedElement] = "cleared";
        }
    });

    var clearedDisbursements = cleared;

    // Fetch all the keys(variables) from the object
    var expiredKeys = Object.keys(expired);

    //console.log(expiredKeys) // [disbursement_status, year, start_date etc]

    // Iterate over each key to update Disbursement status to expired
    expiredKeys.forEach((expiredElement) => {
        // check whether the element is disbursement_status to update its value to expired
        if (expiredElement == "disbursement_status") {
            expired[expiredElement] = "expired";
        }
    });

    var expiredDisbursements = expired;

    // Fetch all the keys(variables) from the object
    var totalKeys = Object.keys(total);

    //console.log(totalKeys) // [disbursement_status, year, start_date etc]

    // Iterate over each key to update Disbursement status to total
    totalKeys.forEach((totalElement) => {
        // check whether the element is disbursement_status to update its value to total
        if (totalElement == "disbursement_status") {
            total[totalElement] = "total";
        }
    });

    var totalDisbursements = total;

    /* area chart */
    var areaOptions = {
        series: [{
            name: 'Cleared Loans',
            data: monthlyDisbursementsNumber(clearedDisbursements)
        }, {
            name: 'Expired Loans',
            data: monthlyDisbursementsNumber(expiredDisbursements),
        }, {
            name: 'Disbursements',
            data: monthlyDisbursementsNumber(totalDisbursements),
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
            text: 'Monthly Disbursements Report - Area Chart',
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
                text: 'Number of Disbursements',
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
            name: 'Cleared Loans',
            data: monthlyDisbursementsNumber(clearedDisbursements)
        }, {
            name: 'Expired Loans',
            data: monthlyDisbursementsNumber(expiredDisbursements),
        }, {
            name: 'Disbursements',
            data: monthlyDisbursementsNumber(totalDisbursements),
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
            text: 'Monthly Disbursements Report - Line Graph',
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
                text: 'Number of Disbursements',
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
            name: 'Cleared Loans',
            data: monthlyDisbursementsNumber(clearedDisbursements)
        }, {
            name: 'Expired Loans',
            data: monthlyDisbursementsNumber(expiredDisbursements),
        }, {
            name: 'Disbursement',
            data: monthlyDisbursementsNumber(totalDisbursements),
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
            text: 'Monthly Disbursements Report - Column Chart',
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
                text: 'Number of Disbursements',
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
            name: 'Cleared Loans',
            data: monthlyDisbursementsNumber(clearedDisbursements)
        }, {
            name: 'Expired Loans',
            data: monthlyDisbursementsNumber(expiredDisbursements),
        }, {
            name: 'Disbursement',
            data: monthlyDisbursementsNumber(totalDisbursements),
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
            text: 'Monthly Disbursements Report - Radar Chart',
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

function searchDisbursementLoans() {
    $('#searchButton').html('<i class="fa fa-spinner fa-spin"></i> searching...');
    $('#searchButton').attr('disabled', true);

    var year = $("select#year").val();
    var start_date = $("input#start_date").val();
    var end_date = $("input#end_date").val();
    var gender = $("select#gender").val();
    var account_no = $("input#account_no").val();
    var others = $("input#others").val();
    var client_account_status = $("select#status").val();
    var client_name = $("input#client_name").val();
    var disbursement_code = $("input#disbursement_code").val();
    var branch_id = $("select#branch_id").val();
    var staff_id = $("select#staff_id").val();
    var start_expiry_date = $("input#start_expiry_date").val();
    var end_expiry_date = $("input#end_expiry_date").val();
    var disbursement_status = $("select#disbursement_status").val();
    var start_amount_applied = $("input#start_amount_applied").val();
    var end_amount_applied = $("input#end_amount_applied").val();

    // JS  cleared loan Disbursements data objects
    var clearedObjects = {
        year: year,
        start_date: start_date,
        end_date: end_date,
        gender: gender,
        account_no: account_no,
        others: others,
        client_account_status: client_account_status,
        client_name: client_name,
        disbursement_code: disbursement_code,
        branch_id: branch_id,
        staff_id: staff_id,
        start_expiry_date: start_expiry_date,
        end_expiry_date: end_expiry_date,
        disbursement_status: disbursement_status,
        start_amount_applied: start_amount_applied,
        end_amount_applied: end_amount_applied,
    };

    // JS  expired loan Disbursements data objects
    var expiredObjects = {
        year: year,
        start_date: start_date,
        end_date: end_date,
        gender: gender,
        account_no: account_no,
        others: others,
        client_account_status: client_account_status,
        client_name: client_name,
        disbursement_code: disbursement_code,
        branch_id: branch_id,
        staff_id: staff_id,
        start_expiry_date: start_expiry_date,
        end_expiry_date: end_expiry_date,
        disbursement_status: disbursement_status,
        start_amount_applied: start_amount_applied,
        end_amount_applied: end_amount_applied,
    };

    // JS  total loan Disbursements data objects
    var totalObjects = {
        year: year,
        start_date: start_date,
        end_date: end_date,
        gender: gender,
        account_no: account_no,
        others: others,
        client_account_status: client_account_status,
        client_name: client_name,
        disbursement_code: disbursement_code,
        branch_id: branch_id,
        staff_id: staff_id,
        start_expiry_date: start_expiry_date,
        end_expiry_date: end_expiry_date,
        disbursement_status: disbursement_status,
        start_amount_applied: start_amount_applied,
        end_amount_applied: end_amount_applied,
    };


    var url = baseURL + "/client/reports/view/report/disbursements";
    // ajax adding data to database
    $.ajax({
        url: url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        beforeSend: function () {
            $("#loan_disbursement_counter").html('<div class="text-center">' + '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>');
            // loan principal
            $("#loan_principal_counter").html('<div class="text-center">' + '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>');
            // loan interest
            $("#loan_interest_counter").html('<div class="text-center">' + '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>');
            // load the spinner for loan disbursement table
            $("#loan_disbursements_table").html('<div class="text-center">' + '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>');
        },
        success: function (results) {

            disbursementsChartsReport(clearedObjects, expiredObjects, totalObjects);
            // loan disbursement counter
            var loansCounterDataTable =
                '<div class="table-responsive"><table id="disbursementCounter" class="table table-sm table-striped table-hover"' +
                'cellspacing="0" width="100%"><tbody></tbody>' +
                '<tfoot>' +
                '<tr>' +
                '<th>Total Loans</th>' +
                '<th>' + results.loanSummary.runningLoans + '</th>' +
                '<th>' + results.loanSummary.loanArrears + '</th>' +
                '<th>' + results.loanSummary.clearedLoans + '</th>' +
                '<th>' + results.loanSummary.expiredLoans + '</th>' +
                '<th>' + results.loanSummary.loans + '</th>' +
                '</tr>' +
                '</tfoot></table></div>';

            $("#loan_disbursement_counter").html(loansCounterDataTable);

            $("#disbursementCounter").DataTable({
                data: results.loanscounter,
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
                    { title: "Number of Running Loans" },
                    { title: "Number of Arrears Loans" },
                    { title: "Number of Cleared Loans" },
                    { title: "Number of Expired Loans" },
                    { title: "Total Number of Loans" }
                ],
                // dom: "lBfrtip",
                // buttons: ["copy", "csv", "excel", "pdf", "print"],
                dom:
                    "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",
                buttons: buttonsConfig,
            });

            // loan principal counter
            var loanPrincipalDataTable =
                '<div class="table-responsive"><table id="loanPrincipal" class="table table-sm table-striped table-hover"' +
                'cellspacing="0" width="100%"><tbody></tbody>' +
                '<tfoot>' +
                '<tr>' +
                '<th>Total</th>' +
                '<th>' + results.disbursement.principalDisbursed + '</th>' +
                '<th>' + results.disbursement.principalCollected + '</th>' +
                '<th>' + results.disbursement.principalOutstanding + '</th>' +
                '</tr>' +
                '</tfoot></table></div>';

            $("#loan_principal_counter").html(loanPrincipalDataTable);

            $("#loanPrincipal").DataTable({
                data: results.disbursement.principalReleased,
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
                    { title: "Principal Released [" + currency + "]" },
                    { title: "Principal Paid [" + currency + "]" },
                    { title: "Principal Balance [" + currency + "]" }
                ],
                // dom: "lBfrtip",
                // buttons: ["copy", "csv", "excel", "pdf", "print"],
                dom:
                    "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",
                buttons: buttonsConfig,
            });

            // loan interest counter
            var loanInterestDataTable =
                '<div class="table-responsive"><table id="loanInterest" class="table table-sm table-striped table-hover"' +
                'cellspacing="0" width="100%"><tbody></tbody>' +
                '<tfoot>' +
                '<tr>' +
                '<th>Total</th>' +
                '<th>' + results.revenue.interestTotal + '</th>' +
                '<th>' + results.revenue.interestCollected + '</th>' +
                '<th>' + results.revenue.interestOutstanding + '</th>' +
                '</tr>' +
                '</tfoot></table></div>';

            $("#loan_interest_counter").html(loanInterestDataTable);

            $("#loanInterest").DataTable({
                data: results.revenue.interests,
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
                    { title: "Interest to be Paid [" + currency + "]" },
                    { title: "Interest Paid [" + currency + "]" },
                    { title: "Interest Balance [" + currency + "]" }
                ],
                // dom: "lBfrtip",
                // buttons: ["copy", "csv", "excel", "pdf", "print"],
                dom:
                    "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",
                buttons: buttonsConfig,
            });

            // loan disbursement information
            var loansTableDataTable =
                '<div class="table-responsive"><table id="loansTable" class="table table-sm table-striped table-hover"' +
                'cellspacing="0" width="100%"><tbody></tbody>' +
                '</table></div>';

            $("#loan_disbursements_table").html(loansTableDataTable);

            $("#loansTable").DataTable({
                data: results.loans,
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
                    { title: "Disbursement Date" },
                    // { title: "Code" },
                    { title: "Client" },
                    // { title: "Account Number" },
                    { title: "Loan Product" },
                    { title: "Interest" },
                    { title: "Duration" },
                    { title: "Disbursed [" + currency + "]" },
                    { title: "Balance [" + currency + "]" },
                    { title: "Status" },
                    { title: "Source" },
                    { title: "Expiry" },
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
                        "targets": [10], //last column
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

function monthlyDisbursementsNumber(data) {
    const total = [];
    $.ajax({
        url: "/client/reports/view/report/disbursements",
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
            client_account_status: data.client_account_status,
            client_name: data.client_name,
            disbursement_code: data.disbursement_code,
            branch_id: data.branch_id,
            staff_id: data.staff_id,
            start_expiry_date: data.start_expiry_date,
            end_expiry_date: data.end_expiry_date,
            disbursement_status: data.disbursement_status,
            start_amount_applied: data.start_amount_applied,
            end_amount_applied: data.end_amount_applied
        },
        success: function (data) {
            if (data.monthlytotalloans.length > 0) {
                data.monthlytotalloans.forEach(function (value, index) {
                    total.push(value);
                });
                // for (let index in data.monthlytotalloans) {
                //   const element = data.monthlytotalloans[index];
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