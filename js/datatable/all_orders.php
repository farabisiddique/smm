<?php


$path = realpath('./env.php');

include  $path;  



// if(!@include("./../env.php")) throw new Exception("Failed to include 'script.php'"); 

// DB table to use
$table = 'orders';

// Table's primary key
$primaryKey = 'order_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
  // array( 'db' => 'order_id', 'dt' => 1 )
  // array( 'db' => 'order_created_at',   'dt' => 2 )
  // array( 'db' => 'order_link',     'dt' => 5 ),

  // array( 'db' => 'order_service_id',     'dt' => 10 ),
  // array( 'db' => 'order_qty',     'dt' => 11 ),
  // array( 'db' => 'order_charge',     'dt' => 12 ),
  // array(
  //   'db'        => 'total',
  //   'dt'        => 13,
  //   'formatter' => function( $d, $row ) {
  //     return '৳ '.number_format($d);
  //   }
  // ),
  // array(
  //   'db'        => 'order_status_id',
  //   'dt'        => 14,
  //   'formatter' => function( $d, $row ) {
  //     return date( 'l, jS M, y', strtotime($d));
  //   }
  // )


);



$sql_details = array(
  'user' => DB_USER,
  'pass' => DB_PASS,
  'db'   => DB_NAME,
  'host' => HOSTNAME
);



require( 'ssp.class.php' );


$result=SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns); 
var_dump($result);
die();
    $start=$_REQUEST['start']+1;
    $idx=0;
    foreach($result['data'] as &$res){
        $res[0]=(string)$start;
        $start++;
        $idx++;
    }

    echo json_encode($result);



