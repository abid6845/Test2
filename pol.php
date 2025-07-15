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
// Handle form submission for adding a POL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['POL_Grade'])) {
    $POL_Grade = $_POST['POL_Grade'];
    $Regular_Allocation = $_POST['Regular_Allocation'];
    $Additional_Allocation = $_POST['Additional_Allocation'];
    $Total_Allocation = $_POST['Total_Allocation'];
    $Expense_Last_Month = $_POST['Expense_Last_Month'];
    $Expense_Running_Month = $_POST['Expense_Running_Month'];
    $Expense_Total = $_POST['Expense_Total'];
    $Remaining_POL = $_POST['Remaining_POL'];

    $sql = "INSERT INTO POL (POL_Grade, Regular_Allocation, Additional_Allocation, Total_Allocation, Expense_Last_Month, Expense_Running_Month, Expense_Total, Remaining_POL) 
            VALUES (:POL_Grade, :Regular_Allocation, :Additional_Allocation, :Total_Allocation, :Expense_Last_Month, :Expense_Running_Month, :Expense_Total, :Remaining_POL)";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':POL_Grade', $POL_Grade);
    oci_bind_by_name($stid, ':Regular_Allocation', $Regular_Allocation);
    oci_bind_by_name($stid, ':Additional_Allocation', $Additional_Allocation);
    oci_bind_by_name($stid, ':Total_Allocation', $Total_Allocation);
    oci_bind_by_name($stid, ':Expense_Last_Month', $Expense_Last_Month);
    oci_bind_by_name($stid, ':Expense_Running_Month', $Expense_Running_Month);
    oci_bind_by_name($stid, ':Expense_Total', $Expense_Total);
    oci_bind_by_name($stid, ':Remaining_POL', $Remaining_POL);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
        print_r($m);
    } else {
        echo "<p>POL added successfully!</p>";
    }

    oci_free_statement($stid);
}

// Handle form submission for updating a POL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updatePOL_ID'])) {
    $POL_ID = $_POST['updatePOL_ID'];
    $POL_Grade = isset($_POST['updatePOL_Grade']) ? $_POST['updatePOL_Grade'] : '';
    $Regular_Allocation = isset($_POST['updateRegular_Allocation']) ? $_POST['updateRegular_Allocation'] : '';
    $Additional_Allocation = isset($_POST['updateAdditional_Allocation']) ? $_POST['updateAdditional_Allocation'] : '';
    $Total_Allocation = isset($_POST['updateTotal_Allocation']) ? $_POST['updateTotal_Allocation'] : '';
    $Expense_Last_Month = isset($_POST['updateExpense_Last_Month']) ? $_POST['updateExpense_Last_Month'] : '';
    $Expense_Running_Month = isset($_POST['updateExpense_Running_Month']) ? $_POST['updateExpense_Running_Month'] : '';
    $Expense_Total = isset($_POST['updateExpense_Total']) ? $_POST['updateExpense_Total'] : '';
    $Remaining_POL = isset($_POST['updateRemaining_POL']) ? $_POST['updateRemaining_POL'] : '';

    $sql = "UPDATE POL SET POL_Grade = :POL_Grade, Regular_Allocation = :Regular_Allocation, Additional_Allocation = :Additional_Allocation, Total_Allocation = :Total_Allocation, 
            Expense_Last_Month = :Expense_Last_Month, Expense_Running_Month = :Expense_Running_Month, Expense_Total = :Expense_Total, Remaining_POL = :Remaining_POL
            WHERE POL_ID = :POL_ID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':POL_ID', $POL_ID);
    oci_bind_by_name($stid, ':POL_Grade', $POL_Grade);
    oci_bind_by_name($stid, ':Regular_Allocation', $Regular_Allocation);
    oci_bind_by_name($stid, ':Additional_Allocation', $Additional_Allocation);
    oci_bind_by_name($stid, ':Total_Allocation', $Total_Allocation);
    oci_bind_by_name($stid, ':Expense_Last_Month', $Expense_Last_Month);
    oci_bind_by_name($stid, ':Expense_Running_Month', $Expense_Running_Month);
    oci_bind_by_name($stid, ':Expense_Total', $Expense_Total);
    oci_bind_by_name($stid, ':Remaining_POL', $Remaining_POL);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to update data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>POL updated successfully!</p>";
    }

    oci_free_statement($stid);
}

