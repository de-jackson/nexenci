$(document).ready(function () {
    // update the client account balance
    auto_run_updates('savings');
    // auto update particular total debit && credit
    auto_run_updates('particular-totals');
    // update disbursement days, status, class etc
    auto_run_updates('disbursement-state');
    // update disbursement balances
    auto_run_updates('disbursement-balances');
});

// auto run updates
function auto_run_updates(updates) {
    $.ajax({
        url: '/admin/auto-update/'+ updates,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            console.log(updates +': '+ data.message);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("Could not Update "+ updates +" due to: " + errorThrown);
        }
    });
}