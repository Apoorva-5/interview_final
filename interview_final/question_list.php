<?php
session_start();
include('dbconnection.php');
$test_id = $_GET['exam_id'];

// Set the default timezone
date_default_timezone_set('Asia/Kolkata');

// Ensure user is logged in
if (!isset($_SESSION['login_id'])) {
    header('location:index.php');
    exit();
}

// Ensure batch information is available
if (!isset($_SESSION['batch'])) {
    die("Batch information not available. Please log in again.");
}

$student_batch = $_SESSION['batch'];
$user_id = $_SESSION['login_id'];

// ✅ Step 1: Check if the student has already attempted the exam
$check_sql = "SELECT * FROM submit_answer WHERE user_id = ? AND exam_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $user_id, $test_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "<div class='alert alert-danger text-center'>
            You have already attempted this exam. Redirecting to the results page...
          </div>";
    header("Refresh: 2; URL=result1.php?exam_id=$test_id");
    exit();
}

// ✅ Step 2: Fetch exam details
$sql = "SELECT * FROM exam_schedule WHERE batch = ? AND exam_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $student_batch, $test_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $exam = $result->fetch_assoc();
    $exam_id = $exam['exam_id'];
    $exam_date = $exam['exam_date'];
    $exam_time = $exam['exam_time'];
    $duration = intval($exam['duration']);
    $category_name = $exam['category_name'];

    // Calculate exam start and end time
    $exam_start_datetime = strtotime($exam_date . ' ' . $exam_time);
    $exam_end_datetime = $exam_start_datetime + ($duration * 60);
    $current_time = time();

    if ($current_time < $exam_start_datetime) {
        echo "<div class='alert alert-info text-center'>
                Upcoming Exam: " . date('d M Y, h:i A', $exam_start_datetime) . "<br>
                The exam has not yet started. Please wait for the scheduled time.
              </div>";
    } elseif ($current_time > $exam_end_datetime) {
        echo "<div class='alert alert-danger text-center'>
                The scheduled exam has already concluded. Please contact the exam administrator for more details.
              </div>";
        exit;
    } else {
        $remaining_time = $exam_end_datetime - $current_time;
        echo "<div id='timer' class='alert alert-warning text-right' style='font-size: 15px; font-weight: bold; position: absolute; top: 10px; right: 20px;'>Time Remaining: <span id='countdown'></span></div>";

        // Fetch questions for the exam
        $sql = "SELECT * FROM question_bank WHERE category_name = ? ORDER BY id ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $question_result = $stmt->get_result();

        if ($question_result->num_rows > 0) {
            echo "<form id='examForm' method='post' action='submit_answers.php' onsubmit='disableWarning()'>
                    <input type='hidden' name='exam_id' value='" . htmlspecialchars($exam_id) . "'>";

            while ($row = $question_result->fetch_assoc()) {
                echo "<div class='card my-3'>
                        <div class='card-body'>
                            <h5 class='card-title'>" . htmlspecialchars($row['question']) . "</h5>";

                for ($i = 1; $i <= 4; $i++) {
                    $option = htmlspecialchars($row['option' . $i]);
                    echo "<div class='form-check'>
                            <input class='form-check-input' type='radio' name='question_" . $row['id'] . "' value='" . $option . "' id='question_" . $row['id'] . "_" . $i . "'>
                            <label class='form-check-label' for='question_" . $row['id'] . "_" . $i . "'>" . $option . "</label>
                          </div>";
                }

                echo "</div></div>";
            }

            echo "<button type='submit' class='btn btn-success'>Submit Answers</button>
                  </form>";
        } else {
            echo "<p>No questions available for this exam.</p>";
        }
    }
} else {
    echo "<div class='alert alert-danger text-center'>No exam schedule found for this batch.</div>";
}

$stmt->close();
$conn->close();
?>

<script>
let warningGiven = false;
let remainingTime = <?php echo $remaining_time; ?>;
let formSubmitted = false;

function disableWarning() {
    formSubmitted = true;
}

document.addEventListener("visibilitychange", function() {
    if (!formSubmitted && document.hidden) {
        if (!warningGiven) {
            alert("Warning! You are not allowed to switch tabs. If you switch again, your test will be submitted automatically.");
            warningGiven = true;
        } else {
            document.getElementById("examForm").submit();
        }
    }
});

document.addEventListener("copy", function(event) {
    event.preventDefault();
    alert("Copying is not allowed during the exam!");
});

function updateCountdown() {
    let minutes = Math.floor(remainingTime / 60);
    let seconds = remainingTime % 60;
    document.getElementById("countdown").innerText = `${minutes}m ${seconds}s`;
    
    if (remainingTime <= 0) {
        clearInterval(timerInterval);
        document.getElementById("examForm").submit();
    }
    remainingTime--;
}

let timerInterval = setInterval(updateCountdown, 1000);
updateCountdown();
</script>
