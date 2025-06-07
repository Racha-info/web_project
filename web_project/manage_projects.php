<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

require 'db.php'; 

$message = "";

// Department values matching the ENUM exactly
$departments = ['computer_science', 'mathematics', 'physics', 'chemistry'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $department = $_POST['department'] ?? '';

    if ($title && $description && in_array($department, $departments)) {
        $title = mysqli_real_escape_string($conn, $title);
        $description = mysqli_real_escape_string($conn, $description);
        $department = mysqli_real_escape_string($conn, $department);

        $sql = "INSERT INTO projects (title, description, department) VALUES ('$title', '$description', '$department')";

        if (mysqli_query($conn, $sql)) {
            $message = "✅ Project added successfully.";

            // Clear form fields
            $_POST['title'] = '';
            $_POST['description'] = '';
            $_POST['department'] = '';
        } else {
            $message = "❌ Database error: " . mysqli_error($conn);
        }
    } else {
        $message = "❌ Please fill all fields and select a valid department.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Project</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e0f7fa;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 60px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-left: 8px solid #004d66;
        }

        h2 {
            color: #004d66;
            margin-bottom: 24px;
            font-size: 28px;
        }

        label {
            display: block;
            margin-top: 16px;
            font-weight: 600;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 12px 16px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            border-color: #004d66;
            outline: none;
        }

        .button-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 28px;
        }

        .back-button {
            padding: 14px 28px;
            background-color: #ccc;
            color: #333;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: background-color 0.3s;
            flex-shrink: 0;
            text-align: center;
        }

        .back-button:hover {
            background-color: #bbb;
        }

        button {
            background-color: #004d66;
            color: #fff;
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            flex-shrink: 0;
        }

        button:hover {
            background-color: #00394d;
        }

        .message {
            margin-top: 20px;
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 8px;
            color: #004d66;
            background-color: #e0f7fa;
            border-left: 4px solid #004d66;
        }

        .message.error {
            color: #d32f2f;
            background-color: #fdecea;
            border-left-color: #d32f2f;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>📁 Add a New Final-Year Project</h2>

        <form method="post">
            <label for="title">Project Title:</label>
            <input type="text" name="title" id="title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" />

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="5" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

            <label for="department">Department:</label>
            <select name="department" id="department" required>
    <option value="" selected disabled>Select Department</option>
    <?php foreach ($departments as $dept): ?>
        <option value="<?= htmlspecialchars($dept) ?>">
            <?= htmlspecialchars(ucwords(str_replace('_', ' ', $dept))) ?>
        </option>
    <?php endforeach; ?>
            </select>


            <div class="button-row">
                <button type="submit">➕ Add Project</button>
                <a href="admin_dashboard.php" class="back-button">← Back to Dashboard</a>
            </div>
        </form>

        <?php if (!empty($message)) : ?>
            <div class="message <?= strpos($message, 'successfully') === false ? 'error' : '' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
