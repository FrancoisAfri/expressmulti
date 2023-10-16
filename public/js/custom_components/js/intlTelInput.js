

function intlTelInput(input) {
    window.intlTelInput(input, {
        initialCountry: "auto",
        geoIpLookup: function(success, failure) {
            $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                let countryCode = (resp && resp.country) ? resp.country : "";
                success(countryCode);
            });
        },
        utilsScript: "build/js/utils.js",
    });
}
