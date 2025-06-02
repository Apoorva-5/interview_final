<?php

session_start();
// error_reporting(0);
include('../dbconnection.php');

$msg = ""; // To store error/success messages


if(isset($_POST['save'])){
    $id = $_POST['id'];
    $USN = $_POST['USN'];
    $exam_id = $_POST['exam_id'];
    $category_name = $_POST['category_name'];
    $login_id = $_POST['login_id'];
    $total_marks = $_POST['total_marks'];
    // $College = $_POST['College'];
   

    $query = mysqli_query($conn, "INSERT INTO students_result (Id, USN, exam_id, category_name, login_id, total_marks) VALUES ('$id', '$USN, $exam_id', '$category_name', '$login_id', '$total_marks')");


    header("Location: result.php");
    // exit();
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

    <!--Search name from table-->
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
            <li class="nav-item">
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
            <li class="nav-item ">
                <a class="nav-link collapsed" href="scheduleTest.php">
                    <i class="bi bi-journal-text"></i>
                    <span>Schedule Test</span>
                </a>
            </li>

            <!-- Result -->
            <li class="nav-item active">
                <a class="nav-link active" href="result.php">
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
                    <div class="card-body mt-3">
                        <h3>Students Result</h3>
                        <!-- <input class="form-control" id="myInput" type="search" placeholder="Search here"> -->

                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>USN</th>
                                    <th>Exam id</th>
                                    <th>category name</th>
                                    <th>Login id</th>
                                    <th>Total marks</th>
                                    
                                </tr>
                            </thead>
                            <tbody id="myTable">
                                <?php 
                                $query1 = mysqli_query($conn, "SELECT * FROM students_result");
                                while($row1 = mysqli_fetch_array($query1)) {
                                ?>
                                <!-- <tr class="<?php echo ($row1['blocked'] == 1) ? 'table-danger' : ''; ?>"> -->
                                    <td><?php echo $row1['id']; ?></td>
                                    <td><?php echo $row1['USN']; ?></td>
                                    <td><?php echo $row1['exam_id']; ?></td>
                                    <td><?php echo $row1['category_name']; ?></td> 
                                    <td><?php echo $row1['login_id']; ?></td>
                                    <td><?php echo $row1['total_marks']; ?></td>
                                    <!-- <td><?php echo $row1['College']; ?></td>  -->
                                    
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <!-- <button class="btn btn-primary" onclick="showBlockModal()">Block Student</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal for blocking students
<div id="blockModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="hideBlockModal()">&times;</span>
        <h3>Block a Student</h3>
        <label for="blockStudentId">Enter Student ID:</label>
        <input type="number" id="blockStudentId" class="form-control">
        <button class="btn btn-warning mt-2" onclick="blockStudent()">Block</button>
    </div>
</div> -->

<

<!-- <style>
    /* Modal styling */
    .modal {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        margin: 15% auto;
        width: 50%;
        text-align: center;
        border-radius: 10px;
    }

    .close {
        color: red;
        float: right;
        font-size: 28px;
        cursor: pointer;
    }
</style> -->

    <!-- ======= Footer ======= -->
    <!-- <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Designed by Kakunje Software Private Limited</span></strong>. All Rights Reserved
        </div>
    </footer> -->
<!-- <script src="assets/js/main.js"></script>
<script>
    /* Name Filter*/ 
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
}); -->
</script>
</body>
</html>
