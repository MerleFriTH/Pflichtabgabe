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
    $result;
    $inDB = "false";
    //set inDB true if IBSN in DB
    if ($inDB == true) {
        //return JSON-Object of DB
        return "nothing";
    } else {
        $result = askOCLC($isbn);
        if ($result == "noResult") {
            return "noResult";
        } else {
            return $result;
        }
    }
    
    
}

echo getResults("389721105X");
?>
<br>
<?php
echo getResults("3897211051");

?>