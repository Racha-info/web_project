<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

require 'db.php';

// Handle deletion if requested via GET with ?delete_id=...
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    // Optional: check that $delete_id is valid and belongs to announcement
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "✅ Announcement deleted successfully.";
    } else {
        $message = "❌ Error deleting announcement: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all announcements ordered by datetime DESC
$result = $conn->query("SELECT * FROM announcements ORDER BY datetime DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Manage Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            padding: 40px;
            background: linear-gradient(to right, #f0faff, #ffffff);
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        h2 {
            color: #004d66;
            margin-bottom: 25px;
            text-align: center;
        }
        .message {
            text-align: center;
            font-weight: 600;
            margin-bottom: 15px;
            color: #00695c;
        }
        table th, table td {
            vertical-align: middle !important;
        }
    </style>
    <script>
        function confirmDelete(title) {
            return confirm(`Are you sure you want to delete the announcement: "${title}"?`);
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Manage Announcements</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Display Location</th>
                <th>Date & Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['content'])) ?></td>
                    <td><?= htmlspecialchars($row['display']) ?></td>
                    <td><?= htmlspecialchars($row['datetime']) ?></td>
                    <td>
                        <a href="delete_announcements.php?delete_id=<?= $row['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirmDelete('<?= htmlspecialchars(addslashes($row['title'])) ?>')">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No announcements found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-4 text-center">
  <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
