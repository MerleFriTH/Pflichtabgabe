<?php
error_reporting(E_ALL);
/**
 * Created by IntelliJ IDEA.
 * User: Merle
 * Date: 08.03.2018
 * Time: 22:22
 */
require_once './My_MySQLi.php';

function askOCLC($isbn) {
    $format = "json";
    $method = "getMetadata";

    $url = "http://xisbn.worldcat.org/webservices/xid/isbn/" . $isbn . "?method=" . $method . "&format=" . $format . "&fl=*";
    $result = file_get_contents($url);

    $decode = json_decode($result);

    if ($decode->stat != "ok") {
        $result = "noResult";
    }
    return $result;
}

function sendResult($inputisbn) {
    $mysql = new My_MySQLi("localhost", "root", "", "isbnMetadata");
    $selectSQL1 = "SELECT * FROM metadata WHERE isbn='$inputisbn'";

    $resultDB = $mysql->query($selectSQL1);

    while ($row = $resultDB->fetch_array(MYSQL_ASSOC)) {
        $myArray[] = $row;
    }
    $json = json_encode($myArray);
    return $json;
}

function getResults($inputisbn) {
    //openDB
    $mysql = new My_MySQLi("localhost", "root", "", "isbnMetadata");
    $selectSQL = "SELECT * FROM metadata WHERE isbn='$inputisbn'";
    $sqlResult = $mysql->query($selectSQL);
    //echo var_dump($sqlResult);
    $numRows = mysqli_num_rows($sqlResult);
    //echo $numRows;
    $returnvalue = "0";

    if ($numRows == 0) {
        $result = askOCLC($inputisbn);
        //echo $result;
        if ($result == "noResult") {
            $returnvalue = "noResult";
        } else {
            //fill metadata in variables
            //addslashes to escape special characters
            //unfornutately I could not get mysqli_real_escape_string to work
            //as the isbn should be unique, I do not look into book[1] etc
            $decoded = json_decode($result);
            $book = $decoded->list;
            $publisher = addslashes($book[0]->publisher);
            $lang = addslashes($book[0]->lang);
            $city = addslashes($book[0]->city);
            $author = addslashes($book[0]->author);
            $ed = addslashes($book[0]->ed);
            $year = addslashes($book[0]->year);
            $isbn = $book[0]->isbn;
            $isbn = addslashes($isbn[0]);
            $title = addslashes($book[0]->title);

            //open DB
            $insertSQL = "INSERT INTO metadata (publisher,lang,city,author,ed,year,isbn,title) VALUES ('$publisher','$lang','$city','$author','$ed','$year','$isbn','$title')";
            $mysql->query($insertSQL);
            $returnvalue = sendResult($inputisbn);
        }
    } else {
            $returnvalue = sendResult($inputisbn);
            //return $result;
    }
    return $returnvalue;
}

echo getResults("3827315352");
?>