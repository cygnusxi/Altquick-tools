<?php

// Set the URL of the API endpoint
$url = 'https://altquick.com/api/v1/ticker/24hr';

// Set the HTTP method to GET
$method = 'GET';

// Set the HTTP headers
$headers = array(
    'Content-Type: application/json',
    //'Authorization: Bearer YOUR_API_KEY'
);

// Make the request
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Execute the request
$response = curl_exec($curl);

// Check for errors
if (curl_errno($curl)) {
    echo 'Error: ' . curl_error($curl);
    exit;
}

// Decode the JSON response
$data = json_decode($response, true);

// Print the market info
if (isset($data['BTC_CURE'])) {
    echo 'Market Name: ' . $data['BTC_CURE']['name'] . PHP_EOL;
    echo 'Base Currency: ' . $data['BTC_CURE']['base_currency'] . PHP_EOL;
    echo 'Quote Currency: ' . $data['BTC_CURE']['quote_currency'] . PHP_EOL;
    echo 'Price: ' . $data['BTC_CURE']['price'] . PHP_EOL;
    echo '24h Volume: ' . $data['BTC_CURE']['volume_24h'] . PHP_EOL;
    echo '24h High: ' . $data['BTC_CURE']['high_24h'] . PHP_EOL;
    echo '24h Low: ' . $data['BTC_CURE']['low_24h'] . PHP_EOL;
} else {
    echo 'Market not found' . PHP_EOL;
}

// Close the cURL object
curl_close($curl);

?>