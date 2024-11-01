var statement = "cashflow";
$(document).ready(function () {
    // Call the generate_cashflow function when the button is clicked
    $('#statementBtn').on('click', function() {
        generate_cashflow();
    });
    generate_cashflow();
});

function generate_cashflow() {
    $.ajax({
        url: '/admin/statements/generate-statement',
        method: 'GET',
        dataType: 'json',
        data: {
            start_date: $('[name="start_date"]').val(),
            end_date: $('[name="end_date"]').val(),
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
            var printURL = "/admin/statements/export-statement/" + statement + "/" + startDate + "/" + endDate;
            $('#printURL').attr('href', printURL);

            // Generate table rows
            var html = '';
            if (response.cashFlowData.length > 0) {
                $.each(response.cashFlowData, function (cashflowId, cashflowType) {
                    // Cash Flow from cashflow_type
                    html += '<tr>';
                    html += '<th class="text-info text-bold p-3" colspan="2">Cash Flow from ' + cashflowType.name + '</th>';
                    html += '</tr>';

                    // Particulars for each cash flow type
                    if (response.particularData.length > 0) {
                        $.each(response.particularData, function (index, particular) {
                            if (particular.cash_flow_typeId == cashflowType.id) {
                                html += '<tr>';
                                html += '<td class="pl-5"><a href="/admin/statements/particular-statement/' + particular.id + '/' + response.start_date + '/' + response.end_date + '" class="text"><i>' + particular.particular_name + '</i></a></td>';
                                html += '<td align="right"><i>' + particular.balance + ' </i></td>';
                                html += '</tr>';
                            }
                        });
                    } else {
                        html += '<tr><th class="text-center" colspan="2">No Particulars Found</th></tr>';
                    }

                    // Cashflow-type totals
                    html += '<tr>';
                    html += '<td class="text-info p-3"><i>Total Cash Flow From ' + cashflowType.name + '</i></td>';
                    html += '<td class="text-info" align="right"><i><u>' + cashflowType.balance + ' </u></i></td>';
                    html += '</tr>';
                });
            } else {
                html += '<tr><th class="text-center" colspan="2">No Cash Flow Types Found</th></tr>';
            }

            // Insert the generated HTML into the table body
            $('#cashflow-body').html(html);
        },
        error: function () {
            // Handle error case
        }
    });
}