<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('lib/smsOffline.php');
require_once('lib/cosmote.php');
require_once('lib/vodafone.php');
require_once('lib/cosmote.php');
require_once('lib/notify.php');
require_once('lib/log.php');

if(isset($_GET['rmLog'])) {
    /** Temporary to DELETE Log file */
    log::rmLog($_GET['rmLog']);
    exit;
}

/** 
 * Get incoming parameters
 */
$data = $_REQUEST;


$smsOffline = new SmsOffline();
$smsOffline->uniqueCode 		 = "p4321";
$smsOffline->mobilpayShortNumber = "7415";

$smsOffline->setOperator( $data ['destination'] );
$smsOffline->setPhoneNumber( $data ['sender'] );
		

/** to notify Merchant  */
$notify  = new Notify();
$notify->notifyURL = 'http://35.204.43.65/parcare/merchant/';
$notify->verifyURL = 'http://35.204.43.65/parcare/merchant/verifyOrder.php';

Log::setArrLog($_GET);
Log::setArrLog($smsOffline);
Log::setStrLog($smsOffline->operator);

if(in_array($smsOffline->operator, SmsOffline::OPERATOR_ARR)) {
	$notifyArr = array(
		'sender' 	=> $data['sender'],
		'message' 	=> $data['message'],
		'dateTime' 	=> $data['timestamp'] //use preg_match to make d-m-Y H:i:s
	); 
	/**
	 * Notify merchant for first time
	 * Merchant can save the information on Database
	 * */
	$notifyFeedback = $notify->sendNotify($notifyArr);

	switch($smsOffline->operator) {
		case SmsOffline::OPERATOR_COSMOTE_NAME:
			$op = new Cosmote();
			break;
		case SmsOffline::OPERATOR_VODAFONE_NAME:
			$op = new Vodafone();
			break;
		case SmsOffline::OPERATOR_ORANGE_NAME:
			$op = new Orange();
			break;
		default :
			throw new Exception( "Oppps! There is a problem, verify the data!");
	}

	$op->reciveMsg 	 		 = $data['message'];
	$op->phoneNumber 		 = $data['sender'];
	$op->operator 	 		 = SmsOffline::OPERATOR_COSMOTE_NAME;
	$op->uniqueCode  		 = $smsOffline->uniqueCode; // ?????? NOT HAPPY WITH THIS VAR
	$op->mobilpayShortNumber = $smsOffline->mobilpayShortNumber; // ?????? NOT HAPPY WITH THIS VAR too
	$op->merchantVerifyURL 	 = $notify->verifyURL;

	$op->makeOperation();
} else {
	throw new Exception( "The operation is not found!!!");
}