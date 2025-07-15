<?php
session_start();
require 'connection.php'; // Include the database connection

// Initialize variables
$login_error = '';
$roleid = null; // Initialize roleid to null
$driverid = null; // Initialize driverid to null

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        $login_error = "Username and password are required.";
    } else {
        // Prepare and execute the SQL statement to check credentials
        $sql = "SELECT * FROM users WHERE username = :username AND password = :password"; // Ensure passwords are hashed in production
        $stmt = oci_parse($conn, $sql);

        // Bind the parameters
        oci_bind_by_name($stmt, ":username", $username);
        oci_bind_by_name($stmt, ":password", $password); // In production, use hashed password verification

        // Execute the statement
        oci_execute($stmt);

        // Check if a row was returned
        if ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
            // Debug output
            // echo "<pre>"; // Uncomment for debugging
            // print_r($row); // Uncomment for debugging
            // echo "</pre>"; // Uncomment for debugging

            // Check if roleid exists
            if (isset($row['ROLEID'])) {
                $roleid = $row['ROLEID'];
            } else {
                $login_error = "Role is not assigned yet.";
            }

            // Check if DriverID exists
            if (isset($row['DriverID'])) {
                $driverid = $row['DriverID'];
            }

            // If no errors occurred, proceed with the rest of the login process
            if (empty($login_error)) {
                $_SESSION['username'] = $username;
                $_SESSION['roleid'] = $roleid;

                // Redirect based on role ID
                switch ($roleid) {
                    case 1:
                        header("Location: dashboard.php");
                        break;
                    case 2:
                        header("Location: nco_dashboard.php"); // Adjust the page for roleid 2
                        break;
                    case 3:
                        header("Location: driver_dashboard.php"); // Adjust the page for roleid 3
                        break;
                    default:
                        $login_error = "Invalid role assigned.";
                }
                exit();
            }
        } else {
            // Password is incorrect or user does not exist
            $login_error = "Invalid username or password."; // Provide a generic message for incorrect credentials
        }

        // Free the statement
        oci_free_statement($stmt);
    }
}

// Handle displaying the login error if it exists
if (!empty($login_error)) {
    echo "<p style='color: red;'>" . htmlentities($login_error, ENT_QUOTES) . "</p>";
}

// Close the connection
oci_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="img/logo/MVMS_logo.png" rel="icon">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> <!-- Custom CSS for styling -->
</head>

<body style="background-color: #2d3c2c;">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4">Login</h3>

                        <!-- Display error if login failed -->
                        <?php if (!empty($login_error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $login_error; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form method="POST" action="">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="username" id="floatingUsername" placeholder="Username" required>
                                <label for="floatingUsername">Username</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password" required>
                                <label for="floatingPassword">Password</label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="showPassword" onclick="togglePassword()">
                                    <label class="form-check-label" for="showPassword">Show Password</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                        </form>

                        <hr>

                        <!-- Register link -->
                        <div class="text-center">
                            <a href="register.php" class="text-decoration-none">Create an Account!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById('floatingPassword');
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>

</html>
