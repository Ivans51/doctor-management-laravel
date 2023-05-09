let siteBaseSetting = document.location.pathname.split("/")[2]

console.log(siteBaseSetting)

if (siteBaseSetting === undefined) {
    $($('#menu-setting li')[0]).addClass('active')
}
if (siteBaseSetting === 'change-password') {
    $($('#menu-setting li')[1]).addClass('active')
}
if (siteBaseSetting === 'notifications') {
    $($('#menu-setting li')[2]).addClass('active')
}
if (siteBaseSetting === 'reviews') {
    $($('#menu-setting li')[3]).addClass('active')
}
