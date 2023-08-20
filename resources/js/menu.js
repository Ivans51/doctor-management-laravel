// open sidebar and hide in tailwind class
window.openSidebar = function openSidebar() {
    const sidebar = $('#sidebar');
    const overlay = $('#overlay');

    if (sidebar.hasClass('-left-full')) {
        sidebar.removeClass('-left-full')
        sidebar.addClass('left-0')
    } else {
        sidebar.removeClass('left-0')
        sidebar.addClass('-left-full')
    }
}

// close sidebar click outside
$(document).mouseup(function (e) {
    $('#sidebar').each(function () {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0) {
            $(this).removeClass('left-0')
            $(this).addClass('-left-full')
        }
    });
})
