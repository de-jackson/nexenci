$(document).ready(function() {
    // transaction payment methods
    selectPaymentMethod();
    // transaction entry type
    transactionTypes(account_typeId, transaction_menu);
    // account ledgers i.e particulars
    accountLedgers(account_typeId);
    // Display the message
    displayMessage();
});

function accountTransaction() {
    $('#btnSavings').text('submitting...');
    $('#btnSavings').attr('disabled', true);
    // ajax adding data to database
    var formData = new FormData($('#form')[0]);
    $.ajax({
        url: "/client/transactions/create",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            if (!data.inputerror) {
                if (data.status) {
                    Swal.fire("Success!", data.messages, "success");
                    makePayment(data);
                    // Reset the form
                    $('#form')[0].reset();
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
            $('#btnSavings').text('Submit'); //change button text
            $('#btnSavings').attr('disabled', false); //set button enable 
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(textStatus, errorThrown, 'error');
            $('#btnSavings').text('Submit'); //change button text
            $('#btnSavings').attr('disabled', false); //set button enable 

        }
    });
}

function makePayment(data) {
    FlutterwaveCheckout({
        /*
        https://developer.flutterwave.com/docs/collecting-payments/inline

        https://developer.flutterwave.com/reference/endpoints/transactions#verify-a-transaction

        FLWPUBK_TEST-405a2fbf430a2b8beba4c18ccbafd864-X
        
        Use the card number 4187427415564246 with CVV 828 and expiry 09/32
        LIVE
        FLWPUBK-f03c1defb89e3021b476e32505f4b297-X
        EXAMPLE
        TXT: txt-88320440
        Status: successful
        TXT: 4558588
        */
        public_key: "FLWPUBK-f03c1defb89e3021b476e32505f4b297-X",
        tx_ref: "csa-" + data.account.tx_ref,
        amount: data.account.amount,
        currency: data.account.currency,
        payment_options: "card, banktransfer, ussd",
        redirect_url: data.account.redirect_url,
        /*
        FLWPUBK-e023fc88186637db17878a537bc60501-X
        meta: {
            consumer_id: 23,
            consumer_mac: "92a3-912ba-1192a",
        },
        */
        customer: {
            email: data.client.email,
            phone_number: data.client.mobile,
            name: data.client.name,
        },
        customizations: {
            title: setting.business_name,
            description: setting.description,
            logo: setting.email_template_logo,
            
        },
        onclose: function(incomplete) {
            if (incomplete === true) {
                // Record event in analytics
                Swal.fire('Cancelled', "You have cancelled your payment!", "error");
                // Reset the form
                $('#form')[0].reset();
            }
        }
    });
}