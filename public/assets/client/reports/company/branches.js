// dataTables buttons config
var buttonsConfig = [];
if (userPermissions.includes('export' + title) || userPermissions === '"all"') {
    buttonsConfig.push(
        {
            extend: 'excel',
            className: 'btn btn-sm btn-success',
            titleAttr: 'Export ' + title + ' To Excel',
            text: '<i class="fas fa-file-excel"></i>',
            filename: 'Branches ' + title + ' Information',
            extension: '.xlsx',
        },
        {
            extend: 'pdf',
            className: 'btn btn-sm btn-danger',
            titleAttr: 'Export Branches ' + title + ' To PDF',
            text: '<i class="fas fa-file-pdf"></i>',
            filename: 'Branches ' + title + ' Information',
            extension: '.pdf',
        },
        {
            extend: 'csv',
            className: 'btn btn-sm btn-success',
            titleAttr: 'Export Branches ' + title + ' To CSV',
            text: '<i class="fas fa-file-csv"></i>',
            filename: 'Branches ' + title + ' Information',
            extension: '.csv',
        },
        {
            extend: 'copy',
            className: 'btn btn-sm btn-warning',
            titleAttr: 'Copy Branches ' + title + ' Information',
            text: '<i class="fas fa-copy"></i>',
        },
        {
            extend: 'print',
            title: "<h3 class='text-center text-bold'>" + businessName + "</h3><h4 class='text-center text-bold'>Branches " + title + " Information</h4><h5 class='text-center'>Printed On " + new Date().getHours() + " : " + new Date().getMinutes() + " " + new Date().toDateString() + "</h5>",
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
            titleAttr: 'Print Branches ' + title + ' Information',
            text: '<i class="fa fa-print"></i>',
            filename: 'Branches ' + title + ' Information'
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

    // load the staff officer
    generateStaff();

    // JS debit data objects
    var data = {
        entry_status: "",
        year: "",
        start_date: "",
        end_date: "",
        gender: "",
        account_no: "",
        others: "",
        payment_id: "",
        entry_typeId: "",
        reference_id: "",
        branch_id: "",
        staff_id: ""
    };

    // JS credit data object
    var creditData = {
        entry_status: "",
        year: "",
        start_date: "",
        end_date: "",
        gender: "",
        account_no: "",
        others: "",
        payment_id: "",
        entry_typeId: "",
        reference_id: "",
        branch_id: "",
        staff_id: ""
    };

    // JS total amount data object
    var total = {
        entry_status: "",
        year: "",
        start_date: "",
        end_date: "",
        gender: "",
        account_no: "",
        others: "",
        payment_id: "",
        entry_typeId: "",
        reference_id: "",
        branch_id: "",
        staff_id: ""
    };

    // auto load the savings report
    filterSavings();

    // auto load the savings chart
    savingsCharts(data, creditData, total);

});

function savingsCharts(jsDebitDataObject, jsCreditDataObject, total) {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */
    var ticksStyle = {
        // fontColor: '#495057',
        fontStyle: "bold",
    };

    // Fetch all the keys(variables) from the object
    var debitKeys = Object.keys(jsDebitDataObject);

    //console.log(debitKeys) // [entry_status, year, start_date etc]
    // Iterate over each key to update entry status to debit
    debitKeys.forEach((debitElement) => {
        // check whether the element is entry_status to update its value to debit
        if (debitElement == "entry_status") {
            jsDebitDataObject[debitElement] = "debit";
        }
    });

    var debit = jsDebitDataObject;

    // Fetch all the keys(variables) from the object
    var creditKeys = Object.keys(jsCreditDataObject);

    //console.log(creditKeys) // [entry_status, year, start_date etc]

    // Iterate over each key to update entry status to debit
    creditKeys.forEach((creditElement) => {
        // check whether the element is entry_status to update its value to debit
        if (creditElement == "entry_status") {
            jsCreditDataObject[creditElement] = "credit";
        }
    });

    var credit = jsCreditDataObject;

    // Fetch all the keys(variables) from the object
    var totalKeys = Object.keys(total);

    //console.log(totalKeys) // [entry_status, year, start_date etc]

    // Iterate over each key to update entry status to debit
    totalKeys.forEach((totalElement) => {
        // check whether the element is entry_status to update its value to debit
        if (totalElement == "entry_status") {
            total[totalElement] = "total";
        }
    });

    var totalAmount = total;

    /* area chart */
    var areaOptions = {
        series: [{
            name: 'Credit',
            data: monthly_totals(credit)
        }, {
            name: 'Debit',
            data: monthly_totals(debit),
        }, {
            name: 'Total',
            data: monthly_totals(totalAmount),
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
            text: 'Monthly Branches Savings Report - Area Chart',
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
                text: 'Number of Branches',
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
            name: 'Credit',
            data: monthly_totals(credit)
        }, {
            name: 'Debit',
            data: monthly_totals(debit),
        }, {
            name: 'Total',
            data: monthly_totals(totalAmount),
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
            text: 'Monthly Branches Savings Report - Line Graph',
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
                text: 'Number of Branches',
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
            name: 'Credit',
            data: monthly_totals(credit)
        }, {
            name: 'Debit',
            data: monthly_totals(debit),
        }, {
            name: 'Total',
            data: monthly_totals(totalAmount),
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
            text: 'Monthly Branches Savings Report - Column Chart',
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
                text: 'Branches Savings Collection',
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
            name: 'Credit',
            data: monthly_totals(credit)
        }, {
            name: 'Debit',
            data: monthly_totals(debit),
        }, {
            name: 'Total',
            data: monthly_totals(totalAmount),
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
            text: 'Monthly Branches Savings Report - Radar Chart',
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
            url: '/admin/transactions/transaction-types/12',
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                $('select#entry_typeId').html('<option value="">-- select --</option>');
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $("<option />").val(data[i].id)
                            .text(data[i].type)
                            .appendTo($('select#entry_typeId'));
                    }
                } else {
                    $('select#entry_typeId').html('<option value="">No Type</option>');
                }

            },
            error: function (err) {
                $('select#entry_typeId').html('<option value="">Error Occured</option>');
            }
        });
    } else {
        $.ajax({
            url: '/admin/transactions/transaction-types/12',
            type: "POST",
            dataType: "JSON",
            success: function (response) {
                if (response.length > 0) {
                    $('select#entry_typeId').find('option').not(':first').remove();
                    // Add options
                    $.each(response, function (index, data) {
                        if (data['id'] == entry_typeId) {
                            var selection = 'selected';
                        } else {
                            var selection = '';
                        }
                        $('select#entry_typeId').append('<option value="' + data['id'] + '" ' + selection + '>' + data['type'] + '</option>');
                    });
                } else {
                    $('select#entry_typeId').html('<option value="">No Type</option>');

                }
            }
        });
    }
}

