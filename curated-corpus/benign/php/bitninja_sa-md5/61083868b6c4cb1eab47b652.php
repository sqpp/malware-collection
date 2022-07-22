<?php

$receiver = $_POST['receiver'];          //PROVIDE YOUR EMAIL ADDRESS
$subject = $_POST['subject'];            //PROVIDE THE SUBJECT OF THE CONTACT FORM MAIL



$name = $_POST['name'];
$email = $_POST['email'];
$mail_message = $_POST['message'];


$message = "\nName: " . $name .
	"\nEmail: " . $email ;

$message .= "\nMessage: " . $mail_message .
	"\n\n\n\nDate: " . date("Y-m-d h:i:s");

$siteEmail = $receiver;
$emailTitle = $subject;
$thankYouMessage = "Thank you for contacting us, we'll get back to you shortly.";  
$err_msg =  'Please Try Again';

if($_POST['website_url'] == '')
{
	if(mail($siteEmail, $emailTitle, $message, 'From: ' . $name . ' <' . $email . '>'))
		{ echo 'success'; }
	else { echo 'error'; }
}
else
{
	echo 'error';
}


?>
