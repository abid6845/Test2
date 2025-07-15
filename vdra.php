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

// Handle form submission for adding VDRA data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['BANo'])) {
    $BANo = $_POST['BANo'];
    $VDRA_Date = $_POST['VDRA_Date'];
    $Route = $_POST['Route'];
    $KM_Reading = $_POST['KM_Reading'];
    $POL_Used = $_POST['POL_Used'];
    $Tank_State = $_POST['Tank_State'];    
    $Diesel = $_POST['Diesel'];
    $Hd_30 = $_POST['Hd_30'];
    $GX_90 = $_POST['GX_90'];
    $K2 = $_POST['K2'];
    $Greese = $_POST['Greese'];
    $Break_Fluid = $_POST['Break_Fluid'];
    $MS_74 = $_POST['MS_74'];
    $Octen_100 = $_POST['Octen_100'];

    // Prepare SQL statement including all necessary fields
    $sql = "INSERT INTO VDRA (BANo, VDRA_Date, Route, KM_Reading, POL_Used, Tank_State, Diesel, Hd_30, GX_90, K2, Greese, Break_Fluid, MS_74, Octen_100) 
            VALUES (:BANo, TO_DATE(:VDRA_Date, 'DD-MON-YY'), :Route, :KM_Reading, :POL_Used, :Tank_State, :Diesel, :Hd_30, :GX_90, :K2, :Greese, :Break_Fluid, :MS_74, :Octen_100)";

    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':BANo', $BANo);
    oci_bind_by_name($stid, ':VDRA_Date', $VDRA_Date);
    oci_bind_by_name($stid, ':Route', $Route);
    oci_bind_by_name($stid, ':KM_Reading', $KM_Reading);
    oci_bind_by_name($stid, ':POL_Used', $POL_Used);
    oci_bind_by_name($stid, ':Tank_State', $Tank_State);
    oci_bind_by_name($stid, ':Diesel', $Diesel);
    oci_bind_by_name($stid, ':Hd_30', $Hd_30);
    oci_bind_by_name($stid, ':GX_90', $GX_90);
    oci_bind_by_name($stid, ':K2', $K2);
    oci_bind_by_name($stid, ':Greese', $Greese);
    oci_bind_by_name($stid, ':Break_Fluid', $Break_Fluid);
    oci_bind_by_name($stid, ':MS_74', $MS_74);
    oci_bind_by_name($stid, ':Octen_100', $Octen_100);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>VDRA Data added successfully!</p>";
    }

    oci_free_statement($stid);
}

