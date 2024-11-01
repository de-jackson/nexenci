$(document).ready(function() {
    // auto generate menu
    menus();
    // load system settings
    if (settingsID == 0 && title.toLowerCase() != "settings") {
        window.location.assign("/admin/company/settings");
    } else {
        load_settings(settingsID);
    }
    // remove errors on input fields
    $("input").on("input",function() {
        $(this).parent().removeClass('has-error');
        $(this).next().empty();
    });
    // remove errors on select boxes
    $("select").on("input",function() {
        $(this).parent().removeClass('has-error');
        $(this).next().next().empty();
    });
    // remove errors on textareas
    $("textarea").on("input",function() {
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    //check all table inputs
    $("#check-all").click(function() {
        $(".data-check").prop('checked', $(this).prop('checked'));
    });
    // logged in user data
    loggedInUserData();
});

// get DatePicker
$(document).on("focus", ".getDatePicker", function () {
    $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        yearRange: "2020:2050",
    });
});

// load resources
$(function() {
    // Summernote editor
    $('#summernote').summernote({
        placeholder: 'Enter Text Here...',
        tabsize: 2,
        height: 100
    })
    $('#newSummernote').summernote({
        placeholder: 'Enter Text Here...',
        tabsize: 2,
        height: 100
    })
    $('#addSummernote').summernote({
        placeholder: 'Enter Text Here...',
        tabsize: 2,
        height: 100
    })
    $('#viewSummernote').summernote({
        placeholder: 'Display Text Here...',
        tabsize: 2,
        height: 100
    })
    $('#seeSummernote').summernote({
        placeholder: 'Display Text Here...',
        tabsize: 2,
        height: 100
    })
    //Initialize Select2 Elements
    $(".select2").select2();
    $(".select2bs4").select2({
        theme: "bootstrap4",
    });

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {
        placeholder: "dd/mm/yyyy"
    });
    $("#datemask2").inputmask("mm/dd/yyyy", {
        placeholder: "mm/dd/yyyy"
    });
    //Money Euro
    $("[data-mask]").inputmask();

});
// logged in user data
function loggedInUserData() {
    $.ajax({
        url: "/admin/user/" + userID,
        type: "GET",
        async: false,
        dataType: "JSON",
        success: function(data) {
            userPermissions = data.allowed;
            // hide\show actions based on user permission
            if (userPermissions.includes('create' + title) || userPermissions.includes('all')) {
                $('button.create'+title).show()
                $('button.import'+title).show()
            } else {
                $('button.create'+title).hide()
                $('button.import'+title).hide()
            }
            if (userPermissions.includes('update' + title) || userPermissions.includes('all')) {
                $('button.update' + title).show()
                $('a.update' + title).show()
            } else {
                $('button.update' + title).hide()
                $('a.update' + title).show()
            }
            if (userPermissions.includes('delete' + title) || userPermissions.includes('all')) {
                $('button.delete' + title).show()
            } else {
                $('button.delete' + title).hide()
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}
// load side bar menu
function menus() {
    // logged in user data
    $.ajax({
        url: "/admin/user/" + userID,
        type: "GET",
        async: false,
        dataType: "JSON",
        success: function(loggedInUser) {
            userPermissions = loggedInUser.allowed;
            $.ajax({
                url: "/menu/load-menu",
                type: "GET",
                async: false,
                dataType: "JSON",
                success: function(data) {
                    var html = child = '';
                    if (data.length > 0) {
                        var parent = 0; // default parent tab
                        for (var i = 0; i < data.length; i++) {
                            // assing classes to parent navs
                            if (menu.toLowerCase() == data[i].menu.toLowerCase() && data[i].url == 'javascript: void(0)') {
                                dropNav = ' nav-item menu-open';
                            } else {
                                dropNav = ' nav-item';
                            }
                            // assing classes to non-parent navs
                            if (menu.toLowerCase() == data[i].menu) {
                                parentNav = 'nav-link active';
                            } else {
                                parentNav = 'nav-link';
                            }
                            // load non-children navs
                            if (data[i].parent_id == 0) {
                                // load parent navs with children
                                if (data[i].url == 'javascript: void(0)') {
                                    // dispay menu if user has permission to view it
                                    if (userPermissions.includes('create' + data[i].title) || userPermissions.includes('view' + data[i].title) || userPermissions.includes('update' + data[i].title) || userPermissions.includes('delete' + data[i].title) || userPermissions.includes('all')) {
                                        html += '<li class="' + dropNav + '">' +
                                            '<a href="/' + data[i].url + '" class="' + parentNav + '">' +
                                            '<i class="' + data[i].icon + '"></i> ' +
                                            '<p> ' +
                                            data[i].title +
                                            '<i class="fas fa-angle-left right"></i>' +
                                            '</p>' +
                                            '</a>' +
                                            '<ul class="nav nav-treeview">';
                                        // load children navs
                                        $.ajax({
                                            url: "/menus/child-menu/" + data[i].id,
                                            type: "GET",
                                            async: false,
                                            dataType: "JSON",
                                            success: function(results) {
                                                if (results.length > 0) {
                                                    for (var j = 0; j < results.length; j++) {
                                                        if (results[j].parent_id == data[i].id) {
                                                            if (currentURL == (baseURL + results[j].url)) {
                                                                childNav = 'nav-link active';
                                                            } else {
                                                                childNav = 'nav-link';
                                                            }
                                                            // dispay menu if user has permission to view it
                                                            if (userPermissions.includes('create' + results[j].title) || userPermissions.includes('view' + results[j].title) || userPermissions.includes('update' + results[j].title) || userPermissions.includes('delete' + results[j].title) || userPermissions.includes('all')) {
                                                                html += '<li class="nav-item">' +
                                                                    '<a href="/' + results[j].url + '" class="' + childNav + '">' +
                                                                    '<i class="' + results[j].icon + ' nav-icon"></i>' +
                                                                    '<p>' + results[j].title + '</p>' +
                                                                    ' </a>' +
                                                                    '</li>';
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                        html += '</ul>' +
                                            ' </li>';
                                    }
                                }
                                // load parent navs without children
                                else {
                                    // dispay menu if user has permission to view it
                                    if (userPermissions.includes('create' + data[i].title) || userPermissions.includes('view' + data[i].title) || userPermissions.includes('update' + data[i].title) || userPermissions.includes('delete' + data[i].title) || userPermissions.includes('all')) {
                                        html += '<li class="nav-item">' +
                                            '<a href="/' + data[i].url + '" class="' + parentNav + '">' +
                                            '<i class="' + data[i].icon + '"></i> ' +
                                            '<p> ' + data[i].title + '</p>' +
                                            '</a>' +
                                            '</li>';
                                    }
                                }
                            }
                        }
                        // load logout tab
                        html += '<li class="nav-item">' +
                            '<a href="/logout" class="nav-link">' +
                            '<p class="text-danger">' +
                            '<i class="fas fa-sign-out nav-icon"></i>' +
                            'Sign Out' +
                            '</p>' +
                            '</a>' +
                            '</li>';
                    }
                    // attach generated tobs to side menu
                    $('ul#menus').html(html);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Loading menu Failed!")
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}
// search through menu
function menuSearch(str) {
    if (str.length != 0) {
        $.ajax({
            url: "/menus/search-menu/" + str,
            dataType: "GET",
            dataType: "JSON",
            success: function(data) {
                if (data.length > 0) {
                    var results = '';
                    for (var i = 0; i < data.length; i++) {
                        results += '<ul class="nav nav-pills nav-sidebar">' +
                            '<li class="nav-item text-mute">' +
                            '<a href="/' + data[i].url + '" class="' + parentNav + '">' +
                            '<p> ' + data[i].title + '</p>' +
                            '</a>' +
                            '</li>'
                        '</ul>';
                    }
                }
                $("#searchHint").html(results);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Search Menu Failed!");
            }
        });
    } else {
        $("#searchHint").html('');
    }
}
// load settings
function load_settings(id) {
    //Ajax Load data from ajax
    $.ajax({
        url: "/admin/company/settings/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('span#businessName').text(data.business_name);
            $('span#systemName').text(data.system_name);
            if (data.business_logo && (imageExists('/uploads/logo/' + data.business_logo))) {
                $('a#logo').html('<img src="/uploads/logo/' + data.business_logo + '" alt="Logo" class="brand-image img-center" style="height: 100px; width: 100px; opacity: 0.8;">');
            } else {
                $('a#logo').html('<img src="/assets/dist/img/default.jpg" alt="Logo" class="brand-image img-center" style="height: 100px; width: 100px; opacity: 0.8;">');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}
// reload datatable ajax
function reload_table(table) {
    $("#"+table).DataTable().ajax.reload();
    /* This is to uncheck the column header check box */
    $('input[type=checkbox]').each(function() {
        this.checked = false;
    });
}
// get\show selected branches
function selectBranch(branch_id = null) {
    if(!branch_id){
        $.ajax({
            url: "/admin/branches/get-branches",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('select#branch_id').html('<option value="">-- select --</option>');
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $("<option />").val(data[i].id)
                            .text(data[i].branch_name)
                            .appendTo($('select#branch_id'));
                    }
                } else {
                    $('select#branch_id').html('<option value="">No Branch</option>');
                }

            },
            error: function(err) {
                $('select#branch_id').html('<option value="">Error Occured</option>');
            }
        });
    } else{
        $.ajax({
            url: "/admin/branches/get-branches",
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                if (response.length > 0) {
                    $('select#branch_id').find('option').not(':first').remove();
                    // Add options
                    $.each(response, function(index, data) {
                        if (data['id'] == branch_id) {
                            var selection = 'selected';
                        } else {
                            var selection = '';
                        }
                        $('select#branch_id').append('<option value="' + data['id'] + '" ' + selection + '>' + data['branch_name'] + '</option>');
                    });
                } else {
                    $('select#branch_id').html('<option value="">No Branch</option>');
                }
            }
        });
    }
}
// get\show selected departments
function selectDepartment(department_id = null) {
    if(!department_id){
        $.ajax({
            url: "/admin/departments/all-departments",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                $('select#department_id').html('<option value="">-- select --</option>');
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $("<option />").val(data[i].id)
                            .text(data[i].department_name)
                            .appendTo($('select#department_id'));
                    }
                } else {
                    $('select#department_id').html('<option value="">No Department</option>');
                }
            },
            error: function(err) {
                $('select#subcategory_id').html('<option value="">Error Occured</option>');
            }
        });
    } else{
        $.ajax({
            url: "/admin/departments/all-departments",
            type: "POST",
            dataType: "JSON",
            success: function(response) {
                if (response.length > 0) {
                    $('#department_id').find('option').not(':first').remove();
                    // Add options
                    $.each(response, function(index, data) {
                        if (data['id'] == department_id) {
                            var selection = 'selected';
                        } else {
                            var selection = '';
                        }
                        $('#department_id').append('<option value="' + data['id'] + '" ' + selection + '>' + data['department_name'] + '</option>');
                    });
                } else {
                    $('select#department_id').html('<option value="">No Department</option>');
                }
            }
        });
    }
}
// all positions for a department
$('select#department_id').on('change', function() {
    var department_id = this.value;
    if (department_id == 0 || department_id == '') {
        $('select#position_id').html('<option value="">-- select department--</option>');
    } else {
        // select positions from db
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: '/admin/positions/department-positions/' + department_id,
            success: function(data) {
                $('select#position_id').html('<option value="">-- select --</option>');
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $("<option />").val(data[i].id)
                            .text(data[i].position)
                            .appendTo($('select#position_id'));
                    }
                } else {
                    $('select#position_id').html('<option value="">No Position</option>');
                }

            },
            error: function(err) {
                $('select#position_id').html('<option value="">Error Occured</option>');
            }
        });
    }
});
// get\show selected currencies
function selectCurrency(currency_id = null) {
    if(!currency_id || currency_id == null){
        $.ajax({
            url: "/admin/settings/get-currencies",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('select#currency_id').html('<option value="">-- select --</option>');
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $("<option />").val(data[i].id)
                            .text(data[i].symbol +" ~ "+ data[i].currency)
                            .appendTo($('select#currency_id'));
                    }
                } else {
                    $('select#currency_id').html('<option value="">No Currency</option>');
                }
            },
            error: function(err) {
                $('select#currency_id').html('<option value="">Error Occured</option>');
            }
        });
    } else{
        $.ajax({
            url: "/admin/settings/get-currencies",
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                if (response.length > 0) {
                    $('select#currency_id').find('option').not(':first').remove();
                    // Add options
                    $.each(response, function(index, data) {
                        if (data['id'] == currency_id) {
                            var selection = 'selected';
                        } else {
                            var selection = '';
                        }
                        $('select#currency_id').append('<option value="' + data['id'] + '" ' + selection + '>' + data['symbol'] +" ~ "+ data['currency']  + '</option>');
                    });
                } else {
                    $('select#currency_id').html('<option value="">No currency</option>');

                }
            }
        });
    }
}
// get\show selected clients
function selectClient(client_id = null) {
    if(!client_id){
        $.ajax({
            url: "/admin/clients/all-clients",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('select#client_id').html('<option value="">-- select --</option>');
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $("<option />").val(data[i].id)
                            .text(data[i].name)
                            .appendTo($('select#client_id'));
                    }
                } else {
                    $('select#client_id').html('<option value="">No client</option>');
                }
            },
            error: function(err) {
                $('select#client_id').html('<option value="">Error Occured</option>');
            }
        });
    }else{
        $.ajax({
            url: "/admin/clients/all-clients",
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                if (response.length > 0) {
                    $('select#client_id').find('option').not(':first').remove();
                    // Add options
                    $.each(response, function(index, data) {
                        if (data['id'] == client_id) {
                            var selection = 'selected';
                        } else {
                            var selection = '';
                        }
                        $('select#client_id').append('<option value="' + data['id'] + '" ' + selection + '>' + data['name'] + '</option>');
                    });
                } else {
                    $('select#client_id').html('<option value="">No Client</option>');
                }
            }
        });
    }
}
// get loan products
function selectProduct(product_id = null) {
    if(!product_id){
        $.ajax({
            url: "/admin/loans/all-products",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('select#product_id').html('<option value="">-- select --</option>');
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $("<option />").val(data[i].id)
                            .text(data[i].product_name)
                            .appendTo($('select#product_id'));
                    }
                } else {
                    $('select#product_id').html('<option value="">No Product</option>');
                }
            },
            error: function(err) {
                $('select#product_id').html('<option value="">Error Occured</option>');
            }
        });
    } else{
        $.ajax({
            url: "/admin/loans/all-products",
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                if (response.length > 0) {
                    $('select#product_id').find('option').not(':first').remove();
                    // Add options
                    $.each(response, function(index, data) {
                        if (data['id'] == product_id) {
                            var selection = 'selected';
                        } else {
                            var selection = '';
                        }
                        $('select#product_id').append('<option value="' + data['id'] + '" ' + selection + '>' + data['product_name'] + '</option>');
                    });
                } else {
                    $('select#product_id').html('<option value="">No Product</option>');

                }
            }
        });
    }
}

