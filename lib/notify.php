<?php
class Notify {
    public $notifyURL = null;   // URL to notify merchant
    public $verifyURL = null;   // URL to Verify order status
    public function __construct() {
        //
    }

    // Send notification json
    public function sendNotify($jsonStr, $customURL = "") {
        if(!isset($this->notifyURL) || is_null($this->notifyURL)) {
            throw new \Exception('INVALID_NOTIFY_URL');
            exit;
        }

        $url = !empty($customURL) ? $customURL : $this->notifyURL;
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
            switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 200:  # OK
                    $arr = array(
                        'status'  => 1,
                        'code'    => $http_code,
                        'message' => "You send your request, successfully",
                        'data'    => json_decode($result)
                    );
                break;
                case 404:  # Not Found 
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'message' => "You send request to wrong URL",
                        'data'    => json_decode($result)
                    );
                break;
                case 400:  # Bad Request
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'message' => "You send Bad Request",
                        'data'    => json_decode($result)
                    );
                break;
                case 405:  # Method Not Allowed
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'message' => "Your method of sending data are not Allowed",
                        'data'    => json_decode($result)
                    );
                break;
                default:
                    $arr = array(
                        'status'  => 0,
                        'code'    => $http_code,
                        'message' => "Opps! Something is wrong, verify how you send data & try again!!!",
                        'data'    => json_decode($result)
                    );
                break;
            }
        } else {
            $arr = array(
                'status'  => 0,
                'code'    => 0,
                'message' => "Opps! There is some problem, you are not able to send data!!!"
            );
        }
        
        // Close cURL resource
        curl_close($ch);
        
        $finalResult = json_encode($arr, JSON_FORCE_OBJECT);
        return $finalResult;
    }

    
    // Get order status from merchant
    public function getVerify($jsonStr) {
        if(!isset($this->verifyURL) || is_null($this->verifyURL)) {
            throw new \Exception('INVALID_VERIFY_URL');
            exit;
        }

        $url = $this->verifyURL;
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