function filterSavings() {
    $('#filter-savings').html('<i class="fa fa-spinner fa-spin"></i> searching...');
    $('#filter-savings').attr('disabled', true);

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
        staff_id: staff_id
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
        staff_id: staff_id
    };

    // JS  total data objects
    var total = {
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
        staff_id: staff_id
    };


    var url = baseURL + "/admin/reports/module/get-report-type/branches";
    // ajax adding data to database
    $.ajax({
        url: url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        beforeSend: function () {
            $("#filter_savings").html('<div class="text-center">' + '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>');
        },
        success: function (results) {

            // call the savings chart report
            savingsCharts(data, creditObject, total);

            // branches monthly Branches savings collection
            var banchesDataTable =
                '<div class="table-responsive"><table id="branchSavings" class="table table-sm table-striped table-hover"' +
                'cellspacing="0" width="100%"><tbody></tbody>' +
                '<tfoot>' +
                '<tr>' +
                '<th>Total Amount</th>' +
                '<th></th>' +
                '<th>' + results.branches.entry.credit + '</th>' +
                '<th>' + results.branches.entry.debit + '</th>' +
                '<th>' + results.branches.entry.entry + '</th>' +
                '</tr>' +
                '</tfoot>' +
                '</table></div>';
            $("#branches_savings").html(banchesDataTable);
            var table = $("#branchSavings").DataTable({
                data: results.branches.branchesSavings,
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
                bAutoWidth: true,
                columns: [
                    { title: "Year - Month" },
                    { title: "Branch" },
                    { title: "Total Credit [" + currency + "]" },
                    { title: "Total Debit [" + currency + "]" },
                    { title: "Total Amount [" + currency + "]" }
                ],
                // dom: "lBfrtip",
                // buttons: ["copy", "csv", "excel", "pdf", "print"],
                dom:
                    "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",
                buttons: buttonsConfig,
            });

            var dataTable =
                '<div class="table-responsive"><table id="savings" class="table table-sm table-striped table-hover"' +
                'cellspacing="0" width="100%"><tbody></tbody>' +
                '<tfoot>' +
                '<tr>' +
                '<th>Total Amount</th>' +
                '<th>' + results.branches.collections.credit + '</th>' +
                '<th>' + results.branches.collections.debit + '</th>' +
                '<th>' + results.branches.collections.total + '</th>' +
                '</tr>' +
                '</tfoot></table></div>';
            $("#filter_savings").html(dataTable);
            var table = $("#savings").DataTable({
                data: results.branches.entry.monthly,
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
                bInfo: true,
                bAutoWidth: true,
                columns: [
                    { title: "Year - Month" },
                    { title: "Total Credit [" + currency + "]" },
                    { title: "Total Debit [" + currency + "]" },
                    { title: "Total Amount [" + currency + "]" }
                ],
                // dom: "lBfrtip",
                // buttons: ["copy", "csv", "excel", "pdf", "print"],
                dom:
                    "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",
                buttons: buttonsConfig,
            });
            // client counter
            var dataTableClient =
                '<div class="table-responsive"><table id="clientCounter" class="table table-sm table-striped table-hover"' +
                'cellspacing="0" width="100%"><tbody></tbody>' +
                '<tfoot>' +
                '<tr>' +
                '<th>Total Clients</th>' +
                '<th>' + results.branches.clients.credits + '</th>' +
                '<th>' + results.branches.clients.debits + '</th>' +
                '<th>' + results.branches.clients.total + '</th>' +
                '</tr>' +
                '</tfoot></table></div>';
            $("#client_counter").html(dataTableClient);
            var table = $("#clientCounter").DataTable({
                data: results.branches.clients.clientsCounter,
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
                bInfo: true,
                bAutoWidth: true,
                columns: [
                    { title: "Year - Month" },
                    { title: "Number of Clients [Credit]" },
                    { title: "Number of Clients [Debit]" },
                    { title: "Total Clients" }
                ],
                // dom: "lBfrtip",
                // buttons: ["copy", "csv", "excel", "pdf", "print"],
                dom:
                    "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",
                buttons: buttonsConfig,
            });

            // client savings entries
            var dataTableSavings =
                '<div class="table-responsive"><table id="savings_client_entries" class="table table-sm table-striped table-hover"' +
                'cellspacing="0" width="100%"><tbody></tbody>' +
                '</table></div>';
            $("#client_savings").html(dataTableSavings);
            var table = $("#savings_client_entries").DataTable({
                data: results.branches.clients.entries,
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
                bAutoWidth: true,
                columns: [
                    { title: "S.No", "width": "4%" },
                    { title: "Date" },
                    { title: "Client" },
                    { title: "Account No" },
                    { title: "Amount [" + currency + "]" },
                    { title: "Type" },
                    { title: "Status" },
                    { title: "Total [" + currency + "]" }
                ],
                // dom: "lBfrtip",
                // buttons: ["copy", "csv", "excel", "pdf", "print"],
                dom:
                    "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",
                buttons: buttonsConfig,
            });

            $('#filter-savings').html('<i class="fa fa-search fa-fw"></i>');
            $('#filter-savings').attr('disabled', false);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // swal("Sorry!", "Check your Internet Connection!", "error");
            $('#filter-savings').html('<i class="fa fa-search fa-fw"></i>');
            $('#filter-savings').attr('disabled', false);

        }
    });
}

function monthly_totals(data) {
    const total = [];
    $.ajax({
        url: "/admin/reports/module/get-report-type/branches",
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
            staff_id: data.staff_id
        },
        success: function (data) {
            if (data.branches.collections.monthly.length > 0) {
                data.branches.collections.monthly.forEach(function (value, index) {
                    total.push(value);
                });
                // for (let index in data.branches.collections.monthly) {
                //   const element = data.branches.collections.monthly[index];
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