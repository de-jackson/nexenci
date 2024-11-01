$(document).ready(function() {
    const phoneInputs = $(".phone-input");

    phoneInputs.each(function() {
        const phoneInput = $(this);
        const iti = intlTelInput(phoneInput[0], {
            initialCountry: "auto",
            separateDialCode: true,
            strictMode: true,
            nationalMode: true,
            geoIpLookup: function(callback) {
                $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    const countryCode = (resp && resp.country) ? resp.country : "UG";
                    callback(countryCode);
                });
            },
            utilsScript: "/assets/vendor/intl-tel-input@23.7.0/build/js/utils.js"
        });

        // Store the intlTelInput instance in a data attribute for later use
        phoneInput.data('itiInstance', iti);

        // Set initial phone number if provided
        const phoneNumber = $("#" + phoneInput.attr('id')).val();
        if (phoneNumber) {
            iti.setNumber(phoneNumber);
            const fullNumber = iti.getNumber();
            const countryCode = iti.getSelectedCountryData().dialCode;
            $("#" + phoneInput.attr('id') + "_full").val(fullNumber);
            $("#" + phoneInput.attr('id') + "_country_code").val(countryCode);
        }

        phoneInput.on('blur', function() {
            const itiInstance = phoneInput.data('itiInstance');
            const fullNumber = itiInstance.getNumber();
            const countryCode = itiInstance.getSelectedCountryData().dialCode;
            $("#" + phoneInput.attr('id') + "_full").val(fullNumber);
            $("#" + phoneInput.attr('id') + "_country_code").val(countryCode);
            // console.log(countryCode);

            // Set initial phone number if available
            const initialPhoneNumber = $("#" + phoneInput.attr('id')).val();
            if (initialPhoneNumber) {
                itiInstance.setNumber(initialPhoneNumber);
            }

            // console.log(initialPhoneNumber);
        });
    });

    
    $('#formOLD').on('submit', function() {
        phoneInputs.each(function() {
            const phoneInput = $(this);
            const itiInstance = phoneInput.data('itiInstance');
            const fullNumber = itiInstance.getNumber();
            $("#" + phoneInput.attr('id') + "_full").val(fullNumber);
        });
    });
});