<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "job_hive");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch vacancy count by category
$sql = "SELECT category, COUNT(*) as vacancy_count FROM jobs GROUP BY category";
$result = $conn->query($sql);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $key = strtolower(trim($row['category']));
        $categories[$key] = $row['vacancy_count'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Explore By Category</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Add your Bootstrap or other CSS links here -->
</head>
<body>
<div class="container-xxl py-5">
    <div class="container">
        <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Explore By Category</h1>
        <div class="row g-4">
            <?php
            // Helper function to print each category card
            function categoryCard($iconClass, $name, $categories) {
                $key = strtolower(trim($name));
                $count = isset($categories[$key]) ? $categories[$key] : 0;
                echo '
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <a class="cat-item rounded p-4" href="">
                        <i class="fa fa-3x ' . $iconClass . ' text-primary mb-4"></i>
                        <h6 class="mb-3">' . $name . '</h6>
                        <p class="mb-0">' . $count . ' Vacancy</p>
                    </a>
                </div>';
            }

            categoryCard("fa-mail-bulk", "Marketing", $categories);
            categoryCard("fa-headset", "Customer Service", $categories);
            categoryCard("fa-user-tie", "Human Resource", $categories);
            categoryCard("fa-tasks", "Project Management", $categories);
            categoryCard("fa-chart-line", "Business Development", $categories);
            categoryCard("fa-hands-helping", "Sales & Communication", $categories);
            categoryCard("fa-book-reader", "Teaching & Education", $categories);
            categoryCard("fa-drafting-compass", "Design & Creative", $categories);
            ?>
        </div>
    </div>
</div>
</body>
</html>
