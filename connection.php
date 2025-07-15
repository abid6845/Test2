<?php
                $username = 'SYSTEM';
                $password = '12345';
                $connection_string = 'DESKTOP-VLJ8HV5:1522/XE';

                $conn = oci_connect($username, $password, $connection_string);

                if (!$conn) {
                    $m = oci_error();
                    echo "<p>Connection failed: " . $m['message'] . "</p>";
                    exit;
                }

                
?>