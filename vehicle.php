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

    // Handle form submission for adding a vehicle
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['baNo'])) {
        $baNo = $_POST['baNo'];
        $name = $_POST['name'];
        $model = $_POST['model'];
        $status = $_POST['status'];
        $class = $_POST['class'];
        $vehicleCode = $_POST['vehicleCode'];
        $unit = $_POST['unit'];
        $KM_Reading = $_POST['KM_Reading'];
        $kpl = $_POST['kpl'];
        $availability = $_POST['availability'];
        $driverID = $_POST['driverID'];

        $sql = "INSERT INTO vehicle (BANO, NAME, MODEL, STATUS, CLASS, VEHICLECODE, UNIT, KM_READING, KPL, AVAILABILITY, DRIVERID) 
                VALUES (:baNo, :name, :model, :status, :class, :vehicleCode, :unit, :KM_Reading, :kpl, :availability, :driverID)";
        $stid = oci_parse($conn, $sql);

        oci_bind_by_name($stid, ':baNo', $baNo);
        oci_bind_by_name($stid, ':name', $name);
        oci_bind_by_name($stid, ':model', $model);
        oci_bind_by_name($stid, ':status', $status);
        oci_bind_by_name($stid, ':class', $class);
        oci_bind_by_name($stid, ':vehicleCode', $vehicleCode);
        oci_bind_by_name($stid, ':unit', $unit);
        oci_bind_by_name($stid, ':KM_Reading', $KM_Reading);
        oci_bind_by_name($stid, ':kpl', $kpl);
        oci_bind_by_name($stid, ':availability', $availability);
        oci_bind_by_name($stid, ':driverID', $driverID);

        $r = oci_execute($stid);

        if (!$r) {
            $m = oci_error($stid);
            echo "<p>Failed to insert data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
        } else {
            echo "<p>Vehicle added successfully!</p>";
        }

        oci_free_statement($stid);
    }

    // Handle form submission for updating a vehicle
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateBaNo'])) {
      $baNo = $_POST['updateBaNo'];
      $name = isset($_POST['updateName']) ? $_POST['updateName'] : '';
      $model = isset($_POST['updateModel']) ? $_POST['updateModel'] : '';
      $status = isset($_POST['updateStatus']) ? $_POST['updateStatus'] : '';
      $class = isset($_POST['updateClass']) ? $_POST['updateClass'] : '';
      $vehicleCode = isset($_POST['updateVehicleCode']) ? $_POST['updateVehicleCode'] : '';
      $unit = isset($_POST['updateUnit']) ? $_POST['updateUnit'] : '';
      $runningKMR = isset($_POST['updateKM_Reading']) ? $_POST['updateKM_Reading'] : '';
      $kpl = isset($_POST['updateKPL']) ? $_POST['updateKPL'] : '';
      $availability = isset($_POST['updateAvailability']) ? $_POST['updateAvailability'] : '';
      $driverID = isset($_POST['updateDriverID']) ? $_POST['updateDriverID'] : '';
  
      $sql = "UPDATE vehicle SET NAME = :name, MODEL = :model, STATUS = :status, CLASS = :class, 
              VEHICLECODE = :vehicleCode, UNIT = :unit, KM_READING = :KM_Reading, KPL = :kpl, 
              AVAILABILITY = :availability, DRIVERID = :driverID WHERE BANO = :baNo";
      $stid = oci_parse($conn, $sql);
  
      oci_bind_by_name($stid, ':baNo', $baNo);
      oci_bind_by_name($stid, ':name', $name);
      oci_bind_by_name($stid, ':model', $model);
      oci_bind_by_name($stid, ':status', $status);
      oci_bind_by_name($stid, ':class', $class);
      oci_bind_by_name($stid, ':vehicleCode', $vehicleCode);
      oci_bind_by_name($stid, ':unit', $unit);
      oci_bind_by_name($stid, ':KM_Reading', $KM_Reading);
      oci_bind_by_name($stid, ':kpl', $kpl);
      oci_bind_by_name($stid, ':availability', $availability);
      oci_bind_by_name($stid, ':driverID', $driverID);
  
      $r = oci_execute($stid);
  
      if (!$r) {
          $m = oci_error($stid);
          echo "<p>Failed to update data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
      } else {
          echo "<p>Vehicle updated successfully!</p>";
      }
  
      oci_free_statement($stid);
  }
  

  // Handle form submission for deleting a vehicle
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteBANo'])) {
      $baNo = $_POST['deleteBANo'];

      $sql = "DELETE FROM vehicle WHERE BANO = :baNo";
      $stid = oci_parse($conn, $sql);

      oci_bind_by_name($stid, ':baNo', $baNo);

      $r = oci_execute($stid);

      if (!$r) {
          $m = oci_error($stid);
          echo "<p>Failed to delete data: " . htmlentities($m['message'], ENT_QUOTES) . "</p>";
      } else {
          echo "<p>Vehicle deleted successfully!</p>";
      }

      oci_free_statement($stid);
  }

  // Retrieve vehicles for display
  $sql = "SELECT * FROM vehicle";
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
  <title>Vehicles</title>
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
            <h1 class="h3 mb-0 text-gray-800">Vehicle</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item">Vehicles</li>
              <li class="breadcrumb-item active" aria-current="page">Vehicle Info</li>
            </ol>
          </div>

          <!-- Row -->
          <div class="row">   
            <!-- DataTable with Hover -->
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold" style="color: #4B5320;">Vehicle</h6>
                </div>
                <div class="text-center mb-4">
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addVehicleModal">
                  Add New Vehicle
                  </button>
