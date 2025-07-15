<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('connection.php');
include 'session_check.php'; // Include session check script

if (empty($_SESSION['username'])) {
    header('location:login.php');
    exit;
}

if (isset($_POST['sqlQuery'])) {
    $sqlQuery = $_POST['sqlQuery'];

    // Check if the query is a SELECT statement
    if (stripos(trim($sqlQuery), 'SELECT') === 0) {
        $stmt = oci_parse($conn, $sqlQuery);
        oci_execute($stmt);

        echo "<table border='1'>";

        // Fetch the column names
        $numCols = oci_num_fields($stmt);
        echo "<tr>";
        for ($i = 1; $i <= $numCols; $i++) {
            $colName = oci_field_name($stmt, $i);
            echo "<th>" . htmlspecialchars($colName) . "</th>";
        }
        echo "</tr>";

        // Fetch the rows
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            echo "<tr>";
            foreach ($row as $item) {
                echo "<td>" . htmlspecialchars($item) . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";

        oci_free_statement($stmt);
    } else {
        echo "Error: Only SELECT queries are allowed.";
    }

    // Close the connection
    oci_close($conn);
} else {
    echo "Error: No SQL query provided.";
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="img/logo/MVMS_logo.png" rel="icon">
    <title>SQL Executor</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            height: 100px;
        }
        iframe {
            width: 100%;
            height: 300px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body id="page-top">
    <div class="container-fluid">
        <h1>SQL Executor</h1>
        <form action="execute_sql.php" method="POST" target="resultFrame">
            
            <label for="sqlQuery">Enter SQL Query:</label><br>
            <textarea id="sqlQuery" name="sqlQuery" required></textarea><br>
            <input type="submit" value="Execute SQL">
        </form>

        <iframe name="resultFrame"></iframe>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
