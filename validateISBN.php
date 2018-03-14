<?php
/**
 * Created by IntelliJ IDEA.
 * User: Merle
 * Date: 08.03.2018
 * Time: 22:22
 */
function validateISBN($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$content = file_get_contents("php://input");
$decoded = json_decode($content, true);
print (json_last_error() == JSON_ERROR_NONE);
echo validateISBN($decoded->{"isbn"});

//echo validateISBN("456");
?>