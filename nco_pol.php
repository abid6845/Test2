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
    // Handle form submission for Executing POL Adding Procedure
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['POL_Grade'])) {
        $POL_Grade = $_POST['POL_Grade'];
        $Regular_Allocation = $_POST['Regular_Allocation'];
        $Additional_Allocation = $_POST['Additional_Allocation'];
    
        // Corrected SQL query with anonymous PL/SQL block
        $sql = "BEGIN update_pol_allocation(:POL_Grade, :Regular_Allocation, :Additional_Allocation); END;";
        $stid = oci_parse($conn, $sql);
    
        // Bind parameters properly
        oci_bind_by_name($stid, ':POL_Grade', $POL_Grade);
        oci_bind_by_name($stid, ':Regular_Allocation', $Regular_Allocation);
        oci_bind_by_name($stid, ':Additional_Allocation', $Additional_Allocation);
    
        // Execute the statement and check for errors
        $r = oci_execute($stid);
        if (!$r) {
            $m = oci_error($stid);
            echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
        } else {
            echo "<p>POL added successfully!</p>";
        }
    
        // Free statement resource
        oci_free_statement($stid);
    }
  
    





// Retrieve POLs for display
$sql = "SELECT * FROM POL";
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
  <title>POL</title>
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
            <h1 class="h3 mb-0 text-gray-800">POL</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item">POL</li>
              <li class="breadcrumb-item active" aria-current="page">POLS</li>
            </ol>
          </div>

          <!-- Row -->
          <div class="row">   
            <!-- DataTable with Hover -->
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold" style="color: #4B5320;">POL</h6>
                </div>
                <div class="text-center mb-4">
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPOLModal">
                    Add New POL
                  </button>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>Serial</th>
                        <th>POL ID</th>
                        <th>POL Grade</th>
                        <th>Regular Allocation</th>
                        <th>Additional Allocation</th>
                        <th>Total Allocation</th>
                        <th>Expense Last Month</th>
                        <th>Expense Running Month</th>
                        <th>Expense Total</th>
                        <th>Remaining POL</th>
                    
                      </tr>
                    </thead>
                    <tbody>
  <?php
  $serial = 1;
  while ($row = oci_fetch_assoc($stid)) {
      // Escape special characters in JSON data for safe inclusion in JavaScript
      $rowJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
      echo "<tr>";
      echo "<td>{$serial}</td>";
      echo "<td>{$row['POL_ID']}</td>";
      echo "<td>{$row['POL_GRADE']}</td>";
      echo "<td>{$row['REGULAR_ALLOCATION']}</td>";
      echo "<td>{$row['ADDITIONAL_ALLOCATION']}</td>";
      echo "<td>{$row['TOTAL_ALLOCATION']}</td>";
      echo "<td>{$row['EXPENSE_LAST_MONTH']}</td>";
      echo "<td>{$row['EXPENSE_RUNNING_MONTH']}</td>";
      echo "<td>{$row['EXPENSE_TOTAL']}</td>";
      echo "<td>{$row['REMAINING_POL']}</td>";
      
      echo "</tr>";
      $serial++;
  }
  ?>
</tbody>

                  </table>
                </div>
              </div>
            </div>
            <!-- DataTable with Hover -->
          </div>
          <!-- Row -->
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
    </div>
  </div>

  <!-- Add POL Modal -->
<div class="modal fade" id="addPOLModal" tabindex="-1" role="dialog" aria-labelledby="addPOLModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPOLModalLabel">Add New POL</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="">
        <div class="modal-body">
          <div class="form-group">
            <label for="POL_Grade">POL Grade</label>
            <input type="text" class="form-control" id="POL_Grade" name="POL_Grade" required>
          </div>
          <div class="form-group">
            <label for="Regular_Allocation">Regular Allocation</label>
            <input type="text" class="form-control" id="Regular_Allocation" name="Regular_Allocation" required>
          </div>
          <div class="form-group">
            <label for="Additional_Allocation">Additional Allocation</label>
            <input type="text" class="form-control" id="Additional_Allocation" name="Additional_Allocation" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add POL</button>
        </div>
      </form>
    </div>
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
  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>

  
</body>

</html>
