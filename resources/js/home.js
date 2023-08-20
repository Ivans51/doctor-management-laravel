function openCloseHeaderBtn(content, btn) {
    const contentMenu = document.getElementById(content)
    const btnMenu = document.getElementById(btn)
    document.addEventListener('click', (event) => {
        const isBtnCurrency = btnMenu.contains(event.target)
        const isContentCurrency = contentMenu.contains(event.target)

        if (isBtnCurrency && (contentMenu.style.display === 'none' || contentMenu.style.display === '')) {
            contentMenu.style.display = 'block'
        } else if (!isContentCurrency) {
            contentMenu.style.display = 'none'
        }
    })
}

function isMayorDate(value) {
    const date = new Date(value)
    const currentDate = new Date()
    return date >= currentDate
}

isMayorDate('2021-05-05')
openCloseHeaderBtn('content-notification', 'btn-notification')
openCloseHeaderBtn('content-user', 'btn-user')
