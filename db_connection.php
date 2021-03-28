<?php
    $db_host = "localhost";
    $db_username = "id12914471_sanmeron";
    $db_password = "sanmerondb";
    $db_name = "id12914471_sanmerondb";

    $connection = mysqli_connect($db_host,
                                $db_username,
                                $db_password,
                                $db_name);

    if (mysqli_connect_errno() != 0) {
        echo "Error in connection!";
        exit();
    }
?>
