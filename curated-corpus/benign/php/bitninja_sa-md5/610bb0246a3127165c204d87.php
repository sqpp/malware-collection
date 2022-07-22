#!/usr/local/bin/php -q
<?php
// v3.0.3
require(dirname(__FILE__) . "/../../../init.php");
include('autounblockcsf_functions.php');

$moduleconfigresult = select_query('tbladdonmodules','setting,value',array('module'=>'autounblockcsf'));
while ($moduleconfigdata = mysql_fetch_array($moduleconfigresult)) {
	if ($moduleconfigdata['setting'] == 'unblock_email') {$emailA = $moduleconfigdata['value'];}
	if ($moduleconfigdata['setting'] == 'allow_pipe') {$allowPipe = $moduleconfigdata['value'];}
	if ($moduleconfigdata['setting'] == 'limit_unblock') {$userLimit = $moduleconfigdata['value'];}
	if ($moduleconfigdata['setting'] == 'limit_search') {$allowSearch = $moduleconfigdata['value'];}
}

if ($allowPipe == 'on') {
	$fd = fopen("php://stdin", "r");
	$email_content = "";
	while (!feof($fd)) {
		$email_content .= fread($fd, 1024);
	}
	fclose($fd);

	$lines = explode("\n", $email_content);

	$sender = "";
	$subject = "";
	$headers = "";
	$message = "";
	$is_header= true;
	for ($i=0; $i < count($lines); $i++) {
		if ($is_header) {
			$headers .= $lines[$i]."\n";
			if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
				$subject = $matches[1];
			}
			if (preg_match("/^Sender: (.*)/", $lines[$i], $matches)) {
				$sender = $matches[1];
			}
		} else {
			$message .= $lines[$i]."\n";
		}
		if (trim($lines[$i])=="") {
			$is_header = false;
		}
	}
	$envsender = trim(getenv('SENDER'));
	if (autounblockcsf_validIpAddress($subject)) { 
		$ip = trim($subject);
	}

	if (!empty($envsender) && !empty($ip)) {
		$senderresult = select_query('tblclients','id',array('email'=>$envsender,'status'=>'Active'));
		$senderdata = mysql_fetch_array($senderresult);
		$userid = $senderdata[id];
		$checkLimit = autounblockcsf_checkLimit($userLimit, $allowSearch, $userid);
		if (!isset($checkLimit['autoAction'])) {
			return false;
		}
		$getClientServers = autounblockcsf_getClientServers($userid);
		$userProductServers = autounblockcsf_userProductServers($userid);
		if ($getClientServers['status'] == '0') {
			$ClientServers = $userProductServers;
		} else {
			$ClientServers = array_merge($getClientServers['servers'],$userProductServers);
		}
		foreach ($ClientServers as $key=>$server) {
			if ($server['id']) {
				getAutoUnblock($ip, 'remove', $server['id'], $userid);
			} elseif ($server['productid']) {
				getAutoUnblock($ip, 'remove', $server, $userid);
			}
		}
	} else {
		global $remote_ip;
		if ($remote_ip) {
			$Pmsg = '<h3>The IP address: <span style="color:red">'.$remote_ip.'</span> is trying to accsess the AutoUnblock csf pipe.php directly!!!</h3>';
		} else {
			$Pmsg = '<p>There was an access to the pipe with the following information:<br/>
			Massage subject: '.$subject.'<br/>
			Massage sender: '.$envsender.'<br/>
			</p>';
		}
		$Pheaders  = 'MIME-Version: 1.0' . "\r\n";
		$Pheaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		mail($emailA,'Pipe AutoUnblock csf',$Pmsg,$Pheaders);
		exit;
	}
}	
?>