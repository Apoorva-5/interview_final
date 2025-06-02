<!-- <!-- <?php

session_start();
include('dbconnection.php'); // Ensure this connects to your database

$delete = $_GET['delete'];

// Handle form submission for scheduling tests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch = $_POST['batch'];
    $examDate = $_POST['examDate'];
    $examTime = $_POST['examTime'];
    $duration = $_POST['Duration'];

    $query = mysqli_query($conn, "INSERT INTO exam_schedule (batch, exam_date, exam_time, duration) VALUES ('$batch','$examDate','$examTime','$duration')");

    header("Location: schedule.php");

}

?> -->


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
    <!-- This is to add js to search bar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="dashborad1.php">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Test -->
            <li class="nav-item ">
                <a class="nav-link collapsed" href="test.php">
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
            <li class="nav-item active">
                <a class="nav-link active" href="schedule.php">
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
                            <h4 class="card-title mb-3">Exam Timings</h4>
                            <form method="POST" class="row g-3">
                                <!-- <div class="col-md-6">
                                    <label for="batch" class="form-label">Batch</label>
                                    <input type="text" class="form-control" name="batch" id="batch" placeholder="Batch"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="examDate" class="form-label">Exam Date</label>
                                    <input type="date" class="form-control" name="examDate" id="examDate" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="examTime" class="form-label">Exam Time</label>
                                    <input type="time" class="form-control" name="examTime" id="examTime" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="Duration" class="form-label">Duration (in minutes)</label>
                                    <input type="number" class="form-control" name="Duration" id="Duration"
                                        placeholder="Enter duration in minutes" required>
                                </div>

                                <div class="col-6 d-flex justify-content-start mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                                     <button type="reset" class="btn btn-secondary">Reset</button>
                                </div> -->
                            </form>
                            <!-- Searchable Table Section -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <input type="text" id="myInput" class="form-control"
                                                        placeholder="Search...">
                                                </div>
                                            </div>
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Batch</th>
                                                        <th>Exam Date</th>
                                                        <th>Exam Time</th>
                                                        <th>Duration</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="myTable">
                                                    <?php
                                                    $query3 = mysqli_query($conn, "select * from exam_schedule");
                                                    while ($row3 = mysqli_fetch_array($query3)) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $row3['batch']; ?></td>
                                                            <td><?php echo $row3['exam_date']; ?></td>
                                                            <td><?php echo $row3['exam_time']; ?></td>
                                                            <td><?php echo $row3['duration']; ?> mins</td>
                                                            <td>
                                                                <a href="delete_schedule.php?delete=<?php echo $row3['id']; ?>"
                                                                    class="btn btn-danger btn-sm">Delete</a>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <!-- <p class="text-center mt-3 text-muted">Designed by Kakunja Software Private Limited</p> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div>
        </div> -->

        </div>

    </main>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Designed by Kakunje Software Private Limited</span></strong>. All Rights
            Reserved
        </div>
    </footer>

    <script>
        /* Name Filter*/
        $(document).ready(function () {
            // search bar id="myInput"
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                //tbody id = "myTable"
                $("#myTable tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

</body>

</html> -->