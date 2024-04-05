<?php

require_once './config/database.php';
require_once './global_functions/GlobalFunctions.php';
require_once './secret.php';

$client_id_sandbox = SandboxId::$CLIENT_ID;
$client_secret_sandbox = SandboxId::$CLIENT_SECRET;

$client_id_live = LiveId::$CLIENT_ID;
$client_secret_live = LiveId::$CLIENT_SECRET;

$rootUrl_sandbox = RootUrl::$SANDBOX;
$rootUrl_live = RootUrl::$LIVE;

$database = new Database();
$db = $database->getConnection();

$member = new GlobalFunctions($db);

// $data = json_decode(file_get_contents("php://input"));


// step 1 : get authorised token
// Step 1: Get authorized token
$authUrl = $rootUrl_sandbox . "/v1/oauth2/token";
$authCredentials = base64_encode($client_id_sandbox . ":" . $client_secret_sandbox);
$authHeaders = [
    "Content-Type: application/x-www-form-urlencoded",
    "Authorization: Basic " . $authCredentials
];
$authData = "grant_type=client_credentials";

$authCurl = curl_init();
curl_setopt($authCurl, CURLOPT_URL, $authUrl);
curl_setopt($authCurl, CURLOPT_POST, true);
curl_setopt($authCurl, CURLOPT_POSTFIELDS, $authData);
curl_setopt($authCurl, CURLOPT_HTTPHEADER, $authHeaders);
curl_setopt($authCurl, CURLOPT_RETURNTRANSFER, true);
$authResult = curl_exec($authCurl);
curl_close($authCurl);

// Check if the authorization was successful
$authData = json_decode($authResult, true);
if (isset($authData['access_token'])) {
    $access_token = $authData['access_token'];

    // Now you can use $access_token in your subsequent API calls
    // For example, to make a payment request
    // Step 2: Make a payment request
    $paymentUrl = $rootUrl_sandbox . "/v2/payments/payment";
    $paymentHeaders = [
        "Content-Type: application/json",
        "Authorization: Bearer " . $access_token
    ];
    $paymentData = [
        "intent" => "sale",
        "payer" => [
            "payment_method" => "paypal"
        ],
        "transactions" => [
            [
                "amount" => [
                    "total" => "10.00",
                    "currency" => "USD"
                ]
            ]
        ],
        "redirect_urls" => [
            "return_url" => "https://example.com/success",
            "cancel_url" => "https://example.com/cancel"
        ]
    ];

    $paymentCurl = curl_init();
    curl_setopt($paymentCurl, CURLOPT_URL, $paymentUrl);
    curl_setopt($paymentCurl, CURLOPT_POST, true);
    curl_setopt($paymentCurl, CURLOPT_HTTPHEADER, $paymentHeaders);
    curl_setopt($paymentCurl, CURLOPT_POSTFIELDS, json_encode($paymentData));
    curl_setopt($paymentCurl, CURLOPT_RETURNTRANSFER, true);
    $paymentResult = curl_exec($paymentCurl);
    curl_close($paymentCurl);

    // Handle the payment response
    $paymentData = json_decode($paymentResult, true);
    // Process the payment response as needed
    var_dump($paymentData);
} else {
    // Authorization failed
    echo "Authorization failed.";
}
