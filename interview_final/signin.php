<?php
  session_start();
  // error_reporting(0);
  include('dbconnection.php'); // Ensure this file connects to your database correctly

  $msg = ""; // To store error/success messages

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $USN = $_POST['USN'];
    $Email_id = $_POST['Email_id'];
    $Mobile_no = $_POST['Mobile_no'];
    $Branch = $_POST['Branch'];
    $semester = $_POST['semester'];
    $College = $_POST['College'];
    $batch = $_POST['batch'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
      $msg = "Passwords do not match.";
    } else {
      // Hash the password
      // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Check if the user already exists
      $stmt = $conn->prepare("SELECT id FROM students_details WHERE USN = ?");
      $stmt->bind_param("s", $USN);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $msg = "USN/Registration No is already registered.";
      } else {
        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO students_details (username, USN, Email_id, Mobile_no, Branch, semester, College, batch, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)");
        $stmt->bind_param("sssssssss", $username, $USN, $Email_id, $Mobile_no, $Branch, $semester, $College, $batch, $password);

        if ($stmt->execute()) {
          $msg = "Registration successful. You can now <a href='index.php'>log in</a>.";
        } else {
          $msg = "An error occurred. Please try again.";
        }
      }

      $stmt->close(); // Close the statement
    }
  }

  $conn->close(); // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-center">Sign Up</h5>

            <!-- Display error or success message -->
            <?php if (!empty($msg)): ?>
              <div class="alert alert-<?php echo strpos($msg, 'successful') !== false ? 'success' : 'danger'; ?> text-center">
                <?php echo $msg; ?>
              </div>
            <?php endif; ?>

            <form method="POST" action="">
              <div class="mb-3">
                <label for="username" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your full name" required>
              </div>

              <div class="mb-3">
                <label for="USN" class="form-label">USN</label>
                <input type="text" class="form-control" id="USN" name="USN" placeholder="Enter your USN" required>
              </div>

              <div class="mb-3">
                <label for="Email_id" class="form-label">Email</label>
                <input type="email" class="form-control" id="Email_id" name="Email_id" placeholder="Enter your Email" required>
              </div>

              <div class="mb-3">
                <label for="Mobile_no" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" id="Mobile_no" name="Mobile_no" placeholder="Enter your Mobile Number" required>
              </div>

              <div class="mb-3">
                <label for="Branch" class="form-label">Branch</label>
                <input type="text" class="form-control" id="Branch" name="Branch" placeholder="Enter your Branch" required>
              </div>

              <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <input type="text" class="form-control" id="semester" name="semester" placeholder="Enter your Semester" required>
              </div>

              <div class="mb-3">
                <label for="College" class="form-label">College</label>
                <input type="text" class="form-control" id="College" name="College" placeholder="Enter your College" required>
              </div>

              <div class="mb-3">
                <label for="batch" class="form-label">Batch</label>
                <input type="text" class="form-control" id="batch" name="batch" placeholder="Enter your Batch" required>
              </div>

              <div class="mb-3">
                <label for="registerPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="registerPassword" name="password" placeholder="Create a password" required>
              </div>

              <div class="mb-3">
                <label for="registerConfirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="registerConfirmPassword" name="confirm_password" placeholder="Confirm your password" required>
              </div>

              <button type="submit" class="btn btn-success w-100">Submit</button>
              <div class="text-center mt-3">
                <p>Already have an account? <a href="index.php">Login here</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
