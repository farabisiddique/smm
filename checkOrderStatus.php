<?php
include './db.php';
include './functions.php';

function checkStatusFromApi($orderId){
  $api = new Api(); 

  $orderStatusResponseFromApi = $api->status($orderId);

  $orderStatus = $orderStatusResponseFromApi->status;

  return $orderStatus;

}
// Check if orderId is provided
if(isset($_POST['orderId'])) {
    $orderId = $_POST['orderId'];

    $stmt = $conn->prepare("SELECT order_api_id FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
   
    $orderArray = array();
    if ($result->num_rows >0) {
        while( $orderRow = $result->fetch_assoc() ){
            array_push($orderArray, $orderRow);
        }
    }
    $orderApiId = $orderArray[0]['order_api_id'];
    $orderCurrentStatus = checkStatusFromApi($orderApiId);
    echo $orderCurrentStatus;

} 

?>
