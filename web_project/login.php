<?php
session_start();  // Start a new or resume existing session to track user login state

// Process login form submission only when the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection parameters
    $host = "localhost";
    $dbname = "web_project";
    $dbuser = "root";
    $dbpass = "";

    // Establish a new MySQLi database connection
    $conn = new mysqli($host, $dbuser, $dbpass, $dbname);

    // Check if the connection was successful; terminate script on failure
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve and sanitize user inputs from POST request
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? '';

    // Validate required fields are provided
    if (!$username || !$password || !$role) {
        $error = "Please fill in all fields.";
    } else {
        // Role-based authentication logic
        if ($role === 'student') {
            // Prepare SQL statement to safely fetch student data by email
            $stmt = $conn->prepare("SELECT * FROM students WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // Verify password and set session variables upon successful login
            if ($user && $password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['first_name'];
                $_SESSION['role'] = 'student';
                // Redirect to student information page after successful login
                header("Location: student_information.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
            $stmt->close();  // Close the prepared statement
        } elseif ($role === 'admin') {
            // Prepare SQL statement to fetch admin data by email
            $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // Verify admin password and initialize session variables on success
            if ($user && $password === $user['password']) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['username'] = $user['full_name'];
                $_SESSION['role'] = 'admin';
                // Redirect to admin dashboard after successful login
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $error = "Invalid admin credentials.";
            }
            $stmt->close();  // Close the prepared statement
        } else {
            // Handle invalid role selection
            $error = "Unknown role selected.";
        }
    }

    // Close database connection to free resources
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Student Portal</title>
  <!-- Bootstrap CSS for styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    /* Page layout and styling */
    body {
      margin: 0;
      height: 100vh;
      background: linear-gradient(to right, #e0f7fa, #ffffff);
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #004d66;
    }

    /* Container for the login box */
    .login-box {
      background: #ffffff;
      border-radius: 18px;
      box-shadow: 0 10px 30px rgba(0, 77, 102, 0.2);
      padding: 45px 35px;
      max-width: 420px;
      width: 100%;
      text-align: center;
      color: #004d66;
      transition: transform 0.3s ease;
    }

    /* Hover effect to lift the login box */
    .login-box:hover {
      transform: translateY(-6px);
      box-shadow: 0 14px 35px rgba(0, 77, 102, 0.3);
    }

    /* Login form heading */
    .login-box h2 {
      margin-bottom: 25px;
      font-weight: 700;
      letter-spacing: 1.5px;
      color: #004d66;
    }

    /* Label styling */
    label {
      color: #004d66;
      font-weight: 500;
      margin-bottom: 6px;
      display: inline-block;
    }

    /* Input and select box styles */
    .form-control,
    .form-select {
      background: #f0fdfd;
      border: 1px solid #a3d3db;
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 1rem;
      color: #004d66;
      transition: box-shadow 0.3s ease;
    }

    /* Placeholder text color */
    .form-control::placeholder {
      color: #004d6699;
    }

    /* Focus outline for inputs */
    .form-control:focus,
    .form-select:focus {
      box-shadow: 0 0 0 3px rgba(0, 77, 102, 0.2);
      outline: none;
    }

    /* Login button styles */
    .btn-login {
      margin-top: 20px;
      width: 100%;
      background: #004d66;
      border: none;
      padding: 14px;
      border-radius: 30px;
      font-weight: 700;
      font-size: 1.1rem;
      color: white;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    /* Hover effect for login button */
    .btn-login:hover {
      background: #007080;
      transform: scale(1.02);
    }

    /* Link style for going back */
    .back-link {
      margin-top: 18px;
      font-weight: 600;
      color: #004d66;
      display: inline-block;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    /* Hover effect for back link */
    .back-link:hover {
      color: #007080;
      text-decoration: underline;
    }

    /* Error message styling */
    .error-message {
      margin-top: 12px;
      color: #cc0000;
      font-weight: 600;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <h2>Student Portal Login</h2>

    <!-- Display error messages if any -->
    <?php if (!empty($error)): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Login form capturing Email, password, and role -->
    <form action="" method="post" autocomplete="off">
      <div class="mb-3 text-start">
        <label for="username" class="form-label">Email </label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Enter email " required />
      </div>
      <div class="mb-3 text-start">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required />
      </div>
      <div class="mb-4 text-start">
        <label for="role" class="form-label">Select Role</label>
        <select id="role" name="role" class="form-select" required>
          <option value="" disabled selected>Select role</option>
          <option value="student">Student</option>
          <option value="admin">Administrator</option>
        </select>
      </div>
      <button type="submit" class="btn-login">Login</button>
    </form>

    <!-- Link to return to the home page -->
    <a href="index.php" class="back-link">← Back to Home</a>
  </div>

</body>
</html>
