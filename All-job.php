<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must be logged in to post a job.'); window.location.href='emlogin.html';</script>";
    exit;
}

$conn = new mysqli("localhost", "root", "", "job_hive");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobTitle = $_POST['job_title'];
    $jobDescription = $_POST['job_description'];
    $jobCategory = $_POST['category'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $jobType = $_POST['job_type'];
    $startDate = $_POST['start_date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];
    $contact = $_POST['phone'];
    $vacancy = $_POST['vacancy'];
    $postedBy = $_SESSION['username'];
    $uploadPath = "";

    if (isset($_FILES['jobImage']) && $_FILES['jobImage']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['jobImage']['name'];
        $fileTmp = $_FILES['jobImage']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $newFileName = uniqid('job_', true) . '.' . $ext;
            $uploadDir = 'uploads/';
            $uploadPath = $uploadDir . $newFileName;

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($fileTmp, $uploadPath)) {
                $stmt = $conn->prepare("INSERT INTO jobs 
                    (job_title, job_description, category, location, salary, job_type, phone, job_image, posted_by, posted_at, start_date, start_time, end_time, vacancy) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)");

                if ($stmt === false) {
                    die("Prepare failed: " . $conn->error);
                }

                $stmt->bind_param("sssssssssssssi", 
                    $jobTitle, $jobDescription, $jobCategory, $location, $salary, 
                    $jobType, $contact, $uploadPath, $postedBy, 
                    $startDate, $startTime, $endTime, $vacancy);

                if ($stmt->execute()) {
                    echo "<script>alert('Job posted successfully!'); window.location.href='pp.php';</script>";
                } else {
                    echo "<script>alert('Database error while posting job.');</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Failed to upload image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Only jpg, jpeg, png, gif allowed.');</script>";
        }
    } else {
        echo "<script>alert('Please upload an image.');</script>";
    }
}

$conn->close();
?>



<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post a Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h1 class="text-center mb-4">Post a Job</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="jobTitle" class="form-label">Job Title</label>
                    <input type="text" class="form-control" id="jobTitle" name="job_title" required>
                </div>

                <div class="mb-3">
                    <label for="jobDescription" class="form-label">Job Description</label>
                    <textarea class="form-control" id="jobDescription" name="job_description" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="jobCategory" class="form-label">Category</label>
                    <select class="form-select" id="jobCategory" name="category" required>
                        <option value="">-- Select Category --</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Customer Service">Customer Service</option>
                        <option value="Human Resources">Human Resources</option>
                        <option value="Project Management">Project Management</option>
                        <option value="Business Development">Business Development</option>
                        <option value="Sales & Communication">Sales & Communication</option>
                        <option value="Teaching & Education">Teaching & Education</option>
                        <option value="Design & Creative">Design & Creative</option>
                    </select>
                </div>
          <div class="mb-3">
    <label for="phone" class="form-label">Contact Number</label>
    <input type="text" class="form-control" id="phone" name="phone" required>
</div>

                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <select class="form-select" id="location" name="location" required>
                        <option value="" disabled selected>-- Select Location --</option>
                        <option value="Bangalore">Bangalore</option>
                        <option value="Hyderabad">Hyderabad</option>
                        <option value="Pune">Pune</option>
                        <option value="Chennai">Chennai</option>
                        <option value="Mumbai">Mumbai</option>
                        <option value="Gurgaon">Gurgaon</option>
                        <option value="Noida">Noida</option>
                        <option value="Kolkata">Kolkata</option>
                        <option value="Ahmedabad">Ahmedabad</option>
                        <option value="Remote">Remote</option>
                    </select>
                </div>
                 <div class="mb-3">
    <label for="vacancy" class="form-label">Vacancy</label>
    <input type="number" class="form-control" id="vacancy" name="vacancy" min="" required>
</div>

                <div class="mb-3">
                    <label for="salary" class="form-label">Salary</label>
                    <input type="text" class="form-control" id="salary" name="salary" required>
                </div>

                <div class="mb-3">
                    <label for="jobType" class="form-label">Job Type</label>
                    <select class="form-select" id="jobType" name="job_type" required>
                        <option value="">-- Select Job Type --</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>

                <div class="mb-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                </div>

                <div class="mb-3">
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" required>
                </div>

                <div class="mb-3">
                    <label for="jobImage" class="form-label">Upload Job Image</label>
                    <input type="file" class="form-control" id="jobImage" name="jobImage" accept="image/*" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Post Job</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
