function appendResults() {
    const form = document.getElementById("isbnform");
    form.addEventListener('submit', function(){
        $('#all').append('<div id="results"> Test </div>');
    });

}

window.addEventListener('load', appendResults());