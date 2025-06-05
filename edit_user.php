<?php
$conn = new mysqli("localhost", "root", "", "job_hive");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user = $conn->query("SELECT * FROM login1 WHERE id=$id")->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn->query("UPDATE login1 SET email='$email', username='$username', password='$password' WHERE id=$id");
    header("Location: index.php"); // Redirect back
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>
    <form method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" value="<?= $user['email'] ?>" required><br><br>
        <label>Username:</label><br>
        <input type="text" name="username" value="<?= $user['username'] ?>" required><br><br>
        <label>Password:</label><br>
        <input type="text" name="password" value="<?= $user['password'] ?>" required><br><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
