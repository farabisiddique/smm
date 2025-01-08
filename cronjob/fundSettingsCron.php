<?php

include __DIR__ . '/../envAPI.php';
include __DIR__ . '/../db.php';

$url = 'https://v6.exchangerate-api.com/v6/'.DOLLAR_API_KEY.'/pair/USD/BDT';

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);

$response = curl_exec($curl);

curl_close($curl);

$data = json_decode($response, true);


if ($data && $data['result'] == 'success') {
    $conversionRate = ceil($data['conversion_rate']);
    $sql = "UPDATE site_settings SET dollar_rate = ? WHERE setting_id = 1";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("d", $conversionRate); 

    if ($stmt->execute()) {
        echo "Dollar rate updated successfully.";
    } 

} 

?>
