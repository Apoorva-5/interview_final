<?php

session_start();
error_reporting(0);
include('../dbconnection.php'); // Ensure this connects to your database
// include('pagination.php'); // Include the pagination file

// Handle form submission for scheduling tests
if (isset($_POST['save'])) {
    $category_name = $_POST['category'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_ans = $_POST['correct_ans'];

    $query = mysqli_query($conn, "INSERT INTO question_bank (question, option1, option2, option3, option4, correct_ans, category_name) 
                                  VALUES ('$question','$option1','$option2','$option3','$option4','$correct_ans','$category_name')");

    if ($query) {
        header("Location: question.php"); // Redirect after successful insertion
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// AJAX handler to filter by category
// Handle AJAX request to filter by category
if (isset($_GET['category'])) {
    $category = $_GET['category']; // Get the category selected from the dropdown
    if (!empty($category)) {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM question_bank WHERE category_name = ?");
        if ($stmt === false) {
            die('Failed to prepare statement: ' . $conn->error);
        }

        // Bind the parameter to the prepared statement
        $stmt->bind_param("s", $category); // 's' denotes the type (string) of the parameter
        $stmt->execute();
        $result = $stmt->get_result();

        // Get the number of rows returned
        $count = $result->num_rows;
        $rowCount = $count + 1;
        // Return the row count
        echo $rowCount; // This will send the row count as the response
        $stmt->close();
    } else {
        echo "Category is empty.";
    }
    exit; // Terminate the PHP execution after processing the AJAX request
}


// Ensure pagination query runs on every page load
// if (!isset($query3)) {
    $limit = 10;  // Records per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    
    // SQL Query with Correct Numbering and Pagination
    $query3 = mysqli_query($conn, "
    SELECT *, 
    ROW_NUMBER() OVER (PARTITION BY category_name ORDER BY id) AS question_no
    FROM question_bank
    LIMIT $offset, $limit
");


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

    <script>
       function runQuery() {
    var selectedCategory = document.getElementById('categorySelect').value;

    if (selectedCategory !== "") {
        fetch('?category=' + encodeURIComponent(selectedCategory))
            .then(response => response.text())
            .then(data => {
                document.getElementById('result').value = data;
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
                                    <div class="col-md-10">
                                        <label for="category" class="form-label">Category</label>

                                        <select id="categorySelect" name="category" class="form-control" onchange="runQuery()">
                                            <option value="">Select Category</option>
                                            <?php
                                            // Fetch categories from the database
                                            $query1 = mysqli_query($conn, "SELECT * FROM language_category");
                                            while ($row1 = mysqli_fetch_array($query1)) { ?>
                                                <option value="<?php echo $row1['category_name']; ?>">
                                                    <?php echo $row1['category_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>


                                    </div>
                                    <div class="col-md-2 col-5 mt-5">
                                        <a href="add_category.php" class="btn btn-primary ms-2"><i
                                                class="bi bi-plus"></i></a>
                                        <a href="delete_category.php" class="btn btn-danger ms-2"><i
                                                class="bi bi-dash"></i></a>
                                    </div>

                                    </select>
                                    <!-- <div ></div> -->
                                    <div class="col-md-2 col-1">
                                        <label class="form-label">Question No.</label>
                                        <!-- <p id=""></p> -->
                                        <input type="text" id="result" name="result" class="form-control" required>
                                    </div>
                                    <div class="col-md-10 col-9">
                                        <label for="question" class="form-label">Question</label>
                                        <input type="text" id="question" name="question" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="option1" class="form-label">Option 1</label>
                                        <input type="text" id="option1" name="option1" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="option2" class="form-label">Option 2</label>
                                        <input type="text" id="option2" name="option2" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="option3" class="form-label">Option 3</label>
                                        <input type="text" id="option3" name="option3" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="option4" class="form-label">Option 4</label>
                                        <input type="text" id="option4" name="option4" class="form-control" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="correct_ans" class="form-label">Correct Answer</label>
                                        <input type="text" id="correct_ans" name="correct_ans" class="form-control"
                                            required>
                                    </div>

                                    <!-- </div>
                                            <div class="col-xl-1 col-lg-3 col-md-2 col-sm-3 col-5 mt-5">
                                                <a href="add_category.php" class="btn-small"><i class="bi bi-plus"></i></a>
                                                <a href="delete_category.php" class="btn-small"><i class="bi bi-dash"></i></a>
                                            </div> -->

                                    <div class="col-md-12 mt-3">
                                        <input type="submit" name="save" class="btn btn-primary" value="Submit">
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div style="overflow-x:auto;">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12 mt-3">
                                    <input type="text" id="myInput" class="form-control"
                                        placeholder="Search for questions">
                                </div>
                            </div>
                            <div style="overflow:auto">
                            <table class="table text-center table-bordered">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <!-- <th>Question No</th> -->
                                        <th>Category</th>
                                        <th>Question</th>
                                        <th>Option1</th>
                                        <th>Option2</th>
                                        <th>Option3</th>
                                        <th>Option4</th>
                                        <th>Answer</th>
                                        <th>Delete</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                    <?php
                                    $cnt = 1;
                                    while ($row3 = mysqli_fetch_array($query3)) { ?>
                                        <tr>
                                            <!-- <td><?php echo $cnt; ?></td> -->
                                            <!-- <td><?php echo $row3['question_no']; ?></td> -->
                                            <td><?php echo $row3['category_name']; ?></td>
                                            <td><?php echo $row3['question']; ?></td>
                                            <td><?php echo $row3['option1']; ?></td>
                                            <td><?php echo $row3['option2']; ?></td>
                                            <td><?php echo $row3['option3']; ?></td>
                                            <td><?php echo $row3['option4']; ?></td>
                                            <td><?php echo $row3['correct_ans']; ?></td>
                                            <td>
                                                <a href="delete_question.php?delete=<?php echo $row3['id']; ?>"
                                                    class="bi bi-trash"></a>
                                            </td>
                                            <td><a href="edit_question.php?edit=<?php echo $row3['id']; ?>"
                                            class="bi bi-pencil"></a></td>
                                        </tr>
                                    <?php $cnt = $cnt + 1; } ?>
                                </tbody>

                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include Pagination Navigation only at the bottom -->
        <?php include('pagination.php'); ?>

    </main>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Kakunje Software Private Limited</span></strong>. All Rights Reserved
        </div>
    </footer>

    <script src="assets/js/main.js"></script>

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