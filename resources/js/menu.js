// Utility functions
function openSidebarMenu() {
    $('#sidebar').removeClass('-left-full').addClass('left-0');
    $('#overlay').show();
    $('body').addClass('overflow-hidden');
}

function closeSidebar() {
    $('#sidebar').removeClass('left-0').addClass('-left-full');
    $('#overlay').hide();
    $('body').removeClass('overflow-hidden');
}

// Toggle sidebar
window.openSidebar = function () {
    if ($('#sidebar').hasClass('-left-full')) {
        openSidebarMenu();
    } else {
        closeSidebar();
    }
};

// Close sidebar when clicking outside (desktop)
$(document).mouseup(function (e) {
    const sidebar = $('#sidebar');
    if (!sidebar.is(e.target) && sidebar.has(e.target).length === 0) {
        closeSidebar();
    }
});

// Close sidebar when clicking overlay (mobile)
$('#overlay').on('click', closeSidebar);