</              div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>Serial</th>
                        <th>BA No</th>
                        <th>Name</th>
                        <th>Model</th>
                        <th>Status</th>
                        <th>Class</th>
                        <th>Vehicle Code</th>
                        <th>Unit</th>
                        <th>KM Reading</th>
                        <th>KPL</th>
                        <th>Availability</th>
                        <th>Driver ID</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Serial</th>
                        <th>BA No</th>
                        <th>Name</th>
                        <th>Model</th>
                        <th>Status</th>
                        <th>Class</th>
                        <th>Vehicle Code</th>
                        <th>Unit</th>
                        <th>KM Reading</th>
                        <th>KPL</th>
                        <th>Availability</th>
                        <th>Driver ID</th>
                        <th>Action</th>
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

          <!-- Add Vehicle Modal -->
          <div class="modal fade" id="addVehicleModal" tabindex="-1" role="dialog" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVehicleModalLabel">Add New Vehicle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form fields for vehicle details -->
                    <div class="form-group">
                        <label for="baNo">BA No</label>
                        <input type="text" class="form-control" id="baNo" name="baNo" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" class="form-control" id="model" name="model" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" class="form-control" id="status" name="status" required>
                    </div>
                    <div class="form-group">
                        <label for="class">Class</label>
                        <input type="text" class="form-control" id="class" name="class" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicleCode">Vehicle Code</label>
                        <input type="text" class="form-control" id="vehicleCode" name="vehicleCode" required>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <input type="text" class="form-control" id="unit" name="unit" required>
                    </div>
                    <div class="form-group">
                        <label for="runningKMR">KM Reading</label>
                        <input type="text" class="form-control" id="KM_Reading" name="KM_Reading" required>
                    </div>
                    <div class="form-group">
                        <label for="kpl">KPL</label>
                        <input type="text" class="form-control" id="kpl" name="kpl" required>
                    </div>
                    <div class="form-group">
                        <label for="availability">Availability</label>
                        <input type="text" class="form-control" id="availability" name="availability" required>
                    </div>
                    <div class="form-group">
                        <label for="driverID">Driver ID</label>
                        <input type="text" class="form-control" id="driverID" name="driverID">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Update Vehicle Modal -->
