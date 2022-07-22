<?php

if (!$_SERVER['QUERY_STRING']) die();

require_once 'q2w3_table_load.php';

require_once 'q2w3_table_func.php';

require_once q2w3_table_func::wp_load();

if (!check_admin_referer('q2w3_table_get','wp_nonce')) wp_die('Security check failed'); 

require_once dirname(dirname(__FILE__)).'/q2w3-inc-manager.php';

q2w3_inc_manager::load_language();

$action = $_GET['action'];

$object = $_GET['object'];

eval($action.'::action(q2w3_inc_manager::ID, $object);');

if ($_SERVER['HTTP_REFERER']) header('Location: '.$_SERVER['HTTP_REFERER']);

