<?php
session_start();
$conn = new mysqli("localhost", "root", "", "job_hive");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $app_id = $_POST['app_id'];
    $action = $_POST['action'];
    $newStatus = ($action == 'approve') ? 'approved' : 'rejected';

    $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $app_id);
    $stmt->execute();

    header("Location: view_applications.php");
}
?>
