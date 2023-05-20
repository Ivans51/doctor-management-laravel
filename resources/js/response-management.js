// Set error message
function setError(error) {
    if (error.response.errors) {
        $('#content-error').show();
        $('#list-errors').html('');
        $.each(error.response.errors, function (key, value) {
            $('#list-errors').append('<li>' + value + '</li>');
        });
    } else {
        const auth = $('#message-error');
        auth.show();
        auth.html(error.response.data.message);
    }
}
