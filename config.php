<?php
// config.php
$db_host = 'db'; // In Docker, this is the service name of the DB
$db_user = 'root';
$db_pass = 'supergeheim';
$db_name = 'phples';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
