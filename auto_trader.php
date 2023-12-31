<?php

//load credentials
include("config.php"); 

function placeMarketOrder($side, $price) {
    global $apiKey, $apiSecret, $totaltrades, $totalQuantity;

    // Set the API endpoint
    $url = 'https://altquick.com/api/v1/order';

    // Generate a nonce
    $nonce = time();

    // Set the request parameters
    $params = array(
        'market' => 'BTC_CURE',
        'side' => $side,
        'type' => 'limit',
		'price' => $price,
        'quantity' => mt_rand(2, 6)
    );

 
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
	
	// Extract the quantity value
    $quantity = json_decode($response, true)['quantity'];

    // Add the quantity value to the $totalQuantity variable
    $totalQuantity += $quantity;
	
	//caculate total btc sats spent
	//$totalSats = (float) ($totalQuantity * $price);
    //$totalSatsf = (float) $totalSats;
	
    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        // Output the response
		//display improvements
		system('clear');
	echo '***********************************************' . PHP_EOL;
	echo '******* Welcome to CURE Altquick Tools  *******' . PHP_EOL;
	echo '***********************************************' . PHP_EOL;
	echo 'Executing ' . $side . ' continuously           ' . PHP_EOL;
	echo 'Total Trades = ' . $totaltrades . '            ' . PHP_EOL;
	echo 'Total Coins = ' . $totalQuantity . '           ' . PHP_EOL;
	//echo 'Total BTC Cost = ' . $totalSats . '    ' . PHP_EOL;
	echo '                                               ' . PHP_EOL;
        echo 'Last Trade Details: ' . $response;
    }
	echo '
	';
    // Close cURL session
    curl_close($ch);
}

	///super fancy CLI FX
	system('clear');
	echo '***********************************************' . PHP_EOL;
	echo '******* Welcome to CURE Altquick Tools  *******' . PHP_EOL;
	echo '***********************************************' . PHP_EOL;

	// Prompt the user for input
	$side = readline("Enter 'buy' or 'sell' to indicate your trade side: ");
	$x = readline("Enter the order price in satoshis: ");
	//$y = readline("Enter the minimum coins per order: ");
	//$z = readline("Enter the maximum coins per order: ");

	// Convert the input values to the appropriate data types
	$x = (float) $x;
	//$y = (int) $y;
	//$z = (int) $z;

	//assign final var values
	$totalSats = 0;
	$totalQuantity = 0;
	$totaltrades = 0;
	$price = $x;
	//$min = $y;
	//$max = $z;
	//$quantity = mt_rand($min, $max)
	
	// Validate the trade side input
if ($side !== 'buy' && $side !== 'sell') {
    echo "Invalid trade side. Please enter 'buy' or 'sell'." . PHP_EOL;
    exit;
}

while (true) {
	$totaltrades++;
    placeMarketOrder($side, $price);
    sleep(rand(1200, 3600)); // Sleep for a random number of seconds between 1200 and 3600 (20 to 60 minutes)
}
