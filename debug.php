<?php
include './functions.php';
include './db.php';

function beautifulVarDump($var, $indent = 0) {
    $indentation = str_repeat('&nbsp;', $indent * 4);
    $type = gettype($var);

    echo $indentation . '<span style="color: blue;">' . $type . '</span>';

    if ($type == 'array' || $type == 'object') {
        echo ' (<span style="color: green;">' . count((array)$var) . '</span>)<br>';
        foreach ((array)$var as $key => $value) {
            echo $indentation . '&nbsp;&nbsp;&nbsp;&nbsp;<strong>' . $key . '</strong>: ';
            beautifulVarDump($value, $indent + 1);
        }
    } else {
        echo ' <span style="color: red;">' . htmlspecialchars(print_r($var, true)) . '</span><br>';
    }
}

function arrayToHtmlTable($data) {
    if (empty($data) || !is_array($data)) {
        return '<p>No data available or data format is incorrect.</p>';
    }

    // Start the table
    $html = '<table border="1">';

    // Check if the first element is an object and get its properties for the header
    if (is_object($data[0])) {
        $html .= '<tr>';
        foreach ($data[0] as $key => $value) {
            $html .= '<th>' . htmlspecialchars((string)$key) . '</th>';
        }
        $html .= '</tr>';
    } else {
        return '<p>Data format is not as expected. Expected an array of objects.</p>';
    }

    // Data rows
    foreach ($data as $object) {
        if (!is_object($object)) {
            continue; // Skip if not an object
        }
        $html .= '<tr>';
        foreach ($object as $property => $value) {
            // Handle boolean values explicitly
            if (is_bool($value)) {
                $value = $value ? 'True' : 'False';
            }
            $html .= '<td>' . htmlspecialchars((string)$value) . '</td>';
        }
        $html .= '</tr>';
    }

    // Close the table
    $html .= '</table>';

    return $html;
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

function checkStatusFromApi($orderId){
  $api = new Api(); 

  $orderStatusResponseFromApi = $api->status($orderId);

  $orderStatus = $orderStatusResponseFromApi->status;

  return $orderStatus;

}



$serviceApiId = 1730;
$pageLnk = 'https://www.facebook.com/mayaclothingbd?mibextid=ZbWKwL';
$followQty = 100;



$orderApiId = addOrderThruApi($serviceApiId,$pageLnk,$followQty);

// $orderApiId = 1529397;
// $orderCurrentStatus = checkStatusFromApi($orderApiId);



var_dump($orderApiId);



?>