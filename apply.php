<?php
session_start();

$conn = new mysqli("localhost", "root", "", "job_hive");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must be logged in to apply.'); window.location.href='emlogin.html';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'], $_POST['apply_type'])) {
    $jobId = intval($_POST['job_id']);
    $applyType = $_POST['apply_type'];

    // Prepare statement to get poster from jobs table
    $stmt = $conn->prepare("SELECT posted_by FROM jobs WHERE job_id = ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("i", $jobId);
    $stmt->execute();
    $stmt->bind_result($postedBy);
    $fetchSuccess = $stmt->fetch();
    $stmt->close();

    // Debug output - comment out after verifying
    // var_dump("jobId: $jobId", "postedBy: $postedBy", "fetchSuccess: $fetchSuccess"); exit;

    if (!$fetchSuccess || empty($postedBy)) {
        echo "<script>alert('Invalid job selected.'); window.location.href='All-job2.php';</script>";
        exit();
    }

    // Save to session for form
    $_SESSION['apply_job_id'] = $jobId;
    $_SESSION['apply_type'] = $applyType;
    $_SESSION['job_poster'] = $postedBy;

} else {
    echo "<script>alert('Invalid access.'); window.location.href='All-job2.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply - <?= htmlspecialchars(ucfirst($_SESSION['apply_type'])) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container py-5">
    <h2 class="mb-4">Apply as <?= htmlspecialchars(ucfirst($_SESSION['apply_type'])) ?></h2>

    <?php if ($_SESSION['apply_type'] === 'individual'): ?>
        <!-- Individual Application Form -->
        <form action="submit_application.php" method="post">
            <input type="hidden" name="job_id" value="<?= $_SESSION['apply_job_id'] ?>">
            <input type="hidden" name="apply_type" value="individual">

            <div class="mb-3">
                <label>Your Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Your Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Your Skills or Experience (Resume)</label>
                <textarea name="resume" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Submit Application</button>
        </form>

    <?php else: ?>
        <!-- Group Application Form -->
        <form action="submit_application.php" method="post">
            <input type="hidden" name="job_id" value="<?= $_SESSION['apply_job_id'] ?>">
            <input type="hidden" name="apply_type" value="group">

            <div class="mb-3">
                <label>Team Leader Name</label>
                <input type="text" name="leader_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Leader Email</label>
                <input type="email" name="leader_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Group Members (comma-separated names)</label>
                <textarea name="group_members" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>Team Resume or Project Info</label>
                <textarea name="group_resume" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Group Application</button>
        </form>
    <?php endif; ?>
</body>
</html>
