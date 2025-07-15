<?php
    require('fpdf.php'); // Adjust the path as necessary

    require('connection.php');
    session_start();

    // Check if user is logged in
    if (empty($_SESSION['username'])) {
        header('location:login.php');
        exit;
    }

    $username = $_SESSION['username'];


  // Retrieve vehicles for display
  $sql = "SELECT * FROM vehicle";
  $stid = oci_parse($conn, $sql);
  oci_execute($stid);

// Ensure this snippet is included only when the form is submitted
if (isset($_POST['generatePDF'])) {
    // Include your database connection
    
    // Prepare your SQL query
    $sql = "SELECT BANO, NAME, MODEL, STATUS, CLASS, VEHICLECODE, UNIT, KM_READING, KPL, AVAILABILITY, DRIVERID FROM VEHICLE"; // Adjust the query as needed
    $stid = oci_parse($conn, $sql);
    oci_execute($stid);

    // Create PDF instance
    $pdf = new FPDF('L', 'mm', 'Legal'); // Landscape orientation, mm units, Legal size
    $pdf->AddPage();

    // Set total width for the table
    $totalWidth = 285; // Adjust this value as needed for legal size
    $customWidths = [12, 13, 55, 50, 15, 20, 30, 30, 30, 20, 30, 30]; // Ensure NAME column has more width

    // Header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($totalWidth, 10, 'Vehicle Report', 0, 1, 'C');
    $pdf->Ln(10); // Add space

    // Table Header
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell($customWidths[0], 10, 'Serial', 1);
    $pdf->Cell($customWidths[1], 10, 'BA No', 1);
    $pdf->Cell($customWidths[2], 10, 'Name', 1);
    $pdf->Cell($customWidths[3], 10, 'Model', 1);
    $pdf->Cell($customWidths[4], 10, 'Status', 1);
    $pdf->Cell($customWidths[5], 10, 'Class', 1);
    $pdf->Cell($customWidths[6], 10, 'Vehicle Code', 1);
    $pdf->Cell($customWidths[7], 10, 'Unit', 1);
    $pdf->Cell($customWidths[8], 10, 'KM Reading', 1);
    $pdf->Cell($customWidths[9], 10, 'KPL', 1);
    $pdf->Cell($customWidths[10], 10, 'Availability', 1);
    $pdf->Cell($customWidths[11], 10, 'Driver ID', 1);
    $pdf->Ln();

    // Add data from the database
    $pdf->SetFont('Arial', '', 10);
    $sl = 1;
    while ($row = oci_fetch_assoc($stid)) {
        $pdf->Cell($customWidths[0], 10, $sl, 1);
        $pdf->Cell($customWidths[1], 10, ($row['BANO'] !== null ? htmlentities($row['BANO'], ENT_QUOTES) : ''), 1);
        $pdf->Cell($customWidths[2], 10, ($row['NAME'] !== null ? htmlentities($row['NAME'], ENT_QUOTES) : ''), 1);
        $pdf->Cell($customWidths[3], 10, ($row['MODEL'] !== null ? htmlentities($row['MODEL'], ENT_QUOTES) : ''), 1);
        $pdf->Cell($customWidths[4], 10, ($row['STATUS'] !== null ? htmlentities($row['STATUS'], ENT_QUOTES) : ''), 1);
        $pdf->Cell($customWidths[5], 10, ($row['CLASS'] !== null ? htmlentities($row['CLASS'], ENT_QUOTES) : ''), 1);
        $pdf->Cell($customWidths[6], 10, ($row['VEHICLECODE'] !== null ? htmlentities($row['VEHICLECODE'], ENT_QUOTES) : ''), 1);
        $pdf->Cell($customWidths[7], 10, ($row['UNIT'] !== null ? htmlentities($row['UNIT'], ENT_QUOTES) : ''), 1);
        $pdf->Cell($customWidths[8], 10, ($row['KM_READING'] !== null ? htmlentities($row['KM_READING'], ENT_QUOTES) : ''), 1);
        $pdf->Cell($customWidths[9], 10, ($row['KPL'] !== null ? htmlentities($row['KPL'], ENT_QUOTES) : ''), 1);
        $pdf->Cell($customWidths[10], 10, ($row['AVAILABILITY'] !== null ? htmlentities($row['AVAILABILITY'], ENT_QUOTES) : ''), 1);
        $pdf->Cell($customWidths[11], 10, ($row['DRIVERID'] !== null ? htmlentities($row['DRIVERID'], ENT_QUOTES) : ''), 1);
        $pdf->Ln();
        $sl++;
    }

    // Clean up
    oci_free_statement($stid);
    oci_close($conn);

    // Output the PDF
    $pdf->Output('D', 'Vehicle_Report.pdf'); // Change 'D' to 'I' if you want to display in browser
    exit; // Ensure script stops after generating PDF
}


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
  <title>Generate Reports</title>
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
              <li class="breadcrumb-item">Generate Reports</li>
              <li class="breadcrumb-item active" aria-current="page">Vehicle Report</li>
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
                <!-- Add a form around the button -->
                <form method="post" action="vehicle_pdf2.php">
                    <button type="submit" name="generatePDF" class="btn btn-primary">Generate PDF Report</button>
                </form>

                </div>
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
        document.getElementById("generate-pdf").onclick = function() {
            window.location.href = 'vehicle_pdf2.php'; // Adjust the path if necessary
        };
    </script>

</body>

</html>
