<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

require 'db.php'; 

$message = "";

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $stmt = mysqli_prepare($conn, "DELETE FROM projects WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $delete_id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "✅ Project deleted successfully.";
    } else {
        $message = "❌ Error deleting project: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

$result = mysqli_query($conn, "SELECT id, title, description FROM projects ORDER BY id DESC");
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Projects List</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

  body {
    background: #e6f0f3;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 40px 20px;
    color: #12343b;
  }

  .container {
    max-width: 900px;
    margin: auto;
    background: #f9fbfc;
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(18, 52, 59, 0.15);
    padding: 40px 60px;
  }

  h2 {
    text-align: center;
    font-weight: 600;
    font-size: 2.4rem;
    color: #0d3948;
    margin-bottom: 40px;
    text-transform: uppercase;
    letter-spacing: 0.07em;
  }

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
    box-shadow: 0 6px 20px rgba(0, 124, 138, 0.4);
    transition: background 0.3s ease, box-shadow 0.3s ease;
  }

  .back-button:hover {
    background: linear-gradient(135deg, #004d56, #007c8a);
    box-shadow: 0 8px 28px rgba(0, 124, 138, 0.7);
  }

  .message {
    padding: 15px;
    border-left: 6px solid #d4af37;
    background: #f0f9fa;
    font-weight: 600;
    color: #004d56;
    border-radius: 12px;
    margin-bottom: 25px;
  }

  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 14px;
  }

  thead tr {
    background: linear-gradient(90deg, #007c8a, #004d56);
    color: #f5d76e;
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
    box-shadow: 0 6px 15px rgba(0, 124, 138, 0.15);
    transition: background 0.3s ease, box-shadow 0.3s ease;
  }

  tbody tr:hover {
    background: #d0e4e8;
    box-shadow: 0 10px 25px rgba(0, 124, 138, 0.3);
  }

  tbody td:first-child {
    border-top-left-radius: 16px;
    border-bottom-left-radius: 16px;
  }

  tbody td:last-child {
    border-top-right-radius: 16px;
    border-bottom-right-radius: 16px;
  }

  form.delete-form {
    display: inline;
  }

  .delete-btn {
    padding: 10px 20px;
    background-color: #f5d76e;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    color: #004d56;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .delete-btn:hover {
    background-color: #e4c63d;
  }

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
    .delete-btn {
      width: 100%;
      font-size: 1rem;
      padding: 12px;
    }
  }
</style>
<script>
  function confirmDelete() {
    return confirm("Are you sure you want to delete this project?");
  }
</script>
</head>
<body>
<div class="container">
  <div class="button-row">
    <a href="admin_dashboard.php" class="back-button">← Back to Dashboard</a>
  </div>

  <h2>Projects List</h2>

  <?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($project = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($project['id']) ?></td>
          <td><?= htmlspecialchars($project['title']) ?></td>
          <td><?= htmlspecialchars($project['description']) ?></td>
          <td>
            <form method="post" class="delete-form" onsubmit="return confirmDelete()">
              <input type="hidden" name="delete_id" value="<?= $project['id'] ?>">
              <button type="submit" class="delete-btn">Delete</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
