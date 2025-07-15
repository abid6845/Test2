<?php
require('connection.php');
include 'session_check.php'; // Include session check script

// Check if user is logged in
if (empty($_SESSION['username'])) {
    header('location:login.php');
    exit;
}

$username = $_SESSION['username'];

// Query to get the count of drivers
$query = "SELECT COUNT(*) AS driver_count FROM driver_vehicle_route";
$result = oci_parse($conn, $query);
$r = oci_execute($result);

// Fetch the result
$row = oci_fetch_assoc($result); // Use $result here
$driver_count = $row['DRIVER_COUNT']; // Use uppercase for Oracle alias

// Query to get the count of distinct vehicles
$query = "SELECT COUNT(DISTINCT vehicleid) AS vehicle_count FROM driver_vehicle_route";
$result = oci_parse($conn, $query);
$r = oci_execute($result);

// Fetch the result
$row = oci_fetch_assoc($result); // Use $result here
$vehicle_count = $row['VEHICLE_COUNT']; // Use uppercase for Oracle alias

// Query to get the count of available drivers
$query = "SELECT COUNT(*) AS available_driver_count FROM driver WHERE availability = 'Available'";
$result = oci_parse($conn, $query);
$r = oci_execute($result);

// Fetch the result
$row = oci_fetch_assoc($result); // Use $result here
$available_driver_count = $row['AVAILABLE_DRIVER_COUNT']; // Use uppercase for Oracle alias

// Query to get the count of available vehicles
$query = "SELECT COUNT(*) AS available_vehicle_count FROM vehicle WHERE availability = 'Available'";
$result = oci_parse($conn, $query);
$r = oci_execute($result);

// Fetch the result
$row = oci_fetch_assoc($result); // Use $result here
$available_vehicle_count = $row['AVAILABLE_VEHICLE_COUNT']; // Use uppercase for Oracle alias




// Assume you have a connection to your database established
// Assume you have a connection to your database established
$query = "
    SELECT 
        Name, 
        COUNT(*) AS AvailableCount 
    FROM 
        Vehicle 
    WHERE 
        Availability = 'Available' 
    GROUP BY 
        Name 
    HAVING 
        Name IN ('Truck 1/4 Ton 4x4 Toyota Pickup', 
                  'Microbus', 
                  'Water Trailer', 
                  'Motor Cycle Runner turbo', 
                  'Truck 1/4 Ton 4x4 Toyota Jeep')";

$result = oci_parse($conn, $query);
oci_execute($result); // Execute the query

// Initialize an array to hold the counts
$vehicleCounts = [];

// Fetch the results
while ($row = oci_fetch_array($result, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $vehicleCounts[$row['NAME']] = $row['AVAILABLECOUNT']; // Store counts
}



// Sample SQL query
$sql = "
SELECT 'DriverID' AS Name, TO_CHAR(DriverID) AS Driver
FROM DRIVER
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username  -- Use a bind variable for security
)
UNION ALL
SELECT 'First_Name', First_Name
FROM DRIVER
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'Last_Name', Last_Name
FROM DRIVER
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'Rank', Rank
FROM DRIVER
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'Unit', Unit
FROM DRIVER
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'LicenseNumber', LicenseNumber
FROM DRIVER
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'Availability', Availability
FROM DRIVER
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username)";
; // Replace YOUR_TABLE_NAME with your actual table name

$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':username', $username);
oci_execute($stmt);

// Fetch the data
$vehicles = [];
while ($row = oci_fetch_assoc($stmt)) {
    $vehicles[] = $row;
}


// Assuming you have already connected to the database and stored the connection in $conn

// Prepare the SQL query to retrieve vehicle details based on the driver's DriverID
$sql = "
SELECT 
    'BANO' AS Name, BANO AS Value
FROM VEHICLE
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'Name', Name
FROM VEHICLE
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'Model', Model
FROM VEHICLE
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'Status', Status
FROM VEHICLE
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'Class', Class
FROM VEHICLE
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'VehicleCode', VehicleCode
FROM VEHICLE
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'Unit', Unit
FROM VEHICLE
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'KM_Reading', TO_CHAR(KM_Reading)  -- Convert number to char for consistency
FROM VEHICLE
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'KPL', TO_CHAR(KPL)  -- Convert number to char for consistency
FROM VEHICLE
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)
UNION ALL
SELECT 'Availability', Availability
FROM VEHICLE
WHERE DriverID = (
    SELECT DriverID
    FROM users
    WHERE Username = :username
)";

// Prepare and execute the SQL statement
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':username', $username);
oci_execute($stmt);

// Fetch the data
$veh = [];
while ($row = oci_fetch_assoc($stmt)) {
    $veh[] = $row;
}








// Close the statement
oci_free_statement($result);

// Close the connection
oci_close($conn);
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
  <title>MVMS</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include 'driver_sidebar.php'; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include 'topbar.php'; ?>
        <!-- Topbar -->

      <!-- Container Fluid-->
      <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="driver_dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- Employed Drivers -->
          <div class="col-xl-3 col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-uppercase mb-1">Employed Drivers</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $driver_count; ?></div>
                  <div class="mt-2 mb-0 text-muted text-xs">
                    <!-- Optional additional info can go here -->
                  </div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-calendar fa-2x text-primary"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- On Road Vehicle -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-uppercase mb-1">On Road Vehicle</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $vehicle_count; ?></div>
                  <div class="mt-2 mb-0 text-muted text-xs">
                    <!-- Optional additional info can go here -->
                  </div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-shopping-cart fa-2x text-success"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
            <!-- New User Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Available Drivers</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $available_driver_count; ?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- Optional additional info can go here -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
           <!-- Pending Requests Card Example -->
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Available Vehicle</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $available_vehicle_count; ?></div>
                    <div class="mt-2 mb-0 text-muted text-xs">
                      <!-- Additional information can go here -->
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-car fa-2x text-warning"></i> <!-- Changed icon for vehicles -->
                  </div>
                </div>
              </div>
            </div>
          </div>

<!-- Invoice Example -->
<div class="col-xl-6 col-lg-7 mb-4">
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Driver Info</h6>
        </div>
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Driver</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td><a href="#"><?= htmlspecialchars($vehicle['NAME']) ?></a></td>
                        <td><?= htmlspecialchars($vehicle['DRIVER']) ?></td>
                        
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer"></div>
    </div>
</div>

<?php
// Close the database connection
oci_close($conn);
?>

           <!-- Invoice Example -->
<div class="col-xl-6 col-lg-7 mb-4">
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Driver Info</h6>
        </div>
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Vehicle</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($vehicles)): ?>
                    <tr>
                        <td colspan="2" class="text-center">No vehicles assigned to this driver.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($veh as $veh): ?>
                        <tr>
                            <td><a href="#"><?= htmlspecialchars($veh['NAME']) ?></a></td>
                            <td><?= htmlspecialchars($veh['VALUE']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer"></div>
    </div>
</div>

<?php
// Close the database connection
oci_close($conn);
?>

<?php
// Close the database connection
oci_close($conn);
?>


         

          <!-- Modal Logout -->
          <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelLogout"
            aria-hidden="true">
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
                  <a href="login.php" class="btn btn-primary">Logout</a>
                </div>
              </div>
            </div>
          </div>


        <!---Container Fluid-->
 

        <!-- Container Fluid-->
     

          <!-- Row -->
          <div class="row">   
            <!-- DataTable with Hover -->
           
          </div>
          <!--Row-->

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

</body>

</html>
