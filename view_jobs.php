<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "job_hive");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all jobs
$sql = "SELECT * FROM jobs";
$result = $conn->query($sql);

// Start HTML
echo "<!DOCTYPE html>
<html>
<head>
    <title>Job Listings</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; }
        h2 { text-align: center; }
        table { width: 90%; margin: auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        a.delete-link { color: red; text-decoration: none; }
        a.delete-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Posted Jobs</h2>";

if ($result && $result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Category</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Type</th>
                <th>Posted At</th>
                <th>Action</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['job_title']) . "</td>
                <td>" . htmlspecialchars($row['job_description']) . "</td>
                <td>" . htmlspecialchars($row['category']) . "</td>
                <td>" . htmlspecialchars($row['location']) . "</td>
                <td>" . htmlspecialchars($row['salary']) . "</td>
                <td>" . htmlspecialchars($row['job_type']) . "</td>
                <td>" . htmlspecialchars($row['posted_at']) . "</td>
                <td>
                    <a class='delete-link' href='delete_job.php?id=" . urlencode($row['id']) . "' 
                       onclick=\"return confirm('Are you sure you want to delete this job?');\">
                       Delete
                    </a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center;'>No job listings found.</p>";
}

echo "</body></html>";

$conn->close();
?>
