$(document).ready(function () {
    auto_update_clientSavingsBalances();
    // auto update particular total debit && credit
    auto_update_partilarBalances();
    selectPaymentMethod();
});

// load particular account types by part
function account_types_byPart(part = null, selectedAccount = null) {
    var accountTypeSelect = $('select#account_typeId');
    // Clear existing options
    accountTypeSelect.html('');
    // Add default option
    accountTypeSelect.append('<option value="">-- select --</option>');
    if (part) {
        $.ajax({
            url: '/client/loans/type/accounts/' + part,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                if (data.length > 0) {
                    $.each(data, function (index, type) {
                        var option = $('<option>', {
                            value: type.id,
                            text: type.name
                        });
                        if (type.id == selectedAccount) {
                            option.attr('selected', 'selected');
                        }
                        accountTypeSelect.append(option);
                    });
                } else {
                    accountTypeSelect.html('<option value="">No Account Type</option>');
                }
            },
            error: function (err) {
                accountTypeSelect.html('<option value="">Error Occured</option>');
            }
        });
    } else {
        accountTypeSelect.html('<option value="">No Part Selected</option>');
    }
}

// fetch transaction types
function transaction_types(entry_typeId = null) {
    entry_typeSelect = $('select#entry_typeId');
    // select account type entry\transaction types
    entry_typeSelect.html('<option value="">-- select --</option>');
    var entry_menu = (typeof entry_menu !== 'undefined' ? entry_menu : null);
    $.ajax({
        url: '/client/reports/types/entrytypes/' + account_typeId+ '/'+entry_menu,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            if (data.length > 0) {
                $.each(data, function (index, item) {
                    var option = $('<option>', {
                        value: item.id,
                        text: item.type
                    });
                    if(item.id == entry_typeId){
                        option.attr("selected", true)
                    }
                    entry_typeSelect.append(option);
                });
            } else {
                entry_typeSelect.html('<option value="">No Type</option>');
            }

        },
        error: function (err) {
            entry_typeSelect.html('<option value="">Error Occured</option>');
        }
    });
}
// load particulars on change of account type
$('select#account_typeId').on('change', function () {
    account_typeId = this.value;
    var particularSelect = $('select#particular_id');
    var entry_typeSelect = $('select#entry_typeId');
    if (account_typeId == 0 || account_typeId == '') {
        particularSelect.html('<option value="">Choose Account Type</option>');
        entry_typeSelect.html('<option value="">Choose Account Type</option>');
    } else {
        // select account type particulars
        particularSelect.html('<option value="">-- select --</option>');
        $.ajax({
            url: '/client/reports/types/particulars/' + account_typeId,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                if (data.length > 0) {
                    $.each(data, function (index, item) {
                        var option = $('<option>', {
                            value: item.id,
                            text: item.particular_name
                        });

                        particularSelect.append(option);
                    });
                } else {
                    particularSelect.html('<option value="">No Particular</option>');
                }

            },
            error: function (err) {
                particularSelect.html('<option value="">Error Occured</option>');
            }
        });
        // select account type entry\transaction types
        entry_typeSelect.html('<option value="">-- select --</option>');
        $.ajax({
            url: '/client/reports/types/particulars/' + account_typeId,
            type: "POST",
            dataType: "JSON",
            data: {
                entry_menu: (typeof entry_menu !== 'undefined' ? entry_menu : null),
            },
            success: function (data) {
                if (data.length > 0) {
                    $.each(data, function (index, item) {
                        var option = $('<option>', {
                            value: item.id,
                            text: item.type
                        });

                        entry_typeSelect.append(option);
                    });
                } else {
                    entry_typeSelect.html('<option value="">No Type</option>');
                }

            },
            error: function (err) {
                entry_typeSelect.html('<option value="">Error Occured</option>');
            }
        });
    }
});
// load selected particular
function select_particular(account_typeId, particular_id = null) {
    var particularSelect = $('select#particular_id');
    particularSelect.html('<option value="">-- select --</option>');
    // select account type particulars
    $.ajax({
        url: '/client/reports/types/particulars/' + account_typeId,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            if (data.length > 0) {
                $.each(data, function (index, item) {
                    var option = $('<option>', {
                        value: item.id,
                        text: item.particular_name
                    });

                    if (item.id == particular_id) {
                        option.attr('selected', 'selected');
                    }

                    particularSelect.append(option);
                });
            } else {
                particularSelect.html('<option value="">No Particular</option>');
            }

        },
        error: function (err) {
            particularSelect.html('<option value="">Error Occured</option>');
        }
    });
}
// fill entry menu based on transaction type
$('select#entry_typeId').on('change', function () {
    var type_id = this.value;
    if (!type_id) {
        $('[name="entry_menu"]').val('');
    } else {
        $.ajax({
            url: '/client/reports/types/entrytype/' + type_id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('[name="entry_menu"]').val(data.entry_menu);
            },
            error: function (err) {
                $('[name="entry_menu"]').val('');
            }
        });
    }
});
// load selected transaction type
function select_transactionType(account_typeId, type_id = null) {
    var entry_typeSelect = $('select#entry_typeId');
    entry_typeSelect.html('<option value="">-- select --</option>');
    var entry_menu = (typeof entry_menu !== 'undefined' ? entry_menu : null);
    $.ajax({
        url: '/client/reports/types/entrytypes/' + account_typeId+ '/'+entry_menu,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            if (data.length > 0) {
                $.each(data, function (index, item) {
                    var option = $('<option>', {
                        value: item.id,
                        text: item.type
                    });

                    if (item.id == type_id) {
                        option.attr('selected', true);
                    }
                    entry_typeSelect.append(option);
                });
            } else {
                entry_typeSelect.html('<option value="">No Type</option>');
            }

        },
        error: function (err) {
            entry_typeSelect.html('<option value="">Error Occured</option>');
        }
    });
}
