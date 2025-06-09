<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "job_hive";  // Change to your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper function to sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize inputs - make sure keys have no trailing spaces or tabs
    $name = sanitize($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = sanitize($_POST['phone'] ?? '');
    $job_interests = sanitize($_POST['job_interests'] ?? '');
    $gender = sanitize($_POST['gender'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $address = sanitize($_POST['address'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($job_interests) || empty($gender) || empty($dob) || empty($address)) {
        die("Error: All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    // Prepare the SQL insert statement - make sure column names are correct and have no spaces
    $sql = "INSERT INTO user_details (full_name, email, phone, job_interests, gender, dob, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("sssssss", $name, $email, $phone, $job_interests, $gender, $dob, $address);
if ($stmt->execute()) {
    echo "<script>alert('User details submitted successfully.'); window.location.href='All-job2.php';</script>";
} else {
    echo "<script>alert('Error executing query: " . addslashes($stmt->error) . "'); window.history.back();</script>";
}


    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
