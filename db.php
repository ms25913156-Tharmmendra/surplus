<?php
$host = "localhost";
$user = "placeslk_surplusadmin";  // Change if needed
// $user = "root";  // Change if needed
$pass = "sgf6RTQwYAcxcc";
// $pass = "apppass";
$dbname = "placeslk_surplus";
// $dbname = "surplus";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
