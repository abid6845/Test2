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
    // Get form data
    $BANo = $_POST['BANo'];
    $VDRA_Date = $_POST['VDRA_Date'];
    $Route = $_POST['Route'];
    $KM_Reading = $_POST['KM_Reading'];

    // Prepare SQL statement for calling the stored procedure
    $sql = "BEGIN update_vdra_km(:BANo, TO_DATE(:VDRA_Date, 'YYYY-MM-DD'), :Route, :KM_Reading); END;";

    // Parse SQL statement
    $stid = oci_parse($conn, $sql);

    // Bind variables
    oci_bind_by_name($stid, ':BANo', $BANo);
    oci_bind_by_name($stid, ':VDRA_Date', $VDRA_Date);
    oci_bind_by_name($stid, ':Route', $Route);
    oci_bind_by_name($stid, ':KM_Reading', $KM_Reading);

    // Execute the SQL statement
    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>VDRA Data added successfully!</p>";
    }

    // Free the statement
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
                        <h1 class="h3 mb-0 text-gray-800">Vehicle Daily Running Account</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add VDRA Entry</button>
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
        
    </script>
</body>
</html>
