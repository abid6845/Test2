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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['MaintenanceID'])) {
    $MaintenanceID = $_POST['MaintenanceID'];
    $MaintenanceDate = $_POST['MaintenanceDate'];
    $Details = $_POST['Details'];
    $Cost = $_POST['Cost'];
    $VehicleID = $_POST['VehicleID'];
    $DriverID = $_POST['DriverID'];
   

    $sql = "INSERT INTO Maintenance (MaintenanceID, MaintenanceDate, Details, Cost, VehicleID, DriverID) 
        VALUES (:MaintenanceID, TO_DATE(:MaintenanceDate, 'DD-MON-YY'), :Details, :Cost, :VehicleID, :DriverID)";

    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':MaintenanceID', $MaintenanceID);
    oci_bind_by_name($stid, ':MaintenanceDate', $MaintenanceDate);
    oci_bind_by_name($stid, ':Details', $Details);
    oci_bind_by_name($stid, ':Cost', $Cost);
    oci_bind_by_name($stid, ':VehicleID', $VehicleID);
    oci_bind_by_name($stid, ':DriverID', $DriverID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Maintenance Data added successfully!</p>";
    }

    oci_free_statement($stid);
}

// Handle form submission for updating Maintenance
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateMaintenanceID'])) {
    $MaintenanceID = $_POST['updateMaintenanceID'];
    $MaintenanceDate = $_POST['updateMaintenanceDate'];
    $Details = $_POST['updateDetails'];
    $Cost = $_POST['updateCost'];
    $VehicleID = $_POST['updateVehicleID'];
    $DriverID = $_POST['updateDriverID'];
    
    $sql = "UPDATE Maintenance 
            SET MaintenanceDate = TO_DATE(:MaintenanceDate, 'DD-MON-YY'), 
                Details = :Details, 
                Cost = :Cost, 
                VehicleID = :VehicleID, 
                DriverID = :DriverID 
            WHERE MaintenanceID = :MaintenanceID";
    
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ":MaintenanceID", $MaintenanceID);
    oci_bind_by_name($stid, ":MaintenanceDate", $MaintenanceDate);
    oci_bind_by_name($stid, ":Details", $Details);
    oci_bind_by_name($stid, ":Cost", $Cost);
    oci_bind_by_name($stid, ":VehicleID", $VehicleID);
    oci_bind_by_name($stid, ":DriverID", $DriverID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to update data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Maintenance updated successfully!</p>";
    }

    oci_free_statement($stid);
}


// Handle form submission for deleting a role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteMaintenanceID'])) {
    $MaintenanceID = $_POST['deleteMaintenanceID'];

    $sql = "DELETE FROM Maintenance WHERE MaintenanceID = :MaintenanceID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':MaintenanceID', $MaintenanceID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to delete data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Maintenance entry deleted successfully!</p>";
    }

    oci_free_statement($stid);
}

