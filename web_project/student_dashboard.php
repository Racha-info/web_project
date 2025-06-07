<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$host = "localhost";
$dbname = "web_project";
$dbuser = "root";
$dbpass = "";

$allowed_departments = [
    'all' => 'All Departments',
    'computer_science' => 'Computer Science',
    'mathematics' => 'Mathematics',
    'physics' => 'Physics',
    'chemistry' => 'Chemistry'
];

$selected_department_key = $_GET['department'] ?? $_POST['selected_department'] ?? 'all';
if (!array_key_exists($selected_department_key, $allowed_departments)) {
    $selected_department_key = 'all';
}

$message = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle removal of projects
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_projects'])) {
        foreach ($_POST['remove_projects'] as $project_id) {
            $pdo->prepare("DELETE FROM student_project_wishlist WHERE student_id = ? AND project_id = ?")
                ->execute([$student_id, $project_id]);
        }
        $message = "Selected projects have been removed from your wishlist.";
        header("Location: ?department=" . urlencode($selected_department_key) . "&message=" . urlencode($message));
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wishlist'])) {
        // Clear old wishlist
        $pdo->prepare("DELETE FROM student_project_wishlist WHERE student_id = ?")->execute([$student_id]);
        // Insert new wishlist
        foreach ($_POST['wishlist'] as $project_id) {
            $pdo->prepare("INSERT INTO student_project_wishlist (student_id, project_id) VALUES (?, ?)")->execute([$student_id, $project_id]);
        }
        $message = "Your wishlist has been updated successfully.";
        header("Location: ?department=" . urlencode($selected_department_key) . "&message=" . urlencode($message));
        exit();
    }

    if ($selected_department_key === 'all') {
        $stmt = $pdo->query("SELECT * FROM projects");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE department = ?");
        $stmt->execute([$selected_department_key]);
    }
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $wishlist_stmt = $pdo->prepare("SELECT project_id FROM student_project_wishlist WHERE student_id = ?");
    $wishlist_stmt->execute([$student_id]);
    $wishlist_ids = $wishlist_stmt->fetchAll(PDO::FETCH_COLUMN);

    // Get all wishlisted projects for the remove section
    $wishlisted_projects_stmt = $pdo->prepare("SELECT p.* FROM projects p JOIN student_project_wishlist w ON p.id = w.project_id WHERE w.student_id = ?");
    $wishlisted_projects_stmt->execute([$student_id]);
    $wishlisted_projects = $wishlisted_projects_stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['message'])) {
        $message = $_GET['message'];
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Project Wishlist</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(to right, #e0f7fa, #ffffff);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #004d66;
    }
    .container {
      max-width: 1200px;
      margin-top: 40px;
    }
    .title-box {
      text-align: center;
      margin-bottom: 30px;
    }
    .title-box h2 {
      font-size: 2rem;
      font-weight: 700;
      color: #004d66;
    }
    .back-btn {
      background-color: #006666;
      color: #e0f7f7;
      font-weight: 600;
      border: 2px solid #004d4d;
      border-radius: 35px;
      padding: 10px 24px;
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.25s ease, box-shadow 0.25s ease;
      box-shadow: 0 4px 8px rgba(0, 102, 102, 0.2);
      user-select: none;
    }
    .back-btn:hover {
      background-color: #004d4d;
      box-shadow: 0 6px 14px rgba(0, 77, 77, 0.4);
      color: #cceeee;
    }

    /* Filter buttons container */
    .filter-buttons {
      display: flex;
      flex-direction: column;
      gap: 20px;
      padding: 15px;
      background-color: #f8f9fa;
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Base button style */
    .filter-buttons .btn {
      margin: 5px 0;
      border-radius: 35px;
      font-weight: 700;
      font-size: 1.15rem;
      border: 2px solid #004d66;
      padding: 14px 0;
      background-color: white;
      color: #004d66;
      box-shadow: 0 4px 15px rgba(0, 77, 102, 0.1);
      text-transform: uppercase;
      letter-spacing: 1.4px;
      text-align: center;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 12px;
      width: 100%;
      transition:
        background-color 0.3s ease,
        color 0.3s ease,
        box-shadow 0.3s ease,
        transform 0.3s ease;
      cursor: pointer;
      user-select: none;
    }

    .filter-buttons .btn:hover {
      background-color: #004d66;
      color: white;
      box-shadow: 0 0 12px #004d66;
      transform: scale(1.05);
    }

    .filter-buttons .active-filter {
      background-color: #004d66 !important;
      color: white !important;
      box-shadow: 0 0 15px #004d66;
      transform: scale(1.05);
      font-weight: 900;
      border-color: transparent !important;
    }

    /* Department specific colors */
    .btn-computer_science {
      background-color: #007c91;
      border-color: #007c91;
      color: white;
    }
    .btn-computer_science:hover,
    .btn-computer_science.active-filter {
      background-color: #005f6b !important;
      border-color: #005f6b !important;
      color: white !important;
    }

    .btn-mathematics {
      background-color: #1a8e8c;
      border-color: #1a8e8c;
      color: white;
    }
    .btn-mathematics:hover,
    .btn-mathematics.active-filter {
      background-color: #146d6b !important;
      border-color: #146d6b !important;
      color: white !important;
    }

    .btn-physics {
      background-color: #5789a3;
      border-color: #5789a3;
      color: white;
    }
    .btn-physics:hover,
    .btn-physics.active-filter {
      background-color: #406b82 ;
      border-color: #406b82 !important;
      color: white !important;
    }

    .btn-chemistry {
      background-color: #5da26f;
      border-color: #5da26f;
      color: white;
    }
    .btn-chemistry:hover,
    .btn-chemistry.active-filter {
      background-color: #407e54 !important;
      border-color: #407e54 !important;
      color: white !important;
    }

 /* Wishlist cards */
.wishlist-card {
  background-color: #f0f9fa;
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 3px 10px rgba(0, 77, 102, 0.1);
  height: 100%;
  display: flex;
  align-items: center;
}

.wishlist-card .form-check-label {
  font-size: 1rem;      
  line-height: 1.4;
}

.wishlist-card .form-check-label strong {
  font-size: 1.3rem;    
  font-weight: 700;
}

.wishlist-card .form-check-label small {
  font-size:1.2rem;    
}

    .form-check-input {
      width: 24px;
      height: 24px;
      margin-right: 12px;
      cursor: pointer;
    }

    /* Submit button */
    .submit-btn {
      background-color: #004d4d;
      color: #e0f7f7;
      padding: 14px 36px;
      border-radius: 35px;
      border: none;
      font-weight: 700;
      font-size: 1.1rem;
      transition: background-color 0.25s ease, box-shadow 0.25s ease;
      box-shadow: 0 5px 12px rgba(0, 77, 77, 0.3);
      user-select: none;
      cursor: pointer;
    }

    .submit-btn:hover {
      background-color: #006666;
      box-shadow: 0 8px 18px rgba(0, 102, 102, 0.45);
    }

    .remove-btn {
      background-color: #d9534f;
      color: white;
      padding: 14px 36px;
      border-radius: 35px;
      border: none;
      font-weight: 700;
      font-size: 1.1rem;
      transition: background-color 0.25s ease, box-shadow 0.25s ease;
      box-shadow: 0 5px 12px rgba(217, 83, 79, 0.3);
      user-select: none;
      cursor: pointer;
    }

    .remove-btn:hover {
      background-color: #c9302c;
      box-shadow: 0 8px 18px rgba(201, 48, 44, 0.45);
    }

    .success-msg {
      color: green;
      text-align: center;
      font-weight: bold;
      margin-bottom: 20px;
      user-select: none;
    }

    /* Section headers */
    .section-header {
      font-size: 1.5rem;
      font-weight: 700;
      color: #004d66;
      margin: 30px 0 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #004d66;
    }

    /* Footer styling */
    .footer {
    margin-top: 40px;      
    padding: 4px 0;       
    background-color: #004d66;
    color: #e0f7f7;
    font-size: 0.65rem;    
}
.footer .rights {
    font-size: 0.95rem;  
    font-weight: 600;
}

  </style>
</head>
<body>

<div class="container">
  <div class="mb-3">
    <a href="student_information.php" class="back-btn">&larr; Back to student information</a>
  </div>

  <div class="title-box">
    <h2>Submit Your Final-Year Project Wishlist</h2>
  </div>

  <?php if (!empty($message)): ?>
    <div class="success-msg"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <div class="row">
    <div class="col-md-3">
      <div class="filter-buttons">
        <?php foreach ($allowed_departments as $key => $label):
          $baseClass = 'btn';
          if ($selected_department_key === $key) {
              $baseClass .= ' active-filter';
          }
          if ($key !== 'all') {
              $baseClass .= ' btn-' . $key;
          }
        ?>
          <a href="?department=<?= urlencode($key) ?>" class="<?= $baseClass ?>">
            <?= htmlspecialchars($label) ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="col-md-9">
      <!-- Current Wishlist Section -->
      <?php if (!empty($wishlisted_projects)): ?>
        <div class="current-wishlist">
          <h3 class="section-header">Your Current Wishlist</h3>
          <form method="post" novalidate>
            <input type="hidden" name="selected_department" value="<?= htmlspecialchars($selected_department_key) ?>">
            <div class="row g-4">
              <?php foreach ($wishlisted_projects as $project): ?>
                <div class="col-md-6">
                  <div class="wishlist-card">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remove_projects[]"
                             value="<?= $project['id'] ?>"
                             id="remove_project<?= $project['id'] ?>">
                      <label class="form-check-label" for="remove_project<?= $project['id'] ?>">
                        <strong><?= htmlspecialchars($project['title']) ?></strong><br>
                        <small class="text-muted"><?= htmlspecialchars($project['description']) ?></small><br>
                        <small><em>Department: <?= htmlspecialchars($allowed_departments[$project['department']] ?? $project['department']) ?></em></small>
                      </label>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="text-center mt-5">
              <button type="submit" class="remove-btn">Remove Selected Projects</button>
            </div>
          </form>
        </div>
      <?php endif; ?>

      <!-- Add to Wishlist Section -->
      <div class="add-wishlist">
        <h3 class="section-header">Available Projects</h3>
        <form method="post" novalidate>
          <input type="hidden" name="selected_department" value="<?= htmlspecialchars($selected_department_key) ?>">
          <div class="row g-4">
            <?php foreach ($projects as $project): ?>
              <div class="col-md-6">
                <div class="wishlist-card">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="wishlist[]"
                           value="<?= $project['id'] ?>"
                           id="project<?= $project['id'] ?>"
                           <?= in_array($project['id'], $wishlist_ids) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="project<?= $project['id'] ?>">
                      <strong><?= htmlspecialchars($project['title']) ?></strong><br>
                      <small class="text-muted"><?= htmlspecialchars($project['description']) ?></small><br>
                      <small><em>Department: <?= htmlspecialchars($allowed_departments[$project['department']] ?? $project['department']) ?></em></small>
                    </label>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="text-center mt-5">
            <button type="submit" class="submit-btn">Update Wishlist</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<footer class="footer">
  <div class="container text-center">
   <span class="rights"> &copy; <?= date('Y') ?>  Student Portal — All rights reserved.</span>
  </div>
</footer>

</body>
</html>