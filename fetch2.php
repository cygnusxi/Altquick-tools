<?php

// Set up API endpoint and parameters
$endpoint = 'https://altquick.com/api/v1/ticker/24hr';
$params = [
    'market' => 'BTC_CURE',
];

// Initialize cURL session
$curl = curl_init();

// Set cURL options
curl_setopt_array($curl, [
    CURLOPT_URL => $endpoint . '?' . http_build_query($params),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false, // not recommended in production
]);

// Execute cURL request and get response
$response = curl_exec($curl);

// Close cURL session
curl_close($curl);

// Decode JSON response
$data = json_decode($response, true);

// Check for errors
if (isset($data['success']) && $data['success'] !== true) {
    echo 'Error: ' . $data['message'];
    exit;
}

// Print out CURE price
echo 'CURE price: ' . $data['bidPrice'] . ' BTC';

?>
