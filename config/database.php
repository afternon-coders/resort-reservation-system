<?php

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "resort_reservation_db";
    $conn = "";


    $conn = mysqli_connect($db_server,
                            $db_user,
                            $db_pass,
                            $db_name);

    // Do not echo connection status here so this file can be safely included
    // by helper libraries. On failure, write to error log for diagnosis.
    if (!$conn) {
        error_log('Database connection failed: ' . mysqli_connect_error());
    }

?>