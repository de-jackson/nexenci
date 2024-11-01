var save_method;
var account_typeId = 3; // id for loan repayment particulars
var loansRevenue = 19; // id for revenue from loan particulars
var entry_menu = "financing"; //default entry menu for all disbursement payments
// table IDs
var runnigTableId = 'runningDisbursements';
var arrearsTableId = 'arrearsDisbursements';
var clearedTableId = 'clearedDisbursements';
var expiredTableId = 'expiredDisbursements';
var defaultedTableId = 'defaultedDisbursements';
// dataTables urls
var runnigTableDataUrl = "/client/loans/type/disbursements/running";
var arrearsTableDataUrl = "/client/loans/type/disbursements/arrears";
var clearedTableDataUrl = "/client/loans/type/disbursements/cleared";
var expiredTableDataUrl = "/client/loans/type/disbursements/expired";
var defaultedTableDataUrl = "/client/loans/type/disbursements/defaulted";
// dataTables column config
var columnsConfig = [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "name" },
    { data: "product_name" },
    { data: "disbursement_code" },
    { data: "principal", render: $.fn.DataTable.render.number(',') },
    { data: "actual_interest", render: $.fn.DataTable.render.number(',') },
    { data: "actual_repayment", render: $.fn.DataTable.render.number(',') },
    { data: "actual_installment", render: $.fn.DataTable.render.number(',') },
    { data: "total_collected", render: $.fn.DataTable.render.number(',') },
    { data: "loan_balance", },
    { data: "expiry", },
    { data: "action", orderable: false, searchable: false }
];
// dataTables buttons configs
function createButtonConfig(status, tableId, permissions) {
    var buttonsConfig = [];

    // Show import button
    if (permissions.includes('import' + title) || permissions === '"all"') {
        buttonsConfig.push({
            text: '<i class="fas fa-upload"></i>',
            className: 'btn btn-sm btn-success import' + title,
            attr: {
                id: 'import' + title
            },
            titleAttr: 'Import ' + status + ' ' + title,
            action: function () {
                import_disbursements();
            }
        });
    }
    // Show bulk-delete
    if (permissions.includes('bulkDelete' + title) || permissions === '"all"') {
        buttonsConfig.push({
            text: '<i class="fa fa-trash"></i>',
            className: 'btn btn-sm btn-danger delete' + title,
            attr: {
                id: 'delete' + title
            },
            titleAttr: 'Bulky Delete ' + status + ' ' + title,
            action: function () {
                bulk_deleteDisbursements();
            }
        });
    }

    // Show reload table button by default
    buttonsConfig.push({
        text: '<i class="fa fa-refresh"></i>',
        className: 'btn btn-sm btn-warning',
        titleAttr: 'Reload  ' + status + ' ' + title + ' Information',
        action: function () {
            reload_table(tableId)
        }
    });

    // Show export buttons
    if (permissions.includes('export' + title) || permissions === '"all"') {
        buttonsConfig.push({
            extend: 'excel',
            className: 'btn btn-sm btn-success',
            titleAttr: 'Export ' + status + ' ' + title + ' To Excel',
            text: '<i class="fas fa-file-excel"></i>',
            filename: status + ' ' + title + ' Information',
            extension: '.xlsx',
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
            },
        },
            {
                extend: 'pdf',
                className: 'btn btn-sm btn-danger',
                titleAttr: 'Export ' + status + ' ' + title + ' To PDF',
                text: '<i class="fas fa-file-pdf"></i>',
                filename: status + ' ' + title + ' Information',
                extension: '.pdf',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
            },
            {
                extend: 'csv',
                className: 'btn btn-sm btn-success',
                titleAttr: 'Export ' + status + ' ' + title + ' Information To CSV',
                text: '<i class="fas fa-file-csv"></i>',
                filename: status + ' ' + title + ' Information',
                extension: '.csv',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
            },
            {
                extend: 'copy',
                className: 'btn btn-sm btn-warning',
                titleAttr: 'Copy ' + status + ' ' + title + ' Information',
                text: '<i class="fas fa-copy"></i>',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
            },
            {
                extend: 'print',
                title: "<h3 class='text-center text-bold'>" + businessName + "</h3><h4 class='text-center text-bold'> " + status + " " + title + "  Information</h4><h5 class='text-center'>Printed On " + new Date().getHours() + " : " + new Date().getMinutes() + " " + new Date().toDateString() + "</h5>",
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
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
                titleAttr: 'Print ' + title + ' Information',
                text: '<i class="fa fa-print"></i>',
                filename: status + ' ' + title + ' Information'
            });
    }

    return buttonsConfig;
}

