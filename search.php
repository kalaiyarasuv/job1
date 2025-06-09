<?php
// Database connection settings
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'job_hive'; // Make sure this matches your DB name

// Connect to MySQL
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form values safely
$job_title = isset($_POST['job_title']) ? $conn->real_escape_string($_POST['job_title']) : '';
$category = isset($_POST['category']) ? $conn->real_escape_string($_POST['category']) : '';
$location = isset($_POST['location']) ? $conn->real_escape_string($_POST['location']) : '';

// Build SQL query
$sql = "SELECT * FROM jobs WHERE 1";

// Add filters based on inputs
if (!empty($job_title)) {
   $sql .= " AND job_title LIKE '%$job_title%'";
}
if (!empty($category)) {
    $sql .= " AND category = '$category'";
}
if (!empty($location)) {
    $sql .= " AND location = '$location'";
}

// Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="mb-4">Search Results</h2>

    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['job_title']); ?></h5>
                    <!-- <p class="card-text"><?php echo isset($row['description']) ? htmlspecialchars($row['description']) : 'No description available'; ?></p> -->
                    <p class="card-text">
                        <small class="text-muted">
                            Category: <?php echo htmlspecialchars($row['category']); ?> |
                            Location: <?php echo htmlspecialchars($row['location']); ?>
                        </small>
                    </p>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<div class="alert alert-warning">No jobs found matching your criteria.</div>';
    }

    $conn->close();
    ?>
    
    <a href="index.html" class="btn btn-secondary mt-3">Back to Search</a>
</div>

</body>
</html>
