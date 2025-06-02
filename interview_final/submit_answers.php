<?php
session_start();
include('dbconnection.php');

// Ensure exam_id is set, otherwise, handle the error
if (!isset($_POST['exam_id']) || empty($_POST['exam_id'])) {
    die("Error: Exam ID is missing or invalid.");
}
$exam_id = $_POST['exam_id'];

// Check if user is logged in
if (!isset($_SESSION['login_id'])) {
    header('location:index.php');
    exit();
}
$user_id = $_SESSION['login_id'];

// ✅ Step 1: Check if the student has already attempted the exam
$check_sql = "SELECT * FROM submit_answer WHERE user_id = ? AND exam_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $user_id, $exam_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // If already attempted, redirect to the result page
    header("Location: result1.php?exam_id=$exam_id");
    exit();
}

// ✅ Step 2: Fetch the category associated with the exam
$sql = "SELECT category_name FROM exam_schedule WHERE exam_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $exam_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: No category found for this exam.");
}

$exam = $result->fetch_assoc();
$category_name = $exam['category_name']; // Get category name

$total_questions = 0;
$correct_count = 0;

// ✅ Step 3: Fetch all questions for the given exam category from question_bank
$sql = "SELECT id, correct_ans FROM question_bank WHERE category_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $question_id = $row['id'];
        $correct_answer = $row['correct_ans'];
        $user_answer_key = 'question_' . $question_id;

        if (isset($_POST[$user_answer_key])) {
            $user_answer = $_POST[$user_answer_key];
            $is_correct = ($user_answer === $correct_answer) ? 1 : 0;
            $correct_count += $is_correct;
            $total_questions++;

            // Insert user answer into submit_answer table
            $insert_sql = "INSERT INTO submit_answer (user_id, exam_id, question_id, user_answer, correct_ans, is_correct, category_name) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("siissis", $user_id, $exam_id, $question_id, $user_answer, $correct_answer, $is_correct, $category_name);

            if (!$insert_stmt->execute()) {
                echo "Error inserting answer for question ID $question_id: " . $insert_stmt->error;
            }
        }
    }

    // ✅ Step 4: Store the result in the students_result table
    $total_marks = "$correct_count"; // e.g., "7 / 10"

    // Fetch USN associated with the student
    $usn_query = "SELECT USN FROM students_details WHERE id = ?";
    $usn_stmt = $conn->prepare($usn_query);
    $usn_stmt->bind_param("i", $user_id);
    $usn_stmt->execute();
    $usn_result = $usn_stmt->get_result();
    $usn_row = $usn_result->fetch_assoc();
    $usn = $usn_row['USN'];

    // Insert the result into students_result table
    $result_sql = "INSERT INTO students_result (USN, exam_id, category_name, login_id, total_marks) 
                   VALUES (?, ?, ?, ?, ?)";
    $result_stmt = $conn->prepare($result_sql);
    $result_stmt->bind_param("sisis", $usn, $exam_id, $category_name, $user_id, $total_marks);

    if (!$result_stmt->execute()) {
        echo "Error storing result: " . $result_stmt->error;
    }

    // ✅ Redirect to result page after submitting the answers
    header("Location: result1.php?exam_id=$exam_id&total=$total_questions&correct=$correct_count");
    exit();
} else {
    echo "No questions found for the exam category: $category_name";
}

$stmt->close();
$conn->close();
?>
