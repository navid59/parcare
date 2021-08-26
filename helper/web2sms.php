<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once realpath('/var/www/html/parcare/vendor/autoload.php');
require_once('/var/www/html/parcare/merchant/classes/log.php');
use Web2sms\Sms\SendSMS;

/**
 * Takes raw data from the request
 * To verify sender number into Merchant DB
 */ 
$jsonData = file_get_contents('php://input');
// Log incoming data 
log::setStrLog($jsonData, 'web2smsSent.log.txt');

$objData = json_decode($jsonData);

/**
 * Configure WEB2SMS account
 */
$sendSMS = new SendSMS();
$sendSMS->accountType = 'prepaid';                                     // postpaid | prepaid
$sendSMS->apiKey     = '6997cfcc09caa439fab3d28e466e34a1f96f3eab';     // Your api KEY
$sendSMS->secretKey  = '303ed8f4c1b541464aa15dadf0f9b28099aa7ec0954de53b01a07f8ba2ad951426344c0eafae3b04489bcf456bdb797804105eb913657ed29ab301dd566fdbb9';  // Your secret KEY


$sendSMS->messages[]  = [
                    'sender'            => '0732933900',                                    // who send the SMS             // Optional     //(WEB2SMS verification requre!!!)
                    'recipient'         => '0732933900', //$objData->mobilpayShortNumber    // ex. 7415                      // who receive the SMS          // Mandatory
                    'body'              => $objData->uniqueCode.' da',                      // The actual text of SMS       // Mandatory
                    'scheduleDatetime'  => null,                                            // Date & Time to send SMS      // Optional
                    'validityDatetime'  => null,                                            // Date & Time of expire SMS    // Optional
                    'callbackUrl'       => 'http://35.204.43.65/parcare/merchant/web2sms/', // Call back                    // Optional     //(WEB2SMS verification requre)    
                    'userData'          => null,                                            // User data                    // Optional
                    'visibleMessage'    => false                                            // false -> show the Org Msg & True is not showing the Org Msg           // Optional
                    ];

$sendSMS->setRequest();
$result = $sendSMS->sendSMS();
echo($result);
