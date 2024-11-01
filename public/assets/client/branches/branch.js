var save_method;
var tableId = 'branch';
// dataTables url
var tableDataUrl = "/client/company/branches/generate-branches/" + id;
// dataTables column config
var columnsConfig = [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "branch_name" },
    { data: "branch_mobile" },
    { data: "branch_email" },
    { data: "branch_address" },
    { data: "branch_code" },
    { data: "branch_status" },
    { data: "action", orderable: false, searchable: false },
];
// dataTables buttons config
var buttonsConfig = [];
// show create button
if (userPermissions.includes('create' + title) || (userPermissions === '"all"')) {
    buttonsConfig.push({
        text: '<i class="fas fa-plus"></i>',
        className: 'btn btn-sm btn-info create' + title,
        attr: {
            id: 'create' + title
        },
        titleAttr: 'Add ' + title,
        action: function () {
            add_branch();
        }
    },);
}
// show upload button
if (userPermissions.includes('import' + title) || (userPermissions === '"all"')) {
    buttonsConfig.push({
        text: '<i class="fas fa-upload"></i>',
        className: 'btn btn-sm btn-success import' + title,
        attr: {
            id: 'import' + title
        },
        titleAttr: 'Import ' + title + 's',
        action: function () {
            import_branches()
        }
    },);
}
// show bulk-delete
if (userPermissions.includes('bulkDelete' + title) || (userPermissions === '"all"')) {
    buttonsConfig.push({
        text: '<i class="fa fa-trash"></i>',
        className: 'btn btn-sm btn-danger delete' + title,
        attr: {
            id: 'delete' + title
        },
        titleAttr: 'Bulky Delete ' + title,
        action: function () {
            bulk_deleteBranch();
        }
    });
}
// show reload table button by default
buttonsConfig.push({
    text: '<i class="fa fa-refresh"></i>',
    className: 'btn btn-sm btn-warning',
    titleAttr: 'Reload ' + title + ' Table',
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
        filename: title + ' Information',
        extension: '.xlsx',
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7]
        },
    },
        {
            extend: 'pdf',
            className: 'btn btn-sm btn-danger',
            titleAttr: 'Export ' + title + ' To PDF',
            text: '<i class="fas fa-file-pdf"></i>',
            filename: title + ' Information',
            extension: '.pdf',
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7]
            },
        },
        {
            extend: 'csv',
            className: 'btn btn-sm btn-success',
            titleAttr: 'Export ' + title + ' To CSV',
            text: '<i class="fas fa-file-csv"></i>',
            filename: title + ' Information',
            extension: '.csv',
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7]
            },
        },
        {
            extend: 'copy',
            className: 'btn btn-sm btn-warning',
            titleAttr: 'Copy ' + title + ' Information',
            text: '<i class="fas fa-copy"></i>',
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7]
            },
        },
        {
            extend: 'print',
            title: "<h3 class='text-center text-bold'>" + businessName + "</h3><h4 class='text-center text-bold'>" + title + " Information</h4><h5 class='text-center'>Printed On " + new Date().getHours() + " : " + new Date().getMinutes() + " " + new Date().toDateString() + "</h5>",
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7]
            },
            customize: function (win) {
                $(win.document.body)
                    .css('font-size', '10pt')
                    .css('font-family', 'calibri')
                    .prepend(
                        '<img src="' + baseURL + '/uploads/logo/' + logo + '" style="position:absolute; top:0; left:0;width:100px;height:100px;" />'
                    );
                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
                // Replace the page title with the actual page title
                $(win.document.head).find('title').text(title);
            },

            className: 'btn btn-sm btn-secondary',
            titleAttr: 'Print ' + title + ' Information',
            text: '<i class="fa fa-print"></i>',
            filename: title + ' Information'
        });
}

$(document).ready(function () {
    // call to dataTable initialization function
    initializeDataTable(tableId, tableDataUrl, columnsConfig, buttonsConfig);
});

function exportBranchForm() {
    var branch_id = $('[name="id"]').val();
    window.location.assign("/client/company/branches/print/" + branch_id);
}