// Handle form submission for updating VDRA data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateBANo'])) {
    $BANo = $_POST['updateBANo'];
    $VDRA_Date = isset($_POST['updateVDRA_Date']) ? $_POST['updateVDRA_Date'] : '';
    $Route = isset($_POST['updateRoute']) ? $_POST['updateRoute'] : '';
    $KM_Reading = isset($_POST['updateKM_Reading']) ? $_POST['updateKM_Reading'] : '';
    $POL_Used = isset($_POST['updatePOL_Used']) ? $_POST['updatePOL_Used'] : '';
    $Tank_State = isset($_POST['updateTank_State']) ? $_POST['updateTank_State'] : '';
    $Diesel = isset($_POST['updateDiesel']) ? $_POST['updateDiesel'] : '';
    $Hd_30 = isset($_POST['updateHd_30']) ? $_POST['updateHd_30'] : '';
    $GX_90 = isset($_POST['updateGX_90']) ? $_POST['updateGX_90'] : '';
    $K2 = isset($_POST['updateK2']) ? $_POST['updateK2'] : '';
    $Greese = isset($_POST['updateGreese']) ? $_POST['updateGreese'] : '';
    $Break_Fluid = isset($_POST['updateBreak_Fluid']) ? $_POST['updateBreak_Fluid'] : '';
    $MS_74 = isset($_POST['updateMS_74']) ? $_POST['updateMS_74'] : '';
    $Octen_100 = isset($_POST['updateOcten_100']) ? $_POST['updateOcten_100'] : '';

    // Prepare SQL statement including all necessary fields
    $sql = "UPDATE VDRA SET VDRA_Date = TO_DATE(:VDRA_Date, 'DD-MON-YY'), 
            Route = :Route, KM_Reading = :KM_Reading, POL_Used = :POL_Used, 
            Tank_State = :Tank_State, Diesel = :Diesel, 
            Hd_30 = :Hd_30, GX_90 = :GX_90, K2 = :K2, 
            Greese = :Greese, Break_Fluid = :Break_Fluid, 
            MS_74 = :MS_74, Octen_100 = :Octen_100 
            WHERE BANo = :BANo AND VDRA_Date = TO_DATE(:VDRA_Date, 'DD-MON-YY')";
    
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':BANo', $BANo);
    oci_bind_by_name($stid, ':VDRA_Date', $VDRA_Date);
    oci_bind_by_name($stid, ':Route', $Route);
    oci_bind_by_name($stid, ':KM_Reading', $KM_Reading);
    oci_bind_by_name($stid, ':POL_Used', $POL_Used);
    oci_bind_by_name($stid, ':Tank_State', $Tank_State);
    oci_bind_by_name($stid, ':Diesel', $Diesel);
    oci_bind_by_name($stid, ':Hd_30', $Hd_30);
    oci_bind_by_name($stid, ':GX_90', $GX_90);
    oci_bind_by_name($stid, ':K2', $K2);
    oci_bind_by_name($stid, ':Greese', $Greese);
    oci_bind_by_name($stid, ':Break_Fluid', $Break_Fluid);
    oci_bind_by_name($stid, ':MS_74', $MS_74);
    oci_bind_by_name($stid, ':Octen_100', $Octen_100);
    
    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to update data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>VDRA updated successfully!</p>";
    }

    oci_free_statement($stid);
}

// Handle form submission for deleting a role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteBANo'])) {
    $BANo = $_POST['deleteBANo'];

    $sql = "DELETE FROM VDRA WHERE BANo = :BANo";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':BANo', $BANo);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to delete data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>VDRA Entry deleted successfully!</p>";
    }

    oci_free_statement($stid);
}

