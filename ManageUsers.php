<?php
require_once 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['userId'])) {
        $userId = intval($_POST['userId']);
        $action = $_POST['action'];

        if ($action === 'activate') {
            $status = 1;
            $message = "User activated successfully.";
        } else if ($action === 'deactivate') {
            $status = 0;
            $message = "User deactivated successfully.";
        }

        // Update user status
        $sql = "UPDATE users SET status = ? WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $status, $userId);

        if ($stmt->execute()) {
            $successMessage = $message;
        } else {
            $errorMessage = "Error updating user: " . $conn->error;
        }

        $stmt->close();
    }
}

// Process user search/filter
$searchTerm = "";
$roleFilter = "";

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

if (isset($_GET['role']) && $_GET['role'] !== '') {
    $roleFilter = $_GET['role'];
}
$sql = "SELECT * FROM users WHERE 1=1";

if (!empty($searchTerm)) {
    $searchTerm = "%{$searchTerm}%";
    $sql .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ? OR location LIKE ?)";
}

if (!empty($roleFilter)) {
    $sql .= " AND role = ?";
}

$sql .= " ORDER BY userID DESC";

$stmt = $conn->prepare($sql);

// Bind parameters if search term or role filter exists
if (!empty($searchTerm) && !empty($roleFilter)) {
    $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $roleFilter);
} elseif (!empty($searchTerm)) {
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
} elseif (!empty($roleFilter)) {
    $stmt->bind_param("s", $roleFilter);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .user-table th {
            background-color: #f8f9fa;
        }

        .status-badge {
            width: 100px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .search-box {
            max-width: 300px;
        }

        .role-filter {
            max-width: 200px;
        }

        .success-alert {
            animation: fadeOut 5s forwards;
            animation-delay: 3s;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-box2-heart-fill me-2"></i>Food Surplus</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="bi bi-house-door-fill"></i> Home</a></li>
                    <?php if ($_SESSION['role'] == 'poster' || $_SESSION['role'] == 'admin') { ?>
                        <li class="nav-item"><a class="nav-link" href="Updatepostfood.php"><i class="bi bi-plus-circle-fill"></i> Add Food</a></li>
                        <li class="nav-item"><a class="nav-link" href="ManageUsers.php"><i class="bi bi-plus-circle-fill"></i> Manage Users</a></li>
                    <?php } ?>
                    <li class="nav-item"><a class="nav-link" href="View.php"><i class="bi bi-eye-fill"></i> View Food</a></li>
                    <li class="nav-item"><a class="nav-link" href="myclaims.php"><i class="bi bi-bag-check-fill"></i> My Claims</a></li>
                </ul>
                <div class="d-flex">
                    <div class="dropdown">
                        <a class="btn btn-outline-light dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> <?php echo $_SESSION['ReserverName'] . "-" . $_SESSION['role']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person"></i> My Profile</a></li>
                            <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear"></i> Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container-fluid p-4">
        <div class="row mb-4">
            <div class="col">
                <h5><i class="fas fa-users me-2"></i> User Management</h5>
                <p class="text-muted">Manage user accounts and status</p>
            </div>
        </div>

        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success success-alert" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-4">

            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <form action="" method="GET" class="d-flex">
                            <div class="input-group search-box me-2">
                                <input type="text" class="form-control" placeholder="Search users..." name="search" value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="input-group role-filter">
                                <select class="form-select" name="role" onchange="this.form.submit()">
                                    <option value="" <?php echo empty($roleFilter) ? 'selected' : ''; ?>>All Roles</option>
                                    <option value="admin" <?php echo $roleFilter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="poster" <?php echo $roleFilter === 'poster' ? 'selected' : ''; ?>>Poster</option>
                                    <option value="seeker" <?php echo $roleFilter === 'seeker' ? 'selected' : ''; ?>>Seeker</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary" id="activateSelectedBtn" disabled>
                                <i class="fas fa-check-circle me-1"></i> Activate Selected
                            </button>
                            <button type="button" class="btn btn-outline-danger" id="deactivateSelectedBtn" disabled>
                                <i class="fas fa-ban me-1"></i> Deactivate Selected
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover user-table">
                        <thead>
                            <tr>
                                <th width="40">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $userId = $row["userID"];
                                    $userName = htmlspecialchars($row["name"]);
                                    $userEmail = htmlspecialchars($row["email"]);
                                    $userPhone = htmlspecialchars($row["phone"]);
                                    $userLocation = htmlspecialchars($row["location"]);
                                    $userRole = htmlspecialchars($row["role"]);
                                    $userStatus = $row["status"];

                                    // Determine status badge color
                                    $statusBadgeClass = $userStatus == 1 ? 'bg-success' : 'bg-danger';
                                    $statusText = $userStatus == 1 ? 'Active' : 'Inactive';

                                    // Determine role badge color
                                    $roleBadgeClass = '';
                                    switch ($userRole) {
                                        case 'admin':
                                            $roleBadgeClass = 'bg-dark';
                                            break;
                                        case 'poster':
                                            $roleBadgeClass = 'bg-primary';
                                            break;
                                        case 'seeker':
                                            $roleBadgeClass = 'bg-info';
                                            break;
                                        default:
                                            $roleBadgeClass = 'bg-secondary';
                                    }
                            ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input user-checkbox" type="checkbox" value="<?php echo $userId; ?>">
                                            </div>
                                        </td>
                                        <td><?php echo $userId; ?></td>
                                        <td><?php echo $userName; ?></td>
                                        <td><?php echo $userEmail; ?></td>
                                        <td><?php echo $userPhone; ?></td>
                                        <td><?php echo $userLocation; ?></td>
                                        <td><span class="badge <?php echo $roleBadgeClass; ?>"><?php echo ucfirst($userRole); ?></span></td>
                                        <td><span class="badge status-badge <?php echo $statusBadgeClass; ?>"><?php echo $statusText; ?></span></td>
                                        <td class="action-buttons">
                                            <?php if ($userStatus == 1): ?>
                                                <form action="" method="POST" class="d-inline">
                                                    <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                                                    <input type="hidden" name="action" value="deactivate">
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to deactivate this user?')">
                                                        <i class="fas fa-ban"></i> Deactivate
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form action="" method="POST" class="d-inline">
                                                    <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                                                    <input type="hidden" name="action" value="activate">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check-circle"></i> Activate
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                        </td>
                                    </tr>


                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="9" class="text-center py-4">No users found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <span class="text-muted">Showing <?php echo $result->num_rows; ?> users</span>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <form id="bulkActionForm" action="" method="POST" style="display: none;">
        <input type="hidden" name="userIds" id="bulkUserIds">
        <input type="hidden" name="action" id="bulkAction">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });

            updateBulkButtons();
        });

        document.querySelectorAll('.user-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkButtons);
        });

        function updateBulkButtons() {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            const activateBtn = document.getElementById('activateSelectedBtn');
            const deactivateBtn = document.getElementById('deactivateSelectedBtn');

            if (checkedBoxes.length > 0) {
                activateBtn.disabled = false;
                deactivateBtn.disabled = false;
            } else {
                activateBtn.disabled = true;
                deactivateBtn.disabled = true;
            }
        }

        // Bulk activate
        document.getElementById('activateSelectedBtn').addEventListener('click', function() {
            executeBulkAction('activate');
        });

        // Bulk deactivate
        document.getElementById('deactivateSelectedBtn').addEventListener('click', function() {
            executeBulkAction('deactivate');
        });

        // Execute bulk action
        function executeBulkAction(action) {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            const userIds = Array.from(checkedBoxes).map(cb => cb.value);

            if (userIds.length === 0) return;

            if (action === 'deactivate' && !confirm('Are you sure you want to deactivate the selected users?')) {
                return;
            }

            document.getElementById('bulkUserIds').value = userIds.join(',');
            document.getElementById('bulkAction').value = action;
            document.getElementById('bulkActionForm').submit();
        }

        window.addEventListener('load', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.success-alert');
                alerts.forEach(alert => {
                    alert.style.display = 'none';
                });
            }, 5000);
        });
    </script>
</body>

</html>
<?php
$stmt->close();
$conn->close();
?>