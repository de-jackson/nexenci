// auto disbursement days
function auto_update_disbursementDays() {
    $.ajax({
        url: '/client/loans/type/disbursements/auto',
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            console.log(data.message);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Could not Update Disbursement Status due to: "+ errorThrown);
        }
    });
}
// auto disbursement balances
function auto_update_disbursementBalances() {
    $.ajax({
        url: '/client/loans/type/disbursements/balance',
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            console.log(data.message);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Could not Update Disbursement Total Amount Collected due to: "+ errorThrown);
        }
    });
}
// auto calculate & update total debit & credit for particulars
function auto_update_partilarBalances() {
    $.ajax({
        url: '/client/loans/type/disbursements/ledgers',
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            console.log(data.message);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Could not Update Particular Total Debit & Credit due to: "+ errorThrown);
        }
    });
}
// auto calculate & update account balance for clients
function auto_update_clientSavingsBalances() {
    $.ajax({
        url: '/client/loans/type/disbursements/savings',
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            console.log(data.message);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Could not Update Client Balance due to: "+ errorThrown);
        }
    });
}