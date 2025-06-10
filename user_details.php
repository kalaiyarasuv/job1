<?php
session_start();

// Redirect if already submitted once
if (isset($_SESSION['details_completed']) && $_SESSION['details_completed'] === true) {
    echo "<script>window.location.href = 'All-job2.php';</script>";
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "job_hive";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = sanitize($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = sanitize($_POST['phone'] ?? '');
    $job_interests = sanitize($_POST['job_interests'] ?? '');
    $gender = sanitize($_POST['gender'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $address = sanitize($_POST['address'] ?? '');

    if (empty($name) || empty($email) || empty($phone) || empty($job_interests) || empty($gender) || empty($dob) || empty($address)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $sql = "INSERT INTO user_details (full_name, email, phone, job_interests, gender, dob, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssssss", $name, $email, $phone, $job_interests, $gender, $dob, $address);
        if ($stmt->execute()) {
            $_SESSION['details_completed'] = true;
            $_SESSION['username'] = $email;
            echo "<script>alert('Profile submitted successfully.'); window.location.href = 'All-job2.php';</script>";
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

