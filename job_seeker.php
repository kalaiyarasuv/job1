<?php
session_start();

// 1. Connect to the database
$conn = new mysqli("localhost", "root", "", "job_hive");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Check if the user is logged in
$applicant_email = $_SESSION['email'] ?? '';
if (!$applicant_email) {
    echo "<script>alert('User not logged in. Please log in.'); window.location.href='login.html';</script>";
    exit();
}

// 3. Fetch the applicant's job applications
$sql = "SELECT jobs.posted_by AS posted_by, applications.status 
        FROM applications 
        JOIN jobs ON applications.job_id = jobs.	job_id  
        WHERE applications.email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $applicant_email);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Your Job Applications</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f7f9;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 50px auto;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .application {
      padding: 15px;
      border: 1px solid #eee;
      border-left: 5px solid #bbb;
      border-radius: 8px;
      margin-bottom: 15px;
      transition: 0.3s;
    }

    .application:hover {
      background-color: #f9f9f9;
    }

    .status {
      font-weight: bold;
      padding-left: 10px;
    }

    .approved {
      color: green;
      border-left-color: green;
    }

    .rejected {
      color: red;
      border-left-color: red;
    }

    .pending {
      color: #999;
      border-left-color: #999;
    }

    .no-apps {
      text-align: center;
      color: #888;
      font-size: 1.1em;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Your Applications</h2>

    <?php
    if ($result->num_rows === 0) {
        echo "<p class='no-apps'>No applications found for your account.</p>";
    } else {
     while ($row = $result->fetch_assoc()) {
    $job_name = htmlspecialchars($row['posted_by']);
    $status = strtolower($row['status']);
    $statusClass = 'pending';

    if ($status === 'approved') $statusClass = 'approved';
    elseif ($status === 'rejected') $statusClass = 'rejected';

    echo "<div class='application $statusClass'>";
    echo "<strong>Job Title:</strong> $job_name<br>";
    echo "<strong>Status:</strong> <span class='status'>" . ucfirst($status) . "</span>";
    echo "</div>";
}

    }

    $stmt->close();
    $conn->close();
    ?>
  </div>
</body>
</html>
