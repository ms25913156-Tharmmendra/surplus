<?php
session_start();
include 'db.php';

// Optional: these session vars aren’t directly used in registration,
// but you may use them later when reserving food.
$reserverName    = $_SESSION['ReserverName']    ?? 'Guest';
$reserverContact = $_SESSION['ReserverContact'] ?? 'Unknown';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name             = htmlspecialchars(trim($_POST['name']));
    $phone            = htmlspecialchars(trim($_POST['phone']));
    $location         = htmlspecialchars(trim($_POST['location']));
    $email            = htmlspecialchars(trim($_POST['email']));
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role             = $_POST['role'];  // 'admin', 'poster', or 'seeker'

    // Password match check
    if ($password !== $confirm_password) {
        $message = "<div class='alert alert-danger'>Passwords do not match. Please try again.</div>";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and execute INSERT
        $stmt = $conn->prepare("
            INSERT INTO users (name, phone, location, email, password, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssss",
            $name,
            $phone,
            $location,
            $email,
            $hashed_password,
            $role
        );

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>
                Registration successful! <a href='login.php'>Login here</a>
            </div>";
        } else {
            $message = "<div class='alert alert-danger'>
                Error: " . htmlspecialchars($stmt->error) . "
            </div>";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    >
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --light-gray: #f5f5f5;
            --medium-gray: #e0e0e0;
            --dark-gray: #757575;
            --white: #ffffff;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
        }
        h1, h2 {
            text-align: center;
            color: var(--primary-color);
            font-weight: 600;
        }
        .imagecenter {
            display: block;
            margin: 0 auto 20px;
        }
        label {
            font-weight: 500;
        }
        .form-control, .btn {
            border-radius: 4px;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <img
          src="images/logo1.png"
          class="imagecenter"
          alt="Logo"
          width="20%"
          height="20%"
        >
        <h2>Register</h2>
        <?php
            // Display any messages from registration attempt
            if (!empty($message)) {
                echo $message;
            }
        ?>
        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input
                  type="text"
                  name="name"
                  class="form-control"
                  placeholder="Name"
                  required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input
                  type="text"
                  name="phone"
                  class="form-control"
                  placeholder="Phone"
                  required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input
                  type="text"
                  name="location"
                  class="form-control"
                  placeholder="Location / Address"
                  required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                  type="email"
                  name="email"
                  class="form-control"
                  placeholder="Email"
                  required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Register as:</label>
                <select
                  name="role"
                  class="form-select"
                  required
                >
                    <option value="poster">Food Poster</option>
                    <option value="seeker" selected>Food Seeker</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                  type="password"
                  name="password"
                  class="form-control"
                  placeholder="Password"
                  required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Re‑enter Password</label>
                <input
                  type="password"
                  name="confirm_password"
                  class="form-control"
                  placeholder="Re‑enter Password"
                  required
                >
            </div>

            <button
              type="submit"
              class="btn btn-primary w-100"
            >
                Register
            </button>
        </form>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    ></script>
</body>
</html>
