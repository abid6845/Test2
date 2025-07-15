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

$username = $_SESSION['username'];

// Handle form submission for adding a route
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['RouteName'])) {
    $RouteName = $_POST['RouteName']; // Route name
    $Origin = $_POST['Origin'];
    $Destination = $_POST['Destination'];
    $Distance = $_POST['Distance'];

    // Assuming RouteID is auto-generated using a sequence, e.g., ROUTE_SEQ
    $sql = "INSERT INTO ROUTE (RouteID, RouteName, Origin, Destination, Distance) 
            VALUES (ROUTE_SEQ.NEXTVAL, :RouteName, :Origin, :Destination, :Distance)";

    $stid = oci_parse($conn, $sql);

    // Bind variables
    oci_bind_by_name($stid, ':RouteName', $RouteName);
    oci_bind_by_name($stid, ':Origin', $Origin);
    oci_bind_by_name($stid, ':Destination', $Destination);
    oci_bind_by_name($stid, ':Distance', $Distance);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Route data added successfully!</p>";
    }

    oci_free_statement($stid);
}



// Handle form submission for updating Route
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateRouteID'])) {
    $RouteID = $_POST['updateRouteID'];
    $RouteName = $_POST['updateRouteName']; // New RouteName variable
    $Origin = $_POST['updateOrigin'];
    $Destination = $_POST['updateDestination'];
    $Distance = $_POST['updateDistance'];

    $sql = "UPDATE ROUTE 
            SET RouteName = :RouteName, 
                Origin = :Origin, 
                Destination = :Destination, 
                Distance = :Distance
            WHERE RouteID = :RouteID";
    
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ":RouteID", $RouteID);
    oci_bind_by_name($stid, ":RouteName", $RouteName); // Bind RouteName
    oci_bind_by_name($stid, ":Origin", $Origin);
    oci_bind_by_name($stid, ":Destination", $Destination);
    oci_bind_by_name($stid, ":Distance", $Distance);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to update data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Route updated successfully!</p>";
    }

    oci_free_statement($stid);
}




// Handle form submission for deleting a Route
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteRouteID'])) {
    $RouteID = $_POST['deleteRouteID'];

    $sql = "DELETE FROM Route WHERE RouteID = :RouteID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':RouteID', $RouteID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to delete data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Route entry deleted successfully!</p>";
    }

    oci_free_statement($stid);
}

// Retrieve Route for display
$sql = "SELECT * FROM Route";
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
    <title>Route</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Route</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Miscellaneous</li>
                            <li class="breadcrumb-item active" aria-current="page">Route</li>
                        </ol>
                    </div>

                    <!-- Row -->
                    <div class="row">
                        <!-- DataTable with Hover -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold" style="color: #4B5320;">Route</h6>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addRouteModal">
                                        Add New Route Entry
                                    </button>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Serial</th>
                                                <th>Route ID</th>
                                                <th>Origin</th>
                                                <th>Destination</th>
                                                <th>Distance</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Serial</th>
                                                <th>Route ID</th>
                                                <th>Origin</th>
                                                <th>Destination</th>                                          
                                                <th>Distance</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $sl = 1;
                                            while ($row = oci_fetch_assoc($stid)) {
                                                echo '<tr>';
                                                echo '<td>' . $sl . '</td>';
                                                echo '<td>' . htmlentities($row['ROUTEID'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['ORIGIN'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['DESTINATION'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['DISTANCE'], ENT_QUOTES) . '</td>';                                                
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

                    <!-- Add Route Modal -->
                    <div class="modal fade" id="addRouteModal" tabindex="-1" role="dialog" aria-labelledby="addRouteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addRouteModalLabel">Add New Route Entry</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="RouteName">Route Name</label>
                                            <input type="text" class="form-control" id="RouteName" name="RouteName" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Origin">Origin</label>
                                            <input type="text" class="form-control" id="Origin" name="Origin" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Destination">Destination</label>
                                            <input type="text" class="form-control" id="Destination" name="Destination" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Distance">Distance</label>
                                            <input type="number" class="form-control" id="Distance" name="Distance" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add Route Entry</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Update Route Modal -->
                    <div class="modal fade" id="updateRouteModal" tabindex="-1" role="dialog" aria-labelledby="updateRouteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateRouteModalLabel">Update Route</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="updateRouteForm" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="updateRouteID">Route ID</label>
                                            <input type="text" class="form-control" id="updateRouteID" name="updateRouteID" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateRouteName">Route Name</label>
                                            <input type="text" class="form-control" id="updateRouteName" name="updateRouteName" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateOrigin">Origin</label>
                                            <input type="text" class="form-control" id="updateOrigin" name="updateOrigin" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateDestination">Destination</label>
                                            <input type="text" class="form-control" id="updateDestination" name="updateDestination" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateDistance">Distance</label>
                                            <input type="text" class="form-control" id="updateDistance" name="updateDistance" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update Route</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Delete Route Modal -->
                    <div class="modal fade" id="deleteRouteModal" tabindex="-1" role="dialog" aria-labelledby="deleteRouteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteRouteModalLabel">Delete Route</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="deleteRouteForm" method="post">
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this route?</p>
                                        <input type="hidden" class="form-control" id="deleteRouteID" name="deleteRouteID" readonly>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete Route</button>
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

        function populateUpdateModal(route) {
            document.getElementById('updateRouteID').value = route.ROUTEID;
            document.getElementById('updateRouteName').value = route.ROUTENAME;
            document.getElementById('updateOrigin').value = route.ORIGIN;
            document.getElementById('updateDestination').value = route.DESTINATION;
            document.getElementById('updateDistance').value = route.DISTANCE;
            $('#updateRouteModal').modal('show');
        }

        function populateDeleteModal(route) {
            document.getElementById('deleteRouteID').value = route.ROUTEID;
            $('#deleteRouteModal').modal('show');
        }


    </script>
</body>
</html>
