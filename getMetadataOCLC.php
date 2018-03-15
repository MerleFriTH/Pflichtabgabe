<?php
error_reporting(E_ALL);
/**
 * Author: Merle Friedrichsen
 * Date: 08.03.2018
 */
require_once './My_MySQLi.php';

/* function validateISBN
 * param: string to validate
 * return: validates ISBN or false
 * validates serverinput, cleans data, checks for ISBN-pattern,
 * removes "-" in ISBN, in order to store an isbn only once (not twice: with and without -)
 */
function validateISBN($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $pattern="'^(?:(?=.{17}$)97[89][ -](?:[0-9]+[ -]){2}[0-9]+[ -][0-9]|97[89][0-9]{10}|(?=.{13}$)(?:[0-9]+[ -]){2}[0-9]+[ -][0-9Xx]|[0-9]{9}[0-9Xx])$'";
    if (preg_match($pattern, $data)==1){
        $return = str_replace("-","",$data); 
    } else {
        $return = false;
    }
    return $return;             
}

/* function askOCLC
 * param: isbn
 * return: response from OCLC
 * sends request to OCLC, return metadata for ISBN  (if exisits)
 */
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

/*function sendResult
 * param: ISBN
 * return: retuns JSON encoded result from database
 */
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

/*function getResults
 * param: isbn
 * return: return results from database, if no results in database,
 * gets metadata from OCLC and stores in database
 * always return the data from database -> so it has always the same parameters
 *  */
function getResults($inputisbn) {
    //openDB
    $mysql = new My_MySQLi("localhost", "root", "", "isbnMetadata");
    $selectSQL = "SELECT * FROM metadata WHERE isbn='$inputisbn'";
    $sqlResult = $mysql->query($selectSQL);
    $numRows = mysqli_num_rows($sqlResult);
    $returnvalue = "0";
    // check if there are data in the sqlResult
    //if not -> askOCLC
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
            //get the metadata from the database
            $returnvalue = sendResult($inputisbn);
        }
    } else {//if the data is alredy in the database, get it and return it
            $returnvalue = sendResult($inputisbn);
            //return $result;
    }
    return $returnvalue;
}

//get the data from the postrequest as json
$content = trim(file_get_contents("php://input"));
$decoded = json_decode($content, true);
$userinput = $decoded["isbn"];
//validate ISBN
$valISBN = validateISBN($userinput);
if ($valISBN != false) {
    echo getResults($valISBN);
} else {
    echo "noResult";
}
?>