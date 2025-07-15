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

// Handle form submission for adding a role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['RoleID'])) {
    $RoleID = $_POST['RoleID'];
    $RoleName = $_POST['RoleName'];

    $sql = "INSERT INTO role (RoleID, RoleName) 
            VALUES (:RoleID, :RoleName)";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':RoleID', $RoleID);
    oci_bind_by_name($stid, ':RoleName', $RoleName);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Role added successfully!</p>";
    }

    oci_free_statement($stid);
}

// Handle form submission for updating a role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateRoleID'])) {
    $RoleID = $_POST['updateRoleID'];
    $RoleName = isset($_POST['updateRoleName']) ? $_POST['updateRoleName'] : '';

    $sql = "UPDATE role SET RoleName = :RoleName WHERE RoleID = :RoleID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':RoleID', $RoleID);
    oci_bind_by_name($stid, ':RoleName', $RoleName);
    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to update data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Role updated successfully!</p>";
    }

    oci_free_statement($stid);
}

// Handle form submission for deleting a role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteRoleID'])) {
    $RoleID = $_POST['deleteRoleID'];

    $sql = "DELETE FROM role WHERE RoleID = :RoleID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':RoleID', $RoleID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to delete data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Role deleted successfully!</p>";
    }

    oci_free_statement($stid);
}

// Retrieve roles for display
$sql = "SELECT * FROM role";
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
    <title>Role</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Role</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Miscellaneous</li>
                            <li class="breadcrumb-item active" aria-current="page">Role</li>
                        </ol>
                    </div>

                    <!-- Row -->
                    <div class="row">
                        <!-- DataTable with Hover -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold" style="color: #4B5320;">Role</h6>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addRoleModal">
                                        Add New Role
                                    </button>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Serial</th>
                                                <th>Role ID</th>
                                                <th>Role Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Serial</th>
                                                <th>Role ID</th>
                                                <th>Role Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $sl = 1;
                                            while ($row = oci_fetch_assoc($stid)) {
                                                echo '<tr>';
                                                echo '<td>' . $sl . '</td>';
                                                echo '<td>' . htmlentities($row['ROLEID'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['ROLENAME'], ENT_QUOTES) . '</td>';
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

                    <!-- Add Role Modal -->
                    <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="RoleID">Role ID</label>
                                            <input type="text" class="form-control" id="RoleID" name="RoleID" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="RoleName">Role Name</label>
                                            <input type="text" class="form-control" id="RoleName" name="RoleName" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add Role</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Update Role Modal -->
                    <div class="modal fade" id="updateRoleModal" tabindex="-1" role="dialog" aria-labelledby="updateRoleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateRoleModalLabel">Update Role</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="updateRoleForm" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="updateRoleID">Role ID</label>
                                            <input type="text" class="form-control" id="updateRoleID" name="updateRoleID" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateRoleName">Role Name</label>
                                            <input type="text" class="form-control" id="updateRoleName" name="updateRoleName" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update Role</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Role Modal -->
                    <div class="modal fade" id="deleteRoleModal" tabindex="-1" role="dialog" aria-labelledby="deleteRoleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteRoleModalLabel">Delete Role</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="deleteRoleForm" method="post">
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this role?</p>
                                        <input type="hidden" name="deleteRoleID" id="deleteRoleID">
                                        <div class="form-group">
                                            <label for="deleteRoleName">Role Name</label>
                                            <input type="text" class="form-control" id="deleteRoleName" name="deleteRoleName" readonly>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete Role</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <!---Container Fluid-->
            </div>
            <!-- Documentation Link -->
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
      <!-- Footer -->
    </div>
  </div>
        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#dataTableHover').DataTable(); // Initialize DataTables
        });

        function populateUpdateModal(role) {
            $('#updateRoleID').val(role.ROLEID);
            $('#updateRoleName').val(role.ROLENAME);
            $('#updateRoleModal').modal('show');
        }

        function populateDeleteModal(role) {
            $('#deleteRoleID').val(role.ROLEID);
            $('#deleteRoleName').val(role.ROLENAME);
            $('#deleteRoleModal').modal('show');
        }
    </script>
</body>
</html>
