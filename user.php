<?php
require('connection.php');
include 'session_check.php'; // Include session check script

require('connection.php');


    // Check if user is logged in
    if (empty($_SESSION['username'])) {
        header('location:login.php');
        exit;
    }

    $username = $_SESSION['username'];

// Handle form submission for adding a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Username'])) {
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
    $Mobile = $_POST['Mobile'];
    $RoleID = $_POST['RoleID'];
    $DriverID = $_POST['DriverID'];

    $sql = "INSERT INTO Users (Username, Password, Mobile, RoleID, DriverID) 
            VALUES (:Username, :Password, :Mobile, :RoleID, :DriverID)";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':Username', $Username);
    oci_bind_by_name($stid, ':Password', $Password); // Using hashed password
    oci_bind_by_name($stid, ':Mobile', $Mobile);
    oci_bind_by_name($stid, ':RoleID', $RoleID);
    oci_bind_by_name($stid, ':DriverID', $DriverID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>User added successfully!</p>";
        echo '<script>$("#addUserModal").modal("hide");</script>'; // Close modal
    }

    oci_free_statement($stid);
}


// Handle form submission for updating a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUserID'])) {
    $UserID = $_POST['updateUserID'];
    $Username = $_POST['updateUsername'];
    $Password = $_POST['updatePassword'];
    $Mobile = $_POST['updateMobile'];
    $RoleID = $_POST['updateRoleID'];
    $DriverID = $_POST['updateDriverID'];

    $sql = "UPDATE Users SET 
                Username = :Username,
                Password = :Password,
                Mobile = :Mobile,
                RoleID = :RoleID,
                DriverID = :DriverID
            WHERE UserID = :UserID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':UserID', $UserID);
    oci_bind_by_name($stid, ':Username', $Username);
    oci_bind_by_name($stid, ':Password', $Password);
    oci_bind_by_name($stid, ':Mobile', $Mobile);
    oci_bind_by_name($stid, ':RoleID', $RoleID);
    oci_bind_by_name($stid, ':DriverID', $DriverID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to update data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>User updated successfully!</p>";
    }

    oci_free_statement($stid);
}

// Handle form submission for deleting a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUserID'])) {
    $UserID = $_POST['deleteUserID'];

    $sql = "DELETE FROM Users WHERE UserID = :UserID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':UserID', $UserID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to delete data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>User deleted successfully!</p>";
    }

    oci_free_statement($stid);
}

// Retrieve users for display
$sql = "SELECT * FROM Users";
$stid = oci_parse($conn, $sql);
oci_execute($stid);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/logo/MVMS_logo.png" rel="icon">
    <title>Users</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include 'topbar.php'; ?>
                <!-- Topbar -->
                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Users</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item">Miscellaneous</li>
                            <li class="breadcrumb-item active" aria-current="page">Users</li>
                        </ol>
                    </div>

                    <!-- Row -->
                    <div class="row">
                        <!-- DataTable with Hover -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold" style="color: #4B5320;">Users</h6>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUserModal">
                                        Add New User
                                    </button>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Serial</th>
                                                <th>User ID</th>
                                                <th>Username</th>
                                                <th>Password</th>
                                                <th>Mobile</th>
                                                <th>Role ID</th>
                                                <th>Driver ID</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Serial</th>
                                                <th>User ID</th>
                                                <th>Username</th>
                                                <th>Password</th>
                                                <th>Mobile</th>
                                                <th>Role ID</th>
                                                <th>Driver ID</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $sl = 1;
                                            while ($row = oci_fetch_assoc($stid)) {
                                                echo '<tr>';
                                                echo '<td>' . $sl . '</td>';
                                                echo '<td>' . htmlentities($row['USERID'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['USERNAME'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['PASSWORD'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['MOBILE'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['ROLEID'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['DRIVERID'], ENT_QUOTES) . '</td>';
                                                echo '<td>
                                                        <a href="#" class="btn btn-success d-inline-block" onclick=\'populateUpdateModal(' . json_encode($row) . ')\'>
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-danger d-inline-block ms-2" onclick=\'populateDeleteModal(' . json_encode($row) . ')\'>
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                      </td>';
                                                echo '</tr>';
                                                $sl++;
                                            }

                                            oci_free_statement($stid);
                                            oci_close($conn);
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Row-->

                    <!-- Add User Modal -->
                    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="Username">Username</label>
                                            <input type="text" class="form-control" id="Username" name="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Password">Password</label>
                                            <input type="password" class="form-control" id="Password" name="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Mobile">Mobile</label>
                                            <input type="text" class="form-control" id="Mobile" name="Mobile" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="RoleID">Role ID</label>
                                            <select class="form-control" id="RoleID" name="RoleID" required>
                                                <option value="">Select a role</option>
                                                <option value="1">Administrator</option>
                                                <option value="2">MT NCO/Clk</option>
                                                <option value="3">Driver</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="DriverID">Driver ID</label>
                                            <input type="text" class="form-control" id="DriverID" name="DriverID">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Update User Modal -->
                    <div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="updateUserID" name="updateUserID">
                                        <div class="form-group">
                                            <label for="updateUsername">Username</label>
                                            <input type="text" class="form-control" id="updateUsername" name="updateUsername" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updatePassword">Password</label>
                                            <input type="password" class="form-control" id="updatePassword" name="updatePassword" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateMobile">Mobile</label>
                                            <input type="text" class="form-control" id="updateMobile" name="updateMobile" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateRoleID">Role ID</label>
                                            <select class="form-control" id="updateRoleID" name="updateRoleID" required>
                                                <option value="">Select a role</option>
                                                <option value="1">Administrator</option>
                                                <option value="2">MT NCO/Clk</option>
                                                <option value="3">Driver</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateDriverID">Driver ID</label>
                                            <input type="text" class="form-control" id="updateDriverID" name="updateDriverID">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete User Modal -->
                    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this user?</p>
                                        <input type="hidden" id="deleteUserID" name="deleteUserID">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger">Delete User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Container Fluid -->
            </div>
            <div class="row">
            <div class="col-lg-12">
              
            </div>
          </div>
          <!-- Modal Logout -->
          <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelLogout" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelLogout">Ohh No!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                    <a href="logout.php" class="btn btn-primary">Logout</a> <!-- This triggers session destruction -->
                </div>
                </div>
            </div>
            </div>


        </div>
        <!---Container Fluid-->
      </div>

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>copyright &copy; <script> document.write(new Date().getFullYear()); </script> - developed by
              <b>Project Group 1</b>
            </span>
          </div>
        </div>
      </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTableHover').DataTable(); // Initialize DataTables
        });
        function populateUpdateModal(userData) {
            document.getElementById('updateUserID').value = userData.USERID;
            document.getElementById('updateUsername').value = userData.USERNAME;
            document.getElementById('updatePassword').value = userData.PASSWORD;
            document.getElementById('updateMobile').value = userData.MOBILE;
            document.getElementById('updateRoleID').value = userData.ROLEID;
            document.getElementById('updateDriverID').value = userData.DRIVERID;

            $('#updateUserModal').modal('show');
        }

        function populateDeleteModal(user) {
            document.getElementById('deleteUserID').value = user.USERID;
            $('#deleteUserModal').modal('show');
        }
    </script>
</body>
</html>
