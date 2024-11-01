// view read only permissions
let show_userPermissions = () => {
    $.ajax({
        url: "/admin/menus/menu-permissions",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            var html = inptCreate = inptImport = inptView = inptUpdate = inptDelete = inptBulkDel = inptExport = inptAll = '';
            if (data.length > 0) {
                var html = '';
                for (var i = 0; i < data.length; i++) {
                    if (data[i].create != null || data[i].import != null || data[i].view != null || data[i].update != null || data[i].delete != null || data[i].bulkDelete != null || data[i].export != null) {
                        // create permissions
                        if (data[i].create != null) {
                            if (permissions != null && (permissions.includes('create_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptCreate = '<span class="badge badge-rounded badge-outline-success">Yes</span>';
                            } else {
                                inptCreate = '<span class="badge badge-rounded badge-outline-danger">No</span>';
                            }
                        } else {
                            inptCreate = '<span class="badge badge-rounded badge-outline-warning">Null</span>';
                        }
                        // import permissions
                        if (data[i].import != null) {
                            if (permissions != null && (permissions.includes('import_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptImport = '<span class="badge badge-rounded badge-outline-success">Yes</span>';
                            } else {
                                inptImport = '<span class="badge badge-rounded badge-outline-danger">No</span>';
                            }
                        } else {
                            inptImport = '<span class="badge bg-outline-warning">Null</span>';
                        }
                        // view permissions
                        if (data[i].view != null) {
                            if (permissions != null && (permissions.includes('view_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptView = '<span class="badge badge-rounded badge-outline-success">Yes</span>';
                            } else {
                                inptView = '<span class="badge bg-outline-danger">No</span>';
                            }
                        } else {
                            inptView = '<span class="badge badge-rounded badge-outline-warning">Null</span>';
                        }
                        // update permissions
                        if (data[i].update != null) {
                            if (permissions != null && (permissions.includes('update_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptUpdate = '<span class="badge badge-rounded badge-outline-success">Yes</span>';
                            } else {
                                inptUpdate = '<span class="badge bg-outline-danger">No</span>';
                            }
                        } else {
                            inptUpdate = '<span class="badge bg-outline-warning">Null</span>';
                        }
                        // delete permissions
                        if (data[i].delete != null) {
                            if (permissions != null && (permissions.includes('delete_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptDelete = '<span class="badge badge-rounded badge-outline-success">Yes</span>';
                            } else {
                                inptDelete = '<span class="badge bg-outline-danger">No</span>';
                            }
                        } else {
                            inptDelete = '<span class="badge bg-outline-warning">Null</span>';
                        }
                        // bulk-delete permissions
                        if (data[i].bulkDelete != null) {
                            if (permissions != null && (permissions.includes('bulkDelete_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptBulkDel = '<span class="badge badge-rounded badge-outline-success">Yes</span>';
                            } else {
                                inptBulkDel = '<span class="badge bg-outline-danger">No</span>';
                            }
                        } else {
                            inptBulkDel = '<span class="badge bg-outline-warning">Null</span>';
                        }
                        // export permissions
                        if (data[i].export != null) {
                            if (permissions != null && (permissions.includes('export_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptExport = '<span class="badge badge-rounded badge-outline-success">Yes</span>';
                            } else {
                                inptExport = '<span class="badge badge-rounded badge-outline-danger">No</span>';
                            }
                        } else {
                            inptExport = '<span class="badge badge-rounded badge-outline-warning">Null</span>';
                        }
                        // table rows
                        html += '<tr>' +
                            '<td scope="row">' + data[i].slug + '</td>' +
                            '<td>' + inptCreate + '</td>' +
                            '<td>' + inptImport + '</td>' +
                            '<td>' + inptView + '</td>' +
                            '<td>' + inptUpdate + '</td>' +
                            '<td>' + inptDelete + '</td>'+
                            '<td>' + inptBulkDel + '</td>'+
                            '<td>' + inptExport + '</td>'+
                       '</tr>';
                    }
                }
            } else {
                html += '<tr><td class="text-center" colspan="8">No data found</td></tr>';
            }
            $("tbody#permissions").html(html);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}

// load permissions
let load_permissions = (permissions, action = 'add') => {
    $.ajax({
        url: "/admin/menus/menu-permissions",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            var html = inptCreate = inptImport = inptView = inptUpdate = inptDelete = inptBulkDel = inptExport = inptAll = '';
            if (data.length > 0) {
                html += '<table class="table table-sm table-striped table-hover">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>Module</th><th class="text-center">Create</th><th class="text-center">Import</th><th class="text-center">View</th>' +
                    '<th class="text-center">Edit</th><th class="text-center">Delete</th><th class="text-center">Bulk-Del</th><th class="text-center">Export</th>';
                // if (action != 'view') {
                //     html += '<th class="text-center">All</th>'
                // }
                html += '</tr>' +
                    '</thead>' +
                    '<tbody>';
                for (var i = 0; i < data.length; i++) {
                    if (data[i].create != null || data[i].view != null || data[i].update != null || data[i].delete != null) {
                        // create permissions
                        if (data[i].create != null) {
                            if (permissions != null && (permissions.includes('create_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptCreate = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="create_' + data[i].menu + data[i].slug + '" id="create_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '" checked>'+
                                '</div>';
                            } else {
                                inptCreate = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="create_' + data[i].menu + data[i].slug + '" id="create_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '">'+
                                '</div>';
                            }
                        } else {
                            inptCreate = '<span class="badge badge-rounded badge-outline-warning">Null</span>';
                        }
                        // import permissions
                        if (data[i].import != null) {
                            if (permissions != null && (permissions.includes('import_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptImport = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="import_' + data[i].menu + data[i].slug + '" id="import_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '" checked>'+
                                '</div>';
                            } else {
                                inptImport = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="import_' + data[i].menu + data[i].slug + '" id="import_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '">'+
                                '</div>';
                            }
                        } else {
                            inptImport = '<span class="badge badge-rounded badge-outline-warning">Null</span>';
                        }
                        // view permissions
                        if (data[i].view != null) {
                            if (permissions != null && (permissions.includes('view_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptView = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="view_' + data[i].menu + data[i].slug + '" id="view_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '" checked>'+
                                '</div>';
                            } else {
                                inptView = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="view_' + data[i].menu + data[i].slug + '" id="view_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '">'+
                                '</div>';
                            }
                        } else {
                            inptView = '<span class="badge badge-rounded badge-outline-warning">Null</span>';
                        }
                        // update permissions
                        if (data[i].update != null) {
                            if (permissions != null && (permissions.includes('update_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptUpdate = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="update_' + data[i].menu + data[i].slug + '" id="update_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '" checked>'+
                                '</div>';
                            } else {
                                inptUpdate = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="update_' + data[i].menu + data[i].slug + '" id="update_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '">'+
                                '</div>';
                            }
                        } else {
                            inptUpdate = '<span class="badge badge-rounded badge-outline-warning">Null</span>';
                        }
                        // delete permissions
                        if (data[i].delete != null) {
                            if (permissions != null && (permissions.includes('delete_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptDelete = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="delete_' + data[i].menu + data[i].slug + '" id="delete_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '" checked>'+
                                '</div>';
                            } else {
                                inptDelete = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="delete_' + data[i].menu + data[i].slug + '" id="delete_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '">'+
                                '</div>';
                            }
                        } else {
                            inptDelete = '<span class="badge badge-rounded badge-outline-warning">Null</span>';
                        }
                        // bulk-delete permissions
                        if (data[i].bulkDelete != null) {
                            if (permissions != null && (permissions.includes('bulkDelete_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptBulkDel = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="bulkDelete_' + data[i].menu + data[i].slug + '" id="bulkDelete_' + data[i].menu + data[i].slug + '" class="form-check-input class' + data[i].id + '" role="switch" checked>';
                            } else {
                                inptBulkDel = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="bulkDelete_' + data[i].menu + data[i].slug + '" id="bulkDelete_' + data[i].menu + data[i].slug + '" class="form-check-input class' + data[i].id + '">'+
                                '</div>';
                            }
                        } else {
                            inptBulkDel = '<span class="badge badge-rounded badge-outline-warning">Null</span>';
                        }
                        // export permissions
                        if (data[i].export != null) {
                            if (permissions != null && (permissions.includes('export_' + data[i].menu + data[i].slug) || permissions == 'all')) {
                                inptExport = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="export_' + data[i].menu + data[i].slug + '" id="export_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '" checked>'+
                                '</div>';
                            } else {
                                inptExport = '<div class="form-check form-check-md form-switch">'+
                                    '<input type="checkbox" name="permissions[]" value="export_' + data[i].menu + data[i].slug + '" id="export_' + data[i].menu + data[i].slug + '" onclick="checkParent(' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '" role="switch">'+
                                '</div>';
                            }
                        } else {
                            inptExport = '<span class="badge badge-rounded badge-outline-warning">Null</span>';
                        }
                        // all permissions
                        // if (data[i].create == null && data[i].view == null && data[i].update == null && data[i].delete == null) {
                        //     inptAll = '<span class="badge badge-rounded badge-outline-warning">Null</span>';
                        // } else {
                        //     if (permissions != null && ((permissions.includes('create_' + data[i].menu + data[i].slug) && permissions.includes('view_' + data[i].menu + data[i].slug) && permissions.includes('update_' + data[i].menu + data[i].slug) && permissions.includes('delete_' + data[i].menu + data[i].slug)) || permissions == 'all')) {
                        //         inptAll = '<div class="form-check form-check-md form-switch">'+
                        //             '<input type="checkbox" id="all_' + data[i].menu + data[i].slug + '" onclick="checkALL(' + "'" + data[i].slug + "'" + ',' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '" checked>'+
                        //         '</div>';
                        //     } else {
                        //         inptAll = '<div class="form-check form-check-md form-switch">'+
                        //             '<input type="checkbox" id="all_' + data[i].menu + data[i].slug + '" onclick="checkALL(' + "'" + data[i].slug + "'" + ',' + "'" + data[i].id + "'" + ',' + "'" + data[i].parent_id + "'" + ')" class="form-check-input class' + data[i].id + '">'+
                        //         '</div>';
                        //     }
                        // }
                        // table rows
                        if(data[i].parent_id == 0){
                            var font = "fw-semibold";
                        } else{
                            var font = "fw-normal";
                        }
                        html += '<tr>' +
                            '<td class="'+ font +'">' + data[i].slug + '</td>' +
                            '<td class="text-center">' + inptCreate + '</td>' +
                            '<td class="text-center">' + inptImport + '</td>' +
                            '<td class="text-center">' + inptView + '</td>' +
                            '<td class="text-center">' + inptUpdate + '</td>' +
                            '<td class="text-center">' + inptDelete + '</td>'+
                            '<td class="text-center">' + inptBulkDel + '</td>'+
                            '<td class="text-center">' + inptExport + '</td>';
                        // if (action != 'view') {
                        //     html += '<td class="text-center">' + inptAll + '</td>';
                        // }
                        html += '</tr>';
                    }
                }
                html += '</tr>' +
                    '</tfoot>' +
                    '</table>';
            } else {
                html += '<tr><td colspan="">No data found</td></tr>';
            }
            if (action == 'view') {
                $('div#viewPermissions').html(html);
                $("td input").attr('disabled', true)
            } else {
                $('div#permissions').html(html);
            }
            $('input#view_dashboardDashboard').attr("checked", true);
            $('input#view_dashboardDashboard').click(function() {
                $('input#view_dashboardDashboard').prop("checked", true);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}
// check all permissions
function checkALL(menu, id, parent_id) {
    if (menu != null) {
        if ($("input#all" + menu).is(':checked')) {
            if ($('.class'+ id).not(':checked')) {
                $('.class'+ id).attr('checked', true);
            }
            if ($('.class'+ id).not(':checked')) {
                $('.class'+ id).attr('checked', true);
            }
            if ($('.class'+ id).not(':checked')) {
                $('.class'+ id).attr('checked', true);
            }
            if ($('.class'+ id).not(':checked')) {
                $('.class'+ id).attr('checked', true);
            }
            if ($('.class'+ id).not(':checked')) {
                $('.class'+ id).attr('checked', true);
            }
            if ($('.class'+ id).not(':checked')) {
                $('.class'+ id).attr('checked', true);
            }
            if ($('.class'+ id).not(':checked')) {
                $('.class'+ id).attr('checked', true);
            }
        } else {
            if ($('.class'+ id).is(':checked')) {
                $('.class'+ id).attr('checked', false);
            }
            if ($('.class'+ id).is(':checked')) {
                $('.class'+ id).attr('checked', false);
            }
            if ($('.class'+ id).is(':checked')) {
                $('.class'+ id).attr('checked', false);
            }
            if ($('.class'+ id).is(':checked')) {
                $('.class'+ id).attr('checked', false);
            }
            if ($('.class'+ id).is(':checked')) {
                $('.class'+ id).attr('checked', false);
            }
            if ($('.class'+ id).is(':checked')) {
                $('.class'+ id).attr('checked', false);
            }
            if ($('.class'+ id).is(':checked')) {
                $('.class'+ id).attr('checked', false);
            }
        }
    }
    $('.class' + parent_id).prop("checked", false);
    checkParent(id, parent_id)
}
// check parent if child is checked
function checkParent(id, parent_id) {
    // check parent menu
    if (parent_id != 0) {
        if ($(".class"+ id).is(':checked')) {
            $('.class'+ parent_id).prop("checked", true);
            $('.class'+ parent_id).click(function() {
                $('.class'+ parent_id).prop("checked", true);
            });
        } else {
            $('.class'+ parent_id).prop("checked", false);
            $('.class'+ parent_id).click(function() {
                $('.class'+ parent_id).prop("checked", false);
            });
        }
    }
}