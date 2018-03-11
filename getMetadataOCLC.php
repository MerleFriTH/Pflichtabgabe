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
            $mysql = new My_MySQLi("localhost", "root", "", "isbnMetadata");
            $sql = "INSERT INTO metadata (isbn, author) VALUES ('Test', 'Merle')";
            $mysql->query($sql);
            //$test = $mysql->query("SELECT * FROM metadata");
            return $result;
        }
    }
    
    
}
//$input = $_GET['isbn'];
//getResults($input);

print getResults("389721105X");
?>