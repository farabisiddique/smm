<?php

include './db.php';
include './functions.php';
session_start();
$userid = $_SESSION['user_id'];

$userQuery = $conn->prepare("SELECT * FROM user 
                            WHERE user_id = ? ");

$userQuery->bind_param("i", $userid);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult->num_rows == 1) {
    $userHere = $userResult->fetch_assoc();
    $balance = $userHere['user_balance'];
}




$totalOrderAmount = 133442.23;

if($balance >= $totalOrderAmount){
  echo 1;
}
else{
  echo 3; //Insufficient Balance
}
  

?>