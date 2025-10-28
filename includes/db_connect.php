<?php
$host = "localhost";
$user = "root"; 
$password = "Nethu@1129"; 
$db_name = "tourlanka_db";


$conn = new mysqli($host, $user, $password, $db_name , 3308);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$conn->set_charset("utf8");


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