// permisions
var permissions = function menus_permissions(permissions, action = 'add', page = 'user') {
    $.ajax({
        url: "/menu/load-menu",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            var html = inptCreate = inptView = inptUpdate = inptDelete = inptAll = '';
            if (data.length > 0) {
                html += '<table class="table table-sm table-striped table-hover">' +
                    '<thead>' +
                    '<tr class="text-center">' +
                    '<th>Module</th><th>Create</th><th>View</th>' +
                    '<th>Update</th><th>Delete</th>';
                if (action != 'view') {
                    html += '<th>All</th>'
                }
                html += '</tr>' +
                    '</thead>' +
                    '<tbody>';
                for (var i = 0; i < data.length; i++) {
                    if (data[i].create != null || data[i].view != null || data[i].update != null || data[i].delete != null) {
                        if (data[i].create != null) {
                            if (permissions != null && (permissions.includes('create' + data[i].title) || permissions.includes('all'))) {
                                inptCreate = '<input type="checkbox" name="permissions[]" value="create' + data[i].title + '" id="create' + data[i].title + '" class="form-control form-control-sm ID' + data[i].id + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" checked>';
                            } else {
                                inptCreate = '<input type="checkbox" name="permissions[]" value="create' + data[i].title + '" id="create' + data[i].title + '" class="form-control form-control-sm ID' + data[i].id + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')">';
                            }
                        } else {
                            inptCreate = '-';
                        }
                        if (data[i].view != null) {
                            if (permissions != null && (permissions.includes('view' + data[i].title) || permissions.includes('all'))) {
                                inptView = '<input type="checkbox" name="permissions[]" value="view' + data[i].title + '" id="view' + data[i].title + '" class="form-control form-control-sm ID' + data[i].id + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" checked>';
                            } else {
                                inptView = '<input type="checkbox" name="permissions[]" value="view' + data[i].title + '" id="view' + data[i].title + '" class="form-control form-control-sm ID' + data[i].id + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')">';
                            }
                        } else {
                            inptView = '-';
                        }
                        if (data[i].update != null) {
                            if (permissions != null && (permissions.includes('update' + data[i].title) || permissions.includes('all'))) {
                                inptUpdate = '<input type="checkbox" name="permissions[]" value="update' + data[i].title + '" id="update' + data[i].title + '" class="form-control form-control-sm ID' + data[i].id + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" checked>';
                            } else {
                                inptUpdate = '<input type="checkbox" name="permissions[]" value="update' + data[i].title + '" id="update' + data[i].title + '" class="form-control form-control-sm ID' + data[i].id + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')">';
                            }
                        } else {
                            inptUpdate = '-';
                        }
                        if (data[i].delete != null) {
                            if (permissions != null && (permissions.includes('delete' + data[i].title) || permissions.includes('all'))) {
                                inptDelete = '<input type="checkbox" name="permissions[]" value="delete' + data[i].title + '" id="delete' + data[i].title + '" class="form-control form-control-sm ID' + data[i].id + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" checked>';
                            } else {
                                inptDelete = '<input type="checkbox" name="permissions[]" value="delete' + data[i].title + '" id="delete' + data[i].title + '" class="form-control form-control-sm ID' + data[i].id + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')">';
                            }
                        } else {
                            inptDelete = '-';
                        }
                        if (data[i].create == null && data[i].view == null && data[i].update == null && data[i].delete == null) {
                            inptAll = '-';
                        } else {
                            if (permissions != null && ((permissions.includes('create' + data[i].title) && permissions.includes('view' + data[i].title) && permissions.includes('update' + data[i].title) && permissions.includes('delete' + data[i].title)) || permissions.includes('all'))) {
                                inptAll = '<input type="checkbox" id="all' + data[i].title + '" class="form-control form-control-sm" onclick="checkALL(' + "'" + data[i].title + "'" + ',' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" checked>';
                            } else {
                                inptAll = '<input type="checkbox" id="all' + data[i].title + '" class="form-control form-control-sm" onclick="checkALL(' + "'" + data[i].title + "'" + ',' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')">';
                            }
                        }
                        // table rows
                        if(page.toLowerCase() != 'profile'){
                            html += '<tr>' +
                                '<td>' + data[i].title + '</td>' +
                                '<td align="center">' + inptCreate + '</td>' +
                                '<td align="center">' + inptView + '</td>' +
                                '<td align="center">' + inptUpdate + '</td>' +
                                '<td align="center">' + inptDelete + '</td>';
                            if (action != 'view') {
                                html += '<td align="center">' + inptAll + '</td>';
                            }
                            html += '</tr>';
                        } else{
                            if(permissions.includes('create' + data[i].title) || permissions.includes('view' + data[i].title) || permissions.includes('update' + data[i].title) || permissions.includes('delete' + data[i].title) || permissions.includes('all')){
                                html += '<tr>' +
                                    '<td>' + data[i].title + '</td>' +
                                    '<td align="center">' + inptCreate + '</td>' +
                                    '<td align="center">' + inptView + '</td>' +
                                    '<td align="center">' + inptUpdate + '</td>' +
                                    '<td align="center">' + inptDelete + '</td>';
                                if (action != 'view') {
                                    html += '<td align="center">' + inptAll + '</td>';
                                }
                                html += '</tr>';
                            }
                        }
                    }
                }
                html += '</tbody>' +
                    '<tfoot>' +
                    '<tr class="text-center">' +
                    '<th>Module</th><th>Create</th><th>View</th>' +
                    '<th>Update</th><th>Delete</th>';
                if (action != 'view') {
                    html += '<th>All</th>'
                }
                html += '</tr>' +
                    '</tfoot>' +
                    '</table>';
            } else {
                html += '<p>No data found</p>';
            }
            if (action == 'view') {
                $('div#viewPermissions').html(html);
                $("td input").attr('disabled', true)
            } else {
                $('div#permissions').html(html);
            }
            $('input#viewDashboard').attr("checked", true);
            $('input#viewDashboard').click(function() {
                $('input#viewDashboard').prop("checked", true);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}
// check if image exists
var imageExists = function(imgUrl) {
    if (!imgUrl) {
        return false;
    }
    return new Promise(res => {
        const image = new Image();
        image.onload = () => res(true);
        image.onerror = () => res(false);
        image.src = imgUrl;
    });
}
// check all permissions
function checkALL(menu, id, parent_id) {
    if (menu != null) {
        if ($("input#all" + menu).is(':checked')) {
            if ($('input#create' + menu).not(':checked')) {
                $('input#create' + menu).attr('checked', true);
            }
            if ($('input#view' + menu).not(':checked')) {
                $('input#view' + menu).attr('checked', true);
            }
            if ($('input#update' + menu).not(':checked')) {
                $('input#update' + menu).attr('checked', true);
            }
            if ($('input#delete' + menu).not(':checked')) {
                $('input#delete' + menu).attr('checked', true);
            }
        } else {
            if ($('input#create' + menu).is(':checked')) {
                $('input#create' + menu).attr('checked', false);
            }
            if ($('input#view' + menu).is(':checked')) {
                $('input#view' + menu).attr('checked', false);
            }
            if ($('input#update' + menu).is(':checked')) {
                $('input#update' + menu).attr('checked', false);
            }
            if ($('input#delete' + menu).is(':checked')) {
                $('input#delete' + menu).attr('checked', false);
            }
        }
    }
    $('.ID' + parent_id).prop("checked", false);
    checkParent(id, parent_id)
}
// check parent if child is checked
function checkParent(id, parent_id) {
    if (parent_id != 0) {
        if ($(".ID" + id).is(':checked')) {
            $('.ID' + parent_id).prop("checked", true);
            $('.ID' + parent_id).click(function() {
                $('.ID' + parent_id).prop("checked", true);
            });
        } else {
            $('.ID' + parent_id).prop("checked", false);
            $('.ID' + parent_id).click(function() {
                $('.ID' + parent_id).prop("checked", false);
            });
        }
    }
}
// Image preview
var previewImageFile = function(event) {
    var output = document.getElementById('preview-image');
    output.removeAttribute("class");
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
        URL.revokeObjectURL(output.src)
    }
};
var previewIdImage = function(event) {
    var output = document.getElementById('preview-id');
    output.removeAttribute("class");
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
        URL.revokeObjectURL(output.src)
    }
};
var previewSignature = function(event) {
    var output = document.getElementById('preview-sign');
    output.removeAttribute("class");
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
        URL.revokeObjectURL(output.src)
    }
};
// category subcategories
function subcategories(category_id) {
    if (category_id == '0' || category_id == '') {
        $('select#subcategory_id').html('<option value="">-- no category--</option>');
    } else {
        // select subcategories from db
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: '/admin/accounts/subcategories/' + category_id,
            success: function(data) {
                $('select#subcategory_id').html('<option value="">-- select --</option>');
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $("<option />").val(data[i].id)
                            .text(data[i].subcategory_name)
                            .appendTo($('select#subcategory_id'));
                    }
                } else {
                    $('select#subcategory_id').html('<option value="">No Subcategory</option>');
                }
            },
            error: function(err) {
                $('select#subcategory_id').html('<option value="">Error Occured</option>');
            }
        });
    }
}
// position belonging to a department[]
function department_position(department_id, position_id) {
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "/admin/departments/all-departments",
        success: function(response) {
            if (response.length > 0) {
                $('#department_id').find('option').not(':first').remove();
                // Add options
                $.each(response, function(index, data) {
                    if (data['id'] == department_id) {
                        var selection = 'selected';
                    } else {
                        var selection = '';
                    }
                    $('#department_id').append('<option value="' + data['id'] + '" ' + selection + '>' + data['department_name'] + '</option>');
                });
            } else {
                $('select#department_id').html('<option value="">No Department</option>');
            }
        }
    });

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "/admin/positions/department-positions/" + department_id,
        success: function(response) {
            if (response.length > 0) {
                $('#position_id').find('option').not(':first').remove();
                $.each(response, function(index, data) {
                    if (data['id'] == position_id) {
                        var selection = 'selected';
                    } else {
                        var selection = '';
                    }
                    $('#position_id').append('<option value="' + data['id'] + '" ' + selection + '>' + data['position'] + '</option>');
                });
            } else {
                $('select#position_id').html('<option value="">No Position</option>');
            }
        }
    });
}

