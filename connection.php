<?php

// Host Name
$dbhost = 'localhost';

// Database Name
$dbname = 'inventory_system';

// Database Username
$dbuser = 'root';

// Database Password
$dbpass = '';

$connect = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
if(!$connect)
{
    echo("Failed to connect database.");
}


?>
