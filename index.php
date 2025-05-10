<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Surplus Food Management System</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #e9f5e9;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: #2e7d32;
    }
    .navbar-brand, .nav-link {
      color: #fff !important;
    }
    .carousel-item img {
      height: 400px;
      width: 70%;
      object-fit: contain;
      object-position: top;
    }
    .hero-text {
      position: absolute;
      top: 40%;
      left: 10%;
      color: #fff;
      background-color: rgba(46, 125, 50, 0.7);
      padding: 20px;
      border-radius: 10px;
    }
    .hero-text1 {
      position: absolute;
      top: 40%;
      left: 70%;
      color: #fff;
      background-color: rgba(46, 125, 50, 0.7);
      padding: 20px;
      border-radius: 10px;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#">Surplus Food System</a>
      <div class="ms-auto">
        <a href="login.php" class="btn btn-light">Login</a>
      </div>
    </div>
  </nav>

  <!-- Carousel -->
  <div id="foodCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="images/food1.jpg" class="d-block w-100" alt="Surplus food 1">
        <div class="hero-text">
          <h2>Help Reduce Food Waste</h2>
          <p>Join hands to make a difference!</p>
        </div>
        <div class="hero-text1">
          <h2>Donate Surplus Food</h2>
          <p>Donors can easily post extra food items with location and quantity so they don't go to waste.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="images/food2.jpg" class="d-block w-100" alt="Surplus food 2">
        <div class="hero-text">
          <h2>Distribute to the Needy</h2>
          <p>Your surplus can feed someone today.</p>
        </div>
        <div class="hero-text1">
          <h2>Donate Surplus Food</h2>
          <p>Donors can easily post extra food items with location and quantity so they don't go to waste.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="images/food3.jpg" class="d-block w-100" alt="Surplus food 3">
        <div class="hero-text">
          <h2>Connect. Claim. Care.</h2>
          <p>Easy reservation system for surplus food.</p>
        </div>
        <div class="hero-text1">
          <h2>Collect the reserved Food</h2>
          <p>Our system connects communities and ensures surplus food is collected to those who truly need it.</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#foodCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#foodCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
  </div>
  <div class="container my-5">
  <h2 class="text-center mb-4 text-success fw-bold">How Our System Helps</h2>
  <div class="row text-center">
    
    <!-- Column 1 -->
    <div class="col-md-4 mb-4">
      <img src="images/donate.png" alt="Donate Food" class="img-fluid rounded mb-3" style="height: 200px; object-fit: cover;">
      <h5 class="text-success">Post Surplus Food</h5>
      <p>Donors can easily post extra food items with location and quantity so they don't go to waste.</p>
    </div>

    <!-- Column 2 -->
    <div class="col-md-4 mb-4">
      <img src="images/reserve.png" alt="Reserve Food" class="img-fluid rounded mb-3" style="height: 200px; object-fit: cover;">
      <h5 class="text-success">Reserve What You Need</h5>
      <p>People in need can view and reserve available food items in real-time through a simple interface.</p>
    </div>

    <!-- Column 3 -->
    <div class="col-md-4 mb-4">
      <img src="images/collect.png" alt="Collect Food" class="img-fluid rounded mb-3" style="height: 200px; object-fit: cover;">
      <h5 class="text-success">Collect the Reserved Food</h5>
      <p>Our system connects communities and ensures surplus food is collected to those who truly need it.</p>
    </div>

  </div>
</div>


  <!-- Footer -->
  <footer class="text-center p-3 mt-4" style="background-color: #2e7d32; color: white;">
    &copy; 2025 Surplus Food Management System
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
