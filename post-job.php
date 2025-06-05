<?php
session_start();
$conn = new mysqli("localhost", "root", "", "job_hive");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form inputs
$title = $_POST['jobTitle'];
$category = $_POST['jobCategory'];
$location = $_POST['jobLocation'];
$description = $_POST['jobDescription'];
$salary = $_POST['jobSalary'];
$type = $_POST['jobType'];
$posted_by = $_SESSION['username'] ?? 'Anonymous'; // or get from session/login system

// Image upload logic
$imagePath = '';
if (isset($_FILES['jobImage']) && $_FILES['jobImage']['error'] === UPLOAD_ERR_OK) {
    $imageTmp = $_FILES['jobImage']['tmp_name'];
    $imageName = basename($_FILES['jobImage']['name']);
    $uploadDir = 'uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $newImageName = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $imageName);
    $imagePath = $uploadDir . $newImageName;

    if (!move_uploaded_file($imageTmp, $imagePath)) {
        echo "Failed to upload image.";
        exit;
    }
}

// Insert job into DB
$stmt = $conn->prepare("INSERT INTO jobs (job_title, category, location, job_description, salary, job_type, job_image, posted_by, posted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("ssssssss", $title, $category, $location, $description, $salary, $type, $imagePath, $posted_by);

if ($stmt->execute()) {
    header("Location: All-job.php"); // Redirect after success
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
