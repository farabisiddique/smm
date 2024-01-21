<?php
// Include the functions.php file
require_once 'functions.php';

// Create an instance of the Api class
$api = new Api();

// Call the balance() function
$balance = $api->balance();

// Now you can use the $balance variable in your index.php
echo 'Your balance is: ' . $balance->balance;
?>
