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
        'side' => 'buy',
        'type' => 'market',
        'quantity' => '1',
        'timestamp' => $nonce
    );

    // Sort the parameters alphabetically
    //ksort($params);

    // Build the query string
    $queryString = http_build_query($params);

    // Generate the signature
    $signature = hash_hmac('sha256', $queryString, $apiSecret);

    // Append the signature and API key to the query string
    $queryString .= '&signature=' . $signature;

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url . '?' . $queryString);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);

    // Create a temporary file to capture the verbose output
    $verboseFile = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verboseFile);

    // Execute the request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        // Output the verbose request
        rewind($verboseFile);
        $verboseOutput = stream_get_contents($verboseFile);
        echo 'Outgoing POST Request:' . PHP_EOL;
        echo $verboseOutput . PHP_EOL;

        // Output the response
        echo 'POST Response: ' . $response;
    }

    // Close the verbose file and cURL session
    fclose($verboseFile);
    curl_close($ch);
}

while (true) {
    placeMarketOrder();
    sleep(rand(300, 600)); // Sleep for a random number of seconds between 300 and 600 (5 to 10 minutes)
}