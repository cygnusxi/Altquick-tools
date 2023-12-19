<?php

//load credentials
include("config.php");

function placeMarketOrder($market, $side, $type, $price, $quantity) {
    global $apiKey, $apiSecret;

    // Set the API endpoint
    $url = 'https://altquick.com/api/v1/order';

    // Generate a nonce
    $nonce = time();

    // Set the request parameters
    $params = array(
        'market' => $market,
        'side' => $side,
        'type' => $type,
        'price' => $price,
        'quantity' => $quantity
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

// Prompt the user for input
$side = readline("Enter 'buy' or 'sell' to indicate your trade side: ");
$x = readline("Enter the quantity of coins per order: ");
$y = readline("Enter the starting price: ");
$z = readline("Enter the maximum price: ");

// Convert the input values to the appropriate data types
$x = (int) $x;
$y = (float) $y;
$z = (float) $z;

// Define the market parameters
$market = 'BTC_CURE';
$type = 'limit';

// Validate the trade side input
if ($side !== 'buy' && $side !== 'sell') {
    echo "Invalid trade side. Please enter 'buy' or 'sell'." . PHP_EOL;
    exit;
}

// Iterate through the price range and place market orders
for ($price = $y; $price <= $z; $price += 0.00000001) {
    placeMarketOrder($market, $side, $type, $price, $x);
}