function setMessage() {
    const isbn = document.getElementById("isbn");
    isbn.addEventListener('invalid', function (e) {
        if (isbn.validity.valueMissing) {
            e.target.setCustomValidity("Bitte geben Sie eine ISBN ein");
        } else if (!isbn.checkValidity()) {
            e.target.setCustomValidity("Dies ist keine gültige ISBN");
        }
    });
}
window.addEventListener('load', setMessage);