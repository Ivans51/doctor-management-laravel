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

// constants
const CONST_APPROVED = 'approved'
const CONST_PENDING = 'pending'
const CONST_REJECTED = 'rejected'
const CONST_ACTIVE = 'active'
const CONST_INACTIVE = 'inactive'