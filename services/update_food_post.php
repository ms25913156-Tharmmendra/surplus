<?php
session_start();
require_once '../db.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Plain‑text error
    echo 'error: invalid request method';
    exit;
}

// Collect and validate inputs
$post_id     = isset($_POST['post_id'])     ? intval($_POST['post_id']) : 0;
$qty         = isset($_POST['qty'])         ? intval($_POST['qty'])     : null;
$description = isset($_POST['description']) ? trim($_POST['description']): '';
$location    = isset($_POST['location'])    ? trim($_POST['location'])   : '';
$pickup_time = isset($_POST['pickup_time']) ? trim($_POST['pickup_time']) : '';

// Basic sanity checks
if ($post_id <= 0 || $qty === null || $location === '' || $pickup_time === '') {
    echo 'error: missing or invalid parameters';
    exit;
}

// Convert datetime-local (YYYY‑MM‑DDTHH:MM) → MySQL DATETIME
// If pickuptime includes seconds, your JS should send them. Otherwise append :00.
$pickup_time = str_replace('T', ' ', $pickup_time) . ':00';

// Prepare and execute update
$sql = "
    UPDATE postfood
    SET Qty = ?, 
        Description = ?, 
        location = ?, 
        pickuptime = ?
    WHERE food_id = ?
";
if ($stmt = $conn->prepare($sql)) {
    // bind: i = int, s = string
    $stmt->bind_param(
        'isssi',
        $qty,
        $description,
        $location,
        $pickup_time,
        $post_id
    );

    if ($stmt->execute()) {
        echo 'success';
    } else {
        // echo only plain text—no HTML
        echo 'error: ' . $stmt->error;
    }

    $stmt->close();
} else {
    echo 'error: ' . $conn->error;
}

$conn->close();
