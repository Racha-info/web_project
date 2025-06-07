<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

require 'db.php';

$sql = "SELECT s.first_name, s.last_name, p.title AS project_title 
        FROM student_project_wishlist w
        JOIN students s ON w.student_id = s.id
        JOIN projects p ON w.project_id = p.id
        ORDER BY s.last_name, s.first_name";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Student Wish Lists</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

  /* Base styles */
  body {
    margin: 0; padding: 0;
    font-family: 'Poppins', sans-serif;
    background: #e6f0f3; 
    color: #12343b; 
  }

  .container {
    max-width: 900px;
    margin: 60px auto;
    background: #f9fbfc; 
    border-radius: 20px;
    box-shadow:
      0 8px 24px rgba(18, 52, 59, 0.15),
      inset 0 0 30px rgba(0, 127, 145, 0.05);
    padding: 40px 60px;
  }

  h2 {
    text-align: center;
    font-weight: 600;
    font-size: 2.4rem;
    color: #0d3948; 
    margin-bottom: 40px;
    letter-spacing: 0.07em;
    text-transform: uppercase;
  }

  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 14px;
    font-size: 1rem;
  }

  thead tr {
    background: linear-gradient(90deg, #007c8a, #004d56);
    color: #f5d76e; 
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.1em;
  }

  th, td {
    padding: 18px 25px;
    text-align: left;
  }

  tbody tr {
    background: #e6f0f3;
    border-radius: 16px;
    box-shadow:
      0 6px 15px rgba(0, 124, 138, 0.15);
    transition: background 0.3s ease, box-shadow 0.3s ease;
  }

  tbody tr:hover {
    background: #d0e4e8;
    box-shadow:
      0 10px 25px rgba(0, 124, 138, 0.3);
  }

  /* Round corners on cells for row effect */
  tbody tr td:first-child {
    border-top-left-radius: 16px;
    border-bottom-left-radius: 16px;
  }
  tbody tr td:last-child {
    border-top-right-radius: 16px;
    border-bottom-right-radius: 16px;
  }

  /* Back button */
  .button-row {
    display: flex;
    justify-content: flex-start;
    margin-bottom: 30px;
  }

  .back-button {
    background: linear-gradient(135deg, #007c8a, #004d56);
    color: #f5d76e;
    font-weight: 700;
    padding: 14px 42px;
    border-radius: 30px;
    text-decoration: none;
    box-shadow:
      0 6px 20px rgba(0, 124, 138, 0.4);
    transition: background 0.3s ease, box-shadow 0.3s ease;
    user-select: none;
    letter-spacing: 0.08em;
    display: inline-block;
  }

  .back-button:hover {
    background: linear-gradient(135deg, #004d56, #007c8a);
    box-shadow:
      0 8px 28px rgba(0, 124, 138, 0.7);
  }

  /* Responsive */
  @media (max-width: 640px) {
    .container {
      padding: 30px 25px;
    }
    h2 {
      font-size: 1.8rem;
    }
    th, td {
      padding: 14px 12px;
    }
    .back-button {
      padding: 12px 28px;
      font-size: 0.9rem;
    }
  }
</style>
</head>
<body>

<div class="container">
  <div class="button-row">
    <a href="admin_dashboard.php" class="back-button">← Back to Dashboard</a>
  </div>

  <h2>Student Wish Lists</h2>

  <table>
    <thead>
      <tr>
        <th>Student Name</th>
        <th>Project Title</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
          <td><?= htmlspecialchars($row['project_title']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>