<div class="modal fade" id="updateVehicleModal" tabindex="-1" role="dialog" aria-labelledby="updateVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateVehicleModalLabel">Update Vehicle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateVehicleForm" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="updateBaNo">BA No</label>
                        <input type="text" class="form-control" id="updateBaNo" name="updateBaNo" readonly>
                    </div>
                    <div class="form-group">
                        <label for="updateName">Name</label>
                        <input type="text" class="form-control" id="updateName" name="updateName" required>
                    </div>
                    <div class="form-group">
                        <label for="updateModel">Model</label>
                        <input type="text" class="form-control" id="updateModel" name="updateModel" required>
                    </div>
                    <div class="form-group">
                        <label for="updateStatus">Status</label>
                        <input type="text" class="form-control" id="updateStatus" name="updateStatus" required>
                    </div>
                    <div class="form-group">
                        <label for="updateClass">Class</label>
                        <input type="text" class="form-control" id="updateClass" name="updateClass" required>
                    </div>
                    <div class="form-group">
                        <label for="updateVehicleCode">Vehicle Code</label>
                        <input type="text" class="form-control" id="updateVehicleCode" name="updateVehicleCode" required>
                    </div>
                    <div class="form-group">
                        <label for="updateUnit">Unit</label>
                        <input type="text" class="form-control" id="updateUnit" name="updateUnit" required>
                    </div>
                    <div class="form-group">
                        <label for="updateRunningKMR">KM Reading</label>
                        <input type="text" class="form-control" id="updateKM_Reading" name="updateKM_Reading" required>
                    </div>
                    <div class="form-group">
                        <label for="updateKPL">KPL</label>
                        <input type="text" class="form-control" id="updateKPL" name="updateKPL" required>
                    </div>
                    <div class="form-group">
                        <label for="updateAvailability">Availability</label>
                        <input type="text" class="form-control" id="updateAvailability" name="updateAvailability" >
                    </div>
                    <div class="form-group">
                        <label for="updateDriverID">Driver ID</label>
                        <input type="text" class="form-control" id="updateDriverID" name="updateDriverID" >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Delete Modal -->
<div class="modal fade" id="deleteVehicleModal" tabindex="-1" role="dialog" aria-labelledby="deleteVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVehicleModalLabel">Delete Vehicle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteVehicleForm" method="post">
                <div class="modal-body">
                    <p>Are you sure you want to delete this vehicle?</p>
                    <p><strong>BA No:</strong> <span id="deleteBAnoDisplay"></span></p>
                    <p><strong>Name:</strong> <span id="deleteNameDisplay"></span></p>
                    <input type="hidden" id="deleteBANo" name="deleteBANo">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
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
function populateUpdateModal(vehicle) {
    document.getElementById('updateBaNo').value = vehicle.BANO;
    document.getElementById('updateName').value = vehicle.NAME;
    document.getElementById('updateModel').value = vehicle.MODEL;
    document.getElementById('updateStatus').value = vehicle.STATUS;
    document.getElementById('updateClass').value = vehicle.CLASS;
    document.getElementById('updateVehicleCode').value = vehicle.VEHICLECODE;
    document.getElementById('updateUnit').value = vehicle.UNIT;
    document.getElementById('updateKM_Reading').value = vehicle.KM_READING;
    document.getElementById('updateKPL').value = vehicle.KPL;
    document.getElementById('updateAvailability').value = vehicle.AVAILABILITY;
    document.getElementById('updateDriverID').value = vehicle.DRIVERID;
    $('#updateVehicleModal').modal('show');
}


    // jQuery code to handle modal display and setting values
    function populateDeleteModal(data) {
        // Assuming `data` is an object with BA No and Name
        var baNo = data.BANO;
        var name = data.NAME;

        // Set values in the modal
        document.getElementById('deleteBANo').value = baNo;
        document.getElementById('deleteBAnoDisplay').textContent = baNo;
        document.getElementById('deleteNameDisplay').textContent = name;
        
        // Show the modal
        $('#deleteVehicleModal').modal('show');
    }


</script>


</body>

</html>
