<?php
// Database credentials
$host = "switchyard.proxy.rlwy.net"; // Host from Railway
$port = 34268; // Port from Railway
$username = "root"; // Username from Railway
$password = "rvhsPHvUWTgEQJWZvbHnDxsIuzoLkPKh"; // Password from Railway
$database = "railway"; // Database name from Railway

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
