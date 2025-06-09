<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "job_hive");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get data from emlog table
$sql = "SELECT * FROM emlog";
$result = $conn->query($sql);

// Start HTML output
echo "<!DOCTYPE html>
<html>
<head>
    <title>Employer List</title>
    <style>
        body { font-family: Arial; margin: 40px; background-color: #f8f9fa; }
        table { border-collapse: collapse; width: 80%; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #007bff; color: white; }
        h2 { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <h2>Registered Employers</h2>";

if ($result && $result->num_rows > 0) {
    echo "<table>
            <tr><th>ID</th><th>Username</th><th>Email</th><th>Password</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['email']}</td>
                <td>{$row['password']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center;'>No employers found.</p>";
}

echo "</body></html>";

$conn->close();
?>
