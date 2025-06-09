<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "job_hive");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $conn->real_escape_string($_POST['name']);
$job_interests = $conn->real_escape_string($_POST['job_interests']);
$gender = $conn->real_escape_string($_POST['gender']);
$dob = $conn->real_escape_string($_POST['dob']);
$address = $conn->real_escape_string($_POST['address']);

// Image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $img_tmp = $_FILES['image']['tmp_name'];
    $img_name = basename($_FILES['image']['name']);
    $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $uploadPath = "";

    if (in_array($img_ext, $allowed)) {
        $new_img_name = uniqid("IMG_", true) . "." . $img_ext;
        $upload_dir = 'uploads/';
        $uploadPath = $upload_dir . $new_img_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($img_tmp, $uploadPath)) {
            // Save full path to database
            $sql = "INSERT INTO user_details 
                    (name, job_interests, gender, dob, address, image) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            // Bind full upload path to DB
            $stmt->bind_param("ssssss", $name, $job_interests, $gender, $dob, $address, $uploadPath);

            if ($stmt->execute()) {
                echo "<script>alert('User details saved successfully!'); window.location.href='view_users.php';</script>";
            } else {
                echo "Database error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Invalid image type. Only jpg, jpeg, png, gif allowed.";
    }
} else {
    echo "Please upload an image.";
}

$conn->close();
?>
