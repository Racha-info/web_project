<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student Portal</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  
  <style>
    /* ======= GLOBAL STYLES ======= */

    /* Base body styling */
    body {
      margin: 0;
      min-height: 100vh;
      background: linear-gradient(to right, #e0f7fa, #ffffff);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #004d66;
      display: flex;
      flex-direction: column;
    }

    /* ======= NAVBAR ======= */
    .navbar {
      background-color: #004d66;
      box-shadow: 0 6px 20px rgba(0, 77, 102, 0.25);
      position: relative;
    }

    .navbar-brand {
      font-size: 2.4rem;
      font-weight: 700;
      margin: 0 auto;
      letter-spacing: 2px;
      color: #e0f7fa !important;
      text-shadow: 0 0 5px rgba(224, 247, 250, 0.7);
    }

    /* Login button with icon */
    .login-btn {
      position: absolute;
      right: 25px;
      top: 12px;
      padding: 10px 22px;
      font-weight: 700;
      font-size: 1rem;
      color: #004d66;
      background: #e0f7fa;
      border-radius: 30px;
      border: 2px solid #004d66;
      box-shadow: 0 2px 6px rgba(0, 77, 102, 0.2);
      transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .login-btn:hover {
      background-color: rgb(12, 118, 153);
      color: #e0f7fa;
      box-shadow: 0 0 12px #004d66;
    }

    /* ======= HEADINGS ======= */

    h2.custom-blue-text {
      font-weight: 700;
      font-size: 2.5rem;
      letter-spacing: 2px;
      text-align: center;
      margin-bottom: 3rem;
      color: #004d66;
      text-shadow: 0 0 5px rgba(0, 77, 102, 0.3);
    }

    /* ======= LAYOUT ======= */

    .container {
      max-width: 960px;
      flex-grow: 1;
    }

    .row > [class*='col-'] {
      padding: 0 12px;
    }

    .mb-3 {
      margin-bottom: 1.5rem !important;
    }

    /* ======= BUTTONS ======= */

    /* Common button styling */
    .btn-custom {
      background-color: white;
      color: #004d66;
      border: 2px solid #004d66;
      border-radius: 35px;
      font-weight: 700;
      font-size: 1.15rem;
      padding: 14px 0;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 77, 102, 0.1);
      text-transform: uppercase;
      letter-spacing: 1.4px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 12px;
    }

    /* Optional text size reduction for long button text */
    .small-text {
      font-size: 0.95rem;
      white-space: nowrap;
    }

    /* Department-specific button styles */
    .btn-compsci {
      background-color: #007c91;
      color: white;
      border-color: #007c91;
    }

    .btn-compsci:hover {
      background-color: #005f6b;
      color: white;
      border-color: #005f6b;
    }

    .btn-chem {
      background-color: #5da26f;
      color: white;
      border-color: #5da26f;
    }

    .btn-chem:hover {
      background-color: #407e54;
      color: white;
      border-color: #407e54;
    }

    .btn-math {
      background-color: #1a8e8c;
      color: white;
      border-color: #1a8e8c;
    }

    .btn-math:hover {
      background-color: #146d6b;
      color: white;
      border-color: #146d6b;
    }

    .btn-physics {
      background-color: #5789a3;
      color: white;
      border-color: #5789a3;
    }

    .btn-physics:hover {
      background-color: #406b82;
      color: white;
      border-color: #406b82;
    }

    /* ======= ANNOUNCEMENTS ======= */

    .announcements {
      margin-top: 4rem;
      margin-bottom: 4rem;
    }

    .announcement-card {
      background: #f0fdfd;
      border: 1px solid #a3d3db;
      border-radius: 15px;
      box-shadow: 0 4px 18px rgba(0, 77, 102, 0.08);
      padding: 20px 25px;
      color: #004d66;
      transition: box-shadow 0.3s ease;
    }

    .announcement-card:hover {
      box-shadow: 0 8px 30px rgba(0, 77, 102, 0.15);
    }

    .announcement-title {
      font-weight: 700;
      font-size: 1.5rem;
      margin-bottom: 8px;
      letter-spacing: 1px;
    }

    .announcement-date {
      font-size: 1.1rem;
      font-style: italic;
      margin-bottom: 12px;
      color: #007080;
    }

    .announcement-text {
      font-size: 1.25rem;
      line-height: 1.4;
    }

    /* ======= FOOTER ======= */

    footer {
      background-color: #004d66;
      color: #e0f7fa;
      text-align: center;
      padding: 18px 10px;
      font-weight: 500;
      font-size: 1rem;
      letter-spacing: 1px;
      box-shadow: 0 -4px 20px rgba(0, 77, 102, 0.35);
      user-select: none;
    }

    /* ======= RESPONSIVE DESIGN ======= */

    @media (max-width: 576px) {
      .btn-custom {
        font-size: 1rem;
        padding: 12px 0;
        border-radius: 28px;
      }

      .navbar-brand {
        font-size: 1.8rem;
      }

      .login-btn {
        padding: 8px 18px;
        font-size: 0.9rem;
        right: 15px;
      }

      .announcement-title {
        font-size: 1.15rem;
      }
    }

  </style>
</head>
<body>

  <!-- ======= HEADER / NAVIGATION BAR ======= -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid position-relative">
      <a class="navbar-brand w-100 text-center" href="#">Student Portal</a>
      <a href="login.php" class="btn login-btn">
        <i class="bi bi-box-arrow-in-right"></i> Login
      </a>
    </div>
  </nav>

  <!-- ======= MAIN CONTENT AREA ======= -->
  <div class="container mt-5">

    <!-- ======= DEPARTMENT BUTTONS ======= -->
    <h2 class="custom-blue-text">Departments</h2>
    <div class="row justify-content-center gx-4 gy-4">
      <div class="col-10 col-md-4 col-lg-3">
        <a href="computer_science.php" class="btn btn-custom btn-compsci w-100">
          <i class="bi bi-pc-display"></i> <span class="small-text">Computer Science</span>
        </a>
      </div>
      <div class="col-10 col-md-4 col-lg-3">
        <a href="math.php" class="btn btn-custom btn-math w-100">
          <i class="bi bi-calculator"></i> Mathematics
        </a>
      </div>
      <div class="col-10 col-md-4 col-lg-3">
        <a href="physics.php" class="btn btn-custom btn-physics w-100">
          <i class="bi bi-lightning-charge"></i> Physics
        </a>
      </div>
      <div class="col-10 col-md-4 col-lg-3">
        <a href="chemistry.php" class="btn btn-custom btn-chem w-100">
          <i class="bi bi-droplet-half"></i> Chemistry
        </a>
      </div>
    </div>

    <!-- ======= ANNOUNCEMENTS SECTION ======= -->
    <section class="announcements">
      <h2 class="custom-blue-text">Announcements</h2>

      <div class="row gy-4">
        <?php
        // Include database connection file
        include 'db.php';

        // Fetch the latest 6 general announcements
        $query = "SELECT title, content, datetime FROM announcements WHERE display = 'general' ORDER BY datetime DESC LIMIT 6";
        $result = mysqli_query($conn, $query);

        // Display announcement cards if found
        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="col-md-6">';
            echo '  <div class="announcement-card">';
            echo '    <div class="announcement-title">' . htmlspecialchars($row["title"]) . '</div>';
            echo '    <div class="announcement-date">' . date("F j, Y", strtotime($row["datetime"])) . '</div>';
            echo '    <div class="announcement-text">' . nl2br(htmlspecialchars($row["content"])) . '</div>';
            echo '  </div>';
            echo '</div>';
          }
        } else {
          // Fallback if no announcements found
          echo '<p class="text-center">No general announcements at this time.</p>';
        }

        // Close DB connection
        mysqli_close($conn);
        ?>
      </div>
    </section>
  </div>

  <!-- ======= FOOTER ======= -->
  <footer>
    &copy; 2025 Student Portal — All rights reserved.
  </footer>

</body>
</html>
