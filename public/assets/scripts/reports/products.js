var from = $('[name="from"]').val();
var to = $('[name="to"]').val();
if (from == '') {
    from = 0;
}
if (to == '') {
    to = 0;
}
var tableId = "";
// dataTables url
var tableDataUrl = "/admin/loans/products-report/" + from + "/" + to;
// dataTables column config
var columnsConfig = [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "product_name" },
    { data: "repayment_freq" },
    { data: "repayment_period" },
    { data: "interest_rate" },
    { data: "interest_type" },
    { data: "status" },
    { data: "id" },
    { data: "action", orderable: false, searchable: false }
];
// dataTables buttons config
var buttonsConfig = [];
// show reload table button by default
buttonsConfig.push({
    text: '<i class="fa fa-refresh"></i>',
    className: 'btn btn-sm btn-warning',
    titleAttr: 'Reload Products ' + title + ' Table',
    action: function () {
        reload_table(tableId)
    }
},);
// show export buttons
if (userPermissions.includes('export' + title) || userPermissions === '"all"') {
    buttonsConfig.push({
        extend: 'excel',
        className: 'btn btn-sm btn-success',
        titleAttr: 'Export ' + title + ' To Excel',
        text: '<i class="fas fa-file-excel"></i>',
        filename: 'Products '+ title +' Information',
        extension: '.xlsx',
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
        },
    },
    {
        extend: 'pdf',
        className: 'btn btn-sm btn-danger',
        titleAttr: 'Export Products '+ title +' To PDF',
        text: '<i class="fas fa-file-pdf"></i>',
        filename: 'Products '+ title +' Information',
        extension: '.pdf',
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
        },
    },
    {
        extend: 'csv',
        className: 'btn btn-sm btn-success',
        titleAttr: 'Export Products '+ title +' To CSV',
        text: '<i class="fas fa-file-csv"></i>',
        filename: 'Products '+ title +' Information',
        extension: '.csv',
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
        },
    }, 
    {
        extend: 'copy',
        className: 'btn btn-sm btn-warning',
        titleAttr: 'Copy Products '+ title +' Information',
        text: '<i class="fas fa-copy"></i>',
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
        },
    },
    {
        extend: 'print',
        title: "<h3 class='text-center text-bold'>" + businessName + "</h3><h4 class='text-center text-bold'>Products "+ title +" Information</h4><h5 class='text-center'>Printed On " + new Date().getHours() + " : " + new Date().getMinutes() + " " + new Date().toDateString() + "</h5>",
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
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
        },

        className: 'btn btn-sm btn-primary',
        titleAttr: 'Print Products '+ title +' Information',
        text: '<i class="fa fa-print"></i>',
        filename: 'Products '+ title +' Information'
    });
}

$(document).ready(function () {
    // call to dataTable initialization function
    initializeDataTable(tableId, tableDataUrl, columnsConfig, buttonsConfig);
});

function exportProductForm() {
    var product_id = $('[name="id"]').val();
    window.location.assign("/admin/loans/productform/" + product_id);
}

// view record
function view_product(id) {
    $.ajax({
        url: "/admin/loans/product/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $('[name="id"]').val(data.id);
            $('[name="product_name"]').val(data.product_name);
            $('[name="interest_rate"]').val(data.interest_rate);
            $('[name="interest_type"]').val(data.interest_type);
            $('[name="repayment_period"]').val(data.repayment_period);
            $('[name="repayment_freq"]').val(data.repayment_freq);
            $('[name="product_desc"]').val(data.product_desc);
            $('textarea#seeSummernote').summernote('code', data.product_features);
            $('[name="status"]').val(data.status);
            $('[name="created_at"]').val(data.created_at);
            $('[name="updated_at"]').val(data.updated_at);
            $('#view_modal').modal('show');
            $('.modal-title').text('View ' + data.product_name);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}