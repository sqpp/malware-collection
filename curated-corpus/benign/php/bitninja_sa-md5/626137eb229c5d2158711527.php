<?php
	include(dirname(__FILE__).'/../../config/config.inc.php');
	include(dirname(__FILE__).'/../../init.php');
	include(dirname(__FILE__).'/tmnewsletter.php');
	
	$tmnewsletter = new Tmnewsletter();

	if (Tools::getValue('action') == 'sendemail')
	{
		$email = Tools::getValue('email');
		$status = Tools::getValue('status');
		if (Validate::isEmail($email))
		{
			if ($result = $tmnewsletter->newsletterRegistration($email))
			{
				$tmnewsletter->updateDate((int)$status);
				die(Tools::jsonEncode(array('success_status' => $result)));
			}
			die(Tools::jsonEncode(array('error_status' => 'Something went wrong!')));
		}
	}
	else if (Tools::getValue('action') == 'updatedate')
	{
		$status = Tools::getValue('status');
		$tmnewsletter->updateDate((int)$status);
	}
?>