function generateGender() {
    $.ajax({
        url: "/admin/reports/module/types/gender",
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            $("select#gender").html('<option value="">-- Select --</option>');
            // convert object to key's array
            const keys = Object.keys(data);
            if (keys.length > 0) {
                for (let index in keys) {
                    $("<option />").val(keys[index]).text(keys[index]).appendTo($("select#gender"));
                }
            } else {
                $("select#gender").html('<option value="">No Gender</option>');
            }
        },
        error: function (err) {
            $("select#gender").html('<option value="">Error Occured</option>');
        },
    });
}

function generateStatus() {
    $.ajax({
        url: "/admin/reports/module/types/accountstatus",
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            $("select#status").html('<option value="">-- Select --</option>');
            // convert object to key's array
            const keys = Object.keys(data);
            if (keys.length > 0) {
                for (let index in keys) {
                    $("<option />").val(keys[index]).text(keys[index]).appendTo($("select#status"));
                }
            } else {
                $("select#status").html('<option value="">No Status</option>');
            }
        },
        error: function (err) {
            $("select#status").html('<option value="">Error Occured</option>');
        },
    });
}

function generateStaff(staff_id = null) {
    $.ajax({
        url: "/admin/reports/module/types/staff",
        type: "POST",
        dataType: "JSON",
        success: function(response) {
            if (response.length > 0) {
                $('select#staff_id').find('option').not(':first').remove();
                // Add options
                $.each(response, function(index, data) {
                    if (staff_id && data['id'] == staff_id) {
                        var selection = 'selected';
                    } else {
                        var selection = '';
                    }
                    $('select#staff_id').append('<option value="' + data['id'] + '" ' + selection + '>' + data['staff_name'] + " ["+ data['staffID'] +"]" + '</option>');
                });
            } else {
                $('select#staff_id').html('<option value="">No Staff</option>');

            }
        }
    });
}

