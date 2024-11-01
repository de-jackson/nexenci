$(document).ready(function () {
    // gender
    generateGender();
    // generateCustomeSettings('gender');
    generateCustomeSettings('nationality');
    generateCustomeSettings('maritalstatus');
    generateCustomeSettings('religion');
    generateCustomeSettings('relationships');
    generateCustomeSettings('idtypes');
    generateCustomeSettings("occupation");

    generateStaff(staff_id);


});

function clientAccount(save_method) {
    id = $('[name="id"]').val();
    $('#btnSubmit').text('submitting...');
    $('#btnSubmit').attr('disabled', true);
    var url, method;
    if (save_method == 'add') {
        url = "/client/clients";
    } else if (save_method == 'password') {
        url = "/admin/clients/update-clientStatus/" + id;
    } else {
        url = "/admin/clients/edit-client/" + id;
    }
    // ajax adding data to database
    var formData = new FormData($('#form')[0]);
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data) {
            if (!data.inputerror) {
                if (data.status && data.error == null) {
                    Swal.fire("Success!", data.messages, "success");
                    setInterval(function () {
                        window.location.replace('/client/dashboard');
                    }, 3000);
                } else if (data.error != null) {
                    Swal.fire(data.error, data.messages, "error");
                } else {
                    Swal.fire('Error', "Something unexpected happened, try again later", "error");
                }
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                    $('[name="' + data.inputerror[i] + '"]').closest(".form-group").find(".help-block").text(data.error_string[i]);
                }
            }
            $('#btnSubmit').text('Submit'); //change button text
            $('#btnSubmit').attr('disabled', false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
            $('#btnSubmit').text('Submit'); //change button text
            $('#btnSubmit').attr('disabled', false); //set button enable 

        }
    });
}