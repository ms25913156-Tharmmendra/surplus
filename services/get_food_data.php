<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "error"           => "Invalid request method",
        "draw"            => 1,
        "recordsTotal"    => 0,
        "recordsFiltered" => 0,
        "data"            => []
    ]);
    exit;
}

$role  = $_SESSION['role'] ?? 'seeker';
$draw  = isset($_POST['draw'])  ? intval($_POST['draw'])  : 1;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length= isset($_POST['length'])? intval($_POST['length']): 10;
$search= $conn->real_escape_string($_POST['search']['value'] ?? '');
$order_col_idx = intval($_POST['order'][0]['column'] ?? 0);
$order_dir     = strtolower($_POST['order'][0]['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

$location_filter = $conn->real_escape_string($_POST['location']   ?? '');
$food_filter     = $conn->real_escape_string($_POST['food_type']  ?? '');
$date_filter     = $conn->real_escape_string($_POST['date']       ?? '');

$columns = [
    0 => 'food_id',
    1 => 'food_name',
    2 => 'Qty',
    3 => 'Description',
    4 => 'location',
    5 => 'pickuptime',
    6 => 'Contactinformation'
];
$order_column = $columns[$order_col_idx] ?? $columns[0];

// Build WHERE
$where = "WHERE status = 'Available'";
if ($search !== '') {
    $where .= " AND (
        food_name LIKE '%{$search}%' OR
        Description LIKE '%{$search}%' OR
        location LIKE '%{$search}%' OR
        Contactinformation LIKE '%{$search}%'
    )";
}
if ($location_filter !== '') {
    $where .= " AND location = '{$location_filter}'";
}
if ($food_filter !== '') {
    $where .= " AND food_name = '{$food_filter}'";
}
if ($date_filter !== '') {
    $where .= " AND DATE(pickuptime) <= '{$date_filter}'";
}

// Total records
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM postfood");
$totalRecords= $totalResult->fetch_assoc()['total'];

// Filtered count
$filteredResult = $conn->query("SELECT COUNT(*) AS total FROM postfood {$where}");
$filteredRecords= $filteredResult->fetch_assoc()['total'];

// Fetch page
$sql = "
    SELECT food_id, food_name, Qty, Description, location, pickuptime, Contactinformation
    FROM postfood
    {$where}
    ORDER BY {$order_column} {$order_dir}
    LIMIT {$start}, {$length}
";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    if ($role === 'poster') {
        $action_buttons = sprintf(
            '<button class="btn btn-success btn-sm edit-btn"
                data-id="%d"
                data-name="%s"
                data-qty="%d"
                data-description="%s"
                data-location="%s"
                data-pickuptime="%s"
            >Edit</button>',
            $row['food_id'],
            htmlspecialchars($row['food_name'], ENT_QUOTES),
            $row['Qty'],
            htmlspecialchars($row['Description'], ENT_QUOTES),
            htmlspecialchars($row['location'], ENT_QUOTES),
            htmlspecialchars($row['pickuptime'], ENT_QUOTES)
        );
    } else {
        $action_buttons = sprintf(
            '<button class="btn btn-success btn-sm claim-btn"
                data-id="%d"
                data-name="%s"
                data-qty="%d"
                data-contact="%s"
            >Claim</button>',
            $row['food_id'],
            htmlspecialchars($row['food_name'], ENT_QUOTES),
            $row['Qty'],
            htmlspecialchars($row['Contactinformation'], ENT_QUOTES)
        );
    }

    $data[] = [
        "id"          => $row['food_id'],
        "name"        => htmlspecialchars($row['food_name'], ENT_QUOTES),
        "quantity"    => $row['Qty'],
        "description" => htmlspecialchars($row['Description'], ENT_QUOTES),
        "location"    => htmlspecialchars($row['location'], ENT_QUOTES),
        "pickupTime"  => htmlspecialchars($row['pickuptime'], ENT_QUOTES),
        "contact"     => htmlspecialchars($row['Contactinformation'], ENT_QUOTES),
        "actions"     => $action_buttons
    ];
}

$response = [
    "draw"            => $draw,
    "recordsTotal"    => $totalRecords,
    "recordsFiltered" => $filteredRecords,
    "data"            => $data
];

$conn->close();
echo json_encode($response);