function generateAppointmentType() {
    $.ajax({
        url: "/admin/reports/module/types/appointmenttypes",
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            $("select#appointment_type").html('<option value="">Select Appointment Type</option>');
            // convert object to key's array
            const keys = Object.keys(data);
            if (keys.length > 0) {
                for (let index in keys) {
                    $("<option />").val(keys[index]).text(keys[index]).appendTo($("select#appointment_type"));
                }
            } else {
                $("select#appointment_type").html('<option value="">No Appointment Type</option>');
            }
        },
        error: function (err) {
            $("select#appointment_type").html('<option value="">Error Occured</option>');
        },
    });
}

function generateStaffAccountTypes() {
    $.ajax({
        url: "/admin/reports/module/types/staffaccounttypes",
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            $("select#staff_account_type").html('<option value="">Select Account Type</option>');
            // convert object to key's array
            const keys = Object.keys(data);
            if (keys.length > 0) {
                for (let index in keys) {
                    $("<option />").val(keys[index]).text(keys[index]).appendTo($("select#staff_account_type"));
                }
            } else {
                $("select#staff_account_type").html('<option value="">No Account Type</option>');
            }
        },
        error: function (err) {
            $("select#staff_account_type").html('<option value="">Error Occured</option>');
        },
    });
}

function generateCustomeSettings(menu) {
    $.ajax({
        url: "/admin/reports/module/types/"+menu,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            $("select#"+menu).html('<option value="">Select '+menu+'</option>');
            // convert object to key's array
            const keys = Object.keys(data);
            if (keys.length > 0) {
                for (let index in keys) {
                    $("<option />").val(keys[index]).text(keys[index]).appendTo($("select#"+menu));
                }
            } else {
                $("select#"+menu).html('<option value="">No '+menu+'</option>');
            }
        },
        error: function (err) {
            $("select#"+menu).html('<option value="">Error Occured</option>');
        },
    });
}

