<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Metadaten</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="JS/appendHTML.js"></script>
    <script type="text/javascript" src="JS/messagePattern.js"></script>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div id="all">
    <div id="form">
        <form id="isbnform" method="post" action="">
            <!--TODO bleibt noch nicht auf selber Seite, vielleicht dann, wenn etwas mit den Daten gemacht wird-->
            <label for="isbn">ISBN:
                <!--http://html5pattern.com/Miscs-->
                <input type="text" id="isbn"
                       pattern="^(?:(?=.{17}$)97[89][ -](?:[0-9]+[ -]){2}[0-9]+[ -][0-9]|97[89][0-9]{10}|(?=.{13}$)(?:[0-9]+[ -]){2}[0-9]+[ -][0-9Xx]|[0-9]{9}[0-9Xx])$"
                       required>
            </label>
            <div>
                <button type="reset">Eingabe löschen</button>
                <button type="submit" id="submit">Abschicken</button>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $("#isbnform").submit(function(event){
            event.preventDefault();
        });
        $("#isbnform").submit(function(){
            alert("Hallo Welt");
            $('#all').append('<div id="results"> Test </div>');
        });
    </script>
</div>
</body>
</html>