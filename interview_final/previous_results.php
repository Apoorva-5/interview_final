<?php
session_start();
include('dbconnection.php');

// Ensure the user is logged in
if (!isset($_SESSION['login_id'])) {
    echo "You need to log in to view previous results.";
    exit();
}

$user_id = $_SESSION['login_id']; // Logged-in user's ID

// Get exam_id from user input
$exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : '';

// Fetch distinct exam IDs the user has taken exams in
$exam_sql = "SELECT DISTINCT exam_id FROM submit_answer WHERE user_id = ? ORDER BY exam_id DESC";
$exam_stmt = $conn->prepare($exam_sql);
$exam_stmt->bind_param("i", $user_id);
$exam_stmt->execute();
$exam_result = $exam_stmt->get_result();

$exam_ids = [];
while ($exam_row = $exam_result->fetch_assoc()) {
    $exam_ids[] = $exam_row['exam_id'];
}

if ($exam_id) {
    // Fetch category associated with the exam
    $category_sql = "SELECT category_name FROM exam_schedule WHERE exam_id = ?";
    $category_stmt = $conn->prepare($category_sql);
    $category_stmt->bind_param("s", $exam_id);
    $category_stmt->execute();
    $category_result = $category_stmt->get_result();

    if ($category_result->num_rows > 0) {
        $category_row = $category_result->fetch_assoc();
        $category_name = $category_row['category_name'];
    } else {
        $category_name = "Unknown Category";
    }

    // Fetch exam results for the selected exam ID
    $sql = "SELECT question_bank.question, submit_answer.user_answer, submit_answer.correct_ans, submit_answer.is_correct
            FROM submit_answer 
            INNER JOIN question_bank ON submit_answer.question_id = question_bank.id 
            WHERE submit_answer.user_id = ? AND submit_answer.exam_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $exam_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Count total and correct answers
    $total_questions = $result->num_rows;
    $correct_answers = 0;
    $result_summary = [];

    while ($row = $result->fetch_assoc()) {
        if (trim(strtolower($row['user_answer'])) === trim(strtolower($row['correct_ans']))) {
            $row['is_correct'] = 1; // Ensure correct marking
            $correct_answers++;
        } else {
            $row['is_correct'] = 0;
        }
        $result_summary[] = $row;
        
    }
}

// Close connections
$exam_stmt->close();
if (isset($stmt)) $stmt->close();
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
            <!-- Test -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="test.php">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Test</span>
                </a>
            </li>

            <!-- Result -->
            <li class="nav-item ">
                <a class="nav-link collapsed" href="result.php">
                    <i class="bi bi-grid"></i>
                    <span>Result</span>
                </a>
            </li>
            
            <!--Previous test Result -->
            <li class="nav-item active">
                <a class="nav-link active" href="Previous_results.php">
                    <i class="bi bi-journal-text"></i>
                    <span>Previous test Result</span>
                </a>
            </li>
        </ul>
    </aside>

<main id="main" class="main">
<div class="container-fluid">
    <h2 class="text-center">Your Previous Exam Results</h2>
    <form method="GET" class="text-center mb-4">
        <label for="exam_id">Enter Exam ID:</label>
        <input type="text" name="exam_id" id="exam_id" value="<?php echo htmlspecialchars($exam_id); ?>" placeholder="Enter Exam ID" class="form-control d-inline-block w-auto">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <?php if ($exam_id && $total_questions > 0): ?>
        <div class="alert alert-info text-center">
            <h4>Exam ID: <strong><?php echo htmlspecialchars($exam_id); ?></strong></h4>
            <h5>Category: <strong><?php echo htmlspecialchars($category_name); ?></strong></h5>
            <p>You got <strong><?php echo $correct_answers; ?></strong> out of <strong><?php echo $total_questions; ?></strong> correct.</p>
            <p>Score: <strong><?php echo round(($correct_answers / $total_questions) * 100, 2); ?>%</strong></p>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Question</th>
                    <th>Your Answer</th>
                    <th>Correct Answer</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_summary as $result): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($result['question']); ?></td>
                        <td class="<?php echo $result['is_correct'] ? 'text-success' : 'text-danger'; ?>">
                            <?php echo htmlspecialchars($result['user_answer']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($result['correct_ans']); ?></td>
                        <td><?php echo $result['is_correct'] ? '✔️ Correct' : '❌ Incorrect'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-danger">No previous results found for this exam ID.</p>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="test.php" class="btn btn-primary">🏠 Back to Test</a>
    </div>
</main>
</body>
</html>
