<?php
echo "<p> test01.php loaded</p>";

function logData(string $level, string $message){

    $day = date('Y-m-d');
    $time = date('Y-m-d H:i:s');

    $logData = '[' .$day. '--' .$time. '--' .$level. '] -- ' .$message. "\n";
    $logData .= str_repeat('*', 100). "\n";

    $logFile = LOG_DIR.'/log-' .$day. '.log';
    file_put_contents($logFile, $logData , FILE_APPEND);
}