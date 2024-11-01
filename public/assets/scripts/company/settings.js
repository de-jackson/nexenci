var save_method;
$(document).ready(function() {
    view_settings(id);
    // summernote editor
    // textEditor();
});

// function textEditor() {
//     /* Summernote Validation */

//     var summernoteForm = $('.form-horizontal');
//     var summernoteElement = $('.summernote');

//     var summernoteValidator = summernoteForm.validate({
//         errorElement: "div",
//         errorClass: 'is-invalid',
//         validClass: 'is-valid',
//         ignore: ':hidden:not(.summernote),.note-editable.card-block',
//         errorPlacement: function (error, element) {
//             // Add the `help-block` class to the error element
//             error.addClass("invalid-feedback");
//             // console.log(element);
//             if (element.prop("type") === "checkbox") {
//                 error.insertAfter(element.siblings("label"));
//             } else if (element.hasClass("summernote")) {
//                 error.insertAfter(element.siblings(".note-editor"));
//             } else {
//                 error.insertAfter(element);
//             }
//         }
//     });

//     summernoteElement.summernote({
//         height: 300,
//         callbacks: {
//             onChange: function (contents, $editable) {
//         // Note that at this point, the value of the `textarea` is not the same as the one
//         // you entered into the summernote editor, so you have to set it yourself to make
//         // the validation consistent and in sync with the value.
//         summernoteElement.val(summernoteElement.summernote('isEmpty') ? "" : contents);

//         // You should re-validate your element after change, because the plugin will have
//         // no way to know that the value of your `textarea` has been changed if the change
//         // was done programmatically.
//                 summernoteValidator.element(summernoteElement);
//             }
//         }
//     });
// }

function exportSettingsForm() {
    var settings_id = $('[name="id"]').val();
    window.location.assign("/admin/settings/print-settingsForm/"+settings_id);
}
// pop add model
function add_settings() {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Loan Settings');
    $('[name="id"]').val(0);
    setPhoneNumberWithCountryCode($("#business_contact"), "");
    setPhoneNumberWithCountryCode($("#business_alt_contact"), "");
}

function view_settings(id) {
    //Ajax Load data from ajax
    $.ajax({
        url: "/admin/company/settings/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id"]').val(data.id);
            $('[name="system_name"]').val(data.system_name);
            $('[name="system_abbr"]').val(data.system_abbr);
            $('[name="author"]').val(data.author);
            $('[name="business_about"]').val(window.editor.setData(data.business_about));
            $('[name="system_slogan"]').val(data.system_slogan);
            $('[name="system_version"]').val(data.system_version);
            $('[name="business_name"]').val(data.business_name);
            $('[name="business_abbr"]').val(data.business_abbr);
            $('[name="business_slogan"]').val(data.business_slogan);
            $('[name="business_contact"]').val(data.business_contact);
            $('[name="business_alt_contact"]').val(data.business_alt_contact);
            setPhoneNumberWithCountryCode($("#business_contact"), data.business_contact);
            setPhoneNumberWithCountryCode($("#business_alt_contact"), data.business_alt_contact);
            $('[name="business_email"]').val(data.business_email);
            $('[name="business_pobox"]').val(data.business_pobox);
            $('[name="business_address"]').val(data.business_address);
            $('[name="business_web"]').val(data.business_web);
            $('[name="tax_rate"]').val(data.tax_rate);
            if (data.sms) {
                $("#sms").attr("checked", true);
            }
            if (data.email) {
                $("#email").attr("checked", true);
            }
            $('[name="round_off"]').val(data.round_off);
            $('[name="financial_year_start"]').val(data.financial_year_start);
            $('[name="created_at"]').val(data.created_at);
            $('[name="updated_at"]').val(data.updated_at);
            selectCurrency(data.currency_id);
            $('img#preview-image').attr('src', logo);
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
        }
    });
}

// save
function save_settings() {
    sID = $('[name="id"]').val();
    $(".form-group").removeClass("has-error"); // clear error class
    $(".help-block").empty(); // clear error string
    $('#btnSav').text('submitting...'); //change button text
    $('#btnSav').attr('disabled', true); //set button disable 
    var url, method;
    if(save_method == 'add'){
        url = "/admin/company/settings";
    }else{
        url = "/admin/company/edit-settings/" + sID;
    }
    // ajax adding data to database
    var formData = new FormData($('#settingsForm')[0]);
    // Ensure the editor data is updated in the textarea
    const editorData = window.editor.getData();
    // $('textarea#business_about').val(editorData);
    formData.append("business_about", editorData);
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            if(!data.inputerror){
                if (data.status && data.error == null) {
                    Swal.fire("Success!", data.messages, "success");
                    review_settings_form();
                } else if(data.error != null){
                    Swal.fire(data.error, data.messages, "error");
                } else{
                    Swal.fire('Error', "Something unexpected happened, try again later", "error");
                }
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); 
                    $('[name="' + data.inputerror[i] + '"]').closest(".form-group").find(".help-block").text(data.error_string[i]); 
                }
            }
            $('#btnSav').text('Update');
            $('#btnSav').attr('disabled', false);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
            $('#btnSav').text('Update');
            $('#btnSav').attr('disabled', false); 
        }
    });
}

// reflesh page
function review_settings_form() {
    setInterval(window.location.reload(), 3600);
}