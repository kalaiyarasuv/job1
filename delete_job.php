<?php
$conn = new mysqli("localhost", "root", "", "job_hive");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize input

    // Prepare statement to delete safely
    $stmt = $conn->prepare("DELETE FROM jobs WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Optionally handle error
        die("Prepare failed: " . $conn->error);
    }
}

$conn->close();

// Redirect back to job listing page
header("Location: view_jobs.php");  // Adjust to your correct filename
exit;
