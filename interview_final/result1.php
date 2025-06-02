<?php
session_start();
include('dbconnection.php');

// Ensure user is logged in
if (!isset($_SESSION['login_id'])) {
    echo "User not logged in.";
    exit;
}

$user_id = $_SESSION['login_id'];

// Fetch the exam_id from GET parameter
if (!isset($_GET['exam_id']) || empty($_GET['exam_id'])) {
    //echo "Exam ID is missing.";
    echo "No Result Found.";
    exit;
}
$exam_id = $_GET['exam_id'];

// Fetch the category associated with the exam
$sql = "SELECT category_name FROM exam_schedule WHERE exam_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $exam_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No category found for this exam.";
    exit;
}

$exam = $result->fetch_assoc();
$category_name = $exam['category_name']; // Get category name

// Prepare SQL to fetch results for the user in this category
$sql = "SELECT DISTINCT question_bank.question, submit_answer.user_answer, submit_answer.correct_ans 
        FROM submit_answer 
        INNER JOIN question_bank ON submit_answer.question_id = question_bank.id 
        WHERE submit_answer.user_id = ? AND submit_answer.exam_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $exam_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No results found. Please check your submission.";
    exit;
}

$result_summary = [];
$correct_count = 0;

// Process the results
while ($row = $result->fetch_assoc()) {
    $user_answer_normalized = strtolower(trim($row['user_answer']));
    $correct_answer_normalized = strtolower(trim($row['correct_ans']));
    $is_correct = ($user_answer_normalized === $correct_answer_normalized);

    if ($is_correct) {
        $correct_count++;
    }

    $result_summary[] = [
        'question' => $row['question'],
        'user_answer' => $row['user_answer'],
        'correct_ans' => $row['correct_ans'],
        'is_correct' => $is_correct
    ];
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
    <!-- This is to add js to search bar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
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
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="dashborad1.php">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li> -->

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
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="schedule.php">
                    <i class="bi bi-journal-text"></i>
                    <span>Schedule Test</span>
                </a>
            </li> -->

            <!-- Result -->
            <li class="nav-item active">
                <a class="nav-link active" href="result1.php">
                    <i class="bi bi-grid"></i>
                    <span>Result</span>
                </a>
            </li>

            <!-- previous test result -->
            <li class="nav-item">
                <a class="nav-link collapse" href="previous_results.php">
                    <i class="bi bi-journal-text"></i>
                    <span>previous test result</span>
                </a>
            </li>

        </ul>
    </aside>

<body>
<main id="main" class="main">
        <h2 class="text-center">Quiz Results</h2>
        <div class="alert alert-info text-center">
            Category: <strong><?php echo htmlspecialchars($category_name); ?></strong><br>
            You got <strong><?php echo $correct_count; ?></strong> out of <strong><?php echo count($result_summary); ?></strong> questions correct.
        </div>

        <?php foreach ($result_summary as $result): ?>
            <div class="card my-3">
                <div class="card-body">
                    <h5><?php echo htmlspecialchars($result['question']); ?></h5>
                    <p><strong>Correct Answer:</strong> 
                        <span class="text-success"><?php echo htmlspecialchars($result['correct_ans']); ?></span>
                    </p>
                    <p><strong>Your Answer:</strong> 
                        <span class="<?php echo $result['is_correct'] ? 'text-success' : 'text-danger'; ?>">
                            <?php echo htmlspecialchars($result['user_answer']); ?>
                        </span>
                    </p>
                    <p><?php echo $result['is_correct'] ? '✔️ Correct' : '❌ Incorrect'; ?></p>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- <a href="test.php" class="btn btn-primary">Retake Quiz</a> -->
    </main>

</body>
</html>
