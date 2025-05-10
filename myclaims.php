<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['ReserverName'])) {
    header("Location: login.php");
    exit;
}

$reserverName = $_SESSION['ReserverName'];

// Handle "Mark as Collected"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Id'])) {
    $claim_id = intval($_POST['Id']);
    $stmt = $conn->prepare("UPDATE reservedfood SET Status = 'Collected' WHERE id = ? AND ReserverName = ?");
    $stmt->bind_param("is", $claim_id, $reserverName);
    $stmt->execute();
    $stmt->close();
    header("Location: myclaims.php");
    exit;
}

// Fetch all claims for user
$stmt = $conn->prepare("SELECT * FROM reservedfood WHERE ReserverName = ?");
$stmt->bind_param("s", $reserverName);
$stmt->execute();
$result = $stmt->get_result();
$claims = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Claims</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
 <!-- Navigation bar -->
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
        <li class="nav-item">
          <a class="nav-link" href="Updatepostfood.php">
            <i class="bi bi-plus-circle-fill"></i> Add Food
          </a>
        </li>
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

    <div class="container mt-5">
        <h2>My Claims</h2>

        <?php if (empty($claims)): ?>
            <div class="alert alert-info">No claims found.</div>
        <?php else: ?>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Food Name</th>
                        <th>Quantity</th>
                        <th>Donor Contact</th>
                        <th>Your Contact</th>
                        <th>Reserved Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($claims as $claim): ?>
                        <tr>
                            <td><?= htmlspecialchars($claim['FoodName']) ?></td>
                            <td><?= htmlspecialchars($claim['Qty']) ?></td>
                            <td><?= htmlspecialchars($claim['DonarContactNo']) ?></td>
                            <td><?= htmlspecialchars($claim['ReserverContact']) ?></td>
                            <td><?= htmlspecialchars($claim['ReservedDate']) ?></td>
                            <td>
                                <?php if ($claim['Status'] === 'Pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Collected</span>
                                <?php endif; ?>
                            </td>
                            <td class="d-flex gap-2">
                            <?php if ($claim['Status'] === 'Pending'): ?>
                                    <form method="post">
                                        <input type="hidden" name="Id" value="<?= $claim['Id'] ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Mark as Collected</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Already Collected</button>
                                <?php endif; ?>

                                <?php if (!empty($claim['FoodName'])): ?>
                                    <a href="viewlocation.php?food=<?= urlencode($claim['FoodName']) ?>" target="_blank" class="btn btn-primary btn-sm">View Location</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
