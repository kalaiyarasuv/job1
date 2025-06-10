<?php
$conn = new mysqli("localhost", "root", "", "job_hive");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['application_id']) && isset($_POST['status'])) {
        $application_id = $_POST['application_id'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $application_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
                 echo "<script>alert('Status updated successfully!'); window.location.href='view_applications.php';</script>";
        } else {
                echo "<script>alert('Failed to update status. Please check the application ID.'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "Missing application ID or status.";
    }

    $conn->close();
}
?>
