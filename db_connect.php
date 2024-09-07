<?php
// db_connect.php

$servername = "localhost"; // or your database server address
$username = "root";
$password = "";
$dbname = "car_sale_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// else{
//     die ("Connect");
// }
?>
