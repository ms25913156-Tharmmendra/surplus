<?php
// services/notifyuser.php
require_once '../vendor/autoload.php';  // Twilio SDK

use Twilio\Rest\Client;
require_once '../db.php';               // Your DB connection


// ————— Twilio setup —————
$sid    = "AC2911b31aa4bed132847b25ead739de08";
//$token  = "30f1909694994efb59b2611836e524ba";
$token="95c9c26b59669ad6177d339fb5cc5372";
$twilio = new Client($sid, $token);
$from   = '+19404488901';  // your Twilio number

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'error: invalid request method';
    exit;
}

// Pull parameters
$foodName = trim($_POST['food_name'] ?? '');
$qty      = trim($_POST['qty']       ?? '');

if ($foodName === '' || $qty === '') {
    http_response_code(400);
    echo 'error: missing food_name or qty';
    exit;
}

// Build the message
$body = "New food posted: {$qty} x {$foodName}. Please claim if needed.";

// Query only seekers
$sql = "SELECT phone FROM users WHERE role = 'seeker' AND phone <> ''";
$res = $conn->query($sql);

if (! $res) {
    echo 'error: ' . $conn->error;
    exit;
}

$sent = 0;
while ($row = $res->fetch_assoc()) {
    try {
        $twilio->messages->create(
            $row['phone'],
            [ 'from' => $from, 'body' => $body ]
        );
        $sent++;
    } catch (Exception $e) {
        // optionally log $e->getMessage()
    }
}

echo "success: notified {$sent} seekers";
$conn->close();
?>
