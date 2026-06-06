<?php
$host = 'localhost';
$port = '3307'; // MySQL is running on port 3307
$user = 'root';
$password = ''; // Leave empty if no password
$dbname = 'IMS';

$conn = new mysqli($host, $user, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
