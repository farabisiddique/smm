<?php
session_start();
$path = realpath('./env.php');
include $path;

$order_user_id = $_SESSION['user_id'];

$table ='orders';

$joinQuery = [
    "JOIN services ON orders.order_service_id = services.service_id",
    "JOIN order_status ON orders.order_status_id = order_status.status_id"
];



$primaryKey = 'order_id';

// Define the columns you want to display
$columns = array(
    array( 'db' => 'order_id',     
       'dt' => 1,
       'formatter' => function( $d, $row ) {
          return "MBD".$d;

      } 
    ),
    array( 'db' => 'order_created_at',     
       'dt' => 2,
       'formatter' => function( $d, $row ) {
          return date('d/m/Y', strtotime($d));

      } 
    ),
    array( 'db' => 'order_link',     
       'dt' => 3,
       'formatter' => function( $d, $row ) {
          return "<a href='".$d."' target='_blank'>".$d."</a>";

      } 
    ),
    // array( 'db' => 'order_link', 'dt' => 3 ),
    array( 'db' => 'service_name', 'dt' => 4 ),
    array( 'db' => 'order_qty', 'dt' => 5 ),
    array( 'db' => 'order_charge', 'dt' => 6 ),
    array( 'db' => 'status_name', 'dt' => 7 )
    
);

$sql_details = array(
    'user' => DB_USER,
    'pass' => DB_PASS,
    'db'   => DB_NAME,
    'host' => HOSTNAME
);

require('ssp.class.php');

$whereAll = "order_user_id='$order_user_id'";

$result = SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $whereAll,$joinQuery);


$dtColumnNo = 0; // Adjust based on which column you want the row number to appear in
$start = $_REQUEST['start'] + 1;

foreach ($result['data'] as &$res) {
    $res[$dtColumnNo] = "#" . $start;
    $start++;
}

echo json_encode($result);

?>
