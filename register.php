<?php
// Start the session
session_start();

// Include the database connection
require 'connection.php';

// Initialize variables for form values and error messages
$username = '';
$password = '';
$mobile = '';

$driverid = 'NULL';
$insert_error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form input
    $username = $_POST['username'];
    $password = $_POST['password'];
    $mobile = $_POST['mobile'];
    
    $driverid = $_POST['driverid'];

    // Prepare SQL statement to insert new user
    $sql = "INSERT INTO USERS (Username, Password, Mobile, DriverID)
            VALUES (:username, :password, :mobile, :driverid)";
    $stmt = oci_parse($conn, $sql);

    // Bind the parameters
    oci_bind_by_name($stmt, ':username', $username);
    oci_bind_by_name($stmt, ':password', $password);
    oci_bind_by_name($stmt, ':mobile', $mobile);
 
    oci_bind_by_name($stmt, ':driverid', $driverid);

    // Execute the statement
    if (oci_execute($stmt)) {
        echo '<p style="color: white;">User added successfully!</p>';
    } else {
        $m = oci_error($stmt);
        $insert_error = "Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES);
    }

    // Free the statement
    oci_free_statement($stmt);
}

// Close the connection
oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #2d3c2c;">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4">Add New User</h3>

                    <!-- Display error if any -->
                    <?php if (!empty($insert_error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $insert_error; ?>
                        </div>
                    <?php endif; ?>

                    <!-- User Form -->
                    <form method="POST" action="">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            <label for="username">Username</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile" required>
                            <label for="mobile">Mobile</label>
                        </div>

                        
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="driverid" name="driverid" placeholder="Driver ID" >
                            <label for="driverid">Driver ID</label>
                        </div>
                    
                        <button type="submit" class="btn btn-primary w-100 py-2">Add User</button>
                        <hr>
                        <div class="text-center">
                            <a href="login.php" class="text-decoration-none">Go Back To Login</a>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
