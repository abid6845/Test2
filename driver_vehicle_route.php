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



// Handle form submission for adding a driver-vehicle-route record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['DriverID']) && isset($_POST['VehicleID']) && isset($_POST['RouteID'])) {
    $DriverID = $_POST['DriverID'];
    $VehicleID = $_POST['VehicleID']; // New field for VehicleID
    $RouteID = $_POST['RouteID'];

    // Prepare the SQL statement
    $sql = "INSERT INTO DRIVER_VEHICLE_ROUTE (DriverID, VehicleID, RouteID) 
            VALUES (:DriverID, :VehicleID, :RouteID)"; // No need to include DriverVehicleRouteID
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':DriverID', $DriverID);
    oci_bind_by_name($stid, ':VehicleID', $VehicleID); // Bind VehicleID
    oci_bind_by_name($stid, ':RouteID', $RouteID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Driver-Vehicle-Route added successfully!</p>";
    }

    oci_free_statement($stid);
}

// Handle form submission for updating a driver-vehicle-route record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateDriverVehicleRouteID'])) {
    $DriverVehicleRouteID = $_POST['updateDriverVehicleRouteID'];
    $DriverID = isset($_POST['updateDriverID']) ? $_POST['updateDriverID'] : '';
    $VehicleID = isset($_POST['updateVehicleID']) ? $_POST['updateVehicleID'] : ''; // New field for VehicleID
    $RouteID = isset($_POST['updateRouteID']) ? $_POST['updateRouteID'] : '';

    // Prepare the SQL statement
    $sql = "UPDATE DRIVER_VEHICLE_ROUTE SET DriverID = :DriverID, VehicleID = :VehicleID, RouteID = :RouteID 
            WHERE DriverVehicleRouteID = :DriverVehicleRouteID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':DriverVehicleRouteID', $DriverVehicleRouteID);
    oci_bind_by_name($stid, ':DriverID', $DriverID);
    oci_bind_by_name($stid, ':VehicleID', $VehicleID); // Bind VehicleID
    oci_bind_by_name($stid, ':RouteID', $RouteID);
    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to update data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Driver-Vehicle-Route updated successfully!</p>";
    }

    oci_free_statement($stid);
}


// Handle form submission for deleting a driver-vehicle-route record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteDriverRouteID'])) {
    $DriverVehicleRouteID = $_POST['deleteDriverRouteID'];

    $sql = "DELETE FROM DRIVER_VEHICLE_ROUTE WHERE DriverVehicleRouteID = :DriverVehicleRouteID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':DriverVehicleRouteID', $DriverVehicleRouteID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to delete data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Driver-Vehicle-Route deleted successfully!</p>";
    }

    oci_free_statement($stid);
}


// Retrieve driver-route records for display
$sql = "SELECT * FROM Driver_Vehicle_Route";
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
    <title>Assign Drivers</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Driver-Route Management</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Drivers</li>
                            <li class="breadcrumb-item active" aria-current="page">Assign Driver</li>
                        </ol>
                    </div>

                    <!-- Row -->
                    <div class="row">
                        <!-- DataTable with Hover -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold" style="color: #4B5320;">Driver-Route</h6>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addDriverRouteModal">
                                        Add New Driver-Route
                                    </button>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Serial</th>
                                                <th>Driver Route ID</th>
                                                <th>Driver ID</th>
                                                <th>Vehicle ID</th>
                                                <th>Route ID</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Serial</th>
                                                <th>Driver Route ID</th>
                                                <th>Driver ID</th>
                                                <th>Vehicle ID</th>
                                                <th>Route ID</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $sl = 1;
                                            while ($row = oci_fetch_assoc($stid)) {
                                                echo '<tr>';
                                                echo '<td>' . $sl . '</td>';
                                                echo '<td>' . htmlentities($row['DRIVERVEHICLEROUTEID'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['DRIVERID'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['VEHICLEID'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['ROUTEID'], ENT_QUOTES) . '</td>';
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

                    <!-- Add Driver-Route Modal -->
                    <div class="modal fade" id="addDriverRouteModal" tabindex="-1" role="dialog" aria-labelledby="addDriverRouteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addDriverRouteModalLabel">Add New Driver-Route</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="DriverID">Driver ID</label>
                                            <input type="text" class="form-control" id="DriverID" name="DriverID" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="VehicleID">Vehicle ID</label>
                                            <input type="text" class="form-control" id="VehicleID" name="VehicleID" required> <!-- New field for VehicleID -->
                                        </div>
                                        <div class="form-group">
                                            <label for="RouteID">Route ID</label>
                                            <input type="text" class="form-control" id="RouteID" name="RouteID" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Update Driver-Route Modal -->
                    <div class="modal fade" id="updateDriverRouteModal" tabindex="-1" role="dialog" aria-labelledby="updateDriverRouteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateDriverRouteModalLabel">Update Driver-Route</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="updateDriverVehicleRouteID" name="updateDriverVehicleRouteID"> <!-- Fixed input name -->
                                        <div class="form-group">
                                            <label for="updateDriverID">Driver ID</label>
                                            <input type="text" class="form-control" id="updateDriverID" name="updateDriverID" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateVehicleID">Vehicle ID</label>
                                            <input type="text" class="form-control" id="updateVehicleID" name="updateVehicleID" required> <!-- New field for VehicleID -->
                                        </div>
                                        <div class="form-group">
                                            <label for="updateRouteID">Route ID</label>
                                            <input type="text" class="form-control" id="updateRouteID" name="updateRouteID" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                                        <!-- Delete Driver-Route Modal -->
                                        <div class="modal fade" id="deleteDriverRouteModal" tabindex="-1" role="dialog" aria-labelledby="deleteDriverRouteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteDriverRouteModalLabel">Delete Driver-Route</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this driver-route entry?</p>
                                        <input type="hidden" id="deleteDriverRouteID" name="deleteDriverRouteID">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- Container Fluid-->
            </div>
            <!-- Footer -->
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
            <!-- Footer -->
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
     

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script>

$(document).ready(function () {
            $('#dataTableHover').DataTable(); // Initialize DataTables
        });
function populateUpdateModal(data) {
    document.getElementById('updateDriverVehicleRouteID').value = data.DRIVERVEHICLEROUTEID;
    document.getElementById('updateDriverID').value = data.DRIVERID;
    document.getElementById('updateVehicleID').value = data.VEHICLEID;
    document.getElementById('updateRouteID').value = data.ROUTEID;
    $('#updateDriverRouteModal').modal('show');
}

function populateDeleteModal(data) {
    document.getElementById('deleteDriverRouteID').value = data.DRIVERVEHICLEROUTEID;
    $('#deleteDriverRouteModal').modal('show');
}
</script>

</body>

</html>
