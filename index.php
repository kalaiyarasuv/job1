<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JobHive Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons (FontAwesome) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .admin-header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .dashboard-container {
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card i {
            font-size: 2rem;
        }
        .logout-btn {
            position: absolute;
            right: 20px;
            top: 20px;
        }
    </style>
</head>
<body>

    <div class="admin-header position-relative">
        <h1 class="mb-0">JobHive Admin Panel</h1>
        <a href="logout.php" class="btn btn-danger logout-btn"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <div class="container dashboard-container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <a href="view_jobs.php" class="text-decoration-none text-dark">
                    <div class="card p-4">
                        <i class="fas fa-briefcase text-primary"></i>
                        <h5 class="mt-3">Manage Jobs</h5>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="view_employers.php" class="text-decoration-none text-dark">
                    <div class="card p-4">
                        <i class="fas fa-user-tie text-success"></i>
                        <h5 class="mt-3">Manage Employers</h5>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="view_users.php" class="text-decoration-none text-dark">
                    <div class="card p-4">
                        <i class="fas fa-users text-info"></i>
                        <h5 class="mt-3">Manage Job Seekers</h5>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="card p-4">
                        <i class="fas fa-flag text-warning"></i>
                        <h5 class="mt-3">Reports</h5>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="card p-4">
                        <i class="fas fa-cogs text-secondary"></i>
                        <h5 class="mt-3">Settings</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
