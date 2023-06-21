function successSwal(text = '') {
    return Swal.fire({
        icon: 'success',
        title: 'Success!',
        text,
    })
}

function errorSwal(error, text = '') {
    if (error) {
        console.log(error)
        if (error.responseJSON) {
            console.log(error.responseJSON.message)
        }
    }

    return Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text,
    })
}

function deleteSwal() {
    return new Promise(resolve => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                resolve()
            }
        })
    })
}
