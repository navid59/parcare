<?php
/**
 * pentru Vodafone
 * la vodafone, tranzactia se desfasoara in 2 pasi
 */
require_once('lib/notify.php');

class Vodafone extends SmsOffline {
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
				// initiem plata, la primul sms
				// verificam daca in baza de date exista numarul de telefon $_GET[sender] pentru care se astepata raspunsul MOBILPAY_UNIQUE_CODE+da
				//daca nu, il inseram in baza de date si il rugam sa trimtia codul + da
				$verifyResult 	 = $merchant->getVerify($verifyArr);
				$verifyResultArr = json_decode($verifyResult);
				if($verifyResultArr->status) {
					$smsContentStr = getenv('SMS_TEXT_START') ." ". $this->mobilpayShortNumber ." ". getenv('SMS_TEXT_EXAMPLE');
					$this->sendResponse( $smsContentStr, 0, 0 );
				} else {
					$this->sendResponse( getenv('SMS_TEXT_REPEAT') .getenv('SMS_TEXT_SPACE') . $this->mobilpayShortNumber . " cu  " . $this->uniqueCode , 0, 0 );
				}
			break;
			case $this->uniqueCode.strtolower(self::MOBILPAY_CONFIRM_KEY):
				// verificam in baza de date daca a trimis initial cuvantul  MOBILPAY_UNIQUE_CODE
				$verifyResult 	 = $merchant->getVerify($verifyArr);
				$verifyResultArr = json_decode($verifyResult);

				/*
				* daca are bani in cont, si plata se va face, va primi raspunsul trimis acum
				* daca staus e TRUE intamna ca are bani,... si tot e in regula
				* alfel respuns o sa fii Negativ 
				*/ 
				if ($verifyResultArr->status) { // daca da
					// daca status e TRUE 
					$this->sendResponse( "Comanda Dvs a confirmat cu success, si codul de confirm este : ".$verifyResultArr->code, 1, 0 );
				} else { 
					//daca status e FALSE, inseamna ca nu este in baza de date retrimitem inca odata un sms free
					$this->sendResponse( "Pentru a finaliza plata va rugam trimiteti la " . $this->mobilpayShortNumber . " " . $this->uniqueCode . " da", 0, 0 );
				}
				break;
			case strpos($this->reciveMsg, '#') !== false :
				//SEND BY WEB2SMS simulation response: DA
				// - SEND Param to helper
				// - send plate number to merchant
				$autocar = new autocar();
				$plateNumber = $autocar->getNrInmatriculare($this->reciveMsg);
				
				/**
				 * Notify Merchant to by nr de inmarticulare 
				 * (Navid: change the method of Notify - Not OK NOW)
				 */
				$notify  = new Notify();
				$notify->notifyURL = 'http://35.204.43.65/parcare/merchant/';
				$notify->verifyURL = 'http://35.204.43.65/parcare/merchant/verifyOrder.php';
				$notifyArr = array(
					'sender' 	  => $this->phoneNumber,
					'uniqueCode'  => $this->uniqueCode,
					'shortNumber' => $this->mobilpayShortNumber,
					'plateNumber' => $plateNumber,
					'dateTime' 	  => date('Y-m-d H:i:s')
				);
				$notifyFeedback = $notify->sendNotify($notifyArr);

				/** helper will doing the following
				 * - first, Notify the merchant with phone number & Plate number of car
				 * - send an SMS via web2sms with countent of product code + "da"
				 * - ex of simulated sms : v2p5e
				 */
				$helper = new helper();
				$web2smsHelperURL = 'http://35.204.43.65/parcare/helper/web2sms.php'; 
				$helper->sendRequest($notifyArr, $web2smsHelperURL);
				

				$this->sendResponse( "Parcare este rezervat pt masina cu nr ". $plateNumber . " pt o ore cod-ul de confirmare este XXXX", 1, 0 );
				break;
			default :
				// in cazul in care sms-ul primit este gol
				$this->sendResponse( "Mesajul nu este corect, va rugam trimiteti la " . $this->mobilpayShortNumber . " un SMS cu codul " . $this->uniqueCode, 0, 0 );
				break;
		}
	}

}