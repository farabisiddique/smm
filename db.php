<?php
$servername = "sql6.freemysqlhosting.net";
$dbusername = "sql6680492";
$dbpassword = "vAc4xhWqgp";
$dbname = "sql6680492";
global $conn;
// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

