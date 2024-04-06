<?php

require_once './global_functions/GlobalFunctions.php';
require_once './config/database.php';


// Retrieve the IPN notification from PayPal and store it
$raw_post_data = file_get_contents('php://input');

$webhookData = json_decode($raw_post_data, true);

// Extract order ID
$orderId = $webhookData['resource']['id'];

if (!isset($orderId)) {
    http_response_code(500);
    return;
}



// Extract payment status
$paymentStatus = $webhookData['resource']['status'];


if (!isset($paymentStatus)) {
    http_response_code(500);
    return;
}

$database = new Database();
$db = $database->getConnection();
$member = new GlobalFunctions($db);

if ($paymentStatus == "APPROVED") {
    $member->updateRecord($orderId);
} else {
    http_response_code(400);
    echo "payment not approved: status: $paymentStatus";
}



// 
