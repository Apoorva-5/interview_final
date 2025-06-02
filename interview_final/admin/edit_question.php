<?php
session_start();
include('../dbconnection.php');

$edit = $_GET['edit'];

// Handle form submission for editing a question
if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_ans = $_POST['correct_ans'];
    $category_name = $_POST['category'];

    // Update query
    $query = "UPDATE question_bank SET 
                question = '$question', 
                option1 = '$option1', 
                option2 = '$option2', 
                option3 = '$option3', 
                option4 = '$option4', 
                correct_ans = '$correct_ans', 
                category_name = '$category_name' 
              WHERE id = '$edit'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Question updated successfully!');</script>";
    } else {
        echo "Error: " . mysqli_error($conn); // Error handling
    }
    header("Location: question.php");
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>
       function runQuery() {
    var selectedCategory = document.getElementById('categorySelect').value;

    if (selectedCategory !== "") {
        fetch('?category=' + encodeURIComponent(selectedCategory))
            .then(response => response.text())
            .then(data => {
                document.getElementById('result').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        document.getElementById('result').value = "";
    }
}

    </script>


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
            <li class="nav-item active">
                <a class="nav-link active" href="question.php">
                    <i class="bi bi-grid"></i>
                    <span>Question Bank</span>
                </a>
            </li>

            <!-- Schedule Test -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="scheduleTest.php">
                    <i class="bi bi-journal-text"></i>
                    <span>Schedule Test</span>
                </a>
            </li>

            <!-- Result -->
            <li class="nav-item">
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

    <!-- ======= Main Content ======= -->
    <main id="main" class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mt-3">Question Bank</h4>
                            <!-- <?php if ($msg != "") { ?>
                                <div class="alert alert-info"> <?php echo $msg; ?> </div>
                            <?php } ?> -->
                            <form method='POST'>
                                <input type="hidden" name="qid" value="<?php echo $qid; ?>">
                                <div class="row g-3">
                                    <?php 
                                    $query3 = mysqli_query($conn, "SELECT * FROM question_bank where id='$edit'");
                                    while ($row3 = mysqli_fetch_array($query3)){ ?>
                                     <div class="col-md-6">
                                        <label class="form-label">Category</label>
                                        <select id="categorySelect" name="category" class="form-control">
                                            <option value="<?php echo $row3['category_name']; ?>"><?php echo $row3['category_name']; ?></option>
                                            <?php
                                            $query1 = mysqli_query($conn, "SELECT * FROM language_category");
                                            while ($row1 = mysqli_fetch_array($query1)) { ?>
                                                <option value="<?php echo $row1['category_name']; ?>">
                                                    <?php echo $row1['category_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="question" class="form-label">Question</label>
                                        <input type="text" id="question" name="question" value="<?php echo $row3['question']; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="option1" class="form-label">Option 1</label>
                                        <input type="text" id="option1" name="option1" value="<?php echo $row3['option1']; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="option2" class="form-label">Option 2</label>
                                        <input type="text" id="option2" name="option2" value="<?php echo $row3['option2']; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="option3" class="form-label">Option 3</label>
                                        <input type="text" id="option3" name="option3" value="<?php echo $row3['option3']; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="option4" class="form-label">Option 4</label>
                                        <input type="text" id="option4" name="option4" value="<?php echo $row3['option4']; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="correct_ans" class="form-label">Correct Answer</label>
                                        <input type="text" id="correct_ans" name="correct_ans" value="<?php echo $row3['correct_ans']; ?>" class="form-control"
                                        >
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <input type="submit" name="save" class="btn btn-primary" value="Update">
                                        
                                    </div>
                                    <?php } ?>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </main>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Kakunje Software Private Limited</span></strong>. All Rights Reserved
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery/jquery.min.js"></script>

    <script>
        /* Name Filter*/
        $(document).ready(function () {
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

</body>

</html>