<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "students_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch questions for a specific batch
$batch = 'BatchA'; // Replace 'BatchA' with the required batch name
$sql = "SELECT * FROM question_bank WHERE batch = '$batch'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch-Specific Questions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Batch-Specific Questions</h4>
                
                <form method="post" action="submit_answers.php">
                    <?php
                    if ($result->num_rows > 0) {
                        // Loop through all questions and display them with radio buttons
                        while($row = $result->fetch_assoc()) {
                            echo "<p><strong>" . $row['question'] . "</strong></p>";

                            // Display radio buttons for each option
                            for ($i = 1; $i <= 4; $i++) {
                                $option = $row['option' . $i];
                                $question_id = $row['id'];
                                echo "
                                    <div class='form-check'>
                                        <input class='form-check-input' type='radio' name='question_$question_id' value='$option' id='question_{$question_id}_$i'>
                                        <label class='form-check-label' for='question_{$question_id}_$i'>
                                            $option
                                        </label>
                                    </div>
                                ";
                            }
                            echo "<hr>";
                        }
                    } else {
                        echo "No questions found for this batch.";
                    }
                    $conn->close();
                    ?>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
