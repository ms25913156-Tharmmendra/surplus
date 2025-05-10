<?php
session_start();
// Database connection
include 'db.php';

// PHPMailer
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";
$showLoginButton = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if user with this email exists
    $stmt = $conn->prepare("SELECT userID FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $temporaryPassword = bin2hex(random_bytes(4));
        $hashedPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT);

        // Update password in DB
        $update = $conn->prepare("UPDATE users SET password = ?, is_temp_password = 1 WHERE email = ?");

        //$update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update->bind_param("ss", $hashedPassword, $email);
        $update->execute();

        // Send email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'surplus.jaffna@gmail.com';
            $mail->Password = 'zxpixhkdrezymlho'; // Use your Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
         
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            $mail->setFrom('surplus.jaffna@gmail.com', 'Surplus System');
            $mail->addAddress($email);
            $mail->addReplyTo('surplus.jaffna@gmail.com', 'Surplus Support');
            $mail->isHTML(true);
            $mail->Subject = 'Your Temporary Password';
            $mail->Body = "
                <p>Hello,</p>
                <p>Here is your temporary password: <strong>$temporaryPassword</strong></p>
                <p>Please log in and change your password immediately.</p>
                <p>Thank you.</p> <br>Please click the link below to change your password:<br><a href='http://localhost/surplus/changepassword.php?email=$email'>Change Password</a><br><br>Thank you.";
            $mail->AltBody = "Hello,\n\nHere is your temporary password: $temporaryPassword\n\nPlease log in and change your password immediately.\n\nThank you.";

            $mail->send();
            $message = "<div class='alert alert-success'>Temporary password sent to your email.</div>";
            $showLoginButton = true;
            
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>Mailer Error: " . $mail->ErrorInfo . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Email not found in our system.</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body class="container" style="margin-top: 50px;">
    <h2>Forgot Password</h2>

    <?php if (!empty($message)) echo $message; ?>

    <?php if ($showLoginButton): ?>
        <div class="text-center" style="margin-top: 20px;">
            <a href="login.php" class="btn btn-primary">
                <span class="glyphicon glyphicon-log-in"></span> Go to Login
            </a>
        </div>
    <?php endif; ?>

    <form method="POST" class="form-group" style="margin-top: 30px;">
        <label for="email">Enter your email address:</label>
        <input type="email" name="email" class="form-control" placeholder="Email" required><br>
        <button type="submit" class="btn btn-success">
            <span class="glyphicon glyphicon-send"></span> Submit
        </button>
    </form>
</body>
</html>
