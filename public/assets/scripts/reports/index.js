var save_method;
$(document).ready(function() {
    // var index, value;
    $.ajax({
        url: "/admin/reports/monthly-transactions-report/expense",
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            const total = [];
            if(data.length > 0){
                data.forEach(function(value, index){
                    total.push(value);
                });
                // console.log(total);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
});