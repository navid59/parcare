<?php
/**
 * Merchant check the DB & 10 min before expire time, will send reminder by Web2SMS
 * Merchant make an array to send SMS via WEB2SMS for nutification
 */

// require_once realpath('/var/www/html/parcare/vendor/autoload.php');
// use Web2sms\Sms\SendSMS;


// $sendSMS = new SendSMS();
// $sendSMS->accountType = 'prepaid';                                                  // postpaid | prepaid

// /**
//  * Postpaid account
//  */
// $sendSMS->apiKey     = '6997cfcc09caa439fab3d28e466e34a1f96f3eab';     // Your api KEY
// $sendSMS->secretKey  = '303ed8f4c1b541464aa15dadf0f9b28099aa7ec0954de53b01a07f8ba2ad951426344c0eafae3b04489bcf456bdb797804105eb913657ed29ab301dd566fdbb9';  // Your secret KEY


// $sendSMS->messages[]  = [
//                     'sender'            => null,                                                                                          // who send the SMS             // Optional
//                     'recipient'         => '0732933900',                                                                                  // who receive the SMS          // Mandatory
//                     'body'              => 'Parcare o sa fii expire in 10 min. doresti sa mai stati, trimiteti DA / NU - ex v2p5e da'.rand(0,1000),  // The actual text of SMS       // Mandatory
//                     'scheduleDatetime'  => null,                                                                                          // Date & Time to send SMS                            // Optional
//                     'validityDatetime'  => null,                                                                                          // Date & Time of expire SMS // Optional
//                     'callbackUrl'       => 'http://35.204.43.65/parcare/merchant/web2sms/',                                               // Call back  // Optional    
//                     'userData'          => null,                                                                                          // User data                                          // Optional
//                     'visibleMessage'    => false                                                                                          // false -> show the Org Msg & True is not showing the Org Msg           // Optional
//                     ];

// $sendSMS->setRequest();
// $result = $sendSMS->sendSMS();
// echo($result);

echo "Commented temporary to not send Notify SMS - ".rand(1,1000);
echo "\n";
