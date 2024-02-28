<?php

include './db.php'; // Your database connection file
session_start();
$userid = $_SESSION['user_id']; 

$paymntMthd = $_POST['paymntMthd'];  
$fndamntBDT = $_POST['fndamntBDT']; 
$senderNo = $_POST['senderNo']; 







?>