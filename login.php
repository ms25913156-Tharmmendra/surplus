<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch userID, name, phone, hashed password, is_temp_password, and role
    $stmt = $conn->prepare("
        SELECT userID, name, phone, password, is_temp_password, role 
        FROM users 
        WHERE email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Bind the results, including the new $role field
        $stmt->bind_result($userID, $name, $phone, $hashed_password, $is_temp, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Store all needed session vars
            $_SESSION['userID']          = $userID;
            $_SESSION['ReserverName']    = $name;
            $_SESSION['ReserverContact'] = $phone;
            $_SESSION['email']           = $email;
            $_SESSION['role']            = $role;  // New line!

            // If they’re on a temp password, force change
            if ($is_temp == 1) {
                header("Location: changepassword.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $error_message = "Incorrect password";
        }
    } else {
        $error_message = "Email not found";
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
    <!-- Bootstrap CSS (v3.4.1) -->
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
    >
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .error-message {
            color: red;
            background-color: #f8d7da;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 15px;
            display: none;
        }
    </style>
</head>
<body class="container1">
    <img
      src="images/logo.png"
      class="imagecenter"
      alt="Center Image"
      width="20%"
      height="20%"
    >

    <h2>Login</h2>

    <!-- Display error message if there's any -->
    <?php if (isset($error_message)): ?>
        <div id="error-message" class="error-message">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <input
              type="email"
              name="email"
              class="form-control"
              placeholder="Email"
              required
            >
        </div>

        <div class="form-group">
            <input
              type="password"
              name="password"
              class="form-control"
              placeholder="Password"
              required
            >
        </div>

        <button type="submit" class="btn btn-success">
            <span class="glyphicon glyphicon-log-in"></span> Login
        </button><br><br>

        <a href="forgotpassword.php" class="btn btn-warning">Forgot Password?</a><br><br>

        <p>
            Don't have an account? 
            <a href="register.php">Click here to register</a>
        </p>
    </form>

    <!-- Bootstrap JS Bundle -->
    <script
      src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"
    ></script>

    <script>
        // If there's an error message, show it and hide after 5 seconds
        <?php if (isset($error_message)): ?>
            var errorMessage = document.getElementById("error-message");
            errorMessage.style.display = "block";  // Show the error message
            setTimeout(function() {
                errorMessage.style.display = "none";  // Hide after 5 seconds
            }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>
