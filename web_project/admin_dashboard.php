<?php
session_start();
require_once 'db.php';

// Redirect to login if admin is not authenticated
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$admin_name = 'Admin';

// Fetch admin full name for greeting
$id = $_SESSION['admin_id'];
$sql = "SELECT full_name FROM admins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $admin_name = $row['full_name'];
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <style>
    /* ======= BODY & LAYOUT ======= */
    body {
      background: linear-gradient(to right, #e0f7fa, #ffffff);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #004d66;
    }

    .dashboard {
      max-width: 900px;
      margin: 60px auto;
      padding: 40px;
      background-color: #ffffff;
      border-radius: 16px;
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }

    /* ======= HEADING ======= */
    .dashboard h1 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 30px;
      color: #007080;
    }

    /* ======= NAVIGATION LIST ======= */
    .nav-list {
      list-style: none;
      padding: 0;
    }

    .nav-list li {
      margin-bottom: 18px;
    }

    .nav-list a {
      display: block;
      background: #e0f7fa;
      padding: 16px 20px;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      color: #004d66;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .nav-list a:hover {
      background: #b2ebf2;
      transform: translateX(6px);
    }

    /* ======= LOGOUT BUTTON ======= */
    .logout-btn {
      margin-top: 25px;
      display: inline-block;
      background-color: #004d66;
      color: #fff;
      font-weight: bold;
      padding: 12px 24px;
      border-radius: 30px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .logout-btn:hover {
      background-color: #007080;
    }
  </style>
</head>
<body>

  <div class="dashboard text-center">
    <h1>Welcome, <?= htmlspecialchars($admin_name); ?></h1>

    <ul class="nav-list text-start">
      <li><a href="create_announcement.php">📢 Create Announcement</a></li>
      <li><a href="delete_announcements.php">🗑️ Delete Announcements</a></li>  <!-- Delete announcements -->
      <li><a href="manage_projects.php">📂 Manage Final-Year Projects</a></li>
      <li><a href="view_student_wishlist.php">📝 View Student Wish Lists</a></li>
      <li><a href="admin_students_list.php">🎓 Projects List</a></li>
    </ul>

    <a href="logout.php" class="logout-btn">Logout</a>
  </div>

</body>
</html>
