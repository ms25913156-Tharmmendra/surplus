<?php
session_start();
$reserverName = $_SESSION['ReserverName'] ?? 'Guest';
$reserverContact = $_SESSION['ReserverContact'] ?? 'Unknown';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $foodName = $_POST['foodName'];
    $quantity = intval($_POST['quantity']);
    $description = $_POST['description'];
    $pickupLocation = $_POST['pickupLocation'];
    $pickupTime = $_POST['pickupTime'];
    $contactInfo = $_POST['contactInfo'];
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;

    if (empty($foodName) || empty($quantity) || empty($pickupLocation) || empty($pickupTime) || empty($contactInfo)) {
        die("All fields are required!");
    }

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO postfood (food_name, Qty, Description, location, pickuptime, Contactinformation, status, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $status = 'Available';
    $stmt->bind_param("sisssssdd", $foodName, $quantity, $description, $pickupLocation, $pickupTime, $contactInfo, $status, $latitude, $longitude);

    if ($stmt->execute()) {

        // -------- Notify seekers after posting ----------
        $notifyUrl = 'http://localhost/surplus/services/notifyuser.php';
        $payload = http_build_query([
            'food_name' => $foodName,
            'qty'       => $quantity
        ]);

        $ch = curl_init($notifyUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);

        error_log("Notify user response: $response");
        // -------- End of notify logic -------------------

        header("Location: Updatepostfood.php?success=1");
        exit();
    } else {
        echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Surplus Food</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"> <!-- Added! -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-gray);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="datetime-local"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--medium-gray);
            border-radius: 6px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
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

        .location-btn {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 18px;
            padding: 5px;
            transition: color 0.3s;
        }

        .location-btn:hover {
            color: var(--secondary-color);
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
            margin-top: 10px;
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
  <!-- Navigation bar to add at the top of index.php -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
  
    <a class="navbar-brand" href="index.php">
      <i class="bi bi-box2-heart-fill me-2"></i> &nbsp;  &nbsp; Food Surplus
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="dashboard.php">
            <i class="bi bi-house-door-fill"></i> Home
          </a>
        </li>

        <?php if($_SESSION['role'] == 'poster' || $_SESSION['role'] == 'admin'){?>

        <li class="nav-item">
          <a class="nav-link" href="Updatepostfood.php">
            <i class="bi bi-plus-circle-fill"></i> Add Food
          </a>
        </li>

            <?php } ?>
        <li class="nav-item">
          <a class="nav-link" href="View.php">
            <i class="bi bi-plus-circle-fill"></i> View Food
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="myclaims.php">
            <i class="bi bi-bag-check-fill"></i> My Claims
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
             data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-info-circle-fill"></i> More
          </a>
         
        </li>
      </ul>
      
      <div class="d-flex">
        <div class="dropdown">
          <a class="btn btn-outline-light dropdown-toggle" href="#" role="button" 
             id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i> <?php echo $_SESSION['ReserverName']."-".$_SESSION['role']; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person"></i> My Profile</a></li>
            <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear"></i> Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
          </ul>
        </div>
      </div> &nbsp; &nbsp;
    </div>
 
</nav>


    <div class="container">
        <h1>Post Surplus Food Details</h1>
        
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div style="padding: 15px; background-color: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;" id="success-message">
        ✅ Food Deails posted successful!
    </div>
    <script>
        // Auto-hide the success message after 4 seconds
        setTimeout(() => {
            const msg = document.getElementById('success-message');
            if (msg) msg.style.display = 'none';
        }, 4000);
    
    </script>
<?php endif; ?>
        <?php
        // Display success/error messages if they exist
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Messages are displayed from PHP code above
        }
        ?>

        <form id="foodForm" method="POST" action="">
            <div class="form-group">
                <label for="foodName">Food Name</label>
                <input type="text" id="foodName" name="foodName" placeholder="e.g., Pizza, Sandwiches" required>
            </div>
            
            <div class="form-group">
                <label for="quantity">Quantity (servings)</label>
                <input type="number" id="quantity" name="quantity" min="1" placeholder="How many people can this feed?" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Describe the food (ingredients, dietary info, etc.)" required></textarea>
            </div>
            
            <div class="form-group">
    <label for="pickupLocation">Pickup Location</label>
    <div class="input-wrapper">
        <input type="text" id="pickupLocation" name="pickupLocation" placeholder="Enter address or click the icon" required>
        <button type="button" class="location-btn" title="Use Device Location" id="get_curr_location">
            <i class="fas fa-map-marker-alt"></i>
        </button>
    </div>
    <button type="button" class="btn btn-outline-success btn-sm mt-2" onclick="fetchCoordinates()">📍 Get Coordinates</button>
</div>
            
            <div class="form-group">
                <label for="pickupTime">Pickup Time</label>
                <input type="datetime-local" id="pickupTime" name="pickupTime" required>
            </div>
            
            <div class="form-group">
                <label for="contactInfo">Contact Information</label>
                <input type="text" id="contactInfo" name="contactInfo" placeholder="Phone or email" required>
            </div>
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <button type="submit">Post Food Details</button>
        </form>
    </div>

    <script>
function fetchCoordinates() {
    const address = document.getElementById('pickupLocation').value;
    const apiKey = 'pk.d5058fb639eac5a24a8e45cf0a137d51'; // Your LocationIQ token

    if (!address) {
        alert("Please enter a valid address first.");
        return;
    }

    fetch(`https://us1.locationiq.com/v1/search?key=${apiKey}&q=${encodeURIComponent(address)}&format=json`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const lat = data[0].lat;
                const lon = data[0].lon;
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lon;
             //  alert(`Coordinates fetched:\nLatitude: ${lat}\nLongitude: ${lon}`);
            } else {
                alert('No results found. Try being more specific.');
            }
        })
        .catch(error => {
            console.error('Error fetching coordinates:', error);
            alert('Failed to fetch coordinates. Please try again.');
        });
}

document.getElementById('get_curr_location').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lon;


        }, function() {
            alert("Geolocation is not supported by this browser.");
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
});
</script>


</body>
</html>