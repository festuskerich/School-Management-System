<?php
$date = date('m/d/Y h:i:s a', time());
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $file = fopen("messagelog.txt", "a");
    fwrite($file, "\n \n  Log " . $date);
   fclose($file);
    
    $id = $_POST['id'];
    $status = $_POST['status'];
    $phoneNumber = $_POST['phoneNumber'];
    $networkCode = $_POST['networkCode'];
    $failureReason = $_POST['failureReason'];
    $retryCount = $_POST['retryCount'];
    
    
    
    $log = 'id '.$id.', status '.$status. ', phoneNumber ' . $phoneNumber. ', networkCode' .$networkCode. ' ,failureReason '.$failureReason.' retryCount '.$retryCount;
    
    // file_put_contents($file, "Log ". $log . $date );
    
   $file = fopen("messagelog.txt", "a");
    fwrite($file, "\n \n  Log ". $log . $date);
   fclose($file);
   
}
else{
    $file = fopen("messagelog.txt", "a");
    fwrite($file, "\n \n Failed to write delivery report ".$date );
   fclose($file);
}
?>