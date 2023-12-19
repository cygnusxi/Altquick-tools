<?php

// Import the cURL library
//require_once('autoload.php');

// Create a new cURL handle
$ch = curl_init();

// Set the URL of the AltQuick API
curl_setopt($ch, CURLOPT_URL, 'https://altquick.com/api/v1/order');

// Set the HTTP method to POST
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

// Set the content type to application/json
curl_setopt($ch, CURLOPT_CONTENT_TYPE, 'application/json');

// Set the body of the request
$body = <<<JSON
{
  "market": "BTC_CURE",
  "side": "BUY",
  "type": "LIMIT",
  "price": 1,
  "quantity": 1
}
JSON;

// Set the request body
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

// Set the HTTP authentication header
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Authorization: Bearer '
));

// Execute the request
$response = curl_exec($ch);

// Check the HTTP status code
if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
  echo 'Error: ' . curl_error($ch);
  exit;
}

// Decode the JSON response
$data = json_decode($response, true);

// Check if the order was created successfully
if ($data['success'] === true) {
  echo 'Order created successfully!';
} else {
  echo 'Error creating order: ' . $data['error'];
}

// Close the cURL handle
curl_close($ch);

?>
