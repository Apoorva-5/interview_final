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

// Fetch the category associated with the exam
$sql = "SELECT category_name FROM exam_schedule WHERE exam_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $exam_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if category exists
if ($result->num_rows == 0) {
    die("Error: No category found for this exam.");
}

$exam = $result->fetch_assoc();
$category_name = $exam['category_name']; // Get category name

$total_questions = 0;
$correct_count = 0;

// Fetch all questions for the given exam category from question_bank
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
            $insert_sql = "INSERT INTO submit_answer (user_id, exam_id, question_id, user_answer, correct_ans, is_correct) VALUES (?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("siissi", $user_id, $exam_id, $question_id, $user_answer, $correct_answer, $is_correct);
            
            if (!$insert_stmt->execute()) {
                echo "Error inserting answer for question ID $question_id: " . $insert_stmt->error;
            }
        }
    }
    
    header("Location: result1.php?exam_id=$exam_id&total=$total_questions&correct=$correct_count");
    exit();
} else {
    echo "No questions found for the exam category: $category_name";
}

$stmt->close();
$conn->close();
?>
