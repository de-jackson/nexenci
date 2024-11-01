var statement = "profitloss";
var year = new Date().getFullYear();
var month = new Date().getMonth();
$(document).ready(function () {
    // Call the generate_statement function when the button is clicked
    $('#statementBtn').on('click', function() {
        generate_statement();
    });
    generate_statement()
    // load entry years and months
    $.ajax({
        url: '/admin/statements/entryYears',
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            if (data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    $("<option />").val(data[i])
                        .text(data[i])
                        .appendTo($('select#entryYear'));
                }
            } else {
                $('select#entryYear').html('<option value="">No Years</option>');
            }

        },
        error: function (err) {
            $('select#entryYear').html('<option value="">Error Occured</option>');
        }
    })
    $.ajax({
        url: '/admin/statements/entryMonths/' + year,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            if (data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    $("<option />").val(data[i])
                        .text(data[i])
                        .appendTo($('select#entryMonth'));
                }
            } else {
                $('select#entryMonth').html('<option value="">No Years</option>');
            }

        },
        error: function (err) {
            $('select#entryMonth').html('<option value="">Error Occured</option>');
        }
    })
})

function generate_statement() {
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
            // categories level
            if (response.categoryData.length > 0) {
                $.each(response.categoryData, function (categoryIndex, category) {
                    // filter profit n loss items only
                    if (category.statement_id == 2) {
                        // category name
                        html += '<tr>';
                        html += '<th class="text-info text-bold p-3" colspan="6">' + category.category_name + '</th>';
                        html += '</tr>';

                        // subcategories for each category
                        if (response.subcategoryData.length > 0) {
                            $.each(response.subcategoryData, function (subcategoryIndex, subcategory) {
                                if (subcategory.category_id == category.id) {
                                    // subcategory name
                                    html += '<tr>';
                                    html += '<th class="text-bold p-3" colspan="6">' + subcategory.subcategory_name + '</th>';
                                    html += '</tr>';

                                    // Particulars for each subcategory
                                    if (response.particularData.length > 0) {
                                        $.each(response.particularData, function (particularIndex, particular) {
                                            if (particular.subcategory_id == subcategory.id) {
                                                html += '<tr>';
                                                html += '<td colspan="4" class="pl-5"><a href="/admin/statements/particular-statement/' + particular.id + '/' + response.start_date + '/' + response.end_date + '" class="text"><i>' + particular.particular_name + '</i></a></td>';
                                                html += '<td colspan="2" align="right"><i>' + particular.balance + ' </i></td>';
                                                html += '</tr>';
                                            }
                                        });
                                    } else {
                                        html += '<tr><th class="text-center" colspan="6">No Particulars Found</th></tr>';
                                    }

                                    // Subcategory total
                                    html += '<tr>';
                                    html += '<td colspan="4" class="p-3"><i>Total ' + subcategory.subcategory_name + '</i></td>';
                                    html += '<td colspan="2" class="" align="right"><i><u>' + subcategory.balance + ' </u></i></td>';
                                    html += '</tr>';
                                }
                            });
                        } else {
                            html += '<tr><th class="text-center" colspan="6">No Subcategories Found</th></tr>';
                        }

                        // Category total
                        html += '<tr>';
                        html += '<td colspan="4" class="text-info p-3"><i>Total ' + category.category_name + '</i></td>';
                        html += '<td colspan="2" class="text-info" align="right"><i><u>' + category.balance + ' </u></i></td>';
                        html += '</tr>';
                    }
                });
                // colorize net income
                var textColor = ((response.getTotals.netIncome < 0) ? 'text-danger' : 'text-success');
                // income summary
                html += '<tr class="text-bold ' + textColor + '">' +
                    // gross income
                    '<td align="center">Gross Income</td>' +
                    '<td align="center">' + response.getTotals.grossIncome + '</td>' +
                    // tax provision
                    '<td align="center">Total Tax(' + taxRate + '%)</td>' +
                    '<td align="center">' + response.getTotals.totalTax + '</td>' +
                    // net income
                    '<td align="center">Net Income</td>' +
                    '<td align="center">' + response.getTotals.netIncome + '</td>' +
                    '</tr>';
            } else {
                html += '<tr><th class="text-center" colspan="6">No Categories Found</th></tr>';
            }

            // Insert the generated HTML into the table body
            $('#profitloss-body').html(html);
        },
        error: function () {
            // Handle error case
        }
    });
}

$('select#entryYear').on('change', function () {
    var year = $(this).val();
    if (year != '') {
        $.ajax({
            url: '/admin/statements/entryMonths/' + year,
            dataType: "GET",
            dataType: "JSON",
            success: function (data) {
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $("<option />").val(data[i])
                            .text(data[i])
                            .appendTo($('select#entryMonth'));
                    }
                }
            },
            error: function (error) {
                $('select#entryMonth').html('<option value="">Error Occured</option>');
            }
        });
    } else {
        $('select#entryMonth').html('<option value="' + month + '">' + month + '</option>')
    }
});