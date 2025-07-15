<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="nco_dashboard.php" style="background-color: #2d3c2c;">
      <div class="sidebar-brand-icon">
          <img src="img/logo/MVMS_logo.png">
        </div>
        <div class="sidebar-brand-text mx-3">MVMS</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item">
        <a class="nav-link" href="nco_dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Features
      </div>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap"
          aria-expanded="true" aria-controls="collapseBootstrap">
          <i class="far fa-fw fa-window-maximize"></i>
          <span>Vehicles</span>
        </a>
        <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Vehicle</h6>
            <a class="collapse-item" href="nco_vehicle.php">Vehicle Info</a>
            <a class="collapse-item" href="nco_maintenance.php">Maintenance</a>
            <a class="collapse-item active" href="nco_vdra.php">VDRA</a>
            
            
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseForm" aria-expanded="true"
          aria-controls="collapseForm">
          <i class="fab fa-fw fa-wpforms"></i>
          <span>Drivers</span>
        </a>
        <div id="collapseForm" class="collapse" aria-labelledby="headingForm" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Drivers</h6>
            <a class="collapse-item" href="nco_driver.php">Driver Info</a>
            <a class="collapse-item" href="nco_driver_vehicle_route.php">Assign Drivers</a>
          </div>
        </div>
      </li>
     
      <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
          aria-controls="collapseTable">
          <i class="fas fa-fw fa-table"></i>
          <span>POL</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingForm" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">POL</h6>
            <a class="collapse-item" href="nco_pol.php">POL</a>
            <a class="collapse-item active" href="nco_pol_issue.php">POL Issue</a>
            
          </div>
        </div>
      </li>
      <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
          aria-controls="collapseTable">
          <i class="fas fa-fw fa-table"></i>
          <span>Generate Report</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingForm" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Reports</h6>
            <a class="collapse-item" href="nco_vehicle_pdf2.php">Vehicle Report</a>
            
          </div>
        </div>
      </li>
      
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
      </div>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePage" aria-expanded="true"
          aria-controls="collapsePage">
          <i class="fas fa-fw fa-columns"></i>
          <span>Miscellaneous</span>
        </a>
        <div id="collapsePage" class="collapse" aria-labelledby="headingPage" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
        

          
            <a class="collapse-item" href="nco_route.php">Route</a>
            
            <a class="collapse-item" href="nco_accident.php">Accident</a>

          </div>
        
        </div>
        
      </li>
      
      
      <hr class="sidebar-divider">
      <div class="version" id="version-ruangadmin"></div>
    </ul>