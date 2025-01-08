<?php
// include './env.php';
include __DIR__ . '/./env.php';
$servername = HOSTNAME;
$dbusername = DB_USER;
$dbpassword = DB_PASS; 
$dbname = DB_NAME;
global $conn;
// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

