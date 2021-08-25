<?php
/*
 * pentru Cosmote 
 * la fel ca la vodafone, si la cosmote tranzactia se desfasoara in doua etape, cu specificatia ca SMS-ul 2 se trimite la MOBILPAY_SENDER_NUMBER + 0 
 */
require_once('lib/notify.php');

class Cosmote extends SmsOffline {
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
				// verificam daca in baza de date exista numarul de telefon $_GET[sender] si care astepta raspunsul
				// daca nu, il inseram in baza de date si il rugam sa trimtia codul + da
				// sau rugam sa trimite inca o data SMS cu codeul de produs
				$verifyResult 	 = $merchant->getVerify($verifyArr);
				$verifyResultArr = json_decode($verifyResult);
				if($verifyResultArr->status) {
					$this->sendResponse( "Pentru a finaliza plata va rugam trimiteti la " . $this->mobilpayShortNumber . "0 " . $this->uniqueCode . " da", 0, 0 );
				} else {
					$this->sendResponse( "Va rugam trimiteti inca o data SMS la " . $this->mobilpayShortNumber . " cu  " . $this->uniqueCode , 0, 0 );
				}
				
			break;
			case $this->uniqueCode.strtolower(self::MOBILPAY_CONFIRM_KEY):
				// verificam in baza de date daca a trimis initial cuvantul de test
				$verifyResult 	 = $merchant->getVerify($verifyArr);
				$verifyResultArr = json_decode($verifyResult);
			
				/*
				* daca are bani in cont, si plata se va face, va primi raspunsul trimis acum
				* daca staus e TRUE intamna ca are bani,... si tot e in regula
				* alfel respuns o sa fii Negativ 
				*/ 
				if ($verifyResultArr->status) {
					// daca status e true 
					$this->sendResponse( "Comanda Dvs a confirmat cu success, si codul de confirm este : ".$verifyResultArr->code, 1, 0 );
				} else {
					// daca status e false
					$this->sendResponse( "Pentru a finaliza plata va rugam trimiteti la " . $this->mobilpayShortNumber . "0 " . $this->uniqueCode . " da", 0, 0 );
				}
			break;
			default:
				$this->sendResponse( "Mesajul nu este corect, va rugam trimiteti la " . $this->mobilpayShortNumber . " un SMS cu codul " . $this->uniqueCode, 0, 0 );
			break;
		}
	}
}