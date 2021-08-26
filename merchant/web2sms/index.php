<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../classes/log.php');

// Takes raw data from the request
$json = file_get_contents('php://input');

// Log incoming data 
log::setStrLog($json, '../merchantSmsCallBack.log.txt');

// Simulate result
$result['status']   = true; 
$result['code']     = 1; 
$result['message']  = 'Merchant Notified, sender to renew or cancel the Order';

echo json_encode($result, true);