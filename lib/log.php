<?php 
class Log {
    
    function __construct(){
        //
    }

    
    static function setArrLog($arrLog) {
        if(is_null($arrLog))
            return false;

        $logPoint = date(" - H:i:s - ")." \n";
        ob_start();                     // start buffer capture
        
        foreach($arrLog as $key => $val) {
            $logPoint .= $key ." : ". $val . "\n";
        }
        $logPoint .= "--------------------\n";
        echo $logPoint;
        $contents = ob_get_contents();  // put the buffer into a variable
        ob_end_clean();
           file_put_contents('logs/arr.log.txt', $contents , FILE_APPEND | LOCK_EX);
    }

    static function setStrLog($log) {
        if(is_null($log))
            return false;
            
        $logPoint = rand(1,1000).date(" - H:i:s - ")." | ";
        ob_start();                     // start buffer capture
        echo $logPoint;
        var_dump( $log );               // dump the values
        $contents = ob_get_contents();  // put the buffer into a variable
        ob_end_clean();
           file_put_contents('logs/str.log.txt', $contents , FILE_APPEND | LOCK_EX);
    }
    
    static function rmLog($fileName) {
        if(!is_null($fileName)) {
           echo $dynamicResult =  file_exists("logs/".$fileName) ? unlink('logs/'.$fileName) : 'logs/'.$fileName.' NOT Deleted!!!';
        }else{
            echo $resultArrLog = file_exists('logs/arr.log.txt') ? unlink('logs/str.log.txt'): ' Static file arr.log.txt is NOT DELETED';
            echo $resultStrLog = file_exists('logs/arr.log.txt') ? unlink('logs/str.log.txt'): ' Static file str.log.txt is NOT DELETED';
        }
    }
}