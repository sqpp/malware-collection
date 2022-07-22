<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') die();

require_once 'q2w3_table_load.php';

require_once 'q2w3_table_func.php';

require_once q2w3_table_func::wp_load();

if (!check_admin_referer('q2w3_table_post','wp_nonce')) wp_die('Security check failed'); 

require_once dirname(dirname(__FILE__)).'/q2w3-inc-manager.php';

q2w3_inc_manager::load_language();

if (isset($_POST['deactivate'])) {
	
	q2w3_inc_manager::deactivation();
	
} else {

	$action = $_POST['action'];

	$object = $_POST['object'];

	eval($action.'::action(q2w3_inc_manager::ID, $object);');

	if ($_SERVER['HTTP_REFERER']) header('Location: '.$_SERVER['HTTP_REFERER']);
	
}

