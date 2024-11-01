var save_method;
var tableId= 'menusTable';
// dataTables url for branches
var tableDataUrl = "/admin/menus/get-menu-list/"+ id;
// dataTables column config for branches
var columnsConfig = [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "title" },
    { data: "parent_id" },
    { data: "url" },
    { data: "create" },
    { data: "import" },
    { data: "view" },
    { data: "update" },
    { data: "delete" },
    { data: "bulkDelete" },
    { data: "export" },
    { data: "action", orderable: false, searchable: false},
];
// dataTables buttons config for branches
var buttonsConfig = [];
// show create button
if (userPermissions.includes('create'+ title) || (userPermissions === '"all"')) {
    buttonsConfig.push({
        text: '<i class="fas fa-plus"></i>',
        className: 'btn btn-sm btn-info create' + title,
        attr: {
            id: 'create' + title
        },
        titleAttr: 'Add '+ title,
        action: function () {
            add_menu();
        }
    },);
}
// show bulk-delete
if (userPermissions.includes('bulkDelete'+ title) || (userPermissions === '"all"')) {
    buttonsConfig.push({
        text: '<i class="fa fa-trash"></i>',
        className: 'btn btn-sm btn-danger delete' + title,
        attr: {
            id: 'delete' + title
        },
        titleAttr: 'Bulky Delete '+ title,
        action: function () {
            bulk_deleteMenu();
        }
    });
}
// show reload table button by default
buttonsConfig.push({
    text: '<i class="fa fa-refresh"></i>',
    className: 'btn btn-sm btn-warning',
    titleAttr: 'Reload Table',
    action: function () {
        reload_table(tableId)
    }
},);
// show export buttons
if (userPermissions.includes('export'+ title) || userPermissions === '"all"') {
    buttonsConfig.push({
        extend: 'excel',
        className: 'btn btn-sm btn-success',
        titleAttr: 'Export '+ title +' Table Data To Excel',
        text: '<i class="fas fa-file-excel"></i>',
        filename: title +' Table Data',
        extension: '.xlsx',
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        },
    },
    {
        extend: 'pdf',
        className: 'btn btn-sm btn-danger',
        titleAttr: 'Export '+ title +' Table Data To PDF',
        text: '<i class="fas fa-file-pdf"></i>',
        filename: title +' Table Data',
        extension: '.pdf',
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        },
    },
    {
        extend: 'csv',
        className: 'btn btn-sm btn-success',
        titleAttr: 'Export '+ title +' Table Data To CSV',
        text: '<i class="fas fa-file-csv"></i>',
        filename: title +' Table Data',
        extension: '.csv',
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        },
    }, 
    {
        extend: 'copy',
        className: 'btn btn-sm btn-warning',
        titleAttr: 'Copy '+ title +' Table Data',
        text: '<i class="fas fa-copy"></i>',
        exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        },
    },
    {
        extend: 'print',
        title: "<h3 class='text-center text-bold'>" + businessName + "</h3><h4 class='text-center text-bold>"+title +" Table Data</h4><h5 class='text-center'>Printed On " + new Date().getHours() + " : " + new Date().getMinutes() + " " + new Date().toDateString() + "</h5>",
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
        },

        className: 'btn btn-sm btn-primary',
        titleAttr: 'Print '+ title +' Table Data',
        text: '<i class="fa fa-print"></i>',
        filename: title +' Table Data'
    });
}

$(document).ready(function() {
    // call to dataTable initialization function
    initializeDataTable(tableId, tableDataUrl, columnsConfig, buttonsConfig);
    //  load parent menus
    parent_menus();
});

function parent_menus(){
    if (userMenu.parents && userMenu.parents.length > 0) {
        console.log(userMenu.parents)
        $('select#parent_menu').html('<option value="0">Root</option>');
        $.each(userMenu.parents, function (index, item) {
            $("<option />").val(item['id'])
                .text(item['title'])
                .appendTo($('select#parent_menu'));
        });
    } else {
        $('select#parent_menu').html('<option value="">No Menu</option>');
    }
}

function allChecked(){
    if($("input#all").is(':checked')){
        $('input#create').attr('checked', true);
        $('input#import').attr('checked', true);
        $('input#view').attr('checked', true);
        $('input#update').attr('checked', true);
        $('input#delete').attr('checked', true);
        $('input#bulkDelete').attr('checked', true);
        $('input#export').attr('checked', true);
    }else{
        $('input#create').attr('checked', false);
        $('input#import').attr('checked', false);
        $('input#view').attr('checked', false);
        $('input#update').attr('checked', false);
        $('input#delete').attr('checked', false);
        $('input#bulkDelete').attr('checked', false);
        $('input#export').attr('checked', false);
    }
}

function add_menu() {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New Menu');
    $('[name="id"]').val(0);
    $('input#all').attr('checked', false);
    $('input#create').attr('checked', false);
    $('input#view').attr('checked', false);
    $('input#update').attr('checked', false);
    $('input#delete').attr('checked', false);
}

