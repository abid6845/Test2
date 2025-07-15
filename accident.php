<?php
require('connection.php');
include 'session_check.php'; // Include session check script





    // Check if user is logged in
    if (empty($_SESSION['username'])) {
        header('location:login.php');
        exit;
    }

    


// Handle form submission for adding a Accident
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['AccidentID'])) {
    $AccidentID = $_POST['AccidentID'];
    $AccidentDate = $_POST['AccidentDate'];
    $Description = $_POST['Description'];
    $Damage_Cost = $_POST['Damage_Cost'];
    $VehicleID = $_POST['VehicleID'];
    $DriverID = $_POST['DriverID'];

    $sql = "INSERT INTO Accident (AccidentID, AccidentDate, Description, Damage_Cost, VehicleID, DriverID) 
            VALUES (:AccidentID, TO_DATE(:AccidentDate, 'DD-MON-YY'), :Description, :Damage_Cost, :VehicleID, :DriverID)";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':AccidentID', $AccidentID);
    oci_bind_by_name($stid, ':AccidentDate', $AccidentDate);
    oci_bind_by_name($stid, ':Description', $Description);
    oci_bind_by_name($stid, ':Damage_Cost', $Damage_Cost);
    oci_bind_by_name($stid, ':VehicleID', $VehicleID);
    oci_bind_by_name($stid, ':DriverID', $DriverID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Accident added successfully!</p>";
    }

    oci_free_statement($stid);
}


// Handle form submission for updating a Accident
// Handle form submission for updating an accident
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateAccidentID'])) {
    $AccidentID = $_POST['updateAccidentID'];
    $AccidentDate = $_POST['updateAccidentDate'];
    $Description = $_POST['updateAccidentDescription'];
    $Damage_Cost = $_POST['updateAccidentDamage_Cost'];
    $VehicleID = $_POST['updateAccidentVehicleID'];
    $DriverID = $_POST['updateAccidentDriverID'];

    $sql = "UPDATE Accident SET 
                AccidentDate = TO_DATE(:AccidentDate, 'DD-MON-YY'),
                Description = :Description,
                Damage_Cost = :Damage_Cost,
                VehicleID = :VehicleID,
                DriverID = :DriverID
            WHERE AccidentID = :AccidentID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':AccidentID', $AccidentID);
    oci_bind_by_name($stid, ':AccidentDate', $AccidentDate);
    oci_bind_by_name($stid, ':Description', $Description);
    oci_bind_by_name($stid, ':Damage_Cost', $Damage_Cost);
    oci_bind_by_name($stid, ':VehicleID', $VehicleID);
    oci_bind_by_name($stid, ':DriverID', $DriverID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to update data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Accident updated successfully!</p>";
    }

    oci_free_statement($stid);
}


// Handle form submission for deleting an accident
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteAccidentID'])) {
    $AccidentID = $_POST['deleteAccidentID'];

    $sql = "DELETE FROM Accident WHERE AccidentID = :AccidentID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':AccidentID', $AccidentID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to delete data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Accident deleted successfully!</p>";
    }

    oci_free_statement($stid);
}


