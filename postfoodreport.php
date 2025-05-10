<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['ReserverName']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once 'db.php';

// Define filter options for status
$statuses = ['Claimed', 'Available'];

// Function to generate the report based on the status
function generateReport($status) {
    global $conn;

    // Query to fetch data based on status
    $sql = "SELECT food_id, food_name, Qty, Description, location, pickuptime, Contactinformation
                FROM postfood
                WHERE status = '$status'";

    $result = $conn->query($sql);

    // Initialize summary variables
    $totalQty = 0;
    $totalRecords = 0;
    $reportHTML = ''; // Initialize an empty string to build the report HTML

    // Check if results exist
    if ($result->num_rows > 0) {
        $reportHTML .= "<h3>Status: $status</h3>";
        $reportHTML .= "<table class='table table-bordered table-striped'>";
        $reportHTML .= "<thead><tr><th>Food ID</th><th>Food Name</th><th>Quantity</th><th>Description</th><th>Location</th><th>Pickup Time</th><th>Contact</th></tr></thead><tbody>";

        // Loop through the results and display them
        while($row = $result->fetch_assoc()) {
            $reportHTML .= "<tr>
                                <td>" . $row["food_id"] . "</td>
                                <td>" . $row["food_name"] . "</td>
                                <td>" . $row["Qty"] . "</td>
                                <td>" . $row["Description"] . "</td>
                                <td>" . $row["location"] . "</td>
                                <td>" . $row["pickuptime"] . "</td>
                                <td>" . $row["Contactinformation"] . "</td>
                            </tr>";

            // Update the summary data
            $totalQty += $row["Qty"];
            $totalRecords++;
        }

        $reportHTML .= "</tbody></table>";
        $reportHTML .= "<b>Summary for Status: $status</b><br>";
        $reportHTML .= "Total Records: $totalRecords<br>";
        $reportHTML .= "Total Quantity: $totalQty<br>";
    } else {
        $reportHTML .= "<p>No records found for status: $status</p>";
    }

    return $reportHTML; // Return the generated HTML
}

// Process the AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $status = $_POST['status'];
    // Check if status is valid
    if (in_array($status, $statuses)) {
        echo generateReport($status); // Echo the generated report HTML
    } else {
        echo "<p>Invalid status selected. Please try again.</p>";
    }
    exit; // Important to exit after processing the AJAX request
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Food Status Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Post Food Status Report</h1>

    <div class="mb-3">
        <label for="status-select" class="form-label">Select Status:</label>
        <select id="status-select" class="form-select" onchange="loadReport()">
            <option value="">--Select Status--</option>
            <option value="Claimed">Claimed</option>
            <option value="Available">Available</option>
        </select>
    </div>

    <div id="report-content"></div>

    <button id="print-btn" class="btn btn-primary mt-3" style="display:none;" onclick="printReport()">Print Report</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Function to load the report based on selected status
function loadReport() {
    const status = document.getElementById('status-select').value;

    if (status) {
        // Fetch the report content using AJAX
        const formData = new FormData();
        formData.append('status', status);

        fetch('postfoodstatus.php', {
            method: 'POST',
            body: formData,
            headers: {
        'Cache-Control': 'no-cache', // Disable caching
        'Pragma': 'no-cache',
        'Expires': '0'
    }
        })
        .then(response => response.text())
        .then(data => {
            // Update the report content dynamically
            document.getElementById('report-content').innerHTML = data;

            // Show the Print button only if the report has content (i.e., records are found)
            const printButton = document.getElementById('print-btn');
            if (data.trim() === '' || data.includes('No records found')) {
                printButton.style.display = 'none'; // Hide print button if no records or empty content
            } else {
                printButton.style.display = 'block'; // Show print button if records are found
            }
        })
        .catch(error => {
            document.getElementById('report-content').innerHTML = '<p>Error loading the report. Please try again.</p>';
            document.getElementById('print-btn').style.display = 'none'; // Hide print button on error
        });
    } else {
        document.getElementById('report-content').innerHTML = '';
        document.getElementById('print-btn').style.display = 'none'; // Hide print button if no status is selected
    }
}

// Function to print the report
function printReport() {
    const printContent = document.getElementById('report-content').innerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print Report</title>');
    printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">'); // Include Bootstrap CSS for printing
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>

</body>
</html>