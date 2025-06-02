<?php
session_start();
include('../dbconnection.php'); // Verify the file location

$msg = ""; // Initialize message variable

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Using prepared statements for secure SQL query
    $stmt = $conn->prepare("SELECT * FROM admin_table WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $query = $stmt->get_result();

    // Check if query returned a result
    if ($query->num_rows > 0) {
        $ret = $query->fetch_assoc();
        $_SESSION['login_id'] = $ret['id'];
        $_SESSION['username'] = $ret['username'];
        
        // Set a flag to prevent revisiting
        $_SESSION['exam_started'] = true; 

        // Redirect to student.php after successful login
        header('location:student.php');
        exit();
    } else {
        $msg = "Invalid Detail.";
    }

    $stmt->close();
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
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Enter your password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
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
