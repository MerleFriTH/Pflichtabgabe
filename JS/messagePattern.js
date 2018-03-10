function setMessage() {
    const isbn = document.getElementById("isbn");
    isbn.addEventListener('invalid', function () {
        if (isbn.validity.valueMissing) {
            isbn.setCustomValidity("Bitte geben Sie eine ISBN ein");
        } else if (!isbn.validity.valid) {
            isbn.setCustomValidity("Dies ist keine gültige ISBN");
        }
    },false);
}

window.addEventListener('load', setMessage);
