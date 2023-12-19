<?php

//load credentials
include("config.php");

// Set up API endpoint
$apiEndpoint = 'https://altquick.com/api/v1/ticker?market=BTC_CURE';

// Set up cURL options
$curlOptions = array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$apiKey
    )
);

// Send request to API
$ch = curl_init($apiEndpoint);
curl_setopt_array($ch, $curlOptions);
$response = curl_exec($ch);
curl_close($ch);

// Parse response to extract current market price
$decodedResponse = json_decode($response, true);
$currentPrice = $decodedResponse['last'];

// Display current market price
echo 'Current market price of CURE-BTC: '.$currentPrice.' BTC';
