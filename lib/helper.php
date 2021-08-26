<?php
class Helper {
    public function __construct() {
        //
    }
   
    // helper to send params as json to other URL
    public function sendRequest($jsonStr, $url) {
        if(!isset($url) || is_null($url)) {
            throw new \Exception('INVALID_VERIFY_URL');
            exit;
        }

        $ch = curl_init($url);
        
        $payload = json_encode($jsonStr); // json DATA
    
        // To do a regular HTTP POST like 'application/x-www-form-urlencoded'
        curl_setopt($ch, CURLOPT_POST, true);

        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    
        // Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        // Execute the POST request
        $result = curl_exec($ch);
        
        if (!curl_errno($ch)) {
            $verifyResult = json_decode($result);
        } else {
            throw new Exception( "The Merchant problem!!!");
        }
        
        // Close cURL resource
        curl_close($ch);
        
        $finalResult = json_encode($verifyResult, JSON_FORCE_OBJECT);
        return $finalResult;
    }
}