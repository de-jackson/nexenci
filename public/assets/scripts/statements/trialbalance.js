var statement = "trialbalance";
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
});

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
                    // category name
                    html += '<tr>';
                    html += '<th class="fw-bold px-3" colspan="3">' + category.category_name.toUpperCase() + '</th>';
                    html += '</tr>';

                    // subcategories for each category
                    if (response.subcategoryData.length > 0) {
                        $.each(response.subcategoryData, function (subcategoryIndex, subcategory) {
                            if (subcategory.category_id == category.id) {
                                // subcategory name
                                html += '<tr>';
                                html += '<th class="px-3" colspan="3">' + subcategory.subcategory_name + '</th>';
                                html += '</tr>';

                                // particulars for each subcategory
                                if (response.particularData.length > 0) {
                                    $.each(response.particularData, function (particularIndex, particular) {
                                        if (particular.subcategory_id == subcategory.id) {
                                            html += '<tr>';
                                            html += '<td class="px-5"><a href="/admin/statements/particular-statement/' + particular.id + '/' + response.start_date + '/' + response.end_date + '" class="text-info"><i>' + particular.particular_name + '</i></a></td>';
                                            // put balance on debit side
                                            if (particular.part == "debit") {
                                                html += '<td align="right">' + particular.balance + '</td><td></td>';
                                                html += '</tr>';
                                            }
                                            // put balance on credit side
                                            if (particular.part == "credit") {
                                                html += '<td></td><td align="right">' + particular.balance + '</td>';
                                                html += '</tr>';
                                            }
                                        }
                                    });
                                } else {fw-semibold 
                                    html += '<tr><th class="text-center" colspan="6">No particulars Found</th></tr>';
                                }

                                // Subcategory total
                                html += '<tr>';
                                html += '<td class="px-3"><u><i>Total ' + subcategory.subcategory_name + '</i></u></td>';
                                // put balance on debit side
                                if (subcategory.part == "debit") {
                                    html += '<td align="right"><u><i>' + subcategory.balance + ' </i></u></td><td></td>';
                                    html += '</tr>';
                                }
                                // put balance on credit side
                                if (subcategory.part == "credit") {
                                    html += '<td></td><td align="right"><u><i>' + subcategory.balance + ' </i></u></td>';
                                    html += '</tr>';
                                }
                            }
                        });
                    } else {
                        html += '<tr><th class="text-center" colspan="2">No Subcategories Found</th></tr>';
                    }

                    // Category total
                    html += '<tr>';
                    html += '<td class="fw-bold px-3"><i>Total ' + category.category_name + '</i></td>';
                    // put balance on debit side
                    if (category.part == "debit") {
                        html += '<td class="fw-bold" align="right"><i>' + category.balance + ' </i></td><td></td>';
                        html += '</tr>';
                    }
                    // put balance on credit side
                    if (category.part == "credit") {
                        html += '<td></td><td class="fw-bold" align="right"><i>' + category.balance + ' </i></td>';
                        html += '</tr>';
                    }
                });
                // colorize balance
                var textColor = ((response.getTotals.totalDebits == response.getTotals.totalCredits) ? 'text-success' : 'text-danger');
                // income summary
                // gross income
                html += '<tr class="fw-bold ' + textColor + '">' +
                    '<td align="center">Total</td>' +
                    '<td align="right">' + response.getTotals.totalDebits + '</td>' +
                    '<td align="right">' + response.getTotals.totalCredits + '</td>' +
                    '</tr>';
            } else {
                html += '<tr><th class="text-center" colspan="2">No Categories Found</th></tr>';
            }

            // Insert the generated HTML into the table body
            $('#trialbalance-body').html(html);
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