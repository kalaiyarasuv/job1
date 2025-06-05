<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_user = $_POST['username'];
    $admin_pass = $_POST['password'];

    // Static credentials (for demo, replace with DB validation later)
    if ($admin_user == "admin" && $admin_pass == "admin123") {
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JobHive Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Style -->
    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-box {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-box h2 {
            margin-bottom: 30px;
            font-weight: bold;
            color: #0d6efd;
            text-align: center;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-login {
            background-color: #0d6efd;
            border: none;
            border-radius: 8px;
        }
        .btn-login:hover {
            background-color: #084298;
        }
        .error-msg {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .login-icon {
            font-size: 3rem;
            color: #0d6efd;
            text-align: center;
            display: block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <i class="fas fa-user-shield login-icon"></i>
    <h2>Admin Login</h2>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-login btn-lg text-white">Login</button>
        </div>
        <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