// Handle form submission for deleting a POL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletePOL_ID'])) {
    $POL_ID = $_POST['deletePOL_ID'];

    $sql = "DELETE FROM POL WHERE POL_ID = :POL_ID";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':POL_ID', $POL_ID);

    $r = oci_execute($stid);

    if (!$r) {
        $m = oci_error($stid);
        echo "<p>Failed to delete data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
    } else {
        echo "<p>POL deleted successfully!</p>";
    }

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
            <h1 class="h3 mb-0 text-gray-800">POL</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item">POL</li>
              <li class="breadcrumb-item active" aria-current="page">POL</li>
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
                        <th>Actions</th>
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
      echo "<td>
            <a href=\"#\" class=\"btn btn-success d-inline-block\" onclick='populateUpdateModal($rowJson)'>
                <i class=\"fas fa-edit\"></i>
            </a>
            <a href=\"#\" class=\"btn btn-danger d-inline-block ms-2\" onclick='populateDeleteModal($rowJson)'>
                <i class=\"fas fa-trash\"></i>
            </a>
            </td>";
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
            <div class="form-group">
              <label for="Total_Allocation">Total Allocation</label>
              <input type="text" class="form-control" id="Total_Allocation" name="Total_Allocation" required>
            </div>
            <div class="form-group">
              <label for="Expense_Last_Month">Expense Last Month</label>
              <input type="text" class="form-control" id="Expense_Last_Month" name="Expense_Last_Month" required>
            </div>
            <div class="form-group">
              <label for="Expense_Running_Month">Expense Running Month</label>
              <input type="text" class="form-control" id="Expense_Running_Month" name="Expense_Running_Month" required>
            </div>
            <div class="form-group">
              <label for="Expense_Total">Expense Total</label>
              <input type="text" class="form-control" id="Expense_Total" name="Expense_Total" required>
            </div>
            <div class="form-group">
              <label for="Remaining_POL">Remaining POL</label>
              <input type="text" class="form-control" id="Remaining_POL" name="Remaining_POL" required>
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

  <!-- Update POL Modal -->
  <div class="modal fade" id="updatePOLModal" tabindex="-1" role="dialog" aria-labelledby="updatePOLModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updatePOLModalLabel">Update POL</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="">
          <div class="modal-body">
            <input type="hidden" id="updatePOL_ID" name="updatePOL_ID">
            <div class="form-group">
              <label for="updatePOL_Grade">POL Grade</label>
              <input type="text" class="form-control" id="updatePOL_Grade" name="updatePOL_Grade">
            </div>
            <div class="form-group">
              <label for="updateRegular_Allocation">Regular Allocation</label>
              <input type="text" class="form-control" id="updateRegular_Allocation" name="updateRegular_Allocation">
            </div>
            <div class="form-group">
              <label for="updateAdditional_Allocation">Additional Allocation</label>
              <input type="text" class="form-control" id="updateAdditional_Allocation" name="updateAdditional_Allocation">
            </div>
            <div class="form-group">
              <label for="updateTotal_Allocation">Total Allocation</label>
              <input type="text" class="form-control" id="updateTotal_Allocation" name="updateTotal_Allocation">
            </div>
            <div class="form-group">
              <label for="updateExpense_Last_Month">Expense Last Month</label>
              <input type="text" class="form-control" id="updateExpense_Last_Month" name="updateExpense_Last_Month">
            </div>
            <div class="form-group">
              <label for="updateExpense_Running_Month">Expense Running Month</label>
              <input type="text" class="form-control" id="updateExpense_Running_Month" name="updateExpense_Running_Month">
            </div>
            <div class="form-group">
              <label for="updateExpense_Total">Expense Total</label>
              <input type="text" class="form-control" id="updateExpense_Total" name="updateExpense_Total">
            </div>
            <div class="form-group">
              <label for="updateRemaining_POL">Remaining POL</label>
              <input type="text" class="form-control" id="updateRemaining_POL" name="updateRemaining_POL">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update POL</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete POL Modal -->
  <div class="modal fade" id="deletePOLModal" tabindex="-1" role="dialog" aria-labelledby="deletePOLModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deletePOLModalLabel">Delete POL</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="">
          <div class="modal-body">
            <p>Are you sure you want to delete POL ID <span id="deletePOL_IDDisplay"></span>?</p>
            <input type="hidden" id="deletePOL_ID" name="deletePOL_ID">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Delete POL</button>
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

  <script>
    function populateUpdateModal(data) {
      document.getElementById('updatePOL_ID').value = data.POL_ID;
      document.getElementById('updatePOL_Grade').value = data.POL_GRADE;
      document.getElementById('updateRegular_Allocation').value = data.REGULAR_ALLOCATION;
      document.getElementById('updateAdditional_Allocation').value = data.ADDITIONAL_ALLOCATION;
      document.getElementById('updateTotal_Allocation').value = data.TOTAL_ALLOCATION;
      document.getElementById('updateExpense_Last_Month').value = data.EXPENSE_LAST_MONTH;
      document.getElementById('updateExpense_Running_Month').value = data.EXPENSE_RUNNING_MONTH;
      document.getElementById('updateExpense_Total').value = data.EXPENSE_TOTAL;
      document.getElementById('updateRemaining_POL').value = data.REMAINING_POL;
      $('#updatePOLModal').modal('show');
    }

    function populateDeleteModal(data) {
      document.getElementById('deletePOL_ID').value = data.POL_ID;
      document.getElementById('deletePOL_IDDisplay').textContent = data.POL_ID;
      $('#deletePOLModal').modal('show');
    }
  </script>
</body>

</html>
