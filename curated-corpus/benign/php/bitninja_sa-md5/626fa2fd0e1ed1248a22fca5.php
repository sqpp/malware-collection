<?php
$load_addons = 'vip';
require_once('../../../system/config_addons.php');

function vipUserClean(){
	global $mysqli, $data;
	$time = time();
	$get_vip = $mysqli->query("SELECT * FROM boom_users  WHERE vip_end < $time AND vip_end > 0 AND user_rank = 2");
	if($get_vip->num_rows > 0){	
		while($user = $get_vip->fetch_assoc()){
			require(vipLang($user));
			userReset($user, 1);
			$mysqli->query("UPDATE boom_users SET vip_end = 0 WHERE user_id = '{$user['user_id']}'");
			$mysqli->query("INSERT INTO `boom_private` (time, target, hunter, message) VALUES ('" . time() . "', '{$user['user_id']}', '{$data['system_id']}', '" . escape($lang['vip_end']) . "')");
		}
		return 1;
	}
	else {
		return 0;
	}
}
if(isset($_POST['clean_vip']) && boomAllow(2)){
	echo vipUserClean();
	die();
}
?>