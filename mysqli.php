<?php
require_once './My_MySQLi.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
		<h1>Query</h1>
		<?php
		$mysql = new My_MySQLi("localhost", "root", "", "d0110232");
		$result = $mysql->query("SELECT * FROM bde_user");
		
		echo "Zeilen: " . $result->num_rows . "<br>";
		if ($result->num_rows > 0) {
			// fetch_object liefert genau eine Zeile
			// Um alle Zeilen einer Select zu erhalten muss dies schleifenweise erfolgen.
			while ($resultObj = $result->fetch_object()) {
				var_dump($resultObj);
				echo "<br><br>";
			}
		}
		?>
		
		<h1>Statements</h1>
		<?php
		// Formulierung einer SQL-Anweisung mit Platzhaltern
		$sql = "SELECT User_RealForename, User_RealSurname FROM bde_user WHERE User_Root = ?";
		
		// Wir benutzen hier das gleiche Objekt für die Datenbankverbindung wie oben und holen uns das Statement-Objekt.
		$statement = $mysql->prepare($sql);
		
		// Platzhalter besetzen
		$isRoot = 1;
		// Hier muss eine Variable wie $isRoot übergeben werden, da die Methode eine Referenz erwartet.
		// i steht hier für den Datentyp - in diesem Fall Integer
		$statement->bind_param("i", $isRoot);
		
		// Die nachfolgende Syntax ist identisch mit den zwei vorangegangenen Zeilen
//		$statement->bind_param("i", $varReference = 1);
		
		// Ausführung der SQL-Anweisung
		$statement->execute();
		
		// Wenn Statements für Select-Anweisungen verwendet werden, 
		// dann müssen die einzelnen Spalten an Variablenreferenzen gekoppelt werden
		$statement->bind_result($forename, $surname);
		
		while ($statement->fetch()) {
			echo "Name: " . $forename . "&nbsp;" . $surname . "<br>";
		}
		
		// Schließen des Statements
		$statement->close();
		
		?>
    </body>
</html>
