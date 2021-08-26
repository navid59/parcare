<?php
/**
 * pentru Orange 
 * la orange se taxeaza din prima
 */
require_once('lib/notify.php');

class Orange extends SmsOffline {
	public $reciveMsg; 			 // the message what Cellphone owner sent
	public function __construct() {
		parent::__construct();
	}

	/**
	 * initiem plata
	 */
	public function makeOperation() {
		$merchant  = new Notify();
		$merchant->verifyURL = $this->merchantVerifyURL;
		$verifyArr = array(
			'sender' 	=> $this->phoneNumber
		);

		switch($this->reciveMsg) {
			case $this->uniqueCode:
				// la orange se taxeaza din prima
				$this->sendResponse( "Va multumim pentru plata efectuata! Comanda Dvs a confirmat cu success.", 1, 0 );
			break;
			default :
				// in cazul in care codul nu este cel pe care il asteptam
				$this->sendResponse( "Mesajul nu este corect, va rugam trimiteti la " . $this->mobilpayShortNumber . " un SMS cu codul " . $this->uniqueCode, 0, 0 );
			break;
		}
	}
}


	// if ($_GET ['message'] == $smsOffline->uniqueCode) {
	// 	SmsOffline::sendResponse( "Va multumim pentru plata efectuata!", 1, 0 ); // la orange se taxeaza din prima
	// } else { // in cazul in care codul nu este cel pe care il asteptam
	// 	SmsOffline::sendResponse( "Mesajul nu este corect, va rugam trimiteti la " . MOBILPAY_SENDER_NUMBER . " un SMS cu codul " . $smsOffline->uniqueCode, 0, 0 );
	// }