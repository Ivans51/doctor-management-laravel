// format date dd/mm/yyyy
window.formatDate = function formatDate(date) {
    let d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear()

    if (month.length < 2)
        month = '0' + month
    if (day.length < 2)
        day = '0' + day

    return [day, month, year].join('/')
}

// format number point to decimal and add $ symbol and dot for a thousand
window.formatNumber = function formatNumber(number) {
    return new Intl.NumberFormat('es-ES', {style: 'currency', currency: 'USD'}).format(number)
}

// limit text to 50 characters in description and add three dots
window.limitText = function limitText(text, number = 20) {
    if (text.length <= number) {
        return text
    }
    return text.substring(0, number) + '...'
}

// date is mayor to current with Date object
window.isMayorDate = function isMayorDate(value) {
    const date = new Date(value)
    const currentDate = new Date()
    return date >= currentDate
}

// date is minor to current with Date object
window.isMinorDate = function isMinorDate(value) {
    const date = new Date(value)
    const currentDate = new Date()
    return date < currentDate
}

window.changeStatusAppointment = function changeStatusAppointment(id, status, url) {
    if (id) {
        return new Promise((resolve, reject) => {
            deleteSwal('').then(() => {
                let token = $('meta[name="csrf-token"]').attr('content')

                $.ajax({
                    url: url,
                    type: 'PUT',
                    dataType: 'json',
                    data: {
                        _token: token,
                        appointment_id: id,
                        status: status
                    },
                    success: function () {
                        resolve()
                    },
                    error: function (xhr) {
                        reject()
                    }
                })
            })
        })
    }
}

// constants
window.CONST_APPROVED = 'approved'
window.CONST_PENDING = 'pending'
window.CONST_REJECTED = 'rejected'
window.CONST_ACTIVE = 'active'
window.CONST_INACTIVE = 'inactive'
