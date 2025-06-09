<?php
// Establish a connection to the database
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "job_hive";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize data from the form
$full_name = $conn->real_escape_string($_POST['username']);
$email = $conn->real_escape_string($_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm-password'];

// Full name validation (only alphabets, no spaces, numbers, or special characters)
if (empty($full_name) || !preg_match("/^[a-zA-Z][a-zA-Z ]*$/", $full_name)) {
    echo "<script>alert('Enter valid username.'); window.location.href = 'register.html';</script>";
    exit();
}


// Validate password: not empty and must not contain spaces
if (empty($password)) {
    echo "<script>alert('Password cannot be empty.'); window.location.href = 'register.html';</script>";
    exit();
}
if (strpos($password, ' ') !== false) {
    echo "<script>alert('Password must not contain spaces.'); window.location.href = 'register.html';</script>";
    exit();
}

// Password confirmation check
if ($password !== $confirm_password) {
    echo "<script>alert('Passwords do not match!'); window.location.href = 'register.html';</script>";
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format!'); window.location.href = 'register.html';</script>";
    exit();
}

// Check if the email already exists
$email_check_query = "SELECT * FROM login1 WHERE email = ?";
$stmt_check = $conn->prepare($email_check_query);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('This email is already registered. Please use a different email.'); window.location.href = 'register.html';</script>";
    exit();
}

// Hash the password


// Insert into the database
$stmt = $conn->prepare("INSERT INTO login1 (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $full_name, $email, $password);

if ($stmt->execute()) {
    echo "<script>alert('Account created successfully.'); window.location.href = 'login.html';</script>";
    exit();
} else {
    echo "<script>alert('Error: Could not create account. Please try again later.'); window.location.href = 'register.html';</script>";
}

// Close connections
$stmt->close();
$conn->close();
?>
