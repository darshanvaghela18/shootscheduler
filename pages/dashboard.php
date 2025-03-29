<?php
session_start();

// Agar user logged in nahi hai, toh login page par redirect karna
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// User details session se fetch karna
$name = $_SESSION['name']; // User ka name fetch karna
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShootScheduler Dashboard</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Header -->
  <header class="header">
    <div class="left-section">
      <h1>ShootScheduler</h1>
    </div>
    <div class="center-section">
      <p>Welcome, <strong><?php echo htmlspecialchars($name); ?></strong></p>
    </div>
    <div class="right-section">
      <a href="../server/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <section class="card-container">
     <!-- My Projects -->
      <div class="card">
        <i class="fas fa-clipboard-list card-icon"></i>
        <h2>My Projects</h2>
        <p>Manage and oversee all your movie projects efficiently.</p>
        <a href="../pages/my-projects.php" class="card-btn"></a>
      </div>
      <!-- Scheduling -->
      <div class="card">
        <i class="fas fa-calendar-alt card-icon"></i>
        <h2>Scheduling</h2>
        <p>Create and manage your shooting schedules easily.</p>
        <a href="../pages/schedule.php" class="card-btn"></a>
      </div>
      <!-- Budget Tracking -->
      <div class="card">
        <i class="fas fa-coins card-icon"></i>
        <h2>Budget Tracking</h2>
        <p>Track expenses and budgets for each project effortlessly.</p>
        <a href="../pages/project-list.php" class="card-btn"></a>
      </div>
      <!-- Document Management -->
      <div class="card">
        <i class="fas fa-file-alt card-icon"></i>
        <h2>Document </h2>
        <p>Organize and access your project documents with ease.</p>
        <a href="../pages/document-list.php" class="card-btn"></a>
      </div>
      <!-- Upcoming Projects -->
      <div class="card">
        <i class="fas fa-film card-icon"></i>
        <h2>Upcoming Projects</h2>
        <p>Stay updated with upcoming movie releases and events.</p>
        <a href="../pages/upcoming-projects.php" class="card-btn"></a>
      </div>
      <!-- Add Project -->
      <div class="card">
        <i class="fas fa-plus card-icon"></i>
        <h2>Add Project</h2>
        <p>Start a new project by providing all necessary details.</p>
        <a href="../pages/add-project.php" class="card-btn"></a>
      </div>
     

     <div class="card">
        <i class="card-icon fas fa-comments"></i>
        <h2>Support Chat</h2>
        <p>Chat with Admin</p>
        <a href="user-message.php"></a>
      </div>

    </section>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <p>© 2025 ShootScheduler | Designed with ❤️ for Movie Creators</p>
    <!-- <div class="footer-links">
      <a href="#privacy">Privacy Policy</a>
      <a href="#terms">Terms of Service</a>
      <a href="#contact">Contact Us</a>
    </div> -->
  </footer>

  <!-- <script src="../js/dashboard.js"></script> -->
</body>
</html>
