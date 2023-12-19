<?php

//load credentials
include("config.php");

function placeMarketOrder() {
    global $apiKey, $apiSecret;

    // Set the API endpoint
    $url = 'https://altquick.com/api/v1/order';

    // Generate a nonce
    $nonce = time();

    // Set the request parameters
    $params = array(
        'market' => 'BTC_CURE',
        'side' => 'sell',
        'type' => 'limit',
		'price' => 0.00000019 ,
        'quantity' => mt_rand(2, 6)
    );

    // Sort the parameters alphabetically
    //ksort($params);

    // Build the query string
    $queryString = http_build_query($params);

    // Generate the signature
    $signature = hash_hmac('sha256', $queryString, $apiSecret);

    // Append the signature to the query string
    $queryString .= '&signature=' . $signature;

	echo $queryString;
	echo '
	';
	$url .= "?" .  $queryString;
    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-MBX-APIKEY: ' . $apiKey
    ));

    // Execute the request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        // Output the response
        echo 'POST Response: ' . $response;
    }
	echo '
	';
    // Close cURL session
    curl_close($ch);
}

while (true) {
    placeMarketOrder();
    sleep(rand(1200, 2400)); // Sleep for a random number of seconds between 1200 and 2400 (20 to 40 minutes)
}
