<?php
// Retrieve the email and password from the POST request
$email = $_POST['email'];
$password = $_POST['password'];

// Database connection
$dbname = "job_hive";  // Database name
$conn = new mysqli("localhost", "root", "", $dbname);  // Correct connection string

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
        $Name = $user['username']; 

        
        if ($password === $user['password']) {
            echo "<html><head><style>";
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
    // if(sta_with_end($email,'@gmail.com')){
    //     echo "window.location.href='dash.php';";
    // }
    // elseif(sta_with_end($email,'@staff.co.in')){
    //     echo "window.location.href='staff.html';";
    // }
  
    // If no match found in the table
    $stmt->close();
    return false;
}

// Call the validation function and handle the result
if (!validateUser($conn, 'login1', $email, $password, 'index.html', 'login.html')) {
    echo "<script>";
    echo "alert('Email is Incorrect');";
    echo "window.location.href='login.html';";
    echo "</script>";
    exit();
}

// Close the database connection
$conn->close();
?>
