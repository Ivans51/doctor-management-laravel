const input = document.querySelector("#phone_number");
window.intlTelInput(input, {
    initialCountry: 'auto',
    geoIpLookup: callback => {
        fetch("https://ipapi.co/json")
            .then(res => res.json())
            .then(data => callback(data.country_code))
            .catch(() => callback("us"));
    },
});
