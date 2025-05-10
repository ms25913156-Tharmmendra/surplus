<?php
// Start session if needed
session_start();

// Database connection
include 'db.php';
// Create connection


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect and sanitize inputs
$pickupLocation = $conn->real_escape_string($_POST['pickupLocation']);
$latitude = $conn->real_escape_string($_POST['latitude']);
$longitude = $conn->real_escape_string($_POST['longitude']);

// Add more fields as needed
// For example:
$foodName = $conn->real_escape_string($_POST['food_name'] ?? '');
$qty = $conn->real_escape_string($_POST['qty'] ?? '');
$description = $conn->real_escape_string($_POST['description'] ?? '');
$pickuptime = $conn->real_escape_string($_POST['pickuptime'] ?? '');
$contact = $conn->real_escape_string($_POST['contact'] ?? '');
$status = "Available";

// Insert into postfood table
$sql = "INSERT INTO postfood (food_name, Qty, Description, location, pickuptime, Contactinformation, status, latitude, longitude)
        VALUES ('$foodName', '$qty', '$description', '$pickupLocation', '$pickuptime', '$contact', '$status', '$latitude', '$longitude')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Food post saved successfully!'); window.location.href='dashboard.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