var runnigBtnsConfig = createButtonConfig("Running", runnigTableId, userPermissions);
var arrearsBtnsConfig = createButtonConfig("Arrears", arrearsTableId, userPermissions);
var clearedBtnsConfig = createButtonConfig("Cleared", clearedTableId, userPermissions);
var expiredBtnsConfig = createButtonConfig("Expired", expiredTableId, userPermissions);
var defaultedBtnsConfig = createButtonConfig("Defaulted", expiredTableId, userPermissions);

$(document).ready(function () {
    // redifine dataTable column length on change of data-bs-toggle tab
    $("button[data-bs-toggle=\"tab\"]").on("shown.bs.tab", function (e) {
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust()
            .responsive.recalc();
    });
    // runnig disbursements
    initializeDataTable(runnigTableId, runnigTableDataUrl, columnsConfig, runnigBtnsConfig);
    // arrears disbursements
    initializeDataTable(arrearsTableId, arrearsTableDataUrl, columnsConfig, arrearsBtnsConfig);
    // cleared  disbursements
    initializeDataTable(clearedTableId, clearedTableDataUrl, columnsConfig, clearedBtnsConfig);
    // expired disbursements
    initializeDataTable(expiredTableId, expiredTableDataUrl, columnsConfig, expiredBtnsConfig);
    // defaulted disbursements
    initializeDataTable(defaultedTableId, defaultedTableDataUrl, columnsConfig, defaultedBtnsConfig);

    // load disbursement particulars
    disbursement_particular();
    // load payment methods
    selectPaymentMethod();

    //check all table inputs
    $("#check-allRunning").click(function () {
        $(".data-checkRunning").prop('checked', $(this).prop('checked'));
    });
    $("#check-allArrears").click(function () {
        $(".data-checkArrears").prop('checked', $(this).prop('checked'));
    });
    $("#check-allCleared").click(function () {
        $(".data-checkCleared").prop('checked', $(this).prop('checked'));
    });
    $("#check-allExpired").click(function () {
        $(".data-checkExpired").prop('checked', $(this).prop('checked'));
    });
    $("#check-allDefaulted").click(function () {
        $(".data-checkDefaulted").prop('checked', $(this).prop('checked'));
    });
});

function import_disbursements() {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Import Disbursement(s)');
    $('#importRow').show();
    $('#formRow').hide();
    $('#export').hide();
    $('[name="id"]').val(0);
    $('[name="mode"]').val('imported');
}
// make payment
function add_disbursementPayment(id, client_id) {
    $('#repaymentForm')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#repayment_modal_form').modal('show');
    $('.modal-title').text('Add Disbursement Payment');
    $('[name="disbursement_id"]').val(id);
    $('[name="client_id"]').val(client_id);
    $('[name="account_typeId"]').val(account_typeId);
    $('[name="entry_menu"]').val('financing');
    // load loan info n auto fill form where applicable
    disbursementRecord(id);
    // load loan repayment entry types
    transaction_types();
}
// save payment
function save_payments() {
    $('#btnPay').text('submitting...');
    $('#btnPay').attr('disabled', true);
    // ajax adding data to database
    var formData = new FormData($('#repaymentForm')[0]);
    $.ajax({
        url: "/admin/transactions/transaction",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data) {
            if (!data.inputerror) {
                if (data.status && data.error == null) {
                    $('#repayment_modal_form').modal('hide');
                    Swal.fire("Success!", data.messages, "success");
                    reload_table('runningDisbursements');
                    reload_table('pendingInstallments');
                    reload_table('clearedDisbursements');
                    reload_table('expiredDisbursements');
                } else if (data.error != null) {
                    Swal.fire(data.error, data.messages, "error");
                } else {
                    Swal.fire('Error', "Something unexpected happened, try again later", "error");
                }
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                    $('[name="' + data.inputerror[i] + '"]').closest(".form-group").find(".help-block").text(data.error_string[i]);
                }
            }
            $('#btnPay').text('Submit');
            $('#btnPay').attr('disabled', false);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
            $('#btnPay').text('Submit');
            $('#btnPay').attr('disabled', false);
        }
    });
}

