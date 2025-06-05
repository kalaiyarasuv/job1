<?php
$conn = new mysqli("localhost", "root", "", "job_hive");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM login1 WHERE id=$id");
}

header("Location: index.php"); // Redirect back to main page
exit;
