<?php
$full_path = __FILE__;
$path = explode( 'wp-content', $full_path );
require_once( $path[0] . '/wp-load.php' );

$sitename = get_bloginfo( 'name' );
$siteurl =  home_url();

$to = isset( $_POST['to'] )?trim( $_POST['to'] ):'';
$name = isset( $_POST['name'] )?trim( $_POST['name'] ):'';
$email = isset( $_POST['email'] )?trim( $_POST['email'] ):'';
$content = isset( $_POST['content'] )?trim( $_POST['content'] ):'';


$error = false;
if ( $to === '' || $email === '' || $content === '' || $name === '' ) {
	$error = true;
}
if ( !preg_match( '/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email ) ) {
	$error = true;
}
if ( !preg_match( '/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $to ) ) {
	$error = true;
}

if ( $error == false ) {
	$subject = sprintf( __( '%1$s\'s message from %2$s', 'mk_framework' ), $sitename, $name );
	$body = __( 'Site: ', 'mk_framework' ).$sitename.' ('.$siteurl.')'."\n\n";
	$body .= __( 'Name: ', 'mk_framework' ).$name."\n\n";
	$body .= __( 'Email: ', 'mk_framework' ).$email."\n\n";
	$body .= __( 'Messages: ', 'mk_framework' ).$content;
	$headers = "From: $name <$email>\r\n";
	$headers .= "Reply-To: $email\r\n";


	if ( wp_mail( $to, $subject, $body, $headers ) ) {
		echo 'success';
	}else {
		echo 'fail';
	}
}