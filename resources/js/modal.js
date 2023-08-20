
function configModal(name, btnId) {
    const modalContainer = document.querySelector(`.${name}`);

    document.getElementById(btnId).addEventListener('click', function () {
        modalContainer.classList.remove('hidden');
    });

    const closeModal = document.getElementsByClassName('modal-close');
    for (let i = 0; i < closeModal.length; i++) {
        closeModal[i].addEventListener('click', function () {
            modalContainer.classList.add('hidden');
        })
    }

    const modalOverlay = document.querySelector('.modal-overlay');

    document.addEventListener('click', function(event) {
        if (event.target === modalOverlay) {
            modalContainer.classList.add('hidden');
        }
    });
}
