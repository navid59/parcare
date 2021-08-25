<?php 
class Log {
    
    function __construct(){
        //
    }

    
    // static function setArrLog($arrLog) {
    //     if(is_null($arrLog))
    //         return false;

    //     $logPoint = date(" - H:i:s - ")." \n";
    //     ob_start();                     // start buffer capture
        
    //     foreach($arrLog as $key => $val) {
    //         $logPoint .= $key ." : ". $val . "\n";
    //     }
    //     $logPoint .= "--------------------\n";
    //     echo $logPoint;
    //     $contents = ob_get_contents();  // put the buffer into a variable
    //     ob_end_clean();
    //        file_put_contents('merchantLogs/arr.log.txt', $contents , FILE_APPEND | LOCK_EX);
    // }

    static function setStrLog($log, $logFileName = 'merchant.log.txt') {
        if(is_null($log))
            return false;
            
        ob_start();                     // start buffer capture
        echo ($log)." \n";              // print the values
        $contents = ob_get_contents();  // put the buffer into a variable
        ob_end_clean();
           file_put_contents($logFileName, $contents , FILE_APPEND | LOCK_EX);
    
        return true;
    }
}