<?php
/**
 * Created by IntelliJ IDEA.
 * User: Merle
 * Date: 08.03.2018
 * Time: 22:22
 */
function validateISBN(){
    $isbn = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $isbn = test_input($_POST["isbn"]);
    }
    
    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;  
    }
}

echo validateISBN("389721105X");

?>