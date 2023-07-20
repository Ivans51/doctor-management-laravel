// format date dd/mm/yyyy
function formatDate(date) {
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
function formatNumber(number) {
    return new Intl.NumberFormat('es-ES', {style: 'currency', currency: 'USD'}).format(number)
}

// limit text to 50 characters in description and add three dots
function limitText(text, number = 20) {
    if (text.length <= number) {
        return text
    }
    return text.substring(0, number) + '...'
}

// date is mayor to current with Date object
function isMayorDate(value) {
    const date = new Date(value)
    const currentDate = new Date()
    return date >= currentDate
}

function changeStatusAppointment(id, status, url) {
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

// constants
const CONST_APPROVED = 'approved'
const CONST_PENDING = 'pending'
const CONST_REJECTED = 'rejected'
const CONST_ACTIVE = 'active'
const CONST_INACTIVE = 'inactive'
