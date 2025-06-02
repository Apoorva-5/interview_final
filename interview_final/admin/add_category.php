<?php

session_start();
include('../dbconnection.php'); // Ensure this connects to your database

$msg = ""; // To store error/success messages

// Handle form submission for adding category
if (isset($_POST['submit'])) {
    $category = ($_POST['s_category']);

    // Auto-generate id_reference
    // $result = mysqli_query($conn, "SELECT MAX(id_reference) AS max_id FROM language_category");
    // $row = mysqli_fetch_assoc($result);
    // $id_reference = $row['max_id'] + 1;

    $query0 = mysqli_query($conn, "SELECT * FROM language_category WHERE category_name='$category'");
    $ret = mysqli_fetch_array($query0);
  if ($ret > 0) {
    $msg = "Category already exists!";
  }
  else{
    $query = mysqli_query($conn, "INSERT INTO language_category (category_name) VALUES ('$category')");
    header("location:question.php");
  }
}
    
    // if ($query) {
    //     $msg = "Category added successfully!";
    // } else {
    //     $msg = "Failed to add category.";
    // }

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

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">

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

        </ul>
    </aside>
  <main id="main" class="main">

    <section class="section">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="card-title">
                <div class="row">
                  <div class="col-6">
                    <span>Add Category</span>
                  </div>
                  <div class="col-6 d-flex justify-content-end">
                    <a href="question.php" class="btn-back">
                      <!-- <i class="bi bi-house-fill"></i> -->
                       Back
                    </a>
                  </div>
                </div>
              </div>

              <form method="POST">
                <div class="row d-flex justify-content-center">
                  <div class="col-md-6">
                    <div class="card">
                      <div class="card-body">
                        <p><?php echo $msg ?></p>
                    
                        <div class="row">
                          <div class="col-md-12 mt-3">
                            <label>Category</label>
                           
                            <input type="text" class="form-control mt-2" name="s_category" placeholder="Category" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode = 32);" required>
                          </div>
                          <div class="col-6 mt-5">
                            <input type="submit" class="btn btn-primary" name="submit" value="Add">
                          </div>
                          <div class="col-6 mt-5 d-flex justify-content-end">
                            <input type="reset" class="btn btn-primary" name="reset" value="Reset">
                          </div>
                        </div>
                      </div>  
                    </div>

                  </div>
                </div>
              </form>
            </div>
          </div>  
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Kakunje softwares</span></strong>. All Rights Reserved
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