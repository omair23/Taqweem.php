<?php
$servername = "localhost";
$username = "rapidsof_taqweem";
$password = "M@sj1d-T";
$dbname = "rapidsof_taqweem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
