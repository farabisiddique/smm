<?php
$servername = "sql6.freemysqlhosting.net";
$username = "sql6680492";
$password = "vAc4xhWqgp";
$dbname = "sql6680492";
global $conn;
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

