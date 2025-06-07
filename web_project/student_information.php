<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// DB connection
$host = "localhost";
$dbname = "web_project";
$dbuser = "root";
$dbpass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// Get student info
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Get wishlist projects
$wishStmt = $pdo->prepare("
    SELECT p.title, p.description
    FROM projects p
    JOIN student_project_wishlist w ON p.id = w.project_id
    WHERE w.student_id = :student_id
");
$wishStmt->execute(['student_id' => $_SESSION['user_id']]);
$wishlist = $wishStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Student Info</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(to right, #e0f7fa, #ffffff);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #004d66;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .container {
      max-width: 800px;
      margin: 40px auto;
      position: relative;
      flex-grow: 1;
    }
    .card {
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 77, 102, 0.2);
      transition: transform 0.3s ease;
      margin-bottom: 30px;
      font-size: 1.15rem; /* Increased font size */
    }
    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 14px 35px rgba(0, 77, 102, 0.3);
    }
    .card-header {
      background: #004d66;
      color: white;
      font-size: 1.5rem; /* Bigger header text */
      font-weight: bold;
      border-top-left-radius: 16px;
      border-top-right-radius: 16px;
    }
    .wishlist-item {
      border-bottom: 1px solid #cce0e4;
      padding: 12px 0;
    }
    .wishlist-item:last-child {
      border-bottom: none;
    }
    .wishlist-item p {
      margin: 6px 0;
    }
    .btn-logout {
      background: #004d66;
      color: white;
      border-radius: 30px;
      padding: 10px 24px;
      font-weight: bold;
      text-decoration: none;
      transition: background 0.3s ease;
    }
    .btn-logout:hover {
      background: #007080;
      color: white;
    }
    .logout-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
    }
    footer {
      background: #004d66;
      color: white;
      text-align: center;
      padding: 12px 0;
      font-size: 1rem;
      margin-top: auto;
      box-shadow: 0 -5px 15px rgba(0, 77, 102, 0.3);
    }
  </style>
</head>
<body>

<div class="logout-container">
  <form action="logout.php" method="post" style="display:inline;">
    <button type="submit" class="btn btn-logout">Logout</button>
  </form>
</div>

<div class="container">
  
  <!-- Student Info Box -->
  <div class="card">
    <div class="card-header text-center">
      Student Information
    </div>
    <div class="card-body">
      <p><strong>Full Name:</strong> <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
    </div>
  </div>

  <!-- Project Wishlist Box -->
  <div class="card">
    <div class="card-header text-center">
      Final-Year Project Wishlist
    </div>
    <div class="card-body">
      <?php if (count($wishlist) === 0): ?>
        <p class="text-muted">You haven't selected any projects yet.</p>
      <?php else: ?>
        <ul class="list-unstyled">
          <?php foreach ($wishlist as $project): ?>
            <li class="wishlist-item">
              <p><strong>Project Title:</strong> <?= htmlspecialchars($project['title']) ?></p>
              <p><strong>Description:</strong> <?= htmlspecialchars($project['description']) ?></p>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>

  <div class="text-center mt-4">
    <a href="student_dashboard.php" class="btn btn-logout">← go to Dashboard</a>
  </div>
</div>

<footer>
  &copy; <?= date('Y') ?>  2025 Student Portal — All rights reserved.
</footer>

</body>
</html>
