<?php
$servername = "db4free.net";
$username = "divyam";  
$password = "divyam@1209"; 
$dbname = "edocbase"; 

$database = new mysqli($servername, $username, $password, $dbname);

if ($database->connect_error) {
    die("Connection Failed : " . $database->connect_error);
}
?>

