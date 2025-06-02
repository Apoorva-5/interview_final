<?php
session_start();
include('dbconnection.php'); // Database connection

// If user is not logged in, redirect to login page
if (!isset($_SESSION['login_id'])) {
    header('location:index.php');
    exit();
}

// Check if the batch is set in session
// if (!isset($_SESSION['batch'])==0) {
//     die("Batch information not available. Please log in again.");
// }

$student_batch = $_SESSION['batch']; // Get the student's batch from session

// echo "<script>alert($student_batch);</script>";

// Query to fetch upcoming exam details based on the student's batch
$sql = "SELECT exam_id, exam_date, exam_time, duration 
        FROM exam_schedule 
        WHERE batch = ? AND (exam_date >= CURDATE() OR (exam_date = CURDATE() AND exam_time >= CURTIME()))"; // Upcoming and ongoing exams

$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind the batch value to the query
    $stmt->bind_param("s", $student_batch);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("Error fetching exam details: " . $conn->error);
    }
} else {
    die("Error preparing statement: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Interview</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">TestHub</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        <div class="ms-auto me-3">
            <a href="logout.php" class="btn btn-primary logout">Logout</a>
        </div>
    </header>
    <style>
        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <!-- Test -->
            <li class="nav-item active">
                <a class="nav-link active" href="test.php">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Test</span>
                </a>
            </li>

            <!-- Result -->
            <li class="nav-item ">
                <a class="nav-link collapsed" href="result.php">
                    <i class="bi bi-grid"></i>
                    <span>Result</span>
                </a>
            </li>
            
            <!--Previous test Result -->
            <li class="nav-item ">
                <a class="nav-link collapsed" href="Previous_results.php">
                    <i class="bi bi-journal-text"></i>
                    <span>Previous test Result</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main id="main" class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Student Exam Details</h4>
                            <!-- Display exam details -->
                            <?php if ($result->num_rows > 0): ?>
                                <table class="table text-center table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Exam Date</th>
                                            <th>Exam Time</th>
                                            <th>Duration</th>
                                            <th>Exam Id</th>
                                            <th>Start</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($exam = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($exam['exam_date']); ?></td>
                                                <td><?php echo htmlspecialchars($exam['exam_time']); ?></td>
                                                <td><?php echo htmlspecialchars($exam['duration']); ?></td>
                                                <td><?php echo htmlspecialchars($exam['exam_id']); ?></td>
                                                <td><a href="question_list.php?exam_id=<?php echo htmlspecialchars($exam['exam_id']); ?>" class="btn btn-success">Start Exam</a></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="alert alert-info">No upcoming exams found for your batch.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Designed by Kakunje Software Private Limited</span></strong>. All Rights
            Reserved
        </div>
    </footer>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the statement and connection
$stmt->close();
$conn->close();
?>
