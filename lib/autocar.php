<?php
class autocar {
    public function __construct() {
		//
	}

    public function getNrInmatriculare($str){
        $strSplit = explode("#", $str);
        $plateNumber = $strSplit[1]; 
        return $plateNumber;
    }
}