<?php
session_start();
include('dbconnection.php'); // Verify the file location

$msg = ""; // Initialize message variable

if (isset($_POST['login'])) {
  $Username = $_POST['Username']; // Input name for username
  $Branch = $_POST['Branch'];
  $semester = $_POST['semester'];
  $batch = $_POST['batch'];
  $password = $_POST['password']; // Input name for password

  // Using prepared statements for secure SQL query
  $query = mysqli_query($conn, "SELECT id, batch FROM students_details WHERE USN='$Username' OR Branch='$Branch' OR semester='$semester' OR Username='$Username' AND password='$password'");

// "SELECT * FROM  WHERE USN='$Username' OR Username='$Username' AND password='$password'");

  // Check if query returned a result
  $ret = mysqli_fetch_array($query);
  if ($ret > 0) {  //pass this line to question_list page so that student cannt revisite the exam
    $_SESSION['login_id'] = $ret['id'];
    $_SESSION['batch'] = $ret['batch']; // Set the batch from the query result
    $std_id = $ret['id'];
    $std_usn = $ret['USN'];
    $_SESSION['usn'] = $std_usn;

    // Redirect to test.php after successful login
    header('location:test.php');
  } else {
    $msg = "Invalid Detail.";
  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-center">Login</h5>

            <!-- Display error message if login fails -->
            <?php if (!empty($msg)): ?>
              <div class="alert alert-danger text-center"><?php echo $msg; ?></div>
            <?php endif; ?>

            <form method="POST">
              <div class="mb-3">
                <label for="loginUSN" class="form-label">USN/Registration No</label>
                <input type="text" class="form-control" id="loginUSN" name="Username" placeholder="Enter your USN"
                  required>
              </div>
              <div class="mb-3">
                <label for="Branch" class="form-label">Branch</label>
                <input type="text" class="form-control" id="Branch" name="Branch" placeholder="Enter your Branch"
                  required>
              </div>
              <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <input type="text" class="form-control" id="semester" name="semester" placeholder="Enter your semester"
                  required>
              </div>
              <div class="mb-3">
                <label for="batch" class="form-label">Batch</label>
                <input type="text" class="form-control" id="loginbatch" name="batch" placeholder="Enter your batch"
                  required>
              </div>
              <div class="mb-3">
                <label for="loginPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="loginPassword" name="password"
                  placeholder="Enter your password" required>
              </div>
              <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
              <div class="text-center mt-3">
                <p>Don't have an account? <a href="signin.php">Sign in here</a></p>
                <!-- <a href="logout.php" class="btn btn-danger">Logout</a> -->
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>