<?php
require_once('../includes/init.php');

$res = (object) array();

$res->success = false;
$res->message = '';

if($_SERVER['REQUEST_METHOD'] !== "POST") {
    $res->message = "Connect VPN!";
    die(json_encode($res));
}

if(base64_decode($_SERVER['HTTP_AUTHORIZATION']) !== $_POST['user']) {
    $res->message = "Connect VPN!";
    die(json_encode($res));
}

if(empty($_POST['did'])) {
    $res->message = "Connect VPN!";
    die(json_encode($res));
}

$col_todayTask = 'today_task';
$col_taskTime = 'task_time';

if($_POST['task-app'] > 5){
    $res->message = "Connect VPN!";
    die(json_encode($res));
}

if($multi_task == 1){
if($_POST['task-app'] > 0) {
	$col_todayTask = 'today_task' . $_POST['task-app'];
	$col_taskTime = 'task_time' . $_POST['task-app'];
}
}

if( $spin == 1 || $spin_task == 1){ 
    
    if ($_POST['task-app'] == 5) {
        $task_limit = $spin_click;
	    $col_todayTask = 'today_spin';
        $col_taskTime = 'spin_time';
}
}

if($_POST['task-app'] != 5){
if($task_enabled == 0 ) {
    $res->success = false;
    $res->message = 'Task closed';
    die( json_encode($res) );
}
}
else {
    if($spin_enabled == 0 ) {
    $res->success = false;
    $res->message = 'Spin closed';
    die( json_encode($res) );
}
}

$token = compactify($conn, base64_decode($_POST['user']));
$token = preg_replace('/[^a-z0-9 ]/i', '', $token);

$res->balance = 0;

$sql = "SELECT
		*, TIMESTAMPDIFF(MINUTE, $col_taskTime, NOW()) AS time_diff
		FROM users WHERE users.token = '{$token}'";
		
$result = mysqli_query($conn, $sql);

if (!mysqli_num_rows($result) == 1) {
	$res->success = false;
	$res->message = "User not found";
	die( json_encode($res) );
}

while($data = mysqli_fetch_assoc($result)) {
    $user_data = $data;
}

$res ->balance = $user_data['balance'];

if ( empty($user_data) ) {
	$res->message = 'User not found';
	die(json_encode($res));
}

if ( $user_data["$col_todayTask"] >= $task_limit ) {
	$res->message = 'Daily limit reached';
	die(json_encode($res));
}

if ( $user_data['i_click'] > $invalid_limit ) {
	$res->message = 'Account blocked';
	die(json_encode($res));
}


if ( $task_did == 1){
if($_POST['did'] != $user_data['did']){
    $res->message = "Connect VPN!";
    die(json_encode($res));
}
}

if($_POST['task-app'] != 5){
if ( $user_data['time_diff'] < $task_timer ) {
	$time_left = $task_timer - $data['time_diff'];
	$res->success = false;
	$res->message = "Wait {$time_left} minute";
	die( json_encode($res) );
	$sql = "UPDATE users SET balance = 0 WHERE users.token = '{$token}'";
}
}


$ref = $user_data['ref'];
$number = $user_data['mobile'];

$sql = "UPDATE users SET balance = balance + {$task_reward}, $col_todayTask = $col_todayTask + 1, $col_taskTime = NOW() WHERE users.token = '{$token}';
		UPDATE users SET balance = balance + {$ref_comission} WHERE users.mobile = '{$ref}';
		INSERT INTO history (number, amount, status) VALUES ('$number', '$task_reward', 'Task Added')";

if ( $conn->multi_query($sql) === TRUE ) {
	
	$res->balance = (int) $user_data['balance'] + $task_reward;
		
	$res->success = true;
	$res->message = "{$task_reward} Points added!";
		
	die( json_encode($res) );
	
}  else {
	
	$res->message = 'Database error: ' . mysqli_error($conn);
	
	die( json_encode($res) );
}

$conn->close();

echo json_encode($res);
?>