<?php
session_start();
$employer = $_SESSION['username'];

$conn = new mysqli("localhost", "root", "", "job_hive");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT * FROM applications WHERE posted_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $employer);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applications Received</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }
        h2 {
            text-align: center;
            margin: 30px 0 20px;
            color: #333;
        }
        .custom-table {
            margin: 20px auto;
            width: 95%;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .custom-table th {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            font-size: 15px;
        }
        .custom-table td {
            padding: 14px;
            vertical-align: middle;
            font-size: 14px;
        }
        .custom-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 20px;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Applications to Your Jobs</h2>

<table class="table custom-table">
    <thead>
        <tr>
            <th>Type</th>
            <th>Applicant</th>
            <th>Email</th>
            <th>Group Members</th>
            <th>Resume</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['apply_type']) ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['apply_type'] === 'group' ? nl2br(htmlspecialchars($row['group_members'])) : '-' ?></td>
            <td>
                <?php if (!empty($row['resume_path'])): ?>
                    <a href="<?= htmlspecialchars($row['resume_path']) ?>" target="_blank">View Resume</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
            <td>
                <?php if ($row['status'] === 'pending'): ?>
                    <form method="post" action="user_edit.php" style="display:inline;">
                        <input type="hidden" name="app_id" value="<?= $row['id'] ?>">
                        <button name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                        <button name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                <?php else: ?>
                    <?= ucfirst(htmlspecialchars($row['status'])) ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php
$conn->close();
?>

</body>
</html>
