<!DOCTYPE html>
<!--
author: Merle Friedrichsen
date: 15.03.2018
ISBN-request -> takes user input and shows metadata for an isbn
-->
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <title>Metadaten</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript" src="JS/messagePattern.js"></script>
        <link rel="stylesheet" href="styles/styles.css">
    </head>
    <body>
        <div id="all">
            <div id="form">
                <!--display form, after submit the page is not loaded again-->
                <form id="isbnform" method="post" action="">
                    <label for="isbn">ISBN:
                        <!--validate userinput on client,
                        add a cusstom errormessage through JS/messagePattern,
                        pattern: http://html5pattern.com/Miscs-->
                        <input type="text" id="isbn"
                               pattern="^(?:(?=.{17}$)97[89][ -](?:[0-9]+[ -]){2}[0-9]+[ -][0-9]|97[89][0-9]{10}|(?=.{13}$)(?:[0-9]+[ -]){2}[0-9]+[ -][0-9Xx]|[0-9]{9}[0-9Xx])$"
                               required
                               oninput="setCustomValidity('')">
                    </label>
                    <div>
                        <button type="reset">Eingabe löschen</button>
                        <button type="submit" id="submit">Abschicken</button>
                    </div>
                </form>
            </div>
            <script type="text/javascript">
                $("#isbnform").submit(function (event) {
                    //suppress reloading after submit
                    event.preventDefault();
                });
                $("#isbnform").submit(function () {
                    //change input to JSON
                    var inputISBN = $("input#isbn").val();
                    inputISBN = '{"isbn":"'+inputISBN+'"}';
                    
                    $.post({
                        //pass the userinput to getMetadataOCLC.php
                        url: 'http://localhost/Pflichtabgabe/getMetadataOCLC.php',
                        data: inputISBN,
                        dataType: 'json',
                        success: function (data)
                        {
                            //as there should be only one result for an isbn,
                            //i do not interate through the result
                            //append a div for each result
                            $('#all').append('<div id="results">Titel: '+data[0].title+'</br>\n\
                                                                Autor: '+data[0].author+'</br>\n\
                                                                Verlag, Stadt: '+data[0].publisher+', '+data[0].city+'</br>\n\
                                                                Sprache: '+data[0].lang+'</br>\n\
                                                                Jahr, Ausgabe: '+data[0].year+', '+data[0].ed+'</br>\n\
                                                                ISBN: '+data[0].isbn+'</br>\n\
                                                                </div>');
                        },
                        //in case the isbn is not registered at OCLC
                        error: function ()
                        {
                            $('#all').append('<div id="results"> Die ISBN ist nicht bei OCLC registiert.</div>');
                        }
                    });
                });
            </script>
        </div>
    </body>
</html>