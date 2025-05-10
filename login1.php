<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch is_temp_password too
    $stmt = $conn->prepare("SELECT userID, name, phone, password, is_temp_password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($userID, $name, $phone, $hashed_password, $is_temp);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['ReserverName'] = $name;
            $_SESSION['ReserverContact'] = $phone;
            $_SESSION['email'] = $email;
            $_SESSION['userID'] = $userID;
            $_SESSION['role']
            if ($is_temp == 1) {
                // Redirect to change password if using temp password
                header("Location: changepassword.php");
            } else {
                // Normal dashboard redirection
                header("Location: dashboard.php");
            }
            exit;
        } else {
            echo "<script>alert('Incorrect password'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found'); window.location.href='login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="container1">
    <img src="images/logo.png" class="imagecenter" alt="Center Image" width="20%" height="20%">

    <h2>Login</h2>
    <form method="POST">
        <input type="email" name="email" class="form-control" placeholder="Email" required><br>
        <input type="password" name="password" class="form-control" placeholder="Password" required><br>
        <button type="submit" class="btn btn-success">
            <span class="glyphicon glyphicon-log-in"></span> Login
        </button><br><br>

        <a href="forgotpassword.php" class="btn btn-warning">Forgot Password?</a><br><br>

        <p>Don't have an account? Are you going to Register? If so, <a href="register.php"> Click here to register</a></p>
    </form>
</body>
</html>
