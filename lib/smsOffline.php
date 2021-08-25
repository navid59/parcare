<?php

class SmsOffline {
    public $phoneNumber 		= null; // phone number
	public $operator    		= null; // The operator, could be :Orange, Vodafone sau Cosmote
	public $uniqueCode 			= null; // The UNIQUE CODE what you set in Admin MobilPay
    public $mobilpayShortNumber = null; // the mobilpay short number
	
	const MOBILPAY_CONFIRM_KEY			= " da"; // have to begin with a white space

	const ERROR_INVALID_PHONE_NUMBER    = 1;
	const ERROR_INVALID_DESTINATION     = 2;
	const ERROR_OPERATOR_NOT_FOUND      = 3;
	const ERROR_MESSAGE_TOO_LONG        = 4;
	
	const OPERATOR_ORANGE_NAME          = "Orange";
	const OPERATOR_VODAFONE_NAME        = "Vodafone";
	const OPERATOR_COSMOTE_NAME         = "Cosmote";

	const OPERATOR_ARR = array (
		self::OPERATOR_ORANGE_NAME,
		self::OPERATOR_VODAFONE_NAME,
		self::OPERATOR_COSMOTE_NAME
	);
	
	public function __construct() {
		//
	}

	// public function __construct($data) {
    //     if(!empty($data)) {
    //         if(empty($data ['destination'])){
	// 			throw new Exception( "invalid Destination");
	// 		}
	// 		if(empty($data ['sender'])){
	// 			throw new Exception( "invalid Sender");	
	// 		}
    //     } else {
    //         throw new Exception( "invalid data");
    //     }
	// }
	
	static public function checkPhoneNumber($phoneNumber) {
		if (! $phoneNumber) {
			return false;
		}
		return preg_match( '/^(\d{10})$/x', $phoneNumber );
	}
	
	static public function checkDestionationNumber($destinationNumber) {
		
		if (! $destinationNumber) {
			
			return false;
		}
		if (preg_match( '/^(\d{11,20})$/x', $destinationNumber )) {
			return true;
		}
		return false;
	}
	
	public function setPhoneNumber($phoneNumber) {
		if (! self::checkPhoneNumber( $phoneNumber )) {
			throw new Exception( "invalid phone number", self::ERROR_INVALID_PHONE_NUMBER );
		}
		$this->phoneNumber = $phoneNumber;
	}

	public function setOperator($destinationNumber) {
		if (! self::checkDestionationNumber( $destinationNumber )) {
			throw new Exception( "operator not found", self::ERROR_OPERATOR_NOT_FOUND );
		}
		
		if (preg_match( '/^10([0-9]{4})0000/', $destinationNumber, $c )) {
			if ($c [1] != $this->mobilpayShortNumber) {
				throw new Exception( "Invalid destionation", self::ERROR_INVALID_DESTINATION );
			}
			$this->operator = self::OPERATOR_ORANGE_NAME;
			return;
		}
		
		if (preg_match( '/^217010194163([0-9]{4})/', $destinationNumber, $c )) {
			if ($c [1] != $this->mobilpayShortNumber) {
				throw new Exception( "Invalid destionation", self::ERROR_INVALID_DESTINATION );
			}
			$this->operator = self::OPERATOR_VODAFONE_NAME;
			return;
		}
		
		if (preg_match( '/^085120083126([0-9]{4,5})/', $destinationNumber, $c )) {
			if ($c [1] != $this->mobilpayShortNumber && $c [1] != $this->mobilpayShortNumber . "0") {
				throw new Exception( "Invalid destionation", self::ERROR_INVALID_DESTINATION );
			}
			$this->operator = self::OPERATOR_COSMOTE_NAME;
			return;
		}
		
		throw new Exception( "Operator not found", self::ERROR_OPERATOR_NOT_FOUND );
	}

	static function sendError($error) {
		self::sendResponse( $error, 0, $errorCode );
	}

	static function sendResponse($replyMessage, $charge, $errorCode) {
		if (strlen( $replyMessage ) > 160) {
			throw new Exception( "Message too long", self::ERROR_MESSAGE_TOO_LONG );
		}
		$return = '';
		$chargeType = $charge ? "charge" : "free";
		header( 'Content-type: application/xml' );
		$return .= "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
		$return .= "<reply_message operation=\"$chargeType\" reply=\"1\" error_code=\"$errorCode\">
						" . $replyMessage . "
				</reply_message>";
		
		echo $return;
	}
}