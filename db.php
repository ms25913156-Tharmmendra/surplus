<?php
$host = "localhost";
$user = "placeslk_surplusadmin";  // Change if needed
$pass = "sgf6RTQwYAcxcc";
$dbname = "placeslk_surplus";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
