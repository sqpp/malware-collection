<?php
/***************************************************************************
*                                                                          *
*    Copyright (c) 2009 Simbirsk Technologies Ltd. All rights reserved.    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/


//
// $Id: 365.php 7502 2009-05-19 14:54:59Z zeke $
//

if (defined('PAYMENT_NOTIFICATION')) {
	if ($mode == 'notify') {
		$order_info = fn_get_order_info($_REQUEST['order_id']);
		if ($order_info['status'] == 'O') {
			$pp_response = array();
			$pp_response['order_status'] = 'F';
			$pp_response['reason_text'] = fn_get_lang_var('order_id') . '-' . $_REQUEST['order_id'];
			fn_finish_payment($_REQUEST['order_id'], $pp_response, false);
		}

		fn_order_placement_routines($_REQUEST['order_id']);
	}

} elseif (!empty($_REQUEST['UserAgent'])) {

	DEFINE ('AREA', 'C');
	DEFINE ('AREA_NAME' ,'customer');
	require './../prepare.php';
	require './../init.php';

	$order_id = $_REQUEST['User1'];
	$pp_response = array();
	$pp_response['order_status'] = ($_REQUEST['EventCode'] == 'S') ? 'P' : 'F';
	$pp_response['reason_text'] = fn_get_lang_var('order_id') . '-' . $order_id;
	$pp_response['transaction_id'] = @$_REQUEST['TransactionID'];

	if ($_REQUEST['EventCode'] == 'T') {
		$pp_response['order_status'] = "P";
		$pp_response['reason_text'] = "This is a TEST transaction";
	}

	if (fn_check_payment_script('365.php', $order_id)) {
		fn_finish_payment($order_id, $pp_response); // Force customer notification
	}
	exit;

} elseif (!empty($_REQUEST['CryptExt'])) {
	DEFINE ('AREA', 'C');
	DEFINE ('AREA_NAME', 'customer');
	require './../prepare.php';
	require './../init.php';
	
	fn_redirect(Registry::get('config.current_location') . "/$index_script?dispatch=payment_notification.notify&payment=365&order_id=" . $_REQUEST['User1']);
	exit;

} else {

#!/usr/bin/perl
# string to encrypt
$product_descr = '';
// Products
if (!empty($order_info['items'])) {
	foreach($order_info['items'] as $v) {
		$product_descr = $product_descr . $v['product'] . "; ";
	}
}
// Gift Certificates
if (!empty($order_info['gift_certificates'])) {
	foreach($order_info['gift_certificates'] as $v) {
		$product_descr = $product_descr . $v['gift_cert_code'] . "; ";
	}
}
$string = "Amount=".$order_info['total'] . "&ProductDescription=" . $product_descr . "&Template=checkout";

# encryption key from website settings
$encryption_key = $processor_data['params']['cryptext'];

# calling generate-crypt.pl script passing string and encryption key arguments
chdir(DIR_ROOT . '/payments/365_files');
$encrypted_string = preg_replace("/\+/", "-", exec("perl ./generate-crypt.pl '$string' '$encryption_key'"));
chdir(DIR_ROOT);

# print out newly encrypted string

echo <<<EOT
<html>
<body onLoad="javascript: document.process.submit();">
<form method="post" action="http://secure.365billing.com/cgi-bin/form.cgi?" name="process">
<input type="hidden" name="User1" value="{$order_id}">
<input type="hidden" name="CryptInt" value="{$processor_data['params']['cryptint']}">
<input type="hidden" name="CryptExt" value="{$encrypted_string}">
<input type="hidden" name="SiteID" value="{$processor_data['params']['site_id']}">
EOT;
$msg = fn_get_lang_var('text_cc_processor_connection');
$msg = str_replace('[processor]', '365 Billing server', $msg);
echo <<<EOT
	</form>
   <p><div align=center>{$msg}</div></p>
 </body>
</html>
EOT;
exit;
}

?>
