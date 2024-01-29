<?php
include './functions.php';

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

// Example usage with your data structure
$data = [/* Your array of objects here */];






$api = new Api();

$services = $api->services(); # Return all services

echo arrayToHtmlTable($services);
?>