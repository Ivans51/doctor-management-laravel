function initPhoneInput(formSelector = 'form', phoneInputId = 'phone_number', fullNumberInputId = 'full_phone_number') {
    const phoneInput = document.getElementById(phoneInputId)
    if (!phoneInput) return

    const iti = window.intlTelInput(phoneInput, {
        preferredCountries: ['us', 'gb'],
        loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),
        separateDialCode: true,
        initialCountry: "auto",
        geoIpLookup: function (callback) {
            fetch('https://ipapi.co/json/')
                .then(res => res.json())
                .then(data => callback(data.country_code))
                .catch(() => callback('us'))
        }
    })

    document.querySelector(formSelector).addEventListener('submit', function (event) {
        const phoneNumber = iti.getNumber()
        document.getElementById(fullNumberInputId).value = phoneNumber
    })
}
