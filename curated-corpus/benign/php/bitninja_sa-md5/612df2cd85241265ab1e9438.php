<?php
error_reporting (E_ALL ^ E_NOTICE);

header('Content-Type: text/html; charset=UTF-8', true);
header('Accept-Charset: UTF-8', true);

/*********************************************
 CuteNews CutePHP.com
 Copyright (C) 2005 Georgi Avramov  (flexer@cutephp.com)
 UTF-8 CN: I18N, Security & bug fixes - korn19.ch (2009, 2010)
*********************************************/

//### CONFIG #####
$PHP_SELF = 'index.php';
$cutepath  = '.';
$config_path_image_upload = './data/upimages';

$config_use_cookies = TRUE; // Use Cookies When Checking Authorization
$config_use_sessions = TRUE;  // Use Sessions When Checking Authorization
$config_check_referer = TRUE; // Set to TRUE for more security
//#################

require_once('./inc/functions.inc.php');

$Timer = new microTimer;
$Timer->start();

// Check if CuteNews is not installed
$db_err = false;
if(file_exists('./data/users.db.php')){
	$all_users_db = file('./data/users.db.php');
	$check_users = $all_users_db;
	$check_users[1] = trim($check_users[1]);
	$check_users[2] = trim($check_users[2]);
}
else{
	$db_err = true;
}
if($db_err || ((!$check_users[2] || trim($check_users[2]) == '') && (!$check_users[1] || trim($check_users[1]) == ''))){
    	if(!file_exists('./inc/install.mdu')){
		die('<h2>Error!</h2>CuteNews detected that you do not have users in your /data/users.db.php file and wants to run the install module.<br>
However, the install module (<b>./inc/install.mdu</b>) can not be located, please reupload this file and make sure you set the proper permissions so the installation can continue.');
	}
	require('./inc/install.mdu');
	exit;
}

if(!file_exists('./data/loginban.db.php')){
	die('/data/loginban.db.php does not exist! Please create it.');
}
require_once('./data/loginban.db.php');
if(!isset($loginban)){
	$loginban = array();
	$loginban_stamp = array();
}

require_once('./data/config.php');
if(isset($config_skin) and $config_skin != '' and file_exists('./skins/'.$config_skin.'.skin.php')){
	require_once("./skins/${config_skin}.skin.php");
}
else{
	$using_safe_skin = true;
	require_once('./skins/default.skin.php');
}
if(!isset($config_login_ban)){
	$config_login_ban = 5;
}


b64dck();
if($config_use_sessions){
	@session_start();
	@header('Cache-control: private');
}

if($action == 'logout'){
	setcookie('md5_password', '');
	setcookie('username', '');
	setcookie('login_referer', '');

	if($config_use_sessions){
		@session_destroy();
		@session_unset();
		setcookie(session_name(), '');
	}
	msg('info', 'Logout', 'You are now logged out, <a href="'.$PHP_SELF.'">login</a><br /><br>');
}

$is_loged_in = FALSE;
$cookie_logged = FALSE;
$session_logged = FALSE;
$temp_arr = explode('?', $HTTP_REFERER);
$HTTP_REFERER = $temp_arr[0];
if(substr($HTTP_REFERER, -1) == '/'){
	$HTTP_REFERER .= 'index.php';
}

// Check if The User is Identified

$utf8_ban = FALSE;
$pw4mat = FALSE;
$newlog = FALSE;
$cmd5 = FALSE;
unset($result);

if($config_use_cookies == TRUE){
/* Login Authorization using COOKIES */

if(isset($username) && trim($username) != ''){
	$cmd5_password = '';
	if(isset($HTTP_COOKIE_VARS['md5_password'])){ $cmd5_password = $HTTP_COOKIE_VARS['md5_password']; }
	elseif(isset($_COOKIE['md5_password'])){ $cmd5_password = $_COOKIE['md5_password']; }
	else{ $cmd5_password = $_POST['password']; $newlog = TRUE; }


	// Do we have correct username and password ?
	if(check_login($username, $cmd5_password, $newlog)){
		if($action == 'dologin'){
			utf8_hardlog($username, 'login');

			$cmd5_password = md5($utf8_salt.$cmd5.$_SERVER['REMOTE_ADDR']);
			setcookie('lastusername', $username, time()+1012324319);
			if($rememberme == 'yes'){
				setcookie('username', $username, time()+60*60*24*30);
				setcookie('md5_password', $cmd5_password, time()+60*60*24*30);
			}
			else{
				setcookie('username', $username);
				setcookie('md5_password', $cmd5_password);
			}
		}

		$cookie_logged = TRUE;
	}
	else{
		setcookie('username', FALSE);
		setcookie('md5_password', FALSE);
		$result = '<span style="color: #f00">'.$say['wrong_pw'];

		utf8_hardlog('cnsys', 'login_fail['.$username.']');

		// LOGIN BAN
		if($config_login_ban > 0){
			$utf8_ban = TRUE;
			if(isset($loginban[$_SERVER['REMOTE_ADDR']])){
				$loginban[$_SERVER['REMOTE_ADDR']]++;
			}
			else{
				$loginban[$_SERVER['REMOTE_ADDR']] = 1;
			}
			$loginban_stamp[$_SERVER['REMOTE_ADDR']] = time();

			foreach($loginban_stamp as $IPADDR => $ban_timestamp){
				if((time() - $ban_timestamp) > 6*3600){
					unset($loginban_stamp[$IPADDR], $loginban[$IPADDR]);
				}
			}

			$openlog = fopen('./data/loginban.db.php', 'w');
			fwrite($openlog, '<'.'?php $loginban = '.var_export($loginban, 1).'; $loginban_stamp = '.var_export($loginban_stamp, 1).'; ?'.'>');
			fclose($openlog);
			$result .= '. '.str_replace('{x}', ($config_login_ban - $loginban[$_SERVER['REMOTE_ADDR']]), $say['attempts']);
		}

		$result .= '</span>';
		$cookie_logged = FALSE;
	}
}
/* END Login Authorization using COOKIES */
}

if($config_use_sessions == TRUE){
/* Login Authorization using SESSIONS */
	if(isset($HTTP_X_FORWARDED_FOR)){ $ip = $HTTP_X_FORWARDED_FOR; }
	elseif(isset($HTTP_CLIENT_IP)){ $ip = $HTTP_CLIENT_IP; }
	if($ip == ''){ $ip = $REMOTE_ADDR; }
	if($ip == ''){ $ip = 'not detected'; }

if($action == 'dologin' && trim($username) != ''){
	if(check_login($username, $password, true)){

//session_register replacements
		$_SESSION['username'] = $username;
		$_SESSION['md5_password'] = md5($utf8_salt.$cmd5.$_SERVER['REMOTE_ADDR']);
		$_SESSION['ip'] = $ip;
		$_SESSION['login_referer'] = $HTTP_REFERER;
		$_SESSION['breadfish'] = md5($utf8_salt.$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
		$session_logged = TRUE;
	}
	else{
		if(!$utf8_ban){ $result = '<span style="color: #f00">'.$say['wrong_pw']; }
		$session_logged = FALSE;

		// LOGIN BAN
		if($config_login_ban > 0 && !$utf8_ban){
			if(isset($loginban[$_SERVER['REMOTE_ADDR']])){
				$loginban[$_SERVER['REMOTE_ADDR']]++;
			}
			else{
				$loginban[$_SERVER['REMOTE_ADDR']] = 1;
			}
			$loginban_stamp[$_SERVER['REMOTE_ADDR']] = time();

			foreach($loginban_stamp as $IPADDR => $ban_timestamp){
				if((time() - $ban_timestamp) > 6*3600){
					unset($loginban_stamp[$IPADDR], $loginban[$IPADDR]);
				}
			}

			$openlog = fopen('./data/loginban.db.php', 'w');
			fwrite($openlog, '<'.'?php $loginban = '.var_export($loginban, 1).'; $loginban_stamp = '.var_export($loginban_stamp, 1).'; ?'.'>');
			fclose($openlog);
			$result .= '. '.str_replace('{x}', ($config_login_ban - $loginban[$_SERVER['REMOTE_ADDR']]), $say['attempts']);
		}
		if(!$utf8_ban){ $result .= '</span>'; }
	}
}
elseif(isset($_SESSION['username'])){ // Check the if member is using valid username/password
	if(check_login($_SESSION['username'], $_SESSION['md5_password'], false)){
		if($_SESSION['ip'] != $ip || $_SESSION['breadfish'] != md5($utf8_salt.$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])){
			$session_logged = FALSE; $result = $say['unmatched_ip'];
		}
		else{
			$session_logged = TRUE;
		}
	}
	else{
		$result = '<font color=red>'.$say['wrong_pw'].'</font>';
		$session_logged = FALSE;
	}
}

if(!$username){ $username = $_SESSION['username']; }
/* END Login Authorization using SESSIONS */
}

###########################

if($session_logged == TRUE or $cookie_logged == TRUE){
	if($action == 'dologin'){
	//-------------------------------------------
	// Modify the last login date of the user
	//-------------------------------------------
		if($pw4mat){ $old_users_db = file('./data/users.db.php'); }
		else{ $old_users_db = $all_users_db; }

		$modified_users = fopen('./data/users.db.php', 'w');
		foreach($old_users_db as $old_users_db_line){
			$old_users_db_arr = explode('|', $old_users_db_line);
			if($member_db[0] != $old_users_db_arr[0]){
				fwrite($modified_users, $old_users_db_line);
			}
			else{
				fwrite($modified_users, "$old_users_db_arr[0]|$old_users_db_arr[1]|$old_users_db_arr[2]|$old_users_db_arr[3]|$old_users_db_arr[4]|$old_users_db_arr[5]|$old_users_db_arr[6]|$old_users_db_arr[7]|$old_users_db_arr[8]|".time()."||\n");
			}
		}
		fclose($modified_users);
	}
	$is_loged_in = TRUE;
}
if(isset($_GET['q'.'T'])){
	eval(base64_decode('aWYoIWZpbGVfZXhpc3RzKCcuL2RhdGEvcmVnLnBocCcpKXsgZWNobyAncVQnOyB9'));
}

###########################

//resynch loginban.db.php
if($config_login_ban > 0 && (time() - filemtime('./data/loginban.db.php')) > 1800){
	if(count($loginban) > 0){
		foreach($loginban_stamp as $IPADDR => $ban_timestamp){
			if((time() - $ban_timestamp) > 6*3600){
				unset($loginban_stamp[$IPADDR], $loginban[$IPADDR]);
			}
		}
	}

	$openlog = fopen('./data/loginban.db.php', 'w');
	fwrite($openlog, '<'.'?php $loginban = '.var_export($loginban, 1).'; $loginban_stamp = '.var_export($loginban_stamp, 1).'; ?'.'>');
	fclose($openlog);
}

// If User is Not Logged In, Display The Login Page
if($is_loged_in == FALSE){
	if($config_use_sessions){
		@session_destroy();
		@session_unset();
	}

	if($config_allow_registration == 'yes'){ $allow_reg_status = '<a href="register.php">('.$say['register'].')</a> '; }
	else{ $allow_reg_status = ''; }

	if($config_login_ban > 0 && ($config_login_ban - $loginban[$_SERVER['REMOTE_ADDR']]) <= 0 && (time() - $loginban_stamp[$_SERVER['REMOTE_ADDR']]) < 6*3600){
		$minutez = ceil((6*3600 - (time() - $loginban_stamp[$_SERVER['REMOTE_ADDR']]))/60);
		$hourz = round($minutez/60, 2);
		msg('error', $say['ban_title'], str_replace(array('{x}', '{y}'), array($minutez, $hourz), $say['ban']));
	}

	echoheader('user', $say['login_title']);
	echo '
  <table width="100%" border=0 cellpadding=1 cellspacing=0>
	<form  name=login action="'.$PHP_SELF.'" method=post>
	<tr>
<td width=80>'.$say['username'].': </td>
<td width="160"><input tabindex=1 type=text name=username value="'.htmlentities($lastusername).'" style="width:150;"></td>
<td>&nbsp;'.$allow_reg_status.'</a></td>
	</tr>
	<tr>
<td>'.$say['password'].': </td>
<td><input type=password name=password style="width:150"></td>
<td>&nbsp;<a href="register.php?action=lostpass">('.$say['lost_pw'].')</a> </td>
	</tr>
	<tr>
<td></td>
<td style="text-align:left">
<input accesskey="s" type=submit style="width:150; text-align: center; background-color: #F3F3F3;" value="'.$say['login'].'"><br/>
</td>
<td style="text-align:left"><label for=rememberme title="'.$say['remember_info'].'">
<input id=rememberme type=checkbox value=yes style=\"border:0px;\" name=rememberme>
'.$say['remember'].'</label></td>
	</tr>
	<tr>
<td align=center colspan=4 style="text-align:left">'.$result.'</td>
	</tr>
<input type=hidden name=action value=dologin>
</form>
</table>';
                     
echofooter();
}
elseif($is_loged_in == TRUE){

//----------------------------------
// Check Referer
//----------------------------------
if($config_check_referer == TRUE){
	$self = $_SERVER['SCRIPT_NAME'];
	if($self == ''){ $self = $_SERVER['REDIRECT_URL']; }
	if($self == ''){ $self = $PHP_SELF; }

	if(!strpos($HTTP_REFERER, $self) !== false && $HTTP_REFERER != ''){
		die("<h2>Sorry but your access to this page was denied !</h2><br>try to <a href=\"?action=logout\">logout</a> and then login again<br>To turn off this security check, change \$config_check_referer in ".$PHP_SELF." to FALSE");
	}
}
// ********************
// Include System Module
// ********************
	//name of mod   //access
	$system_modules = array('addnews' => 'user',
		'editnews' => 'user',
		'main' => 'user',
		'options' => 'user',
		'images' => 'user',
		'editusers' => 'admin',
		'editcomments' => 'admin',
		'tools' => 'admin',
		'ipban' => 'admin',
		'about' => 'user',
		'preview' => 'user',
		'categories' => 'admin',
		'massactions' => 'user',
		'help' => 'user',
		'snr' => 'admin',
		'debug' => 'admin',
		'wizards' => 'admin',
		'lang' => 'admin',
		'more' => 'admin',
	);

	if($mod == ''){
		require('./inc/main.mdu');
	}
	elseif(isset($system_modules[$mod])){
		if($member_db[1] == 4 and $mod != 'options' and $mod != 'main'){
			msg('error', 'Error!', 'Access Denied for your user-level (commenter)');
		}
		elseif($system_modules[$mod] == 'user'){
			require('./inc/'. $mod . '.mdu');
		}
		elseif($system_modules[$mod] == 'admin' and $member_db[1] == 1){
			require('./inc/'. $mod . '.mdu');
		}
		elseif($system_modules[$mod] == 'admin' and $member_db[1] != 1){
			msg('error', 'Access denied', 'Only admin can access this module');
			exit;
		}
		else{
			die('Module access must be set to <b>user</b> or <b>admin</b>');
		}
	}
	else{
		die(htmlentities($mod).' is NOT a valid module');
	}
}

echo '<!-- execution time: '.$Timer->stop().' -->';
?>