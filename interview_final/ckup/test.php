<?php
session_start();
include('dbconnection.php'); // Make sure to include your DB connection file

// Handle form submission
$batch = isset($_GET['batch']) ? $_GET['batch'] : '';

if (empty($batch)) {
    echo "No batch selected.";
    exit;
}

// Fetch questions for the selected batch
$sql = "SELECT * FROM question_bank WHERE batch = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $batch);
$stmt->execute();
$result = $stmt->get_result();

// Check if query is successful
if (!$result) {
    die("Error executing query: " . $conn->error);
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
                <span class="d-none d-lg-block">NiceAdmin</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
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

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="dashborad1.php">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Test -->
            <li class="nav-item active">
                <a class="nav-link active" href="test.php">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Test</span>
                </a>
            </li>

            <!-- Question Bank
            <li class="nav-item">
                <a class="nav-link collapsed" href="question.html">
                    <i class="bi bi-grid"></i>
                    <span>Question Bank</span>
                </a>
            </li> -->

            <!-- Schedule Test -->
            <li class="nav-item ">
                <a class="nav-link collapsed" href="schedule.php">
                    <i class="bi bi-journal-text"></i>
                    <span>Schedule Test</span>
                </a>
            </li>

            <!-- Result -->
            <li class="nav-item ">
                <a class="nav-link collapsed" href="result1.php">
                    <i class="bi bi-grid"></i>
                    <span>Result</span>
                </a>
            </li>

        </ul>
    </aside>

    <!-- ======= Main Content ======= -->
    <main id="main" class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Students</h4>
                            <!-- Form Section -->
                            <form method = "POST" class="row g-3">
                                <div class="col-md-6">
                                    <label for="batch" class="form-label">Batch</label>
                                    <select class="form-select" id="batch" name="batch">
                                        <option selected disabled>Choose a Batch</option>
                                        <option value="Batch1">Batch1</option>
                                        <option value="Batch2">Batch2</option>
                                        <option value="Batch3">Batch3</option>
                                        <option value="Batch4">Batch4</option>
                                    </select>
                                </div>
                            </form>
                             
                            <!-- Button Section -->
                            <div class="col-6 d-flex justify-content-start mt-3">
                                <a href="page.php" class="btn btn-primary me-2">Submit</a>
                                <!-- Uncomment this for a reset button -->
                                <!-- <button type="reset" class="btn btn-secondary">Reset</button> -->
                            </div>
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

</body>

</html>