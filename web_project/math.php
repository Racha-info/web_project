<?php
// Include database connection configuration
include 'db.php';

// Fetch the latest 10 announcements for the Math department, ordered by datetime descending
$query = "SELECT title, content, datetime FROM announcements WHERE display='math' ORDER BY datetime DESC LIMIT 10";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Math Department - Student Portal</title>

  <!-- Bootstrap CSS for responsive design and styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <!-- Bootstrap Icons for UI iconography -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

<style>
  /* Page-wide styles */
  body {
    background: linear-gradient(135deg, #f0f8ff, #ffffff);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #1a8e8c;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  /* Header styling */
  header {
    background-color: #1a8e8c;
    color: #f0f8ff;
    padding: 2rem 1rem;
    text-align: center;
    box-shadow: 0 6px 20px rgba(26, 142, 140, 0.3);
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

  /* Icon styling */
  .bi {
    font-size: 3rem;
    color: #1a8e8c;
  }

  /* Container for announcements */
  .container {
    flex-grow: 1;
    max-width: 900px;
    margin-bottom: 4rem;
  }

  /* Individual announcement card styling */
  .announcement-card {
    background: #e9f9f9;
    border-radius: 15px;
    border: 1px solid #a0d1d0;
    box-shadow: 0 4px 18px rgba(26, 142, 140, 0.08);
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
    transition: box-shadow 0.3s ease;
  }

  .announcement-card:hover {
    box-shadow: 0 8px 30px rgba(26, 142, 140, 0.15);
  }

  /* Announcement title styling */
  .announcement-title {
    font-weight: 700;
    font-size: 1.5rem;
    margin-bottom: 0.4rem;
  }

  /* Announcement date styling */
  .announcement-date {
    font-style: italic;
    color: #146d6b;
    margin-bottom: 1rem;
    font-size: 1.1rem;
  }

  /* Announcement content styling */
  .announcement-content {
    font-size: 1.25rem;
    line-height: 1.5;
  }

  /* Footer styling */
  footer {
    background-color: #1a8e8c;
    color: #f0f8ff;
    text-align: center;
    padding: 1rem 0;
    font-weight: 600;
    letter-spacing: 1px;
    user-select: none;
    box-shadow: 0 -4px 20px rgba(26, 142, 140, 0.35);
  }

  /* Back button styling */
  a.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    margin-bottom: 2rem;
    color: #1a8e8c;
    font-weight: 700;
    text-decoration: none;
    border: 2px solid #1a8e8c;
    border-radius: 30px;
    padding: 0.5rem 1.8rem;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    box-shadow: 0 2px 6px rgba(26, 142, 140, 0.2);
    transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    user-select: none;
  }

  a.btn-back:hover {
    background-color: #1a8e8c;
    color: #f0f8ff;
    box-shadow:
      0 0 10px #1a8e8c,
      0 0 15px #146d6b;
  }

  a.btn-back .bi {
    font-size: 1.25rem;
    transition: filter 0.3s ease;
  }

  a.btn-back:hover .bi {
    filter: drop-shadow(0 0 3px #f0f8ff);
  }
</style>

</head>
<body>

<header>
  <h1><i class="bi bi-calculator"></i> Math Department</h1>
</header>

<div class="container">

  <!-- Navigation link back to department selection -->
  <a href="index.php" class="btn-back"><i class="bi bi-arrow-left-circle"></i> Back to Departments</a>

  <?php
  // Check if there are announcements to display
  if ($result && mysqli_num_rows($result) > 0) {
    // Loop through each announcement and display it in a styled card
    while ($row = mysqli_fetch_assoc($result)) {
      echo '<div class="announcement-card">';
      echo '<div class="announcement-title">' . htmlspecialchars($row['title']) . '</div>';
      echo '<div class="announcement-date">' . date('F j, Y, g:i a', strtotime($row['datetime'])) . '</div>';
      echo '<div class="announcement-content">' . nl2br(htmlspecialchars($row['content'])) . '</div>';
      echo '</div>';
    }
  } else {
    // Show a message if no announcements are available
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
// Close database connection
mysqli_close($conn);
?>
