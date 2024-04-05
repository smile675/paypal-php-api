<?php

require_once './config/database.php';
require_once './global_functions/GlobalFunctions.php';
require_once './secret.php';
require_once './core/get_access_token.php';
require_once './core/order.php';

$client_id_sandbox = SandboxId::$CLIENT_ID;
$client_secret_sandbox = SandboxId::$CLIENT_SECRET;

$client_id_live = LiveId::$CLIENT_ID;
$client_secret_live = LiveId::$CLIENT_SECRET;

$rootUrl_sandbox = RootUrl::$SANDBOX;
$rootUrl_live = RootUrl::$LIVE;


//initial setup for mode sandbox
$client_id = $client_id_sandbox;
$client_secret = $client_secret_sandbox;
$root_url = $rootUrl_sandbox;

// //initial setup for mode live
// $client_id = $client_id_live;
// $client_secret = $client_secret_live;
// $root_url = $rootUrl_live;

$database = new Database();
$db = $database->getConnection();

$member = new GlobalFunctions($db);

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;

// Step 1: Get authorized token
$tokenMember = new AccessToken();
$accessToken = $tokenMember->getAccessToken(
    $client_id,
    $client_secret,
    $root_url
);
if (!$accessToken) {
    return;
}

// // step 2: craete order
$orderMember = new Order();

$order = $orderMember->createOrderforSubscribe(
    $accessToken,
    $root_url
);

if (!$order) {
    return;
}

echo json_encode(array(
    "order" => $order
));


// step 3: insert data

// $insertrecord = $member->insertRecord($email, "01234");
// if (!$insertrecord) {
//     http_response_code(500);
//     echo json_encode(array(
//         "code" => 500,
//         "error" => "could not insert data"
//     ));
//     return;
// }
