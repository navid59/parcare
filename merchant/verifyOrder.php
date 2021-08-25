<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('classes/log.php');

/**
 * Takes raw data from the request
 * To verify sender number into Merchant DB
 */ 
$jsonData = file_get_contents('php://input');
// Log incoming data 
log::setStrLog($jsonData, 'merchantVerifyOrder.log.txt');


// Simulate confirm code
$status = rand(0,1);
if($status) {
    $result['status']   = $status;
    $result['code']     = generateRandomString(); 
    $result['message']  = 'Your order is confirmed';
}else {
    $result['status']   = $status;
    $result['code']     = null; 
    $result['message']  = 'Your order is NOT confirmed!!!';
}

echo $searchResult = json_encode($result, true);

// Log serach result in DataBase
log::setStrLog($searchResult, 'merchantVerifyOrder.log.txt');

/**
 * Simulator to generate CONFIRM CODE
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}