<?php
session_start();
include('../dbconnection.php');

// ✅ Display the received parameters for debugging
echo "<pre>";
echo "Exam ID: " . ($_GET['exam_id'] ?? 'NOT RECEIVED') . "<br>";
echo "Student ID: " . ($_GET['login_id'] ?? 'NOT RECEIVED') . "<br>";
echo "</pre>";

// ✅ Ensure both `exam_id` and `login_id` are provided
if (!isset($_GET['exam_id']) || empty($_GET['exam_id']) || !isset($_GET['login_id']) || empty($_GET['login_id'])) {
    echo "Error: Exam ID or Student ID is missing.";
    exit;
}

$exam_id = $_GET['exam_id'];
$student_id = $_GET['login_id'];

// ✅ Fetch the USN from students_result table
$usn_query = "SELECT USN FROM students_result WHERE login_id = ? AND exam_id = ?";
$usn_stmt = $conn->prepare($usn_query);
$usn_stmt->bind_param("ii", $student_id, $exam_id);
$usn_stmt->execute();
$usn_result = $usn_stmt->get_result();
$usn_row = $usn_result->fetch_assoc();
$usn = $usn_row['USN'] ?? 'N/A';

// ✅ Fetch the category-wise result from `submit_answer`
$sql = "SELECT 
            sa.category_name, 
            SUM(CASE WHEN sa.is_correct = 1 THEN 1 ELSE 0 END) AS correct_answers, 
            COUNT(sa.question_id) AS total_questions
        FROM submit_answer sa
        WHERE sa.user_id = ? AND sa.exam_id = ?
        GROUP BY sa.category_name";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $exam_id);
$stmt->execute();
$result = $stmt->get_result();

$results = [];
while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}

$stmt->close();
$conn->close();
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
    <main class="container mt-5">
    <div class="alert alert-info text-center">
        <h4>Admin Side Result Overview</h4>
        <p><strong>USN:</strong> <?php echo htmlspecialchars($usn); ?></p>
        <p><strong>Exam ID:</strong> <?php echo htmlspecialchars($exam_id); ?></p>
    </div>

    <?php if (!empty($results)): ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Correct Answers</th>
                    <th>Total Questions</th>
                    <th>Score (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <?php
                    $total_correct = $result['correct_answers'];
                    $total_questions = $result['total_questions'];
                    $category_name = $result['category_name'];
                    $percentage = ($total_questions > 0) ? round(($total_correct / $total_questions) * 100, 2) : 0;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category_name); ?></td>
                        <td><?php echo $total_correct; ?></td>
                        <td><?php echo $total_questions; ?></td>
                        <td><?php echo $percentage; ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            No results found for this student.
        </div>
    <?php endif; ?>

    <div class="text-center">
        <a href="result.php" class="btn btn-primary">Back to Results</a>
    </div>
</main>

<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
    <div class="copyright">
        &copy; Copyright <strong><span>Designed by Kakunje Software Private Limited</span></strong>. All Rights Reserved
    </div>
</footer>

</body>
</html>
