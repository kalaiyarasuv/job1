<?php
session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

if (!$username) {
    echo "<script>alert('You must be logged in to view this page.'); window.location.href='emlogin.html';</script>";
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "job_hive");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle filtering
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

switch ($filter) {
    case 'today':
        $sql = "SELECT * FROM jobs WHERE DATE(posted_at) = CURDATE() ORDER BY posted_at DESC";
        break;
    case '15days':
        $sql = "SELECT * FROM jobs WHERE posted_at >= DATE_SUB(CURDATE(), INTERVAL 15 DAY) ORDER BY posted_at DESC";
        break;
    default:
        $sql = "SELECT * FROM jobs ORDER BY posted_at DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Job Hive - Job Listings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Heebo&family=Inter:wght@700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Heebo', sans-serif;
        }
        .navbar-text {
            font-weight: 600;
            color: #0d6efd;
            margin-right: 1rem;
        }
        .card-title {
            font-weight: 700;
        }
        .filter-buttons .btn.active {
            background-color: #0d6efd;
            color: white;
        }
           .user_profile img{
            width: 20px;
            position: relative;
            top:9px;
        }
        
    </style>
</head>
<body>
<div class="container-xxl bg-white p-0">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
            <h1 class="m-0 text-primary">JobHive</h1>
        </a>
        <button class="navbar-toggler me-4" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="pp.php" class="nav-item nav-link">Home</a>
                <!-- <a href="about.html" class="nav-item nav-link">About</a> -->
                                <a href="contact.html" class="nav-item nav-link">Contact</a>

                <a href ="user_profile.html" class="user_profile">
                   <img src="https://www.pngmart.com/files/23/Profile-PNG-Photo.png" alt="Profile">
                </a>            </div>
            <a href="All-job.php" class="btn btn-outline-primary rounded-0 py-4 px-lg-4 d-none d-lg-block me-2">Post Job</a>
            <a href="logout.php" class="btn btn-outline-danger rounded-0 py-4 px-lg-4 d-none d-lg-block">Logout</a>
        </div>
    </nav>

    <!-- Job Listings -->
    <div class="container mt-5">
        <div class="filter-buttons mb-4 text-center">
            <a href="?filter=all" class="btn btn-outline-primary <?= $filter == 'all' ? 'active' : '' ?>">All</a>
            <a href="?filter=today" class="btn btn-outline-primary <?= $filter == 'today' ? 'active' : '' ?>">Today</a>
            <a href="?filter=15days" class="btn btn-outline-primary <?= $filter == '15days' ? 'active' : '' ?>">Last 15 Days</a>
        </div>

        <h1 class="text-center mb-5">Latest Job Posts</h1>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php 
                    $hasImage = !empty($row['job_image']) && file_exists($row['job_image']); 
                ?>
                <div class="card mb-4 shadow-sm">
                    <div class="row g-0">
                        <p class="mb-1 text-muted"><strong>Posted by:</strong> <?= htmlspecialchars($row['posted_by']) ?></p>

                        <?php if ($hasImage): ?>
                            <div class="col-md-4">
                                <img src="<?= htmlspecialchars($row['job_image']) ?>" class="img-fluid rounded-start" alt="Job Image">
                            </div>
                        <?php endif; ?>
                        <div class="col-md-<?= $hasImage ? '8' : '12' ?>">
                            <div class="card-body">
                                <h4 class="card-title text-primary"><?= htmlspecialchars($row['job_title']) ?></h4>
                                <h6 class="text-muted"><?= htmlspecialchars($row['job_type']) ?></h6>
                                <p class="card-text"><?= nl2br(htmlspecialchars($row['job_description'])) ?></p>
                                <p>
                                    <strong>Category:</strong> <?= htmlspecialchars($row['category']) ?> | 
                                    <strong>Location:</strong> <?= htmlspecialchars($row['location']) ?> | 
                                    <strong>Salary:</strong> <?= htmlspecialchars($row['salary']) ?>
                                </p>
                                <small class="text-muted">
                                    Posted by <?= htmlspecialchars($row['posted_by']) ?> on 
                                    <?= date('F j, Y, g:i a', strtotime($row['posted_at'])) ?>
                                </small>
                               <form action="apply.php" method="post" class="mt-3">
                                    <input type="hidden" name="job_id" value="<?= htmlspecialchars($row['id']) ?>">
                                    <button type="submit" name="apply_type" value="individual" class="btn btn-success me-2">
                                            Apply as Individual
                                    </button>
                                    <button type="submit" name="apply_type" value="group" class="btn btn-primary">
                                            Apply as Group
                                    </button>
                                    </form>

                            </div>
                            
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No job posts available.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="container-fluid bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                    <h5 class="text-white">Job Hive</h5>
                    <p class="mb-0 small">Helping you get the best job and find top talent — even part-time!</p>
                </div>
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <a href="about.html" class="text-white-50 me-3 text-decoration-none">About</a>
                    <a href="index.html" class="text-white-50 me-3 text-decoration-none">Services</a>
                    <a href="contact.html" class="text-white-50 text-decoration-none">Contact</a>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <small class="text-white-50">&copy; 2025 Job Hive</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" style="position: fixed; bottom: 20px; right: 20px; display: none;">
        <i class="bi bi-arrow-up"></i>
    </a>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    });

    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 600);
        return false;
    });
</script>
</body>
</html>

<?php $conn->close(); ?>
