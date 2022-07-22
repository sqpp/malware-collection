<?php
/**
* Codychat
*
* @package Codychat
* @author www.boomcoding.com
* @copyright 2020
* @terms any use of this script without a legal license is prohibited
* all the content of Codychat is the propriety of BoomCoding and Cannot be 
* used for another project.
*/
require_once("config_session.php");
if (!isset($_POST['content'], $_POST['snum'])){
	die();
}
if(isTooLong($_POST['content'], $data['max_main'])){
	die();
}
if(muted() || roomMuted()){
	die();
}
if(checkFlood()){
	echo 100;
	die();
}

$snum = escape($_POST['snum']);
$content = escape($_POST['content']);
$content = wordFilter($content, 1);
$content = textFilter($content);

if(empty($content) && $content !== '0' || !inRoom()){
	die();
}
echo userPostChat($content, array('snum'=> $snum));
?>