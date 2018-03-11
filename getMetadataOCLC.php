<?php
/**
 * Created by IntelliJ IDEA.
 * User: Merle
 * Date: 08.03.2018
 * Time: 22:22
 */
require_once './My_MySQLi.php';

function askOCLC($isbn)
{
    $format = "json";
    $method = "getMetadata";

    $url = "http://xisbn.worldcat.org/webservices/xid/isbn/".$isbn."?method=".$method."&format=".$format."&fl=*";
    $result = file_get_contents($url);
    
    $decode = json_decode($result);
    
    if ($decode->stat == "ok") {
        return $result;
    } else {
        return "noResult";
    }
 
}

function getResults($isbn) {
    $result = "0";
    $inDB = false;
    //set inDB true if IBSN in DB
    if ($inDB == true) {
        //return JSON-Object of DB
        return "TODO find objekt in DB";
    } else {
        $result = askOCLC($isbn);
        if ($result == "noResult") {
            return "noResult";
        } else {
            //openDB
            $mysql = new My_MySQLi("localhost", "root", "", "isbnMetadata");
            //fill metadata in variables
            //addslashes to escape special characters
            //unfornutately I could not get mysqli_real_escape_string to work
            $decoded = json_decode($result);
            $book = $decoded->list;
            $publisher = addslashes($book[0]->publisher);
            $lang= addslashes($book[0]->lang);
            $city= addslashes($book[0]->city);
            $author= addslashes($book[0]->author);
            $ed= addslashes($book[0]->ed);
            $year= addslashes($book[0]->year);
            $isbn= $book[0]->isbn;
            $isbn = addslashes($isbn[0]);
            $title= addslashes($book[0]->title);
            
            //open DB
            $sql = "INSERT INTO metadata (publisher,lang,city,author,ed,year,isbn,title) VALUES ('$publisher','$lang','$city','$author','$ed','$year','$isbn','$title')";
            $mysql->query($sql);           
            return $result;
        }
    }
    
    
}

print getResults("389721105X");
//$input = $_GET['isbn'];
//getResults($input);
?>