// Retrieve roles for display
$sql = "SELECT * FROM Accident";
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
    <title>Accident</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Accident</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Miscellaneous</li>
                            <li class="breadcrumb-item active" aria-current="page">Accident</li>
                        </ol>
                    </div>

                    <!-- Row -->
                    <div class="row">
                        <!-- DataTable with Hover -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold" style="color: #4B5320;">Accident</h6>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAccidentModal">
                                        Add New Accdent
                                    </button>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Serial</th>
                                                <th>Accident ID</th>
                                                <th>Accident Date</th>
                                                <th>Description</th>
                                                <th>Damage Cost</th>
                                                <th>Vehicle ID</th>
                                                <th>Driver ID</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Serial</th>
                                                <th>Accident ID</th>
                                                <th>Accident Date</th>
                                                <th>Description</th>
                                                <th>Damage Cost</th>
                                                <th>Vehicle ID</th>
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
                                                echo '<td>' . htmlentities($row['ACCIDENTID'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['ACCIDENTDATE'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['DESCRIPTION'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['DAMAGE_COST'], ENT_QUOTES) . '</td>';
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

                    <!-- Add Accident Modal -->
                    <div class="modal fade" id="addAccidentModal" tabindex="-1" role="dialog" aria-labelledby="addAccidentModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addAccidentModalLabel">Add New Accident</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="AccidentID">Accident ID</label>
                                            <input type="text" class="form-control" id="AccidentID" name="AccidentID" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="AccidentDate">Accident Date</label>
                                            <input type="text" class="form-control" id="AccidentDate" name="AccidentDate" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Description">Description</label>
                                            <textarea class="form-control" id="Description" name="Description" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="DamageCost">Damage Cost</label>
                                            <input type="text" class="form-control" id="Damage_Cost" name="Damage_Cost" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="VehicleID">Vehicle ID</label>
                                            <input type="text" class="form-control" id="VehicleID" name="VehicleID" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="DriverID">Driver ID</label>
                                            <input type="text" class="form-control" id="DriverID" name="DriverID" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add Accident</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Update Accident Modal -->
                    <div class="modal fade" id="updateAccidentModal" tabindex="-1" role="dialog" aria-labelledby="updateAccidentModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateAccidentModalLabel">Update Accident</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="updateAccidentForm" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="updateAccidentID">Accident ID</label>
                                            <input type="text" class="form-control" id="updateAccidentID" name="updateAccidentID" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateAccidentDate">Accident Date</label>
                                            <input type="text" class="form-control datepicker" id="updateAccidentDate" name="updateAccidentDate" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateAccidentDescription">Description</label>
                                            <textarea class="form-control" id="updateAccidentDescription" name="updateAccidentDescription" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateAccidentDamageCost">Damage Cost</label>
                                            <input type="text" class="form-control" id="updateAccidentDamage_Cost" name="updateAccidentDamage_Cost" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateAccidentVehicleID">Vehicle ID</label>
                                            <input type="text" class="form-control" id="updateAccidentVehicleID" name="updateAccidentVehicleID" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateAccidentDriverID">Driver ID</label>
                                            <input type="text" class="form-control" id="updateAccidentDriverID" name="updateAccidentDriverID" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update Accident</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Delete Accident Modal -->
                    <div class="modal fade" id="deleteAccidentModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccidentModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteAccidentModalLabel">Delete Accident</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="deleteAccidentForm" method="post">
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this accident?</p>
                                        <input type="hidden" name="deleteAccidentID" id="deleteAccidentID">
                                        <div class="form-group">
                                            <label for="deleteAccidentDate">Accident Date</label>
                                            <input type="text" class="form-control" id="deleteAccidentDate" name="deleteAccidentDate" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="deleteAccidentDescription">Description</label>
                                            <textarea class="form-control" id="deleteAccidentDescription" name="deleteAccidentDescription" readonly></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="deleteAccidentDamageCost">Damage Cost</label>
                                            <input type="text" class="form-control" id="deleteAccidentDamage_Cost" name="deleteAccidentDamage_Cost" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="deleteAccidentVehicleID">Vehicle ID</label>
                                            <input type="text" class="form-control" id="deleteAccidentVehicleID" name="deleteAccidentVehicleID" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="deleteAccidentDriverID">Driver ID</label>
                                            <input type="text" class="form-control" id="deleteAccidentDriverID" name="deleteAccidentDriverID" readonly>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete Accident</button>
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

        function populateUpdateModal(accident) {
            $('#updateAccidentID').val(accident.ACCIDENTID);
            $('#updateAccidentDate').val(accident.ACCIDENTDATE);
            $('#updateAccidentDescription').val(accident.DESCRIPTION);
            $('#updateAccidentDamage_Cost').val(accident.DAMAGE_COST);
            $('#updateAccidentVehicleID').val(accident.VEHICLEID);
            $('#updateAccidentDriverID').val(accident.DRIVERID);
            $('#updateAccidentModal').modal('show');
        }


        function populateDeleteModal(accident) {
            $('#deleteAccidentID').val(accident.ACCIDENTID);
            $('#deleteAccidentDate').val(accident.ACCIDENTDATE);
            $('#deleteAccidentDescription').val(accident.DESCRIPTION);
            $('#deleteAccidentDamage_Cost').val(accident.DAMAGE_COST);
            $('#deleteAccidentVehicleID').val(accident.VEHICLEID);
            $('#deleteAccidentDriverID').val(accident.DRIVERID);
            $('#deleteAccidentModal').modal('show');
        }

    </script>
</body>
</html>
