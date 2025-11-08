<?php
//version 1.0

class Logcarbon {
    public static function carbonlog($information,$createfile='logs'){
        //A PHP array containing the data that we want to log.
        $dataToLog = array(
            date("Y-m-d H:i:s"), //Date and time
            get_client_ip(), //IP address
            get_client_browser(), //browser
            $information, //Custom text
        );
        //Turn array into a delimited string using
        //the implode function
        $data = implode(" ~ ", $dataToLog);
        //Add a newline onto the end.
        $data .= PHP_EOL;
        //The name of your log file.
        //Modify this and add a full path if you want to log it in 
        //a specific directory.
        $directory = "logs/";
        $pathToFile = $directory.$createfile.'.log';
        //Log the data to your file using file_put_contents.
        file_put_contents($pathToFile, $data, FILE_APPEND);
    }

    public static function carbonlogfgets($logsfile='logs'){
        $getfile = fopen('logs/'.$logsfile.'.log', 'r');
        return fgets($getfile);
    }

    public static function carbonlogfclose($logsfile='logs'){
        $getfile = fopen('logs/'.$logsfile.'.log', 'r');
        return fclose($getfile);
    }
}