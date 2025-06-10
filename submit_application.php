<?php 
session_start();
$conn = new mysqli("localhost", "root", "", "job_hive");

// Validate input and session
if (!isset($_SESSION['username'], $_SESSION['job_poster'], $_POST['job_id'], $_POST['apply_type'])) {
    echo "<script>alert('Invalid request'); window.location.href='All-job2.php';</script>";
    exit();
}

$jobId = $_POST['job_id'];
$applyType = $_POST['apply_type'];
$jobPoster = $_SESSION['job_poster'];
$applicant = $_SESSION['username'];
$appliedBy = $applicant;
$status = 'pending';
$appliedAt = date("Y-m-d H:i:s");  // use PHP datetime

if ($applyType === 'individual') {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $resumeText = $_POST['resume'];

    $stmt = $conn->prepare("INSERT INTO applications 
        (job_id, applicant, apply_type, full_name, email, resume_path, status, posted_by, applied_by, applied_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    // No file upload, so we use resume_text directly in resume_path
    $stmt->bind_param("isssssssss", 
        $jobId, $applicant, $applyType, $name, $email, $resumeText, 
        $status, $jobPoster, $appliedBy, $appliedAt
    );

} elseif ($applyType === 'group') {
    $leader = $_POST['leader_name'];
    $leader_email = $_POST['leader_email'];
    $members = $_POST['group_members'];
    $group_resume = $_POST['group_resume'];

    $stmt = $conn->prepare("INSERT INTO applications 
        (job_id, applicant, apply_type, full_name, email, resume_path, group_members, status, posted_by, applied_by, applied_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param("issssssssss", 
        $jobId, $applicant, $applyType, $leader, $leader_email, $group_resume, $members,
        $status, $jobPoster, $appliedBy, $appliedAt
    );
} else {
    echo "<script>alert('Invalid apply type'); window.location.href='All-job2.php';</script>";
    exit();
}

if ($stmt->execute()) {
echo "<script>alert('Application submitted successfully!\\nYou can check your application status in the All Jobs page.'); window.location.href='All-job2.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
