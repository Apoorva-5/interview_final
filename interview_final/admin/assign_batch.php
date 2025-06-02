<?php
session_start();
include('../dbconnection.php');

// // Ensure student is logged in
// if (!isset($_SESSION['login_id']) || empty($_SESSION['login_id'])) {
//     die("Error: User not logged in.");
// }

// $login_id = $_SESSION['login_id'];

// Get student's batch
$batch_query = "SELECT batch FROM students_details WHERE id = ?";
$stmt = $conn->prepare($batch_query);
$stmt->bind_param("s", $login_id);
$stmt->execute();
$batch_result = $stmt->get_result();
$batch_data = $batch_result->fetch_assoc();

if (!$batch_data) {
    die("Error: Batch not found.");
}

$student_batch = $batch_data['batch'];

// Get assigned category (ID reference) for the batch
$category_query = "SELECT id_reference FROM batch_mapping WHERE batch = ?";
$stmt = $conn->prepare($category_query);
$stmt->bind_param("s", $student_batch);
$stmt->execute();
$category_result = $stmt->get_result();
$category_data = $category_result->fetch_assoc();

if (!$category_data) {
    die("No category assigned to your batch.");
}

$id_reference = $category_data['id_reference'];

// Fetch questions based on assigned category
$question_query = "SELECT * FROM questions WHERE id_reference = ?";
$stmt = $conn->prepare($question_query);
$stmt->bind_param("s", $id_reference);
$stmt->execute();
$questions_result = $stmt->get_result();

?>

<h3>Questions for Your Batch</h3>
<ul>
    <?php while ($row = $questions_result->fetch_assoc()) { ?>
        <li><?php echo htmlspecialchars($row['question_text']); ?></li>
    <?php } ?>
</ul>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">Kakunje Software</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
    </header>
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link collapsed" href="student.php">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Student</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="question.php">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Question Bank</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="scheduleTest.php">
                    <i class="bi bi-journal-text"></i>
                    <span>Schedule Test</span>
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link active" href="assign_batch.php">
                    <i class="bi bi-grid"></i>
                    <span>Assign_batch</span>
                </a>
            </li>
        </ul>
    </aside>
    <main id="main" class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mt-3">Assign ID to Your Batch</h4>
                            <form method="POST" action="">
                                <div class="col-md-6">
                                    <label for="batch" class="form-label">Batch</label>
                                    <input type="text" id="batch" name="batch" class="form-control" value="<?php echo htmlspecialchars($student_batch); ?>" readonly>
                                </div>
                                <br>
                                <div class="col-md-6">
                                    <label for="id_reference" class="form-label">Question Category</label>
                                    <select id="id_reference" name="id_reference" class="form-control" required>
                                        <option value="">Select ID</option>
                                        <option value="ID1">Python</option>
                                        <option value="ID2">Java</option>
                                        <option value="ID3">C++</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Assign</button>
                            </form>
                        </div>
                    </div>
                    <h3 class="mt-4">Existing Assignments</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Batch</th>
                                <th>Assigned ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $assignments->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['batch']); ?></td>
                                    <td><?php echo htmlspecialchars($row['id_reference']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

<?php
$conn->close();
?>
