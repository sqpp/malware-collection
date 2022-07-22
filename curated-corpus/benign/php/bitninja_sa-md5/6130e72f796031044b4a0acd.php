<?PHP
error_reporting(E_ALL ^ E_NOTICE);
$cutepath = '.';
require_once('./inc/functions.inc.php');
require_once('./data/config.php');
require_once('./skins/'.$config_skin.'.skin.php');

// Check if CuteNews is not installed
$all_users_db = file('./data/users.db.php');
$check_users = $all_users_db;
$check_users[1] = trim($check_users[1]);
$check_users[2] = trim($check_users[2]);
if((!$check_users[2] or $check_users[2] == '') and (!$check_users[1] or $check_users[1] == '')){
	if(!file_exists('./inc/install.mdu')){
		die('<h2>Error!</h2>CuteNews detected that you do not have users in your users.db.php file and wants to run the install module.<br>
		However, the install module (<b>./inc/install.mdu</b>) can not be located, please reupload this file and make sure you set the proper permissions so the installation can continue.');
	}
	msg('info', 'CuteNews Not Installed', 'CuteNews is not properly installed (users missing) <a href="index.php">go to index.php</a>');
}


$register_level = $config_registration_level;

if($action == 'doregister'){
	$utf8_error = false;
	if($config_allow_registration != 'yes'){ msg('error', $say['error'], $say['register_disabled']); }
	if(!$regusername){ msg('error',$say['error'], $say['blank_user']); }
	if(!$regpassword){ msg('error',$say['error'], $say['blank_pw']); }
	if(!$regemail){ msg('error',$say['error'], $say['blank_email']); }

	$regusername = trim(preg_replace(array("'\n'", "'\r'"), '', $regusername));
	$regnickname = utf8_htmlentities(trim(preg_replace(array("'\n'", "'\r'"), '', $regnickname)));
	$regnickname = str_replace('|', '&#124;', $regnickname);
	$regemail = trim(preg_replace(array("'\n'", "'\r'"), '', $regemail));
	$regpassword = trim($regpassword);

	if(!preg_match("/^[\.A-z0-9_-]{1,15}$/i", $regusername)){ msg('error',$say['error'], $say['invalid_name']); }
	if(!preg_match("/^[\.A-z0-9_-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $regemail)){ msg('error',$say['error'], $say['invalid_mail']); }
	if(!preg_match('/^([[:alnum:][:punct:]]){5,50}$/i', $regpassword)){ msg('error',$say['error'], $say['invalid_pass']); }
	if($utf8_error){ msg('error', $say['error'], $say['utf8']); }

	$all_users = file('./data/users.db.php');
	foreach($all_users as $user_line){
		$user_arr = explode('|', $user_line);
		if(strtolower($user_arr[2]) == strtolower($regusername)){
			msg('error', $say['error'], $say['name_taken']);
		}
	}

	$add_time = time() + ($config_date_adjust*60);
	$regpassword = spw_crypt($regpassword);

	$old_users_file = file('./data/users.db.php');
	$new_users_file = fopen('./data/users.db.php', 'a');
	fwrite($new_users_file, "$add_time|$register_level|$regusername|$regpassword|$regnickname|$regemail|0|1||||\n");
	fclose($new_users_file);

	if($config_notify_registration == 'yes' and $config_notify_status == 'active'){
		send_mail($config_notify_email, 'CuteNews - New User Registered', "New user ($regusername) has just registered:<br />Username: $regusername<br />Nickname: $regnickname<br />Email: $regemail<br /> ");
	}

	utf8_hardlog('cnsys', 'register_new['.$regusername.']');
	msg('user', $say['user_added'], str_replace('{x}', '<a href="index.php">here</a>', $say['register_done']));

}
elseif($action == 'lostpass'){
	echoheader('user', $say['lost_title']);

	echo '<form method="post" action="'.$PHP_SELF.'"><table border=0 cellpading=0 cellspacing=0 width="654" height="59">
<td width="18" height="11">
<td width="71" height="11" align="left">'.$say['username'].'

<td width="203" height="11" align="left"><input type=text name=user seize=20 />
<td width="350" height="26" align="left" rowspan="2" valign="middle">
'.$say['lost_info'].'
<tr>
<td valign="top" height="15">
<td height="15" align="left">'.$say['email'].'
<td height="15" align="left"><input type=text name=email size="20">
</tr>
<tr>
<td valign="top">
<td align="left" colspan="3">&nbsp;
</tr>
<tr>
<td valign="top" height="15">
<td height="15" align="left" colspan="3"><input type=submit value="'.$say['submit_lost'].'">
</tr>
<tr>
<td width="18" height="27"><input type=hidden name=action value=validate>
<input type=hidden name=mod value=lostpass>
<td width="632" height="27" colspan="3">
</tr></table></form>';

	echofooter();
}
elseif($action == 'validate'){
	if(!isset($user) or !$user or $user == '' or !isset($email) or !$email or $email == ''){ msg('error', $say['error'], $say['all_fields']); }

	$found = FALSE;
	$all_users = file('./data/users.db.php');
	foreach($all_users as $user_line){
		$user_arr = explode('|', $user_line);
		if(strtolower($user_arr[2]) == strtolower($user) and strtolower($user_arr[5]) == strtolower($email)){
			$sstring = md5($user_arr[0].$user_arr[3].$user_arr[9]); $found = TRUE; break;
		}
	}
	if(!$found){ utf8_hardlog('cnsys', 'lostpw_fail['.$user.']['.$email.']'); msg('error', $say['error'], $say['unmatch']); }
	else{
		$confirm_url = $config_http_script_dir.'/register.php?a=dsp&s='.$sstring;
		$message = "Hi,\n Someone requested your password to be changed, if this is the desired action and you want to change your password please follow this link: $confirm_url .";

		mail($email, "Confirmation ( New Password for CuteNews )", $message,
		"From: no-reply@$SERVER_NAME\r\n"
		."X-Mailer: PHP/" . phpversion()) or die("Error: Cannot send mail");

		utf8_hardlog('cnsys', 'lostpw_sent['.$user.']['.$email.']');
		msg('info', $say['conf_sent'], $say['conf_info']);
	}

//Do send password
}
elseif($a == 'dsp'){

	if(trim($s) == '' or !$s){ msg('error', $say['error'], $say['all_fields']); }
	$found = FALSE;
	$all_users = file('./data/users.db.php');
	foreach($all_users as $user_line){
		$user_arr = explode('|', $user_line);
		if($s == md5($user_arr[0].$user_arr[3].$user_arr[9])){ $found = TRUE; break;}
	}
	if(!$found){ msg('error', $say['error'], $say['invalid_str']); }
	else{
		$salt = 'abcdefghjkmnpqrstuvwxyz0123456789_-ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		srand((double)microtime()*1000000);
		for($i = 0; $i < 9; $i++){
			$new_pass .= $salt{rand(0,60)};
		}
		$md5_pass = spw_crypt($new_pass);

		$old_db = file('./data/users.db.php');
		$new_db = fopen('./data/users.db.php', 'w');
		foreach($old_db as $old_db_line){
			$old_db_arr = explode('|', $old_db_line);
			if($s != md5($old_db_arr[0].$old_db_arr[3].$old_db_arr[9])){
				fwrite($new_db,"$old_db_line");
			}
			else{
				fwrite($new_db,"$old_db_arr[0]|$old_db_arr[1]|$old_db_arr[2]|$md5_pass|$old_db_arr[4]|$old_db_arr[5]|$old_db_arr[6]|$old_db_arr[7]||$old_db_arr[9]|\n");
			}
		}
		fclose($new_db);

		$message = "Hi $user_arr[2],\n Your new password for CuteNews is $new_pass, please change this password after you login.";
		mail("$user_arr[5]", "Your New Password for CuteNews", $message,
	             "From: no-reply@$SERVER_NAME\r\n"
		."X-Mailer: PHP/" . phpversion()) or die("cannot send mail");

		utf8_hardlog('cnsys', 'lostpw_change['.$user_arr[2].']');
		msg("info", $say['pass_sent_title'], str_replace('{x}', $user_arr[2], $say['pass_sent']));
	}
}
else{
	if($config_allow_registration != 'yes'){ msg('error', $say['error'], $say['register_disabled']); }
	$jquery = 1;
	echoheader('user', $say['register_title']);

$result = htmlentities($result);
echo<<<HTML
    <table leftmargin=0 marginheight=0 marginwidth=0 topmargin=0 border=0 height=100% cellspacing=0>
     <form  name=login action="$PHP_SELF" method="post" accept-charset="utf-8">
     <tr>
	<td width=80>{$say['username']}: </td>
	<td><input tabindex=1 type=text name=regusername style="width:134" size="20"></td>
     </tr>
     <tr>
	<td width=80>{$say['nickname']}: </td>
	<td><input tabindex=1 type=text name=regnickname style="width:134" size="20"></td>
     </tr>
     <tr>
	<td width=80>{$say['password']}: </td>
	<td><input tabindex=1 type="password" id="password" name=regpassword style="width:134" size="20"></td>
	<td> &nbsp; <span id="result"> </span></td>
       
     </tr>
     <tr>
	<td width=80>{$say['email']}: </td>
	<td><input tabindex=1 type=text name=regemail style="width:134" size="20"></td>
     </tr>
      <tr>
	<td></td>
	<td><input accesskey="s" type=submit style="background-color: #F3F3F3;" value='{$say['register_submit']}'></td>
      </tr>
      <tr>
	<td align=center colspan=2>$result</td>
      </tr>
	<input type=hidden name=action value=doregister>
 </form>
 </table>
HTML;
        echofooter();
}
?>