// Retrieve roles for display
$sql = "SELECT * FROM VDRA";
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
    <title>VDRA</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Vehicle Daily Running Account</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">Vehicles</li>
                            <li class="breadcrumb-item active" aria-current="page">VDRA</li>
                        </ol>
                    </div>

                    <!-- Row -->
                    <div class="row">
                        <!-- DataTable with Hover -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold" style="color: #4B5320;">VDRA</h6>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addVDRAModal">
                                        Add New VDRA Data Entry
                                    </button>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead class="thead-light">
                                    <tr>
                                        <th>Serial</th>
                                        <th>BA No</th>
                                        <th>Date</th>
                                        <th>Route</th>                                       
                                        <th>KM Reading</th>
                                        <th>POL Used</th>
                                        <th>Tank State</th>
                                        <th>Diesel</th> <!-- Corrected spelling -->                                       
                                        <th>HD-30</th> <!-- Added HD-30 -->
                                        <th>GX-90</th> <!-- Added GX-90 -->
                                        <th>K2</th> <!-- Added K2 -->
                                        <th>Greese</th> <!-- Added Greese -->
                                        <th>Break Fluid</th> <!-- Added Break Fluid -->
                                        <th>MS-74</th>
                                        <th>Octen-100</th>
                                        <th>Action</th>
                                    </tr>

                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Serial</th>
                                            <th>BA No</th>
                                            <th>Date</th>
                                            <th>Route</th>                                       
                                            <th>KM Reading</th>
                                            <th>POL Used</th>
                                            <th>Tank State</th>
                                            <th>Diesel</th> <!-- Corrected spelling -->                                       
                                            <th>HD-30</th> <!-- Added HD-30 -->
                                            <th>GX-90</th> <!-- Added GX-90 -->
                                            <th>K2</th> <!-- Added K2 -->
                                            <th>Greese</th> <!-- Added Greese -->
                                            <th>Break Fluid</th> <!-- Added Break Fluid -->
                                            <th>MS-74</th>
                                            <th>Octen-100</th>
                                            <th>Action</th>
                                    </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $sl = 1;
                                            while ($row = oci_fetch_assoc($stid)) {
                                                echo '<tr>';
                                                echo '<td>' . $sl . '</td>';
                                                // Assuming $row is the current row fetched from your database
                                                echo '<td>' . htmlentities($row['BANO'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['VDRA_DATE'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['ROUTE'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['KM_READING'], ENT_QUOTES) . '</td>';  // Added KM Reading
                                                echo '<td>' . htmlentities($row['POL_USED'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['TANK_STATE'], ENT_QUOTES) . '</td>';  // Added Tank State
                                                echo '<td>' . htmlentities($row['DIESEL'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['HD_30'], ENT_QUOTES) . '</td>';  // Added HD-30
                                                echo '<td>' . htmlentities($row['GX_90'], ENT_QUOTES) . '</td>';  // Added GX-90
                                                echo '<td>' . htmlentities($row['K2'], ENT_QUOTES) . '</td>';  // Added K2
                                                echo '<td>' . htmlentities($row['GREESE'], ENT_QUOTES) . '</td>';  // Added Greese
                                                echo '<td>' . htmlentities($row['BREAK_FLUID'], ENT_QUOTES) . '</td>';  // Added Break Fluid
                                                echo '<td>' . htmlentities($row['MS_74'], ENT_QUOTES) . '</td>';
                                                echo '<td>' . htmlentities($row['OCTEN_100'], ENT_QUOTES) . '</td>';

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

                    <!-- Add VDRA Modal -->
                    <div class="modal fade" id="addVDRAModal" tabindex="-1" role="dialog" aria-labelledby="addVDRAModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addVDRAModalLabel">Add New VDRA Entry</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="BANo">BA No</label>
                                            <input type="text" class="form-control" id="BANo" name="BANo" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="VDRA_Date">VDRA Date</label>
                                            <input type="date" class="form-control" id="VDRA_Date" name="VDRA_Date" required value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="Route">Route</label>
                                            <input type="text" class="form-control" id="Route" name="Route" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="KM_Reading">KM Reading</label>
                                            <input type="text" class="form-control" id="KM_Reading" name="KM_Reading">
                                        </div>
                                        <div class="form-group">
                                            <label for="POL_Used">POL Used</label>
                                            <input type="text" class="form-control" id="POL_Used" name="POL_Used">
                                        </div>
                                        <div class="form-group">
                                            <label for="Tank_State">Tank State</label>
                                            <input type="text" class="form-control" id="Tank_State" name="Tank_State">
                                        </div>
                                        <div class="form-group">
                                            <label for="Diesel">Diesel</label>
                                            <input type="text" class="form-control" id="Diesel" name="Diesel">
                                        </div>
                                        <div class="form-group">
                                            <label for="Hd_30">HD-30</label>
                                            <input type="text" class="form-control" id="Hd_30" name="Hd_30">
                                        </div>
                                        <div class="form-group">
                                            <label for="GX_90">GX-90</label>
                                            <input type="text" class="form-control" id="GX_90" name="GX_90">
                                        </div>
                                        <div class="form-group">
                                            <label for="K2">K2</label>
                                            <input type="text" class="form-control" id="K2" name="K2">
                                        </div>
                                        <div class="form-group">
                                            <label for="Greese">Greese</label>
                                            <input type="text" class="form-control" id="Greese" name="Greese">
                                        </div>
                                        <div class="form-group">
                                            <label for="Break_Fluid">Break Fluid</label>
                                            <input type="text" class="form-control" id="Break_Fluid" name="Break_Fluid">
                                        </div>
                                        <div class="form-group">
                                            <label for="MS_74">MS-74</label>
                                            <input type="text" class="form-control" id="MS_74" name="MS_74">
                                        </div>
                                        <div class="form-group">
                                            <label for="Octen_100">Octen_100</label>
                                            <input type="text" class="form-control" id="Octen_100" name="Octen_100">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add VDRA Entry</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Update VDRA Modal -->
                    <div class="modal fade" id="updateVDRAModal" tabindex="-1" role="dialog" aria-labelledby="updateVDRAModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form id="updateVDRAForm" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateVDRAModalLabel">Update VDRA Entry</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="updateBANo">BA No</label>
                                            <input type="text" class="form-control" id="updateBANo" name="updateBANo" readonly required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateVDRA_Date">VDRA Date</label>
                                            <input type="date" class="form-control" id="updateVDRA_Date" name="updateVDRA_Date" required value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="updateRoute">Route</label>
                                            <input type="text" class="form-control" id="updateRoute" name="updateRoute" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateKM_Reading">KM Reading</label>
                                            <input type="text" class="form-control" id="updateKM_Reading" name="updateKM_Reading" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updatePOL_Used">POL Used</label>
                                            <input type="text" class="form-control" id="updatePOL_Used" name="updatePOL_Used" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateTank_State">Tank State</label>
                                            <input type="text" class="form-control" id="updateTank_State" name="updateTank_State" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateDiesel">Diesel</label>
                                            <input type="text" class="form-control" id="updateDiesel" name="updateDiesel" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateHd_30">HD-30</label>
                                            <input type="text" class="form-control" id="updateHd_30" name="updateHd_30" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateGX_90">GX-90</label>
                                            <input type="text" class="form-control" id="updateGX_90" name="updateGX_90" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateK2">K2</label>
                                            <input type="text" class="form-control" id="updateK2" name="updateK2" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateGreese">Greese</label>
                                            <input type="text" class="form-control" id="updateGreese" name="updateGreese" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateBreak_Fluid">Break Fluid</label>
                                            <input type="text" class="form-control" id="updateBreak_Fluid" name="updateBreak_Fluid" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateMS_74">MS-74</label>
                                            <input type="text" class="form-control" id="updateMS_74" name="updateMS_74" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="updateOcten_100">Octen 100</label>
                                            <input type="text" class="form-control" id="updateOcten_100" name="updateOcten_100" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update VDRA Entry</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Delete Role Modal -->
                    <div class="modal fade" id="deleteVDRAModal" tabindex="-1" role="dialog" aria-labelledby="deleteVDRAModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteVDRAModalLabel">Delete VDRA Entry</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="deleteVDRAForm" method="post">
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this enrty?</p>
                                        <input type="hidden" name="deleteBANo" id="deleteBANo">
                                        <div class="form-group">
                                            <label for="deleteVDRA_Date">Date</label>
                                            <input type="text" class="form-control" id="deleteVDRA_Date" name="deleteVDRA_Date" readonly>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete VDRA Entry</button>
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

        function populateUpdateModal(VDRA) {
        $('#updateBANo').val(VDRA.BANO);
        $('#updateVDRA_Date').val(VDRA.VDRA_DATE); // Ensure VDRA_DATE is in the 'YYYY-MM-DD' format for the input
        $('#updateRoute').val(VDRA.ROUTE);
        $('#updateKM_Reading').val(VDRA.KM_READING); // Corrected from updateKPL to updateKM_Reading
        $('#updatePOL_Used').val(VDRA.POL_USED);
        $('#updateTank_State').val(VDRA.TANK_STATE); // Added this to match the modal
        $('#updateDiesel').val(VDRA.DIESEL);
        $('#updateHd_30').val(VDRA.HD_30); // Corrected from updateKPR to updateHd_30
        $('#updateGX_90').val(VDRA.GX_90); // Added this to match the modal
        $('#updateK2').val(VDRA.K2); // Added this to match the modal
        $('#updateGreese').val(VDRA.GREESE); // Added this to match the modal
        $('#updateBreak_Fluid').val(VDRA.BREAK_FLUID); // Added this to match the modal
        $('#updateMS_74').val(VDRA.MS_74);
        $('#updateOcten_100').val(VDRA.OCTEN_100);
        $('#updateVDRAModal').modal('show');
    }



        function populateDeleteModal(VDRA) {
            $('#deleteBANo').val(VDRA.BANO);
            $('#deleteVDRA_Date').val(VDRA.VDRA_DATE);
            $('#deleteVDRAModal').modal('show');
        }
    </script>
</body>
</html>
