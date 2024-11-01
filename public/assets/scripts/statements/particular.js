var statement = "particular";
$(document).ready(function () {
    // Call the particular_entries function when the button is clicked
    $('#statementBtn').on('click', function () {
        particular_entries();
    });
    // load particular transactions
    particular_entries();
});

function particular_entries() {
    var startDate = $('[name="start_date"]').val();
    var endDate = $('[name="end_date"]').val();
    var html = '';
    $.ajax({
        url: '/admin/statements/particular-entries',
        dataType: "get",
        dataType: "JSON",
        data: {
            start_date: startDate,
            end_date: endDate,
        },
        success: function (response) {
            // Insert currency symbol
            var startDate = response.start_date;
            var endDate = response.end_date;
            // replace page with the response output
            $('#currency').text(currency);
            $('#startDate').text(formatDate(startDate, 'long'));
            $('#endDate').text(formatDate(endDate, 'long'));
            $('[name="start_date"]').val(startDate);
            $('[name="end_date"]').val(endDate);
            var printURL = "/admin/statements/export-statement/" + statement + "/" + startDate + "/" + endDate + "/" + particularId;
            $('#printURL').attr('href', printURL);
            if (response.data.length > 0) {
                var balanceTotal = debitAmount = creditAmount = debitOpening = creditOpening = 0;
                var debit = credit = debitClosing = creditClosing = 0;
                var opningDebitbal = opningCreditbal = 0;
                var status = "";
                // assing initial opening balance
                if (part == 'debit') {
                    opningDebitbal = debitAmount = opening;
                    creditOpening = 0;
                } else {
                    opningCreditbal = creditAmount = opening;
                    debitOpening = 0;
                }
                // debit opening
                if (opningDebitbal >= 0) {
                    debitOpening = opningDebitbal;
                } else {
                    creditOpening = opningCreditbal;
                }
                // credit opening
                if (opningCreditbal >= 0) {
                    creditOpening = opningCreditbal;
                } else {
                    debitOpening = opningDebitbal;
                }
                // closing balance
                balanceTotal = (debitAmount - creditAmount);
                if (balanceTotal >= 0) {
                    debitClosing = balanceTotal;
                } else {
                    creditClosing = -(balanceTotal);
                }
                //  add openinng balance row  as the first row in the table
                html += '<tr>' +
                    '<td>' + created_at + '</td>' +
                    '<td>Opening Balance</td>' +
                    // '<td>' + part + '</td>' +
                    // '<td>' + particularName + ' Opening Balance</td>' +
                    '<td class="text-right">' + debitOpening.toLocaleString() + '</td>' +
                    '<td class="text-right">' + creditOpening.toLocaleString() + '</td>' +
                    '<td class="text-right">' + debit.toLocaleString() + '</td>' +
                    '<td class="text-right">' + credit.toLocaleString() + '</td>' +
                    '<td class="text-right">' + debitClosing.toLocaleString() + '</td>' +
                    '<td class="text-right">' + creditClosing.toLocaleString() + '</td>' +
                    // '<td><a href="/admin/particular/info/' + particularId + '" class="font-italic" title="Go to Particular"><i class="fas fa-eye text-success"></i>#' + "<?= $particular['id'] ?>" + '</a></td>' +
                    '</tr>'
                    ;
                $.each(response.data, function (i, data) {
                    // assing amount to respective part based on the transaction status
                    // put amount to debit if status is credit n particularID == payment_id
                    if ((data.payment_id == particularId) && (data.status == "credit")) {
                        debit = Number(data.amount);
                        credit = 0;
                        debitAmount += debit;
                    }
                    // put amount to credit if status is debit n particularID == payment_id
                    if ((data.payment_id == particularId) && (data.status == "debit")) {
                        debit = 0;
                        credit = Number(data.amount);
                        creditAmount += credit;
                    }
                    // put amount to credit if status is credit n particularID == particular_id
                    if ((data.particular_id == particularId) && (data.status == "credit")) {
                        debit = 0;
                        credit = Number(data.amount);
                        creditAmount += credit;
                    }
                    // put amount to credit if status is debit n particularID == particular_id
                    if ((data.particular_id == particularId) && (data.status == "debit")) {
                        debit = Number(data.amount);
                        credit = 0;
                        debitAmount += debit;
                    }
                    // debit opening
                    if (balanceTotal >= 0) {
                        debitOpening = balanceTotal;
                        creditOpening = 0;
                    } else {
                        creditOpening = -balanceTotal;
                        debitOpening = 0;
                    }
                    // closing balance
                    balanceTotal = debitAmount - creditAmount;
                    if (balanceTotal >= 0) {
                        debitClosing = balanceTotal;
                        creditClosing = 0;
                    } else {
                        creditClosing = -(balanceTotal);
                        debitClosing = 0;
                    }
                    // set transaction status for payment && non-payment particulars
                    if (particularAcountId == 1) { // payment particular
                        // reciprocate the status for payment particulars
                        if (data.status == "credit") {
                            status = "debit";
                        }
                        if (data.status == "debit") {
                            status = "credit";
                        }
                    } else { // non-payment particular
                        status = data.status;
                    }
                    if (data.payment_id == particularId || data.particular_id == particularId) {
                        // generate table rows
                        html += '<tr>' +
                            '<td>' + data.date + '</td>' +
                            '<td>' + data.type + '</td>' +
                            // '<td>' + status + '</td>' +
                            // '<td>' + data.entry_details + '</td>' +
                            '<td class="text-right">' + debitOpening.toLocaleString() + '</td>' +
                            '<td class="text-right">' + creditOpening.toLocaleString() + '</td>' +
                            '<td class="text-right">' + debit.toLocaleString() + '</td>' +
                            '<td class="text-right">' + credit.toLocaleString() + '</td>' +
                            '<td class="text-right">' + debitClosing.toLocaleString() + '</td>' +
                            '<td class="text-right">' + creditClosing.toLocaleString() + '</td>' +
                            // '<td><a href="/admin/transaction/info/' + data.ref_id + '" class="font-italic" title="Go to Transaction"><i class="fas fa-eye text-success"></i>#' + data.ref_id + '</a></td>' +
                            '</tr>'
                            ;
                    }
                })
                if (balanceTotal < 0) {
                    balanceTotal = (-balanceTotal).toLocaleString() + " <i>(Credit)</i>";
                } else {
                    balanceTotal = (balanceTotal).toLocaleString() + " <i>(Debit)</i>";
                }
                html += '<tr>' +
                    '<th class="text-center" colspan="4">Total[' + currency + ']</th>' +
                    '<th class="text-right">' + debitAmount.toLocaleString() + '</th>' +
                    '<th class="text-right">' + creditAmount.toLocaleString() + '</th>' +
                    '<th class="text-center" colspan="2">Closing Balance: ' + balanceTotal + '</th>' +
                    '</tr>';
            } else {
                html += '<tr>' +
                    '<td class="text-center tex-bold" colspan="8"> No Data Found</th>' +
                    '</tr>';
            }
            $('tbody#particularstatement-body').html(html);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}