// Retrieve roles for display
$sql = "SELECT * FROM Maintenance";
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
    <title>Maintenance</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Maintenance</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Vehicles</li>
                            <li class="breadcrumb-item active" aria-current="page">Maintenance</li>
                        </ol>
                    </div>

                    <!-- Row -->
                    <div class="row">
                        <!-- DataTable with Hover -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold" style="color: #4B5320;">Maintenance</h6>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addMaintenanceModal">
                                        Add New Maintenance Entry
                                    </button>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Serial</th>
                                                <th>MaintenanceID</th>
                                                <th>MaintenanceDate</th>
                                                <th>Details</th>
                                                <th>Cost</th>
                                                <th>VehicleID</th>
                                                <th>DriverID</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Serial</th>
                                                <th>MaintenanceID</th>
                                                <th>MaintenanceDate</th>
                                                <th>Details</th>
                                                <th>Cost</th>
                                                <th>VehicleID</th>
                                                <th>DriverID</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $sl = 1;
                                            while ($row = oci_fetch_assoc($stid)) {
                                                echo '<tr>';
                                                echo '<td>' . $sl . '</td>';
                                                echo '<td>' . htmlentities($row['MAINTENANCEID'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['MAINTENANCEDATE'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['DETAILS'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['COST'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['VEHICLEID'], ENT_QUOTES) . '</td>';
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

                    <!-- Add Maintenance Modal -->
                    <div class="modal fade" id="addMaintenanceModal" tabindex="-1" role="dialog" aria-labelledby="addMaintenanceModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addMaintenanceModalLabel">Add New Maintenance Entry</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="MaintenanceID">Maintenance ID</label>
                                            <input type="text" class="form-control" id="MaintenanceID" name="MaintenanceID" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="MaintenanceDate">Maintenance Date</label>
                                            <input type="text" class="form-control" id="MaintenanceDate" name="MaintenanceDate" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Details">Details</label>
                                            <input type="text" class="form-control" id="Details" name="Details" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Cost">Cost</label>
                                            <input type="text" class="form-control" id="Cost" name="Cost">
                                        </div>
                                        <div class="form-group">
                                            <label for="VehicleID">Vehicle ID</label>
                                            <input type="text" class="form-control" id="VehicleID" name="VehicleID">
                                        </div>
                                        <div class="form-group">
                                            <label for="DriverID">DriverID</label>
                                            <input type="text" class="form-control" id="DriverID" name="DriverID" >
                                        </div>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add Maintenance Entry</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Update Maintenance Modal -->
                    <div class="modal fade" id="updateMaintenanceModal" tabindex="-1" role="dialog" aria-labelledby="updateMaintenanceModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateMaintenanceModalLabel">Update Maintenance</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="updateMaintenanceForm" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="updateMaintenanceID">Maintenance ID</label>
                                            <input type="text" class="form-control" id="updateMaintenanceID" name="updateMaintenanceID" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateMaintenanceDate">Maintenance Date</label>
                                            <input type="text" class="form-control" id="updateMaintenanceDate" name="updateMaintenanceDate" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateDetails">Details</label>
                                            <input type="text" class="form-control" id="updateDetails" name="updateDetails" >
                                        </div>
                                        <div class="form-group">
                                            <label for="updateCost">Cost</label>
                                            <input type="text" class="form-control" id="updateCost" name="updateCost" >
                                        </div>
                                        <div class="form-group">
                                            <label for="updateVehicleID">Vehicle ID</label>
                                            <input type="text" class="form-control" id="updateVehicleID" name="updateVehicleID" >
                                        </div>
                                        <div class="form-group">
                                            <label for="updateDriverID">Driver ID</label>
                                            <input type="text" class="form-control" id="updateDriverID" name="updateDriverID" >
                                        </div>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update Maintenance</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Role Modal -->
                    <div class="modal fade" id="deleteMaintenanceModal" tabindex="-1" role="dialog" aria-labelledby="deleteMaintenanceModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteMaintenanceModalLabel">Delete Maintenance Entry</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="deleteMaintenanceForm" method="post">
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this enrty?</p>
                                        <input type="hidden" name="deleteMaintenanceID" id="deleteMaintenanceID">
                                        <div class="form-group">
                                            <label for="deleteMaintenanceDate">Date</label>
                                            <input type="text" class="form-control" id="deleteMaintenanceDate" name="deleteMaintenanceDate" readonly>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete Maintenance Entry</button>
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

        function populateUpdateModal(record) {
            document.getElementById('updateMaintenanceID').value = record.MAINTENANCEID;
            document.getElementById('updateMaintenanceDate').value = record.MAINTENANCEDATE;
            document.getElementById('updateDetails').value = record.DETAILS;
            document.getElementById('updateCost').value = record.COST;
            document.getElementById('updateVehicleID').value = record.VEHICLEID;
            document.getElementById('updateDriverID').value = record.DRIVERID;
            $('#updateMaintenanceModal').modal('show');
        }

        function populateDeleteModal(record) {
            document.getElementById('deleteMaintenanceID').value = record.MAINTENANCEID;
            document.getElementById('deleteMaintenanceDate').value = record.MAINTENANCEDATE;
            $('#deleteMaintenanceModal').modal('show');
        }

    </script>
</body>
</html>
