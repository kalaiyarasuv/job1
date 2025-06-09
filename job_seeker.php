<?php 
session_start();
$conn = new mysqli("localhost", "root", "", "job_hive");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user email is available in session
$user_email = $_SESSION['email'] ?? '';

if (!$user_email) {
    die("User email not found in session. Please log in.");
}

// Fetch applications for this user
$sql = "SELECT applicant, status FROM applications WHERE email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Your Applications</title>
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

    .approve {
      color: green;
      border-left-color: green;
    }

    .reject {
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
        echo "<p class='no-apps'>No applications found.</p>";
    } else {
        while ($row = $result->fetch_assoc()) {
            $applicant = htmlspecialchars($row['applicant']);
            $status = strtolower($row['status']);
            $statusClass = ($status === 'approve') ? 'approve' : (($status === 'reject') ? 'reject' : 'pending');

            echo "<div class='application $statusClass'>";
            echo "<strong>Applicant:</strong> $applicant";
            echo "<span class='status'>" . ucfirst($status) . "</span>";
            echo "</div>";
        }
    }

    $stmt->close();
    $conn->close();
    ?>
  </div>
</body>
</html>
