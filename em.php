<?php
session_start(); // Start the session to store login data

// Retrieve the email and password from the POST request
$email = $_POST['email'];
$password = $_POST['password'];

// Database connection
$dbname = "job_hive";
$conn = new mysqli("localhost", "root", "", $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate user and redirect accordingly
function validateUser($conn, $table, $email, $password, $RedirectPage, $ErrorRedirect) {
    $sql = "SELECT * FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($password === $user['password']) {
            // ✅ Set session variables
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username']; // optional if you need it later

            header("Location: $RedirectPage");
            exit();
        } else {
            echo "<script>";
            echo "alert('Password is Incorrect');";
            echo "window.location.href='$ErrorRedirect';";
            echo "</script>";
            exit();
        }
    }

    // No user found
    $stmt->close();
    return false;
}

// Validate the user
if (!validateUser($conn, 'emlog', $email, $password, 'pp.php', 'emlogin.html')) {
    echo "<script>";
    echo "alert('Email is Incorrect');";
    echo "window.location.href='emlogin.html';";
    echo "</script>";
    exit();
}

// Close the connection
$conn->close();
?>
