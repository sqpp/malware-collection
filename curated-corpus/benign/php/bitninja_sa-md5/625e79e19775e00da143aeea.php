<?php
require('config_session.php');

if(isset($_POST['take_action'], $_POST['target'])){
	$action = escape($_POST['take_action']);
	$target = escape($_POST['target']);

	if($action == 'unban'){
		echo unbanAccount($target);
		die();
	}
	else if($action == 'unmute'){
		echo unmuteAccount($target);
		die();
	}
	if($action == 'room_block'){
		echo blockRoom($target);
		die();
	}
	else if($action == 'room_mute'){
		echo muteRoom($target);
		die();
	}
	else if($action == 'room_unmute'){
		echo unmuteRoom($target);
		die();
	}
	else if($action == 'muted'){
		echo unmuteAccount($target);
		die();
	}
	else if($action == 'banned'){
		echo unbanAccount($target);
		die();
	}
	else if($action == 'room_unblock'){
		echo unblockRoom($target);
		die();
	}
	else if($action == 'kicked'){
		echo unkickAccount($target);
		die();
	}
	else if($action == 'unkick'){
		echo unkickAccount($target);
		die();
	}
	else {
		echo 0;
		die();
	}
}
if(isset($_POST['check_kick'])){
	if($data['user_kick'] == 0){
		echo 1;
		die();
	}
}
if(isset($_POST['check_maintenance'])){
	if($data['maint_mode'] == 0){
		echo 1;
		die();
	}
}
if(isset($_POST['kick'], $_POST['reason'], $_POST['delay'])){
	$target = escape($_POST['kick']);
	$reason = escape($_POST['reason']);
	$delay = escape($_POST['delay']);
	echo kickAccount($target, $delay, $reason);
	die();
}
if(isset($_POST['mute'], $_POST['reason'], $_POST['delay'])){
	$target = escape($_POST['mute']);
	$reason = escape($_POST['reason']);
	$delay = escape($_POST['delay']);
	echo muteAccount($target, $delay, $reason);
	die();
}
if(isset($_POST['ban'], $_POST['reason'])){
	$target = escape($_POST['ban']);
	$reason = escape($_POST['reason']);
	echo banAccount($target, $reason);
	die();
}
if(isset($_POST['remove_room_staff'], $_POST['target'])){
	$target = escape($_POST['target']);
	echo removeRoomStaff($target);
	die();
}
?>