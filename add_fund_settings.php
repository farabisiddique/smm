<?php

header('Content-Type: application/json');
include './db.php'; 

$sSettingsResult = $conn->query("SELECT * FROM site_settings");

if ($sSettingsResult->num_rows >0) {
    while( $sSettingsRow = $sSettingsResult->fetch_assoc() ){
        $dollarRate = ceil($sSettingsRow['dollar_rate']);
    }
}


// this dollar rate will be taken from database everytime user use addfund and the value in database will be taken from app.exchangerate-api.com/dashboard in every night one time by cron jobs 
  
echo json_encode(['dollarRate' => $dollarRate]);

?>