function textEditor() {
    /* Summernote Validation */

    var summernoteForm = $('.form-horizontal');
    var summernoteElement = $('.summernote');

    var summernoteValidator = summernoteForm.validate({
        errorElement: "div",
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        ignore: ':hidden:not(.summernote),.note-editable.card-block',
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("invalid-feedback");
            // console.log(element);
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.siblings("label"));
            } else if (element.hasClass("summernote")) {
                error.insertAfter(element.siblings(".note-editor"));
            } else {
                error.insertAfter(element);
            }
        }
    });

    summernoteElement.summernote({
        height: 300,
        callbacks: {
            onChange: function (contents, $editable) {
        // Note that at this point, the value of the `textarea` is not the same as the one
        // you entered into the summernote editor, so you have to set it yourself to make
        // the validation consistent and in sync with the value.
        summernoteElement.val(summernoteElement.summernote('isEmpty') ? "" : contents);

        // You should re-validate your element after change, because the plugin will have
        // no way to know that the value of your `textarea` has been changed if the change
        // was done programmatically.
                summernoteValidator.element(summernoteElement);
            }
        }
    });
}

// auto disbursement days
function auto_update_disbursementDays() {
    $.ajax({
        url: '/admin/loans/auto-updateDays',
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            console.log(data.message);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Disbursement Status Update Error: "+ errorThrown);
        }
    });
}
// auto disbursement balances
function auto_update_disbursementBalances() {
    $.ajax({
        url: '/admin/loans/auto-updateBalance',
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            console.log(data.message);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Disbursement Total Collected Update Error: "+ errorThrown);
        }
    });
}
// auto calculate & update total debit & credit for particulars
function auto_update_partilarBalances() {
    $.ajax({
        url: '/admin/accounts/particular-totals',
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            console.log(data.message);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Particular Total Debit & Credit Update Error: "+ errorThrown);
        }
    });
}
// auto calculate & update account balance for clients
function auto_update_clientSavingsBalances() {
    $.ajax({
        url: '/admin/clients/update-savings',
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            console.log(data.message);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Client Account Balance Update Error: "+ textStatus +" "+ errorThrown);
        }
    });
}
// make first letter on a word capital
function capitalizeFirstLetter(word) {
    return word.charAt(0).toUpperCase() + word.slice(1);
  }