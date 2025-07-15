<?php
require('connection.php');
//include 'session_check.php'; // Include session check script

// Check if user is logged in
//if (empty($_SESSION['username'])) {
 //   header('location:login.php');
//    exit;
//}

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




// Second row
// Query to get the count of drivers
$query = "SELECT 
  TO_CHAR(
    ( (SELECT COUNT(DISTINCT VehicleID) FROM DRIVER_VEHICLE_ROUTE) * 100.0 / 
      (SELECT COUNT(*) FROM VEHICLE)
    ), 'FM999990.00'
  ) || '%' AS UtiPer
FROM DUAL";
$result = oci_parse($conn, $query);
$r = oci_execute($result);

// Fetch the result
$row = oci_fetch_assoc($result); // Use $result here
$UtiPer = $row['UTIPER']; // Use uppercase for Oracle alias


// Query to get the count of distinct vehicles
$query = "SELECT 
  TO_CHAR(AVG(MaintenanceCount), 'FM999990.00') AS AvgMaint
FROM (
    SELECT VehicleID, COUNT(*) AS MaintenanceCount
    FROM MAINTENANCE
    GROUP BY VehicleID
)";
$result = oci_parse($conn, $query);
$r = oci_execute($result);

// Fetch the result
$row = oci_fetch_assoc($result); // Use $result here
$AvgMaint = $row['AVGMAINT']; // Use uppercase for Oracle alias

// Query to get the count of available drivers
$query = "SELECT COUNT(*) AS TotalRoutes FROM ROUTE";
$result = oci_parse($conn, $query);
$r = oci_execute($result);

// Fetch the result
$row = oci_fetch_assoc($result); // Use $result here
$TotalRoutes = $row['TOTALROUTES']; // Use uppercase for Oracle alias

// Query to get the count of available vehicles
$query = "SELECT 
    COUNT(DISTINCT DriverID) AS DriversWithAccidents
FROM 
    ACCIDENT";
$result = oci_parse($conn, $query);
$r = oci_execute($result);

// Fetch the result
$row = oci_fetch_assoc($result); // Use $result here
$DriversWithAccidents = $row['DRIVERSWITHACCIDENTS']; // Use uppercase for Oracle alias







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
$sql = "SELECT 
    d.DRIVERID,
    d.FIRST_NAME || ' ' || d.LAST_NAME AS DRIVER_NAME,
    d.RANK,
    d.AVAILABILITY,
    dv.VEHICLEID,
    r.ROUTENAME
FROM 
    driver d
LEFT JOIN 
    driver_vehicle_route dv ON d.DRIVERID = dv.DRIVERID
LEFT JOIN 
    route r ON dv.ROUTEID = r.ROUTEID
WHERE 
    r.ROUTENAME IS NOT NULL
ORDER BY 
    d.RANK, d.AVAILABILITY DESC, DRIVER_NAME"; // Replace YOUR_TABLE_NAME with your actual table name

$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

// Fetch the data
$dvrroute = [];
while ($row = oci_fetch_assoc($stmt)) {
    $dvrroute[] = $row;
}


// Close the statement
oci_free_statement($result);



// Sample SQL query
$sql = "SELECT BANO, VEHICLE_NAME, DRIVER_NAME, TOTAL_MAINTENANCE_COST FROM Vehicle_Maintenance_Cost"; // Replace YOUR_TABLE_NAME with your actual table name

$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

// Fetch the data
$vehicles = [];
while ($row = oci_fetch_assoc($stmt)) {
    $vehicles[] = $row;
}





// Close the statement



// Sample SQL query
$sql = "SELECT 
    Unit, 
    COUNT(*) AS Total_Vehicles
FROM 
    VEHICLE
GROUP BY 
    Unit"; // Replace YOUR_TABLE_NAME with your actual table name

$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

// Fetch the data
$univeh = [];
while ($row = oci_fetch_assoc($stmt)) {
    $univeh[] = $row;
}






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
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
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




           <!-- Pending Requests Card Example -->
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Driver With Accident Record</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $DriversWithAccidents; ?></div>
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
        <!-- On Road Vehicle -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-uppercase mb-1">Maintenance Rate</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $AvgMaint; ?></div>
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
        <!-- Employed Drivers -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-uppercase mb-1">Vehicle Utilization Parcentage</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $UtiPer; ?></div>
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
            <!-- New User Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Active Routes</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $TotalRoutes; ?></div>
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
           



          <div class="col-xl-8 col-lg-7 mb-4">
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Driver Assignment</h6>
       </div>
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>Driver ID</th>
                        <th>Driver Name</th>
                        <th>Rank</th>
                        <th>Vehicle ID</th>
                        <th>Route Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dvrroute as $dvrroute): ?>
                    <tr>
                        <td><a href="#"><?= htmlspecialchars($dvrroute['DRIVERID']) ?></a></td>
                        <td><?= htmlspecialchars($dvrroute['DRIVER_NAME']) ?></td>
                        <td><?= htmlspecialchars($dvrroute['RANK']) ?></td>
                        <td><?= htmlspecialchars($dvrroute['VEHICLEID']) ?></td>
                        <td><?= htmlspecialchars($dvrroute['ROUTENAME']) ?></td>
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
            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
    <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Available Vehicles</h6>
        </div>
        <div class="card-body">
            <?php
            // Array of vehicle types you want to display
            $vehicleTypes = [
                'Truck 1/4 Ton 4x4 Toyota Pickup',
                'Microbus',
                'Water Trailer',
                'Motor Cycle Runner turbo',
                'Truck 1/4 Ton 4x4 Toyota Jeep',
                'Truck 3 Ton 4X4 Arunima Bolyan'
            ];
            
            // Loop through each vehicle type and display the count
            foreach ($vehicleTypes as $type) {
                $count = isset($vehicleCounts[$type]) ? $vehicleCounts[$type] : 0;
                echo '<div class="mb-3">
                        <div class="small text-gray-500">' . htmlspecialchars($type) . '
                            <div class="small float-right"><b>' . $count . ' Available</b></div>
                        </div>
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: ' . ($count / 30 * 100) . '%" aria-valuenow="' . ($count / 800 * 100) . '"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>';
            }
            
            ?>
        </div>
        
    </div>
</div>

<!-- Invoice Example -->
<div class="col-xl-8 col-lg-7 mb-4">
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Max Maintenance Cost</h6>
       </div>
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>BANO</th>
                        <th>Vehicle Name</th>
                        <th>Driver Name</th>
                        <th>Total Maintenance Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td><a href="#"><?= htmlspecialchars($vehicle['BANO']) ?></a></td>
                        <td><?= htmlspecialchars($vehicle['VEHICLE_NAME']) ?></td>
                        <td><?= htmlspecialchars($vehicle['DRIVER_NAME']) ?></td>
                        <td><?= htmlspecialchars($vehicle['TOTAL_MAINTENANCE_COST']) ?></td>
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

            <!-- Message From Customer-->
            <div class="col-xl-4 col-lg-5 ">
            <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Vehicle Per Unit</h6>
       </div>
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>Unit</th>
                        <th>Total Vehicle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($univeh as $univeh): ?>
                    <tr>
                        <td><a href="#"><?= htmlspecialchars($univeh['UNIT']) ?></a></td>
                        <td><?= htmlspecialchars($univeh['TOTAL_VEHICLES']) ?></td>
       
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
          <!--Row-->

          

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
