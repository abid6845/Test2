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


// Handle form submission for adding a driver
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['DriverID'])) {
    $DriverID = $_POST['DriverID'];
    $First_Name = $_POST['First_Name'];
    $Last_Name = $_POST['Last_Name'];
    $Rank = $_POST['Rank'];
    $Unit = $_POST['Unit'];
    $LicenseNumber = $_POST['LicenseNumber'];
    $ContactInfo = $_POST['ContactInfo'];

    $sql = "INSERT INTO driver (DriverID, First_Name, Last_Name, Rank, Unit, LicenseNumber, ContactInfo) 
            VALUES (:DriverID, :First_Name, :Last_Name, :Rank, :Unit, :LicenseNumber, :ContactInfo)";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':DriverID', $DriverID);
    oci_bind_by_name($stid, ':First_Name', $First_Name);
    oci_bind_by_name($stid, ':Last_Name', $Last_Name);
    oci_bind_by_name($stid, ':Rank', $Rank);
    oci_bind_by_name($stid, ':Unit', $Unit);
    oci_bind_by_name($stid, ':LicenseNumber', $LicenseNumber);
    oci_bind_by_name($stid, ':ContactInfo', $ContactInfo);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>Driver added successfully!</p>";
    }

    oci_free_statement($stid);
}




// Retrieve drivers for display
$sql = "SELECT * FROM driver";
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
    <title>Drivers</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include 'nco_sidebar.php'; ?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include 'topbar.php'; ?>
                <!-- Topbar -->
                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Driver</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="nco_dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Drivers</li>
                            <li class="breadcrumb-item active" aria-current="page">Drivers</li>
                        </ol>
                    </div>

                    <!-- Row -->
                    <div class="row">
                        <!-- DataTable with Hover -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold" style="color: #4B5320;">Driver</h6>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#adddriverModal">
                                        Add New Driver
                                    </button>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Serial</th>
                                                <th>Driver ID</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Rank</th>
                                                <th>Unit</th>
                                                <th>License Number</th>
                                                <th>Contact Info</th>
                                         
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Serial</th>
                                                <th>Driver ID</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Rank</th>
                                                <th>Unit</th>
                                                <th>License Number</th>
                                                <th>Contact Info</th>
                                         
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $sl = 1;
                                            while ($row = oci_fetch_assoc($stid)) {
                                                echo '<tr>';
                                                echo '<td>' . $sl . '</td>';
                                                foreach ($row as $item) {
                                                    echo '<td>' . ($item !== null ? htmlentities($item, ENT_QUOTES) : '&nbsp;') . '</td>';
                                                }
                                                

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

                    <!-- Add Driver Modal -->
                    <div class="modal fade" id="adddriverModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color: #4B5320;">Add New Driver</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="" method="POST">
                                        <div class="form-group">
                                            <label for="DriverID" class="col-form-label">Driver ID:</label>
                                            <input type="text" class="form-control" id="DriverID" name="DriverID" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="RoleID" class="col-form-label">First Name:</label>
                                            <input type="text" class="form-control" id="First_Name" name="First_Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Name" class="col-form-label">Name:</label>
                                            <input type="text" class="form-control" id="Last_Name" name="Last_Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Rank" class="col-form-label">Rank:</label>
                                            <input type="text" class="form-control" id="Rank" name="Rank" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Unit" class="col-form-label">Unit:</label>
                                            <input type="text" class="form-control" id="Unit" name="Unit" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="LicenseNumber" class="col-form-label">License Number:</label>
                                            <input type="text" class="form-control" id="LicenseNumber" name="LicenseNumber" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="ContactInfo" class="col-form-label">Contact Info:</label>
                                            <input type="text" class="form-control" id="ContactInfo" name="ContactInfo" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Add Driver</button>
                                        </div>
                                    </form>
                                </div>
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


    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTableHover').DataTable(); // ID From dataTable with Hover
        });

        
      

    </script>
</body>
</html>
