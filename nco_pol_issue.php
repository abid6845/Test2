<?php
require('connection.php');
include 'session_check.php'; // Include session check script




    // Check if user is logged in
    if (empty($_SESSION['username'])) {
        header('location:login.php');
        exit;
    }

    $username = $_SESSION['username'];

// Handle form submission for adding a POL issue
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['IssueDate'])) {
    $IssueDate = $_POST['IssueDate'];
    $VehicleID = $_POST['VehicleID'];
    $POL_Grade = $_POST['POL_Grade'];
    $IssueAmount = $_POST['IssueAmount'];
    $POL_ID = $_POST['POL_ID'];

    $sql = "INSERT INTO POL_Issue ( IssueDate, VehicleID, POL_Grade, IssueAmount, POL_ID) 
            VALUES (TO_DATE(:IssueDate, 'YYYY-MM-DD'), :VehicleID, :POL_Grade, :IssueAmount, :POL_ID)";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':IssueDate', $IssueDate);
    oci_bind_by_name($stid, ':VehicleID', $VehicleID);
    oci_bind_by_name($stid, ':POL_Grade', $POL_Grade);
    oci_bind_by_name($stid, ':IssueAmount', $IssueAmount);
    oci_bind_by_name($stid, ':POL_ID', $POL_ID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>POL Issue added successfully!</p>";
    }

    oci_free_statement($stid);
}





// Retrieve POL issues for display
$sql = "SELECT * FROM POL_Issue";
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
    <title>POL Issue</title>
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
                        <h1 class="h3 mb-0 text-gray-800">POL Issue</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="nco_dashboard.php">Home</a></li>
                            <li class="breadcrumb-item">POL</li>
                            <li class="breadcrumb-item active" aria-current="page">POL Issue</li>
                        </ol>
                    </div>

                    <!-- Row -->
                    <div class="row">
                        <!-- DataTable with Hover -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold" style="color: #4B5320;">POL Issue</h6>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPOLIssueModal">
                                        Add New POL Issue
                                    </button>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Serial</th>
                                                <th>Issue ID</th>
                                                <th>Issue Date</th>
                                                <th>Vehicle ID</th>
                                                <th>POL Grade</th>
                                                <th>Issue Amount</th>
                                                <th>POL ID</th>
                                               
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Serial</th>
                                                <th>Issue ID</th>
                                                <th>Issue Date</th>
                                                <th>Vehicle ID</th>
                                                <th>POL Grade</th>
                                                <th>Issue Amount</th>
                                                <th>POL ID</th>
                                             >
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                        <?php
                                        $sl = 1;
                                        while ($row = oci_fetch_assoc($stid)) {
                                            echo '<tr>';
                                            echo '<td>' . $sl . '</td>';
                                            echo '<td>' . htmlentities($row['ISSUEID'], ENT_QUOTES) . '</td>';
                                            echo '<td>' . htmlentities(date('Y-m-d', strtotime($row['ISSUEDATE'])), ENT_QUOTES) . '</td>';
                                            echo '<td>' . htmlentities($row['VEHICLEID'], ENT_QUOTES) . '</td>';
                                            echo '<td>' . htmlentities($row['POL_GRADE'], ENT_QUOTES) . '</td>';
                                            echo '<td>' . htmlentities($row['ISSUEAMOUNT'], ENT_QUOTES) . '</td>';
                                            echo '<td>' . htmlentities($row['POL_ID'], ENT_QUOTES) . '</td>';
                                            
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
                    <!-- Modal for Adding POL Issue -->
                    <div class="modal fade" id="addPOLIssueModal" tabindex="-1" role="dialog" aria-labelledby="addPOLIssueModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addPOLIssueModalLabel">Add POL Issue</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        
                                        <div class="form-group">
                                            <label for="IssueDate">Issue Date</label>
                                            <input type="date" class="form-control" id="IssueDate" name="IssueDate" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="VehicleID">Vehicle ID</label>
                                            <input type="text" class="form-control" id="VehicleID" name="VehicleID" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="POL_Grade">POL Grade</label>
                                            <input type="text" class="form-control" id="POL_Grade" name="POL_Grade" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="IssueAmount">Issue Amount</label>
                                            <input type="number" class="form-control" id="IssueAmount" name="IssueAmount" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="POL_ID">POL ID</label>
                                            <input type="text" class="form-control" id="POL_ID" name="POL_ID" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add POL Issue</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    



                    
                </div>
                <!-- Container Fluid -->
            </div>
            <!-- Footer -->
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
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/ruang-admin.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTableHover').DataTable(); // Initialize DataTables
        });
    </script>
    
</body>

</html>
