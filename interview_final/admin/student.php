<?php

session_start();
error_reporting(0);
include('../dbconnection.php');

$msg = ""; // To store error/success messages


if(isset($_POST['save'])){
    // $Id = $_POST['Id'];
    $Username = $_POST['Username'];
    $USN = $_POST['USN'];
    $Mobile_no = $_POST['Mobile_no'];
    $Email_id = $_POST['Email_id'];
    $Branch = $_POST['Branch'];
    $College = $_POST['College'];
   

    $query = mysqli_query($conn, "INSERT INTO students_details (Username, USN, Mobile_no, Email_id, Branch, College) VALUES ('$Username','$USN','$Mobile_no','$Email-id','$Branch','$College')");


    header("Location: student.php");
    // exit();
}
// Block Student
if(isset($_GET['block'])){
    $id = $_GET['block'];
    mysqli_query($conn, "UPDATE students_details SET blocked = 1 WHERE Id = '$id'");
    header("Location: student.php");
}

// Unblock Student
if(isset($_GET['unblock'])){
    $id = $_GET['unblock'];
    mysqli_query($conn, "UPDATE students_details SET blocked = 0 WHERE Id = '$id'");
    header("Location: student.php");
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
            <li class="nav-item active">
                <a class="nav-link active" href="student.php">
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
            <li class="nav-item ">
                <a class="nav-link collapsed" href="result.php">
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
                    <div class="card-body" style="width: 100%;">
                        <h3 class="mt-3">Students Details</h3>
                        <input class="form-control" id="myInput" type="search" placeholder="Search here">
                        <div style="overflow:auto">
                            
                        <table class="table text-center table-bordered mt-3 ">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>USN</th>
                                    <th>Mobile_no</th>
                                    <th>Email_id</th>
                                    <th>Branch</th>
                                    <th>Semester</th>
                                    <th>College</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="myTable">
                                <?php 
                                $query1 = mysqli_query($conn, "SELECT * FROM students_details");
                                while($row1 = mysqli_fetch_array($query1)) {
                                ?>
                                <tr class="<?php echo ($row1['blocked'] == 1) ? 'table-danger' : ''; ?>">
                                    <td><?php echo $row1['Username']; ?></td>
                                    <td><?php echo $row1['USN']; ?></td>
                                    <td><?php echo $row1['Mobile_no']; ?></td>
                                    <td><?php echo $row1['Email_id']; ?></td> 
                                    <td><?php echo $row1['Branch']; ?></td>
                                    <td><?php echo $row1['semester']; ?></td>
                                    <td><?php echo $row1['College']; ?></td> 
                                    <td>
                                        <?php if ($row1['blocked'] == 1) { ?>
                                            <span class="text-danger">Blocked</span>
                                        <?php } else { ?>
                                            <span class="text-success">Active</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($row1['blocked'] == 0) { ?>
                                            <a href="student.php?block=<?php echo $row1['Id']; ?>" class="btn btn-warning btn-sm">Block</a>
                                        <?php } else { ?>
                                            <a href="student.php?unblock=<?php echo $row1['Id']; ?>" class="btn btn-success btn-sm">Unblock</a>
                                        <?php } ?>
                                    </td>
                                    <td><a href="delete_student.php?delete=<?php echo $row1['Id']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
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

<!-- Modal for blocking students -->
<div id="blockModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="hideBlockModal()">&times;</span>
        <h3>Block a Student</h3>
        <label for="blockStudentId">Enter Student ID:</label>
        <input type="number" id="blockStudentId" class="form-control">
        <button class="btn btn-warning mt-2" onclick="blockStudent()">Block</button>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    function showBlockModal() {
        document.getElementById("blockModal").style.display = "block";
    }

    function hideBlockModal() {
        document.getElementById("blockModal").style.display = "none";
    }

    function blockStudent() {
        let studentId = document.getElementById("blockStudentId").value;
        if (studentId) {
            window.location.href = "student.php?block=" + studentId;
        } else {
            alert("Please enter a valid Student ID.");
        }
    }
</script>

<style>
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
</style>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Designed by Kakunje Software Private Limited</span></strong>. All Rights Reserved
        </div>
    </footer>
<script src="assets/js/main.js"></script>
<script>
    /* Name Filter*/ 
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
</body>
</html>
