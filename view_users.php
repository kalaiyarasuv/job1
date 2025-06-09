<?php
// Database connection
$dbname = "job_hive";
$conn = new mysqli("localhost", "root", "", $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users from the login1 table
$sql = "SELECT * FROM login1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Users | JobHive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons (optional) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            margin-top: 60px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }
        table {
            margin-bottom: 0;
        }
        .table thead {
            background-color: #0d6efd;
            color: white;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        h2 {
            color: #0d6efd;
            font-weight: bold;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="text-center">
        <h2><i class="fas fa-users me-2"></i>Registered Users</h2>
    </div>
    <div class="card p-4">
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                <thead>
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Username</th>
        <th>Password</th>
        <th>Actions</th> <!-- Add this -->
    </tr>
</thead>
<tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['password'] ?></td>
            <td>
                <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">No users found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php $conn->close(); ?>