function save_menu() {
    mID = $('[name="id"]').val();
    $(".form-group").removeClass("has-error"); // clear error class
    $(".help-block").empty(); // clear error string
    $('#btnSubmit').text('submitting...'); //change button text
    $('#btnSubmit').attr('disabled', true); //set button disable 
    var url, method;
    if (save_method == 'add') {
        url = "/admin/menu";
    } else {
        url = "/admin/edit-menu/" + mID;
    }
    var formData = new FormData($('#form')[0]);
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            if(!data.inputerror) {
                if (data.status && data.error == null)
                {
                    $('#modal_form').modal('hide');
                    Swal.fire("Success!", data.messages, "success");
                    reload_table(tableId);
                } else if(data.error != null){
                    Swal.fire(data.error, data.messages, "error");
                } else{
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
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
            $('#btnSubmit').text('Submit'); 
            $('#btnSubmit').attr('disabled', false); 
        }
    });
}

function edit_menu(menu_id) {
    save_method = 'update';
    $('#export').hide();
    $('input#all').attr('checked', false);
    $('input#create').attr('checked', false);
    $('input#view').attr('checked', false);
    $('input#update').attr('checked', false);
    $('input#delete').attr('checked', false);
    $('input#all').attr('checked', false);
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url: "/admin/menu/" + menu_id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id"]').val(data.id);
            $('[name="title"]').val(data.title);
            $('[name="menu"]').val(data.menu);
            $('[name="parent_id"]').val(data.parent_id);
            $('[name="icon"]').val(data.icon);
            $('[name="order"]').val(data.order);
            $('[name="url"]').val(data.url);
            $('[name="status"]').val(data.status);
            if(data.create == 'on'){
                $('input#create').attr('checked', true);
            }
            if(data.import == 'on'){
                $('input#import').attr('checked', true);
            }
            if(data.view== 'on'){
                $('input#view').attr('checked', true);
            }
            if(data.update == 'on'){
                $('input#update').attr('checked', true);
            }
            if(data.delete == 'on'){
                $('input#delete').attr('checked', true);
            }
            if(data.bulkDelete == 'on'){
                $('input#bulkDelete').attr('checked', true);
            }
            if(data.export == 'on'){
                $('input#export').attr('checked', true);
            }
            if(data.create == 'on' && data.import == 'on' && data.view== 'on' && data.update == 'on' && data.delete == 'on' && data.bulkDelete == 'on' && data.export){
                $('input#all').attr('checked', true);
            }
            $('[name="created_at"]').val(data.created_at);
            $('[name="updated_at"]').val(data.updated_at);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Update ' + data.title); // Set title to Bootstrap modal title
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}

function view_menu(menu_id) {
    $('input#vcreate').attr('checked', false);
    $('input#vview').attr('checked', false);
    $('input#vupdate').attr('checked', false);
    $('input#vdelete').attr('checked', false);
    $.ajax({
        url: "/admin/menu/" + menu_id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id"]').val(data.id);
            $('[name="vicon"]').val(data.icon);
            $('[name="vtitle"]').val(data.title);
            $('[name="vmenu"]').val(data.menu);
            $('[name="vparent_id"]').val(data.parent_id);
            $('[name="vorder"]').val(data.order);
            $('[name="vurl"]').val(data.url);
            $('[name="vstatus"]').val(data.status);
            if(data.create == 'on'){
                $('input#vcreate').attr('checked', true);
            }
            if(data.import == 'on'){
                $('input#vimport').attr('checked', true);
            }
            if(data.view== 'on'){
                $('input#vview').attr('checked', true);
            }
            if(data.update == 'on'){
                $('input#vupdate').attr('checked', true);
            }
            if(data.delete == 'on'){
                $('input#vdelete').attr('checked', true);
            }
            if(data.bulkDelete == 'on'){
                $('input#vbulkDelete').attr('checked', true);
            }
            if(data.export == 'on'){
                $('input#vexport').attr('checked', true);
            }
            $('[name="created_at"]').val(data.created_at);
            $('[name="updated_at"]').val(data.updated_at);
            $('#view_modal').modal('show'); 
            $('.modal-title').text('View ' + data.title); 
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}

function delete_menu(id, name) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to recover menu "+ name +"!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/admin/menu/" + id,
                type: "DELETE",
                dataType: "JSON",
                success: function(data) {
                    if (data.status && data.error == null) {
                        Swal.fire("Success!", name + ' ' + data.messages, "success");
                        reload_table(tableId);
                    } else if(data.error != null){
                        Swal.fire(data.error, data.messages, "error");
                    } else{
                        Swal.fire('Error', "Something unexpected happened, try again later", "error");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire(textStatus, errorThrown, 'error');
                }
            });
        }
    })
}

function bulk_deleteMenu() {
    var list_id = [];
    $(".data-check:checked").each(function() {
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
            text: 'You will not be able to recover selected ' + list_id.length + ' menu(s) once deleted!',
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
                    url: "/menus/bulk-deleteMenu",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status && data.error == null) {
                            Swal.fire("Success!", data.messages, "success");
                            reload_table(tableId);
                        } else if(data.error != null){
                            Swal.fire(data.error, data.messages, "error");
                        } else{
                            Swal.fire('Error', "Something unexpected happened, try again later", "error");
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
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