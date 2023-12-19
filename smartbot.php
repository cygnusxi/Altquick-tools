<?php

//load credentials
include("config.php"); 

function getPrice(){
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
//$PriceC = $data['bidPrice'];
echo 'CURE price: ' . $data['bidPrice'] . ' BTC';
echo '
';
return $data['bidPrice'];
}



function placeMarketOrder() {
    global $apiKey, $apiSecret;

	// Call the getprice function to retrieve the bid price
	$price = getprice();

    // Set the API endpoint
    $url = 'https://altquick.com/api/v1/order';

    // Generate a nonce
    $nonce = time();

    // Set the request parameters
    $params = array(
        'market' => 'BTC_CURE',
        'side' => 'buy',
        'type' => 'limit',
		'price' => $price ,
        'quantity' => mt_rand(2, 10)
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
	getPrice();
	placeMarketOrder();
    sleep(rand(1200, 2400)); // Sleep for a random number of seconds between 300 and 600 (5 to 10 minutes)
}
