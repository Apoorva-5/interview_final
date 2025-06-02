<?php

session_start();
error_reporting(0);
include('../dbconnection.php'); // Ensure this connects to your database

 $delete = $_GET['delete'];

// Handle form submission for scheduling tests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category'];
    $Branch = $_POST['Branch'];
    $batch = $_POST['batch'];
    $examDate = $_POST['examDate'];
    $examTime = $_POST['examTime'];
    $duration = $_POST['Duration'];
    

    $query=mysqli_query($conn,"INSERT INTO exam_schedule (Branch, batch, exam_date, exam_time, duration,category_name) VALUES ('$Branch', '$batch','$examDate','$examTime','$duration','$category_name')");
    
    header("Location: scheduleTest.php");
  
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
    </header>
    <style>
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">

            <!-- Dashboard -->
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="dashboard.php">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li> -->

            <!-- Student -->
            <li class="nav-item ">
                <a class="nav-link collapsed" href="student.php">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Student</span>
                </a>
            </li>

            <!-- Question Bank -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="question.php">
                    <i class="bi bi-grid"></i>
                    <span>Question Bank</span>
                </a>
            </li>

            <!-- Schedule Test -->
            <li class="nav-item active">
                <a class="nav-link active" href="scheduleTest.php">
                    <i class="bi bi-journal-text"></i>
                    <span>Schedule Test</span>
                </a>
            </li>

            <!-- Result --> 
            <li class="nav-item ">
                <a class="nav-link collapsed" href="result.php">
                    <i class="bi bi-grid"></i>
                    <span>Result</span>
                </a> 
            </li>

            <!-- assign_batch -->
         <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="assign_batch.php">
          <i class="bi bi-grid"></i>
          <span>Assign_batch</span>
        </a>
      </li>  -->

        </ul>
    </aside>


    <main id="main" class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="card">
                        <!-- <div class="card-body">
                            <h4 class="card-title mb-4">Students</h4> -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
        <!-- </div> -->

            <!-- Schedule Test Form -->
            <div class="card mb-4">
                <div class="card-body">
                <h4 class="mt-3">Schedule Test</h4>
                    <form action="scheduleTest.php" method="POST">
                    <div class="row g-4">
                    <div class="col-md-6">
                            <label for="Branch" class="form-label">Branch/Course</label>
                            <input type="text" class="form-control" id="Branch" name="Branch" required>
                        </div>
                        <div class="col-md-6">
                            <label for="batch" class="form-label">Batch/Section</label>
                            <select id="batch" name="batch" class="form-control" required>
                                <option value="">Select Batch/Section</option>
                                <option>Batch1</option>
                                <option>Batch2</option>
                                <option>Batch3</option>
                                <option>Batch4</option>
                            </select>
                        
                        </div>
                        <div class="col-md-6">
                            <label for="examDate" class="form-label">Exam Date</label>
                            <input type="date" class="form-control" id="examDate" name="examDate" required>
                        </div>

                        <div class="col-md-6">
                            <label for="examTime" class="form-label">Exam Time</label>
                            <input type="time" class="form-control" id="examTime" name="examTime" required>
                        </div>

                        <div class="col-md-6">
                            <label for="Duration" class="form-label">Duration (in minutes)</label>
                            <input type="number" class="form-control" id="Duration" name="Duration" required>
                        </div>

                        <div class="col-md-6">
                            <label for="id_reference" class="form-label">Question category</label>
                            <select id="category" name="category" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php
                                    $query1 = mysqli_query($conn, "SELECT * FROM language_category");
                                    while ($row = mysqli_fetch_array($query1)) { ?>
                                        <option value="<?php echo $row['category_name']; ?>"><?php echo $row['category_name']; ?></option>
                                    <?php }
                                    ?>
                                </select>

                        </div>

                                        <div class="col-md-12 mt-3">
                                            <input type="submit" name="save" class="btn btn-primary" value="Submit">
                                        </div>
                    </div>
                    </form>
                </div>
                </div>
            </div>

            <!-- Scheduled Tests Table -->
            <div class="card" >
                <div class="card-body mt-3">
                    <h4>Scheduled Tests</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>Exam_id</th>
                                <th>Caterogy</th>
                                <th>Branch</th>
                                <th>Batch</th>
                                <th>Exam Date</th>
                                <th>Exam Time</th>
                                <th>Duration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query3=mysqli_query($conn, "select * from exam_schedule");
                            $count=1;
                            while($row3=mysqli_fetch_array($query3)){
                                ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo $row3['exam_id']; ?></td>
                                        <td><?php echo $row3['category_name']; ?></td>
                                        <td><?php echo $row3['Branch']; ?></td>
                                        <td><?php echo $row3['batch']; ?></td>
                                        <td><?php echo $row3['exam_date']; ?></td>
                                        <td><?php echo $row3['exam_time']; ?></td>
                                        <td><?php echo $row3['duration']; ?> mins</td>
                                        
                                        <td>
                                            <a href="delete_schedule.php?delete=<?php echo $row3['exam_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                <?php $count=$count+1; } 
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
