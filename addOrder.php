<?php

include './db.php';
include './functions.php';

function get_api_services_by_ids($ids) {
    $api = new Api();
    $api_services = $api->services(); 
    $matched_services = []; 

    foreach($api_services as $service) {
        $service_id = $service->service;

        if(in_array($service_id, $ids)) {
            $matched_services[] = $service; 
        }
    }

    return $matched_services; 
}

function get_our_single_service_by_id($id,$conn){

    $servicesResult = $conn->query("SELECT * FROM services WHERE service_id = '$id'");
    $allServices = array();
    if ($servicesResult->num_rows >0) {
        while( $servicesRow = $servicesResult->fetch_assoc() ){
            array_push($allServices, $servicesRow);
        }
    }

  return $allServices[0];

}

function addOrderThruApi($apiId,$lnk,$qty){

    $api = new Api();
    $apiResponse = $api->order([
                       'service' => $apiId, 
                       'link' => $lnk, 
                       'quantity' => $qty
                       ]);

    // $apiResponse ='{"order": 23501}';  
  // Example response from api After calling the order() function from the api functions

    $orderId = $apiResponse->order;

    return $orderId;
}
function addOrderToTable($user, $oApiId, $oLink, $oServiceId, $oQty, $oCharge, $oStatus, $conn) {
    // Prepare an insert statement
    $sql = "INSERT INTO orders (order_user_id, order_api_id, order_link, order_service_id, order_qty, order_charge, order_status) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("iisisds", $user, $oApiId, $oLink, $oServiceId, $oQty, $oCharge, $oStatus);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
          return true;
        } 
        else {
          return false;
        }  
    } 
    else {
      return false;
    }
}

function updateBalance($userId, $newBalance, $conn) {
    // Prepare an SQL statement to update the user's balance
    $sql = "UPDATE user SET user_balance = ? WHERE user_id = ?";

    // Prepare the statement with the database connection
    if ($stmt = $conn->prepare($sql)) {
        // Bind the new balance and user ID to the prepared statement
        $stmt->bind_param("di", $newBalance, $userId);

        // Execute the statement
        if ($stmt->execute()) {
            // Check if any rows were updated
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            // Handle errors during statement execution
            return false;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // Handle errors during statement preparation
        return false;
    }
}



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

$serviceId = $_POST['serviceId']; 

// Data needed to call api
$pageLnk = $_POST['pageLnk'];  
$followQty = $_POST['followQty']; 
$serviceApiId = $_POST['serviceApiId'];  
//end


$apiService = get_api_services_by_ids(array($serviceApiId)); 
$serviceRate = ($apiService[0]->rate)/1000; 

$ourSingleService = get_our_single_service_by_id($serviceId,$conn); 
$rate_percent = ($ourSingleService['service_rate_percentage'])/100; 
$ourServiceId = $ourSingleService['service_id']; 

$charge = $serviceRate + ($serviceRate*$rate_percent); 
$totalCharge = $charge * $followQty; 





if($balance >= $totalCharge){
  // Order through Api
  $orderApiId = addOrderThruApi($serviceApiId,$pageLnk,$followQty);

  $orderDefaultStatus = "Pending";
  // Save data to order table
  $orderAddedtoTable = addOrderToTable($userid,$orderApiId,$pageLnk,$ourServiceId,$followQty,$totalCharge,$orderDefaultStatus,$conn);

  // Update Balance
  $newBalance = $balance - $totalCharge;
  $newBalance = round(floor($newBalance * 100) / 100, 2);
  
  $balanceUpdated = updateBalance($userid,$newBalance,$conn);
  
  if($orderAddedtoTable && $balanceUpdated){
    echo 1;
  }
  else{
    echo 0;
  }


}
else{
  echo 2; //Insufficient Balance
}


?>