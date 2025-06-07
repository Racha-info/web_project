<?php
// Start the session to track admin login status and persist data across requests
session_start();

// Redirect to login page if admin is not authenticated
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Include database connection configuration
require 'db.php';

// Initialize message variable for user feedback
$message = '';

// Handle form submission when method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $display_location = $_POST['display_location'] ?? '';

    // Validate that all required fields are filled
    if ($title && $content && $display_location) {
        // Prepare an SQL statement to insert a new announcement with current timestamp
        $stmt = $conn->prepare("INSERT INTO announcements (title, content, display, datetime) VALUES (?, ?, ?, NOW())");
        if ($stmt) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("sss", $title, $content, $display_location);

            // Execute the insertion query and provide feedback
            if ($stmt->execute()) {
                // Store the last inserted announcement ID in session for later use
                $_SESSION['last_announcement_id'] = $conn->insert_id;

                $message = "✅ Announcement created successfully.";
            } else {
                // Handle execution errors
                $message = "❌ Error: " . $stmt->error;
            }

            // Close the statement to free resources
            $stmt->close();
        } else {
            // Handle preparation errors
            $message = "❌ Prepare failed: " . $conn->error;
        }
    } else {
        // Prompt user to complete all required fields
        $message = "⚠️ Please fill in all fields.";
    }
}

// Define the display category for announcements to fetch (customize as needed)
$display = 'computer_science';

// Retrieve last inserted announcement ID from session to exclude from display
$exclude_id = $_SESSION['last_announcement_id'] ?? 0;

// Prepare SQL statement to fetch announcements excluding the last inserted one
if ($exclude_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM announcements WHERE (display = ? OR display = 'general') AND id != ? ORDER BY datetime DESC");
    $stmt->bind_param("si", $display, $exclude_id);
} else {
    // If no last inserted ID, fetch all announcements matching display criteria
    $stmt = $conn->prepare("SELECT * FROM announcements WHERE display = ? OR display = 'general' ORDER BY datetime DESC");
    $stmt->bind_param("s", $display);
}

// Execute the fetch query
$stmt->execute();

// Retrieve the result set for use in HTML below
$result = $stmt->get_result();

?>
<!-- HTML portion starts here -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Create Announcement (Display: <?= htmlspecialchars($display) ?>)</title>

  <!-- Bootstrap CSS CDN for styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    /* Page and form container styles */
    body {
      padding: 40px;
      background: linear-gradient(to right, #f0faff, #ffffff);
      font-family: 'Segoe UI', sans-serif;
    }
    .form-container {
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      color: #004d66;
      margin-bottom: 25px;
    }
    .btn-submit {
      background-color: #004d66;
      color: white;
      font-weight: bold;
      border-radius: 8px;
    }
    .message {
      margin-top: 15px;
      text-align: center;
      font-weight: 600;
      color: #00695c;
    }
    .announcement {
      background: #e6f7ff;
      padding: 15px 20px;
      border-radius: 12px;
      margin-bottom: 20px;
      border-left: 5px solid #004d66;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Create Announcement</h2>

  <!-- Display feedback message after form submission -->
  <?php if (!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <!-- Announcement creation form -->
  <form method="post" autocomplete="off">
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" name="title" id="title" class="form-control" required />
    </div>

    <div class="mb-3">
      <label for="content" class="form-label">Content</label>
      <textarea name="content" id="content" rows="5" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
      <label for="display_location" class="form-label">Display Location</label>
      <select name="display_location" id="display_location" class="form-select" required>
        <option value="">Select Location</option>
        <option value="general">General</option>
        <option value="computer_science">Computer Science</option>
        <option value="math">Mathematics</option>
        <option value="physics">Physics</option>
        <option value="chemistry">Chemistry</option>
      </select>
    </div>

    <button type="submit" class="btn btn-submit w-100">Create Announcement</button>
  </form>

  <!-- Navigation back to admin dashboard -->
  <div class="mt-4 text-center">
    <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
  </div>
</div>

</body>
</html>