// delete record
function delete_disbursement(id, name) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to recover " + name + "'s disbursement!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/client/disbursements/" + id,
                type: "DELETE",
                dataType: "JSON",
                success: function (data) {
                    //if success reload ajax table
                    if (data.status && data.error == null) {
                        Swal.fire("Success!", name + ' ' + data.messages, "success");
                        reload_table('runningDisbursements');
                        reload_table('pendingInstallments');
                        reload_table('clearedDisbursements');
                        reload_table('expiredDisbursements');
                    } else if (data.error != null) {
                        Swal.fire(data.error, data.messages, "error");
                    } else {
                        Swal.fire('Error', "Something unexpected happened, try again later", "error");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.fire(textStatus, errorThrown, 'error');
                }
            });
        }
    })
}

// bulk delete
function bulk_deleteDisbursements() {
    var list_id = [];
    $(".data-check:checked").each(function () {
        list_id.push(this.value);
    });
    if (list_id.length > 0) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: true
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover these ' + list_id.length + ' disbursement(s) once deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    data: {
                        id: list_id
                    },
                    url: "/client/loans/type/disbursements/bulkdelete",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status && data.error == null) {
                            Swal.fire("Success!", data.messages, "success");
                            reload_table('runningDisbursements');
                            reload_table('pendingInstallments');
                            reload_table('clearedDisbursements');
                            reload_table('expiredDisbursements');
                        } else if (data.error != null) {
                            Swal.fire(data.error, data.messages, "error");
                        } else {
                            Swal.fire('Error', "Something unexpected happened, try again later", "error");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Swal.fire(textStatus, errorThrown, 'error');
                    }
                });
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Bulky delete cancelled :)',
                    'error'
                )
            }
        })
    } else {
        Swal.fire("Sorry!", "No disbursement selected....", "error");
    }
}

function disbursementRecord(id) {
    $.ajax({
        url: "/client/disbursements/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $('strong#cName').text(data.name);
            $('strong#cContact').text(data.mobile);
            $('strong#cNontact2').text(data.alternate_no);
            $('strong#cEmail').text(data.email);
            $('strong#cAccountNo').text(data.account_no);
            $('strong#cBalance').text(Number(data.account_balance).toLocaleString());
            $('strong#cAddress').text(data.residence);
            $('strong#tLoan').text(Number(data.actual_repayment).toLocaleString());
            $('strong#lBalance').text(Number(data.total_balance).toLocaleString());
            $('strong#installment').text(Number(data.actual_installment).toLocaleString());
            $('strong#periodDays').text(data.loan_period_days);
            $('strong#daysCovered').text(data.days_covered);
            $('strong#daysLeft').text(data.days_remaining);
            $('strong#createdAt').text(data.created_at);
            $('strong#expiryDate').text(data.loan_expiry_date);
            $('strong#expiryDay').text(data.expiry_day);
            $('[name="expiry_date"]').val(data.loan_expiry_date);
            if (data.photo && (imageExists('/uploads/clients/passports/' + data.photo))) {
                $('#cPhoto-preview div').html('<img src="/uploads/clients/passports/' + data.photo + '" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">');
            } else {
                $('#cPhoto-preview div').html('<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">');
            }
            $('[name="contact"]').val(data.mobile);
            $('[name="amount"]').val(data.actual_installment);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}