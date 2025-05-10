<?php
session_start();
include 'db.php';

$reserverName = $_SESSION['ReserverName'] ?? 'Guest';
$reserverContact = $_SESSION['ReserverContact'] ?? 'Unknown';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reserverName = $_POST['reserver_name'] ?? ($reserverName ?? 'Guest');
    $reserverContact = $_POST['reserver_contact'] ?? ($reserverContact ?? 'Unknown');
    $foodName = $_POST['food_name'] ?? '';
    $qty = $_POST['qty'] ?? '';
    $donarContact = $_POST['donar_contact'] ?? '';
    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : null;
    $reservedDate = date("Y-m-d H:i:s");
    $status = "Pending";

    if ($postId !== null) {
        // Insert with foreign key to postfood
        $insertStmt = $conn->prepare("INSERT INTO reservedFood (ReserverName, FoodName, Qty, DonarContactNo, ReserverContact, ReservedDate, Status, PostFoodID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param("ssissssi", $reserverName, $foodName, $qty, $donarContact, $reserverContact, $reservedDate, $status, $postId);
    } else {
        echo "Error: Missing post ID.";
        exit;
    }

    if ($insertStmt->execute()) {
        $insertStmt->close();

        // Update status of the food post
        $updateStmt = $conn->prepare("UPDATE postfood SET status = 'Claimed' WHERE food_id = ?");
        $updateStmt->bind_param("i", $postId);
        $updateStmt->execute();
        $updateStmt->close();

        echo "success";
    } else {
        echo "Error: " . $insertStmt->error;
    }
}
$conn->close();
?>
