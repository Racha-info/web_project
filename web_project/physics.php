<?php
include 'db.php';  

// Get announcements for physics dept
$query = "SELECT title, content, datetime FROM announcements WHERE display='physics' ORDER BY datetime DESC LIMIT 10";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Physics Department - Student Portal</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

<style>
  body {
    background: linear-gradient(135deg, #fff8e1, #ffffff);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #5789a3;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  header {
    background-color: #5789a3;
    color: #fff8e1;
    padding: 2rem 1rem;
    text-align: center;
    box-shadow: 0 6px 20px rgba(87, 137, 163, 0.3);
    margin-bottom: 2rem;
  }
  header h1 {
    font-weight: 800;
    font-size: 2.8rem;
    letter-spacing: 2px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.8rem;
  }
  .bi {
    font-size: 3rem;
    color: #a0b6c5;
  }
  .container {
    flex-grow: 1;
    max-width: 900px;
    margin-bottom: 4rem;
  }
  .announcement-card {
    background: #e7f0f8;
    border-radius: 15px;
    border: 1px solid #aec9db;
    box-shadow: 0 4px 18px rgba(87, 137, 163, 0.08);
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
    transition: box-shadow 0.3s ease;
  }
  .announcement-card:hover {
    box-shadow: 0 8px 30px rgba(87, 137, 163, 0.15);
  }
  .announcement-title {
    font-weight: 700;
    font-size: 1.5rem;
    margin-bottom: 0.4rem;
  }
  .announcement-date {
    font-style: italic;
    color: #406b82;
    margin-bottom: 1rem;
    font-size: 1.1rem;
  }
  .announcement-content {
    font-size: 1.25rem;
    line-height: 1.5;
  }
  footer {
    background-color: #5789a3;
    color: #fff8e1;
    text-align: center;
    padding: 1rem 0;
    font-weight: 600;
    letter-spacing: 1px;
    user-select: none;
    box-shadow: 0 -4px 20px rgba(87, 137, 163, 0.35);
  }
  a.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    margin-bottom: 2rem;
    color: #5789a3;
    font-weight: 700;
    text-decoration: none;
    border: 2px solid #5789a3;
    border-radius: 30px;
    padding: 0.5rem 1.8rem;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    box-shadow: 0 2px 6px rgba(87, 137, 163, 0.2);
    transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    user-select: none;
  }
  a.btn-back:hover {
    background-color: #5789a3;
    color: #fff8e1;
    box-shadow:
      0 0 10px #5789a3,
      0 0 15px #406b82;
  }
  a.btn-back .bi {
    font-size: 1.25rem;
    transition: filter 0.3s ease;
  }
  a.btn-back:hover .bi {
    filter: drop-shadow(0 0 3px #fff8e1);
  }
</style>

</head>
<body>

<header>
  <h1><i class="bi bi-lightning-charge"></i> Physics Department</h1>
</header>

<div class="container">

  <a href="index.php" class="btn-back"><i class="bi bi-arrow-left-circle"></i> Back to Departments</a>

  <?php
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo '<div class="announcement-card">';
      echo '<div class="announcement-title">' . htmlspecialchars($row['title']) . '</div>';
      echo '<div class="announcement-date">' . date('F j, Y, g:i a', strtotime($row['datetime'])) . '</div>';
      echo '<div class="announcement-content">' . nl2br(htmlspecialchars($row['content'])) . '</div>';
      echo '</div>';
    }
  } else {
    echo '<p class="text-center fs-5 mt-5">No announcements available for this department.</p>';
  }
  ?>

</div>

<footer>
  &copy; 2025 Student Portal — All rights reserved.
</footer>

</body>
</html>

<?php
mysqli_close($conn);
?>
