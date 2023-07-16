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

// constants
const CONST_APPROVED = 'approved'
const CONST_PENDING = 'pending'
const CONST_REJECTED = 'rejected'
const CONST_ACTIVE = 'active'
const CONST_INACTIVE = 'inactive'
