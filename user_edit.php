<?php

$conn = new mysqli("localhost", "root", "", "job_hive");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Get user ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid user ID.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process update
    $name = sanitize($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = sanitize($_POST['phone'] ?? '');
    $job_interests = sanitize($_POST['job_interests'] ?? '');
    $gender = sanitize($_POST['gender'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $address = sanitize($_POST['address'] ?? '');

    if (empty($name) || empty($email) || empty($phone) || empty($job_interests) || empty($gender) || empty($dob) || empty($address)) {
        die("Error: All fields are required.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    $sql = "UPDATE user_details SET full_name=?, email=?, phone=?, job_interests=?, gender=?, dob=?, address=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) die("Prepare failed: " . $conn->error);

    $stmt->bind_param("sssssssi", $name, $email, $phone, $job_interests, $gender, $dob, $address, $id);

    if ($stmt->execute()) {
        echo "<script>alert('User details updated successfully.'); window.location.href='edit.php?id=$id';</script>";
    } else {
        echo "<script>alert('Update failed: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    // Fetch existing data
    $sql = "SELECT * FROM user_details WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows !== 1) {
        die("User not found.");
    }
    $user = $result->fetch_assoc();
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head><title>Edit User Details</title></head>
<body>
<h2>Edit User Details</h2>
<form method="post" action="">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($user['full_name']) ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required><br><br>

    <label>Job Interests:</label><br>
    <input type="text" name="job_interests" value="<?= htmlspecialchars($user['job_interests']) ?>" required><br><br>

    <label>Gender:</label><br>
    <select name="gender" required>
        <option value="">Select</option>
        <option value="Male" <?= $user['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= $user['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
        <option value="Other" <?= $user['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
    </select><br><br>

    <label>Date of Birth:</label><br>
    <input type="date" name="dob" value="<?= htmlspecialchars($user['dob']) ?>" required><br><br>

    <label>Address:</label><br>
    <textarea name="address" required><?= htmlspecialchars($user['address']) ?></textarea><br><br>

    <button type="submit">Update</button>
</form>
</body>
</html>
