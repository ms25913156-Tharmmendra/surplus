<?php
session_start();
include 'db.php';

// Fetch all food posts
$sql = "SELECT * FROM postfood ORDER BY food_id DESC";
$result = $conn->query($sql);

$reserverName = $_SESSION['ReserverName'] ?? 'Guest';
$reserverContact = $_SESSION['ReserverContact'] ?? 'Unknown';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Food Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--primary-color);
            font-weight: 600;
        }

        .post-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .food-card {
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
        }

        .food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .food-name {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .food-detail {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .food-detail i {
            margin-right: 10px;
            color: var(--dark-gray);
            width: 20px;
            text-align: center;
        }

        .action-buttons {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn-secondary {
            background-color: var(--medium-gray);
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #d0d0d0;
        }

        .empty-message {
            text-align: center;
            grid-column: 1 / -1;
            padding: 40px;
            color: var(--dark-gray);
        }

        @media (max-width: 768px) {
            .post-grid {
                grid-template-columns: 1fr;
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


<div class="container py-4">
    <h1 class="text-center text-success mb-4">Available Food Posts</h1>
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="food-name"><?php echo htmlspecialchars($row['food_name']); ?></h5>
                            <p class="card-text">Description: <?php echo htmlspecialchars($row['Description']); ?></p>
                            <p class="card-text">Quantity: <?php echo htmlspecialchars($row['Qty']); ?></p>
                            <p class="card-text">Location: <?php echo htmlspecialchars($row['location']); ?></p>
                            <p class="card-text">Pickup by: <?php echo date('M j, Y g:i A', strtotime($row['pickuptime'])); ?></p>
                            <p class="card-text">Donor Contact: <?php echo htmlspecialchars($row['Contactinformation']); ?></p>
                            <?php if ($row['status'] !== 'Claimed'): ?>
                                <button 
                                    class="btn btn-success claim-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#claimModal"
                                    data-id="<?php echo $row['food_id']; ?>"
                                    data-name="<?php echo htmlspecialchars($row['food_name']); ?>"
                                    data-qty="<?php echo htmlspecialchars($row['Qty']); ?>"
                                    data-contact="<?php echo htmlspecialchars($row['Contactinformation']); ?>">
                                    Claim
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Reserved</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p>No food posts available.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Claim Modal -->
<div class="modal fade" id="claimModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="claimForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Claim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="post_id" id="modal-post-id">
                    <input type="hidden" name="donar_contact" id="modal-donar-contact">

                    <div class="mb-3">
                        <label>Food Name</label>
                        <input type="text" class="form-control" name="food_name" id="modal-food-name" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Quantity</label>
                        <input type="text" class="form-control" name="qty" id="modal-qty" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Donor Contact</label>
                        <input type="text" class="form-control" id="modal-donar-visible" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Your Name</label>
                        <input type="text" class="form-control" name="reserver_name" value="<?php echo htmlspecialchars($reserverName); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Your Contact</label>
                        <input type="text" class="form-control" name="reserver_contact" value="<?php echo htmlspecialchars($reserverContact); ?>" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Confirm Claim</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const claimBtns = document.querySelectorAll('.claim-btn');
    claimBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('modal-post-id').value = btn.dataset.id;
            document.getElementById('modal-food-name').value = btn.dataset.name;
            document.getElementById('modal-qty').value = btn.dataset.qty;
            document.getElementById('modal-donar-contact').value = btn.dataset.contact;
            document.getElementById('modal-donar-visible').value = btn.dataset.contact;
        });
    });

    document.getElementById('claimForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        fetch('reserve.php', {
            method: 'POST',
            body: formData
        }).then(res => res.text()).then(data => {
            if (data.trim() === 'success') {
                location.reload();
            } else {
                alert('Reservation failed: ' + data);
            }
        });
    });
</script>

</body>
</html>
