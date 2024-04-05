<?php

require_once './config/database.php';
require_once './global_functions/GlobalFunctions.php';
require_once './secret.php';
require_once './core/get_access_token.php';

$client_id_sandbox = SandboxId::$CLIENT_ID;
$client_secret_sandbox = SandboxId::$CLIENT_SECRET;

$client_id_live = LiveId::$CLIENT_ID;
$client_secret_live = LiveId::$CLIENT_SECRET;

$rootUrl_sandbox = RootUrl::$SANDBOX;
$rootUrl_live = RootUrl::$LIVE;

$database = new Database();
$db = $database->getConnection();

$member = new GlobalFunctions($db);

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;

// Step 1: Get authorized token
$token = new AccessToken();
$accessToken = $token->getAccessToken(
    $client_id_sandbox,
    $client_secret_sandbox,
    $rootUrl_sandbox
);
if (!$accessToken) {
    return;
}

// // step 2: insert record in database
// $insertrecord = $member->insertRecord($email, "01234");
// if (!$insertrecord) {
//     http_response_code(500);
//     echo json_encode(array(
//         "code" => 500,
//         "error" => "could not insert data"
//     ));
//     return;
// }

// step 3: execute paymnet using accesstoken
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
