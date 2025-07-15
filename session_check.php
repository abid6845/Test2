<?php
session_start();

// Define expected role IDs for each page
$expectedRoleIds = [
    'dashboard.php' => 1,
    'accident.php' => 1,
    'vehicle.php' => 1,
    'maintenance.php' => 1,
    'vdra.php' => 1,
    'driver.php' => 1,
    'driver_vehicle_route.php' => 1,
    'pol.php' => 1,
    'pol_issue.php' => 1,
    'vehicle_pdf2.php' => 1,
    'role.php' => 1,
    'route.php' => 1,
    'users.php' => 1,
    'nco_dashboard.php' => 2,
    'nco_accident.php' => 2,
    'nco_vehicle.php' => 2,
    'nco_maintenance.php' => 2,
    'nco_vdra.php' => 2,
    'nco_driver.php' => 2,
    'nco_driver_vehicle_route.php' => 2,
    'nco_pol.php' => 2,
    'nco_pol_issue.php' => 2,
    'nco_vehicle_pdf2.php' => 2,
    'nco_route.php' => 2,
    'driver_dashboard.php' => 3,
    'driver_accident.php' => 3,
    'driver_maintenance.php' => 3,
    'driver_vdra.php' => 3
    

    
    
];

// Get the current script name
$currentScript = basename($_SERVER['PHP_SELF']);

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Check if the role ID is set
if (isset($_SESSION['roleid'])) {
    // Check if the current page requires a specific role ID
    if (array_key_exists($currentScript, $expectedRoleIds)) {
        $requiredRoleId = $expectedRoleIds[$currentScript];

        // Compare the role ID
        if ($_SESSION['roleid'] != $requiredRoleId) {
            // Role ID does not match; log out
            session_destroy(); // Destroy the session
            header("Location: login.php"); // Redirect to login page
            exit();
        }
    }
}


// If all checks pass, display the user's name
$userName = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'User';
?>