function add_branch() {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#formRow').show();
    $('#importRow').hide();
    $('.modal-title').text('Add New Branch');
    $('[name="id"]').val(0);
    $('[name="mode"]').val('create');
    $('textarea#summernote').summernote('reset');
    $('select#branch_status').trigger('change');
    $('#importRow').hide();
    $('#formRow').show();
    $('#modal_form').modal('show'); // show bootstrap modal
}
function import_branches() {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Import Branch(es)');
    $('#formRow').hide();
    $('#export').hide();
    $('#importRow').show();
    $('[name="id"]').val(0);
    $('[name="mode"]').val('import');
}

function save_branch() {
    bID = $('[name="id"]').val();
    $('#btnSubmit').text('submitting...'); //change button text
    $('#btnSubmit').attr('disabled', true); //set button disable 
    var url, method;
    if (save_method == 'add') {
        url = "/client/company/branch";
    } else {
        url = "/client/company/edit-branch/" + bID;
    }

    var formData = new FormData($('#form')[0]);
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data) {
            if (!data.inputerror) {
                if (data.status && data.error == null) {
                    $('#modal_form').modal('hide');
                    Swal.fire("Success!", data.messages, "success");
                    reload_table(tableId);
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
            $('#btnSubmit').text('Submit'); //change button text
            $('#btnSubmit').attr('disabled', false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
            $('#btnSubmit').text('Submit');
            $('#btnSubmit').attr('disabled', false);
        }
    });
}

function edit_branch(id) {
    save_method = 'update';
    $('#export').hide();
    $('#form')[0].reset(); // reset form on modals
    $('#importRow').hide();
    $('#export').hide();
    $('#formRow').show();
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url: "/client/company/branch/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $('[name="id"]').val(data.id);
            $('[name="branch_name"]').val(data.branch_name);
            $('[name="branch_email"]').val(data.branch_email);
            $('[name="branch_mobile"]').val(data.branch_mobile);
            $('[name="mobile"]').val(data.alternate_mobile);
            $('[name="branch_address"]').val(data.branch_address)
            $('[name="branch_status"]').val(data.branch_status).trigger('change');
            // $('textarea#summernote').summernote('code', data.branch_address);
            $('[name="created_at"]').val(data.created_at);
            $('[name="updated_at"]').val(data.updated_at);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Update ' + data.branch_name); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}

function view_branch(id) {
    //Ajax Load data from ajax
    $.ajax({
        url: "/client/branches/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $('[name="id"]').val(data.id);
            $('[name="vbranch_code"]').val(data.branch_code);
            $('[name="vbranch_name"]').val(data.branch_name);
            $('[name="vbranch_email"]').val(data.branch_email);
            $('[name="vbranch_mobile"]').val(data.branch_mobile);
            $('[name="valternate_mobile"]').val(data.alternate_mobile);
            $('[name="vbranch_address"]').val(data.branch_address);
            $('[name="vbranch_status"]').val(data.branch_status);
            // $('textarea#viewSummernote').summernote('code', data.branch_address);
            $('[name="created_at"]').val(data.created_at);
            $('[name="updated_at"]').val(data.updated_at);
            $('#view_modal').modal('show');
            $('.modal-title').text('View ' + data.branch_name);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}

function delete_branch(id, name) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to recover branch " + name + "!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/client/company/branch/" + id,
                type: "DELETE",
                dataType: "JSON",
                success: function (data) {
                    if (data.status && data.error == null) {
                        Swal.fire("Success!", name + ' ' + data.messages, "success");
                        reload_table(tableId);
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

function bulk_deleteBranch() {
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
            text: 'You will not be able to recover selected ' + list_id.length + ' branch(es) once deleted!',
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
                    url: "/client/company/branches/bulk-delete",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status && data.error == null) {
                            Swal.fire("Success!", data.messages, "success");
                            reload_table(tableId);
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
        Swal.fire("Sorry!", "No branch selected :)", "error");
    }
}