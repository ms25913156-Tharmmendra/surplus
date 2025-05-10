<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --accent-color: #FF5722;
            --light-gray: #f5f5f5;
            --medium-gray: #e0e0e0;
            --dark-gray: #757575;
            --white: #ffffff;
            --error-color: #f44336;
            --success-color: #4CAF50;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        .container {
            background-color: var(--white);
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--primary-color);
            font-weight: 600;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-gray);
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--medium-gray);
            border-radius: 6px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
            margin-bottom: 15px;
        }
        input:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: left;
            margin-bottom: 20px;
        }
        .stars input {
            display: none;
        }
        .stars label {
            font-size: 2rem;
            color: green;
            cursor: pointer;
            transition: color 0.2s;
        }
        .stars input:checked ~ label,
        .stars label:hover,
        .stars label:hover ~ label {
            color: gold;
        }
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button[type="submit"]:hover {
            background-color: var(--secondary-color);
        }
        .success-message {
            background-color: #dff0d8;
            color: var(--success-color);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        .error-message {
            background-color: #f8d7da;
            color: var(--error-color);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<?php
// Initialize message variable
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    if ($conn->connect_error) {
        $message = "<div class='error-message'>Connection failed: " . $conn->connect_error . "</div>";
    } else {
        $userId = intval($_POST['userId']);
        $donarContactNo = trim($_POST['donarContactNo']);
        $rating = intval($_POST['rating']);
        $comments = trim($_POST['comments']);
        $date = date('Y-m-d');

        $sql = "INSERT INTO Feedback (userID, DonarContactNo, Rating, Comments, Date)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                DonarContactNo = VALUES(DonarContactNo),
                Rating = VALUES(Rating),
                Comments = VALUES(Comments),
                Date = VALUES(Date)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isiss", $userId, $donarContactNo, $rating, $comments, $date);

        if ($stmt->execute()) {
            $message = "<div class='success-message'>Feedback submitted successfully!</div>";
        } else {
            $message = "<div class='error-message'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<div class="container">
    <h1>Submit Feedback</h1>

    <!-- Display success or error message -->
    <?php if (!empty($message)) echo $message; ?>

    <form action="feedback.php" method="POST">
        <label for="userId">Your User ID:</label>
        <input type="number" name="userId" required>

        <label for="donarContactNo">Donor Contact No:</label>
        <input type="text" name="donarContactNo" required>

        <label for="rating">Rating (1-5):</label>
        <div class="stars">
            <input type="radio" id="star5" name="rating" value="5" required/><label for="star5">★</label>
            <input type="radio" id="star4" name="rating" value="4"/><label for="star4">★</label>
            <input type="radio" id="star3" name="rating" value="3"/><label for="star3">★</label>
            <input type="radio" id="star2" name="rating" value="2"/><label for="star2">★</label>
            <input type="radio" id="star1" name="rating" value="1"/><label for="star1">★</label>
        </div>

        <label for="comments">Comments:</label>
        <textarea name="comments" required></textarea>

        <button type="submit">Submit Feedback</button>
    </form>
</div>

</body>
</html>
